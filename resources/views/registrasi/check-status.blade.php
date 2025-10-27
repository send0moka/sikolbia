<x-layouts.landing>
    <div class="min-h-screen bg-gradient-to-br from-teal-50 via-white to-teal-50 dark:from-neutral-900 dark:via-neutral-800 dark:to-neutral-900 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-2xl mx-auto">
            <!-- Header -->
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-neutral-900 dark:text-white mb-2">
                    🔍 Check Status Registrasi
                </h1>
                <p class="text-neutral-600 dark:text-neutral-400">
                    Masukkan email Anda untuk melihat status registrasi
                </p>
            </div>

            <!-- Search Form -->
            <div class="bg-white dark:bg-neutral-800 shadow-xl rounded-lg overflow-hidden mb-6">
                <div class="px-6 py-8">
                    <form action="{{ route('public.registrasi.check-status') }}" method="GET">
                        <div class="space-y-4">
                            <div>
                                <label for="email" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                                    Email Registrasi
                                </label>
                                <input type="email" name="email" id="email" 
                                    value="{{ request('email') }}"
                                    class="w-full px-4 py-3 border border-neutral-300 dark:border-neutral-600 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 dark:bg-neutral-700 dark:text-white" 
                                    placeholder="email@example.com"
                                    required>
                            </div>
                            <button type="submit" 
                                class="w-full px-6 py-3 bg-teal-600 text-white font-medium rounded-lg hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:ring-offset-2 transition-colors">
                                🔍 Cek Status
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Result -->
            @if(isset($registrasi))
            <div class="bg-white dark:bg-neutral-800 shadow-xl rounded-lg overflow-hidden">
                <div class="px-6 py-8">
                    <h2 class="text-xl font-semibold text-neutral-900 dark:text-white mb-6">Status Registrasi Anda</h2>
                    
                    <!-- Status Badge -->
                    <div class="flex items-center justify-between mb-6 p-4 bg-neutral-50 dark:bg-neutral-700 rounded-lg">
                        <div>
                            <p class="text-sm text-neutral-600 dark:text-neutral-400">Status Saat Ini</p>
                            <p class="text-lg font-semibold text-neutral-900 dark:text-white">{{ $registrasi->nama_lengkap }}</p>
                        </div>
                        <span class="{{ $registrasi->status_color }} px-4 py-2 rounded-full text-sm font-medium">
                            {{ $registrasi->status_label }}
                        </span>
                    </div>

                    <!-- Detail Info -->
                    <div class="space-y-3 mb-6">
                        <div class="flex justify-between text-sm">
                            <span class="text-neutral-600 dark:text-neutral-400">Tanggal Daftar:</span>
                            <span class="font-medium text-neutral-900 dark:text-white">{{ $registrasi->created_at->format('d M Y H:i') }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-neutral-600 dark:text-neutral-400">Tipe Akses:</span>
                            <span class="font-medium text-neutral-900 dark:text-white">{{ ucfirst($registrasi->tipe_akses) }}</span>
                        </div>
                        @if($registrasi->tanggal_review)
                        <div class="flex justify-between text-sm">
                            <span class="text-neutral-600 dark:text-neutral-400">Tanggal Review:</span>
                            <span class="font-medium text-neutral-900 dark:text-white">{{ $registrasi->tanggal_review->format('d M Y H:i') }}</span>
                        </div>
                        @endif
                    </div>

                    <!-- Status Messages -->
                    @if($registrasi->status === 'pending')
                    <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-4">
                        <div class="flex">
                            <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                            </svg>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-yellow-800 dark:text-yellow-300">Sedang Diproses</h3>
                                <p class="mt-1 text-sm text-yellow-700 dark:text-yellow-400">
                                    Registrasi Anda sedang dalam proses review oleh tim kami. Kami akan mengirimkan notifikasi via email setelah selesai review.
                                </p>
                            </div>
                        </div>
                    </div>
                    @elseif($registrasi->status === 'approved')
                    <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-4">
                        <div class="flex">
                            <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-green-800 dark:text-green-300">Disetujui!</h3>
                                <p class="mt-1 text-sm text-green-700 dark:text-green-400">
                                    Selamat! Registrasi Anda telah disetujui. Cek email Anda untuk informasi login.
                                </p>
                                <a href="/login" class="mt-3 inline-block px-4 py-2 bg-green-600 text-white text-sm font-medium rounded hover:bg-green-700">
                                    Login Sekarang →
                                </a>
                            </div>
                        </div>
                    </div>
                    @elseif($registrasi->status === 'rejected')
                    <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4">
                        <div class="flex">
                            <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-red-800 dark:text-red-300">Ditolak</h3>
                                <p class="mt-1 text-sm text-red-700 dark:text-red-400">
                                    Mohon maaf, registrasi Anda tidak dapat disetujui saat ini. Cek email Anda untuk detail alasan penolakan.
                                </p>
                            </div>
                        </div>
                    </div>
                    @elseif($registrasi->status === 'need_documents')
                    <div class="bg-orange-50 dark:bg-orange-900/20 border border-orange-200 dark:border-orange-800 rounded-lg p-4">
                        <div class="flex">
                            <svg class="h-5 w-5 text-orange-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"/>
                            </svg>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-orange-800 dark:text-orange-300">Dokumen Tambahan Diperlukan</h3>
                                <p class="mt-1 text-sm text-orange-700 dark:text-orange-400">
                                    Kami memerlukan dokumen tambahan untuk melanjutkan verifikasi. Cek email Anda untuk detail dan link upload.
                                </p>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Admin Note -->
                    @if($registrasi->catatan_admin)
                    <div class="mt-6">
                        <h3 class="text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">Catatan dari Admin:</h3>
                        <p class="text-sm text-neutral-900 dark:text-white bg-neutral-50 dark:bg-neutral-700 p-4 rounded-lg">
                            {{ $registrasi->catatan_admin }}
                        </p>
                    </div>
                    @endif
                </div>
            </div>
            @elseif(request('email'))
            <div class="bg-white dark:bg-neutral-800 shadow-xl rounded-lg overflow-hidden">
                <div class="px-6 py-8 text-center">
                    <svg class="mx-auto h-12 w-12 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-neutral-900 dark:text-white">Tidak Ditemukan</h3>
                    <p class="mt-1 text-sm text-neutral-500 dark:text-neutral-400">
                        Tidak ada registrasi ditemukan dengan email: <strong>{{ request('email') }}</strong>
                    </p>
                    <div class="mt-6">
                        <a href="/registrasi/pemerintah" class="text-sm text-teal-600 hover:text-teal-800 dark:text-teal-400">
                            Daftar Sekarang →
                        </a>
                    </div>
                </div>
            </div>
            @endif

            <!-- Info Box -->
            <div class="mt-6 text-center">
                <p class="text-sm text-neutral-600 dark:text-neutral-400">
                    Belum registrasi? 
                    <a href="/registrasi/pemerintah" class="text-teal-600 hover:text-teal-800 dark:text-teal-400 font-medium">Daftar sebagai Pemerintah</a>
                    atau
                    <a href="/registrasi/akademisi" class="text-teal-600 hover:text-teal-800 dark:text-teal-400 font-medium">Daftar sebagai Akademisi</a>
                </p>
            </div>
        </div>
    </div>
</x-layouts.landing>
