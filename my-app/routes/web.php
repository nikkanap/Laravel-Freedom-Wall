<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FreedomWallController;
use App\Http\Controllers\PostController;

Route::get('/', [FreedomWallController::class, 'index']);

Route::get('/register', [FreedomWallController::class, 'showRegister']);
Route::post('/register', [FreedomWallController::class, 'register']);

Route::get('/login', [FreedomWallController::class, 'showLogin']);
Route::post('/login', [FreedomWallController::class, 'login']);

Route::post('/logout', [FreedomWallController::class, 'logout']);

Route::post('/post_message', [PostController::class, 'store']);
Route::post('/post-message', function () {
    return 'save message here';
});

Route::get('/delete-post/{id}', function ($id) {
    return 'delete post ' . $id;
});