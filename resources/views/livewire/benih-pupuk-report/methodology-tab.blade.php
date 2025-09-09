<!-- Methodology Tab Content -->
<div class="max-w-4xl">
    <div class="prose prose-neutral dark:prose-invert max-w-none">
        <h2 class="text-2xl font-bold text-neutral-900 dark:text-white mb-6">
            Metodologi Data Benih dan Pupuk
        </h2>
        
        <div class="space-y-8">
            <!-- Sumber Data -->
            <section class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-6 border border-blue-200 dark:border-blue-800">
                <h3 class="text-lg font-semibold text-blue-900 dark:text-blue-100 flex items-center mb-4">
                    <flux:icon.database class="w-5 h-5 mr-2" />
                    Sumber Data
                </h3>
                <p class="text-blue-800 dark:text-blue-200">
                    Data benih dan pupuk bersumber dari Kementerian Pertanian Republik Indonesia, 
                    yang dikumpulkan melalui sistem pelaporan berkala dari dinas pertanian di seluruh Indonesia.
                </p>
            </section>

            <!-- Definisi Variabel -->
            <section class="bg-green-50 dark:bg-green-900/20 rounded-lg p-6 border border-green-200 dark:border-green-800">
                <h3 class="text-lg font-semibold text-green-900 dark:text-green-100 flex items-center mb-4">
                    <flux:icon.list-bullet class="w-5 h-5 mr-2" />
                    Definisi Variabel
                </h3>
                
                <div class="space-y-4">
                    <div>
                        <h4 class="font-medium text-green-800 dark:text-green-200 mb-2">Benih:</h4>
                        <ul class="text-green-700 dark:text-green-300 space-y-1 pl-4">
                            <li><strong>Padi Benih Pokok:</strong> Benih padi unggul yang digunakan sebagai induk untuk menghasilkan benih sebar</li>
                            <li><strong>Padi Benih Sebar:</strong> Benih padi siap tanam yang didistribusikan kepada petani</li>
                            <li><strong>Jagung Benih Sebar:</strong> Benih jagung siap tanam dengan berbagai klasifikasi (hibrida, komposit)</li>
                            <li><strong>Kedelai Benih Sebar:</strong> Benih kedelai untuk distribusi kepada petani</li>
                        </ul>
                    </div>
                    
                    <div>
                        <h4 class="font-medium text-green-800 dark:text-green-200 mb-2">Pupuk:</h4>
                        <ul class="text-green-700 dark:text-green-300 space-y-1 pl-4">
                            <li><strong>Pupuk Urea:</strong> Pupuk nitrogen dengan kandungan 45-46% N</li>
                            <li><strong>Pupuk SP-36:</strong> Pupuk fosfat dengan kandungan 36% P2O5</li>
                            <li><strong>Pupuk ZA:</strong> Pupuk nitrogen dan sulfur (21% N, 24% S)</li>
                            <li><strong>Pupuk NPK:</strong> Pupuk majemuk dengan kombinasi Nitrogen, Fosfat, dan Kalium</li>
                            <li><strong>Pupuk Organik:</strong> Pupuk dari bahan organik alami</li>
                        </ul>
                    </div>
                </div>
            </section>

            <!-- Klasifikasi -->
            <section class="bg-purple-50 dark:bg-purple-900/20 rounded-lg p-6 border border-purple-200 dark:border-purple-800">
                <h3 class="text-lg font-semibold text-purple-900 dark:text-purple-100 flex items-center mb-4">
                    <flux:icon.tag class="w-5 h-5 mr-2" />
                    Klasifikasi Data
                </h3>
                
                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <h4 class="font-medium text-purple-800 dark:text-purple-200 mb-2">Klasifikasi Benih:</h4>
                        <ul class="text-purple-700 dark:text-purple-300 space-y-1">
                            <li><strong>Inbrida:</strong> Varietas hasil persilangan dalam (self-pollinated)</li>
                            <li><strong>Hibrida:</strong> Varietas hasil persilangan silang antar tetua</li>
                            <li><strong>Komposit:</strong> Varietas dari populasi bersari bebas</li>
                        </ul>
                    </div>
                    
                    <div>
                        <h4 class="font-medium text-purple-800 dark:text-purple-200 mb-2">Klasifikasi Pupuk:</h4>
                        <ul class="text-purple-700 dark:text-purple-300 space-y-1">
                            <li><strong>Alokasi:</strong> Jumlah pupuk yang dialokasikan/direncanakan</li>
                            <li><strong>Realisasi:</strong> Jumlah pupuk yang benar-benar didistribusikan</li>
                        </ul>
                    </div>
                </div>
            </section>

            <!-- Periode Data -->
            <section class="bg-orange-50 dark:bg-orange-900/20 rounded-lg p-6 border border-orange-200 dark:border-orange-800">
                <h3 class="text-lg font-semibold text-orange-900 dark:text-orange-100 flex items-center mb-4">
                    <flux:icon.calendar class="w-5 h-5 mr-2" />
                    Periode dan Wilayah
                </h3>
                
                <div class="space-y-4">
                    <div>
                        <h4 class="font-medium text-orange-800 dark:text-orange-200 mb-2">Periode Data:</h4>
                        <p class="text-orange-700 dark:text-orange-300">
                            Data tersedia mulai tahun 2014 hingga saat ini, dengan pelaporan bulanan 
                            untuk memantau distribusi dan ketersediaan benih serta pupuk secara real-time.
                        </p>
                    </div>
                    
                    <div>
                        <h4 class="font-medium text-orange-800 dark:text-orange-200 mb-2">Cakupan Wilayah:</h4>
                        <p class="text-orange-700 dark:text-orange-300">
                            Data mencakup seluruh provinsi di Indonesia dengan fokus pada daerah-daerah 
                            sentra produksi pertanian. Setiap wilayah memiliki kode unik untuk kemudahan identifikasi.
                        </p>
                    </div>
                </div>
            </section>

            <!-- Metodologi Perhitungan -->
            <section class="bg-red-50 dark:bg-red-900/20 rounded-lg p-6 border border-red-200 dark:border-red-800">
                <h3 class="text-lg font-semibold text-red-900 dark:text-red-100 flex items-center mb-4">
                    <flux:icon.calculator class="w-5 h-5 mr-2" />
                    Metodologi Perhitungan
                </h3>
                
                <div class="space-y-4">
                    <div>
                        <h4 class="font-medium text-red-800 dark:text-red-200 mb-2">Aggregasi Data:</h4>
                        <p class="text-red-700 dark:text-red-300">
                            Total nilai dihitung dengan menjumlahkan seluruh nilai dari wilayah yang dipilih 
                            untuk periode dan kategori yang sama.
                        </p>
                    </div>
                    
                    <div>
                        <h4 class="font-medium text-red-800 dark:text-red-200 mb-2">Satuan:</h4>
                        <p class="text-red-700 dark:text-red-300">
                            Semua data benih dan pupuk dinyatakan dalam satuan Ton (1.000 kg), 
                            sesuai dengan standar pelaporan Kementerian Pertanian.
                        </p>
                    </div>
                    
                    <div>
                        <h4 class="font-medium text-red-800 dark:text-red-200 mb-2">Validasi Data:</h4>
                        <p class="text-red-700 dark:text-red-300">
                            Data melalui proses validasi berlapis mulai dari tingkat kabupaten/kota, 
                            provinsi, hingga pusat untuk memastikan akurasi dan konsistensi.
                        </p>
                    </div>
                </div>
            </section>

            <!-- Catatan Penting -->
            <section class="bg-neutral-100 dark:bg-neutral-700 rounded-lg p-6 border-l-4 border-neutral-500">
                <h3 class="text-lg font-semibold text-neutral-900 dark:text-neutral-100 flex items-center mb-4">
                    <flux:icon.exclamation-circle class="w-5 h-5 mr-2" />
                    Catatan Penting
                </h3>
                
                <ul class="text-neutral-700 dark:text-neutral-300 space-y-2">
                    <li>• Data yang ditampilkan adalah data resmi dari Kementerian Pertanian RI</li>
                    <li>• Nilai 0 (nol) dapat berarti tidak ada distribusi atau belum ada pelaporan</li>
                    <li>• Data dapat mengalami revisi sesuai dengan pemutakhiran dari sumber</li>
                    <li>• Untuk analisis lebih mendalam, disarankan menggunakan data dalam rentang waktu yang cukup</li>
                </ul>
            </section>
        </div>
    </div>
</div>
