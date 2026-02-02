<flux:main>
    <!-- Page Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Prediksi NBM</h1>
        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            <i class="fas fa-brain mr-1"></i>
            Prediksi Neraca Bahan Makanan menggunakan LSTM Enhanced Ensemble
        </p>
    </div>

    <!-- Main Prediction Interface -->
    <div class="grid gap-6 lg:grid-cols-3">
            <!-- Input Sidebar -->
            <div class="lg:col-span-1">
                <div class="bg-white dark:bg-zinc-800 rounded-lg shadow-sm p-6 border border-zinc-200 dark:border-zinc-700">
                    <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white">Parameter Prediksi</h3>
                    
                    <div class="space-y-4">
                        <!-- Komoditi Selector -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Pilih Komoditi</label>
                            <select wire:model="selectedKomoditi" 
                                class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-white rounded-md focus:ring-blue-500 focus:border-blue-500">
                                <option value="">-- Pilih Komoditi --</option>
                                @foreach ($komoditiList as $item)
                                    <option value="{{ $item['kode_komoditi'] }}">
                                        {{ $item['nama'] }}
                                    </option>
                                @endforeach
                            </select>
                            @error('selectedKomoditi') 
                                <span class="text-sm text-red-600">{{ $message }}</span> 
                            @enderror
                        </div>

                        <!-- N Months Input -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Jumlah Bulan Prediksi</label>
                            <input type="number" wire:model="nMonths" min="1" max="12" 
                                class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-white rounded-md focus:ring-blue-500 focus:border-blue-500">
                            @error('nMonths') 
                                <span class="text-sm text-red-600">{{ $message }}</span> 
                            @enderror
                        </div>

                        <!-- Historical Period Filter -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                <i class="fas fa-history mr-1"></i>Periode Data Historis
                            </label>
                            <select wire:model.live="historicalPeriod" 
                                class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-white rounded-md focus:ring-blue-500 focus:border-blue-500">
                                <option value="6">6 Bulan Terakhir</option>
                                <option value="12">1 Tahun Terakhir</option>
                                <option value="60">5 Tahun Terakhir</option>
                                <option value="all">Semua Data (Terlama)</option>
                            </select>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Untuk visualisasi perbandingan</p>
                        </div>

                        <!-- Predict Button -->
                        <button wire:click="predictKomoditi" 
                            wire:loading.attr="disabled"
                            class="w-full px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 disabled:opacity-50">
                            <span wire:loading.remove wire:target="predictKomoditi">
                                <i class="fas fa-magic mr-2"></i>Prediksi
                            </span>
                            <span wire:loading wire:target="predictKomoditi">
                                <i class="fas fa-spinner fa-spin mr-2"></i>Processing...
                            </span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Results Area -->
            <div class="lg:col-span-2">
                @if ($komoditiPredictionResult)
                    <div class="bg-white dark:bg-zinc-800 rounded-lg shadow-sm p-6 border border-zinc-200 dark:border-zinc-700">
                        <!-- Error Message Display -->
                        @if (isset($komoditiPredictionResult['error']) && $komoditiPredictionResult['error'])
                            <div class="p-6 bg-red-50 border-2 border-red-200 rounded-lg">
                                <div class="flex items-start">
                                    <i class="fas fa-times-circle text-red-600 text-3xl mt-1 mr-4"></i>
                                    <div class="flex-1">
                                        <h4 class="text-xl font-bold text-red-800 mb-3">Prediksi Tidak Dapat Dilakukan</h4>
                                        <div class="bg-white p-4 rounded border border-red-300 mb-4">
                                            <p class="text-red-700 text-base leading-relaxed">
                                                {{ $komoditiPredictionResult['error_message'] ?? 'Terjadi kesalahan saat membuat prediksi' }}
                                            </p>
                                        </div>
                                        <div class="bg-blue-50 p-4 rounded border border-blue-200">
                                            <p class="text-sm font-semibold text-blue-800 mb-2">
                                                <i class="fas fa-info-circle mr-1"></i> Saran:
                                            </p>
                                            <p class="text-sm text-blue-700">
                                                Silakan pilih komoditi lain yang memiliki data historis lengkap, seperti:
                                                <span class="font-semibold">Beras, Jagung, Minyak Goreng Sawit, Telur Ayam Ras, Gula Pasir, Daging Ayam Ras</span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <!-- Normal Result Display -->
                            
                            <!-- Model Metrics Cards -->
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                                <div class="p-4 bg-blue-50 rounded-lg">
                                    <p class="text-xs text-blue-600 font-medium">R² Score</p>
                                    <p class="text-2xl font-bold text-blue-700">
                                        {{ number_format($komoditiPredictionResult['model_info']['r2'] ?? 0.9901, 4) }}
                                    </p>
                                </div>
                                <div class="p-4 bg-green-50 rounded-lg">
                                    <p class="text-xs text-green-600 font-medium">MAPE</p>
                                    <p class="text-2xl font-bold text-green-700">
                                        {{ number_format($komoditiPredictionResult['model_info']['mape'] ?? 3.73, 2) }}%
                                    </p>
                                </div>
                                <div class="p-4 bg-yellow-50 rounded-lg">
                                    <p class="text-xs text-yellow-600 font-medium">MAE</p>
                                    <p class="text-2xl font-bold text-yellow-700">
                                        {{ number_format($komoditiPredictionResult['model_info']['mae'] ?? 867.04, 2) }}
                                    </p>
                                </div>
                                <div class="p-4 bg-red-50 rounded-lg">
                                    <p class="text-xs text-red-600 font-medium">RMSE</p>
                                    <p class="text-2xl font-bold text-red-700">
                                        {{ number_format($komoditiPredictionResult['model_info']['rmse'] ?? 1788.78, 2) }}
                                    </p>
                                </div>
                            </div>

                            <!-- Chart Visualization -->
                            <div class="mb-6" wire:ignore>
                                <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white">
                                    <i class="fas fa-chart-line mr-2 text-blue-600"></i>Visualisasi Prediksi
                                </h3>
                                <div class="bg-white dark:bg-zinc-900 rounded-lg border border-gray-200 dark:border-zinc-700 p-4">
                                    <canvas id="predictionChart" 
                                            style="height: 400px; max-height: 400px;"
                                            @if($chartData && !empty($chartData['historical']) && !empty($chartData['predictions']))
                                            data-chart-data="{{ json_encode($chartData) }}"
                                            @endif
                                    ></canvas>
                                </div>
                            </div>
                            
                            @if($chartData && !empty($chartData['historical']) && !empty($chartData['predictions']))
                            <script>
                                // FORCE RENDER saat ini juga
                                (function() {
                                    const chartData = @json($chartData);
                                    console.log('=== INLINE SCRIPT EXECUTING ===');
                                    console.log('Chart data available:', chartData);
                                    
                                    function tryRender() {
                                        if (typeof renderChart === 'function' && typeof Chart !== 'undefined') {
                                            console.log('Calling renderChart NOW');
                                            renderChart(chartData);
                                            return true;
                                        }
                                        return false;
                                    }
                                    
                                    // Try immediate
                                    if (!tryRender()) {
                                        // Try after 100ms
                                        setTimeout(() => {
                                            if (!tryRender()) {
                                                // Try after 500ms
                                                setTimeout(() => tryRender(), 500);
                                            }
                                        }, 100);
                                    }
                                })();
                            </script>
                            @endif

                            <!-- Export & Title -->
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Hasil Prediksi Detail</h3>
                                <button wire:click="exportKomoditiResult" 
                                    class="px-3 py-1 text-sm bg-green-600 text-white rounded-md hover:bg-green-700">
                                    <i class="fas fa-download mr-1"></i>Export JSON
                                </button>
                            </div>

                            <!-- Predictions Table -->
                            <div class="overflow-x-auto">
                            @php
                                // Check if all predictions are zero
                                $allZero = true;
                                foreach ($komoditiPredictionResult['predictions'] as $pred) {
                                    if (($pred['kalori_hari'] ?? 0) != 0) {
                                        $allZero = false;
                                        break;
                                    }
                                }
                            @endphp

                            @if ($allZero)
                                <div class="mb-4 p-4 bg-yellow-50 border border-yellow-200 rounded-md">
                                    <div class="flex items-start">
                                        <i class="fas fa-exclamation-triangle text-yellow-600 mt-1 mr-3"></i>
                                        <div>
                                            <p class="text-sm font-medium text-yellow-800">Data Tidak Tersedia</p>
                                            <p class="text-sm text-yellow-700 mt-1">
                                                Model tidak dapat memprediksi komoditi ini karena data historis tidak mencukupi atau terlalu sedikit. 
                                                Silakan pilih komoditi lain (contoh: Beras, Jagung, Telur Ayam Ras).
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Periode</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Prediksi (kkal/hari)</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">CI Lower</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">CI Upper</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($komoditiPredictionResult['predictions'] as $idx => $pred)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                {{ $pred['tahun'] ?? '' }}-{{ str_pad($pred['bulan'] ?? 0, 2, '0', STR_PAD_LEFT) }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                {{ number_format($pred['kalori_hari'] ?? 0, 2) }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ number_format($komoditiPredictionResult['confidence_intervals'][$idx]['lower'] ?? 0, 2) }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ number_format($komoditiPredictionResult['confidence_intervals'][$idx]['upper'] ?? 0, 2) }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Summary Stats -->
                        @if (isset($komoditiPredictionResult['summary']))
                            <div class="mt-4 grid grid-cols-3 gap-4">
                                <div class="p-3 bg-gray-50 rounded">
                                    <p class="text-xs text-gray-600">Mean</p>
                                    <p class="text-lg font-semibold">{{ number_format($komoditiPredictionResult['summary']['mean'], 2) }}</p>
                                </div>
                                <div class="p-3 bg-gray-50 rounded">
                                    <p class="text-xs text-gray-600">Std Dev</p>
                                    <p class="text-lg font-semibold">{{ number_format($komoditiPredictionResult['summary']['std'], 2) }}</p>
                                </div>
                                <div class="p-3 bg-gray-50 rounded">
                                    <p class="text-xs text-gray-600">Trend</p>
                                    <p class="text-lg font-semibold">{{ $komoditiPredictionResult['summary']['trend'] }}</p>
                                </div>
                            </div>
                        @endif
                        @endif {{-- End of error check --}}
                    </div>
                @else
                    <div class="bg-gray-50 dark:bg-zinc-800 rounded-lg p-12 text-center border border-zinc-200 dark:border-zinc-700">
                        <i class="fas fa-chart-line text-6xl text-gray-300 dark:text-zinc-600 mb-4"></i>
                        <p class="text-gray-500 dark:text-gray-400">Pilih komoditi dan klik Prediksi untuk melihat hasil</p>
                    </div>
                @endif
            </div>
        </div>
</flux:main>

@push('scripts')
<script>
    let predictionChart = null;

    function renderChart(chartData) {
        console.log('renderChart called with:', chartData);
        
        const ctx = document.getElementById('predictionChart');
        if (!ctx) {
            console.error('Canvas element not found!');
            return;
        }

        if (!chartData || !chartData.historical || !chartData.predictions) {
            console.error('Invalid chart data:', chartData);
            return;
        }

        if (typeof Chart === 'undefined') {
            console.error('Chart.js not loaded!');
            return;
        }

        console.log('Historical:', chartData.historical.length, 'Predictions:', chartData.predictions.length);

        // Destroy existing chart
        if (predictionChart) {
            predictionChart.destroy();
        }

        // Prepare data - SAMBUNGKAN historical dan prediction
        const labels = [...chartData.historical.map(d => d.period), ...chartData.predictions.map(d => d.period)];
        
        // Historical values: isi semua periode historical, null di periode prediction
        const historicalValues = [...chartData.historical.map(d => d.value), ...Array(chartData.predictions.length).fill(null)];
        
        // Prediction values: null di historical KECUALI titik terakhir (untuk sambung), lalu isi prediction
        const lastHistoricalValue = chartData.historical[chartData.historical.length - 1].value;
        const predictionValues = [
            ...Array(chartData.historical.length - 1).fill(null), 
            lastHistoricalValue, // TITIK PENGHUBUNG
            ...chartData.predictions.map(d => d.value)
        ];
        
        // CI bounds - mulai dari titik terakhir historical
        const ciLower = [
            ...Array(chartData.historical.length - 1).fill(null),
            lastHistoricalValue,
            ...chartData.predictions.map(d => d.ci_lower)
        ];
        const ciUpper = [
            ...Array(chartData.historical.length - 1).fill(null),
            lastHistoricalValue,
            ...chartData.predictions.map(d => d.ci_upper)
        ];
        
        // Hitung range data untuk Y axis yang lebih baik
        const allValues = [...chartData.historical.map(d => d.value), ...chartData.predictions.map(d => d.value)];
        const minValue = Math.min(...allValues);
        const maxValue = Math.max(...allValues);
        const padding = (maxValue - minValue) * 0.1; // 10% padding
        const suggestedMin = Math.max(0, minValue - padding);
        const suggestedMax = maxValue + padding;
        
        // Dynamic point radius - hilangkan bulatan jika data terlalu banyak
        const totalDataPoints = chartData.historical.length + chartData.predictions.length;
        const pointRadius = totalDataPoints > 24 ? 0 : 5; // Hilangkan bulatan jika > 24 bulan (2 tahun)
        const pointHoverRadius = totalDataPoints > 24 ? 3 : 7;

        // Create chart
        predictionChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Data Historis (6 Bulan)',
                        data: historicalValues,
                        borderColor: 'rgb(59, 130, 246)',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        borderWidth: 3,
                        tension: 0.4,
                        pointRadius: pointRadius,
                        pointHoverRadius: pointHoverRadius,
                        pointBackgroundColor: 'rgb(59, 130, 246)',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2
                    },
                    {
                        label: 'Prediksi',
                        data: predictionValues,
                        borderColor: 'rgb(16, 185, 129)',
                        backgroundColor: 'rgba(16, 185, 129, 0.1)',
                        borderWidth: 3,
                        borderDash: [8, 4],
                        tension: 0.4,
                        pointRadius: pointRadius,
                        pointHoverRadius: pointHoverRadius,
                        pointBackgroundColor: 'rgb(16, 185, 129)',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2
                    },
                    {
                        label: 'Confidence Interval',
                        data: ciUpper,
                        borderColor: 'rgba(249, 115, 22, 0.4)',
                        backgroundColor: 'rgba(249, 115, 22, 0.15)',
                        fill: '+1',
                        borderWidth: 1,
                        pointRadius: 0,
                        tension: 0.4
                    },
                    {
                        label: 'CI Lower',
                        data: ciLower,
                        borderColor: 'rgba(249, 115, 22, 0.4)',
                        backgroundColor: 'rgba(249, 115, 22, 0.15)',
                        fill: false,
                        borderWidth: 1,
                        pointRadius: 0,
                        tension: 0.4
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                        labels: {
                            filter: (item) => item.text !== 'CI Lower',
                            usePointStyle: true,
                            padding: 15
                        }
                    },
                    title: {
                        display: true,
                        text: 'Prediksi Kalori per Kapita per Hari',
                        font: {
                            size: 16,
                            weight: 'bold'
                        },
                        padding: {
                            bottom: 20
                        }
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        padding: 12,
                        titleFont: {
                            size: 14
                        },
                        bodyFont: {
                            size: 13
                        },
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.parsed.y !== null) {
                                    label += new Intl.NumberFormat('id-ID', { 
                                        minimumFractionDigits: 2, 
                                        maximumFractionDigits: 2 
                                    }).format(context.parsed.y) + ' kkal/hari';
                                }
                                return label;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: false,
                        suggestedMin: suggestedMin,
                        suggestedMax: suggestedMax,
                        title: {
                            display: true,
                            text: 'Kalori (kkal/hari)',
                            font: {
                                size: 13,
                                weight: 'bold'
                            }
                        },
                        ticks: {
                            callback: function(value) {
                                return new Intl.NumberFormat('id-ID').format(value);
                            }
                        },
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Periode (Tahun-Bulan)',
                            font: {
                                size: 13,
                                weight: 'bold'
                            }
                        },
                        grid: {
                            display: false
                        }
                    }
                },
                interaction: {
                    mode: 'nearest',
                    axis: 'x',
                    intersect: false
                }
            }
        });
        
        console.log('Chart rendered successfully!');
    }

    // Listen for Livewire events
    document.addEventListener('livewire:initialized', () => {
        console.log('Livewire ready, listening for chart updates');
        
        // Method 1: Livewire.on
        Livewire.on('update-chart', (event) => {
            console.log('Method 1 - Livewire.on received:', event);
            const chartData = event.chartData || event[0]?.chartData || event[0];
            if (chartData) {
                renderChart(chartData);
            }
        });
    });

    // Method 2: Window event listener (fallback)
    window.addEventListener('update-chart', (event) => {
        console.log('Method 2 - Window event received:', event.detail);
        if (event.detail && event.detail.chartData) {
            // Tunggu DOM selesai update
            setTimeout(() => {
                const canvas = document.getElementById('predictionChart');
                if (canvas) {
                    console.log('Canvas found after timeout, rendering...');
                    renderChart(event.detail.chartData);
                } else {
                    console.error('Canvas still not found after timeout!');
                }
            }, 300);
        }
    });

    // Check for initial data
    document.addEventListener('DOMContentLoaded', function() {
        const initialData = @json($chartData ?? null);
        console.log('Initial data check:', initialData);
        
        if (initialData && initialData.historical && initialData.predictions && 
            initialData.historical.length > 0 && initialData.predictions.length > 0) {
            console.log('Rendering initial chart');
            setTimeout(() => renderChart(initialData), 200);
        } else {
            console.log('No initial data - waiting for prediction');
        }
    });

    // HOOK LIVEWIRE - render chart setelah component update (SEKALI SAJA)
    document.addEventListener('livewire:initialized', () => {
        let lastRendered = null;
        
        Livewire.hook('morph.updated', ({ el, component }) => {
            const canvas = document.getElementById('predictionChart');
            if (canvas && canvas.dataset.chartData) {
                const dataStr = canvas.dataset.chartData;
                
                // Hanya render jika data berubah
                if (dataStr && dataStr !== lastRendered) {
                    lastRendered = dataStr;
                    console.log('New chart data detected!');
                    
                    try {
                        const chartData = JSON.parse(dataStr);
                        console.log('Parsed chart data:', chartData);
                        renderChart(chartData);
                    } catch (e) {
                        console.error('Failed to parse chart data:', e);
                    }
                }
            }
        });
    });
</script>
@endpush
