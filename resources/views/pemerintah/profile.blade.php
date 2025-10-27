<x-layouts.pemerintah title="Profile - Panel Pemerintah - {{ config('app.name') }}">
    <div class="container mx-auto px-4 py-8 max-w-3xl">
        <div class="mb-6">
            <flux:heading size="xl">Profile</flux:heading>
            <flux:subheading>Informasi Akun Anda</flux:subheading>
        </div>

        <flux:card class="mb-6">
            <div class="flex items-center gap-6 mb-6 pb-6 border-b border-neutral-200 dark:border-neutral-700">
                <div class="w-24 h-24 bg-blue-500 rounded-full flex items-center justify-center text-white text-3xl font-bold">
                    {{ Auth::user()->initials() }}
                </div>
                <div>
                    <h3 class="text-2xl font-bold text-neutral-900 dark:text-white">{{ Auth::user()->name }}</h3>
                    <p class="text-neutral-600 dark:text-neutral-400">{{ Auth::user()->email }}</p>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300 mt-2">
                        Pemerintah
                    </span>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <flux:field>
                        <flux:label>Nama Lengkap</flux:label>
                        <flux:input value="{{ Auth::user()->name }}" disabled />
                    </flux:field>
                </div>

                <div>
                    <flux:field>
                        <flux:label>Email</flux:label>
                        <flux:input value="{{ Auth::user()->email }}" disabled />
                    </flux:field>
                </div>

                <div>
                    <flux:field>
                        <flux:label>Role</flux:label>
                        <flux:input value="Pemerintah" disabled />
                    </flux:field>
                </div>

                <div>
                    <flux:field>
                        <flux:label>Status</flux:label>
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                            <span class="text-sm text-green-600 dark:text-green-400 font-medium">Aktif</span>
                        </div>
                    </flux:field>
                </div>

                <div>
                    <flux:field>
                        <flux:label>Terdaftar Sejak</flux:label>
                        <flux:input value="{{ Auth::user()->created_at->format('d M Y') }}" disabled />
                    </flux:field>
                </div>

                <div>
                    <flux:field>
                        <flux:label>Email Verified</flux:label>
                        <div class="flex items-center gap-2">
                            @if(Auth::user()->email_verified_at)
                                <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <span class="text-sm text-green-600 dark:text-green-400">Terverifikasi</span>
                            @else
                                <svg class="w-5 h-5 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                </svg>
                                <span class="text-sm text-yellow-600 dark:text-yellow-400">Belum Terverifikasi</span>
                            @endif
                        </div>
                    </flux:field>
                </div>
            </div>
        </flux:card>

        <flux:card>
            <flux:heading size="lg" class="mb-4">Aktivitas Terakhir</flux:heading>
            <div class="space-y-3">
                <div class="flex items-center gap-3 p-3 bg-neutral-50 dark:bg-neutral-800 rounded-lg">
                    <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                    </svg>
                    <div>
                        <p class="text-sm font-medium text-neutral-900 dark:text-white">Login Terakhir</p>
                        <p class="text-xs text-neutral-600 dark:text-neutral-400">{{ now()->format('d M Y, H:i') }} WIB</p>
                    </div>
                </div>

                <div class="flex items-center gap-3 p-3 bg-neutral-50 dark:bg-neutral-800 rounded-lg">
                    <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                    </svg>
                    <div>
                        <p class="text-sm font-medium text-neutral-900 dark:text-white">Akun Dibuat</p>
                        <p class="text-xs text-neutral-600 dark:text-neutral-400">{{ Auth::user()->created_at->diffForHumans() }}</p>
                    </div>
                </div>
            </div>
        </flux:card>
    </div>
</x-layouts.pemerintah>
