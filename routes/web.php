<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CapController;
use App\Http\Controllers\ComplaintsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InvestigationController;
use App\Http\Controllers\LeaveAttendanceController;
use App\Http\Controllers\PdpController;
use App\Http\Controllers\PolicyController;
use App\Http\Controllers\RiskController;
use App\Http\Controllers\StateController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\TrainingController;
use App\Http\Controllers\TravelController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes (Laravel Architecture)
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('login');
});

// Authentication
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/admin/login', [AuthController::class, 'showAdminLogin'])->name('admin.login');
Route::get('/admin', [AuthController::class, 'showAdminLogin']);
Route::post('/admin/login', [AuthController::class, 'adminLogin']);
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Authenticated Application Modules
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/leave-attendance', [LeaveAttendanceController::class, 'index'])->name('leave.attendance');
    Route::get('/complaints', [ComplaintsController::class, 'index'])->name('complaints');
    Route::post('/complaints', [ComplaintsController::class, 'store'])->name('complaints.store');
    Route::post('/complaints/{id}/convert-cap', [ComplaintsController::class, 'convertToCap'])->name('complaints.convert-cap');
    Route::post('/complaints/{id}/convert-investigation', [ComplaintsController::class, 'convertToInvestigation'])->name('complaints.convert-investigation');

    Route::get('/cap', [CapController::class, 'index'])->name('cap');
    Route::post('/cap/{id}/evidence', [CapController::class, 'uploadEvidence'])->name('cap.evidence');

    Route::get('/pdp', [PdpController::class, 'index'])->name('pdp');
    Route::get('/training', [TrainingController::class, 'index'])->name('training');
    Route::get('/states', [StateController::class, 'index'])->name('states');
    Route::get('/risk', [RiskController::class, 'index'])->name('risk');
    Route::get('/policies', [PolicyController::class, 'index'])->name('policies');
    Route::get('/investigations', [InvestigationController::class, 'index'])->name('investigations');
    Route::get('/travel', [TravelController::class, 'index'])->name('travel');
    Route::get('/staff', [StaffController::class, 'index'])->name('staff');

    // Standalone Identify / Attendify Embed and Test Harness Routes
    Route::get('/identify', function () {
        return view('test_harness.identify_simulator');
    })->name('identify.simulator');
    Route::get('/identify/staff-compliance', function () {
        return view('test_harness.identify_simulator');
    })->name('identify.compliance');
    Route::get('/staff-embed', function () {
        return view('modules.identify_embed');
    })->name('staff.embed');
});
