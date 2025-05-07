<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UserAuthController;
use App\Http\Controllers\TicketController;

Route::group(['controller' => UserAuthController::class], function () {
    Route::post('register', 'register');
    Route::post('login', 'login');
    Route::post('logout', 'logout')->middleware('auth:sanctum');
});
Route::apiResource('tickets', TicketController::class)->middleware(['auth:sanctum', 'admin']);
Route::apiResource('tickets', TicketController::class)->only('index')->middleware(['auth:sanctum', 'customer']);
