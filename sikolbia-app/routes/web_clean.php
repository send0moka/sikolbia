<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| SIKOLBIA - Clean 4-Level User Structure Routes
|--------------------------------------------------------------------------
| 1. Admin Level (Pusdatin only): /admin/konsumsi-pangan/
| 2. Government Level (BPN/Kementan/Bappenas): /ketersediaan/pemerintah/
| 3. Academic Level (Researchers): /ketersediaan/akademisi/
| 4. Public Level (No auth needed): /ketersediaan/
*/

// =============================================================================
// LEVEL 4: PUBLIC ACCESS (No Authentication Required)
// =============================================================================

// Homepage - Public Landing Page
Route::get('/', function () {
    return view('public.homepage');
})->name('home');

// Public NBM Information Routes
Route::prefix('ketersediaan')->name('public.')->group(function () {
    
    // Public Homepage - NBM Statistics Dashboard
    Route::get('/', function () {
        return view('public.ketersediaan.dashboard');
    })->name('ketersediaan.dashboard');
    
    // Public Information Pages
    Route::get('tentang', function () {
        return view('public.ketersediaan.tentang');
    })->name('ketersediaan.tentang');
    
    Route::get('metodologi', function () {
        return view('public.ketersediaan.metodologi');
    })->name('ketersediaan.metodologi');
    
    Route::get('laporan-publik', function () {
        return view('public.ketersediaan.laporan-publik');
    })->name('ketersediaan.laporan-publik');
    
    // Registration Pages
    Route::get('pemerintah/pendaftaran', function () {
        return view('public.ketersediaan.registrasi-pemerintah');
    })->name('registrasi.pemerintah');
    
    Route::get('akademisi/pendaftaran', function () {
        return view('public.ketersediaan.registrasi-akademisi');
    })->name('registrasi.akademisi');
    
    // Process registration submissions
    Route::post('pemerintah/pendaftaran', [App\Http\Controllers\RegistrationController::class, 'processGovernment'])->name('registrasi.pemerintah.process');
    Route::post('akademisi/pendaftaran', [App\Http\Controllers\RegistrationController::class, 'processAcademic'])->name('registrasi.akademisi.process');
});

// Public API Routes (No authentication)
Route::prefix('api/v1/publik')->name('api.public.')->group(function () {
    Route::get('statistik/ringkasan', [App\Http\Controllers\Api\PublicController::class, 'statistikRingkasan']);
    Route::get('komoditas/{id}', [App\Http\Controllers\Api\PublicController::class, 'komoditas']);
    Route::get('laporan/terbaru', [App\Http\Controllers\Api\PublicController::class, 'laporanTerbaru']);
});

// =============================================================================
// LEVEL 3: ACADEMIC ACCESS (Researchers - Limited Authentication)
// =============================================================================

Route::middleware(['auth', 'role:akademisi'])->prefix('ketersediaan/akademisi')->name('akademisi.')->group(function () {
    
    // Academic Dashboard
    Route::get('/', function () {
        return view('akademisi.dashboard');
    })->name('dashboard');
    
    // Research Tools
    Route::get('data-historis', function () {
        return view('akademisi.data-historis');
    })->name('data-historis');
    
    Route::get('analisis-statistik', function () {
        return view('akademisi.analisis-statistik');
    })->name('analisis-statistik');
    
    Route::get('prediksi-terbatas', function () {
        return view('akademisi.prediksi-terbatas');
    })->name('prediksi-terbatas');
    
    Route::get('eksport-data', function () {
        return view('akademisi.eksport-data');
    })->name('eksport-data');
});

// Academic API Routes
Route::middleware(['auth', 'role:akademisi'])->prefix('api/v1/akademisi')->name('api.akademisi.')->group(function () {
    Route::get('data-historis', [App\Http\Controllers\Api\AkademisiController::class, 'dataHistoris']);
    Route::post('eksport-penelitian', [App\Http\Controllers\Api\AkademisiController::class, 'eksportPenelitian']);
    Route::post('prediksi-terbatas', [App\Http\Controllers\Api\AkademisiController::class, 'prediksiTerbatas']);
});

// =============================================================================
// LEVEL 2: GOVERNMENT ACCESS (BPN/Kementan/Bappenas - Full Data Access)
// =============================================================================

Route::middleware(['auth', 'role:pemerintah'])->prefix('ketersediaan/pemerintah')->name('pemerintah.')->group(function () {
    
    // Government Dashboard
    Route::get('/', function () {
        return view('pemerintah.dashboard');
    })->name('dashboard');
    
    // Advanced Analytics
    Route::get('prediksi-lengkap', function () {
        return view('pemerintah.prediksi-lengkap');
    })->name('prediksi-lengkap');
    
    Route::get('analisis-shap', function () {
        return view('pemerintah.analisis-shap');
    })->name('analisis-shap');
    
    Route::get('laporan-kebijakan', function () {
        return view('pemerintah.laporan-kebijakan');
    })->name('laporan-kebijakan');
    
    Route::get('monitoring-real-time', function () {
        return view('pemerintah.monitoring-real-time');
    })->name('monitoring-real-time');
    
    Route::get('eksport-lengkap', function () {
        return view('pemerintah.eksport-lengkap');
    })->name('eksport-lengkap');
});

// Government API Routes
Route::middleware(['auth', 'role:pemerintah'])->prefix('api/v1/pemerintah')->name('api.pemerintah.')->group(function () {
    Route::post('prediksi-lengkap', [App\Http\Controllers\Api\PemerintahController::class, 'prediksiLengkap']);
    Route::post('analisis-shap', [App\Http\Controllers\Api\PemerintahController::class, 'analisisShap']);
    Route::post('eksport-lengkap', [App\Http\Controllers\Api\PemerintahController::class, 'eksportLengkap']);
    Route::get('monitoring-real-time', [App\Http\Controllers\Api\PemerintahController::class, 'monitoringRealTime']);
});

// =============================================================================
// LEVEL 1: ADMIN ACCESS (Pusdatin Only - Full System Control)
// =============================================================================

Route::middleware(['auth', 'role:admin'])->prefix('admin/konsumsi-pangan')->name('admin.')->group(function () {
    
    // Admin Main Dashboard
    Route::get('/', function () {
        return view('admin.dashboard');
    })->name('dashboard');
    
    // User Management
    Route::get('kelola-pengguna', function () {
        return view('admin.kelola-pengguna');
    })->name('kelola-pengguna');
    
    Route::get('persetujuan-pendaftaran', function () {
        return view('admin.persetujuan-pendaftaran');
    })->name('persetujuan-pendaftaran');
    
    // Data Management NBM
    Route::get('kelola-data-nbm', function () {
        return view('admin.kelola-data-nbm');
    })->name('kelola-data-nbm');
    
    Route::get('validasi-data', function () {
        return view('admin.validasi-data');
    })->name('validasi-data');
    
    Route::get('impor-data', function () {
        return view('admin.impor-data');
    })->name('impor-data');
    
    // AI Model Management
    Route::get('kelola-model-ai', function () {
        return view('admin.kelola-model-ai');
    })->name('kelola-model-ai');
    
    Route::get('monitoring-model', function () {
        return view('admin.monitoring-model');
    })->name('monitoring-model');
    
    Route::get('training-model', function () {
        return view('admin.training-model');
    })->name('training-model');
    
    // System Configuration
    Route::get('konfigurasi-sistem', function () {
        return view('admin.konfigurasi-sistem');
    })->name('konfigurasi-sistem');
    
    Route::get('log-aktivitas', function () {
        return view('admin.log-aktivitas');
    })->name('log-aktivitas');
});

// Admin API Routes
Route::middleware(['auth', 'role:admin'])->prefix('api/v1/admin')->name('api.admin.')->group(function () {
    // User Management APIs
    Route::apiResource('pengguna', App\Http\Controllers\Api\Admin\PenggunaController::class);
    Route::post('persetujuan-pendaftaran/{id}', [App\Http\Controllers\Api\Admin\PenggunaController::class, 'approveRegistration']);
    
    // Data Management APIs
    Route::apiResource('data-nbm', App\Http\Controllers\Api\Admin\DataNbmController::class);
    Route::post('impor-data-nbm', [App\Http\Controllers\Api\Admin\DataNbmController::class, 'importData']);
    Route::post('validasi-data-nbm/{id}', [App\Http\Controllers\Api\Admin\DataNbmController::class, 'validateData']);
    
    // AI Model Management APIs
    Route::get('model-ai/status', [App\Http\Controllers\Api\Admin\ModelAiController::class, 'status']);
    Route::post('model-ai/retrain', [App\Http\Controllers\Api\Admin\ModelAiController::class, 'retrain']);
    Route::get('model-ai/monitoring', [App\Http\Controllers\Api\Admin\ModelAiController::class, 'monitoring']);
    
    // System Configuration APIs
    Route::get('log-aktivitas', [App\Http\Controllers\Api\Admin\LogController::class, 'index']);
    Route::get('statistik-sistem', [App\Http\Controllers\Api\Admin\SystemController::class, 'statistik']);
});

// =============================================================================
// AUTHENTICATION ROUTES
// =============================================================================

require __DIR__.'/auth.php';