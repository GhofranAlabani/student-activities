<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TagController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\ActivityReportController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function () {

    // Tags
    Route::get('/tags', [TagController::class, 'index']);
    Route::post('/tags', [TagController::class, 'store']);
    Route::delete('/tags/{tag}', [TagController::class, 'destroy']);

    // Messages
    Route::get('/messages', [MessageController::class, 'index']);
    Route::post('/messages', [MessageController::class, 'store']);
    Route::patch('/messages/{message}/read', [MessageController::class, 'markAsRead']);
    Route::delete('/messages/{message}', [MessageController::class, 'destroy']);

    // Activity Reports
    Route::get('/activity-reports', [ActivityReportController::class, 'index']);
    Route::post('/activity-reports', [ActivityReportController::class, 'store']);
    Route::get('/activity-reports/{activityReport}', [ActivityReportController::class, 'show']);
    Route::delete('/activity-reports/{activityReport}', [ActivityReportController::class, 'destroy']);

});