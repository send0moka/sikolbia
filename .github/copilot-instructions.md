## SIKOLBIA — AI assistant quick brief

Laravel 12 + Livewire 3 app with a separate FastAPI ML service. Five modules: konsumsi (NBM), lahan, iklim OptDPI, benih & pupuk, dan daftar alamat. Preserve integration points (routes, Livewire, exports, ML API) and existing conventions.

Architecture map (where things live)
- Laravel core: `app/` (controllers, Livewire in `app/Livewire`, models in `app/Models`, services in `app/Services`). Routes under `routes/` follow grouped prefixes (`admin/...`, `api/...`).
- Frontend assets: `resources/` built by Vite/Tailwind (`package.json`, `vite.config.js`).
- ML training: `ml_models/` (see `production_model.py`, `data_loader.py`, `train_model.py`).
- ML serving: `fastapi/main.py` loads model from `ml_models/models/nbm_production` and exposes `/health`, `/predict`, `/model/stats`.
- Docker: `docker-compose.yml` services `app`, `nginx` (8000), `mysql` (3306), `phpmyadmin` (8081), `redis` (6379), `fastapi-ml` (8082).

Core workflows (commands implied; see README for full steps)
- Full stack: `docker-compose up --build -d` → run `php artisan migrate:fresh --seed` and `php artisan key:generate` inside `app` container.
- Local dev: Laravel `php artisan serve`; assets `npm run dev`/`build`; ML API via `./start_api.sh` or Docker `fastapi-ml`. ML base URL uses env `ML_API_URL`.
- Tests: use Pest via `composer run pest` (script clears caches, migrates fresh, seeds `RolePermissionSeeder`).

Project conventions and gotchas
- Excel export pattern: Livewire + Maatwebsite Excel; session-based handoff then redirect to a download route (e.g., `admin/benih-pupuk/export/*`). Update `app/Exports/*` and keep session semantics.
- Display fields: many reference tables use `deskripsi` for labels; select/order by `deskripsi` in UI flows.
- Komoditi code scheme: `kode_kelompok + kode_komoditi` (e.g., `01` → `0101`). AJAX relies on this; see `routes/web.php` ketersediaan `api/komoditi` example.
- Permissions: `spatie/laravel-permission`; keep exact `permission:` middleware strings on routes when refactoring.

Key integration points (with concrete examples)
- NBM ML API: Laravel controller `NBMPredictionController` is wired in `routes/nbm_api.php` and `routes/web.php` under `admin/konsumsi-pangan/prediksi-nbm`. FastAPI `/predict` expects exactly 6 months of `NBMDataPoint` `{ tahun, bulan, kelompok, komoditi, kalori_hari }` and returns `{ prediction, confidence_interval, model_info }` (see `fastapi/main.py`).
- Unified Pertanian reports + chatbot: Blade `resources/views/components/pertanian-report-page.blade.php`, JS `resources/js/components/pertanianReportForm.js`, service `app/Services/ReportService.php`. Public routes in `routes/web.php` under `prefix('pertanian')` with moduleType `lahan|benih-pupuk|iklim-opt-dpi`; compatibility APIs exist at `api/benih-pupuk`, `api/lahan`, `api/iklim-opt-dpi` (e.g., `topiks`, `variabels/{topik}`, `years`, `bulans`, `filter`, `sample-data`).
- Ketersediaan NBMs: see `routes/web.php` → `ketersediaan/api/laporan-nbm` and `ketersediaan/api/komoditi` for the komoditi code/join behavior used by the UI.

ML service notes
- Model path is fixed: `ml_models/models/nbm_production`. If serialization or layout changes, update `NBMProductionModel.load_production_model` and keep FastAPI aware of sequence length/features.
- Docker sets `REDIS_URL` and `DATABASE_URL` for FastAPI; CORS already allows `*` and Docker hosts.

When editing
- Touching routes or view names? Update both `routes/web.php` (and `routes/nbm_api.php` where applicable) and any Livewire components referencing them.
- Changing exports/templates? Update `app/Exports/*` and ensure downloads still stream an `xlsx` via `Excel::download`.
- ML contract changes? Re-run a quick `/health` and `/model/stats` check, and validate `/predict` with a 6-point payload.

Good starting references
- App routes and permissions: `routes/web.php`, `routes/nbm_api.php`.
- ML contract: `fastapi/main.py`.
- Exports pattern: `app/Livewire/Admin/IklimoptdpiReports.php` and controllers under `app/Http/Controllers/*Export*`.
- End-to-end dev: root `README.md`, `ml_models/README.md`.

Questions or unclear areas? Tell me which module (NBM/pertanian reports/exports/ML API) to expand, and I’ll add concrete flows or test snippets next.
