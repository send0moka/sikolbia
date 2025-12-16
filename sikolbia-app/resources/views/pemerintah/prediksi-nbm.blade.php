<x-layouts.pemerintah title="Prediksi NBM - Panel Pemerintah - {{ config('app.name') }}">
    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    
    @vite(['resources/js/prediksi-nbm-enhanced.js'])
    
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
                    <strong>Fitur Prediksi ML:</strong> Sistem menggunakan model LSTM yang dilatih dengan data historis lengkap (1993-2024). Untuk prediksi, model menganalisis pola konsumsi dari 6 bulan terakhir untuk menghasilkan proyeksi yang akurat.
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
                            <select id="kelompokSelect" class="w-full px-4 py-2 border border-neutral-300 dark:border-neutral-600 rounded-lg bg-white dark:bg-neutral-700 text-neutral-900 dark:text-white focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                                <option value="">Pilih Kelompok</option>
                                @foreach($kelompokOptions ?? [] as $kelompok)
                                    <option value="{{ $kelompok->kode }}">{{ $kelompok->nama }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">Komoditi</label>
                            <select id="komoditiSelect" class="w-full px-4 py-2 border border-neutral-300 dark:border-neutral-600 rounded-lg bg-white dark:bg-neutral-700 text-neutral-900 dark:text-white focus:ring-2 focus:ring-purple-500 focus:border-purple-500" disabled>
                                <option>Pilih kelompok terlebih dahulu</option>
                            </select>
                            <p class="text-sm text-neutral-600 dark:text-neutral-400 mt-1">Komoditi akan muncul setelah memilih kelompok</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">Bulan Prediksi</label>
                            <input id="bulanPrediksi" type="number" min="1" max="12" value="3" placeholder="1-12" class="w-full px-4 py-2 border border-neutral-300 dark:border-neutral-600 rounded-lg bg-white dark:bg-neutral-700 text-neutral-900 dark:text-white focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                            <p class="text-sm text-neutral-600 dark:text-neutral-400 mt-1">Jumlah bulan ke depan yang akan diprediksi</p>
                        </div>

                        <div class="pt-4">
                            <button id="prediksiBtn" class="w-full px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg font-medium transition flex items-center justify-center gap-2">
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
                                <li>Model dilatih dengan data historis lengkap (1993-2024)</li>
                                <li>Sistem menganalisis pola 6 bulan terakhir sebagai input</li>
                                <li>LSTM menghitung tren dan seasonality konsumsi</li>
                                <li>Prediksi ditampilkan dengan confidence interval</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Results -->
            <div class="lg:col-span-2">
                <div class="bg-white dark:bg-neutral-800 rounded-lg shadow p-6 h-full">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-xl font-semibold text-neutral-900 dark:text-white">Hasil Prediksi</h2>
                        
                        <!-- Action Buttons (hidden initially) -->
                        <div id="exportButtons" style="display: none;" class="flex gap-2">
                            <button id="saveBtn" class="px-3 py-1.5 text-sm bg-purple-600 hover:bg-purple-700 text-white rounded-lg transition flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/>
                                </svg>
                                Simpan
                            </button>
                            <a href="{{ route('pemerintah.prediksi-nbm.history') }}" class="px-3 py-1.5 text-sm bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg transition flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Riwayat
                            </a>
                            <button id="exportExcelBtn" class="px-3 py-1.5 text-sm bg-green-600 hover:bg-green-700 text-white rounded-lg transition flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                Excel
                            </button>
                            <button id="exportPdfBtn" class="px-3 py-1.5 text-sm bg-red-600 hover:bg-red-700 text-white rounded-lg transition flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                </svg>
                                PDF
                            </button>
                        </div>
                    </div>
                    
                    <div class="flex items-center justify-center h-64" id="emptyState">
                        <div class="text-center text-neutral-500 dark:text-neutral-400">
                            <svg class="w-16 h-16 mx-auto mb-4 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                            <p class="font-medium text-lg">Belum Ada Hasil Prediksi</p>
                            <p class="text-sm mt-2">Pilih parameter dan klik "Jalankan Prediksi"</p>
                        </div>
                    </div>
                    
                    <!-- Results Content (will be populated by JS) -->
                    <div id="resultsContent" style="display: none;"></div>
                </div>
            </div>
        </div>

        <!-- Model Information -->
        <div class="bg-white dark:bg-neutral-800 rounded-lg shadow p-6 mt-6">
            <h2 class="text-xl font-semibold text-neutral-900 dark:text-white mb-4">Model Information</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="text-center p-4 bg-neutral-50 dark:bg-neutral-900 rounded-lg">
                    <p class="text-sm text-neutral-600 dark:text-neutral-400">Model</p>
                    <p id="model-type" class="text-lg font-bold text-neutral-900 dark:text-white">LSTM Enhanced Ensemble</p>
                </div>
                <div class="text-center p-4 bg-neutral-50 dark:bg-neutral-900 rounded-lg">
                    <p class="text-sm text-neutral-600 dark:text-neutral-400">Status</p>
                    <p id="model-status" class="text-lg font-bold text-green-600 dark:text-green-400">Active</p>
                </div>
                <div class="text-center p-4 bg-neutral-50 dark:bg-neutral-900 rounded-lg">
                    <p class="text-sm text-neutral-600 dark:text-neutral-400">Version</p>
                    <p id="model-version" class="text-lg font-bold text-neutral-900 dark:text-white">-</p>
                </div>
            </div>
        </div>

        <!-- Chart Visualization Section -->
        <div id="chartSection" class="bg-white dark:bg-neutral-800 rounded-lg shadow p-6 mt-6" style="display: none;">
            <h2 class="text-xl font-semibold text-neutral-900 dark:text-white mb-4 flex items-center gap-2">
                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"/>
                </svg>
                Visualisasi Prediksi
            </h2>
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Trend Chart -->
                <div>
                    <h3 class="text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-3">
                        Trend Historis vs Prediksi
                        <span class="ml-2 text-xs text-neutral-500 dark:text-neutral-400">(Continuous Line)</span>
                    </h3>
                    <div class="bg-neutral-50 dark:bg-neutral-900 rounded-lg p-4" style="min-height: 300px;">
                        <canvas id="trendChart" style="max-height: 300px;"></canvas>
                    </div>
                    <p class="text-xs text-neutral-500 dark:text-neutral-400 mt-2 text-center">
                        <span class="inline-block w-3 h-0.5 bg-blue-500 mr-1"></span> Historis (solid) 
                        <span class="mx-2">→</span>
                        <span class="inline-block w-3 h-0.5 bg-red-500 border-dashed border-t border-red-500 mr-1"></span> Prediksi (dashed)
                    </p>
                </div>
                
                <!-- Confidence Interval Chart -->
                <div>
                    <h3 class="text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-3">Confidence Interval</h3>
                    <div class="bg-neutral-50 dark:bg-neutral-900 rounded-lg p-4" style="min-height: 300px;">
                        <canvas id="confidenceChart" style="max-height: 300px;"></canvas>
                    </div>
                </div>
            </div>
            
            <!-- Bar Comparison Chart (Full Width) -->
            <div class="mt-6">
                <h3 class="text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-3">Perbandingan Per Periode</h3>
                <div class="bg-neutral-50 dark:bg-neutral-900 rounded-lg p-4" style="min-height: 300px;">
                    <canvas id="comparisonChart" style="max-height: 300px;"></canvas>
                </div>
            </div>
        </div>

        <!-- AI Insights Section -->
        <div id="insightsSection" class="bg-white dark:bg-neutral-800 rounded-lg shadow p-6 mt-6" style="display: none;">
            <h2 class="text-xl font-semibold text-neutral-900 dark:text-white mb-4 flex items-center gap-2">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                </svg>
                AI Insights & Recommendations
                <span class="ml-auto text-xs bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 px-2 py-1 rounded">Beta</span>
            </h2>
            
            <div id="insightsContent" class="space-y-4">
                <!-- Insights will be dynamically loaded here -->
                <div class="text-center py-8">
                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600 mx-auto"></div>
                    <p class="text-sm text-neutral-500 mt-2">Menghasilkan insights...</p>
                </div>
            </div>
        </div>

        <!-- Historical Data Reference -->
        <div id="historical-data-section" class="bg-white dark:bg-neutral-800 rounded-lg shadow p-6 mt-6">
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
                    <tbody id="historical-tbody">
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const kelompokSelect = document.getElementById('kelompokSelect');
            const komoditiSelect = document.getElementById('komoditiSelect');
            const bulanPrediksi = document.getElementById('bulanPrediksi');
            const prediksiBtn = document.getElementById('prediksiBtn');

            // Load komoditi when kelompok changes
            kelompokSelect.addEventListener('change', function() {
                const kelompokKode = this.value;
                
                console.log('Kelompok selected:', kelompokKode);
                
                // Reset komoditi
                komoditiSelect.innerHTML = '<option value="">Loading...</option>';
                komoditiSelect.disabled = true;

                if (!kelompokKode) {
                    komoditiSelect.innerHTML = '<option>Pilih kelompok terlebih dahulu</option>';
                    return;
                }

                // Fetch komoditi
                const url = `{{ route('pemerintah.api.komoditi') }}?kelompok_id=${kelompokKode}`;
                console.log('Fetching komoditi from:', url);
                
                fetch(url)
                    .then(response => {
                        console.log('Response status:', response.status);
                        console.log('Response ok:', response.ok);
                        return response.json();
                    })
                    .then(data => {
                        console.log('Komoditi data received:', data);
                        komoditiSelect.innerHTML = '<option value="">Pilih Komoditi</option>';
                        
                        if (data.komoditi && data.komoditi.length > 0) {
                            console.log('Found', data.komoditi.length, 'komoditi items');
                            data.komoditi.forEach(item => {
                                const option = document.createElement('option');
                                option.value = item.kode;
                                option.textContent = item.deskripsi;
                                komoditiSelect.appendChild(option);
                            });
                            komoditiSelect.disabled = false;
                            console.log('Komoditi select enabled with', data.komoditi.length, 'options');
                        } else {
                            console.warn('No komoditi found in response');
                            komoditiSelect.innerHTML = '<option>Tidak ada komoditi</option>';
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching komoditi:', error);
                        komoditiSelect.innerHTML = '<option>Error memuat komoditi</option>';
                    });
            });

            // Handle prediksi button
            prediksiBtn.addEventListener('click', function() {
                const kelompok = kelompokSelect.value;
                const komoditi = komoditiSelect.value;
                const bulan = bulanPrediksi.value;

                if (!kelompok || !komoditi) {
                    alert('Silakan pilih kelompok dan komoditi terlebih dahulu');
                    return;
                }

                if (!bulan || bulan < 1 || bulan > 12) {
                    alert('Bulan prediksi harus antara 1-12');
                    return;
                }

                // Show loading
                const originalText = prediksiBtn.innerHTML;
                prediksiBtn.innerHTML = '<svg class="animate-spin h-5 w-5 mr-2 inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Memproses...';
                prediksiBtn.disabled = true;

                // Call prediction API
                fetch('{{ route("pemerintah.prediksi-nbm.run") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        kelompok: kelompok,
                        komoditi: komoditi,
                        bulan: parseInt(bulan)
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        displayResults(data.data);
                    } else {
                        alert('Error: ' + (data.message || 'Terjadi kesalahan saat prediksi'));
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan: ' + error.message);
                })
                .finally(() => {
                    prediksiBtn.innerHTML = originalText;
                    prediksiBtn.disabled = false;
                });
            });

            function displayResults(data) {
                // Store data globally for export
                window.currentPredictionData = data;
                
                // Show export buttons
                const exportButtons = document.getElementById('exportButtons');
                if (exportButtons) {
                    exportButtons.style.display = 'flex';
                }
                
                // Hide empty state, show results
                const emptyState = document.getElementById('emptyState');
                const resultsContent = document.getElementById('resultsContent');
                if (emptyState) emptyState.style.display = 'none';
                if (resultsContent) resultsContent.style.display = 'block';
                
                // Update hasil prediksi section
                let html = '<div class="mb-4">';
                html += `<p class="text-sm text-neutral-600 dark:text-neutral-400">Komoditi: <strong>${data.komoditi_name}</strong> (${data.kelompok_name})</p>`;
                html += `<p class="text-sm text-neutral-600 dark:text-neutral-400 mt-1">Prediksi untuk: <strong>${data.bulan_prediksi || 1} bulan ke depan</strong></p>`;
                html += `</div>`;
                
                // Check if data is empty (warning from ML API)
                if (data.warning || (data.has_data === false)) {
                    html += `<div class="bg-yellow-50 dark:bg-yellow-900/20 border-l-4 border-yellow-400 p-4 mb-4">`;
                    html += `<div class="flex">`;
                    html += `<div class="flex-shrink-0">`;
                    html += `<svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">`;
                    html += `<path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />`;
                    html += `</svg>`;
                    html += `</div>`;
                    html += `<div class="ml-3">`;
                    html += `<p class="text-sm text-yellow-700 dark:text-yellow-200">`;
                    html += `<strong>Perhatian:</strong> ${data.warning || 'Data historis untuk komoditi ini kosong atau tidak tersedia. Tidak dapat melakukan prediksi yang akurat.'}`;
                    html += `</p>`;
                    html += `</div>`;
                    html += `</div>`;
                    html += `</div>`;
                }
                
                if (data.prediction && data.prediction.length > 0) {
                    // Slice predictions based on bulan_prediksi parameter
                    const numPredictions = data.bulan_prediksi || data.prediction.length;
                    const predictions = data.prediction.slice(0, numPredictions);
                    
                    // Calculate future periods
                    const lastHistorical = data.historical && data.historical.length > 0 ? data.historical[0] : null;
                    
                    // Use confidence_intervals array (one CI per prediction) or fallback to single CI
                    const ciArray = data.confidence_intervals || [];
                    const hasCIArray = ciArray.length > 0;
                    const ci = data.confidence_interval;
                    
                    html += '<div class="overflow-x-auto"><table class="w-full text-sm"><thead class="text-white bg-neutral-50 dark:bg-neutral-900"><tr>';
                    html += '<th class="px-4 py-2 text-left">Periode</th>';
                    html += '<th class="px-4 py-2 text-right">Prediksi Kalori/Hari</th>';
                    if (hasCIArray || (ci && ci.lower_bound !== undefined)) {
                        html += '<th class="px-4 py-2 text-right">Batas Bawah</th>';
                        html += '<th class="px-4 py-2 text-right">Batas Atas</th>';
                        html += '<th class="px-4 py-2 text-right">Margin (%)</th>';
                    }
                    html += '</tr></thead><tbody>';
                    
                    predictions.forEach((pred, idx) => {
                        // Calculate future period (month/year)
                        let periodLabel = `Bulan +${idx + 1}`;
                        if (lastHistorical) {
                            const futureMonth = (parseInt(lastHistorical.bulan) + idx + 1);
                            const futureYear = parseInt(lastHistorical.tahun) + Math.floor((futureMonth - 1) / 12);
                            const month = ((futureMonth - 1) % 12) + 1;
                            periodLabel = `${futureYear}-${String(month).padStart(2, '0')}`;
                        }
                        
                        html += '<tr class="border-b border-neutral-200 dark:border-neutral-700">';
                        html += `<td class="px-4 py-2 text-white/80">${periodLabel}</td>`;
                        html += `<td class="px-4 py-2 text-right font-semibold text-blue-600">${pred.toFixed(2)}</td>`;
                        
                        // Use per-prediction CI if available, otherwise calculate it
                        if (hasCIArray && ciArray[idx]) {
                            const predCI = ciArray[idx];
                            html += `<td class="px-4 py-2 text-right text-neutral-300">${predCI.lower_bound.toFixed(2)}</td>`;
                            html += `<td class="px-4 py-2 text-right text-neutral-300">${predCI.upper_bound.toFixed(2)}</td>`;
                            html += `<td class="px-4 py-2 text-right text-orange-600">±${predCI.margin_percent.toFixed(1)}%</td>`;
                        } else {
                            // Calculate CI for this prediction (15% margin)
                            const margin = pred * 0.15;
                            const lowerBound = pred - margin;
                            const upperBound = pred + margin;
                            html += `<td class="px-4 py-2 text-right text-neutral-300">${lowerBound.toFixed(2)}</td>`;
                            html += `<td class="px-4 py-2 text-right text-neutral-300">${upperBound.toFixed(2)}</td>`;
                            html += `<td class="px-4 py-2 text-right text-orange-600">±15.0%</td>`;
                        }
                        html += '</tr>';
                    });
                    
                    html += '</tbody></table></div>';
                    
                    // Add uncertainty metrics if available
                    if (data.uncertainty_metrics) {
                        html += '<div class="mt-4 p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg">';
                        html += '<p class="text-xs text-blue-800 dark:text-blue-200">';
                        html += `<strong>Metrik Ketidakpastian:</strong> Interval: ${data.uncertainty_metrics.interval_width.toFixed(2)}, `;
                        html += `Relatif: ${data.uncertainty_metrics.relative_uncertainty.toFixed(2)}%, `;
                        html += `Confidence: ${(data.uncertainty_metrics.confidence_level * 100).toFixed(0)}%`;
                        html += '</p></div>';
                    }
                } else {
                    html += '<p class="text-neutral-500">Tidak ada hasil prediksi</p>';
                }
                
                resultsContent.innerHTML = html;
                
                // Update model info using specific IDs
                if (data.model_info) {
                    const modelInfo = data.model_info;
                    const versionElement = document.getElementById('model-version');
                    
                    if (versionElement && modelInfo.model_version) {
                        versionElement.textContent = modelInfo.model_version;
                    }
                }
                
                // Render charts
                if (typeof window.renderPredictionCharts === 'function') {
                    window.renderPredictionCharts(data);
                }
                
                // Update historical data table
                const tbody = document.getElementById('historical-tbody');
                
                if (tbody) {
                    if (data.historical && data.historical.length > 0) {
                        tbody.innerHTML = '';
                        
                        data.historical.forEach((item, index) => {
                            const row = document.createElement('tr');
                            row.className = 'border-b border-neutral-200 dark:border-neutral-700';
                            
                            // Calculate trend (compare with previous month)
                            let trendHtml = '-';
                            if (index === 0) {
                                trendHtml = '<span class="text-blue-600 font-semibold">📍 Terkini</span>';
                            } else if (index < data.historical.length) {
                                const currentValue = item.kalori_hari;
                                const previousValue = data.historical[index - 1].kalori_hari;
                                const diff = currentValue - previousValue;
                                const percentChange = previousValue !== 0 ? ((diff / previousValue) * 100) : 0;
                                
                                if (Math.abs(percentChange) < 1) {
                                    // Stable (< 1% change)
                                    trendHtml = '<span class="text-gray-400">→ Stabil</span>';
                                } else if (diff > 0) {
                                    // Increase
                                    trendHtml = `<span class="text-green-600">↗ +${percentChange.toFixed(1)}%</span>`;
                                } else {
                                    // Decrease
                                    trendHtml = `<span class="text-red-600">↘ ${percentChange.toFixed(1)}%</span>`;
                                }
                            }
                            
                            row.innerHTML = `
                                <td class="px-6 py-3">${item.tahun}</td>
                                <td class="px-6 py-3">${item.bulan}</td>
                                <td class="px-6 py-3">${data.komoditi_name}</td>
                                <td class="px-6 py-3 font-semibold">${item.kalori_hari.toFixed(2)}</td>
                                <td class="px-6 py-3">${trendHtml}</td>
                            `;
                            tbody.appendChild(row);
                        });
                    } else {
                        // No historical data available
                        tbody.innerHTML = `
                            <tr class="border-b border-neutral-200 dark:border-neutral-700">
                                <td colspan="5" class="px-6 py-8 text-center text-neutral-500 dark:text-neutral-400">
                                    <p>Tidak ada data historis tersedia untuk komoditi ini</p>
                                </td>
                            </tr>
                        `;
                    }
                }
                
                // Fetch and display AI insights
                fetchInsights(data);
            }
            
            // Fetch AI insights
            async function fetchInsights(data) {
                const insightsSection = document.getElementById('insightsSection');
                const insightsContent = document.getElementById('insightsContent');
                
                if (!insightsSection || !insightsContent) return;
                
                // Show insights section
                insightsSection.style.display = 'block';
                
                try {
                    const response = await fetch('{{ route("pemerintah.prediksi-nbm.insights") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({
                            prediction_data: data.prediction,
                            historical_data: data.historical
                        })
                    });
                    
                    const result = await response.json();
                    
                    if (result.success) {
                        displayInsights(result.data);
                    } else {
                        insightsContent.innerHTML = `
                            <div class="bg-red-50 dark:bg-red-900/20 border-l-4 border-red-400 p-4">
                                <p class="text-sm text-red-700 dark:text-red-200">Gagal menghasilkan insights: ${result.message}</p>
                            </div>
                        `;
                    }
                } catch (error) {
                    console.error('Insights error:', error);
                    insightsContent.innerHTML = `
                        <div class="bg-red-50 dark:bg-red-900/20 border-l-4 border-red-400 p-4">
                            <p class="text-sm text-red-700 dark:text-red-200">Terjadi kesalahan saat menghasilkan insights</p>
                        </div>
                    `;
                }
            }
            
            // Display insights in UI
            function displayInsights(insights) {
                const insightsContent = document.getElementById('insightsContent');
                
                let html = '<div class="grid grid-cols-1 md:grid-cols-2 gap-4">';
                
                // Trend Analysis Card - Fixed color classes based on direction
                const trend = insights.trend_analysis;
                let trendCardClass, trendTextClass, trendSubtextClass;
                
                if (trend.direction.includes('increasing')) {
                    trendCardClass = 'bg-green-50 dark:bg-green-900/20 border-l-4 border-green-400';
                    trendTextClass = 'text-green-900 dark:text-green-100';
                    trendSubtextClass = 'text-sm text-green-800 dark:text-green-200';
                } else if (trend.direction.includes('decreasing')) {
                    trendCardClass = 'bg-red-50 dark:bg-red-900/20 border-l-4 border-red-400';
                    trendTextClass = 'text-red-900 dark:text-red-100';
                    trendSubtextClass = 'text-sm text-red-800 dark:text-red-200';
                } else {
                    trendCardClass = 'bg-gray-50 dark:bg-gray-900/20 border-l-4 border-gray-400';
                    trendTextClass = 'text-gray-900 dark:text-gray-100';
                    trendSubtextClass = 'text-sm text-gray-800 dark:text-gray-200';
                }
                
                html += `
                    <div class="${trendCardClass} p-4 rounded">
                        <div class="flex items-start gap-3">
                            <div class="text-3xl">${trend.icon}</div>
                            <div class="flex-1">
                                <h3 class="font-semibold ${trendTextClass} mb-1">Analisis Trend</h3>
                                <p class="${trendSubtextClass}">${trend.description}</p>
                                <p class="text-xs ${trendTextClass} mt-2">Perubahan: ${trend.percentage_change > 0 ? '+' : ''}${trend.percentage_change.toFixed(1)}%</p>
                            </div>
                        </div>
                    </div>
                `;
                
                // Risk Level Card - Fixed color classes based on risk color
                const risk = insights.risk_level;
                let riskCardClass, riskTextClass, riskSubtextClass;
                
                if (risk.color === 'green') {
                    riskCardClass = 'bg-green-50 dark:bg-green-900/20 border-l-4 border-green-400';
                    riskTextClass = 'text-green-900 dark:text-green-100';
                    riskSubtextClass = 'text-sm text-green-800 dark:text-green-200';
                } else if (risk.color === 'yellow') {
                    riskCardClass = 'bg-yellow-50 dark:bg-yellow-900/20 border-l-4 border-yellow-400';
                    riskTextClass = 'text-yellow-900 dark:text-yellow-100';
                    riskSubtextClass = 'text-sm text-yellow-800 dark:text-yellow-200';
                } else if (risk.color === 'red') {
                    riskCardClass = 'bg-red-50 dark:bg-red-900/20 border-l-4 border-red-400';
                    riskTextClass = 'text-red-900 dark:text-red-100';
                    riskSubtextClass = 'text-sm text-red-800 dark:text-red-200';
                } else {
                    riskCardClass = 'bg-gray-50 dark:bg-gray-900/20 border-l-4 border-gray-400';
                    riskTextClass = 'text-gray-900 dark:text-gray-100';
                    riskSubtextClass = 'text-sm text-gray-800 dark:text-gray-200';
                }
                
                html += `
                    <div class="${riskCardClass} p-4 rounded">
                        <div class="flex items-start gap-3">
                            <div class="text-3xl">⚠️</div>
                            <div class="flex-1">
                                <h3 class="font-semibold ${riskTextClass} mb-1">Tingkat Risiko</h3>
                                <p class="${riskSubtextClass}">${risk.description}</p>
                                <p class="text-xs ${riskTextClass} mt-2">Level: ${risk.level.toUpperCase()} (Score: ${risk.score})</p>
                            </div>
                        </div>
                    </div>
                `;
                
                html += '</div>';
                
                // Volatility & Comparison
                html += '<div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">';
                
                const volatility = insights.volatility;
                html += `
                    <div class="bg-neutral-50 dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-700 p-4 rounded">
                        <h3 class="font-semibold text-neutral-900 dark:text-white mb-2">Volatilitas</h3>
                        <p class="text-sm text-neutral-700 dark:text-neutral-300">${volatility.description}</p>
                        <p class="text-xs text-neutral-500 mt-2">Coefficient of Variation: ${volatility.coefficient}%</p>
                    </div>
                `;
                
                const comparison = insights.comparison_with_historical;
                html += `
                    <div class="bg-neutral-50 dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-700 p-4 rounded">
                        <h3 class="font-semibold text-neutral-900 dark:text-white mb-2">Perbandingan Historis</h3>
                        <p class="text-sm text-neutral-700 dark:text-neutral-300">${comparison.description}</p>
                        ${comparison.difference_percentage ? `<p class="text-xs text-neutral-500 mt-2">Selisih: ${comparison.difference_percentage > 0 ? '+' : ''}${comparison.difference_percentage.toFixed(1)}%</p>` : ''}
                    </div>
                `;
                
                html += '</div>';
                
                // Anomaly Detection
                const anomaly = insights.anomaly_detection;
                if (anomaly.has_anomalies) {
                    html += `
                        <div class="bg-yellow-50 dark:bg-yellow-900/20 border-l-4 border-yellow-400 p-4 rounded mt-4">
                            <h3 class="font-semibold text-yellow-900 dark:text-yellow-100 mb-2 flex items-center gap-2">
                                <span>⚠️</span> Anomali Terdeteksi
                            </h3>
                            <p class="text-sm text-yellow-800 dark:text-yellow-200 mb-3">${anomaly.description}</p>
                            <ul class="text-xs text-yellow-700 dark:text-yellow-300 space-y-1">
                                ${anomaly.items.map(item => `<li>• ${item.description}</li>`).join('')}
                            </ul>
                        </div>
                    `;
                }
                
                // Recommendations
                html += `
                    <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-700 p-4 rounded mt-4">
                        <h3 class="font-semibold text-blue-900 dark:text-blue-100 mb-3">💡 Rekomendasi</h3>
                        <div class="space-y-2">
                            ${insights.recommendations.map(rec => `
                                <div class="flex items-start gap-2 text-sm">
                                    <span class="text-lg">${rec.icon}</span>
                                    <div class="flex-1">
                                        <span class="px-2 py-0.5 text-xs font-medium rounded ${rec.priority === 'high' ? 'bg-red-100 text-red-800' : rec.priority === 'medium' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800'}">${rec.priority.toUpperCase()}</span>
                                        <p class="text-neutral-700 dark:text-neutral-300 mt-1">${rec.text}</p>
                                    </div>
                                </div>
                            `).join('')}
                        </div>
                    </div>
                `;
                
                // Summary
                html += `
                    <div class="bg-neutral-50 dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-700 p-4 rounded mt-4">
                        <h3 class="font-semibold text-neutral-900 dark:text-white mb-2">Ringkasan</h3>
                        <p class="text-sm text-neutral-700 dark:text-neutral-300">${insights.summary}</p>
                    </div>
                `;
                
                insightsContent.innerHTML = html;
            }
            
            // Save prediction handler
            document.getElementById('saveBtn')?.addEventListener('click', async function() {
                const data = window.currentPredictionData;
                if (!data) {
                    alert('Tidak ada data prediksi untuk disimpan');
                    return;
                }
                
                const btn = this;
                const originalText = btn.innerHTML;
                btn.disabled = true;
                btn.innerHTML = `
                    <svg class="w-4 h-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    Menyimpan...
                `;
                
                try {
                    const response = await fetch('{{ route("pemerintah.prediksi-nbm.save") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({
                            kode_kelompok: kelompokSelect.value,
                            kode_komoditi: komoditiSelect.value,
                            kelompok_name: data.kelompok_name,
                            komoditi_name: data.komoditi_name,
                            bulan_prediksi: data.bulan_prediksi,
                            prediction_data: data.prediction,
                            historical_data: data.historical,
                            confidence_intervals: data.confidence_intervals,
                            model_version: data.model_info?.model_version
                        })
                    });
                    
                    const result = await response.json();
                    
                    if (result.success) {
                        btn.innerHTML = `
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Tersimpan
                        `;
                        btn.classList.remove('bg-purple-600', 'hover:bg-purple-700');
                        btn.classList.add('bg-green-600');
                        setTimeout(() => {
                            btn.innerHTML = originalText;
                            btn.classList.remove('bg-green-600');
                            btn.classList.add('bg-purple-600', 'hover:bg-purple-700');
                            btn.disabled = false;
                        }, 2000);
                    } else {
                        alert('Gagal menyimpan: ' + result.message);
                        btn.innerHTML = originalText;
                        btn.disabled = false;
                    }
                } catch (error) {
                    console.error('Save error:', error);
                    alert('Terjadi kesalahan saat menyimpan');
                    btn.innerHTML = originalText;
                    btn.disabled = false;
                }
            });
            
            // Initialize export handlers
            if (typeof window.setupExportHandlers === 'function') {
                window.setupExportHandlers(
                    '{{ route("pemerintah.prediksi-nbm.export-excel") }}',
                    '{{ route("pemerintah.prediksi-nbm.export-pdf") }}'
                );
            }
        });
    </script>
</x-layouts.pemerintah>
