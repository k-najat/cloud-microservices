<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ArticleController;

Route::get('/health', fn() => response()->json(['status' => 'ok', 'service' => 'article-service']));

Route::prefix('articles')->group(function () {
    Route::get('/',                    [ArticleController::class, 'index']);
    Route::post('/',                   [ArticleController::class, 'store']);
    Route::get('/{id}',                [ArticleController::class, 'show']);
    Route::put('/{id}',                [ArticleController::class, 'update']);
    Route::delete('/{id}',             [ArticleController::class, 'destroy']);
    Route::get('/user/{userId}',       [ArticleController::class, 'byUser']);
});
