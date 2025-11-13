<x-layouts.pemerintah>
    <div class="container mx-auto px-4 py-8">
        <!-- Page Header -->
        <div class="bg-white dark:bg-neutral-800 rounded-lg shadow p-6 mb-6">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h1 class="text-2xl font-bold text-neutral-900 dark:text-white flex items-center gap-2">
                        <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Riwayat Prediksi NBM
                    </h1>
                    <p class="text-sm text-neutral-600 dark:text-neutral-400 mt-1">
                        Kelola dan tinjau kembali prediksi yang telah disimpan
                    </p>
                </div>
                <a href="{{ route('pemerintah.prediksi-nbm') }}" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    Prediksi Baru
                </a>
            </div>

            <!-- Filters -->
            <div class="flex flex-wrap gap-3 mt-4">
                <form method="GET" action="{{ route('pemerintah.prediksi-nbm.history') }}" class="flex flex-wrap gap-3 w-full">
                    <input type="text" name="komoditi" placeholder="Filter Komoditi..." 
                           value="{{ request('komoditi') }}"
                           class="px-3 py-2 border border-neutral-300 dark:border-neutral-600 rounded-lg bg-white dark:bg-neutral-700 text-neutral-900 dark:text-white text-sm">
                    
                    <label class="flex items-center gap-2 text-sm text-neutral-700 dark:text-neutral-300">
                        <input type="checkbox" name="bookmarked" value="1" 
                               {{ request('bookmarked') ? 'checked' : '' }}
                               class="rounded border-neutral-300 dark:border-neutral-600">
                        Hanya Bookmark
                    </label>

                    <button type="submit" class="px-4 py-2 bg-neutral-600 hover:bg-neutral-700 text-white rounded-lg text-sm transition">
                        Filter
                    </button>

                    @if(request()->anyFilled(['komoditi', 'bookmarked']))
                        <a href="{{ route('pemerintah.prediksi-nbm.history') }}" class="px-4 py-2 bg-neutral-300 hover:bg-neutral-400 dark:bg-neutral-700 dark:hover:bg-neutral-600 text-neutral-900 dark:text-white rounded-lg text-sm transition">
                            Reset
                        </a>
                    @endif
                </form>
            </div>
        </div>

        <!-- History List -->
        @if($predictions->count() > 0)
            <div class="space-y-4">
                @foreach($predictions as $prediction)
                    <div class="bg-white dark:bg-neutral-800 rounded-lg shadow hover:shadow-lg transition p-6">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="flex items-center gap-3 mb-2">
                                    <h3 class="text-lg font-semibold text-neutral-900 dark:text-white">
                                        {{ $prediction->komoditi_name }}
                                    </h3>
                                    <span class="px-2 py-1 text-xs font-medium bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 rounded">
                                        {{ $prediction->kelompok_name }}
                                    </span>
                                    @if($prediction->is_bookmarked)
                                        <span class="text-yellow-500" title="Bookmarked">⭐</span>
                                    @endif
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-3">
                                    <div class="text-sm">
                                        <span class="text-neutral-500 dark:text-neutral-400">Periode Prediksi:</span>
                                        <span class="font-medium text-neutral-900 dark:text-white ml-2">{{ $prediction->bulan_prediksi }} bulan</span>
                                    </div>
                                    <div class="text-sm">
                                        <span class="text-neutral-500 dark:text-neutral-400">Rata-rata:</span>
                                        <span class="font-medium text-neutral-900 dark:text-white ml-2">{{ number_format($prediction->averagePrediction, 2) }} kal/hari</span>
                                    </div>
                                    <div class="text-sm">
                                        <span class="text-neutral-500 dark:text-neutral-400">Tanggal:</span>
                                        <span class="font-medium text-neutral-900 dark:text-white ml-2">{{ $prediction->created_at->format('d M Y, H:i') }}</span>
                                    </div>
                                </div>

                                @if($prediction->notes)
                                    <div class="mt-3 text-sm text-neutral-600 dark:text-neutral-400">
                                        <span class="font-medium">Catatan:</span> {{ $prediction->notes }}
                                    </div>
                                @endif

                                <!-- Quick Stats -->
                                <div class="mt-4 flex flex-wrap gap-2">
                                    <span class="px-2 py-1 text-xs bg-neutral-100 dark:bg-neutral-700 text-neutral-700 dark:text-neutral-300 rounded">
                                        📊 {{ count($prediction->prediction_data) }} data point
                                    </span>
                                    <span class="px-2 py-1 text-xs bg-neutral-100 dark:bg-neutral-700 text-neutral-700 dark:text-neutral-300 rounded">
                                        🔬 Model: {{ $prediction->model_version ?? 'unknown' }}
                                    </span>
                                    @if($prediction->confidence_intervals)
                                        <span class="px-2 py-1 text-xs bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-300 rounded">
                                            ✓ Dengan Confidence Interval
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex flex-col gap-2 ml-4">
                                <button onclick="toggleBookmark({{ $prediction->id }}, this)" 
                                        class="p-2 rounded-lg transition {{ $prediction->is_bookmarked ? 'bg-yellow-100 dark:bg-yellow-900 text-yellow-600 dark:text-yellow-400' : 'bg-neutral-100 dark:bg-neutral-700 text-neutral-600 dark:text-neutral-400 hover:bg-neutral-200 dark:hover:bg-neutral-600' }}"
                                        title="{{ $prediction->is_bookmarked ? 'Remove Bookmark' : 'Bookmark' }}">
                                    <svg class="w-5 h-5" fill="{{ $prediction->is_bookmarked ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/>
                                    </svg>
                                </button>

                                <button onclick="viewDetails({{ $prediction->id }})" 
                                        class="p-2 bg-blue-100 dark:bg-blue-900 text-blue-600 dark:text-blue-400 rounded-lg hover:bg-blue-200 dark:hover:bg-blue-800 transition"
                                        title="View Details">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </button>

                                <button onclick="rerunPrediction({{ $prediction->id }})" 
                                        class="p-2 bg-green-100 dark:bg-green-900 text-green-600 dark:text-green-400 rounded-lg hover:bg-green-200 dark:hover:bg-green-800 transition"
                                        title="Re-run Prediction">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                    </svg>
                                </button>

                                <button onclick="deletePrediction({{ $prediction->id }}, this)" 
                                        class="p-2 bg-red-100 dark:bg-red-900 text-red-600 dark:text-red-400 rounded-lg hover:bg-red-200 dark:hover:bg-red-800 transition"
                                        title="Delete">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                {{ $predictions->links() }}
            </div>
        @else
            <!-- Empty State -->
            <div class="bg-white dark:bg-neutral-800 rounded-lg shadow p-12 text-center">
                <svg class="w-16 h-16 text-neutral-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <h3 class="text-lg font-semibold text-neutral-900 dark:text-white mb-2">
                    Belum Ada Prediksi Tersimpan
                </h3>
                <p class="text-neutral-600 dark:text-neutral-400 mb-4">
                    Mulai buat prediksi dan simpan untuk tracking di masa depan
                </p>
                <a href="{{ route('pemerintah.prediksi-nbm') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    Buat Prediksi Pertama
                </a>
            </div>
        @endif
    </div>

    <!-- Detail Modal -->
    <div id="detailModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
        <div class="bg-white dark:bg-neutral-800 rounded-lg shadow-xl max-w-4xl w-full max-h-[90vh] overflow-y-auto">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-bold text-neutral-900 dark:text-white">Detail Prediksi</h2>
                    <button onclick="closeDetailModal()" class="text-neutral-500 hover:text-neutral-700 dark:hover:text-neutral-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                <div id="detailContent" class="text-neutral-900 dark:text-white">
                    <!-- Content will be loaded here -->
                    <div class="text-center py-8">
                        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600 mx-auto"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Toggle bookmark
        async function toggleBookmark(id, button) {
            try {
                const response = await fetch(`{{ url('/pemerintah/prediksi-nbm/bookmark') }}/${id}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });

                const result = await response.json();

                if (result.success) {
                    // Update button appearance
                    const svg = button.querySelector('svg');
                    if (result.is_bookmarked) {
                        button.classList.remove('bg-neutral-100', 'dark:bg-neutral-700', 'text-neutral-600', 'dark:text-neutral-400');
                        button.classList.add('bg-yellow-100', 'dark:bg-yellow-900', 'text-yellow-600', 'dark:text-yellow-400');
                        svg.setAttribute('fill', 'currentColor');
                    } else {
                        button.classList.remove('bg-yellow-100', 'dark:bg-yellow-900', 'text-yellow-600', 'dark:text-yellow-400');
                        button.classList.add('bg-neutral-100', 'dark:bg-neutral-700', 'text-neutral-600', 'dark:text-neutral-400');
                        svg.setAttribute('fill', 'none');
                    }
                } else {
                    alert('Gagal mengubah bookmark: ' + result.message);
                }
            } catch (error) {
                console.error('Bookmark error:', error);
                alert('Terjadi kesalahan');
            }
        }

        // View details
        function viewDetails(id) {
            const modal = document.getElementById('detailModal');
            const content = document.getElementById('detailContent');
            
            modal.classList.remove('hidden');
            
            // Fetch prediction details
            fetch(`{{ url('/pemerintah/prediksi-nbm') }}/${id}`)
                .then(response => response.json())
                .then(data => {
                    let html = `
                        <div class="space-y-4">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <span class="text-sm text-neutral-500 dark:text-neutral-400">Komoditi:</span>
                                    <p class="font-semibold">${data.komoditi_name}</p>
                                </div>
                                <div>
                                    <span class="text-sm text-neutral-500 dark:text-neutral-400">Kelompok:</span>
                                    <p class="font-semibold">${data.kelompok_name}</p>
                                </div>
                            </div>
                            
                            <div>
                                <h3 class="font-semibold mb-2">Hasil Prediksi:</h3>
                                <div class="overflow-x-auto">
                                    <table class="w-full text-sm border border-neutral-200 dark:border-neutral-700">
                                        <thead class="bg-neutral-50 dark:bg-neutral-900">
                                            <tr>
                                                <th class="px-4 py-2 text-left">Periode</th>
                                                <th class="px-4 py-2 text-right">Prediksi (kal/hari)</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            ${data.prediction_data.map((val, idx) => `
                                                <tr class="border-t border-neutral-200 dark:border-neutral-700">
                                                    <td class="px-4 py-2">Bulan ${idx + 1}</td>
                                                    <td class="px-4 py-2 text-right font-semibold">${val.toFixed(2)}</td>
                                                </tr>
                                            `).join('')}
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    `;
                    content.innerHTML = html;
                })
                .catch(error => {
                    content.innerHTML = '<p class="text-red-600">Gagal memuat detail</p>';
                });
        }

        // Close modal
        function closeDetailModal() {
            document.getElementById('detailModal').classList.add('hidden');
        }

        // Re-run prediction
        function rerunPrediction(id) {
            // Redirect to prediksi page with pre-filled data
            window.location.href = `{{ route('pemerintah.prediksi-nbm') }}?rerun=${id}`;
        }

        // Delete prediction
        async function deletePrediction(id, button) {
            if (!confirm('Yakin ingin menghapus prediksi ini?')) return;

            try {
                const response = await fetch(`{{ url('/pemerintah/prediksi-nbm') }}/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });

                const result = await response.json();

                if (result.success) {
                    // Remove the card with animation
                    const card = button.closest('.bg-white, .dark\\:bg-neutral-800');
                    card.style.opacity = '0';
                    card.style.transform = 'translateX(-20px)';
                    card.style.transition = 'all 0.3s';
                    
                    setTimeout(() => {
                        card.remove();
                        
                        // Check if list is empty
                        const container = document.querySelector('.space-y-4');
                        if (!container.children.length) {
                            location.reload();
                        }
                    }, 300);
                } else {
                    alert('Gagal menghapus: ' + result.message);
                }
            } catch (error) {
                console.error('Delete error:', error);
                alert('Terjadi kesalahan');
            }
        }

        // Close modal on outside click
        document.getElementById('detailModal')?.addEventListener('click', function(e) {
            if (e.target === this) {
                closeDetailModal();
            }
        });
    </script>
</x-layouts.pemerintah>
