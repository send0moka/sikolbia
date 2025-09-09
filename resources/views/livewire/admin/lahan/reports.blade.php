<div>
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-neutral-900 dark:text-white">Laporan Data Lahan</h1>
        <p class="mt-1 text-sm text-neutral-600 dark:text-neutral-400">
            Generate dan ekspor laporan data lahan pertanian
        </p>
    </div>

    <!-- Report Type Tabs -->
    <div class="mb-6 border-b border-neutral-200 dark:border-neutral-700">
        <nav class="-mb-px flex space-x-8">
            @foreach(['summary' => 'Ringkasan', 'detailed' => 'Detail', 'comparison' => 'Perbandingan', 'trend' => 'Tren'] as $type => $label)
                <button 
                    wire:click="$set('reportType', '{{ $type }}')"
                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm {{ $reportType === $type ? 'border-blue-500 text-blue-600 dark:text-blue-400' : 'border-transparent text-neutral-500 dark:text-neutral-400 hover:text-neutral-700 dark:hover:text-neutral-200 hover:border-neutral-300' }}"
                >
                    {{ $label }}
                </button>
            @endforeach
        </nav>
    </div>

    <!-- Filters -->
    <div class="mb-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Topik Filter -->
        <div>
            <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1">Topik</label>
            <select wire:model.live="selectedTopik" class="w-full text-sm rounded-md border-neutral-300 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200">
                <option value="">Semua Topik</option>
                @foreach($topiks as $topik)
                    <option value="{{ $topik->id }}">{{ $topik->nama }}</option>
                @endforeach
            </select>
        </div>

        <!-- Variabel Filter -->
        <div>
            <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1">Variabel</label>
            <select wire:model.live="selectedVariabel" class="w-full text-sm rounded-md border-neutral-300 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200">
                <option value="">Semua Variabel</option>
                @foreach($variabels as $variabel)
                    <option value="{{ $variabel->id }}">{{ $variabel->nama }}</option>
                @endforeach
            </select>
        </div>

        <!-- Date Range -->
        <div class="lg:col-span-2">
            <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1">Rentang Tahun</label>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <input 
                        type="date" 
                        wire:model.live="dateFrom" 
                        class="w-full text-sm rounded-md border-neutral-300 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200"
                    >
                </div>
                <div>
                    <input 
                        type="date" 
                        wire:model.live="dateTo" 
                        class="w-full text-sm rounded-md border-neutral-300 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200"
                    >
                </div>
            </div>
        </div>
    </div>

    <!-- Report Content -->
    <div class="bg-white dark:bg-neutral-800 rounded-lg border border-neutral-200 dark:border-neutral-700 overflow-hidden">
        @if($reportType === 'summary')
            <!-- Summary Report -->
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-medium text-neutral-900 dark:text-white">Ringkasan Data Lahan</h3>
                    <div class="flex items-center space-x-2">
                        <select wire:model.live="groupBy" class="text-sm rounded-md border-neutral-300 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200">
                            <option value="region">Kelompokkan Berdasarkan Wilayah</option>
                            <option value="topik">Kelompokkan Berdasarkan Topik</option>
                            <option value="variabel">Kelompokkan Berdasarkan Variabel</option>
                            <option value="year">Kelompokkan Berdasarkan Tahun</option>
                        </select>
                        <div class="flex items-center space-x-2">
                            <button 
                                wire:click="exportReport('csv')" 
                                class="px-3 py-1.5 bg-green-600 text-white text-sm rounded-md hover:bg-green-700 flex items-center"
                                title="Ekspor ke CSV"
                            >
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                CSV
                            </button>
                            <button 
                                wire:click="exportReport('excel')" 
                                class="px-3 py-1.5 bg-blue-600 text-white text-sm rounded-md hover:bg-blue-700 flex items-center"
                                title="Ekspor ke Excel"
                            >
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                Excel
                            </button>
                            <button 
                                wire:click="exportReport('pdf')" 
                                class="px-3 py-1.5 bg-red-600 text-white text-sm rounded-md hover:bg-red-700 flex items-center"
                                title="Ekspor ke PDF/HTML"
                            >
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                PDF
                            </button>
                        </div>
                    </div>
                </div>

                @if($reportData->isNotEmpty())
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-neutral-200 dark:divide-neutral-700">
                            <thead class="bg-neutral-50 dark:bg-neutral-700">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-neutral-500 dark:text-neutral-300 uppercase tracking-wider">
                                        {{ $groupBy === 'region' ? 'Wilayah' : ($groupBy === 'topik' ? 'Topik' : ($groupBy === 'variabel' ? 'Variabel' : 'Tahun')) }}
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-neutral-500 dark:text-neutral-300 uppercase tracking-wider">Jumlah Data</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-neutral-500 dark:text-neutral-300 uppercase tracking-wider">Rata-rata</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-neutral-500 dark:text-neutral-300 uppercase tracking-wider">Total</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-neutral-500 dark:text-neutral-300 uppercase tracking-wider">Maksimum</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-neutral-500 dark:text-neutral-300 uppercase tracking-wider">Minimum</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-neutral-800 divide-y divide-neutral-200 dark:divide-neutral-700">
                                @foreach($reportData as $item)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-neutral-900 dark:text-white">
                                            {{ $item->group_name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-500 dark:text-neutral-300">
                                            {{ number_format($item->total_records) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-500 dark:text-neutral-300">
                                            {{ number_format($item->avg_value, 2) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-500 dark:text-neutral-300">
                                            {{ number_format($item->total_value) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-500 dark:text-neutral-300">
                                            {{ number_format($item->max_value, 2) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-500 dark:text-neutral-300">
                                            {{ number_format($item->min_value, 2) }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-12 text-neutral-500 dark:text-neutral-400">
                        <p>Tidak ada data yang ditemukan dengan filter yang dipilih.</p>
                    </div>
                @endif
            </div>
        @else
            <!-- Other report types can be added here -->
            <div class="p-6 text-center text-neutral-500 dark:text-neutral-400">
                <p>Fitur laporan {{ $reportType }} sedang dalam pengembangan.</p>
            </div>
        @endif
    </div>
</div>
