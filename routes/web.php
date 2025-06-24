<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// use App\Http\Controllers\AreaController;
// use App\Http\Controllers\HospitalController;
// use App\Http\Controllers\UserController;
// use App\Http\Controllers\PhssController;
// use App\Http\Controllers\CustomerController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
// Route::resource('areas', AreaController::class);
// Route::resource('hospitals', HospitalController::class);
// Route::resource('users', UserController::class);
// Route::resource('phss', PhssController::class);
// Route::resource('customers', CustomerController::class);

// API Client Routes
Route::prefix('api-client')->group(function () {
    Route::get('/', function () {
        return view('api-client.dashboard');
    });
    
    Route::get('/login', function () {
        return view('api-client.auth.login');
    });
    
    Route::get('/areas', function () {
        return view('api-client.areas.index');
    });
    
    Route::get('/areas/create', function () {
        return view('api-client.areas.create');
    });
    
    Route::get('/areas/{id}/edit', function ($id) {
        return view('api-client.areas.edit', ['id' => $id]);
    });
    
    Route::get('/hospitals', function () {
        return view('api-client.hospitals.index');
    });
    
    Route::get('/hospitals/create', function () {
        return view('api-client.hospitals.create');
    });
    
    Route::get('/hospitals/{id}/edit', function ($id) {
        return view('api-client.hospitals.edit', ['id' => $id]);
    });
    
    Route::get('/phss', function () {
        return view('api-client.phss.index');
    });
    
    Route::get('/phss/create', function () {
        return view('api-client.phss.create');
    });
    
    Route::get('/phss/{id}/edit', function ($id) {
        return view('api-client.phss.edit', ['id' => $id]);
    });
    
    Route::get('/customers', function () {
        return view('api-client.customers.index');
    });
    
    Route::get('/customers/create', function () {
        return view('api-client.customers.create');
    });
    
    Route::get('/customers/{id}', function ($id) {
        return view('api-client.customers.show', ['id' => $id]);
    });
    
    Route::get('/customers/{id}/edit', function ($id) {
        return view('api-client.customers.edit', ['id' => $id]);
    });
    
    Route::get('/users', function () {
        return view('api-client.users.index');
    });
    
    Route::get('/users/create', function () {
        return view('api-client.users.create');
    });
    
    Route::get('/users/{id}/edit', function ($id) {
        return view('api-client.users.edit', ['id' => $id]);
    });
});