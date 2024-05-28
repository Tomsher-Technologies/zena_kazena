<?php

namespace App\Models;

use App\Models\Common\Seo;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\URL;


class Blog extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'ar_title',
        'content',
        'ar_content',
        'image',
        'status','blog_date',
        'slug','seo_title', 'og_title', 'twitter_title', 'seo_description', 'og_description', 'twitter_description', 'keywords','meta_image'
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function getImage()
    {
        return $this->image ? URL::to($this->image) : asset('adminassets/img/placeholder.png');
    }

    public function getTranslation($field = '', $lang = false)
    {
        $lang = $lang == false ? getActiveLanguage() : $lang;

        if ($lang !== 'en') {
            $field = 'ar_' . $field;
        }

        return $this->$field;
    }

    public function getDate()
    {
        $date = Carbon::parse($this->created_at)->locale(getActiveLanguage());
        return $date->translatedFormat('d M y');
    }

    public function getNewsDate()
    {
        $date = Carbon::parse($this->blog_date)->locale(getActiveLanguage());
        return $date->translatedFormat('d M y');
    }

    // public function getURL($newsURL)
    // {
    //     return URL::to($newsURL . $this->slug);
    // }
}
