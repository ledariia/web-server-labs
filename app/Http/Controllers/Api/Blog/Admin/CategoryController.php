<?php

namespace App\Http\Controllers\Api\Blog\Admin;

use App\Http\Controllers\Api\Blog\Admin\BaseController;
use App\Http\Requests\BlogCategoryUpdateRequest;
use App\Models\BlogCategory;
use App\Repositories\BlogCategoryRepository;
use Illuminate\Support\Str;
use App\Http\Requests\BlogCategoryCreateRequest;
use App\Http\Resources\Api\Blog\Admin\CategoryResource;

class CategoryController extends BaseController
{
    public function __construct(private BlogCategoryRepository $blogCategoryRepository)
    {
        //parent::__construct();
    }

    /**
     * Отримання списку всіх категорій
     */
    public function index()
    {
        $paginator = $this->blogCategoryRepository->getAllWithPaginate();
        return CategoryResource::collection($paginator);
    }

    /**
     * Створення нової категорії
     */
    public function store(BlogCategoryCreateRequest $request)
    {
        $data = $request->input();
        $item = (new BlogCategory())->create($data);

        if ($item) {
            return response()->json([
                'success' => true,
                'message' => 'Успішно збережено'
            ]);
        } else {
            return response()->json(['message' => 'Помилка збереження'], 500);
        }
    }

    /**
     * ДОДАНО: Отримання однієї категорії для редагування (GET)
     */
    public function show($id)
    {
        $item = $this->blogCategoryRepository->getEdit($id);

        if (empty($item)) {
            return response()->json(['message' => "Запис id=[{$id}] не знайдено"], 404);
        }

        return new CategoryResource($item);
    }

    /**
     * Оновлення категорії (PUT/PATCH)
     */
    public function update(BlogCategoryUpdateRequest $request, $id)
    {
        $item = $this->blogCategoryRepository->getEdit($id);

        if (empty($item)) {
            return response()->json(['message' => "Запис id=[{$id}] не знайдено"], 404);
        }

        $data = $request->all();
        $result = $item->update($data);

        if ($result) {
            return response()->json([
                'success' => true,
                'message' => 'Успішно збережено'
            ]);
        } else {
            return response()->json(['message' => 'Помилка збереження'], 500);
        }
    }

    /**
     * ДОДАНО: Видалення категорії (DELETE)
     */
    public function destroy($id)
    {
        try {
            $result = BlogCategory::destroy($id);

            if ($result) {
                return response()->json(['success' => true, 'message' => "Категорію успішно видалено"]);
            }

            return response()->json(['success' => false, 'message' => 'Помилка видалення запису'], 400);
        } catch (\Exception $e) {
            // Захист, якщо ти намагаєшся видалити категорію, до якої вже прив'язані пости
            return response()->json([
                'success' => false,
                'message' => 'Неможливо видалити категорію. До неї вже прив\'язані пости.'
            ], 500);
        }
    }
}
