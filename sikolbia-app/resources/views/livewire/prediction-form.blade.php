{{-- File: resources/views/livewire/prediction-form.blade.php --}}

<div class="max-w-4xl mx-auto p-6">
    {{-- Header --}}
    <div class="bg-gradient-to-r from-blue-600 to-blue-800 rounded-lg shadow-lg p-6 mb-6 text-white">
        <h1 class="text-3xl font-bold mb-2">🔮 Prediksi Konsumsi Kalori</h1>
        <p class="text-blue-100">
            Prediksi konsumsi kalori per kapita per hari berdasarkan data Neraca Bahan Makanan (NBM)
        </p>
        <div class="mt-4 bg-blue-700/50 rounded-lg p-3 text-sm">
            <p class="font-semibold">📊 Model: LSTM Enhanced Ensemble</p>
            <p class="text-blue-200">Akurasi: MAPE 7.46% | R² 0.8830 | Data Training: Jan 1993 - Des 2024</p>
        </div>
    </div>

    {{-- Error Alert --}}
    @if($errorMessage)
    <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded">
        <div class="flex items-center">
            <svg class="w-6 h-6 text-red-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <div>
                <p class="font-semibold text-red-800">Terjadi Kesalahan</p>
                <p class="text-red-600">{{ $errorMessage }}</p>
            </div>
        </div>
    </div>
    @endif

    {{-- Form Card --}}
    <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">📝 Parameter Prediksi</h2>
        
        <form wire:submit.prevent="predict" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                {{-- Bulan Target --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        📅 Bulan
                    </label>
                    <select wire:model="target_month" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @foreach($months as $num => $name)
                            <option value="{{ $num }}">{{ $name }}</option>
                        @endforeach
                    </select>
                    @error('target_month') 
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Tahun Target --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        📆 Tahun
                    </label>
                    <input type="number" 
                           wire:model="target_year" 
                           min="2025" 
                           max="2030"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="2025">
                    @error('target_year') 
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Jumlah Bulan --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        🔢 Jumlah Bulan
                    </label>
                    <input type="number" 
                           wire:model="months_ahead" 
                           min="1" 
                           max="12"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="1-12">
                    @error('months_ahead') 
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Info Box --}}
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <p class="text-sm text-blue-800">
                    <strong>💡 Tips:</strong> 
                    Pilih bulan dan tahun yang ingin Anda prediksi (mulai Januari 2025). 
                    Anda dapat memprediksi hingga 12 bulan ke depan sekaligus.
                </p>
            </div>

            {{-- Buttons --}}
            <div class="flex gap-3">
                <button type="submit" 
                        wire:loading.attr="disabled"
                        class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-lg transition duration-200 flex items-center justify-center disabled:opacity-50 disabled:cursor-not-allowed">
                    <span wire:loading.remove wire:target="predict">
                        🚀 Mulai Prediksi
                    </span>
                    <span wire:loading wire:target="predict" class="flex items-center">
                        <svg class="animate-spin h-5 w-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Memproses... (max 10 detik)
                    </span>
                </button>

                <button type="button" 
                        wire:click="resetForm"
                        class="px-6 py-3 border border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-50 transition duration-200">
                    🔄 Reset
                </button>
            </div>
        </form>
    </div>

    {{-- Results Card --}}
    @if($showResults && count($predictions) > 0)
    <div class="bg-white rounded-lg shadow-lg p-6 animate-fade-in">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-semibold text-gray-800">📊 Hasil Prediksi</h2>
            <span class="text-sm text-gray-500">
                ⏱️ Waktu komputasi: {{ number_format($computationTime, 3) }} detik
            </span>
        </div>

        {{-- Summary Stats --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div class="bg-gradient-to-br from-green-50 to-green-100 p-4 rounded-lg border border-green-200">
                <p class="text-sm text-green-600 font-semibold">Rata-rata</p>
                <p class="text-2xl font-bold text-green-800">
                    {{ number_format(collect($predictions)->avg('predicted_kalori'), 2) }}
                </p>
                <p class="text-xs text-green-600">kkal/kapita/hari</p>
            </div>

            <div class="bg-gradient-to-br from-blue-50 to-blue-100 p-4 rounded-lg border border-blue-200">
                <p class="text-sm text-blue-600 font-semibold">Minimum</p>
                <p class="text-2xl font-bold text-blue-800">
                    {{ number_format(collect($predictions)->min('predicted_kalori'), 2) }}
                </p>
                <p class="text-xs text-blue-600">kkal/kapita/hari</p>
            </div>

            <div class="bg-gradient-to-br from-purple-50 to-purple-100 p-4 rounded-lg border border-purple-200">
                <p class="text-sm text-purple-600 font-semibold">Maksimum</p>
                <p class="text-2xl font-bold text-purple-800">
                    {{ number_format(collect($predictions)->max('predicted_kalori'), 2) }}
                </p>
                <p class="text-xs text-purple-600">kkal/kapita/hari</p>
            </div>
        </div>

        {{-- Table Results --}}
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Periode
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Prediksi Kalori
                        </th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($predictions as $index => $pred)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">
                                {{ $pred['month_name'] }} {{ $pred['year'] }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right">
                            <div class="text-lg font-bold text-blue-600">
                                {{ number_format($pred['predicted_kalori'], 2) }}
                            </div>
                            <div class="text-xs text-gray-500">kkal/kapita/hari</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            @if($pred['predicted_kalori'] >= 2000)
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    ✅ Cukup
                                </span>
                            @elseif($pred['predicted_kalori'] >= 1800)
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                    ⚠️ Kurang
                                </span>
                            @else
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                    ❌ Defisit
                                </span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Download Button --}}
        <div class="mt-6 flex justify-end">
            <button class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition duration-200 flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Download PDF
            </button>
        </div>
    </div>
    @endif
</div>

<style>
@keyframes fade-in {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}
.animate-fade-in {
    animation: fade-in 0.3s ease-out;
}
</style>