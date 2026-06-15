<?php

namespace App\Http\Resources\Api\Blog\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;


class CategoryResource extends JsonResource
{
    public function show($id)
    {
        // Отримуємо категорію через репозиторій
        $item = $this->blogCategoryRepository->getEdit($id);

        if (empty($item)) {
            return response()->json(['message' => "Категорію id=[{$id}] не знайдено"], 404);
        }

        // Обгортаємо категорію в наш ресурс
        return new CategoryResource($item);
    }
    public function toArray(Request $request): array
    {
        return [
            'id'        => $this->id,
            'title'     => $this->title,
            'slug'      => $this->slug,
            'parent_id' => $this->parent_id,
            // 'parent_title' => $this->parentCategory?->title,
        ];
    }
}
