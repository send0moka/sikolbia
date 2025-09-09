<div>

    <!-- Header -->
    <div class="mb-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-semibold text-neutral-900 dark:text-white">Peta Lahan</h1>
                <p class="mt-1 text-sm text-neutral-600 dark:text-neutral-400">
                    Visualisasi data lahan berdasarkan wilayah
                </p>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="mb-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <div>
            <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">Topik Lahan</label>
            <select wire:model.live="selectedTopik" class="w-full text-sm rounded-md border-neutral-300 dark:border-neutral-600 dark:bg-neutral-800 dark:text-neutral-200 focus:ring-accent focus:border-accent">
                <option value="">Semua Topik</option>
                @foreach($topiks as $topik)
                    <option value="{{ $topik->id }}">{{ $topik->nama }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">Variabel</label>
            <select wire:model.live="selectedVariabel" class="w-full text-sm rounded-md border-neutral-300 dark:border-neutral-600 dark:bg-neutral-800 dark:text-neutral-200 focus:ring-accent focus:border-accent">
                <option value="">Semua Variabel</option>
                @foreach($variabels as $variabel)
                    <option value="{{ $variabel->id }}">{{ $variabel->nama }} ({{ $variabel->satuan }})</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">Klasifikasi</label>
            <select wire:model.live="selectedKlasifikasi" class="w-full text-sm rounded-md border-neutral-300 dark:border-neutral-600 dark:bg-neutral-800 dark:text-neutral-200 focus:ring-accent focus:border-accent">
                <option value="">Semua Klasifikasi</option>
                @foreach($klasifikasis as $klasifikasi)
                    <option value="{{ $klasifikasi->id }}">{{ $klasifikasi->nama }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">Tahun</label>
            <select wire:model.live="selectedYear" class="w-full text-sm rounded-md border-neutral-300 dark:border-neutral-600 dark:bg-neutral-800 dark:text-neutral-200 focus:ring-accent focus:border-accent">
                <option value="">Semua Tahun</option>
                @foreach($years as $year)
                    <option value="{{ $year }}">{{ $year }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="mb-6 grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white dark:bg-neutral-800 p-6 rounded-lg border border-neutral-200 dark:border-neutral-700">
            <div class="flex items-center">
                <div class="p-2 bg-blue-100 dark:bg-blue-900/30 rounded-lg">
                    <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-neutral-600 dark:text-neutral-400">Total Data</p>
                    <p class="text-2xl font-semibold text-neutral-900 dark:text-white">{{ number_format($totalData) }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-neutral-800 p-6 rounded-lg border border-neutral-200 dark:border-neutral-700">
            <div class="flex items-center">
                <div class="p-2 bg-green-100 dark:bg-green-900/30 rounded-lg">
                    <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-neutral-600 dark:text-neutral-400">Rata-rata</p>
                    <p class="text-2xl font-semibold text-neutral-900 dark:text-white">{{ number_format($averageValue, 2, ',', '.') }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-neutral-800 p-6 rounded-lg border border-neutral-200 dark:border-neutral-700">
            <div class="flex items-center">
                <div class="p-2 bg-red-100 dark:bg-red-900/30 rounded-lg">
                    <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-neutral-600 dark:text-neutral-400">Maksimum</p>
                    <p class="text-2xl font-semibold text-neutral-900 dark:text-white">{{ number_format($maxValue, 2, ',', '.') }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-neutral-800 p-6 rounded-lg border border-neutral-200 dark:border-neutral-700">
            <div class="flex items-center">
                <div class="p-2 bg-yellow-100 dark:bg-yellow-900/30 rounded-lg">
                    <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-neutral-600 dark:text-neutral-400">Minimum</p>
                    <p class="text-2xl font-semibold text-neutral-900 dark:text-white">{{ number_format($minValue, 2, ',', '.') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Map Visualization -->
    <div class="bg-white dark:bg-neutral-800 rounded-lg border border-neutral-200 dark:border-neutral-700 p-6 mb-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-medium text-neutral-900 dark:text-white">Peta Sebaran Data</h3>
            <div class="flex items-center space-x-2">
                <div class="flex items-center space-x-1">
                    <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                    <span class="text-xs text-neutral-600 dark:text-neutral-400">Tinggi</span>
                </div>
                <div class="flex items-center space-x-1">
                    <div class="w-3 h-3 bg-yellow-500 rounded-full"></div>
                    <span class="text-xs text-neutral-600 dark:text-neutral-400">Sedang</span>
                </div>
                <div class="flex items-center space-x-1">
                    <div class="w-3 h-3 bg-red-500 rounded-full"></div>
                    <span class="text-xs text-neutral-600 dark:text-neutral-400">Rendah</span>
                </div>
            </div>
        </div>
        <div id="map" class="h-96 rounded-lg border border-neutral-200 dark:border-neutral-600"></div>
    </div>

    @script
    <script>
        let map = null;

        function initializeMap() {
            if (map) {
                map.remove();
            }

            map = L.map('map').setView([-2.5489, 118.0149], 5);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors',
                maxZoom: 18
            }).addTo(map);

            const mapData = $wire.mapData;
            const averageValue = $wire.averageValue;
            
            mapData.forEach(function(region) {
                let markerColor = 'red';
                if (region.average_value > averageValue * 1.2) {
                    markerColor = 'green';
                } else if (region.average_value > averageValue * 0.8) {
                    markerColor = 'orange';
                }

                const marker = L.circleMarker([region.lat, region.lng], {
                    radius: Math.max(8, Math.min(20, region.total_data / 2)),
                    fillColor: markerColor,
                    color: markerColor,
                    weight: 2,
                    opacity: 0.8,
                    fillOpacity: 0.6
                }).addTo(map);

                const popupContent = `
                    <div class="p-2">
                        <h4 class="font-semibold text-neutral-900 mb-2">${region.wilayah}</h4>
                        <div class="space-y-1 text-sm">
                            <div class="flex justify-between">
                                <span class="text-neutral-600">Total Data:</span>
                                <span class="font-medium">${region.total_data.toLocaleString()}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-neutral-600">Rata-rata:</span>
                                <span class="font-medium">${region.average_value.toLocaleString()}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-neutral-600">Total Nilai:</span>
                                <span class="font-medium">${region.total_value.toLocaleString()}</span>
                            </div>
                        </div>
                    </div>
                `;

                marker.bindPopup(popupContent);
                marker.on('mouseover', function() {
                    this.openPopup();
                });
            });

            if (mapData.length > 0) {
                const group = new L.featureGroup(map._layers);
                if (Object.keys(group._layers).length > 0) {
                    map.fitBounds(group.getBounds().pad(0.1));
                }
            }
        }

        initializeMap();

        $wire.on('mapDataUpdated', () => {
            setTimeout(initializeMap, 100);
        });
    </script>
    @endscript

    <!-- Data by Region -->
    <div class="bg-white dark:bg-neutral-800 rounded-lg border border-neutral-200 dark:border-neutral-700">
        <div class="p-6 border-b border-neutral-200 dark:border-neutral-700">
            <h3 class="text-lg font-medium text-neutral-900 dark:text-white">Data per Wilayah</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-neutral-500 dark:text-neutral-400">
                <thead class="text-xs text-neutral-700 uppercase bg-neutral-50 dark:bg-neutral-700 dark:text-neutral-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">Wilayah</th>
                        <th scope="col" class="px-6 py-3">Jumlah Data</th>
                        <th scope="col" class="px-6 py-3">Rata-rata Nilai</th>
                        <th scope="col" class="px-6 py-3">Total Nilai</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($mapData as $region)
                    <tr class="bg-white border-b dark:bg-neutral-800 dark:border-neutral-700 hover:bg-neutral-50 dark:hover:bg-neutral-700 transition-colors">
                        <td class="px-6 py-4 font-medium text-neutral-900 dark:text-white">
                            {{ $region['wilayah'] }}
                        </td>
                        <td class="px-6 py-4">
                            {{ number_format($region['total_data']) }}
                        </td>
                        <td class="px-6 py-4">
                            {{ number_format($region['average_value'], 2, ',', '.') }}
                        </td>
                        <td class="px-6 py-4">
                            {{ number_format($region['total_value'], 2, ',', '.') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-4 text-center text-neutral-500 dark:text-neutral-400">
                            Tidak ada data ditemukan
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
