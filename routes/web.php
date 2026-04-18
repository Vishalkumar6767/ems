<?php

use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;

// Auth
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Dashboard redirect based on role
Route::middleware('auth')->group(function () {
    Route::get('/', [LoginController::class, 'dashboard']);
    Route::get('/dashboard', [LoginController::class, 'dashboard'])->name('dashboard');
});

// ─── Super Admin ─────────────────────────────────────────────
Route::middleware(['auth', 'role:super_admin'])
    ->prefix('super-admin')
    ->name('superadmin.')
    ->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\SuperAdmin\DashboardController::class, 'index'])->name('dashboard');

        // Companies (Factories)
        Route::resource('companies', \App\Http\Controllers\SuperAdmin\CompanyController::class);

        // Users (Admins & Employees)
        Route::resource('users', \App\Http\Controllers\SuperAdmin\UserController::class)->except('show');

        // All Workers
        Route::get('/workers', [\App\Http\Controllers\SuperAdmin\UserController::class, 'workers'])->name('workers.index');
        Route::get('/workers/{worker}', [\App\Http\Controllers\SuperAdmin\UserController::class, 'showWorker'])->name('workers.show');

        // Attendance Reports
        Route::get('/attendance/report', [\App\Http\Controllers\SuperAdmin\AttendanceController::class, 'report'])->name('attendance.report');
        Route::get('/attendance/export', [\App\Http\Controllers\SuperAdmin\AttendanceController::class, 'export'])->name('attendance.export');
    });

// ─── Company Admin ───────────────────────────────────────────
Route::middleware(['auth', 'role:company_admin'])
    ->prefix('admin')
    ->name('companyadmin.')
    ->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\CompanyAdmin\DashboardController::class, 'index'])->name('dashboard');

        // Floors
        Route::resource('floors', \App\Http\Controllers\CompanyAdmin\FloorController::class);

        // Workers
        Route::resource('workers', \App\Http\Controllers\CompanyAdmin\WorkerController::class);

        // Attendance
        Route::get('/attendance/mark', [\App\Http\Controllers\CompanyAdmin\AttendanceController::class, 'markForm'])->name('attendance.mark');
        Route::post('/attendance/mark', [\App\Http\Controllers\CompanyAdmin\AttendanceController::class, 'store'])->name('attendance.store');
        Route::get('/attendance/import', [\App\Http\Controllers\CompanyAdmin\AttendanceController::class, 'importForm'])->name('attendance.import');
        Route::post('/attendance/import', [\App\Http\Controllers\CompanyAdmin\AttendanceController::class, 'import'])->name('attendance.import.store');
        Route::get('/attendance/report', [\App\Http\Controllers\CompanyAdmin\AttendanceController::class, 'report'])->name('attendance.report');
        Route::get('/attendance/export', [\App\Http\Controllers\CompanyAdmin\AttendanceController::class, 'export'])->name('attendance.export');
    });

// ─── Employee ────────────────────────────────────────────────
Route::middleware(['auth', 'role:employee'])
    ->prefix('employee')
    ->name('employee.')
    ->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\Employee\DashboardController::class, 'index'])->name('dashboard');
        Route::get('/attendance', [\App\Http\Controllers\Employee\DashboardController::class, 'attendance'])->name('attendance');
    });
