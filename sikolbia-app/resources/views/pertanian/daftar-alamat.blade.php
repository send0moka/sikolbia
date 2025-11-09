<x-layouts.landing title="Daftar Alamat Pertanian">
    <!-- Leaflet.js for interactive maps -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    
    <style>
        #alamatMap {
            height: 500px;
            width: 100%;
            border-radius: 12px;
            margin-bottom: 2rem;
            min-height: 400px; /* Ensure minimum height */
            position: relative; /* Ensure proper positioning */
            z-index: 10; /* Lower than header z-50 */
        }
        .search-input { border-radius: 8px; border: 1px solid #ccc; padding: 8px 12px; width: 100%; }
        .alamat-table th, .alamat-table td { font-size: 14px; padding: 8px 10px; }
        .alamat-table tr:hover { background: #f3f4f6; }
        
        /* Custom popup styling */
        .custom-popup .leaflet-popup-content-wrapper {
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }
        .custom-popup .leaflet-popup-content {
            margin: 0;
            border-radius: 12px;
        }
        .custom-popup .leaflet-popup-tip {
            background-color: white;
        }

        /* Ensure map container is visible */
        .leaflet-container {
            background: #f8f9fa !important;
        }

        /* Ensure popup appears above map but below header */
        .leaflet-popup-pane {
            z-index: 20;
        }

        /* Ensure all Leaflet controls are properly layered */
        .leaflet-control-container {
            z-index: 15;
        }

        .leaflet-tile-pane {
            z-index: 5;
        }

        .leaflet-overlay-pane {
            z-index: 10;
        }

        .leaflet-shadow-pane {
            z-index: 12;
        }

        .leaflet-marker-pane {
            z-index: 13;
        }
    </style>    <div class="py-12 bg-white" x-data="daftarAlamat()" x-init="init()">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-8">
                <h1 class="text-3xl md:text-4xl font-bold text-neutral-900 mb-2">Daftar Alamat Pertanian</h1>
                <p class="text-lg text-neutral-600">Lihat dan telusuri alamat lokasi pertanian, kantor, dan fasilitas terkait secara interaktif.</p>
            </div>

            <!-- Summary Stats -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-green-50 rounded-lg p-4 text-center">
                    <div class="text-2xl font-bold text-green-700" id="totalAlamat">0</div>
                    <div class="text-sm text-green-900">Total Alamat</div>
                </div>
                <div class="bg-blue-50 rounded-lg p-4 text-center">
                    <div class="text-2xl font-bold text-blue-700" id="totalRegion">0</div>
                    <div class="text-sm text-blue-900">Wilayah Terdaftar</div>
                </div>
                <div class="bg-yellow-50 rounded-lg p-4 text-center">
                    <div class="text-2xl font-bold text-yellow-700" id="totalJenis">0</div>
                    <div class="text-sm text-yellow-900">Jenis Instansi</div>
                </div>
            </div>

            <!-- Search & Filter -->
            <div x-show="!isLoading" class="mb-6 flex flex-col md:flex-row gap-4 items-center">
                <input type="text" class="search-input" placeholder="Cari alamat, nama, atau wilayah..." x-model="searchQuery" @input="filterAlamat()">
                
                <select class="search-input md:w-48" x-model="selectedProvinsi" @change="filterAlamat()">
                    <option value="">Semua Provinsi</option>
                    <template x-for="provinsi in provinsiList" :key="provinsi">
                        <option :value="provinsi" x-text="provinsi"></option>
                    </template>
                </select>
                
                <select class="search-input md:w-48" x-model="selectedKabupaten" @change="filterAlamat()">
                    <option value="">Semua Kabupaten/Kota</option>
                    <template x-for="kabupaten in kabupatenList" :key="kabupaten">
                        <option :value="kabupaten" x-text="kabupaten"></option>
                    </template>
                </select>
                
                <select class="search-input md:w-48" x-model="selectedJenis" @change="filterAlamat()">
                    <option value="">Semua Jenis Instansi</option>
                    <template x-for="jenis in jenisLokasi" :key="jenis">
                        <option :value="jenis" x-text="jenis"></option>
                    </template>
                </select>
            </div>

            <!-- Loading State -->
            <div x-show="isLoading" class="mb-6 flex items-center justify-center py-12">
                <div class="flex items-center space-x-3">
                    <svg class="animate-spin h-8 w-8 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span class="text-lg text-gray-600">Memuat data...</span>
                </div>
            </div>

            <!-- Error State -->
            <div x-show="error && !isLoading" class="mb-6 bg-red-50 border border-red-200 rounded-lg p-6">
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-red-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                    <div>
                        <h3 class="text-lg font-medium text-red-800">Terjadi Kesalahan</h3>
                        <p class="text-red-700" x-text="error"></p>
                        <button class="mt-2 px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 transition-colors" @click="init()">Coba Lagi</button>
                    </div>
                </div>
            </div>

            <!-- Active Filters Info -->
            <div x-show="hasActiveFilters()" class="mb-4 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-2">
                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                        </svg>
                        <span class="text-sm text-blue-800 font-medium">Filter aktif:</span>
                        <template x-if="searchQuery">
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-blue-100 text-blue-800">
                                Pencarian: "<span x-text="searchQuery"></span>"
                            </span>
                        </template>
                        <template x-if="selectedProvinsi">
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-blue-100 text-blue-800">
                                Provinsi: <span x-text="selectedProvinsi"></span>
                            </span>
                        </template>
                        <template x-if="selectedKabupaten">
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-blue-100 text-blue-800">
                                Kabupaten/Kota: <span x-text="selectedKabupaten"></span>
                            </span>
                        </template>
                        <template x-if="selectedJenis">
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-blue-100 text-blue-800">
                                Jenis Instansi: <span x-text="selectedJenis"></span>
                            </span>
                        </template>
                    </div>
                    <span class="text-sm text-blue-600" x-text="filteredAlamat.length + ' hasil ditemukan'"></span>
                </div>
            </div>

            <!-- Interactive Map -->
            <div x-show="!isLoading && !error" id="alamatMap"></div>

            <!-- Loading Map -->
            <div x-show="isLoading" class="bg-gray-200 rounded-lg h-80 flex items-center justify-center mb-6">
                <div class="text-center">
                    <svg class="animate-spin h-8 w-8 text-gray-600 mx-auto mb-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <p class="text-gray-600">Memuat peta...</p>
                </div>
            </div>

            <!-- Table of Addresses -->
            <div x-show="!isLoading && !error" class="overflow-x-auto">
                <table class="alamat-table w-full bg-white rounded-lg shadow">
                    <thead class="bg-neutral-100">
                        <tr>
                            <th>No</th>
                            <th>Nama Lokasi</th>
                            <th>Gambar</th>
                            <th>Alamat</th>
                            <th>Provinsi</th>
                            <th>Kabupaten/Kota</th>
                            <th>Jenis Instansi</th>
                            <th>Koordinat</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <template x-for="(alamat, idx) in filteredAlamat" :key="alamat.id">
                            <tr>
                                <td x-text="idx + 1"></td>
                                <td x-text="alamat.nama"></td>
                                <td>
                                    <template x-if="alamat.gambar">
                                        <img :src="alamat.gambar" :alt="alamat.nama" 
                                             class="w-16 h-12 object-cover rounded shadow-sm"
                                             onerror="this.style.display='none'">
                                    </template>
                                    <template x-if="!alamat.gambar">
                                        <span class="text-xs text-gray-400">Tidak ada gambar</span>
                                    </template>
                                </td>
                                <td x-text="alamat.alamat"></td>
                                <td x-text="alamat.provinsi"></td>
                                <td x-text="alamat.kabupaten_kota"></td>
                                <td x-text="alamat.jenis"></td>
                                <td>
                                    <span x-text="alamat.lat + ', ' + alamat.lng"></span>
                                </td>
                                <td>
                                    <button class="px-2 py-1 bg-blue-600 text-white rounded hover:bg-blue-700" @click="focusMap(alamat)">Lihat di Peta</button>
                                </td>
                            </tr>
                        </template>
                        <template x-if="filteredAlamat.length === 0">
                            <tr>
                                <td colspan="9" class="text-center py-8 text-gray-500">
                                    <div class="flex flex-col items-center">
                                        <svg class="w-12 h-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 12h6m-6-4h6m2 5.291A7.962 7.962 0 0112 15c-2.34 0-4.467-.881-6.08-2.33"></path>
                                        </svg>
                                        <p class="text-lg font-medium">Tidak ada data ditemukan</p>
                                        <p class="text-sm">Coba ubah kriteria pencarian atau filter</p>
                                    </div>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>

            <!-- Loading Table -->
            <div x-show="isLoading" class="overflow-x-auto">
                <table class="alamat-table w-full bg-white rounded-lg shadow">
                    <thead class="bg-neutral-100">
                        <tr>
                            <th>No</th>
                            <th>Nama Lokasi</th>
                            <th>Gambar</th>
                            <th>Alamat</th>
                            <th>Provinsi</th>
                            <th>Kabupaten/Kota</th>
                            <th>Jenis Instansi</th>
                            <th>Koordinat</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="9" class="text-center py-12">
                                <div class="flex items-center justify-center">
                                    <svg class="animate-spin h-8 w-8 text-blue-600 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    <span class="text-lg text-gray-600">Memuat data tabel...</span>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        // Ensure DOM is fully loaded before initializing Alpine
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM fully loaded, initializing Alpine components...');
        });

        // Also listen for window load to ensure all resources are loaded
        window.addEventListener('load', function() {
            console.log('Window fully loaded, all resources ready');
        });

        document.addEventListener('alpine:init', () => {
            Alpine.data('daftarAlamat', () => ({
                alamatList: [],
                provinsiList: [],
                kabupatenList: [],
                jenisLokasi: [],
                searchQuery: '',
                selectedProvinsi: '',
                selectedKabupaten: '',
                selectedJenis: '',
                filteredAlamat: [],
                map: null,
                markers: [],
                isLoading: false,
                error: null,
                mapInitialized: false,
                dataInitialized: false,
                initPromise: null,
                searchTimeout: null,

                async init() {
                    // Prevent multiple initialization
                    if (this.dataInitialized) {
                        console.log('Data already initialized, skipping...');
                        return;
                    }

                    // Prevent concurrent initialization calls
                    if (this.initPromise) {
                        console.log('Init already in progress, waiting...');
                        await this.initPromise;
                        return;
                    }

                    this.initPromise = this._init();
                    await this.initPromise;
                    this.initPromise = null;
                },

                async _init() {
                    try {
                        this.isLoading = true;
                        console.log('Starting initialization, isLoading set to true');
                        this.error = null;

                        // Add timeout to prevent infinite loading
                        const timeoutPromise = new Promise((_, reject) => {
                            setTimeout(() => reject(new Error('Request timeout')), 10000);
                        });

                        // Fetch data from public API endpoint
                        const response = await Promise.race([
                            fetch('/api/daftar-alamat/data', {
                                method: 'GET',
                                headers: {
                                    'Accept': 'application/json',
                                    'Content-Type': 'application/json'
                                }
                            }),
                            timeoutPromise
                        ]);

                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}, message: ${response.statusText}`);
                        }

                        const data = await response.json();
                        console.log('Raw API response:', response);
                        console.log('Parsed data:', data);

                        // Validate data
                        if (!Array.isArray(data)) {
                            throw new Error('Data yang diterima bukan array');
                        }

                        if (data.length === 0) {
                            throw new Error('Data kosong diterima dari server');
                        }

                        this.alamatList = data;
                        this.provinsiList = [...new Set(data.map(a => a.provinsi))].filter(p => p).sort();
                        this.kabupatenList = [...new Set(data.map(a => a.kabupaten_kota))].filter(k => k).sort();
                        
                        // Extract individual jenis instansi from arrays
                        const allJenisInstansi = [];
                        data.forEach(item => {
                            if (item.jenis_instansi && Array.isArray(item.jenis_instansi)) {
                                allJenisInstansi.push(...item.jenis_instansi);
                            }
                        });
                        this.jenisLokasi = [...new Set(allJenisInstansi)].sort();
                        
                        this.filteredAlamat = [...data]; // Initialize filtered list

                        console.log('Processed data:');
                        console.log('- Total records:', data.length);
                        console.log('- Provinsi:', this.provinsiList);
                        console.log('- Kabupaten/Kota:', this.kabupatenList);
                        console.log('- Jenis Instansi:', this.jenisLokasi);
                        console.log('- Sample record:', data[0]);

                        // Validate processed data
                        if (this.provinsiList.length === 0) {
                            console.warn('Warning: No provinsi found in data');
                        }
                        if (this.kabupatenList.length === 0) {
                            console.warn('Warning: No kabupaten/kota found in data');
                        }
                        if (this.jenisLokasi.length === 0) {
                            console.warn('Warning: No jenis instansi found in data');
                        }

                        this.$nextTick(() => {
                            this.initMap();
                        });
                        this.dataInitialized = true;
                        this.updateStats();
                        console.log('Initialization complete');

                        // Add window resize listener to handle map resizing
                        window.addEventListener('resize', () => {
                            if (this.map && this.mapInitialized) {
                                setTimeout(() => {
                                    this.map.invalidateSize();
                                }, 100);
                            }
                        });
                    } catch (error) {
                        console.error('Error loading data:', error);
                        this.error = error.message;

                        // Provide fallback data for development
                        console.log('Using fallback data due to error');
                        this.alamatList = [
                            {
                                id: 1,
                                nama: 'Kantor Pusat Pertanian',
                                alamat: 'Jl. Harsono RM. No. 3, Ragunan, Jakarta Selatan',
                                provinsi: 'Daerah Khusus Jakarta',
                                kabupaten_kota: 'Jakarta Selatan',
                                wilayah: 'Jakarta Selatan, Daerah Khusus Jakarta',
                                jenis: 'Dinas Pertanian',
                                jenis_instansi: ['Dinas Pertanian'],
                                lat: -6.3056,
                                lng: 106.8200
                            },
                            {
                                id: 2,
                                nama: 'Balai Penelitian Tanaman Padi',
                                alamat: 'Jl. Raya 9, Sukamandi, Subang',
                                provinsi: 'Jawa Barat',
                                kabupaten_kota: 'Kabupaten Subang',
                                wilayah: 'Kabupaten Subang, Jawa Barat',
                                jenis: 'Balai Pengkajian Teknologi Pertanian',
                                jenis_instansi: ['Balai Pengkajian Teknologi Pertanian'],
                                lat: -6.5833,
                                lng: 107.5833
                            }
                        ];
                        this.provinsiList = [...new Set(this.alamatList.map(a => a.provinsi))].filter(p => p).sort();
                        this.kabupatenList = [...new Set(this.alamatList.map(a => a.kabupaten_kota))].filter(k => k).sort();
                        
                        // Extract individual jenis instansi from arrays for fallback data
                        const allJenisInstansi = [];
                        this.alamatList.forEach(item => {
                            if (item.jenis_instansi && Array.isArray(item.jenis_instansi)) {
                                allJenisInstansi.push(...item.jenis_instansi);
                            }
                        });
                        this.jenisLokasi = [...new Set(allJenisInstansi)].sort();
                        
                        this.filteredAlamat = [...this.alamatList];

                        this.dataInitialized = true;
                        this.updateStats();
                        this.$nextTick(() => {
                            this.initMap();
                        });
                    } finally {
                        // Small delay to ensure all operations are complete
                        setTimeout(() => {
                            this.isLoading = false;
                            console.log('Loading state set to false');
                        }, 100);
                    }
                },

                updateStats() {
                    const totalAlamat = this.filteredAlamat.length;
                    const uniqueRegions = [...new Set(this.filteredAlamat.map(a => a.wilayah))];
                    const uniqueJenis = [...new Set(this.filteredAlamat.map(a => a.jenis))];

                    console.log('Updating stats:', {
                        totalAlamat,
                        totalRegion: uniqueRegions.length,
                        totalJenis: uniqueJenis.length
                    });

                    document.getElementById('totalAlamat').textContent = totalAlamat;
                    document.getElementById('totalRegion').textContent = uniqueRegions.length;
                    document.getElementById('totalJenis').textContent = uniqueJenis.length;
                },

                filterAlamat() {
                    // Prevent filtering if data is not initialized yet
                    if (!this.dataInitialized || !this.alamatList.length) {
                        return;
                    }

                    // Clear existing timeout
                    if (this.searchTimeout) {
                        clearTimeout(this.searchTimeout);
                    }

                    // Debounce search input to avoid excessive filtering
                    this.searchTimeout = setTimeout(() => {
                        this.filteredAlamat = this.alamatList.filter(a => {
                            const matchQuery = this.searchQuery === '' ||
                                a.nama.toLowerCase().includes(this.searchQuery.toLowerCase()) ||
                                a.alamat.toLowerCase().includes(this.searchQuery.toLowerCase()) ||
                                a.provinsi.toLowerCase().includes(this.searchQuery.toLowerCase()) ||
                                a.kabupaten_kota.toLowerCase().includes(this.searchQuery.toLowerCase()) ||
                                a.jenis.toLowerCase().includes(this.searchQuery.toLowerCase());
                            const matchProvinsi = this.selectedProvinsi === '' || a.provinsi === this.selectedProvinsi;
                            const matchKabupaten = this.selectedKabupaten === '' || a.kabupaten_kota === this.selectedKabupaten;
                            
                            // Check if selected jenis exists in jenis_instansi array
                            const matchJenis = this.selectedJenis === '' || 
                                (a.jenis_instansi && Array.isArray(a.jenis_instansi) && a.jenis_instansi.includes(this.selectedJenis));
                            
                            return matchQuery && matchProvinsi && matchKabupaten && matchJenis;
                        });

                        this.updateMapMarkers();
                        this.updateStats();

                        // Invalidate map size after filtering to ensure proper display
                        if (this.map) {
                            setTimeout(() => {
                                this.map.invalidateSize();
                            }, 50);
                        }
                    }, 300); // 300ms debounce
                },

                initMap() {
                    // Prevent multiple map initialization
                    if (this.mapInitialized) {
                        console.log('Map already initialized, skipping...');
                        return;
                    }

                    console.log('Initializing map...');

                    // Wait for DOM to be fully ready and container to have proper dimensions
                    const initMapWithRetry = () => {
                        const mapContainer = document.getElementById('alamatMap');

                        if (!mapContainer) {
                            console.log('Map container not found, retrying in 100ms...');
                            setTimeout(initMapWithRetry, 100);
                            return;
                        }

                        // Check if container has proper dimensions
                        const rect = mapContainer.getBoundingClientRect();
                        if (rect.width === 0 || rect.height === 0) {
                            console.log('Map container has no dimensions, retrying in 100ms...');
                            setTimeout(initMapWithRetry, 100);
                            return;
                        }

                        console.log('Map container dimensions:', rect.width, 'x', rect.height);

                        // Initialize the map
                        this.map = L.map('alamatMap').setView([-2.5, 118], 5.2);
                        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                            attribution: '© OpenStreetMap contributors'
                        }).addTo(this.map);

                        // Add event listeners to handle zoom and pan events
                        this.map.on('zoomstart', () => {
                            // Close any open popups before zoom
                            this.map.closePopup();
                        });

                        this.map.on('movestart', () => {
                            // Close any open popups before move
                            this.map.closePopup();
                        });

                        this.mapInitialized = true;
                        console.log('Map initialized successfully');

                        // Force map to recalculate its size after initialization
                        setTimeout(() => {
                            if (this.map) {
                                this.map.invalidateSize();
                                console.log('Map size invalidated');
                            }
                        }, 100);

                        this.updateMapMarkers();
                    };

                    // Use setTimeout to ensure DOM is ready
                    setTimeout(initMapWithRetry, 50);
                },

                updateMapMarkers() {
                    if (!this.map || !this.mapInitialized) {
                        console.log('Map not initialized yet, skipping marker update');
                        return;
                    }

                    console.log('Updating map markers for', this.filteredAlamat.length, 'locations');

                    // Close any open popups first
                    this.map.closePopup();

                    // Remove old markers with proper cleanup
                    this.markers.forEach(m => {
                        if (m.getPopup()) {
                            m.unbindPopup();
                        }
                        this.map.removeLayer(m);
                    });
                    this.markers = [];

                    this.filteredAlamat.forEach(alamat => {
                        if (alamat.lat && alamat.lng) {
                            // Create popup content with image if available
                            let popupContent = `<div class="max-w-sm p-4">`;
                            
                            if (alamat.gambar) {
                                popupContent += `
                                    <div class="mb-1">
                                        <img src="${alamat.gambar}" alt="${alamat.nama}" 
                                             class="w-full h-32 object-cover rounded-lg shadow-md"
                                             onerror="this.style.display='none'">
                                    </div>
                                `;
                            }
                            
                            popupContent += `
                                <div class="">
                                    <h3 class="p-0 font-bold text-gray-900 text-sm">${alamat.nama}</h3>
                                    <p class="!m-0 text-xs text-gray-600">${alamat.alamat}</p>
                                    <p class="!m-0 text-xs text-blue-600 font-medium">${alamat.provinsi}</p>
                                    <p class="!m-0 text-xs text-blue-600 font-medium">${alamat.kabupaten_kota}</p>
                                    <p class="!m-0 text-xs text-green-600">${alamat.jenis}</p>
                            `;
                            
                            if (alamat.telp) {
                                popupContent += `<p class="!m-0 text-xs text-gray-500">📞 ${alamat.telp}</p>`;
                            }
                            
                            if (alamat.email) {
                                popupContent += `<p class="!m-0 text-xs text-gray-500">✉️ ${alamat.email}</p>`;
                            }
                            
                            if (alamat.website) {
                                popupContent += `<p class="!m-0 text-xs text-blue-500"><a href="${alamat.website}" target="_blank" class="hover:underline">🌐 Website</a></p>`;
                            }
                            
                            popupContent += `</div></div>`;

                            const marker = L.marker([alamat.lat, alamat.lng])
                                .bindPopup(popupContent, {
                                    maxWidth: 300,
                                    className: 'custom-popup',
                                    closeOnClick: true,
                                    autoClose: true,
                                    autoPan: false
                                });
                            
                            // Add marker to map
                            marker.addTo(this.map);
                            this.markers.push(marker);
                        }
                    });

                    console.log('Map markers updated:', this.markers.length, 'markers added');

                    // Invalidate map size after adding markers to ensure proper rendering
                    if (this.map) {
                        setTimeout(() => {
                            this.map.invalidateSize();
                        }, 50);
                    }
                },

                focusMap(alamat) {
                    if (this.map && alamat.lat && alamat.lng) {
                        // Close any open popups first
                        this.map.closePopup();
                        
                        // Set view with smooth transition
                        this.map.setView([alamat.lat, alamat.lng], 14, {
                            animate: true,
                            duration: 1
                        });
                        
                        // Find and open the corresponding marker popup
                        setTimeout(() => {
                            this.markers.forEach(m => {
                                if (m.getLatLng().lat === alamat.lat && m.getLatLng().lng === alamat.lng) {
                                    m.openPopup();
                                }
                            });
                        }, 500); // Wait for zoom animation to complete
                    }
                },

                resetFilters() {
                    console.log('=== RESET FILTERS ===');
                    this.searchQuery = '';
                    this.selectedProvinsi = '';
                    this.selectedKabupaten = '';
                    this.selectedJenis = '';
                    this.filterAlamat();
                },

                hasActiveFilters() {
                    return this.searchQuery !== '' || this.selectedProvinsi !== '' || this.selectedKabupaten !== '' || this.selectedJenis !== '';
                }
            }));
        });
    </script>
</x-layouts.landing>