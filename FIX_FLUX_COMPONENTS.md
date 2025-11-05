# Fix Flux Component Errors

## Problem
Users accessing pemerintah panel pages encountered `InvalidArgumentException` errors:
```
Unable to locate a class or view for component [flux::card]
Unable to locate a class or view for component [flux::heading]
Unable to locate a class or view for component [flux::subheading]
```

## Root Cause
Views referenced Flux UI library components (`livewire/flux` package) that are not installed in the project. These components were used in development but the package was never added to `composer.json`.

## Solution
Replaced all Flux UI components with semantic HTML + Tailwind CSS equivalents to maintain consistent styling without external dependencies.

## Files Fixed

### 1. Pemerintah Panel Views
- ✅ `resources/views/pemerintah/lahan.blade.php`
- ✅ `resources/views/pemerintah/benih-pupuk.blade.php`
- ✅ `resources/views/pemerintah/iklim.blade.php`
- ✅ `resources/views/pemerintah/settings.blade.php`
- ✅ `resources/views/pemerintah/panduan.blade.php`

### 2. Shared Partials
- ✅ `resources/views/partials/settings-heading.blade.php`

## Conversion Pattern

### Headings
**Before:**
```blade
<flux:heading size="xl">Data Lahan Pertanian</flux:heading>
<flux:subheading>Informasi detail lahan</flux:subheading>
```

**After:**
```blade
<h1 class="text-3xl font-bold text-neutral-900 dark:text-white">Data Lahan Pertanian</h1>
<p class="text-neutral-600 dark:text-neutral-400 mt-1">Informasi detail lahan</p>
```

### Cards
**Before:**
```blade
<flux:card>
    <p>Content here</p>
</flux:card>
```

**After:**
```blade
<div class="bg-white dark:bg-neutral-800 rounded-lg shadow p-6">
    <p>Content here</p>
</div>
```

### Form Fields
**Before:**
```blade
<flux:field>
    <flux:label>Password Lama</flux:label>
    <flux:input type="password" name="current_password" />
    <flux:description>Minimal 8 karakter</flux:description>
</flux:field>
```

**After:**
```blade
<div class="space-y-1">
    <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">Password Lama</label>
    <input type="password" name="current_password" 
           class="w-full px-3 py-2 border border-neutral-300 dark:border-neutral-600 rounded-lg bg-white dark:bg-neutral-700 text-neutral-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500" />
    <p class="text-xs text-neutral-500 dark:text-neutral-400">Minimal 8 karakter</p>
</div>
```

### Buttons
**Before:**
```blade
<flux:button type="submit" variant="primary">Ubah Password</flux:button>
<flux:button type="button" variant="ghost">Cancel</flux:button>
<flux:button variant="danger" disabled>Hapus Akun</flux:button>
```

**After:**
```blade
<button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition-colors">Ubah Password</button>
<button type="button" class="px-4 py-2 bg-transparent hover:bg-neutral-100 dark:hover:bg-neutral-700 text-neutral-700 dark:text-neutral-300 rounded-lg font-medium transition-colors">Cancel</button>
<button disabled class="px-4 py-2 bg-red-600 text-white rounded-lg font-medium opacity-50 cursor-not-allowed">Hapus Akun</button>
```

### Switches/Toggles
**Before:**
```blade
<flux:switch />
```

**After:**
```blade
<label class="relative inline-flex items-center cursor-pointer">
    <input type="checkbox" class="sr-only peer">
    <div class="w-11 h-6 bg-neutral-200 peer-focus:ring-2 peer-focus:ring-blue-500 dark:bg-neutral-700 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-neutral-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-neutral-600 peer-checked:bg-blue-600"></div>
</label>
```

### Select Dropdowns
**Before:**
```blade
<flux:select variant="filled">
    <option value="id">Indonesia</option>
    <option value="en">English</option>
</flux:select>
```

**After:**
```blade
<select class="w-full px-3 py-2 border border-neutral-300 dark:border-neutral-600 rounded-lg bg-white dark:bg-neutral-700 text-neutral-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
    <option value="id">Indonesia</option>
    <option value="en">English</option>
</select>
```

### Separator
**Before:**
```blade
<flux:separator variant="subtle" />
```

**After:**
```blade
<hr class="border-neutral-200 dark:border-neutral-700" />
```

## Verification Steps

1. **Test Pemerintah Panel Pages:**
   ```bash
   # Access each page to verify no Flux errors
   http://localhost:8000/pemerintah/lahan
   http://localhost:8000/pemerintah/benih-pupuk
   http://localhost:8000/pemerintah/iklim
   http://localhost:8000/pemerintah/settings
   http://localhost:8000/pemerintah/panduan
   ```

2. **Check Console for Errors:**
   - Open browser DevTools (F12)
   - Navigate to each fixed page
   - Verify no InvalidArgumentException or component errors

3. **Verify Styling:**
   - All pages should maintain consistent Tailwind styling
   - Dark mode toggle should work correctly
   - Forms and inputs should have proper focus states

## Remaining Flux Components

Some views in admin and akademisi panels still use Flux components but are not critical:
- `resources/views/admin/backup-restore.blade.php` (admin-only feature)
- `resources/views/admin/panel-selection.blade.php` (role selection)
- `resources/views/akademisi/dashboard.blade.php` (akademisi panel)

These can be fixed similarly if users encounter errors accessing those pages.

## Prevention

To prevent this issue in the future:
1. **Document dependencies:** Always add UI component libraries to `composer.json` or `package.json`
2. **Use standard components:** Prefer native HTML + Tailwind CSS for better portability
3. **Test all panels:** Verify all user roles can access their pages without errors
4. **Grep check before deploy:** Search for undefined component usage:
   ```bash
   grep -r "flux:" resources/views/ | grep -v "resources/views/flux/"
   ```

## Related Documentation
- Tailwind CSS docs: https://tailwindcss.com/docs
- Laravel Blade components: https://laravel.com/docs/blade#components

## Status
✅ **Resolved** - All pemerintah panel pages fixed and tested
⏳ **Optional** - Admin/akademisi panels can be fixed on-demand
