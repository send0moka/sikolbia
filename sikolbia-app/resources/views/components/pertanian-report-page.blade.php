<x-layouts.landing :title="$title">
    <!-- Add Chart.js for data visualization -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Add SheetJS library for Excel export -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
    <style>
        /* Alpine cloak to prevent flicker */
        [x-cloak] { display: none !important; }
    </style>

<!-- Main Content -->
<div class="py-12 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumb -->
        <nav class="flex mb-8" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('home') }}" class="text-neutral-700 hover:text-blue-600">Home</a>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-neutral-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="ml-1 text-neutral-500">Non-Komoditas Pertanian</span>
                    </div>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-neutral-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="ml-1 text-blue-600 font-medium">{{ $title }}</span>
                    </div>
                </li>
            </ol>
        </nav>

        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl md:text-4xl font-bold text-neutral-900 mb-4">
                {{ $title }}
            </h1>
            <p class="text-xl text-neutral-600">
                {{ $description }}
            </p>
        </div>
        
<!-- Form and Results Section -->
<div x-data="pertanianReportForm({ moduleType: '{{ $moduleType }}', initialData: {{ Js::from($initialData) }} })" x-init="init()" class="space-y-12">
    <!-- Step 1: Select Data -->
    <section class="bg-neutral-50 rounded-lg p-6 border border-neutral-200">
        <h2 class="text-2xl font-bold text-neutral-800 mb-1 flex items-center">
            <span class="bg-blue-600 text-white rounded-full h-8 w-8 flex items-center justify-center mr-3">1</span>
            Pilih Data
        </h2>
        <p class="text-neutral-600 mb-4 ml-11">Pilih Topik, Variabel, Klasifikasi, serta Periode Waktu.</p>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <!-- Left column: Topik -->
            <div class="bg-white p-4 rounded-lg border">
                <h3 class="font-semibold text-neutral-900 mb-3">1.1 Topik</h3>
                <div class="overflow-y-auto border rounded-md p-2 max-h-40">
                    <template x-for="topik in allData.topiks" :key="topik.id">
                        <div @click="selectTopik(topik.id)" class="flex items-center cursor-pointer hover:bg-blue-50 p-2 rounded-md" :class="{'bg-blue-100 font-semibold': selection.topik_id === topik.id}">
                            <span class="ml-2 text-sm text-neutral-700" x-text="topik.nama"></span>
                        </div>
                    </template>
                </div>
            </div>

            <!-- Right column: Variabel + Klasifikasi stacked -->
            <div class="space-y-4 h-full flex flex-col">
                <!-- 1.2 Variabel -->
                <div class="bg-white p-4 rounded-lg border flex-1 flex flex-col">
                    <h3 class="font-semibold text-neutral-900 mb-3">1.2 Variabel</h3>
                    <div x-show="!selection.topik_id" class="text-center py-4 text-neutral-500 border rounded-md">
                        <p class="text-sm">Pilih topik terlebih dahulu</p>
                    </div>
                    <div x-show="selection.topik_id" class="flex-1 flex flex-col">
                        <div class="flex-1 overflow-y-auto border rounded-md p-2 max-h-40">
                            <template x-for="variabel in filteredVariabel" :key="variabel.id">
                                <label class="flex items-center cursor-pointer hover:bg-blue-50 p-2 rounded-md" :class="{'bg-blue-100 font-semibold': selection.variabel_id == variabel.id}">
                                    <input type="radio" name="variabel_pick" :value="variabel.id" x-model="selection.variabel_id" class="form-radio text-blue-600">
                                    <span class="ml-2 text-sm text-neutral-700 flex-1" x-text="variabel.nama + (variabel.satuan ? ' (' + variabel.satuan + ')' : '')"></span>
                                </label>
                            </template>
                        </div>
                    </div>
                </div>

                <!-- 1.3 Klasifikasi -->
                <div class="bg-white p-4 rounded-lg border flex-1 flex flex-col">
                    <h3 class="font-semibold text-neutral-900 mb-3">1.3 Klasifikasi</h3>
                    <div x-show="!selection.variabel_id" class="text-center py-4 text-neutral-500 border rounded-md">
                        <p class="text-sm">Pilih variabel terlebih dahulu</p>
                    </div>
                    <div x-show="selection.variabel_id && filteredKlasifikasi.length > 0" class="flex-1 flex flex-col">
                        <div class="flex-1 overflow-y-auto border rounded-md p-2 max-h-32">
                            <template x-for="klasifikasi in filteredKlasifikasi" :key="klasifikasi.id">
                                <label class="flex items-center cursor-pointer p-2 rounded-md hover:bg-blue-50">
                                    <input type="checkbox" :value="klasifikasi.id" x-model="selection.klasifikasi_ids" class="form-checkbox text-blue-600">
                                    <span class="ml-2 text-sm flex-1" x-text="klasifikasi.nama"></span>
                                </label>
                            </template>
                        </div>
                    </div>
                    <div x-show="selection.variabel_id && filteredKlasifikasi.length === 0" class="text-center text-neutral-500 py-4 border rounded-md">
                        <p class="text-sm">Tidak ada klasifikasi untuk variabel ini</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- 1.4 Waktu with Tahun & Bulan stacked -->
        <div class="bg-white p-4 rounded-lg border mt-4">
            <h3 class="font-semibold text-neutral-900 mb-3">1.4 Waktu</h3>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                <!-- Tahun box -->
                <div class="flex flex-col border rounded-lg p-3">
                    <div class="flex justify-between items-center mb-2">
                        <label class="block font-medium text-neutral-700 text-lg">Tahun</label>
                        <div class="flex gap-1">
                            <button @click="selection.tahun_ids = filteredTahun" class="text-xs text-blue-600 hover:underline">Select All</button>
                            <button @click="selection.tahun_ids = []" class="text-xs text-red-600 hover:underline">Clear</button>
                        </div>
                    </div>
                    <input type="text" x-model="search.tahun" placeholder="Cari tahun..." class="w-full px-2 py-1 border border-neutral-300 rounded text-sm mb-2">
                    <div class="overflow-y-auto border rounded-md p-2 max-h-48">
                        <template x-for="tahun in filteredTahun" :key="tahun">
                            <label class="flex items-center cursor-pointer hover:bg-blue-50 p-2 rounded-md">
                                <input type="checkbox" :value="tahun" x-model="selection.tahun_ids" class="form-checkbox text-blue-600">
                                <span class="ml-2 text-sm text-neutral-700" x-text="tahun"></span>
                            </label>
                        </template>
                    </div>
                </div>

                <!-- Bulan box -->
                <div class="flex flex-col border rounded-lg p-3 bg-white">
                    <div class="flex justify-between items-center mb-2">
                        <label class="block font-medium text-neutral-700 text-lg">Bulan</label>
                        <div class="flex gap-1" x-show="moduleType !== 'lahan'">
                            <button @click="selection.bulan_ids = filteredBulan.map(b => b.id)" class="text-xs text-blue-600 hover:underline">Select All</button>
                            <button @click="selection.bulan_ids = []" class="text-xs text-red-600 hover:underline">Clear</button>
                        </div>
                    </div>
                    <template x-if="moduleType === 'lahan'">
                        <div class="flex-1 min-h-48 flex items-center justify-center text-center text-neutral-600 bg-white rounded-md">
                            Tidak ada data bulanan untuk lahan saat ini.
                        </div>
                    </template>
                    <template x-if="moduleType !== 'lahan'">
                        <div>
                            <input type="text" x-model="search.bulan" placeholder="Cari bulan..." class="w-full px-2 py-1 border border-neutral-300 rounded text-sm mb-2">
                            <div class="overflow-y-auto border rounded-md p-2 max-h-48">
                                <template x-for="bulan in filteredBulan" :key="bulan.id">
                                    <label class="flex items-center cursor-pointer hover:bg-indigo-50 p-2 rounded-md">
                                        <input type="checkbox" :value="bulan.id" x-model="selection.bulan_ids" class="form-checkbox text-indigo-600">
                                        <span class="ml-2 text-sm text-neutral-700" x-text="bulan.nama"></span>
                                    </label>
                                </template>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>

        

        <!-- Action Buttons Section -->
        <div class="flex items-center justify-between mb-4 mt-6">
            <div class="flex items-center gap-2">
                <button @click="addSelection" :disabled="!isSelectionValid()" class="bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-600 flex items-center disabled:opacity-50 disabled:cursor-not-allowed">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                    Tambah
                </button>
                <button @click="removeSelection()" :disabled="selectedForRemoval.length === 0" class="bg-red-500 text-white px-4 py-2 rounded-md hover:bg-red-600 flex items-center disabled:opacity-50 disabled:cursor-not-allowed">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 12H6"></path></svg>
                    Hapus
                </button>
            </div>
            <button @click="resetForm" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400">Set Ulang</button>
        </div>

        <!-- Data Terpilih -->
        <div class="bg-white p-4 rounded-lg border">
            <h3 class="font-semibold text-neutral-900 mb-3">Data Terpilih</h3>
            <div class="space-y-2">
                <template x-for="item in selections" :key="item.id">
                    <div class="flex items-center bg-blue-50 p-2 rounded-md">
                        <input type="checkbox" :value="item.id" x-model="selectedForRemoval" class="form-checkbox text-red-600 mr-3">
                        <div class="flex-1">
                            <div class="font-medium text-sm text-neutral-800">
                                <span class="text-blue-700" x-text="item.topik_nama"></span> » 
                                <span class="text-green-700" x-text="item.variabel_nama"></span>
                                <span class="text-gray-600 text-xs" x-text="item.variabel_satuan ? '(' + item.variabel_satuan + ')' : ''"></span> »
                                <span class="text-orange-700" x-text="item.klasifikasi_nama || 'Semua'"></span>
                            </div>
                            <div class="text-xs text-neutral-600 mt-1">
                                <span class="text-purple-700" x-text="item.tahun_awal + (item.tahun_akhir !== item.tahun_awal ? '-' + item.tahun_akhir : '')"></span>
                                <template x-if="'{{ $moduleType }}' !== 'lahan'">
                                    <span> | <span class="text-indigo-700" x-text="item.bulan_awal + (item.bulan_akhir !== item.bulan_awal ? '-' + item.bulan_akhir : '')"></span></span>
                                </template>
                            </div>
                        </div>
                    </div>
                </template>
                <div x-show="selections.length === 0" class="text-center text-neutral-500 py-4">
                    <p>Belum ada data yang ditambahkan.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Step 2: Konfigurasi Tampilan -->
    <section class="bg-neutral-50 rounded-lg p-6 border border-neutral-200">
        <h2 class="text-2xl font-bold text-neutral-800 mb-1 flex items-center">
            <span class="bg-blue-600 text-white rounded-full h-8 w-8 flex items-center justify-center mr-3">2</span>
            Konfigurasi Tampilan
        </h2>
        <p class="text-neutral-600 mb-6 ml-11">Pilih wilayah dan bagaimana data akan disajikan dalam tabel.</p>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-start">
            <!-- 2.1 Wilayah -->
            <div class="bg-white p-4 rounded-lg border flex flex-col min-h-0 overflow-hidden" x-ref="wilayahBox">
                <h3 class="font-semibold text-neutral-900 mb-3">2.1 Wilayah</h3>

                <select x-model="wilayahLevel" class="w-full px-3 py-2 border border-neutral-300 rounded-md mb-3 text-sm">
                    <option value="nasional">Tingkat Nasional (Provinsi)</option>
                    <option value="provinsi">Tingkat Provinsi (Kabupaten/Kota)</option>
                </select>

                <!-- Tingkat Nasional View -->
                <div x-show="wilayahLevel === 'nasional'">
                    <div class="flex justify-between items-center mb-2">
                        <span class="font-medium text-neutral-800 text-sm">Pilih Provinsi</span>
                        <div class="flex gap-2">
                            <button @click="selection.provinsi_ids = filteredWilayah.map(p => p.id)" class="text-xs text-blue-600 hover:underline">Select All</button>
                            <button @click="selection.provinsi_ids = []" class="text-xs text-red-600 hover:underline">Clear</button>
                        </div>
                    </div>
                    <input type="text" x-model="search.wilayah" placeholder="Cari provinsi..." class="w-full px-3 py-2 border border-neutral-300 rounded-md mb-2 text-sm">
                    <div class="overflow-y-auto border rounded-md p-2" data-wilayah-scroll>
                        <template x-for="provinsi in filteredWilayah" :key="provinsi.id">
                            <label class="flex items-center cursor-pointer p-1 rounded-md hover:bg-blue-50">
                                <input type="checkbox" :value="provinsi.id" x-model="selection.provinsi_ids" class="form-checkbox text-blue-600">
                                <span class="ml-2 text-sm font-medium text-neutral-800" x-text="provinsi.nama"></span>
                            </label>
                        </template>
                    </div>
                </div>

                <!-- Tingkat Provinsi View -->
                <div x-show="wilayahLevel === 'provinsi'" class="flex flex-col min-h-0">
                    <div class="mb-3">
                        <label class="block text-sm font-medium text-neutral-700 mb-1">Pilih Provinsi</label>
                        <select x-model="selectedProvinsiId" class="w-full px-3 py-2 border border-neutral-300 rounded-md text-sm">
                            <option value="">-- Pilih Provinsi --</option>
                            <template x-for="provinsi in allData.wilayahs" :key="provinsi.id">
                                <option :value="provinsi.id" x-text="provinsi.nama"></option>
                            </template>
                        </select>
                    </div>
                    
                    <template x-if="selectedProvinsiId">
                        <div class="flex flex-col min-h-0">
                            <div class="flex justify-between items-center mb-2">
                                <span class="font-medium text-neutral-800 text-sm">Pilih Kabupaten/Kota</span>
                                <div class="flex gap-2">
                                    <button @click="selectAllKabupatenInSelectedProvinsi()" class="text-xs text-blue-600 hover:underline">Select All</button>
                                    <button @click="clearKabupatenInSelectedProvinsi()" class="text-xs text-red-600 hover:underline">Clear</button>
                                </div>
                            </div>
                            <input type="text" x-model="search.wilayah" placeholder="Cari kabupaten/kota..." class="w-full px-3 py-2 border border-neutral-300 rounded-md mb-2 text-sm">
                            <div class="overflow-y-auto border rounded-md p-2" data-wilayah-scroll>
                                <template x-for="provinsi in filteredWilayah" :key="provinsi.id">
                                    <div x-show="provinsi.id == selectedProvinsiId">
                                        <template x-for="kabupaten in provinsi.kabupaten" :key="kabupaten.id">
                                            <label class="flex items-center cursor-pointer p-1 rounded-md hover:bg-blue-50">
                                                <input type="checkbox" :value="kabupaten.id" x-model="selection.kabupaten_ids" class="form-checkbox text-blue-600">
                                                <span class="ml-2 text-sm" x-text="kabupaten.nama"></span>
                                            </label>
                                        </template>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </template>
                    <div x-show="!selectedProvinsiId" class="text-center text-neutral-500 py-4 text-sm">
                        Pilih provinsi terlebih dahulu
                    </div>
                </div>
            </div>

            <!-- 2.2 Tata Letak Tabel -->
            <div class="bg-white p-4 rounded-lg border" x-ref="layoutBox">
                <h3 class="font-semibold text-neutral-900 mb-3">2.2 Tata Letak Tabel</h3>
                <div class="space-y-4">
                    <!-- Tipe 1 -->
                    <label class="block p-4 border rounded-lg cursor-pointer hover:border-blue-500" :class="{'border-blue-500 bg-blue-50 ring-2 ring-blue-200': selection.tata_letak === 'tipe_1'}">
                        <div class="flex gap-6 items-start">
                            <input type="radio" name="tata_letak" value="tipe_1" x-model="selection.tata_letak" class="mt-1">
                            <div class="flex-1">
                                <p class="font-semibold">Tipe 1: Master Header Variabel</p>
                                <p class="text-sm text-neutral-600 mb-2">Kolom diurutkan berdasarkan: Variabel » Klasifikasi » Tahun » Bulan</p>
                                <table class="w-full border-collapse text-xs mt-2 bg-white">
                                    <thead>
                                        <tr class="bg-neutral-100">
                                            <th rowspan="4" class="border p-1 font-semibold align-middle">Wilayah</th>
                                            <th colspan="4" class="border p-1 font-semibold">Variabel A</th>
                                        </tr>
                                        <tr class="bg-neutral-50">
                                            <th colspan="2" class="border p-1 font-normal">Klasifikasi X</th>
                                            <th colspan="2" class="border p-1 font-normal">Klasifikasi Y</th>
                                        </tr>
                                        <tr class="bg-neutral-50">
                                            <th colspan="1" class="border p-1 font-normal">2023</th>
                                            <th colspan="1" class="border p-1 font-normal">2024</th>
                                            <th colspan="1" class="border p-1 font-normal">2023</th>
                                            <th colspan="1" class="border p-1 font-normal">2024</th>
                                        </tr>
                                        <tr class="bg-neutral-50">
                                            <th class="border p-1 font-normal">Jan</th>
                                            <th class="border p-1 font-normal">Jan</th>
                                            <th class="border p-1 font-normal">Jan</th>
                                            <th class="border p-1 font-normal">Jan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="border p-1">Provinsi A</td>
                                            <td class="border p-1 text-center">...</td>
                                            <td class="border p-1 text-center">...</td>
                                            <td class="border p-1 text-center">...</td>
                                            <td class="border p-1 text-center">...</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </label>

                    <!-- Tipe 2 -->
                    <label class="block p-4 border rounded-lg cursor-pointer hover:border-blue-500" :class="{'border-blue-500 bg-blue-50 ring-2 ring-blue-200': selection.tata_letak === 'tipe_2'}">
                        <div class="flex gap-6 items-start">
                            <input type="radio" name="tata_letak" value="tipe_2" x-model="selection.tata_letak" class="mt-1">
                            <div class="flex-1">
                                <p class="font-semibold">Tipe 2: Master Header Klasifikasi</p>
                                <p class="text-sm text-neutral-600 mb-2">Kolom diurutkan berdasarkan: Klasifikasi » Variabel » Tahun » Bulan</p>
                                <table class="w-full border-collapse text-xs mt-2 bg-white">
                                    <thead>
                                        <tr class="bg-neutral-100">
                                            <th rowspan="4" class="border p-1 font-semibold align-middle">Wilayah</th>
                                            <th colspan="4" class="border p-1 font-semibold">Klasifikasi X</th>
                                        </tr>
                                        <tr class="bg-neutral-50">
                                            <th colspan="2" class="border p-1 font-normal">Variabel A</th>
                                            <th colspan="2" class="border p-1 font-normal">Variabel B</th>
                                        </tr>
                                        <tr class="bg-neutral-50">
                                            <th colspan="1" class="border p-1 font-normal">2023</th>
                                            <th colspan="1" class="border p-1 font-normal">2024</th>
                                            <th colspan="1" class="border p-1 font-normal">2023</th>
                                            <th colspan="1" class="border p-1 font-normal">2024</th>
                                        </tr>
                                        <tr class="bg-neutral-50">
                                            <th class="border p-1 font-normal">Jan</th>
                                            <th class="border p-1 font-normal">Jan</th>
                                            <th class="border p-1 font-normal">Jan</th>
                                            <th class="border p-1 font-normal">Jan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="border p-1">Provinsi A</td>
                                            <td class="border p-1 text-center">...</td>
                                            <td class="border p-1 text-center">...</td>
                                            <td class="border p-1 text-center">...</td>
                                            <td class="border p-1 text-center">...</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </label>

                    <!-- Tipe 3 -->
                    <label class="block p-4 border rounded-lg cursor-pointer hover:border-blue-500" :class="{'border-blue-500 bg-blue-50 ring-2 ring-blue-200': selection.tata_letak === 'tipe_3'}">
                        <div class="flex gap-6 items-start">
                            <input type="radio" name="tata_letak" value="tipe_3" x-model="selection.tata_letak" class="mt-1">
                            <div class="flex-1">
                                <p class="font-semibold">Tipe 3: Master Header Waktu</p>
                                <p class="text-sm text-neutral-600 mb-2">Kolom diurutkan berdasarkan: Tahun » Bulan » Variabel » Klasifikasi</p>
                                <table class="w-full border-collapse text-xs mt-2 bg-white">
                                    <thead>
                                        <tr class="bg-neutral-100">
                                            <th rowspan="4" class="border p-1 font-semibold align-middle">Wilayah</th>
                                            <th colspan="4" class="border p-1 font-semibold">2023</th>
                                        </tr>
                                        <tr class="bg-neutral-50">
                                            <th colspan="2" class="border p-1 font-normal">Januari</th>
                                            <th colspan="2" class="border p-1 font-normal">Februari</th>
                                        </tr>
                                        <tr class="bg-neutral-50">
                                            <th colspan="1" class="border p-1 font-normal">Variabel A</th>
                                            <th colspan="1" class="border p-1 font-normal">Variabel B</th>
                                            <th colspan="1" class="border p-1 font-normal">Variabel A</th>
                                            <th colspan="1" class="border p-1 font-normal">Variabel B</th>
                                        </tr>
                                        <tr class="bg-neutral-50">
                                            <th class="border p-1 font-normal">Klas. X</th>
                                            <th class="border p-1 font-normal">Klas. X</th>
                                            <th class="border p-1 font-normal">Klas. X</th>
                                            <th class="border p-1 font-normal">Klas. X</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="border p-1">Provinsi A</td>
                                            <td class="border p-1 text-center">...</td>
                                            <td class="border p-1 text-center">...</td>
                                            <td class="border p-1 text-center">...</td>
                                            <td class="border p-1 text-center">...</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </label>
                </div>
            </div>
        </div>
    </section>

    <!-- Submit Button -->
    <div class="flex justify-end mt-8">
        <button @click.prevent="fetchData" :disabled="isProcessing" class="w-full bg-blue-600 text-white font-bold py-3 px-4 rounded-md hover:bg-blue-700 disabled:bg-blue-300 flex items-center justify-center disabled:cursor-not-allowed">
            <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            Tampilkan Hasil
        </button>
    </div>

    <!-- Modal: Confirm clear all results (teleported) -->
    <template x-teleport="body">
        <div x-show="showClearConfirm" x-transition.opacity class="modal-root fixed inset-0 z-[1000] flex items-center justify-center">
            <div class="fixed inset-0 bg-black/40" style="backdrop-filter: blur(8px); -webkit-backdrop-filter: blur(8px);" @click="showClearConfirm=false"></div>
            <div class="relative bg-white rounded-lg shadow-xl border border-neutral-200 w-full max-w-md mx-4">
                <div class="p-5">
                    <div class="flex items-start gap-3">
                        <div class="flex-shrink-0 mt-0.5">
                            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M5.07 19h13.86a2 2 0 001.73-3l-6.93-12a2 2 0 00-3.46 0l-6.93 12a2 2 0 001.73 3z"/></svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-neutral-900">Kosongkan semua hasil?</h3>
                            <p class="mt-1 text-sm text-neutral-600">Tindakan ini akan menghapus semua hasil yang tersimpan di panel saat ini dan tidak dapat dibatalkan.</p>
                        </div>
                    </div>
                    <div class="mt-5 flex items-center justify-end gap-2">
                        <button type="button" @click="showClearConfirm=false" class="px-3 py-2 text-sm rounded border border-neutral-300 text-neutral-700 hover:bg-neutral-50">Batal</button>
                        <button type="button" @click="clearAllResultsConfirmed()" class="px-3 py-2 text-sm rounded bg-red-600 text-white hover:bg-red-700">Hapus Semua</button>
                    </div>
                </div>
            </div>
            <!-- Body scroll/blur handled by a combined toggler below -->
        </div>
        
    </template>

    <!-- Loading/Processing State (teleported, full-page blur like confirmation modal) -->
    <template x-teleport="body">
        <div x-show="isProcessing" x-transition.opacity class="modal-root fixed inset-0 z-[10000]">
            <!-- Backdrop blur overlay -->
            <div class="fixed inset-0 bg-white/40" style="backdrop-filter: blur(10px); -webkit-backdrop-filter: blur(10px);" aria-hidden="true"></div>
            <!-- Centered dialog -->
            <div class="fixed inset-0 flex items-center justify-center p-4">
                <div class="bg-white rounded-lg shadow-xl border border-neutral-200 w-full max-w-md mx-auto text-center p-6" role="dialog" aria-modal="true" aria-label="Loading">
                    <svg class="animate-spin w-12 h-12 text-blue-500 mx-auto mb-4" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <h3 class="text-lg font-semibold text-neutral-900 mb-2">Memproses Data...</h3>
                    <p class="text-neutral-700">Mohon tunggu, kami sedang menyiapkan laporan untuk Anda.</p>
                </div>
            </div>
            <!-- Body scroll/blur handled by a combined toggler below -->
        </div>
    </template>

    <!-- Single combined body lock/blur toggler (teleported) -->
    <template x-teleport="body">
        <div class="hidden" x-cloak
            x-effect="if(showClearConfirm || isProcessing){document.body.style.overflow='hidden'; document.body.classList.add('modal-open')} else {document.body.style.overflow=''; document.body.classList.remove('modal-open')}"
        ></div>
    </template>

    <!-- Step 3: Tampilan Hasil -->
    <section x-show="storedResults.length > 0" class="bg-neutral-50 rounded-lg p-6 border border-neutral-200 mt-12">
        <h2 class="text-2xl font-bold text-neutral-800 mb-1 flex items-center">
            <span class="bg-blue-600 text-white rounded-full h-8 w-8 flex items-center justify-center mr-3">3</span>
            Tampilan Hasil
        </h2>
        <p class="text-neutral-600 mb-6 ml-11">Hasil dari data yang telah Anda pilih.</p>

        <!-- Results Layout with Vertical Tabs -->
        <div class="flex gap-6">
            <!-- Left Sidebar - Result Selection & Actions -->
            <div class="w-48 flex-shrink-0">
                <div class="bg-white rounded-lg border overflow-hidden flex flex-col">
                    <div class="p-2 border-b bg-neutral-50 flex items-center gap-1">
                        <button type="button" @click="removeSelectedResults()" :disabled="selectedResultIds.length===0"
                                class="px-2 py-1 rounded text-xs font-medium"
                                :class="selectedResultIds.length===0 ? 'bg-rose-200 text-white cursor-not-allowed' : 'bg-rose-500 text-white hover:bg-rose-600'">
                            Hapus Dipilih
                        </button>
                        <button type="button" @click="clearAllResults()" :disabled="storedResults.length===0" title="Kosongkan semua hasil"
                                class="px-2 py-1 rounded text-xs font-medium ml-auto"
                                :class="storedResults.length===0 ? 'bg-neutral-200 text-neutral-500 cursor-not-allowed' : 'bg-neutral-700 text-white hover:bg-neutral-800'">
                            Kosongkan
                        </button>
                    </div>

                    <template x-for="(result, index) in storedResults" :key="result.id">
                        <div class="flex items-start border-b border-neutral-200 last:border-b-0">
                            <label class="p-3 pr-2">
                                <input type="checkbox" class="rounded" :checked="selectedResultIds.includes(result.id)" @click.stop @change="toggleResultSelection(result.id, $event.target.checked)">
                            </label>
                            <button @click="selectStoredResult(index)" :class="{'bg-blue-600 text-white': selectedResultIndex===index, 'bg-white text-neutral-700 hover:bg-neutral-50': selectedResultIndex!==index}"
                                    class="flex-1 px-2 py-3 text-left transition-colors">
                                <div class="font-medium text-sm" x-text="result.title"></div>
                                <div class="text-xs opacity-75 mt-1" x-text="result.timestamp"></div>
                            </button>
                        </div>
                    </template>
                </div>
            </div>

            <!-- Main Content Area -->
            <div class="flex-1 main-content-flex">
                <div x-show="selectedResultIndex !== null" class="bg-white rounded-lg border">
                    <!-- Tab Navigation -->
                    <div class="border-b border-neutral-200">
                        <nav class="-mb-px flex">
                            <button @click="activeResultTab = 'tabel'" 
                                    :class="{'border-blue-500 text-blue-600': activeResultTab === 'tabel', 'border-transparent text-neutral-500 hover:text-neutral-700 hover:border-neutral-300': activeResultTab !== 'tabel'}"
                                    class="whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm">
                                <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 6h18m-9 8h9"></path></svg>
                                Tabel
                            </button>
                            <button @click="activeResultTab = 'grafik'; $nextTick(() => renderChart())" 
                                    :class="{'border-blue-500 text-blue-600': activeResultTab === 'grafik', 'border-transparent text-neutral-500 hover:text-neutral-700 hover:border-neutral-300': activeResultTab !== 'grafik'}"
                                    class="whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm">
                                <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                                Grafik
                            </button>
                            <button @click="activeResultTab = 'metodologi'" 
                                    :class="{'border-blue-500 text-blue-600': activeResultTab === 'metodologi', 'border-transparent text-neutral-500 hover:text-neutral-700 hover:border-neutral-300': activeResultTab !== 'metodologi'}"
                                    class="whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm">
                                <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                Metodologi
                            </button>
                        </nav>
                    </div>

                    <!-- Tab Content -->
                    <div class="p-6 table-tab-content">
                        <!-- Tabel Tab -->
                        <div x-show="activeResultTab === 'tabel'">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-lg font-semibold text-neutral-900">Data Tabel</h3>
                                <button @click="exportExcel()" class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                    Export Excel
                                </button>
                            </div>
                            
                            <!-- Dynamic Table with Complex Headers -->
                            <div class="sticky-table-container">
                                <table class="sticky-table">
                                    <thead>
                                        <template x-for="(headerRow, rowIndex) in dynamicHeaders" :key="'header-row-' + rowIndex">
                                            <tr>
                                                <template x-for="(header, colIndex) in headerRow" :key="'header-' + rowIndex + '-' + colIndex">
                                                    <th :colspan="header.span || 1" 
                                                        :rowspan="header.rowspan || 1" 
                                                        :data-row-index="rowIndex + 1"
                                                        :class="header.name === 'Wilayah' ? 'sticky-wilayah-header' : ''"
                                                        class="bg-neutral-50 font-medium text-neutral-900 text-xs"
                                                        x-text="header.name">
                                                    </th>
                                                </template>
                                            </tr>
                                        </template>
                                    </thead>
                                    <tbody>
                                        <template x-for="(row, rowIndex) in dynamicRows" :key="'row-' + rowIndex">
                                            <tr class="hover:bg-neutral-50">
                                                <td x-text="row.wilayah" class="font-medium"></td>
                                                <template x-for="(value, valueIndex) in row.values" :key="'value-' + rowIndex + '-' + valueIndex">
                                                    <td x-text="value !== null && value !== undefined ? (typeof value === 'number' ? value.toLocaleString('id-ID', {minimumFractionDigits: 2, maximumFractionDigits: 2}) : value) : '-'" class="text-right"></td>
                                                </template>
                                            </tr>
                                        </template>
                                    </tbody>
                                </table>
                            </div>
                            
                            <div x-show="dynamicRows.length === 0" class="text-center text-neutral-500 py-8">
                                Tidak ada data untuk ditampilkan
                            </div>
                        </div>

                        <!-- Grafik Tab -->
                        <div x-show="activeResultTab === 'grafik'">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-lg font-semibold text-neutral-900">Visualisasi Data</h3>
                                <div class="flex items-center gap-3">
                                    <div class="flex items-center">
                                        <input type="checkbox" x-model="showLegend" @change="toggleLegend()" class="form-checkbox text-blue-600 mr-2" id="showLegend">
                                        <label for="showLegend" class="text-sm text-neutral-700">Tampilkan Legend</label>
                                    </div>
                                    <select x-model="selectedProvinceForScroll" @change="scrollToProvince()" class="px-3 py-1 border border-neutral-300 rounded text-sm">
                                        <option value="">Scroll ke Provinsi...</option>
                                        <template x-for="row in dynamicRows" :key="row.wilayah">
                                            <option :value="row.wilayah" x-text="row.wilayah"></option>
                                        </template>
                                    </select>
                                </div>
                            </div>
                            
                            <div id="chart-scroll" class="overflow-x-auto bg-neutral-50 rounded-lg border" style="height: 400px;">
                                <canvas id="dynamicChart" class="max-w-none"></canvas>
                            </div>
                            
                            <div x-show="dynamicRows.length === 0" class="text-center text-neutral-500 py-8">
                                Tidak ada data untuk divisualisasikan
                            </div>
                        </div>

                        <!-- Metodologi Tab -->
                        <div x-show="activeResultTab === 'metodologi'">
                            @php $opts = ['pertanian.partials.metodologi-' . $moduleType, 'pertanian.partials._metodologi_' . str_replace('-', '_', $moduleType)]; @endphp
                            @includeFirst($opts)
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @push('scripts')
    <script>
        // reserved for future enhancements
    </script>
    @endpush
    {{-- Chatbot button (moved inside x-data scope) --}}
    <div @click="chatOpen = true" role="button" aria-label="Buka chatbot"
         class="fixed bottom-6 right-6 z-50 bg-blue-600 text-white rounded-full p-4 h-16 w-16 flex items-center justify-center shadow-lg cursor-pointer hover:bg-blue-700 transition-transform hover:scale-110">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="h-8 w-8" fill="currentColor" aria-hidden="true">
            <!-- Chat bubble body -->
            <rect x="3" y="3" width="18" height="14" rx="3" ry="3"></rect>
            <!-- Tail -->
            <path d="M14 17 L18 17 L18 21 Z"></path>
            <!-- Dots -->
            <circle cx="9" cy="10" r="1.5"></circle>
            <circle cx="12" cy="10" r="1.5"></circle>
            <circle cx="15" cy="10" r="1.5"></circle>
        </svg>
    </div>

    {{-- Chatbot modal (teleported; retains this component scope) --}}
    <template x-teleport="body">
        <div x-show="chatOpen" x-cloak
             @keydown.escape.window="chatOpen = false"
             class="modal-root fixed inset-0 z-[1000] flex items-end justify-end p-4 sm:p-6">
            
            <div x-show="chatOpen" x-transition.opacity class="fixed inset-0 bg-black/30" x-cloak></div>

          <div @click.outside="chatOpen = false"
                 x-show="chatOpen" x-cloak
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="relative bg-white rounded-lg shadow-xl border w-full max-w-md max-h-[55vh] h-auto flex flex-col overflow-hidden min-h-0">
                
                <header class="p-4 border-b flex justify-between items-center flex-shrink-0">
                    <h3 class="font-bold text-lg text-neutral-800">Asisten Data Pertanian</h3>
                    <button @click="chatOpen = false" class="text-neutral-500 hover:text-neutral-800">&times;</button>
                </header>
                
                <main class="flex-1 p-4 overflow-y-auto space-y-4 min-h-0" x-ref="chatScroll">
                    <!-- Guided lock banner -->
                    <div x-show="guidedLock" class="text-xs bg-blue-50 border border-blue-200 text-blue-800 px-3 py-2 rounded flex items-center justify-between">
                        <span>Anda sedang berada di Guided Flow.</span>
                        <button type="button" class="underline hover:no-underline" @click="endGuidedFlow()">Akhiri guided flow</button>
                    </div>
                    <template x-for="(chat, index) in conversation" :key="index">
                        <div class="flex" :class="chat.sender === 'user' ? 'justify-end' : 'justify-start'">
                            <!-- Text bubble -->
                            <template x-if="!chat.type || chat.type === 'text'">
                                <p class="max-w-[80%] inline-block p-3 rounded-lg text-sm"
                                   :class="chat.sender === 'user' ? 'bg-blue-600 text-white' : 'bg-neutral-200 text-neutral-800'">
                                   <!-- Typewriter effect for bot natural replies -->
                                   <template x-if="chat.effect === 'typewriter' && chat.sender !== 'user'">
                                       <span x-data="{ out: '', full: chat.text, i: 0 }"
                                             x-init="const s = setInterval(() => { out = full.slice(0, ++i); if (i >= full.length) clearInterval(s); }, 12)"
                                             x-text="out"></span>
                                   </template>
                                   <template x-if="!chat.effect || chat.sender === 'user'">
                                       <span x-html="chat.text"></span>
                                   </template>
                                </p>
                            </template>

                            <!-- Options bubble (single select) -->
                            <template x-if="chat.type === 'options' && chat.variant !== 'onboarding'">
                                <div class="max-w-[90%] bg-neutral-200 text-neutral-800 p-3 rounded-lg">
                                    <p class="text-sm font-medium mb-2" x-text="chat.title || 'Pilih salah satu:'"></p>
                                    <div class="flex flex-wrap gap-2">
                                        <template x-for="opt in chat.options" :key="opt.value">
                                            <button type="button" class="px-3 py-1.5 rounded-full text-sm bg-white hover:bg-blue-50 border border-neutral-300"
                                                    @click="handleOption(index, opt)">
                                                <span x-text="opt.label"></span>
                                            </button>
                                        </template>
                                    </div>
                                    <div class="mt-3">
                                        <button type="button" class="text-sm text-neutral-700 underline" @click="stepBack()">Kembali satu langkah</button>
                                    </div>
                                </div>
                            </template>
                            <!-- Onboarding minimal chips (no container) -->
                            <template x-if="chat.type === 'options' && chat.variant === 'onboarding'">
                                <div class="max-w-[95%]">
                                    <div class="flex flex-wrap gap-2">
                                        <template x-for="opt in chat.options" :key="opt.value">
                                            <button type="button" class="px-2.5 py-1 rounded-full text-xs border border-neutral-300 bg-white hover:bg-blue-50"
                                                    @click="handleOption(index, opt)">
                                                <span x-text="opt.label"></span>
                                            </button>
                                        </template>
                                    </div>
                                </div>
                            </template>

                            <!-- Checklist bubble (multi select with confirm) -->
                            <template x-if="chat.type === 'checklist'">
                                <div class="max-w-[90%] bg-neutral-200 text-neutral-800 p-3 rounded-lg">
                                    <p class="text-sm font-medium mb-2" x-text="chat.title || 'Pilih beberapa:'"></p>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 max-h-52 overflow-y-auto">
                                        <template x-for="opt in chat.options" :key="opt.value">
                                            <label class="flex items-center gap-2 p-2 rounded-md bg-white hover:bg-blue-50 border">
                                                <input type="checkbox" class="form-checkbox text-blue-600"
                                                       :checked="chat.selected?.includes(opt.value)"
                                                       @change="toggleChecklist(chat, opt.value)">
                                                <span class="text-sm" x-text="opt.label"></span>
                                            </label>
                                        </template>
                                    </div>
                                    <div class="flex justify-between items-center gap-2 mt-3">
                                        <button type="button" class="text-sm text-neutral-700 underline" @click="stepBack()">Kembali</button>
                                        <div class="flex gap-2">
                                            <button type="button" class="text-sm text-neutral-700 underline" @click="clearChecklist(chat)">Bersihkan</button>
                                            <button type="button" class="px-3 py-1.5 rounded-md text-sm bg-blue-600 text-white hover:bg-blue-700"
                                                    @click="confirmChecklist(index)">Lanjut</button>
                                        </div>
                                    </div>
                                </div>
                            </template>

                            <!-- Table preview bubble -->
                            <template x-if="chat.type === 'table'">
                                <div class="max-w-[95%] bg-white text-neutral-800 p-3 rounded-lg border overflow-x-auto">
                                    <p class="text-sm font-medium mb-2" x-text="chat.title || 'Hasil pratinjau'">Hasil pratinjau</p>
                                    <!-- Minimal meta summary -->
                                    <div class="text-[13px] text-neutral-500 mb-2 space-y-0.5">
                                        <div x-show="chat.meta?.module"><span class="font-medium">Modul:</span> <span x-text="chat.meta.module"></span></div>
                                        <div x-show="chat.meta?.topik"><span class="font-medium">Topik:</span> <span x-text="chat.meta.topik"></span></div>
                                        <div x-show="chat.meta?.variabel"><span class="font-medium">Variabel:</span> <span x-text="chat.meta.variabel"></span></div>
                                        <div x-show="chat.meta?.klasifikasi"><span class="font-medium">Klasifikasi:</span> <span x-text="chat.meta.klasifikasi"></span></div>
                                    </div>
                                    <div class="overflow-x-auto">
                                        <table class="min-w-[640px] text-xs border-collapse">
                                            <thead>
                                                <tr>
                                                    <th class="border px-2 py-1 bg-neutral-50 whitespace-nowrap">Wilayah</th>
                                                    <template x-if="Array.isArray(chat.results?.headers) && chat.results.headers.length">
                                                        <template x-for="(h, cIdx) in chat.results.headers[chat.results.headers.length - 1]" :key="'h-'+cIdx">
                                                            <th class="border px-2 py-1 bg-neutral-50 whitespace-nowrap" x-text="h.name"></th>
                                                        </template>
                                                    </template>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <template x-for="(row, r) in (chat.results?.rows || []).slice(0, 8)" :key="'row-'+r">
                                                    <tr>
                                                        <td class="border px-2 py-1 font-medium" x-text="row.wilayah"></td>
                                                        <template x-for="(v, i) in (row.values || []).slice(0, (chat.results?.headers?.[chat.results.headers.length-1]?.length || row.values?.length || 0))" :key="'cell-'+r+'-'+i">
                                                            <td class="border px-2 py-1 text-right" x-text="v !== null && v !== undefined ? (typeof v === 'number' ? v.toLocaleString('id-ID', {minimumFractionDigits: 2, maximumFractionDigits: 2}) : v) : '-' "></td>
                                                        </template>
                                                    </tr>
                                                </template>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="text-xs text-neutral-600 mt-2" x-show="(chat.results?.rows || []).length > 8">Ditampilkan 8 baris pertama.</div>
                                    <div class="flex justify-end gap-2 mt-3">
                                        <button type="button" class="px-3 py-1.5 rounded-md text-sm bg-green-600 text-white hover:bg-green-700"
                                                @click="saveWizardResult(chat)">Simpan ke Panel</button>
                                        <button type="button" class="px-3 py-1.5 rounded-md text-sm bg-neutral-700 text-white hover:bg-neutral-800"
                                                @click="chatOpen=false; $nextTick(()=>selectStoredResult(storedResults.length-1))" x-show="storedResults.length>0">Buka Panel</button>
                                    </div>
                                </div>
                            </template>

                                <!-- Summary preview bubble -->
                            <template x-if="chat.type === 'summary'">
                                <div class="max-w-[95%] bg-white text-neutral-800 p-3 rounded-lg border">
                                    <p class="text-sm font-medium mb-2">Ringkasan Hasil</p>
                                    <div class="text-[13px] text-neutral-500 mb-2 space-y-0.5">
                                        <div x-show="chat.meta?.module"><span class="font-medium">Modul:</span> <span x-text="chat.meta.module"></span></div>
                                        <div x-show="chat.meta?.topik"><span class="font-medium">Topik:</span> <span x-text="chat.meta.topik"></span></div>
                                        <div x-show="chat.meta?.variabel"><span class="font-medium">Variabel:</span> <span x-text="chat.meta.variabel"></span></div>
                                        <div x-show="chat.meta?.klasifikasi"><span class="font-medium">Klasifikasi:</span> <span x-text="chat.meta.klasifikasi"></span></div>
                                    </div>
                                    <ul class="list-disc pl-5 text-sm text-neutral-700 space-y-1">
                                        <template x-for="(line, i) in (chat.summaryLines || [])" :key="'sum-'+i">
                                            <li x-text="line"></li>
                                        </template>
                                    </ul>
                                    <!-- Tiny insights toggle -->
                                    <div x-show="Array.isArray(chat.insights?.columns) && chat.insights.columns.length" class="mt-2">
                                        <button type="button" class="text-xs text-blue-700 underline hover:no-underline"
                                                @click="chat.showInsights = !chat.showInsights"
                                                x-text="chat.showInsights ? 'Sembunyikan insight' : 'Lihat lebih banyak insight'"></button>
                                        <div x-show="chat.showInsights" class="mt-2 border-t pt-2 space-y-1">
                                            <template x-for="(col, idx) in (chat.insights.columns || []).slice(0,3)" :key="'ins-'+idx">
                                                <div class="text-xs text-neutral-700">
                                                    <span class="font-medium" x-text="col.name"></span>:
                                                    <span x-show="col.max !== null">tertinggi <span class="font-medium" x-text="col.max_wilayah || '-' "></span> (<span x-text="(Number(col.max)||0).toLocaleString('id-ID', {minimumFractionDigits: 2, maximumFractionDigits: 2})"></span>)</span>
                                                    <span x-show="col.min !== null && col.min_wilayah && col.min_wilayah !== col.max_wilayah">, terendah <span class="font-medium" x-text="col.min_wilayah"></span> (<span x-text="(Number(col.min)||0).toLocaleString('id-ID', {minimumFractionDigits: 2, maximumFractionDigits: 2})"></span>)</span>
                                                    <span x-show="col.avg !== null">, rata-rata <span x-text="(Number(col.avg)||0).toLocaleString('id-ID', {minimumFractionDigits: 2, maximumFractionDigits: 2})"></span></span>
                                                </div>
                                            </template>

                                            <!-- Summary compare bubble -->
                                            <template x-if="chat.type === 'summary-compare'">
                                                <div class="max-w-[95%] bg-white text-neutral-800 p-3 rounded-lg border">
                                                    <p class="text-sm font-medium mb-2">Ringkasan Perbandingan</p>
                                                    <template x-if="chat.paragraph">
                                                        <p class="text-sm text-neutral-700 leading-relaxed" x-text="chat.paragraph"></p>
                                                    </template>
                                                    <template x-if="!chat.paragraph">
                                                        <ul class="list-disc pl-5 text-sm text-neutral-700 space-y-1">
                                                            <template x-for="(line, i) in (chat.summaryLines || [])" :key="'cmp-'+i">
                                                                <li x-text="line"></li>
                                                            </template>
                                                        </ul>
                                                    </template>
                                                    <div class="flex justify-end gap-2 mt-3">
                                                        <button type="button" class="px-3 py-1.5 rounded-md text-sm bg-blue-600 text-white hover:bg-blue-700" @click="showAsTable(chat)">Tampilkan Tabel</button>
                                                    </div>
                                                </div>
                                            </template>
                                        </div>
                                    </div>
                                    <div class="flex justify-end gap-2 mt-3">
                                        <button type="button" class="px-3 py-1.5 rounded-md text-sm bg-blue-600 text-white hover:bg-blue-700" @click="showAsTable(chat)">Tampilkan Tabel</button>
                                        <button type="button" class="px-3 py-1.5 rounded-md text-sm bg-green-600 text-white hover:bg-green-700" @click="saveWizardResult(chat)">Simpan ke Panel</button>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </template>
                    <!-- Typing indicator: three bouncing dots (larger, subtle) -->
                    <div x-show="isLoading" class="flex justify-start">
                        <div class="max-w-[80%] inline-block p-3 rounded-lg bg-neutral-200 text-neutral-800">
                            <div class="flex items-center gap-1.5" aria-live="polite" aria-label="Bot is typing">
                                <span class="w-2.5 h-2.5 bg-neutral-500 rounded-full animate-bounce" style="animation-delay:-0.2s"></span>
                                <span class="w-2.5 h-2.5 bg-neutral-500 rounded-full animate-bounce" style="animation-delay:0s"></span>
                                <span class="w-2.5 h-2.5 bg-neutral-500 rounded-full animate-bounce" style="animation-delay:0.2s"></span>
                            </div>
                        </div>
                    </div>
                    
                </main>

                <!-- In-panel Reset Confirmation Modal -->
                <div x-show="showChatResetConfirm" x-cloak class="absolute inset-0 z-20 flex items-center justify-center">
                    <div class="absolute inset-0 bg-black/30" @click="cancelResetConfirm()"></div>
                    <div class="relative bg-white rounded-lg shadow-lg border w-[92%] max-w-sm p-4">
                        <div class="flex items-start justify-between">
                            <h4 class="font-semibold text-neutral-900">Mulai Ulang Percakapan?</h4>
                            <button class="text-neutral-500 hover:text-neutral-800" @click="cancelResetConfirm()">&times;</button>
                        </div>
                        <p class="text-sm text-neutral-600 mt-2">Tindakan ini akan menghapus histori chat yang sedang tampil. Anda yakin ingin melanjutkan?</p>
                        <div class="flex justify-end gap-2 mt-4">
                            <button type="button" class="px-3 py-1.5 rounded-md text-sm border hover:bg-neutral-50" @click="cancelResetConfirm()">Batal</button>
                            <button type="button" class="px-3 py-1.5 rounded-md text-sm bg-red-600 text-white hover:bg-red-700" @click="confirmReset()">Mulai Ulang</button>
                        </div>
                    </div>
                </div>

                <footer class="p-4 border-t flex-shrink-0">
                    <div class="flex flex-col gap-2">
                        <form @submit.prevent="sendMessage" class="flex gap-2">
                            <input type="text" x-model="userMessage" :disabled="isLoading || guidedLock" class="w-full border rounded-md p-2 text-sm" placeholder="Ketik pertanyaan Anda...">
                            <button type="submit" :disabled="isLoading || guidedLock" class="bg-blue-600 text-white rounded-md px-4 disabled:bg-blue-300">Kirim</button>
                        </form>
                        <div class="flex justify-between items-center">
                            <button type="button" @click="openResetConfirm()"
                            class="text-sm text-neutral-600 hover:text-neutral-900 underline">Mulai Ulang</button>
                            <div class="text-xs text-neutral-500" x-text="guidedLock ? 'Gunakan pilihan yang tersedia atau akhiri guided flow untuk mengetik.' : 'Atau gunakan alur pandu di atas.'"></div>
                        </div>
                    </div>
                </footer>
            </div>
        </div>
    </template>


</div>


</x-layouts.landing>
