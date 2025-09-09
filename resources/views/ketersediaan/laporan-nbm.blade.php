<x-layouts.landing title="Laporan Data NBM Ketersediaan">
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
                            <span class="ml-1 text-neutral-500">Ketersediaan</span>
                        </div>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-neutral-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="ml-1 text-blue-600 font-medium">Laporan Data NBM</span>
                        </div>
                    </li>
                </ol>
            </nav>

            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-3xl md:text-4xl font-bold text-neutral-900 mb-4">
                    Laporan Data Neraca Bahan Makanan
                </h1>
                <p class="text-xl text-neutral-600">
                    Cari dan analisis data ketersediaan pangan Indonesia melalui Neraca Bahan Makanan (NBM)
                </p>
            </div>

            <!-- Two Column Layout -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8" x-data="searchForm()">
                <!-- Search Form - Left Column -->
                <div class="lg:col-span-1">
                    <div class="bg-neutral-50 rounded-lg p-6 sticky top-24">
                        <h3 class="text-lg font-semibold text-neutral-900 mb-6">Filter Data Ketersediaan</h3>
                        
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
                                    <option value="makanan-berpati">Makanan Berpati</option>
                                    <option value="gula">Gula</option>
                                    <option value="buah-biji-berminyak">Buah Biji Berminyak</option>
                                    <option value="buah-buahan">Buah-buahan</option>
                                    <option value="sayur-sayuran">Sayur-sayuran</option>
                                    <option value="daging">Daging</option>
                                    <option value="telur">Telur</option>
                                    <option value="susu">Susu</option>
                                    <option value="minyak-lemak">Minyak dan Lemak</option>
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
                                <p>• Periode: 1993 - 2025</p>
                                <p>• 10 Kelompok Pangan</p>
                                <p>• 200+ Komoditas</p>
                                <p>• Data Nasional</p>
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
                                    <strong>Kelompok:</strong> <span x-text="getKelompokLabel(filters.kelompok)"></span>
                                </div>
                                <div>
                                    <strong>Komoditi:</strong> <span x-text="getKomoditiLabel(filters.komoditi) || 'Semua Komoditi'"></span>
                                </div>
                                <div>
                                    <strong>Periode:</strong> <span x-text="filters.tahun_awal + (filters.tahun_akhir && filters.tahun_akhir !== filters.tahun_awal ? ' - ' + filters.tahun_akhir : '')"></span>
                                </div>
                                <div>
                                    <strong>Sumber:</strong> Neraca Bahan Makanan, BKP-Kementan
                                </div>
                            </div>
                        </div>

                        <!-- Catatan Section -->
                        <div class="p-6">
                            <h4 class="font-semibold text-neutral-900 mb-4">Data NBM (Ketersediaan Per Kapita Per Tahun):</h4>
                            
                            <!-- Custom Table with specific styling -->
                            <style>
                                .table-container {
                                    width: 100%;
                                    overflow-x: auto;
                                    margin-bottom: 1rem;
                                }
                                .tg {
                                    border-collapse: collapse;
                                    border-spacing: 0;
                                    width: 100%;
                                    border: 1px solid #d1d5db;
                                    margin: 0 auto;
                                    min-width: 600px;
                                }
                                .tg td, .tg th {
                                    border: 1px solid #d1d5db;
                                    padding: 8px 12px;
                                    text-align: center;
                                    vertical-align: top;
                                    white-space: nowrap;
                                }
                                .tg .tg-header {
                                    background-color: #f3f4f6;
                                    font-weight: 600;
                                    color: #111827;
                                }
                                .tg .tg-subheader {
                                    background-color: #f9fafb;
                                    font-weight: 500;
                                    color: #374151;
                                }
                                .tg td:first-child {
                                    text-align: left;
                                    font-weight: 500;
                                    background-color: #f9fafb;
                                    position: sticky;
                                    left: 0;
                                    z-index: 10;
                                    min-width: 200px;
                                }
                                .tg th:first-child {
                                    text-align: center;
                                    font-weight: 600;
                                    position: sticky;
                                    left: 0;
                                    background-color: #f3f4f6;
                                    z-index: 11;
                                    min-width: 200px;
                                }
                                .tg .tg-subheader:first-child {
                                    text-align: left;
                                    background-color: #f3f4f6;
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
                                        <!-- A. Penyediaan -->
                                        <tr>
                                            <td class="tg-subheader" :colspan="results.length + 1">A. Penyediaan (Ribu Ton)</td>
                                        </tr>
                                        <tr>
                                            <td>1. Produksi</td>
                                            <template x-for="result in results" :key="result.tahun">
                                                <td x-text="result.produksi"></td>
                                            </template>
                                        </tr>
                                        <tr>
                                            <td>&nbsp;&nbsp;&nbsp;&nbsp;- Masukan</td>
                                            <template x-for="result in results" :key="result.tahun">
                                                <td x-text="result.masukan"></td>
                                            </template>
                                        </tr>
                                        <tr>
                                            <td>&nbsp;&nbsp;&nbsp;&nbsp;- Keluaran</td>
                                            <template x-for="result in results" :key="result.tahun">
                                                <td x-text="result.keluaran"></td>
                                            </template>
                                        </tr>
                                        <tr>
                                            <td>2. Impor</td>
                                            <template x-for="result in results" :key="result.tahun">
                                                <td x-text="result.impor"></td>
                                            </template>
                                        </tr>
                                        <tr>
                                            <td>3. Ekspor</td>
                                            <template x-for="result in results" :key="result.tahun">
                                                <td x-text="result.ekspor"></td>
                                            </template>
                                        </tr>
                                        <tr>
                                            <td>4. Perubahan Stok</td>
                                            <template x-for="result in results" :key="result.tahun">
                                                <td x-text="result.perubahanStok"></td>
                                            </template>
                                        </tr>
                                        
                                        <!-- B. Pemakaian Dalam Negeri -->
                                        <tr>
                                            <td class="tg-subheader" :colspan="results.length + 1">B. Pemakaian Dalam Negeri (Ribu Ton)</td>
                                        </tr>
                                        <tr>
                                            <td>1. Pakan</td>
                                            <template x-for="result in results" :key="result.tahun">
                                                <td x-text="result.pakan"></td>
                                            </template>
                                        </tr>
                                        <tr>
                                            <td>2. Bibit</td>
                                            <template x-for="result in results" :key="result.tahun">
                                                <td x-text="result.bibit"></td>
                                            </template>
                                        </tr>
                                        <tr>
                                            <td>3. Diolah untuk</td>
                                            <template x-for="result in results" :key="result.tahun">
                                                <td x-text="result.diolah"></td>
                                            </template>
                                        </tr>
                                        <tr>
                                            <td>&nbsp;&nbsp;&nbsp;&nbsp;- Makanan</td>
                                            <template x-for="result in results" :key="result.tahun">
                                                <td x-text="result.diolahMakanan"></td>
                                            </template>
                                        </tr>
                                        <tr>
                                            <td>&nbsp;&nbsp;&nbsp;&nbsp;- Bukan Makanan</td>
                                            <template x-for="result in results" :key="result.tahun">
                                                <td x-text="result.diolahBukanMakanan"></td>
                                            </template>
                                        </tr>
                                        <tr>
                                            <td>4. Tercecer</td>
                                            <template x-for="result in results" :key="result.tahun">
                                                <td x-text="result.tercecer"></td>
                                            </template>
                                        </tr>
                                        <tr>
                                            <td>5. Penggunaan Lain</td>
                                            <template x-for="result in results" :key="result.tahun">
                                                <td x-text="result.penggunaanLain"></td>
                                            </template>
                                        </tr>
                                        <tr>
                                            <td>6. Bahan Makanan</td>
                                            <template x-for="result in results" :key="result.tahun">
                                                <td x-text="result.bahanMakanan"></td>
                                            </template>
                                        </tr>
                                        
                                        <!-- C. Ketersediaan per Kapita -->
                                        <tr>
                                            <td class="tg-subheader" :colspan="results.length + 1">C. Ketersediaan per Kapita</td>
                                        </tr>
                                        <tr>
                                            <td>- Kilogram per Tahun</td>
                                            <template x-for="result in results" :key="result.tahun">
                                                <td x-text="result.kgPerTahun"></td>
                                            </template>
                                        </tr>
                                        <tr>
                                            <td>- Gram per Hari</td>
                                            <template x-for="result in results" :key="result.tahun">
                                                <td x-text="result.gramPerHari"></td>
                                            </template>
                                        </tr>
                                        <tr>
                                            <td>- Energi Kalori per Hari</td>
                                            <template x-for="result in results" :key="result.tahun">
                                                <td x-text="result.energiKalori"></td>
                                            </template>
                                        </tr>
                                        <tr>
                                            <td>- Protein Gram per Hari</td>
                                            <template x-for="result in results" :key="result.tahun">
                                                <td x-text="result.proteinGram"></td>
                                            </template>
                                        </tr>
                                        <tr>
                                            <td>- Lemak Gram per Hari</td>
                                            <template x-for="result in results" :key="result.tahun">
                                                <td x-text="result.lemakGram"></td>
                                            </template>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <!-- Additional Data Summary -->
                            <div class="mt-6 bg-neutral-50 p-4 rounded-lg">
                                <h5 class="font-medium text-neutral-900 mb-2">Ringkasan Data:</h5>
                                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                                    <div>
                                        <span class="text-neutral-600">Rata-rata Ketersediaan:</span>
                                        <div class="font-semibold" x-text="getAverageKetersediaan() + ' kg/kap/thn'"></div>
                                    </div>
                                    <div>
                                        <span class="text-neutral-600">Rata-rata Produksi:</span>
                                        <div class="font-semibold" x-text="getAverageProduksi() + ' ribu ton'"></div>
                                    </div>
                                    <div>
                                        <span class="text-neutral-600">Periode Data:</span>
                                        <div class="font-semibold" x-text="results.length + ' tahun'"></div>
                                    </div>
                                    <div>
                                        <span class="text-neutral-600">Tren:</span>
                                        <div class="font-semibold" x-text="getTrend()"></div>
                                    </div>
                                </div>
                            </div>

                            <!-- Data Notes -->
                            <div class="mt-4 text-sm text-neutral-600">
                                <p><strong>Catatan:</strong></p>
                                <ul class="list-disc list-inside mt-1 space-y-1">
                                    <li>Data ketersediaan dihitung berdasarkan Neraca Bahan Makanan (NBM)</li>
                                    <li>Satuan ketersediaan dalam kilogram per kapita per tahun</li>
                                    <li>Data produksi, impor, dan ekspor dalam satuan ribu ton</li>
                                    <li>Data telah disesuaikan dengan metodologi BKP-Kementan</li>
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
                    <a href="{{ route('ketersediaan.konsep-metode') }}" class="block p-4 bg-white rounded border hover:shadow-md transition duration-200">
                        <h4 class="font-medium text-blue-600">Konsep dan Metode</h4>
                        <p class="text-sm text-neutral-600 mt-1">Metodologi penyusunan NBM</p>
                    </a>
                    <a href="{{ route('konsumsi.laporan-susenas') }}" class="block p-4 bg-white rounded border hover:shadow-md transition duration-200">
                        <h4 class="font-medium text-blue-600">Data Konsumsi</h4>
                        <p class="text-sm text-neutral-600 mt-1">Lihat data konsumsi pangan dari Susenas</p>
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
                
                // Generate years from 1993 to 2025
                years: Array.from({length: 33}, (_, i) => 2025 - i),
                
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
                        {value: 'gabah', label: 'Gabah'},
                        {value: 'beras', label: 'Beras'},
                        {value: 'jagung', label: 'Jagung'},
                        {value: 'gandum', label: 'Gandum'},
                        {value: 'tepung-gandum', label: 'Tepung Gandum'},
                        {value: 'beras-ketan', label: 'Beras Ketan'}
                    ],
                    'makanan-berpati': [
                        {value: 'ubi-kayu', label: 'Ubi Kayu'},
                        {value: 'ubi-jalar', label: 'Ubi Jalar'},
                        {value: 'kentang', label: 'Kentang'},
                        {value: 'sagu', label: 'Sagu'},
                        {value: 'tepung-ubi-kayu', label: 'Tepung Ubi Kayu'},
                        {value: 'tepung-kentang', label: 'Tepung Kentang'},
                        {value: 'talas', label: 'Talas'},
                        {value: 'garut', label: 'Garut'}
                    ],
                    'gula': [
                        {value: 'tebu', label: 'Tebu'},
                        {value: 'gula-pasir', label: 'Gula Pasir'},
                        {value: 'gula-merah', label: 'Gula Merah'},
                        {value: 'sirup', label: 'Sirup'}
                    ],
                    'buah-biji-berminyak': [
                        {value: 'kelapa', label: 'Kelapa'},
                        {value: 'kemiri', label: 'Kemiri'},
                        {value: 'wijen', label: 'Wijen'},
                        {value: 'kacang-tanah', label: 'Kacang Tanah'},
                        {value: 'biji-bunga-matahari', label: 'Biji Bunga Matahari'},
                        {value: 'kelapa-sawit', label: 'Kelapa Sawit'},
                        {value: 'kedelai', label: 'Kedelai'},
                        {value: 'kapok', label: 'Kapok'},
                        {value: 'jarak', label: 'Jarak'},
                        {value: 'lada', label: 'Lada'},
                        {value: 'pala', label: 'Pala'},
                        {value: 'cengkeh', label: 'Cengkeh'}
                    ],
                    'buah-buahan': [
                        {value: 'pisang', label: 'Pisang'},
                        {value: 'jeruk', label: 'Jeruk'},
                        {value: 'mangga', label: 'Mangga'},
                        {value: 'rambutan', label: 'Rambutan'},
                        {value: 'duku', label: 'Duku'},
                        {value: 'durian', label: 'Durian'},
                        {value: 'salak', label: 'Salak'},
                        {value: 'alpukat', label: 'Alpukat'},
                        {value: 'jambu-biji', label: 'Jambu Biji'},
                        {value: 'jambu-air', label: 'Jambu Air'},
                        {value: 'nanas', label: 'Nanas'},
                        {value: 'pepaya', label: 'Pepaya'},
                        {value: 'belimbing', label: 'Belimbing'},
                        {value: 'sukun', label: 'Sukun'},
                        {value: 'nangka', label: 'Nangka'},
                        {value: 'sirsak', label: 'Sirsak'},
                        {value: 'sawo', label: 'Sawo'},
                        {value: 'markisa', label: 'Markisa'},
                        {value: 'apel', label: 'Apel'},
                        {value: 'anggur', label: 'Anggur'},
                        {value: 'strawberi', label: 'Strawberi'},
                        {value: 'melon', label: 'Melon'},
                        {value: 'semangka', label: 'Semangka'},
                        {value: 'kedondong', label: 'Kedondong'},
                        {value: 'buah-lainnya', label: 'Buah Lainnya'}
                    ],
                    'sayur-sayuran': [
                        {value: 'kacang-panjang', label: 'Kacang Panjang'},
                        {value: 'kacang-merah', label: 'Kacang Merah'},
                        {value: 'kacang-hijau', label: 'Kacang Hijau'},
                        {value: 'bayam', label: 'Bayam'},
                        {value: 'kangkung', label: 'Kangkung'},
                        {value: 'sawi', label: 'Sawi'},
                        {value: 'kol', label: 'Kol'},
                        {value: 'wortel', label: 'Wortel'},
                        {value: 'tomat', label: 'Tomat'},
                        {value: 'cabai', label: 'Cabai'},
                        {value: 'buncis', label: 'Buncis'},
                        {value: 'ketimun', label: 'Ketimun'},
                        {value: 'labu-siam', label: 'Labu Siam'},
                        {value: 'terong', label: 'Terong'},
                        {value: 'bawang-merah', label: 'Bawang Merah'},
                        {value: 'bawang-putih', label: 'Bawang Putih'},
                        {value: 'bawang-daun', label: 'Bawang Daun'},
                        {value: 'peterseli', label: 'Peterseli'},
                        {value: 'seledri', label: 'Seledri'},
                        {value: 'kacang-kapri', label: 'Kacang Kapri'},
                        {value: 'jagung-muda', label: 'Jagung Muda'},
                        {value: 'rebung', label: 'Rebung'},
                        {value: 'jamur', label: 'Jamur'},
                        {value: 'labu-kuning', label: 'Labu Kuning'},
                        {value: 'oyong', label: 'Oyong'},
                        {value: 'pare', label: 'Pare'},
                        {value: 'daun-singkong', label: 'Daun Singkong'},
                        {value: 'pepaya-muda', label: 'Pepaya Muda'},
                        {value: 'nangka-muda', label: 'Nangka Muda'},
                        {value: 'sayur-lainnya', label: 'Sayur Lainnya'}
                    ],
                    'daging': [
                        {value: 'daging-sapi', label: 'Daging Sapi'},
                        {value: 'daging-kerbau', label: 'Daging Kerbau'},
                        {value: 'daging-kambing', label: 'Daging Kambing'},
                        {value: 'daging-domba', label: 'Daging Domba'},
                        {value: 'daging-ayam', label: 'Daging Ayam'},
                        {value: 'daging-itik', label: 'Daging Itik'},
                        {value: 'daging-babi', label: 'Daging Babi'},
                        {value: 'daging-lainnya', label: 'Daging Lainnya'}
                    ],
                    'telur': [
                        {value: 'telur-ayam-ras', label: 'Telur Ayam Ras'},
                        {value: 'telur-ayam-kampung', label: 'Telur Ayam Kampung'},
                        {value: 'telur-itik', label: 'Telur Itik'},
                        {value: 'telur-lainnya', label: 'Telur Lainnya'}
                    ],
                    'susu': [
                        {value: 'susu-segar', label: 'Susu Segar'},
                        {value: 'susu-bubuk', label: 'Susu Bubuk'},
                        {value: 'susu-kental-manis', label: 'Susu Kental Manis'},
                        {value: 'susu-skim', label: 'Susu Skim'},
                        {value: 'produk-susu-lainnya', label: 'Produk Susu Lainnya'}
                    ],
                    'minyak-lemak': [
                        {value: 'minyak-kelapa', label: 'Minyak Kelapa'},
                        {value: 'minyak-sawit', label: 'Minyak Sawit'},
                        {value: 'minyak-kedelai', label: 'Minyak Kedelai'},
                        {value: 'minyak-kacang', label: 'Minyak Kacang'},
                        {value: 'margarin', label: 'Margarin'},
                        {value: 'mentega', label: 'Mentega'},
                        {value: 'lemak-hewani', label: 'Lemak Hewani'},
                        {value: 'minyak-lainnya', label: 'Minyak Lainnya'}
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
                        
                        // Generate base values
                        const produksi = Math.floor(Math.random() * 10000 + 1000);
                        const impor = Math.floor(Math.random() * 5000 + 100);
                        const ekspor = Math.floor(Math.random() * 3000 + 50);
                        const kgPerTahun = (Math.random() * 100 + 10).toFixed(1);
                        const gramPerHari = (parseFloat(kgPerTahun) * 1000 / 365).toFixed(1);
                        
                        this.results.push({
                            tahun: year,
                            kelompok: kelompokLabel,
                            komoditi: komoditiLabel || 'Semua Komoditi',
                            
                            // A. Penyediaan
                            produksi: produksi,
                            masukan: Math.floor(produksi * 0.1),
                            keluaran: Math.floor(produksi * 0.05),
                            impor: impor,
                            ekspor: ekspor,
                            perubahanStok: Math.floor(Math.random() * 500 - 250),
                            
                            // B. Pemakaian Dalam Negeri
                            pakan: Math.floor(Math.random() * 2000 + 100),
                            bibit: Math.floor(Math.random() * 500 + 50),
                            diolah: Math.floor(Math.random() * 3000 + 200),
                            diolahMakanan: Math.floor(Math.random() * 2000 + 150),
                            diolahBukanMakanan: Math.floor(Math.random() * 1000 + 50),
                            tercecer: Math.floor(Math.random() * 800 + 100),
                            penggunaanLain: Math.floor(Math.random() * 300 + 50),
                            bahanMakanan: Math.floor(Math.random() * 5000 + 500),
                            
                            // C. Ketersediaan per Kapita
                            kgPerTahun: kgPerTahun,
                            gramPerHari: gramPerHari,
                            energiKalori: Math.floor(Math.random() * 500 + 100),
                            proteinGram: (Math.random() * 20 + 2).toFixed(1),
                            lemakGram: (Math.random() * 15 + 1).toFixed(1)
                        });
                    }
                },

                getKelompokLabel(value) {
                    const labels = {
                        'padi-padian': 'Padi-padian',
                        'makanan-berpati': 'Makanan Berpati',
                        'gula': 'Gula',
                        'buah-biji-berminyak': 'Buah Biji Berminyak',
                        'buah-buahan': 'Buah-buahan',
                        'sayur-sayuran': 'Sayur-sayuran',
                        'daging': 'Daging',
                        'telur': 'Telur',
                        'susu': 'Susu',
                        'minyak-lemak': 'Minyak dan Lemak'
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
                    exportData.push(['Sumber :', 'Neraca Bahan Makanan, BKP-Kementan']);
                    exportData.push(['']);
                    exportData.push(['Catatan: Data ketersediaan dalam kg/kapita/tahun']);
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
                    
                    // Add data rows with dynamic columns
                    const ketersediaanRow = ['Ketersediaan (kg/kapita/tahun)'];
                    this.results.forEach(result => {
                        ketersediaanRow.push(parseFloat(result.ketersediaan));
                    });
                    exportData.push(ketersediaanRow);
                    
                    const produksiRow = ['Produksi (ribu ton)'];
                    this.results.forEach(result => {
                        produksiRow.push(parseInt(result.produksi));
                    });
                    exportData.push(produksiRow);
                    
                    const imporRow = ['Impor (ribu ton)'];
                    this.results.forEach(result => {
                        imporRow.push(parseInt(result.impor));
                    });
                    exportData.push(imporRow);
                    
                    const eksporRow = ['Ekspor (ribu ton)'];
                    this.results.forEach(result => {
                        eksporRow.push(parseInt(result.ekspor));
                    });
                    exportData.push(eksporRow);
                    
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
                    
                    // Set column widths dynamically
                    const colWidths = [{width: 25}]; // Uraian column
                    for (let i = 0; i < numCols; i++) {
                        colWidths.push({width: 15}); // Year columns
                    }
                    ws['!cols'] = colWidths;
                    
                    // Add worksheet to workbook
                    XLSX.utils.book_append_sheet(wb, ws, 'Data NBM');
                    
                    // Generate filename
                    const filename = `data-nbm-${this.filters.kelompok}-${Date.now()}.xlsx`;
                    
                    // Save file
                    XLSX.writeFile(wb, filename);
                },

                getAverageKetersediaan() {
                    if (this.results.length === 0) return '0.0';
                    const avg = this.results.reduce((sum, item) => sum + parseFloat(item.kgPerTahun), 0) / this.results.length;
                    return avg.toFixed(1);
                },

                getAverageProduksi() {
                    if (this.results.length === 0) return '0';
                    const avg = this.results.reduce((sum, item) => sum + parseInt(item.produksi), 0) / this.results.length;
                    return Math.round(avg).toLocaleString('id-ID');
                },

                getTrend() {
                    if (this.results.length < 2) return 'Stabil';
                    const first = parseFloat(this.results[0].kgPerTahun);
                    const last = parseFloat(this.results[this.results.length - 1].kgPerTahun);
                    if (last > first * 1.1) return 'Meningkat';
                    if (last < first * 0.9) return 'Menurun';
                    return 'Stabil';
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
