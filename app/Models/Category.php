<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App;

class Category extends Model
{
    protected $with = ['category_translations'];

    protected $fillable = ['parent_id', 'name', 'icon', 'is_active'];

    public function getTranslation($field = '', $lang = false)
    {
        $lang = $lang == false ? App::getLocale() : $lang;
        $category_translation = $this->category_translations->where('lang', $lang)->first();
        return $category_translation != null ? $category_translation->$field : $this->$field;
    }

    public function category_translations()
    {
        return $this->hasMany(CategoryTranslation::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function classified_products()
    {
        return $this->hasMany(CustomerProduct::class);
    }

    public function categories()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function childrenCategories()
    {
        return $this->hasMany(Category::class, 'parent_id')->with('categories');
    }

    public function childs()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function childrenRecursive()
    {
        return $this->childs()->with('childrenRecursive');
    }

    public function parentCategory()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function attributes()
    {
        return $this->belongsToMany(Attribute::class);
    }

    public function getAllChildren ()
    {

        $sections = collect([]);
        if(!empty($this->childs)){
            foreach ($this->childs as $section) {
                $sections->push($section);
                $sections = $sections->merge($section->getAllChildren());
            }
        }
        return $sections;
    }

    public function allChildCategories()
    {
        return $this->hasMany(Category::class, 'parent_id')->with('categories');
    }

     // Recursive method to update statuses of all child categories
     public function updateChildStatuses($status)
     {
         // Update status of the current category
         $this->update(['is_active' => $status]);
 
         // Recursively update statuses of child categories
         foreach ($this->categories as $child) {
             $child->updateChildStatuses($status);
         }
     }

}
