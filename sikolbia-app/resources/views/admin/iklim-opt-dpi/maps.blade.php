<div>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-6">
                <h2 class="font-semibold text-xl text-neutral-800 dark:text-neutral-200 leading-tight">
                    {{ __('Peta Iklim & OPT DPI') }}
                </h2>
            </div>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-zinc-900 overflow-hidden shadow-xl sm:rounded-lg p-6">
            <div class="text-neutral-900 dark:text-neutral-100">
                <h3 class="text-lg font-medium mb-4">Peta Persebaran Iklim & OPT DPI</h3>
                
                <!-- Map Container -->
                <div id="map" class="w-full h-[600px] rounded-lg border border-neutral-200">
                    <!-- Map will be initialized here -->
                </div>

                <!-- Map Controls -->
                <div class="mt-4 flex gap-4">
                    <div class="w-1/3">
                        <label class="block text-sm font-medium text-neutral-700 mb-2">Filter Wilayah</label>
                        <select class="w-full rounded-md border-neutral-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            <option value="">Semua Wilayah</option>
                            <!-- Will be populated dynamically -->
                        </select>
                    </div>
                    
                    <div class="w-1/3">
                        <label class="block text-sm font-medium text-neutral-700 mb-2">Jenis Data</label>
                        <select class="w-full rounded-md border-neutral-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            <option value="">Semua Jenis</option>
                            <option value="iklim">Data Iklim</option>
                            <option value="opt">Data OPT</option>
                            <option value="dpi">Data DPI</option>
                        </select>
                    </div>
                    
                    <div class="w-1/3">
                        <label class="block text-sm font-medium text-neutral-700 mb-2">Tahun</label>
                        <select class="w-full rounded-md border-neutral-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            <option value="">Pilih Tahun</option>
                            <!-- Will be populated dynamically -->
                        </select>
                    </div>
                </div>

                <!-- Legend -->
                <div class="mt-6">
                    <h4 class="text-sm font-medium text-neutral-700 mb-2">Legenda</h4>
                    <div class="flex gap-4">
                        <div class="flex items-center">
                            <div class="w-4 h-4 bg-green-500 rounded mr-2"></div>
                            <span class="text-sm text-neutral-600">Lahan Produktif</span>
                        </div>
                        <div class="flex items-center">
                            <div class="w-4 h-4 bg-yellow-500 rounded mr-2"></div>
                            <span class="text-sm text-neutral-600">Lahan Semi-Produktif</span>
                        </div>
                        <div class="flex items-center">
                            <div class="w-4 h-4 bg-red-500 rounded mr-2"></div>
                            <span class="text-sm text-neutral-600">Lahan Non-Produktif</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

