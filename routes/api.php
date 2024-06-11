<?php

use App\Http\Controllers\APi\MediaController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
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

Route::post('/login', [AuthController::class, 'login']);
// Route::get('/me', [AuthController::class, 'index'])->middleware('auth:sanctum');
Route::middleware('auth:sanctum')->group(function () {
  Route::post('/logout', [AuthController::class, 'logout']);
  Route::get('/me', [AuthController::class, 'index']);
  });

Route::post('/register', [AuthController::class, 'register']);

Route::get('/user/list',[UserController::class, 'index']);
Route::middleware('auth:sanctum')->group(function () {
  Route::get('/user/profile',[UserController::class, 'show']);
  Route::put('/user/profile/update',[UserController::class, 'update']);

});


