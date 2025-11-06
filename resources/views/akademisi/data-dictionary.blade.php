<x-layouts.akademisi>
    <!-- Page Header -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-neutral-900 dark:text-white">Data Dictionary</h1>
        <p class="text-neutral-600 dark:text-neutral-400 mt-2">Dokumentasi struktur database dan formula Neraca Bahan Makanan</p>
    </div>

    <!-- Formula NBM -->
    <div class="bg-gradient-to-r from-purple-500 to-purple-700 dark:from-purple-600 dark:to-purple-800 rounded-lg shadow-lg p-6 text-white mb-6">
        <h2 class="text-xl font-bold mb-4">Formula Neraca Bahan Makanan (NBM)</h2>
        <div class="space-y-3">
            @foreach($formulas as $formula)
            <div class="bg-white/10 rounded-lg p-4">
                <h3 class="font-semibold text-lg mb-1">{{ $formula['name'] }}</h3>
                <p class="font-mono text-sm bg-black/20 rounded px-3 py-2 mb-2">{{ $formula['formula'] }}</p>
                <p class="text-purple-100 text-sm">{{ $formula['description'] }}</p>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Struktur Tabel Database -->
    @foreach($tables as $tableName => $tableInfo)
    <div class="bg-white dark:bg-neutral-800 rounded-lg shadow mb-6">
        <div class="border-b border-neutral-200 dark:border-neutral-700 p-6">
            <h2 class="text-xl font-semibold text-neutral-900 dark:text-white">
                <span class="font-mono bg-neutral-100 dark:bg-neutral-700 px-3 py-1 rounded">{{ $tableName }}</span>
            </h2>
            <p class="text-neutral-600 dark:text-neutral-400 mt-2">{{ $tableInfo['description'] }}</p>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-neutral-50 dark:bg-neutral-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 dark:text-neutral-300 uppercase tracking-wider">Field Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 dark:text-neutral-300 uppercase tracking-wider">Data Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 dark:text-neutral-300 uppercase tracking-wider">Description</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-200 dark:divide-neutral-700">
                    @foreach($tableInfo['fields'] as $field)
                    <tr class="hover:bg-neutral-50 dark:hover:bg-neutral-700/50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="font-mono text-sm font-medium text-purple-600 dark:text-purple-400">{{ $field['name'] }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300">
                                {{ $field['type'] }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-neutral-700 dark:text-neutral-300">
                            {{ $field['description'] }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endforeach

    <!-- Kode Kelompok Reference -->
    <div class="bg-white dark:bg-neutral-800 rounded-lg shadow p-6 mb-6">
        <h2 class="text-xl font-semibold text-neutral-900 dark:text-white mb-4">Referensi Kode Kelompok Komoditas</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="flex items-center space-x-3">
                <span class="flex-shrink-0 w-12 h-12 bg-green-100 dark:bg-green-900 rounded-lg flex items-center justify-center font-bold text-green-700 dark:text-green-300">01</span>
                <span class="text-neutral-700 dark:text-neutral-300">Padi-padian</span>
            </div>
            <div class="flex items-center space-x-3">
                <span class="flex-shrink-0 w-12 h-12 bg-yellow-100 dark:bg-yellow-900 rounded-lg flex items-center justify-center font-bold text-yellow-700 dark:text-yellow-300">02</span>
                <span class="text-neutral-700 dark:text-neutral-300">Umbi-umbian</span>
            </div>
            <div class="flex items-center space-x-3">
                <span class="flex-shrink-0 w-12 h-12 bg-red-100 dark:bg-red-900 rounded-lg flex items-center justify-center font-bold text-red-700 dark:text-red-300">03</span>
                <span class="text-neutral-700 dark:text-neutral-300">Ikan & Hasil Laut</span>
            </div>
            <div class="flex items-center space-x-3">
                <span class="flex-shrink-0 w-12 h-12 bg-orange-100 dark:bg-orange-900 rounded-lg flex items-center justify-center font-bold text-orange-700 dark:text-orange-300">04</span>
                <span class="text-neutral-700 dark:text-neutral-300">Daging & Unggas</span>
            </div>
            <div class="flex items-center space-x-3">
                <span class="flex-shrink-0 w-12 h-12 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center font-bold text-blue-700 dark:text-blue-300">05</span>
                <span class="text-neutral-700 dark:text-neutral-300">Telur & Susu</span>
            </div>
            <div class="flex items-center space-x-3">
                <span class="flex-shrink-0 w-12 h-12 bg-purple-100 dark:bg-purple-900 rounded-lg flex items-center justify-center font-bold text-purple-700 dark:text-purple-300">06</span>
                <span class="text-neutral-700 dark:text-neutral-300">Sayur-sayuran</span>
            </div>
            <div class="flex items-center space-x-3">
                <span class="flex-shrink-0 w-12 h-12 bg-pink-100 dark:bg-pink-900 rounded-lg flex items-center justify-center font-bold text-pink-700 dark:text-pink-300">07</span>
                <span class="text-neutral-700 dark:text-neutral-300">Buah-buahan</span>
            </div>
            <div class="flex items-center space-x-3">
                <span class="flex-shrink-0 w-12 h-12 bg-indigo-100 dark:bg-indigo-900 rounded-lg flex items-center justify-center font-bold text-indigo-700 dark:text-indigo-300">08</span>
                <span class="text-neutral-700 dark:text-neutral-300">Kacang-kacangan</span>
            </div>
            <div class="flex items-center space-x-3">
                <span class="flex-shrink-0 w-12 h-12 bg-teal-100 dark:bg-teal-900 rounded-lg flex items-center justify-center font-bold text-teal-700 dark:text-teal-300">09</span>
                <span class="text-neutral-700 dark:text-neutral-300">Minyak & Lemak</span>
            </div>
            <div class="flex items-center space-x-3">
                <span class="flex-shrink-0 w-12 h-12 bg-amber-100 dark:bg-amber-900 rounded-lg flex items-center justify-center font-bold text-amber-700 dark:text-amber-300">10</span>
                <span class="text-neutral-700 dark:text-neutral-300">Gula</span>
            </div>
            <div class="flex items-center space-x-3">
                <span class="flex-shrink-0 w-12 h-12 bg-cyan-100 dark:bg-cyan-900 rounded-lg flex items-center justify-center font-bold text-cyan-700 dark:text-cyan-300">11</span>
                <span class="text-neutral-700 dark:text-neutral-300">Lain-lain</span>
            </div>
        </div>
    </div>

    <!-- Catatan Penting -->
    <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-6">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-6 w-6 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                </svg>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-blue-800 dark:text-blue-300">Catatan Penting</h3>
                <div class="mt-2 text-sm text-blue-700 dark:text-blue-400 space-y-1">
                    <p>• Data NBM merupakan data agregat nasional (bukan per provinsi/kabupaten)</p>
                    <p>• Satuan <strong>ton</strong> untuk volume produksi, impor, ekspor</p>
                    <p>• Satuan <strong>kkal/kapita/hari</strong> untuk konsumsi kalori</p>
                    <p>• Satuan <strong>gram/kapita/hari</strong> untuk protein dan lemak</p>
                    <p>• Periode data: 1993 - 2024 (31 tahun)</p>
                    <p>• Total records: 41,316 transaksi NBM</p>
                </div>
            </div>
        </div>
    </div>
</x-layouts.akademisi>
