<?php

use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\OrdersController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

// Route::middleware(['auth:sanctum'])->group(function () {
    Route::apiResource('/feedback', FeedbackController::class);
    Route::apiResource('/order', OrdersController::class);
// });

