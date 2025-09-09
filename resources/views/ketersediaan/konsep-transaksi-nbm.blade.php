<x-layouts.app title="Konsep Transaksi NBM">
    <!-- Header Section -->
    <section class="bg-gradient-to-r from-blue-600 to-indigo-700 dark:from-blue-800 dark:to-indigo-900 text-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h1 class="text-4xl md:text-5xl font-bold mb-4">
                    🧮 Konsep Transaksi NBM
                </h1>
                <p class="text-xl md:text-2xl text-blue-100 dark:text-blue-200 mb-8">
                    Memahami Sistem Neraca Bahan Makanan dari Input hingga Output
                </p>
                <div class="bg-white/10 dark:bg-white/5 backdrop-blur-sm rounded-lg p-6 max-w-4xl mx-auto">
                    <p class="text-lg leading-relaxed">
                        Halaman ini menjelaskan secara detail bagaimana sistem transaksi NBM (Neraca Bahan Makanan) 
                        bekerja dalam aplikasi ini, mulai dari input data hingga menghasilkan output laporan 
                        yang dapat digunakan untuk analisis ketersediaan pangan.
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
                            :class="activeSection === 'overview' ? 'bg-blue-50 dark:bg-blue-900/50 border-blue-200 dark:border-blue-700 text-blue-700 dark:text-blue-300' : 'bg-neutral-50 dark:bg-zinc-800 border-neutral-200 dark:border-zinc-600 text-neutral-700 dark:text-zinc-300 hover:bg-neutral-100 dark:hover:bg-zinc-700'"
                            class="text-sm font-medium px-4 py-2 border rounded-lg transition-colors">
                        📖 Overview NBM
                    </button>
                    <button @click="activeSection = 'dataflow'; document.getElementById('dataflow').scrollIntoView({behavior: 'smooth'})"
                            :class="activeSection === 'dataflow' ? 'bg-blue-50 dark:bg-blue-900/50 border-blue-200 dark:border-blue-700 text-blue-700 dark:text-blue-300' : 'bg-neutral-50 dark:bg-zinc-800 border-neutral-200 dark:border-zinc-600 text-neutral-700 dark:text-zinc-300 hover:bg-neutral-100 dark:hover:bg-zinc-700'"
                            class="text-sm font-medium px-4 py-2 border rounded-lg transition-colors">
                        🔄 Alur Data
                    </button>
                    <button @click="activeSection = 'calculation'; document.getElementById('calculation').scrollIntoView({behavior: 'smooth'})"
                            :class="activeSection === 'calculation' ? 'bg-blue-50 dark:bg-blue-900/50 border-blue-200 dark:border-blue-700 text-blue-700 dark:text-blue-300' : 'bg-neutral-50 dark:bg-zinc-800 border-neutral-200 dark:border-zinc-600 text-neutral-700 dark:text-zinc-300 hover:bg-neutral-100 dark:hover:bg-zinc-700'"
                            class="text-sm font-medium px-4 py-2 border rounded-lg transition-colors">
                        ⚡ Perhitungan
                    </button>
                    <button @click="activeSection = 'demo'; document.getElementById('demo').scrollIntoView({behavior: 'smooth'})"
                            :class="activeSection === 'demo' ? 'bg-blue-50 dark:bg-blue-900/50 border-blue-200 dark:border-blue-700 text-blue-700 dark:text-blue-300' : 'bg-neutral-50 dark:bg-zinc-800 border-neutral-200 dark:border-zinc-600 text-neutral-700 dark:text-zinc-300 hover:bg-neutral-100 dark:hover:bg-zinc-700'"
                            class="text-sm font-medium px-4 py-2 border rounded-lg transition-colors">
                        🧪 Demo & Test
                    </button>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        
        <!-- Section 1: Overview NBM -->
        <section id="overview" class="mb-16">
            <div class="bg-white dark:bg-zinc-900 rounded-lg shadow-lg border border-neutral-200 dark:border-zinc-700 p-8">
                <h2 class="text-3xl font-bold text-neutral-900 dark:text-white mb-6">📖 Apa itu Transaksi NBM?</h2>
                
                <div class="space-y-6">
                    <div class="bg-blue-50 dark:bg-blue-900/20 border-l-4 border-blue-400 dark:border-blue-500 p-6 rounded-r-lg">
                        <h3 class="text-xl font-semibold text-blue-900 dark:text-blue-300 mb-3">🍚 Penjelasan Sederhana</h3>
                        <p class="text-blue-800 dark:text-blue-200 leading-relaxed">
                            Bayangkan Anda mengelola toko sembako besar. Setiap hari ada beras masuk (produksi), beras keluar (konsumsi), 
                            beras rusak (susut), dan beras yang tersisa (stok). <strong>NBM (Neraca Bahan Makanan)</strong> adalah 
                            sistem pencatatan yang membantu kita menghitung berapa sebenarnya ketersediaan beras per orang per hari.
                        </p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-700 rounded-lg p-6">
                            <h4 class="text-lg font-semibold text-green-800 dark:text-green-300 mb-3">✅ Yang Masuk (Supply)</h4>
                            <ul class="space-y-2 text-green-700 dark:text-green-200">
                                <li>• <strong>Produksi</strong>: Hasil panen lokal</li>
                                <li>• <strong>Impor</strong>: Barang dari luar negeri</li>
                                <li>• <strong>Stok Awal</strong>: Sisa tahun sebelumnya</li>
                            </ul>
                        </div>

                        <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-700 rounded-lg p-6">
                            <h4 class="text-lg font-semibold text-red-800 dark:text-red-300 mb-3">❌ Yang Keluar (Demand)</h4>
                            <ul class="space-y-2 text-red-700 dark:text-red-200">
                                <li>• <strong>Konsumsi</strong>: Dimakan masyarakat</li>
                                <li>• <strong>Ekspor</strong>: Dijual ke luar negeri</li>
                                <li>• <strong>Susut</strong>: Rusak/terbuang</li>
                                <li>• <strong>Pakan</strong>: Untuk ternak</li>
                                <li>• <strong>Bibit</strong>: Untuk tanam lagi</li>
                                <li>• <strong>Industri</strong>: Bahan baku pabrik</li>
                            </ul>
                        </div>
                    </div>

                    <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-700 rounded-lg p-6">
                        <h4 class="text-lg font-semibold text-yellow-800 dark:text-yellow-300 mb-3">🎯 Tujuan NBM</h4>
                        <p class="text-yellow-700 dark:text-yellow-200 leading-relaxed">
                            Dengan NBM, pemerintah bisa tahu: <em>"Apakah rakyat Indonesia cukup makan beras tahun ini?"</em> 
                            dan <em>"Berapa kg beras yang tersedia per orang per hari?"</em>. Data ini penting untuk 
                            perencanaan impor, ekspor, dan kebijakan pangan nasional.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Section 2: Data Flow -->
        <section id="dataflow" class="mb-16">
            <div class="bg-white dark:bg-zinc-900 rounded-lg shadow-lg border border-neutral-200 dark:border-zinc-700 p-8">
                <h2 class="text-3xl font-bold text-neutral-900 dark:text-white mb-6">🔄 Alur Data dalam Sistem</h2>
                
                <div class="space-y-8">
                    <!-- Step 1: Input Data -->
                    <div class="bg-neutral-50 dark:bg-zinc-800 rounded-lg p-6">
                        <h3 class="text-xl font-semibold text-neutral-900 dark:text-white mb-4">1️⃣ Input Data (Entry)</h3>
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <div>
                                <p class="text-neutral-700 dark:text-zinc-300 mb-4">
                                    Admin memasukkan data transaksi NBM melalui form yang tersedia. 
                                    Data ini berisi komponen-komponen neraca seperti produksi, impor, ekspor, dll.
                                </p>
                                
                                <div class="bg-neutral-800 dark:bg-zinc-900 rounded-lg p-4 text-green-400 font-mono text-sm overflow-x-auto">
                                    <div class="flex justify-between items-center mb-2">
                                        <span class="text-neutral-400">📁 TransaksiNbmManagement.php</span>
                                        <a href="https://github.com/send0moka/basis-data-konsumsi-pangan/tree/main/app/Livewire/Admin/TransaksiNbmManagement.php" 
                                           target="_blank" 
                                           class="text-blue-400 hover:text-blue-300 text-xs">
                                            🔗 View Source
                                        </a>
                                    </div>
<pre>// Validation rules untuk input data
protected $rules = [
    'kelompok_id' => 'required|exists:kelompok,id',
    'komoditi_id' => 'required|exists:komoditi,id', 
    'tahun' => 'required|integer|min:1990|max:2030',
    'produksi' => 'nullable|numeric|min:0',
    'impor' => 'nullable|numeric|min:0',
    'ekspor' => 'nullable|numeric|min:0',
    // ... komponen lainnya
];</pre>
                                </div>
                            </div>
                            
                            <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-700 rounded-lg p-4">
                                <h4 class="font-semibold text-blue-900 dark:text-blue-300 mb-2">💡 Tips untuk User</h4>
                                <ul class="text-blue-800 dark:text-blue-200 text-sm space-y-1">
                                    <li>• Pastikan data dalam satuan ton</li>
                                    <li>• Tahun harus antara 1990-2030</li>
                                    <li>• Kosongkan field jika tidak ada data</li>
                                    <li>• Gunakan angka positif saja</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Step 2: Processing -->
                    <div class="bg-neutral-50 dark:bg-zinc-800 rounded-lg p-6">
                        <h3 class="text-xl font-semibold text-neutral-900 dark:text-white mb-4">2️⃣ Proses Penyimpanan (Storage)</h3>
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <div>
                                <p class="text-neutral-700 dark:text-zinc-300 mb-4">
                                    Data yang sudah divalidasi kemudian disimpan ke database. 
                                    Sistem juga melakukan pengecekan duplikasi untuk kombinasi kelompok-komoditi-tahun.
                                </p>
                                
                                <div class="bg-neutral-800 dark:bg-zinc-900 rounded-lg p-4 text-green-400 font-mono text-sm overflow-x-auto">
                                    <div class="flex justify-between items-center mb-2">
                                        <span class="text-neutral-400">📁 TransaksiNbm Model</span>
                                        <a href="https://github.com/send0moka/basis-data-konsumsi-pangan/tree/main/app/Models/TransaksiNbm.php" 
                                           target="_blank" 
                                           class="text-blue-400 hover:text-blue-300 text-xs">
                                            🔗 View Source
                                        </a>
                                    </div>
<pre>// Model dengan relationships
public function kelompok() {
    return $this->belongsTo(Kelompok::class);
}

public function komoditi() {
    return $this->belongsTo(Komoditi::class);
}</pre>
                                </div>
                            </div>
                            
                            <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-700 rounded-lg p-4">
                                <h4 class="font-semibold text-green-900 dark:text-green-300 mb-2">🗃️ Database Structure</h4>
                                <div class="text-green-800 dark:text-green-200 text-sm space-y-1">
                                    <div><strong>Tabel:</strong> transaksi_nbm</div>
                                    <div><strong>Primary Key:</strong> id</div>
                                    <div><strong>Foreign Keys:</strong> kelompok_id, komoditi_id</div>
                                    <div><strong>Unique:</strong> kelompok + komoditi + tahun</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Step 3: Calculation -->
                    <div class="bg-neutral-50 dark:bg-zinc-800 rounded-lg p-6">
                        <h3 class="text-xl font-semibold text-neutral-900 dark:text-white mb-4">3️⃣ Perhitungan Otomatis (Calculation)</h3>
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <div>
                                <p class="text-neutral-700 dark:text-zinc-300 mb-4">
                                    Setelah data tersimpan, sistem secara otomatis menghitung ketersediaan bersih dan 
                                    ketersediaan per kapita menggunakan rumus NBM standar.
                                </p>
                                
                                <div class="bg-neutral-800 dark:bg-zinc-900 rounded-lg p-4 text-green-400 font-mono text-sm overflow-x-auto">
                                    <div class="flex justify-between items-center mb-2">
                                        <span class="text-neutral-400">📁 Calculation Logic</span>
                                        <a href="https://github.com/send0moka/basis-data-konsumsi-pangan/tree/main/app/Livewire/Admin/TransaksiNbmManagement.php#L150" 
                                           target="_blank" 
                                           class="text-blue-400 hover:text-blue-300 text-xs">
                                            🔗 View Source
                                        </a>
                                    </div>
<pre>// Rumus Ketersediaan Bersih
$ketersediaan_bersih = 
    ($produksi + $impor + $stok_awal) - 
    ($ekspor + $pakan + $bibit + $industri + $susut);

// Rumus Per Kapita (kg/orang/tahun)
$ketersediaan_perkapita = 
    ($ketersediaan_bersih * 1000) / $populasi;</pre>
                                </div>
                            </div>
                            
                            <div class="bg-orange-50 dark:bg-orange-900/20 border border-orange-200 dark:border-orange-700 rounded-lg p-4">
                                <h4 class="font-semibold text-orange-900 dark:text-orange-300 mb-2">⚙️ Parameter Perhitungan</h4>
                                <ul class="text-orange-800 dark:text-orange-200 text-sm space-y-1">
                                    <li>• <strong>Populasi:</strong> 273 juta jiwa (Indonesia 2023)</li>
                                    <li>• <strong>Satuan Input:</strong> Ton</li>
                                    <li>• <strong>Satuan Output:</strong> Kg/orang/tahun</li>
                                    <li>• <strong>Konversi:</strong> 1 ton = 1000 kg</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Step 4: Output -->
                    <div class="bg-neutral-50 dark:bg-zinc-800 rounded-lg p-6">
                        <h3 class="text-xl font-semibold text-neutral-900 dark:text-white mb-4">4️⃣ Output & Export (Report)</h3>
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <div>
                                <p class="text-neutral-700 dark:text-zinc-300 mb-4">
                                    Data yang sudah dihitung dapat dilihat dalam bentuk tabel, dicetak, atau 
                                    diekspor ke Excel untuk analisis lebih lanjut.
                                </p>
                                
                                <div class="bg-neutral-800 dark:bg-zinc-900 rounded-lg p-4 text-green-400 font-mono text-sm overflow-x-auto">
                                    <div class="flex justify-between items-center mb-2">
                                        <span class="text-neutral-400">📁 Export Functionality</span>
                                        <a href="https://github.com/send0moka/basis-data-konsumsi-pangan/tree/main/app/Exports/TransaksiNbmExport.php" 
                                           target="_blank" 
                                           class="text-blue-400 hover:text-blue-300 text-xs">
                                            🔗 View Source
                                        </a>
                                    </div>
<pre>// Export ke Excel dengan formatting
public function headings(): array {
    return [
        'Kelompok', 'Komoditi', 'Tahun',
        'Produksi (ton)', 'Impor (ton)', 
        'Ketersediaan Bersih (ton)',
        'Per Kapita (kg/orang/tahun)'
    ];
}</pre>
                                </div>
                            </div>
                            
                            <div class="bg-purple-50 dark:bg-purple-900/20 border border-purple-200 dark:border-purple-700 rounded-lg p-4">
                                <h4 class="font-semibold text-purple-900 dark:text-purple-300 mb-2">📊 Format Output</h4>
                                <ul class="text-purple-800 dark:text-purple-200 text-sm space-y-1">
                                    <li>• <strong>Tabel HTML:</strong> View di browser</li>
                                    <li>• <strong>Excel (.xlsx):</strong> Download file</li>
                                    <li>• <strong>Print:</strong> Cetak langsung</li>
                                    <li>• <strong>API:</strong> JSON untuk integrasi</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Section 3: Detailed Calculation -->
        <section id="calculation" class="mb-16">
            <div class="bg-white dark:bg-zinc-900 rounded-lg shadow-lg border border-neutral-200 dark:border-zinc-700 p-8">
                <h2 class="text-3xl font-bold text-neutral-900 dark:text-white mb-6">⚡ Detail Perhitungan NBM</h2>
                
                <div class="space-y-8">
                    <!-- Formula Explanation -->
                    <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-700 rounded-lg p-6">
                        <h3 class="text-xl font-semibold text-blue-900 dark:text-blue-300 mb-4">📐 Rumus Dasar NBM</h3>
                        
                        <div class="space-y-4">
                            <div class="bg-white dark:bg-zinc-900 rounded-lg p-4 border-l-4 border-blue-500">
                                <h4 class="font-semibold text-neutral-900 dark:text-white mb-2">1. Ketersediaan Kotor (Supply)</h4>
                                <div class="bg-neutral-100 dark:bg-zinc-800 rounded p-3 font-mono text-sm text-neutral-800 dark:text-zinc-200">
                                    Supply = Produksi + Impor + Stok Awal
                                </div>
                                <p class="text-neutral-600 dark:text-zinc-400 text-sm mt-2">Total semua yang masuk ke sistem pangan</p>
                            </div>

                            <div class="bg-white dark:bg-zinc-900 rounded-lg p-4 border-l-4 border-red-500">
                                <h4 class="font-semibold text-neutral-900 dark:text-white mb-2">2. Total Penggunaan (Demand)</h4>
                                <div class="bg-neutral-100 dark:bg-zinc-800 rounded p-3 font-mono text-sm text-neutral-800 dark:text-zinc-200">
                                    Demand = Ekspor + Pakan + Bibit + Industri + Susut
                                </div>
                                <p class="text-neutral-600 dark:text-zinc-400 text-sm mt-2">Total semua yang keluar dari sistem pangan</p>
                            </div>

                            <div class="bg-white dark:bg-zinc-900 rounded-lg p-4 border-l-4 border-green-500">
                                <h4 class="font-semibold text-neutral-900 dark:text-white mb-2">3. Ketersediaan Bersih</h4>
                                <div class="bg-neutral-100 dark:bg-zinc-800 rounded p-3 font-mono text-sm text-neutral-800 dark:text-zinc-200">
                                    Ketersediaan Bersih = Supply - Demand
                                </div>
                                <p class="text-neutral-600 dark:text-zinc-400 text-sm mt-2">Yang tersisa untuk konsumsi masyarakat</p>
                            </div>

                            <div class="bg-white dark:bg-zinc-900 rounded-lg p-4 border-l-4 border-purple-500">
                                <h4 class="font-semibold text-neutral-900 dark:text-white mb-2">4. Ketersediaan Per Kapita</h4>
                                <div class="bg-neutral-100 dark:bg-zinc-800 rounded p-3 font-mono text-sm text-neutral-800 dark:text-zinc-200">
                                    Per Kapita = (Ketersediaan Bersih × 1000) ÷ Populasi
                                </div>
                                <p class="text-neutral-600 dark:text-zinc-400 text-sm mt-2">Dalam satuan kg per orang per tahun</p>
                            </div>
                        </div>
                    </div>

                    <!-- Example Calculation -->
                    <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-700 rounded-lg p-6">
                        <h3 class="text-xl font-semibold text-green-900 dark:text-green-300 mb-4">🧮 Contoh Perhitungan: Beras 2023</h3>
                        
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <div>
                                <h4 class="font-semibold text-green-800 dark:text-green-300 mb-3">📊 Data Input</h4>
                                <div class="space-y-2 text-sm">
                                    <div class="flex justify-between">
                                        <span class="text-green-700 dark:text-green-200">Produksi:</span>
                                        <span class="font-mono text-green-800 dark:text-green-100">5.271 ribu ton</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-green-700 dark:text-green-200">Impor:</span>
                                        <span class="font-mono text-green-800 dark:text-green-100">3.982 ribu ton</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-green-700 dark:text-green-200">Stok Awal:</span>
                                        <span class="font-mono text-green-800 dark:text-green-100">0.184 ribu ton</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-green-700 dark:text-green-200">Ekspor:</span>
                                        <span class="font-mono text-green-800 dark:text-green-100">1.707 ribu ton</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-green-700 dark:text-green-200">Pakan:</span>
                                        <span class="font-mono text-green-800 dark:text-green-100">0.259 ribu ton</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-green-700 dark:text-green-200">Bibit:</span>
                                        <span class="font-mono text-green-800 dark:text-green-100">0.320 ribu ton</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-green-700 dark:text-green-200">Industri:</span>
                                        <span class="font-mono text-green-800 dark:text-green-100">1.890 ribu ton</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-green-700 dark:text-green-200">Susut:</span>
                                        <span class="font-mono text-green-800 dark:text-green-100">0.591 ribu ton</span>
                                    </div>
                                </div>
                            </div>
                            
                            <div>
                                <h4 class="font-semibold text-green-800 dark:text-green-300 mb-3">⚡ Proses Perhitungan</h4>
                                <div class="space-y-3 text-sm">
                                    <div class="bg-white dark:bg-zinc-800 rounded p-3">
                                        <div class="font-semibold text-blue-700 dark:text-blue-300 mb-1">Supply Total:</div>
                                        <div class="font-mono text-blue-800 dark:text-blue-200">5.271 + 3.982 + 0.184 = 9.437 ribu ton</div>
                                    </div>
                                    <div class="bg-white dark:bg-zinc-800 rounded p-3">
                                        <div class="font-semibold text-red-700 dark:text-red-300 mb-1">Demand Total:</div>
                                        <div class="font-mono text-red-800 dark:text-red-200">1.707 + 0.259 + 0.320 + 1.890 + 0.591 = 4.767 ribu ton</div>
                                    </div>
                                    <div class="bg-white dark:bg-zinc-800 rounded p-3">
                                        <div class="font-semibold text-green-700 dark:text-green-300 mb-1">Ketersediaan Bersih:</div>
                                        <div class="font-mono text-green-800 dark:text-green-200">9.437 - 4.767 = 4.670 ribu ton</div>
                                    </div>
                                    <div class="bg-white dark:bg-zinc-800 rounded p-3">
                                        <div class="font-semibold text-purple-700 dark:text-purple-300 mb-1">Per Kapita:</div>
                                        <div class="font-mono text-purple-800 dark:text-purple-200">(4.670 × 1000) ÷ 273.5 = 17.1 kg/orang/tahun</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-6 bg-white dark:bg-zinc-800 rounded-lg p-4">
                            <h4 class="font-semibold text-green-800 dark:text-green-300 mb-2">📈 Interpretasi Hasil</h4>
                            <p class="text-green-700 dark:text-green-200 text-sm leading-relaxed">
                                Hasil 17.1 kg/orang/tahun berarti setiap orang Indonesia memiliki ketersediaan beras 
                                sekitar <strong>0.047 kg per hari</strong> atau <strong>47 gram per hari</strong>.
                                <br><br>
                                <em>Catatan: Angka ini menunjukkan ketersediaan beras khusus dalam bentuk produk olahan 
                                (tidak termasuk gabah mentah), sesuai dengan klasifikasi NBM untuk beras siap konsumsi.</em>
                            </p>
                        </div>
                    </div>

                    <!-- Code Implementation -->
                    <div class="bg-neutral-50 dark:bg-zinc-800 rounded-lg p-6">
                        <h3 class="text-xl font-semibold text-neutral-900 dark:text-white mb-4">💻 Implementasi dalam Kode</h3>
                        
                        <div class="bg-neutral-800 dark:bg-zinc-900 rounded-lg p-6 text-green-400 font-mono text-sm overflow-x-auto">
                            <div class="flex justify-between items-center mb-4">
                                <span class="text-neutral-400">📁 app/Livewire/Admin/TransaksiNbmManagement.php</span>
                                <a href="https://github.com/send0moka/basis-data-konsumsi-pangan/tree/main/app/Livewire/Admin/TransaksiNbmManagement.php#L200" 
                                   target="_blank" 
                                   class="text-blue-400 hover:text-blue-300 text-xs">
                                    🔗 View Full Source
                                </a>
                            </div>
<pre>/**
 * Kalkulasi NBM berdasarkan rumus standar
 * @param array $data - Data input transaksi NBM
 * @return array - Hasil perhitungan
 */
public function calculateNBM($data) {
    // Konstanta populasi Indonesia 2023
    $populasi = 273500000; // 273.5 juta jiwa
    
    // 1. Hitung total supply (ketersediaan kotor)
    $supply = ($data['produksi'] ?? 0) + 
              ($data['impor'] ?? 0) + 
              ($data['stok_awal'] ?? 0);
    
    // 2. Hitung total demand (penggunaan)  
    $demand = ($data['ekspor'] ?? 0) +
              ($data['pakan'] ?? 0) +
              ($data['bibit'] ?? 0) +
              ($data['industri'] ?? 0) +
              ($data['susut'] ?? 0);
    
    // 3. Hitung ketersediaan bersih (net availability)
    $ketersediaan_bersih = $supply - $demand;
    
    // 4. Hitung per kapita (kg/orang/tahun)
    $ketersediaan_perkapita = $ketersediaan_bersih > 0 
        ? ($ketersediaan_bersih * 1000) / $populasi 
        : 0;
    
    // 5. Return hasil perhitungan
    return [
        'supply_total' => round($supply, 2),
        'demand_total' => round($demand, 2), 
        'ketersediaan_bersih' => round($ketersediaan_bersih, 2),
        'ketersediaan_perkapita' => round($ketersediaan_perkapita, 3),
        'per_hari' => round($ketersediaan_perkapita / 365, 3)
    ];
}</pre>
                        </div>
                        
                        <div class="mt-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-700 rounded p-4">
                            <h4 class="font-semibold text-blue-900 dark:text-blue-300 mb-2">🔍 Penjelasan Kode</h4>
                            <ul class="text-blue-800 dark:text-blue-200 text-sm space-y-1">
                                <li>• <strong>Line 6:</strong> Menggunakan data populasi resmi BPS 2023</li>
                                <li>• <strong>Line 9-11:</strong> Operator null coalescing (??) untuk handle data kosong</li>
                                <li>• <strong>Line 21:</strong> Konversi ton ke kg dengan mengalikan 1000</li>
                                <li>• <strong>Line 24:</strong> Pembulatan untuk kemudahan pembacaan</li>
                                <li>• <strong>Line 31:</strong> Bonus kalkulasi ketersediaan per hari</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Section 4: Demo & Test -->
        <section id="demo" class="mb-16">
            <div class="bg-white dark:bg-zinc-900 rounded-lg shadow-lg border border-neutral-200 dark:border-zinc-700 p-8">
                <h2 class="text-3xl font-bold text-neutral-900 dark:text-white mb-6">🧪 Demo Perhitungan NBM</h2>
                
                <div class="bg-gradient-to-r from-indigo-50 to-purple-50 dark:from-indigo-900/30 dark:to-purple-900/30 border border-indigo-200 dark:border-indigo-700 rounded-lg p-6 mb-8">
                    <h3 class="text-xl font-semibold text-indigo-900 dark:text-indigo-300 mb-3">🎮 Interactive Calculator</h3>
                    <p class="text-indigo-800 dark:text-indigo-200 mb-4">
                        Coba masukkan data Anda sendiri dan lihat bagaimana sistem menghitung NBM secara real-time!
                    </p>
                </div>

                <!-- Calculator Form -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8" x-data="nbmCalculator()">
                    <!-- Input Form -->
                    <div class="lg:col-span-1">
                        <div class="bg-neutral-50 dark:bg-zinc-800 rounded-lg p-6 sticky top-24">
                            <h3 class="text-lg font-semibold text-neutral-900 dark:text-white mb-6">📝 Input Data NBM</h3>
                            
                            <form @submit.prevent="calculateNBM" class="space-y-4">
                                <!-- Supply Section -->
                                <div class="space-y-3">
                                    <h4 class="font-medium text-green-700 dark:text-green-300 border-b border-green-200 dark:border-green-700 pb-1">📈 Supply (Masuk)</h4>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-neutral-700 dark:text-zinc-300 mb-1">Produksi (ton)</label>
                                        <input type="number" 
                                               x-model="input.produksi" 
                                               @input="calculateNBM"
                                               step="0.01" 
                                               min="0"
                                               class="w-full px-3 py-2 border border-neutral-300 dark:border-zinc-600 bg-white dark:bg-zinc-900 text-neutral-900 dark:text-white rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-neutral-700 dark:text-zinc-300 mb-1">Impor (ton)</label>
                                        <input type="number" 
                                               x-model="input.impor" 
                                               @input="calculateNBM"
                                               step="0.01" 
                                               min="0"
                                               class="w-full px-3 py-2 border border-neutral-300 dark:border-zinc-600 bg-white dark:bg-zinc-900 text-neutral-900 dark:text-white rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-neutral-700 dark:text-zinc-300 mb-1">Stok Awal (ton)</label>
                                        <input type="number" 
                                               x-model="input.stok_awal" 
                                               @input="calculateNBM"
                                               step="0.01" 
                                               min="0"
                                               class="w-full px-3 py-2 border border-neutral-300 dark:border-zinc-600 bg-white dark:bg-zinc-900 text-neutral-900 dark:text-white rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                                    </div>
                                </div>

                                <!-- Demand Section -->
                                <div class="space-y-3">
                                    <h4 class="font-medium text-red-700 dark:text-red-300 border-b border-red-200 dark:border-red-700 pb-1">📉 Demand (Keluar)</h4>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-neutral-700 dark:text-zinc-300 mb-1">Ekspor (ton)</label>
                                        <input type="number" 
                                               x-model="input.ekspor" 
                                               @input="calculateNBM"
                                               step="0.01" 
                                               min="0"
                                               class="w-full px-3 py-2 border border-neutral-300 dark:border-zinc-600 bg-white dark:bg-zinc-900 text-neutral-900 dark:text-white rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-neutral-700 dark:text-zinc-300 mb-1">Pakan (ton)</label>
                                        <input type="number" 
                                               x-model="input.pakan" 
                                               @input="calculateNBM"
                                               step="0.01" 
                                               min="0"
                                               class="w-full px-3 py-2 border border-neutral-300 dark:border-zinc-600 bg-white dark:bg-zinc-900 text-neutral-900 dark:text-white rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-neutral-700 dark:text-zinc-300 mb-1">Bibit (ton)</label>
                                        <input type="number" 
                                               x-model="input.bibit" 
                                               @input="calculateNBM"
                                               step="0.01" 
                                               min="0"
                                               class="w-full px-3 py-2 border border-neutral-300 dark:border-zinc-600 bg-white dark:bg-zinc-900 text-neutral-900 dark:text-white rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-neutral-700 dark:text-zinc-300 mb-1">Industri (ton)</label>
                                        <input type="number" 
                                               x-model="input.industri" 
                                               @input="calculateNBM"
                                               step="0.01" 
                                               min="0"
                                               class="w-full px-3 py-2 border border-neutral-300 dark:border-zinc-600 bg-white dark:bg-zinc-900 text-neutral-900 dark:text-white rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-neutral-700 dark:text-zinc-300 mb-1">Susut (ton)</label>
                                        <input type="number" 
                                               x-model="input.susut" 
                                               @input="calculateNBM"
                                               step="0.01" 
                                               min="0"
                                               class="w-full px-3 py-2 border border-neutral-300 dark:border-zinc-600 bg-white dark:bg-zinc-900 text-neutral-900 dark:text-white rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                                    </div>
                                </div>

                                <!-- Action Buttons -->
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
                            </form>
                        </div>
                    </div>

                    <!-- Results -->
                    <div class="lg:col-span-2">
                        <div class="space-y-6">
                            <!-- Calculation Process -->
                            <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-200 dark:border-blue-700 p-6">
                                <h3 class="text-lg font-semibold text-blue-900 dark:text-blue-300 mb-4">⚡ Proses Perhitungan</h3>
                                
                                <div class="space-y-4">
                                    <div class="bg-white dark:bg-zinc-800 rounded-lg p-4">
                                        <div class="flex justify-between items-center">
                                            <span class="font-medium text-green-700 dark:text-green-300">Total Supply:</span>
                                            <span class="font-mono text-lg text-green-800 dark:text-green-200" x-text="formatNumber(results.supply_total) + ' ton'"></span>
                                        </div>
                                        <div class="text-sm text-neutral-600 dark:text-zinc-400 mt-1">
                                            <span x-text="formatNumber(input.produksi)"></span> + 
                                            <span x-text="formatNumber(input.impor)"></span> + 
                                            <span x-text="formatNumber(input.stok_awal)"></span>
                                        </div>
                                    </div>

                                    <div class="bg-white dark:bg-zinc-800 rounded-lg p-4">
                                        <div class="flex justify-between items-center">
                                            <span class="font-medium text-red-700 dark:text-red-300">Total Demand:</span>
                                            <span class="font-mono text-lg text-red-800 dark:text-red-200" x-text="formatNumber(results.demand_total) + ' ton'"></span>
                                        </div>
                                        <div class="text-sm text-neutral-600 dark:text-zinc-400 mt-1">
                                            <span x-text="formatNumber(input.ekspor)"></span> + 
                                            <span x-text="formatNumber(input.pakan)"></span> + 
                                            <span x-text="formatNumber(input.bibit)"></span> + 
                                            <span x-text="formatNumber(input.industri)"></span> + 
                                            <span x-text="formatNumber(input.susut)"></span>
                                        </div>
                                    </div>

                                    <div class="bg-white dark:bg-zinc-800 rounded-lg p-4 border-l-4 border-purple-500">
                                        <div class="flex justify-between items-center">
                                            <span class="font-medium text-purple-700 dark:text-purple-300">Ketersediaan Bersih:</span>
                                            <span class="font-mono text-xl font-bold text-purple-900 dark:text-purple-200" x-text="formatNumber(results.ketersediaan_bersih) + ' ton'"></span>
                                        </div>
                                        <div class="text-sm text-neutral-600 dark:text-zinc-400 mt-1">
                                            <span x-text="formatNumber(results.supply_total)"></span> - 
                                            <span x-text="formatNumber(results.demand_total)"></span>
                                        </div>
                                    </div>

                                    <div class="bg-gradient-to-r from-green-50 to-blue-50 dark:from-green-900/20 dark:to-blue-900/20 rounded-lg p-4 border-l-4 border-green-500">
                                        <div class="flex justify-between items-center">
                                            <span class="font-medium text-green-700 dark:text-green-300">Per Kapita/Tahun:</span>
                                            <span class="font-mono text-xl font-bold text-green-900 dark:text-green-200" x-text="formatNumber(results.ketersediaan_perkapita, 3) + ' kg'"></span>
                                        </div>
                                        <div class="text-sm text-neutral-600 dark:text-zinc-400 mt-1">
                                            (<span x-text="formatNumber(results.ketersediaan_bersih)"></span> × 1000) ÷ 273,500,000
                                        </div>
                                        <div class="text-sm text-green-600 dark:text-green-300 mt-2 font-medium">
                                            = <span x-text="formatNumber(results.per_hari, 3)"></span> kg per orang per hari
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Interpretation -->
                            <div class="bg-yellow-50 dark:bg-yellow-900/20 rounded-lg border border-yellow-200 dark:border-yellow-700 p-6" x-show="results.ketersediaan_perkapita > 0">
                                <h3 class="text-lg font-semibold text-yellow-900 dark:text-yellow-300 mb-3">📊 Interpretasi Hasil</h3>
                                <div class="space-y-3">
                                    <div x-show="results.ketersediaan_perkapita >= 150">
                                        <div class="flex items-center space-x-2">
                                            <span class="text-green-600 text-xl">✅</span>
                                            <span class="font-medium text-green-800 dark:text-green-300">Ketersediaan Cukup</span>
                                        </div>
                                        <p class="text-green-700 dark:text-green-200 text-sm mt-1">
                                            Ketersediaan per kapita mencukupi kebutuhan konsumsi harian yang direkomendasikan.
                                        </p>
                                    </div>
                                    <div x-show="results.ketersediaan_perkapita >= 100 && results.ketersediaan_perkapita < 150">
                                        <div class="flex items-center space-x-2">
                                            <span class="text-yellow-600 text-xl">⚠️</span>
                                            <span class="font-medium text-yellow-800 dark:text-yellow-300">Ketersediaan Terbatas</span>
                                        </div>
                                        <p class="text-yellow-700 dark:text-yellow-200 text-sm mt-1">
                                            Ketersediaan cukup namun perlu pengawasan dan efisiensi distribusi.
                                        </p>
                                    </div>
                                    <div x-show="results.ketersediaan_perkapita < 100">
                                        <div class="flex items-center space-x-2">
                                            <span class="text-red-600 text-xl">❌</span>
                                            <span class="font-medium text-red-800 dark:text-red-300">Ketersediaan Kurang</span>
                                        </div>
                                        <p class="text-red-700 dark:text-red-200 text-sm mt-1">
                                            Ketersediaan tidak mencukupi kebutuhan. Diperlukan impor atau peningkatan produksi.
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
        <section class="bg-gradient-to-r from-indigo-600 to-purple-700 dark:from-indigo-800 dark:to-purple-900 text-white rounded-lg p-8 text-center">
            <h2 class="text-3xl font-bold mb-4">🚀 Coba Sistem Sesungguhnya!</h2>
            <p class="text-xl text-indigo-100 dark:text-indigo-200 mb-8">
                Sekarang Anda sudah memahami bagaimana NBM bekerja. Saatnya mencoba sistem yang sesungguhnya!
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('admin.transaksi-nbm') }}" 
                   class="bg-white text-indigo-700 px-8 py-3 rounded-lg font-semibold hover:bg-indigo-50 dark:hover:bg-neutral-100 transition duration-300 inline-flex items-center justify-center">
                    📊 Kelola Data NBM
                </a>
                <a href="{{ route('ketersediaan.laporan-nbm') }}" 
                   class="border-2 border-white text-white px-8 py-3 rounded-lg font-semibold hover:bg-white hover:text-indigo-700 dark:hover:text-indigo-800 transition duration-300 inline-flex items-center justify-center">
                    📈 Lihat Laporan NBM
                </a>
            </div>
        </section>
    </div>

    <!-- Alpine.js Component for Calculator -->
    <script>
        function nbmCalculator() {
            return {
                input: {
                    produksi: 0,
                    impor: 0,
                    stok_awal: 0,
                    ekspor: 0,
                    pakan: 0,
                    bibit: 0,
                    industri: 0,
                    susut: 0
                },
                results: {
                    supply_total: 0,
                    demand_total: 0,
                    ketersediaan_bersih: 0,
                    ketersediaan_perkapita: 0,
                    per_hari: 0
                },
                
                calculateNBM() {
                    const populasi = 273500000; // 273.5 juta jiwa
                    
                    // Calculate supply
                    this.results.supply_total = parseFloat(this.input.produksi || 0) + 
                                              parseFloat(this.input.impor || 0) + 
                                              parseFloat(this.input.stok_awal || 0);
                    
                    // Calculate demand
                    this.results.demand_total = parseFloat(this.input.ekspor || 0) +
                                              parseFloat(this.input.pakan || 0) +
                                              parseFloat(this.input.bibit || 0) +
                                              parseFloat(this.input.industri || 0) +
                                              parseFloat(this.input.susut || 0);
                    
                    // Calculate net availability
                    this.results.ketersediaan_bersih = this.results.supply_total - this.results.demand_total;
                    
                    // Calculate per capita
                    this.results.ketersediaan_perkapita = this.results.ketersediaan_bersih > 0 
                        ? (this.results.ketersediaan_bersih * 1000) / populasi 
                        : 0;
                    
                    // Calculate per day
                    this.results.per_hari = this.results.ketersediaan_perkapita / 365;
                },
                
                loadExample() {
                    // Example data for rice 2023 (consistent with laporan NBM)
                    this.input = {
                        produksi: 5271,      // 5.271 ribu ton (from database)
                        impor: 3982,         // 3.982 ribu ton 
                        stok_awal: 184,      // calculated from perubahan_stok
                        ekspor: 1707,        // 1.707 ribu ton
                        pakan: 259,          // 259 ribu ton
                        bibit: 320,          // 320 ribu ton
                        industri: 1890,      // 1890 ribu ton (diolah)
                        susut: 591           // 591 ribu ton (tercecer)
                    };
                    this.calculateNBM();
                },
                
                resetForm() {
                    this.input = {
                        produksi: 0,
                        impor: 0,
                        stok_awal: 0,
                        ekspor: 0,
                        pakan: 0,
                        bibit: 0,
                        industri: 0,
                        susut: 0
                    };
                    this.calculateNBM();
                },
                
                formatNumber(num, decimals = 2) {
                    if (num === null || num === undefined || num === '') return '0';
                    return parseFloat(num).toLocaleString('id-ID', { 
                        minimumFractionDigits: decimals,
                        maximumFractionDigits: decimals 
                    });
                },
                
                init() {
                    this.calculateNBM();
                }
            }
        }
    </script>
</x-layouts.app>
