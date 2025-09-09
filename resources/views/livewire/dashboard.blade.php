<div>
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-neutral-900 dark:text-white">Dashboard</h1>
        <p class="mt-1 text-sm text-neutral-600 dark:text-neutral-400">
            Selamat datang di sistem basis data konsumsi pangan
        </p>
    </div>

    @if(auth()->user()->hasRole(['superadmin', 'admin']))
        <!-- Admin Panel Content -->
        @include('livewire.partials.admin-panel', [
            'totalUsers' => $totalUsers ?? 0,
            'totalRoles' => $totalRoles ?? 0,
            'totalKelompok' => $totalKelompok ?? 0,
            'totalKomoditi' => $totalKomoditi ?? 0,
            'totalTransaksiNbm' => $totalTransaksiNbm ?? 0,
            'recentUsers' => $recentUsers ?? collect(),
            'recentKelompok' => $recentKelompok ?? collect(),
            'recentKomoditi' => $recentKomoditi ?? collect()
        ])
    @else
        <!-- Regular User Content -->
        <div class="grid auto-rows-min gap-4 md:grid-cols-3 mb-6">
        <div class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
        <x-placeholder-pattern class="absolute inset-0 size-full stroke-neutral-900/20 dark:stroke-neutral-100/20" />
                <div class="absolute inset-0 p-4 flex items-center justify-center">
                    <div class="text-center">
            <h3 class="text-lg font-semibold text-neutral-900 dark:text-white">Data Konsumsi</h3>
            <p class="text-sm text-neutral-600 dark:text-neutral-400">Chart data konsumsi pangan</p>
                    </div>
                </div>
            </div>
            <div class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
        <x-placeholder-pattern class="absolute inset-0 size-full stroke-neutral-900/20 dark:stroke-neutral-100/20" />
                <div class="absolute inset-0 p-4 flex items-center justify-center">
                    <div class="text-center">
            <h3 class="text-lg font-semibold text-neutral-900 dark:text-white">Statistik</h3>
            <p class="text-sm text-neutral-600 dark:text-neutral-400">Ringkasan statistik</p>
                    </div>
                </div>
            </div>
            <div class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
        <x-placeholder-pattern class="absolute inset-0 size-full stroke-neutral-900/20 dark:stroke-neutral-100/20" />
                <div class="absolute inset-0 p-4 flex items-center justify-center">
                    <div class="text-center">
            <h3 class="text-lg font-semibold text-neutral-900 dark:text-white">Laporan</h3>
            <p class="text-sm text-neutral-600 dark:text-neutral-400">Laporan bulanan</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="relative h-64 flex-1 overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
        <x-placeholder-pattern class="absolute inset-0 size-full stroke-neutral-900/20 dark:stroke-neutral-100/20" />
            <div class="absolute inset-0 p-4 flex items-center justify-center">
                <div class="text-center">
            <h3 class="text-xl font-semibold text-neutral-900 dark:text-white mb-2">Sistem Basis Data Konsumsi Pangan</h3>
            <p class="text-neutral-600 dark:text-neutral-400">Dashboard untuk mengelola data konsumsi pangan</p>
                </div>
            </div>
        </div>
    @endif
</div>
