<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BrandTranslation extends Model
{
  protected $fillable = [
    'brand_id', 'lang', 'name', 'slug', 'meta_title', 'meta_description', 'og_title', 'og_description', 'twitter_title', 'twitter_description', 'meta_keywords'
  ];

  public function brand()
  {
    return $this->belongsTo(Brand::class);
  }
}
