<div>
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-neutral-900 dark:text-white">Dashboard Iklim & OPT DPI</h1>
        <p class="mt-1 text-sm text-neutral-600 dark:text-neutral-400">
            Selamat datang di sistem monitoring iklim dan organisme pengganggu tanaman
        </p>
    </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 lg:grid-cols-2 gap-4 lg:gap-6 mb-8">
            <!-- Current User Info -->
            <div class="bg-white dark:!bg-neutral-800 overflow-hidden shadow-lg rounded-lg border border-neutral-200 dark:border-neutral-700">
                <div class="p-4 lg:p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 lg:w-12 lg:h-12 bg-green-100 dark:bg-green-900 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 lg:w-6 lg:h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                        </div>
                        <div class="ml-3 lg:ml-4 min-w-0 flex-1">
                            <h3 class="text-base lg:text-lg font-semibold text-neutral-900 dark:text-white truncate">Status akun</h3>
                            <p class="text-2xl lg:text-3xl font-bold text-green-600 dark:text-green-400 truncate">{{ auth()->user()->name }}</p>
                            <p class="text-xs lg:text-sm text-neutral-500 dark:text-neutral-400 truncate">
                                {{ ucfirst(auth()->user()->roles->first()?->name ?? 'No Role') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Data Iklim -->
            <div class="bg-white dark:!bg-neutral-800 overflow-hidden shadow-lg rounded-lg border border-neutral-200 dark:border-neutral-700">
                <div class="p-4 lg:p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 lg:w-12 lg:h-12 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 lg:w-6 lg:h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                                </svg>
                            </div>
                        </div>
                        <div class="ml-3 lg:ml-4 min-w-0 flex-1">
                            <h3 class="text-base lg:text-lg font-semibold text-neutral-900 dark:text-white truncate">Total Data Iklim</h3>
                            <p class="text-2xl lg:text-3xl font-bold text-blue-600 dark:text-blue-400">{{ number_format($totalData) }}</p>
                            <p class="text-xs lg:text-sm text-neutral-500 dark:text-neutral-400 truncate">Data terdaftar</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Produksi -->
            <div class="bg-white dark:!bg-neutral-800 overflow-hidden shadow-lg rounded-lg border border-neutral-200 dark:border-neutral-700">
                <div class="p-4 lg:p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 lg:w-12 lg:h-12 bg-purple-100 dark:bg-purple-900 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 lg:w-6 lg:h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                </svg>
                            </div>
                        </div>
                        <div class="ml-3 lg:ml-4 min-w-0 flex-1">
                            <h3 class="text-base lg:text-lg font-semibold text-neutral-900 dark:text-white truncate">Total Topik</h3>
                            <p class="text-2xl lg:text-3xl font-bold text-purple-600 dark:text-purple-400">{{ number_format($totalTopik) }}</p>
                            <p class="text-xs lg:text-sm text-neutral-500 dark:text-neutral-400 truncate">Kategori topik</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Status Lahan -->
            <div class="bg-white dark:!bg-neutral-800 overflow-hidden shadow-lg rounded-lg border border-neutral-200 dark:border-neutral-700">
                <div class="p-4 lg:p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 lg:w-12 lg:h-12 bg-amber-100 dark:bg-amber-900 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 lg:w-6 lg:h-6 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                        </div>
                        <div class="ml-3 lg:ml-4 min-w-0 flex-1">
                            <h3 class="text-base lg:text-lg font-semibold text-neutral-900 dark:text-white truncate">Data Aktif</h3>
                            <p class="text-2xl lg:text-3xl font-bold text-amber-600 dark:text-amber-400">{{ $activePercent }}</p>
                            <p class="text-xs lg:text-sm text-neutral-500 dark:text-neutral-400 truncate">Status aktif</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Chart & Info Grid -->
        <div class="grid auto-rows-min gap-4 md:grid-cols-3 mb-6">
            <!-- Data Lahan Card -->
            <div class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
                <x-placeholder-pattern class="absolute inset-0 size-full stroke-neutral-900/20 dark:stroke-neutral-100/20" />
                <div class="absolute inset-0 p-4 flex items-center justify-center">
                    <div class="text-center">
                        <h3 class="text-lg font-semibold text-neutral-900 dark:text-white">Data Lahan</h3>
                        <p class="text-sm text-neutral-600 dark:text-neutral-400">Chart penggunaan lahan</p>
                    </div>
                </div>
            </div>

            <!-- Statistik Card -->
            <div class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
                <x-placeholder-pattern class="absolute inset-0 size-full stroke-neutral-900/20 dark:stroke-neutral-100/20" />
                <div class="absolute inset-0 p-4 flex items-center justify-center">
                    <div class="text-center">
                        <h3 class="text-lg font-semibold text-neutral-900 dark:text-white">Statistik</h3>
                        <p class="text-sm text-neutral-600 dark:text-neutral-400">Ringkasan statistik lahan</p>
                    </div>
                </div>
            </div>

            <!-- Laporan Card -->
            <div class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
                <x-placeholder-pattern class="absolute inset-0 size-full stroke-neutral-900/20 dark:stroke-neutral-100/20" />
                <div class="absolute inset-0 p-4 flex items-center justify-center">
                    <div class="text-center">
                        <h3 class="text-lg font-semibold text-neutral-900 dark:text-white">Laporan</h3>
                        <p class="text-sm text-neutral-600 dark:text-neutral-400">Laporan penggunaan lahan</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Description Section -->
        <div class="relative h-64 flex-1 overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
            <x-placeholder-pattern class="absolute inset-0 size-full stroke-neutral-900/20 dark:stroke-neutral-100/20" />
            <div class="absolute inset-0 p-4 flex items-center justify-center">
                <div class="text-center">
                    <h3 class="text-xl font-semibold text-neutral-900 dark:text-white mb-2">Sistem Manajemen Data Lahan</h3>
                    <p class="text-neutral-600 dark:text-neutral-400">Dashboard untuk monitoring dan pengelolaan data lahan pertanian</p>
                </div>
            </div>
        </div>

        <!-- Feature Cards Section -->
        <div class="mt-8">
            <h2 class="text-xl font-semibold text-neutral-900 dark:text-white mb-4">Fitur Utama</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Data Lahan Card -->
                <x-flux.card class="hover:shadow-lg transition-shadow">
                    <div class="p-6">
                        <div class="flex items-center space-x-4">
                            <div class="p-3 bg-green-100 dark:bg-green-900 rounded-lg">
                                <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-neutral-900 dark:text-white">Data Lahan</h3>
                                <p class="text-neutral-600 dark:text-neutral-400">Kelola data lahan pertanian</p>
                            </div>
                        </div>
                        <div class="mt-4">
                            <x-flux.link href="{{ route('admin.lahan.data') }}" class="text-green-600 hover:text-green-800 font-medium">
                                Akses Data →
                            </x-flux.link>
                        </div>
                    </div>
                </x-flux.card>

                <!-- Laporan Lahan Card -->
                <x-flux.card class="hover:shadow-lg transition-shadow">
                    <div class="p-6">
                        <div class="flex items-center space-x-4">
                            <div class="p-3 bg-blue-100 dark:bg-blue-900 rounded-lg">
                                <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-neutral-900 dark:text-white">Laporan Lahan</h3>
                                <p class="text-neutral-600 dark:text-neutral-400">Analisis dan laporan statistik</p>
                            </div>
                        </div>
                        <div class="mt-4">
                            <x-flux.link href="{{ route('admin.lahan.reports') }}" class="text-blue-600 hover:text-blue-800 font-medium">
                                Lihat Laporan →
                            </x-flux.link>
                        </div>
                    </div>
                </x-flux.card>

                <!-- Analisis Lahan Card -->
                <x-flux.card class="hover:shadow-lg transition-shadow">
                    <div class="p-6">
                        <div class="flex items-center space-x-4">
                            <div class="p-3 bg-purple-100 dark:bg-purple-900 rounded-lg">
                                <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-neutral-900 dark:text-white">Analisis Lahan</h3>
                                <p class="text-neutral-600 dark:text-neutral-400">Prediksi & tren lahan</p>
                            </div>
                        </div>
                        <div class="mt-4">
                            <x-flux.link href="{{ route('admin.lahan.analysis') }}" class="text-purple-600 hover:text-purple-800 font-medium">
                                Mulai Analisis →
                            </x-flux.link>
                        </div>
                    </div>
                </x-flux.card>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="mt-8">
            <h2 class="text-xl font-semibold text-neutral-900 dark:text-white mb-4">Aksi Cepat</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <x-flux.button variant="secondary" class="w-full justify-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Tambah Data Baru
                </x-flux.button>

                <x-flux.button variant="secondary" class="w-full justify-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                    </svg>
                    Import Data
                </x-flux.button>

                <x-flux.button variant="secondary" class="w-full justify-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4-4m0 0l-4 4m4-4v12"></path>
                    </svg>
                    Export Data
                </x-flux.button>

                <x-flux.button variant="secondary" class="w-full justify-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    Generate Laporan
                </x-flux.button>
            </div>
        </div>
    </div>
</div>