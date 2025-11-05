<x-layouts.pemerintah title="Laporan NBM - Panel Pemerintah - {{ config('app.name') }}">
    <div class="container mx-auto px-4 py-8">
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-neutral-900 dark:text-white">Laporan NBM</h1>
            <p class="text-neutral-600 dark:text-neutral-400 mt-1">Data Neraca Bahan Makanan</p>
        </div>

        <!-- Info Badge -->
        <div class="mb-6 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
            <div class="flex items-center">
                <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 mr-3" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                </svg>
                <p class="text-sm text-blue-800 dark:text-blue-300">
                    <strong>Mode Read-Only:</strong> Anda dapat melihat dan mengekspor data, namun tidak dapat melakukan perubahan.
                </p>
            </div>
        </div>

        <!-- Filter & Export Card -->
        <div class="bg-white dark:bg-neutral-800 rounded-lg shadow p-6 mb-6">
            <div class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">Kelompok Pangan</label>
                        <select id="kelompokSelect" class="w-full px-4 py-2 border border-neutral-300 dark:border-neutral-600 rounded-lg bg-white dark:bg-neutral-700 text-neutral-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Pilih Kelompok</option>
                            @foreach($kelompokOptions as $kelompok)
                                <option value="{{ $kelompok->kode }}">{{ $kelompok->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">Tahun</label>
                        <select id="tahunSelect" class="w-full px-4 py-2 border border-neutral-300 dark:border-neutral-600 rounded-lg bg-white dark:bg-neutral-700 text-neutral-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Pilih Tahun</option>
                            @for($year = date('Y'); $year >= 2020; $year--)
                                <option value="{{ $year }}">{{ $year }}</option>
                            @endfor
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">Bulan</label>
                        <select id="bulanSelect" class="w-full px-4 py-2 border border-neutral-300 dark:border-neutral-600 rounded-lg bg-white dark:bg-neutral-700 text-neutral-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Semua Bulan</option>
                            @foreach(range(1, 12) as $month)
                                <option value="{{ $month }}">{{ \Carbon\Carbon::create()->month($month)->translatedFormat('F') }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="flex justify-between items-center pt-4 border-t border-neutral-200 dark:border-neutral-700">
                    <div class="flex gap-2">
                        <button id="filterBtn" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            Tampilkan Data
                        </button>
                        <button id="resetBtn" class="px-4 py-2 bg-neutral-200 hover:bg-neutral-300 dark:bg-neutral-700 dark:hover:bg-neutral-600 text-neutral-700 dark:text-neutral-300 rounded-lg font-medium transition flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                            Reset Filter
                        </button>
                    </div>
                    <div class="flex gap-2">
                        <a href="#" id="exportExcelBtn" class="px-4 py-2 border border-neutral-300 dark:border-neutral-600 hover:bg-neutral-50 dark:hover:bg-neutral-700 text-neutral-700 dark:text-neutral-300 rounded-lg font-medium transition flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            Export Excel
                        </a>
                        <a href="#" id="exportPdfBtn" class="px-4 py-2 border border-neutral-300 dark:border-neutral-600 hover:bg-neutral-50 dark:hover:bg-neutral-700 text-neutral-700 dark:text-neutral-300 rounded-lg font-medium transition flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                            </svg>
                            Export PDF
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Data Table Card -->
        <div class="bg-white dark:bg-neutral-800 rounded-lg shadow">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-neutral-700 dark:text-neutral-300">
                    <thead class="text-xs uppercase bg-neutral-50 dark:bg-neutral-900 text-neutral-700 dark:text-neutral-300">
                        <tr>
                            <th class="px-6 py-3">No</th>
                            <th class="px-6 py-3">Kelompok</th>
                            <th class="px-6 py-3">Komoditi</th>
                            <th class="px-6 py-3">Tahun</th>
                            <th class="px-6 py-3">Bulan</th>
                            <th class="px-6 py-3">Kalori/Hari</th>
                            <th class="px-6 py-3">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="border-b border-neutral-200 dark:border-neutral-700">
                            <td colspan="7" class="px-6 py-12 text-center text-neutral-500 dark:text-neutral-400">
                                <svg class="w-12 h-12 mx-auto mb-4 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                <p class="font-medium">Pilih filter dan klik "Tampilkan Data"</p>
                                <p class="text-sm mt-1">Data akan ditampilkan sesuai filter yang Anda pilih</p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="flex items-center justify-between px-6 py-4 border-t border-neutral-200 dark:border-neutral-700">
                <div class="text-sm text-neutral-600 dark:text-neutral-400 pagination-caption">
                    Menampilkan 0 data
                </div>
                <div class="flex gap-2">
                    <button disabled class="pagination-prev px-3 py-1 text-sm border border-neutral-300 dark:border-neutral-600 rounded bg-neutral-100 dark:bg-neutral-700 text-neutral-400 dark:text-neutral-500 cursor-not-allowed">Previous</button>
                    <button disabled class="pagination-next px-3 py-1 text-sm border border-neutral-300 dark:border-neutral-600 rounded bg-neutral-100 dark:bg-neutral-700 text-neutral-400 dark:text-neutral-500 cursor-not-allowed">Next</button>
                </div>
            </div>
        </div>

        <!-- Statistics Summary -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
            <div class="bg-white dark:bg-neutral-800 rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-green-100 dark:bg-green-900/30">
                        <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-neutral-600 dark:text-neutral-400">Total Data</p>
                        <p class="text-2xl font-bold text-neutral-900 dark:text-white statistics-total-data">-</p>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-neutral-800 rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-blue-100 dark:bg-blue-900/30">
                        <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M2 10a8 8 0 018-8v8h8a8 8 0 11-16 0z"/>
                            <path d="M12 2.252A8.014 8.014 0 0117.748 8H12V2.252z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-neutral-600 dark:text-neutral-400">Rata-rata Kalori</p>
                        <p class="text-2xl font-bold text-neutral-900 dark:text-white statistics-avg-kalori">-</p>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-neutral-800 rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-purple-100 dark:bg-purple-900/30">
                        <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-neutral-600 dark:text-neutral-400">Periode Terakhir</p>
                        <p class="text-2xl font-bold text-neutral-900 dark:text-white statistics-periode">-</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript for Interactive Functionality -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const filterBtn = document.getElementById('filterBtn');
            const resetBtn = document.getElementById('resetBtn');
            const exportExcelBtn = document.getElementById('exportExcelBtn');
            const exportPdfBtn = document.getElementById('exportPdfBtn');
            
            const kelompokSelect = document.getElementById('kelompokSelect');
            const tahunSelect = document.getElementById('tahunSelect');
            const bulanSelect = document.getElementById('bulanSelect');

            // Pagination variables
            let currentPage = 1;
            let lastPage = 1;
            let currentFilters = {};

            // Filter Data Function
            filterBtn.addEventListener('click', function() {
                currentPage = 1; // Reset to first page when filtering
                currentFilters = {
                    kelompok: kelompokSelect.value,
                    tahun: tahunSelect.value,
                    bulan: bulanSelect.value
                };
                loadData(currentPage);
            });

            // Load Data Function
            function loadData(page = 1) {
                console.log('loadData called with page:', page);
                console.log('currentFilters:', currentFilters);
                
                const formData = new FormData();
                formData.append('_token', '{{ csrf_token() }}');
                formData.append('kelompok', currentFilters.kelompok || '');
                formData.append('tahun', currentFilters.tahun || '');
                formData.append('bulan', currentFilters.bulan || '');
                
                console.log('FormData entries:');
                for (let [key, value] of formData.entries()) {
                    console.log(key + ': ' + value);
                }
                
                // Add page parameter to URL instead of form data
                const url = new URL('{{ route("pemerintah.laporan-nbm.filter") }}');
                if (page > 1) {
                    url.searchParams.set('page', page);
                }
                
                console.log('Fetching URL:', url.toString());

                // Show loading state
                filterBtn.innerHTML = '<svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Loading...';
                filterBtn.disabled = true;

                fetch(url.toString(), {
                    method: 'POST',
                    body: formData
                })
                .then(response => {
                    console.log('Response status:', response.status);
                    console.log('Response ok:', response.ok);
                    return response.json();
                })
                .then(data => {
                    console.log('Response data:', data);
                    if (data.success) {
                        currentPage = page;
                        updateTable(data.data);
                        updatePaginationInfo(data.data); // Pass pagination data
                        updateStatistics(data.statistics);
                    } else {
                        console.error('Error from server:', data.message);
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Fetch error:', error);
                    alert('Terjadi kesalahan saat memuat data: ' + error.message);
                })
                .finally(() => {
                    filterBtn.innerHTML = '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>Tampilkan Data';
                    filterBtn.disabled = false;
                });
            }

            // Pagination Event Listeners
            document.addEventListener('click', function(e) {
                if (e.target.classList.contains('pagination-prev') && !e.target.disabled) {
                    loadData(currentPage - 1);
                } else if (e.target.classList.contains('pagination-next') && !e.target.disabled) {
                    loadData(currentPage + 1);
                }
            });

            // Reset Filter Function
            resetBtn.addEventListener('click', function() {
                kelompokSelect.value = '';
                tahunSelect.value = '';
                bulanSelect.value = '';
                
                // Reset pagination variables
                currentPage = 1;
                currentFilters = {};
                
                // Reset table to default empty state
                const tbody = document.querySelector('tbody');
                tbody.innerHTML = `
                    <tr class="border-b border-neutral-200 dark:border-neutral-700">
                        <td colspan="7" class="px-6 py-12 text-center text-neutral-500 dark:text-neutral-400">
                            <svg class="w-12 h-12 mx-auto mb-4 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <p class="font-medium">Pilih filter dan klik "Tampilkan Data"</p>
                            <p class="text-sm mt-1">Data akan ditampilkan sesuai filter yang Anda pilih</p>
                        </td>
                    </tr>
                `;
                
                // Reset statistics and pagination
                resetStatistics();
                updatePaginationInfo(null);
            });

            // Export Excel Function
            exportExcelBtn.addEventListener('click', function(e) {
                e.preventDefault();
                
                // Show confirmation with info about limit
                if (!confirm('Export akan memproses maksimal 1000 data terbaru sesuai filter.\n\nTIPS: Gunakan filter (Tahun/Bulan) untuk export data spesifik.\n\nLanjutkan export?')) {
                    return;
                }
                
                // Show loading
                const originalText = this.innerHTML;
                this.innerHTML = '<svg class="animate-spin h-5 w-5 mr-2 inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Exporting...';
                this.style.pointerEvents = 'none';
                
                const params = new URLSearchParams({
                    kelompok: kelompokSelect.value,
                    tahun: tahunSelect.value,
                    bulan: bulanSelect.value
                });
                
                // Reset button after download starts (or after timeout)
                setTimeout(() => {
                    this.innerHTML = originalText;
                    this.style.pointerEvents = 'auto';
                }, 5000);
                
                window.location.href = '{{ route("pemerintah.laporan-nbm.export.excel") }}?' + params.toString();
            });

            // Export PDF Function
            exportPdfBtn.addEventListener('click', function(e) {
                e.preventDefault();
                
                // Show confirmation with info about limit
                if (!confirm('Export PDF akan memproses maksimal 500 data terbaru sesuai filter.\n\nTIPS: Gunakan filter (Tahun/Bulan) untuk export data spesifik.\n\nLanjutkan export?')) {
                    return;
                }
                
                // Show loading
                const originalText = this.innerHTML;
                this.innerHTML = '<svg class="animate-spin h-5 w-5 mr-2 inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Exporting...';
                this.style.pointerEvents = 'none';
                
                const params = new URLSearchParams({
                    kelompok: kelompokSelect.value,
                    tahun: tahunSelect.value,
                    bulan: bulanSelect.value
                });
                
                // Reset button after download starts (or after timeout)
                setTimeout(() => {
                    this.innerHTML = originalText;
                    this.style.pointerEvents = 'auto';
                }, 5000);
                
                window.location.href = '{{ route("pemerintah.laporan-nbm.export.pdf") }}?' + params.toString();
            });

            // Update Table Function
            function updateTable(data) {
                const tbody = document.querySelector('tbody');
                if (data.data && data.data.length > 0) {
                    let html = '';
                    data.data.forEach((item, index) => {
                        const startIndex = ((data.current_page - 1) * data.per_page) + 1;
                        html += `
                            <tr class="border-b border-neutral-200 dark:border-neutral-700 hover:bg-neutral-50 dark:hover:bg-neutral-700">
                                <td class="px-6 py-4">${startIndex + index}</td>
                                <td class="px-6 py-4">${item.kelompok ? item.kelompok.deskripsi : '-'}</td>
                                <td class="px-6 py-4">${item.komoditi ? item.komoditi.deskripsi : '-'}</td>
                                <td class="px-6 py-4">${item.tahun}</td>
                                <td class="px-6 py-4">${item.bulan}</td>
                                <td class="px-6 py-4">${item.kalori_hari || '-'}</td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 text-xs bg-green-100 text-green-800 rounded-full">
                                        ${item.validation_status || 'verified'}
                                    </span>
                                </td>
                            </tr>
                        `;
                    });
                    tbody.innerHTML = html;
                    
                    // Update pagination and caption
                    updatePaginationInfo(data);
                } else {
                    tbody.innerHTML = `
                        <tr class="border-b border-neutral-200 dark:border-neutral-700">
                            <td colspan="7" class="px-6 py-12 text-center text-neutral-500 dark:text-neutral-400">
                                <p class="font-medium">Tidak ada data ditemukan</p>
                                <p class="text-sm mt-1">Coba ubah kriteria filter</p>
                            </td>
                        </tr>
                    `;
                    // Reset pagination for no data
                    updatePaginationInfo(null);
                }
            }

            // Update Pagination Info Function
            function updatePaginationInfo(data) {
                // Store last page for pagination logic
                if (data && data.last_page) {
                    lastPage = data.last_page;
                }
                
                // Update caption
                const captionElement = document.querySelector('.pagination-caption');
                if (captionElement) {
                    if (data && data.total > 0) {
                        captionElement.textContent = `Menampilkan ${data.from}-${data.to} dari ${data.total} data`;
                    } else {
                        captionElement.textContent = 'Menampilkan 0 data';
                    }
                }

                // Update pagination buttons
                const prevBtn = document.querySelector('.pagination-prev');
                const nextBtn = document.querySelector('.pagination-next');
                
                if (prevBtn && nextBtn) {
                    if (data && data.total > 0) {
                        // Previous button
                        if (data.current_page > 1) {
                            prevBtn.disabled = false;
                            prevBtn.classList.remove('cursor-not-allowed', 'bg-neutral-100', 'dark:bg-neutral-700', 'text-neutral-400', 'dark:text-neutral-500');
                            prevBtn.classList.add('hover:bg-neutral-50', 'dark:hover:bg-neutral-600', 'text-neutral-700', 'dark:text-neutral-300');
                        } else {
                            prevBtn.disabled = true;
                            prevBtn.classList.add('cursor-not-allowed', 'bg-neutral-100', 'dark:bg-neutral-700', 'text-neutral-400', 'dark:text-neutral-500');
                            prevBtn.classList.remove('hover:bg-neutral-50', 'dark:hover:bg-neutral-600', 'text-neutral-700', 'dark:text-neutral-300');
                        }

                        // Next button
                        if (data.current_page < data.last_page) {
                            nextBtn.disabled = false;
                            nextBtn.classList.remove('cursor-not-allowed', 'bg-neutral-100', 'dark:bg-neutral-700', 'text-neutral-400', 'dark:text-neutral-500');
                            nextBtn.classList.add('hover:bg-neutral-50', 'dark:hover:bg-neutral-600', 'text-neutral-700', 'dark:text-neutral-300');
                        } else {
                            nextBtn.disabled = true;
                            nextBtn.classList.add('cursor-not-allowed', 'bg-neutral-100', 'dark:bg-neutral-700', 'text-neutral-400', 'dark:text-neutral-500');
                            nextBtn.classList.remove('hover:bg-neutral-50', 'dark:hover:bg-neutral-600', 'text-neutral-700', 'dark:text-neutral-300');
                        }
                    } else {
                        // No data - disable both buttons
                        prevBtn.disabled = true;
                        nextBtn.disabled = true;
                        prevBtn.classList.add('cursor-not-allowed', 'bg-neutral-100', 'dark:bg-neutral-700', 'text-neutral-400', 'dark:text-neutral-500');
                        nextBtn.classList.add('cursor-not-allowed', 'bg-neutral-100', 'dark:bg-neutral-700', 'text-neutral-400', 'dark:text-neutral-500');
                    }
                }
            }

            // Update Statistics Function
            function updateStatistics(stats) {
                const totalDataElement = document.querySelector('.statistics-total-data');
                const avgKaloriElement = document.querySelector('.statistics-avg-kalori');
                const periodeTerbaruElement = document.querySelector('.statistics-periode');

                if (totalDataElement) totalDataElement.textContent = stats.total_data || '-';
                if (avgKaloriElement) avgKaloriElement.textContent = stats.rata_rata_kalori ? Math.round(stats.rata_rata_kalori) : '-';
                if (periodeTerbaruElement && stats.periode_terbaru) {
                    periodeTerbaruElement.textContent = `${stats.periode_terbaru.bulan}/${stats.periode_terbaru.tahun}`;
                }
            }

            // Reset Statistics Function
            function resetStatistics() {
                const totalDataElement = document.querySelector('.statistics-total-data');
                const avgKaloriElement = document.querySelector('.statistics-avg-kalori');
                const periodeTerbaruElement = document.querySelector('.statistics-periode');

                if (totalDataElement) totalDataElement.textContent = '-';
                if (avgKaloriElement) avgKaloriElement.textContent = '-';
                if (periodeTerbaruElement) periodeTerbaruElement.textContent = '-';
            }
            
            // Auto-load data on page load (no filters) - for debugging
            console.log('Page loaded, testing auto-load...');
            setTimeout(() => {
                console.log('Attempting auto-load after 500ms');
                currentFilters = {};
                loadData(1);
            }, 500);
        });
    </script>
</x-layouts.pemerintah>
