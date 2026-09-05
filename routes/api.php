<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CapController;
use App\Http\Controllers\ComplaintsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InvestigationController;
use App\Http\Controllers\PdpController;
use App\Http\Controllers\PolicyController;
use App\Http\Controllers\RiskController;
use App\Http\Controllers\StateController;
use App\Http\Controllers\TrainingController;
use App\Http\Controllers\TravelController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Endpoints (Full-Stack Compliance REST Service)
|--------------------------------------------------------------------------
*/

Route::get('/health', fn() => response()->json(['status' => 'OK', 'framework' => 'Laravel 11', 'timestamp' => now()]));

// Auth
Route::post('/auth/login', [AuthController::class, 'login']);
Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/logout', [AuthController::class, 'logout']);
Route::get('/auth/me', [AuthController::class, 'me']);

// Dashboard
Route::get('/dashboard/metrics', [DashboardController::class, 'index']);

// Complaints
Route::get('/complaints', [ComplaintsController::class, 'index']);
Route::post('/complaints', [ComplaintsController::class, 'store']);
Route::get('/complaints/{id}', [ComplaintsController::class, 'show']);
Route::patch('/complaints/{id}', [ComplaintsController::class, 'update']);
Route::post('/complaints/{id}/convert-cap', [ComplaintsController::class, 'convertToCap']);
Route::post('/complaints/{id}/convert-investigation', [ComplaintsController::class, 'convertToInvestigation']);
Route::delete('/complaints/{id}', [ComplaintsController::class, 'destroy']);

// CAP
Route::get('/cap', [CapController::class, 'index']);
Route::post('/cap', [CapController::class, 'store']);
Route::patch('/cap/{id}', [CapController::class, 'update']);
Route::post('/cap/{id}/evidence', [CapController::class, 'uploadEvidence']);

// Travel & Flight Verification
Route::get('/travel', [TravelController::class, 'index']);
Route::post('/travel', [TravelController::class, 'store']);
Route::post('/travel/{id}/boarding-pass', [TravelController::class, 'uploadBoardingPass']);
Route::post('/travel/{id}/release-payment', [TravelController::class, 'releasePayment']);

// Risk Management
Route::get('/risk', [RiskController::class, 'index']);
Route::post('/risk', [RiskController::class, 'store']);

// Policies
Route::get('/policy', [PolicyController::class, 'index']);
Route::post('/policy/{id}/acknowledge', [PolicyController::class, 'acknowledge']);

// Investigations
Route::get('/investigation', [InvestigationController::class, 'index']);
Route::post('/investigation', [InvestigationController::class, 'store']);

// PDP
Route::get('/pdp', [PdpController::class, 'index']);
Route::post('/pdp', [PdpController::class, 'store']);

// Training
Route::get('/training', [TrainingController::class, 'index']);
Route::post('/training/{id}/complete', [TrainingController::class, 'complete']);

// States
Route::get('/states', [StateController::class, 'index']);
Route::post('/states/{id}/updates', [StateController::class, 'addFieldUpdate']);
