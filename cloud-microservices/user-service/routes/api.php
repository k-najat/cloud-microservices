<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::get('/health', fn() => response()->json(['status' => 'ok', 'service' => 'user-service']));

Route::prefix('users')->group(function () {
    Route::get('/',                                    [UserController::class, 'index']);
    Route::post('/',                                   [UserController::class, 'store']);
    Route::get('/{id}',                                [UserController::class, 'show']);
    Route::put('/{id}',                                [UserController::class, 'update']);
    Route::get('/{id}/notifications',                  [UserController::class, 'notifications']);
    Route::patch('/{id}/notifications/{notifId}/read', [UserController::class, 'markRead']);
});
