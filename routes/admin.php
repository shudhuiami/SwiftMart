<?php

use App\Http\Controllers\AdminAuthController;
use Illuminate\Support\Facades\Route;

// Admin Routes
Route::group(['prefix' => 'auth'], function () {
    Route::post('login', [AdminAuthController::class, 'Login']);
    Route::post('forgot/request', [AdminAuthController::class, 'sendResetLinkEmail']);
    Route::post('reset/password', [AdminAuthController::class, 'reset']);
});


// Admin Protected Routes
Route::middleware(['auth:sanctum', 'admin'])->group(function () {
    Route::post('auth/logout', [AdminAuthController::class, 'logout']);
});
