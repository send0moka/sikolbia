<div>
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-neutral-900 dark:text-white">Peta Daftar Alamat</h1>
        <p class="mt-1 text-sm text-neutral-600 dark:text-neutral-400">
            Visualisasi lokasi dinas pertanian di seluruh Indonesia
        </p>
    </div>

    <!-- Filters -->
    <div class="bg-white dark:!bg-neutral-800 rounded-lg shadow-sm border border-neutral-200 dark:border-neutral-700 p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <select wire:model.live="statusFilter" class="w-full px-3 py-2 border border-neutral-300 dark:border-neutral-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-neutral-700 dark:text-white">
                    <option value="">Semua Status</option>
                    @foreach($statusOptions as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <select wire:model.live="kategoriFilter" class="w-full px-3 py-2 border border-neutral-300 dark:border-neutral-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-neutral-700 dark:text-white">
                    <option value="">Semua Kategori</option>
                    @foreach($kategoriOptions as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <select wire:model.live="wilayahFilter" class="w-full px-3 py-2 border border-neutral-300 dark:border-neutral-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-neutral-700 dark:text-white">
                    <option value="">Semua Wilayah</option>
                    @foreach($wilayahOptions as $wilayah)
                        <option value="{{ $wilayah }}">{{ $wilayah }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="mt-4 flex justify-between items-center">
            <button wire:click="resetFilters" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-neutral-500 dark:text-neutral-400 bg-white dark:bg-neutral-800 hover:text-neutral-700 dark:hover:text-neutral-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition ease-in-out duration-150">
                Reset Filter
            </button>
            <div class="text-sm text-neutral-600 dark:text-neutral-400">
                Menampilkan {{ $alamats->count() }} lokasi dengan koordinat
            </div>
        </div>
    </div>

    <!-- Map Container -->
    <div class="bg-white dark:!bg-neutral-800 rounded-lg shadow-sm border border-neutral-200 dark:border-neutral-700 overflow-hidden">
        <div class="p-6">
            <div id="map" class="w-full h-96 rounded-lg" wire:ignore></div>
        </div>
    </div>

    <!-- Location List -->
    <div class="mt-6 bg-white dark:!bg-neutral-800 rounded-lg shadow-sm border border-neutral-200 dark:border-neutral-700">
        <div class="p-6">
            <h3 class="text-lg font-medium text-neutral-900 dark:text-white mb-4">Daftar Lokasi</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @forelse($alamats as $alamat)
                    <div class="border border-neutral-200 dark:border-neutral-700 rounded-lg p-4 hover:shadow-md transition-shadow cursor-pointer" wire:click="showInfo({{ $alamat->id }})">
                        <div class="flex items-start justify-between mb-2">
                            <h4 class="font-medium text-neutral-900 dark:text-white text-sm truncate">
                                {{ $alamat->nama_dinas }}
                            </h4>
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $alamat->status_badge }} ml-2">
                                {{ $alamat->status }}
                            </span>
                        </div>
                        <p class="text-sm text-neutral-600 dark:text-neutral-400 mb-2 truncate">
                            {{ $alamat->wilayah }}
                        </p>
                        <p class="text-xs text-neutral-500 dark:text-neutral-500 mb-3 line-clamp-2">
                            {{ $alamat->alamat }}
                        </p>
                        <div class="flex items-center justify-between text-xs text-neutral-400">
                            <div class="flex items-center">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1 1 11.314 0z" />
                                    <circle cx="12" cy="12" r="3" />
                                </svg>
                                {{ number_format($alamat->latitude, 4) }}, {{ number_format($alamat->longitude, 4) }}
                            </div>
                            @if($alamat->kategori)
                                <span class="bg-neutral-100 dark:bg-neutral-700 px-2 py-1 rounded text-xs">
                                    {{ $alamat->kategori }}
                                </span>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-12">
                        <svg class="w-12 h-12 mx-auto text-neutral-300 dark:text-neutral-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                        </svg>
                        <p class="text-lg font-medium text-neutral-600 dark:text-neutral-400">Tidak ada lokasi dengan koordinat</p>
                        <p class="text-sm text-neutral-500 dark:text-neutral-500">Tambahkan koordinat pada data alamat untuk menampilkan di peta</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Info Modal -->
    @if($showInfoModal)
    <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-neutral-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white dark:bg-neutral-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                @if($selectedAlamat)
                <div class="bg-white dark:bg-neutral-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <h3 class="text-lg font-medium text-neutral-900 dark:text-white mb-4">{{ $selectedAlamat->nama_dinas }}</h3>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">Wilayah</label>
                        <p class="mt-1 text-sm text-neutral-900 dark:text-white">{{ $selectedAlamat->wilayah }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">Alamat</label>
                        <p class="mt-1 text-sm text-neutral-900 dark:text-white">{{ $selectedAlamat->alamat }}</p>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        @if($selectedAlamat->telp)
                            <div>
                                <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">Telepon</label>
                                <p class="mt-1 text-sm text-neutral-900 dark:text-white">{{ $selectedAlamat->telp }}</p>
                            </div>
                        @endif

                        @if($selectedAlamat->faks)
                            <div>
                                <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">Faks</label>
                                <p class="mt-1 text-sm text-neutral-900 dark:text-white">{{ $selectedAlamat->faks }}</p>
                            </div>
                        @endif

                        @if($selectedAlamat->email)
                            <div>
                                <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">Email</label>
                                <p class="mt-1 text-sm text-neutral-900 dark:text-white">
                                    <a href="mailto:{{ $selectedAlamat->email }}" class="text-blue-600 dark:text-blue-400 hover:underline">
                                        {{ $selectedAlamat->email }}
                                    </a>
                                </p>
                            </div>
                        @endif

                        @if($selectedAlamat->website)
                            <div>
                                <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">Website</label>
                                <p class="mt-1 text-sm text-neutral-900 dark:text-white">
                                    <a href="{{ $selectedAlamat->website }}" target="_blank" class="text-blue-600 dark:text-blue-400 hover:underline">
                                        {{ $selectedAlamat->website }}
                                    </a>
                                </p>
                            </div>
                        @endif
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">Status</label>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $selectedAlamat->status_badge }} mt-1">
                                {{ $selectedAlamat->status }}
                            </span>
                        </div>

                        @if($selectedAlamat->kategori)
                            <div>
                                <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">Kategori</label>
                                <p class="mt-1 text-sm text-neutral-900 dark:text-white">{{ $selectedAlamat->kategori }}</p>
                            </div>
                        @endif
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">Koordinat</label>
                        <p class="mt-1 text-sm text-neutral-900 dark:text-white">
                            {{ number_format($selectedAlamat->latitude, 6) }}, {{ number_format($selectedAlamat->longitude, 6) }}
                        </p>
                    </div>

                    @if($selectedAlamat->posisi)
                        <div>
                            <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">Posisi</label>
                            <p class="mt-1 text-sm text-neutral-900 dark:text-white">{{ $selectedAlamat->posisi }}</p>
                        </div>
                    @endif

                    @if($selectedAlamat->gambar)
                        <div>
                            <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">Gambar</label>
                            <div class="mt-2">
                                <img src="{{ asset('storage/' . $selectedAlamat->gambar) }}" 
                                     alt="{{ $selectedAlamat->nama_dinas }}" 
                                     class="w-full max-w-sm h-48 object-cover rounded-lg shadow-sm border border-neutral-200 dark:border-neutral-600">
                            </div>
                        </div>
                    @endif

                    @if($selectedAlamat->keterangan)
                        <div>
                            <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">Keterangan</label>
                            <p class="mt-1 text-sm text-neutral-900 dark:text-white">{{ $selectedAlamat->keterangan }}</p>
                        </div>
                    @endif
                </div>
                </div>
                <div class="bg-neutral-50 dark:bg-neutral-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button wire:click="closeInfoModal" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Tutup
                    </button>
                </div>
                @endif
            </div>
        </div>
    </div>
    @endif

    @push('styles')
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    @endpush

    @push('scripts')
    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    
    <!-- Map Script -->
    <script>
        function initializeMap() {
            // Check if Leaflet is loaded
            if (typeof L === 'undefined') {
                // Wait for Leaflet to load and try again
                setTimeout(initializeMap, 100);
                return;
            }
            
            // Check if map container exists and is not already initialized
            const mapContainer = document.getElementById('map');
            if (!mapContainer || mapContainer._leaflet_id) {
                return;
            }

            const mapData = @json($mapData);
            
            // Initialize map centered on Indonesia
            const map = L.map('map').setView([-2.5489, 118.0149], 5);
            
            // Add OpenStreetMap tiles
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);
            
            // Custom marker icon
            const customIcon = L.divIcon({
                html: '<div class="bg-blue-600 w-4 h-4 rounded-full border-2 border-white shadow-lg"></div>',
                className: 'custom-marker',
                iconSize: [16, 16],
                iconAnchor: [8, 8]
            });
            
            // Add markers for each location
            const markers = [];
            mapData.forEach(function(location) {
                if (location.lat && location.lng) {
                    const marker = L.marker([location.lat, location.lng], { icon: customIcon })
                        .addTo(map)
                        .bindPopup(`
                            <div class="p-3 min-w-[250px] max-w-[300px]">
                                ${location.gambar ? `
                                    <div class="mb-3">
                                        <img src="${location.gambar}" alt="${location.title}" 
                                             class="w-full h-32 object-cover rounded-lg shadow-sm" 
                                             onerror="this.style.display='none'">
                                    </div>
                                ` : ''}
                                <h3 class="font-semibold text-sm mb-2">${location.title}</h3>
                                <p class="text-xs text-neutral-600 mb-1">${location.wilayah}</p>
                                <p class="text-xs text-neutral-500 mb-2">${location.alamat}</p>
                                <div class="flex justify-between items-center text-xs mb-2">
                                    <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded">${location.status}</span>
                                    ${location.kategori ? `<span class="bg-neutral-100 text-neutral-800 px-2 py-1 rounded">${location.kategori}</span>` : ''}
                                </div>
                                ${location.telp ? `<p class="text-xs mt-1"><strong>Telp:</strong> ${location.telp}</p>` : ''}
                                ${location.email ? `<p class="text-xs"><strong>Email:</strong> ${location.email}</p>` : ''}
                            </div>
                        `);
                    markers.push(marker);
                }
            });
            
            // Fit map to show all markers if there are any
            if (markers.length > 0) {
                const group = new L.featureGroup(markers);
                map.fitBounds(group.getBounds().pad(0.1));
            }
            
            // Listen for Livewire updates to refresh markers
            Livewire.on('mapUpdated', (event) => {
                const newMapData = event[0];
                // Clear existing markers
                markers.forEach(marker => map.removeLayer(marker));
                markers.length = 0;
                
                // Add new markers
                newMapData.forEach(function(location) {
                    if (location.lat && location.lng) {
                        const marker = L.marker([location.lat, location.lng], { icon: customIcon })
                            .addTo(map)
                            .bindPopup(`
                                <div class="p-3 min-w-[250px] max-w-[300px]">
                                    ${location.gambar ? `
                                        <div class="mb-3">
                                            <img src="${location.gambar}" alt="${location.title}" 
                                                 class="w-full h-32 object-cover rounded-lg shadow-sm" 
                                                 onerror="this.style.display='none'">
                                        </div>
                                    ` : ''}
                                    <h3 class="font-semibold text-sm mb-2">${location.title}</h3>
                                    <p class="text-xs text-neutral-600 mb-1">${location.wilayah}</p>
                                    <p class="text-xs text-neutral-500 mb-2">${location.alamat}</p>
                                    <div class="flex justify-between items-center text-xs mb-2">
                                        <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded">${location.status}</span>
                                        ${location.kategori ? `<span class="bg-neutral-100 text-neutral-800 px-2 py-1 rounded">${location.kategori}</span>` : ''}
                                    </div>
                                    ${location.telp ? `<p class="text-xs mt-1"><strong>Telp:</strong> ${location.telp}</p>` : ''}
                                    ${location.email ? `<p class="text-xs"><strong>Email:</strong> ${location.email}</p>` : ''}
                                </div>
                            `);
                        markers.push(marker);
                    }
                });
                
                // Fit map to show all markers
                if (markers.length > 0) {
                    const group = new L.featureGroup(markers);
                    map.fitBounds(group.getBounds().pad(0.1));
                }
            });
        }

        // Initialize map on DOM ready (for page refresh)
        document.addEventListener('DOMContentLoaded', initializeMap);
        
        // Initialize map on Livewire navigation (for SPA navigation)
        document.addEventListener('livewire:navigated', function() {
            // Add small delay to ensure Leaflet script is loaded
            setTimeout(initializeMap, 200);
        });
        
        // Initialize map immediately if DOM is already loaded
        if (document.readyState === 'loading') {
            // DOM is still loading
        } else {
            // DOM is already loaded
            setTimeout(initializeMap, 100);
        }
    </script>
    @endpush
</div>
