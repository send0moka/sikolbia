<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Public\KetersediaanController;
use Livewire\Volt\Volt;

// Landing Page Routes
Route::get('/', function () {
    return view('homepage');
})->name('home');

// Public NBM Information Routes  
Route::prefix('ketersediaan')->name('public.ketersediaan.')->group(function () {
    Route::get('dashboard-publik', [KetersediaanController::class, 'dashboard'])->name('dashboard');
    Route::get('laporan-publik', [KetersediaanController::class, 'laporanPublik'])->name('laporan-publik');
    Route::get('tentang-nbm', [KetersediaanController::class, 'tentang'])->name('tentang-nbm');
    Route::get('metodologi-nbm', [KetersediaanController::class, 'metodologi'])->name('metodologi');
    
    // API for public dashboard data
    Route::get('api/dashboard-data', [KetersediaanController::class, 'apiDashboardData'])->name('api.dashboard-data');
});

// Public Registration Routes (for access upgrade)
Route::prefix('registrasi')->name('public.registrasi.')->group(function () {
    Route::get('pemerintah', [App\Http\Controllers\RegistrasiAksesController::class, 'showFormPemerintah'])->name('pemerintah');
    Route::get('akademisi', [App\Http\Controllers\RegistrasiAksesController::class, 'showFormAkademisi'])->name('akademisi');
    Route::post('proses', [App\Http\Controllers\RegistrasiAksesController::class, 'proses'])->name('proses');
    
    // Resubmit with token
    Route::get('resubmit/{token}', [App\Http\Controllers\RegistrasiAksesController::class, 'showResubmitForm'])->name('resubmit');
    Route::post('resubmit/{token}', [App\Http\Controllers\RegistrasiAksesController::class, 'processResubmit'])->name('resubmit.process');
    
    // Check status
    Route::get('check-status', [App\Http\Controllers\RegistrasiAksesController::class, 'checkStatus'])->name('check-status');
    
    // Upload dokumen tambahan
    Route::get('upload-dokumen/{id}', [App\Http\Controllers\UploadDokumenController::class, 'showForm'])->name('upload-dokumen');
    Route::post('upload-dokumen/{id}', [App\Http\Controllers\UploadDokumenController::class, 'upload'])->name('upload-dokumen.submit');
});

// ADMIN ROUTES - LEVEL 1 ACCESS (PUSDATIN ONLY)  
// =============================================

// Admin Panel Selection Route - setelah login - HANYA UNTUK ADMIN/SUPERADMIN
Route::middleware(['auth', 'verified', 'admin.only'])->group(function () {
    // Main dashboard route
    Route::get('/admin/konsumsi-pangan', function () {
        return view('dashboard');
    })->name('dashboard');

    // Block only the old /dashboard URL
    Route::get('/admin/konsumsi-pangan/dashboard', function () {
        abort(404);
    });

    Route::get('/admin', function () {
        return view('admin.panel-selection');
    })->name('admin.panel-selection');
});

// PEMERINTAH ROUTES - PUBLIC USER ACCESS (READ-ONLY)
// ===================================================
Route::middleware(['auth', 'verified', 'role:pemerintah'])->prefix('pemerintah')->name('pemerintah.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Pemerintah\PemerintahDashboardController::class, 'index'])->name('dashboard');
    
    // Konsumsi Pangan & NBM
    Route::get('/laporan-nbm', [App\Http\Controllers\Pemerintah\PemerintahController::class, 'laporanNbm'])->name('laporan-nbm');
    Route::post('/laporan-nbm/filter', [App\Http\Controllers\Pemerintah\PemerintahController::class, 'filterLaporanNbm'])->name('laporan-nbm.filter');
    Route::get('/laporan-nbm/export/excel', [App\Http\Controllers\Pemerintah\PemerintahController::class, 'exportExcelNbm'])->name('laporan-nbm.export.excel');
    Route::get('/laporan-nbm/export/pdf', [App\Http\Controllers\Pemerintah\PemerintahController::class, 'exportPdfNbm'])->name('laporan-nbm.export.pdf');
    Route::get('/api/komoditi', [App\Http\Controllers\Pemerintah\PemerintahController::class, 'getKomoditi'])->name('api.komoditi');
    
    Route::get('/prediksi-nbm', [App\Http\Controllers\Pemerintah\PemerintahController::class, 'prediksiNbm'])->name('prediksi-nbm');
    Route::post('/prediksi-nbm/run', [App\Http\Controllers\Pemerintah\PemerintahController::class, 'runPrediksi'])->name('prediksi-nbm.run');
    
    // Pertanian
    Route::get('/lahan', [App\Http\Controllers\Pemerintah\PemerintahController::class, 'lahan'])->name('lahan');
    Route::post('/lahan/filter', [App\Http\Controllers\Pemerintah\PemerintahController::class, 'filterLahan'])->name('lahan.filter');
    Route::get('/lahan/variabels/{topik}', [App\Http\Controllers\Pemerintah\PemerintahController::class, 'getVariabelsByTopik'])->name('lahan.variabels');
    Route::post('/lahan/export', [App\Http\Controllers\Pemerintah\PemerintahController::class, 'exportLahan'])->name('lahan.export');
    
    Route::get('/benih-pupuk', [App\Http\Controllers\Pemerintah\PemerintahController::class, 'benihPupuk'])->name('benih-pupuk');
    Route::get('/iklim', [App\Http\Controllers\Pemerintah\PemerintahController::class, 'iklim'])->name('iklim');
    
    // Settings & Profile
    Route::get('/profile', [App\Http\Controllers\Pemerintah\PemerintahController::class, 'profile'])->name('profile');
    Route::get('/settings', [App\Http\Controllers\Pemerintah\PemerintahController::class, 'settings'])->name('settings');
    Route::get('/panduan', [App\Http\Controllers\Pemerintah\PemerintahController::class, 'panduan'])->name('panduan');
});

// AKADEMISI ROUTES - PUBLIC USER ACCESS (READ-ONLY)
// ==================================================
Route::middleware(['auth', 'verified', 'role:akademisi'])->prefix('akademisi')->name('akademisi.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Akademisi\AkademisiDashboardController::class, 'index'])->name('dashboard');
    
    // Data Historis NBM
    Route::get('/data-nbm', [App\Http\Controllers\Akademisi\DataNbmController::class, 'index'])->name('data-nbm');
    Route::get('/export-data', [App\Http\Controllers\Akademisi\ExportDataController::class, 'index'])->name('export-data');
    Route::post('/export-data/download', [App\Http\Controllers\Akademisi\ExportDataController::class, 'download'])->name('export-data.download');
    
    // Tools & Visualisasi
    Route::get('/grafik-statistik', [App\Http\Controllers\Akademisi\GrafikStatistikController::class, 'index'])->name('grafik-statistik');
    Route::get('/filter-query', [App\Http\Controllers\Akademisi\FilterQueryController::class, 'index'])->name('filter-query');
    
    // Dokumentasi
    Route::get('/panduan-sitasi', [App\Http\Controllers\Akademisi\PanduanSitasiController::class, 'index'])->name('panduan-sitasi');
    Route::get('/data-dictionary', [App\Http\Controllers\Akademisi\DataDictionaryController::class, 'index'])->name('data-dictionary');
});

// Ketersediaan Routes
Route::prefix('ketersediaan')->name('ketersediaan.')->group(function () {
    Route::get('konsep-metode', function () {
        return view('ketersediaan.konsep-metode');
    })->name('konsep-metode');
    
    Route::get('laporan-nbm', function () {
        // Order by 'kode' so the dropdown matches the canonical order in the kelompok table (01..11)
        $kelompokOptions = App\Models\Kelompok::aktif()->orderBy('kode')->get(['kode', 'nama']);
        return view('ketersediaan.laporan-nbm', ['kelompokOptions' => $kelompokOptions]);
    })->name('laporan-nbm');
    
    // AJAX endpoint for laporan NBM data
    Route::get('api/laporan-nbm', [App\Http\Controllers\Ketersediaan\LaporanNbmController::class, 'query'])->name('laporan-nbm.api');

    // Return komoditi list for a given kelompok code (AJAX)
    Route::get('api/komoditi', function (\Illuminate\Http\Request $request) {
        $kodeKelompok = $request->get('kode_kelompok');
        if (!$kodeKelompok) {
            return response()->json(['data' => []]);
        }
        // komoditi table uses kode_kelompok + kode_komoditi pattern (e.g. 01 -> 0101,0102...).
        // Do not filter by a non-existent `status_aktif` column here. Return komoditi for the kelompok.
        $kom = App\Models\Komoditi::where('kode_kelompok', $kodeKelompok)
            ->orderBy('kode_komoditi')
            ->get(['kode_komoditi', 'nama']);
        $payload = $kom->map(function ($k) {
            return [
                'value' => $k->kode_komoditi,
                // Only return the komoditi name as label (user requested no kode prefix)
                'label' => $k->nama
            ];
        })->values();
        return response()->json(['data' => $payload]);
    })->name('ketersediaan.api.komoditi');
    
    Route::get('dashboard-komoditas', function () {
        return view('ketersediaan.dashboard-komoditas');
    })->name('dashboard-komoditas');
    
    Route::get('konsep-transaksi-nbm', function () {
        return view('ketersediaan.konsep-transaksi-nbm');
    })->name('konsep-transaksi-nbm');
});

// Konsumsi Routes  
Route::prefix('konsumsi')->name('konsumsi.')->group(function () {
    Route::get('konsep-metode', function () {
        return view('konsumsi.konsep-metode');
    })->name('konsep-metode');
    
    Route::get('konsep-transaksi-susenas', function () {
        return view('konsumsi.konsep-transaksi-susenas');
    })->name('konsep-transaksi-susenas');
    
    Route::get('laporan-susenas', function () {
        return view('konsumsi.laporan-susenas');
    })->name('laporan-susenas');
    
    Route::get('per-kapita-seminggu', function () {
        return view('konsumsi.per-kapita-seminggu');
    })->name('per-kapita-seminggu');
    
    Route::get('per-kapita-setahun', function () {
        return view('konsumsi.per-kapita-setahun');
    })->name('per-kapita-setahun');

    // Public API for Laporan Susenas (used by the public blade via Alpine.js)
    Route::prefix('api')->name('api.')->group(function () {
        Route::get('kelompok-bps', [App\Http\Controllers\Konsumsi\LaporanSusenasController::class, 'kelompok'])->name('kelompok-bps');
        Route::get('komoditi-bps', [App\Http\Controllers\Konsumsi\LaporanSusenasController::class, 'komoditi'])->name('komoditi-bps');
        Route::get('laporan-susenas', [App\Http\Controllers\Konsumsi\LaporanSusenasController::class, 'query'])->name('laporan-susenas');
        Route::get('years', [App\Http\Controllers\Konsumsi\LaporanSusenasController::class, 'years'])->name('years');
        Route::get('stats', [App\Http\Controllers\Konsumsi\LaporanSusenasController::class, 'stats'])->name('stats');
    });
});

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});

// Admin Routes 
Route::middleware(['auth'])->prefix('admin/konsumsi-pangan')->name('admin.')->group(function () {
    // Enhanced Prediction Dashboard with SHAP Analysis - accessible to all authenticated users
    Route::get('prediction-dashboard', function () {
        return view('admin.prediction-dashboard-simple');
    })->name('prediction-dashboard');
    
    // User management - hanya untuk superadmin
    Route::middleware(['permission:view users'])->group(function () {
        Route::view('users', 'admin.users')->name('users');
    });
    
    // Kelompok management - admin dan superadmin bisa akses
    Route::middleware(['permission:view kelompok'])->group(function () {
        Route::view('kelompok', 'admin.kelompok')->name('kelompok');
    });
    
    // Komoditi management - admin dan superadmin bisa akses
    Route::middleware(['permission:view komoditi'])->group(function () {
        Route::view('komoditi', 'admin.komoditi')->name('komoditi');
    });
    
    // Transaksi NBM management - admin dan superadmin bisa akses
    Route::middleware(['permission:view transaksi_nbm'])->group(function () {
        Route::view('transaksi-nbm', 'admin.transaksi-nbm')->name('transaksi-nbm');
    });
    
    // Backup & Restore - hanya untuk superadmin
    Route::middleware(['permission:view users'])->group(function () {
        Route::get('backup-restore', [App\Http\Controllers\BackupRestoreController::class, 'index'])->name('backup-restore');
        Route::post('backup-restore/backup', [App\Http\Controllers\BackupRestoreController::class, 'backup'])->name('backup-restore.backup');
        Route::post('backup-restore/restore', [App\Http\Controllers\BackupRestoreController::class, 'restore'])->name('backup-restore.restore');
        Route::get('backup-restore/download/{filename}', [App\Http\Controllers\BackupRestoreController::class, 'download'])->name('backup-restore.download');
        Route::post('backup-restore/delete', [App\Http\Controllers\BackupRestoreController::class, 'delete'])->name('backup-restore.delete');
    });
});

// Lahan Routes - HANYA UNTUK ADMIN/SUPERADMIN
Route::middleware(['auth', 'role:admin|superadmin'])->prefix('admin/lahan')->name('admin.lahan.')->group(function () {
    Route::get('/', function () {
        return view('admin.lahan.dashboard');
    })->name('dashboard');
    
    // Lahan management CRUD
    Route::view('kelola', 'admin.lahan')->name('kelola');
    
    // Lahan reference tables CRUD
    Route::view('topik', 'admin.lahan-topik')->name('topik');
    Route::view('variabel', 'admin.lahan-variabel')->name('variabel');
    Route::view('klasifikasi', 'admin.lahan-klasifikasi')->name('klasifikasi');
    
    Route::view('data', 'admin.lahan.data')->name('data');
    Route::view('maps', 'admin.lahan.maps')->name('maps');
    Route::view('categories', 'admin.lahan.categories')->name('categories');
    Route::view('inventory', 'admin.lahan.inventory')->name('inventory');
    Route::view('statistics', 'admin.lahan.statistics')->name('statistics');
    Route::view('reports', 'admin.lahan.reports')->name('reports');
    Route::view('analysis', 'admin.lahan.analysis')->name('analysis');
    Route::view('settings', 'admin.lahan.settings')->name('settings');
});

// Iklim OptDPI Routes - HANYA UNTUK ADMIN/SUPERADMIN
Route::middleware(['auth', 'role:admin|superadmin'])->prefix('admin/iklim-opt-dpi')->name('admin.iklim-opt-dpi.')->group(function () {
    Route::view('/', 'admin.iklim-opt-dpi.dashboard-wrapper')->name('dashboard');
    
    // Iklim Opt DPI management CRUD
    Route::view('kelola', 'admin.iklimoptdpi')->name('kelola');
    
    // Iklim Opt DPI reference tables CRUD
    Route::view('topik', 'admin.iklimoptdpi-topik')->name('topik');
    Route::view('variabel', 'admin.iklimoptdpi-variabel')->name('variabel');
    Route::view('klasifikasi', 'admin.iklimoptdpi-klasifikasi')->name('klasifikasi');
    
    Route::view('maps', 'admin.iklim-opt-dpi.maps-wrapper')->name('maps');
    Route::view('monitoring', 'admin.iklim-opt-dpi.monitoring-wrapper')->name('monitoring');
    Route::view('forecasting', 'admin.iklim-opt-dpi.forecasting-wrapper')->name('forecasting');
    Route::view('reports', 'admin.iklim-opt-dpi.reports-wrapper')->name('reports');
});

// Panel Daftar Alamat Routes - HANYA UNTUK ADMIN/SUPERADMIN
Route::middleware(['auth', 'role:admin|superadmin'])->prefix('admin/daftar-alamat')->name('admin.daftar-alamat.')->group(function () {
    Route::get('/', function () {
        return view('admin.daftar-alamat.dashboard');
    })->name('dashboard');

    Route::view('data', 'admin.daftar-alamat.data')->name('data');
    Route::view('maps', 'admin.daftar-alamat.maps')->name('maps');
    Route::view('reports', 'admin.daftar-alamat.reports')->name('reports');
    Route::view('settings', 'admin.daftar-alamat.settings')->name('settings');
    
    // Export routes
    Route::get('export/excel', [App\Http\Controllers\Admin\DaftarAlamatController::class, 'exportExcel'])->name('export.excel');
    Route::get('export/csv', [App\Http\Controllers\Admin\DaftarAlamatController::class, 'exportCsv'])->name('export.csv');
    Route::get('export/pdf', [App\Http\Controllers\Admin\DaftarAlamatController::class, 'exportPdf'])->name('export.pdf');
    
    // Save route for traditional form submission
    Route::post('save', [App\Http\Controllers\Admin\DaftarAlamatController::class, 'save'])->name('save');
});

// Panel Benih Pupuk Routes - HANYA UNTUK ADMIN/SUPERADMIN
Route::middleware(['auth', 'role:admin|superadmin'])->prefix('admin/benih-pupuk')->name('admin.benih-pupuk.')->group(function () {
    Route::get('/', function () {
        return view('admin.panel-benih-pupuk.dashboard');
    })->name('dashboard');
    
    Route::view('data', 'admin.panel-benih-pupuk.data')->name('data');
    
    Route::view('import', 'admin.panel-benih-pupuk.import')->name('import');
    Route::post('preview', [App\Http\Controllers\Admin\BenihPupukImportController::class, 'preview'])->name('preview');
    Route::get('cancel-preview', [App\Http\Controllers\Admin\BenihPupukImportController::class, 'cancelPreview'])->name('cancel-preview');
    Route::post('import', [App\Http\Controllers\Admin\BenihPupukImportController::class, 'import'])->name('admin.benih-pupuk.import');
    
    // Template download route
    Route::get('download-template', [App\Http\Controllers\Admin\BenihPupukExportController::class, 'downloadTemplate'])->name('download-template');
    
    // Export routes - unified export (Excel). CSV/PDF can be added later if needed.
    Route::post('export/excel', [App\Http\Controllers\PertanianReportExportController::class, 'export'])->defaults('moduleType','benih-pupuk')->name('export.excel');
});

// Export download route for Livewire - OUTSIDE auth middleware for download functionality
Route::get('admin/benih-pupuk/export/download', [App\Http\Controllers\Admin\BenihPupukExportController::class, 'download']);
Route::get('admin/benih-pupuk/export/test', function() {
    return response()->json([
        'message' => 'Route works!',
        'time' => now(),
        'session_data' => session()->get('export_data')
    ]);
});
Route::get('admin/benih-pupuk/export/set-session', function() {
    session()->put('export_data', [
        'filename' => 'test-export-' . now()->format('Ymd-His') . '.xlsx',
        'format' => 'xlsx'
    ]);
    return response()->json([
        'message' => 'Session data set!',
        'session_data' => session()->get('export_data')
    ]);
});
Route::get('admin/benih-pupuk/export/simple-test', function() {
    return 'Simple test route works!';
});

// Susenas Routes (accessible by admin and superadmin with proper permissions)
Route::middleware(['auth', 'role:admin|superadmin', 'permission:view kelompokbps|view komoditibps|view susenas'])->prefix('admin/konsumsi-pangan')->name('admin.')->group(function () {
    Route::view('kelompok-bps', 'admin.kelompok-bps')->name('kelompok-bps');
    Route::view('komoditi-bps', 'admin.komoditi-bps')->name('komoditi-bps');
    Route::view('susenas', 'admin.susenas')->name('susenas');
});

// NBM Prediction Routes - HANYA UNTUK ADMIN/SUPERADMIN
Route::middleware(['auth', 'role:admin|superadmin'])->prefix('admin/konsumsi-pangan')->name('admin.')->group(function () {
    Route::get('prediksi-nbm', function () {
        return view('prediksi.index');
    })->name('prediksi-nbm');
    
    // Registrasi Akses Management
    Route::view('registrasi-akses', 'admin.registrasi-akses')->name('registrasi-akses');
    Route::get('registrasi-akses/download/{registrasi}/{file}', [App\Http\Controllers\RegistrasiAksesController::class, 'downloadFile'])->name('registrasi-akses.download');
    
    // User Management - Enhanced admin actions
    Route::view('kelola-pengguna', 'admin.user-management')->name('kelola-pengguna');
    
    // ML Model Dashboard - Enhanced monitoring and evaluation interface
    Route::middleware(['permission:view dashboard'])->get('ml-dashboard', function () {
        return view('admin.ml-dashboard');
    })->name('ml-dashboard');
    
    Route::get('prediksi-nbm/api/health', [App\Http\Controllers\NBMPredictionController::class, 'health'])->name('prediksi-nbm.api.health');
    Route::post('prediksi-nbm/api/predict', [App\Http\Controllers\NBMPredictionController::class, 'predict'])->name('prediksi-nbm.api.predict');
    Route::get('prediksi-nbm/api/stats', [App\Http\Controllers\NBMPredictionController::class, 'modelStats'])->name('prediksi-nbm.api.stats');
    
    // Concept pages
    Route::get('konsep-transaksi-nbm', function () {
        return view('ketersediaan.konsep-transaksi-nbm');
    })->name('konsep-transaksi-nbm');
    
    Route::get('konsep-transaksi-susenas', function () {
        return view('konsumsi.konsep-transaksi-susenas');
    })->name('konsep-transaksi-susenas');
});

// Unified Pertanian Report Routes (Public Access)
Route::prefix('pertanian')->name('pertanian.')->group(function () {
    // Dynamic report page (moduleType: lahan | benih-pupuk | iklim-opt-dpi)
    Route::get('{moduleType}', [App\Http\Controllers\PertanianReportController::class, 'index'])
        ->whereIn('moduleType', ['lahan','benih-pupuk','iklim-opt-dpi'])
        ->name('report');

    // Dynamic filter endpoint (expects JSON selections + config, returns headers & rows)
    Route::post('{moduleType}/filter', [App\Http\Controllers\PertanianReportController::class, 'filter'])
        ->whereIn('moduleType', ['lahan','benih-pupuk','iklim-opt-dpi'])
        ->name('report.filter');

    // Existing daftar-alamat page retained
    Route::get('daftar-alamat', function () { return view('pertanian.daftar-alamat'); })->name('daftar-alamat');

    // Public helper for wilayah tree used by chatbot and report UI
    Route::get('wilayahs', [App\Http\Controllers\PertanianReportController::class, 'wilayahs'])->name('wilayahs');
});

// Unified export route and legacy aliases
Route::prefix('pertanian')->name('pertanian.')->group(function () {
    Route::post('{moduleType}/export', [App\Http\Controllers\PertanianReportExportController::class, 'export'])
        ->whereIn('moduleType', ['lahan','benih-pupuk','iklim-opt-dpi'])
        ->name('report.export');
});

// API Routes for Dashboard Komoditas (without web middleware)
Route::middleware([])->group(function () {
    Route::prefix('api/dashboard-komoditas')->name('api.dashboard-komoditas.')->group(function () {
        Route::get('commodities', [App\Http\Controllers\DashboardKomoditasController::class, 'getCommoditiesData'])->name('commodities');
        Route::get('summary', [App\Http\Controllers\DashboardKomoditasController::class, 'getSummaryStats'])->name('summary');
        Route::get('groups', [App\Http\Controllers\DashboardKomoditasController::class, 'getGroups'])->name('groups');
    });
    
    // Simple test route
    Route::get('api/test', function () {
        return response()->json(['status' => 'ok', 'message' => 'API is working']);
    });
});

// API Routes for Benih Pupuk (compat layer to unified controller)
Route::prefix('api/benih-pupuk')->name('api.benih-pupuk.')->group(function () {
    // Unified data endpoints
    Route::get('topiks', [App\Http\Controllers\PertanianReportController::class, 'topiks'])->defaults('moduleType','benih-pupuk')->name('topiks');
    // Preserve route shape with {topik} by mapping to query param expected by controller
    Route::get('variabels/{topik}', function (\Illuminate\Http\Request $request, $topik) {
        $request->merge(['topik_id' => $topik]);
        return app(App\Http\Controllers\PertanianReportController::class)->variabels($request, 'benih-pupuk');
    })->name('variabels');
    Route::post('klasifikasis', [App\Http\Controllers\PertanianReportController::class, 'klasifikasis'])->defaults('moduleType','benih-pupuk')->name('klasifikasis');

    // Wilayah helpers (module-agnostic)
    Route::get('wilayahs', [App\Http\Controllers\PertanianReportController::class, 'wilayahs'])->name('wilayahs');
    Route::get('provinces', [App\Http\Controllers\PertanianReportController::class, 'provinces'])->name('provinces');
    Route::get('kabupaten/{province}', [App\Http\Controllers\PertanianReportController::class, 'kabupaten'])->name('kabupaten');

    // Temporal helpers
    Route::get('bulans', [App\Http\Controllers\PertanianReportController::class, 'bulans'])->defaults('moduleType','benih-pupuk')->name('bulans');
    Route::get('years', [App\Http\Controllers\PertanianReportController::class, 'years'])->defaults('moduleType','benih-pupuk')->name('years');

    // Filter/alias for backward compatibility
    Route::post('filter', [App\Http\Controllers\PertanianReportController::class, 'filter'])->defaults('moduleType','benih-pupuk')->name('filter');
    Route::post('search', [App\Http\Controllers\PertanianReportController::class, 'filter'])->defaults('moduleType','benih-pupuk')->name('search');
    // Legacy export now points to unified export
    Route::post('export', [App\Http\Controllers\PertanianReportExportController::class, 'export'])->defaults('moduleType','benih-pupuk')->name('export');
    // Unified sample-data for quick sanity check
    Route::get('sample-data', [App\Http\Controllers\PertanianReportController::class, 'sampleData'])->defaults('moduleType','benih-pupuk')->name('sample-data');
});



// API Routes for Iklim OPT DPI (compat layer)
Route::prefix('api/iklim-opt-dpi')->name('api.iklim-opt-dpi.')->group(function () {
    Route::get('topiks', [App\Http\Controllers\PertanianReportController::class, 'topiks'])->defaults('moduleType','iklim-opt-dpi')->name('topiks');
    Route::get('variabels/{topik}', function (\Illuminate\Http\Request $request, $topik) {
        $request->merge(['topik_id' => $topik]);
        return app(App\Http\Controllers\PertanianReportController::class)->variabels($request, 'iklim-opt-dpi');
    })->name('variabels');
    Route::post('klasifikasis', [App\Http\Controllers\PertanianReportController::class, 'klasifikasis'])->defaults('moduleType','iklim-opt-dpi')->name('klasifikasis');
    Route::get('provinces', [App\Http\Controllers\PertanianReportController::class, 'provinces'])->name('provinces');
    Route::get('years', [App\Http\Controllers\PertanianReportController::class, 'years'])->defaults('moduleType','iklim-opt-dpi')->name('years');
    Route::post('filter', [App\Http\Controllers\PertanianReportController::class, 'filter'])->defaults('moduleType','iklim-opt-dpi')->name('filter');
    Route::post('search', [App\Http\Controllers\PertanianReportController::class, 'filter'])->defaults('moduleType','iklim-opt-dpi')->name('search');
    Route::get('sample-data', [App\Http\Controllers\PertanianReportController::class, 'sampleData'])->defaults('moduleType','iklim-opt-dpi')->name('sample-data');
});

// API Routes for Lahan (compat layer)
Route::prefix('api/lahan')->name('api.lahan.')->group(function () {
    Route::get('topiks', [App\Http\Controllers\PertanianReportController::class, 'topiks'])->defaults('moduleType','lahan')->name('topiks');
    Route::get('variabels/{topik}', function (\Illuminate\Http\Request $request, $topik) {
        $request->merge(['topik_id' => $topik]);
        return app(App\Http\Controllers\PertanianReportController::class)->variabels($request, 'lahan');
    })->name('variabels');
    Route::post('klasifikasis', [App\Http\Controllers\PertanianReportController::class, 'klasifikasis'])->defaults('moduleType','lahan')->name('klasifikasis');
    Route::get('provinces', [App\Http\Controllers\PertanianReportController::class, 'provinces'])->name('provinces');
    Route::get('years', [App\Http\Controllers\PertanianReportController::class, 'years'])->defaults('moduleType','lahan')->name('years');
    Route::post('filter', [App\Http\Controllers\PertanianReportController::class, 'filter'])->defaults('moduleType','lahan')->name('filter');
    Route::get('sample-data', [App\Http\Controllers\PertanianReportController::class, 'sampleData'])->defaults('moduleType','lahan')->name('sample-data');
});

Route::prefix('api')->name('api.')->group(function () {
    Route::get('daftar-alamat/data', [App\Http\Controllers\PublicDaftarAlamatController::class, 'getData'])->name('daftar-alamat.data');
    
    // Prediction Dashboard API Routes
    Route::middleware(['auth'])->group(function () {
        Route::post('predict-nbm', [App\Http\Controllers\Admin\PredictionDashboardController::class, 'predict'])->name('predict-nbm');
        Route::post('predict-nbm/multi-step', [App\Http\Controllers\Admin\PredictionDashboardController::class, 'multiStepPredict'])->name('predict-nbm.multi-step');
        Route::get('health-check', [App\Http\Controllers\Admin\PredictionDashboardController::class, 'healthCheck'])->name('health-check');
        Route::get('prediction-stats', [App\Http\Controllers\Admin\PredictionDashboardController::class, 'getStats'])->name('prediction-stats');
        Route::post('prediction-analytics', [App\Http\Controllers\Admin\PredictionDashboardController::class, 'updateAnalytics'])->name('prediction-analytics');
        
        // Mock endpoints for testing (fallback)
        Route::post('mock/predict-nbm', [App\Http\Controllers\Admin\MockPredictionController::class, 'mockPredict'])->name('mock.predict-nbm');
        Route::get('mock/health-check', [App\Http\Controllers\Admin\MockPredictionController::class, 'mockHealthCheck'])->name('mock.health-check');
        Route::get('mock/prediction-stats', [App\Http\Controllers\Admin\MockPredictionController::class, 'mockStats'])->name('mock.prediction-stats');
    });
});

// Public Chatbot Page (dedicated)
Route::get('/chatbot', function () {
    return view('chatbot.index');
})->name('chatbot.index');

require __DIR__.'/auth.php';