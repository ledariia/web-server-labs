<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BlogCategory extends Model
{
    use SoftDeletes;
    use HasFactory;
    const ROOT = 1;
    protected $fillable = [
        'title',
        'slug',
        'parent_id',
        'description',
    ];
    public function parentCategory()
    {
        return $this->belongsTo(BlogCategory::class, 'parent_id', 'id');
    }

    // Додаємо Аксесуар (Accessor), який створює віртуальне поле parent_title
    public function getParentTitleAttribute()
    {
        $title = $this->parentCategory->title
            ?? ($this->isRoot() ? 'Корінь' : '???');

        return $title;
    }

    // Перевірка чи є категорія кореневою
    public function isRoot()
    {
        return $this->id === BlogCategory::ROOT;
    }
}
