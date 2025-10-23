<x-layouts.landing title="Laporan NBM Publik - SIKOLBIA">
    <!-- FontAwesome Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <div class="py-12 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8" x-data="laporanPublik()">
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
                            <span class="ml-1 text-blue-600 font-medium">Laporan NBM Publik</span>
                        </div>
                    </li>
                </ol>
            </nav>

            <!-- Page Header -->
            <div class="mb-8">
                <h1 class="text-3xl md:text-4xl font-bold text-neutral-900 mb-4">
                    Laporan Neraca Bahan Makanan (NBM) Indonesia
                </h1>
                <p class="text-xl text-neutral-600">
                    Akses data konsumsi dan ketersediaan pangan nasional - Level akses publik
                </p>
            </div>

            <!-- Content Container with proper spacing -->
            <div class="max-w-7xl mx-auto">
                <!-- Filter Section -->
                <div class="bg-white p-6 rounded-lg shadow-md mb-8">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">
                        <i class="fas fa-filter text-blue-600 mr-2"></i>
                        Filter Data
                    </h3>
                    <form method="GET" action="{{ route('public.ketersediaan.laporan-publik') }}"
                        class="grid grid-cols-1 md:grid-cols-3 gap-4">

                        <!-- Filter Tahun -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tahun</label>
                            <select name="tahun"
                                class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                @foreach ($tahunList as $tahunOption)
                                    <option value="{{ $tahunOption }}" {{ $tahun == $tahunOption ? 'selected' : '' }}>
                                        {{ $tahunOption }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Filter Kelompok -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Kelompok Komoditas</label>
                            <select name="kelompok"
                                class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                <option value="">Semua Kelompok</option>
                                @foreach ($kelompokList as $kel)
                                    <option value="{{ $kel->kode }}" {{ $kelompok == $kel->kode ? 'selected' : '' }}>
                                        {{ $kel->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Submit Button -->
                        <div class="flex items-end">
                            <button type="submit"
                                class="w-full bg-green-600 text-white py-3 px-6 rounded-lg hover:bg-green-700 focus:ring-2 focus:ring-green-500">
                                <i class="fas fa-search mr-2"></i>
                                Tampilkan Data
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Summary Stats -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                    <div class="bg-white p-6 rounded-lg shadow-md">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-blue-100 text-blue-600 mr-4">
                                <i class="fas fa-list text-xl"></i>
                            </div>
                            <div>
                                <p class="text-2xl font-bold text-gray-800" x-text="stats.totalKomoditas"></p>
                                <p class="text-gray-600 text-sm">Komoditas Ditampilkan</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white p-6 rounded-lg shadow-md">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-green-100 text-green-600 mr-4">
                                <i class="fas fa-fire text-xl"></i>
                            </div>
                            <div>
                                <p class="text-2xl font-bold text-gray-800" x-text="formatNumber(stats.totalKalori)"></p>
                                <p class="text-gray-600 text-sm">Total Kalori/Hari</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white p-6 rounded-lg shadow-md">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-purple-100 text-purple-600 mr-4">
                                <i class="fas fa-chart-bar text-xl"></i>
                            </div>
                            <div>
                                <p class="text-2xl font-bold text-gray-800" x-text="formatNumber(stats.avgKalori)"></p>
                                <p class="text-gray-600 text-sm">Rata-rata Kalori/Hari</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white p-6 rounded-lg shadow-md">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-orange-100 text-orange-600 mr-4">
                                <i class="fas fa-crown text-xl"></i>
                            </div>
                            <div>
                                <p class="text-2xl font-bold text-gray-800" x-text="formatNumber(stats.maxKalori)"></p>
                                <p class="text-gray-600 text-sm">Kalori Tertinggi/Hari</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Insights Panel -->
                <div x-show="!loading && filteredData.length > 0" class="bg-gradient-to-r from-green-50 to-blue-50 border border-green-200 rounded-lg p-6 mb-8">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">
                        <i class="fas fa-lightbulb text-yellow-600 mr-2"></i>
                        Quick Insights
                        <span x-show="searchTerm" class="text-sm font-normal text-gray-600 ml-2">
                            untuk "<span x-text="searchTerm" class="font-medium"></span>"
                        </span>
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 text-sm">
                        <div class="bg-white p-4 rounded-lg shadow-sm">
                            <div class="flex items-center">
                                <i class="fas fa-star text-yellow-500 mr-2"></i>
                                <div>
                                    <div class="font-medium text-gray-800">Komoditas Teratas</div>
                                    <div class="text-gray-600" x-text="getDataInsights()?.topKomoditas || 'N/A'"></div>
                                    <div class="text-xs text-gray-500">
                                        <span x-text="formatNumber(getDataInsights()?.topKalori || 0)"></span> kkal/hari
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-white p-4 rounded-lg shadow-sm">
                            <div class="flex items-center">
                                <i class="fas fa-chart-line text-green-500 mr-2"></i>
                                <div>
                                    <div class="font-medium text-gray-800">Di Atas Rata-rata</div>
                                    <div class="text-gray-600">
                                        <span x-text="getDataInsights()?.aboveAverage || 0"></span> komoditas
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        dari <span x-text="filteredData.length"></span> total
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-white p-4 rounded-lg shadow-sm">
                            <div class="flex items-center">
                                <i class="fas fa-layer-group text-blue-500 mr-2"></i>
                                <div>
                                    <div class="font-medium text-gray-800">Kelompok Unik</div>
                                    <div class="text-gray-600">
                                        <span x-text="getDataInsights()?.kelompokCount || 0"></span> kelompok
                                    </div>
                                    <div class="text-xs text-gray-500">dalam hasil filter</div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-white p-4 rounded-lg shadow-sm">
                            <div class="flex items-center">
                                <i class="fas fa-percentage text-purple-500 mr-2"></i>
                                <div>
                                    <div class="font-medium text-gray-800">Coverage</div>
                                    <div class="text-gray-600">
                                        <span x-text="Math.round((filteredData.length / data.length) * 100)"></span>%
                                    </div>
                                    <div class="text-xs text-gray-500">dari total data</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Quick Filter Buttons -->
                    <div class="mt-4 pt-4 border-t border-green-200">
                        <div class="text-sm text-gray-600 mb-2">Filter Cepat:</div>
                        <div class="flex flex-wrap gap-2">
                            <button @click="searchTerm = 'Padi'; filterData()" 
                                    class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs hover:bg-green-200 transition-colors">
                                <i class="fas fa-seedling mr-1"></i> Padi-padian
                            </button>
                            <button @click="searchTerm = 'Ikan'; filterData()" 
                                    class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs hover:bg-blue-200 transition-colors">
                                <i class="fas fa-fish mr-1"></i> Ikan
                            </button>
                            <button @click="searchTerm = 'Daging'; filterData()" 
                                    class="px-3 py-1 bg-red-100 text-red-800 rounded-full text-xs hover:bg-red-200 transition-colors">
                                <i class="fas fa-drumstick-bite mr-1"></i> Daging
                            </button>
                            <button @click="searchTerm = 'Sayur'; filterData()" 
                                    class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs hover:bg-green-200 transition-colors">
                                <i class="fas fa-carrot mr-1"></i> Sayuran
                            </button>
                            <button @click="searchTerm = 'Telur'; filterData()" 
                                    class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs hover:bg-yellow-200 transition-colors">
                                <i class="fas fa-egg mr-1"></i> Telur
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Data Table -->
                <div class="bg-white rounded-lg shadow-md">
                    <div class="p-6 border-b border-gray-200">
                        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
                            <h3 class="text-lg font-bold text-gray-800">
                                <i class="fas fa-table text-green-600 mr-2"></i>
                                Data Konsumsi Kalori per Komoditas
                            </h3>

                            <!-- Search and Controls -->
                            <div class="flex flex-col sm:flex-row gap-3 w-full lg:w-auto">
                                <!-- Search Bar -->
                                <div class="relative">
                                    <input type="text" 
                                           x-model="searchTerm"
                                           placeholder="Cari komoditas atau kelompok..."
                                           class="w-full sm:w-64 pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-search text-gray-400"></i>
                                    </div>
                                    <button x-show="searchTerm" 
                                            @click="clearSearch()"
                                            class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                        <i class="fas fa-times text-gray-400 hover:text-gray-600"></i>
                                    </button>
                                </div>

                                <!-- Sort Controls -->
                                <div class="flex gap-2">
                                    <select x-model="sortBy" 
                                            class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                        <option value="kalori_harian">Kalori/Hari</option>
                                        <option value="nama">Nama Komoditas</option>
                                        <option value="kelompok">Kelompok</option>
                                        <option value="rata_konsumsi">Konsumsi (ton)</option>
                                    </select>
                                    
                                    <button @click="sortOrder = sortOrder === 'asc' ? 'desc' : 'asc'"
                                            class="px-3 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 focus:ring-2 focus:ring-green-500">
                                        <i class="fas" :class="sortOrder === 'asc' ? 'fa-sort-up' : 'fa-sort-down'"></i>
                                    </button>
                                </div>

                                <!-- Export Button -->
                                <button @click="exportData()" 
                                        :disabled="loading"
                                        class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 focus:ring-2 focus:ring-green-500 disabled:opacity-50 disabled:cursor-not-allowed">
                                    <i class="fas fa-download mr-2"></i>
                                    <span x-show="!loading">Export CSV</span>
                                    <span x-show="loading">
                                        <i class="fas fa-spinner fa-spin mr-1"></i>Exporting...
                                    </span>
                                </button>
                            </div>
                        </div>

                        <!-- Results Info -->
                        <div class="mt-4 text-sm text-gray-600 flex flex-wrap items-center justify-between">
                            <div>
                                Menampilkan <span x-text="paginatedData.length"></span> dari 
                                <span x-text="filteredData.length"></span> komoditas
                                <span x-show="searchTerm" class="ml-2">
                                    untuk "<span x-text="searchTerm" class="font-medium"></span>"
                                </span>
                            </div>
                            <button @click="resetFilters()" 
                                    x-show="searchTerm || sortBy !== 'kalori_harian' || sortOrder !== 'desc'"
                                    class="text-green-600 hover:text-green-800 text-sm">
                                <i class="fas fa-undo mr-1"></i>Reset Filter
                            </button>
                        </div>
                    </div>

                    <!-- Data Table Content -->
                    <div x-show="!loading">
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            No
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer"
                                            @click="sortBy = 'nama'; sortOrder = sortOrder === 'asc' ? 'desc' : 'asc'">
                                            Komoditas
                                            <i class="fas fa-sort ml-1" 
                                               :class="sortBy === 'nama' ? (sortOrder === 'asc' ? 'fa-sort-up' : 'fa-sort-down') : 'fa-sort'"></i>
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer"
                                            @click="sortBy = 'kelompok'; sortOrder = sortOrder === 'asc' ? 'desc' : 'asc'">
                                            Kelompok
                                            <i class="fas fa-sort ml-1"
                                               :class="sortBy === 'kelompok' ? (sortOrder === 'asc' ? 'fa-sort-up' : 'fa-sort-down') : 'fa-sort'"></i>
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer"
                                            @click="sortBy = 'kalori_harian'; sortOrder = sortOrder === 'asc' ? 'desc' : 'asc'">
                                            Kalori & Konsumsi
                                            <i class="fas fa-sort ml-1"
                                               :class="sortBy === 'kalori_harian' ? (sortOrder === 'asc' ? 'fa-sort-up' : 'fa-sort-down') : 'fa-sort'"></i>
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Visual
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <!-- Loading State -->
                                    <template x-show="loading">
                                        <tr>
                                            <td colspan="5" class="px-6 py-8 text-center">
                                                <div class="flex justify-center items-center">
                                                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-green-600"></div>
                                                    <span class="ml-3 text-gray-600">Memuat data...</span>
                                                </div>
                                            </td>
                                        </tr>
                                    </template>
                                    
                                    <!-- Data Rows -->
                                    <template x-for="(item, index) in paginatedData" :key="item.kode_komoditi || index">
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                <span x-text="((currentPage - 1) * itemsPerPage) + index + 1"></span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900" 
                                                     x-text="item.komoditi?.nama || item.kode_komoditi || 'N/A'"></div>
                                                <div class="text-sm text-gray-500">
                                                    Kode: <span x-text="item.kode_komoditi || 'N/A'"></span>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"
                                                x-text="item.kelompok?.nama || 'N/A'">
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-bold text-gray-900">
                                                    <span x-text="formatNumber(item.kalori_harian)"></span> kkal/hari
                                                </div>
                                                <div class="text-xs text-gray-500">
                                                    <span x-text="formatNumber(item.rata_konsumsi)"></span> ribu ton
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="w-20 bg-gray-200 rounded-full h-2 mr-3">
                                                        <div class="bg-green-600 h-2 rounded-full transition-all duration-300"
                                                             :style="`width: ${getProgressWidth(item.kalori_harian)}%`">
                                                        </div>
                                                    </div>
                                                    <span class="text-xs text-gray-500">
                                                        <span x-text="formatNumber(getPercentage(item.kalori_harian), 1)"></span>%
                                                    </span>
                                                </div>
                                            </td>
                                        </tr>
                                    </template>
                                    
                                    <!-- Empty State -->
                                    <template x-show="!loading && filteredData.length === 0">
                                        <tr>
                                            <td colspan="5" class="px-6 py-8 text-center">
                                                <i class="fas fa-search text-4xl text-gray-400 mb-4"></i>
                                                <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak Ditemukan</h3>
                                                <p class="text-gray-500">
                                                    Tidak ada data yang sesuai dengan filter 
                                                    <span x-show="searchTerm">
                                                        "<span x-text="searchTerm" class="font-medium"></span>"
                                                    </span>
                                                </p>
                                                <button @click="resetFilters()" 
                                                        class="mt-4 text-green-600 hover:text-green-800">
                                                    <i class="fas fa-undo mr-1"></i>Reset Filter
                                                </button>
                                            </td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div x-show="totalPages > 1" class="px-6 py-4 border-t border-gray-200">
                            <div class="flex items-center justify-between">
                                <div class="text-sm text-gray-700">
                                    Halaman <span x-text="currentPage"></span> dari <span x-text="totalPages"></span>
                                    (<span x-text="filteredData.length"></span> total data)
                                </div>
                                
                                <div class="flex items-center space-x-2">
                                    <!-- Previous Button -->
                                    <button @click="changePage(currentPage - 1)"
                                            :disabled="currentPage === 1"
                                            class="px-3 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed">
                                        <i class="fas fa-chevron-left"></i>
                                    </button>
                                    
                                    <!-- Page Numbers -->
                                    <template x-for="page in Array.from({length: Math.min(5, totalPages)}, (_, i) => {
                                        const start = Math.max(1, currentPage - 2);
                                        const end = Math.min(totalPages, start + 4);
                                        return start + i <= end ? start + i : null;
                                    }).filter(p => p !== null)" :key="page">
                                        <button @click="changePage(page)"
                                                :class="page === currentPage ? 'bg-green-600 text-white' : 'bg-white text-gray-700 hover:bg-gray-50'"
                                                class="px-3 py-2 border border-gray-300 rounded-lg">
                                            <span x-text="page"></span>
                                        </button>
                                    </template>
                                    
                                    <!-- Next Button -->
                                    <button @click="changePage(currentPage + 1)"
                                            :disabled="currentPage === totalPages"
                                            class="px-3 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed">
                                        <i class="fas fa-chevron-right"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Info & Akses Tambahan -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mt-8">

                    <!-- Info Keterbatasan Data Publik -->
                    <div class="bg-yellow-50 border-l-4 border-yellow-500 p-6 rounded-lg">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <i class="fas fa-exclamation-triangle text-yellow-500 text-xl mt-1"></i>
                            </div>
                            <div class="ml-3">
                                <h4 class="text-lg font-medium text-yellow-900">Keterbatasan Akses Publik</h4>
                                <div class="text-yellow-700 text-sm mt-2 space-y-2">
                                    <p>• Data yang ditampilkan hanya 20 komoditas teratas per tahun</p>
                                    <p>• Tidak termasuk data prediksi AI dan analisis mendalam</p>
                                    <p>• Data regional dan time series tidak tersedia</p>
                                    <p>• Ekspor data dalam format lengkap memerlukan akses khusus</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- CTA untuk Akses Lengkap -->
                    <div class="bg-blue-50 border-l-4 border-blue-500 p-6 rounded-lg">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <i class="fas fa-lock-open text-blue-500 text-xl mt-1"></i>
                            </div>
                            <div class="ml-3">
                                <h4 class="text-lg font-medium text-blue-900">Butuh Akses Lengkap?</h4>
                                <p class="text-blue-700 text-sm mt-2">
                                    Dapatkan akses ke data historis lengkap, prediksi AI, dan tools analisis
                                    profesional.
                                </p>
                                <div class="mt-4 space-x-3">
                                    <a href="{{ route('public.registrasi.pemerintah') }}"
                                        class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700">
                                        <i class="fas fa-university mr-2"></i>
                                        Akses Pemerintah
                                    </a>
                                    <a href="{{ route('public.registrasi.akademisi') }}"
                                        class="inline-flex items-center px-4 py-2 border border-blue-600 text-blue-600 text-sm font-medium rounded-lg hover:bg-blue-600 hover:text-white">
                                        <i class="fas fa-graduation-cap mr-2"></i>
                                        Akses Akademisi
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer Info -->
                <div class="mt-8 text-center text-gray-500 text-sm">
                    <p>Data diperbarui setiap bulan • Sumber: Pusat Data dan Sistem Informasi Pertanian</p>
                    <p class="mt-2">
                        <a href="{{ route('public.ketersediaan.metodologi') }}"
                            class="text-green-600 hover:text-green-800">
                            Pelajari metodologi pengumpulan data <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </p>
                </div>

            </div> <!-- End Content Container -->
        </div>
    </div>

    <script>
        function laporanPublik() {
            return {
                // Data properties
                loading: false,
                error: null,
                data: Object.values(@json($data->toArray())),
                filteredData: Object.values(@json($data->toArray())),
                
                // Filter properties
                searchTerm: '',
                selectedKelompok: '{{ $kelompok }}',
                selectedTahun: '{{ $tahun }}',
                sortBy: 'kalori_harian',
                sortOrder: 'desc',
                
                // Pagination
                currentPage: 1,
                itemsPerPage: 10,
                
                // Stats
                stats: {
                    totalKomoditas: {{ $data->count() }},
                    totalKalori: {{ $data->sum('kalori_harian') }},
                    avgKalori: {{ $data->count() > 0 ? $data->sum('kalori_harian') / $data->count() : 0 }},
                    maxKalori: {{ $data->count() > 0 ? $data->pluck('kalori_harian')->max() : 0 }}
                },

                init() {
                    console.log('📊 Laporan Publik initialized');
                    console.log('Data type:', typeof this.data, 'Is array:', Array.isArray(this.data));
                    console.log('Data sample:', this.data.slice(0, 2));
                    
                    // Ensure data is array
                    if (!Array.isArray(this.data)) {
                        this.data = Object.values(this.data);
                    }
                    
                    this.filteredData = [...this.data];
                    this.updateStats();
                    
                    // Watch for search term changes
                    this.$watch('searchTerm', () => {
                        this.filterData();
                    });
                    
                    // Watch for sort changes
                    this.$watch('sortBy', () => {
                        this.sortData();
                    });
                    
                    this.$watch('sortOrder', () => {
                        this.sortData();
                    });
                },

                filterData() {
                    // Ensure data is array
                    const dataArray = Array.isArray(this.data) ? this.data : Object.values(this.data);
                    let filtered = [...dataArray];
                    
                    // Apply search filter
                    if (this.searchTerm.trim()) {
                        const search = this.searchTerm.toLowerCase();
                        filtered = filtered.filter(item => {
                            const komoditas = (item.komoditi?.nama || item.kode_komoditi || '').toLowerCase();
                            const kelompok = (item.kelompok?.nama || '').toLowerCase();
                            return komoditas.includes(search) || kelompok.includes(search);
                        });
                    }
                    
                    this.filteredData = filtered;
                    this.sortData();
                    this.updateStats();
                    this.currentPage = 1; // Reset to first page
                },

                sortData() {
                    // Ensure filteredData is array
                    if (!Array.isArray(this.filteredData)) {
                        this.filteredData = Object.values(this.filteredData || {});
                    }
                    
                    this.filteredData.sort((a, b) => {
                        let aVal, bVal;
                        
                        switch(this.sortBy) {
                            case 'nama':
                                aVal = (a.komoditi?.nama || a.kode_komoditi || '').toLowerCase();
                                bVal = (b.komoditi?.nama || b.kode_komoditi || '').toLowerCase();
                                break;
                            case 'kelompok':
                                aVal = (a.kelompok?.nama || '').toLowerCase();
                                bVal = (b.kelompok?.nama || '').toLowerCase();
                                break;
                            case 'kalori_harian':
                                aVal = parseFloat(a.kalori_harian) || 0;
                                bVal = parseFloat(b.kalori_harian) || 0;
                                break;
                            case 'rata_konsumsi':
                                aVal = parseFloat(a.rata_konsumsi) || 0;
                                bVal = parseFloat(b.rata_konsumsi) || 0;
                                break;
                            default:
                                return 0;
                        }
                        
                        if (this.sortOrder === 'asc') {
                            return aVal > bVal ? 1 : -1;
                        } else {
                            return aVal < bVal ? 1 : -1;
                        }
                    });
                },

                updateStats() {
                    // Ensure filteredData is array
                    const dataArray = Array.isArray(this.filteredData) ? this.filteredData : Object.values(this.filteredData || {});
                    
                    this.stats = {
                        totalKomoditas: dataArray.length,
                        totalKalori: dataArray.reduce((sum, item) => sum + (parseFloat(item.kalori_harian) || 0), 0),
                        avgKalori: dataArray.length > 0 ? 
                            dataArray.reduce((sum, item) => sum + (parseFloat(item.kalori_harian) || 0), 0) / dataArray.length : 0,
                        maxKalori: dataArray.length > 0 ? 
                            Math.max(...dataArray.map(item => parseFloat(item.kalori_harian) || 0)) : 0
                    };
                },

                get paginatedData() {
                    // Ensure filteredData is array
                    const dataArray = Array.isArray(this.filteredData) ? this.filteredData : Object.values(this.filteredData || {});
                    
                    const start = (this.currentPage - 1) * this.itemsPerPage;
                    const end = start + this.itemsPerPage;
                    return dataArray.slice(start, end);
                },

                get totalPages() {
                    const dataArray = Array.isArray(this.filteredData) ? this.filteredData : Object.values(this.filteredData || {});
                    return Math.ceil(dataArray.length / this.itemsPerPage);
                },

                changePage(page) {
                    if (page >= 1 && page <= this.totalPages) {
                        this.currentPage = page;
                    }
                },

                getProgressWidth(kalori) {
                    if (this.stats.maxKalori === 0) return 0;
                    return Math.min(100, (parseFloat(kalori) / this.stats.maxKalori) * 100);
                },

                getPercentage(kalori) {
                    if (this.stats.totalKalori === 0) return 0;
                    return ((parseFloat(kalori) / this.stats.totalKalori) * 100);
                },

                formatNumber(num, decimals = 2) {
                    if (num === null || num === undefined) return '0';
                    return parseFloat(num).toLocaleString('id-ID', {
                        minimumFractionDigits: decimals,
                        maximumFractionDigits: decimals
                    });
                },

                exportData() {
                    this.loading = true;
                    
                    // Simulate export process
                    setTimeout(() => {
                        try {
                            // Create CSV content
                            const headers = ['No', 'Kode Komoditas', 'Nama Komoditas', 'Kelompok', 'Kalori/Hari', 'Rata-rata Konsumsi (ribu ton)'];
                            const csvContent = [
                                headers.join(','),
                                ...this.filteredData.map((item, index) => [
                                    index + 1,
                                    item.kode_komoditi || '',
                                    `"${(item.komoditi?.nama || item.kode_komoditi || '').replace(/"/g, '""')}"`,
                                    `"${(item.kelompok?.nama || 'N/A').replace(/"/g, '""')}"`,
                                    item.kalori_harian || 0,
                                    item.rata_konsumsi || 0
                                ].join(','))
                            ].join('\n');
                            
                            // Create and download file
                            const blob = new Blob([csvContent], { type: 'text/csv' });
                            const url = window.URL.createObjectURL(blob);
                            const a = document.createElement('a');
                            a.href = url;
                            a.download = `laporan-nbm-publik-${this.selectedTahun}-${new Date().toISOString().slice(0, 10)}.csv`;
                            document.body.appendChild(a);
                            a.click();
                            document.body.removeChild(a);
                            window.URL.revokeObjectURL(url);
                            
                            this.loading = false;
                            
                            // Show success message
                            this.showNotification('✅ Data berhasil diekspor!', 'success');
                            
                        } catch (error) {
                            console.error('Export error:', error);
                            this.error = 'Gagal mengekspor data';
                            this.loading = false;
                            this.showNotification('❌ Gagal mengekspor data', 'error');
                        }
                    }, 1000);
                },

                showNotification(message, type = 'info') {
                    // Create notification element
                    const notification = document.createElement('div');
                    notification.className = `fixed top-4 right-4 p-4 rounded-lg shadow-lg z-50 transform transition-all duration-300 ${
                        type === 'success' ? 'bg-green-500 text-white' :
                        type === 'error' ? 'bg-red-500 text-white' :
                        'bg-blue-500 text-white'
                    }`;
                    notification.textContent = message;
                    
                    document.body.appendChild(notification);
                    
                    // Show notification
                    setTimeout(() => {
                        notification.style.transform = 'translateX(0)';
                    }, 100);
                    
                    // Hide notification after 3 seconds
                    setTimeout(() => {
                        notification.style.transform = 'translateX(100%)';
                        setTimeout(() => {
                            document.body.removeChild(notification);
                        }, 300);
                    }, 3000);
                },

                clearSearch() {
                    this.searchTerm = '';
                },

                resetFilters() {
                    this.searchTerm = '';
                    this.sortBy = 'kalori_harian';
                    this.sortOrder = 'desc';
                    this.currentPage = 1;
                    this.filterData();
                },

                // Highlight search terms in results
                highlightText(text, search) {
                    if (!search || !text) return text;
                    const regex = new RegExp(`(${search})`, 'gi');
                    return text.replace(regex, '<mark class="bg-yellow-200 px-1 rounded">$1</mark>');
                },

                // Get insights about current data
                getDataInsights() {
                    // Ensure filteredData is array
                    const dataArray = Array.isArray(this.filteredData) ? this.filteredData : Object.values(this.filteredData || {});
                    
                    if (dataArray.length === 0) return null;
                    
                    const sorted = [...dataArray].sort((a, b) => 
                        parseFloat(b.kalori_harian) - parseFloat(a.kalori_harian)
                    );
                    
                    const topItem = sorted[0];
                    const avgKalori = this.stats.avgKalori;
                    
                    return {
                        topKomoditas: topItem?.komoditi?.nama || topItem?.kode_komoditi,
                        topKalori: topItem?.kalori_harian,
                        aboveAverage: sorted.filter(item => 
                            parseFloat(item.kalori_harian) > avgKalori
                        ).length,
                        kelompokCount: new Set(dataArray.map(item => 
                            item.kelompok?.nama
                        )).size
                    };
                },

                // Quick filter by kelompok
                filterByKelompok(kelompokName) {
                    this.searchTerm = kelompokName;
                    this.filterData();
                }
            }
        }

        // Auto-refresh data setiap 5 menit (untuk data yang mungkin berubah)
        setInterval(() => {
            console.log('🔄 Auto-refresh check (data NBM biasanya statis)');
        }, 5 * 60 * 1000);
    </script>
</x-layouts.landing>
