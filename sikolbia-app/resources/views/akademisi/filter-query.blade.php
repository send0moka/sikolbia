<x-layouts.akademisi title="Filter & Query - Panel Akademisi">
    <div class="p-6 space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-neutral-900 dark:text-white">Advanced Filter & Query</h1>
                <p class="mt-1 text-sm text-neutral-600 dark:text-neutral-400">
                    Filter data NBM dengan multiple criteria untuk analisis mendalam
                </p>
            </div>
        </div>

        <!-- Advanced Filter Form -->
        <div class="bg-white dark:bg-neutral-800 rounded-lg shadow p-6">
            <form method="GET" action="{{ route('akademisi.filter-query') }}" class="space-y-4">
                <!-- Row 1: Tahun Range -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                            Tahun Dari - Sampai
                        </label>
                        <div class="flex gap-2">
                            <input type="number" name="tahun_dari" value="{{ request('tahun_dari') }}" placeholder="1993" min="1993" max="{{ date('Y') }}"
                                   class="flex-1 px-3 py-2 bg-white dark:bg-neutral-700 border border-neutral-300 dark:border-neutral-600 rounded-lg focus:ring-2 focus:ring-purple-500 dark:text-white">
                            <span class="flex items-center text-neutral-500">-</span>
                            <input type="number" name="tahun_sampai" value="{{ request('tahun_sampai') }}" placeholder="{{ date('Y') }}" min="1993" max="{{ date('Y') }}"
                                   class="flex-1 px-3 py-2 bg-white dark:bg-neutral-700 border border-neutral-300 dark:border-neutral-600 rounded-lg focus:ring-2 focus:ring-purple-500 dark:text-white">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                            Bulan Dari - Sampai
                        </label>
                        <div class="flex gap-2">
                            <select name="bulan_dari" class="flex-1 px-3 py-2 bg-white dark:bg-neutral-700 border border-neutral-300 dark:border-neutral-600 rounded-lg focus:ring-2 focus:ring-purple-500 dark:text-white">
                                <option value="">Semua</option>
                                @for($i = 1; $i <= 12; $i++)
                                    <option value="{{ $i }}" {{ request('bulan_dari') == $i ? 'selected' : '' }}>{{ \Carbon\Carbon::create(null, $i)->format('F') }}</option>
                                @endfor
                            </select>
                            <span class="flex items-center text-neutral-500">-</span>
                            <select name="bulan_sampai" class="flex-1 px-3 py-2 bg-white dark:bg-neutral-700 border border-neutral-300 dark:border-neutral-600 rounded-lg focus:ring-2 focus:ring-purple-500 dark:text-white">
                                <option value="">Semua</option>
                                @for($i = 1; $i <= 12; $i++)
                                    <option value="{{ $i }}" {{ request('bulan_sampai') == $i ? 'selected' : '' }}>{{ \Carbon\Carbon::create(null, $i)->format('F') }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Row 2: Kelompok & Komoditi (Multiple Select) -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                            Kelompok Komoditas (Multiple)
                        </label>
                        <select name="kelompok[]" multiple size="5"
                                class="w-full px-3 py-2 bg-white dark:bg-neutral-700 border border-neutral-300 dark:border-neutral-600 rounded-lg focus:ring-2 focus:ring-purple-500 dark:text-white">
                            @foreach($kelompokList as $kelompok)
                                <option value="{{ $kelompok->kode }}" {{ in_array($kelompok->kode, request('kelompok', [])) ? 'selected' : '' }}>
                                    {{ $kelompok->kode }} - {{ $kelompok->nama }}
                                </option>
                            @endforeach
                        </select>
                        <p class="mt-1 text-xs text-neutral-500">Hold Ctrl/Cmd untuk pilih multiple</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                            Komoditas (Multiple)
                        </label>
                        <select name="komoditi[]" multiple size="5"
                                class="w-full px-3 py-2 bg-white dark:bg-neutral-700 border border-neutral-300 dark:border-neutral-600 rounded-lg focus:ring-2 focus:ring-purple-500 dark:text-white">
                            @foreach($komoditiList as $komoditi)
                                <option value="{{ $komoditi->kode_komoditi }}" {{ in_array($komoditi->kode_komoditi, request('komoditi', [])) ? 'selected' : '' }}>
                                    {{ $komoditi->kode_komoditi }} - {{ $komoditi->nama }}
                                </option>
                            @endforeach
                        </select>
                        <p class="mt-1 text-xs text-neutral-500">Hold Ctrl/Cmd untuk pilih multiple</p>
                    </div>
                </div>

                <!-- Row 3: Bahan Makanan Range -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                            Ketersediaan Bahan Makanan (Min - Max) (ton)
                        </label>
                        <div class="flex gap-2">
                            <input type="number" name="bahan_makanan_min" value="{{ request('bahan_makanan_min') }}" placeholder="Min" step="0.01"
                                   class="flex-1 px-3 py-2 bg-white dark:bg-neutral-700 border border-neutral-300 dark:border-neutral-600 rounded-lg focus:ring-2 focus:ring-purple-500 dark:text-white">
                            <span class="flex items-center text-neutral-500">-</span>
                            <input type="number" name="bahan_makanan_max" value="{{ request('bahan_makanan_max') }}" placeholder="Max" step="0.01"
                                   class="flex-1 px-3 py-2 bg-white dark:bg-neutral-700 border border-neutral-300 dark:border-neutral-600 rounded-lg focus:ring-2 focus:ring-purple-500 dark:text-white">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                            Sorting
                        </label>
                        <div class="flex gap-2">
                            <select name="sort_by" class="flex-1 px-3 py-2 bg-white dark:bg-neutral-700 border border-neutral-300 dark:border-neutral-600 rounded-lg focus:ring-2 focus:ring-purple-500 dark:text-white">
                                <option value="tahun" {{ request('sort_by') == 'tahun' ? 'selected' : '' }}>Tahun</option>
                                <option value="bulan" {{ request('sort_by') == 'bulan' ? 'selected' : '' }}>Bulan</option>
                                <option value="bahan_makanan" {{ request('sort_by') == 'bahan_makanan' ? 'selected' : '' }}>Ketersediaan</option>
                            </select>
                            <select name="sort_order" class="px-3 py-2 bg-white dark:bg-neutral-700 border border-neutral-300 dark:border-neutral-600 rounded-lg focus:ring-2 focus:ring-purple-500 dark:text-white">
                                <option value="desc" {{ request('sort_order') == 'desc' ? 'selected' : '' }}>↓ Descending</option>
                                <option value="asc" {{ request('sort_order') == 'asc' ? 'selected' : '' }}>↑ Ascending</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex gap-2">
                    <button type="submit"
                            class="px-6 py-2 bg-purple-600 hover:bg-purple-700 text-white font-medium rounded-lg transition-colors">
                        <svg class="inline w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        Apply Filter
                    </button>
                    <a href="{{ route('akademisi.filter-query') }}"
                       class="px-6 py-2 bg-neutral-200 hover:bg-neutral-300 dark:bg-neutral-600 dark:hover:bg-neutral-500 text-neutral-700 dark:text-neutral-200 font-medium rounded-lg transition-colors">
                        Reset
                    </a>
                </div>
            </form>
        </div>

        <!-- Summary Statistics -->
        @if($summary)
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div class="bg-gradient-to-br from-purple-500 to-purple-600 text-white rounded-lg shadow p-4">
                <div class="text-sm font-medium opacity-90">Total Records</div>
                <div class="mt-1 text-2xl font-bold">{{ number_format($summary['total_records']) }}</div>
            </div>

            <div class="bg-gradient-to-br from-blue-500 to-blue-600 text-white rounded-lg shadow p-4">
                <div class="text-sm font-medium opacity-90">Rata-rata</div>
                <div class="mt-1 text-2xl font-bold">{{ number_format($summary['avg_bahan_makanan'], 2) }}</div>
                <div class="mt-1 text-xs opacity-75">ton</div>
            </div>

            <div class="bg-gradient-to-br from-green-500 to-green-600 text-white rounded-lg shadow p-4">
                <div class="text-sm font-medium opacity-90">Total</div>
                <div class="mt-1 text-2xl font-bold">{{ number_format($summary['sum_bahan_makanan'], 0) }}</div>
                <div class="mt-1 text-xs opacity-75">ton</div>
            </div>

            <div class="bg-gradient-to-br from-orange-500 to-orange-600 text-white rounded-lg shadow p-4">
                <div class="text-sm font-medium opacity-90">Minimum</div>
                <div class="mt-1 text-2xl font-bold">{{ number_format($summary['min_bahan_makanan'], 2) }}</div>
                <div class="mt-1 text-xs opacity-75">ton</div>
            </div>

            <div class="bg-gradient-to-br from-red-500 to-red-600 text-white rounded-lg shadow p-4">
                <div class="text-sm font-medium opacity-90">Maximum</div>
                <div class="mt-1 text-2xl font-bold">{{ number_format($summary['max_bahan_makanan'], 2) }}</div>
                <div class="mt-1 text-xs opacity-75">ton</div>
            </div>
        </div>
        @endif

        <!-- Results Table -->
        <div class="bg-white dark:bg-neutral-800 rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-neutral-200 dark:border-neutral-700 flex items-center justify-between">
                <h3 class="text-lg font-semibold text-neutral-900 dark:text-white">
                    Hasil Query ({{ $results->total() }} records)
                </h3>
                @if($results->total() > 0)
                <a href="{{ route('akademisi.export-data', request()->all()) }}" 
                   class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                    Export to Excel
                </a>
                @endif
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-neutral-200 dark:divide-neutral-700">
                    <thead class="bg-neutral-50 dark:bg-neutral-900">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 dark:text-neutral-400 uppercase">Tahun</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 dark:text-neutral-400 uppercase">Bulan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 dark:text-neutral-400 uppercase">Kelompok</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 dark:text-neutral-400 uppercase">Komoditas</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-neutral-500 dark:text-neutral-400 uppercase">Ketersediaan (ton)</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-neutral-800 divide-y divide-neutral-200 dark:divide-neutral-700">
                        @forelse($results as $data)
                        <tr class="hover:bg-neutral-50 dark:hover:bg-neutral-700">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-900 dark:text-neutral-300">{{ $data->tahun }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-900 dark:text-neutral-300">{{ \Carbon\Carbon::create(null, $data->bulan)->format('F') }}</td>
                            <td class="px-6 py-4 text-sm text-neutral-900 dark:text-neutral-300">
                                {{ $data->kelompok->kode }} - {{ $data->kelompok->nama }}
                            </td>
                            <td class="px-6 py-4 text-sm text-neutral-900 dark:text-neutral-300">
                                {{ $data->komoditi->kode_komoditi }} - {{ $data->komoditi->nama }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-900 dark:text-neutral-300 text-right font-medium">
                                {{ number_format($data->bahan_makanan, 2) }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-neutral-500 dark:text-neutral-400">
                                <svg class="mx-auto h-12 w-12 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <p class="mt-2 text-sm">Tidak ada data ditemukan. Coba sesuaikan filter.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($results->hasPages())
            <div class="px-6 py-4 border-t border-neutral-200 dark:border-neutral-700">
                {{ $results->links() }}
            </div>
            @endif
        </div>
    </div>
</x-layouts.akademisi>
