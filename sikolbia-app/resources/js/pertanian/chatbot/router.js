// Chatbot message routing and guided step re-prompting
// Keeps legacy public API stable by operating on the provided ctx (Alpine component instance)

import chatbotApi from '../api/chatbotApi.js';
import { injectCompareChips } from './compare.js';

export async function rePromptCurrentStep(ctx) {
  try {
    const step = ctx.wizard.step;
    if (step === 'module') {
      ctx.conversation.push({ sender:'bot', type:'options', title:'Pilih Modul', options:[
        { value:'benih-pupuk', label:'Benih & Pupuk' },
        { value:'lahan', label:'Lahan' },
        { value:'iklim-opt-dpi', label:'Iklim & OPT DPI' },
      ]});
    } else if (step === 'topik') {
      await ctx.ensureModuleData(ctx.wizard.moduleType || ctx.moduleType);
      ctx.loadWizardTopiks();
    } else if (step === 'variabel') {
      await ctx.ensureVariabels(ctx.wizard.moduleType || ctx.moduleType, ctx.wizard.topikId);
      ctx.loadWizardVariabels();
    } else if (step === 'klasifikasi') {
      await ctx.ensureKlasifikasis(ctx.wizard.moduleType || ctx.moduleType, ctx.wizard.variabelId);
      ctx.loadWizardKlasifikasis();
    } else if (step === 'waktu_tahun') {
      ctx.askYears();
    } else if (step === 'waktu_bulan_choice') {
      ctx.loadWizardBulans();
    } else if (step === 'waktu_bulan') {
      ctx.renderBulanChecklist();
    } else if (step === 'wilayah_level') {
      ctx.askWilayah();
    } else if (step === 'wilayah_provinsi') {
      await ctx.ensureWilayahs();
      ctx.askProvinces();
    } else if (step === 'wilayah_pilih_provinsi') {
      await ctx.ensureWilayahs();
      ctx.askProvinces(true);
    } else if (step === 'wilayah_kabupaten') {
      await ctx.ensureWilayahs();
      const provId = (ctx.wizard.provinsiIds && ctx.wizard.provinsiIds[0]) || ctx.selectedProvinsiId;
      if (provId) ctx.askKabupaten(provId);
    } else if (step === 'choose_preview_style') {
      // Always show summary
      ctx.presentPreview('summary');
    } else if (step === 'preview' || step === 'post_preview_help') {
      // Nothing to re-prompt
    } else {
      ctx.resetGuidedChat();
    }
  } finally {
    try { ctx.$nextTick(() => ctx.scrollChatToBottom()); } catch {}
  }
}

export async function handleNaturalMessage(ctx, messageToSend) {
  // Intercept pending typed clarifications first
  if (ctx.pendingPrompt && ctx.pendingPrompt.type === 'klasifikasi') {
    const raw = String(messageToSend||'');
    const lower = raw.toLowerCase();
    const cand = Array.isArray(ctx.pendingPrompt.candidates)?ctx.pendingPrompt.candidates:[];
    const matched = cand.filter(k => lower.includes(String(k.nama||'').toLowerCase()));
    if (!matched.length) {
      ctx.renderBotText('Saya belum mengenali klasifikasi dari teks tersebut. Ketik salah satu nama klasifikasi yang ada.');
      return;
    }
    ctx.wizard.klasifikasiIds = matched.map(k=>k.id);
    ctx.pendingPrompt = null;
    await ctx.finishPreview();
    return;
  }

  // Intercept tutorial confirmation
  if (ctx.pendingPrompt && ctx.pendingPrompt.type === 'tutorial') {
    const raw = String(messageToSend||'');
    const l = raw.trim().toLowerCase();
    const yes = /^(ya|iya|ok|oke|baik|lanjut|mau|boleh|yes|y)$/i.test(l);
    const no  = /^(tidak|ga|gak|nggak|enggak|no|n|skip|nanti)$/i.test(l);
    const pend = ctx.pendingPrompt.preview;
    const mod = ctx.pendingPrompt.module;
    ctx.pendingPrompt = null;
    if (yes) { ctx.renderBotText(ctx.buildTutorialText(mod, pend)); return; }
    if (no) { ctx.renderBotText('Baik, lanjutkan chat bebas.'); return; }
  }

  const raw = String(messageToSend || '');
  const txtLower = raw.trim().toLowerCase();
  // If compare flow is available, intercept "ya tampilkan" to run comparison by default
  if (ctx._compareAvailable && /\b(tampilkan|ya tampilkan|ok tampilkan|silakan tampilkan|tolong tampilkan)\b/i.test(txtLower)) {
    const cm = ctx.compareMeta || {};
    const kind = (Array.isArray(cm.years) && cm.years.length >= 2) ? 'cmp_years' : ((Array.isArray(cm.wilayahs) && cm.wilayahs.length >= 2) ? 'cmp_wilayahs' : null);
    if (kind) {
      ctx.renderBotText('Baik, menyiapkan perbandingan...');
      try { const mod = await import('./compare.js'); await mod.executeComparison(ctx, kind, ctx.structuredSuggestion); } catch(_) {}
      try { ctx.$nextTick(() => ctx.scrollChatToBottom()); } catch {}
      return;
    }
  }

  ctx.isLoading = true;
  try {
    const data = await chatbotApi.sendMessage(messageToSend, 'natural');
  const baseReply = data.reply || data.error || 'Maaf, terjadi kesalahan.';
    let reply = baseReply;
    const intent = data && typeof data === 'object' ? (data.intent || null) : null;
    let hasSignals = false;

    if (data && data.structured && typeof data.structured === 'object') {
      ctx.structuredSuggestion = data.structured;
      const s = ctx.structuredSuggestion || {};
      hasSignals = (Array.isArray(s.modules) && s.modules.length) || (Array.isArray(s.wilayah_hits) && s.wilayah_hits.length) || (Array.isArray(s.years) && s.years.length);
      if (hasSignals) {
        ctx.pendingStructured = true;
      }
    }

    // Compare chips injection and message tweak
    if (intent === 'compare' && data.compare) {
      ctx._compareAvailable = true;
      ctx.compareMeta = data.compare;
      try { injectCompareChips(ctx, { years: data.compare.years, wilayahs: data.compare.wilayahs }); } catch {}
      ctx.pendingStructured = false; // prefer compare chips over plain show
      reply = `${baseReply} — Pilih jenis perbandingan di bawah.`;
    } else if (hasSignals) {
      // Only suggest "ya tampilkan" when not in compare intent
      if (ctx.compactChat) {
        reply = `${baseReply} — Ketik "ya tampilkan" untuk menampilkan, atau lanjutkan chat bebas.`;
      } else {
        ctx.renderBotText(baseReply);
        ctx.renderBotText('Ketik "ya tampilkan" bila ingin saya ambilkan hasilnya sekarang, atau lanjutkan chat bebas.');
        try { ctx.$nextTick(() => ctx.scrollChatToBottom()); } catch {}
        return;
      }
    }

    if (intent === 'unknown' && !hasSignals && !ctx.guidedFallbackShown) {
      ctx.guidedFallbackShown = true;
      ctx.switchChatMode('guided', { silent: true });
      ctx.startGuidedInline();
      try { ctx.$nextTick(() => ctx.scrollChatToBottom()); } catch {}
      return;
    }

    ctx.renderBotText(reply, { typewriter: true });
  } catch (error) {
    const msg = (error && error.message) ? String(error.message) : 'Maaf, terjadi kesalahan.';
    ctx.renderBotText(msg, { typewriter: true });
  } finally {
    ctx.isLoading = false;
    try { ctx.$nextTick(() => ctx.scrollChatToBottom()); } catch {}
  }
}

export async function handleStructuredMessage(ctx, messageToSend) {
  // Intercept default show when compare is available
  const raw0 = String(messageToSend || '');
  const l0 = raw0.trim().toLowerCase();
  if (ctx._compareAvailable && /\b(tampilkan|ya tampilkan|ok tampilkan|silakan tampilkan|tolong tampilkan)\b/i.test(l0)) {
    const cm = ctx.compareMeta || {};
    const kind = (Array.isArray(cm.years) && cm.years.length >= 2) ? 'cmp_years' : ((Array.isArray(cm.wilayahs) && cm.wilayahs.length >= 2) ? 'cmp_wilayahs' : null);
    if (kind) {
      ctx.renderBotText('Baik, menyiapkan perbandingan...');
      try { const mod = await import('./compare.js'); await mod.executeComparison(ctx, kind, ctx.structuredSuggestion); } catch(_) {}
      try { ctx.$nextTick(() => ctx.scrollChatToBottom()); } catch {}
      return;
    }
  }
  if (ctx.pendingPrompt && ctx.pendingPrompt.type === 'klasifikasi') {
    const raw = String(messageToSend||'');
    const lower = raw.toLowerCase();
    const cand = Array.isArray(ctx.pendingPrompt.candidates)?ctx.pendingPrompt.candidates:[];
    const matched = cand.filter(k => lower.includes(String(k.nama||'').toLowerCase()));
    if (!matched.length) {
      ctx.renderBotText('Saya belum mengenali klasifikasi dari teks tersebut. Ketik salah satu nama klasifikasi yang ada.');
      return;
    }
    ctx.wizard.klasifikasiIds = matched.map(k=>k.id);
    ctx.pendingPrompt = null;
    await ctx.finishPreview();
    return;
  }

  if (ctx.pendingPrompt && ctx.pendingPrompt.type === 'tutorial') {
    const raw = String(messageToSend||'');
    const l = raw.trim().toLowerCase();
    const yes = /^(ya|iya|ok|oke|baik|lanjut|mau|boleh|yes|y)$/i.test(l);
    const no  = /^(tidak|ga|gak|nggak|enggak|no|n|skip|nanti)$/i.test(l);
    const pend = ctx.pendingPrompt.preview;
    const mod = ctx.pendingPrompt.module;
    ctx.pendingPrompt = null;
    if (yes) { ctx.renderBotText(ctx.buildTutorialText(mod, pend)); return; }
    if (no) { ctx.renderBotText('Baik, lanjutkan chat bebas.'); return; }
  }

  const raw = String(messageToSend || '');
  const txtLower = raw.trim().toLowerCase();
  if (ctx.structuredSuggestion && /\b(tampilkan|ya tampilkan|ok tampilkan|silakan tampilkan|tolong tampilkan)\b/i.test(txtLower)) {
    ctx.pendingStructured = false;
    ctx.renderBotText('Baik, sedang saya ambilkan datanya...');
    try { await ctx.applyStructuredSuggestion(null, { forceCompact: true }); } catch(_) {}
    try { ctx.$nextTick(() => ctx.scrollChatToBottom()); } catch {}
    return;
  }

  ctx.isLoading = true;
  try {
    const data = await chatbotApi.sendMessage(messageToSend, 'structured');
    let reply = data.reply || data.error || 'Maaf, terjadi kesalahan.';

    if (data && data.structured && typeof data.structured === 'object') {
      ctx.structuredSuggestion = data.structured;
      const s = ctx.structuredSuggestion || {};
      const modules = Array.isArray(s.modules) ? s.modules : [];
      const wilayahs = Array.isArray(s.wilayah_hits) ? s.wilayah_hits : [];

      if (ctx.compactChat && (modules.length || wilayahs.length)) {
        ctx.pendingStructured = true;
        reply = `${reply} — Ketik "ya tampilkan" untuk menampilkan.`;
      } else if (modules.length || wilayahs.length) {
        ctx.pendingStructured = true;
        reply = `${reply} — Ketik "" untuk menampilkan.`;
      }
    }
      // Compare chips injection when intent is compare
      if (data.intent === 'compare' && data.compare) {
        injectCompareChips(ctx, { years: data.compare.years, wilayahs: data.compare.wilayahs });
      }
    ctx.renderBotText(reply, { typewriter: true });
  } catch (error) {
    const msg = (error && error.message) ? String(error.message) : 'Saya belum dapat semua detail. Mau mulai dari modulnya?';
    ctx.renderBotText(msg, { typewriter: true });
  } finally {
    ctx.isLoading = false;
    try { ctx.$nextTick(() => ctx.scrollChatToBottom()); } catch {}
  }
}

export async function handleGuidedFlow(ctx, messageToSend) {
  const raw = String(messageToSend || '');
  const txt = raw.trim().toLowerCase();
  if (txt === '') {
    await rePromptCurrentStep(ctx);
    return;
  }
  if (txt === '/bebas' || txt === '/natural') {
    ctx.switchChatMode('natural');
    return;
  }
  if (txt === '/terstruktur' || txt === '/structured') {
    ctx.switchChatMode('structured');
    return;
  }
  const wantsShow = /\b(tampilkan|show|ya tampilkan|ok tampilkan|silakan tampilkan)\b/i.test(txt);
  if (wantsShow && ctx.structuredSuggestion) {
    ctx.switchChatMode('structured', { silent: true });
    await handleStructuredMessage(ctx, raw);
  } else {
    ctx.switchChatMode('natural', { silent: true });
    await handleNaturalMessage(ctx, raw);
  }
}

export async function sendMessage(ctx) {
  if (!ctx.userMessage.trim()) return;
  ctx.conversation.push({ sender: 'user', text: ctx.sanitizeHtml(ctx.userMessage) });
  ctx.$nextTick(() => { try { ctx.scrollChatToBottom(); } catch {} });
  const messageToSend = ctx.userMessage;
  ctx.userMessage = '';
  if (ctx.chatMode === 'natural') {
    await handleNaturalMessage(ctx, messageToSend);
  } else if (ctx.chatMode === 'structured') {
    await handleStructuredMessage(ctx, messageToSend);
  } else {
    await handleGuidedFlow(ctx, messageToSend);
  }
}

export default {
  rePromptCurrentStep,
  handleNaturalMessage,
  handleStructuredMessage,
  handleGuidedFlow,
  sendMessage,
};
