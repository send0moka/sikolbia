// Guided wizard prompt renderers and helpers (no heavy fetching here)

export function loadWizardTopiks(ctx) {
  const items = Array.isArray(ctx.wizardData.topiks) ? ctx.wizardData.topiks : [];
  const options = items.map((t) => ({ value: t.id, label: t.nama }));
  ctx.conversation.push({ sender: 'bot', type: 'options', title: 'Pilih Topik', options });
  ctx.$nextTick(() => { try { ctx.scrollChatToBottom(); } catch {} });
}

export function loadWizardVariabels(ctx) {
  const key = String(ctx.wizard.topikId);
  const list = ctx.wizardData.variabelsByTopik?.[key] || [];
  const options = list.map((v) => {
    const label = v.satuan ? `${v.nama} (${v.satuan})` : v.nama;
    return { value: v.id, label };
  });
  if (!options.length) {
    ctx.conversation.push({ sender: 'bot', type: 'text', text: 'Tidak ada variabel untuk topik ini.' });
  } else {
    ctx.conversation.push({ sender: 'bot', type: 'options', title: 'Pilih Variabel', options });
  }
  ctx.$nextTick(() => { try { ctx.scrollChatToBottom(); } catch {} });
}

export function loadWizardKlasifikasis(ctx) {
  const key = String(ctx.wizard.variabelId);
  const list = ctx.wizardData.klasifikasisByVariabel?.[key] || [];
  if (!list.length) {
    ctx.conversation.push({ sender: 'bot', type: 'text', text: 'Tidak ada klasifikasi untuk variabel ini.' });
  } else {
    const options = list.map((k) => ({ value: k.id, label: k.nama }));
    ctx.conversation.push({ sender: 'bot', type: 'checklist', title: 'Pilih Klasifikasi', options, selected: [] });
  }
  ctx.$nextTick(() => { try { ctx.scrollChatToBottom(); } catch {} });
}

export function askYears(ctx) {
  const years = Array.isArray(ctx.wizardData.years) ? ctx.wizardData.years : [];
  const options = years.map((y) => ({ value: y, label: String(y) }));
  // Single-year quick select via options
  ctx.conversation.push({ sender: 'bot', type: 'options', title: 'Pilih Tahun (bisa beberapa nanti)', options });
  ctx.$nextTick(() => { try { ctx.scrollChatToBottom(); } catch {} });
}

export function loadWizardBulans(ctx) {
  const bulans = Array.isArray(ctx.wizardData.bulans) ? ctx.wizardData.bulans : [];
  ctx.wizard.step = 'waktu_bulan_choice';
  if (!bulans.length) {
    ctx.conversation.push({ sender: 'bot', type: 'text', text: 'Tidak ada data bulan tersedia.' });
  } else {
    // Step: choose all months or manual selection
    ctx.conversation.push({ sender: 'bot', type: 'options', title: 'Pilih Cara Memilih Bulan', options: [
      { value: 'bulan_all', label: 'Semua Bulan' },
      { value: 'bulan_manual', label: 'Pilih Bulan Manual' },
    ]});
  }
  ctx.$nextTick(() => { try { ctx.scrollChatToBottom(); } catch {} });
}

export function askWilayah(ctx) {
  ctx.conversation.push({ sender: 'bot', type: 'options', title: 'Tingkat Wilayah', options: [
    { value: 'nasional', label: 'Nasional (Pilih Provinsi)' },
    { value: 'provinsi', label: 'Provinsi (Pilih Kabupaten/Kota)' },
  ]});
  ctx.$nextTick(() => { try { ctx.scrollChatToBottom(); } catch {} });
}

export function askProvinces(ctx, single = false) {
  const provinces = (ctx.wizardData.wilayahs || []).map((p) => ({ value: p.id, label: p.nama }));
  if (single) {
    ctx.conversation.push({ sender: 'bot', type: 'options', title: 'Pilih Provinsi', options: provinces });
  } else {
    ctx.conversation.push({ sender: 'bot', type: 'checklist', title: 'Pilih Provinsi', options: provinces, selected: [] });
    ctx.wizard.step = 'wilayah_provinsi';
  }
  ctx.$nextTick(() => { try { ctx.scrollChatToBottom(); } catch {} });
}

export function askKabupaten(ctx, provId) {
  const prov = (ctx.wizardData.wilayahs || []).find((p) => String(p.id) === String(provId));
  const opts = (prov?.kabupaten || []).map((k) => ({ value: k.id, label: k.nama }));
  ctx.conversation.push({ sender: 'bot', type: 'checklist', title: `Pilih Kabupaten/Kota di ${prov?.nama || 'Provinsi'}` , options: opts, selected: [] });
  ctx.wizard.step = 'wilayah_kabupaten';
  ctx.$nextTick(() => { try { ctx.scrollChatToBottom(); } catch {} });
}

export default { loadWizardTopiks, loadWizardVariabels, loadWizardKlasifikasis, askYears, loadWizardBulans, askWilayah, askProvinces, askKabupaten };
