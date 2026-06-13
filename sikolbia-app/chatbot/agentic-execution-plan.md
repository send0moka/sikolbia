# Rencana Eksekusi — SIKOLBIA Chatbot Agentic Refactor

**Tanggal disusun:** 14 Juni 2026
**Status:** Rencana kerja (belum dieksekusi)
**Lingkup TA:** Rancang bangun chatbot RAG untuk data pertanian non-komoditas Kementan (benih & pupuk, iklim OPT DPI, lahan).

## Keputusan arah (sudah disepakati)

- **Strategi refactor:** Agentic penuh (LLM function/tool-calling). LLM jadi orchestrator; PHP menghitung angka.
- **Semantic search:** Postgres + pgvector sebagai **sidecar dev-only** khusus kamus metadata. **Data utama tetap MySQL**, tidak dimigrasi.
- **Kontrak API:** `POST /api/chatbot` dijaga stabil (`{reply, mode, structured?, candidates?, intent?}`).

## Prinsip utama

> **Kode menghitung angka, LLM menarasikan.** LLM tidak pernah mengarang angka — setiap angka di jawaban harus berasal dari hasil tool deterministik.

---

## Peta arsitektur target

```
ChatbotController (natural mode)
        │
        ▼
AgentOrchestrator ──loop──► LLMService.chatWithTools()  ◄── OpenAI / Gemini
        │  ▲                         │
        │  └──── tool results ───────┘
        ▼
   ToolRegistry
   ├─ search_dictionary   → StructuredSearchService + metadata (Fase E: + semantic)
   ├─ resolve_wilayah     → ReportService::getProvinces/getKabupatenByProvince
   ├─ run_report_filter   → ReportService::generateReportData   (angka asli dari MySQL)
   └─ compute_stats       → PHP murni (delta/tren/ranking)
        │
        ▼
   AnswerValidator (guardrail: tiap angka di jawaban harus ada di hasil tool)
        │
        ▼
   {reply, mode, structured?, candidates?}  → JS render murni
```

## Aset yang dipertahankan (jangan dibongkar)

| Komponen | Lokasi |
|---|---|
| Layer LLM terpusat (latency/token/cost/log/trial) | `app/Services/LLM/LLMService.php` |
| Provider abstraction (OpenAI & Gemini) | `app/Services/LLM/Clients/` |
| Budget guard | `app/Services/LLM/LLMBudgetGuard.php` |
| Trial logging | tabel `llm_trials` + model `LlmTrial` |
| Retrieval deterministik (calon tools) | `app/Services/StructuredSearchService.php`, `app/Services/ReportService.php` |
| Rate-limit anon (HMAC cookie + fallback IP) | `app/Http/Middleware/EnsureChatbotAnonId.php` |

SDK `openai-php/laravel v0.17.1` mendukung tool-calling. `ReportService::generateReportData($moduleType, $validatedData)` = fetcher data inti.

---

## Fase 0 — Persiapan & verifikasi (0.5 hari)

Gerbang sebelum menulis kode agent.

- **T0.1** Verifikasi dok OpenAI terbaru: dukungan `tools` untuk `gpt-5-nano` via Chat Completions, batasan `temperature` (kemungkinan harus `=1`), `max_completion_tokens` vs `max_tokens`. Catat hasil.
- **T0.2** Smoke test 1 tool-call manual (tinker) ke OpenAI → konfirmasi format `tool_calls` SDK v0.17.1.
- **T0.3** Pastikan `OPENAI_API_KEY` & `GEMINI_API_KEY` ada di `.env` lokal.

**Acceptance:** catatan parameter valid untuk `gpt-5-nano`; satu tool call manual berhasil round-trip.

> Catatan: bila `gpt-5-nano` rewel untuk tool-calling, pakai model OpenAI lain untuk loop agent (nano tetap boleh untuk normalizer).

## Fase A — Konsolidasi + perbaikan teknis low-risk (1–2 hari)

- **TA.1** Isi `pricing` di `config/llm.php` untuk model yang dipakai. *Acceptance:* `llm_trials.cost_total_usd` > 0 setelah 1 request.
- **TA.2** Perbaiki `OpenAIClient` sesuai T0.1 (temperature/max tokens). *Acceptance:* `RAGSummarizer` tidak lagi selalu jatuh ke `summarizeOffline` (cek log `orchestrator`).
- **TA.3** Putuskan satu pipeline: `orchestrator_enabled=true` permanen; tandai jalur lama di `ChatbotController::handleNatural` sebagai deprecated (belum dihapus — diganti agent di Fase B).
- **TA.4** Verifikasi `.env.testing` tak membawa kredensial nyata; dokumentasikan baseline test gagal `transaksi_nbms.status_angka` (di luar scope, supaya tak rancu dengan regresi).

**Acceptance fase:** `composer run pest` hijau kecuali baseline NBM yang sudah didokumentasikan.

## Fase B — Agentic core ⭐ (4–6 hari)

**B1. Perluas LLMService untuk tool-calling.**
File: `LLMService.php`, `DTO/LLMChatResult.php`, `Clients/OpenAIClient.php`, `Clients/GeminiClient.php`, `Contracts/LLMClientInterface.php`.
Tambah param `tools` + `tool_choice`; `LLMChatResult` membawa `toolCalls[]` (id, name, arguments) & `finishReason`. OpenAIClient teruskan `tools`, parse `choices.0.message.tool_calls`. GeminiClient → `functionDeclarations` + parse `functionCall`.
*Acceptance:* `chat()` dengan `tools` mengembalikan `toolCalls` terisi; logging & trial tetap tercatat (purpose=`agent`).

**B2. Kontrak Tool + Registry.**
File baru: `app/Services/Agent/Tools/ToolInterface.php` (`name()`, `schema()`, `handle(array $args): array`), `app/Services/Agent/ToolRegistry.php` (kumpulkan tools → array JSON-schema; eksekusi by-name).
*Acceptance:* registry hasilkan array `tools` valid; eksekusi by-name kembalikan hasil deterministik.

**B3. Implementasi 4 tools (bungkus logika existing, jangan tulis ulang retrieval).**
- `SearchDictionaryTool` → `StructuredSearchService::search` + `ReportService::getTopiks/getVariabelsByTopik/getKlasifikasiByVariabels`. Kembalikan kandidat **dengan ID**.
- `ResolveWilayahTool` → `ReportService::getProvinces` / `getKabupatenByProvince` + matching nama existing.
- `RunReportFilterTool` → `ReportService::generateReportData($moduleType, $validatedData)` — **sumber angka asli**. Validasi argumen sebelum eksekusi.
- `ComputeStatsTool` → PHP murni: delta antar-tahun, tren, ranking wilayah, min/maks/rata-rata (pindahkan logika dari `ChatReportService::buildRichSummary` agar reusable).

*Acceptance:* tiap tool punya unit test Pest (input→output deterministik); angka identik dengan halaman report existing.

**B4. AgentOrchestrator (loop).**
File baru: `app/Services/Agent/AgentOrchestrator.php`.
- System prompt: peran (asisten data non-komoditas Kementan), **aturan grounding** ("dilarang menyebut angka di luar hasil tool"; "jika di luar 3 modul, tolak sopan"), daftar modul & dimensi.
- Loop: `messages + tools` → jika ada `tool_calls`, eksekusi via registry, append hasil `role: tool`, ulangi (maks 4–5 iterasi, guard biaya) → jika tak ada tool call/jawaban final, kembalikan narasi.
- Disambiguation: bila kandidat ambigu >1, agent menulis pertanyaan klarifikasi **dan** backend sertakan `candidates` (berbasis ID) untuk dirender JS.

*Acceptance:* "Padi benih sebar di Banyumas 2024" → search_dictionary → run_report_filter → jawaban berisi angka asli; log menunjukkan urutan tool.

**B5. Guardrail + wire ke controller.**
File baru: `app/Services/Agent/AnswerValidator.php` — ekstrak angka dari jawaban LLM, cek tiap angka ada di hasil tool (toleransi pembulatan); gagal → fallback ke ringkasan deterministik `ComputeStatsTool`.
Ubah `ChatbotController::handleNatural` → delegasi ke `AgentOrchestrator`. Pertahankan bentuk response.
*Acceptance:* jawaban dengan angka halusinasi otomatis tertolak (uji prompt adversarial); kontrak API tak berubah.

## Fase C — Pindahkan slot-filling JS → backend ⭐ (2–3 hari)

- **TC.1** Backend kirim `candidates: [{id,label,kind}]` + `prompt` saat butuh klarifikasi (dari B4).
- **TC.2** Pangkas `resources/js/pertanian/chatbot/structuredBridge.js`: hapus `scoreByTokens`, `chooseTopik/Variabel/Klasifikasi/Wilayah` → jadi renderer yang menampilkan kandidat backend & kirim balik `id`. Resolve by-id pindah ke agent.
- **TC.3** Sederhanakan `resources/js/pertanian/chatbot/router.js`: hapus interception regex ("ya tampilkan", deteksi compare) — agent yang memutuskan.

**Acceptance:** `structuredBridge.js` tak lagi memuat token-scoring; disambiguation jalan end-to-end; `npm run build` sukses.

## Fase D — Komparasi OpenAI vs Gemini ⭐ (2–3 hari) — wajib sidang

- **TD.1** Dataset uji 30–50 pertanyaan berlabel (cari-data, banding antar-tahun/wilayah, definisi, di-luar-domain, ambigu) → fixture.
- **TD.2** Command `php artisan chatbot:benchmark`: jalankan tiap pertanyaan ke kedua provider lewat agent; catat ke `llm_trials` (latency/token/cost) + simpan jawaban.
- **TD.3** Penilaian kualitas: groundedness (via AnswerValidator), keberhasilan tool, naturalness (rubrik manual). Ekspor CSV/tabel.
- **TD.4** Naikkan model Gemini di `config/llm.php` ke generasi setara (komparasi adil).

**Acceptance:** satu tabel komparasi (latency, token, cost, akurasi angka, success rate) siap untuk Bab Hasil.

## Fase E — Semantic search (Postgres + pgvector, sidecar dev-only) (3–5 hari)

- **TE.1** Tambah service `postgres` (image `pgvector/pgvector`) di compose dev; koneksi Laravel terpisah `pgvector` (jangan ganggu MySQL default).
- **TE.2** Pipeline embedding metadata: ambil `deskripsi` topik/variabel/klasifikasi + nama wilayah (+ sinonim), embed, simpan ke tabel vector Postgres. Command `php artisan dictionary:embed`.
- **TE.3** Upgrade `SearchDictionaryTool`: coba semantic match dulu (mis. "hujan"→"curah hujan"); **fallback ke LIKE/deterministik** bila Postgres mati. Data utama tetap MySQL.
- **TE.4** Tambah komparasi retrieval (semantic vs LIKE) ke benchmark Fase D.

**Acceptance:** Postgres up → disambiguation sinonim lebih tepat; Postgres down → chatbot tetap jalan.

## Fase F — API contract & permission (1–2 hari)

- **TF.1** Pindah closure debug `routes/api.php` (`/debug/prediksi-preview`) ke controller; pastikan mati di produksi.
- **TF.2** Dokumen kontrak API (Markdown/OpenAPI): endpoint → method → auth/permission → request/response. Soroti pemisahan chatbot publik (rate-limited anon) vs metadata picker (auth `pemerintah`/`akademisi`).
- **TF.3** Selaraskan dok: perbaiki `chatbot/rag-structured-retrieval.md` yang menyebut `GET /api/{module}/topiks` padahal implementasinya di `routes/web.php` di bawah auth.

**Acceptance:** tabel kontrak API akurat; tak ada endpoint debug aktif di produksi.

---

## Jalur kritis & urutan

- **0 → A → B → C → D** = tulang punggung TA (naturalness + groundedness + bukti komparasi).
- **E** memuaskan saran dosen; bisa menyusul setelah B.
- **F** low-risk, paralel kapan saja.

Estimasi jalur inti (A–D): ~10–14 hari kerja; +E ~3–5 hari; +F ~1–2 hari.

## Testing & keamanan refactor (tiap fase)

- Tiap tool & validator punya unit test Pest (deterministik).
- Feature test `POST /api/chatbot` memverifikasi kontrak response tak berubah.
- Tiap fase: `composer run pest` + `npm run build` sebelum lanjut.
- Kerjakan di branch terpisah dari `feature/split-docker-project` (working tree masih banyak perubahan belum di-commit — commit/stash dulu agar mulai dari basis bersih).

## Isu teknis konkret yang sudah teridentifikasi

1. Kompatibilitas `gpt-5-nano` di `OpenAIClient` (temperature/`max_tokens`) — diduga penyebab `RAGSummarizer` selalu fallback offline. → TA.2.
2. `pricing` kosong di `config/llm.php` → cost selalu 0, budget USD mati. → TA.1.
3. `ChatReportService::retrieveFactualData` praktis dead code (orchestrator unknown pakai `buildContextFromStructured` tanpa nilai asli) → digantikan tool `run_report_filter`.
4. Bug konkat `+=`→`.=` lama **sudah diperbaiki**, tak perlu diapa-apakan.
