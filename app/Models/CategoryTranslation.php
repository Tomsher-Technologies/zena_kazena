<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoryTranslation extends Model
{
    protected $fillable = [
        'category_id', 'lang', 'name', 'slug', 'meta_title', 'meta_description', 'og_title', 'og_description', 'twitter_title', 'twitter_description', 'meta_keyword'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
