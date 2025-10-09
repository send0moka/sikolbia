## Quick orientation for AI code-assistants

This repository is **SIKOLBIA** (Sistem Informasi Konsumsi + Lahan + Iklim + Benih + Alamat) - a Laravel 12 + Livewire 3 web app with a separate FastAPI ML service. The system integrates 5 main modules: food consumption, land management, climate optimization, seeds & fertilizers, and address database. The goal of edits should be to preserve the app's integration points (routes, Livewire components, ML API) and follow existing conventions in code and deployment.

Key components (quick map):
- Laravel app: `app/` (controllers, Livewire components in `app/Livewire`, models in `app/Models`, services in `app/Services`). See `routes/` for route naming and grouping conventions.
- Frontend assets: `resources/`, built with Vite/npm (see `package.json` and `vite.config.js`).
- ML training & utils: `ml_models/` (read `ml_models/README.md`).
- ML serving API: `fastapi/` — the FastAPI server (entry: `fastapi/main.py`) expects production model under `ml_models/models/nbm_production` and exposes `/health`, `/predict`, `/model/stats`.
- Docker + compose: `Dockerfile`, `docker-compose.yml` — production/dev workflows rely heavily on these.

Developer workflows to preserve and reuse
- Start full stack (recommended): `docker-compose up --build -d` (then use `docker-compose exec app php artisan migrate:fresh --seed` to initialize DB).
- Local Laravel dev: `php artisan serve`; for ML API run `./start_api.sh` (or `start_api.bat` on Windows) from repo root or use `fastapi/` Docker service.
- Build assets: `npm run build` (dev: `npm run dev`). CI uses Node 22 (see `.github/workflows/tests.yml`).
- Tests: `./vendor/bin/pest` or `composer run pest` (composer.json defines `pest` script). The CI workflow copies `.env.example` and runs `php artisan key:generate` before testing.

Project-specific conventions and important gotchas
- Livewire components export data using Maatwebsite Excel. Example: `app/Livewire/Admin/IklimoptdpiReports.php` uses `IklimoptdpiReportsExport` and session-based download flows. When changing exports, update `app/Exports/*` classes.
- Use database `deskripsi` fields for display in many reference tables (topik/variabel). Example: select and order by `deskripsi` in Livewire render methods.
- `komoditi` codes are composed as `kode_kelompok + kode_komoditi` (e.g. kelompok `01` -> komoditi `0101`). AJAX endpoints rely on this pattern (see `routes/web.php` ketersediaan API).
- Permissions use `spatie/laravel-permission`. Routes commonly wrap views with `permission:` middleware — preserve permission strings when renaming routes or controllers.
- Session/Export: some exports are implemented by setting session data then redirecting to a download route (see `admin/benih-pupuk/export/*` routes). Do not remove session usage without migrating behavior.

ML integration notes for code edits
- FastAPI reads production model from `ml_models/models/nbm_production`. If you modify model serialization, ensure `fastapi/main.py` loader (`NBMProductionModel.load_production_model`) is updated.
- FastAPI CORS already allows Docker host and `*` — but keep cautious changes; endpoints used by Laravel are `/health`, `/predict` and `/model/stats` (Laravel controllers call these endpoints — see `routes/web.php` for `prediksi-nbm` routes).
- Environment variables: Docker-compose wires `REDIS_URL`, `DATABASE_URL` for the ML service. When testing locally, mirror these into the FastAPI `.env` or start script.

Where to look for examples
- Example Livewire export: `app/Livewire/Admin/IklimoptdpiReports.php`
- Route naming patterns & API endpoints: `routes/web.php` (look for `prefix('admin/...')` and `prefix('api/...')` patterns)
- ML server & health/predict contract: `fastapi/main.py`
- Model training & preprocessing: `ml_models/` (see `train_model.py`, `production_model.py`, `data_loader.py`)
- Docker orchestration: `docker-compose.yml` (service names: `app`, `nginx`, `mysql`, `fastapi-ml`)

Quick PR checklist for AI edits
- Run or re-run migrations/seeds if DB schema changed: `php artisan migrate` / `migrate:fresh --seed`.
- If view or route names change, update `routes/web.php` and any Livewire components referencing them.
- If changing exports or Excel templates, update `app/Exports/*` and ensure tests or sample routes still return `xlsx` via `Excel::download`.
- For ML changes, run `python` scripts in `ml_models/` and ensure the FastAPI loader can still load the model path `ml_models/models/nbm_production`.
- Preserve file permission steps: storage and bootstrap/cache must stay writeable (Dockerfile and README include these steps).

When in doubt
- Read `README.md` (root) and `ml_models/README.md` first — they document expected commands and environment.
- Prefer small, focused changes (one behavioral change per PR). Include explicit integration tests where possible (e.g., call `/health` after ML edits, sanity-check exports by invoking the export route).

If you want me to update or expand any section (more examples, test commands, CI specifics), tell me which area to expand.
