# Model Versioning System - Implementation Guide

## Overview

Sistem versioning model NBM yang memungkinkan admin untuk:

- Train model baru berdasarkan data terbaru
- Menyimpan berbagai versi model
- Switch antar versi model
- Melihat history dan metrics setiap versi

## Database Structure

### Table: `model_versions`

```sql
- id: Primary key
- version: String (e.g., v1.0.0, v1.0.1) - UNIQUE
- model_name: String (e.g., "LSTM Enhanced Ensemble")
- model_type: String (e.g., "nbm_prediction")
- folder_path: String (path to model files)
- description: Text (optional notes)
- mae, rmse, mape, r2_score: Metrics
- training_data_count, training_data_from, training_data_to: Training info
- epochs, model_config: Training configuration
- status: Enum (training, completed, active, archived, failed)
- is_active: Boolean (currently active model)
- release_stage: Enum (alpha, beta, production)
- released_at: Timestamp
- trained_by: Foreign key to users
- notes, artifacts: Additional metadata
```

## Folder Structure

```
sikolbia-ml/
├── ml_models/
│   ├── models/
│   │   ├── nbm_google_colab/      # Legacy v1.0.0
│   │   ├── nbm_v1.0.1/            # New versions
│   │   ├── nbm_v1.0.2/
│   │   └── active/                # Symlink to active version
│   └── archive/                   # Archived models
└── app/
    └── training/
        └── train_nbm_model.py     # Training script
```

## API Endpoints (FastAPI)

### GET /model/versions

Returns list of all model versions with metadata

### GET /model/active

Returns currently active model

### POST /model/switch

```json
{
  "version": "v1.0.1"
}
```

Switches active model to specified version

### POST /model/train

```json
{
  "data_source": "mysql",
  "csv_path": null,
  "release_stage": "beta",
  "description": "Updated with Q1 2026 data"
}
```

Starts background training for new model version

### GET /model/training/status

Returns current training progress

### DELETE /model/versions/{version}

Archives a model version

## Laravel Components

### Model: `ModelVersion`

Located: `app/Models/ModelVersion.php`

Key methods:

- `getNextPatchVersion()`: Auto-increment version
- `activate()`: Set as active model
- Scopes: `active()`, `completed()`

### Service: `ModelVersionService`

Located: `app/Services/ModelVersionService.php`

Methods:

- `getAllVersions()`: Get all from DB
- `getActiveVersion()`: Get current active
- `syncVersionsFromApi()`: Sync from FastAPI to DB
- `trainNewModel($options)`: Start training
- `getTrainingStatus()`: Check training progress
- `switchVersion($version)`: Change active model
- `archiveVersion($version)`: Archive old model
- `exportTrainingData($path)`: Export data for training

### Livewire Component: `PrediksiNbm`

Located: `app/Livewire/PrediksiNbm.php`

Properties:

```php
public $modelVersions;
public $activeModelVersion;
public $activeModelStage;
public $isTraining = false;
public $trainingProgress = 0;
public $trainingMessage = '';
public $releaseStage = 'beta';
public $modelDescription = '';
public $nextModelVersion;
```

Methods:

```php
public function mount()
public function loadModelVersions()
public function trainNewModel()
public function switchModelVersion($version)
public function checkTrainingStatus() // Polling
```

## Training Script

### Python: `train_nbm_model.py`

Located: `sikolbia-ml/app/training/train_nbm_model.py`

Usage:

```bash
python app/training/train_nbm_model.py \
  --version v1.0.1 \
  --data path/to/transaksi_nbms.csv \
  --output ml_models/models
```

Output:

- Creates folder `ml_models/models/nbm_v1.0.1/`
- Saves all model artifacts (LSTM, XGBoost, Huber, scalers, encoders)
- Generates `metadata.json` with metrics and info

## Workflow

### 1. Admin wants to update model:

1. Click "Update Model" button
2. Fill form (release stage, description)
3. Click "Start Training"

### 2. Backend process:

1. Laravel calls `ModelVersionService::trainNewModel()`
2. Creates DB record with status='training'
3. Exports latest data from `transaksi_nbms` to CSV
4. Calls FastAPI `/model/train` endpoint
5. FastAPI runs Python script in background
6. Training script:
   - Loads data
   - Trains LSTM, XGBoost, Huber
   - Evaluates metrics
   - Saves models to versioned folder
   - Returns metadata

### 3. After training completes:

1. Admin sees new version in history modal
2. Can switch to new version using "Switch" button
3. System updates `is_active` flag and symlink
4. Predictions now use new model

## Permissions

Add to your permission seeder:

```php
Permission::create(['name' => 'manage model_versions']);
```

Assign to admin role:

```php
$adminRole->givePermissionTo('manage model_versions');
```

## Usage Examples

### View version history:

- Click clock icon button
- See all versions with metrics
- Click "Switch" to activate different version

### Train new model:

- Admin updates `transaksi_nbms` data (CRUD operations)
- Click "Update Model" button
- Configure release stage and description
- Start training (runs in background)
- Check progress with polling
- After completion, new version appears in history

### Switch between versions:

- Open version history modal
- Find desired version
- Click "Switch" button
- Confirm switch
- System activates that version

## Migration Steps

1. Run migration:

```bash
php artisan migrate
```

2. Seed initial model version (v1.0.0):

```bash
php artisan tinker
```

```php
App\Models\ModelVersion::create([
    'version' => 'v1.0.0',
    'model_name' => 'LSTM Enhanced Ensemble',
    'model_type' => 'nbm_prediction',
    'folder_path' => 'ml_models/models/nbm_google_colab',
    'description' => 'Production model from Google Colab',
    'mae' => 867.04,
    'rmse' => 1788.78,
    'mape' => 3.73,
    'r2_score' => 0.9901,
    'status' => 'active',
    'is_active' => true,
    'release_stage' => 'production',
    'released_at' => now(),
]);
```

3. Install Python dependencies:

```bash
cd sikolbia-ml
pip install tensorflow scikit-learn xgboost pandas numpy
```

4. Test FastAPI endpoints:

```bash
curl http://localhost:8082/model/versions
curl http://localhost:8082/model/active
```

## Notes

- Training takes 15-30 minutes depending on data size
- Each version preserved in separate folder
- Never delete active version
- Archive old versions to free space
- Always test new models before switching
- Rollback possible by switching to previous version

## Troubleshooting

**Training fails:**

- Check Python dependencies
- Verify CSV data format
- Check disk space
- Review logs in FastAPI

**Switch fails:**

- Ensure model files exist
- Check file permissions
- Verify symlink creation (Windows requires admin/dev mode)
- May need API restart for full effect

**Version sync issues:**

- Run `ModelVersionService::syncVersionsFromApi()`
- Check API connectivity
- Verify folder structure matches DB records
