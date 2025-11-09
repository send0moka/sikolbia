<div>
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6 mb-8">
        <!-- Total Data -->
        <div class="bg-white dark:bg-neutral-800 overflow-hidden shadow-lg rounded-lg border border-neutral-200 dark:border-neutral-700">
            <div class="p-4 lg:p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4 min-w-0 flex-1">
                        <h3 class="text-base lg:text-lg font-semibold text-neutral-900 dark:text-white truncate">Total Data</h3>
                        <p class="text-2xl lg:text-3xl font-bold text-blue-600 dark:text-blue-400">{{ number_format($totalData) }}</p>
                        <p class="text-xs lg:text-sm text-neutral-500 dark:text-neutral-400 truncate">Data lahan terdaftar</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Topik -->
        <div class="bg-white dark:bg-neutral-800 overflow-hidden shadow-lg rounded-lg border border-neutral-200 dark:border-neutral-700">
            <div class="p-4 lg:p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-purple-100 dark:bg-purple-900 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a4 4 0 014-4z" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4 min-w-0 flex-1">
                        <h3 class="text-base lg:text-lg font-semibold text-neutral-900 dark:text-white truncate">Topik</h3>
                        <p class="text-2xl lg:text-3xl font-bold text-purple-600 dark:text-purple-400">{{ $totalTopik }}</p>
                        <p class="text-xs lg:text-sm text-neutral-500 dark:text-neutral-400 truncate">Kategori topik</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Variabel -->
        <div class="bg-white dark:bg-neutral-800 overflow-hidden shadow-lg rounded-lg border border-neutral-200 dark:border-neutral-700">
            <div class="p-4 lg:p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-green-100 dark:bg-green-900 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4 min-w-0 flex-1">
                        <h3 class="text-base lg:text-lg font-semibold text-neutral-900 dark:text-white truncate">Variabel</h3>
                        <p class="text-2xl lg:text-3xl font-bold text-green-600 dark:text-green-400">{{ $totalVariabel }}</p>
                        <p class="text-xs lg:text-sm text-neutral-500 dark:text-neutral-400 truncate">Jenis variabel</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Rata-rata Nilai -->
        <div class="bg-white dark:bg-neutral-800 overflow-hidden shadow-lg rounded-lg border border-neutral-200 dark:border-neutral-700">
            <div class="p-4 lg:p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-amber-100 dark:bg-amber-900 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4 min-w-0 flex-1">
                        <h3 class="text-base lg:text-lg font-semibold text-neutral-900 dark:text-white truncate">Rata-rata Nilai</h3>
                        <p class="text-2xl lg:text-3xl font-bold text-amber-600 dark:text-amber-400">{{ number_format($averageNilai, 2) }}</p>
                        <p class="text-xs lg:text-sm text-neutral-500 dark:text-neutral-400 truncate">Nilai rata-rata data</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Data -->
    <div class="bg-white dark:bg-neutral-800 p-6 rounded-lg border border-neutral-200 dark:border-neutral-700 mb-8">
        <h3 class="text-lg font-medium text-neutral-900 dark:text-white mb-4">Data Terbaru</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-neutral-200 dark:divide-neutral-700">
                <thead class="bg-neutral-50 dark:bg-neutral-700">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-neutral-500 dark:text-neutral-300 uppercase tracking-wider">Topik</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-neutral-500 dark:text-neutral-300 uppercase tracking-wider">Nilai</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-neutral-500 dark:text-neutral-300 uppercase tracking-wider">Tahun</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-neutral-500 dark:text-neutral-300 uppercase tracking-wider">Wilayah</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-neutral-800 divide-y divide-neutral-200 dark:divide-neutral-700">
                    @forelse($recentData as $data)
                        <tr>
                            <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-neutral-900 dark:text-white">
                                {{ $data->topik->deskripsi ?? 'N/A' }}
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-neutral-500 dark:text-neutral-300">
                                {{ number_format($data->nilai, 2) }}
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-neutral-500 dark:text-neutral-300">
                                {{ $data->tahun }}
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-neutral-500 dark:text-neutral-300">
                                {{ $data->wilayah->nama ?? 'N/A' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-4 text-center text-sm text-neutral-500 dark:text-neutral-400">
                                Tidak ada data tersedia
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
