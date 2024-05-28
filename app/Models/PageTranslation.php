<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PageTranslation extends Model
{
  protected $fillable = ['page_id', 'lang', 'title', 'content', 'sub_title', 'heading1', 'content1', 'heading2', 'image1', 'content2', 'heading3', 'content3', 'heading4', 'meta_title', 'meta_description', 'keywords', 'og_title', 'og_description', 'twitter_title', 'twitter_description', 'meta_image'];

  public function page(){
    return $this->belongsTo(Page::class);
  }
}
