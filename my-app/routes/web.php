<?php

use App\Http\Controllers\FreedomWallController;
use Illuminate\Support\Facades\Route;

//done w this i think
Route::get('/', [FreedomWallController::class, 'index']);

Route::get('/register', [FreedomWallController::class, 'showRegister']);

Route::post('/register', [FreedomWallController::class, 'register']);

//needs working
Route::get('/login', [FreedomWallController::class, 'showLogin']);

Route::post('/login', [FreedomWallController::class, 'login']);

Route::post('/logout', [FreedomWallController::class, 'logout']);

Route::post('/post-message', [FreedomWallController::class, 'postMessage']);

Route::get('/delete-post/{id}', function ($id) {
    return 'delete post ' . $id;
});