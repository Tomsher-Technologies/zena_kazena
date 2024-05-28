<?php

namespace App\Models;

use App\Models\Common\Seo;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\URL;


class Faqs extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'ar_title',
        'content',
        'ar_content',
        'sort_order'
    ];

    
    public function getTranslation($field = '', $lang = false)
    {
        $lang = $lang == false ? getActiveLanguage() : $lang;

        if ($lang !== 'en') {
            $field = 'ar_' . $field;
        }

        return $this->$field;
    }

}
