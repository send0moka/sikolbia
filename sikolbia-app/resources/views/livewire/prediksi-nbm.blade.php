<div>
<!-- Success Message Alert -->
@if (session()->has('message'))
    <div x-data="{ show: true }" 
         x-show="show" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform translate-y-2"
         x-transition:enter-end="opacity-100 transform translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         x-init="setTimeout(() => show = false, 8000)"
         class="mb-4 rounded-lg bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 p-4 shadow-sm">
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="ml-3 flex-1">
                <p class="text-sm font-medium text-green-800 dark:text-green-300">
                    {{ session('message') }}
                </p>
            </div>
            <div class="ml-auto pl-3">
                <button @click="show = false" class="inline-flex rounded-md p-1.5 text-green-500 hover:bg-green-100 dark:hover:bg-green-900/50 focus:outline-none">
                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
@endif

<!-- Page Header -->
<div class="mb-6 flex items-start justify-between" x-data="{ showVersionHistory: false, showUpdateModal: false }">
    <div>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Prediksi Kalori per Kapita Neraca Bahan Makanan</h1>
        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            <i class="fas fa-brain mr-1"></i>
            Form untuk melakukan prediksi Kalori per Kapita Neraca Bahan Makanan (NBM) menggunakan model machine learning.
        </p>
    </div>
    <div class="flex items-center gap-2">
        @can('manage model_versions')
        
        @if($hasDataChanges && !$isTraining)
            {{-- ENABLED BUTTON - Bisa diklik --}}
            <button 
                @click="showUpdateModal = true"
                class="px-3 py-1.5 text-xs font-medium rounded-lg bg-purple-600 text-white hover:bg-purple-700 transition-colors shadow-sm flex items-center gap-1.5"
                title="Train New Model Version">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd" />
                </svg>
                Update Model
            </button>
        @else
            {{-- DISABLED - Benar-benar tidak bisa diklik, tidak ada Alpine directive --}}
            <span 
                class="inline-flex px-3 py-1.5 text-xs font-medium rounded-lg bg-purple-600 text-white opacity-50 cursor-not-allowed shadow-sm items-center gap-1.5 pointer-events-none select-none user-select-none"
                title="No data changes detected"
                style="pointer-events: none !important; user-select: none !important;">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd" />
                </svg>
                Update Model
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 ml-0.5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                </svg>
            </span>
        @endif
        
        @endcan
        <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300 border border-blue-300 dark:border-blue-700">
            <i class="fas fa-code-branch mr-1"></i>{{ $activeModelVersion ?? 'v1.0.0' }}
        </span>
        <span class="px-2.5 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300 border border-green-300 dark:border-green-700">
            <i class="fas fa-check-circle mr-1"></i>{{ $activeModelStage ?? 'Production' }}
        </span>
        <button @click="showVersionHistory = true" 
            class="w-7 h-7 flex items-center justify-center rounded-full bg-indigo-600 text-white hover:bg-indigo-700 transition-colors shadow-sm"
            title="Version History">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
            </svg>
        </button>
    </div>

    <!-- Version History Modal -->
    <div x-show="showVersionHistory" 
         x-cloak
         @click.self="showVersionHistory = false"
         class="fixed inset-0 z-50 flex items-center justify-center p-4"
         style="background-color: rgba(0, 0, 0, 0.5);">
        <div class="bg-white dark:bg-zinc-800 rounded-lg shadow-xl max-w-2xl w-full max-h-[80vh] overflow-hidden">
            <!-- Modal Header -->
            <div class="flex items-center justify-between p-4 border-b border-gray-200 dark:border-zinc-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                    <i class="fas fa-clock-rotate-left mr-2 text-blue-600"></i>
                    Version History
                </h3>
                <button @click="showVersionHistory = false" 
                    class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <!-- Modal Body -->
            <div class="p-4 overflow-y-auto max-h-[60vh]">
                <div class="space-y-4">
                    @forelse($modelVersions as $index => $modelVersion)
                    <!-- Version {{ $modelVersion->version }} -->
                    <div class="flex gap-4">
                        <div class="flex flex-col items-center">
                            <div class="w-3 h-3 rounded-full 
                                @if($modelVersion->is_active) bg-green-500
                                @elseif($modelVersion->release_stage === 'production') bg-blue-500
                                @else bg-gray-400
                                @endif"></div>
                            @if(!$loop->last)
                            <div class="w-0.5 h-full bg-gray-300 dark:bg-gray-600"></div>
                            @endif
                        </div>
                        <div class="flex-1 {{ !$loop->last ? 'pb-6' : '' }}">
                            <div class="flex items-center justify-between mb-2">
                                <div class="flex items-center gap-2">
                                    <span class="font-semibold text-gray-900 dark:text-white">{{ $modelVersion->version }}</span>
                                    @if($modelVersion->is_active)
                                    <span class="px-2 py-0.5 text-xs font-medium rounded bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300">
                                        Active
                                    </span>
                                    @endif
                                    <span class="px-2 py-0.5 text-xs font-medium rounded 
                                        @if($modelVersion->release_stage === 'production') bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300
                                        @elseif($modelVersion->release_stage === 'beta') bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300
                                        @else bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300
                                        @endif">
                                        {{ ucfirst($modelVersion->release_stage) }}
                                    </span>
                                </div>
                                @if(!$modelVersion->is_active && $modelVersion->status === 'completed')
                                @can('manage model_versions')
                                <button wire:click="switchModelVersion('{{ $modelVersion->version }}')" 
                                    class="px-2 py-1 text-xs font-medium rounded bg-indigo-100 text-indigo-700 hover:bg-indigo-200 dark:bg-indigo-900/30 dark:text-indigo-300">
                                    <i class="fas fa-exchange-alt mr-1"></i>Switch
                                </button>
                                @endcan
                                @endif
                            </div>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">{{ $modelVersion->model_name }}</p>
                            <ul class="text-xs text-gray-600 dark:text-gray-400 space-y-1 mb-2">
                                @if($modelVersion->description)
                                <li>• {{ $modelVersion->description }}</li>
                                @endif
                                <li>• MAE: {{ number_format($modelVersion->mae, 2) }}, 
                                    RMSE: {{ number_format($modelVersion->rmse, 2) }}, 
                                    MAPE: {{ number_format($modelVersion->mape, 2) }}%, 
                                    R²: {{ number_format($modelVersion->r2_score, 4) }}</li>
                                @if($modelVersion->training_data_count)
                                <li>• Training data: {{ number_format($modelVersion->training_data_count) }} records</li>
                                @endif
                            </ul>
                            <p class="text-xs text-gray-500 dark:text-gray-500">
                                <i class="fas fa-calendar mr-1"></i>Released: {{ $modelVersion->released_at?->format('F j, Y') ?? 'N/A' }}
                                @if($modelVersion->trainer)
                                <span class="ml-2">by {{ $modelVersion->trainer->name }}</span>
                                @endif
                            </p>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                        <i class="fas fa-inbox text-3xl mb-2"></i>
                        <p>No model versions found</p>
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="p-4 border-t border-gray-200 dark:border-zinc-700 bg-gray-50 dark:bg-zinc-900">
                <p class="text-xs text-gray-600 dark:text-gray-400">
                    <i class="fas fa-info-circle mr-1"></i>
                    Model versions are trained on historical NBM data from 1993-2024
                </p>
            </div>
        </div>
    </div>

    <!-- Update Model Modal - Hanya tampil jika hasDataChanges true dan isTraining false -->
    <div x-show="showUpdateModal && {{ $hasDataChanges ? 'true' : 'false' }} && {{ $isTraining ? 'false' : 'true' }}" 
         x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center p-4"
         style="background-color: rgba(0, 0, 0, 0.5);">
        <div class="bg-white dark:bg-zinc-800 rounded-lg shadow-xl max-w-lg w-full overflow-hidden">
            <!-- Modal Header -->
            <div class="flex items-center justify-between p-4 border-b border-gray-200 dark:border-zinc-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                    <i class="fas fa-sync-alt mr-2 text-purple-600"></i>
                    {{ $isTraining ? 'Training in Progress...' : 'Train New Model Version' }}
                </h3>
                @if(!$isTraining)
                <button @click="showUpdateModal = false" 
                    class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                    <i class="fas fa-times text-xl"></i>
                </button>
                @endif
            </div>
            
            <!-- Modal Body -->
            <div class="p-4">
                @if($isTraining)
                <div class="space-y-4">
                    <!-- Spinner dan Status -->
                    <div class="flex items-center justify-center">
                        <div class="animate-spin rounded-full h-16 w-16 border-4 border-purple-200 border-t-purple-600"></div>
                    </div>
                    
                    <!-- Progress Percentage -->
                    <div class="text-center">
                        <p class="text-3xl font-bold text-purple-600 dark:text-purple-400">
                            {{ $trainingProgress }}%
                        </p>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                            {{ $trainingMessage }}
                        </p>
                    </div>
                    
                    <!-- Progress Bar -->
                    <div class="w-full bg-gray-200 dark:bg-zinc-700 rounded-full h-3 overflow-hidden">
                        <div class="bg-gradient-to-r from-purple-500 to-purple-600 h-3 rounded-full transition-all duration-500 ease-out" 
                             style="width: {{ $trainingProgress }}%"></div>
                    </div>
                    
                    <!-- Estimasi Waktu -->
                    @php
                        $totalMinutes = 20; // estimasi total 20 menit
                        $remainingMinutes = $trainingProgress > 0 ? round(($totalMinutes * (100 - $trainingProgress)) / 100) : $totalMinutes;
                    @endphp
                    <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-3">
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-blue-800 dark:text-blue-300">
                                <i class="fas fa-clock mr-2"></i>Estimasi Sisa Waktu:
                            </span>
                            <span class="font-semibold text-blue-900 dark:text-blue-200">
                                {{ $remainingMinutes }} menit
                            </span>
                        </div>
                    </div>
                    
                    <!-- Warning Notice -->
                    <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-3">
                        <div class="flex items-start">
                            <i class="fas fa-exclamation-triangle text-yellow-600 dark:text-yellow-400 mt-0.5 mr-2"></i>
                            <div class="text-xs text-yellow-800 dark:text-yellow-300">
                                <p class="font-semibold mb-1">Jangan tutup halaman ini!</p>
                                <p>Proses training sedang berjalan. Menutup halaman akan membatalkan training.</p>
                            </div>
                        </div>
                    </div>
                </div>
                @else
                <form wire:submit="trainNewModel">
                    <div class="space-y-4">
                        <!-- Info Alert -->
                        <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                            <div class="flex items-start">
                                <i class="fas fa-info-circle text-blue-600 dark:text-blue-400 mt-0.5 mr-3"></i>
                                <div class="text-sm text-blue-800 dark:text-blue-300">
                                    <p class="font-semibold mb-1">Training will create a new model version</p>
                                    <ul class="list-disc list-inside space-y-1 text-xs">
                                        <li>Uses all current data from transaksi_nbms table</li>
                                        <li>Trains LSTM + XGBoost + Huber ensemble</li>
                                        <li>New version will be: <strong>{{ $nextModelVersion }}</strong></li>
                                        <li>Training takes approximately 15-30 minutes</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Release Stage -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <i class="fas fa-tag mr-1"></i>Release Stage
                            </label>
                            <select wire:model="releaseStage" 
                                class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-white rounded-md focus:ring-purple-500 focus:border-purple-500">
                                <option value="alpha">Alpha (Testing)</option>
                                <option value="beta" selected>Beta (Pre-Production)</option>
                                <option value="production">Production (Ready)</option>
                            </select>
                        </div>

                        <!-- Description -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <i class="fas fa-comment mr-1"></i>Description (Optional)
                            </label>
                            <textarea wire:model="modelDescription" rows="3"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-white rounded-md focus:ring-purple-500 focus:border-purple-500"
                                placeholder="e.g., Updated with Q1 2026 data"></textarea>
                        </div>

                        <!-- Info Notice -->
                        <div class="bg-purple-50 dark:bg-purple-900/20 border border-purple-200 dark:border-purple-800 rounded-lg p-3">
                            <div class="flex items-start">
                                <i class="fas fa-info-circle text-purple-600 dark:text-purple-400 mt-0.5 mr-2"></i>
                                <div class="text-xs text-purple-800 dark:text-purple-300">
                                    <p class="font-semibold mb-1">Proses Training</p>
                                    <p>Training akan berjalan secara real-time dengan progress bar. Estimasi waktu: <strong>15-20 menit</strong>.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex justify-end gap-3 mt-6">
                        <button type="button" @click="showUpdateModal = false"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 dark:bg-zinc-700 dark:text-gray-300 dark:border-zinc-600 dark:hover:bg-zinc-600">
                            Cancel
                        </button>
                        <button type="submit"
                            class="px-4 py-2 text-sm font-medium text-white bg-purple-600 rounded-md hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                            <i class="fas fa-rocket mr-2"></i>Start Training
                        </button>
                    </div>
                </form>
                @endif
            </div>
        </div>
    </div>
    
    <!-- Training Progress Modal - Muncul saat isTraining = true -->
    <div x-show="{{ $isTraining ? 'true' : 'false' }}"
         x-cloak
         class="fixed inset-0 z-[60] flex items-center justify-center p-4"
         style="background-color: rgba(0, 0, 0, 0.75); pointer-events: auto;"
         @click.prevent.stop
         wire:poll.2s="checkTrainingStatus">
        <div x-data="{ cancelConfirm: false }"
             @click.stop
             class="bg-white dark:bg-zinc-800 rounded-lg shadow-2xl max-w-lg w-full overflow-hidden border-2 border-purple-500">
            <!-- Modal Header -->
            <div class="bg-gradient-to-r from-purple-600 to-purple-700 p-4">
                <h3 class="text-lg font-bold text-white flex items-center">
                    <i class="fas fa-cog fa-spin mr-3 text-2xl"></i>
                    Training Model in Progress
                </h3>
            </div>
            
            <!-- Modal Body -->
            <div class="p-6 space-y-5">
                <!-- Spinner dan Status -->
                <div class="flex items-center justify-center">
                    <div class="relative">
                        <div class="animate-spin rounded-full h-20 w-20 border-4 border-purple-200 border-t-purple-600"></div>
                        <div class="absolute inset-0 flex items-center justify-center">
                            <i class="fas fa-brain text-2xl text-purple-600"></i>
                        </div>
                    </div>
                </div>
                
                <!-- Progress Percentage -->
                <div class="text-center">
                    <p class="text-4xl font-bold text-purple-600 dark:text-purple-400 mb-2">
                        {{ $trainingProgress }}%
                    </p>
                    <p class="text-sm font-medium text-gray-700 dark:text-gray-300">
                        {{ $trainingMessage ?: 'Processing...' }}
                    </p>
                </div>
                
                <!-- Progress Bar -->
                <div class="w-full bg-gray-200 dark:bg-zinc-700 rounded-full h-4 overflow-hidden shadow-inner">
                    <div class="bg-gradient-to-r from-purple-500 via-purple-600 to-purple-700 h-4 rounded-full transition-all duration-500 ease-out relative" 
                         style="width: {{ $trainingProgress }}%">
                        <div class="absolute inset-0 bg-white opacity-30 animate-pulse"></div>
                    </div>
                </div>
                
                <!-- Estimasi Waktu -->
                @php
                    $totalMinutes = 20;
                    $remainingMinutes = $trainingProgress > 0 ? max(1, round(($totalMinutes * (100 - $trainingProgress)) / 100)) : $totalMinutes;
                @endphp
                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 border border-blue-300 dark:border-blue-700 rounded-lg p-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <i class="fas fa-clock text-blue-600 dark:text-blue-400 text-xl mr-3"></i>
                            <span class="text-sm font-medium text-blue-900 dark:text-blue-200">
                                Estimasi Sisa Waktu
                            </span>
                        </div>
                        <span class="text-2xl font-bold text-blue-700 dark:text-blue-300">
                            {{ $remainingMinutes }} <span class="text-sm">menit</span>
                        </span>
                    </div>
                </div>
                
                <!-- Warning Notice -->
                <div class="bg-yellow-50 dark:bg-yellow-900/20 border-l-4 border-yellow-400 dark:border-yellow-600 p-4 rounded">
                    <div class="flex items-start">
                        <i class="fas fa-exclamation-triangle text-yellow-600 dark:text-yellow-400 mt-1 mr-3 text-lg"></i>
                        <div class="text-sm text-yellow-800 dark:text-yellow-300">
                            <p class="font-bold mb-1">⚠️ Jangan tutup halaman ini!</p>
                            <p class="text-xs">Proses training sedang berjalan. Menutup halaman akan membatalkan seluruh progress.</p>
                        </div>
                    </div>
                </div>
                
                <!-- Cancel Button -->
                <div x-show="!cancelConfirm" class="pt-2">
                    <button @click="cancelConfirm = true" type="button"
                        class="w-full px-4 py-2.5 text-sm font-semibold text-red-700 bg-red-50 border-2 border-red-300 rounded-lg hover:bg-red-100 dark:bg-red-900/30 dark:text-red-300 dark:border-red-700 dark:hover:bg-red-900/50 transition-colors">
                        <i class="fas fa-times-circle mr-2"></i>Cancel Training
                    </button>
                </div>
                
                <!-- Konfirmasi Cancel -->
                <div x-show="cancelConfirm" 
                     style="display: none;"
                     class="bg-red-50 dark:bg-red-900/30 border-2 border-red-400 dark:border-red-700 rounded-lg p-4 shadow-lg">
                    <div class="flex items-start mb-3">
                        <i class="fas fa-exclamation-circle text-red-600 dark:text-red-400 text-2xl mr-3 mt-1"></i>
                        <div>
                            <p class="text-base font-bold text-red-900 dark:text-red-200 mb-2">
                                Konfirmasi Pembatalan
                            </p>
                            <p class="text-sm text-red-800 dark:text-red-300 mb-1">
                                Apakah Anda yakin ingin membatalkan training?
                            </p>
                            <p class="text-xs text-red-700 dark:text-red-400">
                                ⚠️ Progress <strong>{{ $trainingProgress }}%</strong> yang sudah berjalan akan hilang dan harus diulang dari awal.
                            </p>
                        </div>
                    </div>
                    <div class="flex gap-2 justify-end mt-4">
                        <button @click="cancelConfirm = false" type="button"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 dark:bg-zinc-700 dark:text-gray-300 dark:border-zinc-600 dark:hover:bg-zinc-600 transition-colors">
                            <i class="fas fa-arrow-left mr-1"></i>Tidak, Lanjutkan
                        </button>
                        <button wire:click="cancelTraining" 
                                @click="cancelConfirm = false"
                                type="button"
                            class="px-4 py-2 text-sm font-bold text-white bg-red-600 rounded-lg hover:bg-red-700 shadow-md transition-colors">
                            <i class="fas fa-ban mr-1"></i>Ya, Batalkan Training
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

    <!-- Main Prediction Interface -->
    <div class="grid gap-6 lg:grid-cols-3">
            <!-- Input Sidebar -->
            <div class="lg:col-span-1">
                <div class="bg-white dark:bg-zinc-800 rounded-lg shadow-sm p-6 border border-zinc-200 dark:border-zinc-700">
                    <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white">Parameter Prediksi</h3>
                    
                    <div class="space-y-4">
                        <!-- Kelompok Selector -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                <i class="fas fa-layer-group mr-1"></i>Pilih Kelompok Pangan
                            </label>
                            <select wire:model.live="selectedKelompok" 
                                class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-white rounded-md focus:ring-blue-500 focus:border-blue-500">
                                <option value="">-- Pilih Kelompok --</option>
                                @foreach ($kelompokList as $kelompok)
                                    <option value="{{ $kelompok['kode'] }}">
                                        {{ $kelompok['nama'] }}
                                    </option>
                                @endforeach
                            </select>
                            @error('selectedKelompok') 
                                <span class="text-sm text-red-600">{{ $message }}</span> 
                            @enderror
                        </div>

                        <!-- Komoditi Selector -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                <i class="fas fa-apple-alt mr-1"></i>Pilih Komoditi
                            </label>
                            <select wire:model="selectedKomoditi" 
                                {{ $selectedKelompok ? '' : 'disabled' }}
                                class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-white rounded-md focus:ring-blue-500 focus:border-blue-500 disabled:opacity-50 disabled:cursor-not-allowed">
                                <option value="">{{ $selectedKelompok ? '-- Pilih Komoditi --' : '-- Pilih Kelompok Terlebih Dahulu --' }}</option>
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
                                <div class="p-4 bg-yellow-50 rounded-lg">
                                    <p class="text-xs text-yellow-600 font-medium">MAE</p>
                                    <p class="text-2xl font-bold text-yellow-700">
                                        {{ number_format($komoditiPredictionResult['model_info']['mae'] ?? 760.39, 2) }}
                                    </p>
                                </div>
                                <div class="p-4 bg-red-50 rounded-lg">
                                    <p class="text-xs text-red-600 font-medium">RMSE</p>
                                    <p class="text-2xl font-bold text-red-700">
                                        {{ number_format($komoditiPredictionResult['model_info']['rmse'] ?? 1692.31, 2) }}
                                    </p>
                                </div>
                                <div class="p-4 bg-green-50 rounded-lg">
                                    <p class="text-xs text-green-600 font-medium">MAPE</p>
                                    <p class="text-2xl font-bold text-green-700">
                                        {{ number_format($komoditiPredictionResult['model_info']['mape'] ?? 3.73, 2) }}%
                                    </p>
                                </div>
                                <div class="p-4 bg-blue-50 rounded-lg">
                                    <p class="text-xs text-blue-600 font-medium">R² Score</p>
                                    <p class="text-2xl font-bold text-blue-700">
                                        {{ number_format($komoditiPredictionResult['model_info']['r2'] ?? 0.9912, 4) }}
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
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Model Digunakan</th>
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
                                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                @php
                                                    $modelUsed = $pred['model_used'] ?? 'Ensemble';
                                                    $badgeColor = $modelUsed === 'Ensemble' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800';
                                                @endphp
                                                <span class="px-2.5 py-1 rounded-full text-xs font-semibold {{ $badgeColor }}">
                                                    {{ $modelUsed }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Comprehensive Summary Analysis -->
                        @if (isset($komoditiPredictionResult['analysis']))
                        <div class="mt-6 space-y-4">
                            <!-- Summary Header -->
                            <div class="border-b border-gray-200 dark:border-zinc-700 pb-3">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                                    <i class="fas fa-chart-area mr-2 text-blue-600"></i>
                                    Analisis & Insight Prediksi
                                </h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                    Ringkasan komprehensif dari data historis dan hasil prediksi
                                </p>
                            </div>

                            <!-- Kegunaan Data Section -->
                            <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                                <div class="flex items-start">
                                    <i class="fas fa-info-circle text-blue-600 dark:text-blue-400 mt-1 mr-3"></i>
                                    <div>
                                        <h4 class="font-semibold text-blue-900 dark:text-blue-200 mb-2">Kegunaan Prediksi NBM</h4>
                                        <ul class="text-sm text-blue-800 dark:text-blue-300 space-y-1">
                                            <li>• <strong>Perencanaan Ketahanan Pangan:</strong> Memperkirakan ketersediaan kalori per kapita untuk {{ $nMonths }} bulan ke depan</li>
                                            <li>• <strong>Kebijakan Pangan:</strong> Membantu pengambilan keputusan terkait import, distribusi, dan stok nasional</li>
                                            <li>• <strong>Early Warning System:</strong> Mendeteksi potensi defisit atau surplus konsumsi pangan</li>
                                            <li>• <strong>Monitoring Nutrisi:</strong> Memantau trend konsumsi kalori masyarakat dari komoditi {{ $this->getKomoditiName($selectedKomoditi) }}</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <!-- Model Strategy Explanation -->
                            <div class="bg-purple-50 dark:bg-purple-900/20 border border-purple-200 dark:border-purple-800 rounded-lg p-4">
                                <div class="flex items-start">
                                    <i class="fas fa-brain text-purple-600 dark:text-purple-400 mt-1 mr-3"></i>
                                    <div class="w-full">
                                        <h4 class="font-semibold text-purple-900 dark:text-purple-200 mb-2">Strategi Pemilihan Model</h4>
                                        <div class="text-sm text-purple-800 dark:text-purple-300 space-y-2">
                                            <p>Sistem menggunakan <strong>Conditional Ensemble Strategy</strong> dengan threshold 5.000 ribu ton untuk mengoptimalkan akurasi prediksi:</p>
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mt-2">
                                                <div class="bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-700 rounded p-3">
                                                    <p class="font-semibold text-green-900 dark:text-green-200 mb-1">
                                                        <i class="fas fa-robot mr-1"></i>XGBoost
                                                    </p>
                                                    <p class="text-xs text-green-800 dark:text-green-300">
                                                        Digunakan untuk konsumsi <strong>&lt; 5.000 ribu ton</strong>. Unggul pada prediksi nilai kecil hingga menengah dengan pola stabil dan detail fitur kompleks.
                                                    </p>
                                                </div>
                                                <div class="bg-blue-50 dark:bg-blue-900/30 border border-blue-200 dark:border-blue-700 rounded p-3">
                                                    <p class="font-semibold text-blue-900 dark:text-blue-200 mb-1">
                                                        <i class="fas fa-project-diagram mr-1"></i>Ensemble (90% LSTM + 5% XGBoost + 5% Huber)
                                                    </p>
                                                    <p class="text-xs text-blue-800 dark:text-blue-300">
                                                        Digunakan untuk konsumsi <strong>≥ 5.000 ribu ton</strong>. Menggabungkan LSTM untuk menangkap temporal pattern kompleks pada skala besar.
                                                    </p>
                                                </div>
                                            </div>
                                            <p class="text-xs mt-2">
                                                <strong>Model Performance:</strong> MAPE 3.73% • MAE 759.61 ton • R² 0.9912 (99.12% variance explained)
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Trend Analysis -->
                            @php
                                $analysis = $komoditiPredictionResult['analysis'];
                                $trend = $analysis['trend'];
                                $trendColor = $trend['direction'] === 'Naik Signifikan' || $trend['direction'] === 'Naik' ? 'green' : 
                                              ($trend['direction'] === 'Turun Signifikan' ? 'red' : 'yellow');
                                $trendIcon = $trend['direction'] === 'Naik Signifikan' || $trend['direction'] === 'Naik' ? 'arrow-trend-up' : 
                                             ($trend['direction'] === 'Turun Signifikan' ? 'arrow-trend-down' : 'minus');
                            @endphp

                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                <!-- Trend Card -->
                                <div class="bg-white dark:bg-zinc-800 border border-{{ $trendColor }}-200 dark:border-{{ $trendColor }}-800 rounded-lg p-4">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Trend Prediksi</span>
                                        <i class="fas fa-{{ $trendIcon }} text-{{ $trendColor }}-600"></i>
                                    </div>
                                    <div class="text-2xl font-bold text-{{ $trendColor }}-600 dark:text-{{ $trendColor }}-400">
                                        {{ $trend['direction'] }}
                                    </div>
                                    <div class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                        {{ $trend['percent'] >= 0 ? '+' : '' }}{{ number_format($trend['percent'], 2) }}%
                                    </div>
                                    <p class="text-xs text-gray-500 dark:text-gray-500 mt-2">
                                        dari periode terakhir historis ke prediksi akhir
                                    </p>
                                </div>

                                <!-- Growth Rate Card -->
                                <div class="bg-white dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 rounded-lg p-4">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Laju Pertumbuhan</span>
                                        <i class="fas fa-percentage text-purple-600"></i>
                                    </div>
                                    <div class="text-2xl font-bold text-purple-600 dark:text-purple-400">
                                        {{ $trend['growth_rate'] >= 0 ? '+' : '' }}{{ number_format($trend['growth_rate'], 2) }}%
                                    </div>
                                    <p class="text-xs text-gray-500 dark:text-gray-500 mt-2">
                                        Perubahan dari nilai historis terakhir
                                    </p>
                                </div>

                                <!-- Volatility Card -->
                                @php
                                    $volatility = $analysis['volatility'];
                                    $volColor = $volatility['level'] === 'Tinggi' ? 'red' : ($volatility['level'] === 'Sedang' ? 'yellow' : 'green');
                                @endphp
                                <div class="bg-white dark:bg-zinc-800 border border-{{ $volColor }}-200 dark:border-{{ $volColor }}-800 rounded-lg p-4">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Volatilitas</span>
                                        <i class="fas fa-wave-square text-{{ $volColor }}-600"></i>
                                    </div>
                                    <div class="text-2xl font-bold text-{{ $volColor }}-600 dark:text-{{ $volColor }}-400">
                                        {{ $volatility['level'] }}
                                    </div>
                                    <div class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                        CV: {{ number_format($volatility['coefficient'], 2) }}%
                                    </div>
                                    <p class="text-xs text-gray-500 dark:text-gray-500 mt-2">
                                        Stabilitas prediksi {{ $nMonths }} bulan ke depan
                                    </p>
                                </div>
                            </div>

                            <!-- Detailed Statistics -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <!-- Historical Stats -->
                                <div class="bg-gray-50 dark:bg-zinc-900 border border-gray-200 dark:border-zinc-700 rounded-lg p-4">
                                    <h4 class="font-semibold text-gray-900 dark:text-white mb-3 flex items-center">
                                        <i class="fas fa-history mr-2 text-gray-600"></i>
                                        Statistik Data Historis
                                    </h4>
                                    <div class="grid grid-cols-2 gap-3">
                                        <div>
                                            <p class="text-xs text-gray-600 dark:text-gray-400">Rata-rata</p>
                                            <p class="text-lg font-semibold text-gray-900 dark:text-white">
                                                {{ number_format($analysis['historical']['mean'], 2) }}
                                            </p>
                                        </div>
                                        <div>
                                            <p class="text-xs text-gray-600 dark:text-gray-400">Terakhir</p>
                                            <p class="text-lg font-semibold text-gray-900 dark:text-white">
                                                {{ number_format($analysis['historical']['last_value'], 2) }}
                                            </p>
                                        </div>
                                        <div>
                                            <p class="text-xs text-gray-600 dark:text-gray-400">Minimum</p>
                                            <p class="text-lg font-semibold text-gray-900 dark:text-white">
                                                {{ number_format($analysis['historical']['min'], 2) }}
                                            </p>
                                        </div>
                                        <div>
                                            <p class="text-xs text-gray-600 dark:text-gray-400">Maksimum</p>
                                            <p class="text-lg font-semibold text-gray-900 dark:text-white">
                                                {{ number_format($analysis['historical']['max'], 2) }}
                                            </p>
                                        </div>
                                    </div>
                                    <p class="text-xs text-gray-500 dark:text-gray-500 mt-3">
                                        <i class="fas fa-calendar mr-1"></i>
                                        Periode {{ $historicalPeriod === 'all' ? 'Semua Data' : $historicalPeriod . ' Bulan' }} Historis
                                    </p>
                                </div>

                                <!-- Prediction Stats -->
                                <div class="bg-gradient-to-br from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                                    <h4 class="font-semibold text-gray-900 dark:text-white mb-3 flex items-center">
                                        <i class="fas fa-crystal-ball mr-2 text-blue-600"></i>
                                        Statistik Prediksi
                                    </h4>
                                    <div class="grid grid-cols-2 gap-3">
                                        <div>
                                            <p class="text-xs text-gray-600 dark:text-gray-400">Rata-rata</p>
                                            <p class="text-lg font-semibold text-blue-900 dark:text-blue-200">
                                                {{ number_format($analysis['prediction']['mean'], 2) }}
                                            </p>
                                        </div>
                                        <div>
                                            <p class="text-xs text-gray-600 dark:text-gray-400">Akhir Periode</p>
                                            <p class="text-lg font-semibold text-blue-900 dark:text-blue-200">
                                                {{ number_format($analysis['prediction']['last_value'], 2) }}
                                            </p>
                                        </div>
                                        <div>
                                            <p class="text-xs text-gray-600 dark:text-gray-400">Minimum</p>
                                            <p class="text-lg font-semibold text-blue-900 dark:text-blue-200">
                                                {{ number_format($analysis['prediction']['min'], 2) }}
                                            </p>
                                        </div>
                                        <div>
                                            <p class="text-xs text-gray-600 dark:text-gray-400">Maksimum</p>
                                            <p class="text-lg font-semibold text-blue-900 dark:text-blue-200">
                                                {{ number_format($analysis['prediction']['max'], 2) }}
                                            </p>
                                        </div>
                                    </div>
                                    <p class="text-xs text-gray-500 dark:text-gray-500 mt-3">
                                        <i class="fas fa-forward mr-1"></i>
                                        Prediksi {{ $nMonths }} Bulan ke Depan
                                    </p>
                                </div>
                            </div>

                            <!-- Interpretation Box -->
                            <div class="bg-white dark:bg-zinc-800 border-2 border-purple-400 dark:border-purple-600 rounded-lg p-5 shadow-sm">
                                <h4 class="font-bold text-gray-900 dark:text-white mb-3 flex items-center text-base">
                                    <i class="fas fa-lightbulb mr-2 text-yellow-600 dark:text-yellow-400"></i>
                                    Interpretasi & Rekomendasi
                                </h4>
                                <div class="text-sm font-medium text-gray-800 dark:text-gray-100 space-y-2">
                                    @if ($trend['direction'] === 'Naik Signifikan')
                                        <p class="text-gray-900 dark:text-gray-100">✓ <strong class="text-gray-900 dark:text-white">Tren Positif:</strong> Prediksi menunjukkan peningkatan signifikan konsumsi kalori dari komoditi ini. Ini mengindikasikan peningkatan ketersediaan atau konsumsi yang baik.</p>
                                    @elseif ($trend['direction'] === 'Naik')
                                        <p class="text-gray-900 dark:text-gray-100">✓ <strong class="text-gray-900 dark:text-white">Tren Stabil-Meningkat:</strong> Konsumsi diprediksi meningkat secara moderat, menunjukkan kondisi yang stabil dengan pertumbuhan positif.</p>
                                    @elseif ($trend['direction'] === 'Turun Signifikan')
                                        <p class="text-gray-900 dark:text-gray-100">⚠ <strong class="text-gray-900 dark:text-white">Perlu Perhatian:</strong> Prediksi menunjukkan penurunan signifikan. Evaluasi kebijakan distribusi dan ketersediaan stok diperlukan.</p>
                                    @else
                                        <p class="text-gray-900 dark:text-gray-100">✓ <strong class="text-gray-900 dark:text-white">Kondisi Stabil:</strong> Prediksi menunjukkan konsumsi yang relatif stabil tanpa perubahan signifikan.</p>
                                    @endif

                                    @if ($volatility['level'] === 'Tinggi')
                                        <p class="text-gray-900 dark:text-gray-100">⚠ <strong class="text-gray-900 dark:text-white">Volatilitas Tinggi:</strong> Terdapat fluktuasi yang cukup besar dalam prediksi. Perlu monitoring ekstra dan cadangan buffer stok.</p>
                                    @elseif ($volatility['level'] === 'Sedang')
                                        <p class="text-gray-900 dark:text-gray-100">→ <strong class="text-gray-900 dark:text-white">Volatilitas Sedang:</strong> Prediksi menunjukkan variasi normal. Pantau secara berkala untuk antisipasi perubahan.</p>
                                    @else
                                        <p class="text-gray-900 dark:text-gray-100">✓ <strong class="text-gray-900 dark:text-white">Volatilitas Rendah:</strong> Prediksi sangat stabil dengan variasi minimal. Kondisi ideal untuk perencanaan jangka panjang.</p>
                                    @endif

                                    @php
                                        $overallChange = $analysis['comparison']['overall_change'];
                                    @endphp
                                    @if (abs($overallChange) > 10)
                                        <p class="text-gray-900 dark:text-gray-100">📊 <strong class="text-gray-900 dark:text-white">Perubahan Signifikan:</strong> Terdapat perubahan {{ number_format(abs($overallChange), 2) }}% antara rata-rata historis dan prediksi. 
                                        {{ $overallChange > 0 ? 'Pertimbangkan peningkatan produksi atau import.' : 'Evaluasi faktor penyebab penurunan konsumsi.' }}</p>
                                    @endif

                                    <div class="mt-3 pt-3 border-t-2 border-gray-300 dark:border-gray-600">
                                        <p class="font-bold text-gray-900 dark:text-white mb-1">📌 Catatan Penting:</p>
                                        <p class="text-xs font-medium text-gray-700 dark:text-gray-200">Model menggunakan LSTM Enhanced Ensemble dengan confidence interval 95%. Akurasi tergantung kualitas data historis dan faktor eksternal tidak terprediksi.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif

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
</div>

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
