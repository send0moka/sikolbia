<x-layouts.landing title="Dashboard Monitoring Komoditas">
    <!-- Include Chart.js only -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <div class="py-8 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-6">
                <div class="flex justify-between items-center" x-data="headerInfo()" x-init="loadLastUpdateTime()">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">Dashboard Monitoring Komoditas</h1>
                        <p class="text-gray-600">Real-time monitoring harga dan tren komoditas pangan Indonesia</p>
                    </div>
                    <div class="text-right">
                        <div class="text-sm text-gray-500">Last Updated</div>
                        <div class="font-semibold text-gray-900" x-text="lastUpdateTime || 'Loading...'"></div>
                    </div>
                </div>
            </div>

            <!-- Market Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6" x-data="dashboardData()">
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-600">Komoditas Aktif</p>
                            <p class="text-2xl font-bold text-gray-900" x-text="summary.totalCommodities"></p>
                        </div>
                        <div class="p-3 rounded-full bg-blue-100">
                            <svg class="h-8 w-8 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z" />
                            </svg>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-600">Trend Naik</p>
                            <p class="text-2xl font-bold text-green-600" x-text="summary.trendUp"></p>
                            <p class="text-xs text-green-600" x-text="'+' + summary.trendUpPercent + '%'"></p>
                        </div>
                        <div class="p-3 rounded-full bg-green-100">
                            <svg class="h-8 w-8 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                            </svg>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-600">Trend Turun</p>
                            <p class="text-2xl font-bold text-red-600" x-text="summary.trendDown"></p>
                            <p class="text-xs text-red-600" x-text="'-' + summary.trendDownPercent + '%'"></p>
                        </div>
                        <div class="p-3 rounded-full bg-red-100">
                            <svg class="h-8 w-8 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6" />
                            </svg>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-600">Volatilitas Tinggi</p>
                            <p class="text-2xl font-bold text-yellow-600" x-text="summary.highVolatility"></p>
                            <p class="text-xs text-yellow-600">Alert</p>
                        </div>
                        <div class="p-3 rounded-full bg-yellow-100">
                            <svg class="h-8 w-8 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filter Controls -->
            <div class="bg-white rounded-lg shadow mb-6 p-6" x-data="filterControls()">
                <div class="flex flex-wrap items-center gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Metrik</label>
                        <select x-model="selectedMetric" @change="updateView()" 
                                class="border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="harga">Harga</option>
                            <option value="produksi">Produksi</option>
                            <option value="perdagangan">Perdagangan</option>
                            <option value="ketersediaan">Ketersediaan</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Periode</label>
                        <select x-model="selectedPeriod" @change="updateView()"
                                class="border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="2M">2 Bulan</option>
                            <option value="4M">4 Bulan</option>
                            <option value="6M">6 Bulan</option>
                            <option value="1Y">1 Tahun</option>
                            <option value="All Time">Semua Waktu</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kelompok</label>
                        <select x-model="selectedGroup" @change="updateView()"
                                class="border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Semua Kelompok</option>
                            <template x-for="group in groups" :key="group.kode">
                                <option :value="group.kode" x-text="group.nama"></option>
                            </template>
                        </select>
                    </div>
                    
                    <div class="flex-1"></div>
                    
                    <div>
                        <button @click="refreshData()" 
                                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md flex items-center space-x-2">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                            <span>Refresh</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Commodities List -->
            <div x-data="commoditiesList()">
                <!-- Header -->
                <div class="py-4">
                    <div class="flex items-center justify-between">
                        <h2 class="text-lg font-semibold text-gray-900">Daftar Komoditas</h2>
                        <div class="flex items-center space-x-4">
                            <div class="relative">
                                <input type="text" x-model="searchQuery" @input="filterCommodities()"
                                       placeholder="Cari komoditas..."
                                       class="pl-10 pr-4 py-2 bg-white border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <svg class="h-5 w-5 text-gray-400 absolute left-3 top-1/2 transform -translate-y-1/2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Commodities Cards Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <!-- Loading State -->
                    <template x-if="loading">
                        <div class="col-span-full flex justify-center items-center py-12">
                            <div class="text-center">
                                <svg class="animate-spin mx-auto h-8 w-8 text-blue-600 mb-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <p class="text-gray-600">Memuat data komoditas...</p>
                            </div>
                        </div>
                    </template>

                    <!-- Commodity Cards -->
                    <template x-for="(commodity, index) in filteredCommodities.slice(0, displayCount)" :key="`commodity-${commodity.id || index}`">
                        <div class="rounded-lg shadow-sm border p-4 transition-shadow cursor-pointer"
                             :class="commodity?.hasData ? 'bg-white border-gray-200 hover:shadow-md' : 'bg-gray-50 border-gray-300 opacity-70'"
                             @click="commodity?.hasData ? selectCommodity(commodity) : null">
                            
                            <!-- Disabled State Badge (only for commodities without data) -->
                            <div x-show="!commodity?.hasData" class="mb-2">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-200 text-gray-600">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                    Data belum tersedia
                                </span>
                            </div>
                            
            <!-- Card Header -->
            <div class="flex items-center justify-between mb-3">
                <div class="flex items-center">
                    <div class="flex-shrink-0 h-8 w-8">
                        <div class="h-8 w-8 rounded-full flex items-center justify-center text-lg"
                             :class="commodity?.hasData ? 'bg-gray-100' : 'bg-gray-200'"
                             x-text="getCommodityEmoji(commodity?.group || '01')">
                        </div>
                    </div>
                    <div class="ml-2">
                        <h3 class="font-medium text-sm"
                            :class="commodity?.hasData ? 'text-gray-900' : 'text-gray-600'"
                            x-text="commodity?.name || 'Loading...'"></h3>
                        <p class="text-xs text-gray-500" x-text="commodity?.groupName || ''"></p>
                    </div>
                </div>
                <!-- Price Info moved to header (only show for commodities with data) -->
                <div class="text-right" x-show="commodity?.hasData">
                    <div class="text-lg font-bold text-gray-900" x-text="formatValue(commodity?.currentValue || 0, commodity?.unit || 'Rp/Kg')"></div>
                    <div class="flex items-center justify-end space-x-1 mt-1">
                        <span class="text-xs font-medium" 
                              :class="(commodity?.change || 0) >= 0 ? 'text-green-600' : 'text-red-600'"
                              x-text="((commodity?.change || 0) >= 0 ? '+' : '') + formatValue(Math.abs(commodity?.change || 0), commodity?.unit || 'Rp/Kg')">
                        </span>
                        <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-medium"
                              :class="(commodity?.changePercent || 0) >= 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'">
                            <svg class="w-2.5 h-2.5 mr-0.5" fill="currentColor" viewBox="0 0 20 20"
                                 :class="(commodity?.changePercent || 0) >= 0 ? 'transform rotate-0' : 'transform rotate-180'">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                            </svg>
                            <span x-text="Math.abs(commodity?.changePercent || 0).toFixed(2) + '%'"></span>
                        </span>
                    </div>
                </div>
                <!-- No data indicator for header -->
                <div class="text-right text-gray-500" x-show="!commodity?.hasData">
                    <div class="text-lg font-medium">-</div>
                    <div class="text-xs text-gray-400">No data</div>
                </div>
            </div>

                            <!-- Mini Chart (only show for commodities with data) -->
                            <div class="h-16 w-full" x-show="commodity?.hasData">
                                <canvas :id="'chart-' + (commodity?.id || index)" class="w-full h-16" width="200" height="64"></canvas>
                            </div>

                            <!-- Placeholder for no data -->
                            <div class="h-16 w-full flex items-center justify-center" x-show="!commodity?.hasData">
                                <div class="text-center">
                                    <svg class="h-8 w-8 text-gray-400 mx-auto mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <p class="text-xs text-gray-400">Grafik tidak tersedia</p>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>

                <!-- No Data State -->
                <div class="px-6 py-8 text-center" x-show="!loading && filteredCommodities.length === 0">
                    <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2M4 13h2m13-8l-4 4-4-4m-6 0l4 4-4-4" />
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak Ada Data Komoditas</h3>
                    <p class="text-gray-600 mb-4">
                        <span x-show="searchQuery">Tidak ditemukan komoditas dengan kata kunci "<span x-text="searchQuery"></span>"</span>
                        <span x-show="!searchQuery">Belum ada data komoditas yang tersedia</span>
                    </p>
                    <button @click="refreshData(); searchQuery = ''" 
                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm">
                        Muat Ulang Data
                    </button>
                </div>

                <!-- Load More Button -->
                <div class="px-6 py-4 text-center">
                    <!-- Showing count info -->
                    <p class="text-sm text-gray-600 mb-3" x-show="totalCommodities > 0">
                        Menampilkan <span x-text="Math.min(displayCount, totalCommodities)"></span> dari <span x-text="totalCommodities"></span> komoditas
                    </p>
                    
                    <button @click="loadMore()" 
                            x-show="canLoadMore && totalCommodities > 12"
                            class="bg-white p-4 rounded-lg border text-blue-600 hover:text-blue-800 font-medium text-sm transition-colors">
                        <span x-text="showingAll ? 'Tampilkan 12 Komoditas' : `Load More Commodities (+${Math.min(12, totalCommodities - displayCount)})`"></span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Detail Modal -->
    <div x-data="detailModal()" x-show="showModal" x-cloak 
         class="fixed inset-0 z-50 overflow-y-auto" 
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="closeModal()">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="closeModal()"></div>
            
            <div class="relative inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full z-50" @click.stop>
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" x-text="selectedCommodity?.name"></h3>
                        <button @click="closeModal()" class="text-gray-400 hover:text-gray-600">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- Detailed Chart -->
                        <div>
                            <canvas id="detailChart" class="w-full h-64"></canvas>
                        </div>
                        
                        <!-- Stats -->
                        <div class="space-y-4">
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h4 class="font-medium text-gray-900 mb-2">Statistik</h4>
                                <div class="space-y-2 text-sm">
                                    <div class="flex justify-between">
                                        <span>Nilai Terkini:</span>
                                        <span class="font-medium" x-text="selectedCommodity ? formatValue(selectedCommodity.currentValue, selectedCommodity.unit) : ''"></span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span>Tertinggi (52W):</span>
                                        <span class="font-medium" x-text="selectedCommodity ? formatValue(selectedCommodity.yearHigh, selectedCommodity.unit) : ''"></span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span>Terendah (52W):</span>
                                        <span class="font-medium" x-text="selectedCommodity ? formatValue(selectedCommodity.yearLow, selectedCommodity.unit) : ''"></span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span>Rata-rata:</span>
                                        <span class="font-medium" x-text="selectedCommodity ? formatValue(selectedCommodity.average, selectedCommodity.unit) : ''"></span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span>Volatilitas:</span>
                                        <span class="font-medium" x-text="selectedCommodity ? selectedCommodity.volatility.toFixed(2) + '%' : ''"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        [x-cloak] { display: none !important; }
    </style>

    <script>
        function headerInfo() {
            return {
                lastUpdateTime: null,
                
                async loadLastUpdateTime() {
                    try {
                        const response = await fetch('/api/dashboard-komoditas/commodities?period=1Y&metric=harga');
                        const data = await response.json();
                        
                        if (data.success && data.data.length > 0) {
                            // Find the most recent lastUpdate from all commodities
                            const updates = data.data
                                .map(commodity => commodity.lastUpdate)
                                .filter(update => {
                                    // Only include valid date strings in "15 September 2025, 15:20" format
                                    return update && 
                                           update !== '-' && 
                                           update !== 'Unknown' && 
                                           typeof update === 'string' &&
                                           update.includes(' ') && 
                                           update.includes(', ') &&
                                           update.includes(':');
                                });
                            
                            if (updates.length === 0) {
                                this.lastUpdateTime = 'No valid updates';
                                return;
                            }
                            
                            // Sort updates by date (newest first)
                            updates.sort((a, b) => {
                                try {
                                    // Convert "15 September 2025, 15:20" to comparable dates
                                    const parseDate = (dateStr) => {
                                        if (!dateStr || typeof dateStr !== 'string') return new Date(0);
                                        
                                        const parts = dateStr.split(', ');
                                        if (parts.length !== 2) return new Date(0);
                                        
                                        const [datePart, timePart] = parts;
                                        const timeComponents = timePart.split(':');
                                        
                                        if (timeComponents.length !== 2) {
                                            return new Date(0);
                                        }
                                        
                                        // Parse "15 September 2025" format
                                        const dateParts = datePart.split(' ');
                                        if (dateParts.length !== 3) return new Date(0);
                                        
                                        const [day, monthName, year] = dateParts;
                                        const [hour, minute] = timeComponents;
                                        
                                        // Convert month name to number
                                        const months = {
                                            'January': 0, 'February': 1, 'March': 2, 'April': 3,
                                            'May': 4, 'June': 5, 'July': 6, 'August': 7,
                                            'September': 8, 'October': 9, 'November': 10, 'December': 11
                                        };
                                        
                                        const monthIndex = months[monthName];
                                        if (monthIndex === undefined) return new Date(0);
                                        
                                        return new Date(parseInt(year), monthIndex, parseInt(day), 
                                                      parseInt(hour), parseInt(minute));
                                    };
                                    
                                    return parseDate(b) - parseDate(a);
                                } catch (error) {
                                    console.warn('Error parsing dates for sorting:', error);
                                    return 0;
                                }
                            });
                            
                            this.lastUpdateTime = updates[0] || 'Unknown';
                        } else {
                            this.lastUpdateTime = 'No data available';
                        }
                    } catch (error) {
                        console.error('Error loading last update time:', error);
                        this.lastUpdateTime = 'Error loading data';
                    }
                }
            }
        }

        function dashboardData() {
            return {
                summary: {
                    totalCommodities: 0,
                    trendUp: 0,
                    trendDown: 0,
                    highVolatility: 0,
                    trendUpPercent: 0,
                    trendDownPercent: 0
                },
                loading: false,

                init() {
                    this.loadSummaryData();
                },

                async loadSummaryData() {
                    this.loading = true;
                    try {
                        const response = await fetch('/api/dashboard-komoditas/summary');
                        const result = await response.json();
                        
                        if (result.success) {
                            this.summary = result.data;
                        } else {
                            console.error('Failed to load summary data:', result.message);
                        }
                    } catch (error) {
                        console.error('Error loading summary data:', error);
                    } finally {
                        this.loading = false;
                    }
                }
            }
        }

        function filterControls() {
            return {
                selectedMetric: 'harga',
                selectedPeriod: '1Y',
                selectedGroup: '',
                groups: [], // Will be loaded from API
                
                init() {
                    this.loadGroups();
                },

                async loadGroups() {
                    try {
                        const response = await fetch('/api/dashboard-komoditas/groups');
                        const result = await response.json();
                        
                        if (result.success) {
                            this.groups = result.data;
                        } else {
                            console.error('Failed to load groups:', result.message);
                            // Fallback to empty array
                            this.groups = [];
                        }
                    } catch (error) {
                        console.error('Error loading groups:', error);
                        // Fallback to empty array
                        this.groups = [];
                    }
                },
                
                updateView() {
                    // Trigger update of commodities list
                    window.dispatchEvent(new CustomEvent('filter-changed', {
                        detail: {
                            metric: this.selectedMetric,
                            period: this.selectedPeriod,
                            group: this.selectedGroup
                        }
                    }));
                },
                
                refreshData() {
                    // Trigger data refresh
                    window.dispatchEvent(new CustomEvent('refresh-data'));
                }
            }
        }

        function commoditiesList() {
            return {
                searchQuery: '',
                commodities: [],
                filteredCommodities: [],
                canLoadMore: true,
                displayCount: 12,  // Start with 12 commodities
                totalCommodities: 0,
                showingAll: false,  // Track if showing all commodities
                charts: {},
                loading: false,
                currentFilters: {
                    metric: 'harga',
                    period: '1Y',
                    group: ''
                },

                init() {
                    this.loadCommoditiesData();
                    
                    this.$nextTick(() => {
                        this.initializeCharts();
                    });

                    // Listen for filter changes
                    window.addEventListener('filter-changed', (e) => {
                        this.currentFilters = e.detail;
                        this.loadCommoditiesData();
                    });

                    window.addEventListener('refresh-data', () => {
                        this.loadCommoditiesData();
                    });
                },

                async loadCommoditiesData() {
                    this.loading = true;
                    try {
                        const params = new URLSearchParams({
                            metric: this.currentFilters.metric,
                            period: this.currentFilters.period,
                            group: this.currentFilters.group,
                            search: this.searchQuery,
                            limit: this.displayCount
                        });

                        const response = await fetch(`/api/dashboard-komoditas/commodities?${params}`);
                        const result = await response.json();
                        
                        if (result.success) {
                            this.commodities = result.data;
                            this.totalCommodities = result.data.length;
                            this.filteredCommodities = result.data;
                            
                            // Update canLoadMore and showingAll status
                            this.updateLoadMoreStatus();
                            
                            console.log('Commodities loaded from API:', this.commodities.length);
                            
                            // Initialize charts after data is loaded
                            this.$nextTick(() => {
                                this.initializeCharts();
                            });
                        } else {
                            console.error('Failed to load commodities data:', result.message);
                            // Set empty data if API fails
                            this.commodities = [];
                            this.totalCommodities = 0;
                            this.filteredCommodities = [];
                        }
                    } catch (error) {
                        console.error('Error loading commodities data:', error);
                        // Set empty data if API fails
                        this.commodities = [];
                        this.totalCommodities = 0;
                        this.filteredCommodities = [];
                    } finally {
                        this.loading = false;
                    }
                },

                filterCommodities() {
                    if (!this.searchQuery) {
                        this.loadCommoditiesData();
                        return;
                    }

                    // Only filter if we have actual data loaded
                    if (this.commodities.length > 0) {
                        const query = this.searchQuery.toLowerCase();
                        this.filteredCommodities = this.commodities.filter(c => 
                            c.name.toLowerCase().includes(query) || 
                            c.groupName.toLowerCase().includes(query)
                        );
                        
                        this.$nextTick(() => {
                            this.initializeCharts();
                        });
                    }
                },

                getCommodityColor(group) {
                    const colors = {
                        '01': 'bg-blue-500',
                        '02': 'bg-green-500', 
                        '03': 'bg-yellow-500',
                        '04': 'bg-red-500',
                        '05': 'bg-purple-500',
                        '06': 'bg-pink-500',
                        '07': 'bg-indigo-500',
                        '08': 'bg-gray-500',
                        '09': 'bg-orange-500',
                        '10': 'bg-teal-500'
                    };
                    return colors[group] || 'bg-gray-500';
                },

                getCommodityEmoji(group) {
                    const emojis = {
                        '01': '🌾', // Padi-padian
                        '02': '🥔', // Makanan Berpati
                        '03': '🍯', // Gula
                        '04': '🍎', // Buah-buahan
                        '05': '🥬', // Sayur-sayuran
                        '06': '🥩', // Daging
                        '07': '🥚', // Telur
                        '08': '🥛', // Susu
                        '09': '🛢️', // Minyak-Lemak
                        '10': '🐟',  // Ikan
                        '11': '☕'  // Minuman
                    };
                    return emojis[group] || '❓';
                },

                formatValue(value, unit) {
                    if (!value) return '0 ' + unit;
                    return new Intl.NumberFormat('id-ID').format(Math.round(value)) + ' ' + unit;
                },

                initializeCharts() {
                    // Wait for DOM to be updated
                    this.$nextTick(() => {
                        // Get currently displayed commodities based on displayCount
                        const visibleCommodities = this.filteredCommodities.slice(0, this.displayCount);
                        
                        visibleCommodities.forEach(commodity => {
                            // Only create chart if canvas element exists in DOM
                            const canvasId = `chart-${commodity.id}`;
                            const canvas = document.getElementById(canvasId);
                            if (canvas) {
                                this.createMiniChart(commodity);
                            }
                        });
                    });
                },

                createMiniChart(commodity) {
                    const canvasId = `chart-${commodity.id}`;
                    const canvas = document.getElementById(canvasId);
                    
                    if (!canvas) return;
                    
                    // Destroy existing chart if it exists
                    if (this.charts[canvasId]) {
                        this.charts[canvasId].destroy();
                    }

                    const ctx = canvas.getContext('2d');
                    const isPositive = (commodity.changePercent || 0) >= 0;
                    
                    // Process chart data with validation
                    let chartData = commodity.chartData || [];
                    if (typeof chartData[0] === 'object' && chartData[0] && chartData[0].y !== undefined) {
                        // Data is in {x, y} format
                        chartData = chartData.map(item => item.y || 0);
                    }
                    
                    // Ensure we have valid numeric data
                    chartData = chartData.filter(val => val !== null && val !== undefined && !isNaN(val));
                    
                    // If no valid data, create empty chart
                    if (chartData.length === 0) {
                        chartData = [0];
                    }
                    
                    this.charts[canvasId] = new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: chartData.map((_, index) => index),
                            datasets: [{
                                data: chartData,
                                borderColor: isPositive ? '#10B981' : '#EF4444',
                                backgroundColor: isPositive ? 'rgba(16, 185, 129, 0.1)' : 'rgba(239, 68, 68, 0.1)',
                                borderWidth: 2,
                                fill: true,
                                tension: 0.4,
                                pointRadius: 0,
                                pointHoverRadius: 0,
                                hidden: false  // Explicitly set hidden property
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            animation: false,  // Disable animations to prevent errors
                            plugins: {
                                legend: { 
                                    display: false 
                                },
                                tooltip: { 
                                    enabled: false 
                                }
                            },
                            scales: {
                                x: { 
                                    display: false,
                                    grid: { display: false }
                                },
                                y: { 
                                    display: false,
                                    grid: { display: false }
                                }
                            },
                            interaction: { 
                                intersect: false,
                                mode: 'nearest'
                            },
                            elements: { 
                                point: { radius: 0 } 
                            }
                        }
                    });
                },

                selectCommodity(commodity) {
                    window.dispatchEvent(new CustomEvent('commodity-selected', {
                        detail: commodity
                    }));
                },

                loadMore() {
                    if (!this.showingAll) {
                        // Load more commodities (+12)
                        this.displayCount += 12;
                        if (this.displayCount >= this.totalCommodities) {
                            this.displayCount = this.totalCommodities;
                            this.showingAll = true;
                        }
                    } else {
                        // Reset to show only 12
                        this.displayCount = 12;
                        this.showingAll = false;
                    }
                    this.updateLoadMoreStatus();
                    
                    // Re-render charts for newly visible cards
                    this.$nextTick(() => {
                        this.initializeCharts();
                    });
                },

                updateLoadMoreStatus() {
                    // Always show load more button unless there are no commodities
                    this.canLoadMore = this.totalCommodities > 0;
                },

                updateData(filters) {
                    this.currentFilters = filters;
                    // Reset display count when filters change
                    this.displayCount = 12;
                    this.showingAll = false;
                    this.loadCommoditiesData();
                },

                refreshData() {
                    // Reset display count when refreshing
                    this.displayCount = 12;
                    this.showingAll = false;
                    this.loadCommoditiesData();
                }
            }
        }

        function detailModal() {
            return {
                showModal: false,
                selectedCommodity: null,
                detailChart: null,

                init() {
                    window.addEventListener('commodity-selected', (e) => {
                        this.selectedCommodity = e.detail;
                        this.showModal = true;
                        this.$nextTick(() => {
                            this.createDetailChart();
                        });
                    });
                },

                closeModal() {
                    this.showModal = false;
                    if (this.detailChart) {
                        this.detailChart.destroy();
                        this.detailChart = null;
                    }
                },

                createDetailChart() {
                    const canvas = document.getElementById('detailChart');
                    if (!canvas || !this.selectedCommodity) return;

                    if (this.detailChart) {
                        this.detailChart.destroy();
                    }

                    const ctx = canvas.getContext('2d');
                    const isPositive = (this.selectedCommodity.changePercent || 0) >= 0;
                    
                    // Validate and process chart data
                    const chartData = this.selectedCommodity.chartData || [];
                    
                    // Use actual date labels from chartData
                    const labels = chartData.map((item, index) => {
                        if (item && item.x) {
                            // Convert YYYY-MM format to readable month/year
                            const [year, month] = item.x.split('-');
                            const monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 
                                              'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
                            return `${monthNames[parseInt(month) - 1]} ${year}`;
                        }
                        return `Month ${index + 1}`;
                    });
                    
                    const values = chartData.map(item => {
                        if (item && typeof item === 'object') {
                            return item.y || item.value || 0;
                        }
                        return item || 0;
                    });

                    this.detailChart = new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: labels,
                            datasets: [{
                                label: this.selectedCommodity.name,
                                data: values,
                                borderColor: isPositive ? '#10B981' : '#EF4444',
                                backgroundColor: isPositive ? 'rgba(16, 185, 129, 0.1)' : 'rgba(239, 68, 68, 0.1)',
                                borderWidth: 3,
                                fill: true,
                                tension: 0.4,
                                pointRadius: 4,
                                pointHoverRadius: 6,
                                pointBackgroundColor: isPositive ? '#10B981' : '#EF4444',
                                pointBorderColor: '#fff',
                                pointBorderWidth: 2,
                                hidden: false  // Explicitly set hidden property
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            animation: {
                                duration: 400
                            },
                            plugins: {
                                legend: { 
                                    display: false 
                                },
                                tooltip: {
                                    mode: 'index',
                                    intersect: false,
                                    callbacks: {
                                        label: (context) => {
                                            return `${this.selectedCommodity.name}: ${this.formatValue(context.parsed.y, this.selectedCommodity.unit)}`;
                                        }
                                    }
                                }
                            },
                            scales: {
                                x: {
                                    grid: {
                                        display: false
                                    }
                                },
                                y: {
                                    beginAtZero: false,
                                    ticks: {
                                        callback: (value) => {
                                            return new Intl.NumberFormat('id-ID').format(value);
                                        }
                                    }
                                }
                            },
                            interaction: {
                                intersect: false,
                                mode: 'index'
                            }
                        }
                    });
                },

                formatValue(value, unit) {
                    return new Intl.NumberFormat('id-ID').format(Math.round(value)) + ' ' + unit;
                }
            }
        }
    </script>
</x-layouts.landing>