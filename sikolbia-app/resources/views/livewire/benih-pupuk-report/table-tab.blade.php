<!-- Table Tab Content -->
<div class="space-y-8">
    @foreach($results as $index => $resultSet)
        @php
            $queueItem = $resultSet['queue_item'];
            $pivotData = $resultSet['data'];
        @endphp
        
        <!-- Dataset Header -->
        <div class="bg-gradient-to-r from-green-50 to-blue-50 dark:from-neutral-700 dark:to-neutral-600 rounded-lg p-4 mb-4">
            <h4 class="text-lg font-semibold text-neutral-900 dark:text-white">
                Dataset {{ $index + 1 }}: {{ $queueItem['topik'] }}
            </h4>
            <div class="text-sm text-neutral-600 dark:text-neutral-400 mt-1">
                <span class="font-medium">Variabel:</span> {{ implode(', ', $queueItem['variabels']) }}
                <br>
                <span class="font-medium">Klasifikasi:</span> {{ implode(', ', $queueItem['klasifikasis']) }}
                <br>
                <span class="font-medium">Periode:</span> {{ $queueItem['tahun_awal'] }} - {{ $queueItem['tahun_akhir'] }}
                @if(!empty($queueItem['bulans']) && $queueItem['bulans'][0] !== 'Semua')
                    <br><span class="font-medium">Bulan:</span> {{ implode(', ', $queueItem['bulans']) }}
                @endif
            </div>
        </div>

        @if(!empty($pivotData['data']))
            <!-- Data Table -->
            <div class="overflow-x-auto border border-neutral-200 dark:border-neutral-600 rounded-lg">
                <table class="min-w-full divide-y divide-neutral-200 dark:divide-neutral-600">
                    <thead class="bg-neutral-50 dark:bg-neutral-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 dark:text-neutral-400 uppercase tracking-wider sticky left-0 bg-neutral-50 dark:bg-neutral-700 z-10">
                                {{ ucfirst($variabelVertikal) }}
                            </th>
                            @php
                                // Get all unique column headers
                                $allColumns = [];
                                foreach ($pivotData['data'] as $wilayah => $variabelData) {
                                    foreach ($variabelData as $variabel => $tahunData) {
                                        foreach (array_keys($tahunData) as $tahunBulan) {
                                            $colKey = $variabel . ' - ' . $tahunBulan;
                                            if (!in_array($colKey, $allColumns)) {
                                                $allColumns[] = $colKey;
                                            }
                                        }
                                    }
                                }
                            @endphp
                            @foreach($allColumns as $column)
                                <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 dark:text-neutral-400 uppercase tracking-wider">
                                    {{ $column }}
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-neutral-800 divide-y divide-neutral-200 dark:divide-neutral-600">
                        @foreach($pivotData['data'] as $wilayah => $variabelData)
                            <tr class="hover:bg-neutral-50 dark:hover:bg-neutral-700">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-neutral-900 dark:text-white sticky left-0 bg-white dark:bg-neutral-800 z-10">
                                    {{ $wilayah }}
                                </td>
                                @foreach($allColumns as $column)
                                    @php
                                        $parts = explode(' - ', $column);
                                        $variabel = $parts[0];
                                        $tahunBulan = $parts[1] ?? '';
                                        $value = $variabelData[$variabel][$tahunBulan] ?? 0;
                                    @endphp
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-500 dark:text-neutral-400">
                                        {{ number_format($value, 2) }}
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                        
                        <!-- Totals Row -->
                        @if(!empty($pivotData['totals']))
                            <tr class="bg-green-50 dark:bg-green-900/20 border-t-2 border-green-200 dark:border-green-700">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-neutral-900 dark:text-white sticky left-0 bg-green-50 dark:bg-green-900/20 z-10">
                                    TOTAL
                                </td>
                                @foreach($allColumns as $column)
                                    @php
                                        $parts = explode(' - ', $column);
                                        $variabel = $parts[0];
                                        $tahunBulan = $parts[1] ?? '';
                                        $totalValue = $pivotData['totals'][$variabel][$tahunBulan] ?? 0;
                                    @endphp
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-green-700 dark:text-green-300">
                                        {{ number_format($totalValue, 2) }}
                                    </td>
                                @endforeach
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-8">
                <flux:icon.exclamation-triangle class="w-12 h-12 text-yellow-400 mx-auto mb-4" />
                <p class="text-neutral-600 dark:text-neutral-400">
                    Tidak ada data ditemukan untuk dataset ini.
                </p>
            </div>
        @endif
        
        @if(!$loop->last)
            <hr class="border-neutral-200 dark:border-neutral-600">
        @endif
    @endforeach
</div>
