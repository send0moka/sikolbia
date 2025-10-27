<x-layouts.pemerintah title="Dashboard Pemerintah - {{ config('app.name') }}">
    <div class="px-6 py-8">
        <h1 class="text-3xl font-bold text-neutral-900 dark:text-white mb-6">Dashboard Pemerintah</h1>

    <!-- Welcome Card -->
    <div class="mb-8 bg-gradient-to-r from-blue-500 to-blue-700 dark:from-blue-600 dark:to-blue-800 rounded-lg shadow-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold mb-2">Selamat Datang, {{ Auth::user()->name }}!</h2>
                <p class="text-blue-100">Akses Data Sistem Kolaborasi Ketahanan Pangan Bantul & Indonesia</p>
                <p class="text-sm text-blue-200 mt-1">Role: <span class="font-semibold">Pemerintah</span> • Akses: Read-Only</p>
            </div>
            <div class="hidden md:block">
                <svg class="w-24 h-24 text-blue-300 opacity-50" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z"/>
                </svg>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white dark:bg-neutral-800 rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-green-500 rounded-md p-3">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"/>
                    </svg>
                </div>
                <div class="ml-5">
                    <p class="text-neutral-500 dark:text-neutral-400 text-sm">Konsumsi Pangan</p>
                    <p class="text-neutral-900 dark:text-white text-2xl font-semibold">NBM</p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-neutral-800 rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-yellow-500 rounded-md p-3">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="ml-5">
                    <p class="text-neutral-500 dark:text-neutral-400 text-sm">Data Lahan</p>
                    <p class="text-neutral-900 dark:text-white text-2xl font-semibold">Pertanian</p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-neutral-800 rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-purple-500 rounded-md p-3">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                </div>
                <div class="ml-5">
                    <p class="text-neutral-500 dark:text-neutral-400 text-sm">Benih & Pupuk</p>
                    <p class="text-neutral-900 dark:text-white text-2xl font-semibold">Sarana</p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-neutral-800 rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-blue-500 rounded-md p-3">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z"/>
                    </svg>
                </div>
                <div class="ml-5">
                    <p class="text-neutral-500 dark:text-neutral-400 text-sm">Iklim OptDPI</p>
                    <p class="text-neutral-900 dark:text-white text-2xl font-semibold">Cuaca</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Information Panel -->
    <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-6 mb-8">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-6 w-6 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                </svg>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-blue-800 dark:text-blue-300">Informasi Akses</h3>
                <div class="mt-2 text-sm text-blue-700 dark:text-blue-400">
                    <p>Sebagai pengguna <strong>Pemerintah</strong>, Anda memiliki akses <strong>read-only</strong> untuk:</p>
                    <ul class="list-disc list-inside mt-2 space-y-1">
                        <li>Melihat data Konsumsi Pangan & NBM</li>
                        <li>Melihat data Lahan Pertanian</li>
                        <li>Melihat data Benih & Pupuk</li>
                        <li>Melihat data Iklim OptDPI</li>
                        <li>Export data dalam format Excel/CSV</li>
                        <li>Menggunakan fitur prediksi NBM</li>
                    </ul>
                    <p class="mt-3 text-xs">Untuk akses edit/kelola data, silakan hubungi administrator.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Links -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white dark:bg-neutral-800 rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-neutral-900 dark:text-white mb-4">Akses Cepat</h3>
            <div class="space-y-3">
                <a href="/ketersediaan/laporan-nbm" class="flex items-center p-3 bg-neutral-50 dark:bg-neutral-700 rounded-lg hover:bg-neutral-100 dark:hover:bg-neutral-600 transition">
                    <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                        <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/>
                    </svg>
                    <span class="text-neutral-900 dark:text-white">Laporan NBM</span>
                </a>
                <a href="#" class="flex items-center p-3 bg-neutral-50 dark:bg-neutral-700 rounded-lg hover:bg-neutral-100 dark:hover:bg-neutral-600 transition">
                    <svg class="w-5 h-5 text-yellow-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M3 3a1 1 0 000 2v8a2 2 0 002 2h2.586l-1.293 1.293a1 1 0 101.414 1.414L10 15.414l2.293 2.293a1 1 0 001.414-1.414L12.414 15H15a2 2 0 002-2V5a1 1 0 100-2H3zm11.707 4.707a1 1 0 00-1.414-1.414L10 9.586 8.707 8.293a1 1 0 00-1.414 0l-2 2a1 1 0 101.414 1.414L8 10.414l1.293 1.293a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <span class="text-neutral-900 dark:text-white">Data Lahan</span>
                </a>
                <a href="#" class="flex items-center p-3 bg-neutral-50 dark:bg-neutral-700 rounded-lg hover:bg-neutral-100 dark:hover:bg-neutral-600 transition">
                    <svg class="w-5 h-5 text-purple-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M2 6a2 2 0 012-2h12a2 2 0 012 2v2a2 2 0 100 4v2a2 2 0 01-2 2H4a2 2 0 01-2-2v-2a2 2 0 100-4V6z"/>
                    </svg>
                    <span class="text-neutral-900 dark:text-white">Benih & Pupuk</span>
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
                    <span class="text-sm font-medium text-blue-600 dark:text-blue-400">Pemerintah</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-neutral-600 dark:text-neutral-400">Status:</span>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300">
                        Aktif
                    </span>
                </div>
                <div class="pt-3 border-t border-neutral-200 dark:border-neutral-700">
                    <a href="#" class="text-sm text-blue-600 dark:text-blue-400 hover:underline">Ubah Password →</a>
                </div>
            </div>
        </div>
    </div>
    </div>
</x-layouts.pemerintah>
