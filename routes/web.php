<?php

use App\Http\Controllers\ChatController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', fn() => redirect('/dashboard'));

Auth::routes();

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [ChatController::class, 'index'])->name('dashboard');

    // Users
    Route::get('/users', [ChatController::class, 'getUsers']);

    // Private chat
    Route::get('/messages/{user}',        [ChatController::class, 'getMessages']);
    Route::post('/messages',              [ChatController::class, 'sendMessage']);

    // Group chat
    Route::get('/groups/{group}/messages',  [ChatController::class, 'getGroupMessages']);
    Route::post('/groups/{group}/messages', [ChatController::class, 'sendGroupMessage']);
    Route::post('/groups',                  [ChatController::class, 'createGroup']);

    // Online status
    Route::post('/status', [ChatController::class, 'updateStatus']);
});
