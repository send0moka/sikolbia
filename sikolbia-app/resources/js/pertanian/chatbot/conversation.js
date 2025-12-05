// Conversation helpers for chatbot: rendering bubbles, switching modes, and reset flow
import api from '../api/chatbotApi.js';
import { sanitizeHtml } from '../utils/dom.js';

export function renderBotText(ctx, text, opts = {}) {
  const effect = opts.typewriter ? 'typewriter' : null;
  ctx.conversation.push({ sender: 'bot', type: 'text', effect, text: sanitizeHtml(String(text || '')) });
}

export function switchChatMode(ctx, mode, { silent = false } = {}) {
  const m = String(mode || '').toLowerCase();
  if (!['natural', 'structured', 'guided'].includes(m)) return;
  ctx.chatMode = m;
  ctx.guidedLock = (m === 'guided');
  if (!silent) {
    const label = m === 'natural' ? 'chat bebas' : (m === 'structured' ? 'pencarian terstruktur' : 'pandu');
    renderBotText(ctx, `Mode diubah ke ${label}.`);
  }
  // If switching to guided, ensure the wizard has a prompt on screen
  if (m === 'guided' && (!ctx.conversation || ctx.conversation.length === 0)) {
    resetGuidedChat(ctx);
  }
  ctx.$nextTick(() => { try { ctx.scrollChatToBottom(); } catch {} });
}

export function resetGuidedChat(ctx) {
  ctx.wizard = { step: 'module', moduleType: null, topikId: null, variabelId: null, klasifikasiIds: [], tahunIds: [], bulanIds: [], provinsiIds: [], kabupatenIds: [] };
  ctx.guidedLock = true;
  ctx.conversation = [
    { sender: 'bot', type: 'text', text: sanitizeHtml('Saya bisa bantu mencari data <strong>Lahan</strong>, <strong>Benih & Pupuk</strong>, atau <strong>Iklim & OPT DPI</strong>. Mau mulai dari modulnya?') },
    { sender: 'bot', type: 'options', title: 'Pilih Modul', options: [
      { value: 'benih-pupuk', label: 'Benih & Pupuk' },
      { value: 'lahan', label: 'Lahan' },
      { value: 'iklim-opt-dpi', label: 'Iklim & OPT DPI' },
    ]},
  ];
  ctx.$nextTick(() => { try { ctx.scrollChatToBottom(); } catch {} });
}

export function startGuidedInline(ctx) {
  ctx.wizard = { step: 'module', moduleType: null, topikId: null, variabelId: null, klasifikasiIds: [], tahunIds: [], bulanIds: [], provinsiIds: [], kabupatenIds: [] };
  ctx.guidedLock = true;
  ctx.conversation.push({ sender: 'bot', type: 'text', text: 'Silahkan Pilih Modul lagi untuk melanjutkan' });
  ctx.conversation.push({ sender: 'bot', type: 'options', title: 'Pilih Modul', options: [
    { value: 'benih-pupuk', label: 'Benih & Pupuk' },
    { value: 'lahan', label: 'Lahan' },
    { value: 'iklim-opt-dpi', label: 'Iklim & OPT DPI' },
  ] });
  ctx.$nextTick(() => { try { ctx.scrollChatToBottom(); } catch {} });
}

export function openResetConfirm(ctx) { ctx.showChatResetConfirm = true; }
export function cancelResetConfirm(ctx) { ctx.showChatResetConfirm = false; }

export async function confirmReset(ctx) {
  try { await api.resetConversation(); } catch {}
  resetGuidedChat(ctx);
  ctx.userMessage = '';
  ctx.showChatResetConfirm = false;
}

export function onChatOpen(ctx) {
  // Minimal onboarding: introduce and suggest guidance/examples once
  if (!ctx.didOnboardingGuided) {
    ctx.chatMode = 'natural';
    const intro = 'Halo!, Saya adalah asisten virtual untuk data Non-Komoditas Pertanian. Apa yang bisa saya bantu hari ini, atau data apa yang perlu anda cari?';
    renderBotText(ctx, intro, { typewriter: true });
    // Tampilkan chips kecil setelah efek typing selesai
    const delayMs = Math.min(2500, Math.max(600, intro.length * 12 + 150));
    setTimeout(() => {
      try {
        ctx.conversation.push({
          sender: 'bot',
          type: 'options',
          variant: 'onboarding',
          title: '',
          options: [
            { value: 'start_guided', label: 'Perlu bantuan?' },
            { value: 'show_examples', label: 'Lihat contoh' },
          ],
        });
        ctx.$nextTick(() => { try { ctx.scrollChatToBottom(); } catch {} });
      } catch {}
    }, delayMs);
    ctx.didOnboardingGuided = true;
  }
  ctx.$nextTick(() => { try { ctx.scrollChatToBottom(); } catch {} });
}

export function endGuidedFlow(ctx) {
  ctx.guidedLock = false;
  switchChatMode(ctx, 'natural', { silent: true });
  renderBotText(ctx, 'Guided flow diakhiri. Silakan ketik pertanyaan Anda.', { typewriter: true });
  ctx.$nextTick(() => { try { ctx.scrollChatToBottom(); } catch {} });
}

export default { renderBotText, switchChatMode, resetGuidedChat, startGuidedInline, openResetConfirm, cancelResetConfirm, confirmReset, onChatOpen, endGuidedFlow };
