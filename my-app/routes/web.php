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


Route::get('/logout', function () {
    return 'logout here';
});

Route::post('/post-message', function () {
    return 'save message here';
});

Route::get('/delete-post/{id}', function ($id) {
    return 'delete post ' . $id;
});