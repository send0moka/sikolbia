<x-layouts.akademisi title="Grafik & Statistik - Panel Akademisi">
    <div class="p-6 space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-neutral-900 dark:text-white">Grafik & Statistik NBM</h1>
                <p class="mt-1 text-sm text-neutral-600 dark:text-neutral-400">
                    Visualisasi data Neraca Bahan Makanan untuk analisis tren dan pola konsumsi
                </p>
            </div>
        </div>

        <!-- Filter Form -->
        <div class="bg-white dark:bg-neutral-800 rounded-lg shadow p-6">
            <form method="GET" action="{{ route('akademisi.grafik-statistik') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- Kelompok Filter -->
                <div>
                    <label for="kelompok" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                        Kelompok Komoditas
                    </label>
                    <select name="kelompok" id="kelompok"
                            class="w-full px-3 py-2 bg-white dark:bg-neutral-700 border border-neutral-300 dark:border-neutral-600 rounded-lg focus:ring-2 focus:ring-purple-500 dark:text-white">
                        @foreach($kelompokList as $kelompok)
                            <option value="{{ $kelompok->kode }}" {{ $kelompokKode == $kelompok->kode ? 'selected' : '' }}>
                                {{ $kelompok->kode }} - {{ $kelompok->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Tahun Dari -->
                <div>
                    <label for="tahun_dari" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                        Tahun Dari
                    </label>
                    <input type="number" name="tahun_dari" id="tahun_dari" value="{{ $tahunDari }}" min="1993" max="{{ date('Y') }}"
                           class="w-full px-3 py-2 bg-white dark:bg-neutral-700 border border-neutral-300 dark:border-neutral-600 rounded-lg focus:ring-2 focus:ring-purple-500 dark:text-white">
                </div>

                <!-- Tahun Sampai -->
                <div>
                    <label for="tahun_sampai" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                        Tahun Sampai
                    </label>
                    <input type="number" name="tahun_sampai" id="tahun_sampai" value="{{ $tahunSampai }}" min="1993" max="{{ date('Y') }}"
                           class="w-full px-3 py-2 bg-white dark:bg-neutral-700 border border-neutral-300 dark:border-neutral-600 rounded-lg focus:ring-2 focus:ring-purple-500 dark:text-white">
                </div>

                <!-- Submit Button -->
                <div class="flex items-end">
                    <button type="submit"
                            class="w-full px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white font-medium rounded-lg transition-colors">
                        <svg class="inline w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                        Tampilkan Grafik
                    </button>
                </div>
            </form>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div class="bg-white dark:bg-neutral-800 rounded-lg shadow p-4">
                <div class="text-sm font-medium text-neutral-600 dark:text-neutral-400">Rata-rata</div>
                <div class="mt-1 text-2xl font-bold text-purple-600 dark:text-purple-400">{{ number_format($statistics['mean'], 2) }}</div>
                <div class="mt-1 text-xs text-neutral-500 dark:text-neutral-500">ton/bulan</div>
            </div>

            <div class="bg-white dark:bg-neutral-800 rounded-lg shadow p-4">
                <div class="text-sm font-medium text-neutral-600 dark:text-neutral-400">Median</div>
                <div class="mt-1 text-2xl font-bold text-blue-600 dark:text-blue-400">{{ number_format($statistics['median'], 2) }}</div>
                <div class="mt-1 text-xs text-neutral-500 dark:text-neutral-500">ton/bulan</div>
            </div>

            <div class="bg-white dark:bg-neutral-800 rounded-lg shadow p-4">
                <div class="text-sm font-medium text-neutral-600 dark:text-neutral-400">Minimum</div>
                <div class="mt-1 text-2xl font-bold text-green-600 dark:text-green-400">{{ number_format($statistics['min'], 2) }}</div>
                <div class="mt-1 text-xs text-neutral-500 dark:text-neutral-500">ton/bulan</div>
            </div>

            <div class="bg-white dark:bg-neutral-800 rounded-lg shadow p-4">
                <div class="text-sm font-medium text-neutral-600 dark:text-neutral-400">Maximum</div>
                <div class="mt-1 text-2xl font-bold text-red-600 dark:text-red-400">{{ number_format($statistics['max'], 2) }}</div>
                <div class="mt-1 text-xs text-neutral-500 dark:text-neutral-500">ton/bulan</div>
            </div>

            <div class="bg-white dark:bg-neutral-800 rounded-lg shadow p-4">
                <div class="text-sm font-medium text-neutral-600 dark:text-neutral-400">Std. Deviasi</div>
                <div class="mt-1 text-2xl font-bold text-orange-600 dark:text-orange-400">{{ number_format($statistics['std_dev'], 2) }}</div>
                <div class="mt-1 text-xs text-neutral-500 dark:text-neutral-500">ton/bulan</div>
            </div>
        </div>

        <!-- Time Series Chart -->
        <div class="bg-white dark:bg-neutral-800 rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-neutral-900 dark:text-white mb-4">
                Tren Ketersediaan Bahan Makanan ({{ $tahunDari }} - {{ $tahunSampai }})
            </h3>
            <div class="relative h-96">
                <canvas id="timeSeriesChart"></canvas>
            </div>
        </div>

        <!-- Pie Chart - Konsumsi per Kelompok -->
        <div class="bg-white dark:bg-neutral-800 rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-neutral-900 dark:text-white mb-4">
                Distribusi Rata-rata Ketersediaan per Kelompok Komoditas
            </h3>
            <div class="relative h-96">
                <canvas id="pieChart"></canvas>
            </div>
        </div>

        <!-- Data Table Summary -->
        <div class="bg-white dark:bg-neutral-800 rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-neutral-200 dark:border-neutral-700">
                <h3 class="text-lg font-semibold text-neutral-900 dark:text-white">
                    Data Tabulasi ({{ $timeSeriesData->count() }} periode)
                </h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-neutral-200 dark:divide-neutral-700">
                    <thead class="bg-neutral-50 dark:bg-neutral-900">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 dark:text-neutral-400 uppercase tracking-wider">
                                Periode
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-neutral-500 dark:text-neutral-400 uppercase tracking-wider">
                                Ketersediaan (ton)
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-neutral-800 divide-y divide-neutral-200 dark:divide-neutral-700">
                        @foreach($timeSeriesData as $data)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-900 dark:text-neutral-300">
                                {{ \Carbon\Carbon::create($data->tahun, $data->bulan)->format('F Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-900 dark:text-neutral-300 text-right">
                                {{ number_format($data->total_bahan_makanan, 2) }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Prepare data for charts
        const timeSeriesData = @json($timeSeriesData);
        const konsumsiPerKelompok = @json($konsumsiPerKelompok);

        // Time Series Chart
        const timeSeriesCtx = document.getElementById('timeSeriesChart').getContext('2d');
        const timeSeriesChart = new Chart(timeSeriesCtx, {
            type: 'line',
            data: {
                labels: timeSeriesData.map(d => {
                    const date = new Date(d.tahun, d.bulan - 1);
                    return date.toLocaleDateString('id-ID', { year: 'numeric', month: 'short' });
                }),
                datasets: [{
                    label: 'Ketersediaan Bahan Makanan (ton)',
                    data: timeSeriesData.map(d => d.total_bahan_makanan),
                    borderColor: 'rgb(147, 51, 234)',
                    backgroundColor: 'rgba(147, 51, 234, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.dataset.label + ': ' + context.parsed.y.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",") + ' ton';
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return value.toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                            }
                        }
                    }
                }
            }
        });

        // Pie Chart
        const pieCtx = document.getElementById('pieChart').getContext('2d');
        const pieChart = new Chart(pieCtx, {
            type: 'pie',
            data: {
                labels: konsumsiPerKelompok.map(d => d.kelompok.kode + ' - ' + d.kelompok.nama),
                datasets: [{
                    label: 'Rata-rata Ketersediaan',
                    data: konsumsiPerKelompok.map(d => d.avg_bahan_makanan),
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.8)',
                        'rgba(54, 162, 235, 0.8)',
                        'rgba(255, 206, 86, 0.8)',
                        'rgba(75, 192, 192, 0.8)',
                        'rgba(153, 102, 255, 0.8)',
                        'rgba(255, 159, 64, 0.8)',
                        'rgba(199, 199, 199, 0.8)',
                        'rgba(83, 102, 255, 0.8)',
                        'rgba(255, 99, 255, 0.8)',
                        'rgba(99, 255, 132, 0.8)',
                    ],
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.parsed || 0;
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = ((value / total) * 100).toFixed(1);
                                return label + ': ' + value.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",") + ' ton (' + percentage + '%)';
                            }
                        }
                    }
                }
            }
        });
    </script>
    @endpush
</x-layouts.akademisi>
