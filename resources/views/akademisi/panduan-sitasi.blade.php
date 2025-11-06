<x-layouts.akademisi>
    <!-- Page Header -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-neutral-900 dark:text-white">Panduan Sitasi Data</h1>
        <p class="text-neutral-600 dark:text-neutral-400 mt-2">Cara mensitasi data SIKOLBIA dalam publikasi akademik</p>
    </div>

    <!-- Panduan Sitasi -->
    <div class="bg-white dark:bg-neutral-800 rounded-lg shadow p-6 mb-6">
        <h2 class="text-xl font-semibold text-neutral-900 dark:text-white mb-4">Format Sitasi Standar</h2>
        
        <div class="space-y-6">
            <!-- APA Style -->
            <div class="border-l-4 border-purple-500 pl-4">
                <h3 class="font-semibold text-neutral-900 dark:text-white mb-2">APA Style (7th Edition)</h3>
                <div class="bg-neutral-50 dark:bg-neutral-700 rounded p-4 font-mono text-sm">
                    <p class="text-neutral-700 dark:text-neutral-300">
                        Kementerian Pertanian Republik Indonesia. ({{ date('Y') }}). <em>Neraca Bahan Makanan Indonesia</em>. SIKOLBIA - Sistem Kolaborasi Ketahanan Pangan Nasional Indonesia. Diakses pada {{ date('d F Y') }}, dari {{ url('/') }}
                    </p>
                </div>
            </div>

            <!-- IEEE Style -->
            <div class="border-l-4 border-blue-500 pl-4">
                <h3 class="font-semibold text-neutral-900 dark:text-white mb-2">IEEE Style</h3>
                <div class="bg-neutral-50 dark:bg-neutral-700 rounded p-4 font-mono text-sm">
                    <p class="text-neutral-700 dark:text-neutral-300">
                        Kementerian Pertanian RI, "Neraca Bahan Makanan Indonesia," SIKOLBIA - Sistem Kolaborasi Ketahanan Pangan Nasional Indonesia, {{ date('Y') }}. [Online]. Available: {{ url('/') }}. [Accessed: {{ date('d-M-Y') }}].
                    </p>
                </div>
            </div>

            <!-- Harvard Style -->
            <div class="border-l-4 border-green-500 pl-4">
                <h3 class="font-semibold text-neutral-900 dark:text-white mb-2">Harvard Style</h3>
                <div class="bg-neutral-50 dark:bg-neutral-700 rounded p-4 font-mono text-sm">
                    <p class="text-neutral-700 dark:text-neutral-300">
                        Kementerian Pertanian Republik Indonesia ({{ date('Y') }}) <em>Neraca Bahan Makanan Indonesia</em>. SIKOLBIA - Sistem Kolaborasi Ketahanan Pangan Nasional Indonesia. Available at: {{ url('/') }} (Accessed: {{ date('d F Y') }}).
                    </p>
                </div>
            </div>

            <!-- Vancouver Style -->
            <div class="border-l-4 border-yellow-500 pl-4">
                <h3 class="font-semibold text-neutral-900 dark:text-white mb-2">Vancouver Style</h3>
                <div class="bg-neutral-50 dark:bg-neutral-700 rounded p-4 font-mono text-sm">
                    <p class="text-neutral-700 dark:text-neutral-300">
                        Kementerian Pertanian Republik Indonesia. Neraca Bahan Makanan Indonesia [Internet]. Jakarta: SIKOLBIA; {{ date('Y') }} [cited {{ date('Y M d') }}]. Available from: {{ url('/') }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Informasi Tambahan -->
    <div class="bg-purple-50 dark:bg-purple-900/20 border border-purple-200 dark:border-purple-800 rounded-lg p-6 mb-6">
        <h2 class="text-lg font-semibold text-purple-900 dark:text-purple-100 mb-3">Informasi Penting</h2>
        <div class="space-y-2 text-purple-800 dark:text-purple-200 text-sm">
            <p><strong>Nama Sistem:</strong> SIKOLBIA - Sistem Kolaborasi Ketahanan Pangan Nasional Indonesia</p>
            <p><strong>Sumber Data:</strong> Kementerian Pertanian Republik Indonesia, Badan Pangan Nasional, Badan Pusat Statistik</p>
            <p><strong>Periode Data:</strong> 1993 - 2024</p>
            <p><strong>URL Sistem:</strong> {{ url('/') }}</p>
            <p><strong>Lisensi:</strong> Data untuk keperluan akademik dan penelitian</p>
        </div>
    </div>

    <!-- Contoh Penggunaan dalam Publikasi -->
    <div class="bg-white dark:bg-neutral-800 rounded-lg shadow p-6">
        <h2 class="text-xl font-semibold text-neutral-900 dark:text-white mb-4">Contoh Penggunaan dalam Publikasi</h2>
        
        <div class="space-y-4">
            <!-- Contoh dalam Teks -->
            <div>
                <h3 class="font-semibold text-neutral-800 dark:text-neutral-200 mb-2">Dalam Teks (In-text Citation)</h3>
                <div class="bg-neutral-50 dark:bg-neutral-700 rounded p-4">
                    <p class="text-neutral-700 dark:text-neutral-300 mb-2">
                        <strong>APA:</strong> Berdasarkan data Neraca Bahan Makanan Indonesia (Kementerian Pertanian RI, {{ date('Y') }}), konsumsi kalori per kapita...
                    </p>
                    <p class="text-neutral-700 dark:text-neutral-300">
                        <strong>IEEE:</strong> Data konsumsi pangan nasional [1] menunjukkan bahwa...
                    </p>
                </div>
            </div>

            <!-- Contoh dalam Tabel -->
            <div>
                <h3 class="font-semibold text-neutral-800 dark:text-neutral-200 mb-2">Dalam Tabel atau Gambar</h3>
                <div class="bg-neutral-50 dark:bg-neutral-700 rounded p-4">
                    <p class="text-neutral-700 dark:text-neutral-300 italic">
                        Sumber: Kementerian Pertanian RI ({{ date('Y') }}), SIKOLBIA - Sistem Kolaborasi Ketahanan Pangan Nasional Indonesia
                    </p>
                </div>
            </div>

            <!-- Ethical Guidelines -->
            <div>
                <h3 class="font-semibold text-neutral-800 dark:text-neutral-200 mb-2">Pedoman Etika Penelitian</h3>
                <ul class="list-disc list-inside space-y-1 text-neutral-700 dark:text-neutral-300">
                    <li>Selalu cantumkan sumber data dalam setiap publikasi</li>
                    <li>Jangan memodifikasi data tanpa penjelasan metodologi yang jelas</li>
                    <li>Hindari interpretasi yang menyesatkan dari data agregat</li>
                    <li>Gunakan data sesuai dengan tujuan akademik dan penelitian</li>
                    <li>Laporkan keterbatasan data jika ada dalam penelitian Anda</li>
                </ul>
            </div>
        </div>
    </div>
</x-layouts.akademisi>
