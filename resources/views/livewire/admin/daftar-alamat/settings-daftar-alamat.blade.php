<div>
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-neutral-900 dark:text-white">Pengaturan Daftar Alamat</h1>
        <p class="mt-1 text-sm text-neutral-600 dark:text-neutral-400">
            Kelola pengaturan sistem dan data daftar alamat
        </p>
    </div>

    <!-- System Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <div class="bg-white dark:!bg-neutral-800 p-6 rounded-lg shadow-sm border border-neutral-200 dark:border-neutral-700">
            <div class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ number_format($stats['total_alamat']) }}</div>
            <div class="text-sm text-neutral-600 dark:text-neutral-400">Total Alamat</div>
        </div>
        <div class="bg-white dark:!bg-neutral-800 p-6 rounded-lg shadow-sm border border-neutral-200 dark:border-neutral-700">
            <div class="text-2xl font-bold text-green-600 dark:text-green-400">{{ number_format($stats['total_aktif']) }}</div>
            <div class="text-sm text-neutral-600 dark:text-neutral-400">Alamat Aktif</div>
        </div>
        <div class="bg-white dark:!bg-neutral-800 p-6 rounded-lg shadow-sm border border-neutral-200 dark:border-neutral-700">
            <div class="text-2xl font-bold text-purple-600 dark:text-purple-400">{{ number_format($stats['total_with_coordinates']) }}</div>
            <div class="text-sm text-neutral-600 dark:text-neutral-400">Dengan Koordinat</div>
        </div>
        <div class="bg-white dark:!bg-neutral-800 p-6 rounded-lg shadow-sm border border-neutral-200 dark:border-neutral-700">
            <div class="text-2xl font-bold text-orange-600 dark:text-orange-400">{{ number_format($stats['total_provinsi']) }}</div>
            <div class="text-sm text-neutral-600 dark:text-neutral-400">Total Provinsi</div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Import Data -->
        <div class="bg-white dark:!bg-neutral-800 rounded-lg shadow-sm border border-neutral-200 dark:border-neutral-700 p-6">
            <h3 class="text-lg font-medium text-neutral-900 dark:text-white mb-4">Import Data</h3>
            <p class="text-sm text-neutral-600 dark:text-neutral-400 mb-4">
                Upload file CSV atau Excel untuk mengimpor data alamat dalam jumlah besar.
            </p>
            
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1">File Import</label>
                    <input wire:model="importFile" type="file" accept=".csv,.xlsx,.xls" class="w-full text-sm rounded-md border-neutral-300 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200 focus:ring-accent focus:border-accent" />
                    @error('importFile') <span class="text-red-500 dark:text-red-400 text-sm">{{ $message }}</span> @enderror
                    <p class="text-xs text-neutral-500 dark:text-neutral-400 mt-1">Format yang didukung: CSV, Excel (.xlsx, .xls). Maksimal 10MB.</p>
                </div>
                
                <button wire:click="startImport" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 disabled:bg-neutral-400 text-white text-sm font-medium rounded-md transition-colors duration-200" :disabled="!$wire.importFile">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                    </svg>
                    Mulai Import
                </button>
            </div>
        </div>

        <!-- Export Data -->
        <div class="bg-white dark:!bg-neutral-800 rounded-lg shadow-sm border border-neutral-200 dark:border-neutral-700 p-6">
            <h3 class="text-lg font-medium text-neutral-900 dark:text-white mb-4">Export Data</h3>
            <p class="text-sm text-neutral-600 dark:text-neutral-400 mb-4">
                Download semua data alamat dalam format Excel atau CSV.
            </p>
            
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1">Format Export</label>
                    <select wire:model="exportFormat" class="w-full text-sm rounded-md border-neutral-300 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200 focus:ring-accent focus:border-accent">
                        <option value="excel">Excel (.xlsx)</option>
                        <option value="csv">CSV</option>
                    </select>
                </div>
                
                <div class="space-y-2">
                    <label class="flex items-center">
                        <input type="checkbox" wire:model="includeCoordinates" class="rounded border-neutral-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        <span class="ml-2 text-sm text-neutral-700 dark:text-neutral-300">Sertakan data koordinat</span>
                    </label>
                    <label class="flex items-center">
                        <input type="checkbox" wire:model="includeInactive" class="rounded border-neutral-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        <span class="ml-2 text-sm text-neutral-700 dark:text-neutral-300">Sertakan data tidak aktif</span>
                    </label>
                </div>
                
                <button wire:click="exportData" class="inline-flex items-center px-4 py-2 border border-neutral-300 dark:border-neutral-600 bg-white dark:bg-neutral-800 text-neutral-700 dark:text-neutral-300 text-sm font-medium rounded-md hover:bg-neutral-50 dark:hover:bg-neutral-700 transition-colors duration-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Download Data
                </button>
            </div>
        </div>

        <!-- Bulk Actions -->
        <div class="bg-white dark:!bg-neutral-800 rounded-lg shadow-sm border border-neutral-200 dark:border-neutral-700 p-6">
            <h3 class="text-lg font-medium text-neutral-900 dark:text-white mb-4">Aksi Massal</h3>
            <p class="text-sm text-neutral-600 dark:text-neutral-400 mb-4">
                Lakukan perubahan pada banyak data sekaligus.
            </p>
            
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1">Pilih Aksi</label>
                    <select wire:model="bulkAction" class="w-full text-sm rounded-md border-neutral-300 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200 focus:ring-accent focus:border-accent">
                        <option value="">Pilih aksi</option>
                        <option value="update_status">Update Status</option>
                        <option value="update_kategori">Update Kategori</option>
                        <option value="delete_inactive">Hapus Data Tidak Aktif</option>
                        <option value="archive_old">Arsipkan Data Lama</option>
                    </select>
                </div>
                
                @if($bulkAction === 'update_status')
                    <div>
                        <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1">Status Baru</label>
                        <select wire:model="selectedStatus" class="w-full text-sm rounded-md border-neutral-300 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200 focus:ring-accent focus:border-accent">
                            @foreach($statusOptions as $value => $label)
                                <option value="{{ $value }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif
                
                @if($bulkAction === 'update_kategori')
                    <div>
                        <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1">Kategori Baru</label>
                        <select wire:model="selectedKategori" class="w-full text-sm rounded-md border-neutral-300 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200 focus:ring-accent focus:border-accent">
                            @foreach($kategoriOptions as $value => $label)
                                <option value="{{ $value }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif
                
                <button wire:click="startBulkAction" wire:confirm="Yakin ingin melakukan aksi ini? Perubahan tidak dapat dibatalkan." class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 disabled:bg-neutral-400 text-white text-sm font-medium rounded-md transition-colors duration-200" :disabled="!$wire.bulkAction">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                        <circle cx="12" cy="12" r="3"></circle>
                    </svg>
                    Jalankan Aksi
                </button>
            </div>
        </div>

        <!-- Database Management -->
        <div class="bg-white dark:!bg-neutral-800 rounded-lg shadow-sm border border-neutral-200 dark:border-neutral-700 p-6">
            <h3 class="text-lg font-medium text-neutral-900 dark:text-white mb-4">Manajemen Database</h3>
            <p class="text-sm text-neutral-600 dark:text-neutral-400 mb-4">
                Kelola data dan struktur database sistem.
            </p>
            
            <div class="space-y-4">
                <div class="p-4 bg-yellow-50 dark:bg-yellow-900/20 rounded-lg border border-yellow-200 dark:border-yellow-800">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-yellow-600 dark:text-yellow-400 mt-0.5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z" />
                        </svg>
                        <div>
                            <h4 class="text-sm font-medium text-yellow-800 dark:text-yellow-200">Peringatan</h4>
                            <p class="text-sm text-yellow-700 dark:text-yellow-300 mt-1">
                                Aksi di bawah ini akan mengubah atau menghapus data secara permanen. Pastikan Anda telah membuat backup.
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="space-y-2">
                    <button wire:click="seedSampleData" wire:confirm="Yakin ingin menambahkan sample data? Data yang ada tidak akan terhapus." class="inline-flex items-center px-4 py-2 border border-neutral-300 dark:border-neutral-600 bg-white dark:bg-neutral-800 text-neutral-700 dark:text-neutral-300 text-sm font-medium rounded-md hover:bg-neutral-50 dark:hover:bg-neutral-700 transition-colors duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Tambah Sample Data
                    </button>
                    
                    @if(!app()->environment('production'))
                        <button wire:click="resetDatabase" wire:confirm="PERINGATAN: Ini akan menghapus SEMUA data alamat! Yakin ingin melanjutkan?" class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-md transition-colors duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                            Reset Database
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Import Progress Modal -->
    @if($showImportModal)
    <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-neutral-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white dark:bg-neutral-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md sm:w-full">
                <div class="bg-white dark:bg-neutral-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <h3 class="text-lg font-medium text-neutral-900 dark:text-white mb-4">Import Data</h3>
                    <div class="space-y-4">
                        <div>
                            <div class="flex justify-between text-sm mb-2">
                                <span>Progress</span>
                                <span>{{ $importProgress }}%</span>
                            </div>
                            <div class="w-full bg-neutral-200 dark:bg-neutral-700 rounded-full h-2">
                                <div class="bg-blue-600 h-2 rounded-full transition-all duration-300" style="width: {{ $importProgress }}%"></div>
                            </div>
                        </div>
                        
                        <div class="text-sm text-neutral-600 dark:text-neutral-400">
                            {{ $importStatus }}
                        </div>
                    </div>
                </div>
                @if($importProgress === 100)
                <div class="bg-neutral-50 dark:bg-neutral-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button wire:click="closeImportModal" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Selesai
                    </button>
                </div>
                @endif
            </div>
        </div>
    </div>
    @endif

    <!-- Bulk Action Progress Modal -->
    @if($showBulkModal)
    <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-neutral-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white dark:bg-neutral-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md sm:w-full">
                <div class="bg-white dark:bg-neutral-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <h3 class="text-lg font-medium text-neutral-900 dark:text-white mb-4">Proses Aksi Massal</h3>
                    <div class="space-y-4">
                        <div>
                            <div class="flex justify-between text-sm mb-2">
                                <span>Progress</span>
                                <span>{{ $bulkProgress }}%</span>
                            </div>
                            <div class="w-full bg-neutral-200 dark:bg-neutral-700 rounded-full h-2">
                                <div class="bg-blue-600 h-2 rounded-full transition-all duration-300" style="width: {{ $bulkProgress }}%"></div>
                            </div>
                        </div>
                        
                        <div class="text-sm text-neutral-600 dark:text-neutral-400">
                            {{ $bulkStatus }}
                        </div>
                    </div>
                </div>
                @if($bulkProgress === 100)
                <div class="bg-neutral-50 dark:bg-neutral-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button wire:click="closeBulkModal" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Selesai
                    </button>
                </div>
                @endif
            </div>
        </div>
    </div>
    @endif

    <!-- Flash Messages -->
    @if (session()->has('message'))
        <div class="fixed top-4 right-4 bg-green-500 text-white px-4 py-2 rounded-md shadow-lg z-50">
            {{ session('message') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="fixed top-4 right-4 bg-red-500 text-white px-4 py-2 rounded-md shadow-lg z-50">
            {{ session('error') }}
        </div>
    @endif
</div>
