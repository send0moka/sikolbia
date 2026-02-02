# 🤖 NBM Model Versioning System

Sistem manajemen versi model machine learning untuk prediksi NBM dengan fitur training otomatis, versioning, dan switching.

## ✨ Features

- 🚀 **Auto Training**: Train model baru dengan 1 click
- 📊 **Version Management**: Kelola multiple versi model
- 🔄 **Easy Switching**: Ganti versi model tanpa downtime
- 📈 **Metrics Tracking**: Track MAE, RMSE, MAPE, R² setiap versi
- 👥 **User Attribution**: Catat siapa yang train model
- 🏷️ **Release Stages**: Alpha, Beta, Production labeling
- 📝 **Version History**: Timeline lengkap semua versi

## 🎯 Quick Start

```bash
# 1. Migrasi database
php artisan migrate

# 2. Seed model awal
php artisan db:seed --class=ModelVersionSeeder

# 3. Setup permission (lihat SETUP_MODEL_VERSIONING.md)

# 4. Install Python deps
cd sikolbia-ml
pip install tensorflow scikit-learn xgboost pandas numpy

# 5. Restart services
```

**Selesai!** Buka `http://localhost:8000/admin/konsumsi-pangan/prediksi-nbm`

## 📖 Documentation

- **Setup Guide**: [docs/SETUP_MODEL_VERSIONING.md](./SETUP_MODEL_VERSIONING.md)
- **Full Documentation**: [docs/MODEL_VERSIONING_SYSTEM.md](./MODEL_VERSIONING_SYSTEM.md)

## 🎨 UI Overview

### Header (Prediction Page)

```
[Update Model] [v1.0.0] [Production] [🕐 History]
```

### Update Model Modal

- Select release stage (Alpha/Beta/Production)
- Add description
- Start training → background process
- Progress bar shows status

### Version History Modal

```
✓ v1.0.1 [Active] [Production] [Switch]
  • MAE: 850.23, RMSE: 1750.45, MAPE: 3.65%, R²: 0.9912
  • Released: February 3, 2026 by Admin

○ v1.0.0 [Beta] [Switch]
  • MAE: 867.04, RMSE: 1788.78, MAPE: 3.73%, R²: 0.9901
  • Released: February 2, 2026
```

## 🔧 Workflow

1. **Update Data** → Admin adds/modifies data via CRUD interface
2. **Train Model** → Click "Update Model", configure, start training (15-30 min)
3. **Test & Validate** → Check metrics, test predictions
4. **Switch Version** → Activate new model when ready
5. **Rollback if Needed** → Switch back to previous version anytime

## 📁 Architecture

```
Laravel (Frontend/API)
    ↓
ModelVersionService (Business Logic)
    ↓
FastAPI (/model/* endpoints)
    ↓
Python Training Script
    ↓
Model Artifacts (versioned folders)
```

## 🔑 Key Components

### Database

- **Table**: `model_versions`
- **Model**: `App\Models\ModelVersion`

### Laravel

- **Service**: `App\Services\ModelVersionService`
- **Livewire**: `App\Livewire\PrediksiNbm`
- **View**: `resources/views/livewire/prediksi-nbm.blade.php`

### FastAPI

- **Router**: `app/routers/model_management.py`
- **Endpoints**: `/model/versions`, `/model/train`, `/model/switch`

### Python

- **Script**: `app/training/train_nbm_model.py`
- **Models**: LSTM + XGBoost + Huber Ensemble

### Storage

- **Active**: `ml_models/models/nbm_google_colab/` (v1.0.0)
- **New Versions**: `ml_models/models/nbm_v1.0.1/`, `nbm_v1.0.2/`, etc.
- **Archive**: `ml_models/archive/` (old versions)

## 🎓 Example Usage

### Scenario: Q1 2026 Data Update

```
1. Admin adds 3 months of new NBM data (Jan-Mar 2026)
   → transaksi_nbms table updated

2. Admin clicks "Update Model"
   → Select "Beta" release stage
   → Description: "Updated with Q1 2026 data"
   → Click "Start Training"

3. System creates v1.0.1
   → Exports all transaksi_nbms data
   → Trains LSTM + XGBoost + Huber
   → Saves to ml_models/models/nbm_v1.0.1/
   → Calculates metrics: MAE, RMSE, MAPE, R²

4. After 20 minutes, training completes
   → v1.0.1 appears in history with metrics
   → MAE improved: 850.23 (was 867.04)

5. Admin tests predictions with v1.0.1
   → Looks good! Switch to production

6. Admin clicks "Switch" on v1.0.1
   → v1.0.1 becomes active
   → All predictions now use new model

7. If issues arise → Switch back to v1.0.0
   → Instant rollback, no data loss
```

## 🚨 Troubleshooting

| Issue               | Solution                                |
| ------------------- | --------------------------------------- |
| Button not visible  | Add `manage model_versions` permission  |
| Training fails      | Check Python deps, disk space, logs     |
| Can't switch        | Verify files exist, restart FastAPI     |
| Wrong version shown | Sync: `$service->syncVersionsFromApi()` |

## 📊 Model Versioning Strategy

- **Patch versions** (v1.0.x): Data updates, minor improvements
- **Minor versions** (v1.x.0): Architecture changes, new features
- **Major versions** (vx.0.0): Complete redesign, breaking changes

Current: Auto-increment patch (v1.0.0 → v1.0.1 → v1.0.2)

## 🔐 Permissions

```php
// Required permission
'manage model_versions'

// Granted to
- admin role
- super_admin role
```

## 📈 Metrics Comparison

| Version | MAE    | RMSE    | MAPE  | R²     | Status   |
| ------- | ------ | ------- | ----- | ------ | -------- |
| v1.0.1  | 850.23 | 1750.45 | 3.65% | 0.9912 | Active   |
| v1.0.0  | 867.04 | 1788.78 | 3.73% | 0.9901 | Archived |

Lower is better for MAE, RMSE, MAPE. Higher is better for R².

## 🎯 Best Practices

1. ✅ Test new models thoroughly before production
2. ✅ Keep at least 2 versions for rollback capability
3. ✅ Add meaningful descriptions to versions
4. ✅ Use appropriate release stages (alpha → beta → production)
5. ✅ Archive old versions after validation
6. ✅ Restart FastAPI after switching for full effect
7. ✅ Monitor prediction quality after each switch

## 🤝 Contributing

When adding new features:

1. Update database schema (migration)
2. Update ModelVersion model
3. Add service methods
4. Update API endpoints
5. Update UI components
6. Update documentation

## 📞 Support

- **Technical Issues**: Check logs in `sikolbia-ml/logs/`
- **Training Issues**: See training script logs
- **Permission Issues**: Run permission seeder
- **API Issues**: Check FastAPI health: `http://localhost:8082/health`

---

**Version**: 1.0.0
**Last Updated**: February 2, 2026
**Author**: Based on Google Colab implementation by Jehian Athaya Tsani Az Zuhry (H1D022006)
