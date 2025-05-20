<?php

use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DefectController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReasonController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth', 'verified', 'active'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    Route::resource('defects', DefectController::class);
    
    Route::get('/analytics', [AnalyticsController::class, 'index'])->name('analytics.index');
    
    Route::middleware(['admin'])->group(function () {
        Route::resource('reasons', ReasonController::class);
        
        Route::resource('departments', DepartmentController::class);
        
        Route::resource('users', UserController::class);
        Route::patch('/users/{user}/toggle-active', [UserController::class, 'toggleActive'])->name('users.toggle-active');
        
        Route::get('/analytics/export-pdf', [AnalyticsController::class, 'exportPdf'])->name('analytics.export-pdf');
        Route::get('/analytics/export-excel', [AnalyticsController::class, 'exportExcel'])->name('analytics.export-excel');
    });
});

require __DIR__.'/auth.php';