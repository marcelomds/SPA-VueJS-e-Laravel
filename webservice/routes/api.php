<?php

use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::post('/login', [UserController::class, 'login']);
Route::post('/cadastro', [UserController::class, 'register']);

Route::group(['middleware' => 'auth:api'], function () {

    Route::get('/usuario', [UserController::class, 'user']);

    Route::put('/perfil', [UserController::class, 'profile']);
});

