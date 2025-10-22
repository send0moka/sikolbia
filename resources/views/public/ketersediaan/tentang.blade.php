<x-layouts.landing title="Tentang NBM - SIKOLBIA">
    <!-- FontAwesome Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
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
                            <span class="ml-1 text-blue-600 font-medium">Tentang NBM</span>
                        </div>
                    </li>
                </ol>
            </nav>

            <!-- Page Header -->
            <div class="mb-8">
                <h1 class="text-3xl md:text-4xl font-bold text-neutral-900 mb-4">
                    Tentang Neraca Bahan Makanan (NBM)
                </h1>
                <p class="text-xl text-neutral-600">
                    Memahami sistem informasi konsumsi pangan nasional Indonesia
                </p>
            </div>

            <!-- Content Container with proper spacing -->
            <div class="max-w-7xl mx-auto">

                <!-- Definisi NBM -->
                <div class="bg-white rounded-lg shadow-md p-8 mb-8">
            <div class="flex items-start mb-6">
                <div class="flex-shrink-0 p-3 bg-blue-100 rounded-lg mr-6">
                    <i class="fas fa-book text-blue-600 text-2xl"></i>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-gray-800 mb-4">Apa itu Neraca Bahan Makanan?</h2>
                    <div class="prose prose-lg text-gray-700">
                        <p class="mb-4">
                            <strong>Neraca Bahan Makanan (NBM)</strong> adalah sistem akuntansi yang menggambarkan 
                            situasi ketersediaan dan kebutuhan pangan di suatu negara atau wilayah pada periode waktu tertentu. 
                            NBM memberikan informasi komprehensif tentang aliran komoditas pangan mulai dari produksi, 
                            distribusi, hingga konsumsi oleh masyarakat.
                        </p>
                        <p class="mb-4">
                            Sistem ini membantu pemerintah dalam merencanakan kebijakan pangan, mengidentifikasi 
                            surplus atau defisit pangan, serta memantau ketahanan pangan nasional.
                        </p>
                    </div>
                </div>
            </div>
                </div>

                <!-- Komponen NBM -->
                <div class="bg-white rounded-lg shadow-md p-8 mb-8">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">
                <i class="fas fa-puzzle-piece text-green-600 mr-2"></i>
                Komponen NBM
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                <!-- Supply Side -->
                <div class="border-l-4 border-green-500 pl-6">
                    <h3 class="text-xl font-bold text-gray-800 mb-3">
                        <i class="fas fa-arrow-up text-green-600 mr-2"></i>
                        Sisi Penawaran (Supply)
                    </h3>
                    <ul class="space-y-2 text-gray-700">
                        <li class="flex items-start">
                            <i class="fas fa-seedling text-green-600 mr-2 mt-1 text-sm"></i>
                            <span><strong>Produksi:</strong> Total produksi komoditas pangan dalam negeri</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-ship text-green-600 mr-2 mt-1 text-sm"></i>
                            <span><strong>Impor:</strong> Komoditas pangan yang didatangkan dari luar negeri</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-warehouse text-green-600 mr-2 mt-1 text-sm"></i>
                            <span><strong>Stok Awal:</strong> Persediaan komoditas pada awal periode</span>
                        </li>
                    </ul>
                </div>

                <!-- Demand Side -->
                <div class="border-l-4 border-red-500 pl-6">
                    <h3 class="text-xl font-bold text-gray-800 mb-3">
                        <i class="fas fa-arrow-down text-red-600 mr-2"></i>
                        Sisi Permintaan (Demand)
                    </h3>
                    <ul class="space-y-2 text-gray-700">
                        <li class="flex items-start">
                            <i class="fas fa-utensils text-red-600 mr-2 mt-1 text-sm"></i>
                            <span><strong>Konsumsi:</strong> Total konsumsi pangan oleh masyarakat</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-plane-departure text-red-600 mr-2 mt-1 text-sm"></i>
                            <span><strong>Ekspor:</strong> Komoditas pangan yang dikirim ke luar negeri</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-industry text-red-600 mr-2 mt-1 text-sm"></i>
                            <span><strong>Penggunaan Lain:</strong> Untuk industri, pakan ternak, bibit, dll</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-trash text-red-600 mr-2 mt-1 text-sm"></i>
                            <span><strong>Susut:</strong> Kehilangan dalam proses penyimpanan dan distribusi</span>
                        </li>
                    </ul>
                </div>
            </div>
                </div>

                <!-- Formula NBM -->
                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg p-8 mb-8 border border-blue-200">
            <h2 class="text-2xl font-bold text-gray-800 mb-4">
                <i class="fas fa-calculator text-blue-600 mr-2"></i>
                Formula Perhitungan NBM
            </h2>
            <div class="bg-white rounded-lg p-6 font-mono text-center">
                <div class="text-lg text-gray-800 mb-2">
                    <strong>Ketersediaan = Produksi + Impor + Stok Awal - Ekspor - Stok Akhir</strong>
                </div>
                <div class="text-sm text-gray-600 mb-4">
                    Ketersediaan untuk Konsumsi = Ketersediaan - Penggunaan Lain - Susut
                </div>
                <div class="text-lg text-blue-800 font-bold">
                    <strong>Konsumsi per Kapita = Ketersediaan untuk Konsumsi ÷ Jumlah Penduduk</strong>
                </div>
            </div>
                </div>

                <!-- Manfaat NBM -->
                <div class="bg-white rounded-lg shadow-md p-8 mb-8">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">
                <i class="fas fa-bullseye text-purple-600 mr-2"></i>
                Manfaat dan Kegunaan NBM
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                
                <div class="text-center">
                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-chart-line text-green-600 text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-800 mb-2">Perencanaan Kebijakan</h3>
                    <p class="text-gray-600 text-sm">
                        Memberikan data untuk merumuskan kebijakan ketahanan pangan dan strategi pembangunan pertanian
                    </p>
                </div>

                <div class="text-center">
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-shield-alt text-blue-600 text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-800 mb-2">Monitoring Ketahanan</h3>
                    <p class="text-gray-600 text-sm">
                        Memantau status ketahanan pangan nasional dan mengidentifikasi risiko kerawanan pangan
                    </p>
                </div>

                <div class="text-center">
                    <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-balance-scale text-purple-600 text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-800 mb-2">Analisis Keseimbangan</h3>
                    <p class="text-gray-600 text-sm">
                        Menganalisis keseimbangan antara ketersediaan dan kebutuhan pangan di berbagai wilayah
                    </p>
                </div>
            </div>
                </div>

                <!-- Data Coverage SIKOLBIA -->
                <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-lg p-8 mb-8 border border-green-200">
            <h2 class="text-2xl font-bold text-gray-800 mb-4">
                <i class="fas fa-database text-green-600 mr-2"></i>
                Cakupan Data SIKOLBIA
            </h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 text-center">
                <div>
                    <div class="text-3xl font-bold text-green-800">11</div>
                    <div class="text-sm text-gray-600">Kelompok Komoditas</div>
                </div>
                <div>
                    <div class="text-3xl font-bold text-green-800">120</div>
                    <div class="text-sm text-gray-600">Komoditas Pangan</div>
                </div>
                <div>
                    <div class="text-3xl font-bold text-green-800">31</div>
                    <div class="text-sm text-gray-600">Tahun Data (1993-2024)</div>
                </div>
                <div>
                    <div class="text-3xl font-bold text-green-800">34</div>
                    <div class="text-sm text-gray-600">Provinsi</div>
                </div>
            </div>
                </div>

                <!-- Kelompok Komoditas -->
                <div class="bg-white rounded-lg shadow-md p-8 mb-8">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">
                <i class="fas fa-layer-group text-orange-600 mr-2"></i>
                11 Kelompok Komoditas dalam NBM
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <div class="flex items-center p-4 border border-gray-200 rounded-lg">
                    <div class="w-10 h-10 bg-yellow-100 rounded-full flex items-center justify-center mr-3">
                        <i class="fas fa-seedling text-yellow-600"></i>
                    </div>
                    <span class="font-medium text-gray-800">Padi-padian</span>
                </div>
                <div class="flex items-center p-4 border border-gray-200 rounded-lg">
                    <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center mr-3">
                        <i class="fas fa-carrot text-green-600"></i>
                    </div>
                    <span class="font-medium text-gray-800">Umbi-umbian</span>
                </div>
                <div class="flex items-center p-4 border border-gray-200 rounded-lg">
                    <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center mr-3">
                        <i class="fas fa-fish text-purple-600"></i>
                    </div>
                    <span class="font-medium text-gray-800">Ikan</span>
                </div>
                <div class="flex items-center p-4 border border-gray-200 rounded-lg">
                    <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center mr-3">
                        <i class="fas fa-drumstick-bite text-red-600"></i>
                    </div>
                    <span class="font-medium text-gray-800">Daging</span>
                </div>
                <div class="flex items-center p-4 border border-gray-200 rounded-lg">
                    <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                        <i class="fas fa-egg text-blue-600"></i>
                    </div>
                    <span class="font-medium text-gray-800">Telur & Susu</span>
                </div>
                <div class="flex items-center p-4 border border-gray-200 rounded-lg">
                    <div class="w-10 h-10 bg-indigo-100 rounded-full flex items-center justify-center mr-3">
                        <i class="fas fa-seedling text-indigo-600"></i>
                    </div>
                    <span class="font-medium text-gray-800">Kacang-kacangan</span>
                </div>
                <div class="flex items-center p-4 border border-gray-200 rounded-lg">
                    <div class="w-10 h-10 bg-pink-100 rounded-full flex items-center justify-center mr-3">
                        <i class="fas fa-apple-alt text-pink-600"></i>
                    </div>
                    <span class="font-medium text-gray-800">Sayuran & Buah</span>
                </div>
                <div class="flex items-center p-4 border border-gray-200 rounded-lg">
                    <div class="w-10 h-10 bg-yellow-100 rounded-full flex items-center justify-center mr-3">
                        <i class="fas fa-tint text-yellow-600"></i>
                    </div>
                    <span class="font-medium text-gray-800">Minyak & Lemak</span>
                </div>
                <div class="flex items-center p-4 border border-gray-200 rounded-lg">
                    <div class="w-10 h-10 bg-orange-100 rounded-full flex items-center justify-center mr-3">
                        <i class="fas fa-cube text-orange-600"></i>
                    </div>
                    <span class="font-medium text-gray-800">Gula</span>
                </div>
                <div class="flex items-center p-4 border border-gray-200 rounded-lg">
                    <div class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center mr-3">
                        <i class="fas fa-mortar-pestle text-gray-600"></i>
                    </div>
                    <span class="font-medium text-gray-800">Lain-lain</span>
                </div>
                <div class="flex items-center p-4 border border-gray-200 rounded-lg">
                    <div class="w-10 h-10 bg-teal-100 rounded-full flex items-center justify-center mr-3">
                        <i class="fas fa-cocktail text-teal-600"></i>
                    </div>
                    <span class="font-medium text-gray-800">Stimulan</span>
                </div>
            </div>
                </div>

                <!-- AI & Technology -->
                <div class="bg-gradient-to-r from-purple-50 to-pink-50 rounded-lg p-8 mb-8 border border-purple-200">
            <div class="flex items-start">
                <div class="flex-shrink-0 p-3 bg-purple-100 rounded-lg mr-6">
                    <i class="fas fa-robot text-purple-600 text-2xl"></i>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-gray-800 mb-4">Teknologi AI dalam SIKOLBIA</h2>
                    <div class="prose text-gray-700">
                        <p class="mb-4">
                            SIKOLBIA menggunakan teknologi <strong>Artificial Intelligence (AI)</strong> dengan 
                            <strong>SHAP (SHapley Additive exPlanations)</strong> untuk memberikan prediksi 
                            konsumsi pangan yang akurat dan dapat dijelaskan.
                        </p>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-6">
                            <div class="flex items-start">
                                <i class="fas fa-brain text-purple-600 mr-3 mt-1"></i>
                                <div>
                                    <strong>Machine Learning</strong><br>
                                    <span class="text-sm text-gray-600">Prediksi konsumsi berdasarkan pola historis</span>
                                </div>
                            </div>
                            <div class="flex items-start">
                                <i class="fas fa-search text-purple-600 mr-3 mt-1"></i>
                                <div>
                                    <strong>SHAP Analysis</strong><br>
                                    <span class="text-sm text-gray-600">Interpretabilitas faktor yang mempengaruhi prediksi</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
                </div>

                <!-- Navigation Links -->
                <div class="text-center">
            <div class="space-x-4">
                <a href="{{ route('public.ketersediaan.metodologi') }}" 
                   class="inline-flex items-center px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    <i class="fas fa-microscope mr-2"></i>
                    Pelajari Metodologi
                </a>
                <a href="{{ route('public.ketersediaan.dashboard') }}" 
                   class="inline-flex items-center px-6 py-3 border border-green-600 text-green-600 rounded-lg hover:bg-green-600 hover:text-white">
                    <i class="fas fa-chart-bar mr-2"></i>
                    Lihat Dashboard
                </a>
            </div>
                </div>
                
            </div> <!-- End Content Container -->
        </div>
    </div>
</x-layouts.landing>