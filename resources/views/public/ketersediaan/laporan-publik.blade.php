<x-layouts.landing title="Laporan NBM Publik - SIKOLBIA">
    <!-- FontAwesome Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <div class="py-12 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8" x-data="laporanPublik()">
            <!-- Breadcrumb -->
            <nav class="flex mb-8" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('home') }}" class="text-neutral-700 hover:text-blue-600">Home</a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-neutral-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            <span class="ml-1 text-neutral-500">Ketersediaan</span>
                        </div>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-neutral-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            <span class="ml-1 text-blue-600 font-medium">Laporan NBM Publik</span>
                        </div>
                    </li>
                </ol>
            </nav>

            <!-- Page Header -->
            <div class="mb-8">
                <h1 class="text-3xl md:text-4xl font-bold text-neutral-900 mb-4">
                    Laporan Neraca Bahan Makanan (NBM) Indonesia
                </h1>
                <p class="text-xl text-neutral-600">
                    Akses data konsumsi dan ketersediaan pangan nasional - Level akses publik
                </p>
            </div>

            <!-- Content Container with proper spacing -->
            <div class="max-w-7xl mx-auto">
                <!-- Filter Section -->
                <div class="bg-white p-6 rounded-lg shadow-md mb-8">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">
                        <i class="fas fa-filter text-blue-600 mr-2"></i>
                        Filter Data
                    </h3>
                    <form method="GET" action="{{ route('public.ketersediaan.laporan-publik') }}"
                        class="grid grid-cols-1 md:grid-cols-3 gap-4">

                        <!-- Filter Tahun -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tahun</label>
                            <select name="tahun"
                                class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                @foreach ($tahunList as $tahunOption)
                                    <option value="{{ $tahunOption }}" {{ $tahun == $tahunOption ? 'selected' : '' }}>
                                        {{ $tahunOption }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Filter Kelompok -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Kelompok Komoditas</label>
                            <select name="kelompok"
                                class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                <option value="">Semua Kelompok</option>
                                @foreach ($kelompokList as $kel)
                                    <option value="{{ $kel->kode }}" {{ $kelompok == $kel->kode ? 'selected' : '' }}>
                                        {{ $kel->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Submit Button -->
                        <div class="flex items-end">
                            <button type="submit"
                                class="w-full bg-green-600 text-white py-3 px-6 rounded-lg hover:bg-green-700 focus:ring-2 focus:ring-green-500">
                                <i class="fas fa-search mr-2"></i>
                                Tampilkan Data
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Summary Stats -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                    <div class="bg-white p-6 rounded-lg shadow-md">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-blue-100 text-blue-600 mr-4">
                                <i class="fas fa-list text-xl"></i>
                            </div>
                            <div>
                                <p class="text-2xl font-bold text-gray-800">{{ $data->count() }}</p>
                                <p class="text-gray-600 text-sm">Komoditas Ditampilkan</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white p-6 rounded-lg shadow-md">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-green-100 text-green-600 mr-4">
                                <i class="fas fa-fire text-xl"></i>
                            </div>
                            <div>
                                <p class="text-2xl font-bold text-gray-800">
                                    {{ number_format($data->sum('kalori_harian'), 2, ',', '.') }}</p>
                                <p class="text-gray-600 text-sm">Total Kalori/Hari</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white p-6 rounded-lg shadow-md">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-purple-100 text-purple-600 mr-4">
                                <i class="fas fa-chart-bar text-xl"></i>
                            </div>
                            <div>
                                <p class="text-2xl font-bold text-gray-800">
                                    {{ $data->count() > 0 ? number_format($data->sum('kalori_harian') / $data->count(), 2, ',', '.') : '0' }}
                                </p>
                                <p class="text-gray-600 text-sm">Rata-rata Kalori/Hari</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white p-6 rounded-lg shadow-md">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-orange-100 text-orange-600 mr-4">
                                <i class="fas fa-crown text-xl"></i>
                            </div>
                            <div>
                                <p class="text-2xl font-bold text-gray-800">
                                    {{ $data->count() > 0 ? number_format($data->pluck('kalori_harian')->max(), 2, ',', '.') : '0' }}
                                </p>
                                <p class="text-gray-600 text-sm">Kalori Tertinggi/Hari</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Data Table -->
                <div class="bg-white rounded-lg shadow-md">
                    <div class="p-6 border-b border-gray-200">
                        <div class="flex justify-between items-center">
                            <h3 class="text-lg font-bold text-gray-800">
                                <i class="fas fa-table text-green-600 mr-2"></i>
                                Data Konsumsi Kalori per Komoditas
                            </h3>

                            <!-- Info akses lengkap -->
                            <div class="text-sm text-gray-500">
                                <i class="fas fa-info-circle mr-1"></i>
                                Menampilkan 20 komoditas teratas
                            </div>
                        </div>
                    </div>

                    @if ($data->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            No</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Komoditas</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Kelompok</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Kalori & Konsumsi</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Visual</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($data as $index => $item)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                {{ $index + 1 }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $item->komoditi->nama ?? $item->kode_komoditi }}
                                                </div>
                                                <div class="text-sm text-gray-500">
                                                    Kode: {{ $item->kode_komoditi }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $item->kelompok->nama ?? 'N/A' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-bold text-gray-900">
                                                    {{ number_format($item->kalori_harian, 2, ',', '.') }} kkal/hari
                                                </div>
                                                <div class="text-xs text-gray-500">
                                                    {{ number_format($item->rata_konsumsi, 2, ',', '.') }} ribu ton
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="w-20 bg-gray-200 rounded-full h-2 mr-3">
                                                        @php
                                                            $maxKalori = $data->pluck('kalori_harian')->max();
                                                            $totalKalori = $data->sum('kalori_harian');
                                                        @endphp
                                                        <div class="bg-green-600 h-2 rounded-full"
                                                            style="width: {{ $maxKalori > 0 ? min(100, ($item->kalori_harian / $maxKalori) * 100) : 0 }}%">
                                                        </div>
                                                    </div>
                                                    <span class="text-xs text-gray-500">
                                                        {{ $totalKalori > 0 ? number_format(($item->kalori_harian / $totalKalori) * 100, 1) : 0 }}%
                                                    </span>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="p-12 text-center">
                            <i class="fas fa-inbox text-4xl text-gray-400 mb-4"></i>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak Ada Data</h3>
                            <p class="text-gray-500">Tidak ada data konsumsi untuk filter yang dipilih.</p>
                        </div>
                    @endif
                </div>

                <!-- Info & Akses Tambahan -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mt-8">

                    <!-- Info Keterbatasan Data Publik -->
                    <div class="bg-yellow-50 border-l-4 border-yellow-500 p-6 rounded-lg">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <i class="fas fa-exclamation-triangle text-yellow-500 text-xl mt-1"></i>
                            </div>
                            <div class="ml-3">
                                <h4 class="text-lg font-medium text-yellow-900">Keterbatasan Akses Publik</h4>
                                <div class="text-yellow-700 text-sm mt-2 space-y-2">
                                    <p>• Data yang ditampilkan hanya 20 komoditas teratas per tahun</p>
                                    <p>• Tidak termasuk data prediksi AI dan analisis mendalam</p>
                                    <p>• Data regional dan time series tidak tersedia</p>
                                    <p>• Ekspor data dalam format lengkap memerlukan akses khusus</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- CTA untuk Akses Lengkap -->
                    <div class="bg-blue-50 border-l-4 border-blue-500 p-6 rounded-lg">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <i class="fas fa-lock-open text-blue-500 text-xl mt-1"></i>
                            </div>
                            <div class="ml-3">
                                <h4 class="text-lg font-medium text-blue-900">Butuh Akses Lengkap?</h4>
                                <p class="text-blue-700 text-sm mt-2">
                                    Dapatkan akses ke data historis lengkap, prediksi AI, dan tools analisis
                                    profesional.
                                </p>
                                <div class="mt-4 space-x-3">
                                    <a href="{{ route('public.registrasi.pemerintah') }}"
                                        class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700">
                                        <i class="fas fa-university mr-2"></i>
                                        Akses Pemerintah
                                    </a>
                                    <a href="{{ route('public.registrasi.akademisi') }}"
                                        class="inline-flex items-center px-4 py-2 border border-blue-600 text-blue-600 text-sm font-medium rounded-lg hover:bg-blue-600 hover:text-white">
                                        <i class="fas fa-graduation-cap mr-2"></i>
                                        Akses Akademisi
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer Info -->
                <div class="mt-8 text-center text-gray-500 text-sm">
                    <p>Data diperbarui setiap bulan • Sumber: Pusat Data dan Sistem Informasi Pertanian</p>
                    <p class="mt-2">
                        <a href="{{ route('public.ketersediaan.metodologi') }}"
                            class="text-green-600 hover:text-green-800">
                            Pelajari metodologi pengumpulan data <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </p>
                </div>

            </div> <!-- End Content Container -->
        </div>
    </div>

    <script>
        function laporanPublik() {
            return {
                init() {
                    // Inisialisasi jika diperlukan
                }
            }
        }
    </script>
    </div>
    </div>
</x-layouts.landing>
