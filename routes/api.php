<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Підключаємо контролери адмінки
use App\Http\Controllers\Api\Blog\Admin\CategoryController;
use App\Http\Controllers\Api\Blog\Admin\PostController as AdminPostController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Адмінка
Route::prefix('admin/blog')->group(function () {
    $methods = ['index', 'store', 'update'];

    // Маршрути для категорій
    Route::apiResource('categories', CategoryController::class)
        ->only($methods)
        ->names('blog.admin.categories');

    // Маршрути для постів (використовуємо AdminPostController)
    Route::apiResource('posts', AdminPostController::class)
        ->except(['show'])
        ->names('blog.admin.posts');
});
