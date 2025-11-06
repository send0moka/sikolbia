<x-layouts.akademisi>
    <!-- Page Header -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-neutral-900 dark:text-white">Export Data NBM</h1>
        <p class="text-neutral-600 dark:text-neutral-400 mt-2">Download data Neraca Bahan Makanan untuk keperluan riset</p>
    </div>

    <!-- Export Form -->
    <div class="bg-white dark:bg-neutral-800 rounded-lg shadow p-6">
        <form action="{{ route('akademisi.export-data.download') }}" method="POST">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Format Export -->
                <div>
                    <label for="format" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                        Format File <span class="text-red-500">*</span>
                    </label>
                    <select name="format" id="format" required
                            class="w-full px-3 py-2 bg-white dark:bg-neutral-700 border border-neutral-300 dark:border-neutral-600 rounded-lg focus:ring-2 focus:ring-purple-500 dark:text-white">
                        <option value="xlsx">Excel (.xlsx)</option>
                        <option value="csv">CSV (.csv)</option>
                    </select>
                </div>

                <!-- Tahun Dari -->
                <div>
                    <label for="tahun_dari" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                        Tahun Dari
                    </label>
                    <select name="tahun_dari" id="tahun_dari"
                            class="w-full px-3 py-2 bg-white dark:bg-neutral-700 border border-neutral-300 dark:border-neutral-600 rounded-lg focus:ring-2 focus:ring-purple-500 dark:text-white">
                        <option value="">Semua Tahun</option>
                        @foreach($tahunList as $tahun)
                            <option value="{{ $tahun }}">{{ $tahun }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Tahun Sampai -->
                <div>
                    <label for="tahun_sampai" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                        Tahun Sampai
                    </label>
                    <select name="tahun_sampai" id="tahun_sampai"
                            class="w-full px-3 py-2 bg-white dark:bg-neutral-700 border border-neutral-300 dark:border-neutral-600 rounded-lg focus:ring-2 focus:ring-purple-500 dark:text-white">
                        <option value="">Semua Tahun</option>
                        @foreach($tahunList as $tahun)
                            <option value="{{ $tahun }}" {{ $tahun == date('Y') ? 'selected' : '' }}>{{ $tahun }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Kelompok -->
                <div>
                    <label for="kelompok" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                        Kelompok Komoditas
                    </label>
                    <select name="kelompok" id="kelompok"
                            class="w-full px-3 py-2 bg-white dark:bg-neutral-700 border border-neutral-300 dark:border-neutral-600 rounded-lg focus:ring-2 focus:ring-purple-500 dark:text-white">
                        <option value="">Semua Kelompok</option>
                        @foreach($kelompokList as $kelompok)
                            <option value="{{ $kelompok->kode }}">{{ $kelompok->kode }} - {{ $kelompok->nama }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Komoditi -->
                <div class="md:col-span-2">
                    <label for="komoditi" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                        Komoditas Spesifik
                    </label>
                    <select name="komoditi" id="komoditi"
                            class="w-full px-3 py-2 bg-white dark:bg-neutral-700 border border-neutral-300 dark:border-neutral-600 rounded-lg focus:ring-2 focus:ring-purple-500 dark:text-white">
                        <option value="">Semua Komoditas</option>
                        @foreach($komoditiList as $komoditi)
                            <option value="{{ $komoditi->kode_komoditi }}" data-kelompok="{{ $komoditi->kode_kelompok }}">
                                {{ $komoditi->kode_komoditi }} - {{ $komoditi->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex items-center justify-between pt-4 border-t border-neutral-200 dark:border-neutral-700">
                <p class="text-sm text-neutral-600 dark:text-neutral-400">
                    <svg class="inline w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                    </svg>
                    Kosongkan filter untuk export semua data
                </p>
                <button type="submit"
                        class="inline-flex items-center px-6 py-3 bg-purple-600 hover:bg-purple-700 text-white font-medium rounded-lg transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                    Download Data
                </button>
            </div>
        </form>
    </div>

    <!-- Info Box -->
    <div class="bg-purple-50 dark:bg-purple-900/20 border border-purple-200 dark:border-purple-800 rounded-lg p-6 mt-6">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-6 w-6 text-purple-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                </svg>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-purple-800 dark:text-purple-300">Informasi Export</h3>
                <div class="mt-2 text-sm text-purple-700 dark:text-purple-400 space-y-1">
                    <p>• Data yang di-export dapat digunakan untuk penelitian akademik dan publikasi ilmiah</p>
                    <p>• Format Excel (.xlsx) cocok untuk analisis dengan Microsoft Excel atau LibreOffice Calc</p>
                    <p>• Format CSV (.csv) cocok untuk import ke Python, R, atau software statistik lainnya</p>
                    <p>• Jangan lupa cantumkan sitasi data dalam publikasi Anda (lihat <a href="{{ route('akademisi.panduan-sitasi') }}" class="underline font-medium">Panduan Sitasi</a>)</p>
                </div>
            </div>
        </div>
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
