<x-layouts.landing title="Laporan Data Iklim dan OPT DPI">
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
                            <span class="ml-1 text-blue-600 font-medium">Laporan Data Iklim dan OPT DPI</span>
                        </div>
                    </li>
                </ol>
            </nav>

            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-3xl md:text-4xl font-bold text-neutral-900 mb-4">
                    Laporan Data Iklim dan OPT DPI
                </h1>
                <p class="text-xl text-neutral-600">
                    Analisis data iklim, OPT, dan DPI di Indonesia.
                </p>
            </div>

            <!-- Two Column Layout -->
<script>
    const iklimOptDpiInitialData = {
        topiks: @json($topiks),
        variabels: @json($variabels),
        klasifikasis: @json($klasifikasis),
        tahuns: @json($tahuns),
        bulans: @json($bulans),
        wilayahs: @json($wilayahs)
    };

    function iklimOptDpiForm() {
        return {
            init(data) {
                this.allData = data;
                this.$watch('wilayahLevel', () => {
                    this.selectedProvinsiId = null;
                });
                this.$watch('selectedProvinsiId', () => {
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
                tata_letak: 'tipe_1',
                wilayah: {
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
                    isKlasifikasiValid &&
                    this.selection.tahun_ids.length > 0 &&
                    this.selection.bulan_ids.length > 0;
            },

            addSelection() {
                if (!this.isSelectionValid()) return;

                const topik = this.allData.topiks.find(t => t.id === this.selection.topik_id);
                const variabel = this.allData.variabels.find(v => v.id === this.selection.variabel_id);
                
                // Map klasifikasi IDs to names with proper ID comparison
                const klasifikasiNames = this.selection.klasifikasi_ids.map(id => {
                    const klasifikasi = this.allData.klasifikasis.find(k => k.id == id); // Use == for loose comparison
                    return klasifikasi?.nama || '';
                }).filter(name => name !== ''); // Remove empty names
                
                const tahun_awal = Math.min(...this.selection.tahun_ids);
                const tahun_akhir = Math.max(...this.selection.tahun_ids);
                const bulan_awal = this.allData.bulans.find(b => b.id == Math.min(...this.selection.bulan_ids))?.nama || '';
                const bulan_akhir = this.allData.bulans.find(b => b.id == Math.max(...this.selection.bulan_ids))?.nama || '';

                const newSelection = {
                    id: Date.now(),
                    topik_nama: topik?.nama || '',
                    variabel_nama: variabel?.nama || '',
                    variabel_satuan: variabel?.satuan || '',
                    klasifikasi_nama: klasifikasiNames.join(', ') || 'Semua',
                    tahun_awal: tahun_awal,
                    tahun_akhir: tahun_akhir,
                    bulan_awal: bulan_awal,
                    bulan_akhir: bulan_akhir,
                    // Store the actual selection data for processing
                    topik_id: this.selection.topik_id,
                    variabel_id: this.selection.variabel_id,
                    klasifikasi_ids: [...this.selection.klasifikasi_ids],
                    tahun_ids: [...this.selection.tahun_ids],
                    bulan_ids: [...this.selection.bulan_ids]
                };
                
                // Debug log to check data
                console.log('Adding selection:', newSelection);
                console.log('Klasifikasi IDs:', this.selection.klasifikasi_ids);
                console.log('Available klasifikasis:', this.allData.klasifikasis);
                
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
                    tata_letak: 'tipe_1',
                    wilayah: {
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
                            // Only send provinsi_ids if we're at nasional level
                            // Only send kabupaten_ids if we're at provinsi level 
                            provinsi_ids: this.wilayahLevel === 'nasional' ? this.selection.provinsi_ids : [],
                            kabupaten_ids: this.wilayahLevel === 'provinsi' ? this.selection.kabupaten_ids : [],
                        }
                    };

                    console.log('Sending payload:', payload);
                    console.log('Selection state:', this.selection);
                    console.log('Wilayah level:', this.wilayahLevel);

                    const response = await fetch('/api/iklim-opt-dpi/filter', {
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
                    console.log('Results headers:', results.headers);
                    console.log('Results data:', results.data);
                    console.log('Results data length:', results.data ? results.data.length : 0);
                    
                    // Add debugging for first data row
                    if (results.data && results.data.length > 0) {
                        console.log('First row wilayah:', results.data[0].wilayah);
                        console.log('First row values:', results.data[0].values);
                    }

                    // Store the results
                    const resultIndex = this.storedResults.length + 1;
                    const storedResult = {
                        id: Date.now(),
                        title: `Hasil ${resultIndex}`,
                        timestamp: new Date().toLocaleString('id-ID'),
                        results: results,
                        config: { tata_letak: this.selection.tata_letak },
                        selections: this.selections.map(s => ({ ...s })) // Deep copy
                    };

                    this.storedResults.push(storedResult);
                    this.selectedResultIndex = this.storedResults.length - 1;
                    this.searchResults = results;

                    // DO NOT clear selections here - keep them for multiple processing

                } catch (error) {
                    alert('Terjadi kesalahan saat mengambil data: ' + error.message);
                } finally {
                    this.isProcessing = false;
                }
            },

            renderChart() {
                const canvas = document.getElementById('dynamicChart');
                if (!canvas) return;
                
                const ctx = canvas.getContext('2d');
                
                // Destroy existing chart
                if (window.myChart) {
                    window.myChart.destroy();
                }

                if (!this.searchResults || !this.searchResults.data || this.searchResults.data.length === 0) {
                    return;
                }

                // Process data for chart
                const labels = this.dynamicRows.map(row => row.wilayah);
                const datasets = [];
                
                const colors = [
                    'rgba(54, 162, 235, 0.8)',
                    'rgba(255, 99, 132, 0.8)',
                    'rgba(75, 192, 192, 0.8)',
                    'rgba(255, 206, 86, 0.8)',
                    'rgba(153, 102, 255, 0.8)',
                    'rgba(255, 159, 64, 0.8)',
                    'rgba(201, 203, 207, 0.8)',
                    'rgba(255, 159, 64, 0.8)',
                    'rgba(54, 162, 235, 0.6)',
                    'rgba(255, 99, 132, 0.6)',
                    'rgba(75, 192, 192, 0.6)',
                    'rgba(255, 206, 86, 0.6)'
                ];

                // Generate column labels from headers - this ensures we include ALL columns
                const columnLabels = [];
                if (this.dynamicHeaders && this.dynamicHeaders.length > 0) {
                    // Get the bottom-most header row which contains the actual column names
                    const lastHeaderRow = this.dynamicHeaders[this.dynamicHeaders.length - 1];
                    lastHeaderRow.forEach(header => {
                        if (header.name !== 'Wilayah') {
                            columnLabels.push(header.name);
                        }
                    });
                }

                // Create datasets for each column (based on headers, not just first row)
                if (this.dynamicRows.length > 0 && columnLabels.length > 0) {
                    columnLabels.forEach((columnLabel, index) => {
                        const data = this.dynamicRows.map(row => {
                            const value = row.values ? row.values[index] : null;
                            return value !== null && value !== undefined ? parseFloat(value) || 0 : 0;
                        });
                        
                        datasets.push({
                            label: columnLabel,
                            data: data,
                            backgroundColor: colors[index % colors.length],
                            borderColor: colors[index % colors.length].replace('0.8', '1'),
                            borderWidth: 1
                        });
                    });
                }

                // Ensure canvas is wide enough to show all provinces; enables horizontal scroll
                const scrollWrap = document.getElementById('chart-scroll');
                const containerWidth = scrollWrap ? scrollWrap.clientWidth : 800;
                const containerHeight = scrollWrap ? scrollWrap.clientHeight : 384;
                const perLabelWidth = Math.max(70, (datasets.length || 1) * 18 + 40);
                const desiredWidth = Math.max(containerWidth, (labels.length || 1) * perLabelWidth);
                ctx.canvas.style.width = desiredWidth + 'px';
                ctx.canvas.style.height = containerHeight + 'px';
                ctx.canvas.width = desiredWidth; // important when responsive:false
                ctx.canvas.height = containerHeight;

                window.myChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: datasets
                    },
                    options: {
                        // Use fixed sizing so canvas width drives horizontal scroll
                        responsive: false,
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        },
                        plugins: {
                            legend: {
                                display: this.showLegend,
                                position: 'top'
                            }
                        }
                    }
                });
            },

            toggleLegend() {
                this.showLegend = !this.showLegend;
                this.renderChart();
            },

            scrollToProvince() {
                if (!this.selectedProvinceForScroll) return;
                
                const scrollWrap = document.getElementById('chart-scroll');
                if (!scrollWrap) return;
                
                const labels = this.dynamicRows.map(row => row.wilayah);
                const index = labels.indexOf(this.selectedProvinceForScroll);
                if (index === -1) return;
                
                const perLabelWidth = Math.max(70, 120);
                const scrollPosition = index * perLabelWidth;
                const containerWidth = scrollWrap.clientWidth;
                const next = Math.max(0, scrollPosition - containerWidth / 2);
                scrollWrap.scrollTo({ left: next, behavior: 'smooth' });
            },

            // Keep chart sizing responsive to viewport changes while preserving horizontal scroll
            initChartResizeHandlerOnce: (function() {
                let initialized = false;
                return function() {
                    if (!initialized) {
                        initialized = true;
                        window.addEventListener('resize', () => {
                            this.renderChart();
                        });
                    }
                };
            })(),

            exportExcel() {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '/api/iklim-opt-dpi/export';
                form.style.display = 'none';
                
                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                form.appendChild(csrfToken);
                
                const dataInput = document.createElement('input');
                dataInput.type = 'hidden';
                dataInput.name = 'data';
                dataInput.value = JSON.stringify(this.searchResults);
                form.appendChild(dataInput);
                
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
                return this.allData.topiks;
            },

            get filteredVariabel() {
                if (!this.selection.topik_id) return [];
                return this.allData.variabels.filter(v => v.topik_id == this.selection.topik_id);
            },

            get filteredKlasifikasi() {
                if (!this.selection.variabel_id) return [];
                return this.allData.klasifikasis.filter(k => k.variabel_id == this.selection.variabel_id);
            },

            get filteredTahun() {
                return this.allData.tahuns.filter(t => t.toString().includes(this.search.tahun));
            },

            get filteredBulan() {
                return this.allData.bulans.filter(b => b.nama.toLowerCase().includes(this.search.bulan.toLowerCase()));
            },

            toggleKabupaten(id) {
                const index = this.selection.kabupaten_ids.indexOf(id);
                if (index > -1) {
                    this.selection.kabupaten_ids.splice(index, 1);
                } else {
                    this.selection.kabupaten_ids.push(id);
                }
            },

            selectAllKabupatenInSelectedProvinsi() {
                if (!this.selectedProvinsiId) return;
                
                const selectedProvinsi = this.allData.wilayahs.find(p => p.id == this.selectedProvinsiId);
                if (selectedProvinsi && selectedProvinsi.kabupaten) {
                    this.selection.kabupaten_ids = selectedProvinsi.kabupaten.map(k => k.id);
                }
            },

            clearKabupatenInSelectedProvinsi() {
                if (!this.selectedProvinsiId) {
                    this.selection.kabupaten_ids = [];
                    return;
                }
                
                // Only clear kabupaten from the selected provinsi
                const selectedProvinsi = this.allData.wilayahs.find(p => p.id == this.selectedProvinsiId);
                if (selectedProvinsi && selectedProvinsi.kabupaten) {
                    const kabupatenIdsInSelectedProvinsi = selectedProvinsi.kabupaten.map(k => k.id);
                    this.selection.kabupaten_ids = this.selection.kabupaten_ids.filter(id => 
                        !kabupatenIdsInSelectedProvinsi.includes(id)
                    );
                }
            },

            toggleWilayah(id) {
                if (this.wilayahLevel === 'nasional') {
                    const index = this.selection.provinsi_ids.indexOf(id);
                    if (index > -1) {
                        this.selection.provinsi_ids.splice(index, 1);
                    } else {
                        this.selection.provinsi_ids.push(id);
                    }
                } else {
                    this.toggleKabupaten(id);
                }
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
                processedSelections.push({
                    index: selIndex,
                    variabel: sel.variabel_nama,
                    klasifikasis: sel.klasifikasi_nama.split(', '),
                    tahuns: sel.tahun_ids.sort((a, b) => a - b),
                    bulans: sel.bulan_ids.map(bid => this.allData.bulans.find(b => b.id == bid)?.nama).filter(Boolean)
                });
            });
            
            if (processedSelections.length === 0) return [];
            
            // Generate headers based on layout - EXACT same logic as backend
            if (layout === 'tipe_1') {
                // Variabel » Klasifikasi » Tahun » Bulan
                return this.generateTipe1Headers(processedSelections);
            }
            
            if (layout === 'tipe_2') {
                // Klasifikasi » Variabel » Tahun » Bulan
                return this.generateTipe2Headers(processedSelections);
            }
            
            if (layout === 'tipe_3') {
                // Tahun » Bulan » Variabel » Klasifikasi  
                return this.generateTipe3Headers(processedSelections);
            }
            
            // Fallback to simple headers
            return [[
                ...columnOrder.map(col => ({ name: col.charAt(0).toUpperCase() + col.slice(1), span: 1, rowspan: 1 }))
            ]];
        },

        get dynamicRows() {
            const currentResult = this.selectedResultIndex !== null ? this.storedResults[this.selectedResultIndex] : null;
            if (!currentResult || !currentResult.results || !currentResult.results.data || currentResult.results.data.length === 0) {
                return [];
            }
            
            // Sort by wilayah_sorter ascending (not by wilayah name alphabetically)
            const rows = [...currentResult.results.data];
            rows.sort((a, b) => {
                // Use wilayah_sorter if available, otherwise fall back to wilayah name
                const sorterA = a.wilayah_sorter !== undefined ? a.wilayah_sorter : 999;
                const sorterB = b.wilayah_sorter !== undefined ? b.wilayah_sorter : 999;
                
                if (sorterA !== sorterB) {
                    return sorterA - sorterB;
                }
                
                // Fallback to alphabetical if sorters are same
                return String(a.wilayah).localeCompare(String(b.wilayah));
            });
            return rows.map(r => ({
                ...r,
                // Format numeric values for display
                ...Object.keys(r).reduce((acc, key) => {
                    if (key !== 'wilayah' && key !== 'wilayah_sorter' && typeof r[key] === 'number') {
                        acc[key] = r[key].toFixed(2);
                    } else {
                        acc[key] = r[key];
                    }
                    return acc;
                }, {})
            }));
        },

        // Helper function
        getColumnOrderKeys(layout) {
            if (layout === 'tipe_1') return ['variabel', 'klasifikasi', 'tahun', 'bulan'];
            if (layout === 'tipe_2') return ['klasifikasi', 'variabel', 'tahun', 'bulan'];
            if (layout === 'tipe_3') return ['tahun', 'bulan', 'variabel', 'klasifikasi'];
            return [];
        },

        // Helper methods for generating headers based on layout type
        generateTipe1Headers(processedSelections) {
            // Variabel » Klasifikasi » Tahun » Bulan
            const headers = [];
            
            // Row 1: Variabel headers
            const row1 = [{ name: 'Wilayah', span: 1, rowspan: 4 }];
            processedSelections.forEach(sel => {
                const variabelSpan = sel.klasifikasis.length * sel.tahuns.length * sel.bulans.length;
                row1.push({ name: sel.variabel, span: variabelSpan, rowspan: 1 });
            });
            headers.push(row1);
            
            // Row 2: Klasifikasi headers
            const row2 = [];
            processedSelections.forEach(sel => {
                sel.klasifikasis.forEach(klasifikasi => {
                    const klasifikasiSpan = sel.tahuns.length * sel.bulans.length;
                    row2.push({ name: klasifikasi, span: klasifikasiSpan, rowspan: 1 });
                });
            });
            headers.push(row2);
            
            // Row 3: Tahun headers
            const row3 = [];
            processedSelections.forEach(sel => {
                sel.klasifikasis.forEach(() => {
                    sel.tahuns.forEach(tahun => {
                        const tahunSpan = sel.bulans.length;
                        row3.push({ name: tahun.toString(), span: tahunSpan, rowspan: 1 });
                    });
                });
            });
            headers.push(row3);
            
            // Row 4: Bulan headers
            const row4 = [];
            processedSelections.forEach(sel => {
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
        },

        generateTipe2Headers(processedSelections) {
            // Klasifikasi » Variabel » Tahun » Bulan
            const headers = [];
            
            // Collect all unique klasifikasis first
            const allKlasifikasis = [];
            processedSelections.forEach(sel => {
                sel.klasifikasis.forEach(klasifikasi => {
                    if (!allKlasifikasis.includes(klasifikasi)) {
                        allKlasifikasis.push(klasifikasi);
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
                row1.push({ name: klasifikasi, span: klasifikasiSpan, rowspan: 1 });
            });
            headers.push(row1);
            
            // Row 2: Variabel headers
            const row2 = [];
            allKlasifikasis.forEach(klasifikasi => {
                processedSelections.forEach(sel => {
                    if (sel.klasifikasis.includes(klasifikasi)) {
                        const variabelSpan = sel.tahuns.length * sel.bulans.length;
                        row2.push({ name: sel.variabel, span: variabelSpan, rowspan: 1 });
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
                            const tahunSpan = sel.bulans.length;
                            row3.push({ name: tahun.toString(), span: tahunSpan, rowspan: 1 });
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
        },

        generateTipe3Headers(processedSelections) {
            // Tahun » Bulan » Variabel » Klasifikasi
            const headers = [];
            
            // Collect all unique tahuns and bulans
            const allTahuns = [];
            const allBulans = [];
            processedSelections.forEach(sel => {
                sel.tahuns.forEach(tahun => {
                    if (!allTahuns.includes(tahun)) {
                        allTahuns.push(tahun);
                    }
                });
                sel.bulans.forEach(bulan => {
                    if (!allBulans.includes(bulan)) {
                        allBulans.push(bulan);
                    }
                });
            });
            allTahuns.sort((a, b) => a - b);
            
            // Row 1: Tahun headers
            const row1 = [{ name: 'Wilayah', span: 1, rowspan: 4 }];
            allTahuns.forEach(tahun => {
                let tahunSpan = 0;
                allBulans.forEach(bulan => {
                    processedSelections.forEach(sel => {
                        if (sel.tahuns.includes(tahun) && sel.bulans.includes(bulan)) {
                            tahunSpan += sel.klasifikasis.length;
                        }
                    });
                });
                row1.push({ name: tahun.toString(), span: tahunSpan, rowspan: 1 });
            });
            headers.push(row1);
            
            // Row 2: Bulan headers
            const row2 = [];
            allTahuns.forEach(tahun => {
                allBulans.forEach(bulan => {
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
                allBulans.forEach(bulan => {
                    processedSelections.forEach(sel => {
                        if (sel.tahuns.includes(tahun) && sel.bulans.includes(bulan)) {
                            const variabelSpan = sel.klasifikasis.length;
                            row3.push({ name: sel.variabel, span: variabelSpan, rowspan: 1 });
                        }
                    });
                });
            });
            headers.push(row3);
            
            // Row 4: Klasifikasi headers
            const row4 = [];
            allTahuns.forEach(tahun => {
                allBulans.forEach(bulan => {
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
        };
    }
</script>


                    <div x-data="iklimOptDpiForm()" x-init="init(iklimOptDpiInitialData)" class="space-y-12">
                        <!-- Step 1: Select Data -->
<section class="bg-neutral-50 rounded-lg p-6 border border-neutral-200">
    <h2 class="text-2xl font-bold text-neutral-800 mb-1 flex items-center">
        <span class="bg-blue-600 text-white rounded-full h-8 w-8 flex items-center justify-center mr-3">1</span>
        Pilih Data
    </h2>
    <p class="text-neutral-600 mb-4 ml-11">Pilih Topik, Variabel, Klasifikasi, serta Periode Waktu.</p>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Left column: Topik -->
        <div class="bg-white p-4 rounded-lg border">
            <h3 class="font-semibold text-neutral-900 mb-3">1.1 Topik</h3>
            <div class="overflow-y-auto border rounded-md p-2 max-h-40">
                <template x-for="topik in allData.topiks" :key="topik.id">
                    <div @click="selectTopik(topik.id)" class="flex items-center cursor-pointer hover:bg-blue-50 p-2 rounded-md" :class="{'bg-blue-100 font-semibold': selection.topik_id === topik.id}">
                        <span class="ml-2 text-sm text-neutral-700" x-text="topik.nama"></span>
                    </div>
                </template>
            </div>
        </div>

        <!-- Right column: Variabel + Klasifikasi stacked -->
        <div class="space-y-4 h-full flex flex-col">
            <!-- 1.2 Variabel -->
            <div class="bg-white p-4 rounded-lg border flex-1 flex flex-col">
                <h3 class="font-semibold text-neutral-900 mb-3">1.2 Variabel</h3>
                <div x-show="!selection.topik_id" class="text-center py-8 text-neutral-500">
                    <p class="text-sm">Pilih topik terlebih dahulu</p>
                </div>
                <div x-show="selection.topik_id" class="flex-1 flex flex-col">
                    <div class="flex-1 overflow-y-auto border rounded-md p-2 max-h-40">
                        <template x-for="variabel in filteredVariabel" :key="variabel.id">
                            <div @click="selectVariabel(variabel.id)" class="flex items-center cursor-pointer hover:bg-green-50 p-2 rounded-md" :class="{'bg-green-100 font-semibold': selection.variabel_id === variabel.id}">
                                <span class="ml-2 text-sm text-neutral-700 flex-1" x-text="variabel.nama + (variabel.satuan ? ' (' + variabel.satuan + ')' : '')"></span>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            <!-- 1.3 Klasifikasi -->
            <div class="bg-white p-4 rounded-lg border flex-1 flex flex-col">
                <h3 class="font-semibold text-neutral-900 mb-3">1.3 Klasifikasi</h3>
                <div x-show="!selection.variabel_id" class="text-center py-4 text-neutral-500 border rounded-md">
                    <p class="text-sm">Pilih variabel terlebih dahulu</p>
                </div>
                <div x-show="selection.variabel_id && filteredKlasifikasi.length > 0" class="flex-1 flex flex-col">
                    <div class="flex-1 overflow-y-auto border rounded-md p-2 max-h-32">
                        <template x-for="klasifikasi in filteredKlasifikasi" :key="klasifikasi.id">
                            <label class="flex items-center cursor-pointer p-2 rounded-md hover:bg-orange-50">
                                <input type="checkbox" :value="klasifikasi.id" x-model="selection.klasifikasi_ids" class="form-checkbox text-orange-600">
                                <span class="ml-2 text-sm flex-1" x-text="klasifikasi.nama"></span>
                            </label>
                        </template>
                    </div>
                </div>
                <div x-show="selection.variabel_id && filteredKlasifikasi.length === 0" class="text-center text-neutral-500 py-4 border rounded-md">
                    <p class="text-sm">Tidak ada klasifikasi untuk variabel ini</p>
                </div>
            </div>
        </div>
    </div>

    <!-- 1.4 Waktu with Tahun & Bulan stacked -->
    <div class="bg-white p-4 rounded-lg border mt-4">
        <h3 class="font-semibold text-neutral-900 mb-3">1.4 Waktu</h3>
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
            <!-- Tahun box -->
            <div class="flex flex-col border rounded-lg p-3">
                <div class="flex justify-between items-center mb-2">
                    <label class="block font-medium text-neutral-700 text-lg">Tahun</label>
                    <div class="flex gap-1">
                        <button @click="selection.tahun_ids = filteredTahun" class="text-xs text-blue-600 hover:underline">Select All</button>
                        <button @click="selection.tahun_ids = []" class="text-xs text-red-600 hover:underline">Clear</button>
                    </div>
                </div>
                <input type="text" x-model="search.tahun" placeholder="Cari tahun..." class="w-full px-2 py-1 border border-neutral-300 rounded text-sm mb-2">
                <div class="overflow-y-auto border rounded-md p-2 max-h-48">
                    <template x-for="tahun in filteredTahun" :key="tahun">
                        <label class="flex items-center cursor-pointer hover:bg-blue-50 p-2 rounded-md">
                            <input type="checkbox" :value="tahun" x-model="selection.tahun_ids" class="form-checkbox text-blue-600">
                            <span class="ml-2 text-sm text-neutral-700" x-text="tahun"></span>
                        </label>
                    </template>
                </div>
            </div>

            <!-- Bulan box -->
            <div class="flex flex-col border rounded-lg p-3">
                <div class="flex justify-between items-center mb-2">
                    <label class="block font-medium text-neutral-700 text-lg">Bulan</label>
                    <div class="flex gap-1">
                        <button @click="selection.bulan_ids = filteredBulan.map(b => b.id)" class="text-xs text-blue-600 hover:underline">Select All</button>
                        <button @click="selection.bulan_ids = []" class="text-xs text-red-600 hover:underline">Clear</button>
                    </div>
                </div>
                <input type="text" x-model="search.bulan" placeholder="Cari bulan..." class="w-full px-2 py-1 border border-neutral-300 rounded text-sm mb-2">
                <div class="overflow-y-auto border rounded-md p-2 max-h-48">
                    <template x-for="bulan in filteredBulan" :key="bulan.id">
                        <label class="flex items-center cursor-pointer hover:bg-indigo-50 p-2 rounded-md">
                            <input type="checkbox" :value="bulan.id" x-model="selection.bulan_ids" class="form-checkbox text-indigo-600">
                            <span class="ml-2 text-sm text-neutral-700" x-text="bulan.nama"></span>
                        </label>
                    </template>
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
                const colgroup = document.createElement('colgroup');
                const thead = table.querySelector('thead');
                if(!thead) return;
                const firstRow = thead.querySelector('tr:first-child');
                if(!firstRow) return;
                const cells = Array.from(firstRow.querySelectorAll('th'));
                cells.forEach((cell, idx) => {
                    const col = document.createElement('col');
                    if(idx === 0) {
                        col.style.minWidth = '120px';
                    } else {
                        col.style.minWidth = '100px';
                        col.style.width = '100px';
                    }
                    colgroup.appendChild(col);
                });
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

    <!-- Action Buttons Section -->
    <div class="flex items-center justify-between mb-4 mt-6">
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
                    <input type="checkbox" :value="item.id" x-model="selectedForRemoval" class="form-checkbox text-red-600 mr-3">
                    <div class="flex-1">
                        <div class="font-medium text-sm text-neutral-800">
                            <span class="text-blue-700" x-text="item.topik_nama"></span> » 
                            <span class="text-green-700" x-text="item.variabel_nama"></span>
                            <span class="text-gray-600 text-xs" x-text="item.variabel_satuan ? '(' + item.variabel_satuan + ')' : ''"></span>
                        </div>
                        <div class="text-xs text-neutral-600 mt-1">
                            <span class="text-orange-700" x-text="item.klasifikasi_nama || 'Semua'"></span> | 
                            <span class="text-purple-700" x-text="item.tahun_awal + (item.tahun_akhir !== item.tahun_awal ? '-' + item.tahun_akhir : '')"></span> | 
                            <span class="text-indigo-700" x-text="item.bulan_awal + (item.bulan_akhir !== item.bulan_awal ? '-' + item.bulan_akhir : '')"></span>
                        </div>
                    </div>
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
                <option value="nasional">Tingkat Nasional (Provinsi)</option>
                <option value="provinsi">Tingkat Provinsi (Kabupaten/Kota)</option>
            </select>

            <!-- Tingkat Nasional View -->
            <div x-show="wilayahLevel === 'nasional'">
                <div class="flex justify-between items-center mb-2">
                    <span class="font-medium text-neutral-800 text-sm">Pilih Provinsi</span>
                    <div class="flex gap-2">
                        <button @click="selection.provinsi_ids = filteredWilayah.map(p => p.id)" class="text-xs text-blue-600 hover:underline">Select All</button>
                        <button @click="selection.provinsi_ids = []" class="text-xs text-red-600 hover:underline">Clear</button>
                    </div>
                </div>
                <input type="text" x-model="search.wilayah" placeholder="Cari provinsi..." class="w-full px-3 py-2 border border-neutral-300 rounded-md mb-2 text-sm">
                <div class="overflow-y-auto border rounded-md p-2 max-h-[70vh]">
                    <template x-for="provinsi in filteredWilayah" :key="provinsi.id">
                        <label class="flex items-center cursor-pointer p-1 rounded-md hover:bg-blue-50">
                            <input type="checkbox" :value="provinsi.id" x-model="selection.provinsi_ids" @change="toggleWilayah(provinsi.id)" class="form-checkbox text-blue-600">
                            <span class="ml-2 text-sm font-medium text-neutral-800" x-text="provinsi.nama"></span>
                        </label>
                    </template>
                </div>
            </div>

            <!-- Tingkat Provinsi View -->
            <div x-show="wilayahLevel === 'provinsi'">
                <div class="mb-3">
                    <label class="block text-sm font-medium text-neutral-700 mb-1">Pilih Provinsi</label>
                    <select x-model="selectedProvinsiId" class="w-full px-3 py-2 border border-neutral-300 rounded-md text-sm">
                        <option value="">-- Pilih Provinsi --</option>
                        <template x-for="provinsi in allData.wilayahs" :key="provinsi.id">
                            <option :value="provinsi.id" x-text="provinsi.nama"></option>
                        </template>
                    </select>
                </div>
                
                <template x-if="selectedProvinsiId">
                    <div>
                        <div class="flex justify-between items-center mb-2">
                            <span class="font-medium text-neutral-800 text-sm">Pilih Kabupaten/Kota</span>
                            <div class="flex gap-2">
                                <button @click="selectAllKabupatenInSelectedProvinsi()" class="text-xs text-blue-600 hover:underline">Select All</button>
                                <button @click="clearKabupatenInSelectedProvinsi()" class="text-xs text-red-600 hover:underline">Clear</button>
                            </div>
                        </div>
                        <input type="text" x-model="search.wilayah" placeholder="Cari kabupaten/kota..." class="w-full px-3 py-2 border border-neutral-300 rounded-md mb-2 text-sm">
                        <div class="overflow-y-auto border rounded-md p-2 max-h-[70vh]">
                            <template x-for="provinsi in filteredWilayah" :key="provinsi.id">
                                <div x-show="provinsi.id == selectedProvinsiId">
                                    <template x-for="kabupaten in provinsi.kabupaten" :key="kabupaten.id">
                                        <label class="flex items-center cursor-pointer p-1 rounded-md hover:bg-green-50">
                                            <input type="checkbox" :value="kabupaten.id" x-model="selection.kabupaten_ids" @change="toggleKabupaten(kabupaten.id)" class="form-checkbox text-green-600">
                                            <span class="ml-2 text-sm" x-text="kabupaten.nama"></span>
                                        </label>
                                    </template>
                                </div>
                            </template>
                        </div>
                    </div>
                </template>
                <div x-show="!selectedProvinsiId" class="text-center text-neutral-500 py-4 text-sm">
                    Pilih provinsi terlebih dahulu
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
                            <p class="text-sm text-neutral-600 mb-2">Kolom diurutkan berdasarkan: Variabel » Klasifikasi » Tahun » Bulan</p>
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
                            <p class="text-sm text-neutral-600 mb-2">Kolom diurutkan berdasarkan: Klasifikasi » Variabel » Tahun » Bulan</p>
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
                            <p class="text-sm text-neutral-600 mb-2">Kolom diurutkan berdasarkan: Tahun » Bulan » Variabel » Klasifikasi</p>
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
                <div class="p-3 bg-neutral-50 border-b">
                    <h4 class="font-medium text-neutral-800 text-sm">Hasil Pencarian</h4>
                </div>
                <div class="max-h-80 overflow-y-auto">
                    <template x-for="(result, index) in storedResults" :key="result.id">
                        <button @click="selectStoredResult(index)" 
                                :class="{'bg-blue-50 border-l-4 border-l-blue-500': selectedResultIndex === index, 'hover:bg-neutral-50': selectedResultIndex !== index}"
                                class="w-full p-3 text-left border-b border-neutral-100 transition-colors">
                            <div class="font-medium text-sm text-neutral-800" x-text="result.title"></div>
                            <div class="text-xs text-neutral-500 mt-1" x-text="result.timestamp"></div>
                        </button>
                    </template>
                </div>
            </div>
        </div>

        <!-- Main Content Area -->
        <div class="flex-1 main-content-flex">
            <div x-show="selectedResultIndex !== null" class="bg-white rounded-lg border">
                <!-- Tab Navigation -->
                <div class="border-b border-neutral-200">
                    <nav class="-mb-px flex">
                        <button @click="activeResultTab = 'tabel'" 
                                :class="{'border-blue-500 text-blue-600': activeResultTab === 'tabel', 'border-transparent text-neutral-500 hover:text-neutral-700 hover:border-neutral-300': activeResultTab !== 'tabel'}"
                                class="whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm">
                            <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 6h18m-9 8h9"></path></svg>
                            Tabel
                        </button>
                        <button @click="activeResultTab = 'grafik'; $nextTick(() => renderChart())" 
                                :class="{'border-blue-500 text-blue-600': activeResultTab === 'grafik', 'border-transparent text-neutral-500 hover:text-neutral-700 hover:border-neutral-300': activeResultTab !== 'grafik'}"
                                class="whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm">
                            <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                            Grafik
                        </button>
                        <button @click="activeResultTab = 'metodologi'" 
                                :class="{'border-blue-500 text-blue-600': activeResultTab === 'metodologi', 'border-transparent text-neutral-500 hover:text-neutral-700 hover:border-neutral-300': activeResultTab !== 'metodologi'}"
                                class="whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm">
                            <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            Metodologi
                        </button>
                    </nav>
                </div>

                <!-- Tab Content -->
                <div class="p-6 table-tab-content">
                    <!-- Tabel Tab -->
                    <div x-show="activeResultTab === 'tabel'">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold text-neutral-900">Data Tabel</h3>
                            <button @click="exportExcel()" class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                Export Excel
                            </button>
                        </div>
                        
                        <!-- Dynamic Table with Complex Headers -->
                        <div class="sticky-table-container">
                            <table class="sticky-table">
                                <thead>
                                    <template x-for="(headerRow, rowIndex) in dynamicHeaders" :key="'header-row-' + rowIndex">
                                        <tr>
                                            <template x-for="(header, colIndex) in headerRow" :key="'header-' + rowIndex + '-' + colIndex">
                                                <th :colspan="header.span || 1" 
                                                    :rowspan="header.rowspan || 1" 
                                                    :data-row-index="rowIndex + 1"
                                                    :class="header.name === 'Wilayah' ? 'sticky-wilayah-header' : ''"
                                                    class="bg-neutral-50 font-medium text-neutral-900 text-xs"
                                                    x-text="header.name">
                                                </th>
                                            </template>
                                        </tr>
                                    </template>
                                </thead>
                                <tbody>
                                    <template x-for="(row, rowIndex) in dynamicRows" :key="'row-' + rowIndex">
                                        <tr class="hover:bg-neutral-50">
                                            <td x-text="row.wilayah" class="font-medium"></td>
                                            <template x-for="(value, valueIndex) in row.values" :key="'value-' + rowIndex + '-' + valueIndex">
                                                <td x-text="value !== null && value !== undefined ? (typeof value === 'number' ? value.toLocaleString('id-ID', {minimumFractionDigits: 2, maximumFractionDigits: 2}) : value) : '-'" class="text-right"></td>
                                            </template>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>
                        
                        <div x-show="dynamicRows.length === 0" class="text-center text-neutral-500 py-8">
                            Tidak ada data untuk ditampilkan
                        </div>
                    </div>

                    <!-- Grafik Tab -->
                    <div x-show="activeResultTab === 'grafik'">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold text-neutral-900">Visualisasi Data</h3>
                            <div class="flex items-center gap-3">
                                <div class="flex items-center">
                                    <input type="checkbox" x-model="showLegend" @change="toggleLegend()" class="form-checkbox text-blue-600 mr-2" id="showLegend">
                                    <label for="showLegend" class="text-sm text-neutral-700">Tampilkan Legend</label>
                                </div>
                                <select x-model="selectedProvinceForScroll" @change="scrollToProvince()" class="px-3 py-1 border border-neutral-300 rounded text-sm">
                                    <option value="">Scroll ke Provinsi...</option>
                                    <template x-for="row in dynamicRows" :key="row.wilayah">
                                        <option :value="row.wilayah" x-text="row.wilayah"></option>
                                    </template>
                                </select>
                            </div>
                        </div>
                        
                        <div id="chart-scroll" class="overflow-x-auto bg-neutral-50 rounded-lg border" style="height: 400px;">
                            <canvas id="dynamicChart" class="max-w-none"></canvas>
                        </div>
                        
                        <div x-show="dynamicRows.length === 0" class="text-center text-neutral-500 py-8">
                            Tidak ada data untuk divisualisasikan
                        </div>
                    </div>

                    <!-- Metodologi Tab -->
                    <div x-show="activeResultTab === 'metodologi'">
                        <div class="bg-white">
                            <h3 class="text-xl font-bold text-neutral-900 mb-6">Metodologi</h3>
                            
                            <div class="space-y-6">
                                <div>
                                    <h4 class="text-lg font-semibold text-neutral-800 mb-3">Sumber:</h4>
                                    <p class="text-neutral-700 leading-relaxed">Data iklim diperoleh dari Badan Meteorologi, Klimatologi dan Geofisika (BMKG).</p>
                                </div>

                                <div>
                                    <h4 class="text-lg font-semibold text-neutral-800 mb-3">Metodologi:</h4>
                                    <p class="text-neutral-700 leading-relaxed">Data iklim diperoleh dari data harian yang berasal dari hasil pengamatan di stasiun klimatologi BMKG.</p>
                                </div>

                                <div>
                                    <h4 class="text-lg font-semibold text-neutral-800 mb-3">Variabel Iklim:</h4>
                                    <ul class="list-disc list-inside text-neutral-700 space-y-2 pl-4">
                                        <li><strong>Curah Hujan:</strong> Data curah hujan harian yang diukur dalam milimeter (mm)</li>
                                        <li><strong>Suhu:</strong> Data suhu udara harian meliputi suhu maksimum, minimum, dan rata-rata dalam derajat Celsius (°C)</li>
                                        <li><strong>Kelembaban:</strong> Data kelembaban udara relatif dalam persentase (%)</li>
                                        <li><strong>Kecepatan Angin:</strong> Data kecepatan angin dalam meter per detik (m/s)</li>
                                        <li><strong>Penyinaran Matahari:</strong> Data penyinaran matahari dalam persentase (%)</li>
                                    </ul>
                                </div>

                                <div>
                                    <h4 class="text-lg font-semibold text-neutral-800 mb-3">Organisme Pengganggu Tumbuhan (OPT):</h4>
                                    <p class="text-neutral-700 leading-relaxed mb-3">Data OPT mencakup informasi tentang hama dan penyakit tanaman yang diamati berdasarkan:</p>
                                    <ul class="list-disc list-inside text-neutral-700 space-y-2 pl-4">
                                        <li>Intensitas serangan dalam persentase (%)</li>
                                        <li>Luas area terserang dalam hektar (ha)</li>
                                        <li>Jenis komoditas yang terserang</li>
                                        <li>Stadium perkembangan OPT</li>
                                    </ul>
                                </div>

                                <div>
                                    <h4 class="text-lg font-semibold text-neutral-800 mb-3">Klasifikasi Data:</h4>
                                    <p class="text-neutral-700 leading-relaxed">Data diklasifikasikan berdasarkan wilayah administratif (provinsi/kabupaten), periode waktu (bulanan/tahunan), dan jenis variabel iklim/OPT yang diamati.</p>
                                </div>

                                <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded-r-lg">
                                    <div class="flex">
                                        <div class="flex-shrink-0">
                                            <svg class="h-5 w-5 text-blue-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <h4 class="text-lg font-semibold text-blue-800 mb-2">Catatan Penting:</h4>
                                            <p class="text-blue-700 leading-relaxed">Data yang disajikan merupakan hasil pengamatan stasiun meteorologi dan klimatologi BMKG yang telah melalui proses quality control dan validasi sesuai standar internasional World Meteorological Organization (WMO).</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="bg-green-50 border-l-4 border-green-400 p-4 rounded-r-lg">
                                    <div class="flex">
                                        <div class="flex-shrink-0">
                                            <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <h4 class="text-lg font-semibold text-green-800 mb-2">Kegunaan Data:</h4>
                                            <p class="text-green-700 leading-relaxed">Data ini dapat digunakan untuk analisis cuaca dan iklim, perencanaan pertanian, mitigasi bencana alam, serta penelitian dalam bidang meteorologi dan klimatologi.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

    <script>
        const iklimOptDpiInitialData = {
            topiks: @json($topiks),
            variabels: @json($variabels), 
            klasifikasis: @json($klasifikasis),
            tahuns: @json($tahuns),
            bulans: @json($bulans),
            wilayahs: @json($wilayahs)
        };

        console.log('Global initial data:', iklimOptDpiInitialData);
    </div>

@push('scripts')
<script>
    // This script is intentionally left blank.
    // The iklimOptDpiForm() function is now defined within the main component script block.
</script>
@endpush
</div>

</x-layouts.landing>
