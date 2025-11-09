<x-layouts.landing>
    <div class="min-h-screen bg-gradient-to-br from-purple-50 via-white to-purple-50 dark:from-neutral-900 dark:via-neutral-800 dark:to-neutral-900 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-3xl mx-auto">
            <!-- Header -->
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-neutral-900 dark:text-white mb-2">
                    Registrasi Akses Akademisi
                </h1>
                <p class="text-neutral-600 dark:text-neutral-400">
                    Lengkapi formulir di bawah untuk mendapatkan akses ke sistem SIKOLBIA
                </p>
            </div>

            <!-- Success/Error Messages -->
            @if(session('success'))
            <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative dark:bg-green-900 dark:border-green-600 dark:text-green-200" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
            @endif

            @if(session('error'))
            <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative dark:bg-red-900 dark:border-red-600 dark:text-red-200" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
            @endif

            <!-- Form Card -->
            <div class="bg-white dark:bg-neutral-800 shadow-xl rounded-lg overflow-hidden">
                <div class="px-6 py-8">
                    <form action="{{ route('public.registrasi.proses') }}" method="POST">
                        @csrf
                        <input type="hidden" name="tipe_akses" value="akademisi">

                        <!-- Informasi Pribadi -->
                        <div class="mb-8">
                            <h2 class="text-xl font-semibold text-neutral-900 dark:text-white mb-4 pb-2 border-b border-neutral-200 dark:border-neutral-700">
                                Informasi Pribadi
                            </h2>

                            <div class="space-y-4">
                                <div>
                                    <label for="nama_lengkap" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1">
                                        Nama Lengkap <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="nama_lengkap" id="nama_lengkap" 
                                        value="{{ old('nama_lengkap') }}"
                                        class="w-full px-4 py-2 border border-neutral-300 dark:border-neutral-600 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 dark:bg-neutral-700 dark:text-white @error('nama_lengkap') border-red-500 @enderror" 
                                        required>
                                    @error('nama_lengkap')
                                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="email" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1">
                                        Email <span class="text-red-500">*</span>
                                    </label>
                                    <input type="email" name="email" id="email" 
                                        value="{{ old('email') }}"
                                        class="w-full px-4 py-2 border border-neutral-300 dark:border-neutral-600 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 dark:bg-neutral-700 dark:text-white @error('email') border-red-500 @enderror" 
                                        required>
                                    @error('email')
                                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="telepon" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1">
                                        No. Telepon <span class="text-red-500">*</span>
                                    </label>
                                    <input type="tel" name="telepon" id="telepon" 
                                        value="{{ old('telepon') }}"
                                        class="w-full px-4 py-2 border border-neutral-300 dark:border-neutral-600 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 dark:bg-neutral-700 dark:text-white @error('telepon') border-red-500 @enderror" 
                                        required>
                                    @error('telepon')
                                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Informasi Akademik -->
                        <div class="mb-8">
                            <h2 class="text-xl font-semibold text-neutral-900 dark:text-white mb-4 pb-2 border-b border-neutral-200 dark:border-neutral-700">
                                Informasi Akademik
                            </h2>

                            <div class="space-y-4">
                                <div>
                                    <label for="institusi" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1">
                                        Nama Institusi/Universitas <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="institusi" id="institusi" 
                                        value="{{ old('institusi') }}"
                                        placeholder="Contoh: Universitas Indonesia"
                                        class="w-full px-4 py-2 border border-neutral-300 dark:border-neutral-600 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 dark:bg-neutral-700 dark:text-white @error('institusi') border-red-500 @enderror" 
                                        required>
                                    @error('institusi')
                                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="jenjang_pendidikan" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1">
                                        Jenjang Pendidikan
                                    </label>
                                    <select name="jenjang_pendidikan" id="jenjang_pendidikan" 
                                        class="w-full px-4 py-2 border border-neutral-300 dark:border-neutral-600 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 dark:bg-neutral-700 dark:text-white @error('jenjang_pendidikan') border-red-500 @enderror">
                                        <option value="">Pilih Jenjang</option>
                                        <option value="S1" {{ old('jenjang_pendidikan') == 'S1' ? 'selected' : '' }}>S1 (Sarjana)</option>
                                        <option value="S2" {{ old('jenjang_pendidikan') == 'S2' ? 'selected' : '' }}>S2 (Magister)</option>
                                        <option value="S3" {{ old('jenjang_pendidikan') == 'S3' ? 'selected' : '' }}>S3 (Doktor)</option>
                                        <option value="Dosen" {{ old('jenjang_pendidikan') == 'Dosen' ? 'selected' : '' }}>Dosen</option>
                                        <option value="Peneliti" {{ old('jenjang_pendidikan') == 'Peneliti' ? 'selected' : '' }}>Peneliti</option>
                                    </select>
                                    @error('jenjang_pendidikan')
                                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="program_studi" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1">
                                        Program Studi/Bidang Penelitian
                                    </label>
                                    <input type="text" name="program_studi" id="program_studi" 
                                        value="{{ old('program_studi') }}"
                                        placeholder="Contoh: Ilmu Gizi, Pertanian, Ekonomi"
                                        class="w-full px-4 py-2 border border-neutral-300 dark:border-neutral-600 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 dark:bg-neutral-700 dark:text-white @error('program_studi') border-red-500 @enderror">
                                    @error('program_studi')
                                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Tujuan Penggunaan -->
                        <div class="mb-8">
                            <h2 class="text-xl font-semibold text-neutral-900 dark:text-white mb-4 pb-2 border-b border-neutral-200 dark:border-neutral-700">
                                Tujuan Penggunaan Data
                            </h2>

                            <div class="space-y-2">
                                <label class="flex items-center">
                                    <input type="checkbox" name="tujuan_penggunaan[]" value="Penelitian Skripsi" 
                                        {{ in_array('Penelitian Skripsi', old('tujuan_penggunaan', [])) ? 'checked' : '' }}
                                        class="rounded border-neutral-300 text-purple-600 focus:ring-purple-500 dark:border-neutral-600 dark:bg-neutral-700">
                                    <span class="ml-2 text-sm text-neutral-700 dark:text-neutral-300">Penelitian Skripsi</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="tujuan_penggunaan[]" value="Penelitian Tesis" 
                                        {{ in_array('Penelitian Tesis', old('tujuan_penggunaan', [])) ? 'checked' : '' }}
                                        class="rounded border-neutral-300 text-purple-600 focus:ring-purple-500 dark:border-neutral-600 dark:bg-neutral-700">
                                    <span class="ml-2 text-sm text-neutral-700 dark:text-neutral-300">Penelitian Tesis</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="tujuan_penggunaan[]" value="Penelitian Disertasi" 
                                        {{ in_array('Penelitian Disertasi', old('tujuan_penggunaan', [])) ? 'checked' : '' }}
                                        class="rounded border-neutral-300 text-purple-600 focus:ring-purple-500 dark:border-neutral-600 dark:bg-neutral-700">
                                    <span class="ml-2 text-sm text-neutral-700 dark:text-neutral-300">Penelitian Disertasi</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="tujuan_penggunaan[]" value="Penelitian Akademik" 
                                        {{ in_array('Penelitian Akademik', old('tujuan_penggunaan', [])) ? 'checked' : '' }}
                                        class="rounded border-neutral-300 text-purple-600 focus:ring-purple-500 dark:border-neutral-600 dark:bg-neutral-700">
                                    <span class="ml-2 text-sm text-neutral-700 dark:text-neutral-300">Penelitian Akademik</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="tujuan_penggunaan[]" value="Analisis Data" 
                                        {{ in_array('Analisis Data', old('tujuan_penggunaan', [])) ? 'checked' : '' }}
                                        class="rounded border-neutral-300 text-purple-600 focus:ring-purple-500 dark:border-neutral-600 dark:bg-neutral-700">
                                    <span class="ml-2 text-sm text-neutral-700 dark:text-neutral-300">Analisis Data</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="tujuan_penggunaan[]" value="Pembelajaran/Edukasi" 
                                        {{ in_array('Pembelajaran/Edukasi', old('tujuan_penggunaan', [])) ? 'checked' : '' }}
                                        class="rounded border-neutral-300 text-purple-600 focus:ring-purple-500 dark:border-neutral-600 dark:bg-neutral-700">
                                    <span class="ml-2 text-sm text-neutral-700 dark:text-neutral-300">Pembelajaran/Edukasi</span>
                                </label>
                            </div>
                        </div>

                        <!-- Deskripsi Kebutuhan -->
                        <div class="mb-8">
                            <label for="deskripsi_kebutuhan" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1">
                                Deskripsi Kebutuhan/Topik Penelitian
                            </label>
                            <textarea name="deskripsi_kebutuhan" id="deskripsi_kebutuhan" rows="4" 
                                class="w-full px-4 py-2 border border-neutral-300 dark:border-neutral-600 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 dark:bg-neutral-700 dark:text-white @error('deskripsi_kebutuhan') border-red-500 @enderror" 
                                placeholder="Jelaskan topik penelitian Anda dan data apa yang dibutuhkan...">{{ old('deskripsi_kebutuhan') }}</textarea>
                            @error('deskripsi_kebutuhan')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Submit Button -->
                        <div class="flex items-center justify-between">
                            <a href="{{ url('/') }}" class="text-sm text-purple-600 hover:text-purple-800 dark:text-purple-400">
                                &larr; Kembali ke Beranda
                            </a>
                            <button type="submit" 
                                class="px-6 py-3 bg-purple-600 text-white font-medium rounded-lg hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 transition-colors">
                                Kirim Registrasi
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Info Box -->
            <div class="mt-6 bg-purple-50 dark:bg-purple-900/20 border border-purple-200 dark:border-purple-800 rounded-lg p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-purple-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-purple-800 dark:text-purple-300">Informasi</h3>
                        <div class="mt-1 text-sm text-purple-700 dark:text-purple-400 space-y-1">
                            <p>
                                Setelah Anda mengirim registrasi, tim kami akan meninjau aplikasi Anda. 
                                Anda akan menerima email konfirmasi dalam 1-2 hari kerja.
                            </p>
                            <p class="mt-2">
                                <strong>Untuk Mahasiswa:</strong> Pastikan menggunakan email institusi (.ac.id) untuk mempercepat proses verifikasi.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Switch to Pemerintah -->
            <div class="mt-4 text-center">
                <p class="text-sm text-neutral-600 dark:text-neutral-400">
                    Bukan dari institusi akademik? 
                    <a href="{{ route('public.registrasi.pemerintah') }}" class="text-purple-600 hover:text-purple-800 dark:text-purple-400 font-medium">
                        Daftar sebagai Pemerintah
                    </a>
                </p>
            </div>
        </div>
    </div>
</x-layouts.landing>
