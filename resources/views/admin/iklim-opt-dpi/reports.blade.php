<div>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-6">
                <h2 class="font-semibold text-xl text-neutral-800 dark:text-neutral-200 leading-tight">
                    {{ __('Laporan Iklim & OPT DPI') }}
                </h2>
            </div>

            <!-- Flash Messages -->
            @if (session()->has('message'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('message') }}
                </div>
            @endif

            @if (session()->has('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Filter Section -->
            <div class="bg-white dark:bg-zinc-900 overflow-hidden shadow-xl sm:rounded-lg p-6 mb-6">
                <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-4">
                    <div>
                        <label for="report-type" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">Jenis Laporan</label>
                        <select wire:model.live="reportType" class="mt-1 block w-full py-2 px-3 border border-neutral-300 dark:border-neutral-600 bg-white dark:bg-zinc-800 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm dark:text-neutral-200">
                            <option value="monthly">Laporan Bulanan</option>
                            <option value="quarterly">Laporan Triwulan</option>
                            <option value="yearly">Laporan Tahunan</option>
                            <option value="custom">Laporan Kustom</option>
                        </select>
                    </div>
                    <div>
                        <label for="topik" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">Topik</label>
                        <select wire:model.live="selectedTopik" class="mt-1 block w-full py-2 px-3 border border-neutral-300 dark:border-neutral-600 bg-white dark:bg-zinc-800 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm dark:text-neutral-200">
                            <option value="">Semua Topik</option>
                            @foreach($topiks as $topik)
                                <option value="{{ $topik->id }}">{{ $topik->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="variabel" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">Variabel</label>
                        <select wire:model.live="selectedVariabel" class="mt-1 block w-full py-2 px-3 border border-neutral-300 dark:border-neutral-600 bg-white dark:bg-zinc-800 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm dark:text-neutral-200">
                            <option value="">Semua Variabel</option>
                            @foreach($variabels as $variabel)
                                <option value="{{ $variabel->id }}">{{ $variabel->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="wilayah" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">Wilayah</label>
                        <select wire:model.live="selectedWilayah" class="mt-1 block w-full py-2 px-3 border border-neutral-300 dark:border-neutral-600 bg-white dark:bg-zinc-800 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm dark:text-neutral-200">
                            <option value="">Semua Wilayah</option>
                            @foreach($wilayahs as $wilayah)
                                <option value="{{ $wilayah }}">{{ $wilayah }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="periode" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">Periode</label>
                        <input type="month" wire:model.live="selectedPeriode" class="mt-1 block w-full py-2 px-3 border border-neutral-300 dark:border-neutral-600 bg-white dark:bg-zinc-800 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm dark:text-neutral-200">
                    </div>
                </div>
                <div class="flex justify-between items-center">
                    <div class="flex space-x-2">
                        <button wire:click="generateReport" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <flux:icon.document-text class="w-4 h-4 mr-2" />
                            Generate Laporan
                        </button>
                        <button wire:click="downloadAll" class="inline-flex items-center px-4 py-2 border border-neutral-300 dark:border-neutral-600 rounded-md shadow-sm text-sm font-medium text-neutral-700 dark:text-neutral-300 bg-white dark:bg-zinc-800 hover:bg-neutral-50 dark:hover:bg-zinc-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <flux:icon.arrow-down-tray class="w-4 h-4 mr-2" />
                            Download Semua
                        </button>
                    </div>
                    <div class="text-sm text-neutral-500 dark:text-neutral-400">
                        Total: {{ number_format($totalReports) }} data
                    </div>
                </div>
            </div>

            <!-- Summary Statistics -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-white dark:bg-zinc-900 overflow-hidden shadow-xl sm:rounded-lg p-4">
                    <div class="text-center">
                        <div class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ number_format($totalReports) }}</div>
                        <div class="text-sm text-neutral-500 dark:text-neutral-400">Total Data</div>
                    </div>
                </div>
                <div class="bg-white dark:bg-zinc-900 overflow-hidden shadow-xl sm:rounded-lg p-4">
                    <div class="text-center">
                        <div class="text-2xl font-bold text-green-600 dark:text-green-400">{{ number_format($avgNilai ?? 0, 2) }}</div>
                        <div class="text-sm text-neutral-500 dark:text-neutral-400">Rata-rata Nilai</div>
                    </div>
                </div>
                <div class="bg-white dark:bg-zinc-900 overflow-hidden shadow-xl sm:rounded-lg p-4">
                    <div class="text-center">
                        <div class="text-2xl font-bold text-purple-600 dark:text-purple-400">{{ number_format($maxNilai ?? 0, 2) }}</div>
                        <div class="text-sm text-neutral-500 dark:text-neutral-400">Nilai Maksimum</div>
                    </div>
                </div>
                <div class="bg-white dark:bg-zinc-900 overflow-hidden shadow-xl sm:rounded-lg p-4">
                    <div class="text-center">
                        <div class="text-2xl font-bold text-amber-600 dark:text-amber-400">{{ number_format($minNilai ?? 0, 2) }}</div>
                        <div class="text-sm text-neutral-500 dark:text-neutral-400">Nilai Minimum</div>
                    </div>
                </div>
            </div>

            <!-- Reports Data Section -->
            <div class="bg-white dark:bg-zinc-900 overflow-hidden shadow-xl sm:rounded-lg p-6 mb-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-medium text-neutral-900 dark:text-neutral-100">Data Laporan</h3>
                </div>
            
            <!-- Reports Data Table -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-neutral-200 dark:divide-neutral-700">
                    <thead class="bg-neutral-50 dark:bg-zinc-800">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-neutral-500 dark:text-neutral-400 uppercase tracking-wider">
                                ID
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-neutral-500 dark:text-neutral-400 uppercase tracking-wider">
                                Topik
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-neutral-500 dark:text-neutral-400 uppercase tracking-wider">
                                Variabel
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-neutral-500 dark:text-neutral-400 uppercase tracking-wider">
                                Klasifikasi
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-neutral-500 dark:text-neutral-400 uppercase tracking-wider">
                                Wilayah
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-neutral-500 dark:text-neutral-400 uppercase tracking-wider">
                                Nilai
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-neutral-500 dark:text-neutral-400 uppercase tracking-wider">
                                Tahun
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-neutral-500 dark:text-neutral-400 uppercase tracking-wider">
                                Status
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-zinc-900 divide-y divide-neutral-200 dark:divide-neutral-700">
                        @forelse($reportsData as $item)
                            <tr class="hover:bg-neutral-50 dark:hover:bg-zinc-800">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-neutral-900 dark:text-neutral-100">
                                    {{ $item->id }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-500 dark:text-neutral-400">
                                    {{ $item->topik->nama ?? '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-500 dark:text-neutral-400">
                                    {{ $item->variabel->nama ?? '-' }}
                                    @if($item->variabel && $item->variabel->satuan)
                                        <span class="text-xs text-neutral-400">({{ $item->variabel->satuan }})</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-500 dark:text-neutral-400">
                                    {{ $item->klasifikasi->nama ?? '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-500 dark:text-neutral-400">
                                    {{ $item->wilayah }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-500 dark:text-neutral-400">
                                    {{ number_format($item->nilai, 2) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-500 dark:text-neutral-400">
                                    {{ $item->tahun }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $statusColors = [
                                            'Aktif' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
                                            'Tidak Aktif' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
                                            'Draft' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
                                            'Arsip' => 'bg-neutral-100 text-neutral-800 dark:bg-neutral-900 dark:text-neutral-200',
                                            'Pending' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
                                        ];
                                        $colorClass = $statusColors[$item->status] ?? 'bg-neutral-100 text-neutral-800 dark:bg-neutral-900 dark:text-neutral-200';
                                    @endphp
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $colorClass }}">
                                        {{ $item->status }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-4 text-center text-sm text-neutral-500 dark:text-neutral-400">
                                    Tidak ada data laporan yang ditemukan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            </div>

            <!-- Pagination -->
            <div class="mt-4">
                <nav class="flex items-center justify-between">
                    <div class="flex-1 flex justify-between sm:hidden">
                        <button class="relative inline-flex items-center px-4 py-2 border border-neutral-300 dark:border-neutral-600 text-sm font-medium rounded-md text-neutral-700 dark:text-neutral-300 bg-white dark:bg-zinc-800 hover:bg-neutral-50 dark:hover:bg-zinc-700">
                            Sebelumnya
                        </button>
                        <button class="ml-3 relative inline-flex items-center px-4 py-2 border border-neutral-300 dark:border-neutral-600 text-sm font-medium rounded-md text-neutral-700 dark:text-neutral-300 bg-white dark:bg-zinc-800 hover:bg-neutral-50 dark:hover:bg-zinc-700">
                            Selanjutnya
                        </button>
                    </div>
                    <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                        <div>
                            <p class="text-sm text-neutral-700 dark:text-neutral-300">
                                Menampilkan
                                <span class="font-medium">1</span>
                                sampai
                                <span class="font-medium">10</span>
                                dari
                                <span class="font-medium">20</span>
                                hasil
                            </p>
                        </div>
                        <div>
                            <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px">
                                <button class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-neutral-300 dark:border-neutral-600 bg-white dark:bg-zinc-800 text-sm font-medium text-neutral-500 dark:text-neutral-400 hover:bg-neutral-50 dark:hover:bg-zinc-700">
                                    <span class="sr-only">Sebelumnya</span>
                                    <!-- Heroicon name: solid/chevron-left -->
                                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                                <button class="relative inline-flex items-center px-4 py-2 border border-neutral-300 dark:border-neutral-600 bg-white dark:bg-zinc-800 text-sm font-medium text-neutral-700 dark:text-neutral-300 hover:bg-neutral-50 dark:hover:bg-zinc-700">
                                    1
                                </button>
                                <button class="relative inline-flex items-center px-4 py-2 border border-neutral-300 dark:border-neutral-600 bg-white dark:bg-zinc-800 text-sm font-medium text-neutral-700 dark:text-neutral-300 hover:bg-neutral-50 dark:hover:bg-zinc-700">
                                    2
                                </button>
                                <button class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-neutral-300 dark:border-neutral-600 bg-white dark:bg-zinc-800 text-sm font-medium text-neutral-500 dark:text-neutral-400 hover:bg-neutral-50 dark:hover:bg-zinc-700">
                                    <span class="sr-only">Selanjutnya</span>
                                    <!-- Heroicon name: solid/chevron-right -->
                                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </nav>
                        </div>
                    </div>
                </nav>
            </div>
        </div>
    </div>
</div>
