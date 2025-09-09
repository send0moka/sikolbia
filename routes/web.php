<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

// Landing Page Routes
Route::get('/', function () {
    return view('homepage');
})->name('home');

// Admin Panel Selection Route - setelah login
Route::middleware(['auth', 'verified'])->group(function () {
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

// Ketersediaan Routes
Route::prefix('ketersediaan')->name('ketersediaan.')->group(function () {
    Route::get('konsep-metode', function () {
        return view('ketersediaan.konsep-metode');
    })->name('konsep-metode');
    
    Route::get('laporan-nbm', function () {
        return view('ketersediaan.laporan-nbm');
    })->name('laporan-nbm');
    
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
});

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});

// Admin Routes 
Route::middleware(['auth'])->prefix('admin/konsumsi-pangan')->name('admin.')->group(function () {
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
});

// Lahan Routes
Route::middleware(['auth'])->prefix('admin/lahan')->name('admin.lahan.')->group(function () {
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

// Iklim OPT-DPI Routes
Route::middleware(['auth'])->prefix('admin/iklim-opt-dpi')->name('admin.iklim-opt-dpi.')->group(function () {
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

// Panel Daftar Alamat Routes
Route::middleware(['auth'])->prefix('admin/daftar-alamat')->name('admin.daftar-alamat.')->group(function () {
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

// Panel Benih Pupuk Routes
Route::middleware(['auth'])->prefix('admin/benih-pupuk')->name('admin.benih-pupuk.')->group(function () {
    Route::get('/', function () {
        return view('admin.panel-benih-pupuk.dashboard');
    })->name('dashboard');
    
    Route::view('data', 'admin.panel-benih-pupuk.data')->name('data');
    
    // Export routes - using direct names without duplicate prefix
    Route::get('export/excel', [App\Http\Controllers\BenihPupukController::class, 'exportExcel'])->name('export.excel');
    Route::get('export/csv', [App\Http\Controllers\BenihPupukController::class, 'exportCsv'])->name('export.csv');
    Route::get('export/pdf', [App\Http\Controllers\BenihPupukController::class, 'exportPdf'])->name('export.pdf');
});

// Susenas Routes (accessible by both superadmin and admin)
Route::middleware(['auth', 'permission:view kelompokbps|view komoditibps|view susenas'])->prefix('admin/konsumsi-pangan')->name('admin.')->group(function () {
    Route::view('kelompok-bps', 'admin.kelompok-bps')->name('kelompok-bps');
    Route::view('komoditi-bps', 'admin.komoditi-bps')->name('komoditi-bps');
    Route::view('susenas', 'admin.susenas')->name('susenas');
});

// NBM Prediction Routes
Route::middleware(['auth'])->prefix('admin/konsumsi-pangan')->name('admin.')->group(function () {
    Route::get('prediksi-nbm', function () {
        return view('prediksi.index');
    })->name('prediksi-nbm');
    
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

// Benih & Pupuk Routes (Public Access)
Route::prefix('pertanian')->name('pertanian.')->group(function () {
    Route::get('benih-pupuk', [App\Http\Controllers\BenihPupukController::class, 'index'])->name('benih-pupuk');
    Route::get('iklim-opt-dpi', [App\Http\Controllers\IklimOptDpiController::class, 'index'])->name('iklim-opt-dpi');
    Route::get('lahan', [App\Http\Controllers\LahanController::class, 'index'])->name('lahan');
    Route::get('daftar-alamat', function () {
        return view('pertanian.daftar-alamat');
    })->name('daftar-alamat');
});

// API Routes for Benih Pupuk
Route::prefix('api/benih-pupuk')->name('api.benih-pupuk.')->group(function () {
    Route::get('topiks', [App\Http\Controllers\BenihPupukController::class, 'getTopiks'])->name('topiks');
    Route::get('variabels/{topik}', [App\Http\Controllers\BenihPupukController::class, 'getVariabelsByTopik'])->name('variabels');
    Route::post('klasifikasis', [App\Http\Controllers\BenihPupukController::class, 'getKlasifikasiByVariabels'])->name('klasifikasis');
    Route::get('wilayahs', [App\Http\Controllers\BenihPupukController::class, 'getWilayahs'])->name('wilayahs');
    Route::get('provinces', [App\Http\Controllers\BenihPupukController::class, 'getProvinces'])->name('provinces');
    Route::get('kabupaten/{province}', [App\Http\Controllers\BenihPupukController::class, 'getKabupatenByProvince'])->name('kabupaten');
    Route::get('bulans', [App\Http\Controllers\BenihPupukController::class, 'getBulans'])->name('bulans');
    Route::get('years', [App\Http\Controllers\BenihPupukController::class, 'getAvailableYears'])->name('years');
    Route::post('search', [App\Http\Controllers\BenihPupukController::class, 'search'])->name('search');
    Route::post('filter', [App\Http\Controllers\BenihPupukController::class, 'filter'])->name('filter');
    Route::post('export', [App\Http\Controllers\BenihPupukController::class, 'exportExcel'])->name('export');
    Route::get('sample-data', [App\Http\Controllers\BenihPupukController::class, 'getSampleData'])->name('sample-data');
});



// API Routes for Iklim OPT DPI
Route::prefix('api/iklim-opt-dpi')->name('api.iklim-opt-dpi.')->group(function () {
    Route::get('topiks', [App\Http\Controllers\IklimOptDpiController::class, 'getTopiks'])->name('topiks');
    Route::get('variabels/{topik}', [App\Http\Controllers\IklimOptDpiController::class, 'getVariabelsByTopik'])->name('variabels');
    Route::post('klasifikasis', [App\Http\Controllers\IklimOptDpiController::class, 'getKlasifikasiByVariabels'])->name('klasifikasis');
    Route::get('provinces', [App\Http\Controllers\IklimOptDpiController::class, 'getProvinces'])->name('provinces');
    Route::get('years', [App\Http\Controllers\IklimOptDpiController::class, 'getAvailableYears'])->name('years');
    Route::post('filter', [App\Http\Controllers\IklimOptDpiController::class, 'filter'])->name('filter');
    Route::post('search', [App\Http\Controllers\IklimOptDpiController::class, 'search'])->name('search');
});

// API Routes for Lahan
Route::prefix('api/lahan')->name('api.lahan.')->group(function () {
    Route::get('topiks', [App\Http\Controllers\LahanController::class, 'getTopiks'])->name('topiks');
    Route::get('variabels/{topik}', [App\Http\Controllers\LahanController::class, 'getVariabelsByTopik'])->name('variabels');
    Route::post('klasifikasis', [App\Http\Controllers\LahanController::class, 'getKlasifikasiByVariabels'])->name('klasifikasis');
    Route::get('provinces', [App\Http\Controllers\LahanController::class, 'getProvinces'])->name('provinces');
    Route::get('years', [App\Http\Controllers\LahanController::class, 'getAvailableYears'])->name('years');
    Route::post('filter', [App\Http\Controllers\LahanController::class, 'filter'])->name('filter');
});

Route::prefix('api')->name('api.')->group(function () {
    Route::get('daftar-alamat/data', [App\Http\Controllers\PublicDaftarAlamatController::class, 'getData'])->name('daftar-alamat.data');
});

require __DIR__.'/auth.php';