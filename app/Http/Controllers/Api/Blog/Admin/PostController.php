<?php

namespace App\Http\Controllers\Api\Blog\Admin; // Додали \Admin

use App\Http\Controllers\Api\Blog\Admin\BaseController;
use App\Repositories\BlogPostRepository;
use App\Repositories\BlogCategoryRepository;
use App\Http\Requests\BlogPostUpdateRequest;
use App\Models\BlogPost;
use App\Http\Requests\BlogPostCreateRequest;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Jobs\BlogPostAfterCreateJob;
use App\Jobs\BlogPostAfterDeleteJob;
use App\Http\Resources\Api\Blog\Admin\PostResource;

class PostController extends BaseController
{
    private BlogCategoryRepository $blogCategoryRepository; // властивість через яку будемо звертатись в репозиторій категорій
    public function toArray(Request $request): array
    {
        return [
            'id'             => $this->id,
            'title'          => $this->title,
            'slug'           => $this->slug,
            'is_published'   => (bool) $this->is_published,
            'content_raw'    => $this->content_raw,
            'excerpt'        => $this->excerpt,
            'date_published' => $this->published_at ? $this->published_at->format('Y-m-d H:i:s') : null,
            'user_id'        => $this->user_id,
            'category_id'    => $this->category_id,
            'user'           => $this->user ? ['name' => $this->user->name] : null,
            'category'       => $this->category ? ['title' => $this->category->title] : null,
        ];
    }
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

        return PostResource::collection($paginator);
    }
    public function show($id)
    {
        $item = $this->blogPostRepository->getEdit($id);

        if (empty($item)) {
            return response()->json(['message' => "Запис id=[{$id}] не знайдено"], 404);
        }
        return new PostResource($item);
    }
    /**
     * Зберегти нову статтю (store)
     */
    public function store(BlogPostCreateRequest $request)
    {
        $data = $request->input(); // отримуємо масив даних
        $item = (new BlogPost())->create($data); // створюємо в БД

        if ($item) {
            // Відправляємо завдання в чергу
            BlogPostAfterCreateJob::dispatch($item);

            return ['success' => true, 'message' => 'Успішно збережено'];
        } else {
            return ['success' => false, 'msg' => 'Помилка збереження'];
        }
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
    public function destroy($id)
    {
        $result = BlogPost::destroy($id); // софт деліт (запис лишається в базі, але позначається як видалений)

        if ($result) {
            // Відправляємо завдання з відкладеним стартом у 20 секунд
            BlogPostAfterDeleteJob::dispatch($id)->delay(20);

            return ['success' => true, 'message' => "Запис з ID [{$id}] успішно видалено"];
        } else {
            return ['success' => false, 'msg' => 'Помилка видалення запису'];
        }
    }

}
