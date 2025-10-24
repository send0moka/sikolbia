<div>
    <!-- Header -->
    <flux:heading size="xl" class="mb-6">{{ __('Registrasi Akses') }}</flux:heading>
    
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6 mb-8">
        <!-- Total Registrasi -->
        <div class="bg-white dark:!bg-neutral-800 overflow-hidden shadow-lg rounded-lg border border-neutral-200 dark:border-neutral-700">
            <div class="p-4 lg:p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 lg:w-12 lg:h-12 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 lg:w-6 lg:h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-3 lg:ml-4 min-w-0 flex-1">
                        <h3 class="text-base lg:text-lg font-semibold text-neutral-900 dark:text-white truncate">Total Registrasi</h3>
                        <p class="text-2xl lg:text-3xl font-bold text-blue-600 dark:text-blue-400">{{ $stats['total'] ?? 0 }}</p>
                        <p class="text-xs lg:text-sm text-neutral-500 dark:text-neutral-400 truncate">Semua aplikasi</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pending -->
        <div class="bg-white dark:!bg-neutral-800 overflow-hidden shadow-lg rounded-lg border border-neutral-200 dark:border-neutral-700">
            <div class="p-4 lg:p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 lg:w-12 lg:h-12 bg-yellow-100 dark:bg-yellow-900 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 lg:w-6 lg:h-6 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-3 lg:ml-4 min-w-0 flex-1">
                        <h3 class="text-base lg:text-lg font-semibold text-neutral-900 dark:text-white truncate">Pending</h3>
                        <p class="text-2xl lg:text-3xl font-bold text-yellow-600 dark:text-yellow-400">{{ $stats['pending'] ?? 0 }}</p>
                        <p class="text-xs lg:text-sm text-neutral-500 dark:text-neutral-400 truncate">Menunggu review</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Approved -->
        <div class="bg-white dark:!bg-neutral-800 overflow-hidden shadow-lg rounded-lg border border-neutral-200 dark:border-neutral-700">
            <div class="p-4 lg:p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 lg:w-12 lg:h-12 bg-green-100 dark:bg-green-900 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 lg:w-6 lg:h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-3 lg:ml-4 min-w-0 flex-1">
                        <h3 class="text-base lg:text-lg font-semibold text-neutral-900 dark:text-white truncate">Disetujui</h3>
                        <p class="text-2xl lg:text-3xl font-bold text-green-600 dark:text-green-400">{{ $stats['approved'] ?? 0 }}</p>
                        <p class="text-xs lg:text-sm text-neutral-500 dark:text-neutral-400 truncate">Telah disetujui</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Rejected -->
        <div class="bg-white dark:!bg-neutral-800 overflow-hidden shadow-lg rounded-lg border border-neutral-200 dark:border-neutral-700">
            <div class="p-4 lg:p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 lg:w-12 lg:h-12 bg-red-100 dark:bg-red-900 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 lg:w-6 lg:h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-3 lg:ml-4 min-w-0 flex-1">
                        <h3 class="text-base lg:text-lg font-semibold text-neutral-900 dark:text-white truncate">Ditolak</h3>
                        <p class="text-2xl lg:text-3xl font-bold text-red-600 dark:text-red-400">{{ $stats['rejected'] ?? 0 }}</p>
                        <p class="text-xs lg:text-sm text-neutral-500 dark:text-neutral-400 truncate">Tidak disetujui</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters and Search -->
    <div class="bg-white dark:!bg-neutral-800 overflow-hidden shadow rounded-lg border border-neutral-200 dark:border-neutral-700 mb-6">
        <div class="px-4 py-5 sm:p-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
                <!-- Search -->
                <div>
                    <flux:input wire:model.live="search" placeholder="Cari nama, email, atau instansi..." class="w-full" />
                </div>
                
                <!-- Filter Status -->
                <div>
                    <flux:select wire:model.live="filterStatus" placeholder="Semua Status">
                        <option value="">Semua Status</option>
                        <option value="pending">Pending</option>
                        <option value="approved">Disetujui</option>
                        <option value="rejected">Ditolak</option>
                        <option value="need_documents">Butuh Dokumen</option>
                    </flux:select>
                </div>

                <!-- Filter Tipe -->
                <div>
                    <flux:select wire:model.live="filterTipe" placeholder="Semua Tipe">
                        <option value="">Semua Tipe</option>
                        <option value="pemerintah">Pemerintah</option>
                        <option value="akademisi">Akademisi</option>
                    </flux:select>
                </div>

                <!-- Reset Filters -->
                <div>
                    <flux:button wire:click="resetFilters" variant="outline" class="w-full">
                        Reset Filter
                    </flux:button>
                </div>
            </div>
        </div>
    </div>

    <!-- Data Table -->
    <div class="bg-white dark:!bg-neutral-800 overflow-hidden shadow rounded-lg border border-neutral-200 dark:border-neutral-700">
        <div class="px-4 py-5 sm:p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg leading-6 font-medium text-neutral-900 dark:text-white">
                    Daftar Registrasi Akses
                </h3>
                <div class="text-sm text-neutral-500 dark:text-neutral-400">
                    Total: {{ $registrasiList->total() }} registrasi
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-neutral-500 dark:text-neutral-400">
                    <thead class="text-xs text-neutral-700 uppercase bg-neutral-50 dark:bg-neutral-700 dark:text-neutral-400">
                        <tr>
                            <th scope="col" class="px-6 py-3 cursor-pointer" wire:click="sortBy('nama_lengkap')">
                                Nama Lengkap
                                @if($sortBy === 'nama_lengkap')
                                    <span class="ml-1">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                                @endif
                            </th>
                            <th scope="col" class="px-6 py-3">Email</th>
                            <th scope="col" class="px-6 py-3">Tipe</th>
                            <th scope="col" class="px-6 py-3">Instansi</th>
                            <th scope="col" class="px-6 py-3 cursor-pointer" wire:click="sortBy('status')">
                                Status
                                @if($sortBy === 'status')
                                    <span class="ml-1">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                                @endif
                            </th>
                            <th scope="col" class="px-6 py-3 cursor-pointer" wire:click="sortBy('created_at')">
                                Tanggal
                                @if($sortBy === 'created_at')
                                    <span class="ml-1">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                                @endif
                            </th>
                            <th scope="col" class="px-6 py-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($registrasiList as $registrasi)
                        <tr class="bg-white border-b dark:!bg-neutral-800 dark:border-neutral-600 hover:bg-neutral-50 dark:hover:!bg-neutral-700 transition-colors">
                            <td class="px-6 py-4 font-medium text-neutral-900 dark:text-white">
                                {{ $registrasi->nama_lengkap }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $registrasi->email }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    {{ $registrasi->tipe_akses === 'pemerintah' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300' : 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-300' }}">
                                    {{ ucfirst($registrasi->tipe_akses) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                {{ $registrasi->tipe_akses === 'pemerintah' ? $registrasi->instansi : $registrasi->institusi }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $registrasi->status_color }}">
                                    {{ $registrasi->status_label }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                {{ $registrasi->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex space-x-2">
                                    <flux:button 
                                        wire:click="viewDetail({{ $registrasi->id }})" 
                                        size="sm" 
                                        variant="outline"
                                        onclick="console.log('Detail button clicked for ID: {{ $registrasi->id }}')"
                                        class="text-blue-600 hover:text-blue-900">
                                        Detail
                                    </flux:button>
                                    
                                    @if($registrasi->status === 'pending')
                                        <flux:button 
                                            wire:click="approve({{ $registrasi->id }})" 
                                            size="sm" 
                                            class="bg-green-600 text-white hover:bg-green-700">
                                            Setujui
                                        </flux:button>
                                        <flux:button 
                                            wire:click="reject({{ $registrasi->id }})" 
                                            size="sm" 
                                            class="bg-red-600 text-white hover:bg-red-700">
                                            Tolak
                                        </flux:button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-6 py-4 text-center text-neutral-500">
                                Tidak ada data registrasi ditemukan
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-4">
                {{ $registrasiList->links() }}
            </div>
        </div>
    </div>

    <!-- Detail Modal -->
    @if($showDetailModal && $selectedRegistrasi)
    <div class="fixed inset-0 bg-gray-600/50 dark:bg-gray-900/50 overflow-y-auto h-full w-full z-50" wire:click="closeModal">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white dark:bg-neutral-800" wire:click.stop>
            <div class="p-6">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-lg font-medium text-neutral-900 dark:text-white">
                    Detail Registrasi - {{ $selectedRegistrasi->nama_lengkap }}
                </h3>
                <flux:button wire:click="closeModal" variant="outline" size="sm">
                    Tutup
                </flux:button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Informasi Dasar -->
                <div class="space-y-4">
                    <h4 class="font-medium text-neutral-900 dark:text-white">Informasi Dasar</h4>
                    
                    <div>
                        <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">Nama Lengkap</label>
                        <p class="text-sm text-neutral-900 dark:text-white">{{ $selectedRegistrasi->nama_lengkap }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">Email</label>
                        <p class="text-sm text-neutral-900 dark:text-white">{{ $selectedRegistrasi->email }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">No. Telepon</label>
                        <p class="text-sm text-neutral-900 dark:text-white">{{ $selectedRegistrasi->telepon }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">Tipe Registrasi</label>
                        <p class="text-sm text-neutral-900 dark:text-white">{{ ucfirst($selectedRegistrasi->tipe_akses) }}</p>
                    </div>
                </div>

                <!-- Informasi Instansi -->
                <div class="space-y-4">
                    <h4 class="font-medium text-neutral-900 dark:text-white">
                        {{ $selectedRegistrasi->tipe_akses === 'pemerintah' ? 'Informasi Instansi' : 'Informasi Institusi' }}
                    </h4>
                    
                    @if($selectedRegistrasi->tipe_akses === 'pemerintah')
                        <div>
                            <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">Instansi</label>
                            <p class="text-sm text-neutral-900 dark:text-white">{{ $selectedRegistrasi->instansi }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">Jenis Dinas</label>
                            <p class="text-sm text-neutral-900 dark:text-white">{{ $selectedRegistrasi->jenis_dinas }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">Jabatan</label>
                            <p class="text-sm text-neutral-900 dark:text-white">{{ $selectedRegistrasi->jabatan }}</p>
                        </div>
                    @else
                        <div>
                            <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">Institusi</label>
                            <p class="text-sm text-neutral-900 dark:text-white">{{ $selectedRegistrasi->institusi }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">Jenjang Pendidikan</label>
                            <p class="text-sm text-neutral-900 dark:text-white">{{ $selectedRegistrasi->jenjang_pendidikan }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">Program Studi</label>
                            <p class="text-sm text-neutral-900 dark:text-white">{{ $selectedRegistrasi->program_studi }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Tujuan Penggunaan -->
            <div class="mt-6">
                <h4 class="font-medium text-neutral-900 dark:text-white mb-3">Tujuan Penggunaan Data</h4>
                @if($selectedRegistrasi->tujuan_penggunaan && is_array($selectedRegistrasi->tujuan_penggunaan))
                    <div class="flex flex-wrap gap-2">
                        @foreach($selectedRegistrasi->tujuan_penggunaan as $tujuan)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300">
                                {{ $tujuan }}
                            </span>
                        @endforeach
                    </div>
                @else
                    <p class="text-sm text-neutral-500 dark:text-neutral-400">Tidak ada tujuan penggunaan yang ditentukan</p>
                @endif
            </div>

            <!-- Deskripsi Kebutuhan -->
            @if($selectedRegistrasi->deskripsi_kebutuhan)
            <div class="mt-6">
                <h4 class="font-medium text-neutral-900 dark:text-white mb-3">Deskripsi Kebutuhan</h4>
                <p class="text-sm text-neutral-900 dark:text-white bg-neutral-50 dark:bg-neutral-700 p-3 rounded-lg">
                    {{ $selectedRegistrasi->deskripsi_kebutuhan }}
                </p>
            </div>
            @endif

            <!-- Status dan Catatan -->
            <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">Status</label>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $selectedRegistrasi->status_color }}">
                        {{ $selectedRegistrasi->status_label }}
                    </span>
                </div>

                <div>
                    <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">Tanggal Registrasi</label>
                    <p class="text-sm text-neutral-900 dark:text-white">{{ $selectedRegistrasi->created_at->format('d F Y, H:i') }}</p>
                </div>
            </div>

            @if($selectedRegistrasi->catatan_admin)
            <div class="mt-6">
                <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">Catatan Admin</label>
                <p class="text-sm text-neutral-900 dark:text-white bg-neutral-50 dark:bg-neutral-700 p-3 rounded-lg">
                    {{ $selectedRegistrasi->catatan_admin }}
                </p>
            </div>
            @endif

            <!-- Action Buttons -->
            @if($selectedRegistrasi->status === 'pending')
            <div class="mt-6 flex justify-end space-x-3">
                <flux:button wire:click="needDocuments({{ $selectedRegistrasi->id }})" variant="outline">
                    Minta Dokumen Tambahan
                </flux:button>
                <flux:button wire:click="approve({{ $selectedRegistrasi->id }})" class="bg-green-600 text-white hover:bg-green-700">
                    Setujui
                </flux:button>
                <flux:button wire:click="reject({{ $selectedRegistrasi->id }})" class="bg-red-600 text-white hover:bg-red-700">
                    Tolak
                </flux:button>
            </div>
            @endif
            </div>
        </div>
    </div>
    @endif
</div>
