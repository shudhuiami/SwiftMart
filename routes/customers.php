<?php

use App\Http\Controllers\CustomerAuthController;
use Illuminate\Support\Facades\Route;

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


// Admin Routes
Route::group(['prefix' => 'auth'], function () {
    Route::post('login', [AdminAuthController::class, 'Login']);
    Route::post('forgot/request', [AdminAuthController::class, 'Forgot']);
    Route::post('reset/password', [AdminAuthController::class, 'Reset']);
});


// Admin Protected Routes
Route::middleware(['auth:sanctum', 'admin'])->prefix('admin')->group(function () {
    Route::post('logout', [AdminAuthController::class, 'Logout']);
});

