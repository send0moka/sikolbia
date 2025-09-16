<x-layouts.landing title="Laporan Data Lahan">
    <!-- Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

    <style>
    .table-layout-radio { overflow: hidden; }
    .table-layout-radio:hover { transform: translateY(-1px); box-shadow: 0 4px 12px rgba(0,0,0,.1); }
    .table-preview { transition: all .2s ease-in-out; }
    /* Disable aggressive scale to avoid overflow in small containers */
    .table-layout-radio:hover .table-preview { transform: none; }
        .preview-table th, .preview-table td { font-size: 10px; padding: 4px 6px; }

        /* Sticky table styles (aligned with iklim/benih views) */
        .main-content-flex { overflow: hidden; max-width: 100%; }
        .sticky-table-container { position: relative; overflow: auto; max-height: 500px; max-width: 100%; width: 100%; border-radius: .5rem; border: 1px solid #e5e7eb; box-sizing: border-box; contain: layout paint; will-change: transform; transform: translateZ(0); }
        .sticky-table { border-collapse: separate; border-spacing: 0; width: 100%; min-width: max-content; table-layout: auto; }
        .sticky-table th, .sticky-table td { border-bottom:1px solid #e5e7eb; border-right:1px solid #e5e7eb; white-space: nowrap; padding:.5rem .75rem; text-align:center; vertical-align: middle; box-sizing: border-box; }
        .sticky-table td { text-align: right; }
        .sticky-table th:first-child, .sticky-table td:first-child { text-align: left; }
        .sticky-table th:last-child, .sticky-table td:last-child { border-right: none; }
        .sticky-table thead th { position: sticky; background:#f9fafb; z-index:2; will-change: top; backface-visibility: hidden; border-bottom:1px solid #e5e7eb; border-right:1px solid #e5e7eb; }
        .sticky-table thead th::after { content:''; position:absolute; bottom:0; right:0; left:0; height:1px; background:#e5e7eb; z-index:1; }
        .sticky-table thead th::before { content:''; position:absolute; top:0; right:0; bottom:0; width:1px; background:#e5e7eb; z-index:1; }
        .sticky-table .sticky-wilayah-header { position: sticky !important; left:0 !important; background:#f9fafb !important; z-index:4 !important; border-right:1px solid #e5e7eb !important; font-weight:600 !important; box-shadow:none !important; will-change:left, top; backface-visibility: hidden; }
        .sticky-table tbody td:first-child { position: sticky !important; left:0 !important; background:#fff !important; z-index:1 !important; font-weight:500 !important; border-right:1px solid #e5e7eb !important; box-shadow:none !important; will-change:left; backface-visibility: hidden; }
        .sticky-table-container { --row-top-1:0px; --row-top-2:0px; --row-top-3:0px; --row-top-4:0px; --row-top-5:0px; --row-top-6:0px; --row-top-7:0px; --row-top-8:0px; }
        .sticky-table thead th[data-row-index="1"]{ top:var(--row-top-1,0px);} .sticky-table thead th[data-row-index="2"]{ top:var(--row-top-2,0px);} .sticky-table thead th[data-row-index="3"]{ top:var(--row-top-3,0px);} .sticky-table thead th[data-row-index="4"]{ top:var(--row-top-4,0px);} .sticky-table thead th[data-row-index="5"]{ top:var(--row-top-5,0px);} .sticky-table thead th[data-row-index="6"]{ top:var(--row-top-6,0px);} .sticky-table thead th[data-row-index="7"]{ top:var(--row-top-7,0px);} .sticky-table thead th[data-row-index="8"]{ top:var(--row-top-8,0px);} 
        .sticky-table .sticky-wilayah-header[data-row-index="1"]{ top:var(--row-top-1,0px)!important;} .sticky-table .sticky-wilayah-header[data-row-index="2"]{ top:var(--row-top-2,0px)!important;} .sticky-table .sticky-wilayah-header[data-row-index="3"]{ top:var(--row-top-3,0px)!important;}
        .sticky-table tbody tr:hover td { background:#f3f4f6; }
        .sticky-table tbody tr:hover td:first-child { background:#eff6ff; }

    /* Tabs (match iklim style: underlined active tab, subtle buttons) */
    .tabs-nav { border-bottom: 1px solid #e5e7eb; }
    .tab-link { padding: .5rem .75rem; font-weight: 500; border-bottom: 2px solid transparent; color:#374151; }
    .tab-link:hover { color:#111827; border-color:#e5e7eb; }
    .tab-link.active { color:#1d4ed8; border-color:#1d4ed8; }

    /* Mini preview card visuals */
    .preview-card { background:#f9fafb; border:1px solid #e5e7eb; border-radius:.5rem; overflow:hidden; width:100%; max-width:100%; }
    .preview-scroll { display:block; max-width:100%; overflow-x:auto; overflow-y:hidden; }
    .preview-table { border-collapse: separate; border-spacing: 0; table-layout: fixed; width: 100%; min-width: 0; }
    .preview-table th, .preview-table td { border:1px solid #e5e7eb; text-align:center; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
    .preview-table thead th { background:#f3f4f6; }
    </style>

    <div class="py-12 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Breadcrumb -->
            <nav class="flex mb-8" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('home') }}" class="text-neutral-700 hover:text-blue-600">Home</a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-neutral-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                            <span class="ml-1 text-neutral-500">Pertanian</span>
                        </div>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-neutral-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                            <span class="ml-1 text-blue-600 font-medium">Laporan Data Lahan</span>
                        </div>
                    </li>
                </ol>
            </nav>

            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-3xl md:text-4xl font-bold text-neutral-900 mb-4">Laporan Data Lahan</h1>
                <p class="text-xl text-neutral-600">Analisis data lahan pertanian di Indonesia.</p>
            </div>

<script>
    // Expose initial data on the window object so Alpine components can
    // reliably access it regardless of script load/execute order.
    window.lahanInitialData = {
        topiks: @json($topiks),
        variabels: @json($variabels),
        klasifikasis: @json($klasifikasis),
        tahuns: @json($tahuns),
        wilayahs: @json($wilayahs)
    };

    function lahanForm() {
        return {
            init(data){
                // Defensive: accept undefined and provide empty collections as fallback.
                this.allData = data || { topiks:[], variabels:[], klasifikasis:[], tahuns:[], wilayahs:[] };

                // Build provinsi list from top-level wilayahs and flatten kabupaten from children
                const wilayahs = Array.isArray(this.allData.wilayahs) ? this.allData.wilayahs : [];
                this.provinsis = wilayahs.map(p => ({ id: p.id, nama: p.nama }));
                this.kabupatens = wilayahs.flatMap(p => (Array.isArray(p.kabupaten) ? p.kabupaten : []).map(k => ({ id: k.id, nama: k.nama, id_parent: k.id_parent })));
            },

            // Sources
            allData: { topiks:[], variabels:[], klasifikasis:[], tahuns:[], wilayahs:[] },
            provinsis: [],
            kabupatens: [],

            // Form state
            selection: {
                topik_id: null,
                variabel_id: null,
                klasifikasi_ids: [],
                tahun_ids: [],
                provinsi_ids: [],
                kabupaten_ids: [],
                tata_letak: 'tipe_1'
            },

            wilayahLevel: 'nasional', // nasional: provinces; provinsi: kabupaten
            selectedProvinsiId: null,

            // UI state
            isProcessing: false,
            selections: [],
            selectedForRemoval: [],
            storedResults: [],
            selectedResultIndex: null,
            activeResultTab: 'tabel',
            showLegend: false,

            // Search helpers
            search: { tahun:'', wilayah:'' },

            selectTopik(id){ this.selection.topik_id = id; this.selection.variabel_id=null; this.selection.klasifikasi_ids=[]; },
            selectVariabel(id){ this.selection.variabel_id = id; this.selection.klasifikasi_ids=[]; },

            isSelectionValid(){
                const hasKlas = this.filteredKlasifikasi.length > 0;
                const klasOk = !hasKlas || (hasKlas && this.selection.klasifikasi_ids.length>0);
                return this.selection.topik_id && this.selection.variabel_id && klasOk && this.selection.tahun_ids.length>0;
            },

            addSelection(){
                if (!this.isSelectionValid()) return;
                const topik = this.allData.topiks.find(t=>t.id==this.selection.topik_id);
                const variabel = this.allData.variabels.find(v=>v.id==this.selection.variabel_id);
                const klasifikasiNames = this.selection.klasifikasi_ids.map(id=> (this.allData.klasifikasis.find(k=>k.id==id)?.nama)||'').filter(Boolean);
                const tahun_awal = Math.min(...this.selection.tahun_ids);
                const tahun_akhir = Math.max(...this.selection.tahun_ids);
                this.selections.push({
                    id: Date.now(),
                    topik_nama: topik?.nama || '',
                    variabel_nama: variabel?.nama || '',
                    variabel_satuan: variabel?.satuan || '',
                    klasifikasi_nama: klasifikasiNames.join(', ')||'Semua',
                    tahun_awal, tahun_akhir,
                    // payload
                    topik_id: this.selection.topik_id,
                    variabel_id: this.selection.variabel_id,
                    klasifikasi_ids: [...this.selection.klasifikasi_ids],
                    tahun_ids: [...this.selection.tahun_ids]
                });
                this.resetSelectionOnly();
            },

            removeSelection(){
                const ids = this.selectedForRemoval.map(Number);
                this.selections = this.selections.filter(s => !ids.includes(s.id));
                this.selectedForRemoval = [];
            },

            resetSelectionOnly(){
                this.selection.topik_id=null; this.selection.variabel_id=null; this.selection.klasifikasi_ids=[]; this.selection.tahun_ids=[];
            },
            resetForm(){ this.selection = { topik_id:null, variabel_id:null, klasifikasi_ids:[], tahun_ids:[], provinsi_ids:[], kabupaten_ids:[], tata_letak:'tipe_1'}; this.selections=[]; this.selectedForRemoval=[]; this.wilayahLevel='nasional'; this.selectedProvinsiId=null; this.storedResults=[]; this.selectedResultIndex=null; },

            get filteredVariabel(){ if(!this.selection.topik_id) return []; return this.allData.variabels.filter(v=>v.id_topik==this.selection.topik_id); },
            get filteredKlasifikasi(){ if(!this.selection.variabel_id) return []; return this.allData.klasifikasis.filter(k=>k.id_variabel==this.selection.variabel_id); },
            get filteredTahun(){ return this.allData.tahuns.filter(t => t.toString().includes(this.search.tahun)); },
            get filteredProvinsi(){ const s=this.search.wilayah.toLowerCase(); return this.provinsis.filter(p=>!s||p.nama.toLowerCase().includes(s)); },
            get kabupatenOfSelectedProvinsi(){ if(!this.selectedProvinsiId) return []; return this.kabupatens.filter(k=>k.id_parent==this.selectedProvinsiId); },

            toggleWilayah(id){
                if(this.wilayahLevel==='nasional'){
                    const idx = this.selection.provinsi_ids.indexOf(id); if(idx>-1) this.selection.provinsi_ids.splice(idx,1); else this.selection.provinsi_ids.push(id);
                } else {
                    const idx = this.selection.kabupaten_ids.indexOf(id); if(idx>-1) this.selection.kabupaten_ids.splice(idx,1); else this.selection.kabupaten_ids.push(id);
                }
            },
            selectAllKabupaten(){ if(!this.selectedProvinsiId) return; this.selection.kabupaten_ids = this.kabupatenOfSelectedProvinsi.map(k=>k.id); },
            clearKabupaten(){ if(!this.selectedProvinsiId) { this.selection.kabupaten_ids=[]; return; } const ids = this.kabupatenOfSelectedProvinsi.map(k=>k.id); this.selection.kabupaten_ids = this.selection.kabupaten_ids.filter(id=>!ids.includes(id)); },

            async fetchData(){
                if(this.selections.length===0){ alert('Silakan tambahkan data terlebih dahulu.'); return; }
                const wilayah_ids = this.wilayahLevel==='nasional' ? this.selection.provinsi_ids : this.selection.kabupaten_ids;
                if(wilayah_ids.length===0){ alert('Pilih minimal satu wilayah.'); return; }
                this.isProcessing=true;
                try{
                    const payload = {
                        selections: this.selections.map(s=>({ topik_id:s.topik_id, variabel_id:s.variabel_id, klasifikasi_ids:s.klasifikasi_ids, tahuns:s.tahun_ids })),
                        wilayah_ids,
                        layout: this.selection.tata_letak
                    };
                    const res = await fetch('/api/lahan/filter', { method:'POST', headers:{ 'Content-Type':'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') }, body: JSON.stringify(payload)});
                    const json = await res.json();
                    if(!res.ok || !json.success){ throw new Error(json.message||'Gagal memproses data'); }

                    const resultIndex = this.storedResults.length + 1;
                    const stored = { id: Date.now(), title:`Hasil ${resultIndex}`, timestamp: new Date().toLocaleString('id-ID'), results: json.data };
                    this.storedResults.push(stored);
                    this.selectedResultIndex = this.storedResults.length-1;
                } catch(err){ alert(err.message); }
                finally{ this.isProcessing=false; }
            },

            // Dynamic headers built from processed_selections to mirror backend order
            get dynamicHeaders(){
                const cur = this.selectedResultIndex!==null ? this.storedResults[this.selectedResultIndex] : null;
                if(!cur) return [];
                const layout = cur.results.layout;
                const processed = cur.results.processed_selections || [];
                if(processed.length===0) return [];
                if(layout==='tipe_1') return this.generateTipe1Headers(processed);
                if(layout==='tipe_2') return this.generateTipe2Headers(processed);
                if(layout==='tipe_3') return this.generateTipe3Headers(processed);
                return [];
            },

            // Column keys in exact order for value extraction
            get columnKeys(){
                const cur = this.selectedResultIndex!==null ? this.storedResults[this.selectedResultIndex] : null;
                if(!cur) return [];
                const layout = cur.results.layout; const processed = cur.results.processed_selections||[]; const keys=[];
                if(layout==='tipe_1'){
                    processed.forEach(sel=>{ sel.klasifikasis.forEach(k=>{ sel.tahuns.forEach(t=> keys.push(sel.variabel+'|'+k+'|'+t)); }); });
                } else if(layout==='tipe_2'){
                    // order by klasifikasi groups then variabels within
                    const allK=[]; processed.forEach(sel=> sel.klasifikasis.forEach(k=>{ if(!allK.includes(k)) allK.push(k); }));
                    allK.forEach(k=>{ processed.forEach(sel=>{ if(sel.klasifikasis.includes(k)){ sel.tahuns.forEach(t=> keys.push(sel.variabel+'|'+k+'|'+t)); } }); });
                } else if(layout==='tipe_3'){
                    const allT=[]; processed.forEach(sel=> sel.tahuns.forEach(t=>{ if(!allT.includes(t)) allT.push(t); })); allT.sort((a,b)=>a-b);
                    allT.forEach(t=>{ processed.forEach(sel=>{ if(sel.tahuns.includes(t)){ sel.klasifikasis.forEach(k=> keys.push(sel.variabel+'|'+k+'|'+t)); } }); });
                }
                return keys;
            },

            get dynamicRows(){
                const cur = this.selectedResultIndex!==null ? this.storedResults[this.selectedResultIndex] : null; if(!cur) return [];
                const rows = cur.results.rows || [];
                const keys = this.columnKeys;
                // Use server order (already sorted by sorter)
                return rows.map(r=> ({ wilayah: r.wilayah_nama, values: keys.map(k=> { const v = r.data?.[k]; return (typeof v==='number' ? v : (v==null? null : Number(v))); }) }));
            },

            // Header generators (3 rows): Variabel/Klasifikasi/Tahun in different orders
            generateTipe1Headers(processed){ // Variabel » Klasifikasi » Tahun
                const headers=[]; const row1=[{name:'Wilayah', span:1, rowspan:3}];
                const variabels=[]; processed.forEach(s=>{ if(!variabels.includes(s.variabel)) variabels.push(s.variabel); });
                variabels.forEach(v=>{ let span=0; processed.forEach(s=>{ if(s.variabel===v) span += s.klasifikasis.length * s.tahuns.length; }); if(span>0) row1.push({name:v, span, rowspan:1}); }); headers.push(row1);
                const row2=[]; variabels.forEach(v=>{ processed.forEach(s=>{ if(s.variabel===v){ s.klasifikasis.forEach(k=> row2.push({name:k, span:s.tahuns.length, rowspan:1})); } }); }); headers.push(row2);
                const row3=[]; variabels.forEach(v=>{ processed.forEach(s=>{ if(s.variabel===v){ s.klasifikasis.forEach(()=> s.tahuns.forEach(t=> row3.push({name:String(t), span:1, rowspan:1}))); } }); }); headers.push(row3);
                return headers;
            },
            generateTipe2Headers(processed){ // Klasifikasi » Variabel » Tahun
                const headers=[]; const allK=[]; processed.forEach(s=> s.klasifikasis.forEach(k=>{ if(!allK.includes(k)) allK.push(k); }));
                const row1=[{name:'Wilayah', span:1, rowspan:3}]; allK.forEach(k=>{ let span=0; processed.forEach(s=>{ if(s.klasifikasis.includes(k)) span += s.tahuns.length; }); row1.push({name:k, span, rowspan:1}); }); headers.push(row1);
                const row2=[]; allK.forEach(k=>{ processed.forEach(s=>{ if(s.klasifikasis.includes(k)) row2.push({name:s.variabel, span:s.tahuns.length, rowspan:1}); }); }); headers.push(row2);
                const row3=[]; allK.forEach(k=>{ processed.forEach(s=>{ if(s.klasifikasis.includes(k)) s.tahuns.forEach(t=> row3.push({name:String(t), span:1, rowspan:1})); }); }); headers.push(row3);
                return headers;
            },
            generateTipe3Headers(processed){ // Tahun » Variabel » Klasifikasi
                const headers=[]; const allT=[]; processed.forEach(s=> s.tahuns.forEach(t=>{ if(!allT.includes(t)) allT.push(t); })); allT.sort((a,b)=>a-b);
                const row1=[{name:'Wilayah', span:1, rowspan:3}]; allT.forEach(t=>{ let span=0; processed.forEach(s=>{ if(s.tahuns.includes(t)) span += s.klasifikasis.length; }); row1.push({name:String(t), span, rowspan:1}); }); headers.push(row1);
                const row2=[]; allT.forEach(t=>{ processed.forEach(s=>{ if(s.tahuns.includes(t)) row2.push({name:s.variabel, span:s.klasifikasis.length, rowspan:1}); }); }); headers.push(row2);
                const row3=[]; allT.forEach(t=>{ processed.forEach(s=>{ if(s.tahuns.includes(t)) s.klasifikasis.forEach(k=> row3.push({name:k, span:1, rowspan:1})); }); }); headers.push(row3);
                return headers;
            },

            // Chart rendering: one dataset per column (bottom header row)
            renderChart(){
                const canvas = document.getElementById('dynamicChartLahan'); if(!canvas) return; const ctx = canvas.getContext('2d');
                if(window.myChartLahan){ window.myChartLahan.destroy(); }
                const labels = this.dynamicRows.map(r=>r.wilayah);
                const bottom = (this.dynamicHeaders[this.dynamicHeaders.length-1]||[]).filter(h=>h.name!=='Wilayah');
                const columnLabels = bottom.map(h=>h.name);
                const datasets=[]; const colors=['rgba(54,162,235,.8)','rgba(255,99,132,.8)','rgba(75,192,192,.8)','rgba(255,206,86,.8)','rgba(153,102,255,.8)','rgba(255,159,64,.8)','rgba(201,203,207,.8)'];
                if(this.dynamicRows.length>0){
                    columnLabels.forEach((label, i)=>{
                        const data = this.dynamicRows.map(r=>{ const v=r.values?.[i]; return v!=null? Number(v):0; });
                        const c = colors[i%colors.length];
                        datasets.push({ label, data, backgroundColor:c, borderColor:c.replace('.8', '1'), borderWidth:1 });
                    });
                }
                const scrollWrap = document.getElementById('chart-scroll-lahan'); const cw = scrollWrap? scrollWrap.clientWidth:800; const ch = scrollWrap? scrollWrap.clientHeight:384; const per= Math.max(70,(datasets.length||1)*18+40); const desired = Math.max(cw, (labels.length||1)*per);
                ctx.canvas.style.width = desired+'px'; ctx.canvas.style.height = ch+'px'; ctx.canvas.width=desired; ctx.canvas.height=ch;
                window.myChartLahan = new Chart(ctx, { type:'bar', data:{ labels, datasets }, options:{ responsive:false, scales:{ y:{ beginAtZero:true } }, plugins:{ legend:{ display:this.showLegend, position:'top' } } } });
            },

            exportExcel(){
                // Client-side export based on current dynamic headers/rows
                const headers = (this.dynamicHeaders[this.dynamicHeaders.length-1]||[]).map(h=>h.name);
                const rows = this.dynamicRows.map(r=> [r.wilayah, ...(r.values||[])]);
                const aoa = [['Wilayah', ...headers.filter(h=>h!=='Wilayah')], ...rows];
                const ws = XLSX.utils.aoa_to_sheet(aoa); const wb = XLSX.utils.book_new(); XLSX.utils.book_append_sheet(wb, ws, 'Lahan'); XLSX.writeFile(wb, 'laporan-lahan.xlsx');
            }
        }
    }
</script>

            <div x-data="lahanForm()" x-init="init(window.lahanInitialData || { topiks:[], variabels:[], klasifikasis:[], tahuns:[], wilayahs:[] })" class="space-y-12">
                <!-- Step 1: Pilih Data -->
                <section class="bg-neutral-50 rounded-lg p-6 border border-neutral-200">
                    <h2 class="text-2xl font-bold text-neutral-800 mb-1 flex items-center">
                        <span class="bg-blue-600 text-white rounded-full h-8 w-8 flex items-center justify-center mr-3">1</span>
                        Pilih Data
                    </h2>
                    <p class="text-neutral-600 mb-4 ml-11">Pilih Topik, Variabel, Klasifikasi, serta Tahun.</p>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
                        <!-- 1.1 Topik -->
                        <div class="bg-white p-4 rounded-lg border h-full">
                            <h3 class="font-semibold text-neutral-900 mb-3">1.1 Topik</h3>
                            <div class="overflow-y-auto border rounded-md p-2 max-h-40">
                                <template x-for="topik in allData.topiks" :key="topik.id">
                                    <div @click="selectTopik(topik.id)" class="flex items-center cursor-pointer hover:bg-blue-50 p-2 rounded-md" :class="{'bg-blue-100 font-semibold': selection.topik_id === topik.id}">
                                        <span class="ml-2 text-sm text-neutral-700" x-text="topik.nama"></span>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <!-- 1.2 Variabel & 1.3 Klasifikasi -->
                        <div class="space-y-4 h-full flex flex-col">
                            <div class="bg-white p-4 rounded-lg border flex-1 flex flex-col">
                                <h3 class="font-semibold text-neutral-900 mb-3">1.2 Variabel</h3>
                                <div x-show="!selection.topik_id" class="text-center py-8 text-neutral-500">Pilih topik terlebih dahulu</div>
                                <div x-show="selection.topik_id" class="overflow-y-auto border rounded-md p-2 max-h-40">
                                    <template x-for="v in filteredVariabel" :key="v.id">
                                        <div @click="selectVariabel(v.id)" class="flex items-center cursor-pointer hover:bg-blue-50 p-2 rounded-md" :class="{'bg-blue-100 font-semibold': selection.variabel_id === v.id}">
                                            <span class="ml-2 text-sm text-neutral-700" x-text="v.nama"></span>
                                            <span class="ml-auto text-xs text-neutral-500" x-text="v.satuan? '('+v.satuan+')':''"></span>
                                        </div>
                                    </template>
                                </div>
                            </div>

                            <div class="bg-white p-4 rounded-lg border flex-1 flex flex-col">
                                <h3 class="font-semibold text-neutral-900 mb-3">1.3 Klasifikasi</h3>
                                <div x-show="!selection.variabel_id" class="text-center py-8 text-neutral-500">Pilih variabel terlebih dahulu</div>
                                <div x-show="selection.variabel_id" class="overflow-y-auto border rounded-md p-2 max-h-40">
                                    <template x-for="k in filteredKlasifikasi" :key="k.id">
                                        <label class="flex items-center p-2 rounded-md hover:bg-blue-50 cursor-pointer">
                                            <input type="checkbox" class="mr-2" :value="k.id" x-model="selection.klasifikasi_ids">
                                            <span class="text-sm text-neutral-700" x-text="k.nama"></span>
                                        </label>
                                    </template>
                                </div>
                            </div>
                        </div>
                        <!-- 1.4 Tahun -->
                        <div class="bg-white p-4 rounded-lg border h-full flex flex-col">
                            <h3 class="font-semibold text-neutral-900 mb-3">1.4 Waktu (Tahun)</h3>
                            <div class="flex items-center justify-between mb-2">
                                <span class="font-medium text-neutral-800">Tahun</span>
                                <div class="text-sm">
                                    <button class="text-blue-600 hover:underline mr-3" @click="selection.tahun_ids = [...allData.tahuns]">Select All</button>
                                    <button class="text-red-500 hover:underline" @click="selection.tahun_ids = []">Clear</button>
                                </div>
                            </div>
                            <input type="text" class="w-full mb-2 border rounded-md px-3 py-2" placeholder="Cari tahun..." x-model="search.tahun">
                            <div class="overflow-y-auto border rounded-md p-2 max-h-48 flex-1">
                                <template x-for="t in filteredTahun" :key="'th-'+t">
                                    <label class="flex items-center p-2 rounded-md hover:bg-blue-50 cursor-pointer">
                                        <input type="checkbox" class="mr-2" :value="t" x-model="selection.tahun_ids">
                                        <span class="text-sm text-neutral-700" x-text="t"></span>
                                    </label>
                                </template>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center justify-between mb-4 mt-6">
                        <div class="flex items-center gap-2">
                            <button @click="addSelection" :disabled="!isSelectionValid()" class="bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-600 disabled:bg-green-300">+ Tambah</button>
                            <button @click="removeSelection" :disabled="selectedForRemoval.length===0" class="bg-rose-400 text-white px-4 py-2 rounded-md hover:bg-rose-500 disabled:bg-rose-300">- Hapus</button>
                        </div>
                        <button @click="resetForm" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400">Set Ulang</button>
                    </div>

                    <!-- Data Terpilih -->
                    <div class="bg-white p-4 rounded-lg border">
                        <h3 class="font-semibold text-neutral-900 mb-3">Data Terpilih</h3>
                        <div class="space-y-2" x-show="selections.length>0">
                            <template x-for="item in selections" :key="item.id">
                                <label class="flex items-center p-2 rounded-md border">
                                    <input type="checkbox" class="mr-2" :value="item.id" x-model="selectedForRemoval">
                                    <div class="flex-1">
                                        <div class="font-medium" x-text="item.variabel_nama + (item.variabel_satuan? ' ('+item.variabel_satuan+')':'')"></div>
                                        <div class="text-sm text-neutral-600" x-text="'Klasifikasi: ' + item.klasifikasi_nama"></div>
                                        <div class="text-sm text-neutral-600" x-text="'Tahun: ' + item.tahun_awal + ' – ' + item.tahun_akhir"></div>
                                    </div>
                                </label>
                            </template>
                        </div>
                        <div x-show="selections.length===0" class="text-neutral-500">Belum ada data yang ditambahkan.</div>
                    </div>
                </section>

                <!-- Step 2: Konfigurasi Tampilan -->
                <section class="bg-neutral-50 rounded-lg p-6 border border-neutral-200">
                    <h2 class="text-2xl font-bold text-neutral-800 mb-1 flex items-center">
                        <span class="bg-blue-600 text-white rounded-full h-8 w-8 flex items-center justify-center mr-3">2</span>
                        Konfigurasi Tampilan
                    </h2>
                    <p class="text-neutral-600 mb-6 ml-11">Pilih wilayah dan bagaimana data akan disajikan dalam tabel.</p>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Wilayah -->
                        <div class="bg-white p-4 rounded-lg border">
                            <h3 class="font-semibold text-neutral-900 mb-3">2.1 Wilayah</h3>
                            <div class="mb-3">
                                <select class="border rounded-md px-3 py-2" x-model="wilayahLevel">
                                    <option value="nasional">Tingkat Nasional (Provinsi)</option>
                                    <option value="provinsi">Tingkat Provinsi (Kabupaten/Kota)</option>
                                </select>
                            </div>
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm text-neutral-600">Pilih Wilayah</span>
                                <div class="text-sm" x-show="wilayahLevel==='nasional'">
                                    <button class="text-blue-600 hover:underline mr-3" @click="selection.provinsi_ids = filteredProvinsi.map(p=>p.id)">Select All</button>
                                    <button class="text-red-500 hover:underline" @click="selection.provinsi_ids = []">Clear</button>
                                </div>
                            </div>
                            <input type="text" class="w-full mb-2 border rounded-md px-3 py-2" placeholder="Cari provinsi..." x-model="search.wilayah" x-show="wilayahLevel==='nasional'">

                            <!-- Provinsi list -->
                            <div x-show="wilayahLevel==='nasional'" class="overflow-y-auto border rounded-md p-2 max-h-72">
                                <template x-for="p in filteredProvinsi" :key="p.id">
                                    <label class="flex items-center p-2 rounded-md hover:bg-blue-50 cursor-pointer">
                                        <input type="checkbox" class="mr-2" :value="p.id" x-model="selection.provinsi_ids">
                                        <span class="text-sm" x-text="p.nama"></span>
                                    </label>
                                </template>
                            </div>

                            <!-- Kabupaten list -->
                            <div x-show="wilayahLevel==='provinsi'">
                                <div class="mb-2">
                                    <label class="block text-sm mb-1">Pilih Provinsi</label>
                                    <select class="border rounded-md px-3 py-2 w-full" x-model="selectedProvinsiId">
                                        <option :value="null">-- Pilih Provinsi --</option>
                                        <template x-for="p in provinsis" :key="'sel-'+p.id">
                                            <option :value="p.id" x-text="p.nama"></option>
                                        </template>
                                    </select>
                                </div>
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-sm text-neutral-600">Kabupaten/Kota</span>
                                    <div class="text-sm">
                                        <button class="text-blue-600 hover:underline mr-3" @click="selectAllKabupaten()">Select All</button>
                                        <button class="text-red-500 hover:underline" @click="clearKabupaten()">Clear</button>
                                    </div>
                                </div>
                                <div class="overflow-y-auto border rounded-md p-2 max-h-72">
                                    <template x-for="k in kabupatenOfSelectedProvinsi" :key="'kab-'+k.id">
                                        <label class="flex items-center p-2 rounded-md hover:bg-blue-50 cursor-pointer">
                                            <input type="checkbox" class="mr-2" :value="k.id" x-model="selection.kabupaten_ids">
                                            <span class="text-sm" x-text="k.nama"></span>
                                        </label>
                                    </template>
                                </div>
                            </div>
                        </div>

                        <!-- Tata Letak Tabel -->
                        <div class="bg-white p-4 rounded-lg border">
                            <h3 class="font-semibold text-neutral-900 mb-3">2.2 Tata Letak Tabel</h3>
                            <div class="space-y-4">
                                <!-- Tipe 1 Preview -->
                                <label class="block p-3 border rounded-lg cursor-pointer table-layout-radio" :class="{'ring-2 ring-blue-500': selection.tata_letak==='tipe_1'}">
                                    <div class="flex items-start gap-3">
                                        <input type="radio" class="mt-1" value="tipe_1" x-model="selection.tata_letak">
                                        <div class="flex-1">
                                            <div class="font-medium mb-1">Tipe 1: Master Header Variabel</div>
                                            <div class="text-sm text-neutral-600 mb-3">Kolom diurutkan: <strong>Variabel</strong> » <strong>Klasifikasi</strong> » <strong>Tahun</strong></div>
                                            <div class="preview-card table-preview">
                                                <div class="preview-scroll">
                                                    <table class="preview-table">
                                                        <thead>
                                                            <tr>
                                                                <th rowspan="3">Wilayah</th>
                                                                <th colspan="6">Var A</th>
                                                            </tr>
                                                            <tr>
                                                                <th colspan="3">Klas X</th>
                                                                <th colspan="3">Klas Y</th>
                                                            </tr>
                                                            <tr>
                                                                <th>2019</th><th>2020</th><th>2021</th>
                                                                <th>2019</th><th>2020</th><th>2021</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr><td>Prov. A</td><td>…</td><td>…</td><td>…</td><td>…</td><td>…</td><td>…</td></tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </label>

                                <!-- Tipe 2 Preview -->
                                <label class="block p-3 border rounded-lg cursor-pointer table-layout-radio" :class="{'ring-2 ring-blue-500': selection.tata_letak==='tipe_2'}">
                                    <div class="flex items-start gap-3">
                                        <input type="radio" class="mt-1" value="tipe_2" x-model="selection.tata_letak">
                                        <div class="flex-1">
                                            <div class="font-medium mb-1">Tipe 2: Master Header Klasifikasi</div>
                                            <div class="text-sm text-neutral-600 mb-3">Kolom diurutkan: <strong>Klasifikasi</strong> » <strong>Variabel</strong> » <strong>Tahun</strong></div>
                                            <div class="preview-card table-preview">
                                                <div class="preview-scroll">
                                                    <table class="preview-table">
                                                        <thead>
                                                            <tr>
                                                                <th rowspan="3">Wilayah</th>
                                                                <th colspan="6">Klas X</th>
                                                            </tr>
                                                            <tr>
                                                                <th colspan="3">Var A</th>
                                                                <th colspan="3">Var B</th>
                                                            </tr>
                                                            <tr>
                                                                <th>2019</th><th>2020</th><th>2021</th>
                                                                <th>2019</th><th>2020</th><th>2021</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr><td>Prov. A</td><td>…</td><td>…</td><td>…</td><td>…</td><td>…</td><td>…</td></tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </label>

                                <!-- Tipe 3 Preview -->
                                <label class="block p-3 border rounded-lg cursor-pointer table-layout-radio" :class="{'ring-2 ring-blue-500': selection.tata_letak==='tipe_3'}">
                                    <div class="flex items-start gap-3">
                                        <input type="radio" class="mt-1" value="tipe_3" x-model="selection.tata_letak">
                                        <div class="flex-1">
                                            <div class="font-medium mb-1">Tipe 3: Master Header Waktu</div>
                                            <div class="text-sm text-neutral-600 mb-3">Kolom diurutkan: <strong>Tahun</strong> » <strong>Variabel</strong> » <strong>Klasifikasi</strong></div>
                                            <div class="preview-card table-preview">
                                                <div class="preview-scroll">
                                                    <table class="preview-table">
                                                        <thead>
                                                            <tr>
                                                                <th rowspan="3">Wilayah</th>
                                                                <th colspan="6">2020</th>
                                                            </tr>
                                                            <tr>
                                                                <th colspan="3">Var A</th>
                                                                <th colspan="3">Var B</th>
                                                            </tr>
                                                            <tr>
                                                                <th>K X</th><th>K Y</th><th>K Z</th>
                                                                <th>K X</th><th>K Y</th><th>K Z</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr><td>Prov. A</td><td>…</td><td>…</td><td>…</td><td>…</td><td>…</td><td>…</td></tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end mt-8">
                        <button @click.prevent="fetchData" :disabled="isProcessing" class="w-full bg-blue-600 text-white font-bold py-3 px-4 rounded-md hover:bg-blue-700 disabled:bg-blue-300 flex items-center justify-center disabled:cursor-not-allowed">
                            <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Tampilkan Hasil
                        </button>
                    </div>
                </section>

                <!-- Loading overlay -->
                <div x-show="isProcessing" class="fixed inset-0 flex items-center justify-center" style="z-index:10000; backdrop-filter: blur(8px); background-color: rgba(255,255,255,.3);">
                    <div class="bg-white rounded-lg p-8 text-center shadow-2xl border border-neutral-200">
                        <svg class="animate-spin w-12 h-12 text-blue-500 mx-auto mb-4" fill="none" viewBox="0 0 24 24"></svg>
                        <h3 class="text-lg font-medium text-neutral-900 mb-2">Memproses Data...</h3>
                        <p class="text-neutral-700">Mohon tunggu, kami sedang menyiapkan laporan untuk Anda.</p>
                    </div>
                </div>

                <!-- Step 3: Hasil -->
                <section x-show="storedResults.length>0" class="bg-neutral-50 rounded-lg p-6 border border-neutral-200 mt-12">
                    <h2 class="text-2xl font-bold text-neutral-800 mb-1 flex items-center">
                        <span class="bg-blue-600 text-white rounded-full h-8 w-8 flex items-center justify-center mr-3">3</span>
                        Tampilan Hasil
                    </h2>
                    <p class="text-neutral-600 mb-6 ml-11">Hasil dari data yang telah Anda pilih.</p>

                    <div class="flex gap-6">
                        <!-- Left: result list -->
                        <div class="w-48 flex-shrink-0">
                            <div class="text-sm text-neutral-500 mb-2">Hasil Pencarian</div>
                            <div class="space-y-2">
                                <template x-for="(res, idx) in storedResults" :key="res.id">
                                    <button @click="selectedResultIndex=idx; activeResultTab='tabel'; $nextTick(()=>renderChart());" class="w-full text-left px-3 py-2 rounded-md" :class="{'bg-blue-600 text-white': selectedResultIndex===idx, 'bg-white border text-neutral-700': selectedResultIndex!==idx}">
                                        <div class="font-medium" x-text="'Hasil ' + (idx+1)"></div>
                                        <div class="text-xs opacity-80" x-text="res.timestamp"></div>
                                    </button>
                                </template>
                            </div>
                        </div>

                        <!-- Right: content -->
                        <div class="flex-1 main-content-flex">
                            <div class="flex items-center justify-between mb-4">
                                <div class="tabs-nav">
                                    <nav class="-mb-px flex space-x-6 items-center">
                                        <button class="tab-link flex items-center gap-2" :class="{ 'active': activeResultTab==='tabel' }" @click="activeResultTab='tabel'">
                                            <span class="inline-block w-4 h-4 rounded-sm bg-blue-500 opacity-70"></span>
                                            <span>Tabel</span>
                                        </button>
                                        <button class="tab-link flex items-center gap-2" :class="{ 'active': activeResultTab==='grafik' }" @click="activeResultTab='grafik'; $nextTick(()=>renderChart());">
                                            <span class="inline-block w-4 h-4 rounded-sm bg-emerald-500 opacity-70"></span>
                                            <span>Grafik</span>
                                        </button>
                                        <button class="tab-link flex items-center gap-2" :class="{ 'active': activeResultTab==='metodologi' }" @click="activeResultTab='metodologi'">
                                            <span class="inline-block w-4 h-4 rounded-sm bg-gray-400 opacity-70"></span>
                                            <span>Metodologi</span>
                                        </button>
                                    </nav>
                                </div>
                                <div class="flex items-center gap-2">
                                    <button @click="exportExcel" class="bg-green-600 hover:bg-green-700 text-white px-3 py-2 rounded-md">Export Excel</button>
                                </div>
                            </div>

                            <!-- Tabel -->
                            <div x-show="activeResultTab==='tabel'" class="table-tab-content">
                                <h4 class="text-sm font-medium text-neutral-700 mb-2">Data Tabel</h4>
                                <div class="sticky-table-container" x-data x-init="(() => { const container = $el; const table = container.querySelector('table'); if(!table) return; const recalc = () => { const theadRows = Array.from(table.tHead?.rows||[]); let acc=0; theadRows.forEach((tr,idx)=>{ const h=tr.getBoundingClientRect().height; acc += h; container.style.setProperty(`--row-top-${idx+1}`, (idx===0?0:acc-h)+'px'); }); }; recalc(); new ResizeObserver(recalc).observe(table); })()">
                                    <table class="sticky-table">
                                        <thead>
                                            <template x-for="(headerRow, rowIndex) in dynamicHeaders" :key="'hrow-'+rowIndex">
                                                <tr>
                                                    <template x-for="(h, colIndex) in headerRow" :key="'hcell-'+rowIndex+'-'+colIndex">
                                                        <th :colspan="h.span" :rowspan="h.rowspan" :class="{'sticky-wilayah-header': h.name==='Wilayah'}" :data-row-index="rowIndex+1" x-text="h.name"></th>
                                                    </template>
                                                </tr>
                                            </template>
                                        </thead>
                                        <tbody>
                                            <template x-for="(row, rIdx) in dynamicRows" :key="'row-'+rIdx">
                                                <tr>
                                                    <td x-text="row.wilayah"></td>
                                                    <template x-for="(val, cIdx) in row.values" :key="'c-'+cIdx">
                                                        <td x-text="val===null||val===undefined? '-' : Number(val).toFixed(2)"></td>
                                                    </template>
                                                </tr>
                                            </template>
                                        </tbody>
                                    </table>
                                </div>
                                <div x-show="dynamicRows.length===0" class="text-center text-neutral-500 py-8">Tidak ada data untuk ditampilkan.</div>
                            </div>

                            <!-- Grafik -->
                            <div x-show="activeResultTab==='grafik'">
                                <div class="flex items-center gap-3 mb-3">
                                    <label class="flex items-center gap-2 text-sm"><input type="checkbox" x-model="showLegend" @change="renderChart"> Tampilkan legenda</label>
                                </div>
                                <div id="chart-scroll-lahan" class="overflow-x-auto border rounded-md" style="height: 420px;">
                                    <canvas id="dynamicChartLahan"></canvas>
                                </div>
                            </div>

                            <!-- Metodologi -->
                            <div x-show="activeResultTab==='metodologi'" class="bg-white p-4 rounded-lg border">
                                @include('pertanian.partials.metodologi-lahan')
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // reserved for future enhancements
    </script>
    @endpush
</x-layouts.landing>
