# Chatbot Asisten Data Pertanian

Dokumentasi chatbot untuk modul pertanian (Benih & Pupuk, Lahan, Iklim & OPT DPI). Sejak refactor, chatbot memakai pola LLM-as-Orchestrator yang menyatukan RAG + Structured Retrieval. Default mode adalah percakapan natural; alur pandu (guided) muncul saat onboarding dan menjadi fallback jika intent tidak jelas.

## 📚 Daftar Isi

- [Ringkasan Fitur](#-ringkasan-fitur)
- [Arsitektur](#-arsitektur)
- [API & Kontrak Respons](#-api--kontrak-respons)
- [Alur Percakapan](#-alur-percakapan)
- [Struktur Data Bubble](#-struktur-data-bubble-ringkas)
- [Konfigurasi & Perilaku](#-konfigurasi--perilaku)
- [Observability & Logging](#-observability--logging)
- [Pengembangan & Build](#-pengembangan--build)
- [Pengujian](#-pengujian)
- [Troubleshooting](#-troubleshooting)

## ✨ Ringkasan Fitur

- Natural-first dengan intent routing: smalltalk, definition, data (structured), unknown
- Guided hanya saat onboarding dan sebagai fallback jika intent unknown
- RAG Summarizer untuk jawaban natural dengan konteks terpilih (OpenAI opsional; ada offline fallback)
- Structured-first handoff saat sinyal kuat (modul + entitas waktu/wilayah) terdeteksi
- Data Dictionary bubble, pratinjau Ringkasan ↔ Tabel, simpan ke Panel, “Kembali satu langkah”, dan konfirmasi “Mulai Ulang” tetap ada

## 🧩 Arsitektur

- UI
  - Blade: `resources/views/components/pertanian-report-page.blade.php`
  - Alpine: `resources/js/components/pertanianReportForm.js`
- Controller
  - `app/Http/Controllers/Api/ChatbotController.php` — router tipis untuk mode natural/structured/guided
- Services
  - `app/Services/ChatOrchestrationService.php` — intent detection & routing
  - `app/Services/RAGSummarizer.php` — ringkas-jawab natural (OpenAI jika tersedia; offline fallback)
  - `app/Services/ReportService.php` — structured retrieval dan metadata (wilayah, tahun, dll.)
  - `app/Services/KnowledgeBaseService.php`, `SmallTalkService.php` — definisi domain & small talk
  - `app/Services/GuidedService.php` — alur pandu backend-driven
- Konfigurasi
  - `config/chatbot.php` — feature flag `orchestrator_enabled`, batas context/summary
  - `config/logging.php` — channel harian `orchestrator`
- Endpoints terkait data
  - Metadata: `/api/{module}/topiks`, `/api/{module}/variabels/{topikId}`, `/api/{module}/klasifikasis`, `/api/{module}/years`, `/api/{module}/bulans`
  - Wilayah: `/pertanian/wilayahs`
  - Filter/pratinjau: `/pertanian/{module}/filter`

## 🔌 API & Kontrak Respons

Endpoint utama:

- POST `/api/chatbot` — kirim pesan chat
- POST `/api/chatbot/reset` — reset state (compat; RAG tidak pakai session)

Request (umum):

```json
{ "message": "string", "mode": "natural|structured|guided" }
```

Guided mode juga menerima:

```json
{ "mode": "guided", "step": "start|module|region|year|confirm", "context": { /* selections */ } }
```

Response (kontrak stabil):

- Natural:
  - `{ reply: string, mode: "natural", intent?: "smalltalk|definition|data|unknown", structured?: object }`
- Structured:
  - `{ reply: string, mode: "structured", structured: { /* structured_result */ } }`
- Guided:
  - `{ reply: string, mode: "guided", options: [ { label, value, type } ] }`

Contoh curl (singkat):

```bash
curl -s http://localhost:8000/api/chatbot \
  -H 'Content-Type: application/json' \
  -d '{"message":"Halo"}'

curl -s http://localhost:8000/api/chatbot \
  -H 'Content-Type: application/json' \
  -d '{"mode":"guided","step":"start"}'
```

Catatan: Field `intent` hanya muncul jika orchestrator diaktifkan. Frontend aman untuk mengabaikannya.

## 🔁 Alur Percakapan

1) Onboarding: tampil alur pandu singkat (sekali) untuk memperkenalkan modul dan opsi
2) Default percakapan: user mengetik bebas (natural). Orchestrator menentukan intent:
   - smalltalk/definition → jawab natural cepat (KB/SmallTalk + RAGSummarizer)
   - data (sinyal kuat modul+entitas) → handoff ke structured dan kembalikan `structured_result`
   - unknown → jawaban natural ringkas + frontend menawarkan guided fallback
3) Guiding fallback: jika pengguna memilih, lanjutkan step-wise (module → region → year → confirm)

## 📦 Struktur Data Bubble (ringkas)

- text: `{ sender: 'bot'|'user', type?: 'text', text: string }`
- options: `{ sender:'bot', type:'options', title:string, options:[{ value:any, label:string }] }`
- checklist: `{ sender:'bot', type:'checklist', title:string, options:[{ value:any, label:string }], selected: any[] }`
- table: `{ sender:'bot', type:'table', title:string, meta:{module,topik,variabel,klasifikasi}, results:{ headers:HeaderRow[], rows:Row[] }, payload:{ selections, config, moduleType } }`
- summary: `{ sender:'bot', type:'summary', title:string, meta:{...}, summaryLines:string[], payload:{...} }`

## ⚙️ Konfigurasi & Perilaku

- Feature flag orchestrator: `config/chatbot.php` → `orchestrator_enabled` (true/false)
- OpenAI opsional: set `OPENAI_API_KEY` untuk aktivasi di `RAGSummarizer` dan `ChatNormalizationService`; tanpa API key, fallback offline aktif
- Batas context/summary: dikendalikan dari `config/chatbot.php` (token guard & truncation)
- Natural default; guided hanya onboarding dan fallback saat `intent=unknown`

## 👀 Observability & Logging

- Channel log: `orchestrator` (daily). Mencatat intent, branch, dan elapsed ms
- P95 sederhana: dihitung dari cache rolling; digunakan untuk melihat latensi kasar per cabang
- Rencana opsional: expose metrics via endpoint ringan (belum diaktifkan)

## 🧪 Pengembangan & Build

- Edit UI: `resources/views/components/pertanian-report-page.blade.php`
- Edit logika: `resources/js/components/pertanianReportForm.js`
- Controller & services: lihat bagian Arsitektur di atas
- Build aset: `npm run build`

## 🧫 Pengujian

- Unit & Feature (Pest):
  - `tests/Unit/OrchestratorIntentTest.php`
  - `tests/Feature/ChatbotSmalltalkDefinitionTest.php`
- CI: `.github/workflows/orchestrator-tests.yml` menjalankan subset tests yang terkait orchestrator
- Menjalankan semua tests: `composer run pest` (lihat README utama)

## 🆘 Troubleshooting

- Tanpa `OPENAI_API_KEY`: summarizer otomatis memakai offline fallback
- Respons natural kosong: cek `logs/orchestrator-*.log` dan batas context di `config/chatbot.php`
- Structured tidak muncul padahal pertanyaan spesifik: pastikan sinyal kuat (modul + tahun/wilayah) terdeteksi; jika tidak, pilih guided fallback

---

Dokumen ini melengkapi bagian Chatbot di `README.md` utama dan ditujukan untuk developer/kontributor yang ingin memahami arsitektur, kontrak API, dan alur kerja chatbot terkini.
