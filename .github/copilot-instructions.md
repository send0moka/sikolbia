## SIKOLBIA — AI coding agent guide

Laravel 12 + Livewire 3 web app with a separate FastAPI ML service. Five modules: konsumsi (NBM), lahan, iklim OptDPI, benih & pupuk, dan daftar alamat. Keep route groups, Livewire wiring, export flows, permissions, and the ML API contract intact.

### Architecture map (what lives where)
- Backend: `app/` (Controllers, Livewire in `app/Livewire`, Models in `app/Models`, Services in `app/Services`)
- Routes: `routes/` grouped under `admin/...`, `api/...`, `pertanian/...` (+ `routes/nbm_api.php` for ML)
- Frontend: `resources/` built by Vite/Tailwind; unified reports UI in `resources/js/pertanian/*`
- ML training: `ml_models/` (e.g., `production_model.py`, `data_loader.py`, `train_model.py`)
- ML serving: `fastapi/main.py` loads `ml_models/models/nbm_production`
- Docker services: `docker-compose.yml` → app/nginx(8000), mysql(3306), phpmyadmin(8081), redis(6379), fastapi-ml(8082)

### Critical workflows
- Full stack (Docker): compose up, then inside `app`: run migrations fresh + seed and `php artisan key:generate` (see root `README.md` for exact commands)
- Local dev: Laravel serve, Vite `npm run dev`/`npm run build`, ML API via `./start_api.sh` or the `fastapi-ml` container
- One-shot dev stack: `composer run dev` (serves Laravel, queue listener, and Vite together)
- Tests (Pest): `composer run pest` (clears caches, migrates fresh, seeds `RolePermissionSeeder`)

### Project conventions and patterns
- Excel export flow (Livewire + Maatwebsite Excel): component sets session → redirect to download route under `admin/.../export/*`; implement in `app/Exports/*` and preserve the session handoff
- Display labels: reference tables use `deskripsi`; UIs select/order by `deskripsi`
- Komoditi code: `kode_kelompok + kode_komoditi` (e.g., `01` → `0101`); public AJAX relies on this in `routes/web.php` → `ketersediaan/api/komoditi`
- Permissions: `spatie/laravel-permission`; keep exact `permission:` strings in middleware unchanged

### Integration contracts (stable — do not break)
- NBM ML API
  - Routes: wired via `routes/nbm_api.php` and mounted under `admin/konsumsi-pangan/prediksi-nbm` in `routes/web.php`
  - FastAPI endpoints: `/health`, `/predict`, `/predict/multi-step`, `/predict/batch`, `/model/stats`
  - Predict payload: exactly 6 sequential points of `{ tahun, bulan, kelompok, komoditi, kalori_hari }`
  - Predict response: `{ success, prediction, confidence_interval, model_info, input_summary }`
  - Ports: container 8082; manual `uvicorn` entry uses 8081; CORS already open to Laravel/NGINX
- Chatbot (LLM-as-Orchestrator)
  - Endpoint: `POST /api/chatbot` → `{ reply, mode: natural|structured|guided }`
  - Services: `ChatOrchestrationService`, `RAGSummarizer`, `ChatReportService` (chat-specific), `GuidedService`
  - Note: Chatbot methods were removed from `ReportService`; use `ChatReportService` APIs instead
  - UI: `resources/views/components/pertanian-report-page.blade.php`, legacy bridge `resources/js/components/pertanianReportForm.js`, modular logic in `resources/js/pertanian/*`
- Unified Pertanian reports
  - Web routes under `prefix('pertanian')` with `moduleType` `lahan|benih-pupuk|iklim-opt-dpi`
  - Compatibility APIs: `api/benih-pupuk`, `api/lahan`, `api/iklim-opt-dpi` (`topiks`, `variabels/{topik}`, `years`, `bulans`, `filter`, `sample-data`)
- Public NBM availability
  - `ketersediaan/api/laporan-nbm` and `ketersediaan/api/komoditi` implement the komoditi join/ordering used by the UI

### Config and wiring
- ML URLs: `config/app.php` (`ML_API_URL`), plus `config/services.php`/`config/nbm_prediction.php` (`NBM_API_URL`)
- Model path/layout: `ml_models/models/nbm_production` (and optional `..._enhanced`); sequence length is 6 → preserve feature order or update loader accordingly

### When editing (checklist)
- Touch both routes and Livewire: update `routes/web.php` (and `routes/nbm_api.php` if ML-related) and any referencing Livewire components
- Exports: adjust `app/Exports/*`; downloads must stream `xlsx` via `Excel::download` and keep the session → redirect pattern
- ML contract: re-verify `/health`, `/model/stats`, and `/predict` with a 6-point payload; if multi-step changed, also recheck `/predict/multi-step`

### Pointers (good starting places)
- Routes & permissions: `routes/web.php`, `routes/nbm_api.php`
- ML API and model: `fastapi/main.py`, `ml_models/README.md`
- Livewire + export: `app/Livewire/Admin/IklimoptdpiReports.php`, `app/Http/Controllers/*Export*`
- End-to-end refs: root `README.md`, `FASTAPI_INTEGRATION_SUCCESS.md`, `docs/chatbot/README.md`## SIKOLBIA — AI assistant quick brief

Laravel 12 + Livewire 3 app with a separate FastAPI ML service. Five modules: konsumsi (NBM), lahan, iklim OptDPI, benih & pupuk, dan daftar alamat. Keep route groups, Livewire wiring, export flows, and the ML API contract intact.

Architecture (where things live)
- Laravel core: `app/` (controllers, Livewire in `app/Livewire`, models in `app/Models`, services in `app/Services`). Routes in `routes/` are grouped under `admin/...`, `api/...`, `pertanian/...`.
- Frontend: `resources/` built with Vite/Tailwind (`package.json`, `vite.config.js`).
- ML training: `ml_models/` (see `production_model.py`, `data_loader.py`, `train_model.py`).
- ML serving: `fastapi/main.py` loads from `ml_models/models/nbm_production`; endpoints: `/health`, `/predict`, `/predict/multi-step`, `/predict/batch`, `/model/stats`.
- Docker: `docker-compose.yml` services `app`, `nginx` (8000), `mysql` (3306), `phpmyadmin` (8081), `redis` (6379), `fastapi-ml` (8082). Manual FastAPI runs on 8081.

Core workflows (see README for full commands)
- Full stack: `docker-compose up --build -d` → inside `app`: `php artisan migrate:fresh --seed` and `php artisan key:generate`.
- Local dev: Laravel `php artisan serve`; assets `npm run dev`/`npm run build`; ML API via `./start_api.sh` or the `fastapi-ml` container.
- One-shot dev stack: `composer run dev` (serves Laravel, queue listener, and Vite concurrently).
- Tests: Pest via `composer run pest` (clears caches, migrates fresh, seeds `RolePermissionSeeder`).

Project conventions and gotchas
- Excel export: Livewire + Maatwebsite Excel. Pattern: Livewire sets session, then redirect to a download route (e.g., `admin/benih-pupuk/export/*`). Update `app/Exports/*` and keep the session handoff.
- Display labels: reference tables commonly use `deskripsi`; order/select by `deskripsi` in UIs.
- Komoditi code: `kode_kelompok + kode_komoditi` (e.g., `01` → `0101`). Public AJAX uses this; see `routes/web.php` → `ketersediaan/api/komoditi`.
- Permissions: `spatie/laravel-permission`. Preserve exact `permission:` middleware strings across refactors.

Integration points (keep these stable)
- NBM ML API: Laravel `NBMPredictionController` wired in `routes/nbm_api.php` and under `admin/konsumsi-pangan/prediksi-nbm` in `routes/web.php`. FastAPI `/predict` requires exactly 6 months of `NBMDataPoint` `{ tahun, bulan, kelompok, komoditi, kalori_hari }`; returns `{ success, prediction, confidence_interval, model_info, input_summary }`. See `fastapi/main.py`.
- Chatbot (LLM-orchestrator): Controller `app/Http/Controllers/Api/ChatbotController.php`; services `app/Services/ChatOrchestrationService.php`, `RAGSummarizer.php`, `ReportService.php`, `GuidedService.php`. Public endpoint `POST /api/chatbot` responds with `{ reply, mode: natural|structured|guided }`. UI lives in `resources/views/components/pertanian-report-page.blade.php` and `resources/js/components/pertanianReportForm.js`.
- Unified Pertanian reports: `routes/web.php` under `prefix('pertanian')` with `moduleType` `lahan|benih-pupuk|iklim-opt-dpi`. Compatibility APIs: `api/benih-pupuk`, `api/lahan`, `api/iklim-opt-dpi` (e.g., `topiks`, `variabels/{topik}`, `years`, `bulans`, `filter`, `sample-data`).
- Ketersediaan NBM (public): `ketersediaan/api/laporan-nbm` and `ketersediaan/api/komoditi` implement the komoditi join/ordering behavior used by the UI.

ML service notes
- Model path: `ml_models/models/nbm_production` (and optional `..._enhanced`). Sequence length is 6; keep feature order compatible. Update `NBMProductionModel.load_production_model` if layout changes.
- Ports: Docker service on 8082; manual `uvicorn` entry runs 8081. CORS already allows Laravel/NGINX origins and `*`.
- Config: Laravel reads ML URLs from `config/app.php` (`ML_API_URL`) and NBMPrediction from `config/services.php`/`config/nbm_prediction.php` (`NBM_API_URL`). Keep these in sync across environments.

When editing
- Routes/views: update both `routes/web.php` (and `routes/nbm_api.php` if ML-related) and any Livewire components that reference them.
- Exports/templates: adjust `app/Exports/*`; downloads must stream `xlsx` via `Excel::download` and preserve the session redirect pattern.
- ML contract: after changes, verify `/health`, `/model/stats`, and `/predict` with a 6-point payload; if multi-step changed, also recheck `/predict/multi-step`.

Good references
- Routes & permissions: `routes/web.php`, `routes/nbm_api.php`.
- ML API contract: `fastapi/main.py`.
- Livewire + export pattern: `app/Livewire/Admin/IklimoptdpiReports.php`, `app/Http/Controllers/*Export*`.
- Developer guideposts: root `README.md`, `ml_models/README.md`, `FASTAPI_INTEGRATION_SUCCESS.md`.

Questions or unclear areas? Tell me which module (NBM/pertanian reports/exports/ML API) to expand, and I’ll add concrete flows or test snippets next.
