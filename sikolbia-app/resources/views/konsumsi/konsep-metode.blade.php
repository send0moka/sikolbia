<x-layouts.landing title="Konsep dan Metode Konsumsi Pangan">
    <div class="py-12 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Breadcrumb -->
            <nav class="flex mb-8" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('home') }}" class="text-neutral-700 hover:text-blue-600">
                            Home
                        </a>
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
                            <span class="ml-1 text-blue-600 font-medium">Konsep dan Metode</span>
                        </div>
                    </li>
                </ol>
            </nav>

            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-3xl md:text-4xl font-bold text-neutral-900 mb-4">
                    Konsep dan Metode Konsumsi Pangan
                </h1>
                <p class="text-xl text-neutral-600">
                    Pemahaman konsep dan metodologi pengumpulan data konsumsi pangan melalui Survei Sosial Ekonomi Nasional (Susenas)
                </p>
            </div>

            <!-- Two Column Layout -->
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-8" x-data="{ activeSection: 'konsep' }">
                <!-- Sidebar Navigation -->
                <div class="lg:col-span-1">
                    <div class="bg-neutral-50 rounded-lg p-4 sticky top-24">
                        <h3 class="font-semibold text-neutral-900 mb-4">Daftar Isi</h3>
                        <nav class="space-y-2">
                            <button @click="activeSection = 'konsep'" 
                                    :class="activeSection === 'konsep' ? 'bg-blue-100 text-blue-700 border-blue-300' : 'text-neutral-600 hover:text-blue-600'"
                                    class="w-full text-left px-3 py-2 rounded-md text-sm font-medium border transition-colors">
                                Konsep & Metode
                            </button>
                            <button @click="activeSection = 'perkembangan'" 
                                    :class="activeSection === 'perkembangan' ? 'bg-blue-100 text-blue-700 border-blue-300' : 'text-neutral-600 hover:text-blue-600'"
                                    class="w-full text-left px-3 py-2 rounded-md text-sm font-medium border transition-colors">
                                Perkembangan Susenas
                            </button>
                            <button @click="activeSection = 'metodologi'" 
                                    :class="activeSection === 'metodologi' ? 'bg-blue-100 text-blue-700 border-blue-300' : 'text-neutral-600 hover:text-blue-600'"
                                    class="w-full text-left px-3 py-2 rounded-md text-sm font-medium border transition-colors">
                                Metodologi
                            </button>
                            <button @click="activeSection = 'jenis-data'" 
                                    :class="activeSection === 'jenis-data' ? 'bg-blue-100 text-blue-700 border-blue-300' : 'text-neutral-600 hover:text-blue-600'"
                                    class="w-full text-left px-3 py-2 rounded-md text-sm font-medium border transition-colors">
                                Jenis Data
                            </button>
                            <button @click="activeSection = 'indikator'" 
                                    :class="activeSection === 'indikator' ? 'bg-blue-100 text-blue-700 border-blue-300' : 'text-neutral-600 hover:text-blue-600'"
                                    class="w-full text-left px-3 py-2 rounded-md text-sm font-medium border transition-colors">
                                Indikator Utama
                            </button>
                        </nav>
                    </div>
                </div>

                <!-- Main Content -->
                <div class="lg:col-span-3">
                    <!-- Konsep & Metode Section -->
                    <div x-show="activeSection === 'konsep'" 
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 transform translate-y-4"
                         x-transition:enter-end="opacity-100 transform translate-y-0"
                         class="bg-white rounded-lg border p-6">
                        
                        <h2 class="text-2xl font-bold text-neutral-900 mb-4">Konsep & Metode Pengumpulan Data Konsumsi</h2>
                        <p class="text-neutral-700 leading-relaxed mb-6">
                            Data konsumsi yang dimaksud adalah konsumsi di rumah tangga yang bersumber dari Survei Sosial Ekonomi Nasional (Susenas). 
                            Susenas merupakan salah satu survei yang diselenggarakan oleh BPS.
                        </p>

                        <!-- Expandable Details -->
                        <div x-data="{ expanded: false }" class="border rounded-lg">
                            <button @click="expanded = !expanded" 
                                    class="w-full px-4 py-3 text-left flex justify-between items-center hover:bg-neutral-50 transition-colors">
                                <span class="font-medium text-neutral-900">Detail Aspek Sosial Ekonomi</span>
                                <svg :class="expanded ? 'rotate-180' : ''" class="w-5 h-5 text-neutral-500 transition-transform">
                                    <path fill="currentColor" d="M16.293 9.293L12 13.586 7.707 9.293A1 1 0 016.293 10.707l5 5a1 1 0 001.414 0l5-5a1 1 0 00-1.414-1.414z"/>
                                </svg>
                            </button>
                            <div x-show="expanded" x-collapse class="px-4 pb-4">
                                <p class="text-neutral-600 mb-3">Hasil dari Susenas merupakan data mengenai berbagai aspek sosial ekonomi dan pemenuhan kebutuhan hidup:</p>
                                <ul class="list-disc list-inside space-y-1 text-neutral-600 ml-4">
                                    <li>Sandang (pakaian dan tekstil)</li>
                                    <li>Pangan (makanan dan minuman)</li>
                                    <li>Papan (perumahan dan fasilitas)</li>
                                    <li>Pendidikan</li>
                                    <li>Kesehatan</li>
                                    <li>Keamanan</li>
                                    <li>Kesempatan kerja</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Perkembangan Section -->
                    <div x-show="activeSection === 'perkembangan'" 
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 transform translate-y-4"
                         x-transition:enter-end="opacity-100 transform translate-y-0"
                         class="bg-white rounded-lg border p-6">
                        
                        <h2 class="text-2xl font-bold text-neutral-900 mb-4">Perkembangan Pelaksanaan Susenas</h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="bg-blue-50 p-4 rounded-lg border-l-4 border-blue-500">
                                <h3 class="font-semibold text-blue-900 mb-2">Sebelum 2011</h3>
                                <ul class="text-blue-800 space-y-1 text-sm">
                                    <li>• Susenas Panel</li>
                                    <li>• Dilaksanakan bulan Maret</li>
                                    <li>• Frekuensi: Tahunan</li>
                                </ul>
                            </div>
                            <div class="bg-green-50 p-4 rounded-lg border-l-4 border-green-500">
                                <h3 class="font-semibold text-green-900 mb-2">Sejak 2011</h3>
                                <ul class="text-green-800 space-y-1 text-sm">
                                    <li>• Susenas Triwulanan</li>
                                    <li>• Database: Data Maret (Triwulan I)</li>
                                    <li>• Kuesioner: Modul konsumsi/pengeluaran</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Metodologi Section -->
                    <div x-show="activeSection === 'metodologi'" 
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 transform translate-y-4"
                         x-transition:enter-end="opacity-100 transform translate-y-0"
                         class="bg-white rounded-lg border p-6 space-y-6">
                        
                        <h2 class="text-2xl font-bold text-neutral-900 mb-4">Metodologi Pengumpulan Data</h2>

                        <!-- Metode Wawancara -->
                        <div x-data="{ expanded: false }" class="border rounded-lg">
                            <button @click="expanded = !expanded" 
                                    class="w-full px-4 py-3 text-left flex justify-between items-center hover:bg-neutral-50">
                                <span class="font-medium text-neutral-900">Metode Wawancara & Recall</span>
                                <svg :class="expanded ? 'rotate-180' : ''" class="w-5 h-5 text-neutral-500 transition-transform">
                                    <path fill="currentColor" d="M16.293 9.293L12 13.586 7.707 9.293A1 1 0 016.293 10.707l5 5a1 1 0 001.414 0l5-5a1 1 0 00-1.414-1.414z"/>
                                </svg>
                            </button>
                            <div x-show="expanded" x-collapse class="px-4 pb-4">
                                <p class="text-neutral-600 mb-3">Wawancara dengan kepala rumah tangga menggunakan metode recall:</p>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div class="bg-orange-50 p-3 rounded border-l-4 border-orange-400">
                                        <h4 class="font-medium text-orange-900">Makanan</h4>
                                        <p class="text-orange-800 text-sm">Recall seminggu yang lalu</p>
                                    </div>
                                    <div class="bg-purple-50 p-3 rounded border-l-4 border-purple-400">
                                        <h4 class="font-medium text-purple-900">Bukan Makanan</h4>
                                        <p class="text-purple-800 text-sm">Recall sebulan yang lalu</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Klasifikasi Data -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="bg-blue-50 p-4 rounded-lg border-l-4 border-blue-500">
                                <h4 class="font-semibold text-blue-900 mb-2">Pengeluaran Makanan</h4>
                                <ul class="list-disc list-inside space-y-1 text-blue-800 text-sm">
                                    <li>215 komoditas</li>
                                    <li>Kuantitas + nilai rupiah</li>
                                    <li>Recall seminggu</li>
                                </ul>
                            </div>
                            <div class="bg-green-50 p-4 rounded-lg border-l-4 border-green-500">
                                <h4 class="font-semibold text-green-900 mb-2">Bukan Makanan</h4>
                                <ul class="list-disc list-inside space-y-1 text-green-800 text-sm">
                                    <li>Nilai rupiah</li>
                                    <li>Listrik, gas, air, BBM: + kuantitas</li>
                                    <li>Recall sebulan</li>
                                </ul>
                            </div>
                        </div>

                        <!-- Cakupan -->
                        <div class="bg-neutral-50 p-4 rounded-lg">
                            <h4 class="font-semibold text-neutral-900 mb-3">Cakupan Wilayah dan Waktu</h4>
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-4 text-sm">
                                <div>
                                    <span class="font-medium text-neutral-700">Wilayah:</span>
                                    <p class="text-neutral-600">34 provinsi</p>
                                </div>
                                <div>
                                    <span class="font-medium text-neutral-700">Klasifikasi:</span>
                                    <p class="text-neutral-600">Kota & Desa</p>
                                </div>
                                <div>
                                    <span class="font-medium text-neutral-700">Periode:</span>
                                    <p class="text-neutral-600">Sejak 1993</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Jenis Data Section -->
                    <div x-show="activeSection === 'jenis-data'" 
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 transform translate-y-4"
                         x-transition:enter-end="opacity-100 transform translate-y-0"
                         class="bg-white rounded-lg border p-6">
                        
                        <h2 class="text-2xl font-bold text-neutral-900 mb-4">Jenis Data yang Dikumpulkan</h2>
                        
                        <div class="space-y-4">
                            <div x-data="{ expanded: false }" class="border rounded-lg">
                                <button @click="expanded = !expanded" 
                                        class="w-full px-4 py-3 text-left flex justify-between items-center hover:bg-neutral-50">
                                    <span class="font-medium text-neutral-900">Data Konsumsi Pangan</span>
                                    <svg :class="expanded ? 'rotate-180' : ''" class="w-5 h-5 text-neutral-500 transition-transform">
                                        <path fill="currentColor" d="M16.293 9.293L12 13.586 7.707 9.293A1 1 0 016.293 10.707l5 5a1 1 0 001.414 0l5-5a1 1 0 00-1.414-1.414z"/>
                                    </svg>
                                </button>
                                <div x-show="expanded" x-collapse class="px-4 pb-4">
                                    <ul class="list-disc list-inside space-y-2 text-neutral-600">
                                        <li><strong>Konsumsi Kalori:</strong> Jumlah energi per kapita per hari</li>
                                        <li><strong>Konsumsi Protein:</strong> Jumlah protein per kapita per hari</li>
                                        <li><strong>Konsumsi per Komoditas:</strong> Kuantitas untuk setiap jenis pangan</li>
                                        <li><strong>Pengeluaran Pangan:</strong> Nilai pengeluaran rumah tangga</li>
                                    </ul>
                                </div>
                            </div>

                            <div x-data="{ expanded: false }" class="border rounded-lg">
                                <button @click="expanded = !expanded" 
                                        class="w-full px-4 py-3 text-left flex justify-between items-center hover:bg-neutral-50">
                                    <span class="font-medium text-neutral-900">Kelompok Pangan (14 Kelompok)</span>
                                    <svg :class="expanded ? 'rotate-180' : ''" class="w-5 h-5 text-neutral-500 transition-transform">
                                        <path fill="currentColor" d="M16.293 9.293L12 13.586 7.707 9.293A1 1 0 016.293 10.707l5 5a1 1 0 001.414 0l5-5a1 1 0 00-1.414-1.414z"/>
                                    </svg>
                                </button>
                                <div x-show="expanded" x-collapse class="px-4 pb-4">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-2 text-sm">
                                        <div class="space-y-1">
                                            <p>• Padi-padian</p>
                                            <p>• Umbi-umbian</p>
                                            <p>• Ikan/udang/cumi/kerang</p>
                                            <p>• Daging</p>
                                            <p>• Telur dan susu</p>
                                            <p>• Sayur-sayuran</p>
                                            <p>• Kacang-kacangan</p>
                                        </div>
                                        <div class="space-y-1">
                                            <p>• Buah-buahan</p>
                                            <p>• Minyak dan lemak</p>
                                            <p>• Bahan minuman</p>
                                            <p>• Bumbu-bumbuan</p>
                                            <p>• Konsumsi lainnya</p>
                                            <p>• Makanan dan minuman jadi</p>
                                            <p>• Tembakau dan sirih</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Indikator Section -->
                    <div x-show="activeSection === 'indikator'" 
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 transform translate-y-4"
                         x-transition:enter-end="opacity-100 transform translate-y-0"
                         class="bg-white rounded-lg border p-6">
                        
                        <h2 class="text-2xl font-bold text-neutral-900 mb-4">Indikator Utama</h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="bg-yellow-50 p-4 rounded-lg border-l-4 border-yellow-500">
                                <h3 class="font-semibold text-yellow-900 mb-3">Konsumsi Energi</h3>
                                <ul class="list-disc list-inside space-y-1 text-yellow-800 text-sm">
                                    <li>Konsumsi Energi per Kapita (Kkal/kapita/hari)</li>
                                    <li>Tingkat Kecukupan Energi (% terhadap AKG)</li>
                                    <li>Prevalensi Kurang Energi (< 70% AKG)</li>
                                </ul>
                            </div>
                            <div class="bg-red-50 p-4 rounded-lg border-l-4 border-red-500">
                                <h3 class="font-semibold text-red-900 mb-3">Konsumsi Protein</h3>
                                <ul class="list-disc list-inside space-y-1 text-red-800 text-sm">
                                    <li>Konsumsi Protein per Kapita (g/kapita/hari)</li>
                                    <li>Tingkat Kecukupan Protein (% terhadap AKG)</li>
                                    <li>Prevalensi Kurang Protein (< 80% AKG)</li>
                                </ul>
                            </div>
                        </div>

                        <div class="mt-6 bg-neutral-50 p-4 rounded-lg">
                            <h4 class="font-semibold text-neutral-900 mb-3">Pengolahan dan Analisis Data</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 text-sm">
                                <div class="text-center">
                                    <div class="bg-blue-100 p-2 rounded-full w-8 h-8 mx-auto mb-2 flex items-center justify-center">
                                        <span class="text-blue-600 font-bold text-xs">1</span>
                                    </div>
                                    <p class="font-medium">Data Cleaning</p>
                                </div>
                                <div class="text-center">
                                    <div class="bg-green-100 p-2 rounded-full w-8 h-8 mx-auto mb-2 flex items-center justify-center">
                                        <span class="text-green-600 font-bold text-xs">2</span>
                                    </div>
                                    <p class="font-medium">Konversi Satuan</p>
                                </div>
                                <div class="text-center">
                                    <div class="bg-purple-100 p-2 rounded-full w-8 h-8 mx-auto mb-2 flex items-center justify-center">
                                        <span class="text-purple-600 font-bold text-xs">3</span>
                                    </div>
                                    <p class="font-medium">Konversi Gizi</p>
                                </div>
                            </div>
                        </div>

                        <!-- Expandable Additional Info -->
                        <div class="mt-6 space-y-4">
                            <div x-data="{ expanded: false }" class="border rounded-lg">
                                <button @click="expanded = !expanded" 
                                        class="w-full px-4 py-3 text-left flex justify-between items-center hover:bg-neutral-50">
                                    <span class="font-medium text-neutral-900">Kegunaan Data Susenas</span>
                                    <svg :class="expanded ? 'rotate-180' : ''" class="w-5 h-5 text-neutral-500 transition-transform">
                                        <path fill="currentColor" d="M16.293 9.293L12 13.586 7.707 9.293A1 1 0 016.293 10.707l5 5a1 1 0 001.414 0l5-5a1 1 0 00-1.414-1.414z"/>
                                    </svg>
                                </button>
                                <div x-show="expanded" x-collapse class="px-4 pb-4">
                                    <ul class="list-disc list-inside space-y-2 text-neutral-600">
                                        <li>Penyusunan peta ketahanan dan kerentanan pangan</li>
                                        <li>Monitoring pencapaian target konsumsi energi dan protein</li>
                                        <li>Evaluasi program fortifikasi pangan</li>
                                        <li>Perencanaan program diversifikasi konsumsi pangan</li>
                                        <li>Penelitian pola konsumsi dan gizi masyarakat</li>
                                        <li>Pelaporan indikator gizi nasional dan internasional</li>
                                    </ul>
                                </div>
                            </div>

                            <div x-data="{ expanded: false }" class="border rounded-lg">
                                <button @click="expanded = !expanded" 
                                        class="w-full px-4 py-3 text-left flex justify-between items-center hover:bg-neutral-50">
                                    <span class="font-medium text-neutral-900">Keterbatasan Data</span>
                                    <svg :class="expanded ? 'rotate-180' : ''" class="w-5 h-5 text-neutral-500 transition-transform">
                                        <path fill="currentColor" d="M16.293 9.293L12 13.586 7.707 9.293A1 1 0 016.293 10.707l5 5a1 1 0 001.414 0l5-5a1 1 0 00-1.414-1.414z"/>
                                    </svg>
                                </button>
                                <div x-show="expanded" x-collapse class="px-4 pb-4">
                                    <ul class="list-disc list-inside space-y-2 text-neutral-600">
                                        <li>Data konsumsi di luar rumah tangga belum tercakup secara detail</li>
                                        <li>Recall bias pada pengisian kuesioner</li>
                                        <li>Seasonal variation belum dapat ditangkap dengan baik</li>
                                        <li>Data mikronutrien masih terbatas</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Related Links -->
            <div class="mt-8 bg-neutral-50 p-6 rounded-lg">
                <h3 class="text-lg font-semibold text-neutral-900 mb-4">Halaman Terkait</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <a href="{{ route('konsumsi.laporan-susenas') }}" 
                       class="block p-4 bg-white rounded border hover:shadow-md transition duration-200">
                        <h4 class="font-medium text-blue-600">Laporan Data Susenas</h4>
                        <p class="text-sm text-neutral-600 mt-1">Akses data dan laporan konsumsi pangan dari Susenas</p>
                    </a>
                    <a href="{{ route('konsumsi.per-kapita-seminggu') }}" 
                       class="block p-4 bg-white rounded border hover:shadow-md transition duration-200">
                        <h4 class="font-medium text-blue-600">Konsumsi Per Kapita Seminggu</h4>
                        <p class="text-sm text-neutral-600 mt-1">Data konsumsi pangan per kapita dalam periode seminggu</p>
                    </a>
                    <a href="{{ route('konsumsi.per-kapita-setahun') }}" 
                       class="block p-4 bg-white rounded border hover:shadow-md transition duration-200">
                        <h4 class="font-medium text-blue-600">Konsumsi Per Kapita Setahun</h4>
                        <p class="text-sm text-neutral-600 mt-1">Data konsumsi pangan per kapita dalam periode setahun</p>
                    </a>
                    <a href="{{ route('login') }}" 
                       class="block p-4 bg-white rounded border hover:shadow-md transition duration-200">
                        <h4 class="font-medium text-blue-600">Manajemen Data</h4>
                        <p class="text-sm text-neutral-600 mt-1">Login untuk mengakses dan mengelola data konsumsi</p>
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-layouts.landing>
