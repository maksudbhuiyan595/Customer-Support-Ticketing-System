<?php

use App\Http\Controllers\CommentController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserAuthController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\ChatController;

Route::group(['controller' => UserAuthController::class], function () {
    Route::post('register', 'register');
    Route::post('login', 'login');
    Route::post('logout', 'logout')->middleware('auth:sanctum');
});
Route::middleware(['auth:sanctum', 'admin'])->group(function () {
    Route::apiResource('tickets', TicketController::class);
    Route::patch('update-status', [TicketController::class, 'updateStatus']);
});
Route::middleware(['auth:sanctum', 'customer'])->group(function () {
    Route::apiResource('tickets', TicketController::class)->only('index');
});
Route::middleware(['auth:sanctum', 'customer'])->group(function () {
    Route::apiResource('comments', CommentController::class)->only(['store', 'index']);
});
Route::middleware(['auth:sanctum','customer'])->group(function () {
    Route::get('get-chat', [ChatController::class, 'index']);
    Route::post('chats', [ChatController::class, 'store']);
    Route::post('chats-mark-as-read', [ChatController::class, 'markAsRead']);
});
