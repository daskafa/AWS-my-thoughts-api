<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ThoughtController;
use Illuminate\Support\Facades\Route;

Route::post('authenticate', [AuthController::class, 'authenticate']);
Route::post('register', [AuthController::class, 'register']);

Route::middleware(['auth:sanctum', 'isUser'])->group(function () {
    Route::apiResources([
        'thoughts' => ThoughtController::class,
    ]);

    Route::prefix('comments')->group(function () {
        Route::post('store', [CommentController::class, 'store']);
        Route::delete('destroy/{id}', [CommentController::class, 'destroy']);
    });
});
