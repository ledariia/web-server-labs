<?php

namespace App\Http\Controllers\Api\Blog\Admin; // Додали \Admin

use App\Http\Controllers\Api\Blog\Admin\BaseController;
use App\Repositories\BlogPostRepository;
use App\Repositories\BlogCategoryRepository;
use App\Http\Requests\BlogPostUpdateRequest;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class PostController extends BaseController
{
    private BlogCategoryRepository $blogCategoryRepository; // властивість через яку будемо звертатись в репозиторій категорій

    public function __construct(private BlogPostRepository $blogPostRepository)
    {
        //parent::__construct();
    }

    /**
     * Отримати список статей (index)
     */
    public function index()
    {
        $paginator = $this->blogPostRepository->getAllWithPaginate();

        return $paginator;
    }

    /**
     * Зберегти нову статтю (store)
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Оновити статтю (update)
     */
    public function update(BlogPostUpdateRequest $request, string $id) // ЗАМІНИЛИ Request на BlogPostUpdateRequest
    {
        $item = $this->blogPostRepository->getEdit($id);

        if (empty($item)) { //якщо ід не знайдено
            return ['message' => "Запис id=[{$id}] не знайдено"];
        }

        $data = $request->all(); //отримаємо масив даних, які надійшли з форми

        if (empty($data['slug'])) { //якщо псевдонім порожній
            $data['slug'] = Str::slug($data['title']); //генеруємо псевдонім
        }

        if (empty($item->published_at) && $data['is_published']) { //якщо поле published_at порожнє і нам прийшло 1 в ключі is_published, то
            $data['published_at'] = Carbon::now(); //генеруємо поточну дату
        }

        $result = $item->update($data); //оновлюємо дані об'єкта і зберігаємо в БД

        if ($result) {
            return [
                'success' => true,
                'message' => 'Успішно збережено'
            ];
        } else {
            return ['message' => 'Помилка збереження'];
        }
    }

    /**
     * Видалити статтю (destroy)
     */
    public function destroy(string $id)
    {
        //
    }
}
