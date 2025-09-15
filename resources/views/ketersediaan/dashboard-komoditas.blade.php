<x-layouts.landing title="Dashboard Monitoring Komoditas">
    <!-- Include Chart.js only -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <div class="py-8 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-6">
                <div class="flex justify-between items-center" x-data="{ currentTime: new Date().toLocaleString('id-ID') }" x-init="setInterval(() => { currentTime = new Date().toLocaleString('id-ID') }, 60000)">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">Dashboard Monitoring Komoditas</h1>
                        <p class="text-gray-600">Real-time monitoring harga dan tren komoditas pangan Indonesia</p>
                    </div>
                    <div class="text-right">
                        <div class="text-sm text-gray-500">Last Updated</div>
                        <div class="font-semibold text-gray-900" x-text="currentTime"></div>
                    </div>
                </div>
            </div>

            <!-- Market Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6" x-data="dashboardData()">
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-600">Total Komoditas</p>
                            <p class="text-2xl font-bold text-gray-900">247</p>
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
                            <p class="text-2xl font-bold text-green-600">156</p>
                            <p class="text-xs text-green-600">+63.2%</p>
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
                            <p class="text-2xl font-bold text-red-600">91</p>
                            <p class="text-xs text-red-600">-36.8%</p>
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
                            <p class="text-2xl font-bold text-yellow-600">23</p>
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
                            <option value="1M">1 Bulan</option>
                            <option value="3M">3 Bulan</option>
                            <option value="6M">6 Bulan</option>
                            <option value="1Y">1 Tahun</option>
                            <option value="3Y">3 Tahun</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kelompok</label>
                        <select x-model="selectedGroup" @change="updateView()"
                                class="border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Semua Kelompok</option>
                            <option value="01">Padi-padian</option>
                            <option value="02">Makanan Berpati</option>
                            <option value="03">Gula</option>
                            <option value="04">Buah-buahan</option>
                            <option value="05">Sayur-sayuran</option>
                            <option value="06">Daging</option>
                            <option value="07">Telur</option>
                            <option value="08">Susu</option>
                            <option value="09">Minyak-Lemak</option>
                            <option value="10">Ikan</option>
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
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                    <template x-for="(commodity, index) in filteredCommodities" :key="`commodity-${index}`">
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 hover:shadow-md transition-shadow cursor-pointer" @click="selectCommodity(commodity)">
                            <!-- Card Header -->
                            <div class="flex items-center justify-between mb-3">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-8 w-8">
                                        <div class="h-8 w-8 rounded-full bg-gray-100 flex items-center justify-center text-lg"
                                             x-text="getCommodityEmoji(commodity?.group || '01')">
                                        </div>
                                    </div>
                                    <div class="ml-2">
                                        <h3 class="font-medium text-gray-900 text-sm" x-text="commodity?.name || 'Loading...'"></h3>
                                        <p class="text-xs text-gray-500" x-text="commodity?.groupName || ''"></p>
                                    </div>
                                </div>
                                <div class="text-xs text-gray-400" x-text="commodity?.lastUpdate || '-'"></div>
                            </div>

                            <!-- Price Info -->
                            <div class="mb-3">
                                <div class="text-lg font-bold text-gray-900" x-text="formatValue(commodity?.currentValue || 0, commodity?.unit || 'Rp/Kg')"></div>
                                <div class="flex items-center space-x-2 mt-1">
                                    <span class="text-sm font-medium" 
                                          :class="(commodity?.change || 0) >= 0 ? 'text-green-600' : 'text-red-600'"
                                          x-text="((commodity?.change || 0) >= 0 ? '+' : '') + formatValue(commodity?.change || 0, commodity?.unit || 'Rp/Kg')">
                                    </span>
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium"
                                          :class="(commodity?.changePercent || 0) >= 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20"
                                             :class="(commodity?.changePercent || 0) >= 0 ? 'transform rotate-0' : 'transform rotate-180'">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                        </svg>
                                        <span x-text="Math.abs(commodity?.changePercent || 0).toFixed(2) + '%'"></span>
                                    </span>
                                </div>
                            </div>

                            <!-- Mini Chart -->
                            <div class="h-16 w-full">
                                <canvas :id="'chart-' + (commodity?.id || index)" class="w-full h-16" width="200" height="64"></canvas>
                            </div>
                        </div>
                    </template>
                </div>

                <!-- Debug Info (Temporary) -->
                <div class="px-6 py-4 bg-gray-50 text-xs text-gray-600" x-show="filteredCommodities.length === 0">
                    <p>Debug: No commodities found. Total commodities: <span x-text="commodities.length"></span></p>
                    <p>Filtered commodities: <span x-text="filteredCommodities.length"></span></p>
                    <p>Search query: "<span x-text="searchQuery"></span>"</p>
                </div>

                <!-- Load More Button -->
                <div class="px-6 py-4 text-center">
                    <button @click="loadMore()" 
                            x-show="canLoadMore"
                            class="bg-white p-4 rounded-lg border text-blue-600 hover:text-blue-800 font-medium text-sm">
                        Load More Commodities
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
        function dashboardData() {
            return {
                // Dashboard summary data can be implemented here
            }
        }

        function filterControls() {
            return {
                selectedMetric: 'harga',
                selectedPeriod: '3M',
                selectedGroup: '',
                
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
                displayCount: 20,
                charts: {},

                init() {
                    this.loadSampleData();
                    this.filteredCommodities = this.commodities.slice(0, this.displayCount);
                    
                    // Debug: Log data to console
                    console.log('Commodities loaded:', this.commodities.length);
                    console.log('Sample commodity:', this.commodities[0]);
                    console.log('Filtered commodities:', this.filteredCommodities.length);
                    console.log('Sample filtered commodity:', this.filteredCommodities[0]);
                    
                    // Force refresh in case of reactivity issues
                    setTimeout(() => {
                        this.filteredCommodities = [...this.commodities.slice(0, this.displayCount)];
                        console.log('Force refresh, filtered commodities:', this.filteredCommodities.length);
                    }, 100);
                    
                    this.$nextTick(() => {
                        this.initializeCharts();
                    });

                    // Listen for filter changes
                    window.addEventListener('filter-changed', (e) => {
                        this.updateData(e.detail);
                    });

                    window.addEventListener('refresh-data', () => {
                        this.refreshData();
                    });
                },

                loadSampleData() {
                    const groupNames = {
                        '01': 'Padi-padian',
                        '02': 'Makanan Berpati', 
                        '03': 'Gula',
                        '04': 'Buah-buahan',
                        '05': 'Sayur-sayuran',
                        '06': 'Daging',
                        '07': 'Telur',
                        '08': 'Susu',
                        '09': 'Minyak-Lemak',
                        '10': 'Ikan'
                    };

                    const sampleCommodities = [
                        { id: 1, name: 'Beras Premium', group: '01', groupName: groupNames['01'], currentValue: 15500, change: 250, changePercent: 1.64, unit: 'Rp/kg', lastUpdate: '2 min ago' },
                        { id: 2, name: 'Beras Medium', group: '01', groupName: groupNames['01'], currentValue: 12800, change: -150, changePercent: -1.16, unit: 'Rp/kg', lastUpdate: '5 min ago' },
                        { id: 3, name: 'Jagung Pipil', group: '01', groupName: groupNames['01'], currentValue: 4500, change: 75, changePercent: 1.69, unit: 'Rp/kg', lastUpdate: '1 min ago' },
                        { id: 4, name: 'Singkong', group: '02', groupName: groupNames['02'], currentValue: 3200, change: -50, changePercent: -1.54, unit: 'Rp/kg', lastUpdate: '3 min ago' },
                        { id: 5, name: 'Ubi Jalar', group: '02', groupName: groupNames['02'], currentValue: 4800, change: 120, changePercent: 2.56, unit: 'Rp/kg', lastUpdate: '4 min ago' },
                        { id: 6, name: 'Gula Pasir', group: '03', groupName: groupNames['03'], currentValue: 14200, change: -300, changePercent: -2.07, unit: 'Rp/kg', lastUpdate: '1 min ago' },
                        { id: 7, name: 'Pisang Ambon', group: '04', groupName: groupNames['04'], currentValue: 8500, change: 150, changePercent: 1.80, unit: 'Rp/kg', lastUpdate: '6 min ago' },
                        { id: 8, name: 'Jeruk Manis', group: '04', groupName: groupNames['04'], currentValue: 12000, change: -200, changePercent: -1.64, unit: 'Rp/kg', lastUpdate: '2 min ago' },
                        { id: 9, name: 'Cabai Merah', group: '05', groupName: groupNames['05'], currentValue: 45000, change: 2500, changePercent: 5.88, unit: 'Rp/kg', lastUpdate: '1 min ago' },
                        { id: 10, name: 'Bawang Merah', group: '05', groupName: groupNames['05'], currentValue: 28000, change: -1500, changePercent: -5.08, unit: 'Rp/kg', lastUpdate: '3 min ago' },
                        { id: 11, name: 'Daging Sapi', group: '06', groupName: groupNames['06'], currentValue: 135000, change: 5000, changePercent: 3.85, unit: 'Rp/kg', lastUpdate: '5 min ago' },
                        { id: 12, name: 'Daging Ayam', group: '06', groupName: groupNames['06'], currentValue: 32000, change: -800, changePercent: -2.44, unit: 'Rp/kg', lastUpdate: '2 min ago' },
                        { id: 13, name: 'Telur Ayam', group: '07', groupName: groupNames['07'], currentValue: 26500, change: 750, changePercent: 2.91, unit: 'Rp/kg', lastUpdate: '4 min ago' },
                        { id: 14, name: 'Susu Segar', group: '08', groupName: groupNames['08'], currentValue: 9500, change: 200, changePercent: 2.15, unit: 'Rp/liter', lastUpdate: '7 min ago' },
                        { id: 15, name: 'Minyak Goreng', group: '09', groupName: groupNames['09'], currentValue: 16800, change: -400, changePercent: -2.33, unit: 'Rp/liter', lastUpdate: '3 min ago' },
                        { id: 16, name: 'Ikan Bandeng', group: '10', groupName: groupNames['10'], currentValue: 25000, change: 1000, changePercent: 4.17, unit: 'Rp/kg', lastUpdate: '6 min ago' }
                    ];

                    // Add calculated fields
                    this.commodities = sampleCommodities.map(c => ({
                        ...c,
                        yearHigh: c.currentValue * (1 + Math.random() * 0.3),
                        yearLow: c.currentValue * (1 - Math.random() * 0.25),
                        average: c.currentValue * (1 + (Math.random() - 0.5) * 0.1),
                        volatility: Math.random() * 15 + 5,
                        chartData: this.generateChartData(c.currentValue)
                    }));
                },

                generateChartData(baseValue) {
                    const data = [];
                    const points = 30;
                    let value = baseValue * 0.9;
                    
                    for (let i = 0; i < points; i++) {
                        value += (Math.random() - 0.5) * baseValue * 0.05;
                        data.push({
                            x: new Date(Date.now() - (points - i) * 24 * 60 * 60 * 1000),
                            y: value
                        });
                    }
                    return data;
                },

                filterCommodities() {
                    const query = this.searchQuery.toLowerCase();
                    this.filteredCommodities = this.commodities
                        .filter(c => 
                            c.name.toLowerCase().includes(query) || 
                            c.groupName.toLowerCase().includes(query)
                        )
                        .slice(0, this.displayCount);
                    
                    this.$nextTick(() => {
                        this.initializeCharts();
                    });
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
                        '10': '🐟'  // Ikan
                    };
                    return emojis[group] || '❓';
                },

                formatValue(value, unit) {
                    return new Intl.NumberFormat('id-ID').format(Math.round(value)) + ' ' + unit;
                },

                initializeCharts() {
                    this.filteredCommodities.forEach(commodity => {
                        this.createMiniChart(commodity, false); // Only card charts now
                    });
                },

                createMiniChart(commodity, isMobile) {
                    const canvasId = `chart-${commodity.id}`;
                    const canvas = document.getElementById(canvasId);
                    
                    if (!canvas) return;
                    
                    // Destroy existing chart if it exists
                    if (this.charts[canvasId]) {
                        this.charts[canvasId].destroy();
                    }

                    const ctx = canvas.getContext('2d');
                    const isPositive = commodity.changePercent >= 0;
                    
                    // Generate simple labels for chart data
                    const labels = commodity.chartData.map((_, index) => index);
                    const values = commodity.chartData.map(item => item.y || item.value || item);
                    
                    this.charts[canvasId] = new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: labels,
                            datasets: [{
                                data: values,
                                borderColor: isPositive ? '#10B981' : '#EF4444',
                                backgroundColor: isPositive ? 'rgba(16, 185, 129, 0.1)' : 'rgba(239, 68, 68, 0.1)',
                                borderWidth: 2,
                                fill: true,
                                tension: 0.4,
                                pointRadius: 0,
                                pointHoverRadius: 0
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: { display: false },
                                tooltip: { enabled: false }
                            },
                            scales: {
                                x: {
                                    display: false
                                },
                                y: {
                                    display: false
                                }
                            },
                            interaction: {
                                intersect: false
                            },
                            elements: {
                                point: {
                                    radius: 0
                                }
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
                    this.displayCount += 20;
                    this.filterCommodities();
                    this.canLoadMore = this.displayCount < this.commodities.length;
                },

                updateData(filters) {
                    // Simulate data update based on filters
                    this.loadSampleData();
                    this.filterCommodities();
                },

                refreshData() {
                    // Simulate data refresh
                    this.loadSampleData();
                    this.filterCommodities();
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
                    const isPositive = this.selectedCommodity.changePercent >= 0;
                    
                    // Generate simple labels for chart data
                    const labels = this.selectedCommodity.chartData.map((_, index) => `Day ${index + 1}`);
                    const values = this.selectedCommodity.chartData.map(item => item.y || item.value || item);

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
                                pointBorderWidth: 2
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: { display: false },
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