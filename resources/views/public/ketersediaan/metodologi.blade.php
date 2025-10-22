<x-layouts.landing title="Metodologi NBM - SIKOLBIA">
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
                            <span class="ml-1 text-blue-600 font-medium">Metodologi NBM</span>
                        </div>
                    </li>
                </ol>
            </nav>

            <!-- Page Header -->
            <div class="mb-8">
                <h1 class="text-3xl md:text-4xl font-bold text-neutral-900 mb-4">
                    Metodologi Neraca Bahan Makanan (NBM)
                </h1>
                <p class="text-xl text-neutral-600">
                    Pemahaman mendalam tentang metodologi dan standar perhitungan NBM Indonesia
                </p>
            </div>

            <!-- Content Container with proper spacing -->
            <div class="max-w-7xl mx-auto">
                <!-- Overview -->
                <div class="bg-white rounded-lg shadow-md p-8 mb-8">
                    <h2 class="text-2xl font-bold text-gray-800 mb-4">
                        <i class="fas fa-book-open text-blue-600 mr-2"></i>
                        Pendahuluan
                    </h2>
                    <p class="text-gray-700 leading-relaxed mb-4">
                        Metodologi NBM yang digunakan dalam SIKOLBIA mengacu pada standar internasional FAO (Food and
                        Agriculture Organization)
                        yang telah diadaptasi dengan kondisi Indonesia. Metodologi ini memastikan konsistensi, akurasi,
                        dan dapat diperbandingkan
                        data konsumsi pangan di tingkat nasional dan regional.
                    </p>
                    <p class="text-gray-700 leading-relaxed">
                        Data NBM dikumpulkan dari berbagai sumber resmi pemerintah dan diolah menggunakan teknologi AI
                        untuk menghasilkan
                        informasi yang komprehensif tentang situasi pangan Indonesia.
                    </p>
                </div>

                <!-- Sumber Data -->
                <div class="bg-white rounded-lg shadow-md p-8 mb-8">
                    <h2 class="text-2xl font-bold text-gray-800 mb-6">
                        <i class="fas fa-database text-green-600 mr-2"></i>
                        Sumber Data
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        <!-- Primary Sources -->
                        <div class="border-l-4 border-green-500 pl-6">
                            <h3 class="text-xl font-bold text-gray-800 mb-3">Sumber Data Primer</h3>
                            <ul class="space-y-3">
                                <li class="flex items-start">
                                    <i class="fas fa-building text-green-600 mr-3 mt-1"></i>
                                    <div>
                                        <strong>BPS (Badan Pusat Statistik)</strong><br>
                                        <span class="text-sm text-gray-600">Data produksi, impor, ekspor, dan
                                            konsumsi</span>
                                    </div>
                                </li>
                                <li class="flex items-start">
                                    <i class="fas fa-seedling text-green-600 mr-3 mt-1"></i>
                                    <div>
                                        <strong>Kementerian Pertanian</strong><br>
                                        <span class="text-sm text-gray-600">Data produksi pertanian dan
                                            peternakan</span>
                                    </div>
                                </li>
                                <li class="flex items-start">
                                    <i class="fas fa-ship text-green-600 mr-3 mt-1"></i>
                                    <div>
                                        <strong>Kementerian Perdagangan</strong><br>
                                        <span class="text-sm text-gray-600">Data ekspor dan impor komoditas
                                            pangan</span>
                                    </div>
                                </li>
                                <li class="flex items-start">
                                    <i class="fas fa-fish text-green-600 mr-3 mt-1"></i>
                                    <div>
                                        <strong>Kementerian Kelautan dan Perikanan</strong><br>
                                        <span class="text-sm text-gray-600">Data produksi perikanan dan kelautan</span>
                                    </div>
                                </li>
                            </ul>
                        </div>

                        <!-- Secondary Sources -->
                        <div class="border-l-4 border-blue-500 pl-6">
                            <h3 class="text-xl font-bold text-gray-800 mb-3">Sumber Data Sekunder</h3>
                            <ul class="space-y-3">
                                <li class="flex items-start">
                                    <i class="fas fa-university text-blue-600 mr-3 mt-1"></i>
                                    <div>
                                        <strong>Penelitian Akademik</strong><br>
                                        <span class="text-sm text-gray-600">Studi dan survei dari universitas</span>
                                    </div>
                                </li>
                                <li class="flex items-start">
                                    <i class="fas fa-industry text-blue-600 mr-3 mt-1"></i>
                                    <div>
                                        <strong>Asosiasi Industri</strong><br>
                                        <span class="text-sm text-gray-600">Data produksi industri makanan</span>
                                    </div>
                                </li>
                                <li class="flex items-start">
                                    <i class="fas fa-globe text-blue-600 mr-3 mt-1"></i>
                                    <div>
                                        <strong>Organisasi Internasional</strong><br>
                                        <span class="text-sm text-gray-600">FAO, WHO, dan lembaga internasional
                                            lainnya</span>
                                    </div>
                                </li>
                                <li class="flex items-start">
                                    <i class="fas fa-chart-line text-blue-600 mr-3 mt-1"></i>
                                    <div>
                                        <strong>Survei Pasar</strong><br>
                                        <span class="text-sm text-gray-600">Data harga dan ketersediaan pasar</span>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Metodologi Pengumpulan -->
                <div class="bg-white rounded-lg shadow-md p-8 mb-8">
                    <h2 class="text-2xl font-bold text-gray-800 mb-6">
                        <i class="fas fa-search text-purple-600 mr-2"></i>
                        Metodologi Pengumpulan Data
                    </h2>

                    <!-- Timeline -->
                    <div class="relative">
                        <div class="absolute left-4 top-0 bottom-0 w-0.5 bg-gray-300"></div>

                        <div class="relative flex items-start mb-8">
                            <div
                                class="flex-shrink-0 w-8 h-8 bg-green-600 rounded-full flex items-center justify-center text-white text-sm font-bold">
                                1</div>
                            <div class="ml-6">
                                <h3 class="text-lg font-bold text-gray-800">Identifikasi Kebutuhan Data</h3>
                                <p class="text-gray-600 text-sm mt-1">
                                    Menentukan jenis data yang diperlukan berdasarkan 11 kelompok komoditas dan 120
                                    jenis komoditas pangan
                                </p>
                                <ul class="mt-3 text-sm text-gray-600 space-y-1">
                                    <li>• Produksi per komoditas per wilayah</li>
                                    <li>• Data impor dan ekspor bulanan</li>
                                    <li>• Stok dan persediaan</li>
                                    <li>• Konsumsi rumah tangga</li>
                                </ul>
                            </div>
                        </div>

                        <div class="relative flex items-start mb-8">
                            <div
                                class="flex-shrink-0 w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center text-white text-sm font-bold">
                                2</div>
                            <div class="ml-6">
                                <h3 class="text-lg font-bold text-gray-800">Pengumpulan Data</h3>
                                <p class="text-gray-600 text-sm mt-1">
                                    Mengumpulkan data dari berbagai sumber resmi melalui sistem digital dan koordinasi
                                    antar instansi
                                </p>
                                <div class="grid grid-cols-2 gap-4 mt-3 text-sm">
                                    <div>
                                        <strong class="text-gray-700">Frekuensi:</strong>
                                        <ul class="text-gray-600">
                                            <li>• Bulanan: Impor/Ekspor</li>
                                            <li>• Triwulanan: Produksi</li>
                                            <li>• Tahunan: Konsumsi RT</li>
                                        </ul>
                                    </div>
                                    <div>
                                        <strong class="text-gray-700">Format:</strong>
                                        <ul class="text-gray-600">
                                            <li>• API Integration</li>
                                            <li>• Excel Templates</li>
                                            <li>• Database Export</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="relative flex items-start mb-8">
                            <div
                                class="flex-shrink-0 w-8 h-8 bg-yellow-600 rounded-full flex items-center justify-center text-white text-sm font-bold">
                                3</div>
                            <div class="ml-6">
                                <h3 class="text-lg font-bold text-gray-800">Validasi dan Verifikasi</h3>
                                <p class="text-gray-600 text-sm mt-1">
                                    Melakukan pemeriksaan konsistensi data dan validasi silang antar sumber
                                </p>
                                <div class="mt-3 p-4 bg-gray-50 rounded-lg">
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                                        <div>
                                            <strong class="text-gray-700">Range Check</strong><br>
                                            <span class="text-gray-600">Memastikan nilai dalam rentang logis</span>
                                        </div>
                                        <div>
                                            <strong class="text-gray-700">Cross Validation</strong><br>
                                            <span class="text-gray-600">Bandingkan dengan sumber lain</span>
                                        </div>
                                        <div>
                                            <strong class="text-gray-700">Trend Analysis</strong><br>
                                            <span class="text-gray-600">Analisis pola dan tren historis</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="relative flex items-start">
                            <div
                                class="flex-shrink-0 w-8 h-8 bg-purple-600 rounded-full flex items-center justify-center text-white text-sm font-bold">
                                4</div>
                            <div class="ml-6">
                                <h3 class="text-lg font-bold text-gray-800">Standardisasi dan Integrasi</h3>
                                <p class="text-gray-600 text-sm mt-1">
                                    Menstandarkan format data dan mengintegrasikan ke dalam database terpusat
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Metodologi Pengolahan -->
                <div class="bg-white rounded-lg shadow-md p-8 mb-8">
                    <h2 class="text-2xl font-bold text-gray-800 mb-6">
                        <i class="fas fa-cogs text-orange-600 mr-2"></i>
                        Metodologi Pengolahan Data
                    </h2>

                    <!-- Formula Calculations -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

                        <!-- NBM Calculation -->
                        <div class="border border-gray-200 rounded-lg p-6">
                            <h3 class="text-lg font-bold text-gray-800 mb-4">Perhitungan NBM Dasar</h3>
                            <div class="space-y-4 text-sm">
                                <div class="p-3 bg-blue-50 rounded border border-blue-200">
                                    <strong>Ketersediaan Bruto =</strong><br>
                                    Produksi + Impor + Stok Awal - Ekspor - Stok Akhir
                                </div>
                                <div class="p-3 bg-green-50 rounded border border-green-200">
                                    <strong>Ketersediaan Bersih =</strong><br>
                                    Ketersediaan Bruto - Penggunaan Non-Pangan - Susut
                                </div>
                                <div class="p-3 bg-purple-50 rounded border border-purple-200">
                                    <strong>Konsumsi per Kapita =</strong><br>
                                    Ketersediaan Bersih ÷ Jumlah Penduduk ÷ 365 hari
                                </div>
                                <div class="p-3 bg-orange-50 rounded border border-orange-200">
                                    <strong>Kalori per Kapita/Hari =</strong><br>
                                    Konsumsi per Kapita × Faktor Konversi Kalori
                                </div>
                            </div>
                        </div>

                        <!-- Conversion Factors -->
                        <div class="border border-gray-200 rounded-lg p-6">
                            <h3 class="text-lg font-bold text-gray-800 mb-4">Faktor Konversi</h3>
                            <div class="space-y-3 text-sm">
                                <div>
                                    <strong class="text-gray-700">Kalori per 100g:</strong>
                                    <p class="text-gray-600">Berdasarkan Tabel Komposisi Pangan Indonesia (TKPI)</p>
                                </div>
                                <div>
                                    <strong class="text-gray-700">Faktor Susut:</strong>
                                    <ul class="text-gray-600 mt-1">
                                        <li>• Padi-padian: 5-10%</li>
                                        <li>• Sayuran segar: 15-25%</li>
                                        <li>• Buah-buahan: 10-20%</li>
                                        <li>• Ikan segar: 8-15%</li>
                                    </ul>
                                </div>
                                <div>
                                    <strong class="text-gray-700">Konversi Satuan:</strong>
                                    <p class="text-gray-600">Standarisasi ke kilogram untuk konsistensi perhitungan</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- AI Methodology -->
                <div class="bg-gradient-to-r from-purple-50 to-indigo-50 rounded-lg p-8 mb-8 border border-purple-200">
                    <h2 class="text-2xl font-bold text-gray-800 mb-6">
                        <i class="fas fa-robot text-purple-600 mr-2"></i>
                        Metodologi AI dan Machine Learning
                    </h2>

                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                        <!-- Data Preparation -->
                        <div class="bg-white p-6 rounded-lg border border-purple-100">
                            <div class="flex items-center mb-3">
                                <i class="fas fa-database text-purple-600 mr-2"></i>
                                <h3 class="font-bold text-gray-800">Persiapan Data</h3>
                            </div>
                            <ul class="text-sm text-gray-600 space-y-2">
                                <li>• Pembersihan data outlier</li>
                                <li>• Normalisasi skala data</li>
                                <li>• Feature engineering</li>
                                <li>• Time series decomposition</li>
                                <li>• Missing value imputation</li>
                            </ul>
                        </div>

                        <!-- Model Training -->
                        <div class="bg-white p-6 rounded-lg border border-purple-100">
                            <div class="flex items-center mb-3">
                                <i class="fas fa-brain text-purple-600 mr-2"></i>
                                <h3 class="font-bold text-gray-800">Pelatihan Model</h3>
                            </div>
                            <ul class="text-sm text-gray-600 space-y-2">
                                <li>• LSTM untuk time series</li>
                                <li>• Random Forest ensemble</li>
                                <li>• Cross-validation (5-fold)</li>
                                <li>• Hyperparameter tuning</li>
                                <li>• Model validation</li>
                            </ul>
                        </div>

                        <!-- SHAP Analysis -->
                        <div class="bg-white p-6 rounded-lg border border-purple-100">
                            <div class="flex items-center mb-3">
                                <i class="fas fa-search text-purple-600 mr-2"></i>
                                <h3 class="font-bold text-gray-800">SHAP Analysis</h3>
                            </div>
                            <ul class="text-sm text-gray-600 space-y-2">
                                <li>• Feature importance</li>
                                <li>• Local explanations</li>
                                <li>• Global interpretability</li>
                                <li>• Shapley value calculation</li>
                                <li>• Visualization dashboard</li>
                            </ul>
                        </div>
                    </div>

                    <!-- Model Performance -->
                    <div class="mt-6 p-6 bg-white rounded-lg border border-purple-100">
                        <h3 class="font-bold text-gray-800 mb-3">
                            <i class="fas fa-chart-line text-purple-600 mr-2"></i>
                            Performa Model
                        </h3>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-center">
                            <div>
                                <div class="text-2xl font-bold text-purple-800">87.3%</div>
                                <div class="text-sm text-gray-600">Akurasi Prediksi</div>
                            </div>
                            <div>
                                <div class="text-2xl font-bold text-purple-800">0.89</div>
                                <div class="text-sm text-gray-600">R² Score</div>
                            </div>
                            <div>
                                <div class="text-2xl font-bold text-purple-800">4.2%</div>
                                <div class="text-sm text-gray-600">MAPE Error</div>
                            </div>
                            <div>
                                <div class="text-2xl font-bold text-purple-800">6 bln</div>
                                <div class="text-sm text-gray-600">Horizon Prediksi</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quality Control -->
                <div class="bg-white rounded-lg shadow-md p-8 mb-8">
                    <h2 class="text-2xl font-bold text-gray-800 mb-6">
                        <i class="fas fa-shield-alt text-red-600 mr-2"></i>
                        Kontrol Kualitas Data
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

                        <div class="text-center p-6 border border-gray-200 rounded-lg">
                            <i class="fas fa-check-circle text-green-600 text-3xl mb-3"></i>
                            <h3 class="font-bold text-gray-800 mb-2">Akurasi</h3>
                            <p class="text-sm text-gray-600">Verifikasi kebenaran data dengan sumber primer</p>
                        </div>

                        <div class="text-center p-6 border border-gray-200 rounded-lg">
                            <i class="fas fa-sync text-blue-600 text-3xl mb-3"></i>
                            <h3 class="font-bold text-gray-800 mb-2">Konsistensi</h3>
                            <p class="text-sm text-gray-600">Memastikan format dan standar yang seragam</p>
                        </div>

                        <div class="text-center p-6 border border-gray-200 rounded-lg">
                            <i class="fas fa-clock text-purple-600 text-3xl mb-3"></i>
                            <h3 class="font-bold text-gray-800 mb-2">Ketepatan Waktu</h3>
                            <p class="text-sm text-gray-600">Update data sesuai jadwal yang telah ditentukan</p>
                        </div>

                        <div class="text-center p-6 border border-gray-200 rounded-lg">
                            <i class="fas fa-chart-bar text-orange-600 text-3xl mb-3"></i>
                            <h3 class="font-bold text-gray-800 mb-2">Kelengkapan</h3>
                            <p class="text-sm text-gray-600">Memastikan tidak ada data yang hilang</p>
                        </div>
                    </div>
                </div>

                <!-- Limitations -->
                <div class="bg-yellow-50 border-l-4 border-yellow-500 p-6 rounded-lg mb-8">
                    <h2 class="text-xl font-bold text-yellow-900 mb-4">
                        <i class="fas fa-exclamation-triangle text-yellow-600 mr-2"></i>
                        Keterbatasan dan Asumsi
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm">
                        <div>
                            <h3 class="font-bold text-yellow-900 mb-2">Keterbatasan Data:</h3>
                            <ul class="text-yellow-800 space-y-1">
                                <li>• Data konsumsi berbasis survei (margin of error)</li>
                                <li>• Lag time dalam pelaporan data produksi</li>
                                <li>• Variasi metodologi antar daerah</li>
                                <li>• Data informal sector terbatas</li>
                            </ul>
                        </div>
                        <div>
                            <h3 class="font-bold text-yellow-900 mb-2">Asumsi Model:</h3>
                            <ul class="text-yellow-800 space-y-1">
                                <li>• Pola konsumsi relatif stabil dalam jangka pendek</li>
                                <li>• Faktor ekonomi makro berjalan normal</li>
                                <li>• Tidak ada force majeure (bencana besar)</li>
                                <li>• Kebijakan pemerintah tidak berubah drastis</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Standards and References -->
                <div class="bg-white rounded-lg shadow-md p-8 mb-8">
                    <h2 class="text-2xl font-bold text-gray-800 mb-6">
                        <i class="fas fa-book text-indigo-600 mr-2"></i>
                        Standar dan Referensi
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

                        <!-- International Standards -->
                        <div>
                            <h3 class="text-lg font-bold text-gray-800 mb-3">Standar Internasional</h3>
                            <ul class="space-y-2 text-gray-700">
                                <li class="flex items-start">
                                    <i class="fas fa-globe text-indigo-600 mr-2 mt-1 text-sm"></i>
                                    <span><strong>FAO Food Balance Sheets</strong> - Metodologi dasar NBM</span>
                                </li>
                                <li class="flex items-start">
                                    <i class="fas fa-heart text-indigo-600 mr-2 mt-1 text-sm"></i>
                                    <span><strong>WHO/FAO Nutrient Requirements</strong> - Standar kebutuhan gizi</span>
                                </li>
                                <li class="flex items-start">
                                    <i class="fas fa-chart-pie text-indigo-600 mr-2 mt-1 text-sm"></i>
                                    <span><strong>OECD Food Statistics</strong> - Klasifikasi komoditas</span>
                                </li>
                            </ul>
                        </div>

                        <!-- National Standards -->
                        <div>
                            <h3 class="text-lg font-bold text-gray-800 mb-3">Standar Nasional</h3>
                            <ul class="space-y-2 text-gray-700">
                                <li class="flex items-start">
                                    <i class="fas fa-flag text-red-600 mr-2 mt-1 text-sm"></i>
                                    <span><strong>SNI 7622:2011</strong> - Standar komposisi pangan Indonesia</span>
                                </li>
                                <li class="flex items-start">
                                    <i class="fas fa-balance-scale text-red-600 mr-2 mt-1 text-sm"></i>
                                    <span><strong>Permentan No. 65/2010</strong> - Pedoman penyusunan NBM</span>
                                </li>
                                <li class="flex items-start">
                                    <i class="fas fa-database text-red-600 mr-2 mt-1 text-sm"></i>
                                    <span><strong>Metadata BPS</strong> - Standar klasifikasi statistik</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Contact & More Info -->
                <div class="text-center">
                    <div class="space-y-4">
                        <h3 class="text-xl font-bold text-gray-800">Butuh Informasi Lebih Detail?</h3>
                        <p class="text-gray-600 max-w-2xl mx-auto">
                            Dokumentasi lengkap metodologi dan standar operasional tersedia untuk pengguna dengan akses
                            khusus.
                        </p>
                        <div class="space-x-4">
                            <a href="{{ route('public.ketersediaan.tentang-nbm') }}"
                                class="inline-flex items-center px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700">
                                <i class="fas fa-arrow-left mr-2"></i>
                                Kembali ke Tentang
                            </a>
                            <a href="{{ route('public.ketersediaan.dashboard') }}"
                                class="inline-flex items-center px-6 py-3 border border-green-600 text-green-600 rounded-lg hover:bg-green-600 hover:text-white">
                                <i class="fas fa-chart-bar mr-2"></i>
                                Lihat Dashboard
                            </a>
                        </div>
                    </div>
                </div>

            </div> <!-- End Content Container -->
        </div>
    </div>
</x-layouts.landing>
