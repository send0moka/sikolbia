<x-layouts.akademisi>
    <!-- Page Header -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-neutral-900 dark:text-white">Data NBM Historis</h1>
        <p class="text-neutral-600 dark:text-neutral-400 mt-2">Browse dan filter data Neraca Bahan Makanan Indonesia</p>
    </div>

    <!-- Filter Form -->
    <div class="bg-white dark:bg-neutral-800 rounded-lg shadow p-6 mb-6">
        <form method="GET" action="{{ route('akademisi.data-nbm') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Tahun Filter -->
            <div>
                <label for="tahun" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                    Tahun
                </label>
                <select name="tahun" id="tahun"
                        class="w-full px-3 py-2 bg-white dark:bg-neutral-700 border border-neutral-300 dark:border-neutral-600 rounded-lg focus:ring-2 focus:ring-purple-500 dark:text-white">
                    <option value="">Semua Tahun</option>
                    @foreach($tahunList as $tahun)
                        <option value="{{ $tahun }}" {{ request('tahun') == $tahun ? 'selected' : '' }}>
                            {{ $tahun }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Kelompok Filter -->
            <div>
                <label for="kelompok" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                    Kelompok
                </label>
                <select name="kelompok" id="kelompok"
                        class="w-full px-3 py-2 bg-white dark:bg-neutral-700 border border-neutral-300 dark:border-neutral-600 rounded-lg focus:ring-2 focus:ring-purple-500 dark:text-white">
                    <option value="">Semua Kelompok</option>
                    @foreach($kelompokList as $kelompok)
                        <option value="{{ $kelompok->kode }}" {{ request('kelompok') == $kelompok->kode ? 'selected' : '' }}>
                            {{ $kelompok->kode }} - {{ $kelompok->nama }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Komoditi Filter -->
            <div>
                <label for="komoditi" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                    Komoditas
                </label>
                <select name="komoditi" id="komoditi"
                        class="w-full px-3 py-2 bg-white dark:bg-neutral-700 border border-neutral-300 dark:border-neutral-600 rounded-lg focus:ring-2 focus:ring-purple-500 dark:text-white">
                    <option value="">Semua Komoditas</option>
                    @foreach($komoditiList as $komoditi)
                        <option value="{{ $komoditi->kode_komoditi }}" 
                                data-kelompok="{{ $komoditi->kode_kelompok }}"
                                {{ request('komoditi') == $komoditi->kode_komoditi ? 'selected' : '' }}>
                            {{ $komoditi->kode_komoditi }} - {{ $komoditi->nama }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Submit Button -->
            <div class="flex items-end gap-2">
                <button type="submit"
                        class="flex-1 px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white font-medium rounded-lg transition-colors">
                    <svg class="inline w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    Filter
                </button>
                <a href="{{ route('akademisi.data-nbm') }}"
                   class="px-4 py-2 bg-neutral-200 hover:bg-neutral-300 dark:bg-neutral-600 dark:hover:bg-neutral-500 text-neutral-700 dark:text-neutral-200 font-medium rounded-lg transition-colors">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Data Table -->
    <div class="bg-white dark:bg-neutral-800 rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-neutral-50 dark:bg-neutral-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 dark:text-neutral-300 uppercase tracking-wider">
                            Tahun
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 dark:text-neutral-300 uppercase tracking-wider">
                            Bulan
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 dark:text-neutral-300 uppercase tracking-wider">
                            Kelompok
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 dark:text-neutral-300 uppercase tracking-wider">
                            Komoditas
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-neutral-500 dark:text-neutral-300 uppercase tracking-wider">
                            Kalori/Hari
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-neutral-500 dark:text-neutral-300 uppercase tracking-wider">
                            Protein/Hari
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-neutral-500 dark:text-neutral-300 uppercase tracking-wider">
                            Lemak/Hari
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-200 dark:divide-neutral-700">
                    @forelse($transaksiNbm as $transaksi)
                    <tr class="hover:bg-neutral-50 dark:hover:bg-neutral-700/50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-neutral-900 dark:text-white">
                            {{ $transaksi->tahun }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-700 dark:text-neutral-300">
                            {{ $transaksi->bulan }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-700 dark:text-neutral-300">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300">
                                {{ $transaksi->kelompok ? $transaksi->kelompok->nama : $transaksi->kode_kelompok }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-neutral-700 dark:text-neutral-300">
                            {{ $transaksi->komoditi ? $transaksi->komoditi->nama : $transaksi->kode_komoditi }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-medium text-neutral-900 dark:text-white">
                            {{ number_format($transaksi->kalori_hari ?? 0, 2) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-neutral-700 dark:text-neutral-300">
                            {{ number_format($transaksi->protein_hari ?? 0, 2) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-neutral-700 dark:text-neutral-300">
                            {{ number_format($transaksi->lemak_hari ?? 0, 2) }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center">
                            <svg class="mx-auto h-12 w-12 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-neutral-900 dark:text-white">Tidak ada data</h3>
                            <p class="mt-1 text-sm text-neutral-500 dark:text-neutral-400">Coba ubah filter pencarian Anda</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($transaksiNbm->hasPages())
        <div class="px-6 py-4 border-t border-neutral-200 dark:border-neutral-700">
            <!-- Custom Pagination -->
            <nav role="navigation" aria-label="Pagination Navigation" class="flex items-center justify-between">
                <div class="flex justify-between flex-1 sm:hidden">
                    @if ($transaksiNbm->onFirstPage())
                        <span class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 cursor-default leading-5 rounded-md dark:text-gray-600 dark:bg-gray-800 dark:border-gray-600">
                            Previous
                        </span>
                    @else
                        <a href="{{ $transaksiNbm->previousPageUrl() }}" class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 leading-5 rounded-md hover:text-gray-500 focus:outline-none focus:ring ring-gray-300 focus:border-blue-300 active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-300 dark:focus:border-blue-700 dark:active:bg-gray-700 dark:active:text-gray-300">
                            Previous
                        </a>
                    @endif

                    @if ($transaksiNbm->hasMorePages())
                        <a href="{{ $transaksiNbm->nextPageUrl() }}" class="relative inline-flex items-center px-4 py-2 ml-3 text-sm font-medium text-gray-700 bg-white border border-gray-300 leading-5 rounded-md hover:text-gray-500 focus:outline-none focus:ring ring-gray-300 focus:border-blue-300 active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-300 dark:focus:border-blue-700 dark:active:bg-gray-700 dark:active:text-gray-300">
                            Next
                        </a>
                    @else
                        <span class="relative inline-flex items-center px-4 py-2 ml-3 text-sm font-medium text-gray-500 bg-white border border-gray-300 cursor-default leading-5 rounded-md dark:text-gray-600 dark:bg-gray-800 dark:border-gray-600">
                            Next
                        </span>
                    @endif
                </div>

                <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                    <div>
                        <p class="text-sm text-gray-700 leading-5 dark:text-gray-400">
                            Showing
                            <span class="font-medium">{{ $transaksiNbm->firstItem() }}</span>
                            to
                            <span class="font-medium">{{ $transaksiNbm->lastItem() }}</span>
                            of
                            <span class="font-medium">{{ $transaksiNbm->total() }}</span>
                            results
                        </p>
                    </div>

                    <div>
                        <span class="relative z-0 inline-flex rtl:flex-row-reverse shadow-sm rounded-md">
                            {{-- Previous Page Link --}}
                            @if ($transaksiNbm->onFirstPage())
                                <span aria-disabled="true" aria-label="Previous">
                                    <span class="relative inline-flex items-center px-2 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 cursor-default rounded-l-md leading-5 dark:bg-gray-800 dark:border-gray-600" aria-hidden="true">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                                        </svg>
                                    </span>
                                </span>
                            @else
                                <a href="{{ $transaksiNbm->previousPageUrl() }}" rel="prev" class="relative inline-flex items-center px-2 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-l-md leading-5 hover:text-gray-400 focus:z-10 focus:outline-none focus:ring ring-gray-300 focus:border-blue-300 active:bg-gray-100 active:text-gray-500 transition ease-in-out duration-150 dark:bg-gray-800 dark:border-gray-600 dark:active:bg-gray-700 dark:focus:border-blue-800" aria-label="Previous">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                                    </svg>
                                </a>
                            @endif

                            {{-- Pagination Elements --}}
                            @php
                                $start = max($transaksiNbm->currentPage() - 2, 1);
                                $end = min($start + 4, $transaksiNbm->lastPage());
                                $start = max($end - 4, 1);
                            @endphp

                            {{-- First Page --}}
                            @if($start > 1)
                                <a href="{{ $transaksiNbm->url(1) }}" class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-gray-700 bg-white border border-gray-300 leading-5 hover:text-gray-500 focus:z-10 focus:outline-none focus:ring ring-gray-300 focus:border-blue-300 active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-400 dark:hover:text-gray-300 dark:active:bg-gray-700 dark:focus:border-blue-800">
                                    1
                                </a>
                                @if($start > 2)
                                    <span class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-gray-700 bg-white border border-gray-300 cursor-default leading-5 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-400">...</span>
                                @endif
                            @endif

                            {{-- Page Numbers --}}
                            @for ($i = $start; $i <= $end; $i++)
                                @if ($i == $transaksiNbm->currentPage())
                                    <span aria-current="page">
                                        <span class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-white bg-purple-600 border border-purple-600 cursor-default leading-5">{{ $i }}</span>
                                    </span>
                                @else
                                    <a href="{{ $transaksiNbm->url($i) }}" class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-gray-700 bg-white border border-gray-300 leading-5 hover:text-gray-500 focus:z-10 focus:outline-none focus:ring ring-gray-300 focus:border-blue-300 active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-400 dark:hover:text-gray-300 dark:active:bg-gray-700 dark:focus:border-blue-800" aria-label="Go to page {{ $i }}">
                                        {{ $i }}
                                    </a>
                                @endif
                            @endfor

                            {{-- Last Page --}}
                            @if($end < $transaksiNbm->lastPage())
                                @if($end < $transaksiNbm->lastPage() - 1)
                                    <span class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-gray-700 bg-white border border-gray-300 cursor-default leading-5 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-400">...</span>
                                @endif
                                <a href="{{ $transaksiNbm->url($transaksiNbm->lastPage()) }}" class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-gray-700 bg-white border border-gray-300 leading-5 hover:text-gray-500 focus:z-10 focus:outline-none focus:ring ring-gray-300 focus:border-blue-300 active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-400 dark:hover:text-gray-300 dark:active:bg-gray-700 dark:focus:border-blue-800">
                                    {{ $transaksiNbm->lastPage() }}
                                </a>
                            @endif

                            {{-- Next Page Link --}}
                            @if ($transaksiNbm->hasMorePages())
                                <a href="{{ $transaksiNbm->nextPageUrl() }}" rel="next" class="relative inline-flex items-center px-2 py-2 -ml-px text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-r-md leading-5 hover:text-gray-400 focus:z-10 focus:outline-none focus:ring ring-gray-300 focus:border-blue-300 active:bg-gray-100 active:text-gray-500 transition ease-in-out duration-150 dark:bg-gray-800 dark:border-gray-600 dark:active:bg-gray-700 dark:focus:border-blue-800" aria-label="Next">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                    </svg>
                                </a>
                            @else
                                <span aria-disabled="true" aria-label="Next">
                                    <span class="relative inline-flex items-center px-2 py-2 -ml-px text-sm font-medium text-gray-500 bg-white border border-gray-300 cursor-default rounded-r-md leading-5 dark:bg-gray-800 dark:border-gray-600" aria-hidden="true">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                        </svg>
                                    </span>
                                </span>
                            @endif
                        </span>
                    </div>
                </div>
            </nav>
        </div>
        @endif
    </div>

    <!-- Info -->
    <div class="mt-6 flex items-center justify-between text-sm text-neutral-600 dark:text-neutral-400">
        <p>
            Menampilkan <strong class="text-neutral-900 dark:text-white">{{ $transaksiNbm->firstItem() ?? 0 }}</strong> 
            sampai <strong class="text-neutral-900 dark:text-white">{{ $transaksiNbm->lastItem() ?? 0 }}</strong> 
            dari <strong class="text-neutral-900 dark:text-white">{{ $transaksiNbm->total() }}</strong> data
        </p>
        <a href="{{ route('akademisi.export-data') }}" 
           class="inline-flex items-center px-4 py-2 bg-white dark:bg-neutral-700 border border-neutral-300 dark:border-neutral-600 rounded-lg hover:bg-neutral-50 dark:hover:bg-neutral-600 transition-colors">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
            </svg>
            Export Data
        </a>
    </div>

    <script>
        // Filter komoditi berdasarkan kelompok yang dipilih
        document.getElementById('kelompok').addEventListener('change', function() {
            const kelompokKode = this.value;
            const komoditiSelect = document.getElementById('komoditi');
            const options = komoditiSelect.querySelectorAll('option');
            
            options.forEach(option => {
                if (option.value === '') {
                    option.style.display = 'block';
                } else {
                    const optionKelompok = option.getAttribute('data-kelompok');
                    option.style.display = (!kelompokKode || optionKelompok === kelompokKode) ? 'block' : 'none';
                }
            });
            
            // Reset komoditi selection if not matching
            if (komoditiSelect.value) {
                const selectedOption = komoditiSelect.querySelector(`option[value="${komoditiSelect.value}"]`);
                if (selectedOption && selectedOption.style.display === 'none') {
                    komoditiSelect.value = '';
                }
            }
        });
    </script>
</x-layouts.akademisi>
