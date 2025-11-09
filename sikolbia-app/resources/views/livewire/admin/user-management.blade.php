<div>
    <!-- Header -->
    <div class="mb-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-semibold text-neutral-900 dark:text-white">Kelola Pengguna</h1>
                <p class="mt-1 text-sm text-neutral-600 dark:text-neutral-400">
                    Kelola akun pengguna, role, dan status aktivasi
                </p>
            </div>
            @can('create users')
            <button wire:click="openCreateModal" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                Tambah Pengguna
            </button>
            @endcan
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-6">
        <div class="bg-white dark:bg-neutral-800 rounded-lg shadow-sm border border-neutral-200 dark:border-neutral-700 p-4">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 dark:bg-blue-900/30">
                    <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-neutral-600 dark:text-neutral-400">Total Pengguna</p>
                    <p class="text-2xl font-bold text-neutral-900 dark:text-white">{{ $stats['total'] }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white dark:bg-neutral-800 rounded-lg shadow-sm border border-neutral-200 dark:border-neutral-700 p-4">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 dark:bg-green-900/30">
                    <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-neutral-600 dark:text-neutral-400">Aktif</p>
                    <p class="text-2xl font-bold text-green-600 dark:text-green-400">{{ $stats['active'] }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white dark:bg-neutral-800 rounded-lg shadow-sm border border-neutral-200 dark:border-neutral-700 p-4">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-red-100 dark:bg-red-900/30">
                    <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L18.364 5.636M5.636 5.636l12.728 12.728"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-neutral-600 dark:text-neutral-400">Suspended</p>
                    <p class="text-2xl font-bold text-red-600 dark:text-red-400">{{ $stats['suspended'] }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white dark:bg-neutral-800 rounded-lg shadow-sm border border-neutral-200 dark:border-neutral-700 p-4">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 dark:bg-blue-900/30">
                    <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-neutral-600 dark:text-neutral-400">Pemerintah</p>
                    <p class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $stats['pemerintah'] }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white dark:bg-neutral-800 rounded-lg shadow-sm border border-neutral-200 dark:border-neutral-700 p-4">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-100 dark:bg-purple-900/30">
                    <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-neutral-600 dark:text-neutral-400">Akademisi</p>
                    <p class="text-2xl font-bold text-purple-600 dark:text-purple-400">{{ $stats['akademisi'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Flash Messages -->
    @if (session()->has('message'))
        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded dark:bg-green-900/30 dark:border-green-700 dark:text-green-300">
            {{ session('message') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded dark:bg-red-900/30 dark:border-red-700 dark:text-red-300">
            {{ session('error') }}
        </div>
    @endif

    <!-- Search, Filters & Actions -->
    <div class="mb-6 bg-white dark:bg-neutral-800 rounded-lg shadow-sm border border-neutral-200 dark:border-neutral-700 p-4">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <div class="flex flex-col sm:flex-row sm:items-center gap-3 flex-1">
                <input 
                    wire:model.live="search" 
                    placeholder="Cari berdasarkan nama atau email..."
                    class="w-full sm:max-w-sm px-3 py-2 border border-neutral-300 dark:border-neutral-600 rounded-md bg-white dark:bg-neutral-800 text-neutral-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                />
                
                <select wire:model.live="filterRole" class="px-3 py-2 border border-neutral-300 dark:border-neutral-600 rounded-md bg-white dark:bg-neutral-800 text-neutral-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Semua Role</option>
                    @foreach($roles as $role)
                        <option value="{{ $role->name }}">{{ ucfirst($role->name) }}</option>
                    @endforeach
                </select>
                
                <select wire:model.live="filterStatus" class="px-3 py-2 border border-neutral-300 dark:border-neutral-600 rounded-md bg-white dark:bg-neutral-800 text-neutral-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Semua Status</option>
                    <option value="active">Aktif</option>
                    <option value="suspended">Suspended</option>
                </select>
                
                <button wire:click="resetFilters" class="px-3 py-2 text-sm text-neutral-600 dark:text-neutral-400 hover:text-neutral-900 dark:hover:text-white border border-neutral-300 dark:border-neutral-600 rounded-md hover:bg-neutral-50 dark:hover:bg-neutral-700 transition">
                    Reset Filter
                </button>
            </div>
            
            <div class="flex items-center gap-3">
                <div class="flex items-center space-x-2">
                    <label class="text-sm text-neutral-600 dark:text-neutral-400">Tampil</label>
                    <select wire:model.live="perPage" class="text-sm px-2 py-1 border border-neutral-300 dark:border-neutral-600 rounded bg-white dark:bg-neutral-800 text-neutral-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        @foreach($perPageOptions as $size)
                            <option value="{{ $size }}">{{ $size }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="flex items-center space-x-2">
                    <select wire:model="exportFormat" class="text-sm px-2 py-1 border border-neutral-300 dark:border-neutral-600 rounded bg-white dark:bg-neutral-800 text-neutral-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="xlsx">XLSX</option>
                        <option value="csv">CSV</option>
                    </select>
                    <button wire:click="export" class="px-3 py-1 text-sm bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                        Export
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Users Table -->
    <div class="bg-white dark:bg-neutral-800 overflow-hidden shadow-sm rounded-lg border border-neutral-200 dark:border-neutral-700" id="users-table-wrapper">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-neutral-500 dark:text-neutral-400" id="users-table">
                <thead class="text-xs text-neutral-700 uppercase bg-neutral-50 dark:bg-neutral-700 dark:text-neutral-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">Pengguna</th>
                        <th scope="col" class="px-6 py-3">Role</th>
                        <th scope="col" class="px-6 py-3">Status</th>
                        <th scope="col" class="px-6 py-3">Bergabung</th>
                        <th scope="col" class="px-6 py-3 no-print">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $user)
                    <tr class="bg-white border-b dark:bg-neutral-800 dark:border-neutral-700 hover:bg-neutral-50 dark:hover:bg-neutral-700 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <div class="h-10 w-10 rounded-full bg-gradient-to-r from-blue-500 to-purple-500 flex items-center justify-center text-white font-semibold">
                                        {{ substr($user->name, 0, 2) }}
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-neutral-900 dark:text-white">
                                        {{ $user->name }}
                                    </div>
                                    <div class="text-sm text-neutral-500 dark:text-neutral-400">
                                        {{ $user->email }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            @foreach($user->roles as $role)
                                @php
                                    $roleColors = [
                                        'admin' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300',
                                        'superadmin' => 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-300',
                                        'pemerintah' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300',
                                        'akademisi' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
                                        'user' => 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300',
                                    ];
                                    $colorClass = $roleColors[$role->name] ?? 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300';
                                @endphp
                                <span class="{{ $colorClass }} text-xs font-medium px-2.5 py-0.5 rounded">
                                    {{ ucfirst($role->name) }}
                                </span>
                            @endforeach
                        </td>
                        <td class="px-6 py-4">
                            @if($user->suspended_at && $user->suspended_until > now())
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                    </svg>
                                    Suspended
                                </span>
                                <div class="text-xs text-neutral-500 dark:text-neutral-400 mt-1">
                                    Sampai: {{ $user->suspended_until->format('d/m/Y') }}
                                </div>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    Aktif
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-neutral-900 dark:text-white">
                                {{ $user->created_at->format('d/m/Y') }}
                            </div>
                            <div class="text-xs text-neutral-500 dark:text-neutral-400">
                                {{ $user->created_at->diffForHumans() }}
                            </div>
                        </td>
                        <td class="px-6 py-4 no-print">
                            <div class="flex items-center space-x-1">
                                <!-- Detail Button -->
                                <button 
                                    wire:click="openDetailModal({{ $user->id }})" 
                                    class="p-2 text-blue-600 hover:bg-blue-100 dark:hover:bg-blue-900/30 rounded-lg transition"
                                    title="Lihat Detail">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </button>

                                <!-- Activity Button -->
                                <button 
                                    wire:click="openActivityModal({{ $user->id }})" 
                                    class="p-2 text-purple-600 hover:bg-purple-100 dark:hover:bg-purple-900/30 rounded-lg transition"
                                    title="Lihat Aktivitas">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                    </svg>
                                </button>

                                @can('edit users')
                                <!-- Edit Button -->
                                <button 
                                    wire:click="openEditModal({{ $user->id }})" 
                                    class="p-2 text-green-600 hover:bg-green-100 dark:hover:bg-green-900/30 rounded-lg transition"
                                    title="Edit User">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </button>
                                @endcan

                                @if($user->suspended_at && $user->suspended_until > now())
                                    <!-- Reactivate Button -->
                                    <button 
                                        wire:click="reactivateUser({{ $user->id }})" 
                                        class="p-2 text-green-600 hover:bg-green-100 dark:hover:bg-green-900/30 rounded-lg transition"
                                        title="Aktifkan Kembali"
                                        onclick="return confirm('Yakin ingin mengaktifkan kembali user ini?')">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </button>
                                @else
                                    <!-- Suspend Button -->
                                    <button 
                                        wire:click="openSuspendModal({{ $user->id }})" 
                                        class="p-2 text-yellow-600 hover:bg-yellow-100 dark:hover:bg-yellow-900/30 rounded-lg transition"
                                        title="Suspend User">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L18.364 5.636M5.636 5.636l12.728 12.728"/>
                                        </svg>
                                    </button>
                                @endif

                                @can('delete users')
                                <!-- Delete Button -->
                                <button 
                                    wire:click="openDeleteModal({{ $user->id }})" 
                                    class="p-2 text-red-600 hover:bg-red-100 dark:hover:bg-red-900/30 rounded-lg transition"
                                    title="Hapus User">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                                @endcan
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <svg class="w-12 h-12 text-neutral-400 dark:text-neutral-500 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                                <p class="text-neutral-500 dark:text-neutral-400 text-lg font-medium">Tidak ada pengguna ditemukan</p>
                                <p class="text-neutral-400 dark:text-neutral-500 text-sm mt-1">Coba ubah filter pencarian atau tambah pengguna baru</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="px-6 py-3 flex flex-col md:flex-row md:items-center md:justify-between gap-4 bg-neutral-50 dark:bg-neutral-700 border-t border-neutral-200 dark:border-neutral-600">
            <div class="text-xs text-neutral-600 dark:text-neutral-400 md:mr-auto">
                Menampilkan
                <span class="font-medium text-neutral-800 dark:text-neutral-200">{{ $users->firstItem() ?? 0 }}</span>
                -
                <span class="font-medium text-neutral-800 dark:text-neutral-200">{{ $users->lastItem() ?? 0 }}</span>
                dari
                <span class="font-medium text-neutral-800 dark:text-neutral-200">{{ $users->total() }}</span>
                pengguna
            </div>
            {{ $users->links('pagination::tailwind') }}
        </div>
    </div>

    <!-- Create User Modal -->
    @if($showCreateModal)
    <div class="fixed inset-0 bg-neutral-900/70 dark:bg-neutral-950/80 backdrop-blur-sm overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border border-neutral-200 dark:border-neutral-700 w-96 shadow-xl rounded-md bg-white dark:bg-neutral-800">
            <div class="mt-3">
                <h3 class="text-lg font-medium text-neutral-900 dark:text-white mb-4">Tambah Pengguna Baru</h3>
                <form wire:submit="createUser">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1">Nama Lengkap</label>
                            <input wire:model="name" type="text" class="w-full px-3 py-2 border border-neutral-300 dark:border-neutral-600 rounded-md bg-white dark:bg-neutral-800 text-neutral-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Nama lengkap" required />
                            @error('name') <span class="text-red-500 dark:text-red-400 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1">Email</label>
                            <input wire:model="email" type="email" class="w-full px-3 py-2 border border-neutral-300 dark:border-neutral-600 rounded-md bg-white dark:bg-neutral-800 text-neutral-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="email@example.com" required />
                            @error('email') <span class="text-red-500 dark:text-red-400 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1">Password</label>
                            <input wire:model="password" type="password" class="w-full px-3 py-2 border border-neutral-300 dark:border-neutral-600 rounded-md bg-white dark:bg-neutral-800 text-neutral-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required />
                            @error('password') <span class="text-red-500 dark:text-red-400 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1">Role</label>
                            <select wire:model="selectedRole" class="w-full px-3 py-2 border border-neutral-300 dark:border-neutral-600 rounded-md bg-white dark:bg-neutral-800 text-neutral-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Pilih role</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->name }}">{{ ucfirst($role->name) }}</option>
                                @endforeach
                            </select>
                            @error('selectedRole') <span class="text-red-500 dark:text-red-400 text-sm">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="flex justify-end space-x-3 mt-6">
                        <button type="button" wire:click="closeCreateModal" class="px-4 py-2 text-sm font-medium text-neutral-700 dark:text-neutral-300 bg-white dark:bg-neutral-700 border border-neutral-300 dark:border-neutral-600 rounded-md hover:bg-neutral-50 dark:hover:bg-neutral-600 transition">
                            Batal
                        </button>
                        <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700 transition">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    <!-- Edit User Modal -->
    @if($showEditModal && $editingUser)
    <div class="fixed inset-0 bg-neutral-900/70 dark:bg-neutral-950/80 backdrop-blur-sm overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border border-neutral-200 dark:border-neutral-700 w-96 shadow-xl rounded-md bg-white dark:bg-neutral-800">
            <div class="mt-3">
                <h3 class="text-lg font-medium text-neutral-900 dark:text-white mb-4">Edit Pengguna</h3>
                <form wire:submit="updateUser">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1">Nama Lengkap</label>
                            <input wire:model="name" type="text" class="w-full px-3 py-2 border border-neutral-300 dark:border-neutral-600 rounded-md bg-white dark:bg-neutral-800 text-neutral-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Nama lengkap" required />
                            @error('name') <span class="text-red-500 dark:text-red-400 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1">Email</label>
                            <input wire:model="email" type="email" class="w-full px-3 py-2 border border-neutral-300 dark:border-neutral-600 rounded-md bg-white dark:bg-neutral-800 text-neutral-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="email@example.com" required />
                            @error('email') <span class="text-red-500 dark:text-red-400 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1">Password Baru</label>
                            <input wire:model="password" type="password" class="w-full px-3 py-2 border border-neutral-300 dark:border-neutral-600 rounded-md bg-white dark:bg-neutral-800 text-neutral-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Kosongkan jika tidak ingin mengubah" />
                            @error('password') <span class="text-red-500 dark:text-red-400 text-sm">{{ $message }}</span> @enderror
                            <p class="text-xs text-neutral-500 dark:text-neutral-400 mt-1">Kosongkan jika tidak ingin mengubah password</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1">Role</label>
                            <select wire:model="selectedRole" class="w-full px-3 py-2 border border-neutral-300 dark:border-neutral-600 rounded-md bg-white dark:bg-neutral-800 text-neutral-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Pilih role</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->name }}">{{ ucfirst($role->name) }}</option>
                                @endforeach
                            </select>
                            @error('selectedRole') <span class="text-red-500 dark:text-red-400 text-sm">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="flex justify-end space-x-3 mt-6">
                        <button type="button" wire:click="closeEditModal" class="px-4 py-2 text-sm font-medium text-neutral-700 dark:text-neutral-300 bg-white dark:bg-neutral-700 border border-neutral-300 dark:border-neutral-600 rounded-md hover:bg-neutral-50 dark:hover:bg-neutral-600 transition">
                            Batal
                        </button>
                        <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700 transition">
                            Update
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    <!-- Delete User Modal -->
    @if($showDeleteModal && $deletingUser)
    <div class="fixed inset-0 bg-neutral-900/70 dark:bg-neutral-950/80 backdrop-blur-sm overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border border-neutral-200 dark:border-neutral-700 w-96 shadow-xl rounded-md bg-white dark:bg-neutral-800">
            <div class="mt-3 text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 dark:bg-red-900/30 mb-4">
                    <svg class="h-6 w-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-neutral-900 dark:text-white mb-4">Konfirmasi Hapus Pengguna</h3>
                <p class="text-sm text-neutral-600 dark:text-neutral-400 mb-6">
                    Apakah Anda yakin ingin menghapus pengguna <strong>{{ $deletingUser->name }}</strong>?
                    <br>Tindakan ini tidak dapat dibatalkan.
                </p>

                <div class="flex justify-center space-x-3">
                    <button wire:click="closeDeleteModal" class="px-4 py-2 text-sm font-medium text-neutral-700 dark:text-neutral-300 bg-white dark:bg-neutral-700 border border-neutral-300 dark:border-neutral-600 rounded-md hover:bg-neutral-50 dark:hover:bg-neutral-600 transition">
                        Batal
                    </button>
                    <button wire:click="deleteUser" class="px-4 py-2 text-sm font-medium text-white bg-red-600 border border-transparent rounded-md hover:bg-red-700 transition">
                        Hapus
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Suspend User Modal -->
    @if($showSuspendModal && $suspendingUser)
    <div class="fixed inset-0 bg-neutral-900/70 dark:bg-neutral-950/80 backdrop-blur-sm overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border border-neutral-200 dark:border-neutral-700 w-96 shadow-xl rounded-md bg-white dark:bg-neutral-800">
            <div class="mt-3">
                <h3 class="text-lg font-medium text-neutral-900 dark:text-white mb-4">Suspend Pengguna</h3>
                <div class="mb-4 p-3 bg-yellow-50 dark:bg-yellow-900/30 border border-yellow-200 dark:border-yellow-800 rounded">
                    <p class="text-sm text-yellow-800 dark:text-yellow-300">
                        Anda akan menangguhkan akses untuk: <strong>{{ $suspendingUser->name }}</strong>
                    </p>
                </div>
                
                <form wire:submit="suspendUser">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1">Alasan Penangguhan</label>
                            <textarea wire:model="suspendReason" rows="3" class="w-full px-3 py-2 border border-neutral-300 dark:border-neutral-600 rounded-md bg-white dark:bg-neutral-800 text-neutral-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Masukkan alasan penangguhan..." required></textarea>
                            @error('suspendReason') <span class="text-red-500 dark:text-red-400 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1">Durasi (hari)</label>
                            <input wire:model="suspendDuration" type="number" min="1" max="365" class="w-full px-3 py-2 border border-neutral-300 dark:border-neutral-600 rounded-md bg-white dark:bg-neutral-800 text-neutral-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="30" required />
                            @error('suspendDuration') <span class="text-red-500 dark:text-red-400 text-sm">{{ $message }}</span> @enderror
                            <p class="text-xs text-neutral-500 dark:text-neutral-400 mt-1">Maksimal 365 hari</p>
                        </div>
                    </div>

                    <div class="flex justify-end space-x-3 mt-6">
                        <button type="button" wire:click="closeSuspendModal" class="px-4 py-2 text-sm font-medium text-neutral-700 dark:text-neutral-300 bg-white dark:bg-neutral-700 border border-neutral-300 dark:border-neutral-600 rounded-md hover:bg-neutral-50 dark:hover:bg-neutral-600 transition">
                            Batal
                        </button>
                        <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-yellow-600 border border-transparent rounded-md hover:bg-yellow-700 transition">
                            Suspend User
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    <!-- User Detail Modal -->
    @if($showDetailModal && $selectedUser)
    <div class="fixed inset-0 bg-neutral-900/70 dark:bg-neutral-950/80 backdrop-blur-sm overflow-y-auto h-full w-full z-50">
        <div class="relative top-10 mx-auto p-5 border border-neutral-200 dark:border-neutral-700 w-full max-w-2xl shadow-xl rounded-md bg-white dark:bg-neutral-800">
            <div class="mt-3">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-medium text-neutral-900 dark:text-white">Detail Pengguna</h3>
                    <button wire:click="closeDetailModal" class="text-neutral-400 hover:text-neutral-600 dark:hover:text-neutral-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Basic Info -->
                    <div class="space-y-4">
                        <div class="flex items-center space-x-4">
                            <div class="h-16 w-16 rounded-full bg-gradient-to-r from-blue-500 to-purple-500 flex items-center justify-center text-white font-bold text-xl">
                                {{ substr($selectedUser->name, 0, 2) }}
                            </div>
                            <div>
                                <h4 class="text-lg font-semibold text-neutral-900 dark:text-white">{{ $selectedUser->name }}</h4>
                                <p class="text-sm text-neutral-600 dark:text-neutral-400">{{ $selectedUser->email }}</p>
                            </div>
                        </div>

                        <div class="border-t border-neutral-200 dark:border-neutral-600 pt-4">
                            <dl class="space-y-3">
                                <div>
                                    <dt class="text-sm font-medium text-neutral-500 dark:text-neutral-400">Role</dt>
                                    <dd class="mt-1">
                                        @foreach($selectedUser->roles as $role)
                                            @php
                                                $roleColors = [
                                                    'admin' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300',
                                                    'superadmin' => 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-300',
                                                    'pemerintah' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300',
                                                    'akademisi' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
                                                    'user' => 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300',
                                                ];
                                                $colorClass = $roleColors[$role->name] ?? 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300';
                                            @endphp
                                            <span class="{{ $colorClass }} inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium">
                                                {{ ucfirst($role->name) }}
                                            </span>
                                        @endforeach
                                    </dd>
                                </div>

                                <div>
                                    <dt class="text-sm font-medium text-neutral-500 dark:text-neutral-400">Status</dt>
                                    <dd class="mt-1">
                                        @if($selectedUser->suspended_at && $selectedUser->suspended_until > now())
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300">
                                                Suspended sampai {{ $selectedUser->suspended_until->format('d/m/Y') }}
                                            </span>
                                            @if($selectedUser->suspend_reason)
                                                <p class="text-sm text-neutral-600 dark:text-neutral-400 mt-2">
                                                    <strong>Alasan:</strong> {{ $selectedUser->suspend_reason }}
                                                </p>
                                            @endif
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300">
                                                Aktif
                                            </span>
                                        @endif
                                    </dd>
                                </div>

                                <div>
                                    <dt class="text-sm font-medium text-neutral-500 dark:text-neutral-400">Bergabung</dt>
                                    <dd class="mt-1 text-sm text-neutral-900 dark:text-white">
                                        {{ $selectedUser->created_at->format('d F Y, H:i') }}
                                        <span class="text-neutral-500 dark:text-neutral-400">({{ $selectedUser->created_at->diffForHumans() }})</span>
                                    </dd>
                                </div>

                                @if($selectedUser->email_verified_at)
                                <div>
                                    <dt class="text-sm font-medium text-neutral-500 dark:text-neutral-400">Email Terverifikasi</dt>
                                    <dd class="mt-1 text-sm text-neutral-900 dark:text-white">
                                        {{ $selectedUser->email_verified_at->format('d F Y, H:i') }}
                                    </dd>
                                </div>
                                @endif
                            </dl>
                        </div>
                    </div>

                    <!-- Registration Info -->
                    <div class="space-y-4">
                        <h5 class="font-medium text-neutral-900 dark:text-white">Informasi Registrasi</h5>
                        
                        @if(isset($selectedUser->registrasi) && $selectedUser->registrasi)
                            <div class="bg-neutral-50 dark:bg-neutral-700 p-4 rounded-lg">
                                <dl class="space-y-3">
                                    <div>
                                        <dt class="text-sm font-medium text-neutral-500 dark:text-neutral-400">Tipe Akses</dt>
                                        <dd class="mt-1 text-sm text-neutral-900 dark:text-white">{{ ucfirst($selectedUser->registrasi->tipe_akses) }}</dd>
                                    </div>

                                    @if($selectedUser->registrasi->instansi)
                                    <div>
                                        <dt class="text-sm font-medium text-neutral-500 dark:text-neutral-400">Instansi</dt>
                                        <dd class="mt-1 text-sm text-neutral-900 dark:text-white">{{ $selectedUser->registrasi->instansi }}</dd>
                                    </div>
                                    @endif

                                    @if($selectedUser->registrasi->institusi)
                                    <div>
                                        <dt class="text-sm font-medium text-neutral-500 dark:text-neutral-400">Institusi</dt>
                                        <dd class="mt-1 text-sm text-neutral-900 dark:text-white">{{ $selectedUser->registrasi->institusi }}</dd>
                                    </div>
                                    @endif

                                    @if($selectedUser->registrasi->jabatan)
                                    <div>
                                        <dt class="text-sm font-medium text-neutral-500 dark:text-neutral-400">Jabatan</dt>
                                        <dd class="mt-1 text-sm text-neutral-900 dark:text-white">{{ $selectedUser->registrasi->jabatan }}</dd>
                                    </div>
                                    @endif

                                    <div>
                                        <dt class="text-sm font-medium text-neutral-500 dark:text-neutral-400">Status Registrasi</dt>
                                        <dd class="mt-1">
                                            @php
                                                $statusColors = [
                                                    'pending' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300',
                                                    'approved' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
                                                    'rejected' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300',
                                                ];
                                                $statusColor = $statusColors[$selectedUser->registrasi->status] ?? 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300';
                                            @endphp
                                            <span class="{{ $statusColor }} inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium">
                                                {{ ucfirst($selectedUser->registrasi->status) }}
                                            </span>
                                        </dd>
                                    </div>

                                    @if($selectedUser->registrasi->dokumen_uploaded_at)
                                    <div>
                                        <dt class="text-sm font-medium text-neutral-500 dark:text-neutral-400">Dokumen Diupload</dt>
                                        <dd class="mt-1 text-sm text-neutral-900 dark:text-white">
                                            {{ $selectedUser->registrasi->dokumen_uploaded_at->format('d F Y, H:i') }}
                                        </dd>
                                    </div>
                                    @endif
                                </dl>
                            </div>
                        @else
                            <div class="bg-neutral-50 dark:bg-neutral-700 p-4 rounded-lg">
                                <p class="text-sm text-neutral-600 dark:text-neutral-400">
                                    Tidak ada data registrasi ditemukan. User mungkin dibuat langsung oleh admin.
                                </p>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="flex justify-end mt-6">
                    <button wire:click="closeDetailModal" class="px-4 py-2 text-sm font-medium text-neutral-700 dark:text-neutral-300 bg-white dark:bg-neutral-700 border border-neutral-300 dark:border-neutral-600 rounded-md hover:bg-neutral-50 dark:hover:bg-neutral-600 transition">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- User Activity Modal -->
    @if($showActivityModal && $selectedUser)
    <div class="fixed inset-0 bg-neutral-900/70 dark:bg-neutral-950/80 backdrop-blur-sm overflow-y-auto h-full w-full z-50">
        <div class="relative top-10 mx-auto p-5 border border-neutral-200 dark:border-neutral-700 w-full max-w-3xl shadow-xl rounded-md bg-white dark:bg-neutral-800">
            <div class="mt-3">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-medium text-neutral-900 dark:text-white">Aktivitas Pengguna: {{ $selectedUser->name }}</h3>
                    <button wire:click="closeActivityModal" class="text-neutral-400 hover:text-neutral-600 dark:hover:text-neutral-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <div class="bg-yellow-50 dark:bg-yellow-900/30 border border-yellow-200 dark:border-yellow-800 rounded-lg p-4 mb-6">
                    <div class="flex">
                        <svg class="w-5 h-5 text-yellow-400 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-yellow-800 dark:text-yellow-300">Fitur dalam Pengembangan</h3>
                            <p class="mt-2 text-sm text-yellow-700 dark:text-yellow-400">
                                Fitur monitoring aktivitas user sedang dalam tahap pengembangan. Data berikut adalah contoh dan akan diganti dengan data real di versi production.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="space-y-4">
                    @php
                        $mockActivities = [
                            [
                                'action' => 'Login',
                                'description' => 'User berhasil login ke sistem',
                                'ip_address' => '192.168.1.100',
                                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                                'created_at' => now()->subHours(2),
                            ],
                            [
                                'action' => 'Export Data',
                                'description' => 'Download laporan NBM dalam format Excel',
                                'ip_address' => '192.168.1.100',
                                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                                'created_at' => now()->subHours(3),
                            ],
                            [
                                'action' => 'View Dashboard',
                                'description' => 'Mengakses dashboard NBM',
                                'ip_address' => '192.168.1.100',
                                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                                'created_at' => now()->subHours(4),
                            ],
                            [
                                'action' => 'Logout',
                                'description' => 'User keluar dari sistem',
                                'ip_address' => '192.168.1.100',
                                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                                'created_at' => now()->subDay(),
                            ],
                        ];
                    @endphp

                    @foreach($mockActivities as $activity)
                    <div class="flex items-start space-x-3 p-4 bg-neutral-50 dark:bg-neutral-700 rounded-lg">
                        <div class="flex-shrink-0">
                            @php
                                $iconColors = [
                                    'Login' => 'text-green-600',
                                    'Logout' => 'text-red-600',
                                    'Export Data' => 'text-blue-600',
                                    'View Dashboard' => 'text-purple-600',
                                ];
                                $iconColor = $iconColors[$activity['action']] ?? 'text-gray-600';
                            @endphp
                            <div class="w-8 h-8 bg-white dark:bg-neutral-600 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 {{ $iconColor }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    @if($activity['action'] === 'Login')
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                                    @elseif($activity['action'] === 'Logout')
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                    @elseif($activity['action'] === 'Export Data')
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    @else
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                    @endif
                                </svg>
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between">
                                <p class="text-sm font-medium text-neutral-900 dark:text-white">{{ $activity['action'] }}</p>
                                <p class="text-xs text-neutral-500 dark:text-neutral-400">{{ $activity['created_at']->diffForHumans() }}</p>
                            </div>
                            <p class="text-sm text-neutral-600 dark:text-neutral-400">{{ $activity['description'] }}</p>
                            <div class="mt-2 text-xs text-neutral-500 dark:text-neutral-400">
                                <span>IP: {{ $activity['ip_address'] }}</span>
                                <span class="mx-2">•</span>
                                <span>{{ $activity['created_at']->format('d/m/Y H:i:s') }}</span>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="flex justify-end mt-6">
                    <button wire:click="closeActivityModal" class="px-4 py-2 text-sm font-medium text-neutral-700 dark:text-neutral-300 bg-white dark:bg-neutral-700 border border-neutral-300 dark:border-neutral-600 rounded-md hover:bg-neutral-50 dark:hover:bg-neutral-600 transition">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
