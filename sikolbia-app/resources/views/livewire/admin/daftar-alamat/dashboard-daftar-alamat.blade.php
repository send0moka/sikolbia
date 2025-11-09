<div>
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-neutral-900 dark:text-white">Dashboard Daftar Alamat</h1>
        <p class="mt-1 text-sm text-neutral-600 dark:text-neutral-400">
            Selamat datang di sistem manajemen daftar alamat terpadu
        </p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 lg:grid-cols-2 gap-4 lg:gap-6 mb-8">
        <!-- Total Alamat -->
        <div class="bg-white dark:!bg-neutral-800 overflow-hidden shadow-lg rounded-lg border border-neutral-200 dark:border-neutral-700">
            <div class="p-4 lg:p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 lg:w-12 lg:h-12 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 lg:w-6 lg:h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1 1 11.314 0z" />
                                <circle cx="12" cy="12" r="3" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <div class="text-sm font-medium text-neutral-500 dark:text-neutral-400 truncate">
                            Total Alamat
                        </div>
                        <div class="text-2xl font-bold text-neutral-900 dark:text-white">
                            {{ number_format($totalAlamat) }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Aktif -->
        <div class="bg-white dark:!bg-neutral-800 overflow-hidden shadow-lg rounded-lg border border-neutral-200 dark:border-neutral-700">
            <div class="p-4 lg:p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 lg:w-12 lg:h-12 bg-green-100 dark:bg-green-900 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 lg:w-6 lg:h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <div class="text-sm font-medium text-neutral-500 dark:text-neutral-400 truncate">
                            Alamat Aktif
                        </div>
                        <div class="text-2xl font-bold text-neutral-900 dark:text-white">
                            {{ number_format($totalAktif) }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total dengan Koordinat -->
        <div class="bg-white dark:!bg-neutral-800 overflow-hidden shadow-lg rounded-lg border border-neutral-200 dark:border-neutral-700">
            <div class="p-4 lg:p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 lg:w-12 lg:h-12 bg-purple-100 dark:bg-purple-900 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 lg:w-6 lg:h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <div class="text-sm font-medium text-neutral-500 dark:text-neutral-400 truncate">
                            Dengan Koordinat
                        </div>
                        <div class="text-2xl font-bold text-neutral-900 dark:text-white">
                            {{ number_format($totalWithCoordinates) }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Provinsi -->
        <div class="bg-white dark:!bg-neutral-800 overflow-hidden shadow-lg rounded-lg border border-neutral-200 dark:border-neutral-700">
            <div class="p-4 lg:p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 lg:w-12 lg:h-12 bg-orange-100 dark:bg-orange-900 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 lg:w-6 lg:h-6 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <div class="text-sm font-medium text-neutral-500 dark:text-neutral-400 truncate">
                            Total Provinsi
                        </div>
                        <div class="text-2xl font-bold text-neutral-900 dark:text-white">
                            {{ number_format($totalProvinsi) }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts and Recent Data -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Status Distribution -->
        <div class="bg-white dark:!bg-neutral-800 overflow-hidden shadow-lg rounded-lg border border-neutral-200 dark:border-neutral-700">
            <div class="p-6">
                <h3 class="text-lg font-medium text-neutral-900 dark:text-white mb-4">Distribusi Status</h3>
                <div class="space-y-3">
                    @foreach($statusStats as $status => $count)
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ (new \App\Models\DaftarAlamat(['status' => $status]))->status_badge }}">
                                    {{ $status }}
                                </span>
                            </div>
                            <span class="text-sm font-medium text-neutral-900 dark:text-white">{{ number_format($count) }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Top Provinsi -->
        <div class="bg-white dark:!bg-neutral-800 overflow-hidden shadow-lg rounded-lg border border-neutral-200 dark:border-neutral-700">
            <div class="p-6">
                <h3 class="text-lg font-medium text-neutral-900 dark:text-white mb-4">Top Provinsi</h3>
                <div class="space-y-3">
                    @foreach($kategoriStats as $provinsi => $count)
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-neutral-600 dark:text-neutral-400">{{ $provinsi }}</span>
                            <span class="text-sm font-medium text-neutral-900 dark:text-white">{{ number_format($count) }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Alamat and Top Wilayah -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Alamat -->
        <div class="bg-white dark:!bg-neutral-800 overflow-hidden shadow-lg rounded-lg border border-neutral-200 dark:border-neutral-700">
            <div class="p-6">
                <h3 class="text-lg font-medium text-neutral-900 dark:text-white mb-4">Alamat Terbaru</h3>
                <div class="space-y-4">
                    @forelse($recentAlamat as $alamat)
                        <div class="flex items-start space-x-3">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                    </svg>
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-neutral-900 dark:text-white truncate">
                                    {{ $alamat->nama_dinas }}
                                </p>
                                <p class="text-sm text-neutral-500 dark:text-neutral-400 truncate">
                                    {{ $alamat->kabupaten_kota }}, {{ $alamat->provinsi }}
                                </p>
                                <p class="text-xs text-neutral-400 dark:text-neutral-500">
                                    {{ $alamat->created_at ? $alamat->created_at->diffForHumans() : 'Data lama' }}
                                </p>
                            </div>
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $alamat->status_badge }}">
                                {{ $alamat->status }}
                            </span>
                        </div>
                    @empty
                        <p class="text-sm text-neutral-500 dark:text-neutral-400">Belum ada data alamat.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Top Kabupaten/Kota -->
        <div class="bg-white dark:!bg-neutral-800 overflow-hidden shadow-lg rounded-lg border border-neutral-200 dark:border-neutral-700">
            <div class="p-6">
                <h3 class="text-lg font-medium text-neutral-900 dark:text-white mb-4">Top Kabupaten/Kota</h3>
                <div class="space-y-3">
                    @foreach($wilayahStats as $kabupatenKota => $count)
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-neutral-600 dark:text-neutral-400 truncate">{{ $kabupatenKota }}</span>
                            <span class="text-sm font-medium text-neutral-900 dark:text-white">{{ number_format($count) }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
