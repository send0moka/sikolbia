<div>
    <!-- Header -->
    <div class="mb-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-semibold text-neutral-900 dark:text-white">Statistik Lahan</h1>
                <p class="mt-1 text-sm text-neutral-600 dark:text-neutral-400">
                    Analisis statistik dan visualisasi data lahan pertanian
                </p>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="mb-6 flex flex-col sm:flex-row gap-4">
        <div class="flex-1">
            <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">Filter Tahun</label>
            <select wire:model.live="selectedYear" class="w-full text-sm rounded-md border-neutral-300 dark:border-neutral-600 dark:bg-neutral-800 dark:text-neutral-200 focus:ring-accent focus:border-accent">
                <option value="">Semua Tahun</option>
                @foreach($years as $year)
                    <option value="{{ $year }}">{{ $year }}</option>
                @endforeach
            </select>
        </div>
        <div class="flex-1">
            <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">Filter Topik</label>
            <select wire:model.live="selectedTopik" class="w-full text-sm rounded-md border-neutral-300 dark:border-neutral-600 dark:bg-neutral-800 dark:text-neutral-200 focus:ring-accent focus:border-accent">
                <option value="">Semua Topik</option>
                @foreach($topiks as $topik)
                    <option value="{{ $topik->id }}">{{ $topik->nama }}</option>
                @endforeach
            </select>
        </div>
        <div class="flex-1">
            <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">Filter Variabel</label>
            <select wire:model.live="selectedVariabel" class="w-full text-sm rounded-md border-neutral-300 dark:border-neutral-600 dark:bg-neutral-800 dark:text-neutral-200 focus:ring-accent focus:border-accent">
                <option value="">Semua Variabel</option>
                @foreach($variabels as $v)
                    <option value="{{ $v->id }}">{{ $v->deskripsi ?? $v->nama }}</option>
                @endforeach
            </select>
        </div>
        <div class="flex-1">
            <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">Filter Klasifikasi</label>
            <select wire:model.live="selectedKlasifikasi" class="w-full text-sm rounded-md border-neutral-300 dark:border-neutral-600 dark:bg-neutral-800 dark:text-neutral-200 focus:ring-accent focus:border-accent">
                <option value="">Semua Klasifikasi</option>
                @foreach($klasifikasis as $k)
                    <option value="{{ $k->id }}">{{ $k->deskripsi ?? $k->nama }}</option>
                @endforeach
            </select>
        </div>
        <div class="flex-1">
            <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">Filter Wilayah</label>
            <select wire:model.live="selectedWilayah" class="w-full text-sm rounded-md border-neutral-300 dark:border-neutral-600 dark:bg-neutral-800 dark:text-neutral-200 focus:ring-accent focus:border-accent">
                <option value="">Semua Wilayah</option>
                @foreach($wilayahs as $w)
                    <option value="{{ $w->id }}">{{ $w->nama }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="mb-6 grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white dark:bg-neutral-800 p-6 rounded-lg border border-neutral-200 dark:border-neutral-700">
            <div class="flex items-center">
                <div class="p-2 bg-blue-100 dark:bg-blue-900/30 rounded-lg">
                    <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-neutral-600 dark:text-neutral-400">Total Data</p>
                    <p class="text-2xl font-semibold text-neutral-900 dark:text-white">{{ number_format($totalData) }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-neutral-800 p-6 rounded-lg border border-neutral-200 dark:border-neutral-700">
            <div class="flex items-center">
                <div class="p-2 bg-green-100 dark:bg-green-900/30 rounded-lg">
                    <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-neutral-600 dark:text-neutral-400">Total Nilai</p>
                    <p class="text-2xl font-semibold text-neutral-900 dark:text-white">{{ number_format($totalValue, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-neutral-800 p-6 rounded-lg border border-neutral-200 dark:border-neutral-700">
            <div class="flex items-center">
                <div class="p-2 bg-purple-100 dark:bg-purple-900/30 rounded-lg">
                    <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-neutral-600 dark:text-neutral-400">Rata-rata</p>
                    <p class="text-2xl font-semibold text-neutral-900 dark:text-white">{{ number_format($averageValue, 2, ',', '.') }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-neutral-800 p-6 rounded-lg border border-neutral-200 dark:border-neutral-700">
            <div class="flex items-center">
                <div class="p-2 {{ $growthRate >= 0 ? 'bg-green-100 dark:bg-green-900/30' : 'bg-red-100 dark:bg-red-900/30' }} rounded-lg">
                    <svg class="w-6 h-6 {{ $growthRate >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        @if($growthRate >= 0)
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                        @else
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"></path>
                        @endif
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-neutral-600 dark:text-neutral-400">Pertumbuhan</p>
                    <p class="text-2xl font-semibold {{ $growthRate >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                        {{ number_format($growthRate, 1) }}%
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row 1 -->
    <div class="mb-6 grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Yearly Trends Chart -->
        <div class="bg-white dark:bg-neutral-800 p-6 rounded-lg border border-neutral-200 dark:border-neutral-700" x-data="yearlyTrendsChart()" x-init="initChart()">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-neutral-900 dark:text-white">Tren Tahunan</h3>
                <div class="flex items-center space-x-2">
                    <button @click="toggleDataset('avg')" :class="{'bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400': showAvg, 'bg-neutral-100 dark:bg-neutral-700 text-neutral-600 dark:text-neutral-400': !showAvg}" class="px-3 py-1 text-xs rounded-md transition-colors">Rata-rata</button>
                    <button @click="toggleDataset('count')" :class="{'bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400': showCount, 'bg-neutral-100 dark:bg-neutral-700 text-neutral-600 dark:text-neutral-400': !showCount}" class="px-3 py-1 text-xs rounded-md transition-colors">Jumlah Data</button>
                </div>
            </div>
            <div class="h-64">
                <canvas id="yearlyTrendsChart" x-ref="chart"></canvas>
            </div>
            <div x-ref="yearlyData" style="display:none">@json($yearlyTrends)</div>
            @if(count($yearlyTrends) > 0)
            <div class="mt-2 text-xs text-neutral-500 dark:text-neutral-400 text-right">
                Data dari {{ count($yearlyTrends) }} tahun
            </div>
            @endif
            
            <script>
            function yearlyTrendsChart() {
                return {
                    chart: null,
                    showAvg: true,
                    showCount: true,

                    initChart() {
                        const ctx = this.$refs.chart.getContext('2d');
                        // read data from DOM so Livewire updates are reflected
                        let yearlyData = [];
                        try {
                            const node = this.$root.querySelector('[x-ref="yearlyData"]');
                            yearlyData = JSON.parse(node ? node.textContent || '[]' : '[]');
                        } catch (e) {
                            yearlyData = [];
                        }

                        const labels = yearlyData.map(item => item.year);
                        const avgValues = yearlyData.map(item => item.avg_value);
                        const countValues = yearlyData.map(item => item.count);

                        const chartData = {
                            labels: labels,
                            datasets: [
                                {
                                    label: 'Rata-rata Nilai',
                                    data: avgValues,
                                    borderColor: 'rgba(59, 130, 246, 1)',
                                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                                    borderWidth: 2,
                                    tension: 0.3,
                                    fill: true,
                                    yAxisID: 'y',
                                    hidden: !this.showAvg
                                },
                                {
                                    label: 'Jumlah Data',
                                    data: countValues,
                                    borderColor: 'rgba(16, 185, 129, 1)',
                                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                                    borderWidth: 2,
                                    tension: 0.3,
                                    fill: false,
                                    yAxisID: 'y1',
                                    hidden: !this.showCount
                                }
                            ]
                        };

                        const config = {
                            type: 'line',
                            data: chartData,
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                interaction: {
                                    mode: 'index',
                                    intersect: false,
                                },
                                plugins: {
                                    legend: {
                                        position: 'top',
                                        labels: {
                                            color: '#6b7280',
                                            font: { size: 12 }
                                        }
                                    },
                                    tooltip: {
                                        backgroundColor: '#1f2937',
                                        titleColor: '#f9fafb',
                                        bodyColor: '#f9fafb',
                                        borderColor: '#374151',
                                        borderWidth: 1,
                                        padding: 10,
                                        usePointStyle: true,
                                        callbacks: {
                                            label: function(context) {
                                                let label = context.dataset.label || '';
                                                if (label) label += ': ';
                                                if (context.parsed.y !== null) {
                                                    if (context.datasetIndex === 0) {
                                                        label += context.parsed.y.toLocaleString('id-ID', {maximumFractionDigits: 2});
                                                    } else {
                                                        label += context.parsed.y.toLocaleString('id-ID');
                                                    }
                                                }
                                                return label;
                                            }
                                        }
                                    }
                                },
                                scales: {
                                    x: { grid: { color: 'rgba(229, 231, 235, 0.1)' }, ticks: { color: '#9ca3af' }, border: { color: 'rgba(229, 231, 235, 0.1)' } },
                                    y: { type: 'linear', display: this.showAvg, position: 'left', grid: { color: 'rgba(229, 231, 235, 0.1)' }, ticks: { color: '#9ca3af', callback: function(value) { return value.toLocaleString('id-ID'); } }, border: { color: 'rgba(229, 231, 235, 0.1)' } },
                                    y1: { type: 'linear', display: this.showCount, position: 'right', grid: { drawOnChartArea: false }, ticks: { color: '#9ca3af', callback: function(value) { return value.toLocaleString('id-ID'); } } }
                                }
                            }
                        };

                        if (this.chart) this.chart.destroy();
                        this.chart = new Chart(ctx, config);

                        // Small timeout to ensure container layout finished then force resize to avoid invisible charts
                        setTimeout(() => { try { this.chart.resize(); } catch (e) {} }, 50);

                        this.initListener && this.initListener();
                    },

                    toggleDataset(type) {
                        if (type === 'avg') this.showAvg = !this.showAvg;
                        else if (type === 'count') this.showCount = !this.showCount;

                        if (this.chart) {
                            this.chart.data.datasets[0].hidden = !this.showAvg;
                            this.chart.data.datasets[1].hidden = !this.showCount;
                            this.chart.options.scales.y.display = this.showAvg;
                            this.chart.options.scales.y1.display = this.showCount;
                            this.chart.update();
                        }
                    },

                    // Re-init when backend updates data
                    initListener() {
                        if (window.Livewire) {
                            Livewire.hook('message.processed', (message, component) => {
                                try {
                                    if (this.chart) this.chart.destroy();
                                    this.initChart();
                                } catch (e) {
                                    // ignore
                                }
                            });
                        }
                    }
                };
            }
            </script>
        </div>

        <!-- Variabel Distribution (replaces Topik) -->
        <div class="bg-white dark:bg-neutral-800 p-6 rounded-lg border border-neutral-200 dark:border-neutral-700" x-data="variabelDistributionChart()" x-init="initChart()">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-neutral-900 dark:text-white">Distribusi Variabel</h3>
                <div class="flex items-center space-x-2">
                    <button @click="toggleView()" class="px-3 py-1 text-xs rounded-md transition-colors" :class="{'bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400': showChart, 'bg-neutral-100 dark:bg-neutral-700 text-neutral-600 dark:text-neutral-400': !showChart}">
                        <span x-text="showChart ? 'Tabel' : 'Grafik'"></span>
                    </button>
                </div>
            </div>
            
            <!-- Chart View -->
            <div x-show="showChart" class="h-64">
                <canvas id="variabelDistributionChart" x-ref="chart"></canvas>
            </div>
            <div x-ref="variabelData" style="display:none">@json($variabelDistribution)</div>
            
            <!-- Table View -->
            <div x-show="!showChart" class="space-y-3">
                @forelse($variabelDistribution as $item)
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <div class="flex items-center justify-between mb-1">
                            <span class="text-sm font-medium text-neutral-900 dark:text-white">{{ $item['name'] }}</span>
                            <span class="text-sm text-neutral-600 dark:text-neutral-400">{{ $item['percentage'] }}%</span>
                        </div>
                        <div class="w-full bg-neutral-200 dark:bg-neutral-700 rounded-full h-2">
                            <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $item['percentage'] }}%"></div>
                        </div>
                        <div class="flex justify-between text-xs text-neutral-500 dark:text-neutral-400 mt-1">
                            <span>{{ number_format($item['count']) }} data</span>
                            <span>Avg: {{ number_format($item['avg_value'] ?? 0, 2) }}</span>
                        </div>
                    </div>
                </div>
                @empty
                <p class="text-neutral-500 dark:text-neutral-400 text-center py-4">Tidak ada data</p>
                @endforelse
            </div>
            
            <script>
            function variabelDistributionChart() {
                return {
                    chart: null,
                    showChart: true,
                    
                    initChart() {
                        const ctx = this.$refs.chart.getContext('2d');
                        let data = [];
                        try {
                            data = JSON.parse(this.$root.querySelector('[x-ref="variabelData"]').textContent || '[]');
                        } catch (e) {
                            data = [];
                        }
                        if (!data || data.length === 0) return;
                        const colors = [
                            'rgba(59, 130, 246, 0.7)',
                            'rgba(16, 185, 129, 0.7)',
                            'rgba(245, 158, 11, 0.7)',
                            'rgba(139, 92, 246, 0.7)',
                            'rgba(236, 72, 153, 0.7)'
                        ];
                        const borderColors = colors.map(c => c.replace('0.7', '1'));
                        const chartData = {
                            labels: data.map(i => i.name),
                            datasets: [{
                                data: data.map(i => i.count),
                                backgroundColor: colors.slice(0, data.length),
                                borderColor: borderColors.slice(0, data.length),
                                borderWidth: 1,
                                hoverOffset: 10
                            }]
                        };
                        const config = { type: 'doughnut', data: chartData, options: { responsive: true, maintainAspectRatio: false, cutout: '60%' } };
                        if (this.chart) this.chart.destroy();
                        this.chart = new Chart(ctx, config);
                        // Resize shortly after init to avoid invisible canvas when container layout shifts
                        setTimeout(() => { try { this.chart.resize(); } catch (e) {} }, 50);
                        this.initListener && this.initListener();
                    },
                    toggleView() { this.showChart = !this.showChart; },
                    destroyChart() { if (this.chart) this.chart.destroy(); }
                    ,
                    // Re-init when backend updates data
                    initListener() {
                        if (window.Livewire) {
                            Livewire.hook('message.processed', (message, component) => {
                                try {
                                    if (this.chart) this.chart.destroy();
                                    this.initChart();
                                } catch (e) {
                                    // ignore
                                }
                            });
                        }
                    }
                };
            }
            </script>
        </div>
    </div>

    <!-- Charts Row 2 -->
    <div class="mb-6 grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Klasifikasi Distribution (replaces Status) -->
        <div class="bg-white dark:bg-neutral-800 p-6 rounded-lg border border-neutral-200 dark:border-neutral-700" x-data="klasifikasiDistributionChart()" x-init="initChart()">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-neutral-900 dark:text-white">Distribusi Klasifikasi</h3>
                <div class="flex items-center space-x-2">
                    <button @click="toggleView()" class="px-3 py-1 text-xs rounded-md transition-colors" :class="{'bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400': showChart, 'bg-neutral-100 dark:bg-neutral-700 text-neutral-600 dark:text-neutral-400': !showChart}">
                        <span x-text="showChart ? 'Tabel' : 'Grafik'"></span>
                    </button>
                </div>
            </div>
            
            <!-- Chart View -->
            <div x-show="showChart" class="h-64">
                <canvas id="klasifikasiDistributionChart" x-ref="chart"></canvas>
            </div>
            <div x-ref="klasifikasiData" style="display:none">@json($klasifikasiDistribution)</div>
            
            <!-- Table View -->
            <div x-show="!showChart" class="space-y-3">
                @forelse($klasifikasiDistribution as $item)
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <span class="px-2 py-1 text-xs rounded-full mr-3 bg-neutral-100 text-neutral-800 dark:bg-neutral-900/30 dark:text-neutral-300">{{ $item['name'] }}</span>
                        <span class="text-sm text-neutral-900 dark:text-white">{{ number_format($item['count']) }}</span>
                    </div>
                    <span class="text-sm text-neutral-600 dark:text-neutral-400">{{ $item['percentage'] }}%</span>
                </div>
                @empty
                <p class="text-neutral-500 dark:text-neutral-400 text-center py-4">Tidak ada data</p>
                @endforelse
            </div>
            
            <script>
            function klasifikasiDistributionChart() {
                return {
                    chart: null,
                    showChart: true,

                    initChart() {
                        const ctx = this.$refs.chart.getContext('2d');
                        let data = [];
                        try {
                            data = JSON.parse(this.$root.querySelector('[x-ref="klasifikasiData"]').textContent || '[]');
                        } catch (e) {
                            data = [];
                        }
                        if (!data || data.length === 0) return;

                        // filter out undefined/placeholder names
                        const filtered = data.filter(i => (i.name || '').toString().toLowerCase() !== 'undefined');
                        if (filtered.length === 0) return;

                        const labels = filtered.map(i => i.name);
                        const values = filtered.map(i => i.count);
                        const colors = [
                            'rgba(59, 130, 246, 0.7)',
                            'rgba(16, 185, 129, 0.7)',
                            'rgba(245, 158, 11, 0.7)'
                        ];
                        const backgroundColors = values.map((_, idx) => colors[idx % colors.length]);

                        const config = {
                            type: 'bar',
                            data: {
                                labels: labels,
                                datasets: [{ data: values, backgroundColor: backgroundColors, borderRadius: 4 }]
                            },
                            options: { responsive: true, maintainAspectRatio: false, indexAxis: 'y' }
                        };

                        if (this.chart) this.chart.destroy();
                        this.chart = new Chart(ctx, config);
                    },

                    toggleView() { this.showChart = !this.showChart; },
                    destroyChart() { if (this.chart) this.chart.destroy(); },

                    // Re-init when backend updates data
                    initListener() {
                        if (window.Livewire) {
                            Livewire.hook('message.processed', (message, component) => {
                                try {
                                    if (this.chart) this.chart.destroy();
                                    this.initChart();
                                } catch (e) {
                                    // ignore
                                }
                            });
                        }
                    }
                };
            }
            </script>
        </div>

        <!-- Top Regions -->
        <div class="bg-white dark:bg-neutral-800 p-6 rounded-lg border border-neutral-200 dark:border-neutral-700">
            <h3 class="text-lg font-medium text-neutral-900 dark:text-white mb-4">Top 10 Wilayah</h3>
            <div class="space-y-3">
                @forelse($regionStats as $index => $region)
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <span class="w-6 h-6 bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300 rounded-full flex items-center justify-center text-xs font-medium mr-3">
                            {{ $index + 1 }}
                        </span>
                        <span class="text-sm font-medium text-neutral-900 dark:text-white">{{ $region['region'] }}</span>
                    </div>
                    <div class="text-right">
                        <div class="text-sm font-medium text-neutral-900 dark:text-white">{{ number_format($region['count']) }}</div>
                        <div class="text-xs text-neutral-500 dark:text-neutral-400">Avg: {{ number_format($region['avg_value'], 2) }}</div>
                    </div>
                </div>
                @empty
                <p class="text-neutral-500 dark:text-neutral-400 text-center py-4">Tidak ada data</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Variabel Comparison Table -->
    <div class="bg-white dark:bg-neutral-800 rounded-lg border border-neutral-200 dark:border-neutral-700">
        <div class="p-6 border-b border-neutral-200 dark:border-neutral-700">
            <h3 class="text-lg font-medium text-neutral-900 dark:text-white">Perbandingan Variabel</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-neutral-500 dark:text-neutral-400">
                <thead class="text-xs text-neutral-700 uppercase bg-neutral-50 dark:bg-neutral-700 dark:text-neutral-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">Variabel</th>
                        <th scope="col" class="px-6 py-3">Satuan</th>
                        <th scope="col" class="px-6 py-3">Jumlah Data</th>
                        <th scope="col" class="px-6 py-3">Rata-rata</th>
                        <th scope="col" class="px-6 py-3">Maksimum</th>
                        <th scope="col" class="px-6 py-3">Minimum</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($variabelComparison as $variabel)
                    <tr class="bg-white border-b dark:bg-neutral-800 dark:border-neutral-700 hover:bg-neutral-50 dark:hover:bg-neutral-700 transition-colors">
                        <td class="px-6 py-4 font-medium text-neutral-900 dark:text-white">
                            {{ $variabel['name'] }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300">
                                {{ $variabel['unit'] }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            {{ number_format($variabel['count']) }}
                        </td>
                        <td class="px-6 py-4 font-medium">
                            {{ number_format($variabel['avg_value'], 2, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 text-green-600 dark:text-green-400 font-medium">
                            {{ number_format($variabel['max_value'], 2, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 text-red-600 dark:text-red-400 font-medium">
                            {{ number_format($variabel['min_value'], 2, ',', '.') }}
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
