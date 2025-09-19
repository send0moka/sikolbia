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
                                <path fill-rule="evenodd"
                                    d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            <span class="ml-1 text-neutral-500">Ketersediaan</span>
                        </div>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-neutral-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                    clip-rule="evenodd"></path>
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
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4" x-data="searchForm()">
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
                                <select x-model="filters.kelompok" @change="loadKomoditi()"
                                    class="w-full px-3 py-2 border border-neutral-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <option value="">Pilih Kelompok</option>
                                    @if (!empty($kelompokOptions))
                                        @foreach ($kelompokOptions as $k)
                                            <option value="{{ $k->kode }}">{{ $k->nama }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>

                            <!-- Pilih Komoditi -->
                            <div>
                                <label for="komoditi" class="block text-sm font-medium text-neutral-700 mb-2">
                                    Komoditi
                                </label>
                                <select x-model="filters.komoditi" :disabled="!filters.kelompok"
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
                                <select x-model="filters.tahun_awal" @change="validateYearRange()"
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
                                <select x-model="filters.tahun_akhir" :disabled="!filters.tahun_awal"
                                    class="w-full px-3 py-2 border border-neutral-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent disabled:bg-neutral-100">
                                    <option value="">Pilih Tahun</option>
                                    <template x-for="year in availableEndYears" :key="year">
                                        <option :value="year" x-text="year"></option>
                                    </template>
                                </select>
                            </div>

                            <!-- Button Tampilkan Data -->
                            <button type="submit" :disabled="!canSearch"
                                :class="canSearch ? 'bg-blue-600 hover:bg-blue-700' : 'bg-neutral-400 cursor-not-allowed'"
                                class="w-full text-white px-4 py-3 rounded-md font-medium transition duration-200">
                                <span x-show="!loading">Tampilkan Data</span>
                                <span x-show="loading" class="flex items-center justify-center">
                                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white"
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10"
                                            stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor"
                                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                        </path>
                                    </svg>
                                    Memuat...
                                </span>
                            </button>

                            <!-- Reset Button -->
                            <button type="button" @click="resetForm()"
                                class="w-full bg-neutral-500 hover:bg-neutral-600 text-white px-4 py-2 rounded-md font-medium transition duration-200">
                                Reset Filter
                            </button>
                        </form>

                        <!-- Quick Stats -->
                        <div class="mt-8 pt-6 border-t border-neutral-200">
                            <h4 class="font-medium text-neutral-900 mb-3">Informasi Data NBM</h4>
                            <div class="space-y-2 text-sm text-neutral-600">
                                <p>• <strong>Periode:</strong> 1993 - 2024 (Bulanan)</p>
                                <p>• <strong>Kelompok:</strong> 10 Kelompok Pangan</p>
                                <p>• <strong>Komoditas:</strong> 200+ Komoditas</p>
                                <p>• <strong>Cakupan:</strong> Data Nasional</p>
                                <p>• <strong>Indikator:</strong> Ekonomi, Iklim, Kebijakan</p>
                            </div>

                            <!-- Data Integration Note -->
                            <div class="mt-4 bg-blue-50 p-3 rounded border-l-4 border-blue-400">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 text-blue-600 mr-2" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span class="text-blue-800 font-medium text-xs">Data Terintegrasi
                                        Multi-Dimensi</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Results - Right Column -->
                <div class="lg:col-span-2">
                    <!-- No Data State -->
                    <div x-show="!hasSearched && !hasData" class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-neutral-400" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-neutral-900">Belum Ada Data yang Ditampilkan</h3>
                        <p class="mt-1 text-sm text-neutral-500">Pilih filter dan klik "Tampilkan Data" untuk melihat
                            hasil</p>
                    </div>

                    <!-- Loading State -->
                    <div x-show="loading" class="text-center py-12">
                        <svg class="animate-spin mx-auto h-8 w-8 text-blue-600" xmlns="http://www.w3.org/2000/svg"
                            fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                            </path>
                        </svg>
                        <p class="mt-2 text-sm text-neutral-600">Memuat data...</p>
                    </div>

                    <!-- Results Table -->
                    <div x-show="hasData && !loading" class="bg-white rounded-lg border border-neutral-200">
                        <!-- Results Header -->
                        <div
                            class="bg-neutral-50 px-6 py-4 border-b border-neutral-200 flex justify-between items-center">
                            <div>
                                <h3 class="text-lg font-semibold text-neutral-900">Hasil Pencarian</h3>
                                <p class="text-sm text-neutral-600" x-text="`${results.length} data ditemukan`"></p>
                            </div>
                            <button @click="exportToExcel()"
                                class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md flex items-center space-x-2 transition duration-200">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                    </path>
                                </svg>
                                <span>Export Excel</span>
                            </button>
                        </div>

                        <!-- Data Information -->
                        <div class="p-6 bg-blue-50 border-b">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                                <div>
                                    <strong>Kelompok:</strong> <span
                                        x-text="getKelompokLabel(filters.kelompok)"></span>
                                </div>
                                <div>
                                    <strong>Komoditi:</strong> <span
                                        x-text="getKomoditiLabel(filters.komoditi) || 'Semua Komoditi'"></span>
                                </div>
                                <div>
                                    <strong>Periode:</strong> <span
                                        x-text="filters.tahun_awal + (filters.tahun_akhir && filters.tahun_akhir !== filters.tahun_awal ? ' - ' + filters.tahun_akhir : '')"></span>
                                </div>
                                <div>
                                    <strong>Sumber:</strong> Neraca Bahan Makanan, BKP-Kementan
                                </div>
                            </div>
                        </div>

                        <!-- Catatan Section -->
                        <div class="p-6">
                            <h4 class="font-semibold text-neutral-900 mb-4">Data NBM (Ketersediaan Per Kapita Per
                                Tahun):</h4>

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

                                .tg td,
                                .tg th {
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
                                <table class="tg text-sm">
                                    <thead>
                                        <tr>
                                            <th class="tg-header" rowspan="2">Uraian</th>
                                            <th class="tg-header" :colspan="results.length">Tahun</th>
                                        </tr>
                                        <tr>
                                            <template x-for="(result, index) in results" :key="index">
                                                <th class="tg-header">
                                                    <span x-text="result.tahun"></span>
                                                    <span x-show="result.tahun == '2023'"
                                                        class="text-blue-600">(s)</span>
                                                    <span x-show="result.tahun == '2024'"
                                                        class="text-blue-600">(ss)</span>
                                                </th>
                                            </template>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- A. Penyediaan -->
                                        <tr>
                                            <td class="tg-subheader">A. Penyediaan <i class="text-xs">/ Supply</i>
                                                (Ribu Ton)</td>
                                            <template x-for="result in results" :key="result.tahun">
                                                <td class="tg-header"
                                                    x-text="Number(result.penyediaan) === 0 ? '-' : Number(result.penyediaan).toLocaleString('id-ID')">
                                                </td>
                                            </template>
                                        </tr>
                                        <tr>
                                            <td :colspan="results.length + 1">1. Produksi <i class="text-xs">/
                                                    Production</i></td>
                                        </tr>
                                        <tr>
                                            <td>&nbsp;&nbsp;&nbsp;&nbsp;- Masukan <i class="text-xs">/ Input</i></td>
                                            <template x-for="result in results" :key="result.tahun">
                                                <td
                                                    x-text="Number(result.masukan) === 0 ? '-' : Number(result.masukan).toLocaleString('id-ID')">
                                                </td>
                                            </template>
                                        </tr>
                                        <tr>
                                            <td>&nbsp;&nbsp;&nbsp;&nbsp;- Keluaran <i class="text-xs">/ Output</i></td>
                                            <template x-for="result in results" :key="result.tahun">
                                                <td
                                                    x-text="Number(result.keluaran) === 0 ? '-' : Number(result.keluaran).toLocaleString('id-ID')">
                                                </td>
                                            </template>
                                        </tr>
                                        <tr>
                                            <td>2. Impor <i class="text-xs">/ Import</i></td>
                                            <template x-for="result in results" :key="result.tahun">
                                                <td
                                                    x-text="Number(result.impor) === 0 ? '-' : Number(result.impor).toLocaleString('id-ID', { minimumFractionDigits: 2, maximumFractionDigits: 2 })">
                                                </td>
                                            </template>
                                        </tr>
                                        <tr>
                                            <td>3. Ekspor <i class="text-xs">/ Export</i></td>
                                            <template x-for="result in results" :key="result.tahun">
                                                <td
                                                    x-text="Number(result.ekspor) === 0 ? '-' : Number(result.ekspor).toLocaleString('id-ID', { minimumFractionDigits: 2, maximumFractionDigits: 2 })">
                                                </td>
                                            </template>
                                        </tr>
                                        <tr>
                                            <td>4. Perubahan Stok <i class="text-xs">/ Change in stocks</i></td>
                                            <template x-for="result in results" :key="result.tahun">
                                                <td
                                                    x-text="Number(result.perubahanStok) === 0 ? '-' : Number(result.perubahanStok).toLocaleString('id-ID', { minimumFractionDigits: 2, maximumFractionDigits: 2 })">
                                                </td>
                                            </template>
                                        </tr>

                                        <!-- B. Penggunaan -->
                                        <tr>
                                            <td class="tg-subheader">B. Penggunaan <i class="text-xs">/
                                                    Utilization</i> (Ribu Ton)</td>
                                            <template x-for="result in results" :key="result.tahun">
                                                <td class="tg-header"
                                                    x-text="Number(result.penggunaan) === 0 ? '-' : Number(result.penggunaan).toLocaleString('id-ID')">
                                                </td>
                                            </template>
                                        </tr>
                                        <tr>
                                            <td>1. Pakan <i class="text-xs">/ Feed</i></td>
                                            <template x-for="result in results" :key="result.tahun">
                                                <td
                                                    x-text="Number(result.pakan) === 0 ? '-' : Number(result.pakan).toLocaleString('id-ID')">
                                                </td>
                                            </template>
                                        </tr>
                                        <tr>
                                            <td>2. Bibit <i class="text-xs">/ Seed</i></td>
                                            <template x-for="result in results" :key="result.tahun">
                                                <td
                                                    x-text="Number(result.bibit) === 0 ? '-' : Number(result.bibit).toLocaleString('id-ID')">
                                                </td>
                                            </template>
                                        </tr>
                                        <tr>
                                            <td :colspan="results.length + 1">3. Diolah untuk <i class="text-xs">/
                                                    Manufactured for</i> :
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>&nbsp;&nbsp;&nbsp;&nbsp;- Makanan <i class="text-xs">/ Food</i></td>
                                            <template x-for="result in results" :key="result.tahun">
                                                <td
                                                    x-text="Number(result.diolahMakanan) === 0 ? '-' : Number(result.diolahMakanan).toLocaleString('id-ID')">
                                                </td>
                                            </template>
                                        </tr>
                                        <tr>
                                            <td>&nbsp;&nbsp;&nbsp;&nbsp;- Bukan Makanan <i class="text-xs">/ Non
                                                    food</i></td>
                                            <template x-for="result in results" :key="result.tahun">
                                                <td
                                                    x-text="Number(result.diolahBukanMakanan) === 0 ? '-' : Number(result.diolahBukanMakanan).toLocaleString('id-ID')">
                                                </td>
                                            </template>
                                        </tr>
                                        <tr>
                                            <td>4. Tercecer <i class="text-xs">/ Waste</i></td>
                                            <template x-for="result in results" :key="result.tahun">
                                                <td
                                                    x-text="Number(result.tercecer) === 0 ? '-' : Number(result.tercecer).toLocaleString('id-ID')">
                                                </td>
                                            </template>
                                        </tr>
                                        <tr>
                                            <td>5. Penggunaan Lain <i class="text-xs">/ Other Uses</i></td>
                                            <template x-for="result in results" :key="result.tahun">
                                                <td
                                                    x-text="Number(result.penggunaanLain) === 0 ? '-' : Number(result.penggunaanLain).toLocaleString('id-ID')">
                                                </td>
                                            </template>
                                        </tr>
                                        <tr>
                                            <td>6. Bahan Makanan <i class="text-xs">/ Food Ingredients</i></td>
                                            <template x-for="result in results" :key="result.tahun">
                                                <td
                                                    x-text="Number(result.bahanMakanan) === 0 ? '-' : Number(result.bahanMakanan).toLocaleString('id-ID')">
                                                </td>
                                            </template>
                                        </tr>

                                        <!-- C. Ketersediaan per Kapita -->
                                        <tr>
                                            <td class="tg-subheader" :colspan="results.length + 1">C. Ketersediaan per
                                                Kapita <i class="text-xs">/ Per capita availability</i></td>
                                        </tr>
                                        <tr>
                                            <td>- Kilogram per Tahun <i class="text-xs">/ Kilograms per Year</i></td>
                                            <template x-for="result in results" :key="result.tahun">
                                                <td
                                                    x-text="Number(result.kgPerTahun) === 0 ? '-' : Number(result.kgPerTahun).toLocaleString('id-ID')">
                                                </td>
                                            </template>
                                        </tr>
                                        <tr>
                                            <td>- Gram per Hari <i class="text-xs">/ Grams per Day</i></td>
                                            <template x-for="result in results" :key="result.tahun">
                                                <td
                                                    x-text="Number(result.gramPerHari) === 0 ? '-' : Number(result.gramPerHari).toLocaleString('id-ID')">
                                                </td>
                                            </template>
                                        </tr>
                                        <tr>
                                            <td>- Energi Kalori per Hari <i class="text-xs">/ Calories per Day</i></td>
                                            <template x-for="result in results" :key="result.tahun">
                                                <td
                                                    x-text="Number(result.energiKalori) === 0 ? '-' : Number(result.energiKalori).toLocaleString('id-ID')">
                                                </td>
                                            </template>
                                        </tr>
                                        <tr>
                                            <td>- Protein Gram per Hari <i class="text-xs">/ Protein Grams per Day</i>
                                            </td>
                                            <template x-for="result in results" :key="result.tahun">
                                                <td
                                                    x-text="Number(result.proteinGram) === 0 ? '-' : Number(result.proteinGram).toLocaleString('id-ID')">
                                                </td>
                                            </template>
                                        </tr>
                                        <tr>
                                            <td>- Lemak Gram per Hari <i class="text-xs">/ Fat Grams per Day</i></td>
                                            <template x-for="result in results" :key="result.tahun">
                                                <td
                                                    x-text="Number(result.lemakGram) === 0 ? '-' : Number(result.lemakGram).toLocaleString('id-ID')">
                                                </td>
                                            </template>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="mb-2 text-xs text-neutral-600">
                                <strong>Keterangan:</strong> <span class="text-blue-600">(s)</span> Angka Sementara, <span class="text-blue-600">(ss)</span> Angka Sangat Sementara<br>
                                <strong>Mulai tahun 2017</strong> menggunakan data produksi padi bersumber dari KSA, BPS<br>
                                <i>Note: (s) Preliminary Figures, (ss) Very Preliminary Figures</i>
                            </div>
                            <!-- Additional Data Summary -->
                            <div class="mt-6 bg-neutral-50 p-4 rounded-lg">
                                <h5 class="font-medium text-neutral-900 mb-2">Ringkasan Data:</h5>
                                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                                    <div>
                                        <span class="text-neutral-600">Rata-rata Ketersediaan:</span>
                                        <div class="font-semibold" x-text="getAverageKetersediaan() + ' kg/kap/thn'">
                                        </div>
                                    </div>
                                    <div>
                                        <span class="text-neutral-600">Rata-rata Penyediaan:</span>
                                        <div class="font-semibold" x-text="getAveragePenyediaan() + ' ribu ton'">
                                        </div>
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
                                <p><strong>Catatan Data NBM Diperkaya:</strong></p>
                                <ul class="list-disc list-inside mt-1 space-y-1">
                                    <li>Data ketersediaan dihitung berdasarkan Neraca Bahan Makanan (NBM) dengan
                                        metodologi BKP-Kementan</li>
                                    <li>Satuan ketersediaan dalam kilogram per kapita per tahun, dengan data penyediaan
                                        dalam ribu ton</li>
                                    <li><strong>Data Diperkaya:</strong> Termasuk indikator ekonomi (harga, inflasi,
                                        GDP), iklim (curah hujan, suhu, El Niño), dan kebijakan (impor, subsidi)</li>
                                    <li><strong>Kualitas Data:</strong> Setiap record memiliki confidence score, status
                                        validasi, dan deteksi outlier</li>
                                    <li><strong>Temporal Granularity:</strong> Data tersedia dalam periode bulanan,
                                        kuartalan, dan tahunan (1993-2024)</li>
                                    <li><strong>Multi-Source Integration:</strong> Integrasi data dari BPS, Kementan,
                                        BI, BMKG, dan sumber resmi lainnya</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- No Results -->
                    <div x-show="hasSearched && !hasData && !loading"
                        class="text-center py-12 bg-white rounded-lg border border-neutral-200">
                        <svg class="mx-auto h-12 w-12 text-neutral-400" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9.172 16.172a4 4 0 015.656 0M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-neutral-900">Tidak Ada Data Ditemukan</h3>
                        <p class="mt-1 text-sm text-neutral-500">Coba ubah filter pencarian atau periode tahun</p>
                    </div>
                </div>
            </div>

            <!-- Related Links -->
            <div class="mt-12 bg-neutral-50 p-6 rounded-lg">
                <h3 class="text-lg font-semibold text-neutral-900 mb-4">Informasi Tambahan & Halaman Terkait</h3>

                <!-- NBM System Overview -->
                <div class="mb-6 bg-gradient-to-r from-blue-50 to-green-50 p-4 rounded-lg border border-blue-200">
                    <h4 class="font-semibold text-blue-900 mb-3">📊 Sistem NBM Terintegrasi</h4>
                    <p class="text-blue-800 text-sm mb-3">
                        Neraca Bahan Makanan Indonesia menyediakan analisis komprehensif ketersediaan pangan dengan
                        mengintegrasikan data produksi, perdagangan, dan berbagai faktor yang mempengaruhi ketahanan
                        pangan.
                    </p>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                        <div>
                            <h5 class="font-medium text-green-800 mb-1">💰 Aspek Ekonomi</h5>
                            <p class="text-green-700">Harga, inflasi, daya beli, dan stabilitas ekonomi pangan</p>
                        </div>
                        <div>
                            <h5 class="font-medium text-blue-800 mb-1">🌡️ Kondisi Iklim & Produksi</h5>
                            <p class="text-blue-700">Cuaca, produktivitas lahan, dan manajemen cadangan pangan</p>
                        </div>
                        <div>
                            <h5 class="font-medium text-purple-800 mb-1">🏛️ Kebijakan & Kualitas</h5>
                            <p class="text-purple-700">Regulasi perdagangan, subsidi, dan sistem validasi data</p>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <a href="{{ route('ketersediaan.konsep-metode') }}"
                        class="block p-4 bg-white rounded border hover:shadow-md transition duration-200">
                        <h4 class="font-medium text-blue-600">📖 Konsep dan Metode</h4>
                        <p class="text-sm text-neutral-600 mt-1">Metodologi NBM dan indikator pendukung</p>
                    </a>
                    <a href="{{ route('ketersediaan.dashboard-komoditas') }}"
                        class="block p-4 bg-white rounded border hover:shadow-md transition duration-200">
                        <h4 class="font-medium text-blue-600">📊 Dashboard Komoditas</h4>
                        <p class="text-sm text-neutral-600 mt-1">Monitor harga dan tren komoditas real-time</p>
                    </a>
                    <a href="{{ route('login') }}"
                        class="block p-4 bg-white rounded border hover:shadow-md transition duration-200">
                        <h4 class="font-medium text-blue-600">⚙️ Manajemen Data</h4>
                        <p class="text-sm text-neutral-600 mt-1">Login untuk akses data lengkap dan analisis lanjutan
                        </p>
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
                // Inject kelompok options from server-side into the Alpine component
                kelompokOptions: @json($kelompokOptions ?? []),
                availableKomoditi: [],
                results: [],
                loading: false,
                hasSearched: false,

                // Generate years from 1993 to 2025
                years: Array.from({
                    length: 33
                }, (_, i) => 2025 - i),

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

                // komoditiData removed: komoditi list is loaded dynamically via AJAX

                async loadKomoditi() {
                    this.availableKomoditi = [];
                    this.filters.komoditi = '';
                    if (!this.filters.kelompok) return;
                    try {
                        const res = await fetch(
                            `/ketersediaan/api/komoditi?kode_kelompok=${encodeURIComponent(this.filters.kelompok)}`, {
                                headers: {
                                    'Accept': 'application/json'
                                }
                            });
                        if (!res.ok) return;
                        const payload = await res.json();
                        this.availableKomoditi = payload.data || [];
                    } catch (e) {
                        console.error('Failed to load komoditi', e);
                    }
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

                    try {
                        const params = new URLSearchParams();
                        params.append('kelompok', this.filters.kelompok);
                        if (this.filters.komoditi) params.append('komoditi', this.filters.komoditi);
                        params.append('tahun_awal', this.filters.tahun_awal);
                        if (this.filters.tahun_akhir) params.append('tahun_akhir', this.filters.tahun_akhir);

                        const res = await fetch(`/ketersediaan/api/laporan-nbm?${params.toString()}`, {
                            headers: {
                                'Accept': 'application/json'
                            }
                        });

                        if (!res.ok) {
                            const err = await res.json().catch(() => ({}));
                            console.error('API error', err);
                            this.results = [];
                            this.loading = false;
                            return;
                        }

                        const payload = await res.json();
                        this.results = payload.data || [];
                    } catch (e) {
                        console.error(e);
                        this.results = [];
                    } finally {
                        this.loading = false;
                    }
                },

                // Data will be fetched from backend API endpoint

                getKelompokLabel(value) {
                    if (!value) return '';
                    const found = this.kelompokOptions.find(k => String(k.kode) === String(value));
                    return found ? found.nama : value;
                },

                getKomoditiLabel(value) {
                    if (!value) return '';
                    // First check currently loaded komoditi list
                    const found = this.availableKomoditi.find(k => String(k.value) === String(value));
                    if (found) return found.label;

                    // As a fallback, try fetching the komoditi from the server synchronously (best-effort)
                    return value;
                },

                exportToExcel() {
                    // Create a new workbook
                    const wb = XLSX.utils.book_new();

                    // Prepare data with headers and information
                    const exportData = [];

                    // Add header information
                    exportData.push(['Data NBM (Ketersediaan Per Kapita Per Tahun)']);
                    exportData.push(['']);
                    exportData.push(['Kelompok:', this.getKelompokLabel(this.filters.kelompok)]);
                    exportData.push(['Komoditi:', this.getKomoditiLabel(this.filters.komoditi) || 'Semua Komoditi']);
                    exportData.push(['Periode:', this.filters.tahun_awal + (this.filters.tahun_akhir && this.filters.tahun_akhir !== this.filters.tahun_awal ? ' - ' + this.filters.tahun_akhir : '')]);
                    exportData.push(['Sumber:', 'Neraca Bahan Makanan, BKP-Kementan']);
                    exportData.push(['']);

                    // Table headers
                    const headerRow1 = ['Uraian'];
                    for (let i = 0; i < this.results.length; i++) {
                        headerRow1.push('Tahun');
                    }
                    exportData.push(headerRow1);

                    // Year row with (s) and (ss) annotations
                    const yearRow = [''];
                    this.results.forEach(result => {
                        let yearLabel = result.tahun;
                        if (result.tahun === '2023') yearLabel += ' (s)';
                        if (result.tahun === '2024') yearLabel += ' (ss)';
                        yearRow.push(yearLabel);
                    });
                    exportData.push(yearRow);

                    // A. PENYEDIAAN SECTION
                    const penyediaanHeaderRow = ['A. Penyediaan / Supply (Ribu Ton)'];
                    this.results.forEach(result => {
                        penyediaanHeaderRow.push(Number(result.penyediaan) === 0 ? '-' : Number(result.penyediaan));
                    });
                    exportData.push(penyediaanHeaderRow);

                    // 1. Produksi
                    exportData.push(['1. Produksi / Production'].concat(new Array(this.results.length).fill('')));
                    
                    // - Masukan
                    const masukanRow = ['    - Masukan / Input'];
                    this.results.forEach(result => {
                        masukanRow.push(Number(result.masukan) === 0 ? '-' : Number(result.masukan));
                    });
                    exportData.push(masukanRow);

                    // - Keluaran
                    const keluaranRow = ['    - Keluaran / Output'];
                    this.results.forEach(result => {
                        keluaranRow.push(Number(result.keluaran) === 0 ? '-' : Number(result.keluaran));
                    });
                    exportData.push(keluaranRow);

                    // 2. Impor
                    const imporRow = ['2. Impor / Import'];
                    this.results.forEach(result => {
                        imporRow.push(Number(result.impor) === 0 ? '-' : Number(result.impor));
                    });
                    exportData.push(imporRow);

                    // 3. Ekspor
                    const eksporRow = ['3. Ekspor / Export'];
                    this.results.forEach(result => {
                        eksporRow.push(Number(result.ekspor) === 0 ? '-' : Number(result.ekspor));
                    });
                    exportData.push(eksporRow);

                    // 4. Perubahan Stok
                    const perubahanStokRow = ['4. Perubahan Stok / Change in stocks'];
                    this.results.forEach(result => {
                        perubahanStokRow.push(Number(result.perubahanStok) === 0 ? '-' : Number(result.perubahanStok));
                    });
                    exportData.push(perubahanStokRow);

                    // B. PENGGUNAAN SECTION
                    const penggunaanHeaderRow = ['B. Penggunaan / Utilization (Ribu Ton)'];
                    this.results.forEach(result => {
                        penggunaanHeaderRow.push(Number(result.penggunaan) === 0 ? '-' : Number(result.penggunaan));
                    });
                    exportData.push(penggunaanHeaderRow);

                    // 1. Pakan
                    const pakanRow = ['1. Pakan / Feed'];
                    this.results.forEach(result => {
                        pakanRow.push(Number(result.pakan) === 0 ? '-' : Number(result.pakan));
                    });
                    exportData.push(pakanRow);

                    // 2. Bibit
                    const bibitRow = ['2. Bibit / Seed'];
                    this.results.forEach(result => {
                        bibitRow.push(Number(result.bibit) === 0 ? '-' : Number(result.bibit));
                    });
                    exportData.push(bibitRow);

                    // 3. Diolah untuk
                    exportData.push(['3. Diolah untuk / Manufactured for :'].concat(new Array(this.results.length).fill('')));
                    
                    // - Makanan
                    const diolahMakananRow = ['    - Makanan / Food'];
                    this.results.forEach(result => {
                        diolahMakananRow.push(Number(result.diolahMakanan) === 0 ? '-' : Number(result.diolahMakanan));
                    });
                    exportData.push(diolahMakananRow);

                    // - Bukan Makanan
                    const diolahBukanMakananRow = ['    - Bukan Makanan / Non food'];
                    this.results.forEach(result => {
                        diolahBukanMakananRow.push(Number(result.diolahBukanMakanan) === 0 ? '-' : Number(result.diolahBukanMakanan));
                    });
                    exportData.push(diolahBukanMakananRow);

                    // 4. Tercecer
                    const tercecerRow = ['4. Tercecer / Waste'];
                    this.results.forEach(result => {
                        tercecerRow.push(Number(result.tercecer) === 0 ? '-' : Number(result.tercecer));
                    });
                    exportData.push(tercecerRow);

                    // 5. Penggunaan Lain
                    const penggunaanLainRow = ['5. Penggunaan Lain / Other Uses'];
                    this.results.forEach(result => {
                        penggunaanLainRow.push(Number(result.penggunaanLain) === 0 ? '-' : Number(result.penggunaanLain));
                    });
                    exportData.push(penggunaanLainRow);

                    // 6. Bahan Makanan
                    const bahanMakananRow = ['6. Bahan Makanan / Food Ingredients'];
                    this.results.forEach(result => {
                        bahanMakananRow.push(Number(result.bahanMakanan) === 0 ? '-' : Number(result.bahanMakanan));
                    });
                    exportData.push(bahanMakananRow);

                    // C. KETERSEDIAAN PER KAPITA SECTION
                    exportData.push(['C. Ketersediaan per Kapita / Per capita availability'].concat(new Array(this.results.length).fill('')));

                    // - Kilogram per Tahun
                    const kgPerTahunRow = ['- Kilogram per Tahun / Kilograms per Year'];
                    this.results.forEach(result => {
                        kgPerTahunRow.push(Number(result.kgPerTahun) === 0 ? '-' : Number(result.kgPerTahun));
                    });
                    exportData.push(kgPerTahunRow);

                    // - Gram per Hari
                    const gramPerHariRow = ['- Gram per Hari / Grams per Day'];
                    this.results.forEach(result => {
                        gramPerHariRow.push(Number(result.gramPerHari) === 0 ? '-' : Number(result.gramPerHari));
                    });
                    exportData.push(gramPerHariRow);

                    // - Energi Kalori per Hari
                    const energiKaloriRow = ['- Energi Kalori per Hari / Calories per Day'];
                    this.results.forEach(result => {
                        energiKaloriRow.push(Number(result.energiKalori) === 0 ? '-' : Number(result.energiKalori));
                    });
                    exportData.push(energiKaloriRow);

                    // - Protein Gram per Hari
                    const proteinGramRow = ['- Protein Gram per Hari / Protein Grams per Day'];
                    this.results.forEach(result => {
                        proteinGramRow.push(Number(result.proteinGram) === 0 ? '-' : Number(result.proteinGram));
                    });
                    exportData.push(proteinGramRow);

                    // - Lemak Gram per Hari
                    const lemakGramRow = ['- Lemak Gram per Hari / Fat Grams per Day'];
                    this.results.forEach(result => {
                        lemakGramRow.push(Number(result.lemakGram) === 0 ? '-' : Number(result.lemakGram));
                    });
                    exportData.push(lemakGramRow);

                    // Add notes section
                    exportData.push(['']);
                    exportData.push(['Keterangan: (s) Angka Sementara, (ss) Angka Sangat Sementara']);
                    exportData.push(['Mulai tahun 2017 menggunakan data produksi padi bersumber dari KSA, BPS']);
                    exportData.push(['Note: (s) Preliminary Figures, (ss) Very Preliminary Figures']);

                    // Create worksheet
                    const ws = XLSX.utils.aoa_to_sheet(exportData);

                    // Define merge ranges for proper table layout
                    if (!ws['!merges']) ws['!merges'] = [];

                    const numCols = this.results.length;

                    // Merge title
                    if (numCols > 0) {
                        ws['!merges'].push({
                            s: { r: 0, c: 0 },
                            e: { r: 0, c: numCols }
                        });
                    }

                    // Merge "Uraian" header (spans 2 rows)
                    ws['!merges'].push({
                        s: { r: 7, c: 0 },
                        e: { r: 8, c: 0 }
                    });

                    // Merge "Tahun" header (spans all year columns)
                    if (numCols > 1) {
                        ws['!merges'].push({
                            s: { r: 7, c: 1 },
                            e: { r: 7, c: numCols }
                        });
                    }

                    // Set column widths
                    const colWidths = [{ width: 35 }]; // Uraian column
                    for (let i = 0; i < numCols; i++) {
                        colWidths.push({ width: 15 }); // Year columns
                    }
                    ws['!cols'] = colWidths;

                    // Add worksheet to workbook
                    XLSX.utils.book_append_sheet(wb, ws, 'Data NBM');

                    // Generate filename
                    const filename = `data-nbm-${this.getKelompokLabel(this.filters.kelompok).replace(/[^a-zA-Z0-9]/g, '_')}-${this.filters.tahun_awal}${this.filters.tahun_akhir ? '_' + this.filters.tahun_akhir : ''}-${Date.now()}.xlsx`;

                    // Save file
                    XLSX.writeFile(wb, filename);
                },

                getAverageKetersediaan() {
                    if (this.results.length === 0) return '0.0';
                    const avg = this.results.reduce((sum, item) => sum + parseFloat(item.kgPerTahun), 0) / this.results
                        .length;
                    return avg.toFixed(1);
                },

                getAveragePenyediaan() {
                    if (this.results.length === 0) return '0';
                    const avg = this.results.reduce((sum, item) => sum + parseFloat(item.penyediaan), 0) / this.results
                        .length;
                    return Math.round(avg).toLocaleString('id-ID');
                },

                getTrend() {
                    if (this.results.length < 2) return 'Stabil';
                    
                    // Calculate slope using linear regression approach
                    const first = parseFloat(this.results[0].kgPerTahun);
                    const last = parseFloat(this.results[this.results.length - 1].kgPerTahun);
                    const numberOfYears = this.results.length - 1;
                    
                    // Calculate average slope (kg per year change)
                    const slope = (last - first) / numberOfYears;
                    
                    // Determine trend based on slope threshold of ±1 kg/year
                    if (slope > 1) return 'Meningkat';
                    if (slope < -1) return 'Menurun';
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
