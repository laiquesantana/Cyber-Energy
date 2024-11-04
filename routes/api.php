<?php

use App\Http\Controllers\Auth\RequestPasswordController as RequestPasswordController;
use App\Http\Controllers\v1\ChatController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;

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
Route::get('health', [AuthController::class, 'health']);

Route::prefix('v1')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login'])->name('login');
    Route::post('logout', [AuthController::class, 'logout']);
});

Route::prefix('v1/')->group(function () {
    Route::post('/chat', [ChatController::class, 'create']);
    Route::get('/chat', [ChatController::class, 'index']);
    Route::get('/chat/{id}', [ChatController::class, 'show']);
    Route::put('/chat/{id}', [ChatController::class, 'update']);
    Route::delete('/chat/{id}', [ChatController::class, 'delete']);
});
