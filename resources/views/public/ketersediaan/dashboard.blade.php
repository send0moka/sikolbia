<x-layouts.landing title="Dashboard NBM Publik - SIKOLBIA">
    <!-- FontAwesome Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <div class="py-12 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8" x-data="nbmDashboard()">
            <!-- Breadcrumb -->
            <nav class="flex mb-8" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('home') }}" class="text-neutral-700 hover:text-blue-600">Home</a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-neutral-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            <span class="ml-1 text-neutral-500">Ketersediaan</span>
                        </div>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-neutral-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            <span class="ml-1 text-blue-600 font-medium">Dashboard NBM Publik</span>
                        </div>
                    </li>
                </ol>
            </nav>

            <!-- Page Header -->
            <div class="mb-8">
                <h1 class="text-3xl md:text-4xl font-bold text-neutral-900 mb-4">
                    Dashboard Neraca Bahan Makanan (NBM) Indonesia
                </h1>
                <p class="text-xl text-neutral-600">
                    Informasi publik konsumsi pangan nasional - Data terbaru dari 120 komoditas dalam 11 kelompok
                </p>
            </div>

        <!-- Key Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white p-6 rounded-lg shadow-md">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-blue-100 text-blue-600 mr-4">
                        <i class="fas fa-calendar-alt text-xl"></i>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-neutral-800">{{ $stats['tahun_terbaru'] }}</p>
                        <p class="text-neutral-600 text-sm">Tahun Data Terbaru</p>
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-md">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-green-100 text-green-600 mr-4">
                        <i class="fas fa-seedling text-xl"></i>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-neutral-800">{{ $stats['total_komoditas'] }}</p>
                        <p class="text-neutral-600 text-sm">Komoditas Pangan</p>
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-md">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-purple-100 text-purple-600 mr-4">
                        <i class="fas fa-layer-group text-xl"></i>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-neutral-800">{{ $stats['total_kelompok'] }}</p>
                        <p class="text-neutral-600 text-sm">Kelompok Komoditas</p>
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-md">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-orange-100 text-orange-600 mr-4">
                        <i class="fas fa-fire text-xl"></i>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-neutral-800">{{ number_format($stats['rata_kalori'], 0) }}</p>
                        <p class="text-neutral-600 text-sm">Rata-rata Kalori/Hari</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            
            <!-- Konsumsi per Kelompok Chart -->
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h3 class="text-lg font-bold text-gray-800 mb-4">
                    <i class="fas fa-chart-pie text-blue-600 mr-2"></i>
                    Konsumsi Kalori per Kelompok Komoditas
                </h3>
                <div class="relative h-64">
                    <canvas id="kelompokChart"></canvas>
                </div>
            </div>

            <!-- Tren Konsumsi Chart -->
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h3 class="text-lg font-bold text-gray-800 mb-4">
                    <i class="fas fa-chart-line text-green-600 mr-2"></i>
                    Tren Konsumsi Kalori (5 Tahun Terakhir)
                </h3>
                <div class="relative h-64">
                    <canvas id="trenChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Top Komoditas Table -->
        <div class="bg-white rounded-lg shadow-md mb-8">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-bold text-gray-800">
                    <i class="fas fa-trophy text-yellow-600 mr-2"></i>
                    Top 10 Komoditas Konsumsi Kalori Tertinggi
                </h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ranking</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Komoditas</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kelompok</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kalori/Hari</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Persentase</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <!-- Loading State -->
                        <template x-show="loading">
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center">
                                    <div class="flex justify-center items-center">
                                        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                                        <span class="ml-3 text-gray-600">Memuat data...</span>
                                    </div>
                                </td>
                            </tr>
                        </template>
                        
                        <!-- Error State -->
                        <template x-show="error && !loading">
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center">
                                    <div class="text-red-600">
                                        <i class="fas fa-exclamation-triangle mr-2"></i>
                                        <span x-text="error"></span>
                                    </div>
                                </td>
                            </tr>
                        </template>
                        
                        <!-- Data Rows -->
                        <template x-show="!loading && !error" x-for="(item, index) in topKomoditas" :key="index">
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    <span class="flex items-center">
                                        <span x-show="index < 3" class="text-yellow-500 mr-2">
                                            <i class="fas fa-medal"></i>
                                        </span>
                                        <span x-text="index + 1"></span>
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900" x-text="item.nama_komoditi || item.nama"></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500" x-text="item.kelompok"></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">
                                    <span x-text="parseFloat(item.kalori_harian || item.kalori || 0).toFixed(1)"></span> kkal/hari
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-16 bg-gray-200 rounded-full h-2 mr-3">
                                            <div class="bg-blue-600 h-2 rounded-full" 
                                                 :style="`width: ${Math.min(100, ((item.kalori_harian || item.kalori || 0) / Math.max(...topKomoditas.map(t => t.kalori_harian || t.kalori || 0))) * 100)}%`">
                                            </div>
                                        </div>
                                        <span class="text-sm text-gray-900" 
                                              x-text="`${Math.round(((item.kalori_harian || item.kalori || 0) / topKomoditas.reduce((sum, t) => sum + (t.kalori_harian || t.kalori || 0), 0)) * 100)}%`">
                                        </span>
                                    </div>
                                </td>
                            </tr>
                        </template>
                        
                        <!-- Empty State -->
                        <template x-show="!loading && !error && topKomoditas.length === 0">
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                    <i class="fas fa-inbox text-2xl mb-2"></i>
                                    <div>Tidak ada data tersedia</div>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Information Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
            
            <!-- Data Information -->
            <div class="bg-blue-50 border-l-4 border-blue-500 p-6 rounded-lg">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <i class="fas fa-info-circle text-blue-500 text-xl mt-1"></i>
                    </div>
                    <div class="ml-3">
                        <h4 class="text-lg font-medium text-blue-900">Tentang Data NBM</h4>
                        <p class="text-blue-700 text-sm mt-2">
                            Neraca Bahan Makanan (NBM) adalah sistem akuntansi yang menggambarkan 
                            situasi ketersediaan dan kebutuhan pangan di suatu negara atau wilayah pada 
                            periode waktu tertentu.
                        </p>
                        <div class="mt-4">
                            <a href="{{ route('public.ketersediaan.metodologi') }}" 
                               class="text-blue-600 text-sm font-medium hover:text-blue-800">
                                Pelajari Metodologi <i class="fas fa-arrow-right ml-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- AI Prediction Info -->
            <div class="bg-purple-50 border-l-4 border-purple-500 p-6 rounded-lg">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <i class="fas fa-robot text-purple-500 text-xl mt-1"></i>
                    </div>
                    <div class="ml-3">
                        <h4 class="text-lg font-medium text-purple-900">Prediksi AI</h4>
                        <p class="text-purple-700 text-sm mt-2">
                            Sistem menggunakan teknologi Artificial Intelligence dengan SHAP analysis 
                            untuk memberikan prediksi konsumsi pangan yang akurat dan dapat dipahami.
                        </p>
                        <div class="mt-4 space-y-2">
                            <div class="flex items-center text-sm text-purple-700">
                                <i class="fas fa-check-circle text-purple-500 mr-2"></i>
                                Prediksi akurasi > 80%
                            </div>
                            <div class="flex items-center text-sm text-purple-700">
                                <i class="fas fa-check-circle text-purple-500 mr-2"></i>
                                Interpretabilitas SHAP
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Access Registration CTA -->
        <div class="relative rounded-xl p-8 text-center shadow-2xl border-2 border-blue-700 overflow-hidden text-white" 
             style="background: linear-gradient(to right, #1e3a8a, #1e40af, #3730a3);">
            <div class="relative z-10">
                <h3 class="text-2xl md:text-3xl font-bold mb-4 text-white drop-shadow-lg">Butuh Akses Lebih Lengkap?</h3>
                <p class="text-blue-100 mb-8 max-w-2xl mx-auto text-lg leading-relaxed">
                    Dapatkan akses ke data historis lengkap, prediksi AI advanced, dan tools analisis 
                    dengan mendaftar sesuai kategori Anda.
                </p>
                <div class="flex flex-col sm:flex-row justify-center gap-4">
                    <a href="{{ route('public.registrasi.pemerintah') }}" 
                       class="bg-white text-blue-800 px-8 py-4 rounded-lg font-semibold hover:bg-blue-50 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                        <i class="fas fa-university mr-2"></i> Akses Pemerintah
                    </a>
                    <a href="{{ route('public.registrasi.akademisi') }}" 
                       class="border-2 border-white text-white px-8 py-4 rounded-lg font-semibold hover:bg-white hover:text-blue-800 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                        <i class="fas fa-graduation-cap mr-2"></i> Akses Akademisi
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script>
        function nbmDashboard() {
            return {
                stats: @json($stats),
                topKomoditas: [],
                kelompokData: [],
                trenData: [],
                loading: true,
                error: null,

                async init() {
                    await this.loadDashboardData();
                    this.$nextTick(() => {
                        this.initCharts();
                    });
                },

                async loadDashboardData() {
                    try {
                        this.loading = true;
                        this.error = null;
                        
                        const response = await fetch('{{ route("public.ketersediaan.api.dashboard-data") }}');
                        
                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }
                        
                        const data = await response.json();
                        
                        console.log('API Response:', data); // Debug log
                        
                        this.topKomoditas = data.top_komoditas || [];
                        this.kelompokData = data.kelompok || [];
                        this.trenData = data.tren || [];
                        
                        console.log('Top Komoditas loaded:', this.topKomoditas.length);
                        console.log('Kelompok loaded:', this.kelompokData.length);
                        
                        this.loading = false;
                    } catch (error) {
                        console.error('Error loading dashboard data:', error);
                        this.error = 'Gagal memuat data dashboard. Data mungkin tidak tersedia.';
                        this.loading = false;
                    }
                },

                // Remove fallback static data - no longer needed
                setFallbackData() {
                    // No static fallback - show error instead
                    this.error = 'Data tidak tersedia dari server';
                    this.topKomoditas = [];
                    this.kelompokData = [];
                    this.trenData = [];
                },

                initCharts() {
                    this.createKelompokChart();
                    this.createTrenChart();
                },

                createKelompokChart() {
                    const ctx = document.getElementById('kelompokChart').getContext('2d');
                    
                    // Check if data is available
                    if (!this.kelompokData || this.kelompokData.length === 0) {
                        // Show no data message in chart area
                        ctx.font = '16px Arial';
                        ctx.fillStyle = '#6B7280';
                        ctx.textAlign = 'center';
                        ctx.fillText('Tidak ada data kelompok', ctx.canvas.width / 2, ctx.canvas.height / 2);
                        return;
                    }
                    
                    // Siapkan data untuk chart berdasarkan data dari API
                    const labels = this.kelompokData.map(item => {
                        // Gunakan nama kelompok langsung dari database
                        return item.nama_kelompok || `Kelompok ${item.kode_kelompok}`;
                    });
                    
                    const data = this.kelompokData.map(item => parseFloat(item.total_konsumsi) || 0);
                    
                    new Chart(ctx, {
                        type: 'doughnut',
                        data: {
                            labels: labels,
                            datasets: [{
                                data: data,
                                backgroundColor: [
                                    '#3B82F6', '#10B981', '#F59E0B', '#EF4444', 
                                    '#8B5CF6', '#06B6D4', '#6B7280', '#F97316',
                                    '#EC4899', '#84CC16', '#06B6D4'
                                ]
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    position: 'bottom',
                                    labels: { 
                                        fontSize: 12,
                                        usePointStyle: true,
                                        padding: 15
                                    }
                                }
                            }
                        }
                    });
                },

                createTrenChart() {
                    const ctx = document.getElementById('trenChart').getContext('2d');
                    
                    // Check if data is available
                    if (!this.trenData || this.trenData.length === 0) {
                        // Show no data message in chart area
                        ctx.font = '16px Arial';
                        ctx.fillStyle = '#6B7280';
                        ctx.textAlign = 'center';
                        ctx.fillText('Tidak ada data tren', ctx.canvas.width / 2, ctx.canvas.height / 2);
                        return;
                    }
                    
                    // Siapkan data tren dari API
                    const labels = this.trenData.map(item => item.tahun.toString());
                    const data = this.trenData.map(item => parseFloat(item.rata_kalori) || 0);
                    
                    new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: labels,
                            datasets: [{
                                label: 'Rata-rata Kalori per Hari',
                                data: data,
                                borderColor: '#10B981',
                                backgroundColor: 'rgba(16, 185, 129, 0.1)',
                                fill: true,
                                tension: 0.4,
                                pointBackgroundColor: '#10B981',
                                pointBorderColor: '#ffffff',
                                pointBorderWidth: 2,
                                pointRadius: 5
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            scales: {
                                y: {
                                    beginAtZero: false,
                                    min: Math.max(0, Math.min(...data) - 50)
                                }
                            },
                            plugins: {
                                legend: {
                                    display: false
                                }
                            }
                        }
                    });
                }
            }
        }
    </script>
        </div>
    </div>
</x-layouts.landing>