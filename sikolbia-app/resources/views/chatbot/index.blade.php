<x-layouts.landing>
    <style>[x-cloak]{display:none!important}</style>

    <section class="py-12 bg-white">
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
                            <span class="ml-1 text-blue-600 font-medium">Chatbot</span>
                        </div>
                    </li>
                </ol>
            </nav>

            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-3xl md:text-4xl font-bold text-neutral-900 mb-4">Asisten Data Pertanian Non-Komoditas</h1>
                <p class="text-xl text-neutral-600">Tanyakan data pertanian non-komoditas (Lahan, Benih & Pupuk, Iklim & OPT DPI) dan biarkan asisten memandu pilihan dimensi.</p>
            </div>

            <!-- Chat Container -->
          <div x-data="pertanianReportForm({ moduleType: 'benih-pupuk', initialData: { topiks: [], variabels: [], klasifikasis: [], tahuns: [], bulans: [], wilayahs: [] } })"
              x-init="init(); chatOpen = true"
              class="w-full">

                <div class="bg-white border rounded-xl shadow-sm overflow-hidden">
                    <header class="p-4 border-b flex justify-between items-center">
                        <h3 class="font-bold text-lg text-neutral-800">Chatbot — Asisten Data Pertanian Non-Komoditas</h3>
                        <div class="flex items-center gap-2">
                            <!-- Help icon button (info) -->
                            <button type="button" @click="showHelp = true" class="inline-flex items-center justify-center h-9 w-9 rounded-md border bg-white hover:bg-neutral-50 text-neutral-600 hover:text-neutral-900" title="Bantuan & Contoh Prompt" aria-label="Bantuan & Contoh Prompt">
                                <!-- Information circle icon -->
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="h-5 w-5" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M12 2a10 10 0 100 20 10 10 0 000-20zm.75 5.75a.75.75 0 11-1.5 0 .75.75 0 011.5 0zM10.75 10.25a.75.75 0 01.75-.75h.75a.75.75 0 01.75.75v5h1a.75.75 0 010 1.5h-3.5a.75.75 0 010-1.5h.75v-4.5z" clip-rule="evenodd"/>
                                </svg>
                            </button>
                            <!-- Reset icon button (restart/refresh) -->
                            <button type="button" @click="openResetConfirm()" class="inline-flex items-center justify-center h-9 w-9 rounded-md border bg-white hover:bg-neutral-50 text-neutral-600 hover:text-neutral-900" title="Mulai Ulang" aria-label="Mulai Ulang">
                                <!-- Refresh/Restart icon using strokes -->
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <!-- clockwise corner -->
                                    <path d="M20 7v5h-5"/>
                                    <path d="M19 12a7 7 0 10-12.5 4.5"/>
                                    <!-- counter corner -->
                                    <path d="M4 17v-5h5"/>
                                    <path d="M5 12a7 7 0 1012.5-4.5"/>
                                </svg>
                            </button>
                        </div>
                    </header>

                    <main class="flex-1 p-4 overflow-y-auto space-y-4" style="max-height: 60vh" x-ref="chatScroll">
                        <template x-for="(chat, index) in conversation" :key="index">
                            <div class="flex" :class="chat.sender === 'user' ? 'justify-end' : 'justify-start'">
                                <!-- Text bubble -->
                                <template x-if="!chat.type || chat.type === 'text'">
                                    <p class="max-w-[80%] inline-block p-3 rounded-lg text-sm"
                                       :class="chat.sender === 'user' ? 'bg-blue-600 text-white' : 'bg-neutral-200 text-neutral-800'"
                                       x-html="chat.text"></p>
                                </template>

                                <!-- Options bubble -->
                                <template x-if="chat.type === 'options'">
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

                                <!-- Checklist bubble -->
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
                                    </div>
                                </template>
                            </div>
                        </template>
                        <div x-show="isLoading" class="flex justify-start">
                            <p class="max-w-[80%] inline-block p-3 rounded-lg text-sm bg-neutral-200 text-neutral-800">
                                <span class="animate-pulse">...</span>
                            </p>
                        </div>
                    </main>

                    <!-- Reset Modal -->
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

                    <footer class="p-4 border-t">
                        <form @submit.prevent="sendMessage" class="flex gap-2">
                            <input type="text" x-model="userMessage" x-ref="userInput" :disabled="isLoading" class="w-full border rounded-md p-2 text-sm" placeholder="Ketik pertanyaan Anda...">
                            <button type="submit" :disabled="isLoading" class="bg-blue-600 text-white rounded-md px-4 disabled:bg-blue-300">Kirim</button>
                        </form>
                        <p class="text-xs text-neutral-500 mt-2">Tip: Anda bisa mulai dengan "Tampilkan pupuk urea 2024 di Jawa Tengah".</p>
                    </footer>
                </div>

            <!-- Help Modal (teleported), kept inside the same Alpine scope -->
            <template x-teleport="body">
                <div x-show="showHelp" x-cloak @keydown.escape.window="showHelp=false" class="fixed inset-0 z-[1200]">
                    <div class="absolute inset-0 bg-black/40" @click="showHelp=false"></div>
                    <div class="absolute inset-0 flex items-center justify-center p-4">
                        <div class="w-full max-w-3xl bg-white rounded-lg shadow-xl border overflow-hidden">
                            <!-- Header -->
                            <div class="p-4 border-b flex items-center justify-between">
                                <h3 class="font-semibold text-lg text-neutral-800">Bantuan Chatbot</h3>
                                <button class="text-neutral-500 hover:text-neutral-800" @click="showHelp=false" aria-label="Tutup">&times;</button>
                            </div>

                            <!-- Tabs (use parent helpTab so it persists) -->
                            <div>
                                <div class="px-4 pt-3">
                                    <nav class="flex flex-wrap gap-2" role="tablist">
                                        <button type="button" @click="helpTab='examples'" :aria-selected="helpTab==='examples'" class="px-3 py-1.5 rounded-md text-sm border" :class="helpTab==='examples' ? 'bg-blue-600 text-white border-blue-600' : 'bg-white text-neutral-700 hover:bg-neutral-50'">Contoh</button>
                                        <button type="button" @click="helpTab='concepts'" :aria-selected="helpTab==='concepts'" class="px-3 py-1.5 rounded-md text-sm border" :class="helpTab==='concepts' ? 'bg-blue-600 text-white border-blue-600' : 'bg-white text-neutral-700 hover:bg-neutral-50'">Panduan Konsep</button>
                                        <button type="button" @click="helpTab='data'" :aria-selected="helpTab==='data'" class="px-3 py-1.5 rounded-md text-sm border" :class="helpTab==='data' ? 'bg-blue-600 text-white border-blue-600' : 'bg-white text-neutral-700 hover:bg-neutral-50'">Data & Atribut</button>
                                        <button type="button" @click="helpTab='visual'" :aria-selected="helpTab==='visual'" class="px-3 py-1.5 rounded-md text-sm border" :class="helpTab==='visual' ? 'bg-blue-600 text-white border-blue-600' : 'bg-white text-neutral-700 hover:bg-neutral-50'">Visual</button>
                                        <button type="button" @click="helpTab='tips'" :aria-selected="helpTab==='tips'" class="px-3 py-1.5 rounded-md text-sm border" :class="helpTab==='tips' ? 'bg-blue-600 text-white border-blue-600' : 'bg-white text-neutral-700 hover:bg-neutral-50'">Tips Lanjutan</button>
                                    </nav>
                                </div>

                                <!-- Tab Panels -->
                                <div class="p-4 max-h-[70vh] overflow-y-auto">
                                    <!-- Examples -->
                                    <div x-show="helpTab==='examples'" x-transition>
                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                            <button type="button" class="text-left p-3 rounded-md border hover:bg-blue-50" @click="userMessage='Tampilkan pupuk urea 2024 di Jawa Tengah'; showHelp=false; $nextTick(()=>{$refs.userInput?.focus()})">Tampilkan pupuk urea 2024 di Jawa Tengah</button>
                                            <button type="button" class="text-left p-3 rounded-md border hover:bg-blue-50" @click="userMessage='Bandingkan curah hujan 2023–2024 untuk Jawa Barat dan Banten (Jan–Mar)'; showHelp=false; $nextTick(()=>{$refs.userInput?.focus()})">Bandingkan curah hujan 2023–2024 (Jan–Mar) Jawa Barat vs Banten</button>
                                            <button type="button" class="text-left p-3 rounded-md border hover:bg-blue-50" @click="userMessage='Luas lahan 2021–2023 nasional (ringkas)'; showHelp=false; $nextTick(()=>{$refs.userInput?.focus()})">Luas lahan 2021–2023 nasional (ringkas)</button>
                                            <button type="button" class="text-left p-3 rounded-md border hover:bg-blue-50" @click="userMessage='Data NPK 2022–2024 untuk Jawa Timur (semua bulan)'; showHelp=false; $nextTick(()=>{$refs.userInput?.focus()})">Data NPK 2022–2024 Jawa Timur (semua bulan)</button>
                                        </div>
                                        <div class="mt-5">
                                            <h4 class="text-sm font-semibold text-neutral-800 mb-2">Tambahkan rentang/bulan cepat</h4>
                                            <div class="flex flex-wrap gap-2 mb-2">
                                                <button type="button" class="px-3 py-1.5 rounded-full border bg-white hover:bg-neutral-50 text-sm" @click="userMessage=(userMessage?userMessage+' ':'')+'semua bulan'; showHelp=false; $nextTick(()=>{$refs.userInput?.focus()})">Semua bulan</button>
                                                <button type="button" class="px-3 py-1.5 rounded-full border bg-white hover:bg-neutral-50 text-sm" @click="userMessage=(userMessage?userMessage+' ':'')+'Jan–Mar'; showHelp=false; $nextTick(()=>{$refs.userInput?.focus()})">Jan–Mar</button>
                                                <button type="button" class="px-3 py-1.5 rounded-full border bg-white hover:bg-neutral-50 text-sm" @click="userMessage=(userMessage?userMessage+' ':'')+'Apr–Jun'; showHelp=false; $nextTick(()=>{$refs.userInput?.focus()})">Apr–Jun</button>
                                                <button type="button" class="px-3 py-1.5 rounded-full border bg-white hover:bg-neutral-50 text-sm" @click="userMessage=(userMessage?userMessage+' ':'')+'Jul–Sep'; showHelp=false; $nextTick(()=>{$refs.userInput?.focus()})">Jul–Sep</button>
                                                <button type="button" class="px-3 py-1.5 rounded-full border bg-white hover:bg-neutral-50 text-sm" @click="userMessage=(userMessage?userMessage+' ':'')+'Okt–Des'; showHelp=false; $nextTick(()=>{$refs.userInput?.focus()})">Okt–Des</button>
                                            </div>
                                            <div class="grid grid-cols-3 sm:grid-cols-6 gap-2">
                                                <template x-for="m in ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des']" :key="m">
                                                    <button type="button" class="px-2 py-1.5 rounded-md border bg-white hover:bg-neutral-50 text-sm" @click="userMessage=(userMessage?userMessage+' ':'')+m; showHelp=false; $nextTick(()=>{$refs.userInput?.focus()})" x-text="m"></button>
                                                </template>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Concepts -->
                                    <div x-show="helpTab==='concepts'" x-transition class="space-y-3 text-sm text-neutral-700">
                                        <p>Chatbot memandu Anda melalui beberapa langkah agar hasil data sesuai kebutuhan. Alurnya:</p>
                                        <ol class="list-decimal pl-5 space-y-1">
                                            <li><span class="font-medium">Modul</span>: pilih domain data — Lahan, Benih & Pupuk, atau Iklim & OPT DPI.</li>
                                            <li><span class="font-medium">Topik</span>: kelompok besar di dalam modul (mis. Pupuk, Curah Hujan, Luas Lahan).</li>
                                            <li><span class="font-medium">Variabel</span>: item data spesifik di sebuah topik (mis. Pupuk Urea, NPK, Curah Hujan Bulanan). Satuan tampil di nama/tooltip.</li>
                                            <li><span class="font-medium">Klasifikasi</span>: pecahan/jenis turunan dari variabel (mis. jenis pupuk, kategori lahan). Bisa pilih lebih dari satu.</li>
                                            <li><span class="font-medium">Waktu</span>: tahun dan, bila relevan, bulan. Anda bisa memilih "semua bulan" atau rentang Jan–Mar, dst.</li>
                                            <li><span class="font-medium">Wilayah</span>: level nasional, provinsi, atau kabupaten/kota. Chatbot menawarkan daftar sesuai pilihan.</li>
                                            <li><span class="font-medium">Pratinjau</span>: lihat tabel atau ringkasan. Dari sini, Anda dapat menyesuaikan lagi.</li>
                                        </ol>
                                        <p>Anda juga bisa langsung mengetik pertanyaan bebas. Bila informasi belum lengkap, chatbot akan menanyakan sisa dimensinya.</p>
                                    </div>

                                    <!-- Data & Attributes -->
                                    <div x-show="helpTab==='data'" x-transition class="space-y-4 text-sm text-neutral-700">
                                        <div>
                                            <h4 class="font-semibold text-neutral-800 mb-1">Struktur Data</h4>
                                            <ul class="list-disc pl-5 space-y-1">
                                                <li><span class="font-medium">Topik</span>: pengelompokan utama. Contoh: Pupuk, Lahan, Iklim.</li>
                                                <li><span class="font-medium">Variabel</span>: entitas terukur. Memiliki atribut <em>nama</em>, opsional <em>satuan</em>, dan <em>deskripsi</em>.</li>
                                                <li><span class="font-medium">Klasifikasi</span>: label turunan (mis. jenis, kategori, kelompok) yang menyaring data variabel.</li>
                                                <li><span class="font-medium">Waktu</span>: tahun wajib untuk semua modul; bulan relevan untuk sebagian (mis. iklim, pupuk).</li>
                                                <li><span class="font-medium">Wilayah</span>: Nasional → Provinsi → Kabupaten/Kota. Sistem otomatis menampilkan pilihan yang valid.</li>
                                            </ul>
                                        </div>
                                        <div>
                                            <h4 class="font-semibold text-neutral-800 mb-1">Istilah & Catatan</h4>
                                            <ul class="list-disc pl-5 space-y-1">
                                                <li><span class="font-medium">Satuan</span>: ditampilkan pada variabel bila tersedia (mis. ton, ha, mm).</li>
                                                <li><span class="font-medium">Tahun terbaru</span>: pilihan cepat untuk memakai tahun paling akhir yang tersedia.</li>
                                                <li><span class="font-medium">Semua bulan</span>: memilih seluruh bulan pada tahun yang dipilih.</li>
                                                <li><span class="font-medium">Ringkasan</span>: merangkum beberapa baris menjadi poin-poin naratif.</li>
                                            </ul>
                                        </div>
                                        <div>
                                            <h4 class="font-semibold text-neutral-800 mb-1">Aksesibilitas</h4>
                                            <ul class="list-disc pl-5 space-y-1">
                                                <li>Gunakan ikon <span class="font-medium">i</span> untuk bantuan ini, dan ikon <span class="font-medium">restart</span> untuk mengulang percakapan.</li>
                                                <li>Tekan Esc untuk menutup modal.</li>
                                            </ul>
                                        </div>
                                        <div>
                                            <h4 class="font-semibold text-neutral-800 mb-1">Contoh Data per Modul</h4>
                                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                                <div class="p-3 rounded-md border bg-neutral-50">
                                                    <div class="font-medium mb-1">Benih & Pupuk</div>
                                                    <ul class="list-disc pl-5 space-y-1">
                                                        <li>Topik: Pupuk → Variabel: Urea, NPK</li>
                                                        <li>Klasifikasi: jenis, kemasan, produsen (bila ada)</li>
                                                        <li>Waktu: Tahun + Bulan</li>
                                                        <li>Wilayah: Nasional/Provinsi/Kabupaten</li>
                                                    </ul>
                                                </div>
                                                <div class="p-3 rounded-md border bg-neutral-50">
                                                    <div class="font-medium mb-1">Lahan</div>
                                                    <ul class="list-disc pl-5 space-y-1">
                                                        <li>Topik: Lahan → Variabel: Luas Lahan</li>
                                                        <li>Klasifikasi: penggunaan/kategori lahan</li>
                                                        <li>Waktu: Tahun (tanpa bulan)</li>
                                                        <li>Wilayah: Nasional/Provinsi/Kabupaten</li>
                                                    </ul>
                                                </div>
                                                <div class="p-3 rounded-md border bg-neutral-50">
                                                    <div class="font-medium mb-1">Iklim & OPT DPI</div>
                                                    <ul class="list-disc pl-5 space-y-1">
                                                        <li>Topik: Curah Hujan, Suhu, dll.</li>
                                                        <li>Klasifikasi: parameter iklim/OPT</li>
                                                        <li>Waktu: Tahun + Bulan</li>
                                                        <li>Wilayah: Nasional/Provinsi/Kabupaten</li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Visual mockups -->
                                    <div x-show="helpTab==='visual'" x-transition class="space-y-4 text-sm text-neutral-700">
                                        <p>Gambaran singkat tampilan hasil dan langkah pemilihan.</p>
                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                            <!-- Card: Tabel -->
                                            <div class="p-3 rounded-md border">
                                                <div class="font-medium mb-2">Pratinjau Tabel</div>
                                                <div class="h-28 rounded-md bg-neutral-50 border flex items-center justify-center">
                                                    <svg width="120" height="64" viewBox="0 0 120 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <rect x="1" y="1" width="118" height="62" rx="6" fill="#fff" stroke="#D4D4D8"/>
                                                        <rect x="8" y="12" width="104" height="12" fill="#E5E7EB"/>
                                                        <rect x="8" y="28" width="104" height="8" fill="#F3F4F6"/>
                                                        <rect x="8" y="40" width="104" height="8" fill="#F3F4F6"/>
                                                    </svg>
                                                </div>
                                                <p class="mt-2 text-xs text-neutral-500">Tabel menunjukkan wilayah di kolom pertama, diikuti kolom nilai per periode/klasifikasi.</p>
                                            </div>
                                            <!-- Card: Ringkasan -->
                                            <div class="p-3 rounded-md border">
                                                <div class="font-medium mb-2">Pratinjau Ringkasan</div>
                                                <div class="h-28 rounded-md bg-neutral-50 border flex items-center justify-center">
                                                    <svg width="120" height="64" viewBox="0 0 120 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <rect x="1" y="1" width="118" height="62" rx="6" fill="#fff" stroke="#D4D4D8"/>
                                                        <circle cx="20" cy="24" r="6" fill="#E5E7EB"/>
                                                        <rect x="32" y="20" width="72" height="8" fill="#F3F4F6"/>
                                                        <rect x="12" y="36" width="92" height="6" fill="#F3F4F6"/>
                                                    </svg>
                                                </div>
                                                <p class="mt-2 text-xs text-neutral-500">Ringkasan merangkum poin utama (mis. nilai tertinggi/rendah, tren) dalam kalimat pendek.</p>
                                            </div>
                                            <!-- Card: Pilih Bulan -->
                                            <div class="p-3 rounded-md border">
                                                <div class="font-medium mb-2">Pemilihan Bulan</div>
                                                <div class="h-28 rounded-md bg-neutral-50 border flex items-center justify-center">
                                                    <svg width="120" height="64" viewBox="0 0 120 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <rect x="1" y="1" width="118" height="62" rx="6" fill="#fff" stroke="#D4D4D8"/>
                                                        <rect x="8" y="14" width="26" height="12" rx="6" fill="#E5E7EB"/>
                                                        <rect x="38" y="14" width="26" height="12" rx="6" fill="#F3F4F6"/>
                                                        <rect x="68" y="14" width="26" height="12" rx="6" fill="#F3F4F6"/>
                                                        <rect x="8" y="34" width="26" height="12" rx="6" fill="#F3F4F6"/>
                                                        <rect x="38" y="34" width="26" height="12" rx="6" fill="#F3F4F6"/>
                                                        <rect x="68" y="34" width="26" height="12" rx="6" fill="#F3F4F6"/>
                                                    </svg>
                                                </div>
                                                <p class="mt-2 text-xs text-neutral-500">Anda dapat memilih “Semua bulan”, rentang (Jan–Mar), atau bulan spesifik.</p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Advanced Tips -->
                                    <div x-show="helpTab==='tips'" x-transition class="space-y-3 text-sm text-neutral-700">
                                        <ul class="list-disc pl-5 space-y-1">
                                            <li><span class="font-medium">Perbandingan</span>: “Bandingkan curah hujan 2023–2024 (Jan–Mar) Jawa Barat vs Banten”.</li>
                                            <li><span class="font-medium">Filter wilayah cepat</span>: sebutkan langsung provinsi/kabupaten dalam pertanyaan.</li>
                                            <li><span class="font-medium">Ringkas</span>: tambahkan kata “ringkas” untuk merangkum hasil.</li>
                                            <li><span class="font-medium">Rentang</span>: gunakan 2022–2024 atau Jan–Mar. Sistem mengerti tanda en-dash (–) atau strip (-).</li>
                                            <li><span class="font-medium">Lanjutkan dari hasil</span>: setelah pratinjau, minta “tampilkan kabupaten teratas” atau “ubah ke tabel”.</li>
                                        </ul>
                                        <p class="text-xs text-neutral-500">Catatan: Ketersediaan bulan/klasifikasi dapat berbeda tiap modul/variabel.</p>
                                    </div>
                                </div>

                                <div class="p-4 border-t flex justify-end">
                                    <button class="px-3 py-1.5 rounded-md border hover:bg-neutral-50 text-sm" @click="showHelp=false">Tutup</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </template>

            </div>
        </div>
    </section>
</x-layouts.landing>
