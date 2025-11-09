<x-layouts.pemerintah title="Benih & Pupuk - Panel Pemerintah - {{ config('app.name') }}">
    <div class="container mx-auto px-4 py-8">
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-neutral-900 dark:text-white">Data Benih & Pupuk</h1>
            <p class="text-neutral-600 dark:text-neutral-400 mt-1">Informasi Sarana Produksi Pertanian</p>
        </div>

        <div class="mb-6 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-4">
            <div class="flex items-center">
                <svg class="w-5 h-5 text-yellow-600 dark:text-yellow-400 mr-3" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                </svg>
                <p class="text-sm text-yellow-800 dark:text-yellow-300">
                    <strong>Halaman dalam pengembangan.</strong> Fitur data benih dan pupuk akan segera tersedia.
                </p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-white dark:bg-neutral-800 rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold text-neutral-900 dark:text-white mb-4">Data Benih</h2>
                <div class="space-y-4">
                    <div class="flex items-center p-3 bg-neutral-50 dark:bg-neutral-900 rounded-lg">
                        <svg class="w-8 h-8 text-green-600 dark:text-green-400 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M12.316 3.051a1 1 0 01.633 1.265l-4 12a1 1 0 11-1.898-.632l4-12a1 1 0 011.265-.633zM5.707 6.293a1 1 0 010 1.414L3.414 10l2.293 2.293a1 1 0 11-1.414 1.414l-3-3a1 1 0 010-1.414l3-3a1 1 0 011.414 0zm8.586 0a1 1 0 011.414 0l3 3a1 1 0 010 1.414l-3 3a1 1 0 11-1.414-1.414L16.586 10l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd"/>
                        </svg>
                        <div>
                            <p class="font-medium text-neutral-900 dark:text-white">Jenis Benih</p>
                            <p class="text-sm text-neutral-600 dark:text-neutral-400">Varietas benih yang tersedia</p>
                        </div>
                    </div>
                    <div class="flex items-center p-3 bg-neutral-50 dark:bg-neutral-900 rounded-lg">
                        <svg class="w-8 h-8 text-blue-600 dark:text-blue-400 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z"/>
                        </svg>
                        <div>
                            <p class="font-medium text-neutral-900 dark:text-white">Distribusi Benih</p>
                            <p class="text-sm text-neutral-600 dark:text-neutral-400">Penyaluran benih ke petani</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-neutral-800 rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold text-neutral-900 dark:text-white mb-4">Data Pupuk</h2>
                <div class="space-y-4">
                    <div class="flex items-center p-3 bg-neutral-50 dark:bg-neutral-900 rounded-lg">
                        <svg class="w-8 h-8 text-purple-600 dark:text-purple-400 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                            <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/>
                        </svg>
                        <div>
                            <p class="font-medium text-neutral-900 dark:text-white">Jenis Pupuk</p>
                            <p class="text-sm text-neutral-600 dark:text-neutral-400">Organik dan anorganik</p>
                        </div>
                    </div>
                    <div class="flex items-center p-3 bg-neutral-50 dark:bg-neutral-900 rounded-lg">
                        <svg class="w-8 h-8 text-orange-600 dark:text-orange-400 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                        </svg>
                        <div>
                            <p class="font-medium text-neutral-900 dark:text-white">Jadwal Distribusi</p>
                            <p class="text-sm text-neutral-600 dark:text-neutral-400">Waktu penyaluran pupuk</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.pemerintah>
