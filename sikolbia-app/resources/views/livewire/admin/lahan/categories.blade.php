<div>
    <!-- Header -->
    <div class="mb-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-semibold text-neutral-900 dark:text-white">Kategori Lahan</h1>
                <p class="mt-1 text-sm text-neutral-600 dark:text-neutral-400">
                    Analisis data berdasarkan kategori topik, variabel, dan klasifikasi
                </p>
            </div>
        </div>
    </div>

    <!-- Tab Navigation -->
    <div class="mb-6">
        <nav class="flex space-x-8" aria-label="Tabs">
            <button wire:click="setActiveTab('topik')" 
                    class="py-2 px-1 border-b-2 font-medium text-sm transition-colors {{ $activeTab === 'topik' ? 'border-blue-500 text-blue-600 dark:text-blue-400' : 'border-transparent text-neutral-500 hover:text-neutral-700 hover:border-neutral-300 dark:text-neutral-400 dark:hover:text-neutral-300' }}">
                Topik Lahan
            </button>
            <button wire:click="setActiveTab('variabel')" 
                    class="py-2 px-1 border-b-2 font-medium text-sm transition-colors {{ $activeTab === 'variabel' ? 'border-blue-500 text-blue-600 dark:text-blue-400' : 'border-transparent text-neutral-500 hover:text-neutral-700 hover:border-neutral-300 dark:text-neutral-400 dark:hover:text-neutral-300' }}">
                Variabel Lahan
            </button>
            <button wire:click="setActiveTab('klasifikasi')" 
                    class="py-2 px-1 border-b-2 font-medium text-sm transition-colors {{ $activeTab === 'klasifikasi' ? 'border-blue-500 text-blue-600 dark:text-blue-400' : 'border-transparent text-neutral-500 hover:text-neutral-700 hover:border-neutral-300 dark:text-neutral-400 dark:hover:text-neutral-300' }}">
                Klasifikasi Lahan
            </button>
        </nav>
    </div>

    <!-- Topik Tab -->
    @if($activeTab === 'topik')
    <div class="space-y-6">
        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-white dark:bg-neutral-800 p-6 rounded-lg border border-neutral-200 dark:border-neutral-700">
                <div class="flex items-center">
                    <div class="p-2 bg-blue-100 dark:bg-blue-900/30 rounded-lg">
                        <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-neutral-600 dark:text-neutral-400">Total Topik</p>
                        <p class="text-2xl font-semibold text-neutral-900 dark:text-white">{{ count($topikStats) }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-neutral-800 p-6 rounded-lg border border-neutral-200 dark:border-neutral-700">
                <div class="flex items-center">
                    <div class="p-2 bg-green-100 dark:bg-green-900/30 rounded-lg">
                        <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-neutral-600 dark:text-neutral-400">Total Data</p>
                        <p class="text-2xl font-semibold text-neutral-900 dark:text-white">{{ number_format(array_sum(array_column($topikStats, 'total_data'))) }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-neutral-800 p-6 rounded-lg border border-neutral-200 dark:border-neutral-700">
                <div class="flex items-center">
                    <div class="p-2 bg-purple-100 dark:bg-purple-900/30 rounded-lg">
                        <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-neutral-600 dark:text-neutral-400">Rata-rata Nilai</p>
                        <p class="text-2xl font-semibold text-neutral-900 dark:text-white">{{ number_format(collect($topikStats)->avg('avg_value'), 2, ',', '.') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Topik Table -->
        <div class="bg-white dark:bg-neutral-800 rounded-lg border border-neutral-200 dark:border-neutral-700">
            <div class="p-6 border-b border-neutral-200 dark:border-neutral-700">
                <h3 class="text-lg font-medium text-neutral-900 dark:text-white">Detail Topik Lahan</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-neutral-500 dark:text-neutral-400">
                    <thead class="text-xs text-neutral-700 uppercase bg-neutral-50 dark:bg-neutral-700 dark:text-neutral-400">
                        <tr>
                            <th scope="col" class="px-6 py-3">Nama Topik</th>
                            <th scope="col" class="px-6 py-3">Jumlah Data</th>
                            <th scope="col" class="px-6 py-3">Rata-rata Nilai</th>
                            <th scope="col" class="px-6 py-3">Total Nilai</th>
                            <th scope="col" class="px-6 py-3">Dibuat</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($topikStats as $topik)
                        <tr class="bg-white border-b dark:bg-neutral-800 dark:border-neutral-700 hover:bg-neutral-50 dark:hover:bg-neutral-700 transition-colors">
                            <td class="px-6 py-4 font-medium text-neutral-900 dark:text-white">
                                {{ $topik['nama'] }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300">
                                    {{ number_format($topik['total_data']) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                {{ number_format($topik['avg_value'], 2, ',', '.') }}
                            </td>
                            <td class="px-6 py-4">
                                {{ number_format($topik['total_value'], 2, ',', '.') }}
                            </td>
                            <td class="px-6 py-4">
                                {{ \Carbon\Carbon::parse($topik['created_at'])->format('d/m/Y') }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-neutral-500 dark:text-neutral-400">
                                Tidak ada data topik ditemukan
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

    <!-- Variabel Tab -->
    @if($activeTab === 'variabel')
    <div class="space-y-6">
        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-white dark:bg-neutral-800 p-6 rounded-lg border border-neutral-200 dark:border-neutral-700">
                <div class="flex items-center">
                    <div class="p-2 bg-green-100 dark:bg-green-900/30 rounded-lg">
                        <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-neutral-600 dark:text-neutral-400">Total Variabel</p>
                        <p class="text-2xl font-semibold text-neutral-900 dark:text-white">{{ count($variabelStats) }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-neutral-800 p-6 rounded-lg border border-neutral-200 dark:border-neutral-700">
                <div class="flex items-center">
                    <div class="p-2 bg-blue-100 dark:bg-blue-900/30 rounded-lg">
                        <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-neutral-600 dark:text-neutral-400">Total Data</p>
                        <p class="text-2xl font-semibold text-neutral-900 dark:text-white">{{ number_format(array_sum(array_column($variabelStats, 'total_data'))) }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-neutral-800 p-6 rounded-lg border border-neutral-200 dark:border-neutral-700">
                <div class="flex items-center">
                    <div class="p-2 bg-orange-100 dark:bg-orange-900/30 rounded-lg">
                        <svg class="w-6 h-6 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-neutral-600 dark:text-neutral-400">Rata-rata Nilai</p>
                        <p class="text-2xl font-semibold text-neutral-900 dark:text-white">{{ number_format(collect($variabelStats)->avg('avg_value'), 2, ',', '.') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Variabel Table -->
        <div class="bg-white dark:bg-neutral-800 rounded-lg border border-neutral-200 dark:border-neutral-700">
            <div class="p-6 border-b border-neutral-200 dark:border-neutral-700">
                <h3 class="text-lg font-medium text-neutral-900 dark:text-white">Detail Variabel Lahan</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-neutral-500 dark:text-neutral-400">
                    <thead class="text-xs text-neutral-700 uppercase bg-neutral-50 dark:bg-neutral-700 dark:text-neutral-400">
                        <tr>
                            <th scope="col" class="px-6 py-3">Nama Variabel</th>
                            <th scope="col" class="px-6 py-3">Satuan</th>
                            <th scope="col" class="px-6 py-3">Jumlah Data</th>
                            <th scope="col" class="px-6 py-3">Rata-rata Nilai</th>
                            <th scope="col" class="px-6 py-3">Total Nilai</th>
                            <th scope="col" class="px-6 py-3">Dibuat</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($variabelStats as $variabel)
                        <tr class="bg-white border-b dark:bg-neutral-800 dark:border-neutral-700 hover:bg-neutral-50 dark:hover:bg-neutral-700 transition-colors">
                            <td class="px-6 py-4 font-medium text-neutral-900 dark:text-white">
                                {{ $variabel['nama'] }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300">
                                    {{ $variabel['satuan'] }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300">
                                    {{ number_format($variabel['total_data']) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                {{ number_format($variabel['avg_value'], 2, ',', '.') }}
                            </td>
                            <td class="px-6 py-4">
                                {{ number_format($variabel['total_value'], 2, ',', '.') }}
                            </td>
                            <td class="px-6 py-4">
                                {{ \Carbon\Carbon::parse($variabel['created_at'])->format('d/m/Y') }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-neutral-500 dark:text-neutral-400">
                                Tidak ada data variabel ditemukan
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

    <!-- Klasifikasi Tab -->
    @if($activeTab === 'klasifikasi')
    <div class="space-y-6">
        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-white dark:bg-neutral-800 p-6 rounded-lg border border-neutral-200 dark:border-neutral-700">
                <div class="flex items-center">
                    <div class="p-2 bg-purple-100 dark:bg-purple-900/30 rounded-lg">
                        <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-neutral-600 dark:text-neutral-400">Total Klasifikasi</p>
                        <p class="text-2xl font-semibold text-neutral-900 dark:text-white">{{ count($klasifikasiStats) }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-neutral-800 p-6 rounded-lg border border-neutral-200 dark:border-neutral-700">
                <div class="flex items-center">
                    <div class="p-2 bg-indigo-100 dark:bg-indigo-900/30 rounded-lg">
                        <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-neutral-600 dark:text-neutral-400">Total Data</p>
                        <p class="text-2xl font-semibold text-neutral-900 dark:text-white">{{ number_format(array_sum(array_column($klasifikasiStats, 'total_data'))) }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-neutral-800 p-6 rounded-lg border border-neutral-200 dark:border-neutral-700">
                <div class="flex items-center">
                    <div class="p-2 bg-pink-100 dark:bg-pink-900/30 rounded-lg">
                        <svg class="w-6 h-6 text-pink-600 dark:text-pink-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-neutral-600 dark:text-neutral-400">Rata-rata Nilai</p>
                        <p class="text-2xl font-semibold text-neutral-900 dark:text-white">{{ number_format(collect($klasifikasiStats)->avg('avg_value'), 2, ',', '.') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Klasifikasi Table -->
        <div class="bg-white dark:bg-neutral-800 rounded-lg border border-neutral-200 dark:border-neutral-700">
            <div class="p-6 border-b border-neutral-200 dark:border-neutral-700">
                <h3 class="text-lg font-medium text-neutral-900 dark:text-white">Detail Klasifikasi Lahan</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-neutral-500 dark:text-neutral-400">
                    <thead class="text-xs text-neutral-700 uppercase bg-neutral-50 dark:bg-neutral-700 dark:text-neutral-400">
                        <tr>
                            <th scope="col" class="px-6 py-3">Nama Klasifikasi</th>
                            <th scope="col" class="px-6 py-3">Jumlah Data</th>
                            <th scope="col" class="px-6 py-3">Rata-rata Nilai</th>
                            <th scope="col" class="px-6 py-3">Total Nilai</th>
                            <th scope="col" class="px-6 py-3">Dibuat</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($klasifikasiStats as $klasifikasi)
                        <tr class="bg-white border-b dark:bg-neutral-800 dark:border-neutral-700 hover:bg-neutral-50 dark:hover:bg-neutral-700 transition-colors">
                            <td class="px-6 py-4 font-medium text-neutral-900 dark:text-white">
                                {{ $klasifikasi['nama'] }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 text-xs rounded-full bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-300">
                                    {{ number_format($klasifikasi['total_data']) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                {{ number_format($klasifikasi['avg_value'], 2, ',', '.') }}
                            </td>
                            <td class="px-6 py-4">
                                {{ number_format($klasifikasi['total_value'], 2, ',', '.') }}
                            </td>
                            <td class="px-6 py-4">
                                {{ \Carbon\Carbon::parse($klasifikasi['created_at'])->format('d/m/Y') }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-neutral-500 dark:text-neutral-400">
                                Tidak ada data klasifikasi ditemukan
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif
</div>
