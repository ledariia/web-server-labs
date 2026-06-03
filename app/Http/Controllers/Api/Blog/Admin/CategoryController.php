<?php

namespace App\Http\Controllers\Api\Blog\Admin;
use App\Http\Requests\BlogCategoryUpdateRequest;
use App\Models\BlogCategory;
use App\Repositories\BlogCategoryRepository;

//use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Http\Requests\BlogCategoryCreateRequest;

class CategoryController extends BaseController
{
    public function __construct(private BlogCategoryRepository $blogCategoryRepository)
    {
        //parent::__construct();

    }
    public function index()
    {
        //$paginator = BlogCategory::paginate(5);
        $paginator = $this->blogCategoryRepository->getAllWithPaginate(5);
        return $paginator;
    }


    public function store(BlogCategoryCreateRequest $request)
    {
        $data = $request->input(); //отримаємо масив даних, які надійшли з форми
        if (empty($data['slug'])) { //якщо псевдонім порожній
            $data['slug'] = Str::slug($data['title']); //генеруємо псевдонім
        }

        $item = (new BlogCategory())->create($data); //створюємо об'єкт і додаємо в БД

        if ($item) {
            return [
                'success' => true,
                'message' => 'Успішно збережено'
            ];
        } else {
            return ['message' => 'Помилка збереження'];
        }
    }


    public function update(BlogCategoryUpdateRequest $request, $id)
    {
        $item = $this->blogCategoryRepository->getEdit($id);

        if (empty($item)) {
            return ['message' => "Запис id=[{$id}] не знайдено"];
        }

        $data = $request->all();

        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['title']);
        }

        $result = $item->update($data);

        if ($result) {
            return [
                'success' => true,
                'message' => 'Успішно збережено'
            ];
        } else {
            return ['message' => 'Помилка збереження'];
        }
    }
}
