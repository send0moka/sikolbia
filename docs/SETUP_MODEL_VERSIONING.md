# Model Versioning System - Setup Instructions

## 🚀 Quick Setup

### 1. Run Database Migration

```bash
cd sikolbia-app
php artisan migrate
```

This creates the `model_versions` table.

### 2. Seed Initial Model Version

```bash
php artisan db:seed --class=ModelVersionSeeder
```

This creates v1.0.0 entry for the current nbm_google_colab model.

### 3. Update Permissions

Add to your `RolePermissionSeeder.php`:

```php
// Add new permission
Permission::create(['name' => 'manage model_versions', 'guard_name' => 'web']);

// Assign to admin role
$adminRole = Role::findByName('admin');
$adminRole->givePermissionTo('manage model_versions');
```

Then run:

```bash
php artisan db:seed --class=RolePermissionSeeder
```

### 4. Install Python Dependencies

```bash
cd ../sikolbia-ml
pip install tensorflow scikit-learn xgboost pandas numpy
```

### 5. Restart Services

```bash
# Restart FastAPI
cd sikolbia-ml
./start_dev.sh  # or start_dev.bat on Windows

# Restart Laravel (if using artisan serve)
cd ../sikolbia-app
php artisan serve
```

## ✅ Verification

### 1. Check FastAPI Endpoints

```bash
# Test model versions endpoint
curl http://localhost:8082/model/versions

# Test active model endpoint
curl http://localhost:8082/model/active

# Test training status
curl http://localhost:8082/model/training/status
```

Expected response for `/model/versions`:

```json
[
  {
    "version": "v1.0.0",
    "model_name": "LSTM Enhanced Ensemble",
    "mae": 867.04,
    "rmse": 1788.78,
    "mape": 3.73,
    "r2_score": 0.9901,
    "status": "active",
    "is_active": true,
    "release_stage": "production"
  }
]
```

### 2. Check Laravel

Visit: `http://localhost:8000/admin/konsumsi-pangan/prediksi-nbm`

You should see:

- ✓ Version badge showing "v1.0.0"
- ✓ "Production" badge
- ✓ "Update Model" button (if you have `manage model_versions` permission)
- ✓ History button (clock icon)

Click history button to see v1.0.0 with full details.

### 3. Check Database

```bash
php artisan tinker
```

```php
App\Models\ModelVersion::all();
// Should return 1 record for v1.0.0

App\Models\ModelVersion::active()->first();
// Should return the active v1.0.0 model
```

## 🎯 Usage

### Train New Model Version

1. **Update Data**: Add/modify data in `transaksi_nbms` table via the CRUD interface at:
   `http://localhost:8000/admin/konsumsi-pangan/transaksi-nbm`

2. **Start Training**:
   - Go to prediction page
   - Click "Update Model" button
   - Select release stage (Alpha/Beta/Production)
   - Add description (optional)
   - Click "Start Training"

3. **Monitor Progress**:
   - Modal shows training progress
   - Takes 15-30 minutes
   - You can close modal and continue working

4. **After Completion**:
   - New version appears in history
   - Click "Switch" to activate it
   - Or keep using current version

### Switch Model Versions

1. Click history button (clock icon)
2. Find desired version
3. Click "Switch" button
4. Confirm switch
5. **Important**: Restart FastAPI for full effect:
   ```bash
   cd sikolbia-ml
   # Stop current process (Ctrl+C)
   ./start_dev.sh  # or start_dev.bat
   ```

## 📁 File Structure After Setup

```
sikolbia-ml/
├── ml_models/
│   ├── models/
│   │   ├── nbm_google_colab/  ← v1.0.0 (current)
│   │   ├── nbm_v1.0.1/        ← New versions will be here
│   │   ├── nbm_v1.0.2/
│   │   └── active/            ← Symlink to active version
│   └── archive/               ← Archived old models
└── data/
    └── transaksi_nbms_export.csv  ← Training data export
```

## 🔧 Troubleshooting

### Permission "manage model_versions" not found

```bash
php artisan cache:clear
php artisan config:clear
php artisan db:seed --class=RolePermissionSeeder
```

### "Update Model" button not visible

- Check user has permission: `php artisan tinker` → `auth()->user()->can('manage model_versions')`
- Add permission to role if missing

### Training fails immediately

- Check FastAPI logs
- Verify Python dependencies: `pip list | grep -E "tensorflow|scikit|xgboost"`
- Check disk space
- Verify data export works

### Can't switch model version

- Ensure model files exist in version folder
- Check file permissions
- On Windows, may need Developer Mode for symlinks
- Restart FastAPI after switching

### Version not showing in history

```php
// In tinker:
$service = app(\App\Services\ModelVersionService::class);
$result = $service->syncVersionsFromApi();
dd($result);
```

## 🎨 UI Components

The system adds:

1. **Version badge** (top right): Shows current active version
2. **Stage badge** (top right): Alpha/Beta/Production
3. **Update Model button** (purple): Opens training modal
4. **History button** (clock icon): Opens version history modal

In history modal:

- Green dot = Active version
- Blue dot = Production stage
- Gray dot = Other stages
- **Switch** button = Change active version

## 📊 Next Steps

1. Test training with small dataset first
2. Monitor first training completion
3. Verify new model predictions work
4. Compare metrics between versions
5. Set up automated training schedule (optional)

## 🔄 Rollback Procedure

If new model has issues:

1. Open version history
2. Find previous good version (e.g., v1.0.0)
3. Click "Switch"
4. Restart FastAPI
5. Verify predictions work correctly

## 📝 Notes

- Each training creates a new patch version (v1.0.0 → v1.0.1 → v1.0.2)
- All model files are preserved (never deleted automatically)
- Archive old versions manually to free space
- Training data automatically exported from current `transaksi_nbms` table
- Model artifacts are ~3-5 MB per version

## ⚠️ Important

- Never delete active model version
- Always test new models before production use
- Keep at least 2 recent versions for rollback
- Training requires ~2GB RAM and takes 15-30 minutes
- Restart FastAPI after switching for full effect

---

**Support**: See `docs/MODEL_VERSIONING_SYSTEM.md` for detailed documentation
