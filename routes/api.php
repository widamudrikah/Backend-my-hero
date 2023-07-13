<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\KisahController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::controller(AuthController::class)->group(function(){
    Route::post('/register', 'registerUser');
    Route::post('/login', 'loginUser');
});

Route::controller(KisahController::class)->group(function(){
    Route::get('/kisah', 'index');
    Route::post('/kisah/tambah', 'create');
    Route::get('/kisah/{id}', 'show');
    Route::put('/kisah/update/{id}', 'update');
    Route::delete('/kisah/delete/{id}', 'delete');
});

Route::controller(UserController::class)->group(function(){
    Route::get('/user', 'index');
});


