// Form selection and validation helpers

export function selectTopik(ctx, id) {
  ctx.selection.topik_id = id;
  ctx.selection.variabel_id = null;
  ctx.selection.klasifikasi_ids = [];
}

export function selectVariabel(ctx, id) {
  ctx.selection.variabel_id = id;
  ctx.selection.klasifikasi_ids = [];
}

export function isSelectionValid(ctx) {
  const hasKlasifikasiOptions = ctx.filteredKlasifikasi.length > 0;
  const isKlasifikasiValid = !hasKlasifikasiOptions || (hasKlasifikasiOptions && ctx.selection.klasifikasi_ids.length > 0);
  const requireBulan = ctx.moduleType !== 'lahan';
  const bulanOk = requireBulan ? ctx.selection.bulan_ids.length > 0 : true;
  return ctx.selection.topik_id && ctx.selection.variabel_id && isKlasifikasiValid && ctx.selection.tahun_ids.length > 0 && bulanOk;
}

export function addSelection(ctx) {
  if (!isSelectionValid(ctx)) return;
  const topik = ctx.allData.topiks.find(t => String(t.id) === String(ctx.selection.topik_id));
  const variabel = ctx.allData.variabels.find(v => String(v.id) === String(ctx.selection.variabel_id));
  const klasifikasiNames = ctx.selection.klasifikasi_ids
    .map(id => (ctx.allData.klasifikasis.find(k => String(k.id) === String(id))?.nama || ''))
    .filter(name => name !== '');
  const tahun_awal = Math.min(...ctx.selection.tahun_ids);
  const tahun_akhir = Math.max(...ctx.selection.tahun_ids);
  const bulan_awal = ctx.moduleType !== 'lahan' ? (ctx.allData.bulans.find(b => b.id == Math.min(...ctx.selection.bulan_ids))?.nama || '') : '';
  const bulan_akhir = ctx.moduleType !== 'lahan' ? (ctx.allData.bulans.find(b => b.id == Math.max(...ctx.selection.bulan_ids))?.nama || '') : '';
  const newSelection = {
    id: Date.now(),
    topik_nama: topik?.nama || '',
    variabel_nama: variabel?.nama || '',
    variabel_satuan: variabel?.satuan || '',
    klasifikasi_nama: klasifikasiNames.join(', ') || 'Semua',
    tahun_awal,
    tahun_akhir,
    bulan_awal,
    bulan_akhir,
    topik_id: ctx.selection.topik_id,
    variabel_id: ctx.selection.variabel_id,
    klasifikasi_ids: [...ctx.selection.klasifikasi_ids],
    tahun_ids: [...ctx.selection.tahun_ids],
    bulan_ids: ctx.moduleType !== 'lahan' ? [...ctx.selection.bulan_ids] : [],
  };
  ctx.selections.push(newSelection);
  resetSelection(ctx);
}

export function removeSelection(ctx) {
  const idsToRemove = ctx.selectedForRemoval.map(Number);
  ctx.selections = ctx.selections.filter(item => !idsToRemove.includes(item.id));
  ctx.selectedForRemoval = [];
}

export function resetSelection(ctx) {
  ctx.selection.topik_id = null;
  ctx.selection.variabel_id = null;
  ctx.selection.klasifikasi_ids = [];
  ctx.selection.tahun_ids = [];
  ctx.selection.bulan_ids = [];
}

export function resetForm(ctx) {
  ctx.selection = {
    topik_id: null,
    variabel_id: null,
    klasifikasi_ids: [],
    tahun_ids: [],
    bulan_ids: [],
    provinsi_ids: [],
    kabupaten_ids: [],
    tata_letak: 'tipe_1',
    wilayah: { selected_provinsi: null },
  };
  ctx.selections = [];
  ctx.searchResults = { headers: [], rows: [], config: {} };
  ctx.selectedForRemoval = [];
  ctx.wilayahLevel = 'nasional';
  ctx.selectedProvinsiId = null;
}

export function loadVariabels(ctx) { ctx.selection.variabel_id = null; ctx.selection.klasifikasi_ids = []; }
export function loadKlasifikasis(ctx) { ctx.selection.klasifikasi_ids = []; }

export default {
  selectTopik,
  selectVariabel,
  isSelectionValid,
  addSelection,
  removeSelection,
  resetSelection,
  resetForm,
  loadVariabels,
  loadKlasifikasis,
};
