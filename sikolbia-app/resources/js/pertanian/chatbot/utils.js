// Chatbot summary and tutorial helpers

export function buildSummaryLines(tableData) {
  const lines = [];
  const headers = (tableData?.headers || []);
  const last = headers.length ? headers[headers.length - 1] : [];
  const colNames = last.map((h) => h.name).filter((n) => n && n !== 'Wilayah');
  const rows = (tableData?.rows || []).slice(0, 3); // limit a few wilayah
  rows.forEach((r) => {
    const pairs = [];
    (r.values || []).forEach((v, i) => {
      const name = colNames[i];
      if (name && v !== null && v !== undefined) {
        try {
          const text = (typeof v === 'number') ? Number(v).toLocaleString('id-ID', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) : v;
          pairs.push(`${name}: ${text}`);
        } catch {
          pairs.push(`${name}: ${v}`);
        }
      }
    });
    if (pairs.length) lines.push(`${r.wilayah} — ${pairs.slice(0, 6).join(', ')}`);
  });
  if (!lines.length) lines.push('Tidak ada data untuk diringkas.');
  return lines;
}

export function buildTutorialText(moduleSlug, pend) {
  try {
    const labelMod = (pend?.meta?.module) || (String(moduleSlug || '')
      .replace('benih-pupuk', 'Benih & Pupuk')
      .replace('iklim-opt-dpi', 'Iklim & OPT DPI')
      .replace('lahan', 'Lahan'));
    const url = `/pertanian/${moduleSlug}`;
    const meta = pend?.meta || {};
    const sel = pend?.selections?.[0] || {};
    const cfg = pend?.config || {};
    const wilayahNames = (pend?.results?.rows || []).slice(0, 3).map((r) => r.wilayah).filter(Boolean);
    const wilayahText = wilayahNames.length
      ? wilayahNames.join(', ') + (((pend?.results?.rows || []).length > 3) ? ', dan lainnya' : '')
      : 'sesuai pratinjau';
    const tahun = (sel.tahun_ids && sel.tahun_ids.length)
      ? sel.tahun_ids.join(', ')
      : (sel.tahuns && sel.tahuns.length ? sel.tahuns.join(', ') : 'terbaru');
    const bulanIds = Array.isArray(sel.bulan_ids) ? sel.bulan_ids : [];
    const bulanText = bulanIds.length ? 'semua bulan yang tersedia' : (moduleSlug === 'lahan' ? '(tidak bulanan)' : 'semua bulan');
    const steps = [
      `Buka halaman ${labelMod}: <a href="${url}" target="_blank" rel="noopener">${url}</a>`,
      `Pilih modul: ${labelMod}`,
      meta.topik ? `Pilih topik: ${meta.topik}` : null,
      meta.variabel ? `Pilih variabel: ${meta.variabel}` : null,
      meta.klasifikasi ? `Centang klasifikasi: ${meta.klasifikasi}` : 'Centang klasifikasi yang diinginkan',
      `Pilih tahun: ${tahun}`,
      moduleSlug !== 'lahan' ? `Pilih bulan: ${bulanText}` : null,
      `Pilih wilayah: ${wilayahText}`,
      `Atur tata letak tabel: ${cfg.tata_letak || 'tipe_1'}`,
      `Tekan Tampilkan untuk melihat tabel lengkap.`,
    ].filter(Boolean);
    return `Tutorial singkat membuka tabel lengkap:\n- ` + steps.join('\n- ');
  } catch {
    return 'Tutorial tidak tersedia saat ini.';
  }
}

export default { buildSummaryLines, buildTutorialText };
