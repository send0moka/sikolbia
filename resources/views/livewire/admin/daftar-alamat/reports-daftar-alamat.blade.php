<div>
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-neutral-900 dark:text-white">Laporan Daftar Alamat</h1>
        <p class="mt-1 text-sm text-neutral-600 dark:text-neutral-400">
            Generate dan analisis laporan data alamat dinas pertanian
        </p>
    </div>

    <!-- Report Configuration -->
    <div class="bg-white dark:!bg-neutral-800 rounded-lg shadow-sm border border-neutral-200 dark:border-neutral-700 p-6 mb-6">
        <h3 class="text-lg font-medium text-neutral-900 dark:text-white mb-4">Konfigurasi Laporan</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
            <div>
                <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">Jenis Laporan</label>
                <select wire:model.live="reportType" class="w-full px-3 py-2 border border-neutral-300 dark:border-neutral-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-neutral-700 dark:text-white">
                    <option value="summary">Ringkasan</option>
                    <option value="detail">Detail</option>
                    <option value="chart">Grafik</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">Tanggal Dari</label>
                <input wire:model.live="dateFrom" type="date" class="w-full px-3 py-2 border border-neutral-300 dark:border-neutral-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-neutral-700 dark:text-white" />
            </div>

            <div>
                <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">Tanggal Sampai</label>
                <input wire:model.live="dateTo" type="date" class="w-full px-3 py-2 border border-neutral-300 dark:border-neutral-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-neutral-700 dark:text-white" />
            </div>

            <div>
                <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">Status</label>
                <select wire:model.live="statusFilter" class="w-full px-3 py-2 border border-neutral-300 dark:border-neutral-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-neutral-700 dark:text-white">
                    <option value="">Semua Status</option>
                    @foreach($statusOptions as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">Kategori</label>
                <select wire:model.live="kategoriFilter" class="w-full px-3 py-2 border border-neutral-300 dark:border-neutral-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-neutral-700 dark:text-white">
                    <option value="">Semua Kategori</option>
                    @foreach($kategoriOptions as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">Wilayah</label>
                <select wire:model.live="wilayahFilter" class="w-full px-3 py-2 border border-neutral-300 dark:border-neutral-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-neutral-700 dark:text-white">
                    <option value="">Semua Wilayah</option>
                    @foreach($wilayahOptions as $wilayah)
                        <option value="{{ $wilayah }}">{{ $wilayah }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="flex justify-between items-center">
            <button wire:click="generateReport" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
                Generate Laporan
            </button>
            
            <div class="flex space-x-2">
                <button wire:click="exportExcel" class="inline-flex items-center px-4 py-2 bg-white dark:bg-neutral-700 border border-neutral-300 dark:border-neutral-600 rounded-md font-semibold text-xs text-neutral-700 dark:text-neutral-300 uppercase tracking-widest shadow-sm hover:bg-neutral-50 dark:hover:bg-neutral-600 focus:outline-none focus:border-blue-300 focus:ring ring-blue-200 active:text-neutral-800 active:bg-neutral-50 disabled:opacity-25 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Export Excel
                </button>
                <button wire:click="exportPdf" class="inline-flex items-center px-4 py-2 bg-white dark:bg-neutral-700 border border-neutral-300 dark:border-neutral-600 rounded-md font-semibold text-xs text-neutral-700 dark:text-neutral-300 uppercase tracking-widest shadow-sm hover:bg-neutral-50 dark:hover:bg-neutral-600 focus:outline-none focus:border-blue-300 focus:ring ring-blue-200 active:text-neutral-800 active:bg-neutral-50 disabled:opacity-25 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Export PDF
                </button>
            </div>
        </div>
    </div>

    <!-- Report Content -->
    @if($reportType === 'summary' && !empty($summaryData))
        <!-- Summary Report -->
        <div class="bg-white dark:!bg-neutral-800 rounded-lg shadow-sm border border-neutral-200 dark:border-neutral-700 p-6 mb-6">
            <h3 class="text-lg font-medium text-neutral-900 dark:text-white mb-6">Ringkasan Laporan</h3>
            
            <!-- Summary Stats -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
                <div class="bg-blue-50 dark:bg-blue-900/20 p-4 rounded-lg">
                    <div class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ number_format($summaryData['total_alamat']) }}</div>
                    <div class="text-sm text-blue-600 dark:text-blue-400">Total Alamat</div>
                </div>
                <div class="bg-green-50 dark:bg-green-900/20 p-4 rounded-lg">
                    <div class="text-2xl font-bold text-green-600 dark:text-green-400">{{ number_format($summaryData['total_aktif']) }}</div>
                    <div class="text-sm text-green-600 dark:text-green-400">Alamat Aktif</div>
                </div>
                <div class="bg-purple-50 dark:bg-purple-900/20 p-4 rounded-lg">
                    <div class="text-2xl font-bold text-purple-600 dark:text-purple-400">{{ number_format($summaryData['total_with_coordinates']) }}</div>
                    <div class="text-sm text-purple-600 dark:text-purple-400">Dengan Koordinat</div>
                </div>
                <div class="bg-orange-50 dark:bg-orange-900/20 p-4 rounded-lg">
                    <div class="text-2xl font-bold text-orange-600 dark:text-orange-400">{{ number_format($summaryData['total_provinsi']) }}</div>
                    <div class="text-sm text-orange-600 dark:text-orange-400">Total Provinsi</div>
                </div>
            </div>

            <!-- Breakdown Tables -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Status Breakdown -->
                <div>
                    <h4 class="font-medium text-neutral-900 dark:text-white mb-3">Berdasarkan Status</h4>
                    <div class="space-y-2">
                        @foreach($summaryData['status_breakdown'] as $status => $count)
                            <div class="flex justify-between items-center p-2 bg-neutral-50 dark:bg-neutral-700 rounded">
                                <span class="text-sm text-neutral-600 dark:text-neutral-400">{{ $status }}</span>
                                <span class="text-sm font-medium text-neutral-900 dark:text-white">{{ number_format($count) }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Kategori Breakdown -->
                <div>
                    <h4 class="font-medium text-neutral-900 dark:text-white mb-3">Berdasarkan Kategori</h4>
                    <div class="space-y-2">
                        @foreach($summaryData['kategori_breakdown'] as $kategori => $count)
                            <div class="flex justify-between items-center p-2 bg-neutral-50 dark:bg-neutral-700 rounded">
                                <span class="text-sm text-neutral-600 dark:text-neutral-400 truncate">{{ $kategori }}</span>
                                <span class="text-sm font-medium text-neutral-900 dark:text-white">{{ number_format($count) }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Wilayah Breakdown -->
                <div>
                    <h4 class="font-medium text-neutral-900 dark:text-white mb-3">Top 10 Wilayah</h4>
                    <div class="space-y-2">
                        @foreach($summaryData['wilayah_breakdown'] as $wilayah => $count)
                            <div class="flex justify-between items-center p-2 bg-neutral-50 dark:bg-neutral-700 rounded">
                                <span class="text-sm text-neutral-600 dark:text-neutral-400 truncate">{{ $wilayah }}</span>
                                <span class="text-sm font-medium text-neutral-900 dark:text-white">{{ number_format($count) }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if($reportType === 'detail' && !empty($detailData))
        <!-- Detail Report -->
        <div class="bg-white dark:!bg-neutral-800 rounded-lg shadow-sm border border-neutral-200 dark:border-neutral-700 overflow-hidden">
            <div class="p-6">
                <h3 class="text-lg font-medium text-neutral-900 dark:text-white mb-4">Laporan Detail</h3>
            </div>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-neutral-200 dark:divide-neutral-700">
                    <thead class="bg-neutral-50 dark:bg-neutral-900">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 dark:text-neutral-400 uppercase tracking-wider">No</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 dark:text-neutral-400 uppercase tracking-wider">Wilayah</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 dark:text-neutral-400 uppercase tracking-wider">Nama Dinas</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 dark:text-neutral-400 uppercase tracking-wider">Alamat</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 dark:text-neutral-400 uppercase tracking-wider">Kontak</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 dark:text-neutral-400 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 dark:text-neutral-400 uppercase tracking-wider">Kategori</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:!bg-neutral-800 divide-y divide-neutral-200 dark:divide-neutral-700">
                        @foreach($detailData as $alamat)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-900 dark:text-white">{{ $alamat->no }}</td>
                                <td class="px-6 py-4 text-sm text-neutral-900 dark:text-white">{{ $alamat->wilayah }}</td>
                                <td class="px-6 py-4 text-sm text-neutral-900 dark:text-white">{{ $alamat->nama_dinas }}</td>
                                <td class="px-6 py-4 text-sm text-neutral-500 dark:text-neutral-400 max-w-xs truncate">{{ $alamat->alamat }}</td>
                                <td class="px-6 py-4 text-sm text-neutral-500 dark:text-neutral-400">
                                    <div class="space-y-1">
                                        @if($alamat->telp)
                                            <div>{{ $alamat->telp }}</div>
                                        @endif
                                        @if($alamat->email)
                                            <div>{{ $alamat->email }}</div>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $alamat->status_badge }}">
                                        {{ $alamat->status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-neutral-500 dark:text-neutral-400">{{ $alamat->kategori }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    @if($reportType === 'chart' && !empty($chartData))
        <!-- Chart Report -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Status Chart -->
            <div class="bg-white dark:!bg-neutral-800 rounded-lg shadow-sm border border-neutral-200 dark:border-neutral-700 p-6">
                <h3 class="text-lg font-medium text-neutral-900 dark:text-white mb-4">Distribusi Status</h3>
                <div class="h-64 flex items-center justify-center bg-neutral-50 dark:bg-neutral-700 rounded-lg">
                    <div class="text-center">
                        <svg class="w-16 h-16 mx-auto text-neutral-400 dark:text-neutral-500 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                        <p class="text-sm text-neutral-600 dark:text-neutral-400">Chart.js atau library grafik lainnya</p>
                        <p class="text-xs text-neutral-500 dark:text-neutral-500">akan diintegrasikan di sini</p>
                    </div>
                </div>
                <div class="mt-4 space-y-2">
                    @foreach($chartData['status_chart'] as $item)
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="w-3 h-3 rounded-full mr-2" style="background-color: {{ $item['color'] }}"></div>
                                <span class="text-sm text-neutral-600 dark:text-neutral-400">{{ $item['label'] }}</span>
                            </div>
                            <span class="text-sm font-medium text-neutral-900 dark:text-white">{{ number_format($item['value']) }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Kategori Chart -->
            <div class="bg-white dark:!bg-neutral-800 rounded-lg shadow-sm border border-neutral-200 dark:border-neutral-700 p-6">
                <h3 class="text-lg font-medium text-neutral-900 dark:text-white mb-4">Distribusi Kategori</h3>
                <div class="h-64 flex items-center justify-center bg-neutral-50 dark:bg-neutral-700 rounded-lg">
                    <div class="text-center">
                        <svg class="w-16 h-16 mx-auto text-neutral-400 dark:text-neutral-500 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z" />
                        </svg>
                        <p class="text-sm text-neutral-600 dark:text-neutral-400">Pie Chart</p>
                        <p class="text-xs text-neutral-500 dark:text-neutral-500">distribusi kategori</p>
                    </div>
                </div>
                <div class="mt-4 space-y-2">
                    @foreach($chartData['kategori_chart'] as $item)
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="w-3 h-3 rounded-full mr-2" style="background-color: {{ $item['color'] }}"></div>
                                <span class="text-sm text-neutral-600 dark:text-neutral-400 truncate">{{ $item['label'] }}</span>
                            </div>
                            <span class="text-sm font-medium text-neutral-900 dark:text-white">{{ number_format($item['value']) }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Wilayah Chart -->
            <div class="lg:col-span-2 bg-white dark:!bg-neutral-800 rounded-lg shadow-sm border border-neutral-200 dark:border-neutral-700 p-6">
                <h3 class="text-lg font-medium text-neutral-900 dark:text-white mb-4">Top 10 Wilayah</h3>
                <div class="h-64 flex items-center justify-center bg-neutral-50 dark:bg-neutral-700 rounded-lg">
                    <div class="text-center">
                        <svg class="w-16 h-16 mx-auto text-neutral-400 dark:text-neutral-500 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                        <p class="text-sm text-neutral-600 dark:text-neutral-400">Bar Chart</p>
                        <p class="text-xs text-neutral-500 dark:text-neutral-500">distribusi per wilayah</p>
                    </div>
                </div>
                <div class="mt-4 grid grid-cols-2 gap-2">
                    @foreach($chartData['wilayah_chart'] as $item)
                        <div class="flex items-center justify-between p-2 bg-neutral-50 dark:bg-neutral-700 rounded">
                            <span class="text-sm text-neutral-600 dark:text-neutral-400 truncate">{{ $item['label'] }}</span>
                            <span class="text-sm font-medium text-neutral-900 dark:text-white">{{ number_format($item['value']) }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    @if(empty($summaryData) && empty($detailData) && empty($chartData))
        <!-- Empty State -->
        <div class="bg-white dark:!bg-neutral-800 rounded-lg shadow-sm border border-neutral-200 dark:border-neutral-700 p-12 text-center">
            <svg class="w-16 h-16 mx-auto text-neutral-300 dark:text-neutral-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2-2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
            </svg>
            <h3 class="text-lg font-medium text-neutral-900 dark:text-white mb-2">Belum Ada Laporan</h3>
            <p class="text-neutral-600 dark:text-neutral-400 mb-4">Klik "Generate Laporan" untuk membuat laporan berdasarkan filter yang dipilih</p>
            <button wire:click="generateReport" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
                Generate Laporan
            </button>
        </div>
    @endif
</div>
