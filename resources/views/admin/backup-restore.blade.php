<x-layouts.app.sidebar>
    <flux:main class="min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Header -->
            <div class="mb-8">
                <flux:heading size="xl" class="mb-2">Backup & Restore Database</flux:heading>
                <flux:subheading>Kelola backup dan restore data konsumsi pangan</flux:subheading>
            </div>

            <!-- Alert Messages -->
            <div id="alert-container" class="mb-6"></div>

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <!-- Last Backup Card -->
                <div class="bg-white dark:bg-zinc-800 rounded-lg shadow p-6 border border-zinc-200 dark:border-zinc-700">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-zinc-900 dark:text-white">Backup Terakhir</h3>
                        <flux:icon.circle-stack class="w-8 h-8 text-blue-500" />
                    </div>
                    @if($lastBackup)
                        <p class="text-sm text-zinc-600 dark:text-zinc-400 mb-2">
                            <span class="font-medium">{{ $lastBackup->filename }}</span>
                        </p>
                        <p class="text-xs text-zinc-500 dark:text-zinc-500">
                            {{ $lastBackup->created_at->diffForHumans() }}
                        </p>
                        <p class="text-xs text-zinc-500 dark:text-zinc-500">
                            Oleh: {{ $lastBackup->user_name }}
                        </p>
                    @else
                        <p class="text-sm text-zinc-500 dark:text-zinc-400">Belum ada backup</p>
                    @endif
                </div>

                <!-- Last Restore Card -->
                <div class="bg-white dark:bg-zinc-800 rounded-lg shadow p-6 border border-zinc-200 dark:border-zinc-700">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-zinc-900 dark:text-white">Restore Terakhir</h3>
                        <flux:icon.arrow-path class="w-8 h-8 text-green-500" />
                    </div>
                    @if($lastRestore)
                        <p class="text-sm text-zinc-600 dark:text-zinc-400 mb-2">
                            <span class="font-medium">{{ $lastRestore->filename }}</span>
                        </p>
                        <p class="text-xs text-zinc-500 dark:text-zinc-500">
                            {{ $lastRestore->created_at->diffForHumans() }}
                        </p>
                        <p class="text-xs text-zinc-500 dark:text-zinc-500">
                            Oleh: {{ $lastRestore->user_name }}
                        </p>
                    @else
                        <p class="text-sm text-zinc-500 dark:text-zinc-400">Belum ada restore</p>
                    @endif
                </div>

                <!-- Total Backups Card -->
                <div class="bg-white dark:bg-zinc-800 rounded-lg shadow p-6 border border-zinc-200 dark:border-zinc-700">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-zinc-900 dark:text-white">Total File Backup</h3>
                        <flux:icon.document-duplicate class="w-8 h-8 text-purple-500" />
                    </div>
                    <p class="text-3xl font-bold text-zinc-900 dark:text-white">{{ count($backupFiles) }}</p>
                    <p class="text-xs text-zinc-500 dark:text-zinc-500 mt-2">File tersedia</p>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <!-- Backup Section -->
                <div class="bg-white dark:bg-zinc-800 rounded-lg shadow p-6 border border-zinc-200 dark:border-zinc-700">
                    <h3 class="text-lg font-semibold text-zinc-900 dark:text-white mb-4">Buat Backup Baru</h3>
                    <p class="text-sm text-zinc-600 dark:text-zinc-400 mb-4">
                        Backup akan mencakup tabel: konsumsi, komoditi, transaksi_nbms, registrasi_akses, users, tb_kelompokbps, tb_komoditibps, transaksi_susenas
                    </p>
                    <form id="backup-form" class="space-y-4">
                        <flux:input 
                            type="text" 
                            name="description" 
                            label="Deskripsi (opsional)" 
                            placeholder="Contoh: Backup sebelum update data..."
                        />
                        <flux:button type="submit" variant="primary" class="w-full">
                            <div class="flex items-center justify-center gap-2">
                                <flux:icon.circle-stack class="w-5 h-5" />
                                <span>Buat Backup Sekarang</span>
                            </div>
                        </flux:button>
                    </form>
                </div>

                <!-- Restore Section -->
                <div class="bg-white dark:bg-zinc-800 rounded-lg shadow p-6 border border-zinc-200 dark:border-zinc-700">
                    <h3 class="text-lg font-semibold text-zinc-900 dark:text-white mb-4">Restore Database</h3>
                    <p class="text-sm text-red-600 dark:text-red-400 mb-4">
                        ⚠️ Perhatian: Restore akan mengganti data yang ada dengan data dari backup!
                    </p>
                    <form id="restore-form" class="space-y-4">
                        <flux:select name="backup_file" label="Pilih File Backup" required>
                            <option value="">-- Pilih file backup --</option>
                            @foreach($backupFiles as $file)
                                <option value="{{ $file['filename'] }}">
                                    {{ $file['filename'] }} ({{ $file['formatted_size'] }}) - {{ $file['formatted_date'] }}
                                </option>
                            @endforeach
                        </flux:select>
                        <flux:input 
                            type="password" 
                            name="confirm_password" 
                            label="Konfirmasi Password" 
                            placeholder="Masukkan password Anda"
                            required
                        />
                        <flux:input 
                            type="text" 
                            name="description" 
                            label="Deskripsi (opsional)" 
                            placeholder="Contoh: Restore data bulan lalu..."
                        />
                        <flux:button type="submit" variant="danger" class="w-full">
                            <div class="flex items-center justify-center gap-2">
                                <flux:icon.arrow-path class="w-5 h-5" />
                                <span>Restore Database</span>
                            </div>
                        </flux:button>
                    </form>
                </div>
            </div>

            <!-- Available Backups -->
            <div class="bg-white dark:bg-zinc-800 rounded-lg shadow border border-zinc-200 dark:border-zinc-700 mb-8">
                <div class="p-6 border-b border-zinc-200 dark:border-zinc-700">
                    <h3 class="text-lg font-semibold text-zinc-900 dark:text-white">File Backup Tersedia</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-zinc-50 dark:bg-zinc-900">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Nama File</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Ukuran</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Tanggal</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">
                            @forelse($backupFiles as $file)
                                <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-900">
                                    <td class="px-6 py-4 text-sm text-zinc-900 dark:text-white">{{ $file['filename'] }}</td>
                                    <td class="px-6 py-4 text-sm text-zinc-600 dark:text-zinc-400">{{ $file['formatted_size'] }}</td>
                                    <td class="px-6 py-4 text-sm text-zinc-600 dark:text-zinc-400">{{ $file['formatted_date'] }}</td>
                                    <td class="px-6 py-4 text-sm space-x-2">
                                        <a href="{{ route('admin.backup-restore.download', $file['filename']) }}" 
                                           class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                                            Download
                                        </a>
                                        <button onclick="deleteBackup('{{ $file['filename'] }}')" 
                                                class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300">
                                            Hapus
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-8 text-center text-sm text-zinc-500 dark:text-zinc-400">
                                        Tidak ada file backup tersedia
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Activity Log -->
            <div class="bg-white dark:bg-zinc-800 rounded-lg shadow border border-zinc-200 dark:border-zinc-700">
                <div class="p-6 border-b border-zinc-200 dark:border-zinc-700">
                    <h3 class="text-lg font-semibold text-zinc-900 dark:text-white">Log Aktivitas</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-zinc-50 dark:bg-zinc-900">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Tipe</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">File</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">User</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Waktu</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Durasi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">
                            @forelse($logs as $log)
                                <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-900">
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $log->type === 'backup' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' : 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' }}">
                                            {{ ucfirst($log->type) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-zinc-900 dark:text-white">
                                        {{ $log->filename }}
                                        @if($log->description)
                                            <p class="text-xs text-zinc-500 dark:text-zinc-400">{{ $log->description }}</p>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm text-zinc-600 dark:text-zinc-400">
                                        {{ $log->user_name }}
                                        <p class="text-xs text-zinc-500 dark:text-zinc-500">{{ $log->user_email }}</p>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                            {{ $log->status === 'success' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : '' }}
                                            {{ $log->status === 'failed' ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' : '' }}
                                            {{ $log->status === 'in_progress' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' : '' }}">
                                            {{ ucfirst($log->status) }}
                                        </span>
                                        @if($log->error_message)
                                            <p class="text-xs text-red-600 dark:text-red-400 mt-1">{{ $log->error_message }}</p>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm text-zinc-600 dark:text-zinc-400">
                                        {{ $log->created_at->format('d M Y H:i:s') }}
                                        <p class="text-xs text-zinc-500 dark:text-zinc-500">{{ $log->created_at->diffForHumans() }}</p>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-zinc-600 dark:text-zinc-400">
                                        {{ $log->formatted_duration }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-8 text-center text-sm text-zinc-500 dark:text-zinc-400">
                                        Belum ada aktivitas
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($logs->hasPages())
                    <div class="p-6 border-t border-zinc-200 dark:border-zinc-700">
                        {{ $logs->links() }}
                    </div>
                @endif
            </div>
        </div>
    </flux:main>

    @push('scripts')
    <script>
        // Show alert message
        function showAlert(message, type = 'success') {
            const alertContainer = document.getElementById('alert-container');
            const alertClass = type === 'success' 
                ? 'bg-green-50 border-green-200 text-green-800 dark:bg-green-900 dark:border-green-700 dark:text-green-200'
                : 'bg-red-50 border-red-200 text-red-800 dark:bg-red-900 dark:border-red-700 dark:text-red-200';
            
            alertContainer.innerHTML = `
                <div class="border rounded-lg p-4 ${alertClass}">
                    <div class="flex items-center justify-between">
                        <p class="text-sm font-medium">${message}</p>
                        <button onclick="this.parentElement.parentElement.remove()" class="text-current opacity-70 hover:opacity-100">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            `;
            
            // Auto remove after 5 seconds
            setTimeout(() => {
                alertContainer.innerHTML = '';
            }, 5000);
        }

        // Backup form submission
        document.getElementById('backup-form').addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const submitBtn = e.target.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<div class="flex items-center justify-center gap-2"><svg class="animate-spin h-5 w-5" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg><span>Memproses...</span></div>';
            
            const formData = new FormData(e.target);
            
            try {
                const response = await fetch('{{ route("admin.backup-restore.backup") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    },
                    body: formData
                });
                
                const data = await response.json();
                
                if (data.success) {
                    showAlert('✓ ' + data.message, 'success');
                    e.target.reset();
                    setTimeout(() => location.reload(), 2000);
                } else {
                    showAlert('✗ ' + data.message, 'error');
                }
            } catch (error) {
                showAlert('✗ Terjadi kesalahan: ' + error.message, 'error');
            } finally {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            }
        });

        // Restore form submission
        document.getElementById('restore-form').addEventListener('submit', async (e) => {
            e.preventDefault();
            
            if (!confirm('Apakah Anda yakin ingin melakukan restore? Data yang ada akan diganti dengan data dari backup!')) {
                return;
            }
            
            const submitBtn = e.target.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<div class="flex items-center justify-center gap-2"><svg class="animate-spin h-5 w-5" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg><span>Memproses...</span></div>';
            
            const formData = new FormData(e.target);
            
            try {
                const response = await fetch('{{ route("admin.backup-restore.restore") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    },
                    body: formData
                });
                
                const data = await response.json();
                
                if (data.success) {
                    showAlert('✓ ' + data.message, 'success');
                    e.target.reset();
                    setTimeout(() => location.reload(), 2000);
                } else {
                    showAlert('✗ ' + data.message, 'error');
                }
            } catch (error) {
                showAlert('✗ Terjadi kesalahan: ' + error.message, 'error');
            } finally {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            }
        });

        // Delete backup file
        async function deleteBackup(filename) {
            if (!confirm('Apakah Anda yakin ingin menghapus file backup ini?')) {
                return;
            }
            
            try {
                const response = await fetch('{{ route("admin.backup-restore.delete") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ filename })
                });
                
                const data = await response.json();
                
                if (data.success) {
                    showAlert('✓ ' + data.message, 'success');
                    setTimeout(() => location.reload(), 1000);
                } else {
                    showAlert('✗ ' + data.message, 'error');
                }
            } catch (error) {
                showAlert('✗ Terjadi kesalahan: ' + error.message, 'error');
            }
        }
    </script>
    @endpush
</x-layouts.app.sidebar>
