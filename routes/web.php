<?php

use App\Http\Controllers\ChatController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn() => redirect('/dashboard'));

require __DIR__.'/auth.php';

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [ChatController::class, 'index'])->name('dashboard');

    Route::get('/users', [ChatController::class, 'getUsers']);

    Route::get('/messages/{user}',        [ChatController::class, 'getMessages']);
    Route::post('/messages',              [ChatController::class, 'sendMessage']);

    Route::get('/groups/{group}/messages',  [ChatController::class, 'getGroupMessages']);
    Route::post('/groups/{group}/messages', [ChatController::class, 'sendGroupMessage']);
    Route::post('/groups',                  [ChatController::class, 'createGroup']);

    Route::post('/status', [ChatController::class, 'updateStatus']);
});