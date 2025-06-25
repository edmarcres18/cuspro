<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AreaApiController;
use App\Http\Controllers\Api\HospitalApiController;
use App\Http\Controllers\Api\PhssApiController;
use App\Http\Controllers\Api\CustomerApiController;
use App\Http\Controllers\Api\UserApiController;
use App\Http\Controllers\Api\AuthApiController;
use App\Http\Controllers\Api\HomeApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Public routes
Route::post('/register', [AuthApiController::class, 'register']);
Route::post('/login', [AuthApiController::class, 'login']);
Route::get('/hospitals', [HospitalApiController::class, 'getHospital']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    // User routes
    Route::get('/user', [AuthApiController::class, 'user']);
    Route::post('/logout', [AuthApiController::class, 'logout']);

    // Dashboard stats
    Route::get('/dashboard', [HomeApiController::class, 'index']);

    // Resource routes
    Route::apiResource('areas', AreaApiController::class);
    Route::apiResource('hospitals', HospitalApiController::class);
    Route::apiResource('phss', PhssApiController::class);
    Route::apiResource('customers', CustomerApiController::class);

    // User management routes - Admin only
    Route::middleware('admin')->apiResource('users', UserApiController::class);
});
