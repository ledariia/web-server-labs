<?php

namespace App\Http\Controllers\Api\Blog;

use App\Models\BlogPost;
use Illuminate\Http\Request;

class PostController extends BaseController
{
    public function index()
    {
        $items = BlogPost::all();

        return $items;
    }
}
