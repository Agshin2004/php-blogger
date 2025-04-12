<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/post/{postId}', [PostController::class, 'getSinglePostApi']);
Route::post('/post', [PostController::class, 'createNewPostApi'])->middleware('auth:sanctum');
Route::delete('/post/{post}', [PostController::class, 'deletePostApi'])->middleware(['auth:sanctum', 'can:delete,post']);
Route::post('/login', [AuthController::class, 'LoginApi']);