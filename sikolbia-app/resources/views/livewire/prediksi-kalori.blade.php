{{-- File: sikolbia-app/resources/views/livewire/prediksi-kalori.blade.php --}}

<div class="container mx-auto px-4 py-6">
    {{-- Header --}}
    <div class="bg-gradient-to-r from-blue-600 to-indigo-700 rounded-xl shadow-xl p-8 mb-6 text-white">
        <div class="flex items-center gap-4 mb-4">
            <div class="bg-white/20 p-4 rounded-lg">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
            </div>
            <div>
                <h1 class="text-3xl font-bold">Prediksi Konsumsi Kalori</h1>
                <p class="text-blue-100 mt-1">
                    Prediksi konsumsi kalori per kapita per hari berdasarkan Neraca Bahan Makanan
                </p>
            </div>
        </div>
        
        @if(!empty($modelInfo))
        <div class="bg-blue-800/40 backdrop-blur-sm rounded-lg p-4 mt-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                <div>
                    <span class="text-blue-200">Model:</span>
                    <span class="font-semibold ml-2">{{ $modelInfo['name'] ?? 'LSTM Enhanced Ensemble' }}</span>
                </div>
                <div>
                    <span class="text-blue-200">Version:</span>
                    <span class="font-semibold ml-2">{{ $modelInfo['version'] ?? '1.0' }}</span>
                </div>
                <div>
                    <span class="text-blue-200">Akurasi:</span>
                    <span class="font-semibold ml-2">{{ $modelInfo['accuracy'] ?? 'MAPE 7.46%' }}</span>
                </div>
            </div>
        </div>
        @endif
    </div>

    {{-- Error Alert --}}
    @if($errorMessage)
    <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-lg shadow-sm">
        <div class="flex items-start">
            <svg class="w-6 h-6 text-red-500 mr-3 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <div>
                <p class="font-semibold text-red-800">Terjadi Kesalahan</p>
                <p class="text-red-700 mt-1">{{ $errorMessage }}</p>
            </div>
        </div>
    </div>
    @endif

    {{-- Form Card --}}
    <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
        <div class="flex items-center gap-3 mb-6">
            <div class="bg-blue-100 p-2 rounded-lg">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
            </div>
            <h2 class="text-xl font-bold text-gray-800">Parameter Prediksi</h2>
        </div>
        
        <form wire:submit.prevent="predict">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                {{-- Bulan --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <span class="flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            Bulan Target
                        </span>
                    </label>
                    <select wire:model="target_month" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                        @foreach($months as $num => $name)
                            <option value="{{ $num }}">{{ $name }}</option>
                        @endforeach
                    </select>
                    @error('target_month') 
                    <p class="text-red-500 text-xs mt-1 flex items-center gap-1">
                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                        </svg>
                        {{ $message }}
                    </p>
                    @enderror
                </div>

                {{-- Tahun --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <span class="flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Tahun Target
                        </span>
                    </label>
                    <input type="number" 
                           wire:model="target_year" 
                           min="2025" 
                           max="2030"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                           placeholder="2025">
                    @error('target_year') 
                    <p class="text-red-500 text-xs mt-1 flex items-center gap-1">
                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                        </svg>
                        {{ $message }}
                    </p>
                    @enderror
                </div>

                {{-- Jumlah Bulan --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <span class="flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"></path>
                            </svg>
                            Jumlah Bulan
                        </span>
                    </label>
                    <input type="number" 
                           wire:model="months_ahead" 
                           min="1" 
                           max="12"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                           placeholder="1-12 bulan">
                    @error('months_ahead') 
                    <p class="text-red-500 text-xs mt-1 flex items-center gap-1">
                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                        </svg>
                        {{ $message }}
                    </p>
                    @enderror
                </div>
            </div>

            {{-- Info Box --}}
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-lg p-4 mb-6">
                <div class="flex items-start gap-3">
                    <svg class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                    </svg>
                    <div class="text-sm">
                        <p class="font-semibold text-blue-900 mb-1">Informasi Prediksi</p>
                        <p class="text-blue-800">
                            Model ini memprediksi <strong>konsumsi kalori agregat</strong> dari semua komoditi pangan dalam database. 
                            Data training: Januari 1993 - Desember 2024. Prediksi tersedia mulai Januari 2025.
                        </p>
                    </div>
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="flex flex-col sm:flex-row gap-3">
                <button type="submit" 
                        wire:loading.attr="disabled"
                        class="flex-1 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-semibold py-3 px-6 rounded-lg transition duration-200 flex items-center justify-center disabled:opacity-50 disabled:cursor-not-allowed shadow-lg hover:shadow-xl">
                    <span wire:loading.remove wire:target="predict" class="flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                        Mulai Prediksi
                    </span>
                    <span wire:loading wire:target="predict" class="flex items-center gap-2">
                        <svg class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Memproses... (max 10 detik)
                    </span>
                </button>

                <button type="button" 
                        wire:click="resetForm"
                        class="sm:w-32 px-6 py-3 border-2 border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-50 transition duration-200 flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                    Reset
                </button>
            </div>
        </form>
    </div>

    {{-- Results Card --}}
    @if($showResults && count($predictions) > 0)
    <div class="bg-white rounded-xl shadow-lg p-6 animate-fade-in">
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-3">
                <div class="bg-green-100 p-2 rounded-lg">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
                <h2 class="text-xl font-bold text-gray-800">Hasil Prediksi</h2>
            </div>
            <div class="text-right">
                <p class="text-sm text-gray-500">Waktu Komputasi</p>
                <p class="text-lg font-bold text-blue-600">{{ number_format($computationTime, 3) }}s</p>
            </div>
        </div>

        {{-- Summary Stats --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div class="bg-gradient-to-br from-green-50 to-emerald-100 p-5 rounded-xl border border-green-200 shadow-sm">
                <p class="text-sm text-green-700 font-semibold mb-1">Rata-rata</p>
                <p class="text-3xl font-bold text-green-800">
                    {{ number_format(collect($predictions)->avg('predicted_kalori'), 2) }}
                </p>
                <p class="text-xs text-green-600 mt-1">kkal/kapita/hari</p>
            </div>

            <div class="bg-gradient-to-br from-blue-50 to-cyan-100 p-5 rounded-xl border border-blue-200 shadow-sm">
                <p class="text-sm text-blue-700 font-semibold mb-1">Minimum</p>
                <p class="text-3xl font-bold text-blue-800">
                    {{ number_format(collect($predictions)->min('predicted_kalori'), 2) }}
                </p>
                <p class="text-xs text-blue-600 mt-1">kkal/kapita/hari</p>
            </div>

            <div class="bg-gradient-to-br from-purple-50 to-pink-100 p-5 rounded-xl border border-purple-200 shadow-sm">
                <p class="text-sm text-purple-700 font-semibold mb-1">Maksimum</p>
                <p class="text-3xl font-bold text-purple-800">
                    {{ number_format(collect($predictions)->max('predicted_kalori'), 2) }}
                </p>
                <p class="text-xs text-purple-600 mt-1">kkal/kapita/hari</p>
            </div>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto rounded-lg border border-gray-200">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                            Periode
                        </th>
                        <th class="px-6 py-4 text-right text-xs font-bold text-gray-600 uppercase tracking-wider">
                            Prediksi Kalori
                        </th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-gray-600 uppercase tracking-wider">
                            Status Kecukupan
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($predictions as $index => $pred)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center gap-3">
                                <div class="bg-blue-100 text-blue-700 font-bold rounded-lg px-3 py-1 text-sm">
                                    {{ $index + 1 }}
                                </div>
                                <div>
                                    <div class="text-sm font-bold text-gray-900">
                                        {{ $pred['month_name'] }} {{ $pred['year'] }}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        Bulan ke-{{ $index + 1 }} dari {{ count($predictions) }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right">
                            <div class="text-xl font-bold text-blue-600">
                                {{ number_format($pred['predicted_kalori'], 2) }}
                            </div>
                            <div class="text-xs text-gray-500">kkal/kapita/hari</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            @if($pred['predicted_kalori'] >= 2000)
                                <span class="px-4 py-2 inline-flex items-center gap-2 text-xs leading-5 font-bold rounded-full bg-green-100 text-green-800">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                    Cukup
                                </span>
                            @elseif($pred['predicted_kalori'] >= 1800)
                                <span class="px-4 py-2 inline-flex items-center gap-2 text-xs leading-5 font-bold rounded-full bg-yellow-100 text-yellow-800">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                    Kurang
                                </span>
                            @else
                                <span class="px-4 py-2 inline-flex items-center gap-2 text-xs leading-5 font-bold rounded-full bg-red-100 text-red-800">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                    </svg>
                                    Defisit
                                </span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Export Buttons --}}
        <div class="mt-6 flex flex-col sm:flex-row gap-3 justify-end">
            <button wire:click="exportExcel" class="px-5 py-2.5 bg-green-600 text-white rounded-lg hover:bg-green-700 transition duration-200 flex items-center justify-center gap-2 font-semibold shadow-md hover:shadow-lg">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Download Excel
            </button>
            <button wire:click="exportPdf" class="px-5 py-2.5 bg-red-600 text-white rounded-lg hover:bg-red-700 transition duration-200 flex items-center justify-center gap-2 font-semibold shadow-md hover:shadow-lg">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                </svg>
                Download PDF
            </button>
        </div>
    </div>
    @endif
</div>

<style>
@keyframes fade-in {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}
.animate-fade-in {
    animation: fade-in 0.4s ease-out;
}
</style>