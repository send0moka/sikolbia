<div>
    <!-- Header -->
    <div class="mb-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-semibold text-neutral-900 dark:text-white flex items-center">
                    <svg class="w-6 h-6 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                    Prediksi Konsumsi Pangan NBM
                </h1>
                <p class="mt-1 text-sm text-neutral-600 dark:text-neutral-400">
                    Sistem prediksi berbasis Machine Learning untuk konsumsi kalori pangan dengan akurasi tinggi
                </p>
            </div>
            <div class="flex items-center gap-3">
                <div class="flex items-center gap-2">
                    @if($apiStatus === 'healthy')
                        <div class="flex items-center px-3 py-1 text-sm font-medium text-green-700 bg-green-100 rounded-full dark:bg-green-900/30 dark:text-green-400">
                            <div class="w-2 h-2 bg-green-500 rounded-full mr-2"></div>
                            API Connected
                        </div>
                    @else
                        <div class="flex items-center px-3 py-1 text-sm font-medium text-red-700 bg-red-100 rounded-full dark:bg-red-900/30 dark:text-red-400">
                            <div class="w-2 h-2 bg-red-500 rounded-full mr-2"></div>
                            API Error
                        </div>
                    @endif
                    <flux:button wire:click="checkApiHealth" variant="outline" size="sm" class="border-neutral-300 hover:border-blue-500 hover:text-blue-600">
                        <svg class="w-4 h-4 inline mr-2 -translate-y-[1.5px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                        Refresh
                    </flux:button>
                </div>
            </div>
        </div>
    </div>

    <!-- Flash Messages -->
    @if (session()->has('message'))
        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded dark:bg-green-900/30 dark:border-green-700 dark:text-green-300">
            {{ session('message') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded dark:bg-red-900/30 dark:border-red-700 dark:text-red-300">
            {{ session('error') }}
        </div>
    @endif

    <!-- Model Statistics -->
    @if($modelStats)
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white dark:bg-zinc-800 rounded-lg border border-zinc-200 dark:border-zinc-700 p-6 text-center">
            <div class="text-green-500 mb-3">
                <svg class="w-8 h-8 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                </svg>
            </div>
            <h3 class="text-2xl font-bold text-neutral-900 dark:text-white">{{ $modelStats['accuracy'] ?? 'N/A' }}</h3>
            <p class="text-sm text-neutral-600 dark:text-neutral-400">Model Accuracy</p>
        </div>
        
        <div class="bg-white dark:bg-zinc-800 rounded-lg border border-zinc-200 dark:border-zinc-700 p-6 text-center">
            <div class="text-blue-500 mb-3">
                <svg class="w-8 h-8 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                </svg>
            </div>
            <h3 class="text-2xl font-bold text-neutral-900 dark:text-white">{{ $modelStats['mape'] ?? 'N/A' }}</h3>
            <p class="text-sm text-neutral-600 dark:text-neutral-400">MAPE</p>
        </div>
        
        <div class="bg-white dark:bg-zinc-800 rounded-lg border border-zinc-200 dark:border-zinc-700 p-6 text-center">
            <div class="text-yellow-500 mb-3">
                <svg class="w-8 h-8 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4"></path>
                </svg>
            </div>
            <h3 class="text-2xl font-bold text-neutral-900 dark:text-white">{{ number_format($modelStats['training_records'] ?? 0) }}</h3>
            <p class="text-sm text-neutral-600 dark:text-neutral-400">Training Data</p>
        </div>
        
        <div class="bg-white dark:bg-zinc-800 rounded-lg border border-zinc-200 dark:border-zinc-700 p-6 text-center">
            <div class="text-neutral-500 mb-3">
                <svg class="w-8 h-8 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                    <circle cx="12" cy="12" r="3"></circle>
                </svg>
            </div>
            <p class="text-sm font-medium text-neutral-900 dark:text-white">{{ $modelStats['model_type'] ?? 'N/A' }}</p>
            <p class="text-sm text-neutral-600 dark:text-neutral-400">Model Type</p>
        </div>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Input Form -->
        <div class="lg:col-span-2">
            <div class="bg-white dark:bg-zinc-800 rounded-lg border border-zinc-200 dark:border-zinc-700">
                <div class="px-6 py-4 border-b border-zinc-200 dark:border-zinc-700">
                    <h3 class="text-lg font-medium text-neutral-900 dark:text-white flex items-center">
                        <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                        </svg>
                        Input Data Prediksi
                    </h3>
                </div>
                
                <div class="p-6 space-y-6">
                    <div class="p-4 bg-blue-50 border border-blue-200 rounded-lg dark:bg-blue-900/20 dark:border-blue-800">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="w-5 h-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-blue-800 dark:text-blue-200">Petunjuk</h3>
                                <div class="mt-2 text-sm text-blue-700 dark:text-blue-300">
                                    <p>Masukkan data konsumsi kalori 6 bulan terakhir (dalam urutan kronologis) untuk mendapatkan prediksi bulan berikutnya.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-6 bg-white dark:bg-neutral-800 rounded-lg shadow p-4">
                        <h3 class="text-lg font-medium text-neutral-900 dark:text-white mb-4">Rentang Waktu</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1">Dari Bulan</label>
                                <input 
                                    type="month" 
                                    wire:model.live="startDate"
                                    wire:change="$set('startDate', $event.target.value)"
                                    class="w-full p-2 rounded-md border-neutral-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-neutral-700 dark:border-neutral-600 dark:placeholder-neutral-400 dark:text-white"
                                    max="{{ now()->subMonth()->format('Y-m') }}"
                                >
                                @error('startDate')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1">Sampai Bulan</label>
                                <input 
                                    type="month" 
                                    wire:model.live="endDate"
                                    wire:change="$set('endDate', $event.target.value)"
                                    class="w-full p-2 rounded-md border-neutral-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-neutral-700 dark:border-neutral-600 dark:placeholder-neutral-400 dark:text-white"
                                    max="{{ now()->subMonth()->format('Y-m') }}"
                                >
                                @error('endDate')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="flex items-end">
                                <button 
                                    type="button"
                                    wire:click="updateData"
                                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md transition-colors"
                                >
                                    Terapkan
                                </button>
                            </div>
                        </div>
                        @if(count($data) > 0)
                        <p class="mt-2 text-sm text-neutral-500 dark:text-neutral-400">
                            Menampilkan data dari {{ count($data) }} bulan
                        </p>
                        @endif
                    </div>

                    <form wire:submit="predict" class="space-y-6">
                        @foreach($data as $monthIndex => $monthData)
                        <div class="bg-white dark:bg-neutral-800 rounded-lg shadow overflow-hidden">
                            <div class="px-4 py-3 bg-neutral-50 dark:bg-neutral-700 border-b border-neutral-200 dark:border-neutral-600">
                                <h4 class="text-lg font-medium text-neutral-900 dark:text-white">{{ $monthData['month_name'] }}</h4>
                            </div>
                            
                            <div class="divide-y divide-neutral-200 dark:divide-neutral-700">
                                @foreach($monthData['komoditi_data'] as $itemIndex => $item)
                                <div class="p-4 {{ $loop->even ? 'bg-neutral-50 dark:bg-neutral-700/50' : 'bg-white dark:bg-neutral-800' }}">
                                    <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
                                        <div class="md:col-span-4">
                                            <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1">Kelompok Pangan</label>
                                            <flux:select 
                                                wire:model.live="data.{{ $monthIndex }}.komoditi_data.{{ $itemIndex }}.kelompok" 
                                                wire:loading.attr="disabled"
                                                class="w-full"
                                                id="kelompok-{{ $monthIndex }}-{{ $itemIndex }}"
                                            >
                                                <option value="">Pilih Kelompok</option>
                                                @foreach(array_keys($komoditiOptions) as $kelompok)
                                                    <option value="{{ $kelompok }}" {{ $item['kelompok'] === $kelompok ? 'selected' : '' }}>
                                                        {{ $kelompok }}
                                                    </option>
                                                @endforeach
                                            </flux:select>
                                            @error("data.{$monthIndex}.komoditi_data.{$itemIndex}.kelompok")
                                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        
                                        <div class="md:col-span-4">
                                            <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1">Komoditi</label>
                                            <div class="flex space-x-2">
                                                <flux:select 
                                                    wire:model="data.{{ $monthIndex }}.komoditi_data.{{ $itemIndex }}.komoditi" 
                                                    wire:loading.attr="disabled"
                                                    class="w-full"
                                                    id="komoditi-{{ $monthIndex }}-{{ $itemIndex }}"
                                                >
                                                    <option value="">Pilih Komoditi</option>
                                                    @if(!empty($item['kelompok']) && isset($komoditiOptions[$item['kelompok']]))
                                                        @foreach($komoditiOptions[$item['kelompok']] as $komoditi)
                                                            <option value="{{ $komoditi['value'] }}" {{ $item['komoditi'] === $komoditi['value'] ? 'selected' : '' }}>
                                                                {{ $komoditi['label'] }}
                                                            </option>
                                                        @endforeach
                                                    @endif
                                                </flux:select>
                                                
                                                @if($itemIndex > 0)
                                                <button 
                                                    type="button" 
                                                    wire:click="removeKomoditi({{ $monthIndex }}, {{ $itemIndex }})"                                                    class="px-3 py-2 text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 transition-colors"
                                                    title="Hapus komoditi"
                                                    wire:loading.attr="disabled"
                                                >
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                </button>
                                                @endif
                                            </div>
                                            @error("data.{$monthIndex}.komoditi_data.{$itemIndex}.komoditi")
                                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        
                                        <div class="md:col-span-3">
                                            <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1">Kalori per Hari</label>
                                            <div class="relative">
                                                <flux:input 
                                                    wire:model="data.{{ $monthIndex }}.komoditi_data.{{ $itemIndex }}.kalori_hari" 
                                                    type="number" 
                                                    step="0.01" 
                                                    min="0" 
                                                    max="1000"
                                                    placeholder="0.00"
                                                    id="kalori-{{ $monthIndex }}-{{ $itemIndex }}"
                                                />
                                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                                    <span class="text-neutral-500 dark:text-neutral-400 text-sm">kcal/hari</span>
                                                </div>
                                            </div>
                                            @error("data.{$monthIndex}.komoditi_data.{$itemIndex}.kalori_hari")
                                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        
                                        <div class="md:col-span-1 flex justify-end">
                                            @if($itemIndex === count($monthData['komoditi_data']) - 1)
                                            <button 
                                                type="button" 
                                                wire:click="addKomoditi({{ $monthIndex }})"                                                class="p-2 text-green-600 hover:text-green-800 dark:text-green-400 dark:hover:text-green-300 transition-colors"
                                                title="Tambah komoditi"
                                                wire:loading.attr="disabled"
                                            >
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                                </svg>
                                            </button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endforeach

                        <flux:button 
                            type="submit" 
                            variant="primary" 
                            size="base" 
                            class="w-full bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white font-semibold py-3 px-6 rounded-lg shadow-lg hover:shadow-xl transition-all duration-200 transform hover:scale-[1.02]" 
                            wire:loading.attr="disabled"
                        >
                            <svg class="w-5 h-5 inline mr-2 -translate-y-[1.5px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                            </svg>
                            Prediksi Konsumsi Bulan Depan
                        </flux:button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Results & Actions -->
        <div class="space-y-4">
            <!-- Prediction Result -->
            <div class="bg-white dark:bg-zinc-800 rounded-lg border border-zinc-200 dark:border-zinc-700">
                <div class="px-6 py-4 border-b border-zinc-200 dark:border-zinc-700">
                    <h3 class="text-lg font-medium text-neutral-900 dark:text-white flex items-center">
                        <svg class="w-5 h-5 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v4"></path>
                        </svg>
                        Hasil Prediksi
                    </h3>
                </div>
                
                <div class="p-6">
                    @if($predictionResult)
                        <div class="bg-gradient-to-r from-blue-500 to-purple-600 text-white p-6 rounded-lg mb-4">
                            <div class="text-center">
                                <div class="text-4xl font-bold mb-1">{{ number_format($predictionResult['prediction'] ?? 0, 2) }}</div>
                                <div class="text-lg opacity-90">kcal/hari</div>
                            </div>
                        </div>
                        
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">Confidence Interval</label>
                                <div class="flex justify-between text-sm text-neutral-600 dark:text-neutral-400 mb-1">
                                    <span>{{ number_format($predictionResult['confidence_interval']['lower_bound'] ?? 0, 2) }} kcal</span>
                                    <span>{{ number_format($predictionResult['confidence_interval']['upper_bound'] ?? 0, 2) }} kcal</span>
                                </div>
                                <div class="w-full bg-neutral-200 rounded-full h-2">
                                    <div class="bg-gradient-to-r from-green-400 to-blue-500 h-2 rounded-full" style="width: 85%"></div>
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-2 gap-2">
                                <div class="bg-neutral-50 dark:bg-neutral-800 rounded-lg p-3 text-center">
                                    <div class="text-xs text-neutral-500 mb-1">Model Accuracy</div>
                                    <div class="font-semibold">{{ $predictionResult['model_info']['accuracy'] ?? 'N/A' }}</div>
                                </div>
                                <div class="bg-neutral-50 dark:bg-neutral-800 rounded-lg p-3 text-center">
                                    <div class="text-xs text-neutral-500 mb-1">MAPE</div>
                                    <div class="font-semibold">{{ $predictionResult['model_info']['mape'] ?? 'N/A' }}</div>
                                </div>
                            </div>
                            
                            <div class="text-center text-sm text-neutral-500">
                                @if(isset($predictionResult['timestamp']))
                                    {{ \Carbon\Carbon::parse($predictionResult['timestamp'])->format('d M Y H:i:s') }}
                                @else
                                    {{ now()->format('d M Y H:i:s') }}
                                @endif
                            </div>
                        </div>
                    @else
                        <div class="text-center text-neutral-500 py-8">
                            <svg class="w-16 h-16 mx-auto mb-4 opacity-25" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v4"></path>
                            </svg>
                            <p>Silakan masukkan data dan klik tombol prediksi untuk melihat hasil.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white dark:bg-zinc-800 rounded-lg border border-zinc-200 dark:border-zinc-700">
                <div class="px-6 py-4 border-b border-zinc-200 dark:border-zinc-700">
                    <h3 class="text-lg font-medium text-neutral-900 dark:text-white flex items-center">
                        <svg class="w-5 h-5 mr-2 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                        Quick Actions
                    </h3>
                </div>
                
                <div class="p-6 space-y-2">
                    <flux:button wire:click="loadSampleData" variant="outline" size="sm" class="w-full justify-start border-blue-200 hover:border-blue-400 hover:bg-blue-50 dark:border-blue-700 dark:hover:bg-blue-900/20 text-blue-700 dark:text-blue-300">
                        <svg class="w-4 h-4 inline mr-2 -translate-y-[1.5px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Load Sample Data
                    </flux:button>
                    
                    <flux:button wire:click="clearData" variant="outline" size="sm" class="w-full justify-start border-red-200 hover:border-red-400 hover:bg-red-50 dark:border-red-700 dark:hover:bg-red-900/20 text-red-700 dark:text-red-300">
                        <svg class="w-4 h-4 inline mr-2 -translate-y-[1.5px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                        Clear All Data
                    </flux:button>
                    
                    @if($predictionResult)
                    <flux:button wire:click="exportResult" variant="outline" size="sm" class="w-full justify-start border-green-200 hover:border-green-400 hover:bg-green-50 dark:border-green-700 dark:hover:bg-green-900/20 text-green-700 dark:text-green-300">
                        <svg class="w-4 h-4 inline mr-2 -translate-y-[1.5px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3M3 17V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"></path>
                        </svg>
                        Export Result
                    </flux:button>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>