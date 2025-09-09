<x-layouts.landing title="Laporan Data Benih dan Pupuk">
    <!-- Add Chart.js for data visualization -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Add SheetJS library for Excel export -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
    
    <!-- Custom CSS for enhanced radio buttons and table layouts -->
    <style>
        .table-layout-radio:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        
        .table-preview {
            transition: all 0.2s ease-in-out;
        }
        
        .table-layout-radio:hover .table-preview {
            transform: scale(1.02);
        }
        
        .preview-table th, .preview-table td {
            font-size: 10px;
            padding: 4px 6px;
        }

        /* ======================================================= */
        /* == KODE CSS FINAL UNTUK TABEL HEADER BERTINGKAT == */
        /* ======================================================= */

        /* Container parent untuk membatasi overflow */
        .table-tab-content {
            width: 100%;
            max-width: 100%;
            overflow: hidden;
            position: relative;
        }

        /* Tambahan CSS untuk memastikan tidak ada element yang keluar dari container */
        .table-tab-content * {
            box-sizing: border-box;
        }

        /* CSS untuk flex container utama */
        .main-content-flex {
            overflow: hidden;
            max-width: 100%;
        }

        .sticky-table-container {
            position: relative;
            /* FIX 1: Mengatasi overflow dengan scrollbar horizontal & vertikal */
            overflow: auto;
            max-height: 500px; /* Atur tinggi maksimal tabel sebelum scroll */
            max-width: 100%;
            width: 100%;
            border-radius: 0.5rem;
            border: 1px solid #e5e7eb;
            /* Tambahan untuk memastikan container tidak melampaui parent */
            box-sizing: border-box;
            /* Tambahan constraint untuk memaksa scroll horizontal */
            contain: layout paint;
            /* Promote to its own layer for smoother scrolling */
            will-change: transform;
            transform: translateZ(0);
        }

        .sticky-table {
            /* FIX 3: Mengubah cara render border agar tidak hilang saat scroll */
            border-collapse: separate;
            border-spacing: 0;
            /* Menggunakan min-width untuk memastikan tabel bisa scroll horizontal */
            width: 100%;
            min-width: max-content;
            /* Menghapus table-layout fixed agar kolom bisa auto-size */
            table-layout: auto;
        }

        /* Mengatur border & padding individual untuk setiap sel */
        .sticky-table th,
        .sticky-table td {
            border-bottom: 1px solid #e5e7eb;
            border-right: 1px solid #e5e7eb;
            white-space: nowrap;
            padding: 0.5rem 0.75rem;
            text-align: center;
            vertical-align: middle;
            /* Tambahan untuk memastikan border konsisten */
            box-sizing: border-box;
        }
        
        .sticky-table td {
            text-align: right;
        }
        .sticky-table th:first-child,
        .sticky-table td:first-child {
            text-align: left;
        }

        /* Hapus border kanan di kolom paling akhir agar rapi */
        .sticky-table th:last-child,
        .sticky-table td:last-child {
            border-right: none;
        }


        /* === LOGIKA STICKY YANG SUDAH DISEMPURNAKAN === */

        /* Pengaturan umum untuk semua header di <thead> */
        .sticky-table thead th {
            position: sticky;
            background-color: #f9fafb;
            z-index: 2; /* keep minimal to reduce compositing cost */
            will-change: top;
            backface-visibility: hidden;
            /* Memastikan border tetap konsisten saat sticky */
            border-bottom: 1px solid #e5e7eb;
            border-right: 1px solid #e5e7eb;
        }

        /* PERBAIKAN: CSS untuk memastikan border tidak hilang saat scroll */
        .sticky-table thead th::after {
            content: '';
            position: absolute;
            bottom: 0;
            right: 0;
            left: 0;
            height: 1px;
            background-color: #e5e7eb;
            z-index: 1;
        }

        .sticky-table thead th::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            bottom: 0;
            width: 1px;
            background-color: #e5e7eb;
            z-index: 1;
        }

        /* CSS khusus untuk header Wilayah yang sticky */
        .sticky-table .sticky-wilayah-header {
            position: sticky !important;
            left: 0 !important;
            background-color: #f9fafb !important;
            z-index: 4 !important; /* slightly above other headers */
            border-right: 1px solid #e5e7eb !important;
            font-weight: 600 !important;
            /* Remove shadow to reduce paint work */
            box-shadow: none !important;
            will-change: left, top;
            backface-visibility: hidden;
        }

        /* CSS untuk kolom data wilayah (kolom pertama dalam tbody) */
        .sticky-table tbody td:first-child {
            position: sticky !important;
            left: 0 !important;
            background-color: #ffffff !important;
            z-index: 1 !important; /* under headers */
            font-weight: 500 !important;
            border-right: 1px solid #e5e7eb !important;
            box-shadow: none !important;
            will-change: left;
            backface-visibility: hidden;
        }

        /* FIX 2 (Dinamis): posisi 'top' tiap baris header memakai CSS variables */
        /* Variabel akan di-set via JS berdasarkan tinggi aktual tiap baris thead */
        .sticky-table-container { 
            --row-top-1: 0px; 
            --row-top-2: 0px; 
            --row-top-3: 0px; 
            --row-top-4: 0px; 
            --row-top-5: 0px; 
            --row-top-6: 0px; 
            --row-top-7: 0px; 
            --row-top-8: 0px; 
        }
        .sticky-table thead th[data-row-index="1"] { top: var(--row-top-1, 0px); }
        .sticky-table thead th[data-row-index="2"] { top: var(--row-top-2, 0px); }
        .sticky-table thead th[data-row-index="3"] { top: var(--row-top-3, 0px); }
        .sticky-table thead th[data-row-index="4"] { top: var(--row-top-4, 0px); }
        .sticky-table thead th[data-row-index="5"] { top: var(--row-top-5, 0px); }
        .sticky-table thead th[data-row-index="6"] { top: var(--row-top-6, 0px); }
        .sticky-table thead th[data-row-index="7"] { top: var(--row-top-7, 0px); }
        .sticky-table thead th[data-row-index="8"] { top: var(--row-top-8, 0px); }

        /* Pastikan header Wilayah mengikuti barisnya sendiri */
    .sticky-table .sticky-wilayah-header[data-row-index="1"] { top: var(--row-top-1, 0px) !important; }
    .sticky-table .sticky-wilayah-header[data-row-index="2"] { top: var(--row-top-2, 0px) !important; }
    .sticky-table .sticky-wilayah-header[data-row-index="3"] { top: var(--row-top-3, 0px) !important; }
    .sticky-table .sticky-wilayah-header[data-row-index="4"] { top: var(--row-top-4, 0px) !important; }
    .sticky-table .sticky-wilayah-header[data-row-index="5"] { top: var(--row-top-5, 0px) !important; }
    .sticky-table .sticky-wilayah-header[data-row-index="6"] { top: var(--row-top-6, 0px) !important; }
    .sticky-table .sticky-wilayah-header[data-row-index="7"] { top: var(--row-top-7, 0px) !important; }
    .sticky-table .sticky-wilayah-header[data-row-index="8"] { top: var(--row-top-8, 0px) !important; }

        /* Fallback untuk header kolom pertama yang bukan header Wilayah */
        .sticky-table thead th:first-child:not(.sticky-wilayah-header) {
            position: sticky;
            left: 0;
            background-color: #f9fafb;
            z-index: 80;
            border-right: 1px solid #e5e7eb;
            box-shadow: 1px 0 2px rgba(0, 0, 0, 0.06);
        }


        /* Efek hover agar lebih jelas */
        .sticky-table tbody tr:hover td {
            background-color: #f3f4f6;
        }
        .sticky-table tbody tr:hover td:first-child {
            background-color: #eff6ff; /* Warna hover berbeda untuk kolom sticky */
        }
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
                            <svg class="w-6 h-6 text-neutral-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="ml-1 text-neutral-500">Pertanian</span>
                        </div>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-neutral-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="ml-1 text-blue-600 font-medium">Laporan Benih & Pupuk</span>
                        </div>
                    </li>
                </ol>
            </nav>

            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-3xl md:text-4xl font-bold text-neutral-900 mb-4">
                    Laporan Data Benih dan Pupuk
                </h1>
                <p class="text-xl text-neutral-600">
                    Cari dan analisis data ketersediaan benih dan pupuk pertanian di Indonesia
                </p>
            </div>

            <!-- Two Column Layout -->
<script>
    const benihPupukInitialData = {
        topiks: @json($topiks),
        variabels: @json($variabels),
        klasifikasis: @json($klasifikasis),
        tahuns: @json($tahuns),
        bulans: @json($bulans),
        wilayahs: @json($wilayahs)
    };

    function benihPupukForm() {
        return {
            init(data) {
                this.allData = data;
                this.$watch('wilayahLevel', () => {
                    // Reset selections when switching levels
                    this.selection.provinsi_ids = [];
                    this.selection.kabupaten_ids = [];
                    this.selectedProvinsiId = null;
                });
                this.$watch('selectedProvinsiId', () => {
                    // Reset kabupaten selection when province changes
                    this.selection.kabupaten_ids = [];
                });
            },
            // Data sources from Controller
            allData: {
                topiks: [],
                variabels: [],
                klasifikasis: [],
                tahuns: [],
                bulans: [],
                wilayahs: []
            },

            // Form state
            selection: {
                topik_id: null,
                variabel_id: null,
                klasifikasi_ids: [],
                tahun_ids: [],
                bulan_ids: [],
                provinsi_ids: [],
                kabupaten_ids: [],
                tata_letak: 'master-header-variabel',
                wilayah: {
                    tingkat: 'nasional',
                    provinsi: [],
                    kabupaten: [],
                    selected_provinsi: null,
                },
            },

            wilayahLevel: 'nasional', // 'nasional' or 'provinsi'
            selectedProvinsiId: null,

            // Search inputs
            search: {
                topik: '',
                variabel: '',
                klasifikasi: '',
                tahun: '',
                bulan: '',
                wilayah: '',
            },

            // UI state
            isProcessing: false,
            activeTab: 'tabel',
            activeResultTab: 'tabel',
            selections: [],
            selectedForRemoval: [],
            searchResults: [],
            storedResults: [],
            selectedResultIndex: null,
            // Grafik helpers
            selectedProvinceForScroll: null,
            showLegend: false,

            // Methods
            selectTopik(id) {
                this.selection.topik_id = id;
                this.selection.variabel_id = null;
                this.selection.klasifikasi_ids = [];
            },

            selectVariabel(id) {
                this.selection.variabel_id = id;
                this.selection.klasifikasi_ids = [];
            },

                        isSelectionValid() {
                const hasKlasifikasiOptions = this.filteredKlasifikasi.length > 0;
                const isKlasifikasiValid = !hasKlasifikasiOptions || (hasKlasifikasiOptions && this.selection.klasifikasi_ids.length > 0);

                return this.selection.topik_id &&
                    this.selection.variabel_id &&
                    this.selection.tahun_ids.length > 0 &&
                    this.selection.bulan_ids.length > 0 &&
                    isKlasifikasiValid;
            },

            addSelection() {
                if (!this.isSelectionValid()) return;

                const topik = this.allData.topiks.find(t => t.id === this.selection.topik_id);
                const variabel = this.allData.variabels.find(v => v.id === this.selection.variabel_id);
                
                const tahun_awal = Math.min(...this.selection.tahun_ids);
                const tahun_akhir = Math.max(...this.selection.tahun_ids);
                const bulan_awal = this.allData.bulans.find(b => b.id == Math.min(...this.selection.bulan_ids))?.nama || '';
                const bulan_akhir = this.allData.bulans.find(b => b.id == Math.max(...this.selection.bulan_ids))?.nama || '';

                const newSelection = {
                    id: Date.now(),
                    topik_id: this.selection.topik_id,
                    variabel_id: this.selection.variabel_id,
                    klasifikasi_ids: [...this.selection.klasifikasi_ids],
                    tahun_ids: [...this.selection.tahun_ids],
                    bulan_ids: [...this.selection.bulan_ids],
                    display: {
                        variabel_nama: variabel ? `${topik.nama} - ${variabel.nama}` : topik.nama,
                                                klasifikasi_nama: this.selection.klasifikasi_ids.length > 0 
                            ? this.allData.klasifikasis
                                                                .filter(k => this.selection.klasifikasi_ids.includes(String(k.id)))
                                .map(k => k.nama)
                                .join(', ')
                            : 'Semua',
                        tahun: this.selection.tahun_ids.length > 1 ? `${tahun_awal} - ${tahun_akhir}` : tahun_awal,
                        bulan: this.selection.bulan_ids.length > 1 ? `${bulan_awal} - ${bulan_akhir}` : bulan_awal
                    }
                };

                                this.selections.push(newSelection);
                this.resetSelection();
            },

            removeSelection() {
                // Convert IDs for removal to numbers for strict comparison
                const idsToRemove = this.selectedForRemoval.map(Number);
                // Filter the array by creating a new one, which is a robust way to trigger reactivity
                this.selections = this.selections.filter(item => !idsToRemove.includes(item.id));
                // Clear the selection
                this.selectedForRemoval = [];
            },

            resetSelection() {
                this.selection.topik_id = null;
                this.selection.variabel_id = null;
                this.selection.klasifikasi_ids = [];
                this.selection.tahun_ids = [];
                this.selection.bulan_ids = [];
            },

            resetForm() {
                this.selection = {
                    topik_id: null,
                    variabel_id: null,
                    klasifikasi_ids: [],
                    tahun_ids: [],
                    bulan_ids: [],
                    provinsi_ids: [],
                    kabupaten_ids: [],
                    tata_letak: 'data-series',
                    wilayah: {
                        tingkat: 'nasional',
                        provinsi: [],
                        kabupaten: [],
                        selected_provinsi: null,
                    },
                };
                this.selections = [];
                this.searchResults = { data: [], columnOrder: [], config: {} };
                this.selectedForRemoval = [];
                this.wilayahLevel = 'nasional';
                this.selectedProvinsiId = null;
            },

            selectStoredResult(index) {
                this.selectedResultIndex = index;
                this.searchResults = this.storedResults[index].results;
                // Re-render chart if grafik tab is active
                if (this.activeResultTab === 'grafik') {
                    this.$nextTick(() => this.renderChart());
                }
            },

            async fetchData() {
                if (this.selections.length === 0) {
                    alert('Silakan tambahkan data terlebih dahulu.');
                    return;
                }
                this.isProcessing = true;
                this.searchResults = { data: [], columnOrder: [], config: {} }; // Reset results

                try {
                    const payload = {
                        selections: this.selections.map(s => ({
                            variabel_id: s.variabel_id,
                            tahun_ids: s.tahun_ids,
                            klasifikasi_ids: s.klasifikasi_ids,
                            bulan_ids: s.bulan_ids,
                        })),
                        config: {
                            tata_letak: this.selection.tata_letak,
                            provinsi_ids: this.selection.provinsi_ids,
                            kabupaten_ids: this.selection.kabupaten_ids,
                        }
                    };

                    console.log('Sending payload:', payload);
                    console.log('Selection state:', this.selection);

                    const response = await fetch('{{ route('api.benih-pupuk.filter') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify(payload)
                    });

                    if (!response.ok) {
                        const errorData = await response.json();
                        console.error('Server error:', errorData);
                        throw new Error(errorData.message || 'Network response was not ok');
                    }

                    const results = await response.json();
                    console.log('Received results:', results);
                    this.searchResults = results;
                    
                    // Store result with timestamp and selections info
                    const resultData = {
                        id: Date.now(),
                        timestamp: new Date().toLocaleString('id-ID'),
                        results: results,
                        selections: [...this.selections],
                        config: { ...this.selection }
                    };
                    this.storedResults.push(resultData);
                    this.selectedResultIndex = this.storedResults.length - 1;
                    // Optional: render chart if you have chart data
                    // this.renderChart(results); 

                } catch (error) {
                    console.error('Fetch error:', error);
                    alert('Terjadi kesalahan saat mengambil data: ' + error.message);
                } finally {
                    this.isProcessing = false;
                }
            },

            renderChart() {
                const currentResult = this.selectedResultIndex !== null ? this.storedResults[this.selectedResultIndex] : null;
                if (!currentResult || !currentResult.results || !currentResult.results.data || currentResult.results.data.length === 0) {
                    return;
                }

                const ctx = document.getElementById('chart-container');
                if (!ctx) return;
                
                const context = ctx.getContext('2d');
                if (window.myChart instanceof Chart) {
                    window.myChart.destroy();
                }

                // Prepare chart data from table data
                const data = currentResult.results.data;
                const labels = data.map(row => row.wilayah);
                // default selected province value
                if (!this.selectedProvinceForScroll && labels.length) {
                    this.selectedProvinceForScroll = labels[0];
                }
                
                // Create datasets for each data column
                const datasets = [];
                const colors = [
                    '#3B82F6', '#EF4444', '#10B981', '#F59E0B', '#8B5CF6',
                    '#06B6D4', '#84CC16', '#F97316', '#EC4899', '#6366F1'
                ];
                
                if (data.length > 0 && data[0].values) {
                    // Get unique combinations from selections for dataset labels
                    const selections = currentResult.selections;
                    let datasetLabels = [];
                    
                    selections.forEach(sel => {
                        const variabel = this.allData.variabels.find(v => v.id === sel.variabel_id);
                        sel.klasifikasi_ids.forEach(kid => {
                            const klasifikasi = this.allData.klasifikasis.find(k => String(k.id) === String(kid));
                            sel.tahun_ids.forEach(tid => {
                                sel.bulan_ids.forEach(bid => {
                                    const bulan = this.allData.bulans.find(b => String(b.id) === String(bid));
                                    if (variabel && klasifikasi && bulan) {
                                        datasetLabels.push(`${variabel.nama} - ${klasifikasi.nama} - ${tid} - ${bulan.nama}`);
                                    }
                                });
                            });
                        });
                    });
                    
                    // Create datasets for each value column
                    data[0].values.forEach((_, valueIndex) => {
                        datasets.push({
                            label: datasetLabels[valueIndex] || `Data ${valueIndex + 1}`,
                            data: data.map(row => parseFloat(row.values[valueIndex]) || 0),
                            backgroundColor: colors[valueIndex % colors.length] + '80',
                            borderColor: colors[valueIndex % colors.length],
                            borderWidth: 1,
                            borderRadius: 4,
                            borderSkipped: false,
                        });
                    });
                }

        // Ensure canvas is wide enough to show all provinces; enables horizontal scroll
        const scrollWrap = document.getElementById('chart-scroll');
        const containerWidth = scrollWrap ? scrollWrap.clientWidth : 800;
        const containerHeight = scrollWrap ? scrollWrap.clientHeight : 384;
        const perLabelWidth = Math.max(70, (datasets.length || 1) * 18 + 40);
        const desiredWidth = Math.max(containerWidth, (labels.length || 1) * perLabelWidth);
        ctx.style.width = desiredWidth + 'px';
        ctx.style.height = containerHeight + 'px';
        ctx.width = desiredWidth; // important when responsive:false
        ctx.height = containerHeight;

        window.myChart = new Chart(context, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: datasets
                    },
                    options: {
            // Use fixed sizing so canvas width drives horizontal scroll
            responsive: false,
                        maintainAspectRatio: false,
                        interaction: {
                            mode: 'index',
                            intersect: false,
                        },
                        plugins: {
                            title: {
                                display: true,
                                text: 'Data Benih dan Pupuk',
                                font: {
                                    size: 16,
                                    weight: 'bold'
                                }
                            },
                            legend: {
                                position: 'top',
                                align: 'start',
                                display: this.showLegend,
                                labels: {
                                    boxWidth: 10,
                                    padding: 10,
                                    font: {
                                        size: 10
                                    }
                                }
                            },
                            tooltip: {
                                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                                titleColor: 'white',
                                bodyColor: 'white',
                                borderColor: 'rgba(255, 255, 255, 0.1)',
                                borderWidth: 1,
                                cornerRadius: 6,
                                displayColors: true,
                                callbacks: {
                                    label: function(context) {
                                        return context.dataset.label + ': ' + context.parsed.y.toLocaleString('id-ID');
                                    }
                                }
                            }
                        },
                        scales: {
                            x: {
                                grid: {
                                    display: false
                                },
                                ticks: {
                                    autoSkip: false,
                                    maxRotation: 45,
                                    minRotation: 0,
                                    font: {
                                        size: 10
                                    }
                                }
                            },
                            y: {
                                beginAtZero: true,
                                grid: {
                                    color: 'rgba(0, 0, 0, 0.1)'
                                },
                                ticks: {
                                    callback: function(value) {
                                        return value.toLocaleString('id-ID');
                                    },
                                    font: {
                                        size: 10
                                    }
                                }
                            }
                        }
                    }
                });
            },

            toggleLegend() {
                this.showLegend = !this.showLegend;
                if (window.myChart instanceof Chart) {
                    window.myChart.options.plugins.legend.display = this.showLegend;
                    window.myChart.update();
                } else {
                    // If chart doesn't exist yet, just render with new state
                    this.$nextTick(() => this.renderChart());
                }
            },

            scrollToProvince() {
                const scrollWrap = document.getElementById('chart-scroll');
                const canvas = document.getElementById('chart-container');
                if (!scrollWrap || !canvas || !(window.myChart instanceof Chart)) return;
                const chart = window.myChart;
                const xScale = chart.scales.x;
                const labels = chart.data.labels || [];
                const target = this.selectedProvinceForScroll;
                const idx = labels.indexOf(target);
                if (idx === -1 || !xScale) return;
                // Get pixel for the center of the bar cluster at index
                const xCenter = xScale.getPixelForValue(idx);
                // Compute ideal scroll so the target is centered; clamp to bounds
                const desired = xCenter - scrollWrap.clientWidth / 2;
                const max = canvas.clientWidth - scrollWrap.clientWidth;
                const next = Math.max(0, Math.min(max, desired));
                scrollWrap.scrollTo({ left: next, behavior: 'smooth' });
            },

            // Keep chart sizing responsive to viewport changes while preserving horizontal scroll
            initChartResizeHandlerOnce: (function() {
                let bound = false;
                return function() {
                    if (bound) return;
                    bound = true;
                    window.addEventListener('resize', () => {
                        const grafikTab = document.querySelector('[x-show*="\'grafik\'"]');
                        const isHidden = grafikTab && (grafikTab.style.display === 'none' || grafikTab.getAttribute('aria-hidden') === 'true');
                        if (!isHidden) {
                            // defer until layout stable
                            setTimeout(() => this.renderChart(), 100);
                        }
                    });
                }
            })(),

            exportExcel() {
                if (this.dynamicRows.length === 0) {
                    alert('Tidak ada data untuk diekspor.');
                    return;
                }

                // Create a hidden form to submit data
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '{{ route('api.benih-pupuk.export') }}';
                form.style.display = 'none';

                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                form.appendChild(csrfInput);

                const headersInput = document.createElement('input');
                headersInput.type = 'hidden';
                headersInput.name = 'headers';
                headersInput.value = JSON.stringify(this.dynamicHeaders);
                form.appendChild(headersInput);

                const rowsInput = document.createElement('input');
                rowsInput.type = 'hidden';
                rowsInput.name = 'rows';
                rowsInput.value = JSON.stringify(this.dynamicRows);
                form.appendChild(rowsInput);
                
                const titleInput = document.createElement('input');
                titleInput.type = 'hidden';
                titleInput.name = 'title';
                titleInput.value = "Laporan Benih dan Pupuk";
                form.appendChild(titleInput);

                document.body.appendChild(form);
                form.submit();
                document.body.removeChild(form);
            },

            // Dependent selection reset
            loadVariabels() {
                this.selection.variabel_id = null;
                this.selection.klasifikasi_ids = [];
            },

            loadKlasifikasis() {
                this.selection.klasifikasi_ids = [];
            },

            // Computed properties for filtering dropdowns
            get filteredTopik() {
                if (!this.search.topik) return this.allData.topiks;
                return this.allData.topiks.filter(t => t.nama.toLowerCase().includes(this.search.topik.toLowerCase()));
            },

            get filteredVariabel() {
                if (!this.selection.topik_id) return [];
                let variabels = this.allData.variabels.filter(v => v.topik_id == this.selection.topik_id);
                if (this.search.variabel) {
                    variabels = variabels.filter(v => v.nama.toLowerCase().includes(this.search.variabel.toLowerCase()));
                }
                return variabels;
            },

            get filteredKlasifikasi() {
                if (!this.selection.variabel_id) return [];
                let klasifikasis = this.allData.klasifikasis.filter(k => k.variabel_id == this.selection.variabel_id);
                if (this.search.klasifikasi) {
                    klasifikasis = klasifikasis.filter(k => k.nama.toLowerCase().includes(this.search.klasifikasi.toLowerCase()));
                }
                return klasifikasis;
            },

            get filteredTahun() {
                if (!this.search.tahun) return this.allData.tahuns;
                return this.allData.tahuns.filter(t => t.toString().includes(this.search.tahun));
            },

            get filteredBulan() {
                if (!this.search.bulan) return this.allData.bulans;
                return this.allData.bulans.filter(b => b.nama.toLowerCase().includes(this.search.bulan.toLowerCase()));
            },


            toggleKabupaten(id) {
                if (this.selection.kabupaten_ids.includes(id)) {
                    this.selection.kabupaten_ids = this.selection.kabupaten_ids.filter(kId => kId !== id);
                } else {
                    this.selection.kabupaten_ids.push(id);
                }
            },

            toggleWilayah(id) {
                if (this.selection.provinsi_ids.includes(id)) {
                    this.selection.provinsi_ids = this.selection.provinsi_ids.filter(pId => pId !== id);
                } else {
                    this.selection.provinsi_ids.push(id);
                }
            },

            get filteredWilayah() {
                const searchTerm = this.search.wilayah.toLowerCase();
                if (this.wilayahLevel === 'nasional') {
                    if (!searchTerm) return this.allData.wilayahs;
                    return this.allData.wilayahs.filter(p => p.nama.toLowerCase().includes(searchTerm));
                }

                // Tingkat Provinsi
                if (this.selectedProvinsiId) {
                    const selectedProv = this.allData.wilayahs.find(p => p.id == this.selectedProvinsiId);
                    if (!selectedProv) return [];
                    // Return an array with a single province object, containing filtered kabupaten
                    const filteredKabupaten = selectedProv.kabupaten.filter(k => k.nama.toLowerCase().includes(searchTerm));
                    return [{ ...selectedProv, kabupaten: filteredKabupaten }];
                }

                // Default view for 'provinsi' level before a province is selected
                if (!searchTerm) return this.allData.wilayahs;
                return this.allData.wilayahs.filter(provinsi => {
                    const provMatch = provinsi.nama.toLowerCase().includes(searchTerm);
                    const kabMatch = provinsi.kabupaten.some(kab => kab.nama.toLowerCase().includes(searchTerm));
                    return provMatch || kabMatch;
                });
            },

        get dynamicHeaders() {
            const currentResult = this.selectedResultIndex !== null ? this.storedResults[this.selectedResultIndex] : null;
            if (!currentResult || !currentResult.results || !currentResult.results.data || currentResult.results.data.length === 0) {
                return [];
            }

            const { columnOrder, config } = currentResult.results;
            const layout = config?.tata_letak || currentResult.config.tata_letak;
            
            // Process selections in the EXACT same order as backend
            const processedSelections = [];
            currentResult.selections.forEach((sel, selIndex) => {
                const variabel = this.allData.variabels.find(v => v.id === sel.variabel_id);
                if (!variabel) return;
                
                // Get klasifikasis for this selection, sorted by ID (same as backend)
                const selKlasifikasis = sel.klasifikasi_ids
                    .map(kid => this.allData.klasifikasis.find(k => String(k.id) === String(kid)))
                    .filter(k => k !== undefined)
                    .sort((a, b) => a.id - b.id); // Sort by ID to match backend
                
                // Get tahuns for this selection, sorted numerically (same as backend)
                const selTahuns = sel.tahun_ids.sort((a, b) => Number(a) - Number(b));
                
                // Get bulans for this selection, sorted by bulan ID (same as backend)
                const selBulans = sel.bulan_ids
                    .map(bid => this.allData.bulans.find(b => String(b.id) === String(bid)))
                    .filter(b => b !== undefined)
                    .sort((a, b) => a.id - b.id); // Sort by ID to match backend
                
                processedSelections.push({
                    index: selIndex,
                    variabel: variabel.nama,
                    klasifikasis: selKlasifikasis.map(k => k.nama),
                    tahuns: selTahuns,
                    bulans: selBulans.map(b => b.nama)
                });
            });
            
            if (processedSelections.length === 0) return [];
            
            // Generate headers based on layout - EXACT same logic as backend
            if (layout === 'tipe_1') {
                // Variabel » Klasifikasi » Tahun » Bulan
                const headers = [];
                
                // Row 1: Variabel headers
                const row1 = [{ name: 'Wilayah', span: 1, rowspan: 4 }];
                processedSelections.forEach((sel) => {
                    const span = sel.klasifikasis.length * sel.tahuns.length * sel.bulans.length;
                    row1.push({ name: sel.variabel, span: span, rowspan: 1 });
                });
                headers.push(row1);
                
                // Row 2: Klasifikasi headers
                const row2 = [];
                processedSelections.forEach((sel) => {
                    sel.klasifikasis.forEach(klasifikasi => {
                        const span = sel.tahuns.length * sel.bulans.length;
                        row2.push({ name: klasifikasi, span: span, rowspan: 1 });
                    });
                });
                headers.push(row2);
                
                // Row 3: Tahun headers
                const row3 = [];
                processedSelections.forEach((sel) => {
                    sel.klasifikasis.forEach(() => {
                        sel.tahuns.forEach(tahun => {
                            row3.push({ name: tahun, span: sel.bulans.length, rowspan: 1 });
                        });
                    });
                });
                headers.push(row3);
                
                // Row 4: Bulan headers
                const row4 = [];
                processedSelections.forEach((sel) => {
                    sel.klasifikasis.forEach(() => {
                        sel.tahuns.forEach(() => {
                            sel.bulans.forEach(bulan => {
                                row4.push({ name: bulan, span: 1, rowspan: 1 });
                            });
                        });
                    });
                });
                headers.push(row4);
                
                return headers;
            }
            
            if (layout === 'tipe_2') {
                // Klasifikasi » Variabel » Tahun » Bulan - Group by klasifikasi first
                const headers = [];
                
                // Get all unique klasifikasis in order
                const allKlasifikasis = [];
                processedSelections.forEach(sel => {
                    sel.klasifikasis.forEach(k => {
                        if (!allKlasifikasis.includes(k)) {
                            allKlasifikasis.push(k);
                        }
                    });
                });
                
                // Row 1: Klasifikasi headers
                const row1 = [{ name: 'Wilayah', span: 1, rowspan: 4 }];
                allKlasifikasis.forEach(klasifikasi => {
                    let klasifikasiSpan = 0;
                    processedSelections.forEach(sel => {
                        if (sel.klasifikasis.includes(klasifikasi)) {
                            klasifikasiSpan += sel.tahuns.length * sel.bulans.length;
                        }
                    });
                    if (klasifikasiSpan > 0) {
                        row1.push({ name: klasifikasi, span: klasifikasiSpan, rowspan: 1 });
                    }
                });
                headers.push(row1);
                
                // Row 2: Variabel headers
                const row2 = [];
                allKlasifikasis.forEach(klasifikasi => {
                    processedSelections.forEach(sel => {
                        if (sel.klasifikasis.includes(klasifikasi)) {
                            const span = sel.tahuns.length * sel.bulans.length;
                            row2.push({ name: sel.variabel, span: span, rowspan: 1 });
                        }
                    });
                });
                headers.push(row2);
                
                // Row 3: Tahun headers
                const row3 = [];
                allKlasifikasis.forEach(klasifikasi => {
                    processedSelections.forEach(sel => {
                        if (sel.klasifikasis.includes(klasifikasi)) {
                            sel.tahuns.forEach(tahun => {
                                row3.push({ name: tahun, span: sel.bulans.length, rowspan: 1 });
                            });
                        }
                    });
                });
                headers.push(row3);
                
                // Row 4: Bulan headers
                const row4 = [];
                allKlasifikasis.forEach(klasifikasi => {
                    processedSelections.forEach(sel => {
                        if (sel.klasifikasis.includes(klasifikasi)) {
                            sel.tahuns.forEach(() => {
                                sel.bulans.forEach(bulan => {
                                    row4.push({ name: bulan, span: 1, rowspan: 1 });
                                });
                            });
                        }
                    });
                });
                headers.push(row4);
                
                return headers;
            }
            
            if (layout === 'tipe_3') {
                // Tahun » Bulan » Variabel » Klasifikasi
                const headers = [];
                
                // Get all unique tahuns from all selections
                const allTahuns = [];
                processedSelections.forEach(sel => {
                    sel.tahuns.forEach(t => {
                        if (!allTahuns.includes(t)) {
                            allTahuns.push(t);
                        }
                    });
                });
                allTahuns.sort((a, b) => Number(a) - Number(b));
                
                // Row 1: Tahun headers
                const row1 = [{ name: 'Wilayah', span: 1, rowspan: 4 }];
                allTahuns.forEach(tahun => {
                    let tahunSpan = 0;
                    processedSelections.forEach(sel => {
                        if (sel.tahuns.includes(tahun)) {
                            tahunSpan += sel.bulans.length * sel.klasifikasis.length;
                        }
                    });
                    if (tahunSpan > 0) {
                        row1.push({ name: tahun, span: tahunSpan, rowspan: 1 });
                    }
                });
                headers.push(row1);
                
                // Row 2: Bulan headers
                const row2 = [];
                allTahuns.forEach(tahun => {
                    const allBulans = [];
                    processedSelections.forEach(sel => {
                        if (sel.tahuns.includes(tahun)) {
                            sel.bulans.forEach(b => {
                                if (!allBulans.includes(b)) {
                                    allBulans.push(b);
                                }
                            });
                        }
                    });
                    // Sort bulans by their position in the master bulan list
                    const sortedBulans = allBulans.sort((a, b) => {
                        const indexA = this.allData.bulans.findIndex(bulan => bulan.nama === a);
                        const indexB = this.allData.bulans.findIndex(bulan => bulan.nama === b);
                        return indexA - indexB;
                    });
                    
                    sortedBulans.forEach(bulan => {
                        let bulanSpan = 0;
                        processedSelections.forEach(sel => {
                            if (sel.tahuns.includes(tahun) && sel.bulans.includes(bulan)) {
                                bulanSpan += sel.klasifikasis.length;
                            }
                        });
                        if (bulanSpan > 0) {
                            row2.push({ name: bulan, span: bulanSpan, rowspan: 1 });
                        }
                    });
                });
                headers.push(row2);
                
                // Row 3: Variabel headers
                const row3 = [];
                allTahuns.forEach(tahun => {
                    const allBulans = [];
                    processedSelections.forEach(sel => {
                        if (sel.tahuns.includes(tahun)) {
                            sel.bulans.forEach(b => {
                                if (!allBulans.includes(b)) {
                                    allBulans.push(b);
                                }
                            });
                        }
                    });
                    const sortedBulans = allBulans.sort((a, b) => {
                        const indexA = this.allData.bulans.findIndex(bulan => bulan.nama === a);
                        const indexB = this.allData.bulans.findIndex(bulan => bulan.nama === b);
                        return indexA - indexB;
                    });
                    
                    sortedBulans.forEach(bulan => {
                        processedSelections.forEach(sel => {
                            if (sel.tahuns.includes(tahun) && sel.bulans.includes(bulan)) {
                                row3.push({ name: sel.variabel, span: sel.klasifikasis.length, rowspan: 1 });
                            }
                        });
                    });
                });
                headers.push(row3);
                
                // Row 4: Klasifikasi headers
                const row4 = [];
                allTahuns.forEach(tahun => {
                    const allBulans = [];
                    processedSelections.forEach(sel => {
                        if (sel.tahuns.includes(tahun)) {
                            sel.bulans.forEach(b => {
                                if (!allBulans.includes(b)) {
                                    allBulans.push(b);
                                }
                            });
                        }
                    });
                    const sortedBulans = allBulans.sort((a, b) => {
                        const indexA = this.allData.bulans.findIndex(bulan => bulan.nama === a);
                        const indexB = this.allData.bulans.findIndex(bulan => bulan.nama === b);
                        return indexA - indexB;
                    });
                    
                    sortedBulans.forEach(bulan => {
                        processedSelections.forEach(sel => {
                            if (sel.tahuns.includes(tahun) && sel.bulans.includes(bulan)) {
                                sel.klasifikasis.forEach(klasifikasi => {
                                    row4.push({ name: klasifikasi, span: 1, rowspan: 1 });
                                });
                            }
                        });
                    });
                });
                headers.push(row4);
                
                return headers;
            }
            
            // Fallback to simple headers
            return [[
                { name: 'Wilayah', span: 1, rowspan: 1 },
                ...columnOrder.map(col => ({ name: col.charAt(0).toUpperCase() + col.slice(1), span: 1, rowspan: 1 }))
            ]];
        },

        get dynamicRows() {
            const currentResult = this.selectedResultIndex !== null ? this.storedResults[this.selectedResultIndex] : null;
            if (!currentResult || !currentResult.results || !currentResult.results.data || currentResult.results.data.length === 0) {
                return [];
            }
            
            // Sort by wilayah_sorter ascending if available, and format values to 2 decimals for display
            const rows = [...currentResult.results.data];
            rows.sort((a, b) => {
                const sa = (a.wilayah_sorter ?? Number.MAX_SAFE_INTEGER);
                const sb = (b.wilayah_sorter ?? Number.MAX_SAFE_INTEGER);
                if (sa !== sb) return sa - sb;
                // fallback stable by wilayah name
                return String(a.wilayah).localeCompare(String(b.wilayah));
            });
            return rows.map(r => ({
                ...r,
                values: Array.isArray(r.values)
                    ? r.values.map(v => {
                        const num = typeof v === 'number' ? v : parseFloat(String(v).replace(/,/g, '.'));
                        return isFinite(num) ? num.toFixed(2) : v;
                      })
                    : r.values
            }));
        },

        // Helper function
        getColumnOrderKeys(layout) {
            if (layout === 'tipe_1') return ['variabel', 'klasifikasi', 'tahun', 'bulan'];
            if (layout === 'tipe_2') return ['klasifikasi', 'variabel', 'tahun', 'bulan'];
            if (layout === 'tipe_3') return ['tahun', 'bulan', 'variabel', 'klasifikasi'];
            return [];
        }
        };
    }
</script>


                    <div x-data="benihPupukForm()" x-init="init(benihPupukInitialData)" class="space-y-12">
                        <!-- Step 1: Select Data -->
<section class="bg-neutral-50 rounded-lg p-6 border border-neutral-200">
    <h2 class="text-2xl font-bold text-neutral-800 mb-1 flex items-center">
        <span class="bg-blue-600 text-white rounded-full h-8 w-8 flex items-center justify-center mr-3">1</span>
        Pilih Data
    </h2>
    <p class="text-neutral-600 mb-4 ml-11">Pilih Topik, Variabel, dan Periode Waktu.</p>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Left column: Topik + Variabel stacked -->
        <div class="space-y-4 h-full flex flex-col">
            <!-- 1.1 Topik -->
            <div class="bg-white p-4 rounded-lg border">
                <h3 class="font-semibold text-neutral-900 mb-3">1.1 Topik</h3>
                <div class="space-y-1 h-32 overflow-y-auto">
                    <template x-for="topik in filteredTopik" :key="topik.id">
                        <div @click="selectTopik(topik.id)" class="flex items-center cursor-pointer hover:bg-blue-50 p-2 rounded-md" :class="{'bg-blue-100 font-semibold': selection.topik_id === topik.id}">
                            <span class="ml-2 text-sm text-neutral-700" x-text="topik.nama"></span>
                        </div>
                    </template>
                </div>
            </div>

            <!-- 1.2 Variabel -->
            <div class="bg-white p-4 rounded-lg border flex-1 flex flex-col">
                <h3 class="font-semibold text-neutral-900 mb-3">1.2 Variabel</h3>
                <div x-show="!selection.topik_id" class="text-center py-8 text-neutral-500">
                    <p class="text-sm">Pilih topik dahulu</p>
                </div>
                <div x-show="selection.topik_id" :class="!selection.topik_id ? 'opacity-50 pointer-events-none' : ''" class="flex-1 flex flex-col">
                    <div class="flex-1 overflow-y-auto mb-3 border rounded-md p-2">
                        <template x-for="variabel in filteredVariabel" :key="variabel.id">
                            <div @click="selectVariabel(variabel.id)" class="flex items-center cursor-pointer hover:bg-blue-50 p-2 rounded-md" :class="{'bg-blue-100 font-semibold': selection.variabel_id === variabel.id}">
                                <span class="ml-2 text-sm text-neutral-700" x-text="variabel.nama"></span>
                            </div>
                        </template>
                    </div>
                    <h4 class="font-medium text-neutral-800 mb-1 text-sm">Klasifikasi Variabel</h4>
                    <div x-show="!selection.variabel_id" class="text-center py-4 text-neutral-500 border rounded-md">
                        <p class="text-sm">Pilih variabel dahulu</p>
                    </div>
                    <div x-show="selection.variabel_id">
                        <div class="border rounded-md p-2">
                            <template x-for="klasifikasi in filteredKlasifikasi" :key="klasifikasi.id">
                                <label class="flex items-center cursor-pointer hover:bg-green-50 p-2 rounded-md">
                                    <input type="checkbox" :value="klasifikasi.id" x-model="selection.klasifikasi_ids">
                                    <span class="ml-2 text-sm text-neutral-700" x-text="klasifikasi.nama"></span>
                                </label>
                            </template>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right column: 1.3 Waktu with Tahun & Bulan stacked -->
    <div class="bg-white p-4 rounded-lg border h-full">
            <h3 class="font-semibold text-neutral-900 mb-3">1.3 Waktu</h3>
            <div class="grid grid-cols-1 gap-4">
                <!-- Tahun box -->
                <div class="flex flex-col border rounded-lg p-3">
                    <label class="block text-sm font-medium text-neutral-700 mb-1">Tahun</label>
                    <div class="flex justify-between items-center mb-1">
                        <button @click="selection.tahun_ids = filteredTahun.map(t => t)" class="text-xs text-blue-600 hover:underline">Select All</button>
                        <button @click="selection.tahun_ids = []" class="text-xs text-red-600 hover:underline">Clear</button>
                    </div>
                    <div class="overflow-y-auto border rounded-md p-2 max-h-56">
                        <template x-for="year in filteredTahun" :key="year">
                            <label class="flex items-center cursor-pointer hover:bg-blue-50 p-2 rounded-md">
                                <input type="checkbox" :value="year" x-model="selection.tahun_ids">
                                <span class="ml-2 text-sm text-neutral-700" x-text="year"></span>
                            </label>
                        </template>
                    </div>
                </div>

                <!-- Bulan box -->
                <div class="flex flex-col border rounded-lg p-3">
                    <label class="block text-sm font-medium text-neutral-700 mb-1">Bulan</label>
                    <div class="flex justify-between items-center mb-1">
                        <button @click="selection.bulan_ids = filteredBulan.map(b => b.id)" class="text-xs text-blue-600 hover:underline">Select All</button>
                        <button @click="selection.bulan_ids = []" class="text-xs text-red-600 hover:underline">Clear</button>
                    </div>
                    <div class="overflow-y-auto border rounded-md p-2 max-h-56">
                        <template x-for="bulan in filteredBulan" :key="bulan.id">
                            <label class="flex items-center cursor-pointer hover:bg-green-50 p-2 rounded-md">
                                <input type="checkbox" :value="bulan.id" x-model="selection.bulan_ids">
                                <span class="ml-2 text-sm text-neutral-700" x-text="bulan.nama"></span>
                            </label>
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    (function(){
        const INIT_ATTR = 'data-sticky-init';
        // Equalize data column widths to the widest data column for better readability
        function equalizeColumns(table){
            try{
                if(!table.tHead || !table.tBodies || table.tBodies.length === 0) return;
                const thead = table.tHead;
                const lastHeadRow = thead.rows[thead.rows.length - 1];
                if(!lastHeadRow) return;
                const dataLeafHeaders = Array.from(lastHeadRow.cells);
                const body = table.tBodies[0];
                // Determine number of data columns (exclude the first body cell which is Wilayah)
                const dataColCount = dataLeafHeaders.length;
                if(dataColCount === 0) return;

                // Measure each data column's natural width and find the global max among them
                const LIMIT_ROWS = 200; // cap to avoid heavy reflow on huge tables
                const rowSampleCount = Math.min(body.rows.length, LIMIT_ROWS);
                const perColMax = new Array(dataColCount).fill(0);
                const dpr = window.devicePixelRatio || 1;

                for(let j=0; j<dataColCount; j++){
                    // header leaf width
                    let w = dataLeafHeaders[j].getBoundingClientRect().width;
                    // body cells in column j (offset by +1 due to first Wilayah cell)
                    for(let r=0; r<rowSampleCount; r++){
                        const tr = body.rows[r];
                        const td = tr && tr.cells && tr.cells[1 + j];
                        if(td){
                            const cw = td.getBoundingClientRect().width;
                            if(cw > w) w = cw;
                        }
                    }
                    // Snap to device pixels
                    perColMax[j] = Math.round(w * dpr) / dpr;
                }
                const globalMax = Math.max.apply(null, perColMax);
                if(!isFinite(globalMax) || globalMax <= 0) return;

                // Build/replace a colgroup to enforce widths
                let colgroup = table.querySelector('colgroup.equalized-cols');
                if(colgroup) colgroup.remove();
                colgroup = document.createElement('colgroup');
                colgroup.className = 'equalized-cols';
                // First col: Wilayah (auto width)
                const colWilayah = document.createElement('col');
                colWilayah.className = 'col-wilayah';
                colgroup.appendChild(colWilayah);
                // Data cols: set equal width based on the widest
                for(let j=0; j<dataColCount; j++){
                    const c = document.createElement('col');
                    c.style.width = `${globalMax}px`;
                    colgroup.appendChild(c);
                }
                table.insertBefore(colgroup, table.firstChild);
            }catch(e){ /* noop */ }
        }
    function recalcFor(table){
            try{
                const wrap = table.closest('.sticky-table-container');
                const thead = table.querySelector('thead');
                if(!wrap || !thead) return;
                // Ensure columns are equalized before computing sticky offsets
                equalizeColumns(table);
                const rows = Array.from(thead.querySelectorAll('tr'));
                let acc = 0;
        const dpr = window.devicePixelRatio || 1;
        rows.forEach((row, idx)=>{
            wrap.style.setProperty(`--row-top-${idx+1}` , `${acc}px`);
            const rectH = row.getBoundingClientRect().height;
            const snapped = Math.round(rectH * dpr) / dpr;
            acc += snapped;
                });
            }catch(e){ /* noop */ }
        }

        function wire(table){
            if(!table || table.hasAttribute(INIT_ATTR)) return;
            table.setAttribute(INIT_ATTR,'1');
            const doRecalc = ()=>recalcFor(table);
            // Initial calc after fonts/layout paint
            requestAnimationFrame(()=>requestAnimationFrame(doRecalc));
            // Resize of the container or window
            const ro = new ResizeObserver(doRecalc);
            const wrap = table.closest('.sticky-table-container');
            if(wrap) ro.observe(wrap);
            ro.observe(table);
            // Mutations inside the table (headers updated)
            const mo = new MutationObserver(()=>requestAnimationFrame(doRecalc));
            mo.observe(table, { childList:true, subtree:true, attributes:true });
            // Visibility changes (tabs/results switching)
            const io = new IntersectionObserver((entries)=>{
                entries.forEach(e=>{ if(e.isIntersecting) doRecalc(); });
            }, { root: null, threshold: 0 });
            io.observe(table);
            // Also recalc on window resize
            window.addEventListener('resize', doRecalc);
        }

        function scan(){
            document.querySelectorAll('table.sticky-table').forEach(wire);
        }
        // Initial scan and on DOM updates
        scan();
        const rootMO = new MutationObserver(()=>scan());
        rootMO.observe(document.body, { childList:true, subtree:true });
        // Alpine after-render hooks could be used; fallback to periodic micro task
        setTimeout(scan, 0);
        setTimeout(scan, 200);
        setTimeout(scan, 500);
    })();
    </script>

    <div class="flex items-center justify-between mb-4">
        <div class="flex items-center gap-2">
            <button @click="addSelection" :disabled="!isSelectionValid()" class="bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-600 flex items-center disabled:opacity-50 disabled:cursor-not-allowed">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                Tambah
            </button>
            <button @click="removeSelection()" :disabled="selectedForRemoval.length === 0" class="bg-red-500 text-white px-4 py-2 rounded-md hover:bg-red-600 flex items-center disabled:opacity-50 disabled:cursor-not-allowed">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 12H6"></path></svg>
                Hapus
            </button>
        </div>
        <button @click="resetForm" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400">Set Ulang</button>
    </div>

    <!-- Data Terpilih -->
    <div class="bg-white p-4 rounded-lg border">
        <h3 class="font-semibold text-neutral-900 mb-3">Data Terpilih</h3>
        <div class="space-y-2">
            <template x-for="item in selections" :key="item.id">
                <div class="flex items-center bg-blue-50 p-2 rounded-md">
                    <input type="checkbox" :value="item.id" x-model="selectedForRemoval">
                                        <span class="ml-2 text-sm text-neutral-800" x-text="`${item.display.variabel_nama}${item.display.klasifikasi_nama !== 'Semua' ? ' - ' + item.display.klasifikasi_nama : ''} - ${item.display.tahun} - ${item.display.bulan}`"></span>
                </div>
            </template>
            <div x-show="selections.length === 0" class="text-center text-neutral-500 py-4">
                <p>Belum ada data yang ditambahkan.</p>
            </div>
        </div>
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
        <!-- 2.1 Wilayah -->
        <div class="bg-white p-4 rounded-lg border">
            <h3 class="font-semibold text-neutral-900 mb-3">2.1 Wilayah</h3>

            <select x-model="wilayahLevel" class="w-full px-3 py-2 border border-neutral-300 rounded-md mb-3 text-sm">
                <option value="nasional">Tingkat Nasional</option>
                <option value="provinsi">Tingkat Provinsi</option>
            </select>

            <!-- Tingkat Nasional View -->
            <div x-show="wilayahLevel === 'nasional'">
                 <div class="flex justify-between items-center mb-1">
                    <button @click="selection.provinsi_ids = filteredWilayah.map(p => p.id)" class="text-xs text-blue-600 hover:underline">Select All</button>
                    <button @click="selection.provinsi_ids = []" class="text-xs text-red-600 hover:underline">Clear</button>
                </div>
                <div class="overflow-y-auto border rounded-md p-2 max-h-[70vh]">
                    <template x-for="provinsi in filteredWilayah" :key="provinsi.id">
                        <label class="flex items-center cursor-pointer p-2 rounded-md hover:bg-blue-50">
                            <input type="checkbox" :value="provinsi.id" :checked="selection.provinsi_ids.includes(provinsi.id)" @click="toggleWilayah(provinsi.id)">
                            <span class="ml-2 text-sm font-bold text-neutral-800" x-text="provinsi.nama"></span>
                        </label>
                    </template>
                </div>
            </div>

            <!-- Tingkat Provinsi View -->
            <div x-show="wilayahLevel === 'provinsi'">
                <select x-model="selectedProvinsiId" class="w-full px-3 py-2 border border-neutral-300 rounded-md mb-3 text-sm">
                    <option :value="null">-- Pilih Provinsi Dahulu --</option>
                    <template x-for="provinsi in allData.wilayahs" :key="provinsi.id">
                        <option :value="provinsi.id" x-text="provinsi.nama"></option>
                    </template>
                </select>

                <div x-show="selectedProvinsiId">
                     <div class="flex justify-between items-center mb-1">
                        <button @click="selection.kabupaten_ids = filteredWilayah.flatMap(p => p.kabupaten.map(k => k.id))" class="text-xs text-blue-600 hover:underline">Select All</button>
                        <button @click="selection.kabupaten_ids = []" class="text-xs text-red-600 hover:underline">Clear</button>
                    </div>
                    <input type="text" x-model="search.wilayah" placeholder="Cari kabupaten/kota..." class="w-full px-3 py-2 border border-neutral-300 rounded-md mb-2 text-sm">
                    <div class="overflow-y-auto border rounded-md p-2 max-h-[70vh]">
                        <template x-for="provinsi in filteredWilayah" :key="provinsi.id">
                            <div class="ml-6">
                                <template x-for="kabupaten in provinsi.kabupaten" :key="kabupaten.id">
                                    <label class="flex items-center cursor-pointer p-1 rounded-md hover:bg-green-50">
                                        <input type="checkbox" :value="kabupaten.id" :checked="selection.kabupaten_ids.includes(kabupaten.id)" @click="toggleKabupaten(kabupaten.id)">
                                        <span class="ml-2 text-sm text-neutral-700" x-text="kabupaten.nama"></span>
                                    </label>
                                </template>
                            </div>
                        </template>
                    </div>
                </div>
                 <div x-show="!selectedProvinsiId" class="p-4 bg-gray-50 rounded-md text-center">
                    <p class="text-sm text-neutral-500">Silakan pilih provinsi untuk menampilkan daftar kabupaten/kota.</p>
                </div>
            </div>
        </div>

        <!-- 2.2 Tata Letak Tabel -->
        <div class="bg-white p-4 rounded-lg border">
            <h3 class="font-semibold text-neutral-900 mb-3">2.2 Tata Letak Tabel</h3>
            <div class="space-y-4">
                <!-- Tipe 1 -->
                <label class="block p-4 border rounded-lg cursor-pointer hover:border-blue-500" :class="{'border-blue-500 bg-blue-50 ring-2 ring-blue-200': selection.tata_letak === 'tipe_1'}">
                    <div class="flex gap-6 items-start">
                        <input type="radio" name="tata_letak" value="tipe_1" x-model="selection.tata_letak" class="mt-1">
                        <div class="flex-1">
                            <p class="font-semibold">Tipe 1: Master Header Variabel</p>
                            <p class="text-sm text-neutral-600 mb-2">Kolom diurutkan berdasarkan: Variabel &raquo; Klasifikasi &raquo; Tahun &raquo; Bulan</p>
                            <table class="w-full border-collapse text-xs mt-2 bg-white">
                                <thead>
                                    <tr class="bg-neutral-100">
                                        <th rowspan="4" class="border p-1 font-semibold align-middle">Wilayah</th>
                                        <th colspan="4" class="border p-1 font-semibold">Variabel A</th>
                                    </tr>
                                    <tr class="bg-neutral-50">
                                        <th colspan="2" class="border p-1 font-normal">Klasifikasi X</th>
                                        <th colspan="2" class="border p-1 font-normal">Klasifikasi Y</th>
                                    </tr>
                                    <tr class="bg-neutral-50">
                                        <th colspan="1" class="border p-1 font-normal">2023</th>
                                        <th colspan="1" class="border p-1 font-normal">2024</th>
                                        <th colspan="1" class="border p-1 font-normal">2023</th>
                                        <th colspan="1" class="border p-1 font-normal">2024</th>
                                    </tr>
                                    <tr class="bg-neutral-50">
                                        <th class="border p-1 font-normal">Jan</th>
                                        <th class="border p-1 font-normal">Jan</th>
                                        <th class="border p-1 font-normal">Jan</th>
                                        <th class="border p-1 font-normal">Jan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="border p-1">Provinsi A</td>
                                        <td class="border p-1 text-center">...</td>
                                        <td class="border p-1 text-center">...</td>
                                        <td class="border p-1 text-center">...</td>
                                        <td class="border p-1 text-center">...</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </label>

                <!-- Tipe 2 -->
                <label class="block p-4 border rounded-lg cursor-pointer hover:border-blue-500" :class="{'border-blue-500 bg-blue-50 ring-2 ring-blue-200': selection.tata_letak === 'tipe_2'}">
                    <div class="flex gap-6 items-start">
                        <input type="radio" name="tata_letak" value="tipe_2" x-model="selection.tata_letak" class="mt-1">
                        <div class="flex-1">
                            <p class="font-semibold">Tipe 2: Master Header Klasifikasi</p>
                            <p class="text-sm text-neutral-600 mb-2">Kolom diurutkan berdasarkan: Klasifikasi &raquo; Variabel &raquo; Tahun &raquo; Bulan</p>
                            <table class="w-full border-collapse text-xs mt-2 bg-white">
                                <thead>
                                    <tr class="bg-neutral-100">
                                        <th rowspan="4" class="border p-1 font-semibold align-middle">Wilayah</th>
                                        <th colspan="4" class="border p-1 font-semibold">Klasifikasi X</th>
                                    </tr>
                                    <tr class="bg-neutral-50">
                                        <th colspan="2" class="border p-1 font-normal">Variabel A</th>
                                        <th colspan="2" class="border p-1 font-normal">Variabel B</th>
                                    </tr>
                                    <tr class="bg-neutral-50">
                                        <th colspan="1" class="border p-1 font-normal">2023</th>
                                        <th colspan="1" class="border p-1 font-normal">2024</th>
                                        <th colspan="1" class="border p-1 font-normal">2023</th>
                                        <th colspan="1" class="border p-1 font-normal">2024</th>
                                    </tr>
                                    <tr class="bg-neutral-50">
                                        <th class="border p-1 font-normal">Jan</th>
                                        <th class="border p-1 font-normal">Jan</th>
                                        <th class="border p-1 font-normal">Jan</th>
                                        <th class="border p-1 font-normal">Jan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="border p-1">Provinsi A</td>
                                        <td class="border p-1 text-center">...</td>
                                        <td class="border p-1 text-center">...</td>
                                        <td class="border p-1 text-center">...</td>
                                        <td class="border p-1 text-center">...</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </label>

                <!-- Tipe 3 -->
                <label class="block p-4 border rounded-lg cursor-pointer hover:border-blue-500" :class="{'border-blue-500 bg-blue-50 ring-2 ring-blue-200': selection.tata_letak === 'tipe_3'}">
                    <div class="flex gap-6 items-start">
                        <input type="radio" name="tata_letak" value="tipe_3" x-model="selection.tata_letak" class="mt-1">
                        <div class="flex-1">
                            <p class="font-semibold">Tipe 3: Master Header Waktu</p>
                            <p class="text-sm text-neutral-600 mb-2">Kolom diurutkan berdasarkan: Tahun &raquo; Bulan &raquo; Variabel &raquo; Klasifikasi</p>
                            <table class="w-full border-collapse text-xs mt-2 bg-white">
                                <thead>
                                    <tr class="bg-neutral-100">
                                        <th rowspan="4" class="border p-1 font-semibold align-middle">Wilayah</th>
                                        <th colspan="4" class="border p-1 font-semibold">2023</th>
                                    </tr>
                                    <tr class="bg-neutral-50">
                                        <th colspan="2" class="border p-1 font-normal">Januari</th>
                                        <th colspan="2" class="border p-1 font-normal">Februari</th>
                                    </tr>
                                    <tr class="bg-neutral-50">
                                        <th colspan="1" class="border p-1 font-normal">Variabel A</th>
                                        <th colspan="1" class="border p-1 font-normal">Variabel B</th>
                                        <th colspan="1" class="border p-1 font-normal">Variabel A</th>
                                        <th colspan="1" class="border p-1 font-normal">Variabel B</th>
                                    </tr>
                                    <tr class="bg-neutral-50">
                                        <th class="border p-1 font-normal">Klas. X</th>
                                        <th class="border p-1 font-normal">Klas. X</th>
                                        <th class="border p-1 font-normal">Klas. X</th>
                                        <th class="border p-1 font-normal">Klas. X</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="border p-1">Provinsi A</td>
                                        <td class="border p-1 text-center">...</td>
                                        <td class="border p-1 text-center">...</td>
                                        <td class="border p-1 text-center">...</td>
                                        <td class="border p-1 text-center">...</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </label>
            </div>
        </div>
    </div>
</section>
<!-- Submit Button -->
<div class="flex justify-end mt-8">
    <button @click.prevent="fetchData" :disabled="isProcessing" class="w-full bg-blue-600 text-white font-bold py-3 px-4 rounded-md hover:bg-blue-700 disabled:bg-blue-300 flex items-center justify-center disabled:cursor-not-allowed">
        <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        Tampilkan Hasil
    </button>
</div>

<!-- Loading/Processing State -->
<div x-show="isProcessing" class="fixed inset-0 flex items-center justify-center" style="z-index: 10000; backdrop-filter: blur(8px); background-color: rgba(255, 255, 255, 0.3);">
    <div class="bg-white rounded-lg p-8 text-center shadow-2xl border border-neutral-200">
        <svg class="animate-spin w-12 h-12 text-blue-500 mx-auto mb-4" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        <h3 class="text-lg font-medium text-neutral-900 mb-2">Memproses Data...</h3>
        <p class="text-neutral-700">Mohon tunggu, kami sedang menyiapkan laporan untuk Anda.</p>
    </div>
</div>

<!-- Step 3: Tampilan Hasil -->
<section x-show="storedResults.length > 0" class="bg-neutral-50 rounded-lg p-6 border border-neutral-200 mt-12">
    <h2 class="text-2xl font-bold text-neutral-800 mb-1 flex items-center">
        <span class="bg-blue-600 text-white rounded-full h-8 w-8 flex items-center justify-center mr-3">3</span>
        Tampilan Hasil
    </h2>
    <p class="text-neutral-600 mb-6 ml-11">Hasil dari data yang telah Anda pilih.</p>

    <!-- Results Layout with Vertical Tabs -->
    <div class="flex gap-6">
        <!-- Left Sidebar - Result Selection -->
        <div class="w-48 flex-shrink-0">
            <div class="bg-white rounded-lg border overflow-hidden">
                <template x-for="(result, index) in storedResults" :key="result.id">
                    <button @click="selectStoredResult(index)" 
                            :class="{'bg-blue-600 text-white': selectedResultIndex === index, 'bg-white text-neutral-700 hover:bg-neutral-50': selectedResultIndex !== index}"
                            class="w-full px-4 py-3 text-left border-b border-neutral-200 last:border-b-0 transition-colors">
                        <div class="font-medium text-sm" x-text="`Result #${index + 1}`"></div>
                        <div class="text-xs opacity-75 mt-1" x-text="result.timestamp"></div>
                    </button>
                </template>
            </div>
        </div>

        <!-- Main Content Area -->
        <div class="flex-1 main-content-flex">
            <div x-show="selectedResultIndex !== null" class="bg-white rounded-lg border">
                <!-- Tab Navigation -->
                <div class="border-b border-neutral-200">
                    <nav class="flex" aria-label="Tabs">
                        <button @click="activeResultTab = 'tabel'" 
                                :class="activeResultTab === 'tabel' ? 'border-blue-500 text-blue-600' : 'border-transparent text-neutral-500 hover:text-neutral-700 hover:border-neutral-300'"
                                class="py-4 px-6 border-b-2 font-medium text-sm whitespace-nowrap">
                            Tabel
                        </button>
                        <button @click="activeResultTab = 'grafik'" 
                                :class="activeResultTab === 'grafik' ? 'border-blue-500 text-blue-600' : 'border-transparent text-neutral-500 hover:text-neutral-700 hover:border-neutral-300'"
                                class="py-4 px-6 border-b-2 font-medium text-sm whitespace-nowrap">
                            Grafik
                        </button>
                        <button @click="activeResultTab = 'metodologi'" 
                                :class="activeResultTab === 'metodologi' ? 'border-blue-500 text-blue-600' : 'border-transparent text-neutral-500 hover:text-neutral-700 hover:border-neutral-300'"
                                class="py-4 px-6 border-b-2 font-medium text-sm whitespace-nowrap">
                            Metodologi
                        </button>
                        <div class="ml-auto flex items-center px-6">
                            <button @click="exportExcel()" class="px-4 py-2 bg-green-600 text-white rounded-md font-medium flex items-center gap-2 text-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                Export (.xlsx)
                            </button>
                        </div>
                    </nav>
                </div>

                <!-- Tab Content -->
                <div class="p-6">
                    <!-- Tabel Tab -->
                    <div x-show="activeResultTab === 'tabel'" class="table-tab-content">
                        <!-- Container dengan constraint yang sama seperti grafik -->
                        <div class="w-full overflow-hidden">
                            <div class="sticky-table-container">
                                <table class="sticky-table">
                                    <thead>
                                        <template x-for="(headerRow, rowIndex) in dynamicHeaders" :key="rowIndex">
                                            <tr>
                                                <template x-for="(header, headerIndex) in headerRow" :key="headerIndex">
                                                    <th :colspan="header.span || 1"
                                                        :rowspan="header.rowspan || 1"
                                                        :data-row-index="(rowIndex + 1)"
                                                        :class="header.name === 'Wilayah' || header.name?.includes('Wilayah') ? 'sticky-wilayah-header' : ''"
                                                        x-text="header.name">
                                                    </th>
                                                </template>
                                            </tr>
                                        </template>
                                    </thead>
                                    <tbody>
                                        <template x-if="!dynamicRows || dynamicRows.length === 0">
                                            <tr>
                                                <td :colspan="(dynamicHeaders[0] || []).length > 0 ? (dynamicHeaders[dynamicHeaders.length - 1] || []).length : 1" class="text-center py-8 text-neutral-500">
                                                    Tidak ada data yang sesuai dengan kriteria yang dipilih.
                                                </td>
                                            </tr>
                                        </template>
                                        <template x-for="row in dynamicRows" :key="row.wilayah">
                                            <tr>
                                                <td class="text-sm font-medium text-neutral-800"
                                                    :title="row.wilayah"
                                                    x-text="row.wilayah">
                                                </td>
                                                <template x-for="(value, valueIndex) in row.values" :key="valueIndex">
                                                    <td class="text-sm text-neutral-700 text-right"
                                                        :title="(Number.isFinite(parseFloat(value)) ? parseFloat(value).toFixed(2) : value)"
                                                        x-text="(Number.isFinite(parseFloat(value)) ? parseFloat(value).toFixed(2) : value)">
                                                    </td>
                                                </template>
                                            </tr>
                                        </template>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!-- sticky headers are initialized with a global script below -->

                    <!-- Grafik Tab -->
                    <div x-show="activeResultTab === 'grafik'" x-init="initChartResizeHandlerOnce(); $watch('activeResultTab', value => { if (value === 'grafik') $nextTick(() => renderChart()) })">
                        <!-- Controls: province selector and scroll button -->
                        <div class="flex items-center gap-2 mb-3">
                            <select x-model="selectedProvinceForScroll" class="border rounded px-2 py-1 text-sm">
                                <template x-for="row in dynamicRows" :key="row.wilayah">
                                    <option :value="row.wilayah" x-text="row.wilayah"></option>
                                </template>
                            </select>
                            <button type="button" @click="scrollToProvince()" class="px-3 py-1.5 bg-blue-600 text-white rounded text-sm">Scroll ke Provinsi</button>
                            <button type="button" @click="toggleLegend()" class="px-3 py-1.5 bg-neutral-700 text-white rounded text-sm" x-text="showLegend ? 'Sembunyikan Legend' : 'Tampilkan Legend'"></button>
                        </div>
                        <!-- Horizontal scroll so all provinces (labels) can be viewed -->
                        <div id="chart-scroll" class="h-96 w-full overflow-x-auto">
                            <canvas id="chart-container" class="h-full"></canvas>
                        </div>
                    </div>

                    <!-- Metodologi Tab -->
                    <div x-show="activeResultTab === 'metodologi'">
                        <div class="prose max-w-none">
                            <h3 class="text-lg font-semibold text-neutral-900 mb-4">Sumber:</h3>
                            <p class="text-neutral-700 mb-6">
                                Data benih produksi padi, jagung dan kedelai diperoleh dari Direktorat Perbenihan - Direktorat Jenderal Tanaman Pangan.
                            </p>
                            
                            <h3 class="text-lg font-semibold text-neutral-900 mb-4">Metodologi:</h3>
                            <p class="text-neutral-700">
                                Data benih produksi padi, jagung dan kedelai diperoleh dari Badan Pengawasan dan Sertifikasi Benih yang ada di tiap-tiap provinsi dan dilaporkan tiap bulan ke Direktorat Perbenihan - Direktorat Jenderal Tanaman Pangan.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

    <script>
        const benihPupukInitialData = {
            topiks: @json($topiks),
            variabels: @json($variabels),
            klasifikasis: @json($klasifikasis),
            tahuns: @json($tahuns),
            bulans: @json($bulans),
            wilayahs: @json($wilayahs)
        };

        console.log('Global initial data:', benihPupukInitialData);

        function benihPupukForm() {
            return {
                init(data) {
                    console.log('Data received in init():', data);
                    this.allData = data;
                },
                // Data sources from Controller
                allData: {
                    topiks: [],
                    variabels: [],
                    klasifikasis: [],
                    tahuns: [],
                    bulans: [],
                    wilayahs: []
                },

                // Form state
                selection: {
                    topik_id: null,
                    variabel_id: null,
                    klasifikasi_ids: [],
                    tahun_awal: '',
                    tahun_akhir: '',
                    bulan_awal: '',
                    bulan_akhir: '',
                    wilayah_ids: [],
                    tata_letak: 'tipe_1',
                },

                search: {
                    topik: '',
                    variabel: '',
                    klasifikasi: '',
                    tahun: '',
                    bulan: '',
                    wilayah: '',
                },
                
                // UI state
                yearMode: 'range',
                monthMode: 'range',
                isProcessing: false,
                addingToQueue: false,
                activeTab: 'tabel',
                selectedResultIndex: null,
                wilayahLevel: 'nasional',
                filterQueue: [],
                searchResults: [], // Initialize searchResults to an empty array

                // Consolidate search results by table layout type
                get consolidatedResults() {
                    if (this.searchResults.length === 0) return [];

                    // Group results by table layout and topic
                    const layoutGroups = {};
                    
                    this.searchResults.forEach(result => {
                        const layoutKey = result.queueItem.tata_letak || 'tipe_1';
                        const topikKey = result.queueItem.topik;
                        const groupKey = `${layoutKey}_${topikKey}`;
                        
                        if (!layoutGroups[groupKey]) {
                            layoutGroups[groupKey] = {
                                layout: layoutKey,
                                topik: topikKey,
                                topik_nama: result.topik_nama || (topikKey === '1' ? 'Benih' : 'Pupuk'),
                                results: [],
                                consolidatedData: null
                            };
                        }
                        
                        layoutGroups[groupKey].results.push(result);
                    });

                    // Convert to array and consolidate data within each group
                    return Object.values(layoutGroups).map(group => {
                        // Merge all regions, variables, classifications from all results in this group
                        const allRegions = new Map();
                        const allVariabels = new Map();
                        const allKlasifikasis = new Map();
                        const allYearGroups = new Map();
                        let combinedData = [];

                        group.results.forEach(result => {
                            // Collect unique regions
                            if (result.selectedRegions) {
                                result.selectedRegions.forEach(region => {
                                    allRegions.set(region.id, region);
                                });
                            }
                            
                            // Collect unique variables
                            if (result.selectedVariabels) {
                                result.selectedVariabels.forEach(variabel => {
                                    allVariabels.set(variabel.id, variabel);
                                });
                            }
                            
                            // Collect unique classifications
                            if (result.selectedKlasifikasis) {
                                result.selectedKlasifikasis.forEach(klasifikasi => {
                                    allKlasifikasis.set(klasifikasi.id, klasifikasi);
                                });
                            }
                            
                            // Collect year groups
                            if (result.yearGroups) {
                                result.yearGroups.forEach(yearGroup => {
                                    allYearGroups.set(yearGroup.year, yearGroup);
                                });
                            }
                            
                            // Combine raw data
                            if (result.data && Array.isArray(result.data)) {
                                combinedData = combinedData.concat(result.data);
                            }
                        });

                        // Create consolidated result
                        const consolidated = {
                            layout: group.layout,
                            topik: group.topik,
                            topik_nama: group.topik_nama,
                            queueItems: group.results.map(r => r.queueItem),
                            selectedRegions: Array.from(allRegions.values()),
                            selectedVariabels: Array.from(allVariabels.values()),
                            selectedKlasifikasis: Array.from(allKlasifikasis.values()),
                            yearGroups: Array.from(allYearGroups.values()).sort((a, b) => a.year - b.year),
                            data: combinedData,
                            resultCount: group.results.length,
                            
                            // Create dataMap for efficient lookup
                            dataMap: new Map()
                        };
                        
                        // Build efficient data lookup map
                        combinedData.forEach(item => {
                            const key = `${item.id_wilayah}_${item.id_variabel}_${item.id_klasifikasi}_${item.tahun}_${item.id_bulan}`;
                            consolidated.dataMap.set(key, item);
                        });

                        return consolidated;
                    });
                },

                async loadInitialData() {
                    try {
                        console.log('Loading initial data from APIs...');
                        
                        // Load basic reference data
                        const [topiks, regions, bulans, years] = await Promise.all([
                            fetch('/api/benih-pupuk/topiks').then(r => r.json()),
                            fetch('/api/benih-pupuk/provinces').then(r => r.json()),
                            fetch('/api/benih-pupuk/bulans').then(r => r.json()),
                            fetch('/api/benih-pupuk/years').then(r => r.json())
                        ]);

                        console.log('API responses:', { topiks, regions: regions.length, bulans: bulans.length, years: years.length });

                        this.topiks = topiks;
                        this.availableRegions = regions;
                        this.bulans = bulans;
                        this.years = years;

                        console.log('Data assigned successfully');

                    } catch (error) {
                        console.error('Error loading initial data:', error);
                        // Fallback to basic data if API fails
                        this.years = Array.from({length: 12}, (_, i) => 2014 + i);
                        this.bulans = [
                            { id: 1, nama: 'Januari' }, { id: 2, nama: 'Februari' }, { id: 3, nama: 'Maret' },
                            { id: 4, nama: 'April' }, { id: 5, nama: 'Mei' }, { id: 6, nama: 'Juni' },
                            { id: 7, nama: 'Juli' }, { id: 8, nama: 'Agustus' }, { id: 9, nama: 'September' },
                            { id: 10, nama: 'Oktober' }, { id: 11, nama: 'November' }, { id: 12, nama: 'Desember' }
                        ];
                        this.topiks = [
                            { id: 1, nama: 'Benih' },
                            { id: 2, nama: 'Pupuk' }
                        ];
                        this.availableRegions = [
                            { id: 1, nama: 'Aceh' },
                            { id: 2, nama: 'Sumatera Utara' },
                            { id: 3, nama: 'Sumatera Barat' }
                        ];
                async verifyDisplayedData() {
                    console.log('=== DATA VERIFICATION ===');
                    // ... (no changes)
                },

                loadVariabels() {
                    this.selection.variabel_id = null;
                    this.selection.klasifikasi_ids = [];
                },

                loadKlasifikasis() {
                    this.selection.klasifikasi_ids = [];
                },

                get filteredTopik() {
                    if (!this.search.topik) return this.allData.topiks;
                    return this.allData.topiks.filter(t => t.nama.toLowerCase().includes(this.search.topik.toLowerCase()));
                },

                get filteredVariabel() {
                    if (!this.selection.topik_id) return [];
                    let variabels = this.allData.variabels.filter(v => v.topik_id == this.selection.topik_id);
                    if (this.search.variabel) {
                        variabels = variabels.filter(v => v.nama.toLowerCase().includes(this.search.variabel.toLowerCase()));
                    }
                    return variabels;
                },

                get filteredKlasifikasi() {
                    if (!this.selection.variabel_id) return [];
                    let klasifikasis = this.allData.klasifikasis.filter(k => k.variabel_id == this.selection.variabel_id);
                    if (this.search.klasifikasi) {
                        klasifikasis = klasifikasis.filter(k => k.nama.toLowerCase().includes(this.search.klasifikasi.toLowerCase()));
                    }
                    return klasifikasis;
                },

                get filteredTahun() {
                    if (!this.search.tahun) return this.allData.tahuns;
                    return this.allData.tahuns.filter(y => y.toString().includes(this.search.tahun));
                },

                get filteredBulan() {
                    if (!this.search.bulan) return this.allData.bulans;
                    return this.allData.bulans.filter(b => b.nama.toLowerCase().includes(this.search.bulan.toLowerCase()));
                },

                get filteredWilayah() {
                    if (!this.search.wilayah) return this.allData.wilayahs;
                    const search = this.search.wilayah.toLowerCase();
                    return this.allData.wilayahs.map(prov => {
                        const filteredKab = prov.kabupaten.filter(kab => kab.nama.toLowerCase().includes(search));
                        if (prov.nama.toLowerCase().includes(search) || filteredKab.length > 0) {
                            return { ...prov, kabupaten: prov.nama.toLowerCase().includes(search) ? prov.kabupaten : filteredKab };
                        }
                        return null;
                    }).filter(Boolean);
                },

                getQueueSummary(queueItem) {
                    const topik = queueItem.topik === '1' ? 'Benih' : 'Pupuk';
                    // Handle both old format (variabels array) and new format (selectedVariabel single value)
                    const variabelCount = queueItem.variabels ? queueItem.variabels.length : (queueItem.selectedVariabel ? 1 : 0);
                    const klasifikasiCount = queueItem.klasifikasis.length;
                    const regionCount = queueItem.selectedRegions.length;
                    
                    let timeRange = '';
                    if (queueItem.yearMode === 'all') {
                        timeRange = 'Semua tahun';
                    } else if (queueItem.yearMode === 'specific') {
                        timeRange = `${queueItem.selectedYears.length} tahun`;
                    } else {
                        timeRange = `${queueItem.tahun_awal}-${queueItem.tahun_akhir}`;
                    }
                    
                    return `${topik} • ${variabelCount} variabel • ${klasifikasiCount} klasifikasi • ${regionCount} wilayah • ${timeRange}`;
                },

                getVariabelNames(queueItem) {
                    // First try to get from API response if available
                    const currentResult = this.searchResults.find(r => r.queueItem === queueItem);
                    if (currentResult && currentResult.selectedVariabels && currentResult.selectedVariabels.length > 0) {
                        const variabels = currentResult.selectedVariabels;
                        if (variabels.length <= 2) {
                            return variabels.map(v => v.deskripsi || v.nama || 'Unknown').join(', ');
                        }
                        return `${variabels[0].deskripsi || variabels[0].nama || 'Unknown'}, ${variabels[1].deskripsi || variabels[1].nama || 'Unknown'} (+${variabels.length - 2} lainnya)`;
                    }
                    
                    // Fallback to original method - handle both formats
                    if (queueItem.variabels && this.availableVariabels) {
                        // New format: variabels array
                        const selectedVariabels = this.availableVariabels.filter(v => queueItem.variabels.includes(v.id));
                        if (selectedVariabels.length === 0) return 'Default Variabel';
                        return selectedVariabels.map(v => v.deskripsi).join(', ');
                    } else if (queueItem.selectedVariabel && this.availableVariabels) {
                        // Old format: selectedVariabel single value
                        const selectedVariabel = this.availableVariabels.find(v => v.id === queueItem.selectedVariabel);
                        if (!selectedVariabel) return 'Default Variabel';
                        return selectedVariabel.deskripsi;
                    }
                    return 'Default Variabel';
                },

                getKlasifikasiNames(queueItem) {
                    // First try to get from API response if available
                    const currentResult = this.searchResults.find(r => r.queueItem === queueItem);
                    if (currentResult && currentResult.selectedKlasifikasis && currentResult.selectedKlasifikasis.length > 0) {
                        const klasifikasis = currentResult.selectedKlasifikasis;
                        if (klasifikasis.length <= 3) {
                            return klasifikasis.map(k => k.deskripsi || k.nama || 'Unknown').join(', ');
                        }
                        return `${klasifikasis.slice(0, 3).map(k => k.deskripsi || k.nama || 'Unknown').join(', ')} (+${klasifikasis.length - 3} lainnya)`;
                    }
                    
                    // Fallback to original method
                    if (!queueItem.klasifikasis || !this.availableKlasifikasis) return 'Default Klasifikasi';
                    const selectedKlasifikasis = this.availableKlasifikasis.filter(k => queueItem.klasifikasis.includes(k.id));
                    if (selectedKlasifikasis.length === 0) return 'Default Klasifikasi';
                    if (selectedKlasifikasis.length <= 3) {
                        return selectedKlasifikasis.map(k => k.deskripsi).join(', ');
                    }
                    return `${selectedKlasifikasis.slice(0, 3).map(k => k.deskripsi).join(', ')} (+${selectedKlasifikasis.length - 3} lainnya)`;
                },

                getRegionName(regionOrId) {
                    // Handle both region objects and region IDs
                    if (typeof regionOrId === 'object' && regionOrId.nama) {
                        return regionOrId.nama;
                    }
                    // Handle region ID (number or string)
                    const region = this.availableRegions.find(r => r.id == regionOrId);
                    return region ? region.nama : `Wilayah ${regionOrId}`;
                },

                getPeriodSummary(queueItem) {
                    let yearSummary = '';
                    if (queueItem.yearMode === 'all') {
                        yearSummary = 'Semua tahun';
                    } else if (queueItem.yearMode === 'specific') {
                        const years = queueItem.selectedYears.sort();
                        yearSummary = years.length <= 3 ? years.join(', ') : `${years.slice(0, 3).join(', ')} (+${years.length - 3})`;
                    } else {
                        yearSummary = `${queueItem.tahun_awal}-${queueItem.tahun_akhir}`;
                    }

                    let monthSummary = '';
                    if (queueItem.monthMode === 'all') {
                        monthSummary = 'Semua bulan';
                    } else if (queueItem.monthMode === 'specific') {
                        const months = this.bulans.filter(b => queueItem.selectedMonths.includes(b.id));
                        monthSummary = months.length <= 3 ? months.map(m => m.nama.substring(0, 3)).join(', ') : 
                                     `${months.slice(0, 3).map(m => m.nama.substring(0, 3)).join(', ')} (+${months.length - 3})`;
                    } else {
                        const startMonth = this.bulans.find(b => b.id == queueItem.bulan_awal)?.nama.substring(0, 3) || '';
                        const endMonth = this.bulans.find(b => b.id == queueItem.bulan_akhir)?.nama.substring(0, 3) || '';
                        monthSummary = `${startMonth}-${endMonth}`;
                    }

                    return `${yearSummary} | ${monthSummary}`;
                },

                exportSingleResult(result, index) {
                    const wb = XLSX.utils.book_new();
                    
                    // Export the enhanced table data based on display type
                    let wsData = [];
                    
                    if (result.queueItem.tata_letak === 'tipe_1') {
                        // Multi-level header for Tipe 1
                        const mainHeaders = ['Variabel'];
                        const subHeaders = [''];
                        
                        result.yearGroups.forEach(yearGroup => {
                            yearGroup.months.forEach(month => {
                                mainHeaders.push(`${yearGroup.year}`);
                                subHeaders.push(month.nama.substring(0, 3));
                            });
                        });
                        
                        wsData.push(mainHeaders);
                        wsData.push(subHeaders);
                        
                        // Data rows
                        result.detailData.forEach(row => {
                            wsData.push([`${row.variabel} (${row.klasifikasi})`, ...row.values]);
                        });
                        
                        // Regional totals
                        result.regionTotals.forEach(region => {
                            wsData.push([`TOTAL ${region.nama}`, ...region.totals]);
                        });
                        
                        // Grand total
                        wsData.push(['TOTAL KESELURUHAN', ...result.grandTotals]);
                        
                    } else {
                        // Fallback to simple structure
                        const headerRow = ['Label', ...result.headers];
                        wsData.push(headerRow);
                        
                        result.data.forEach(row => {
                            wsData.push([row.label, ...row.values]);
                        });
                        
                        if (result.totals) {
                            wsData.push(['TOTAL', ...result.totals]);
                        }
                    }
                    
                    const ws = XLSX.utils.aoa_to_sheet(wsData);
                    XLSX.utils.book_append_sheet(wb, ws, `Hasil_${result.resultIndex}`);
                    
                    const topikName = result.queueItem.topik === '1' ? 'benih' : 'pupuk';
                    XLSX.writeFile(wb, `laporan-${topikName}-hasil-${result.resultIndex}-${new Date().toISOString().split('T')[0]}.xlsx`);
                },

                // Enhanced getCellValue that searches across ALL search results
                getCellValue(regionId, year, monthId, variabelId, klasifikasiId) {
                    try {
                        // Search through ALL search results to find the data
                        // This allows data from different queue items to be combined
                        for (const result of this.searchResults) {
                            if (result.raw_data && Array.isArray(result.raw_data)) {
                                const targetRecord = result.raw_data.find(record => 
                                    String(record.id_wilayah) === String(regionId) &&
                                    String(record.tahun) === String(year) &&
                                    String(record.id_bulan) === String(monthId) &&
                                    String(record.id_variabel) === String(variabelId) &&
                                    String(record.id_klasifikasi) === String(klasifikasiId)
                                );
                                
                                if (targetRecord) {
                                    return parseFloat(targetRecord.nilai) || 0;
                                }
                            }
                        }
                        
                        return 0;
                    } catch (error) {
                        console.error('getCellValue error:', error);
                        return 0;
                    }
                },

                // Create a dataMap from the API response for efficient data lookup
                createDataMap(apiData, queueItem) {
                    const dataMap = {};
                    
                    try {
                        // Debug the API response structure
                        console.log('createDataMap called with:', {
                            hasApiData: !!apiData,
                            hasRawData: !!(apiData && apiData.raw_data),
                            rawDataLength: apiData && apiData.raw_data ? apiData.raw_data.length : 0,
                            sampleRawDataItem: apiData && apiData.raw_data && apiData.raw_data[0] ? apiData.raw_data[0] : null,
                            layoutType: queueItem.tata_letak
                        });
                        
                        // Check if we have raw data from the API
                        if (!apiData || !apiData.raw_data || !Array.isArray(apiData.raw_data)) {
                            console.log('No raw_data in API response, cannot create dataMap');
                            return dataMap;
                        }
                        
                        // Convert raw data to key-value map
                        apiData.raw_data.forEach(item => {
                            // Create key in format: regionId_year_monthId_variabelId_klasifikasiId
                            // Use the correct property names from the database result
                            const key = `${item.id_wilayah}_${item.tahun}_${item.id_bulan}_${item.id_variabel}_${item.id_klasifikasi}`;
                            dataMap[key] = parseFloat(item.nilai) || 0;
                        });
                        
                        console.log('DataMap created successfully:', {
                            totalEntries: apiData.raw_data.length,
                            uniqueKeys: Object.keys(dataMap).length,
                            sampleKeys: Object.keys(dataMap).slice(0, 5),
                            sampleValues: Object.values(dataMap).slice(0, 5),
                            firstFewItems: apiData.raw_data.slice(0, 3).map(item => ({
                                key: `${item.id_wilayah}_${item.tahun}_${item.id_bulan}_${item.id_variabel}_${item.id_klasifikasi}`,
                                value: item.nilai
                            }))
                        });
                        
                    } catch (error) {
                        console.error('Error creating dataMap:', error);
                    }
                    
                    return dataMap;
                },

                // Get aggregated value across all regions for tipe_2 and tipe_3 layouts
                getAggregatedValue(layoutType, klasifikasiId, variabelId, year, monthId) {
                    try {
                        // Find the current result context
                        let result = null;
                        for (const searchResult of this.searchResults) {
                            if (searchResult.dataMap && searchResult.queueItem.tata_letak === layoutType) {
                                result = searchResult;
                                break;
                            }
                        }
                        
                        if (!result || !result.dataMap || !result.selectedRegions) {
                            return 0;
                        }

                        // Sum values across all selected regions for this combination
                        let total = 0;
                        let count = 0;
                        
                        result.selectedRegions.forEach(region => {
                            const key = `${region.id}_${year}_${monthId}_${variabelId}_${klasifikasiId}`;
                            const value = result.dataMap[key];
                            if (value !== undefined) {
                                total += parseFloat(value);
                                count++;
                            }
                        });
                        
                        // Return average to match backend logic
                        return count > 0 ? total / count : 0;
                        
                    } catch (error) {
                        console.error('getAggregatedValue error:', error);
                        return 0;
                    }
                },

                formatNumber(value) {
                    if (value === null || value === undefined) return '-';
                    return new Intl.NumberFormat('id-ID', {
                        minimumFractionDigits: 0,
                        maximumFractionDigits: 2
                    }).format(value);
                },

                async exportToExcel() {
                    if (!this.searchResults) return;

                    try {
                        const response = await fetch('/api/benih-pupuk/export-excel', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json',
                            },
                            body: JSON.stringify({
                                headers: this.searchResults.headers,
                                rows: this.searchResults.rows,
                                config: this.config
                            })
                        });

                        if (!response.ok) {
                            throw new Error('Gagal membuat file Excel di server.');
                        }

                        const blob = await response.blob();
                        const url = window.URL.createObjectURL(blob);
                        const a = document.createElement('a');
                        a.style.display = 'none';
                        a.href = url;
                        a.download = 'laporan-benih-pupuk.xlsx';
                        document.body.appendChild(a);
                        a.click();
                        window.URL.revokeObjectURL(url);
                        document.body.removeChild(a);

                    } catch (error) {
                        console.error('Error exporting to Excel:', error);
                        alert('Terjadi kesalahan saat mengekspor data. Silakan periksa konsol untuk detailnya.');
                    }
                }
            };
        }
    </div>

@push('scripts')
<script>
    // This script is intentionally left blank.
    // The benihPupukForm() function is now defined within the main component script block.
</script>
@endpush
</div>

</x-layouts.landing>
