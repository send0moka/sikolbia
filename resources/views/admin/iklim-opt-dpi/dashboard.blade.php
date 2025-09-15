<div>
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-neutral-900 dark:text-white">Dashboard Iklim & OPT DPI</h1>
        <p class="mt-1 text-sm text-neutral-600 dark:text-neutral-400">
            Selamat datang di sistem monitoring iklim dan organisme pengganggu tanaman
        </p>
    </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 lg:grid-cols-2 gap-4 lg:gap-6 mb-8">
            <!-- Current User Info -->
            <div class="bg-white dark:!bg-neutral-800 overflow-hidden shadow-lg rounded-lg border border-neutral-200 dark:border-neutral-700">
                <div class="p-4 lg:p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 lg:w-12 lg:h-12 bg-green-100 dark:bg-green-900 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 lg:w-6 lg:h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                        </div>
                        <div class="ml-3 lg:ml-4 min-w-0 flex-1">
                            <h3 class="text-base lg:text-lg font-semibold text-neutral-900 dark:text-white truncate">Status akun</h3>
                            <p class="text-2xl lg:text-3xl font-bold text-green-600 dark:text-green-400 truncate">{{ auth()->user()->name }}</p>
                            <p class="text-xs lg:text-sm text-neutral-500 dark:text-neutral-400 truncate">
                                {{ ucfirst(auth()->user()->roles->first()?->name ?? 'No Role') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Data Iklim -->
            <div class="bg-white dark:!bg-neutral-800 overflow-hidden shadow-lg rounded-lg border border-neutral-200 dark:border-neutral-700">
                <div class="p-4 lg:p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 lg:w-12 lg:h-12 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 lg:w-6 lg:h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                                </svg>
                            </div>
                        </div>
                        <div class="ml-3 lg:ml-4 min-w-0 flex-1">
                            <h3 class="text-base lg:text-lg font-semibold text-neutral-900 dark:text-white truncate">Total Data Iklim</h3>
                            <p class="text-2xl lg:text-3xl font-bold text-blue-600 dark:text-blue-400">{{ number_format($totalData) }}</p>
                            <p class="text-xs lg:text-sm text-neutral-500 dark:text-neutral-400 truncate">Data terdaftar</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Produksi -->
            <div class="bg-white dark:!bg-neutral-800 overflow-hidden shadow-lg rounded-lg border border-neutral-200 dark:border-neutral-700">
                <div class="p-4 lg:p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 lg:w-12 lg:h-12 bg-purple-100 dark:bg-purple-900 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 lg:w-6 lg:h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                </svg>
                            </div>
                        </div>
                        <div class="ml-3 lg:ml-4 min-w-0 flex-1">
                            <h3 class="text-base lg:text-lg font-semibold text-neutral-900 dark:text-white truncate">Total Topik</h3>
                            <p class="text-2xl lg:text-3xl font-bold text-purple-600 dark:text-purple-400">{{ number_format($totalTopik) }}</p>
                            <p class="text-xs lg:text-sm text-neutral-500 dark:text-neutral-400 truncate">Kategori topik</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Status Lahan -->
            <div class="bg-white dark:!bg-neutral-800 overflow-hidden shadow-lg rounded-lg border border-neutral-200 dark:border-neutral-700">
                <div class="p-4 lg:p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 lg:w-12 lg:h-12 bg-amber-100 dark:bg-amber-900 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 lg:w-6 lg:h-6 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                        </div>
                        <div class="ml-3 lg:ml-4 min-w-0 flex-1">
                            <h3 class="text-base lg:text-lg font-semibold text-neutral-900 dark:text-white truncate">Data Aktif</h3>
                            <p class="text-2xl lg:text-3xl font-bold text-amber-600 dark:text-amber-400">{{ $activePercent }}</p>
                            <p class="text-xs lg:text-sm text-neutral-500 dark:text-neutral-400 truncate">Status aktif</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Iklim & OPT-DPI Overview Grid -->
        <div class="grid auto-rows-min gap-4 md:grid-cols-3 mb-6">
            <!-- Yearly Trend Chart Placeholder -->
            <div class="relative overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 bg-transparent p-4 h-64">
                <h3 class="text-lg font-semibold text-neutral-900 dark:text-white">Tren Tahunan</h3>
                <p class="text-sm text-neutral-600 dark:text-neutral-400">Ringkasan data per tahun</p>
                <!-- add a little inner padding to prevent the filled area and axes from being clipped -->
                <div class="mt-3 relative h-40 pt-2 pb-2 px-2">
                    <canvas id="yearlyChart" class="w-full h-full block"></canvas>
                </div>
            </div>

            <!-- Topik Averages Card with toggle -->
            <div class="relative overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 bg-transparent p-4 h-64">
                <div>
                    <div class="flex items-start justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-neutral-900 dark:text-white">Rata-rata Nilai per Topik</h3>
                            <p class="text-sm text-neutral-600 dark:text-neutral-400">Topik teratas berdasarkan jumlah data dan rata-rata nilai</p>
                        </div>
                        <div class="ml-4">
                            <div id="topikSegment" class="inline-flex rounded-md bg-neutral-50 dark:bg-neutral-800 border">
                                <button id="btnChart" class="px-3 py-1.5 text-sm font-medium rounded-l-md bg-neutral-900 text-white">Chart</button>
                                <button id="btnData" class="px-3 py-1.5 text-sm font-medium rounded-r-md">Data</button>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <div id="topikViewWrapper" class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                            <div id="topikListContainer" class="h-44 pr-2 pt-1">
                                <div class="space-y-3">
                                    @foreach($topikStats as $t)
                                        <div class="flex items-center justify-between">
                                            <div class="min-w-0">
                                                <p class="text-sm font-medium text-neutral-900 dark:text-white truncate">{{ is_object($t) ? ($t->topik_name ?? $t->topik_name) : ($t['topik_name'] ?? $t['topik_name']) }}</p>
                                                <p class="text-xs text-neutral-500 dark:text-neutral-400">Total: {{ is_object($t) ? $t->total : $t['total'] }}</p>
                                            </div>
                                            <div class="text-right">
                                                <p class="text-sm font-semibold text-blue-600 dark:text-blue-400">{{ number_format(is_object($t) ? $t->avg_nilai : $t['avg_nilai'], 2) }}</p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <div id="topikChartContainer" class="relative h-44 transition-all duration-200">
                                <canvas id="topikChart" class="w-full h-full block"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Wilayah Top Stats -->
            <div class="relative overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 bg-transparent p-4 h-64">
                <h3 class="text-lg font-semibold text-neutral-900 dark:text-white">Top Wilayah (Berdasarkan Jumlah Data)</h3>
                <p class="text-sm text-neutral-600 dark:text-neutral-400">Wilayah dengan jumlah pengamatan terbanyak</p>
                <div class="mt-4 space-y-2 h-44">
                    @foreach($wilayahStats as $idx => $w)
                        @if($idx < 5)
                            <div class="flex items-center justify-between">
                                <div class="min-w-0">
                                    <p class="text-sm font-medium text-neutral-900 dark:text-white truncate">{{ $w['name'] ?? ('Wilayah '.($w['id'] ?? '-')) }}</p>
                                    <p class="text-xs text-neutral-500 dark:text-neutral-400">ID: {{ $w['id'] ?? '-' }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-semibold text-neutral-900 dark:text-white">{{ $w['total'] }}</p>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Recent Data Table -->
        <div class="mb-6">
            <h2 class="text-lg font-semibold text-neutral-900 dark:text-white mb-3">Data Terbaru</h2>
            <div class="mb-2 text-sm">
                <a href="{{ route('admin.iklim-opt-dpi.kelola') }}" class="text-blue-600">Lihat semua data →</a>
            </div>
            <div class="bg-white dark:!bg-neutral-800 rounded-lg border border-neutral-200 dark:border-neutral-700 overflow-hidden">
                <div class="p-4 overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead>
                            <tr class="text-xs text-neutral-500 uppercase">
                                <th class="px-3 py-2">#</th>
                                <th class="px-3 py-2">Tahun</th>
                                <th class="px-3 py-2">Topik</th>
                                <th class="px-3 py-2">Variabel</th>
                                <th class="px-3 py-2">Wilayah</th>
                                <th class="px-3 py-2">Nilai</th>
                                <th class="px-3 py-2">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentData as $idx => $d)
                                <tr class="border-t border-neutral-100 dark:border-neutral-700">
                                    <td class="px-3 py-2">{{ $idx + 1 }}</td>
                                    <td class="px-3 py-2">{{ $d->tahun }}</td>
                                    <td class="px-3 py-2">{{ $d->topik?->deskripsi ?? $d->iklimoptdpiTopik?->nama ?? '-' }}</td>
                                    <td class="px-3 py-2">{{ $d->variabel?->deskripsi ?? $d->iklimoptdpiVariabel?->deskripsi ?? '-' }}</td>
                                    <td class="px-3 py-2">{{ $d->wilayah?->nama ?? $d->wilayah ?? '-' }}</td>
                                    <td class="px-3 py-2">{{ $d->nilai }}</td>
                                    <td class="px-3 py-2">{{ $d->status }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        @push('scripts')
            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
            <script>
                (function() {
                    // Yearly chart data (ensure earliest -> latest)
                    @php
                        // make a sorted copy by tahun ascending to guarantee correct chart order
                        $sortedYearly = $yearlyData->sortBy(fn($r) => $r->tahun ?? ($r['tahun'] ?? null))->values();
                    @endphp
                    const yearlyLabels = @json($sortedYearly->pluck('tahun'));
                    const yearlyValues = @json($sortedYearly->pluck('total'));

                    const ctxYear = document.getElementById('yearlyChart');
                    let yearlyChartInstance = null;
                    if (ctxYear) {
                        // compute a proportional padding around min/max (5%) instead of fixed 10k rounding
                        const numericVals = yearlyValues.map(v => Number(v) || 0);
                        const rawMin = Math.min(...numericVals);
                        const rawMax = Math.max(...numericVals);
                        const pad = Math.max(1, (rawMax - rawMin) * 0.05);
                        const suggestedMin = Math.max(0, Math.floor((rawMin - pad)));
                        const suggestedMax = Math.ceil(rawMax + pad);

                        yearlyChartInstance = new Chart(ctxYear.getContext('2d'), {
                            type: 'line',
                            data: {
                                labels: yearlyLabels,
                                datasets: [{
                                    label: 'Jumlah Data',
                                    data: numericVals,
                                    borderColor: '#3B82F6',
                                    backgroundColor: 'rgba(59,130,246,0.16)',
                                    fill: true,
                                    tension: 0.2,
                                    pointRadius: 3,
                                    pointHoverRadius: 5,
                                    borderWidth: 2
                                }]
                            },
                                options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                layout: {
                                    padding: { top: 8, bottom: 14, left: 4, right: 4 }
                                },
                                scales: {
                                    y: {
                                        suggestedMin: suggestedMin,
                                        suggestedMax: suggestedMax,
                                        ticks: {
                                            // show ticks in compact form (e.g., 38k)
                                            callback: function(value) {
                                                if (value >= 1000) return (value/1000) + 'k';
                                                return value;
                                            }
                                        },
                                        grid: { color: 'rgba(255,255,255,0.03)' }
                                    },
                                    x: {
                                        grid: { display: false }
                                    }
                                },
                                plugins: {
                                    legend: { display: true, position: 'top' }
                                }
                            }
                        });
                    }

                    // Topik avg chart
                    const topikLabels = @json($topikStats->map(fn($t) => is_object($t) ? $t->topik_name : $t['topik_name']));
                    const topikValues = @json($topikStats->map(fn($t) => is_object($t) ? (float)$t->avg_nilai : (float)$t['avg_nilai']));
                    const ctxTopikEl = document.getElementById('topikChart');
                    let topikChartInstance = null;
                    if (ctxTopikEl) {
                        const ctxTopik = ctxTopikEl.getContext('2d');
                        topikChartInstance = new Chart(ctxTopik, {
                            type: 'bar',
                            data: {
                                labels: topikLabels,
                                datasets: [{
                                    label: 'Rata-rata Nilai',
                                    data: topikValues,
                                    backgroundColor: '#8B5CF6'
                                }]
                            },
                            options: { responsive: true, maintainAspectRatio: false }
                        });
                    }

                    // Topik view controls
                    const chartContainer = document.getElementById('topikChartContainer');
                    const listContainer = document.getElementById('topikListContainer');
                    const viewWrapper = document.getElementById('topikViewWrapper');

                    // Modes: 'chart', 'data' (default to chart on first render)
                    let mode = 'chart';
                    const btnChart = document.getElementById('btnChart');
                    const btnData = document.getElementById('btnData');
                    function applyMode() {
                        if (!chartContainer || !listContainer || !viewWrapper) return;

                        if (mode === 'chart') {
                            // show chart full width
                            listContainer.classList.add('hidden', 'lg:hidden');
                            chartContainer.classList.remove('hidden');
                            chartContainer.classList.add('lg:col-span-2');
                            viewWrapper.classList.remove('lg:grid-cols-2');
                            viewWrapper.classList.add('grid-cols-1');
                            // style active button
                            if (btnChart) btnChart.classList.add('bg-neutral-900','text-white');
                            if (btnData) btnData.classList.remove('bg-neutral-900','text-white');
                            // resize charts after layout change
                            setTimeout(() => { if (topikChartInstance) topikChartInstance.resize(); if (yearlyChartInstance) yearlyChartInstance.resize(); }, 200);
                        } else if (mode === 'data') {
                            // show data list full width
                            listContainer.classList.remove('hidden', 'lg:hidden');
                            chartContainer.classList.add('hidden');
                            viewWrapper.classList.remove('lg:grid-cols-2');
                            viewWrapper.classList.add('grid-cols-1');
                            if (btnChart) btnChart.classList.remove('bg-neutral-900','text-white');
                            if (btnData) btnData.classList.add('bg-neutral-900','text-white');
                        }
                    }


                    // Button handlers for segmented control
                    if (btnChart) btnChart.addEventListener('click', function() { mode = 'chart'; applyMode(); });
                    if (btnData) btnData.addEventListener('click', function() { mode = 'data'; applyMode(); });

                    // Adjust mode on resize: restore 'both' on large screens
                    window.addEventListener('resize', function() {
                        if (window.innerWidth >= 1024 && mode !== 'both') {
                            mode = 'both';
                            applyMode();
                        }
                        if (window.innerWidth < 1024 && mode === 'both') {
                            mode = 'chart';
                            applyMode();
                        }
                    });

                    // initial apply
                    applyMode();
                })();
            </script>
        @endpush

        <!-- Feature Cards Section (Iklim & OPT-DPI) -->
        <div class="mt-8">
            <h2 class="text-xl font-semibold text-neutral-900 dark:text-white mb-4">Fitur Utama Iklim & OPT-DPI</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Data Iklim Card -->
                <div class="bg-white dark:!bg-neutral-800 rounded-lg border border-neutral-200 dark:border-neutral-700 p-6">
                    <div class="flex items-center space-x-4">
                        <div class="p-3 bg-blue-100 dark:bg-blue-900 rounded-lg">
                            <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-neutral-900 dark:text-white">Data Iklim</h3>
                            <p class="text-neutral-600 dark:text-neutral-400">Kelola data pengamatan iklim dan OPT</p>
                        </div>
                    </div>
                    <div class="mt-4">
                        <a href="{{ route('admin.iklim-opt-dpi.kelola') }}" class="text-blue-600 hover:text-blue-800 font-medium">Akses Data →</a>
                    </div>
                </div>

                <!-- Topik Management Card -->
                <div class="bg-white dark:!bg-neutral-800 rounded-lg border border-neutral-200 dark:border-neutral-700 p-6">
                    <div class="flex items-center space-x-4">
                        <div class="p-3 bg-purple-100 dark:bg-purple-900 rounded-lg">
                            <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-neutral-900 dark:text-white">Topik</h3>
                            <p class="text-neutral-600 dark:text-neutral-400">Kelola kategori topik</p>
                        </div>
                    </div>
                    <div class="mt-4">
                        <a href="{{ route('admin.iklim-opt-dpi.topik') }}" class="text-purple-600 hover:text-purple-800 font-medium">Lihat Topik →</a>
                    </div>
                </div>

                <!-- Variabel & Klasifikasi Card -->
                <div class="bg-white dark:!bg-neutral-800 rounded-lg border border-neutral-200 dark:border-neutral-700 p-6">
                    <div class="flex items-center space-x-4">
                        <div class="p-3 bg-amber-100 dark:bg-amber-900 rounded-lg">
                            <svg class="w-6 h-6 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-neutral-900 dark:text-white">Variabel & Klasifikasi</h3>
                            <p class="text-neutral-600 dark:text-neutral-400">Kelola variabel dan klasifikasi OPT</p>
                        </div>
                    </div>
                    <div class="mt-4 flex space-x-4">
                        <a href="{{ route('admin.iklim-opt-dpi.variabel') }}" class="text-amber-600 hover:text-amber-800 font-medium">Variabel →</a>
                        <a href="{{ route('admin.iklim-opt-dpi.klasifikasi') }}" class="text-amber-600 hover:text-amber-800 font-medium">Klasifikasi →</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions (Iklim) -->
        <div class="mt-8">
            <h2 class="text-xl font-semibold text-neutral-900 dark:text-white mb-4">Aksi Cepat</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <a href="{{ route('admin.iklim-opt-dpi.kelola') }}" class="inline-flex items-center justify-center w-full px-4 py-3 bg-neutral-700 hover:bg-neutral-600 text-white rounded-md">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                    Tambah Data Iklim
                </a>

                <a href="{{ route('admin.iklim-opt-dpi.reports') }}" class="inline-flex items-center justify-center w-full px-4 py-3 bg-neutral-700 hover:bg-neutral-600 text-white rounded-md">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4-4v12"/></svg>
                    Import Iklim
                </a>

                <a href="{{ route('admin.iklim-opt-dpi.reports') }}" class="inline-flex items-center justify-center w-full px-4 py-3 bg-neutral-700 hover:bg-neutral-600 text-white rounded-md">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4-4m0 0l-4 4m4-4v12"/></svg>
                    Export Iklim
                </a>

                <a href="#" class="inline-flex items-center justify-center w-full px-4 py-3 bg-neutral-700 hover:bg-neutral-600 text-white rounded-md">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    Generate Laporan Iklim
                </a>
            </div>
        </div>
    </div>
</div>