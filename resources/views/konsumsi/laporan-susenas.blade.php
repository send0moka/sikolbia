<x-layouts.landing title="Laporan Data Susenas Konsumsi">
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
                            <span class="ml-1 text-blue-600 font-medium">Laporan Data Susenas</span>
                        </div>
                    </li>
                </ol>
            </nav>

            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-3xl md:text-4xl font-bold text-neutral-900 mb-4">
                    Laporan Data Susenas Konsumsi
                </h1>
                <p class="text-xl text-neutral-600">
                    Cari dan analisis data konsumsi pangan rumah tangga dari Survei Sosial Ekonomi Nasional (Susenas)
                </p>
            </div>

            <!-- Two Column Layout -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8" x-data="searchForm()">
                <!-- Search Form - Left Column -->
                <div class="lg:col-span-1">
                    <div class="bg-neutral-50 rounded-lg p-6 sticky top-24">
                        <h3 class="text-lg font-semibold text-neutral-900 mb-6">Filter Data Konsumsi</h3>
                        
                        <form @submit.prevent="searchData" class="space-y-6">
                            <!-- Pilih Kelompok -->
                            <div>
                                <label for="kelompok" class="block text-sm font-medium text-neutral-700 mb-2">
                                    Kelompok Pangan
                                </label>
                                <select x-model="filters.kelompok" 
                                        @change="loadKomoditi()"
                                        class="w-full px-3 py-2 border border-neutral-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <option value="">Pilih Kelompok</option>
                                    <option value="padi-padian">Padi-padian</option>
                                    <option value="umbi-umbian">Umbi-umbian</option>
                                    <option value="ikan-udang-cumi">Ikan/Udang/Cumi/Kerang</option>
                                    <option value="daging">Daging</option>
                                    <option value="telur-susu">Telur dan Susu</option>
                                    <option value="sayur-sayuran">Sayur-sayuran</option>
                                    <option value="kacang-kacangan">Kacang-kacangan</option>
                                    <option value="buah-buahan">Buah-buahan</option>
                                    <option value="minyak-lemak">Minyak dan Lemak</option>
                                    <option value="bahan-minuman">Bahan Minuman</option>
                                    <option value="bumbu-bumbuan">Bumbu-bumbuan</option>
                                    <option value="konsumsi-lainnya">Konsumsi Lainnya</option>
                                    <option value="makanan-minuman-jadi">Makanan dan Minuman Jadi</option>
                                    <option value="tembakau-sirih">Tembakau dan Sirih</option>
                                </select>
                            </div>

                            <!-- Pilih Komoditi -->
                            <div>
                                <label for="komoditi" class="block text-sm font-medium text-neutral-700 mb-2">
                                    Komoditi
                                </label>
                                <select x-model="filters.komoditi" 
                                        :disabled="!filters.kelompok"
                                        class="w-full px-3 py-2 border border-neutral-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent disabled:bg-neutral-100">
                                    <option value="">Pilih Komoditi</option>
                                    <template x-for="komoditi in availableKomoditi" :key="komoditi.value">
                                        <option :value="komoditi.value" x-text="komoditi.label"></option>
                                    </template>
                                </select>
                            </div>

                            <!-- Tahun Awal -->
                            <div>
                                <label for="tahun_awal" class="block text-sm font-medium text-neutral-700 mb-2">
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
                            <div>
                                <label for="tahun_akhir" class="block text-sm font-medium text-neutral-700 mb-2">
                                    Tahun Akhir
                                </label>
                                <select x-model="filters.tahun_akhir" 
                                        :disabled="!filters.tahun_awal"
                                        class="w-full px-3 py-2 border border-neutral-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent disabled:bg-neutral-100">
                                    <option value="">Pilih Tahun</option>
                                    <template x-for="year in availableEndYears" :key="year">
                                        <option :value="year" x-text="year"></option>
                                    </template>
                                </select>
                            </div>

                            <!-- Button Tampilkan Data -->
                            <button type="submit" 
                                    :disabled="!canSearch"
                                    :class="canSearch ? 'bg-blue-600 hover:bg-blue-700' : 'bg-neutral-400 cursor-not-allowed'"
                                    class="w-full text-white px-4 py-3 rounded-md font-medium transition duration-200">
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
                                    class="w-full bg-neutral-500 hover:bg-neutral-600 text-white px-4 py-2 rounded-md font-medium transition duration-200">
                                Reset Filter
                            </button>
                        </form>

                        <!-- Quick Stats -->
                        <div class="mt-8 pt-6 border-t border-neutral-200">
                            <h4 class="font-medium text-neutral-900 mb-3">Informasi Data</h4>
                            <div class="space-y-2 text-sm text-neutral-600">
                                <p>• Periode: 1993 - 2023</p>
                                <p>• 34 Provinsi</p>
                                <p>• 14 Kelompok Pangan</p>
                                <p>• 215+ Komoditi</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Results - Right Column -->
                <div class="lg:col-span-2">
                    <!-- No Data State -->
                    <div x-show="!hasSearched && !hasData" class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-neutral-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
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
                                <h3 class="text-lg font-semibold text-neutral-900">Hasil Pencarian</h3>
                                <p class="text-sm text-neutral-600" x-text="`${results.length} data ditemukan`"></p>
                            </div>
                            <button @click="exportToExcel()" 
                                    class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md flex items-center space-x-2 transition duration-200">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <span>Export Excel</span>
                            </button>
                        </div>

                        <!-- Data Information -->
                        <div class="p-6 bg-blue-50 border-b">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                                <div>
                                    <span class="font-semibold text-neutral-700">Kelompok:</span>
                                    <span class="ml-2 text-neutral-900" x-text="getKelompokLabel(filters.kelompok)"></span>
                                </div>
                                <div>
                                    <span class="font-semibold text-neutral-700">Komoditi:</span>
                                    <span class="ml-2 text-neutral-900" x-text="getKomoditiLabel(filters.komoditi) || 'Semua Komoditi'"></span>
                                </div>
                                <div>
                                    <span class="font-semibold text-neutral-700">Sumber:</span>
                                    <span class="ml-2 text-neutral-900">SUSENAS, BPS</span>
                                </div>
                                <div>
                                    <span class="font-semibold text-neutral-700">Periode:</span>
                                    <span class="ml-2 text-neutral-900" x-text="filters.tahun_awal + (filters.tahun_akhir && filters.tahun_akhir !== filters.tahun_awal ? ' - ' + filters.tahun_akhir : '')"></span>
                                </div>
                            </div>
                        </div>

                        <!-- Catatan Section -->
                        <div class="p-6">
                            <h4 class="font-semibold text-neutral-900 mb-4">Catatan:</h4>
                            
                            <!-- Custom Table with specific styling -->
                            <style>
                                .table-container {
                                    overflow-x: auto;
                                    max-width: 100%;
                                }
                                .tg {
                                    border-collapse: collapse;
                                    border-spacing: 0;
                                    width: 100%;
                                    margin: 0 auto;
                                    min-width: 600px;
                                }
                                .tg td, .tg th {
                                    border: 1px solid #d1d5db;
                                    padding: 8px 12px;
                                    text-align: left;
                                    vertical-align: top;
                                    white-space: nowrap;
                                }
                                .tg .tg-header {
                                    background-color: #f3f4f6;
                                    font-weight: 600;
                                }
                                .tg .tg-subheader {
                                    background-color: #f9fafb;
                                    font-weight: 500;
                                }
                                .tg td:first-child {
                                    position: sticky;
                                    left: 0;
                                    background-color: white;
                                    z-index: 10;
                                    min-width: 200px;
                                }
                                .tg th:first-child {
                                    position: sticky;
                                    left: 0;
                                    background-color: #f3f4f6;
                                    z-index: 11;
                                    min-width: 200px;
                                }
                                .tg .tg-subheader:first-child {
                                    background-color: #f9fafb;
                                }
                            </style>
                            
                            <div class="table-container">
                                <table class="tg">
                                <thead>
                                    <tr>
                                        <th class="tg-header" rowspan="2">Uraian</th>
                                        <th class="tg-header" :colspan="results.length">Tahun</th>
                                    </tr>
                                    <tr>
                                        <template x-for="(result, index) in results" :key="index">
                                            <th class="tg-header" x-text="result.tahun"></th>
                                        </template>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="tg-subheader" :colspan="results.length + 1">Konsumsi seminggu (kapita/minggu)</td>
                                    </tr>
                                    <tr>
                                        <td>- Kuantitas (Kg)</td>
                                        <template x-for="(result, index) in results" :key="index">
                                            <td x-text="(parseFloat(result.konsumsi) * 7 / 1000).toFixed(4)"></td>
                                        </template>
                                    </tr>
                                    <tr>
                                        <td>- Nilai (Rp)</td>
                                        <template x-for="(result, index) in results" :key="index">
                                            <td x-text="formatRupiah(Math.floor(Math.random() * 50000 + 15000))"></td>
                                        </template>
                                    </tr>
                                    <tr>
                                        <td class="tg-subheader" :colspan="results.length + 1">Konsumsi setahun (kapita/tahun)</td>
                                    </tr>
                                    <tr>
                                        <td>- Kuantitas (Kg)</td>
                                        <template x-for="(result, index) in results" :key="index">
                                            <td x-text="(parseFloat(result.konsumsi) * 365 / 1000).toFixed(4)"></td>
                                        </template>
                                    </tr>
                                    <tr>
                                        <td>- Nilai (Rp)</td>
                                        <template x-for="(result, index) in results" :key="index">
                                            <td x-text="formatRupiah(Math.floor(Math.random() * 2000000 + 800000))"></td>
                                        </template>
                                    </tr>
                                </tbody>
                            </table>
                            </div>

                            <!-- Additional Data Summary -->
                            <div class="mt-6 bg-neutral-50 p-4 rounded-lg">
                                <h5 class="font-medium text-neutral-900 mb-3">Ringkasan Data</h5>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                                    <div>
                                        <span class="font-medium text-neutral-700">Rata-rata Konsumsi Harian:</span>
                                        <p class="text-neutral-900" x-text="getAverageConsumption() + ' g/kapita/hari'"></p>
                                    </div>
                                    <div>
                                        <span class="font-medium text-neutral-700">Rata-rata Energi:</span>
                                        <p class="text-neutral-900" x-text="getAverageEnergy() + ' kkal'"></p>
                                    </div>
                                    <div>
                                        <span class="font-medium text-neutral-700">Rata-rata Protein:</span>
                                        <p class="text-neutral-900" x-text="getAverageProtein() + ' g'"></p>
                                    </div>
                                </div>
                            </div>

                            <!-- Data Notes -->
                            <div class="mt-4 text-sm text-neutral-600">
                                <p class="mb-2"><strong>Keterangan:</strong></p>
                                <ul class="list-disc list-inside space-y-1 ml-4">
                                    <li>Data konsumsi dihitung berdasarkan recall method dari survei SUSENAS</li>
                                    <li>Nilai konsumsi mencakup konsumsi di rumah tangga</li>
                                    <li>Konversi nilai gizi menggunakan Daftar Komposisi Pangan Indonesia (DKPI)</li>
                                    <li>Data yang ditampilkan merupakan rata-rata nasional</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- No Results -->
                    <div x-show="hasSearched && !hasData && !loading" class="text-center py-12 bg-white rounded-lg border border-neutral-200">
                        <svg class="mx-auto h-12 w-12 text-neutral-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-neutral-900">Tidak Ada Data Ditemukan</h3>
                        <p class="mt-1 text-sm text-neutral-500">Coba ubah filter pencarian atau periode tahun</p>
                    </div>
                </div>
            </div>

            <!-- Related Links -->
            <div class="mt-12 bg-neutral-50 p-6 rounded-lg">
                <h3 class="text-lg font-semibold text-neutral-900 mb-4">Halaman Terkait</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <a href="{{ route('konsumsi.konsep-metode') }}" class="block p-4 bg-white rounded border hover:shadow-md transition duration-200">
                        <h4 class="font-medium text-blue-600">Konsep dan Metode</h4>
                        <p class="text-sm text-neutral-600 mt-1">Metodologi Susenas konsumsi</p>
                    </a>
                    <a href="{{ route('konsumsi.per-kapita-seminggu') }}" class="block p-4 bg-white rounded border hover:shadow-md transition duration-200">
                        <h4 class="font-medium text-blue-600">Per Kapita Seminggu</h4>
                        <p class="text-sm text-neutral-600 mt-1">Data konsumsi mingguan</p>
                    </a>
                    <a href="{{ route('login') }}" class="block p-4 bg-white rounded border hover:shadow-md transition duration-200">
                        <h4 class="font-medium text-blue-600">Manajemen Data</h4>
                        <p class="text-sm text-neutral-600 mt-1">Login untuk akses data lengkap</p>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Alpine.js Component -->
    <script>
        function searchForm() {
            return {
                filters: {
                    kelompok: '',
                    komoditi: '',
                    tahun_awal: '',
                    tahun_akhir: ''
                },
                availableKomoditi: [],
                results: [],
                loading: false,
                hasSearched: false,
                
                // Generate years from 1993 to 2023
                years: Array.from({length: 31}, (_, i) => 2023 - i),
                
                get availableEndYears() {
                    if (!this.filters.tahun_awal) {
                        return [];
                    }
                    return this.years.filter(year => year >= parseInt(this.filters.tahun_awal));
                },
                
                get hasData() {
                    return this.results.length > 0;
                },
                
                get canSearch() {
                    return this.filters.kelompok && this.filters.tahun_awal;
                },

                komoditiData: {
                    'padi-padian': [
                        {value: 'beras', label: 'Beras'},
                        {value: 'jagung', label: 'Jagung'},
                        {value: 'gandum', label: 'Gandum'},
                        {value: 'tepung-beras', label: 'Tepung Beras'},
                        {value: 'tepung-terigu', label: 'Tepung Terigu'}
                    ],
                    'umbi-umbian': [
                        {value: 'singkong', label: 'Singkong'},
                        {value: 'kentang', label: 'Kentang'},
                        {value: 'ubi-jalar', label: 'Ubi Jalar'},
                        {value: 'talas', label: 'Talas'},
                        {value: 'sagu', label: 'Sagu'}
                    ],
                    'ikan-udang-cumi': [
                        {value: 'ikan-segar', label: 'Ikan Segar'},
                        {value: 'ikan-asin', label: 'Ikan Asin'},
                        {value: 'udang', label: 'Udang'},
                        {value: 'cumi', label: 'Cumi'},
                        {value: 'kerang', label: 'Kerang'}
                    ],
                    'daging': [
                        {value: 'daging-sapi', label: 'Daging Sapi'},
                        {value: 'daging-ayam', label: 'Daging Ayam'},
                        {value: 'daging-kambing', label: 'Daging Kambing'},
                        {value: 'daging-babi', label: 'Daging Babi'}
                    ],
                    'telur-susu': [
                        {value: 'telur-ayam', label: 'Telur Ayam'},
                        {value: 'telur-bebek', label: 'Telur Bebek'},
                        {value: 'susu-segar', label: 'Susu Segar'},
                        {value: 'susu-kental', label: 'Susu Kental'},
                        {value: 'susu-bubuk', label: 'Susu Bubuk'}
                    ],
                    'sayur-sayuran': [
                        {value: 'bayam', label: 'Bayam'},
                        {value: 'kangkung', label: 'Kangkung'},
                        {value: 'sawi', label: 'Sawi'},
                        {value: 'kol', label: 'Kol'},
                        {value: 'wortel', label: 'Wortel'},
                        {value: 'tomat', label: 'Tomat'},
                        {value: 'cabai', label: 'Cabai'}
                    ],
                    'kacang-kacangan': [
                        {value: 'kacang-tanah', label: 'Kacang Tanah'},
                        {value: 'kacang-hijau', label: 'Kacang Hijau'},
                        {value: 'kedelai', label: 'Kedelai'},
                        {value: 'tahu', label: 'Tahu'},
                        {value: 'tempe', label: 'Tempe'}
                    ],
                    'buah-buahan': [
                        {value: 'pisang', label: 'Pisang'},
                        {value: 'jeruk', label: 'Jeruk'},
                        {value: 'apel', label: 'Apel'},
                        {value: 'mangga', label: 'Mangga'},
                        {value: 'pepaya', label: 'Pepaya'}
                    ],
                    'minyak-lemak': [
                        {value: 'minyak-kelapa', label: 'Minyak Kelapa'},
                        {value: 'minyak-sawit', label: 'Minyak Sawit'},
                        {value: 'margarin', label: 'Margarin'},
                        {value: 'mentega', label: 'Mentega'}
                    ],
                    'bahan-minuman': [
                        {value: 'teh', label: 'Teh'},
                        {value: 'kopi', label: 'Kopi'},
                        {value: 'gula-pasir', label: 'Gula Pasir'},
                        {value: 'gula-merah', label: 'Gula Merah'}
                    ],
                    'bumbu-bumbuan': [
                        {value: 'bawang-merah', label: 'Bawang Merah'},
                        {value: 'bawang-putih', label: 'Bawang Putih'},
                        {value: 'kemiri', label: 'Kemiri'},
                        {value: 'ketumbar', label: 'Ketumbar'},
                        {value: 'garam', label: 'Garam'}
                    ],
                    'konsumsi-lainnya': [
                        {value: 'mie-instan', label: 'Mie Instan'},
                        {value: 'kerupuk', label: 'Kerupuk'},
                        {value: 'emping', label: 'Emping'}
                    ],
                    'makanan-minuman-jadi': [
                        {value: 'roti', label: 'Roti'},
                        {value: 'kue', label: 'Kue'},
                        {value: 'minuman-kemasan', label: 'Minuman Kemasan'}
                    ],
                    'tembakau-sirih': [
                        {value: 'rokok-kretek', label: 'Rokok Kretek'},
                        {value: 'rokok-putih', label: 'Rokok Putih'},
                        {value: 'tembakau', label: 'Tembakau'},
                        {value: 'sirih', label: 'Sirih'}
                    ]
                },

                loadKomoditi() {
                    this.availableKomoditi = this.komoditiData[this.filters.kelompok] || [];
                    this.filters.komoditi = '';
                },

                validateYearRange() {
                    // Reset tahun akhir jika kurang dari tahun awal
                    if (this.filters.tahun_akhir && this.filters.tahun_awal) {
                        if (parseInt(this.filters.tahun_akhir) < parseInt(this.filters.tahun_awal)) {
                            this.filters.tahun_akhir = '';
                        }
                    }
                },

                async searchData() {
                    this.loading = true;
                    this.hasSearched = true;
                    
                    // Simulate API call
                    await new Promise(resolve => setTimeout(resolve, 1500));
                    
                    // Generate sample data
                    this.generateSampleData();
                    
                    this.loading = false;
                },

                generateSampleData() {
                    const startYear = parseInt(this.filters.tahun_awal);
                    const endYear = parseInt(this.filters.tahun_akhir) || startYear;
                    
                    this.results = [];
                    
                    for (let year = startYear; year <= endYear; year++) {
                        const kelompokLabel = this.getKelompokLabel(this.filters.kelompok);
                        const komoditiLabel = this.getKomoditiLabel(this.filters.komoditi);
                        
                        this.results.push({
                            tahun: year,
                            kelompok: kelompokLabel,
                            komoditi: komoditiLabel || 'Semua Komoditi',
                            konsumsi: (Math.random() * 300 + 50).toFixed(1),
                            energi: Math.floor(Math.random() * 1000 + 100),
                            protein: (Math.random() * 30 + 5).toFixed(1)
                        });
                    }
                },

                getKelompokLabel(value) {
                    const labels = {
                        'padi-padian': 'Padi-padian',
                        'umbi-umbian': 'Umbi-umbian',
                        'ikan-udang-cumi': 'Ikan/Udang/Cumi/Kerang',
                        'daging': 'Daging',
                        'telur-susu': 'Telur dan Susu',
                        'sayur-sayuran': 'Sayur-sayuran',
                        'kacang-kacangan': 'Kacang-kacangan',
                        'buah-buahan': 'Buah-buahan',
                        'minyak-lemak': 'Minyak dan Lemak',
                        'bahan-minuman': 'Bahan Minuman',
                        'bumbu-bumbuan': 'Bumbu-bumbuan',
                        'konsumsi-lainnya': 'Konsumsi Lainnya',
                        'makanan-minuman-jadi': 'Makanan dan Minuman Jadi',
                        'tembakau-sirih': 'Tembakau dan Sirih'
                    };
                    return labels[value] || value;
                },

                getKomoditiLabel(value) {
                    if (!value) return '';
                    
                    for (const kategori of Object.values(this.komoditiData)) {
                        const komoditi = kategori.find(k => k.value === value);
                        if (komoditi) return komoditi.label;
                    }
                    return value;
                },

                exportToExcel() {
                    // Create a new workbook
                    const wb = XLSX.utils.book_new();
                    
                    // Prepare data with headers and information
                    const exportData = [];
                    
                    // Add header information
                    exportData.push(['Kelompok :', this.getKelompokLabel(this.filters.kelompok)]);
                    exportData.push(['Komoditi :', this.getKomoditiLabel(this.filters.komoditi) || 'Semua Komoditi']);
                    exportData.push(['Sumber :', 'SUSENAS, BPS']);
                    exportData.push(['']);
                    exportData.push(['Catatan:']);
                    exportData.push(['']);
                    
                    // Add table header with dynamic columns based on results length
                    const headerRow = ['Uraian'];
                    // Add "Tahun" headers for each column
                    for (let i = 0; i < this.results.length; i++) {
                        headerRow.push('Tahun');
                    }
                    exportData.push(headerRow);
                    
                    // Add year row
                    const yearRow = [''];
                    this.results.forEach(result => {
                        yearRow.push(result.tahun);
                    });
                    exportData.push(yearRow);
                    
                    // Add consumption data rows with dynamic columns
                    const weeklyHeaderRow = ['Konsumsi seminggu (kapita/minggu)'];
                    for (let i = 0; i < this.results.length; i++) {
                        weeklyHeaderRow.push('');
                    }
                    exportData.push(weeklyHeaderRow);
                    
                    // Kuantitas (Kg) - seminggu
                    const kgWeekRow = ['- Kuantitas (Kg)'];
                    this.results.forEach(result => {
                        kgWeekRow.push((parseFloat(result.konsumsi) * 7 / 1000).toFixed(4));
                    });
                    exportData.push(kgWeekRow);
                    
                    // Nilai (Rp) - seminggu
                    const rpWeekRow = ['- Nilai (Rp)'];
                    this.results.forEach(result => {
                        rpWeekRow.push(this.formatRupiah(Math.floor(Math.random() * 50000 + 15000)));
                    });
                    exportData.push(rpWeekRow);
                    
                    // Add yearly consumption with dynamic columns
                    const yearlyHeaderRow = ['Konsumsi setahun (kapita/tahun)'];
                    for (let i = 0; i < this.results.length; i++) {
                        yearlyHeaderRow.push('');
                    }
                    exportData.push(yearlyHeaderRow);
                    
                    // Kuantitas (Kg) - setahun
                    const kgYearRow = ['- Kuantitas (Kg)'];
                    this.results.forEach(result => {
                        kgYearRow.push((parseFloat(result.konsumsi) * 365 / 1000).toFixed(4));
                    });
                    exportData.push(kgYearRow);
                    
                    // Nilai (Rp) - setahun
                    const rpYearRow = ['- Nilai (Rp)'];
                    this.results.forEach(result => {
                        rpYearRow.push(this.formatRupiah(Math.floor(Math.random() * 2000000 + 800000)));
                    });
                    exportData.push(rpYearRow);
                    
                    // Create worksheet
                    const ws = XLSX.utils.aoa_to_sheet(exportData);
                    
                    // Define merge ranges for proper table layout with dynamic columns
                    if (!ws['!merges']) ws['!merges'] = [];
                    
                    const numCols = this.results.length;
                    
                    // Merge cells for "Uraian" header (row 7, spans 2 rows)
                    ws['!merges'].push({s: {r: 6, c: 0}, e: {r: 7, c: 0}});
                    
                    // Merge cells for "Tahun" header (row 7, spans all year columns)
                    if (numCols > 1) {
                        ws['!merges'].push({s: {r: 6, c: 1}, e: {r: 6, c: numCols}});
                    }
                    
                    // Merge cells for category headers
                    ws['!merges'].push({s: {r: 8, c: 0}, e: {r: 8, c: numCols}}); // Konsumsi seminggu
                    ws['!merges'].push({s: {r: 11, c: 0}, e: {r: 11, c: numCols}}); // Konsumsi setahun
                    
                    // Set column widths dynamically
                    const colWidths = [{width: 25}]; // Uraian column
                    for (let i = 0; i < numCols; i++) {
                        colWidths.push({width: 15}); // Year columns
                    }
                    ws['!cols'] = colWidths;
                    
                    // Add worksheet to workbook
                    XLSX.utils.book_append_sheet(wb, ws, 'Data Susenas');
                    
                    // Generate filename
                    const filename = `data-susenas-${this.filters.kelompok}-${Date.now()}.xlsx`;
                    
                    // Save file
                    XLSX.writeFile(wb, filename);
                },

                formatRupiah(amount) {
                    return new Intl.NumberFormat('id-ID', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    }).format(amount);
                },

                getAverageConsumption() {
                    if (this.results.length === 0) return '0.0';
                    const avg = this.results.reduce((sum, item) => sum + parseFloat(item.konsumsi), 0) / this.results.length;
                    return avg.toFixed(1);
                },

                getAverageEnergy() {
                    if (this.results.length === 0) return '0';
                    const avg = this.results.reduce((sum, item) => sum + parseInt(item.energi), 0) / this.results.length;
                    return Math.round(avg);
                },

                getAverageProtein() {
                    if (this.results.length === 0) return '0.0';
                    const avg = this.results.reduce((sum, item) => sum + parseFloat(item.protein), 0) / this.results.length;
                    return avg.toFixed(1);
                },

                resetForm() {
                    this.filters = {
                        kelompok: '',
                        komoditi: '',
                        tahun_awal: '',
                        tahun_akhir: ''
                    };
                    this.availableKomoditi = [];
                    this.results = [];
                    this.hasSearched = false;
                }
            }
        }
    </script>
</x-layouts.landing>
