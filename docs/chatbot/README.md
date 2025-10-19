# Chatbot Asisten Data Pertanian

Dokumentasi fitur chatbot untuk modul pertanian (Benih & Pupuk, Lahan, Iklim & OPT DPI) yang terintegrasi ke halaman laporan berbasis komponen `pertanian-report-page.blade.php`.

## 📚 Daftar Isi

- [Ringkasan Fitur](#-ringkasan-fitur)
- [Arsitektur](#-arsitektur)
- [Alur Percakapan](#-alur-percakapan)
- [Struktur Data Bubble](#-struktur-data-bubble-ringkas)
- [Konfigurasi & Perilaku](#-konfigurasi--perilaku)
- [Pengembangan & Build](#-pengembangan--build)
- [Pengujian Manual](#-pengujian-manual-checklist)
- [Keterbatasan](#-keterbatasan-singkat)
- [Roadmap](#-roadmap-ringkas)
- [Troubleshooting](#-troubleshooting)

## ✨ Ringkasan Fitur

- Alur pandu (guided conversation) lintas modul: Modul → Topik → Variabel → Klasifikasi → Waktu → Wilayah → Pratinjau
- Quick Start intents: pratinjau instan (contoh: Pupuk Urea/NPK terbaru, Lahan terbaru, Curah Hujan terbaru)
- Data Dictionary bubble: deskripsi singkat variabel, satuan, dan info terkait setelah variabel dipilih
- Pilihan pratinjau: Ringkasan atau Tabel; dari Ringkasan dapat beralih ke Tabel
- Navigasi balik: "Kembali satu langkah" pada bubble pilihan/checklist
- Lanjut bantuan: "Ingin dibantu lagi?" setelah pratinjau untuk memulai alur baru atau beralih ke teks bebas
- Simpan ke Panel: hasil pratinjau disimpan ke panel untuk analisis lengkap (tabel/grafik) dan ekspor
- Konfirmasi reset: modal konfirmasi saat "Mulai Ulang" untuk mencegah penghapusan histori yang tidak disengaja

## 🧩 Arsitektur

- UI
  - Blade: `resources/views/components/pertanian-report-page.blade.php`
  - Alpine: `resources/js/components/pertanianReportForm.js`
- Layanan
  - Report/RAG: `app/Services/ReportService.php`
- Endpoints
  - Metadata modul: `/api/{module}/topiks`, `/api/{module}/variabels/{topikId}`, `/api/{module}/klasifikasis`, `/api/{module}/years`, `/api/{module}/bulans`
  - Wilayah: `/pertanian/wilayahs`
  - Filter/pratinjau: `/pertanian/{module}/filter`
  - Chatbot reset: `/api/chatbot/reset`

## 🔁 Alur Percakapan

1) Buka chatbot → tampil "Pilih Modul" dan "Mulai Cepat"
2) Pilih modul/topik/variabel → tampil Data Dictionary (nama, satuan, deskripsi)
3) Pilih klasifikasi → pilih tahun (opsi cepat: tahun terbaru) → pilih bulan (kecuali Lahan)
4) Pilih wilayah (tingkat nasional → provinsi, atau tingkat provinsi → kabupaten/kota)
5) Pilih tampilan hasil: Ringkasan atau Tabel
6) Simpan ke Panel bila perlu → lanjut bantuan atau ketik pertanyaan bebas

Quick Start melewati langkah 2–4 dengan default: tahun terbaru, semua bulan (non-Lahan), top-5 provinsi.

## 📦 Struktur Data Bubble (ringkas)

- text: `{ sender: 'bot'|'user', type?: 'text', text: string }`
- options: `{ sender:'bot', type:'options', title:string, options:[{ value:any, label:string }] }`
- checklist: `{ sender:'bot', type:'checklist', title:string, options:[{ value:any, label:string }], selected: any[] }`
- table: `{ sender:'bot', type:'table', title:string, meta:{module,topik,variabel,klasifikasi}, results:{ headers:HeaderRow[], rows:Row[] }, payload:{ selections, config, moduleType } }`
- summary: `{ sender:'bot', type:'summary', title:string, meta:{...}, summaryLines:string[], payload:{...} }`

`headers` adalah array of header-row; baris terakhir berisi nama kolom (kecuali "Wilayah"). `rows` berisi `{ wilayah, values:number[], wilayah_sorter? }`.

## ⚙️ Konfigurasi & Perilaku

- Quick Start Templates (di JS): default untuk Urea, NPK, Lahan, Curah Hujan; memilih topik/variabel dengan pencocokan nama sederhana
- Ringkasan (summary): menampilkan beberapa baris ringkas (3 wilayah, hingga 6 metrik) + tombol "Tampilkan Tabel"
- Pratinjau Tabel: membatasi 8 baris di chat untuk performa; tampilan penuh di panel hasil
- Reset Konfirmasi: modal in-panel mencegah reset tak sengaja

## 🧪 Pengembangan & Build

- Edit UI: `resources/views/components/pertanian-report-page.blade.php`
- Edit logika: `resources/js/components/pertanianReportForm.js`
- Build aset: `npm run build`

## ✅ Pengujian Manual (checklist)

- [ ] Quick Start menghasilkan preview tanpa error (tiap template)
- [ ] Wizard lengkap dari Modul hingga Pratinjau bekerja untuk 3 modul
- [ ] Data Dictionary muncul setelah pilih variabel
- [ ] Ringkasan → Tampilkan Tabel bekerja
- [ ] Simpan ke Panel mengisi panel hasil dan dapat dibuka
- [ ] "Ingin dibantu lagi?" menawarkan alur pandu atau teks bebas
- [ ] "Kembali satu langkah" bekerja di options & checklist
- [ ] Reset menampilkan modal konfirmasi dan benar-benar menghapus riwayat saat dikonfirmasi

## 🚧 Keterbatasan (Singkat)

- NLQ (free-text) masih heuristik; sinonim dan disambiguasi terbatas
- Ringkasan bersifat deskriptif awal; analitik (min/max/avg/YoY) dapat ditambah
- Quick Start memakai subset (top-5 provinsi) untuk kecepatan; perlu opsi ekspansi

## 🗺️ Roadmap Ringkas

- Ringkasan statistik (min/max/avg/YoY, Top/Bottom N) di chat
- Klarifikasi otomatis saat entitas ambigu (wilayah, variabel)
- Mini-chart dalam bubble (sparkline/bar kecil) + tombol "Lihat Grafik"
- OpenAPI minimal untuk endpoint filter & metadata
- Modularisasi state machine & komponen bubble untuk testability

## 🆘 Troubleshooting

- Preview kosong atau lambat: periksa koneksi endpoint `/pertanian/{module}/filter` dan log browser; coba subset wilayah/klasifikasi lebih kecil
- Ringkasan tidak muncul: pastikan data `headers` dan `rows` ada; cek konsol browser untuk error JS
- Quick Start tidak menemukan variabel: periksa keyword `variabelMatches` di template; sesuaikan dengan nama variabel sebenarnya

---

Dokumen ini melengkapi bagian Chatbot di `README.md` utama dan ditujukan untuk developer/kontributor yang ingin memahami arsitektur dan alur kerja fitur chatbot secara mendalam.
