<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;


Route::get('/user', function (Request $request) {
    // Simple func to check if user is logged in
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/login', [AuthController::class, 'LoginApi']);

Route::get('/post/{postId}', [PostController::class, 'getSinglePostApi']);
Route::post('/post', [PostController::class, 'createNewPostApi'])->middleware('auth:sanctum');
Route::patch('/post/{postId}', [PostController::class, 'updatePostApi'])->middleware(['auth:sanctum']);
Route::delete('/post/{post}', [PostController::class, 'deletePostApi'])->middleware(['auth:sanctum', 'can:delete,post']);