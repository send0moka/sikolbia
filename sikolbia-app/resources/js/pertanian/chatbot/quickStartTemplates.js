// Static Quick Start template definitions for guided flow

export function getQuickStartTemplates() {
  return [
    { id:'qs_pupuk_urea_latest', label:'Mulai Cepat: Pupuk Urea (nasional, tahun terbaru)', module:'benih-pupuk', topikMatch:'pupuk', variabelMatches:['urea'], pick:{ years:'latest', months:'all', wilayah:'top5prov', klasifikasi:'few' } },
    { id:'qs_pupuk_npk_latest', label:'Mulai Cepat: Pupuk NPK (nasional, tahun terbaru)', module:'benih-pupuk', topikMatch:'pupuk', variabelMatches:['npk'], pick:{ years:'latest', months:'all', wilayah:'top5prov', klasifikasi:'few' } },
    { id:'qs_lahan_latest', label:'Mulai Cepat: Lahan (nasional, tahun terbaru)', module:'lahan', topikMatch:'lahan', variabelMatches:['luas','lahan','total'], pick:{ years:'latest', wilayah:'top5prov', klasifikasi:'few' } },
    { id:'qs_iklim_hujan_latest', label:'Mulai Cepat: Curah Hujan (nasional, tahun terbaru)', module:'iklim-opt-dpi', topikMatch:'hujan', variabelMatches:['curah','hujan'], pick:{ years:'latest', months:'all', wilayah:'top5prov', klasifikasi:'few' } },
  ];
}

export default { getQuickStartTemplates };
