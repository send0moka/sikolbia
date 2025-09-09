<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-semibold text-neutral-900 dark:text-white">Dashboard Benih Pupuk</h1>
            <p class="mt-1 text-sm text-neutral-600 dark:text-neutral-400">Overview data benih pupuk dan statistik</p>
        </div>
        <div class="flex space-x-2">
            <select wire:model.live="selectedYear" class="rounded-md border-neutral-300 dark:border-neutral-600 dark:bg-neutral-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                @foreach($stats['available_years'] as $year)
                    <option value="{{ $year }}">{{ $year }}</option>
                @endforeach
            </select>
            <select wire:model.live="selectedMonth" class="rounded-md border-neutral-300 dark:border-neutral-600 dark:bg-neutral-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                @foreach(\App\Models\BenihPupukBulan::getBulanNames() as $id => $name)
                    <option value="{{ $id }}">{{ $name }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
        <div class="bg-white dark:bg-neutral-800 overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-indigo-500 rounded-md flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-neutral-500 dark:text-neutral-400 truncate">Total Data</dt>
                            <dd class="text-lg font-medium text-neutral-900 dark:text-white">{{ number_format($stats['total_data']) }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-neutral-800 overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-green-500 rounded-md flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-neutral-500 dark:text-neutral-400 truncate">Data Aktif</dt>
                            <dd class="text-lg font-medium text-neutral-900 dark:text-white">{{ number_format($stats['data_aktif']) }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-neutral-800 overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-blue-500 rounded-md flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-neutral-500 dark:text-neutral-400 truncate">Total Topik</dt>
                            <dd class="text-lg font-medium text-neutral-900 dark:text-white">{{ number_format($stats['total_topik']) }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-neutral-800 overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-purple-500 rounded-md flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-neutral-500 dark:text-neutral-400 truncate">Total Wilayah</dt>
                            <dd class="text-lg font-medium text-neutral-900 dark:text-white">{{ number_format($stats['total_wilayah']) }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts and Recent Data -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Monthly Data Chart -->
        <div class="bg-white dark:bg-neutral-800 shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-neutral-900 dark:text-white">Data Bulanan {{ $selectedYear }}</h3>
                <div class="mt-5">
                    @if($monthlyData->count() > 0)
                        <div class="space-y-3">
                            @foreach($monthlyData as $data)
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-neutral-600 dark:text-neutral-400">
                                        {{ \App\Models\BenihPupukBulan::getBulanNames()[$data->id_bulan] ?? "Bulan {$data->id_bulan}" }}
                                    </span>
                                    <div class="flex items-center space-x-2">
                                        <span class="text-sm font-medium text-neutral-900 dark:text-white">{{ number_format($data->total_records) }}</span>
                                        <div class="w-20 bg-neutral-200 dark:bg-neutral-700 rounded-full h-2">
                                            <div class="bg-indigo-600 h-2 rounded-full" style="width: {{ min(100, ($data->total_records / $monthlyData->max('total_records')) * 100) }}%"></div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-sm text-neutral-500 dark:text-neutral-400">Tidak ada data untuk tahun {{ $selectedYear }}</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Top Wilayah -->
        <div class="bg-white dark:bg-neutral-800 shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-neutral-900 dark:text-white">Top Wilayah</h3>
                <div class="mt-5">
                    @if($topWilayah->count() > 0)
                        <div class="space-y-3">
                            @foreach($topWilayah as $wilayah)
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-neutral-600 dark:text-neutral-400 truncate">
                                        {{ $wilayah->wilayah->nama ?? 'Unknown' }}
                                    </span>
                                    <div class="flex items-center space-x-2">
                                        <span class="text-sm font-medium text-neutral-900 dark:text-white">{{ number_format($wilayah->total_records) }}</span>
                                        <div class="w-20 bg-neutral-200 dark:bg-neutral-700 rounded-full h-2">
                                            <div class="bg-green-600 h-2 rounded-full" style="width: {{ min(100, ($wilayah->total_records / $topWilayah->max('total_records')) * 100) }}%"></div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-sm text-neutral-500 dark:text-neutral-400">Tidak ada data wilayah</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Data -->
    <div class="bg-white dark:bg-neutral-800 shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg leading-6 font-medium text-neutral-900 dark:text-white mb-4">Data Terbaru</h3>
            @if($recentData->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-neutral-200 dark:divide-neutral-700">
                        <thead class="bg-neutral-50 dark:bg-neutral-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 dark:text-neutral-300 uppercase tracking-wider">Tahun</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 dark:text-neutral-300 uppercase tracking-wider">Bulan</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 dark:text-neutral-300 uppercase tracking-wider">Wilayah</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 dark:text-neutral-300 uppercase tracking-wider">Variabel</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 dark:text-neutral-300 uppercase tracking-wider">Nilai</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 dark:text-neutral-300 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-neutral-800 divide-y divide-neutral-200 dark:divide-neutral-700">
                            @foreach($recentData as $data)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-900 dark:text-white">{{ $data->tahun }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-900 dark:text-white">{{ $data->bulan->deskripsi ?? '-' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-900 dark:text-white">{{ $data->wilayah->nama ?? '-' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-900 dark:text-white">{{ Str::limit($data->variabel->deskripsi ?? '-', 30) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-900 dark:text-white">{{ $data->formatted_nilai }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $data->status_badge }}">
                                            {{ $data->status_label }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-sm text-neutral-500 dark:text-neutral-400">Belum ada data</p>
            @endif
        </div>
    </div>
</div>
