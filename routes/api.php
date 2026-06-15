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
    // Вказуємо всі методи, які потрібні для CRUD (Лабораторна 18)
    $methods = ['index', 'store', 'update', 'destroy', 'show'];

    // Маршрути для категорій (ВИПРАВЛЕНО)
    Route::apiResource('categories', CategoryController::class)
        ->only($methods)
        ->names('blog.admin.categories');

    // Маршрути для постів (ВИПРАВЛЕНО: прибрали except('show'))
    Route::apiResource('posts', AdminPostController::class)
        ->names('blog.admin.posts');
});

Route::group(['prefix' => 'digging_deeper'], function () {
    Route::get('process-video', [\App\Http\Controllers\DiggingDeeperController::class, 'processVideo'])
        ->name('digging_deeper.processVideo');

    Route::get('prepare-catalog', [\App\Http\Controllers\DiggingDeeperController::class, 'prepareCatalog'])
        ->name('digging_deeper.prepareCatalog');
});
