# LLM‑Centric Chatbot Refactor Plan (Sikolbia) — May 2026

Tujuan dokumen ini adalah menjadi rencana kerja yang **eksekutabel** (bisa dikerjakan bertahap) untuk membuat chatbot RAG Sikolbia lebih **LLM‑centric** (natural, agentic) tanpa mengorbankan **groundedness** terhadap data tabular terstruktur.

> Prinsip utama: **kode menghitung angka, LLM menarasikan**.

## 0) Constraint & Non‑Goals

### Constraint (wajib dijaga)
- Stack utama: Laravel 12 + MySQL (server Kementan).
- Data numerik & terstruktur; jawaban wajib bisa ditrace ke hasil retrieval.
- Kontrak API chatbot tetap stabil:
  - `POST /api/chatbot` → `{ reply, mode: natural|structured|guided, structured?, intent?, compare? }`
  - `POST /api/chatbot/summary` tetap bekerja.
- Integrasi lain jangan rusak (NBM ML API, pertanian reports, export flows).

### Non‑Goals (untuk refactor ini)
- Tidak memigrasi data utama ke PostgreSQL.
- Tidak membuat UI/halaman baru atau UX besar.
- Tidak mengubah skema tabel data pertanian.

## 1) Baseline (kondisi saat ini)

### Yang sudah ada (bagus)
- Backend sudah punya struktur `ChatbotController` + services (`ChatOrchestrationService`, `ChatReportService`, `StructuredSearchService`, `RAGSummarizer`).
- Ada feature flag `ORCHESTRATOR_ENABLED` dan channel log `orchestrator`.

### Masalah utama yang membuat terasa “hardcoded”
- Slot‑filling & disambiguation banyak di JS (`structuredBridge.js`, `compare.js`) → chatbot terasa rule‑based.
- “Natural response” sering berupa template/deterministic summary (JS maupun PHP), bukan hasil LLM yang menulis narasi.

## 2) Target Architecture (end‑state)

### LLM‑centric (agentic) tapi grounded
- Backend menjadi **orchestrator/agent**.
- LLM hanya boleh mengakses data melalui **tools deterministik**:
  1) `search_dictionary` (topik/variabel/klasifikasi)
  2) `search_wilayah`
  3) `run_report_filter` (pakai pipeline report existing)
  4) `compute_stats` (delta/trend/ranking dihitung PHP)
  5) `final_answer` (LLM menulis jawaban natural dari hasil tool)

### Token/Cost Controller (wajib untuk saran dosen)
- Semua panggilan LLM lewat satu layer → metrik token/cost konsisten.
- Ada budget harian (cost dan/atau token) dan fallback jika limit tercapai.

## 3) Prioritas Eksekusi (milestone)

### P0 — Bugfix yang mempengaruhi kualitas konteks (1 jam)
**Goal**: memastikan konteks RAG tidak rusak.
- Fix string concat bug di `ChatReportService::retrieveFactualData` (`+=` → `.=`).
- Acceptance:
  - Fungsi mengembalikan konteks berisi bagian `Contoh Data:` ketika ada sample rows.

### P1 — Token/Cost Controller + Observability (0.5–1 hari)
**Goal**: setiap call LLM tercatat (provider/model/token/cost/latency) + ada budget guard.

Deliverables:
- `config/llm.php`:
  - `default_provider`, timeout, temperature default
  - pricing per model (input/output)
  - budget harian (mis. `LLM_DAILY_BUDGET_USD`)
- Layer service:
  - `LLMClientInterface` + `LLMResponse`
  - `LLMService` (ukur latency, token usage, cost estimate)
  - `LLMBudgetGuard` (enforce limit + fallback)
- Penyimpanan trials:
  - Tabel `llm_trials` (migration) + model `LlmTrial`
  - Logging ke channel `orchestrator`

Acceptance:
- Setiap request normalizer/summarizer menulis 1 baris log `orchestrator` berisi `{provider, model, latency_ms, prompt_tokens, completion_tokens, cost_total}`.
- Jika budget harian habis → normalizer/summarizer tidak call provider, tapi fallback deterministic/offline.

### P2 — Provider Abstraction (OpenAI vs Gemini) (0.5–1 hari)
**Goal**: bisa switch provider dari `.env` untuk eksperimen komparasi.

Deliverables:
- Implementasi:
  - `OpenAIClient` (pakai OpenAI Laravel facade yang sudah ada)
  - `GeminiClient` (HTTP REST; dev-only; error-safe jika API key kosong)
- Konfigurasi `.env`:
  - `LLM_DEFAULT_PROVIDER=openai|gemini`
  - `GEMINI_API_KEY`, `GEMINI_MODEL`, `GEMINI_BASE_URL`

Acceptance:
- Ganti provider cukup ubah `.env`, tanpa mengubah kode chatbot.

### P3 — Wire Chatbot ke LLM layer (0.5 hari)
**Goal**: semua call LLM lewat `LLMService` dan tercatat.

Scope minimal:
- Refactor:
  - `ChatNormalizationService` → pakai `LLMService`
  - `RAGSummarizer` → pakai `LLMService`
- Tambah “purpose tags” untuk logging:
  - `purpose=normalizer|summarizer|answer_writer`

Acceptance:
- Tidak ada pemanggilan `OpenAI::chat()->create()` langsung di code chatbot (kecuali di client).

### P4 — Make answers more natural (LLM rephrase) (1 hari)
**Goal**: output ringkasan/tabel tetap grounded, tapi narasi lebih natural.

Approach:
- Setelah `buildRichSummary()` menghasilkan `lines + insights`, panggil LLM untuk menulis jawaban bahasa Indonesia yang natural:
  - Input: pertanyaan user + ringkasan statistik + metadata (satuan/periode/wilayah)
  - Output: paragraf + 3 bullet insight
- Guardrail:
  - Jika LLM menyebut angka baru yang tidak ada di context → fallback ke ringkasan deterministic.

Acceptance:
- “feel” jawaban tidak template, namun angka tetap sesuai context.

### P5 — Pindahkan slot-filling dari JS ke backend (2–3 hari)
**Goal**: kurangi heuristik JS (structuredBridge/compare) → backend jadi “otak”.

Approach:
- Tambah endpoint internal (tetap gunakan `POST /api/chatbot`):
  - `mode=structured` mengembalikan prompt disambiguation berbasis ID (bukan token scoring JS)
- JS menjadi renderer:
  - menerima `prompt + candidates` dari backend
  - mengirim balik pilihan user

Acceptance:
- File `structuredBridge.js` hanya berisi renderer + pengiriman pilihan, bukan scoring/token matching.

### P6 — Semantic Search dev-only (Postgres + pgvector) (2–4 hari)
**Goal**: semantic search untuk kamus metadata tanpa mengubah MySQL.

Deliverables:
- Compose dev (tambahan): `postgres` + `pgvector`.
- Tabel vector untuk:
  - wilayah, topik, variabel, klasifikasi (+ synonyms)
- Pipeline dev untuk membangun embedding dan menyimpan ke Postgres.

Acceptance:
- Jika Postgres up: disambiguation kandidat lebih tepat.
- Jika Postgres down: fallback ke `LIKE`/deterministic search.

## 4) Risk & Mitigations
- Biaya membengkak → enforce budget + sampling + truncation.
- Hallucination angka → hitung angka di PHP + validation + fallback.
- Perubahan besar UI → tahan dulu, fokus backend.

## 5) Definition of Done (untuk TA)
- Ada laporan metrik komparasi provider (latency, token/cost, quality) mengikuti `llm-comparison-plan.md`.
- Chatbot terasa natural (narasi LLM) tapi grounded (angka sesuai context).
- Hardcode JS berkurang signifikan (slot filling dipindah ke backend).

## 6) Eksekusi (commands)
- Jalankan tests: `composer run pest`
- Jalankan docker: `docker-compose up --build -d`
- Lihat log: `storage/logs/orchestrator-*.log`
