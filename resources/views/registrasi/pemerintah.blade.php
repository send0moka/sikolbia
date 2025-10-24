<x-layouts.landing>
    <div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-blue-50 dark:from-neutral-900 dark:via-neutral-800 dark:to-neutral-900 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-3xl mx-auto">
            <!-- Header -->
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-neutral-900 dark:text-white mb-2">
                    Registrasi Akses Pemerintah
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
                        <input type="hidden" name="tipe_akses" value="pemerintah">

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
                                        class="w-full px-4 py-2 border border-neutral-300 dark:border-neutral-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-neutral-700 dark:text-white @error('nama_lengkap') border-red-500 @enderror" 
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
                                        class="w-full px-4 py-2 border border-neutral-300 dark:border-neutral-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-neutral-700 dark:text-white @error('email') border-red-500 @enderror" 
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
                                        class="w-full px-4 py-2 border border-neutral-300 dark:border-neutral-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-neutral-700 dark:text-white @error('telepon') border-red-500 @enderror" 
                                        required>
                                    @error('telepon')
                                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Informasi Instansi -->
                        <div class="mb-8">
                            <h2 class="text-xl font-semibold text-neutral-900 dark:text-white mb-4 pb-2 border-b border-neutral-200 dark:border-neutral-700">
                                Informasi Instansi
                            </h2>

                            <div class="space-y-4">
                                <div>
                                    <label for="instansi" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1">
                                        Nama Instansi <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="instansi" id="instansi" 
                                        value="{{ old('instansi') }}"
                                        class="w-full px-4 py-2 border border-neutral-300 dark:border-neutral-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-neutral-700 dark:text-white @error('instansi') border-red-500 @enderror" 
                                        required>
                                    @error('instansi')
                                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="jenis_dinas" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1">
                                        Jenis Dinas
                                    </label>
                                    <select name="jenis_dinas" id="jenis_dinas" 
                                        class="w-full px-4 py-2 border border-neutral-300 dark:border-neutral-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-neutral-700 dark:text-white @error('jenis_dinas') border-red-500 @enderror">
                                        <option value="">Pilih Jenis Dinas</option>
                                        <option value="Dinas Pertanian" {{ old('jenis_dinas') == 'Dinas Pertanian' ? 'selected' : '' }}>Dinas Pertanian</option>
                                        <option value="Dinas Pangan" {{ old('jenis_dinas') == 'Dinas Pangan' ? 'selected' : '' }}>Dinas Pangan</option>
                                        <option value="Dinas Ketahanan Pangan" {{ old('jenis_dinas') == 'Dinas Ketahanan Pangan' ? 'selected' : '' }}>Dinas Ketahanan Pangan</option>
                                        <option value="BPS" {{ old('jenis_dinas') == 'BPS' ? 'selected' : '' }}>BPS</option>
                                        <option value="Lainnya" {{ old('jenis_dinas') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                                    </select>
                                    @error('jenis_dinas')
                                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="jabatan" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1">
                                        Jabatan <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="jabatan" id="jabatan" 
                                        value="{{ old('jabatan') }}"
                                        class="w-full px-4 py-2 border border-neutral-300 dark:border-neutral-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-neutral-700 dark:text-white @error('jabatan') border-red-500 @enderror" 
                                        required>
                                    @error('jabatan')
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
                                    <input type="checkbox" name="tujuan_penggunaan[]" value="Perencanaan Kebijakan" 
                                        class="rounded border-neutral-300 text-blue-600 focus:ring-blue-500 dark:border-neutral-600 dark:bg-neutral-700">
                                    <span class="ml-2 text-sm text-neutral-700 dark:text-neutral-300">Perencanaan Kebijakan</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="tujuan_penggunaan[]" value="Monitoring dan Evaluasi" 
                                        class="rounded border-neutral-300 text-blue-600 focus:ring-blue-500 dark:border-neutral-600 dark:bg-neutral-700">
                                    <span class="ml-2 text-sm text-neutral-700 dark:text-neutral-300">Monitoring dan Evaluasi</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="tujuan_penggunaan[]" value="Penelitian" 
                                        class="rounded border-neutral-300 text-blue-600 focus:ring-blue-500 dark:border-neutral-600 dark:bg-neutral-700">
                                    <span class="ml-2 text-sm text-neutral-700 dark:text-neutral-300">Penelitian</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="tujuan_penggunaan[]" value="Analisis Data" 
                                        class="rounded border-neutral-300 text-blue-600 focus:ring-blue-500 dark:border-neutral-600 dark:bg-neutral-700">
                                    <span class="ml-2 text-sm text-neutral-700 dark:text-neutral-300">Analisis Data</span>
                                </label>
                            </div>
                        </div>

                        <!-- Deskripsi Kebutuhan -->
                        <div class="mb-8">
                            <label for="deskripsi_kebutuhan" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1">
                                Deskripsi Kebutuhan
                            </label>
                            <textarea name="deskripsi_kebutuhan" id="deskripsi_kebutuhan" rows="4" 
                                class="w-full px-4 py-2 border border-neutral-300 dark:border-neutral-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-neutral-700 dark:text-white @error('deskripsi_kebutuhan') border-red-500 @enderror" 
                                placeholder="Jelaskan kebutuhan data Anda...">{{ old('deskripsi_kebutuhan') }}</textarea>
                            @error('deskripsi_kebutuhan')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Submit Button -->
                        <div class="flex items-center justify-between">
                            <a href="{{ url('/') }}" class="text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400">
                                &larr; Kembali ke Beranda
                            </a>
                            <button type="submit" 
                                class="px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                                Kirim Registrasi
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Info Box -->
            <div class="mt-6 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-blue-800 dark:text-blue-300">Informasi</h3>
                        <p class="mt-1 text-sm text-blue-700 dark:text-blue-400">
                            Setelah Anda mengirim registrasi, tim kami akan meninjau aplikasi Anda. 
                            Anda akan menerima email konfirmasi dalam 1-2 hari kerja.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.landing>
