<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
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
Route::get('/me', [AuthController::class, 'index'])->middleware('auth:sanctum');


Route::middleware('auth:sanctum')->prefix('post')->group(function () {
    Route::get('/list', [PostController::class, 'index']);
    Route::post('/create', [PostController::class, 'store'])->name('post.create');
    Route::get('/show/{id}', [PostController::class, 'show'])->name('post.show');
    Route::put('/update/{id}', [PostController::class, 'update'])->name('post.update');
    Route::delete('/delete/{id}', [PostController::class, 'destroy'])->name('post.destroy');
});

