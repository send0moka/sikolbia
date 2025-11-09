<x-layouts.landing title="Konsumsi Per Kapita Seminggu">
    <!-- Add SheetJS library for Excel export -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
    
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
                            <span class="ml-1 text-neutral-500">Konsumsi</span>
                        </div>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-neutral-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="ml-1 text-blue-600 font-medium">Konsumsi Per Kapita Seminggu</span>
                        </div>
                    </li>
                </ol>
            </nav>

            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-3xl md:text-4xl font-bold text-neutral-900 mb-4">
                    Konsumsi Pangan Per Kapita Seminggu
                </h1>
                <p class="text-xl text-neutral-600">
                    Cari dan analisis data konsumsi pangan per kapita seminggu berdasarkan Survei Sosial Ekonomi Nasional (Susenas)
                </p>
            </div>

            <!-- Two Column Layout -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8" x-data="searchForm()" x-init="init()">
                <!-- Search Form - Left Column -->
                <div class="lg:col-span-1">
                    <div class="bg-neutral-50 rounded-lg p-6 sticky top-24">
                        <h3 class="text-lg font-semibold text-neutral-900 mb-6">Filter Data Konsumsi</h3>
                        
                        <form @submit.prevent="searchData" class="space-y-6">
                            <!-- Kelompok Filter -->
                            <div>
                                <label class="block text-sm font-medium text-neutral-700 mb-2">
                                    Kelompok Pangan
                                </label>
                                <select x-model="filters.kd_kelompokbps" 
                                        @change="loadKomoditi()"
                                        class="w-full px-3 py-2 border border-neutral-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <option value="">Pilih Kelompok</option>
                                    <template x-for="opt in kelompokOptions" :key="opt.value">
                                        <option :value="opt.value" x-text="opt.label"></option>
                                    </template>
                                </select>
                            </div>

                            <!-- Komoditi Filter -->
                            <div x-show="availableKomoditi.length > 0">
                                <label class="block text-sm font-medium text-neutral-700 mb-2">
                                    Komoditi
                                </label>
                                <select x-model="filters.kd_komoditibps" 
                                        :disabled="!filters.kd_kelompokbps"
                                        class="w-full px-3 py-2 border border-neutral-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent disabled:bg-neutral-100">
                                    <option value="">Semua Komoditi</option>
                                    <template x-for="komoditi in availableKomoditi" :key="komoditi.value">
                                        <option :value="komoditi.value" x-text="komoditi.label"></option>
                                    </template>
                                </select>
                            </div>

                            <!-- Tahun Awal -->
                            <div>
                                <label class="block text-sm font-medium text-neutral-700 mb-2">
                                    Tahun Awal
                                </label>
                                <select x-model="filters.tahun_awal" 
                                        @change="validateYearRange()"
                                        class="w-full px-3 py-2 border border-neutral-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <option value="">Pilih Tahun</option>
                                    <template x-for="year in years" :key="year">
                                        <option :value="year" x-text="year"></option>
                                    </template>
                                </select>
                            </div>

                            <!-- Tahun Akhir -->
                            <div x-show="filters.tahun_awal">
                                <label class="block text-sm font-medium text-neutral-700 mb-2">
                                    Tahun Akhir <span class="text-neutral-400">(opsional)</span>
                                </label>
                                <select x-model="filters.tahun_akhir"
                                        class="w-full px-3 py-2 border border-neutral-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <option value="">Pilih Tahun</option>
                                    <template x-for="year in availableEndYears" :key="year">
                                        <option :value="year" x-text="year"></option>
                                    </template>
                                </select>
                            </div>

                            <!-- Search Button -->
                            <button type="submit" 
                                    :disabled="!canSearch"
                                    :class="canSearch ? 'bg-blue-600 hover:bg-blue-700' : 'bg-neutral-400 cursor-not-allowed'"
                                    class="w-full text-white py-2 px-4 rounded-md transition duration-200 font-medium">
                                <span x-show="!loading">Tampilkan Data</span>
                                <span x-show="loading" class="flex items-center justify-center">
                                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    Memuat...
                                </span>
                            </button>

                            <!-- Reset Button -->
                            <button type="button" 
                                    @click="resetForm()"
                                    class="w-full bg-neutral-500 text-white py-2 px-4 rounded-md hover:bg-neutral-600 transition duration-200 font-medium">
                                Reset Filter
                            </button>
                        </form>

                        <!-- Quick Stats -->
                        <div class="mt-8 pt-6 border-t border-neutral-200">
                            <h4 class="font-medium text-neutral-900 mb-3">Info Data</h4>
                                <div class="space-y-2 text-sm text-neutral-600">
                                <div>• Data tersedia: <span x-text="periodeText"></span></div>
                                <div>• Periode: Triwulanan</div>
                                <div>• Cakupan: Nasional</div>
                                <div>• Sumber: BPS</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Results - Right Column -->
                <div class="lg:col-span-2">
                    <!-- No Data State -->
                    <div x-show="!hasSearched && !hasData" class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-neutral-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-neutral-900">Belum Ada Data yang Ditampilkan</h3>
                        <p class="mt-1 text-sm text-neutral-500">Pilih filter dan klik "Tampilkan Data" untuk melihat hasil</p>
                    </div>

                    <!-- Loading State -->
                    <div x-show="loading" class="text-center py-12">
                        <svg class="animate-spin mx-auto h-8 w-8 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <p class="mt-2 text-sm text-neutral-600">Memuat data...</p>
                    </div>

                    <!-- Results Table -->
                    <div x-show="hasData && !loading" class="bg-white rounded-lg border border-neutral-200">
                        <!-- Results Header -->
                        <div class="bg-neutral-50 px-6 py-4 border-b border-neutral-200 flex justify-between items-center">
                            <div>
                                <h4 class="text-lg font-semibold text-neutral-900">Hasil Pencarian</h4>
                                <p class="text-sm text-neutral-600" x-text="`${results.length} data ditemukan`"></p>
                            </div>
                            <button @click="exportToExcel()" 
                                    class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 transition duration-200 flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                Export Excel
                            </button>
                        </div>

                        <!-- Data Information -->
                        <div class="p-6 bg-blue-50 border-b">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                                <div>
                                    <span class="text-neutral-600 font-semibold">Kelompok:</span>
                                    <span class="ml-2 text-neutral-900" x-text="applied.kelompokLabel"></span>
                                </div>
                                <div>
                                    <span class="text-neutral-600 font-semibold">Komoditi:</span>
                                    <span class="ml-2 text-neutral-900" x-text="applied.komoditiLabel || 'Semua Komoditi'"></span>
                                </div>
                                <div>
                                    <span class="text-neutral-600 font-semibold">Periode:</span>
                                    <span class="ml-2 text-neutral-900" x-text="applied.periode"></span>
                                </div>
                                <div>
                                    <span class="text-neutral-600 font-semibold">Sumber:</span>
                                    <span class="ml-2 text-neutral-900">SUSENAS, BPS</span>
                                </div>
                            </div>
                        </div>

                        <!-- Catatan Section -->
                        <div class="p-6">
                            <div class="overflow-x-auto">
                                <table class="min-w-full">
                                    <thead>
                                        <tr>
                                            <th rowspan="2" class="sticky left-0 z-10 bg-neutral-50 px-6 py-4 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider border-r border-neutral-200">
                                                Uraian
                                            </th>
                                            <template x-for="(result, index) in results" :key="index">
                                                <th class="px-6 py-3 text-center text-xs font-medium text-neutral-500 uppercase tracking-wider border-l border-neutral-200">
                                                    Tahun
                                                </th>
                                            </template>
                                        </tr>
                                        <tr>
                                            <template x-for="(result, index) in results" :key="index">
                                                <th class="px-6 py-3 text-center text-sm font-medium text-neutral-900 border-l border-neutral-200" x-text="result.tahun">
                                                </th>
                                            </template>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-neutral-200">
                                        <!-- Konsumsi seminggu section -->
                                        <tr class="bg-blue-50">
                                            <td colspan="100%" class="px-6 py-3 text-center font-semibold text-neutral-900 border-b border-neutral-300">
                                                Konsumsi seminggu (kapita/minggu)
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="sticky left-0 z-10 bg-white px-6 py-4 text-sm font-medium text-neutral-900 border-r border-neutral-200">
                                                - Kuantitas (<span x-text="unitLabel"></span>)
                                            </td>
                                            <template x-for="(result, index) in results" :key="index">
                                                <td class="px-6 py-4 text-sm text-neutral-900 text-center border-l border-neutral-200" x-text="formatNumber(result.qtyWeek)">
                                                </td>
                                            </template>
                                        </tr>
                                        <tr class="bg-neutral-50">
                                            <td class="sticky left-0 z-10 bg-neutral-50 px-6 py-4 text-sm font-medium text-neutral-900 border-r border-neutral-200">
                                                - Nilai (Rp)
                                            </td>
                                            <template x-for="(result, index) in results" :key="index">
                                                <td class="px-6 py-4 text-sm text-neutral-900 text-center border-l border-neutral-200" x-text="formatRupiah(result.valueWeek || 0)">
                                                </td>
                                            </template>
                                        </tr>
                                        <tr>
                                            <td class="sticky left-0 z-10 bg-white px-6 py-4 text-sm font-medium text-neutral-900 border-r border-neutral-200">
                                                - Gizi
                                            </td>
                                            <template x-for="(result, index) in results" :key="index">
                                                <td class="px-6 py-4 text-sm text-neutral-900 text-center border-l border-neutral-200" x-text="formatNumber(result.gizi)">
                                                </td>
                                            </template>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <!-- Additional Data Summary -->
                            <div class="mt-6 bg-neutral-50 p-4 rounded-lg">
                                <h5 class="font-medium text-neutral-900 mb-2">Ringkasan Data:</h5>
                                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 text-sm">
                                    <div>
                                        <span class="block text-neutral-600 min-h-10 leading-snug">Rata-rata Konsumsi:</span>
                                        <div class="font-semibold" x-text="getAverageDailyQty()"></div>
                                    </div>
                                    <div>
                                        <span class="block text-neutral-600 min-h-10 leading-snug">Rata-rata Nilai Konsumsi Harian:</span>
                                        <div class="font-semibold" x-text="getAverageDailyValue()"></div>
                                    </div>
                                    <div>
                                        <span class="block text-neutral-600 min-h-10 leading-snug">Rata-rata konsumsi gizi harian:</span>
                                        <div class="font-semibold" x-text="getAverageDailyGizi()"></div>
                                    </div>
                                    <div>
                                        <span class="block text-neutral-600 min-h-10 leading-snug">Periode Data:</span>
                                        <div class="font-semibold" x-text="results.length + ' tahun'"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- No Results -->
                    <div x-show="hasSearched && !hasData && !loading" class="text-center py-12 bg-white rounded-lg border border-neutral-200">
                        <svg class="mx-auto h-12 w-12 text-neutral-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-neutral-900">Tidak Ada Data Ditemukan</h3>
                        <p class="mt-1 text-sm text-neutral-500">Tidak ditemukan data untuk filter yang dipilih. Sesuaikan filter lalu klik "Tampilkan Data".</p>
                    </div>
    <!-- Alpine.js Component -->
    <script>
        function searchForm() {
            return {
                filters: {
                    kd_kelompokbps: '',
                    kd_komoditibps: '',
                    tahun_awal: '',
                    tahun_akhir: ''
                },
                kelompokOptions: [],
                availableKomoditi: [],
                results: [],
                loading: false,
                hasSearched: false,
                selectedKelompokLabel: '',
                selectedKomoditiLabel: '',
                applied: { kelompokLabel: '', komoditiLabel: '', periode: '' },

                years: [],
                get minYear() { if (!this.years.length) return null; return this.years.reduce((m,y)=>y<m?y:m,this.years[0]); },
                get maxYear() { if (!this.years.length) return null; return this.years.reduce((m,y)=>y>m?y:m,this.years[0]); },
                get periodeText() { if (!this.years.length) return '-'; return `${this.minYear}-${this.maxYear}`; },

                get availableEndYears() {
                    if (!this.filters.tahun_awal) return [];
                    return this.years.filter(year => year >= parseInt(this.filters.tahun_awal));
                },
                get hasData() { return this.results.length > 0; },
                get canSearch() { return !!this.filters.kd_kelompokbps && !!this.filters.kd_komoditibps && !!this.filters.tahun_awal; },
                get unitLabel() { return (this.results[0]?.satuan) || 'Kg'; },

                init() {
                    this.loadKelompok();
                    this.loadYears();
                },
                async loadKelompok() {
                    try {
                        const resp = await fetch('/konsumsi/api/kelompok-bps');
                        const json = await resp.json();
                        this.kelompokOptions = json.data || [];
                    } catch (e) {
                        console.error(e);
                        this.kelompokOptions = [];
                    }
                },
                async loadKomoditi() {
                    this.availableKomoditi = [];
                    this.filters.kd_komoditibps = '';
                    this.selectedKelompokLabel = (this.kelompokOptions.find(k => k.value === this.filters.kd_kelompokbps) || {}).label || '';
                    if (!this.filters.kd_kelompokbps) return;
                    try {
                        const url = new URL(window.location.origin + '/konsumsi/api/komoditi-bps');
                        url.searchParams.set('kd_kelompokbps', this.filters.kd_kelompokbps);
                        const resp = await fetch(url);
                        const json = await resp.json();
                        this.availableKomoditi = json.data || [];
                    } catch (e) {
                        console.error(e);
                        this.availableKomoditi = [];
                    }
                },
                async loadYears() {
                    try {
                        const resp = await fetch('/konsumsi/api/years');
                        const json = await resp.json();
                        this.years = (json.data || []).map(y => parseInt(y));
                    } catch (e) {
                        console.error(e);
                        this.years = [];
                    }
                },
                validateYearRange() {
                    if (this.filters.tahun_akhir && this.filters.tahun_awal) {
                        if (parseInt(this.filters.tahun_akhir) < parseInt(this.filters.tahun_awal)) {
                            this.filters.tahun_akhir = '';
                        }
                    }
                },
                async searchData() {
                    this.loading = true;
                    this.hasSearched = true;
                    try {
                        const url = new URL(window.location.origin + '/konsumsi/api/laporan-susenas');
                        url.searchParams.set('kd_kelompokbps', this.filters.kd_kelompokbps);
                        if (this.filters.kd_komoditibps) url.searchParams.set('kd_komoditibps', this.filters.kd_komoditibps);
                        url.searchParams.set('tahun_awal', this.filters.tahun_awal);
                        if (this.filters.tahun_akhir) url.searchParams.set('tahun_akhir', this.filters.tahun_akhir);

                        const resp = await fetch(url);
                        const json = await resp.json();
                        this.results = (json.data || []).map(r => ({
                            tahun: r.tahun,
                            qtyWeek: r.qtyWeek,
                            valueWeek: r.valueWeek,
                            gizi: r.gizi,
                            satuan: r.satuan,
                        }));
                        this.selectedKomoditiLabel = (this.availableKomoditi.find(k => k.value === this.filters.kd_komoditibps) || {}).label || '';
                        this.applied.kelompokLabel = this.selectedKelompokLabel;
                        this.applied.komoditiLabel = this.selectedKomoditiLabel;
                        this.applied.periode = this.filters.tahun_awal + (this.filters.tahun_akhir && this.filters.tahun_akhir !== this.filters.tahun_awal ? ' - ' + this.filters.tahun_akhir : '');
                    } catch (e) {
                        console.error(e);
                        this.results = [];
                    } finally {
                        this.loading = false;
                    }
                },
                exportToExcel() {
                    const wb = XLSX.utils.book_new();
                    const exportData = [];
                    exportData.push(['Kelompok :', this.applied.kelompokLabel || '-']);
                    exportData.push(['Komoditi :', this.applied.komoditiLabel]);
                    exportData.push(['Periode :', this.applied.periode || '-']);
                    exportData.push(['Sumber :', 'SUSENAS, BPS']);
                    exportData.push(['']);
                    exportData.push(['Catatan: Data konsumsi per kapita seminggu']);
                    exportData.push(['']);
                    const headerRow = ['Uraian'];
                    for (let i = 0; i < this.results.length; i++) headerRow.push('Tahun');
                    exportData.push(headerRow);
                    const yearRow = [''];
                    this.results.forEach(result => yearRow.push(result.tahun));
                    exportData.push(yearRow);
                    const weeklyHeaderRow = ['Konsumsi seminggu (kapita/minggu)'];
                    for (let i = 0; i < this.results.length; i++) weeklyHeaderRow.push('');
                    exportData.push(weeklyHeaderRow);
                    const kgWeekRow = [`- Kuantitas (${this.unitLabel})`];
                    this.results.forEach(result => kgWeekRow.push(this.formatNumber(result.qtyWeek)));
                    exportData.push(kgWeekRow);
                    const rpWeekRow = ['- Nilai (Rp)'];
                    this.results.forEach(result => rpWeekRow.push(this.formatRupiah(result.valueWeek || 0)));
                    exportData.push(rpWeekRow);
                    const energyWeekRow = ['- Gizi'];
                    this.results.forEach(result => energyWeekRow.push(this.formatNumber(result.gizi)));
                    exportData.push(energyWeekRow);
                    const ws = XLSX.utils.aoa_to_sheet(exportData);
                    if (!ws['!merges']) ws['!merges'] = [];
                    const numCols = this.results.length;
                    // Rows (0-based):
                    // 7: headerRow (Uraian + Tahun)
                    // 8: yearRow
                    // 9: weeklyHeaderRow
                    ws['!merges'].push({s: {r: 7, c: 0}, e: {r: 8, c: 0}}); // Uraian vertical
                    if (numCols > 1) ws['!merges'].push({s: {r: 7, c: 1}, e: {r: 7, c: numCols}}); // Tahun horizontal
                    ws['!merges'].push({s: {r: 9, c: 0}, e: {r: 9, c: numCols}}); // Konsumsi seminggu header
                    const colWidths = [{width: 35}];
                    for (let i = 0; i < numCols; i++) colWidths.push({width: 15});
                    ws['!cols'] = colWidths;
                    XLSX.utils.book_append_sheet(wb, ws, 'Data Seminggu');
                    const filename = `data-konsumsi-seminggu-${this.filters.kd_kelompokbps || 'all'}-${Date.now()}.xlsx`;
                    XLSX.writeFile(wb, filename);
                },
                formatRupiah(amount) { return new Intl.NumberFormat('id-ID', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(amount); },
                formatNumber(v) { if (v===null||v===undefined||Number.isNaN(v)) return '-'; return new Intl.NumberFormat('id-ID', { minimumFractionDigits: 0, maximumFractionDigits: 2 }).format(Number(v)); },
                formatInteger(v) { if (v===null||v===undefined||Number.isNaN(v)) return '-'; return new Intl.NumberFormat('id-ID', { minimumFractionDigits: 0, maximumFractionDigits: 0 }).format(Math.round(Number(v))); },
                getAverageDailyQty() { if (!this.results.length) return '0 /hari'; const avg = this.results.reduce((s,i)=>s+((parseFloat(i.qtyWeek||0))/7),0)/this.results.length; return `${this.formatNumber(avg)} ${this.unitLabel}/hari`; },
                getAverageDailyValue() { if (!this.results.length) return this.formatRupiah(0)+' /hari'; const avg = this.results.reduce((s,i)=>s+((parseFloat(i.valueWeek||0))/7),0)/this.results.length; return 'Rp ' + this.formatRupiah(avg)+' /hari'; },
                getAverageDailyGizi() { if (!this.results.length) return '0'; const avg = this.results.reduce((s,i)=>s+((parseFloat(i.gizi||0))/7),0)/this.results.length; return this.formatNumber(avg); },
                resetForm() {
                    this.filters = { kd_kelompokbps:'', kd_komoditibps:'', tahun_awal:'', tahun_akhir:'' };
                    this.availableKomoditi = [];
                    this.results = [];
                    this.hasSearched = false;
                    this.selectedKelompokLabel = '';
                    this.selectedKomoditiLabel = '';
                    this.applied = { kelompokLabel: '', komoditiLabel: '', periode: '' };
                }
            }
        }
    </script>
</x-layouts.landing>
