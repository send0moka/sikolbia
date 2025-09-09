<x-layouts.app title="Konsep Transaksi Susenas">
    <!-- Header Section -->
    <section class="bg-gradient-to-r from-emerald-600 to-teal-700 dark:from-emerald-800 dark:to-teal-900 text-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h1 class="text-4xl md:text-5xl font-bold mb-4">
                    📊 Konsep Transaksi Susenas
                </h1>
                <p class="text-xl md:text-2xl text-emerald-100 dark:text-emerald-200 mb-8">
                    Memahami Sistem Survei Sosial Ekonomi Nasional dari Input hingga Output
                </p>
                <div class="bg-white/10 dark:bg-white/5 backdrop-blur-sm rounded-lg p-6 max-w-4xl mx-auto">
                    <p class="text-lg leading-relaxed">
                        Halaman ini menjelaskan secara detail bagaimana sistem transaksi Susenas (Survei Sosial Ekonomi Nasional) 
                        bekerja dalam aplikasi ini, mulai dari input data konsumsi rumah tangga hingga menghasilkan output laporan 
                        yang dapat digunakan untuk analisis pola konsumsi pangan masyarakat Indonesia.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Navigation Quick Menu -->
    <section class="bg-neutral-50 dark:bg-zinc-800 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-zinc-900 rounded-lg shadow-sm border border-neutral-200 dark:border-zinc-700 p-6">
                <h2 class="text-lg font-semibold text-neutral-900 dark:text-white mb-4">📚 Daftar Isi</h2>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4" x-data="{ activeSection: 'overview' }">
                    <button @click="activeSection = 'overview'; document.getElementById('overview').scrollIntoView({behavior: 'smooth'})"
                            :class="activeSection === 'overview' ? 'bg-emerald-50 dark:bg-emerald-900/50 border-emerald-200 dark:border-emerald-700 text-emerald-700 dark:text-emerald-300' : 'bg-neutral-50 dark:bg-zinc-800 border-neutral-200 dark:border-zinc-600 text-neutral-700 dark:text-zinc-300 hover:bg-neutral-100 dark:hover:bg-zinc-700'"
                            class="text-sm font-medium px-4 py-2 border rounded-lg transition-colors">
                        📖 Overview Susenas
                    </button>
                    <button @click="activeSection = 'dataflow'; document.getElementById('dataflow').scrollIntoView({behavior: 'smooth'})"
                            :class="activeSection === 'dataflow' ? 'bg-emerald-50 dark:bg-emerald-900/50 border-emerald-200 dark:border-emerald-700 text-emerald-700 dark:text-emerald-300' : 'bg-neutral-50 dark:bg-zinc-800 border-neutral-200 dark:border-zinc-600 text-neutral-700 dark:text-zinc-300 hover:bg-neutral-100 dark:hover:bg-zinc-700'"
                            class="text-sm font-medium px-4 py-2 border rounded-lg transition-colors">
                        🔄 Alur Data
                    </button>
                    <button @click="activeSection = 'calculation'; document.getElementById('calculation').scrollIntoView({behavior: 'smooth'})"
                            :class="activeSection === 'calculation' ? 'bg-emerald-50 dark:bg-emerald-900/50 border-emerald-200 dark:border-emerald-700 text-emerald-700 dark:text-emerald-300' : 'bg-neutral-50 dark:bg-zinc-800 border-neutral-200 dark:border-zinc-600 text-neutral-700 dark:text-zinc-300 hover:bg-neutral-100 dark:hover:bg-zinc-700'"
                            class="text-sm font-medium px-4 py-2 border rounded-lg transition-colors">
                        ⚡ Perhitungan
                    </button>
                    <button @click="activeSection = 'demo'; document.getElementById('demo').scrollIntoView({behavior: 'smooth'})"
                            :class="activeSection === 'demo' ? 'bg-emerald-50 dark:bg-emerald-900/50 border-emerald-200 dark:border-emerald-700 text-emerald-700 dark:text-emerald-300' : 'bg-neutral-50 dark:bg-zinc-800 border-neutral-200 dark:border-zinc-600 text-neutral-700 dark:text-zinc-300 hover:bg-neutral-100 dark:hover:bg-zinc-700'"
                            class="text-sm font-medium px-4 py-2 border rounded-lg transition-colors">
                        🧪 Demo & Test
                    </button>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        
        <!-- Section 1: Overview Susenas -->
        <section id="overview" class="mb-16">
            <div class="bg-white dark:bg-zinc-900 rounded-lg shadow-lg border border-neutral-200 dark:border-zinc-700 p-8">
                <h2 class="text-3xl font-bold text-neutral-900 dark:text-white mb-6">📖 Apa itu Transaksi Susenas?</h2>
                
                <div class="space-y-6">
                    <div class="bg-emerald-50 dark:bg-emerald-900/20 border-l-4 border-emerald-400 dark:border-emerald-500 p-6 rounded-r-lg">
                        <h3 class="text-xl font-semibold text-emerald-900 dark:text-emerald-300 mb-3">🏠 Penjelasan Sederhana</h3>
                        <p class="text-emerald-800 dark:text-emerald-200 leading-relaxed">
                            Bayangkan Anda melakukan survei ke rumah-rumah di seluruh Indonesia untuk menanyakan: <em>"Berapa kilogram beras yang dikonsumsi keluarga Anda dalam seminggu?"</em>. 
                            <strong>Susenas (Survei Sosial Ekonomi Nasional)</strong> adalah sistem pencatatan yang mengumpulkan data konsumsi makanan dari 
                            rumah tangga untuk mengetahui pola makan masyarakat Indonesia secara riil.
                        </p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-700 rounded-lg p-6">
                            <h4 class="text-lg font-semibold text-blue-800 dark:text-blue-300 mb-3">📋 Data yang Dikumpulkan</h4>
                            <ul class="space-y-2 text-blue-700 dark:text-blue-200">
                                <li>• <strong>Konsumsi per Kapita</strong>: Jumlah makanan per orang</li>
                                <li>• <strong>Frekuensi Konsumsi</strong>: Seberapa sering dimakan</li>
                                <li>• <strong>Lokasi Geografis</strong>: Data per provinsi/kabupaten</li>
                                <li>• <strong>Karakteristik RT</strong>: Urban/rural, ekonomi, dll</li>
                            </ul>
                        </div>

                        <div class="bg-orange-50 dark:bg-orange-900/20 border border-orange-200 dark:border-orange-700 rounded-lg p-6">
                            <h4 class="text-lg font-semibold text-orange-800 dark:text-orange-300 mb-3">🎯 Tujuan Susenas</h4>
                            <ul class="space-y-2 text-orange-700 dark:text-orange-200">
                                <li>• <strong>Pola Konsumsi</strong>: Menganalisis kebiasaan makan</li>
                                <li>• <strong>Ketahanan Pangan</strong>: Mengukur akses pangan</li>
                                <li>• <strong>Kebijakan Publik</strong>: Dasar pengambilan keputusan</li>
                                <li>• <strong>Monitoring</strong>: Evaluasi program pemerintah</li>
                            </ul>
                        </div>
                    </div>

                    <div class="bg-purple-50 dark:bg-purple-900/20 border border-purple-200 dark:border-purple-700 rounded-lg p-6">
                        <h4 class="text-lg font-semibold text-purple-800 dark:text-purple-300 mb-3">🔍 Perbedaan dengan NBM</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <h5 class="font-semibold text-purple-700 dark:text-purple-200 mb-2">📊 Susenas (Konsumsi Aktual)</h5>
                                <p class="text-purple-600 dark:text-purple-300 text-sm">
                                    Data dari survei rumah tangga - apa yang benar-benar dikonsumsi masyarakat sehari-hari
                                </p>
                            </div>
                            <div>
                                <h5 class="font-semibold text-purple-700 dark:text-purple-200 mb-2">🧮 NBM (Ketersediaan Teoritis)</h5>
                                <p class="text-purple-600 dark:text-purple-300 text-sm">
                                    Data dari neraca perdagangan - berapa yang tersedia secara teoritis untuk dikonsumsi
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Section 2: Data Flow -->
        <section id="dataflow" class="mb-16">
            <div class="bg-white dark:bg-zinc-900 rounded-lg shadow-lg border border-neutral-200 dark:border-zinc-700 p-8">
                <h2 class="text-3xl font-bold text-neutral-900 dark:text-white mb-6">🔄 Alur Data dalam Sistem</h2>
                
                <div class="space-y-8">
                    <!-- Step 1: Data Collection -->
                    <div class="bg-neutral-50 dark:bg-zinc-800 rounded-lg p-6">
                        <h3 class="text-xl font-semibold text-neutral-900 dark:text-white mb-4">1️⃣ Pengumpulan Data (Collection)</h3>
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <div>
                                <p class="text-neutral-700 dark:text-zinc-300 mb-4">
                                    Data Susenas dikumpulkan oleh BPS melalui survei rumah tangga yang dilakukan secara berkala. 
                                    Admin dapat menginput data konsumsi per kapita untuk berbagai komoditas pangan.
                                </p>
                                
                                <div class="bg-neutral-800 dark:bg-zinc-900 rounded-lg p-4 text-green-400 font-mono text-sm overflow-x-auto">
                                    <div class="flex justify-between items-center mb-2">
                                        <span class="text-neutral-400">📁 SusenasManagement.php</span>
                                        <a href="https://github.com/send0moka/basis-data-konsumsi-pangan/tree/main/app/Livewire/Admin/SusenasManagement.php" 
                                           target="_blank" 
                                           class="text-blue-400 hover:text-blue-300 text-xs">
                                            🔗 View Source
                                        </a>
                                    </div>
<pre>// Validation rules untuk data Susenas
protected $rules = [
    'kelompok_id' => 'required|exists:kelompok_bps,id',
    'komoditi_id' => 'required|exists:komoditi_bps,id',
    'tahun' => 'required|integer|min:1993|max:2030',
    'konsumsi_perkapita' => 'required|numeric|min:0',
    'pengeluaran_perkapita' => 'nullable|numeric|min:0',
    // ... komponen lainnya
];</pre>
                                </div>
                            </div>
                            
                            <div class="bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-700 rounded-lg p-4">
                                <h4 class="font-semibold text-emerald-900 dark:text-emerald-300 mb-2">📝 Format Data Susenas</h4>
                                <ul class="text-emerald-800 dark:text-emerald-200 text-sm space-y-1">
                                    <li>• <strong>Konsumsi:</strong> Kg/kapita/tahun</li>
                                    <li>• <strong>Pengeluaran:</strong> Rupiah/kapita/tahun</li>
                                    <li>• <strong>Cakupan:</strong> Data nasional</li>
                                    <li>• <strong>Periode:</strong> Tahunan (1993-2023)</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Step 2: Data Processing -->
                    <div class="bg-neutral-50 dark:bg-zinc-800 rounded-lg p-6">
                        <h3 class="text-xl font-semibold text-neutral-900 dark:text-white mb-4">2️⃣ Pemrosesan Data (Processing)</h3>
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <div>
                                <p class="text-neutral-700 dark:text-zinc-300 mb-4">
                                    Data yang sudah divalidasi disimpan ke database dengan struktur yang terintegrasi 
                                    dengan sistem kelompok dan komoditas BPS.
                                </p>
                                
                                <div class="bg-neutral-800 dark:bg-zinc-900 rounded-lg p-4 text-green-400 font-mono text-sm overflow-x-auto">
                                    <div class="flex justify-between items-center mb-2">
                                        <span class="text-neutral-400">📁 Susenas Model</span>
                                        <a href="https://github.com/send0moka/basis-data-konsumsi-pangan/tree/main/app/Models/Susenas.php" 
                                           target="_blank" 
                                           class="text-blue-400 hover:text-blue-300 text-xs">
                                            🔗 View Source
                                        </a>
                                    </div>
<pre>// Model dengan relationships
public function kelompokBps() {
    return $this->belongsTo(KelompokBps::class);
}

public function komoditiBps() {
    return $this->belongsTo(KomoditiBps::class);
}</pre>
                                </div>
                            </div>
                            
                            <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-700 rounded-lg p-4">
                                <h4 class="font-semibold text-blue-900 dark:text-blue-300 mb-2">🗃️ Struktur Database</h4>
                                <div class="text-blue-800 dark:text-blue-200 text-sm space-y-1">
                                    <div><strong>Tabel:</strong> susenas</div>
                                    <div><strong>Primary Key:</strong> id</div>
                                    <div><strong>Foreign Keys:</strong> kelompok_bps_id, komoditi_bps_id</div>
                                    <div><strong>Unique:</strong> kelompok + komoditi + tahun</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Step 3: Analysis -->
                    <div class="bg-neutral-50 dark:bg-zinc-800 rounded-lg p-6">
                        <h3 class="text-xl font-semibold text-neutral-900 dark:text-white mb-4">3️⃣ Analisis Data (Analysis)</h3>
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <div>
                                <p class="text-neutral-700 dark:text-zinc-300 mb-4">
                                    Sistem dapat menganalisis tren konsumsi, perbandingan antar periode, 
                                    dan menghasilkan insights untuk pengambilan keputusan.
                                </p>
                                
                                <div class="bg-neutral-800 dark:bg-zinc-900 rounded-lg p-4 text-green-400 font-mono text-sm overflow-x-auto">
                                    <div class="flex justify-between items-center mb-2">
                                        <span class="text-neutral-400">📁 Analysis Logic</span>
                                        <a href="https://github.com/send0moka/basis-data-konsumsi-pangan/tree/main/app/Livewire/Admin/SusenasManagement.php#L100" 
                                           target="_blank" 
                                           class="text-blue-400 hover:text-blue-300 text-xs">
                                            🔗 View Source
                                        </a>
                                    </div>
<pre>// Analisis tren konsumsi
public function calculateTrend($data) {
    $years = collect($data)->pluck('tahun')->sort();
    $consumptions = collect($data)->pluck('konsumsi_perkapita');
    
    // Hitung rata-rata perubahan tahunan
    $avgChange = $consumptions->avg();
    
    return [
        'trend' => $avgChange > 0 ? 'Meningkat' : 'Menurun',
        'avg_consumption' => round($avgChange, 2)
    ];
}</pre>
                                </div>
                            </div>
                            
                            <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-700 rounded-lg p-4">
                                <h4 class="font-semibold text-green-900 dark:text-green-300 mb-2">📈 Jenis Analisis</h4>
                                <ul class="text-green-800 dark:text-green-200 text-sm space-y-1">
                                    <li>• <strong>Tren Temporal:</strong> Perubahan dari waktu ke waktu</li>
                                    <li>• <strong>Perbandingan:</strong> Antar komoditas atau periode</li>
                                    <li>• <strong>Rata-rata:</strong> Konsumsi per kelompok pangan</li>
                                    <li>• <strong>Proyeksi:</strong> Prediksi konsumsi masa depan</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Step 4: Output & Reporting -->
                    <div class="bg-neutral-50 dark:bg-zinc-800 rounded-lg p-6">
                        <h3 class="text-xl font-semibold text-neutral-900 dark:text-white mb-4">4️⃣ Output & Pelaporan (Reporting)</h3>
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <div>
                                <p class="text-neutral-700 dark:text-zinc-300 mb-4">
                                    Data Susenas dapat diekspor dalam berbagai format untuk keperluan 
                                    penelitian, analisis kebijakan, dan publikasi.
                                </p>
                                
                                <div class="bg-neutral-800 dark:bg-zinc-900 rounded-lg p-4 text-green-400 font-mono text-sm overflow-x-auto">
                                    <div class="flex justify-between items-center mb-2">
                                        <span class="text-neutral-400">📁 Export Functionality</span>
                                        <a href="https://github.com/send0moka/basis-data-konsumsi-pangan/tree/main/app/Exports/SusenasExport.php" 
                                           target="_blank" 
                                           class="text-blue-400 hover:text-blue-300 text-xs">
                                            🔗 View Source
                                        </a>
                                    </div>
<pre>// Export data Susenas
public function headings(): array {
    return [
        'Kelompok BPS', 'Komoditi BPS', 'Tahun',
        'Konsumsi Per Kapita (kg/tahun)',
        'Pengeluaran Per Kapita (Rp/tahun)',
        'Tren Konsumsi'
    ];
}</pre>
                                </div>
                            </div>
                            
                            <div class="bg-purple-50 dark:bg-purple-900/20 border border-purple-200 dark:border-purple-700 rounded-lg p-4">
                                <h4 class="font-semibold text-purple-900 dark:text-purple-300 mb-2">📊 Format Output</h4>
                                <ul class="text-purple-800 dark:text-purple-200 text-sm space-y-1">
                                    <li>• <strong>Dashboard:</strong> Visualisasi interaktif</li>
                                    <li>• <strong>Excel:</strong> Data tabular untuk analisis</li>
                                    <li>• <strong>PDF:</strong> Laporan formal</li>
                                    <li>• <strong>API:</strong> Integrasi dengan sistem lain</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Section 3: Detailed Analysis -->
        <section id="calculation" class="mb-16">
            <div class="bg-white dark:bg-zinc-900 rounded-lg shadow-lg border border-neutral-200 dark:border-zinc-700 p-8">
                <h2 class="text-3xl font-bold text-neutral-900 dark:text-white mb-6">⚡ Detail Analisis Susenas</h2>
                
                <div class="space-y-8">
                    <!-- Analysis Methods -->
                    <div class="bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-700 rounded-lg p-6">
                        <h3 class="text-xl font-semibold text-emerald-900 dark:text-emerald-300 mb-4">📐 Metode Analisis Data</h3>
                        
                        <div class="space-y-4">
                            <div class="bg-white dark:bg-zinc-900 rounded-lg p-4 border-l-4 border-emerald-500">
                                <h4 class="font-semibold text-neutral-900 dark:text-white mb-2">1. Konsumsi Per Kapita Tahunan</h4>
                                <div class="bg-neutral-100 dark:bg-zinc-800 rounded p-3 font-mono text-sm text-neutral-800 dark:text-zinc-200">
                                    Konsumsi = Total Konsumsi RT ÷ Jumlah Anggota RT
                                </div>
                                <p class="text-neutral-600 dark:text-zinc-400 text-sm mt-2">Rata-rata konsumsi per orang per tahun dalam kilogram</p>
                            </div>

                            <div class="bg-white dark:bg-zinc-900 rounded-lg p-4 border-l-4 border-blue-500">
                                <h4 class="font-semibold text-neutral-900 dark:text-white mb-2">2. Tren Konsumsi Temporal</h4>
                                <div class="bg-neutral-100 dark:bg-zinc-800 rounded p-3 font-mono text-sm text-neutral-800 dark:text-zinc-200">
                                    Tren = (Konsumsi_akhir - Konsumsi_awal) ÷ Jumlah_tahun
                                </div>
                                <p class="text-neutral-600 dark:text-zinc-400 text-sm mt-2">Perubahan konsumsi dari waktu ke waktu</p>
                            </div>

                            <div class="bg-white dark:bg-zinc-900 rounded-lg p-4 border-l-4 border-orange-500">
                                <h4 class="font-semibold text-neutral-900 dark:text-white mb-2">3. Analisis Pengeluaran</h4>
                                <div class="bg-neutral-100 dark:bg-zinc-800 rounded p-3 font-mono text-sm text-neutral-800 dark:text-zinc-200">
                                    Harga_implisit = Pengeluaran ÷ Konsumsi
                                </div>
                                <p class="text-neutral-600 dark:text-zinc-400 text-sm mt-2">Estimasi harga rata-rata yang dibayar konsumen</p>
                            </div>

                            <div class="bg-white dark:bg-zinc-900 rounded-lg p-4 border-l-4 border-purple-500">
                                <h4 class="font-semibold text-neutral-900 dark:text-white mb-2">4. Perbandingan Antar Periode</h4>
                                <div class="bg-neutral-100 dark:bg-zinc-800 rounded p-3 font-mono text-sm text-neutral-800 dark:text-zinc-200">
                                    Perubahan_% = ((Nilai_baru - Nilai_lama) ÷ Nilai_lama) × 100
                                </div>
                                <p class="text-neutral-600 dark:text-zinc-400 text-sm mt-2">Persentase perubahan konsumsi antar tahun</p>
                            </div>
                        </div>
                    </div>

                    <!-- Example Analysis -->
                    <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-700 rounded-lg p-6">
                        <h3 class="text-xl font-semibold text-blue-900 dark:text-blue-300 mb-4">🧮 Contoh Analisis: Beras 2020-2023 (Data Asli SUSENAS)</h3>
                        
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <div>
                                <h4 class="font-semibold text-blue-800 dark:text-blue-300 mb-3">📊 Data Konsumsi Aktual</h4>
                                <div class="space-y-2 text-sm">
                                    <div class="flex justify-between">
                                        <span class="text-blue-700 dark:text-blue-200">2020:</span>
                                        <span class="font-mono text-blue-800 dark:text-blue-100">82.13 kg/kapita/tahun</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-blue-700 dark:text-blue-200">2021:</span>
                                        <span class="font-mono text-blue-800 dark:text-blue-100">51.54 kg/kapita/tahun</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-blue-700 dark:text-blue-200">2022:</span>
                                        <span class="font-mono text-blue-800 dark:text-blue-100">64.64 kg/kapita/tahun</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-blue-700 dark:text-blue-200">2023:</span>
                                        <span class="font-mono text-blue-800 dark:text-blue-100">40.62 kg/kapita/tahun</span>
                                    </div>
                                </div>
                                <div class="mt-3 text-xs text-blue-600 dark:text-blue-300 bg-blue-100 dark:bg-blue-900/30 p-2 rounded">
                                    <strong>Sumber:</strong> SUSENAS BPS<br>
                                    <strong>Metode:</strong> Recall method (survei konsumsi rumah tangga)
                                </div>
                            </div>
                            
                            <div>
                                <h4 class="font-semibold text-blue-800 dark:text-blue-300 mb-3">⚡ Hasil Analisis</h4>
                                <div class="space-y-3 text-sm">
                                    <div class="bg-white dark:bg-zinc-800 rounded p-3">
                                        <div class="font-semibold text-emerald-700 dark:text-emerald-300 mb-1">Rata-rata Konsumsi:</div>
                                        <div class="font-mono text-emerald-800 dark:text-emerald-200">(82.13 + 51.54 + 64.64 + 40.62) ÷ 4 = 59.73 kg/kapita/tahun</div>
                                    </div>
                                    <div class="bg-white dark:bg-zinc-800 rounded p-3">
                                        <div class="font-semibold text-red-700 dark:text-red-300 mb-1">Tren Konsumsi:</div>
                                        <div class="font-mono text-red-800 dark:text-red-200">(40.62 - 82.13) ÷ 3 = -13.84 kg/tahun (Menurun Drastis)</div>
                                    </div>
                                    <div class="bg-white dark:bg-zinc-800 rounded p-3">
                                        <div class="font-semibold text-red-700 dark:text-red-300 mb-1">Perubahan 2020-2023:</div>
                                        <div class="font-mono text-red-800 dark:text-red-200">((40.62 - 82.13) ÷ 82.13) × 100 = -50.5%</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-6 bg-white dark:bg-zinc-800 rounded-lg p-4">
                            <h4 class="font-semibold text-blue-800 dark:text-blue-300 mb-2">📈 Interpretasi Hasil</h4>
                            <p class="text-blue-700 dark:text-blue-200 text-sm leading-relaxed">
                                Data SUSENAS menunjukkan <strong>penurunan konsumsi beras yang sangat signifikan</strong> sebesar 50.5% dalam periode 2020-2023. 
                                Rata-rata konsumsi hanya 59.73 kg/kapita/tahun atau <strong>163.6 gram per hari</strong>. 
                                <br><br>
                                <em>Catatan: Data ini mencerminkan konsumsi riil rumah tangga berdasarkan recall method. Penurunan drastis bisa disebabkan oleh 
                                diversifikasi pangan, perubahan metode survei, atau faktor-faktor ekonomi dan sosial pasca pandemi COVID-19.</em>
                            </p>
                            <div class="mt-3 p-3 bg-yellow-100 dark:bg-yellow-900/20 border border-yellow-300 dark:border-yellow-700 rounded">
                                <h5 class="font-semibold text-yellow-800 dark:text-yellow-300 text-sm mb-1">🔍 Perbedaan dengan NBM:</h5>
                                <p class="text-yellow-700 dark:text-yellow-200 text-xs">
                                    Konsumsi SUSENAS (59.73 kg/tahun) jauh lebih rendah dari ketersediaan NBM beras (17.1 kg/tahun untuk beras siap konsumsi). 
                                    Ini normal karena SUSENAS mengukur konsumsi aktual, sedangkan NBM mengukur ketersediaan teoritis.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Code Implementation -->
                    <div class="bg-neutral-50 dark:bg-zinc-800 rounded-lg p-6">
                        <h3 class="text-xl font-semibold text-neutral-900 dark:text-white mb-4">💻 Implementasi dalam Kode</h3>
                        
                        <div class="bg-neutral-800 dark:bg-zinc-900 rounded-lg p-6 text-green-400 font-mono text-sm overflow-x-auto">
                            <div class="flex justify-between items-center mb-4">
                                <span class="text-neutral-400">📁 app/Livewire/Admin/SusenasManagement.php</span>
                                <a href="https://github.com/send0moka/basis-data-konsumsi-pangan/tree/main/app/Livewire/Admin/SusenasManagement.php#L150" 
                                   target="_blank" 
                                   class="text-blue-400 hover:text-blue-300 text-xs">
                                    🔗 View Full Source
                                </a>
                            </div>
<pre>/**
 * Analisis data Susenas
 * @param array $data - Data konsumsi per tahun
 * @return array - Hasil analisis
 */
public function analyzeSusenas($data) {
    $consumptions = collect($data)->pluck('konsumsi_perkapita');
    $years = collect($data)->pluck('tahun');
    
    // 1. Hitung rata-rata konsumsi
    $avgConsumption = $consumptions->avg();
    
    // 2. Hitung tren (slope)
    $firstYear = $years->min();
    $lastYear = $years->max();
    $firstConsumption = $data->where('tahun', $firstYear)->first()['konsumsi_perkapita'];
    $lastConsumption = $data->where('tahun', $lastYear)->first()['konsumsi_perkapita'];
    
    $yearSpan = $lastYear - $firstYear;
    $trend = $yearSpan > 0 ? ($lastConsumption - $firstConsumption) / $yearSpan : 0;
    
    // 3. Hitung persentase perubahan
    $percentageChange = $firstConsumption > 0 
        ? (($lastConsumption - $firstConsumption) / $firstConsumption) * 100 
        : 0;
    
    // 4. Return hasil analisis
    return [
        'avg_consumption' => round($avgConsumption, 2),
        'trend_per_year' => round($trend, 3),
        'percentage_change' => round($percentageChange, 2),
        'trend_direction' => $trend > 0 ? 'Meningkat' : 'Menurun',
        'consumption_per_day' => round($avgConsumption / 365, 3)
    ];
}</pre>
                        </div>
                        
                        <div class="mt-4 bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-700 rounded p-4">
                            <h4 class="font-semibold text-emerald-900 dark:text-emerald-300 mb-2">🔍 Penjelasan Kode</h4>
                            <ul class="text-emerald-800 dark:text-emerald-200 text-sm space-y-1">
                                <li>• <strong>Line 8:</strong> Menggunakan Laravel Collection untuk operasi data</li>
                                <li>• <strong>Line 11:</strong> Menghitung rata-rata dengan method avg()</li>
                                <li>• <strong>Line 18:</strong> Menghitung slope untuk tren linear</li>
                                <li>• <strong>Line 22:</strong> Persentase perubahan relatif terhadap nilai awal</li>
                                <li>• <strong>Line 30:</strong> Bonus konversi ke konsumsi harian</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Section 4: Demo & Test -->
        <section id="demo" class="mb-16">
            <div class="bg-white dark:bg-zinc-900 rounded-lg shadow-lg border border-neutral-200 dark:border-zinc-700 p-8">
                <h2 class="text-3xl font-bold text-neutral-900 dark:text-white mb-6">🧪 Demo Analisis Susenas</h2>
                
                <div class="bg-gradient-to-r from-teal-50 to-cyan-50 dark:from-teal-900/30 dark:to-cyan-900/30 border border-teal-200 dark:border-teal-700 rounded-lg p-6 mb-8">
                    <h3 class="text-xl font-semibold text-teal-900 dark:text-teal-300 mb-3">🎮 Interactive Analyzer</h3>
                    <p class="text-teal-800 dark:text-teal-200 mb-4">
                        Masukkan data konsumsi per kapita untuk beberapa tahun dan lihat analisis tren secara real-time!
                    </p>
                </div>

                <!-- Analyzer Form -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8" x-data="susenasAnalyzer()">
                    <!-- Input Form -->
                    <div class="lg:col-span-1">
                        <div class="bg-neutral-50 dark:bg-zinc-800 rounded-lg p-6 sticky top-24">
                            <h3 class="text-lg font-semibold text-neutral-900 dark:text-white mb-6">📝 Input Data Konsumsi</h3>
                            
                            <form @submit.prevent="analyzeData" class="space-y-4">
                                <!-- Year Inputs -->
                                <div class="space-y-3">
                                    <h4 class="font-medium text-emerald-700 dark:text-emerald-300 border-b border-emerald-200 dark:border-emerald-700 pb-1">📅 Data per Tahun (kg/kapita/tahun)</h4>
                                    
                                    <template x-for="(year, index) in years" :key="index">
                                        <div class="flex space-x-2">
                                            <div class="w-20">
                                                <input type="number" 
                                                       x-model="year.tahun"
                                                       @input="analyzeData"
                                                       min="1993" 
                                                       max="2030"
                                                       placeholder="Tahun"
                                                       class="w-full px-2 py-2 border border-neutral-300 dark:border-zinc-600 bg-white dark:bg-zinc-900 text-neutral-900 dark:text-white rounded-md focus:outline-none focus:ring-2 focus:ring-emerald-500 text-sm">
                                            </div>
                                            <div class="flex-1">
                                                <input type="number" 
                                                       x-model="year.konsumsi"
                                                       @input="analyzeData"
                                                       step="0.1" 
                                                       min="0"
                                                       placeholder="Konsumsi (kg)"
                                                       class="w-full px-3 py-2 border border-neutral-300 dark:border-zinc-600 bg-white dark:bg-zinc-900 text-neutral-900 dark:text-white rounded-md focus:outline-none focus:ring-2 focus:ring-emerald-500 text-sm">
                                            </div>
                                            <button type="button" 
                                                    @click="removeYear(index)"
                                                    x-show="years.length > 2"
                                                    class="px-2 py-2 bg-red-500 text-white rounded-md hover:bg-red-600 text-sm">
                                                ✕
                                            </button>
                                        </div>
                                    </template>
                                </div>

                                <!-- Action Buttons -->
                                <div class="space-y-2">
                                    <button type="button" 
                                            @click="addYear()"
                                            x-show="years.length < 8"
                                            class="w-full bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors">
                                        ➕ Tambah Tahun
                                    </button>
                                    
                                    <div class="flex space-x-2">
                                        <button type="button" 
                                                @click="loadExample()"
                                                class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors">
                                            📊 Load Contoh
                                        </button>
                                        <button type="button" 
                                                @click="resetForm()"
                                                class="flex-1 bg-neutral-600 hover:bg-neutral-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors">
                                            🔄 Reset
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Results -->
                    <div class="lg:col-span-2">
                        <div class="space-y-6">
                            <!-- Analysis Results -->
                            <div class="bg-emerald-50 dark:bg-emerald-900/20 rounded-lg border border-emerald-200 dark:border-emerald-700 p-6">
                                <h3 class="text-lg font-semibold text-emerald-900 dark:text-emerald-300 mb-4">⚡ Hasil Analisis</h3>
                                
                                <div class="space-y-4">
                                    <div class="bg-white dark:bg-zinc-800 rounded-lg p-4">
                                        <div class="flex justify-between items-center">
                                            <span class="font-medium text-blue-700 dark:text-blue-300">Rata-rata Konsumsi:</span>
                                            <span class="font-mono text-lg text-blue-800 dark:text-blue-200" x-text="formatNumber(results.avg_consumption) + ' kg/tahun'"></span>
                                        </div>
                                        <div class="text-sm text-neutral-600 dark:text-zinc-400 mt-1">
                                            = <span x-text="formatNumber(results.consumption_per_day * 1000)"></span> gram per hari
                                        </div>
                                    </div>

                                    <div class="bg-white dark:bg-zinc-800 rounded-lg p-4 border-l-4" 
                                         :class="results.trend_per_year > 0 ? 'border-green-500' : 'border-red-500'">
                                        <div class="flex justify-between items-center">
                                            <span class="font-medium text-purple-700 dark:text-purple-300">Tren Konsumsi:</span>
                                            <span class="font-mono text-lg font-bold" 
                                                  :class="results.trend_per_year > 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400'"
                                                  x-text="(results.trend_per_year > 0 ? '+' : '') + formatNumber(results.trend_per_year, 3) + ' kg/tahun'"></span>
                                        </div>
                                        <div class="text-sm text-neutral-600 dark:text-zinc-400 mt-1">
                                            Status: <span x-text="results.trend_direction" 
                                                         :class="results.trend_per_year > 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400'"></span>
                                        </div>
                                    </div>

                                    <div class="bg-white dark:bg-zinc-800 rounded-lg p-4">
                                        <div class="flex justify-between items-center">
                                            <span class="font-medium text-orange-700 dark:text-orange-300">Perubahan Total:</span>
                                            <span class="font-mono text-lg font-bold" 
                                                  :class="results.percentage_change > 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400'"
                                                  x-text="(results.percentage_change > 0 ? '+' : '') + formatNumber(results.percentage_change, 2) + '%'"></span>
                                        </div>
                                        <div class="text-sm text-neutral-600 dark:text-zinc-400 mt-1">
                                            Dari tahun awal ke tahun akhir
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Data Visualization -->
                            <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-200 dark:border-blue-700 p-6">
                                <h3 class="text-lg font-semibold text-blue-900 dark:text-blue-300 mb-4">📊 Visualisasi Data</h3>
                                
                                <div class="space-y-3">
                                    <template x-for="(year, index) in validYears" :key="index">
                                        <div class="bg-white dark:bg-zinc-800 rounded-lg p-3">
                                            <div class="flex justify-between items-center mb-2">
                                                <span class="font-medium text-neutral-700 dark:text-zinc-300" x-text="year.tahun"></span>
                                                <span class="font-mono text-neutral-900 dark:text-white" x-text="formatNumber(year.konsumsi) + ' kg'"></span>
                                            </div>
                                            <div class="w-full bg-neutral-200 dark:bg-zinc-700 rounded-full h-2">
                                                <div class="bg-emerald-500 h-2 rounded-full transition-all duration-500" 
                                                     :style="'width: ' + Math.min((year.konsumsi / maxConsumption) * 100, 100) + '%'"></div>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </div>

                            <!-- Interpretation -->
                            <div class="bg-yellow-50 dark:bg-yellow-900/20 rounded-lg border border-yellow-200 dark:border-yellow-700 p-6" 
                                 x-show="validYears.length >= 2">
                                <h3 class="text-lg font-semibold text-yellow-900 dark:text-yellow-300 mb-3">📈 Interpretasi Hasil</h3>
                                <div class="space-y-3">
                                    <div x-show="results.trend_per_year > 2">
                                        <div class="flex items-center space-x-2">
                                            <span class="text-green-600 text-xl">📈</span>
                                            <span class="font-medium text-green-800 dark:text-green-300">Tren Meningkat Signifikan</span>
                                        </div>
                                        <p class="text-green-700 dark:text-green-200 text-sm mt-1">
                                            Konsumsi mengalami peningkatan yang cukup signifikan. Ini bisa mengindikasikan peningkatan akses pangan atau perubahan preferensi konsumen.
                                        </p>
                                    </div>
                                    <div x-show="results.trend_per_year > 0 && results.trend_per_year <= 2">
                                        <div class="flex items-center space-x-2">
                                            <span class="text-blue-600 text-xl">📊</span>
                                            <span class="font-medium text-blue-800 dark:text-blue-300">Tren Meningkat Moderat</span>
                                        </div>
                                        <p class="text-blue-700 dark:text-blue-200 text-sm mt-1">
                                            Konsumsi mengalami peningkatan yang stabil dan terkendali.
                                        </p>
                                    </div>
                                    <div x-show="results.trend_per_year < 0 && results.trend_per_year >= -2">
                                        <div class="flex items-center space-x-2">
                                            <span class="text-orange-600 text-xl">📉</span>
                                            <span class="font-medium text-orange-800 dark:text-orange-300">Tren Menurun Moderat</span>
                                        </div>
                                        <p class="text-orange-700 dark:text-orange-200 text-sm mt-1">
                                            Konsumsi mengalami penurunan. Perlu dianalisis apakah ini karena diversifikasi pangan atau faktor ekonomi.
                                        </p>
                                    </div>
                                    <div x-show="results.trend_per_year < -2">
                                        <div class="flex items-center space-x-2">
                                            <span class="text-red-600 text-xl">📉</span>
                                            <span class="font-medium text-red-800 dark:text-red-300">Tren Menurun Signifikan</span>
                                        </div>
                                        <p class="text-red-700 dark:text-red-200 text-sm mt-1">
                                            Konsumsi mengalami penurunan yang signifikan. Perlu investigasi lebih lanjut tentang akses pangan dan kondisi ekonomi masyarakat.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Final Section: Try the Real System -->
        <section class="bg-gradient-to-r from-emerald-600 to-teal-700 dark:from-emerald-800 dark:to-teal-900 text-white rounded-lg p-8 text-center">
            <h2 class="text-3xl font-bold mb-4">🚀 Coba Sistem Sesungguhnya!</h2>
            <p class="text-xl text-emerald-100 dark:text-emerald-200 mb-8">
                Sekarang Anda sudah memahami bagaimana analisis Susenas bekerja. Saatnya mencoba sistem yang sesungguhnya!
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('admin.susenas') }}" 
                   class="bg-white text-emerald-700 px-8 py-3 rounded-lg font-semibold hover:bg-emerald-50 dark:hover:bg-neutral-100 transition duration-300 inline-flex items-center justify-center">
                    📊 Kelola Data Susenas
                </a>
                <a href="{{ route('konsumsi.laporan-susenas') }}" 
                   class="border-2 border-white text-white px-8 py-3 rounded-lg font-semibold hover:bg-white hover:text-emerald-700 dark:hover:text-emerald-800 transition duration-300 inline-flex items-center justify-center">
                    📈 Lihat Laporan Susenas
                </a>
            </div>
        </section>
    </div>

    <!-- Alpine.js Component for Analyzer -->
    <script>
        function susenasAnalyzer() {
            return {
                years: [
                    { tahun: 2020, konsumsi: 0 },
                    { tahun: 2021, konsumsi: 0 }
                ],
                results: {
                    avg_consumption: 0,
                    trend_per_year: 0,
                    percentage_change: 0,
                    trend_direction: 'Stabil',
                    consumption_per_day: 0
                },
                
                get validYears() {
                    return this.years.filter(year => 
                        year.tahun && year.konsumsi && 
                        year.tahun >= 1993 && year.tahun <= 2030 && 
                        year.konsumsi > 0
                    ).sort((a, b) => a.tahun - b.tahun);
                },
                
                get maxConsumption() {
                    const consumptions = this.validYears.map(year => parseFloat(year.konsumsi));
                    return Math.max(...consumptions, 100); // minimum 100 for visualization
                },
                
                analyzeData() {
                    const validData = this.validYears;
                    
                    if (validData.length < 2) {
                        this.results = {
                            avg_consumption: 0,
                            trend_per_year: 0,
                            percentage_change: 0,
                            trend_direction: 'Data tidak cukup',
                            consumption_per_day: 0
                        };
                        return;
                    }
                    
                    // Calculate average consumption
                    const consumptions = validData.map(year => parseFloat(year.konsumsi));
                    const avgConsumption = consumptions.reduce((a, b) => a + b, 0) / consumptions.length;
                    
                    // Calculate trend
                    const firstYear = validData[0];
                    const lastYear = validData[validData.length - 1];
                    const yearSpan = lastYear.tahun - firstYear.tahun;
                    const trend = yearSpan > 0 ? (lastYear.konsumsi - firstYear.konsumsi) / yearSpan : 0;
                    
                    // Calculate percentage change
                    const percentageChange = firstYear.konsumsi > 0 
                        ? ((lastYear.konsumsi - firstYear.konsumsi) / firstYear.konsumsi) * 100 
                        : 0;
                    
                    this.results = {
                        avg_consumption: avgConsumption,
                        trend_per_year: trend,
                        percentage_change: percentageChange,
                        trend_direction: trend > 0.1 ? 'Meningkat' : (trend < -0.1 ? 'Menurun' : 'Stabil'),
                        consumption_per_day: avgConsumption / 365
                    };
                },
                
                addYear() {
                    const lastYear = Math.max(...this.years.map(y => y.tahun || 2020));
                    this.years.push({
                        tahun: lastYear + 1,
                        konsumsi: 0
                    });
                },
                
                removeYear(index) {
                    if (this.years.length > 2) {
                        this.years.splice(index, 1);
                        this.analyzeData();
                    }
                },
                
                loadExample() {
                    // Example data for rice consumption 2020-2023 (actual SUSENAS data)
                    this.years = [
                        { tahun: 2020, konsumsi: 82.13 },
                        { tahun: 2021, konsumsi: 51.54 },
                        { tahun: 2022, konsumsi: 64.64 },
                        { tahun: 2023, konsumsi: 40.62 }
                    ];
                    this.analyzeData();
                },
                
                resetForm() {
                    this.years = [
                        { tahun: 2020, konsumsi: 0 },
                        { tahun: 2021, konsumsi: 0 }
                    ];
                    this.analyzeData();
                },
                
                formatNumber(num, decimals = 1) {
                    if (num === null || num === undefined || num === '' || isNaN(num)) return '0';
                    return parseFloat(num).toLocaleString('id-ID', { 
                        minimumFractionDigits: decimals,
                        maximumFractionDigits: decimals 
                    });
                },
                
                init() {
                    this.analyzeData();
                }
            }
        }
    </script>
</x-layouts.app>
