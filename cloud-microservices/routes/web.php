<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\GatewayController;

// ─── Frontend ────────────────────────────────────────────────
Route::get('/', fn() => redirect()->route('articles.index'));

// MongoDB — khassna ndiro routes yedwiya (model binding ma khdamsh b _id)
Route::get('/articles',                [ArticleController::class, 'index'])->name('articles.index');
Route::get('/articles/create',         [ArticleController::class, 'create'])->name('articles.create');
Route::post('/articles',               [ArticleController::class, 'store'])->name('articles.store');
Route::get('/articles/{id}',           [ArticleController::class, 'show'])->name('articles.show');
Route::get('/articles/{id}/edit',      [ArticleController::class, 'edit'])->name('articles.edit');
Route::put('/articles/{id}',           [ArticleController::class, 'update'])->name('articles.update');
Route::delete('/articles/{id}',        [ArticleController::class, 'destroy'])->name('articles.destroy');

// ─── API Gateway ─────────────────────────────────────────────
Route::prefix('api')->group(function () {
    Route::get('/health', [GatewayController::class, 'health']);
});
