// Wilayah selection helpers

export function toggleKabupaten(ctx, id) {
  const index = ctx.selection.kabupaten_ids.indexOf(id);
  if (index > -1) { ctx.selection.kabupaten_ids.splice(index, 1); } else { ctx.selection.kabupaten_ids.push(id); }
}

export function selectAllKabupatenInSelectedProvinsi(ctx) {
  if (!ctx.selectedProvinsiId) return;
  const selectedProvinsi = ctx.allData.wilayahs.find(p => p.id == ctx.selectedProvinsiId);
  if (selectedProvinsi && selectedProvinsi.kabupaten) {
    ctx.selection.kabupaten_ids = selectedProvinsi.kabupaten.map(k => k.id);
  }
}

export function clearKabupatenInSelectedProvinsi(ctx) {
  if (!ctx.selectedProvinsiId) { ctx.selection.kabupaten_ids = []; return; }
  const selectedProvinsi = ctx.allData.wilayahs.find(p => p.id == ctx.selectedProvinsiId);
  if (selectedProvinsi && selectedProvinsi.kabupaten) {
    const kabupatenIdsInSelectedProvinsi = selectedProvinsi.kabupaten.map(k => k.id);
    ctx.selection.kabupaten_ids = ctx.selection.kabupaten_ids.filter(id => !kabupatenIdsInSelectedProvinsi.includes(id));
  }
}

export function toggleWilayah(ctx, id) {
  if (ctx.wilayahLevel === 'nasional') {
    const index = ctx.selection.provinsi_ids.indexOf(id);
    if (index > -1) { ctx.selection.provinsi_ids.splice(index, 1); } else { ctx.selection.provinsi_ids.push(id); }
  } else {
    toggleKabupaten(ctx, id);
  }
}

export default {
  toggleKabupaten,
  selectAllKabupatenInSelectedProvinsi,
  clearKabupatenInSelectedProvinsi,
  toggleWilayah,
};
