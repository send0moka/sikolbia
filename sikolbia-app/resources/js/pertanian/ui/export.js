// Excel export logic for pertanian reports

export function exportExcel(ctx) {
  const currentResult = ctx.selectedResultIndex !== null ? ctx.storedResults[ctx.selectedResultIndex] : null;
  if (!currentResult || !currentResult.results) return;

  // Temporarily disable beforeunload prompt during export
  ctx.skipUnloadPrompt = true;

  const form = document.createElement('form');
  form.method = 'POST';
  form.action = `/pertanian/${ctx.moduleType}/export`;
  form.style.display = 'none';

  const csrfToken = document.createElement('input');
  csrfToken.type = 'hidden';
  csrfToken.name = '_token';
  csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
  form.appendChild(csrfToken);

  // Build selections[] inputs
  const selections = currentResult.exportSelections || [];
  const isLahan = ctx.moduleType === 'lahan';
  selections.forEach((sel, idx) => {
    const mk = (name, value) => {
      const el = document.createElement('input');
      el.type = 'hidden';
      el.name = `selections[${idx}][${name}]`;
      el.value = value;
      form.appendChild(el);
    };
    mk('variabel_id', sel.variabel_id);
    const yearField = isLahan ? 'tahuns' : 'tahun_ids';
    (sel.tahun_ids || sel.tahuns || []).forEach(v => {
      const el = document.createElement('input');
      el.type = 'hidden';
      el.name = `selections[${idx}][${yearField}][]`;
      el.value = v;
      form.appendChild(el);
    });
    (sel.klasifikasi_ids || []).forEach(v => {
      const el = document.createElement('input');
      el.type = 'hidden';
      el.name = `selections[${idx}][klasifikasi_ids][]`;
      el.value = v;
      form.appendChild(el);
    });
    if (!isLahan) {
      (sel.bulan_ids || []).forEach(v => {
        const el = document.createElement('input');
        el.type = 'hidden';
        el.name = `selections[${idx}][bulan_ids][]`;
        el.value = v;
        form.appendChild(el);
      });
    }
  });

  // Build config inputs
  const cfg = currentResult.exportConfig || { tata_letak: ctx.selection?.tata_letak, provinsi_ids: [], kabupaten_ids: [] };
  const cfgTata = document.createElement('input');
  cfgTata.type = 'hidden';
  cfgTata.name = 'config[tata_letak]';
  cfgTata.value = cfg.tata_letak || 'tipe_1';
  form.appendChild(cfgTata);
  (cfg.provinsi_ids || []).forEach(v => {
    const el = document.createElement('input');
    el.type = 'hidden';
    el.name = 'config[provinsi_ids][]';
    el.value = v;
    form.appendChild(el);
  });
  (cfg.kabupaten_ids || []).forEach(v => {
    const el = document.createElement('input');
    el.type = 'hidden';
    el.name = 'config[kabupaten_ids][]';
    el.value = v;
    form.appendChild(el);
  });

  // Optional: filename
  const filename = document.createElement('input');
  filename.type = 'hidden';
  filename.name = 'filename';
  filename.value = `laporan-${ctx.moduleType}-${Date.now()}.xlsx`;
  form.appendChild(filename);

  document.body.appendChild(form);
  form.submit();
  document.body.removeChild(form);

  // Re-enable prompt after export completes (best-effort)
  const reset = () => { ctx.skipUnloadPrompt = false; window.removeEventListener('focus', reset); };
  window.addEventListener('focus', reset);
  setTimeout(reset, 3000);
}
