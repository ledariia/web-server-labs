<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
// Імпортуємо твій контролер явно, щоб Laravel точно знав де він
use App\Http\Controllers\Api\Blog\PostController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('blog')->group(function () {
    Route::apiResource('posts', PostController::class)->names('blog.posts');
});
