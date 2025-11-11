<x-layouts.pemerintah title="Data Iklim, OPT & DPI - Panel Pemerintah - {{ config('app.name') }}">
    <div class="container mx-auto px-4 py-8" x-data="iklimData()">
        {{-- Header --}}
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-neutral-900 dark:text-white">Data Iklim, OPT & DPI</h1>
            <p class="text-neutral-600 dark:text-neutral-400 mt-1">Informasi Iklim, Organisme Pengganggu Tanaman (OPT), dan Dampak Perubahan Iklim (DPI)</p>
        </div>

        {{-- Filter Section --}}
        <div class="bg-white dark:bg-neutral-800 rounded-lg shadow-sm p-6 mb-6">
            <h2 class="text-lg font-semibold text-neutral-900 dark:text-white mb-4">Filter Data</h2>
            
            <form @submit.prevent="filterData" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                {{-- Topik --}}
                <div>
                    <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                        Topik
                    </label>
                    <select x-model="filters.topik" @change="loadVariabels" 
                            class="w-full px-4 py-2 border border-neutral-300 dark:border-neutral-600 rounded-lg focus:ring-2 focus:ring-green-500 dark:bg-neutral-700 dark:text-white">
                        <option value="">-- Semua Topik --</option>
                        @foreach($topikOptions as $topik)
                            <option value="{{ $topik->id }}">{{ $topik->deskripsi }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Variabel --}}
                <div>
                    <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                        Variabel
                    </label>
                    <select x-model="filters.variabel" :disabled="!filters.topik"
                            class="w-full px-4 py-2 border border-neutral-300 dark:border-neutral-600 rounded-lg focus:ring-2 focus:ring-green-500 dark:bg-neutral-700 dark:text-white disabled:opacity-50">
                        <option value="">-- Pilih Topik Dulu --</option>
                        <template x-for="v in variabelOptions" :key="v.id">
                            <option :value="v.id" x-text="v.deskripsi + (v.satuan ? ' (' + v.satuan + ')' : '')"></option>
                        </template>
                    </select>
                </div>

                {{-- Klasifikasi --}}
                <div>
                    <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                        Klasifikasi
                    </label>
                    <select x-model="filters.klasifikasi"
                            class="w-full px-4 py-2 border border-neutral-300 dark:border-neutral-600 rounded-lg focus:ring-2 focus:ring-green-500 dark:bg-neutral-700 dark:text-white">
                        <option value="">-- Semua Klasifikasi --</option>
                        @foreach($klasifikasiOptions as $klasifikasi)
                            <option value="{{ $klasifikasi->id }}">{{ $klasifikasi->deskripsi }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Wilayah --}}
                <div>
                    <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                        Wilayah
                    </label>
                    <select x-model="filters.wilayah"
                            class="w-full px-4 py-2 border border-neutral-300 dark:border-neutral-600 rounded-lg focus:ring-2 focus:ring-green-500 dark:bg-neutral-700 dark:text-white">
                        <option value="">-- Semua Wilayah --</option>
                        @foreach($wilayahOptions as $wilayah)
                            <option value="{{ $wilayah->id }}">{{ $wilayah->nama }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Tahun --}}
                <div>
                    <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                        Tahun
                    </label>
                    <select x-model="filters.tahun"
                            class="w-full px-4 py-2 border border-neutral-300 dark:border-neutral-600 rounded-lg focus:ring-2 focus:ring-green-500 dark:bg-neutral-700 dark:text-white">
                        <option value="">-- Semua Tahun --</option>
                        @for($year = date('Y'); $year >= 2020; $year--)
                            <option value="{{ $year }}">{{ $year }}</option>
                        @endfor
                    </select>
                </div>

                {{-- Bulan --}}
                <div>
                    <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                        Bulan
                    </label>
                    <select x-model="filters.bulan"
                            class="w-full px-4 py-2 border border-neutral-300 dark:border-neutral-600 rounded-lg focus:ring-2 focus:ring-green-500 dark:bg-neutral-700 dark:text-white">
                        <option value="">-- Semua Bulan --</option>
                        @foreach(['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'] as $index => $namaBulan)
                            <option value="{{ $index + 1 }}">{{ $namaBulan }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Buttons --}}
                <div class="md:col-span-2 lg:col-span-3 flex gap-3">
                    <button type="submit" 
                            class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg font-medium transition-colors"
                            :disabled="loading">
                        <span x-show="!loading">Tampilkan Data</span>
                        <span x-show="loading" class="flex items-center">
                            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Loading...
                        </span>
                    </button>
                    
                    <button type="button" @click="resetFilters" 
                            class="px-6 py-2 bg-neutral-200 dark:bg-neutral-700 hover:bg-neutral-300 dark:hover:bg-neutral-600 text-neutral-700 dark:text-white rounded-lg font-medium transition-colors">
                        Reset
                    </button>

                    <button type="button" @click="exportData" 
                            x-show="data.length > 0"
                            class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition-colors flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Export Excel
                    </button>
                </div>
            </form>
        </div>

        {{-- Data Table --}}
        <div class="bg-white dark:bg-neutral-800 rounded-lg shadow-sm overflow-hidden" x-show="data.length > 0">
            <div class="p-4 border-b border-neutral-200 dark:border-neutral-700">
                <h3 class="text-lg font-semibold text-neutral-900 dark:text-white">
                    Hasil Data (<span x-text="pagination.total"></span> records)
                </h3>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-neutral-50 dark:bg-neutral-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 dark:text-neutral-300 uppercase tracking-wider">No</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 dark:text-neutral-300 uppercase tracking-wider">Tahun</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 dark:text-neutral-300 uppercase tracking-wider">Bulan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 dark:text-neutral-300 uppercase tracking-wider">Wilayah</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 dark:text-neutral-300 uppercase tracking-wider">Topik</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 dark:text-neutral-300 uppercase tracking-wider">Variabel</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 dark:text-neutral-300 uppercase tracking-wider">Klasifikasi</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-neutral-500 dark:text-neutral-300 uppercase tracking-wider">Nilai</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-neutral-800 divide-y divide-neutral-200 dark:divide-neutral-700">
                        <template x-for="(row, index) in data" :key="row.id">
                            <tr class="hover:bg-neutral-50 dark:hover:bg-neutral-700/50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-900 dark:text-neutral-100" x-text="((pagination.current_page - 1) * pagination.per_page) + index + 1"></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-900 dark:text-neutral-100" x-text="row.tahun"></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-900 dark:text-neutral-100" x-text="row.bulan?.nama || '-'"></td>
                                <td class="px-6 py-4 text-sm text-neutral-900 dark:text-neutral-100" x-text="row.wilayah?.nama || '-'"></td>
                                <td class="px-6 py-4 text-sm text-neutral-900 dark:text-neutral-100" x-text="row.variabel?.topik?.deskripsi || '-'"></td>
                                <td class="px-6 py-4 text-sm text-neutral-900 dark:text-neutral-100" x-text="row.variabel?.deskripsi || '-'"></td>
                                <td class="px-6 py-4 text-sm text-neutral-900 dark:text-neutral-100" x-text="row.klasifikasi?.deskripsi || '-'"></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-neutral-900 dark:text-neutral-100">
                                    <span x-text="parseFloat(row.nilai).toLocaleString('id-ID', {minimumFractionDigits: 2, maximumFractionDigits: 4})"></span>
                                    <span class="text-neutral-500 dark:text-neutral-400 text-xs ml-1" x-text="row.variabel?.satuan || ''"></span>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="px-6 py-4 border-t border-neutral-200 dark:border-neutral-700 flex items-center justify-between">
                <div class="text-sm text-neutral-700 dark:text-neutral-300">
                    Showing <span x-text="((pagination.current_page - 1) * pagination.per_page) + 1"></span> 
                    to <span x-text="Math.min(pagination.current_page * pagination.per_page, pagination.total)"></span> 
                    of <span x-text="pagination.total"></span> results
                </div>
                
                <div class="flex gap-2">
                    <button @click="changePage(pagination.current_page - 1)" 
                            :disabled="pagination.current_page === 1"
                            class="px-4 py-2 border border-neutral-300 dark:border-neutral-600 rounded-lg text-sm font-medium text-neutral-700 dark:text-neutral-300 hover:bg-neutral-50 dark:hover:bg-neutral-700 disabled:opacity-50 disabled:cursor-not-allowed">
                        Previous
                    </button>
                    <button @click="changePage(pagination.current_page + 1)" 
                            :disabled="pagination.current_page === pagination.last_page"
                            class="px-4 py-2 border border-neutral-300 dark:border-neutral-600 rounded-lg text-sm font-medium text-neutral-700 dark:text-neutral-300 hover:bg-neutral-50 dark:hover:bg-neutral-700 disabled:opacity-50 disabled:cursor-not-allowed">
                        Next
                    </button>
                </div>
            </div>
        </div>

        {{-- Empty State --}}
        <div x-show="!loading && data.length === 0 && hasSearched" 
             class="bg-white dark:bg-neutral-800 rounded-lg shadow-sm p-12 text-center">
            <svg class="mx-auto h-12 w-12 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-neutral-900 dark:text-white">Tidak ada data</h3>
            <p class="mt-1 text-sm text-neutral-500 dark:text-neutral-400">Silakan ubah filter untuk menemukan data.</p>
        </div>

        {{-- Initial State --}}
        <div x-show="!loading && data.length === 0 && !hasSearched" 
             class="bg-white dark:bg-neutral-800 rounded-lg shadow-sm p-12 text-center">
            <svg class="mx-auto h-12 w-12 text-neutral-400" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"/>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-neutral-900 dark:text-white">Gunakan Filter</h3>
            <p class="mt-1 text-sm text-neutral-500 dark:text-neutral-400">Pilih filter dan klik "Tampilkan Data" untuk melihat data iklim, OPT & DPI.</p>
        </div>
    </div>

    @push('scripts')
    <script>
        function iklimData() {
            return {
                filters: {
                    topik: '',
                    variabel: '',
                    klasifikasi: '',
                    wilayah: '',
                    tahun: '',
                    bulan: '',
                    limit: 100
                },
                variabelOptions: [],
                data: [],
                pagination: {
                    current_page: 1,
                    last_page: 1,
                    per_page: 100,
                    total: 0
                },
                loading: false,
                hasSearched: false,

                async loadVariabels() {
                    if (!this.filters.topik) {
                        this.variabelOptions = [];
                        this.filters.variabel = '';
                        return;
                    }

                    try {
                        const response = await fetch(`/pemerintah/iklim/variabels/${this.filters.topik}`);
                        const result = await response.json();
                        
                        if (result.success) {
                            this.variabelOptions = result.data;
                        }
                    } catch (error) {
                        console.error('Error loading variabels:', error);
                    }
                },

                async filterData() {
                    this.loading = true;
                    this.hasSearched = true;

                    try {
                        const formData = new FormData();
                        Object.keys(this.filters).forEach(key => {
                            if (this.filters[key]) {
                                formData.append(key, this.filters[key]);
                            }
                        });

                        const response = await fetch('/pemerintah/iklim/filter', {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            }
                        });

                        const result = await response.json();

                        if (result.success) {
                            this.data = result.data;
                            this.pagination = result.pagination;
                        } else {
                            alert(result.message || 'Gagal memuat data');
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        alert('Terjadi kesalahan saat memuat data');
                    } finally {
                        this.loading = false;
                    }
                },

                resetFilters() {
                    this.filters = {
                        topik: '',
                        variabel: '',
                        klasifikasi: '',
                        wilayah: '',
                        tahun: '',
                        bulan: '',
                        limit: 100
                    };
                    this.variabelOptions = [];
                    this.data = [];
                    this.hasSearched = false;
                },

                async changePage(page) {
                    if (page < 1 || page > this.pagination.last_page) return;
                    
                    this.filters.page = page;
                    await this.filterData();
                },

                exportData() {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '/pemerintah/iklim/export';
                    
                    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
                    const csrfInput = document.createElement('input');
                    csrfInput.type = 'hidden';
                    csrfInput.name = '_token';
                    csrfInput.value = csrfToken;
                    form.appendChild(csrfInput);

                    Object.keys(this.filters).forEach(key => {
                        if (this.filters[key] && key !== 'limit') {
                            const input = document.createElement('input');
                            input.type = 'hidden';
                            input.name = key;
                            input.value = this.filters[key];
                            form.appendChild(input);
                        }
                    });

                    document.body.appendChild(form);
                    form.submit();
                    document.body.removeChild(form);
                }
            }
        }
    </script>
    @endpush
</x-layouts.pemerintah>
