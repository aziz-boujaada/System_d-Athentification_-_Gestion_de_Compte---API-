<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\ProfileController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [ProfileController::class, 'showProfile']);
    Route::put('/me', [ProfileController::class, 'updateProfile']);
    Route::put('/me/reset-password', [ProfileController::class, 'resetPassword']);
    Route::delete('/profile/{id}', [ProfileController::class, 'destroyProfile']);
});