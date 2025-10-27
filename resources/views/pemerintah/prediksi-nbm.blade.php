<x-layouts.pemerintah title="Prediksi NBM - Panel Pemerintah - {{ config('app.name') }}">
    <div class="container mx-auto px-4 py-8">
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-neutral-900 dark:text-white">Prediksi NBM</h1>
            <p class="text-neutral-600 dark:text-neutral-400 mt-1">Prediksi Machine Learning untuk Neraca Bahan Makanan</p>
        </div>

        <!-- Info Badge -->
        <div class="mb-6 bg-purple-50 dark:bg-purple-900/20 border border-purple-200 dark:border-purple-800 rounded-lg p-4">
            <div class="flex items-center">
                <svg class="w-5 h-5 text-purple-600 dark:text-purple-400 mr-3" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                </svg>
                <p class="text-sm text-purple-800 dark:text-purple-300">
                    <strong>Fitur Prediksi ML:</strong> Sistem akan memprediksi konsumsi pangan berdasarkan data historis 6 bulan terakhir.
                </p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Input Form -->
            <div class="lg:col-span-1">
                <div class="bg-white dark:bg-neutral-800 rounded-lg shadow p-6 h-full">
                    <h2 class="text-xl font-semibold text-neutral-900 dark:text-white mb-4">Parameter Prediksi</h2>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">Kelompok Pangan</label>
                            <select class="w-full px-4 py-2 border border-neutral-300 dark:border-neutral-600 rounded-lg bg-white dark:bg-neutral-700 text-neutral-900 dark:text-white focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                                <option value="">Pilih Kelompok</option>
                                @foreach($kelompokOptions ?? [] as $kelompok)
                                    <option value="{{ $kelompok->kode }}">{{ $kelompok->nama }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">Komoditi</label>
                            <select class="w-full px-4 py-2 border border-neutral-300 dark:border-neutral-600 rounded-lg bg-white dark:bg-neutral-700 text-neutral-900 dark:text-white focus:ring-2 focus:ring-purple-500 focus:border-purple-500" disabled>
                                <option>Pilih kelompok terlebih dahulu</option>
                            </select>
                            <p class="text-sm text-neutral-600 dark:text-neutral-400 mt-1">Komoditi akan muncul setelah memilih kelompok</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">Bulan Prediksi</label>
                            <input type="number" min="1" max="12" placeholder="1-12" class="w-full px-4 py-2 border border-neutral-300 dark:border-neutral-600 rounded-lg bg-white dark:bg-neutral-700 text-neutral-900 dark:text-white focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                            <p class="text-sm text-neutral-600 dark:text-neutral-400 mt-1">Jumlah bulan ke depan yang akan diprediksi</p>
                        </div>

                        <div class="pt-4">
                            <button class="w-full px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg font-medium transition flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"/>
                                </svg>
                                Jalankan Prediksi
                            </button>
                        </div>

                        <div class="pt-4 border-t border-neutral-200 dark:border-neutral-700">
                            <p class="text-xs text-neutral-600 dark:text-neutral-400 mb-2">
                                <strong>Cara Kerja:</strong>
                            </p>
                            <ul class="text-xs text-neutral-600 dark:text-neutral-400 space-y-1 list-disc list-inside">
                                <li>Sistem menganalisis data 6 bulan terakhir</li>
                                <li>Model ML menghitung tren konsumsi</li>
                                <li>Prediksi ditampilkan dengan confidence interval</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Results -->
            <div class="lg:col-span-2">
                <div class="bg-white dark:bg-neutral-800 rounded-lg shadow p-6 h-full">
                    <h2 class="text-xl font-semibold text-neutral-900 dark:text-white mb-4">Hasil Prediksi</h2>
                    
                    <div class="flex items-center justify-center h-64">
                        <div class="text-center text-neutral-500 dark:text-neutral-400">
                            <svg class="w-16 h-16 mx-auto mb-4 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                            <p class="font-medium text-lg">Belum Ada Hasil Prediksi</p>
                            <p class="text-sm mt-2">Pilih parameter dan klik "Jalankan Prediksi"</p>
                        </div>
                    </div>
                </div>

                <!-- Model Information -->
                <div class="bg-white dark:bg-neutral-800 rounded-lg shadow p-6 mt-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="text-center p-4 bg-neutral-50 dark:bg-neutral-900 rounded-lg">
                            <p class="text-sm text-neutral-600 dark:text-neutral-400">Model</p>
                            <p class="text-lg font-bold text-neutral-900 dark:text-white">LSTM</p>
                        </div>
                        <div class="text-center p-4 bg-neutral-50 dark:bg-neutral-900 rounded-lg">
                            <p class="text-sm text-neutral-600 dark:text-neutral-400">Accuracy</p>
                            <p class="text-lg font-bold text-green-600 dark:text-green-400">-</p>
                        </div>
                        <div class="text-center p-4 bg-neutral-50 dark:bg-neutral-900 rounded-lg">
                            <p class="text-sm text-neutral-600 dark:text-neutral-400">Last Training</p>
                            <p class="text-lg font-bold text-neutral-900 dark:text-white">-</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Historical Data Reference -->
        <div class="bg-white dark:bg-neutral-800 rounded-lg shadow p-6 mt-6">
            <h2 class="text-xl font-semibold text-neutral-900 dark:text-white mb-4">Data Historis (6 Bulan Terakhir)</h2>
            
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-neutral-700 dark:text-neutral-300">
                    <thead class="text-xs uppercase bg-neutral-50 dark:bg-neutral-900 text-neutral-700 dark:text-neutral-300">
                        <tr>
                            <th class="px-6 py-3">Tahun</th>
                            <th class="px-6 py-3">Bulan</th>
                            <th class="px-6 py-3">Komoditi</th>
                            <th class="px-6 py-3">Kalori/Hari</th>
                            <th class="px-6 py-3">Tren</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="border-b border-neutral-200 dark:border-neutral-700">
                            <td colspan="5" class="px-6 py-8 text-center text-neutral-500 dark:text-neutral-400">
                                <p>Pilih kelompok dan komoditi untuk melihat data historis</p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-layouts.pemerintah>
