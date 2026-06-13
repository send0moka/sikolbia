// Bridge between structured suggestion and report preview
import api from '../api/pertanianApi.js';
import chatbotApi from '../api/chatbotApi.js';

function normalizeText(v) {
  return String(v || '').toLowerCase().replace(/[^a-z0-9\s]/g, ' ').replace(/\s+/g, ' ').trim();
}

function tokenize(v) {
  return normalizeText(v).split(' ').filter(Boolean);
}

function scoreByTokens(text, tokens) {
  const hay = normalizeText(text);
  if (!hay || !tokens.length) return 0;
  let score = 0;
  for (const t of tokens) {
    if (t.length < 2) continue;
    if (hay === t) score += 6;
    else if (hay.startsWith(`${t} `) || hay.endsWith(` ${t}`)) score += 4;
    else if (hay.includes(t)) score += 2;
  }
  return score;
}

function makeDisambiguationPrompt(title, candidates, multi = false) {
  const listed = candidates
    .slice(0, 8)
    .map((c, i) => `${i + 1}. ${c.label}`)
    .join('; ');
  const hint = multi
    ? 'Balas dengan nama atau nomor (pisahkan dengan koma untuk lebih dari satu).'
    : 'Balas dengan nama atau nomor pilihan.';
  return `${title}: ${listed}. ${hint}`;
}

function setPrompt(ctx, field, title, candidates, multi = false) {
  ctx.pendingPrompt = {
    type: 'structured_disambiguation',
    field,
    multi,
    candidates,
  };
  ctx.conversation.push({ sender: 'bot', type: 'text', text: makeDisambiguationPrompt(title, candidates, multi) });
}

function resolveModule(ctx, s, moduleChoice) {
  const mods = Array.isArray(s.modules) ? s.modules : [];
  let chosenModule = moduleChoice || null;
  if (!chosenModule) {
    const explicit = detectExplicitModule(s.query);
    if (explicit && mods.includes(explicit)) chosenModule = explicit;
  }
  if (!chosenModule) chosenModule = mods.length ? mods[0] : (ctx.wizard.moduleType || ctx.moduleType);
  return chosenModule || null;
}

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

function chooseTopik(ctx, queryTokens, queryText) {
  const topiks = ctx.wizardData.topiks || [];
  if (!topiks.length) return { status: 'error', message: 'Topik tidak ditemukan untuk modul ini.' };

  if (ctx.wizard.topikId) {
    const ok = topiks.find((t) => String(t.id) === String(ctx.wizard.topikId));
    if (ok) return { status: 'ok', value: ok.id };
  }

  const explicitTopik = detectExplicitTopik(queryText);
  if (explicitTopik) {
    const hit = topiks.find((tp) => normalizeText(tp.nama).includes(explicitTopik));
    if (hit) return { status: 'ok', value: hit.id };
  }

  const scored = topiks
    .map((tp) => ({
      id: tp.id,
      label: String(tp.nama || ''),
      score: scoreByTokens(`${tp.nama || ''} ${tp.deskripsi || ''}`, queryTokens),
    }))
    .sort((a, b) => b.score - a.score);

  if (scored.length === 1) return { status: 'ok', value: scored[0].id };
  if (scored[0].score > 0 && (scored[1]?.score || 0) === 0) return { status: 'ok', value: scored[0].id };

  const candidates = (scored[0].score > 0 ? scored : topiks.map((tp) => ({ id: tp.id, label: String(tp.nama || '') })))
    .slice(0, 8)
    .map((x) => ({ id: x.id, label: x.label }));
  setPrompt(ctx, 'topikId', 'Topik belum spesifik', candidates, false);
  return { status: 'needs_input' };
}

function chooseVariabel(ctx, queryTokens) {
  const list = ctx.wizardData.variabelsByTopik[String(ctx.wizard.topikId)] || [];
  if (!list.length) return { status: 'error', message: 'Variabel tidak ditemukan untuk topik ini.' };

  if (ctx.wizard.variabelId) {
    const ok = list.find((v) => String(v.id) === String(ctx.wizard.variabelId));
    if (ok) return { status: 'ok', value: ok.id };
  }

  const scored = list
    .map((v) => ({
      id: v.id,
      label: String(v.nama || v.deskripsi || ''),
      score: scoreByTokens(`${v.nama || ''} ${v.deskripsi || ''} ${v.satuan || ''}`, queryTokens),
    }))
    .sort((a, b) => b.score - a.score);

  if (scored.length === 1) return { status: 'ok', value: scored[0].id };
  if (scored[0].score > 0 && (scored[1]?.score || 0) === 0) return { status: 'ok', value: scored[0].id };

  const candidates = (scored[0].score > 0 ? scored : list.map((v) => ({ id: v.id, label: String(v.nama || v.deskripsi || '') })))
    .slice(0, 8)
    .map((x) => ({ id: x.id, label: x.label }));
  setPrompt(ctx, 'variabelId', 'Variabel belum spesifik', candidates, false);
  return { status: 'needs_input' };
}

function chooseKlasifikasi(ctx, queryTokens) {
  const list = ctx.wizardData.klasifikasisByVariabel[String(ctx.wizard.variabelId)] || [];
  if (!list.length) return { status: 'error', message: 'Klasifikasi tidak ditemukan untuk variabel ini.' };

  if (Array.isArray(ctx.wizard.klasifikasiIds) && ctx.wizard.klasifikasiIds.length > 0) {
    const keep = list
      .filter((k) => ctx.wizard.klasifikasiIds.map(String).includes(String(k.id)))
      .map((k) => k.id);
    if (keep.length) return { status: 'ok', value: keep };
  }

  const scored = list
    .map((k) => ({
      id: k.id,
      label: String(k.nama || k.deskripsi || ''),
      score: scoreByTokens(`${k.nama || ''} ${k.deskripsi || ''}`, queryTokens),
    }))
    .sort((a, b) => b.score - a.score);

  const matched = scored.filter((x) => x.score > 0).slice(0, 3);
  if (matched.length === 1) return { status: 'ok', value: [matched[0].id] };
  if (list.length === 1) return { status: 'ok', value: [list[0].id] };
  if (matched.length > 1) return { status: 'ok', value: matched.map((m) => m.id) };

  const candidates = list.slice(0, 8).map((k) => ({ id: k.id, label: String(k.nama || k.deskripsi || '') }));
  setPrompt(ctx, 'klasifikasiIds', 'Klasifikasi belum spesifik', candidates, true);
  return { status: 'needs_input' };
}

function chooseTahun(ctx, s) {
  const yearsAvailable = (ctx.wizardData.years || ctx.wizardData.tahuns || []).map((y) => Number(y)).filter(Number.isFinite);
  if (!yearsAvailable.length) return { status: 'error', message: 'Data tahun tidak tersedia untuk kombinasi pilihan ini.' };

  if (Array.isArray(ctx.wizard.tahunIds) && ctx.wizard.tahunIds.length) {
    const keep = ctx.wizard.tahunIds.map(Number).filter((y) => yearsAvailable.includes(y));
    if (keep.length) return { status: 'ok', value: keep };
  }

  const fromQuery = Array.isArray(s.years) ? s.years.map((v) => Number(v)).filter(Number.isFinite) : [];
  if (fromQuery.length) {
    const keep = fromQuery.filter((y) => yearsAvailable.includes(y));
    if (keep.length) return { status: 'ok', value: keep };
    const candidates = yearsAvailable
      .sort((a, b) => b - a)
      .slice(0, 8)
      .map((y) => ({ id: y, label: String(y) }));
    setPrompt(ctx, 'tahunIds', 'Tahun yang diminta tidak tersedia', candidates, false);
    return { status: 'needs_input' };
  }

  const candidates = yearsAvailable
    .sort((a, b) => b - a)
    .slice(0, 8)
    .map((y) => ({ id: y, label: String(y) }));
  setPrompt(ctx, 'tahunIds', 'Tahun perlu dipilih terlebih dahulu', candidates, false);
  return { status: 'needs_input' };
}

function chooseBulan(ctx, s) {
  const bulans = Array.isArray(ctx.wizardData.bulans) ? ctx.wizardData.bulans : [];
  const availableIds = bulans.map((b) => Number(b.id)).filter(Number.isFinite);
  if (!availableIds.length) return { status: 'error', message: 'Data bulan tidak tersedia untuk kombinasi pilihan ini.' };

  if (Array.isArray(ctx.wizard.bulanIds) && ctx.wizard.bulanIds.length) {
    const keep = ctx.wizard.bulanIds.map(Number).filter((m) => availableIds.includes(m));
    if (keep.length) return { status: 'ok', value: keep };
  }

  const fromQuery = Array.isArray(s.months)
    ? s.months.map((m) => Number(typeof m === 'object' ? m.id : m)).filter(Number.isFinite)
    : [];

  if (fromQuery.length) {
    const keep = fromQuery.filter((m) => availableIds.includes(m));
    if (keep.length) return { status: 'ok', value: keep };
    const candidates = bulans.slice(0, 12).map((b) => ({ id: Number(b.id), label: String(b.nama || b.id) }));
    setPrompt(ctx, 'bulanIds', 'Bulan yang diminta tidak tersedia', candidates, true);
    return { status: 'needs_input' };
  }

  const candidates = bulans.slice(0, 12).map((b) => ({ id: Number(b.id), label: String(b.nama || b.id) }));
  setPrompt(ctx, 'bulanIds', 'Bulan perlu dipilih terlebih dahulu', candidates, true);
  return { status: 'needs_input' };
}

function chooseWilayah(ctx, s, queryText) {
  const provinces = Array.isArray(ctx.wizardData.wilayahs) ? ctx.wizardData.wilayahs : [];
  const provMap = new Map();
  const kabMap = new Map();
  for (const p of provinces) {
    provMap.set(String(p.id), p);
    for (const k of (p.kabupaten || [])) kabMap.set(String(k.id), { ...k, id_parent: p.id, provinsi: p.nama });
  }

  if ((ctx.wizard.provinsiIds || []).length || (ctx.wizard.kabupatenIds || []).length) {
    return { status: 'ok', value: true };
  }

  const hits = Array.isArray(s.wilayah_hits) ? s.wilayah_hits : [];
  if (!hits.length) {
    const candidates = provinces.slice(0, 8).map((p) => ({ id: p.id, label: `Provinsi ${p.nama}`, kind: 'provinsi' }));
    setPrompt(ctx, 'wilayah', 'Wilayah belum disebutkan', candidates, false);
    return { status: 'needs_input' };
  }

  const qNorm = normalizeText(queryText);
  const ranked = hits.map((h) => {
    const name = String(h.nama || '');
    const n = normalizeText(name);
    let score = qNorm.includes(n) ? 10 : scoreByTokens(name, tokenize(queryText));
    return { ...h, score };
  }).sort((a, b) => b.score - a.score);

  const top = ranked[0];
  const second = ranked[1];
  const ambiguous = second && top && top.score === second.score;

  if (!ambiguous) {
    const id = String(top.id);
    if (provMap.has(id)) {
      ctx.wizard.provinsiIds = [top.id];
      ctx.wizard.kabupatenIds = [];
      return { status: 'ok', value: true };
    }
    if (kabMap.has(id)) {
      const kb = kabMap.get(id);
      ctx.wizard.provinsiIds = [kb.id_parent];
      ctx.wizard.kabupatenIds = [top.id];
      return { status: 'ok', value: true };
    }
  }

  const candidates = ranked.slice(0, 8).map((h) => {
    const id = String(h.id);
    if (provMap.has(id)) {
      const prov = provMap.get(id);
      const core = normalizeText(prov.nama);
      return {
        id: h.id,
        label: `Provinsi ${h.nama}`,
        kind: 'provinsi',
        core,
        aliases: [
          `provinsi ${core}`,
          core,
        ],
      };
    }
    if (kabMap.has(id)) {
      const kb = kabMap.get(id);
      const core = normalizeText(kb.nama || h.nama);
      return {
        id: h.id,
        label: `Kab/Kota ${h.nama} (Provinsi ${kb.provinsi})`,
        kind: 'kabupaten',
        parentId: kb.id_parent,
        core,
        aliases: [
          `kabupaten ${core}`,
          `kab ${core}`,
          `kab. ${core}`,
          `kab kota ${core}`,
          core,
        ],
      };
    }
    return { id: h.id, label: String(h.nama || h.id), kind: 'unknown' };
  });
  setPrompt(ctx, 'wilayah', 'Wilayah terdeteksi lebih dari satu', candidates, false);
  return { status: 'needs_input' };
}

export async function applyStructuredSuggestion(ctx, moduleChoice, options = {}) {
  try {
    const s = ctx.structuredSuggestion || {};
    const queryText = String(s.query || '');
    const queryTokens = tokenize(queryText);
    let chosenModule = resolveModule(ctx, s, moduleChoice);
    if (!chosenModule) {
      ctx.conversation.push({ sender: 'bot', type: 'text', text: 'Tidak ada modul yang terdeteksi dari pencarian terstruktur.' });
      return { status: 'no-module' };
    }

    ctx.wizard.moduleType = chosenModule;
    await ctx.ensureModuleData(chosenModule);

    const topikRes = chooseTopik(ctx, queryTokens, queryText);
    if (topikRes.status === 'needs_input') return { status: 'needs_input', field: 'topikId' };
    if (topikRes.status === 'error') {
      ctx.conversation.push({ sender: 'bot', type: 'text', text: topikRes.message });
      return { status: 'error' };
    }
    ctx.wizard.topikId = topikRes.value;
    await ctx.ensureVariabels(chosenModule, ctx.wizard.topikId);

    const varRes = chooseVariabel(ctx, queryTokens);
    if (varRes.status === 'needs_input') return { status: 'needs_input', field: 'variabelId' };
    if (varRes.status === 'error') {
      ctx.conversation.push({ sender: 'bot', type: 'text', text: varRes.message });
      return { status: 'error' };
    }
    ctx.wizard.variabelId = varRes.value;
    await ctx.ensureKlasifikasis(chosenModule, ctx.wizard.variabelId);

    const klasRes = chooseKlasifikasi(ctx, queryTokens);
    if (klasRes.status === 'needs_input') return { status: 'needs_input', field: 'klasifikasiIds' };
    if (klasRes.status === 'error') {
      ctx.conversation.push({ sender: 'bot', type: 'text', text: klasRes.message });
      return { status: 'error' };
    }
    ctx.wizard.klasifikasiIds = klasRes.value;

    const tahunRes = chooseTahun(ctx, s);
    if (tahunRes.status === 'needs_input') return { status: 'needs_input', field: 'tahunIds' };
    if (tahunRes.status === 'error') {
      ctx.conversation.push({ sender: 'bot', type: 'text', text: tahunRes.message });
      return { status: 'error' };
    }
    ctx.wizard.tahunIds = tahunRes.value;

    if (chosenModule !== 'lahan') {
      const bulanRes = chooseBulan(ctx, s);
      if (bulanRes.status === 'needs_input') return { status: 'needs_input', field: 'bulanIds' };
      if (bulanRes.status === 'error') {
        ctx.conversation.push({ sender: 'bot', type: 'text', text: bulanRes.message });
        return { status: 'error' };
      }
      ctx.wizard.bulanIds = bulanRes.value;
    } else {
      ctx.wizard.bulanIds = [];
    }

    const wilayahRes = chooseWilayah(ctx, s, queryText);
    if (wilayahRes.status === 'needs_input') return { status: 'needs_input', field: 'wilayah' };
    if (wilayahRes.status === 'error') {
      ctx.conversation.push({ sender: 'bot', type: 'text', text: wilayahRes.message });
      return { status: 'error' };
    }

    // Finalize by fetching preview
    await finishPreview(ctx);
    return { status: 'applied' };
  } catch (e) {
    ctx.conversation.push({ sender: 'bot', type: 'text', text: 'Gagal menerapkan hasil terstruktur: ' + (e.message || e) });
    return { status: 'error' };
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
    const tahunLabel = (ctx.wizard.tahunIds || []).map(String).join(', ');
    const bulanLabel = (ctx.wizard.moduleType !== 'lahan')
      ? (ctx.wizardData.bulans || [])
          .filter((b) => (ctx.wizard.bulanIds || []).map(String).includes(String(b.id)))
          .map((b) => String(b.nama || b.id))
          .join(', ')
      : null;
    const meta = {
      module: ctx.wizard.moduleType?.replace('benih-pupuk', 'Benih & Pupuk')?.replace('iklim-opt-dpi', 'Iklim & OPT DPI')?.replace('lahan', 'Lahan'),
      topik: topikObj?.nama || null,
      variabel: varObj ? (varObj.nama + (varObj.satuan ? ` (${varObj.satuan})` : '')) : null,
      klasifikasi: klasList.length ? klasList.join(', ') : null,
      tahun: tahunLabel || null,
      bulan: bulanLabel || null,
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
    if (meta.tahun) {
      lines = [`Periode tahun: ${meta.tahun}${meta.bulan ? ` | Bulan: ${meta.bulan}` : ''}`, ...lines];
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
