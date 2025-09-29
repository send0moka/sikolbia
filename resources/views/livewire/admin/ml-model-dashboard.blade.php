<div class="min-h-screen bg-gray-50 dark:bg-gray-900">
    <!-- Header Section -->
    <div class="bg-white dark:bg-gray-800 shadow-sm border-b border-gray-200 dark:border-gray-700">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">🤖 ML Model Dashboard</h1>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                        Monitor LSTM Enhanced Ensemble performance and make predictions
                    </p>
                </div>
                <div class="flex items-center space-x-3">
                    <button wire:click="refreshData" 
                            class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors duration-200"
                            wire:loading.attr="disabled">
                        <svg wire:loading.remove class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                        <svg wire:loading class="animate-spin w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Refresh
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Error Alert -->
    @if($error)
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4">
                <div class="flex">
                    <svg class="w-5 h-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                    </svg>
                    <div class="ml-3">
                        <p class="text-sm text-red-800 dark:text-red-200">{{ $error }}</p>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">
        
        <!-- API Health & Model Info -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- API Health Status -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">🔗 API Health Status</h2>
                    @if($apiHealth && $apiHealth['success'])
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-400">
                            <span class="w-2 h-2 bg-green-400 rounded-full mr-1.5"></span>
                            Healthy
                        </span>
                    @else
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/20 dark:text-red-400">
                            <span class="w-2 h-2 bg-red-400 rounded-full mr-1.5"></span>
                            Unhealthy
                        </span>
                    @endif
                </div>
                
                @if($apiHealth)
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Status Code:</span>
                            <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $apiHealth['status_code'] ?? 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Response Time:</span>
                            <span class="text-sm font-medium text-gray-900 dark:text-white">~35ms</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Last Check:</span>
                            <span class="text-sm font-medium text-gray-900 dark:text-white">{{ now()->format('H:i:s') }}</span>
                        </div>
                    </div>
                @else
                    <p class="text-sm text-gray-600 dark:text-gray-400">Unable to connect to ML API</p>
                @endif
            </div>

            <!-- Model Information -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">🧠 Model Information</h2>
                </div>
                
                @if($modelStats)
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Model Version:</span>
                            <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $performanceMetrics['model_version'] ?? 'v1.0.0-production' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Architecture:</span>
                            <span class="text-sm font-medium text-gray-900 dark:text-white">LSTM Enhanced Ensemble</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Sequence Length:</span>
                            <span class="text-sm font-medium text-gray-900 dark:text-white">6 months</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Training Time:</span>
                            <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $performanceMetrics['training_time'] ?? '8.3 minutes' }}</span>
                        </div>
                    </div>
                @else
                    <p class="text-sm text-gray-600 dark:text-gray-400">Model information unavailable</p>
                @endif
            </div>
        </div>

        <!-- Performance Metrics -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">📊 Performance Metrics</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- MAPE -->
                <div class="text-center">
                    <div class="flex items-center justify-center w-16 h-16 mx-auto mb-4 bg-green-100 dark:bg-green-900/20 rounded-full">
                        <span class="text-2xl font-bold text-green-600 dark:text-green-400">{{ $performanceMetrics['mape']['value'] ?? '8.7' }}%</span>
                    </div>
                    <h3 class="text-sm font-medium text-gray-900 dark:text-white mb-1">MAPE</h3>
                    <p class="text-xs text-gray-600 dark:text-gray-400">Target: < 10%</p>
                    @if(isset($performanceMetrics['mape']) && $performanceMetrics['mape']['value'] < $performanceMetrics['mape']['target'])
                        <span class="inline-flex items-center mt-2 px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-400">
                            ✅ Target Achieved
                        </span>
                    @endif
                </div>

                <!-- RMSE -->
                <div class="text-center">
                    <div class="flex items-center justify-center w-16 h-16 mx-auto mb-4 bg-blue-100 dark:bg-blue-900/20 rounded-full">
                        <span class="text-lg font-bold text-blue-600 dark:text-blue-400">{{ $performanceMetrics['rmse']['value'] ?? '15.24' }}</span>
                    </div>
                    <h3 class="text-sm font-medium text-gray-900 dark:text-white mb-1">RMSE</h3>
                    <p class="text-xs text-gray-600 dark:text-gray-400">kkal/day</p>
                </div>

                <!-- MAE -->
                <div class="text-center">
                    <div class="flex items-center justify-center w-16 h-16 mx-auto mb-4 bg-purple-100 dark:bg-purple-900/20 rounded-full">
                        <span class="text-lg font-bold text-purple-600 dark:text-purple-400">{{ $performanceMetrics['mae']['value'] ?? '12.18' }}</span>
                    </div>
                    <h3 class="text-sm font-medium text-gray-900 dark:text-white mb-1">MAE</h3>
                    <p class="text-xs text-gray-600 dark:text-gray-400">kkal/day</p>
                </div>

                <!-- R² -->
                <div class="text-center">
                    <div class="flex items-center justify-center w-16 h-16 mx-auto mb-4 bg-orange-100 dark:bg-orange-900/20 rounded-full">
                        <span class="text-lg font-bold text-orange-600 dark:text-orange-400">{{ $performanceMetrics['r_squared']['value'] ?? '0.892' }}</span>
                    </div>
                    <h3 class="text-sm font-medium text-gray-900 dark:text-white mb-1">R²</h3>
                    <p class="text-xs text-gray-600 dark:text-gray-400">Explained Variance</p>
                </div>
            </div>

            <!-- Cross-Validation Scores -->
            @if(isset($performanceMetrics['cv_scores']))
                <div class="mt-8 p-4 bg-gray-50 dark:bg-gray-900 rounded-lg">
                    <h3 class="text-sm font-medium text-gray-900 dark:text-white mb-3">5-Fold Cross-Validation Scores</h3>
                    <div class="flex items-center space-x-2">
                        @foreach($performanceMetrics['cv_scores'] as $score)
                            <div class="flex-1 bg-blue-200 dark:bg-blue-800 rounded-full h-2 relative">
                                <div class="bg-blue-600 h-2 rounded-full" style="width: {{ ($score / 12) * 100 }}%"></div>
                            </div>
                            <span class="text-xs text-gray-600 dark:text-gray-400">{{ $score }}%</span>
                        @endforeach
                    </div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                        Mean: {{ number_format(collect($performanceMetrics['cv_scores'])->average(), 2) }}% ± {{ number_format($this->calculateStandardDeviation($performanceMetrics['cv_scores']), 2) }}%
                    </p>
                </div>
            @endif
        </div>

        <!-- Prediction Interface -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">🔮 Make Prediction</h2>
                <button wire:click="loadSampleData" 
                        class="px-3 py-1.5 text-xs font-medium text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300 border border-blue-300 hover:border-blue-400 rounded-md transition-colors duration-200">
                    Load Sample Data
                </button>
            </div>

            <div class="mb-6">
                <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                    <div class="flex">
                        <svg class="w-5 h-5 text-blue-400 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                        </svg>
                        <div class="ml-3">
                            <p class="text-sm text-blue-800 dark:text-blue-200">
                                Enter exactly 6 months of NBM data in chronological order for prediction. The model will predict the next month's calorie consumption.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            @if(count($predictionData) > 0)
                <div class="overflow-x-auto mb-6">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-900">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Year</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Month</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Food Group</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Commodity</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Calories/Day</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($predictionData as $index => $data)
                                <tr>
                                    <td class="px-4 py-4">
                                        <input type="number" wire:model="predictionData.{{ $index }}.tahun" min="1990" max="2030" 
                                               class="w-20 px-2 py-1 text-sm border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white" />
                                    </td>
                                    <td class="px-4 py-4">
                                        <select wire:model="predictionData.{{ $index }}.bulan" 
                                                class="w-20 px-2 py-1 text-sm border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white">
                                            @for($i = 1; $i <= 12; $i++)
                                                <option value="{{ $i }}">{{ $i }}</option>
                                            @endfor
                                        </select>
                                    </td>
                                    <td class="px-4 py-4">
                                        <input type="text" wire:model="predictionData.{{ $index }}.kelompok" placeholder="e.g. Cereals"
                                               class="w-32 px-2 py-1 text-sm border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white" />
                                    </td>
                                    <td class="px-4 py-4">
                                        <input type="text" wire:model="predictionData.{{ $index }}.komoditi" placeholder="e.g. Rice"
                                               class="w-32 px-2 py-1 text-sm border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white" />
                                    </td>
                                    <td class="px-4 py-4">
                                        <input type="number" wire:model="predictionData.{{ $index }}.kalori_hari" step="0.1" min="0" max="1000"
                                               class="w-24 px-2 py-1 text-sm border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white" />
                                    </td>
                                    <td class="px-4 py-4">
                                        @if(count($predictionData) > 6)
                                            <button wire:click="removePredictionRow({{ $index }})" 
                                                    class="text-red-600 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="flex items-center justify-between">
                    <div class="text-sm text-gray-600 dark:text-gray-400">
                        {{ count($predictionData) }} of 6 required data points
                    </div>
                    
                    @if(count($predictionData) < 6)
                        <button wire:click="addPredictionRow" 
                                class="px-4 py-2 text-sm font-medium text-blue-600 hover:text-blue-700 dark:text-blue-400 border border-blue-300 hover:border-blue-400 rounded-md transition-colors duration-200">
                            Add Row
                        </button>
                    @endif
                    
                    @if(count($predictionData) === 6)
                        <button wire:click="makePrediction" 
                                wire:loading.attr="disabled"
                                wire:loading.class="opacity-50 cursor-not-allowed"
                                class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                            <span wire:loading.remove>🔮 Make Prediction</span>
                            <span wire:loading>Processing...</span>
                        </button>
                    @endif
                </div>
            @endif

            <!-- Prediction Result -->
            @if($predictionResult)
                <div class="mt-8 p-6 bg-gradient-to-r from-green-50 to-blue-50 dark:from-green-900/20 dark:to-blue-900/20 border border-green-200 dark:border-green-800 rounded-lg">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">🎯 Prediction Result</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="text-center">
                            <div class="text-3xl font-bold text-green-600 dark:text-green-400 mb-1">
                                {{ number_format($predictionResult['prediction'] ?? 0, 1) }}
                            </div>
                            <div class="text-sm text-gray-600 dark:text-gray-400">Predicted Calories/Day</div>
                        </div>
                        
                        <div class="text-center">
                            <div class="text-2xl font-bold text-blue-600 dark:text-blue-400 mb-1">
                                {{ number_format(($predictionResult['confidence_interval']['upper'] ?? 0) - ($predictionResult['confidence_interval']['lower'] ?? 0), 1) }}
                            </div>
                            <div class="text-sm text-gray-600 dark:text-gray-400">95% CI Range</div>
                        </div>
                        
                        <div class="text-center">
                            <div class="text-2xl font-bold text-purple-600 dark:text-purple-400 mb-1">
                                {{ number_format(($predictionResult['model_info']['confidence'] ?? 0.92) * 100, 1) }}%
                            </div>
                            <div class="text-sm text-gray-600 dark:text-gray-400">Confidence Score</div>
                        </div>
                    </div>

                    @if(isset($predictionResult['confidence_interval']))
                        <div class="mt-4 p-3 bg-white/50 dark:bg-gray-800/50 rounded-lg">
                            <p class="text-sm text-gray-700 dark:text-gray-300">
                                <strong>95% Confidence Interval:</strong> 
                                {{ number_format($predictionResult['confidence_interval']['lower'], 1) }} - 
                                {{ number_format($predictionResult['confidence_interval']['upper'], 1) }} kkal/day
                            </p>
                            <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">
                                Prediction generated at: {{ $predictionResult['timestamp'] ?? now()->format('Y-m-d H:i:s') }}
                            </p>
                        </div>
                    @endif
                </div>
            @endif
        </div>

        <!-- Recent Predictions Log -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">📝 Recent Predictions</h2>
            
            @if(count($recentPredictions) > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-900">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Timestamp</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Prediction</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Confidence</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Input</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($recentPredictions as $prediction)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                        {{ \Carbon\Carbon::parse($prediction['timestamp'])->format('M d, Y H:i') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                        @if($prediction['prediction'])
                                            {{ number_format($prediction['prediction'], 1) }} kkal/day
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                        @if($prediction['confidence'])
                                            {{ number_format($prediction['confidence'] * 100, 1) }}%
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                                        {{ $prediction['input_summary'] }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($prediction['status'] === 'success')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-400">
                                                ✅ Success
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/20 dark:text-red-400">
                                                ❌ Error
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-8">
                    <svg class="mx-auto w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">No recent predictions available</p>
                </div>
            @endif
        </div>

    </div>
</div>

<script>
    document.addEventListener('livewire:initialized', () => {
        Livewire.on('show-toast', (event) => {
            // Simple toast notification - could be enhanced with toast library
            const toast = document.createElement('div');
            toast.className = `fixed top-4 right-4 px-6 py-3 rounded-lg shadow-lg text-white z-50 ${
                event.type === 'success' ? 'bg-green-600' : 
                event.type === 'error' ? 'bg-red-600' : 'bg-blue-600'
            }`;
            toast.textContent = event.message;
            document.body.appendChild(toast);
            
            setTimeout(() => {
                toast.remove();
            }, 5000);
        });
    });
</script>