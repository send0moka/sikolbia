<x-layouts.akademisi>
    <!-- Welcome Card -->
    <div class="bg-gradient-to-r from-purple-500 to-purple-700 dark:from-purple-600 dark:to-purple-800 rounded-lg shadow-lg p-6 text-white mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold mb-2">Selamat Datang, {{ Auth::user()->name }}!</h2>
                <p class="text-purple-100">Akses Riset & Data Ketahanan Pangan Nasional Indonesia</p>
                <p class="text-sm text-purple-200 mt-1">Role: <span class="font-semibold">Akademisi</span> • Akses: Read-Only & Export</p>
            </div>
            <div class="hidden md:block">
                <svg class="w-24 h-24 text-purple-300 opacity-50" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z"/>
                </svg>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <div class="bg-white dark:bg-neutral-800 rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-green-500 rounded-md p-3">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                </div>
                <div class="ml-5">
                    <p class="text-neutral-500 dark:text-neutral-400 text-sm">Data NBM</p>
                    <p class="text-neutral-900 dark:text-white text-2xl font-semibold">Riset</p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-neutral-800 rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-yellow-500 rounded-md p-3">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <div class="ml-5">
                    <p class="text-neutral-500 dark:text-neutral-400 text-sm">Publikasi</p>
                    <p class="text-neutral-900 dark:text-white text-2xl font-semibold">Export</p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-neutral-800 rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-purple-500 rounded-md p-3">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"/>
                    </svg>
                </div>
                <div class="ml-5">
                    <p class="text-neutral-500 dark:text-neutral-400 text-sm">Prediksi ML</p>
                    <p class="text-neutral-900 dark:text-white text-2xl font-semibold">Analisis</p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-neutral-800 rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-blue-500 rounded-md p-3">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                </div>
                <div class="ml-5">
                    <p class="text-neutral-500 dark:text-neutral-400 text-sm">Dataset</p>
                    <p class="text-neutral-900 dark:text-white text-2xl font-semibold">Penelitian</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Information Panel -->
    <div class="bg-purple-50 dark:bg-purple-900/20 border border-purple-200 dark:border-purple-800 rounded-lg p-6 mb-6">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-6 w-6 text-purple-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                </svg>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-purple-800 dark:text-purple-300">Informasi Akses Akademisi</h3>
                <div class="mt-2 text-sm text-purple-700 dark:text-purple-400">
                    <p>Sebagai pengguna <strong>Akademisi</strong>, Anda memiliki akses untuk:</p>
                    <ul class="list-disc list-inside mt-2 space-y-1">
                        <li><strong>Melihat</strong> seluruh data Konsumsi Pangan, NBM, Lahan, Benih & Pupuk, dan Iklim OptDPI</li>
                        <li><strong>Export data</strong> dalam format Excel/CSV untuk keperluan riset & publikasi</li>
                        <li><strong>Menggunakan fitur prediksi NBM</strong> berbasis Machine Learning</li>
                        <li><strong>Mengakses statistik</strong> dan visualisasi data</li>
                    </ul>
                    <p class="mt-3 font-medium">Data yang dieksport dapat digunakan untuk:</p>
                    <ul class="list-disc list-inside space-y-1">
                        <li>Penelitian akademik & publikasi ilmiah</li>
                        <li>Skripsi, Tesis, dan Disertasi</li>
                        <li>Analisis statistik dan Machine Learning</li>
                    </ul>
                    <p class="mt-3 text-xs">Mohon cantumkan sumber data: <strong>"SIKOLBIA - Sistem Kolaborasi Ketahanan Pangan Nasional Indonesia"</strong> dalam publikasi Anda.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Links -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white dark:bg-neutral-800 rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-neutral-900 dark:text-white mb-4">Akses Data & Riset</h3>
            <div class="space-y-3">
                <a href="/ketersediaan/laporan-nbm" class="flex items-center p-3 bg-neutral-50 dark:bg-neutral-700 rounded-lg hover:bg-neutral-100 dark:hover:bg-neutral-600 transition">
                    <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                        <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/>
                    </svg>
                    <div>
                        <span class="block text-neutral-900 dark:text-white font-medium">Dataset NBM</span>
                        <span class="text-xs text-neutral-500 dark:text-neutral-400">Norma Batas Maksimal Konsumsi</span>
                    </div>
                </a>
                <a href="#" class="flex items-center p-3 bg-neutral-50 dark:bg-neutral-700 rounded-lg hover:bg-neutral-100 dark:hover:bg-neutral-600 transition">
                    <svg class="w-5 h-5 text-yellow-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M3 3a1 1 0 000 2v8a2 2 0 002 2h2.586l-1.293 1.293a1 1 0 101.414 1.414L10 15.414l2.293 2.293a1 1 0 001.414-1.414L12.414 15H15a2 2 0 002-2V5a1 1 0 100-2H3zm11.707 4.707a1 1 0 00-1.414-1.414L10 9.586 8.707 8.293a1 1 0 00-1.414 0l-2 2a1 1 0 101.414 1.414L8 10.414l1.293 1.293a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <div>
                        <span class="block text-neutral-900 dark:text-white font-medium">Data Lahan Pertanian</span>
                        <span class="text-xs text-neutral-500 dark:text-neutral-400">Luas & produktivitas lahan</span>
                    </div>
                </a>
                <a href="#" class="flex items-center p-3 bg-neutral-50 dark:bg-neutral-700 rounded-lg hover:bg-neutral-100 dark:hover:bg-neutral-600 transition">
                    <svg class="w-5 h-5 text-purple-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M2 6a2 2 0 012-2h12a2 2 0 012 2v2a2 2 0 100 4v2a2 2 0 01-2 2H4a2 2 0 01-2-2v-2a2 2 0 100-4V6z"/>
                    </svg>
                    <div>
                        <span class="block text-neutral-900 dark:text-white font-medium">Saprodi (Benih & Pupuk)</span>
                        <span class="text-xs text-neutral-500 dark:text-neutral-400">Sarana produksi pertanian</span>
                    </div>
                </a>
                <a href="#" class="flex items-center p-3 bg-neutral-50 dark:bg-neutral-700 rounded-lg hover:bg-neutral-100 dark:hover:bg-neutral-600 transition">
                    <svg class="w-5 h-5 text-blue-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                    </svg>
                    <div>
                        <span class="block text-neutral-900 dark:text-white font-medium">Iklim OptDPI</span>
                        <span class="text-xs text-neutral-500 dark:text-neutral-400">Data curah hujan & iklim</span>
                    </div>
                </a>
            </div>
        </div>

        <div class="bg-white dark:bg-neutral-800 rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-neutral-900 dark:text-white mb-4">Informasi Akun</h3>
            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-sm text-neutral-600 dark:text-neutral-400">Nama:</span>
                    <span class="text-sm font-medium text-neutral-900 dark:text-white">{{ Auth::user()->name }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-neutral-600 dark:text-neutral-400">Email:</span>
                    <span class="text-sm font-medium text-neutral-900 dark:text-white">{{ Auth::user()->email }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-neutral-600 dark:text-neutral-400">Role:</span>
                    <span class="text-sm font-medium text-purple-600 dark:text-purple-400">Akademisi</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-neutral-600 dark:text-neutral-400">Status:</span>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300">
                        Aktif
                    </span>
                </div>
            </div>

            <div class="mt-6 pt-6 border-t border-neutral-200 dark:border-neutral-700">
                <h4 class="text-sm font-medium text-neutral-900 dark:text-white mb-3">Petunjuk Sitasi Data</h4>
                <div class="bg-neutral-50 dark:bg-neutral-700 rounded p-3 text-xs text-neutral-600 dark:text-neutral-300">
                    <p class="font-mono">Sumber: SIKOLBIA - Sistem Kolaborasi Ketahanan Pangan Nasional Indonesia. Diakses: [{{ date('Y') }}]</p>
                </div>
                <a href="#" class="mt-3 inline-block text-sm text-purple-600 dark:text-purple-400 hover:underline">Ubah Password →</a>
                </div>
            </div>
        </div>
    </div>
</x-layouts.akademisi>
