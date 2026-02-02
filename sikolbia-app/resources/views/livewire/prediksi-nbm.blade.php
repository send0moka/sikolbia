<div>
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-gray-900">Prediksi NBM</h1>
        <p class="mt-1 text-sm text-gray-600">
            Prediksi Neraca Bahan Makanan dengan Machine Learning (LSTM Enhanced Ensemble - MAPE 3.74%)
        </p>
    </div>

    <!-- Mode Toggle -->
    <div class="mb-6 flex gap-2">
        <button wire:click="switchMode('komoditi')" 
            class="px-4 py-2 rounded-md {{ $predictionMode === 'komoditi' ? 'bg-blue-600 text-white' : 'bg-white text-gray-700 border border-gray-300' }}">
            <i class="fas fa-chart-bar mr-2"></i>Prediksi Per Komoditi
        </button>
        <button wire:click="switchMode('manual')" 
            class="px-4 py-2 rounded-md {{ $predictionMode === 'manual' ? 'bg-blue-600 text-white' : 'bg-white text-gray-700 border border-gray-300' }}">
            <i class="fas fa-pencil mr-2"></i>Prediksi Manual (6 Bulan)
        </button>
    </div>

    @if ($predictionMode === 'komoditi')
        <!-- Prediksi Per Komoditi Mode -->
        <div class="grid gap-6 md:grid-cols-3">
            <!-- Input Sidebar -->
            <div class="md:col-span-1">
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-semibold mb-4">Parameter Prediksi</h3>
                    
                    <div class="space-y-4">
                        <!-- Komoditi Selector -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Pilih Komoditi</label>
                            <select wire:model="selectedKomoditi" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
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
                            <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah Bulan Prediksi</label>
                            <input type="number" wire:model="nMonths" min="1" max="12" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                            @error('nMonths') 
                                <span class="text-sm text-red-600">{{ $message }}</span> 
                            @enderror
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
            <div class="md:col-span-2">
                @if ($komoditiPredictionResult)
                    <div class="bg-white rounded-lg shadow-sm p-6">
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
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-lg font-semibold">Hasil Prediksi</h3>
                                <button wire:click="exportKomoditiResult" 
                                    class="px-3 py-1 text-sm bg-green-600 text-white rounded-md hover:bg-green-700">
                                    <i class="fas fa-download mr-1"></i>Export JSON
                                </button>
                            </div>

                            <!-- Model Info -->
                            <div class="mb-4 p-4 bg-blue-50 rounded-md">
                                <p class="text-sm">
                                    <strong>Model:</strong> {{ $komoditiPredictionResult['model_info']['type'] ?? 'N/A' }}<br>
                                    <strong>Metrics:</strong> 
                                    R² {{ number_format($komoditiPredictionResult['model_info']['r2'] ?? 0, 4) }}, 
                                    MAPE {{ number_format($komoditiPredictionResult['model_info']['mape'] ?? 0, 2) }}%, 
                                    MAE {{ number_format($komoditiPredictionResult['model_info']['mae'] ?? 0, 2) }}, 
                                    RMSE {{ number_format($komoditiPredictionResult['model_info']['rmse'] ?? 0, 2) }}
                                </p>
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
                    <div class="bg-gray-50 rounded-lg p-12 text-center">
                        <i class="fas fa-chart-line text-6xl text-gray-300 mb-4"></i>
                        <p class="text-gray-500">Pilih komoditi dan klik Prediksi untuk melihat hasil</p>
                    </div>
                @endif
            </div>
        </div>

    @else
        <!-- Manual Prediction Mode (Original 6-Month Input) -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <p class="text-gray-600 mb-4">Mode prediksi manual (coming soon)</p>
        </div>
    @endif
</div>
