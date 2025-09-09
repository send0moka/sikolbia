<div>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-6">
                <h2 class="font-semibold text-xl text-neutral-800 dark:text-neutral-200 leading-tight">
                    {{ __('Peramalan Iklim & OPT DPI') }}
                </h2>
            </div>

            <!-- Flash Messages -->
            @if (session()->has('message'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('message') }}
                </div>
            @endif

            <!-- Search and Filter Section -->
            <div class="bg-white dark:bg-zinc-900 overflow-hidden shadow-xl sm:rounded-lg p-6 mb-6">
                <div class="grid grid-cols-1 md:grid-cols-6 gap-4 mb-4">
                    <div>
                        <label for="search" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">Pencarian</label>
                        <input type="text" wire:model.live="search" placeholder="Cari wilayah, topik, variabel..." class="mt-1 block w-full py-2 px-3 border border-neutral-300 dark:border-neutral-600 bg-white dark:bg-zinc-800 text-neutral-900 dark:text-neutral-100 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    </div>
                    <div>
                        <label for="topik" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">Topik</label>
                        <select wire:model.live="selectedTopik" class="mt-1 block w-full py-2 px-3 border border-neutral-300 dark:border-neutral-600 bg-white dark:bg-zinc-800 text-neutral-900 dark:text-neutral-100 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <option value="">Semua Topik</option>
                            @foreach($topiks as $topik)
                                <option value="{{ $topik->id }}">{{ $topik->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="variabel" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">Variabel</label>
                        <select wire:model.live="selectedVariabel" class="mt-1 block w-full py-2 px-3 border border-neutral-300 dark:border-neutral-600 bg-white dark:bg-zinc-800 text-neutral-900 dark:text-neutral-100 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <option value="">Semua Variabel</option>
                            @foreach($variabels as $variabel)
                                <option value="{{ $variabel->id }}">{{ $variabel->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="klasifikasi" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">Klasifikasi</label>
                        <select wire:model.live="selectedKlasifikasi" class="mt-1 block w-full py-2 px-3 border border-neutral-300 dark:border-neutral-600 bg-white dark:bg-zinc-800 text-neutral-900 dark:text-neutral-100 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <option value="">Semua Klasifikasi</option>
                            @foreach($klasifikasis as $klasifikasi)
                                <option value="{{ $klasifikasi->id }}">{{ $klasifikasi->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="wilayah" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">Wilayah</label>
                        <select wire:model.live="selectedWilayah" class="mt-1 block w-full py-2 px-3 border border-neutral-300 dark:border-neutral-600 bg-white dark:bg-zinc-800 text-neutral-900 dark:text-neutral-100 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <option value="">Semua Wilayah</option>
                            @foreach($wilayahs as $wilayah)
                                <option value="{{ $wilayah }}">{{ $wilayah }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="periode" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">Periode</label>
                        <select wire:model.live="selectedPeriode" class="mt-1 block w-full py-2 px-3 border border-neutral-300 dark:border-neutral-600 bg-white dark:bg-zinc-800 text-neutral-900 dark:text-neutral-100 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <option value="1">1 Tahun Terakhir</option>
                            <option value="3">3 Tahun Terakhir</option>
                            <option value="6">6 Tahun Terakhir</option>
                            <option value="12">12 Tahun Terakhir</option>
                        </select>
                    </div>
                </div>
                <div class="flex justify-between items-center">
                    <div class="flex space-x-2">
                        <button wire:click="generateForecast" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <flux:icon.chart-bar class="w-4 h-4 mr-2" />
                            Generate Peramalan
                        </button>
                        <button wire:click="exportData" class="inline-flex items-center px-4 py-2 border border-neutral-300 dark:border-neutral-600 rounded-md shadow-sm text-sm font-medium text-neutral-700 dark:text-neutral-300 bg-white dark:bg-zinc-800 hover:bg-neutral-50 dark:hover:bg-zinc-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <flux:icon.arrow-down-tray class="w-4 h-4 mr-2" />
                            Ekspor Data
                        </button>
                    </div>
                    <div class="text-sm text-neutral-500 dark:text-neutral-400">
                        Total: {{ $data->total() }} data
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="bg-white dark:bg-zinc-900 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-neutral-900 dark:text-neutral-100 mb-6">Data Iklim & OPT DPI</h3>

                    <!-- Data Table -->
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
                                @forelse($data as $item)
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
                                            Tidak ada data yang ditemukan.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-6">
                        {{ $data->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
