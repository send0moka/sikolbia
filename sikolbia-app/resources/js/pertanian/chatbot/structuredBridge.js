// Bridge between structured suggestion and report preview
import api from '../api/pertanianApi.js';
import chatbotApi from '../api/chatbotApi.js';

function detectExplicitModule(txt) {
  const t = String(txt || '').toLowerCase();
  if (/(benih|pupuk)/i.test(t)) return 'benih-pupuk';
  if (/(iklim|\bopt\b|\bdpi\b|hujan|curah)/i.test(t)) return 'iklim-opt-dpi';
  if (/(lahan|sawah|panen|kebun)/i.test(t)) return 'lahan';
  return null;
}

function detectExplicitTopik(txt) {
  const t = String(txt || '').toLowerCase();
  if (/\bpupuk\b|\b(urea|npk|za|kcl)\b/i.test(t)) return 'pupuk';
  if (/\bbenih\b|sebar/i.test(t)) return 'benih';
  return null;
}

export async function applyStructuredSuggestion(ctx, moduleChoice, options = {}) {
  try {
    const s = ctx.structuredSuggestion || {};
    const mods = Array.isArray(s.modules) ? s.modules : [];
    let chosenModule = moduleChoice || null;
    if (!chosenModule) {
      const explicit = detectExplicitModule(s.query);
      if (explicit && mods.includes(explicit)) chosenModule = explicit;
    }
    if (!chosenModule) {
      chosenModule = mods.length ? mods[0] : (ctx.wizard.moduleType || ctx.moduleType);
    }
    if (!chosenModule) {
      ctx.conversation.push({ sender: 'bot', type: 'text', text: 'Tidak ada modul yang terdeteksi dari pencarian terstruktur.' });
      return;
    }

    ctx.wizard.moduleType = chosenModule;
    await ctx.ensureModuleData(chosenModule);

    // Year selection: prefer the max of suggested years
    const years = Array.isArray(s.years) ? s.years.map((v) => parseInt(v, 10)).filter(Number.isFinite) : [];
    if (years.length) ctx.wizard.tahunIds = [Math.max(...years)];

    // Wilayah selection: prefer one matching query string
    const qLower = String(s.query || '').toLowerCase();
    const wilayahHits = Array.isArray(s.wilayah_hits) ? s.wilayah_hits : [];
    let chosenWilayah = null;
    for (const w of wilayahHits) {
      const nm = String(w.nama || '').toLowerCase();
      if (nm && qLower.includes(nm)) { chosenWilayah = w; break; }
    }
    if (!chosenWilayah && wilayahHits.length) chosenWilayah = wilayahHits[0];
    ctx.wizard.provinsiIds = chosenWilayah ? [chosenWilayah.id].filter(Boolean) : [];
    ctx.wizard.kabupatenIds = [];

    // Topik selection: try to infer from query; otherwise pick first
    const topiks = ctx.wizardData.topiks || [];
    const explicitTopik = detectExplicitTopik(s.query);
    let topikObj = null;
    if (explicitTopik) topikObj = topiks.find((tp) => String(tp.nama || '').toLowerCase().includes(explicitTopik));
    if (!topikObj) topikObj = topiks[0] || null;
    if (!topikObj) {
      ctx.conversation.push({ sender: 'bot', type: 'text', text: 'Topik tidak ditemukan untuk modul ini.' });
      return;
    }
    ctx.wizard.topikId = topikObj.id;
    await ctx.ensureVariabels(chosenModule, ctx.wizard.topikId);

    // Variabel selection: choose first if available
    const varList = ctx.wizardData.variabelsByTopik[String(ctx.wizard.topikId)] || [];
    const varObj = varList[0] || null;
    if (!varObj) {
      ctx.conversation.push({ sender: 'bot', type: 'text', text: 'Variabel tidak ditemukan untuk topik ini.' });
      return;
    }
    ctx.wizard.variabelId = varObj.id;
    await ctx.ensureKlasifikasis(chosenModule, ctx.wizard.variabelId);

    // Klasifikasi: pick a few if available
    const klasList = ctx.wizardData.klasifikasisByVariabel[String(ctx.wizard.variabelId)] || [];
    ctx.wizard.klasifikasiIds = klasList.slice(0, Math.min(3, klasList.length)).map((k) => k.id);

    // Months: for non-lahan, pick all available
    if (chosenModule !== 'lahan') {
      const bulans = ctx.wizardData.bulans || [];
      ctx.wizard.bulanIds = bulans.map((b) => b.id);
    } else {
      ctx.wizard.bulanIds = [];
    }

    // Finalize by fetching preview
    await finishPreview(ctx);
  } catch (e) {
    ctx.conversation.push({ sender: 'bot', type: 'text', text: 'Gagal menerapkan hasil terstruktur: ' + (e.message || e) });
  }
}

export async function finishPreview(ctx) {
  // Build minimal payload for preview
  const isLahan = ctx.wizard.moduleType === 'lahan';
  const selections = [{
    variabel_id: ctx.wizard.variabelId,
    klasifikasi_ids: ctx.wizard.klasifikasiIds,
    ...(isLahan ? { tahuns: ctx.wizard.tahunIds } : { tahun_ids: ctx.wizard.tahunIds, bulan_ids: ctx.wizard.bulanIds })
  }];
  const config = {
    tata_letak: 'tipe_1',
    provinsi_ids: ctx.wizard.provinsiIds,
    kabupaten_ids: ctx.wizard.kabupatenIds,
  };
  // Client-side sanity checks
  if (!ctx.wizard.variabelId) { ctx.conversation.push({ sender: 'bot', type: 'text', text: 'Variabel belum dipilih.' }); return; }
  if (!Array.isArray(ctx.wizard.klasifikasiIds) || ctx.wizard.klasifikasiIds.length === 0) { ctx.conversation.push({ sender: 'bot', type: 'text', text: 'Pilih minimal satu klasifikasi.' }); return; }
  if (!Array.isArray(ctx.wizard.tahunIds) || ctx.wizard.tahunIds.length === 0) { ctx.conversation.push({ sender: 'bot', type: 'text', text: 'Pilih minimal satu tahun.' }); return; }
  if (!isLahan && (!Array.isArray(ctx.wizard.bulanIds) || ctx.wizard.bulanIds.length === 0)) { ctx.conversation.push({ sender: 'bot', type: 'text', text: 'Pilih minimal satu bulan.' }); return; }
  const wilayahCount = (config.provinsi_ids?.length || 0) + (config.kabupaten_ids?.length || 0);
  if (wilayahCount === 0) { ctx.conversation.push({ sender: 'bot', type: 'text', text: 'Pilih minimal satu wilayah.' }); return; }

  try {
    ctx.isLoading = true;
    const data = await api.filterReport(ctx.wizard.moduleType, { selections, config });
    // Compose meta for summary
    let topikObj = (ctx.wizardData.topiks || []).find((t) => String(t.id) === String(ctx.wizard.topikId));
    if (!topikObj && ctx.wizard.moduleType === 'benih-pupuk') {
      const explicitTopik = /\b(pupuk)\b/i.test(String(ctx.structuredSuggestion?.query || '')) ? 'pupuk' : (/\b(benih)\b|sebar/i.test(String(ctx.structuredSuggestion?.query || '')) ? 'benih' : null);
      if (explicitTopik) {
        const tps = ctx.wizardData.topiks || [];
        topikObj = tps.find((tp) => String(tp.nama || '').toLowerCase().includes(explicitTopik));
      }
    }
    const variabelList = ctx.wizardData.variabelsByTopik[String(ctx.wizard.topikId)] || [];
    const varObj = variabelList.find((v) => String(v.id) === String(ctx.wizard.variabelId));
    const klasList = (ctx.wizardData.klasifikasisByVariabel[String(ctx.wizard.variabelId)] || [])
      .filter((k) => (ctx.wizard.klasifikasiIds || []).map(String).includes(String(k.id)))
      .map((k) => k.nama);
  const tablePayload = { headers: data.headers || [], rows: data.rows || [] };
    const meta = {
      module: ctx.wizard.moduleType?.replace('benih-pupuk', 'Benih & Pupuk')?.replace('iklim-opt-dpi', 'Iklim & OPT DPI')?.replace('lahan', 'Lahan'),
      topik: topikObj?.nama || null,
      variabel: varObj ? (varObj.nama + (varObj.satuan ? ` (${varObj.satuan})` : '')) : null,
      klasifikasi: klasList.length ? klasList.join(', ') : null,
    };
    ctx.wizard._pendingPreview = { results: tablePayload, meta, selections, config, moduleType: ctx.wizard.moduleType };
    let lines = [];
    let insights = null;
    try {
      const sr = await chatbotApi.summarizePreview({ ...tablePayload, meta });
      lines = Array.isArray(sr?.summaryLines) ? sr.summaryLines : [];
      insights = sr?.insights || null;
    } catch (e) {
      lines = ctx.buildSummaryLines ? ctx.buildSummaryLines(tablePayload) : [];
    }
    ctx.conversation.push({ sender: 'bot', type: 'summary', title: 'Ringkasan', summaryLines: lines, meta, insights, payload: ctx.wizard._pendingPreview });
    ctx.wizard.step = 'preview';
    const modSlug = ctx.wizard.moduleType;
    const labelMod = meta.module || modSlug;
    ctx.pendingPrompt = { type: 'tutorial', module: modSlug, preview: ctx.wizard._pendingPreview };
    ctx.renderBotText && ctx.renderBotText(`Perlu panduan mencari tabel lengkap di halaman ${labelMod}? Ketik "ya" untuk pandu, atau ketik bebas untuk lanjut.`);
  } catch (e) {
    ctx.conversation.push({ sender: 'bot', type: 'text', text: 'Gagal memuat pratinjau: ' + (e.message || e) });
  } finally {
    ctx.isLoading = false;
    ctx.$nextTick(() => { try { ctx.scrollChatToBottom(); } catch {} });
  }
}

export default { applyStructuredSuggestion, finishPreview };
