<?php

namespace App\Http\Resources\Api\Blog\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;
class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'             => $this->id,
            'title'          => $this->title,
            'slug'           => $this->slug,
            'is_published'   => (bool) $this->is_published,

            // Форматуємо дату і змінюємо назву ключа
            'date_published' => $this->published_at ? Carbon::parse($this->published_at)->format('Y-m-d H:i:s') : null,

            'user_id'        => $this->user_id,
            'category_id'    => $this->category_id,

            // Додаємо вкладені об'єкти для фронтенду
            'user'           => $this->user ? ['name' => $this->user->name] : null,
            'category'       => $this->category ? ['title' => $this->category->title] : null,
        ];
    }
}
