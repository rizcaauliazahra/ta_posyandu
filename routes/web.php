<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\MeasurementController as AdminMeasurementController;
use App\Http\Controllers\Admin\RecommendationController;
use App\Http\Controllers\Admin\StandardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\User\DashboardController as UserDashboardController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/pilih-role');

Route::middleware('guest')->group(function () {
    Route::get('/pilih-role', [AuthController::class, 'chooseRole'])->name('role.choose');
    Route::get('/login', [AuthController::class, 'chooseRole'])->name('login');
    Route::get('/login/{role}', [AuthController::class, 'showLogin'])->name('login.role');
    Route::post('/login', [AuthController::class, 'login'])->name('login.store');
    Route::get('/register', [AuthController::class, 'chooseRole'])->name('register');
    Route::get('/register/{role}', [AuthController::class, 'showRegister'])->name('register.role');
    Route::post('/register', [AuthController::class, 'register'])->name('register.store');
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::post('/dashboard/update-schedule', [AdminDashboardController::class, 'updateSchedule'])->name('dashboard.update-schedule');
    Route::resource('users', UserController::class)->except('show');
    Route::resource('measurement', AdminMeasurementController::class)->except('show');
    Route::get('pantau-anak', [AdminMeasurementController::class, 'pantauIndex'])->name('pantau-anak.index');
    Route::get('pantau-anak/{child}', [AdminMeasurementController::class, 'pantau'])->name('pantau-anak');
    Route::get('pantau-anak/{child}/grafik', [AdminMeasurementController::class, 'grafik'])->name('pantau-anak.grafik');
    Route::get('pantau-anak/{child}/riwayat', [AdminMeasurementController::class, 'riwayat'])->name('pantau-anak.riwayat');
    Route::get('pantau-anak/{child}/latest', [AdminMeasurementController::class, 'latest'])->name('pantau-anak.latest');
    Route::post('pantau-anak/{child}/save-live', [AdminMeasurementController::class, 'saveLive'])->name('pantau-anak.save-live');
    Route::post('pantau-anak/{child}/send-recommendation', [AdminMeasurementController::class, 'sendRecommendation'])->name('pantau-anak.send-recommendation');
    Route::patch('measurement/{measurement}/recommendation', [AdminMeasurementController::class, 'updateRecommendation'])->name('measurement.update-recommendation');
    Route::resource('recommendations', RecommendationController::class)->except('show');
    Route::get('standards/{type}', [StandardController::class, 'index'])->name('standards.index');
    Route::get('standards/{type}/create', [StandardController::class, 'create'])->name('standards.create');
    Route::post('standards/{type}', [StandardController::class, 'store'])->name('standards.store');
    Route::get('standards/{type}/{id}/edit', [StandardController::class, 'edit'])->name('standards.edit');
    Route::put('standards/{type}/{id}', [StandardController::class, 'update'])->name('standards.update');
    Route::delete('standards/{type}/{id}', [StandardController::class, 'destroy'])->name('standards.destroy');
    Route::get('tabel-gizi', [\App\Http\Controllers\Admin\NutritionTableController::class, 'index'])->name('tabel-gizi.index');
});

Route::middleware(['auth', 'role:user'])->prefix('pengguna')->name('user.')->group(function () {
    Route::get('/dashboard', UserDashboardController::class)->name('dashboard');
    Route::get('/dashboard/latest', [UserDashboardController::class, 'latest'])->name('dashboard.latest');

    Route::get('/profil', [UserDashboardController::class, 'profil'])->name('profil');
    Route::put('/profil', [UserDashboardController::class, 'updateProfil'])->name('profil.update');
    Route::get('/grafik', [UserDashboardController::class, 'grafik'])->name('grafik');
    Route::get('/riwayat', [UserDashboardController::class, 'riwayat'])->name('riwayat');
    Route::get('/tabel-gizi', [\App\Http\Controllers\Admin\NutritionTableController::class, 'index'])->name('tabel-gizi');
});

Route::middleware('auth')->group(function () {
    Route::get('/export/riwayat.csv', [ExportController::class, 'csv'])->name('export.csv');
    Route::get('/export/riwayat.xls', [ExportController::class, 'excel'])->name('export.excel');
    Route::get('/export/riwayat.pdf', [ExportController::class, 'pdf'])->name('export.pdf');
});
