<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\HospitalForceController;
use App\Http\Controllers\Admin\DepartmentController;
use App\Http\Controllers\Admin\ViolationTypeController;
use App\Http\Controllers\Admin\LeaveTypeController;
use App\Http\Controllers\Admin\PersonnelController as AdminPersonnelController;
use App\Http\Controllers\Admin\PersonnelLeaveController as AdminPersonnelLeaveController;
use App\Http\Controllers\Admin\ReportController as AdminReportController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin Routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('hospital-forces', HospitalForceController::class);
    Route::resource('departments', DepartmentController::class);
    Route::resource('violation-types', ViolationTypeController::class);
    Route::resource('leave-types', LeaveTypeController::class);
    Route::resource('personnel', AdminPersonnelController::class);

    // Personnel Leaves Routes (Admin)
    Route::post('personnel-leaves/{personnel_leave}/approve', [AdminPersonnelLeaveController::class, 'approve'])->name('personnel-leaves.approve');
    Route::post('personnel-leaves/{personnel_leave}/reject', [AdminPersonnelLeaveController::class, 'reject'])->name('personnel-leaves.reject');
    Route::resource('personnel-leaves', AdminPersonnelLeaveController::class)->only(['index', 'show', 'store']);

    // Reports Routes (Admin)
    Route::prefix('reports')->name('reports.')->group(function() {
        Route::get('daily-eligible-for-leave', [AdminReportController::class, 'dailyEligibleForLeave'])->name('dailyEligibleForLeave');
        Route::get('leave-permit/{personnel_leave}', [AdminReportController::class, 'leavePermitData'])->name('leavePermitData'); // Note: uses {personnel_leave} for route model binding
        Route::get('period-leave-report', [AdminReportController::class, 'periodLeaveReport'])->name('periodLeaveReport');
        Route::get('period-violation-report', [AdminReportController::class, 'periodViolationReport'])->name('periodViolationReport');
    });
});

// Military Affairs Officer Routes
Route::middleware(['auth', 'role:military_affairs_officer'])->prefix('military-affairs')->name('military_affairs.')->group(function () {
    Route::get('personnel', [AdminPersonnelController::class, 'index'])->name('personnel.index'); // Assumes PersonnelController filters by role or this is an overview

    // Personnel Leaves for Military Affairs
    Route::post('personnel-leaves/{personnel_leave}/approve', [AdminPersonnelLeaveController::class, 'approve'])->name('personnel-leaves.approve');
    Route::post('personnel-leaves/{personnel_leave}/reject', [AdminPersonnelLeaveController::class, 'reject'])->name('personnel-leaves.reject');
    Route::resource('personnel-leaves', AdminPersonnelLeaveController::class)->only(['index', 'show', 'store']);

    // Reports for Military Affairs
    Route::prefix('reports')->name('reports.')->group(function() {
        Route::get('leave-permit/{personnel_leave}', [AdminReportController::class, 'leavePermitData'])->name('leavePermitData');
        Route::get('period-leave-report', [AdminReportController::class, 'periodLeaveReport'])->name('periodLeaveReport');
    });
});

// Civilian Affairs Officer Routes
Route::middleware(['auth', 'role:civilian_affairs_officer'])->prefix('civilian-affairs')->name('civilian_affairs.')->group(function () {
    Route::get('personnel', [AdminPersonnelController::class, 'index'])->name('personnel.index'); // Assumes PersonnelController filters by role or this is an overview

    // Personnel Leaves for Civilian Affairs
    Route::post('personnel-leaves/{personnel_leave}/approve', [AdminPersonnelLeaveController::class, 'approve'])->name('personnel-leaves.approve');
    Route::post('personnel-leaves/{personnel_leave}/reject', [AdminPersonnelLeaveController::class, 'reject'])->name('personnel-leaves.reject');
    Route::resource('personnel-leaves', AdminPersonnelLeaveController::class)->only(['index', 'show', 'store']);

    // Reports for Civilian Affairs
    Route::prefix('reports')->name('reports.')->group(function() {
        Route::get('leave-permit/{personnel_leave}', [AdminReportController::class, 'leavePermitData'])->name('leavePermitData');
        Route::get('period-leave-report', [AdminReportController::class, 'periodLeaveReport'])->name('periodLeaveReport');
    });
});

require __DIR__.'/auth.php';
