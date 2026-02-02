# Model Update Button State Logic

## Overview

The Update Model button in the NBM Prediction system now intelligently enables/disables based on actual data changes, providing better UX and preventing unnecessary model retraining.

## Business Logic

### Button States

#### 🟢 ENABLED (Button Active)

Button is enabled when:

- There are new records in `transaksi_nbms` after the last model training
- Existing records in `transaksi_nbms` have been updated after the last model training
- No `last_data_sync` timestamp exists for the active model (first-time setup)

#### 🔴 DISABLED (Button Inactive)

Button is disabled when:

- No data changes detected in `transaksi_nbms` since last training
- Model training is currently in progress (`$isTraining = true`)
- All data has been synced with the current active model

### Visual Indicators

- **Disabled state**: Button shows lock icon 🔒 and has reduced opacity
- **Enabled state**: Standard purple button with update icon
- **Tooltip**: Shows reason for disabled state ("No data changes detected")

## Technical Implementation

### Database Schema

#### model_versions Table

```sql
ALTER TABLE model_versions
ADD COLUMN last_data_sync TIMESTAMP NULL
AFTER released_at;
```

**Purpose**: Records the timestamp when the model was last trained/synced with data

### Livewire Component (PrediksiNbm.php)

#### Properties

```php
public $hasDataChanges = false; // Track if there are new data changes
```

#### Methods

##### checkDataChanges()

```php
public function checkDataChanges()
{
    // 1. Get active model and its last_data_sync timestamp
    $activeModel = \App\Models\ModelVersion::where('is_active', true)->first();

    // 2. If no active model, enable button
    if (!$activeModel) {
        $this->hasDataChanges = true;
        return;
    }

    // 3. Check if last_data_sync exists
    $lastSync = $activeModel->last_data_sync;

    if (!$lastSync) {
        // If model created within 1 hour, assume synced
        if ($activeModel->created_at->diffInHours(now()) < 1) {
            $this->hasDataChanges = false;
            return;
        }
        // Otherwise enable button
        $this->hasDataChanges = true;
        return;
    }

    // 4. Check for data changes after last sync
    $hasNewData = DB::table('transaksi_nbms')
        ->where(function($query) use ($lastSync) {
            $query->where('created_at', '>', $lastSync)
                  ->orWhere('updated_at', '>', $lastSync);
        })
        ->exists();

    $this->hasDataChanges = $hasNewData;
}
```

**Logic Flow**:

1. Retrieve active model and its `last_data_sync` timestamp
2. If no active model exists → enable button
3. If `last_data_sync` is NULL:
    - Check if model was just created (< 1 hour ago) → disable button
    - Otherwise → enable button
4. Compare `transaksi_nbms` timestamps with `last_data_sync`:
    - If any record has `created_at` or `updated_at` > `last_data_sync` → enable button
    - Otherwise → disable button

##### loadModelVersions()

```php
public function loadModelVersions()
{
    // ... existing code ...

    // Check if there are data changes since last training
    $this->checkDataChanges();
}
```

**When Called**: On component mount and after model operations

##### checkTrainingStatus()

```php
public function checkTrainingStatus()
{
    // ... check training status ...

    if (!$this->isTraining && $this->trainingProgress >= 100) {
        // Training completed, update last_data_sync
        $newModel = \App\Models\ModelVersion::orderBy('created_at', 'desc')->first();
        if ($newModel) {
            $newModel->update(['last_data_sync' => now()]);
        }

        // Reload versions and recheck data changes
        $this->loadModelVersions();
    }
}
```

**Purpose**: Sets `last_data_sync` to current timestamp after successful training

### Blade Template (prediksi-nbm.blade.php)

```blade
<button @click="showUpdateModal = true"
    wire:disabled="{{ !$hasDataChanges || $isTraining }}"
    :class="{'opacity-50 cursor-not-allowed': {{ !$hasDataChanges || $isTraining ? 'true' : 'false' }}}"
    class="px-3 py-1.5 text-xs font-medium rounded-lg bg-purple-600 text-white
           hover:bg-purple-700 transition-colors shadow-sm flex items-center gap-1.5
           disabled:opacity-50 disabled:cursor-not-allowed"
    title="{{ $hasDataChanges ? 'Train New Model Version' : 'No data changes detected' }}">
    <!-- Update icon -->
    <svg>...</svg>
    Update Model
    <!-- Lock icon when disabled -->
    @if(!$hasDataChanges && !$isTraining)
        <svg class="h-3 w-3 ml-0.5">
            <path d="...lock icon path..." />
        </svg>
    @endif
</button>
```

**Features**:

- `wire:disabled`: Disables button when no changes or training in progress
- Dynamic tooltip: Shows appropriate message based on state
- Lock icon: Appears when button is disabled due to no changes

## Workflow Diagram

```
┌─────────────────────────────────────────┐
│  Admin visits Prediksi NBM page         │
└──────────────┬──────────────────────────┘
               │
               ▼
┌─────────────────────────────────────────┐
│  loadModelVersions() called              │
│  → checkDataChanges() executed           │
└──────────────┬──────────────────────────┘
               │
               ▼
        ┌──────┴───────┐
        │              │
        ▼              ▼
   No Changes      Has Changes
   Button 🔴       Button 🟢
        │              │
        │              ▼
        │    ┌─────────────────────┐
        │    │  Admin clicks        │
        │    │  "Update Model"      │
        │    └──────────┬───────────┘
        │               │
        │               ▼
        │    ┌─────────────────────┐
        │    │  Training starts     │
        │    │  Button disabled     │
        │    │  (isTraining=true)   │
        │    └──────────┬───────────┘
        │               │
        │               ▼
        │    ┌─────────────────────┐
        │    │  Training completes  │
        │    │  last_data_sync set  │
        │    └──────────┬───────────┘
        │               │
        └───────────────┴──────────►
                        │
                        ▼
              ┌─────────────────────┐
              │  checkDataChanges()  │
              │  Button disabled     │
              └─────────────────────┘
```

## Testing

### Test Script

Use `test-button-state.php` to verify the logic:

```bash
docker-compose exec app php test-button-state.php
```

**Output Example (No Changes)**:

```
=== Testing Button State Logic ===
Active Model: v1.0.0
Last Data Sync: 2026-02-02 23:20:53

Latest transaksi_nbm record:
  Updated at: 2026-01-20 01:36:31

🔒 No data changes detected
Update Model button: 🔴 DISABLED
Records changed since last sync: 0
```

**Output Example (With Changes)**:

```
=== Testing Button State Logic ===
Active Model: v1.0.0
Last Data Sync: 2026-02-02 23:20:53

Latest transaksi_nbm record:
  Updated at: 2026-02-02 23:29:20

✅ Data changes detected!
Update Model button: 🟢 ENABLED
Records changed since last sync: 1
```

### Manual Testing Steps

#### Test 1: Initial State (No Changes)

1. Visit http://localhost:8000/admin/konsumsi-pangan/prediksi-nbm
2. **Expected**: Update Model button is disabled (grayed out with lock icon)
3. Hover over button
4. **Expected**: Tooltip shows "No data changes detected"

#### Test 2: Simulate Data Change

1. Visit http://localhost:8000/admin/konsumsi-pangan/transaksi-nbm
2. Edit any existing record or create a new one
3. Save changes
4. Return to Prediksi NBM page
5. **Expected**: Update Model button is now enabled (purple, no lock icon)
6. Hover over button
7. **Expected**: Tooltip shows "Train New Model Version"

#### Test 3: After Training

1. Click "Update Model" button (when enabled)
2. Fill training form and submit
3. **Expected**: Button becomes disabled during training
4. Wait for training to complete
5. **Expected**: Button remains disabled (no new changes yet)

#### Test 4: Multiple Changes

1. Make multiple edits to transaksi_nbms
2. Check button state
3. **Expected**: Button enabled
4. Hover to see count of changed records

## Configuration

### Environment Variables

No additional environment variables required. Uses existing database connection.

### Dependencies

- Laravel 12
- Livewire 3
- Alpine.js (for UI reactivity)
- Tailwind CSS (for styling)

## Migration Commands

```bash
# Run migration to add last_data_sync column
docker-compose exec app php artisan migrate

# Set initial sync timestamp for existing model
docker-compose exec app php artisan tinker
DB::table('model_versions')
  ->where('version', 'v1.0.0')
  ->update(['last_data_sync' => now()]);
```

## Benefits

### User Experience

- ✅ **Clear feedback**: Users know immediately if training is needed
- ✅ **Prevents waste**: No unnecessary retraining on unchanged data
- ✅ **Visual clarity**: Lock icon + tooltip explain why button is disabled
- ✅ **Professional UX**: Button state reflects actual system state

### System Benefits

- ✅ **Resource efficiency**: Only train when data has changed
- ✅ **Better versioning**: Each version corresponds to actual data updates
- ✅ **Audit trail**: `last_data_sync` provides clear tracking
- ✅ **Performance**: Prevents redundant expensive operations

## Troubleshooting

### Button always enabled

**Cause**: `last_data_sync` is NULL for active model

**Solution**:

```bash
docker-compose exec app php artisan tinker
$model = App\Models\ModelVersion::where('is_active', true)->first();
$model->update(['last_data_sync' => now()]);
```

### Button always disabled

**Cause**: `last_data_sync` is in the future or all data is older

**Check**:

```bash
docker-compose exec app php test-button-state.php
```

**Solution**: Verify timestamp values and time zone settings

### Changes not detected

**Cause**: Timestamps not updating on CRUD operations

**Check**: Ensure `updated_at` column is being set:

```sql
SELECT id, updated_at FROM transaksi_nbms
ORDER BY updated_at DESC LIMIT 5;
```

## Future Enhancements

### Potential Improvements

1. **Change count badge**: Show number of records changed since last sync

    ```blade
    @if($changedRecordsCount > 0)
        <span class="badge">{{ $changedRecordsCount }} changes</span>
    @endif
    ```

2. **Auto-refresh**: Poll for changes and update button state automatically

    ```php
    // In mount() or with wire:poll
    protected $listeners = ['dataChanged' => 'checkDataChanges'];
    ```

3. **Change preview**: Show which records changed before training

    ```php
    public function getChangedRecords()
    {
        return DB::table('transaksi_nbms')
            ->where('updated_at', '>', $activeModel->last_data_sync)
            ->select('id', 'komoditi', 'updated_at')
            ->get();
    }
    ```

4. **Webhook integration**: Trigger check on data import completion
    ```php
    event(new DataImported($recordCount));
    ```

## Related Documentation

- [MODEL_VERSIONING_SYSTEM.md](./MODEL_VERSIONING_SYSTEM.md) - Overall versioning architecture
- [SETUP_MODEL_VERSIONING.md](./SETUP_MODEL_VERSIONING.md) - Setup instructions
- [MODEL_VERSIONING_README.md](./MODEL_VERSIONING_README.md) - User guide

## Support

For issues or questions:

1. Check `test-button-state.php` output
2. Review Laravel logs: `storage/logs/laravel.log`
3. Check browser console for Livewire errors
4. Verify database timestamps are correct
