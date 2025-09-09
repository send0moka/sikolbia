<x-layouts.landing>
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
                            <span class="ml-1 text-neutral-500">Ketersediaan</span>
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
                    Konsep & Metode Penghitungan Neraca Bahan Makanan (NBM)
                </h1>
                <p class="text-xl text-neutral-600">
                    Pemahaman konsep dan metodologi dalam penyusunan data ketersediaan pangan melalui Neraca Bahan Makanan (NBM)
                </p>
            </div>

            <!-- Two Column Layout -->
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-8" x-data="konsepMetode()">
                <!-- Sidebar Navigation - Left Column -->
                <div class="lg:col-span-1">
                    <div class="bg-neutral-50 rounded-lg p-6 sticky top-24">
                        <h3 class="text-lg font-semibold text-neutral-900 mb-6">Daftar Isi</h3>
                        <nav class="space-y-2">
                            <button @click="activeSection = 'pengertian'"
                                    :class="activeSection === 'pengertian' ? 'bg-blue-100 text-blue-700 border-blue-300' : 'text-neutral-600 hover:text-neutral-900 border-transparent'"
                                    class="w-full text-left px-3 py-2 rounded-md border text-sm font-medium transition duration-200">
                                Pengertian NBM
                            </button>
                            <button @click="activeSection = 'perhitungan'"
                                    :class="activeSection === 'perhitungan' ? 'bg-blue-100 text-blue-700 border-blue-300' : 'text-neutral-600 hover:text-neutral-900 border-transparent'"
                                    class="w-full text-left px-3 py-2 rounded-md border text-sm font-medium transition duration-200">
                                Cara Perhitungan
                            </button>
                            <button @click="activeSection = 'komponen'"
                                    :class="activeSection === 'komponen' ? 'bg-blue-100 text-blue-700 border-blue-300' : 'text-neutral-600 hover:text-neutral-900 border-transparent'"
                                    class="w-full text-left px-3 py-2 rounded-md border text-sm font-medium transition duration-200">
                                Komponen NBM
                            </button>
                            <button @click="activeSection = 'metodologi'"
                                    :class="activeSection === 'metodologi' ? 'bg-blue-100 text-blue-700 border-blue-300' : 'text-neutral-600 hover:text-neutral-900 border-transparent'"
                                    class="w-full text-left px-3 py-2 rounded-md border text-sm font-medium transition duration-200">
                                Metodologi
                            </button>
                            <button @click="activeSection = 'kegunaan'"
                                    :class="activeSection === 'kegunaan' ? 'bg-blue-100 text-blue-700 border-blue-300' : 'text-neutral-600 hover:text-neutral-900 border-transparent'"
                                    class="w-full text-left px-3 py-2 rounded-md border text-sm font-medium transition duration-200">
                                Kegunaan & Sumber
                            </button>
                        </nav>
                    </div>
                </div>

                <!-- Main Content - Right Column -->
                <div class="lg:col-span-3">
                    <!-- Pengertian NBM -->
                    <div x-show="activeSection === 'pengertian'" class="bg-white rounded-lg border border-neutral-200 p-8">
                        <h2 class="text-2xl font-bold text-neutral-900 mb-6">Pengertian Neraca Bahan Makanan (NBM)</h2>
                        <div class="prose prose-lg max-w-none">
                            <p class="text-neutral-700 leading-relaxed mb-6">
                                NBM memberikan informasi tentang situasi pengadaan/penyediaan pangan, baik yang berasal dari produksi dalam negeri, 
                                ekspor-impor dan stok serta penggunaan pangan untuk kebutuhan pakan, bibit, penggunaan untuk industri, serta informasi 
                                ketersediaan pangan untuk konsumsi penduduk suatu negara/wilayah dalam kurun waktu tertentu.
                            </p>
                            
                            <div class="bg-blue-50 p-6 rounded-lg border-l-4 border-blue-500">
                                <h4 class="font-semibold text-blue-900 mb-2">Tujuan Utama NBM:</h4>
                                <ul class="text-blue-800 space-y-2">
                                    <li>• Mengukur ketersediaan pangan dari sisi supply dan demand</li>
                                    <li>• Menggambarkan keseimbangan produksi dan konsumsi pangan</li>
                                    <li>• Menyediakan data untuk perencanaan ketahanan pangan</li>
                                    <li>• Monitoring distribusi dan penggunaan pangan nasional</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Cara Perhitungan -->
                    <div x-show="activeSection === 'perhitungan'" class="bg-white rounded-lg border border-neutral-200 p-8">
                        <h2 class="text-2xl font-bold text-neutral-900 mb-6">Cara Perhitungan NBM</h2>
                        <div class="prose prose-lg max-w-none">
                            
                            <h3 class="text-xl font-semibold text-neutral-800 mb-4">1. Penyediaan (Supply)</h3>
                            <div class="bg-green-50 p-6 rounded-lg border-l-4 border-green-500 mb-6">
                                <p class="font-mono text-center text-xl mb-4 text-green-800">
                                    <strong>Ps = P - St + I - E</strong>
                                </p>
                                <div class="text-sm space-y-2 text-green-700">
                                    <p><strong>dimana:</strong></p>
                                    <ul class="list-none space-y-1 ml-4">
                                        <li>Ps = total penyediaan dalam negeri</li>
                                        <li>P = produksi</li>
                                        <li>St = stok akhir - stok awal</li>
                                        <li>I = Impor</li>
                                        <li>E = ekspor</li>
                                    </ul>
                                </div>
                            </div>

                            <h3 class="text-xl font-semibold text-neutral-800 mb-4">2. Penggunaan (Utilization)</h3>
                            <div class="bg-blue-50 p-6 rounded-lg border-l-4 border-blue-500 mb-6">
                                <p class="font-mono text-center text-xl mb-4 text-blue-800">
                                    <strong>Pg = Pk + Bt + Id + Tc + K</strong>
                                </p>
                                <div class="text-sm space-y-2 text-blue-700">
                                    <p><strong>dimana:</strong></p>
                                    <ul class="list-none space-y-1 ml-4">
                                        <li>Pg = total penggunaan</li>
                                        <li>Pk = pakan</li>
                                        <li>Bt = bibit</li>
                                        <li>Id = industri</li>
                                        <li>Tc = tercecer</li>
                                        <li>K = ketersediaan bahan makanan</li>
                                    </ul>
                                </div>
                            </div>

                            <div class="bg-yellow-50 p-6 rounded-lg border-l-4 border-yellow-500">
                                <h4 class="font-semibold text-yellow-900 mb-2">Catatan Penting:</h4>
                                <p class="text-yellow-800">
                                    Untuk komponen pakan dan tercecer dapat digunakan besaran konversi persentase terhadap penyediaan dalam negeri. 
                                    Ketersediaan pangan per kapita, diperoleh dari ketersediaan dibagi dengan jumlah penduduk.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Komponen NBM -->
                    <div x-show="activeSection === 'komponen'" class="bg-white rounded-lg border border-neutral-200 p-8">
                        <h2 class="text-2xl font-bold text-neutral-900 mb-6">Komponen Detail NBM</h2>
                        <div class="prose prose-lg max-w-none">
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Sisi Penyediaan -->
                                <div class="bg-green-50 p-6 rounded-lg">
                                    <h3 class="text-lg font-semibold text-green-800 mb-4">Sisi Penyediaan (Supply)</h3>
                                    <ul class="space-y-3 text-green-700">
                                        <li>
                                            <strong>Produksi:</strong> Hasil produksi dalam negeri dari sektor pertanian, peternakan, perikanan, dan industri pangan
                                        </li>
                                        <li>
                                            <strong>Impor:</strong> Masuknya komoditas pangan dari luar negeri
                                        </li>
                                        <li>
                                            <strong>Perubahan Stok:</strong> Pengurangan atau penambahan stok komoditas pangan (stok akhir - stok awal)
                                        </li>
                                        <li>
                                            <strong>Ekspor:</strong> Keluarnya komoditas pangan ke luar negeri (dikurangi dari penyediaan)
                                        </li>
                                    </ul>
                                </div>

                                <!-- Sisi Penggunaan -->
                                <div class="bg-blue-50 p-6 rounded-lg">
                                    <h3 class="text-lg font-semibold text-blue-800 mb-4">Sisi Penggunaan (Utilization)</h3>
                                    <ul class="space-y-3 text-blue-700">
                                        <li>
                                            <strong>Pakan:</strong> Penggunaan untuk makanan ternak
                                        </li>
                                        <li>
                                            <strong>Bibit:</strong> Penggunaan untuk benih atau bibit tanaman
                                        </li>
                                        <li>
                                            <strong>Industri:</strong> Penggunaan untuk keperluan industri non-pangan
                                        </li>
                                        <li>
                                            <strong>Tercecer:</strong> Kehilangan selama proses penanganan, penyimpanan, dan distribusi
                                        </li>
                                        <li>
                                            <strong>Ketersediaan Bahan Makanan:</strong> Jumlah pangan yang tersedia untuk konsumsi penduduk
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Metodologi -->
                    <div x-show="activeSection === 'metodologi'" class="bg-white rounded-lg border border-neutral-200 p-8">
                        <h2 class="text-2xl font-bold text-neutral-900 mb-6">Metodologi Penyusunan NBM</h2>
                        <div class="prose prose-lg max-w-none">
                            
                            <div class="space-y-6">
                                <div class="flex items-start space-x-4">
                                    <div class="flex-shrink-0 w-8 h-8 bg-blue-600 text-white rounded-full flex items-center justify-center font-bold">1</div>
                                    <div>
                                        <h4 class="font-semibold text-neutral-900">Pengumpulan Data</h4>
                                        <p class="text-neutral-700">Data diperoleh dari berbagai sumber seperti BPS, Kementerian Pertanian, dan instansi terkait</p>
                                    </div>
                                </div>
                                
                                <div class="flex items-start space-x-4">
                                    <div class="flex-shrink-0 w-8 h-8 bg-blue-600 text-white rounded-full flex items-center justify-center font-bold">2</div>
                                    <div>
                                        <h4 class="font-semibold text-neutral-900">Klasifikasi Komoditas</h4>
                                        <p class="text-neutral-700">Pengelompokan komoditas berdasarkan jenis dan karakteristiknya</p>
                                    </div>
                                </div>
                                
                                <div class="flex items-start space-x-4">
                                    <div class="flex-shrink-0 w-8 h-8 bg-blue-600 text-white rounded-full flex items-center justify-center font-bold">3</div>
                                    <div>
                                        <h4 class="font-semibold text-neutral-900">Konversi Satuan</h4>
                                        <p class="text-neutral-700">Penyeragaman satuan ke dalam ton atau kilogram</p>
                                    </div>
                                </div>
                                
                                <div class="flex items-start space-x-4">
                                    <div class="flex-shrink-0 w-8 h-8 bg-blue-600 text-white rounded-full flex items-center justify-center font-bold">4</div>
                                    <div>
                                        <h4 class="font-semibold text-neutral-900">Perhitungan Keseimbangan</h4>
                                        <p class="text-neutral-700">Aplikasi rumus keseimbangan NBM</p>
                                    </div>
                                </div>
                                
                                <div class="flex items-start space-x-4">
                                    <div class="flex-shrink-0 w-8 h-8 bg-blue-600 text-white rounded-full flex items-center justify-center font-bold">5</div>
                                    <div>
                                        <h4 class="font-semibold text-neutral-900">Validasi Data</h4>
                                        <p class="text-neutral-700">Pengecekan konsistensi dan kewajaran data</p>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-8 bg-neutral-50 p-6 rounded-lg">
                                <h3 class="text-lg font-semibold text-neutral-900 mb-4">Periode dan Cakupan</h3>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div class="text-center">
                                        <div class="text-2xl font-bold text-blue-600">Tahunan</div>
                                        <div class="text-sm text-neutral-600">Periode Data</div>
                                    </div>
                                    <div class="text-center">
                                        <div class="text-2xl font-bold text-blue-600">34 Provinsi</div>
                                        <div class="text-sm text-neutral-600">Cakupan Wilayah</div>
                                    </div>
                                    <div class="text-center">
                                        <div class="text-2xl font-bold text-blue-600">200+ Komoditas</div>
                                        <div class="text-sm text-neutral-600">Jenis Pangan</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Kegunaan dan Sumber -->
                    <div x-show="activeSection === 'kegunaan'" class="bg-white rounded-lg border border-neutral-200 p-8">
                        <h2 class="text-2xl font-bold text-neutral-900 mb-6">Kegunaan Data NBM</h2>
                        <div class="prose prose-lg max-w-none">
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                                <div class="space-y-4">
                                    <div class="flex items-center space-x-3">
                                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <span class="text-neutral-700">Perencanaan ketahanan pangan nasional</span>
                                    </div>
                                    <div class="flex items-center space-x-3">
                                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <span class="text-neutral-700">Monitoring ketersediaan pangan</span>
                                    </div>
                                    <div class="flex items-center space-x-3">
                                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <span class="text-neutral-700">Evaluasi kebijakan pangan</span>
                                    </div>
                                </div>
                                <div class="space-y-4">
                                    <div class="flex items-center space-x-3">
                                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <span class="text-neutral-700">Penelitian dan analisis pangan</span>
                                    </div>
                                    <div class="flex items-center space-x-3">
                                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <span class="text-neutral-700">Pelaporan internasional (FAO, dll.)</span>
                                    </div>
                                </div>
                            </div>

                            <h3 class="text-xl font-semibold text-neutral-900 mb-4">Sumber Data</h3>
                            <div class="bg-neutral-50 p-6 rounded-lg">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <ul class="space-y-2 text-neutral-700">
                                        <li>• Badan Pusat Statistik (BPS)</li>
                                        <li>• Kementerian Pertanian</li>
                                        <li>• Kementerian Kelautan dan Perikanan</li>
                                    </ul>
                                    <ul class="space-y-2 text-neutral-700">
                                        <li>• Kementerian Perindustrian</li>
                                        <li>• Badan Ketahanan Pangan</li>
                                        <li>• Gabungan Pengusaha Makanan dan Minuman Indonesia (GAPMMI)</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Related Links -->
            <div class="mt-12 bg-neutral-50 p-6 rounded-lg">
                <h3 class="text-lg font-semibold text-neutral-900 mb-4">Halaman Terkait</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <a href="{{ route('ketersediaan.laporan-nbm') }}" 
                       class="block p-4 bg-white rounded border hover:shadow-md transition duration-200">
                        <h4 class="font-medium text-blue-600">Laporan Data NBM</h4>
                        <p class="text-sm text-neutral-600 mt-1">Akses data dan laporan Neraca Bahan Makanan terbaru</p>
                    </a>
                    <a href="{{ route('login') }}" 
                       class="block p-4 bg-white rounded border hover:shadow-md transition duration-200">
                        <h4 class="font-medium text-blue-600">Manajemen Data</h4>
                        <p class="text-sm text-neutral-600 mt-1">Login untuk mengakses dan mengelola data NBM</p>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Alpine.js Component -->
    <script>
        function konsepMetode() {
            return {
                activeSection: 'pengertian',
                
                init() {
                    // Set default active section
                    this.activeSection = 'pengertian';
                }
            }
        }
    </script>
</x-layouts.landing>
