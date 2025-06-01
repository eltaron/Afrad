<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\PersonnelController as AdminPersonnelController;
use App\Http\Controllers\Admin\PersonnelLeaveController as AdminPersonnelLeaveController;
use App\Http\Controllers\Admin\ReportController as AdminReportController;
use App\Http\Resources\UserResource;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix('v1')->name('api.v1.')->group(function () {
    Route::middleware('auth:sanctum')->group(function () {
        // Personnel
        Route::get('personnel', [AdminPersonnelController::class, 'index'])->name('personnel.index');
        Route::get('personnel/{personnel}', [AdminPersonnelController::class, 'show'])->name('personnel.show');
        // Note: POST, PUT, DELETE for personnel can be added here if needed, pointing to AdminPersonnelController methods.

        // Leave Requests
        Route::get('personnel-leaves', [AdminPersonnelLeaveController::class, 'index'])->name('personnel-leaves.index');
        Route::get('personnel-leaves/{personnel_leave}', [AdminPersonnelLeaveController::class, 'show'])->name('personnel-leaves.show'); // route model binding uses snake_case
        Route::post('personnel-leaves', [AdminPersonnelLeaveController::class, 'store'])->name('personnel-leaves.store');
        Route::post('personnel-leaves/{personnel_leave}/approve', [AdminPersonnelLeaveController::class, 'approve'])->name('personnel-leaves.approve');
        Route::post('personnel-leaves/{personnel_leave}/reject', [AdminPersonnelLeaveController::class, 'reject'])->name('personnel-leaves.reject');

        // Reports
        Route::get('reports/daily-eligible-for-leave', [AdminReportController::class, 'dailyEligibleForLeave'])->name('reports.dailyEligibleForLeave');
        Route::get('reports/leave-permit/{personnel_leave}', [AdminReportController::class, 'leavePermitData'])->name('reports.leavePermitData');
        Route::get('reports/period-leave-report', [AdminReportController::class, 'periodLeaveReport'])->name('reports.periodLeaveReport');
        Route::get('reports/period-violation-report', [AdminReportController::class, 'periodViolationReport'])->name('reports.periodViolationReport');

        // Current authenticated user
        Route::get('/user', function (Request $request) {
            // Assuming User model has a relationship 'hospitalForce' if applicable to the user type directly
            // Or this could be part of a more complex ProfileResource if user has many associated details.
            return new UserResource($request->user());
        })->name('user');
    });

    // TODO: Add public API routes here if any (e.g., for login if not using a separate SPA login flow)
    // Example: Route::post('/login', [AuthController::class, 'login']);
});
