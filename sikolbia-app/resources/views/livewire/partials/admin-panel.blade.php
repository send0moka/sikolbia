<!-- Admin Panel Content -->
<div>
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 @if(auth()->user()->hasRole('superadmin')) xl:grid-cols-4 lg:grid-cols-2 @else xl:grid-cols-3 lg:grid-cols-3 md:grid-cols-3 @endif gap-4 lg:gap-6 mb-8">
        <!-- Current User Info -->
        <div class="bg-white dark:!bg-neutral-800 overflow-hidden shadow-lg rounded-lg border border-neutral-200 dark:border-neutral-700">
            <div class="p-4 lg:p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 lg:w-12 lg:h-12 bg-indigo-100 dark:bg-indigo-900 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 lg:w-6 lg:h-6 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-3 lg:ml-4 min-w-0 flex-1">
                        <h3 class="text-base lg:text-lg font-semibold text-neutral-900 dark:text-white truncate">Status akun</h3>
                        <p class="text-2xl lg:text-3xl font-bold text-indigo-600 dark:text-indigo-400 truncate">{{ auth()->user()->name }}</p>
                        <p class="text-xs lg:text-sm text-neutral-500 dark:text-neutral-400 truncate">
                            {{ ucfirst(auth()->user()->roles->first()?->name ?? 'No Role') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        @if(auth()->user()->hasRole('superadmin'))
        <!-- Total Users (Only for Superadmin) -->
        <div class="bg-white dark:!bg-neutral-800 overflow-hidden shadow-lg rounded-lg border border-neutral-200 dark:border-neutral-700">
            <div class="p-4 lg:p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 lg:w-12 lg:h-12 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 lg:w-6 lg:h-6 text-blue-600 dark:text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-3 lg:ml-4 min-w-0 flex-1">
                        <h3 class="text-base lg:text-lg font-semibold text-neutral-900 dark:text-white truncate">Total User</h3>
                        <p class="text-2xl lg:text-3xl font-bold text-blue-600 dark:text-blue-400">{{ $totalUsers ?? 0 }}</p>
                        <p class="text-xs lg:text-sm text-neutral-500 dark:text-neutral-400 truncate">Pengguna terdaftar</p>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Total Transaksi NBM -->
        <div class="bg-white dark:bg-neutral-800 overflow-hidden shadow-lg rounded-lg border border-neutral-200 dark:border-neutral-700">
            <div class="p-4 lg:p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 lg:w-12 lg:h-12 bg-orange-100 dark:bg-orange-900 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 lg:w-6 lg:h-6 text-orange-600 dark:text-orange-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-3 lg:ml-4 min-w-0 flex-1">
                        <h3 class="text-base lg:text-lg font-semibold text-neutral-900 dark:text-white truncate">Transaksi NBM</h3>
                        <p class="text-2xl lg:text-3xl font-bold text-orange-600 dark:text-orange-400">{{ $totalTransaksiNbm ?? 0 }}</p>
                        <p class="text-xs lg:text-sm text-neutral-500 dark:text-neutral-400 truncate">Data transaksi</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Data Susenas -->
        <div class="bg-white dark:bg-neutral-800 overflow-hidden shadow-lg rounded-lg border border-neutral-200 dark:border-neutral-700">
            <div class="p-4 lg:p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 lg:w-12 lg:h-12 bg-purple-100 dark:bg-purple-900 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 lg:w-6 lg:h-6 text-purple-600 dark:text-purple-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-3 lg:ml-4 min-w-0 flex-1">
                        <h3 class="text-base lg:text-lg font-semibold text-neutral-900 dark:text-white truncate">Data Susenas</h3>
                        <p class="text-2xl lg:text-3xl font-bold text-purple-600 dark:text-purple-400">{{ $totalSusenas ?? 0 }}</p>
                        <p class="text-xs lg:text-sm text-neutral-500 dark:text-neutral-400 truncate">Transaksi konsumsi</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Menu Kelola Data NBM -->
    <div class="mb-6">
        <div class="bg-white dark:!bg-neutral-800 overflow-hidden shadow rounded-lg border border-neutral-200 dark:border-neutral-700">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-neutral-900 dark:text-white mb-4">
                    Menu Transaksi NBM
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Kelompok -->
                    <a href="{{ route('admin.kelompok') }}" class="group block p-4 bg-yellow-50 dark:bg-yellow-900/20 rounded-lg border border-yellow-200 dark:border-yellow-700 hover:bg-yellow-100 dark:hover:bg-yellow-900/30 transition-colors">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-yellow-500 rounded-md flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M17.707 9.293a1 1 0 010 1.414l-7 7a1 1 0 01-1.414 0l-7-7A.997.997 0 012 10V5a3 3 0 013-3h5c.256 0 .512.098.707.293l7 7zM5 6a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-yellow-900 dark:text-yellow-100 group-hover:text-yellow-900">
                                    Kelompok
                                </p>
                                <p class="text-sm text-yellow-700 dark:text-yellow-300">
                                    {{ $totalKelompok ?? 0 }} kelompok
                                </p>
                            </div>
                        </div>
                    </a>

                    <!-- Komoditi -->
                    <a href="{{ route('admin.komoditi') }}" class="group block p-4 bg-orange-50 dark:bg-orange-900/20 rounded-lg border border-orange-200 dark:border-orange-700 hover:bg-orange-100 dark:hover:bg-orange-900/30 transition-colors">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-orange-500 rounded-md flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M7 3a1 1 0 000 2h6a1 1 0 100-2H7zM4 7a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1zM2 11a2 2 0 012-2h12a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-orange-900 dark:text-orange-100 group-hover:text-orange-900">
                                    Komoditi
                                </p>
                                <p class="text-sm text-orange-700 dark:text-orange-300">
                                    {{ $totalKomoditi ?? 0 }} komoditi
                                </p>
                            </div>
                        </div>
                    </a>

                    <!-- Transaksi NBM -->
                    <a href="{{ route('admin.transaksi-nbm') }}" class="group block p-4 bg-red-50 dark:bg-red-900/20 rounded-lg border border-red-200 dark:border-red-700 hover:bg-red-100 dark:hover:bg-red-900/30 transition-colors">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-red-500 rounded-md flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"></path>
                                        <path fill-rule="evenodd" d="M4 5a2 2 0 012-2v1a1 1 0 001 1h6a1 1 0 001-1V3a2 2 0 012 2v6.5A2.5 2.5 0 0113.5 14H12v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2H2.5A2.5 2.5 0 010 11.5V5zm9 4a1 1 0 10-2 0 1 1 0 002 0z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-red-900 dark:text-red-100 group-hover:text-red-900">
                                    Transaksi NBM
                                </p>
                                <p class="text-sm text-red-700 dark:text-red-300">
                                    {{ $totalTransaksiNbm ?? 0 }} transaksi
                                </p>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>

     <!-- Menu Susenas -->
    <div class="mb-6">
        <div class="bg-white dark:!bg-neutral-800 overflow-hidden shadow rounded-lg border border-neutral-200 dark:border-neutral-700">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-neutral-900 dark:text-white mb-4">
                    Menu Susenas
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Kelompok BPS -->
                    <a href="{{ route('admin.kelompok-bps') }}" class="group block p-4 bg-teal-50 dark:bg-teal-900/20 rounded-lg border border-teal-200 dark:border-teal-700 hover:bg-teal-100 dark:hover:bg-teal-900/30 transition-colors">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-teal-500 rounded-md flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-teal-900 dark:text-teal-100 group-hover:text-teal-900">
                                    Kelompok BPS
                                </p>
                                <p class="text-sm text-teal-700 dark:text-teal-300">
                                    {{ $totalKelompokbps ?? 0 }} kelompok
                                </p>
                            </div>
                        </div>
                    </a>

                    <!-- Komoditi BPS -->
                    <a href="{{ route('admin.komoditi-bps') }}" class="group block p-4 bg-indigo-50 dark:bg-indigo-900/20 rounded-lg border border-indigo-200 dark:border-indigo-700 hover:bg-indigo-100 dark:hover:bg-indigo-900/30 transition-colors">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-indigo-500 rounded-md flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-indigo-900 dark:text-indigo-100 group-hover:text-indigo-900">
                                    Komoditi BPS
                                </p>
                                <p class="text-sm text-indigo-700 dark:text-indigo-300">
                                    {{ $totalKomoditibps ?? 0 }} komoditi
                                </p>
                            </div>
                        </div>
                    </a>

                    <!-- Data Susenas -->
                    <a href="{{ route('admin.susenas') }}" class="group block p-4 bg-purple-50 dark:bg-purple-900/20 rounded-lg border border-purple-200 dark:border-purple-700 hover:bg-purple-100 dark:hover:bg-purple-900/30 transition-colors">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-purple-500 rounded-md flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-purple-900 dark:text-purple-100 group-hover:text-purple-900">
                                    Data Susenas
                                </p>
                                <p class="text-sm text-purple-700 dark:text-purple-300">
                                    {{ $totalSusenas ?? 0 }} data
                                </p>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Data NBM Section -->
    <div class="mb-6">
        <div class="bg-white dark:!bg-neutral-800 overflow-hidden shadow rounded-lg border border-neutral-200 dark:border-neutral-700">
            <div class="px-4 py-5 sm:p-6">
                <h2 class="text-xl leading-6 font-bold text-neutral-900 dark:text-white mb-6">
                    Data NBM
                </h2>
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Recent Kelompok NBM -->
                    <div class="bg-neutral-50 dark:bg-neutral-700/50 rounded-lg p-4">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg leading-6 font-medium text-neutral-900 dark:text-white">
                                Kelompok NBM Terbaru
                            </h3>
                            @can('view kelompok')
                            <a href="{{ route('admin.kelompok') }}" class="text-blue-600 hover:text-blue-500 text-sm font-medium" wire:navigate>
                                Lihat Semua
                            </a>
                            @endcan
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm text-left text-neutral-500 dark:text-neutral-400">
                                <thead class="text-xs text-neutral-700 uppercase bg-neutral-100 dark:bg-neutral-600 dark:text-neutral-300">
                                    <tr>
                                        <th scope="col" class="px-3 py-2">Kode</th>
                                        <th scope="col" class="px-3 py-2">Nama</th>
                                        <th scope="col" class="px-3 py-2">Dibuat</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($recentKelompok ?? [] as $kelompok)
                                    <tr class="bg-white border-b dark:!bg-neutral-800 dark:border-neutral-600 hover:bg-neutral-50 dark:hover:!bg-neutral-700 transition-colors">
                                        <td class="px-3 py-2 font-medium text-neutral-900 dark:text-white text-xs">
                                            {{ $kelompok->kode }}
                                        </td>
                                        <td class="px-3 py-2 text-xs">
                                            {{ Str::limit($kelompok->nama, 20) }}
                                        </td>
                                        <td class="px-3 py-2 text-xs">
                                            {{ $kelompok->created_at->format('d/m/Y') }}
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="3" class="px-3 py-3 text-center text-neutral-500 text-xs">
                                            Belum ada kelompok
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Recent Komoditi NBM -->
                    <div class="bg-neutral-50 dark:bg-neutral-700/50 rounded-lg p-4">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg leading-6 font-medium text-neutral-900 dark:text-white">
                                Komoditi NBM Terbaru
                            </h3>
                            @can('view komoditi')
                            <a href="{{ route('admin.komoditi') }}" class="text-blue-600 hover:text-blue-500 text-sm font-medium" wire:navigate>
                                Lihat Semua
                            </a>
                            @endcan
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm text-left text-neutral-500 dark:text-neutral-400">
                                <thead class="text-xs text-neutral-700 uppercase bg-neutral-100 dark:bg-neutral-600 dark:text-neutral-300">
                                    <tr>
                                        <th scope="col" class="px-3 py-2">Kelompok</th>
                                        <th scope="col" class="px-3 py-2">Kode</th>
                                        <th scope="col" class="px-3 py-2">Nama</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($recentKomoditi ?? [] as $komoditi)
                                    <tr class="bg-white border-b dark:!bg-neutral-800 dark:border-neutral-600 hover:bg-neutral-50 dark:hover:!bg-neutral-700 transition-colors">
                                        <td class="px-3 py-2 font-medium text-neutral-900 dark:text-white text-xs">
                                            {{ $komoditi->kode_kelompok }}
                                        </td>
                                        <td class="px-3 py-2 font-medium text-neutral-900 dark:text-white text-xs">
                                            {{ $komoditi->kode_komoditi }}
                                        </td>
                                        <td class="px-3 py-2 text-xs">
                                            {{ Str::limit($komoditi->nama, 20) }}
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="3" class="px-3 py-3 text-center text-neutral-500 text-xs">
                                            Belum ada komoditi
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Recent Transaksi NBM -->
                    <div class="bg-neutral-50 dark:bg-neutral-700/50 rounded-lg p-4">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg leading-6 font-medium text-neutral-900 dark:text-white">
                                Transaksi NBM Terbaru
                            </h3>
                            @can('view transaksi_nbm')
                            <a href="{{ route('admin.transaksi-nbm') }}" class="text-blue-600 hover:text-blue-500 text-sm font-medium" wire:navigate>
                                Lihat Semua
                            </a>
                            @endcan
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm text-left text-neutral-500 dark:text-neutral-400">
                                <thead class="text-xs text-neutral-700 uppercase bg-neutral-100 dark:bg-neutral-600 dark:text-neutral-300">
                                    <tr>
                                        <th scope="col" class="px-3 py-2">Tahun</th>
                                        <th scope="col" class="px-3 py-2">Komoditi</th>
                                        <th scope="col" class="px-3 py-2">Perubahan Stok</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($recentTransaksiNbm ?? [] as $transaksi)
                                    <tr class="bg-white border-b dark:!bg-neutral-800 dark:border-neutral-600 hover:bg-neutral-50 dark:hover:!bg-neutral-700 transition-colors">
                                        <td class="px-3 py-2 font-medium text-neutral-900 dark:text-white text-xs">
                                            {{ $transaksi->tahun }}
                                        </td>
                                        <td class="px-3 py-2 text-xs">
                                            {{ Str::limit($transaksi->komoditi->nama ?? 'N/A', 15) }}
                                        </td>
                                        <td class="px-3 py-2 font-medium text-neutral-900 dark:text-white text-xs">
                                            {{ number_format($transaksi->perubahan_stok ?? 0, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="3" class="px-3 py-3 text-center text-neutral-500 text-xs">
                                            Belum ada transaksi
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Data Susenas Section -->
    <div class="mb-6">
        <div class="bg-white dark:!bg-neutral-800 overflow-hidden shadow rounded-lg border border-neutral-200 dark:border-neutral-700">
            <div class="px-4 py-5 sm:p-6">
                <h2 class="text-xl leading-6 font-bold text-neutral-900 dark:text-white mb-6">
                    Data Susenas
                </h2>
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Recent Kelompok BPS -->
                    <div class="bg-neutral-50 dark:bg-neutral-700/50 rounded-lg p-4">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg leading-6 font-medium text-neutral-900 dark:text-white">
                                Kelompok BPS Terbaru
                            </h3>
                            @can('view kelompokbps')
                            <a href="{{ route('admin.kelompok-bps') }}" class="text-blue-600 hover:text-blue-500 text-sm font-medium" wire:navigate>
                                Lihat Semua
                            </a>
                            @endcan
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm text-left text-neutral-500 dark:text-neutral-400">
                                <thead class="text-xs text-neutral-700 uppercase bg-neutral-100 dark:bg-neutral-600 dark:text-neutral-300">
                                    <tr>
                                        <th scope="col" class="px-3 py-2">Kode</th>
                                        <th scope="col" class="px-3 py-2">Nama</th>
                                        <th scope="col" class="px-3 py-2">Dibuat</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($recentKelompokbps ?? [] as $kelompokbps)
                                    <tr class="bg-white border-b dark:!bg-neutral-800 dark:border-neutral-600 hover:bg-neutral-50 dark:hover:!bg-neutral-700 transition-colors">
                                        <td class="px-3 py-2 font-medium text-neutral-900 dark:text-white text-xs">
                                            {{ $kelompokbps->kd_kelompokbps }}
                                        </td>
                                        <td class="px-3 py-2 text-xs">
                                            {{ Str::limit($kelompokbps->nm_kelompokbps, 20) }}
                                        </td>
                                        <td class="px-3 py-2 text-xs">
                                            {{ $kelompokbps->created_at->format('d/m/Y') }}
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="3" class="px-3 py-3 text-center text-neutral-500 text-xs">
                                            Belum ada kelompok BPS
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Recent Komoditi BPS -->
                    <div class="bg-neutral-50 dark:bg-neutral-700/50 rounded-lg p-4">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg leading-6 font-medium text-neutral-900 dark:text-white">
                                Komoditi BPS Terbaru
                            </h3>
                            @can('view komoditibps')
                            <a href="{{ route('admin.komoditi-bps') }}" class="text-blue-600 hover:text-blue-500 text-sm font-medium" wire:navigate>
                                Lihat Semua
                            </a>
                            @endcan
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm text-left text-neutral-500 dark:text-neutral-400">
                                <thead class="text-xs text-neutral-700 uppercase bg-neutral-100 dark:bg-neutral-600 dark:text-neutral-300">
                                    <tr>
                                        <th scope="col" class="px-3 py-2">Kelompok</th>
                                        <th scope="col" class="px-3 py-2">Kode</th>
                                        <th scope="col" class="px-3 py-2">Nama</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($recentKomoditibps ?? [] as $komoditibps)
                                    <tr class="bg-white border-b dark:!bg-neutral-800 dark:border-neutral-600 hover:bg-neutral-50 dark:hover:!bg-neutral-700 transition-colors">
                                        <td class="px-3 py-2 font-medium text-neutral-900 dark:text-white text-xs">
                                            {{ $komoditibps->kd_kelompokbps }}
                                        </td>
                                        <td class="px-3 py-2 font-medium text-neutral-900 dark:text-white text-xs">
                                            {{ $komoditibps->kd_komoditibps }}
                                        </td>
                                        <td class="px-3 py-2 text-xs">
                                            {{ Str::limit($komoditibps->nm_komoditibps, 20) }}
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="3" class="px-3 py-3 text-center text-neutral-500 text-xs">
                                            Belum ada komoditi BPS
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Recent Susenas Data -->
                    <div class="bg-neutral-50 dark:bg-neutral-700/50 rounded-lg p-4">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg leading-6 font-medium text-neutral-900 dark:text-white">
                                Data Susenas Terbaru
                            </h3>
                            @can('view susenas')
                            <a href="{{ route('admin.susenas') }}" class="text-blue-600 hover:text-blue-500 text-sm font-medium" wire:navigate>
                                Lihat Semua
                            </a>
                            @endcan
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm text-left text-neutral-500 dark:text-neutral-400">
                                <thead class="text-xs text-neutral-700 uppercase bg-neutral-100 dark:bg-neutral-600 dark:text-neutral-300">
                                    <tr>
                                        <th scope="col" class="px-3 py-2">Tahun</th>
                                        <th scope="col" class="px-3 py-2">Komoditi</th>
                                        <th scope="col" class="px-3 py-2">Konsumsi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($recentSusenas ?? [] as $susenas)
                                    <tr class="bg-white border-b dark:!bg-neutral-800 dark:border-neutral-600 hover:bg-neutral-50 dark:hover:!bg-neutral-700 transition-colors">
                                        <td class="px-3 py-2 font-medium text-neutral-900 dark:text-white text-xs">
                                            {{ $susenas->tahun }}
                                        </td>
                                        <td class="px-3 py-2 text-xs">
                                            {{ Str::limit($susenas->komoditibps->nm_komoditibps ?? 'N/A', 15) }}
                                        </td>
                                        <td class="px-3 py-2 font-medium text-neutral-900 dark:text-white text-xs">
                                            {{ number_format($susenas->konsumsikuantity, 2) }}
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="3" class="px-3 py-3 text-center text-neutral-500 text-xs">
                                            Belum ada data susenas
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if(auth()->user()->hasRole('superadmin'))
        <!-- Recent Users (Only for Superadmin) -->
        <div class="bg-white dark:!bg-neutral-800 overflow-hidden shadow rounded-lg border border-neutral-200 dark:border-neutral-700">
            <div class="px-4 py-5 sm:p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg leading-6 font-medium text-neutral-900 dark:text-white">
                        User Terbaru
                    </h3>
                    <a href="{{ route('admin.users') }}" class="text-blue-600 hover:text-blue-500 text-sm font-medium" wire:navigate>
                        Lihat Semua
                    </a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-neutral-500 dark:text-neutral-400">
                        <thead class="text-xs text-neutral-700 uppercase bg-neutral-50 dark:bg-neutral-700 dark:text-neutral-400">
                            <tr>
                                <th scope="col" class="px-6 py-3">Nama</th>
                                <th scope="col" class="px-6 py-3">Email</th>
                                <th scope="col" class="px-6 py-3">Role</th>
                                <th scope="col" class="px-6 py-3">Bergabung</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($recentUsers ?? [] as $user)
                            <tr class="bg-white border-b dark:!bg-neutral-800 dark:border-neutral-700 hover:bg-neutral-50 dark:hover:!bg-neutral-700 transition-colors">
                                <td class="px-6 py-4 font-medium text-neutral-900 dark:text-white">
                                    {{ $user->name }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $user->email }}
                                </td>
                                <td class="px-6 py-4">
                                    @foreach($user->roles as $role)
                                        <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-blue-900 dark:text-blue-300">
                                            {{ ucfirst($role->name) }}
                                        </span>
                                    @endforeach
                                </td>
                                <td class="px-6 py-4">
                                    {{ $user->created_at->format('d/m/Y H:i') }}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-6 py-4 text-center text-neutral-500">
                                    Belum ada user
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif
</div>