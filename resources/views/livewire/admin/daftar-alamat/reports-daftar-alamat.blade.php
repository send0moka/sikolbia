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
                <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">Provinsi</label>
                <select wire:model.live="provinsiFilter" class="w-full px-3 py-2 border border-neutral-300 dark:border-neutral-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-neutral-700 dark:text-white">
                    <option value="">Semua Provinsi</option>
                    @foreach($provinsiOptions as $provinsi)
                        <option value="{{ $provinsi }}">{{ $provinsi }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">Kabupaten/Kota</label>
                <select wire:model.live="kabupatenKotaFilter" class="w-full px-3 py-2 border border-neutral-300 dark:border-neutral-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-neutral-700 dark:text-white">
                    <option value="">Semua Kabupaten/Kota</option>
                    @foreach($kabupatenKotaOptions as $kabupatenKota)
                        <option value="{{ $kabupatenKota }}">{{ $kabupatenKota }}</option>
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
                @if($reportType === 'chart')
                    <button onclick="location.reload()" class="inline-flex items-center px-3 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:outline-none transition ease-in-out duration-150">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        Refresh
                    </button>
                @endif
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

                <!-- Provinsi Breakdown -->
                <div>
                    <h4 class="font-medium text-neutral-900 dark:text-white mb-3">Berdasarkan Provinsi</h4>
                    <div class="space-y-2">
                        @foreach($summaryData['provinsi_breakdown'] as $provinsi => $count)
                            <div class="flex justify-between items-center p-2 bg-neutral-50 dark:bg-neutral-700 rounded">
                                <span class="text-sm text-neutral-600 dark:text-neutral-400 truncate">{{ $provinsi }}</span>
                                <span class="text-sm font-medium text-neutral-900 dark:text-white">{{ number_format($count) }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Kabupaten/Kota Breakdown -->
                <div>
                    <h4 class="font-medium text-neutral-900 dark:text-white mb-3">Top 10 Kabupaten/Kota</h4>
                    <div class="space-y-2">
                        @foreach($summaryData['kabupaten_kota_breakdown'] as $kabupatenKota => $count)
                            <div class="flex justify-between items-center p-2 bg-neutral-50 dark:bg-neutral-700 rounded">
                                <span class="text-sm text-neutral-600 dark:text-neutral-400 truncate">{{ $kabupatenKota }}</span>
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
                            <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 dark:text-neutral-400 uppercase tracking-wider">Provinsi</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 dark:text-neutral-400 uppercase tracking-wider">Kabupaten/Kota</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 dark:text-neutral-400 uppercase tracking-wider">Nama Dinas</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 dark:text-neutral-400 uppercase tracking-wider">Alamat</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 dark:text-neutral-400 uppercase tracking-wider">Kontak</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 dark:text-neutral-400 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:!bg-neutral-800 divide-y divide-neutral-200 dark:divide-neutral-700">
                        @foreach($detailData as $alamat)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-900 dark:text-white">{{ $alamat->no }}</td>
                                <td class="px-6 py-4 text-sm text-neutral-900 dark:text-white">{{ $alamat->provinsi }}</td>
                                <td class="px-6 py-4 text-sm text-neutral-900 dark:text-white">{{ $alamat->kabupaten_kota }}</td>
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
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    @if($reportType === 'chart' && !empty($chartData))
        <!-- Debug Info (Remove in production) -->
        @if(app()->environment('local'))
            <div class="mb-4 p-4 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg">
                <h4 class="font-medium text-yellow-800 dark:text-yellow-200 mb-2">Debug Info:</h4>
                <div class="text-xs text-yellow-700 dark:text-yellow-300 space-y-1">
                    <div>Total Records: {{ $chartData['total_records'] ?? 'N/A' }}</div>
                    <div>Status Data: {{ count($chartData['status_chart'] ?? []) }} items</div>
                    <div>Provinsi Data: {{ count($chartData['provinsi_chart'] ?? []) }} items</div>
                    <div>Kabupaten/Kota Data: {{ count($chartData['kabupaten_kota_chart'] ?? []) }} items</div>
                </div>
            </div>
        @endif

        {{-- Simple Fallback Chart bila Chart.js gagal --}}
        <div class="mb-6 p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg">
            <h4 class="font-medium text-blue-800 dark:text-blue-200 mb-3">📊 Laporan Data (Simple View)</h4>
            
            {{-- Status Distribution --}}
            <div class="mb-4">
                <h5 class="font-medium text-gray-700 dark:text-gray-300 mb-2">Distribusi Status:</h5>
                @if(!empty($chartData['status_chart']))
                    @foreach($chartData['status_chart'] as $item)
                        <div class="flex items-center mb-1">
                            <div class="w-4 h-4 bg-blue-500 rounded mr-2"></div>
                            <span class="text-sm">{{ $item['label'] ?? $item['status'] }}: {{ $item['total'] ?? $item['count'] ?? 0 }}</span>
                        </div>
                    @endforeach
                @endif
            </div>

            {{-- Provinsi Top 5 --}}
            <div class="mb-4">
                <h5 class="font-medium text-gray-700 dark:text-gray-300 mb-2">Top 5 Provinsi:</h5>
                @if(!empty($chartData['provinsi_chart']))
                    @foreach(array_slice($chartData['provinsi_chart'], 0, 5) as $item)
                        <div class="flex items-center justify-between mb-1">
                            <span class="text-sm">{{ $item['label'] ?? $item['provinsi'] }}</span>
                            <span class="text-sm font-medium">{{ $item['total'] ?? $item['count'] ?? 0 }}</span>
                        </div>
                    @endforeach
                @endif
            </div>

            {{-- Kab/Kota Top 5 --}}
            <div>
                <h5 class="font-medium text-gray-700 dark:text-gray-300 mb-2">Top 5 Kabupaten/Kota:</h5>
                @if(!empty($chartData['kabupaten_kota_chart']))
                    @foreach(array_slice($chartData['kabupaten_kota_chart'], 0, 5) as $item)
                        <div class="flex items-center justify-between mb-1">
                            <span class="text-sm">{{ $item['label'] ?? $item['kabupaten_kota'] }}</span>
                            <span class="text-sm font-medium">{{ $item['total'] ?? $item['count'] ?? 0 }}</span>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>

        <!-- Chart Report -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Status Chart -->
            <div class="bg-white dark:!bg-neutral-800 rounded-lg shadow-sm border border-neutral-200 dark:border-neutral-700 p-6">
                <h3 class="text-lg font-medium text-neutral-900 dark:text-white mb-4">Distribusi Status</h3>
                <div class="h-64 relative">
                    <div id="statusChartLoader" class="absolute inset-0 flex items-center justify-center bg-neutral-50 dark:bg-neutral-700 rounded-lg">
                        <div class="text-center">
                            <div class="animate-spin h-8 w-8 border-4 border-blue-600 border-t-transparent rounded-full mx-auto mb-2"></div>
                            <p class="text-sm text-neutral-600 dark:text-neutral-400">Loading...</p>
                        </div>
                    </div>
                    <canvas id="statusChart" class="w-full h-full"></canvas>
                </div>
                <div class="mt-4 space-y-2">
                    @if(!empty($chartData['status_chart']))
                        @foreach($chartData['status_chart'] as $item)
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="w-3 h-3 rounded-full mr-2" style="background-color: {{ $item['color'] }}"></div>
                                    <span class="text-sm text-neutral-600 dark:text-neutral-400">{{ $item['label'] }}</span>
                                </div>
                                <span class="text-sm font-medium text-neutral-900 dark:text-white">{{ number_format($item['value']) }}</span>
                            </div>
                        @endforeach
                    @else
                        <p class="text-center text-sm text-neutral-500 dark:text-neutral-400">Tidak ada data status</p>
                    @endif
                </div>
            </div>

            <!-- Provinsi Chart -->
            <div class="bg-white dark:!bg-neutral-800 rounded-lg shadow-sm border border-neutral-200 dark:border-neutral-700 p-6">
                <h3 class="text-lg font-medium text-neutral-900 dark:text-white mb-4">Distribusi Provinsi</h3>
                <div class="h-64 relative">
                    <div id="provinsiChartLoader" class="absolute inset-0 flex items-center justify-center bg-neutral-50 dark:bg-neutral-700 rounded-lg">
                        <div class="text-center">
                            <div class="animate-spin h-8 w-8 border-4 border-blue-600 border-t-transparent rounded-full mx-auto mb-2"></div>
                            <p class="text-sm text-neutral-600 dark:text-neutral-400">Loading...</p>
                        </div>
                    </div>
                    <canvas id="provinsiChart" class="w-full h-full"></canvas>
                </div>
                <div class="mt-4 space-y-2 max-h-32 overflow-y-auto">
                    @if(!empty($chartData['provinsi_chart']))
                        @foreach($chartData['provinsi_chart'] as $item)
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="w-3 h-3 rounded-full mr-2" style="background-color: {{ $item['color'] }}"></div>
                                    <span class="text-sm text-neutral-600 dark:text-neutral-400 truncate">{{ $item['label'] }}</span>
                                </div>
                                <span class="text-sm font-medium text-neutral-900 dark:text-white">{{ number_format($item['value']) }}</span>
                            </div>
                        @endforeach
                    @else
                        <p class="text-center text-sm text-neutral-500 dark:text-neutral-400">Tidak ada data provinsi</p>
                    @endif
                </div>
            </div>

            <!-- Kabupaten/Kota Chart -->
            <div class="lg:col-span-2 bg-white dark:!bg-neutral-800 rounded-lg shadow-sm border border-neutral-200 dark:border-neutral-700 p-6">
                <h3 class="text-lg font-medium text-neutral-900 dark:text-white mb-4">Top 10 Kabupaten/Kota</h3>
                <div class="h-80 relative">
                    <div id="kabupatenKotaChartLoader" class="absolute inset-0 flex items-center justify-center bg-neutral-50 dark:bg-neutral-700 rounded-lg">
                        <div class="text-center">
                            <div class="animate-spin h-8 w-8 border-4 border-blue-600 border-t-transparent rounded-full mx-auto mb-2"></div>
                            <p class="text-sm text-neutral-600 dark:text-neutral-400">Loading...</p>
                        </div>
                    </div>
                    <canvas id="kabupatenKotaChart" class="w-full h-full"></canvas>
                </div>
                <div class="mt-4 grid grid-cols-2 gap-2 max-h-40 overflow-y-auto">
                    @if(!empty($chartData['kabupaten_kota_chart']))
                        @foreach($chartData['kabupaten_kota_chart'] as $item)
                            <div class="flex items-center justify-between p-2 bg-neutral-50 dark:bg-neutral-700 rounded">
                                <span class="text-sm text-neutral-600 dark:text-neutral-400 truncate">{{ $item['label'] }}</span>
                                <span class="text-sm font-medium text-neutral-900 dark:text-white">{{ number_format($item['value']) }}</span>
                            </div>
                        @endforeach
                    @else
                        <div class="col-span-2 text-center">
                            <p class="text-sm text-neutral-500 dark:text-neutral-400">Tidak ada data kabupaten/kota</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Inline Script Test -->
        <script>
            // Direct inline test - no Livewire push
            console.log('� INLINE SCRIPT RUNNING');
            console.log('Chart data:', @json($chartData ?? []));
            
            // Immediate DOM check
            setTimeout(function() {
                console.log('🔍 Checking DOM elements...');
                
                const statusChart = document.getElementById('statusChart');
                const loader = document.getElementById('statusChartLoader');
                
                console.log('Elements found:', {
                    statusChart: !!statusChart,
                    loader: !!loader
                });
                
                if (statusChart) {
                    const ctx = statusChart.getContext('2d');
                    console.log('Canvas context:', !!ctx);
                    
                    // Draw test rectangle
                    ctx.fillStyle = '#FF0000';
                    ctx.fillRect(0, 0, 100, 100);
                    console.log('✅ Canvas test drawn');
                    
                    if (loader) {
                        loader.innerHTML = '<div class="text-green-600">✅ Canvas Working!</div>';
                    }
                }
                
                // Try to load Chart.js
                if (typeof Chart === 'undefined') {
                    console.log('📦 Loading Chart.js...');
                    const script = document.createElement('script');
                    script.src = 'https://cdn.jsdelivr.net/npm/chart.js';
                    script.onload = function() {
                        console.log('✅ Chart.js loaded!');
                        createSimpleChart();
                    };
                    script.onerror = function() {
                        console.error('❌ Chart.js failed to load');
                        if (loader) loader.innerHTML = '<div class="text-red-600">❌ Chart.js Load Failed</div>';
                    };
                    document.head.appendChild(script);
                } else {
                    console.log('✅ Chart.js already available');
                    createSimpleChart();
                }
            }, 1000);
            
            function createSimpleChart() {
                console.log('🎨 Creating chart...');
                const testData = @json($chartData ?? []);
                const statusChart = document.getElementById('statusChart');
                const loader = document.getElementById('statusChartLoader');
                
                if (statusChart && testData.status_chart && testData.status_chart.length > 0) {
                    try {
                        const ctx = statusChart.getContext('2d');
                        new Chart(ctx, {
                            type: 'doughnut',
                            data: {
                                labels: testData.status_chart.map(item => item.label || 'Unknown'),
                                datasets: [{
                                    data: testData.status_chart.map(item => item.total || item.value || 0),
                                    backgroundColor: ['#3B82F6', '#10B981', '#F59E0B', '#EF4444']
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false
                            }
                        });
                        
                        if (loader) loader.style.display = 'none';
                        console.log('✅ Chart created successfully!');
                    } catch (error) {
                        console.error('❌ Chart error:', error);
                        if (loader) loader.innerHTML = '<div class="text-red-600">❌ Chart Error: ' + error.message + '</div>';
                    }
                } else {
                    console.log('❌ No data or canvas');
                    if (loader) loader.innerHTML = '<div class="text-yellow-600">⚠️ No Data</div>';
                }
            }
        </script>

        @push('scripts')
        <script>
            // This might not run due to Livewire conflicts
            console.log('🚫 Push script running (might be blocked)');
        </script>
                    const loader = document.getElementById(id);
                    if (loader) loader.style.display = 'none';
                });
            }

            function initializeCharts() {
                console.log('Starting chart initialization...');
                
                if (typeof Chart === 'undefined') {
                    console.error('Chart.js not available');
                    hideAllLoaders();
                    return;
                }

                // Hide all loaders first
                hideAllLoaders();

                // Chart data
                const statusData = @json($chartData['status_chart'] ?? []);
                const provinsiData = @json($chartData['provinsi_chart'] ?? []);
                const kabupatenKotaData = @json($chartData['kabupaten_kota_chart'] ?? []);

                console.log('Data:', { statusData, provinsiData, kabupatenKotaData });

                // Theme colors
                const isDark = document.documentElement.classList.contains('dark');
                const textColor = isDark ? '#e5e7eb' : '#374151';
                const gridColor = isDark ? '#374151' : '#e5e7eb';

                // Status Chart
                const statusCtx = document.getElementById('statusChart');
                if (statusCtx && statusData.length > 0) {
                    try {
                        new Chart(statusCtx, {
                            type: 'doughnut',
                            data: {
                                labels: statusData.map(item => item.label),
                                datasets: [{
                                    data: statusData.map(item => item.value),
                                    backgroundColor: statusData.map(item => item.color),
                                    borderWidth: 2
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: {
                                    legend: { display: false },
                                    tooltip: {
                                        callbacks: {
                                            label: function(context) {
                                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                                const percentage = ((context.parsed * 100) / total).toFixed(1);
                                                return context.label + ': ' + context.parsed + ' (' + percentage + '%)';
                                            }
                                        }
                                    }
                                }
                            }
                        });
                        console.log('Status chart created');
                    } catch (e) {
                        console.error('Status chart error:', e);
                    }
                }

                // Provinsi Chart
                const provinsiCtx = document.getElementById('provinsiChart');
                if (provinsiCtx && provinsiData.length > 0) {
                    try {
                        new Chart(provinsiCtx, {
                            type: 'pie',
                            data: {
                                labels: provinsiData.map(item => item.label),
                                datasets: [{
                                    data: provinsiData.map(item => item.value),
                                    backgroundColor: provinsiData.map(item => item.color),
                                    borderWidth: 2
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: {
                                    legend: { display: false },
                                    tooltip: {
                                        callbacks: {
                                            label: function(context) {
                                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                                const percentage = ((context.parsed * 100) / total).toFixed(1);
                                                return context.label + ': ' + context.parsed + ' (' + percentage + '%)';
                                            }
                                        }
                                    }
                                }
                            }
                        });
                        console.log('Provinsi chart created');
                    } catch (e) {
                        console.error('Provinsi chart error:', e);
                    }
                }

                // Kabupaten/Kota Chart
                const kabupatenCtx = document.getElementById('kabupatenKotaChart');
                if (kabupatenCtx && kabupatenKotaData.length > 0) {
                    try {
                        new Chart(kabupatenCtx, {
                            type: 'bar',
                            data: {
                                labels: kabupatenKotaData.map(item => item.label),
                                datasets: [{
                                    data: kabupatenKotaData.map(item => item.value),
                                    backgroundColor: 'rgba(59, 130, 246, 0.6)',
                                    borderColor: 'rgba(59, 130, 246, 1)',
                                    borderWidth: 1
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                indexAxis: 'y',
                                plugins: {
                                    legend: { display: false }
                                },
                                scales: {
                                    x: {
                                        beginAtZero: true,
                                        ticks: { color: textColor },
                                        grid: { color: gridColor }
                                    },
                                    y: {
                                        ticks: { color: textColor },
                                        grid: { color: gridColor }
                                    }
                                }
                            }
                        });
                        console.log('Kabupaten chart created');
                    } catch (e) {
                        console.error('Kabupaten chart error:', e);
                    }
                }

                console.log('Chart initialization completed');
            }

            // Livewire update handling
            document.addEventListener('livewire:updated', function() {
                console.log('Livewire updated, reinitializing charts...');
                setTimeout(initializeCharts, 200);
            });

            // Force hide loaders after 3 seconds
            setTimeout(function() {
                console.log('Force hiding loaders after timeout');
                hideAllLoaders();
            }, 3000);
        </script>
        @endpush
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
