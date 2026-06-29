<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);

// 🔐 Seguridad con token para Sanctum
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    // PRODUCTOS
    Route::controller(ProductController::class)->group(function () {
        Route::middleware('section:products.view')->get('/products', 'index');
        Route::middleware('section:products.view')->get('/products/export/pdf', 'exportPdf');
        Route::middleware('section:products.view')->get('/products/export/excel', 'exportExcel');

        Route::middleware('section:products.create')->post('/products', 'store');
        Route::middleware('section:products.edit')->put('/products/{id}', 'update');
        Route::middleware('section:products.delete')->delete('/products/{id}', 'destroy');
    });

    // USUARIOS
    Route::controller(UserController::class)->group(function () {
        Route::middleware('section:users.view')->get('/users', 'index');
        Route::middleware('section:users.view')->get('/users/export/pdf', 'exportPdf');
        Route::middleware('section:users.view')->get('/users/export/excel', 'exportExcel');

        Route::middleware('section:users.create')->post('/users', 'store');
        Route::middleware('section:users.view')->get('/users/{id}', 'show');
        Route::middleware('section:users.edit')->put('/users/{id}', 'update');
        Route::middleware('section:users.delete')->delete('/users/{id}', 'destroy');
    });

    // PERFILES
    Route::controller(ProfileController::class)->group(function () {
        Route::middleware('section:profiles.view')->get('/profiles', 'index');
        Route::middleware('section:profiles.view')->get('/profiles/export/pdf', 'exportPdf');
        Route::middleware('section:profiles.view')->get('/profiles/export/excel', 'exportExcel');

        Route::middleware('section:profiles.create')->post('/profiles', 'store');
        Route::middleware('section:profiles.edit')->put('/profiles/{id}', 'update');
        Route::middleware('section:profiles.delete')->delete('/profiles/{id}', 'destroy');
    });
});
