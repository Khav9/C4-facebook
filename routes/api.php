<?php

use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\FollowController;
use App\Http\Controllers\Api\FriendshipController;
use App\Http\Controllers\Api\LikeController;
use App\Http\Controllers\APi\MediaController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
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

Route::post('login', [AuthController::class, 'login']);
// Route::get('/me', [AuthController::class, 'index'])->middleware('auth:sanctum');
Route::middleware('auth:sanctum')->group(function () {
  Route::post('/logout', [AuthController::class, 'logout']);
  Route::get('/me', [AuthController::class, 'index']);
});

Route::post('/register', [AuthController::class, 'register']);

Route::get('user/list', [UserController::class, 'index']);
Route::middleware('auth:sanctum')->group(function () {
  Route::get('/user/profile', [UserController::class, 'show']);
  Route::put('/user/profile/update', [UserController::class, 'update']);
  Route::post('/user/upload-profile', [UserController::class, 'uploadProfile']);

});


Route::get('/me', [AuthController::class, 'index'])->middleware('auth:sanctum');

// Route::get('/comment/list',[CommentController::class, 'index'])->name('comment.list');

Route::middleware('auth:sanctum')->group(function (){
    Route::post('post/{id}/comment/create',[CommentController::class, 'store']);
    Route::delete('post/{post_id}/comment/delete/{id}',[CommentController::class, 'destroy']);
    Route::put('post/{post_id}/comment/update/{id}',[CommentController::class, 'update']);
});


Route::middleware('auth:sanctum')->prefix('post')->group(function () {
  Route::get('/list', [PostController::class, 'index']);
  Route::post('/create', [PostController::class, 'store'])->name('post.create');
  Route::get('/show/{id}', [PostController::class, 'show'])->name('post.show');
  Route::put('/update/{id}', [PostController::class, 'update'])->name('post.update');
  Route::delete('/delete/{id}', [PostController::class, 'destroy'])->name('post.destroy');

});


Route::post('like-unlike-post', [LikeController::class, 'store'])->middleware('auth:sanctum');
Route::post('follow-unfollow-user',[FollowController::class,'store'])->middleware('auth:sanctum');
Route::get('followers/list', [FollowController::class, 'index'])->middleware('auth:sanctum');


Route::get('posts', [PostController::class, 'allPost']);

Route::middleware('auth:sanctum')->group(function () {
  Route::post('friend/add/{id}', [FriendshipController::class, 'sendRequest']);
  Route::post('friend/accept/{id}', [FriendshipController::class, 'acceptRequest']);
  Route::post('friend/reject/{id}', [FriendshipController::class, 'rejectRequest']);
  Route::get('friend/list', [FriendshipController::class, 'index']);
});
