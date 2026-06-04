<?php

namespace App\Http\Controllers\Api\Blog\Admin; // Додали \Admin

use App\Http\Controllers\Api\Blog\Admin\BaseController;
use App\Repositories\BlogPostRepository;
use Illuminate\Http\Request;

class PostController extends BaseController
{
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
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Видалити статтю (destroy)
     */
    public function destroy(string $id)
    {
        //
    }
}
