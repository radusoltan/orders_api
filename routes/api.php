<?php

use App\Http\Controllers\OrderController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/orders', [OrderController::class, 'index']);
Route::get('/orders/{orderNumber}', [OrderController::class, 'show']);
Route::post('/orders', [OrderController::class, 'store']);
