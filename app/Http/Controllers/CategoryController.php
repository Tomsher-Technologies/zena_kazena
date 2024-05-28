<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;
use App\Models\CategoryTranslation;
use App\Utility\CategoryUtility;
use Illuminate\Support\Str;
use Cache;

class CategoryController extends Controller
{

    function __construct()
    {
        $this->middleware('auth');
       
        $this->middleware('permission:manage_categories', ['only' => ['index','create','store','edit','update','destroy','updateFeatured']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $sort_search = null;
        $categories = Category::orderBy('id', 'desc');
        if ($request->has('search')) {
            $sort_search = $request->search;
            $categories = $categories->where('name', 'like', '%' . $sort_search . '%');
        }
        $categories = $categories->paginate(15);
        return view('backend.product.categories.index', compact('categories', 'sort_search'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::where('parent_id', 0)
            ->with('childrenCategories')
            ->get();

        return view('backend.product.categories.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'slug' => 'required',
        ]);

        $category               = new Category;
        $category->name         = $request->name ?? NULL;
        $category->icon         = $request->icon;
        $category->parent_id    = $request->parent_id;
        $category->is_active    = $request->status;
        $category->save();

        $slug               = $request->slug ? Str::slug($request->slug, '-') : Str::slug($request->name, '-');
        $slug               = Str::lower($slug);
        $same_slug_count    = CategoryTranslation::where('slug', 'LIKE', $slug . '%')->count();
        $slug_suffix        = $same_slug_count ? '-' . $same_slug_count + 1 : '';
        $slug              .= $slug_suffix;

        $category_translation                       = CategoryTranslation::firstOrNew(['lang' => env('DEFAULT_LANGUAGE'), 'category_id' => $category->id]);
        $category_translation->name                 = $request->name;
        $category_translation->slug                 = $slug;
        $category_translation->meta_title           = $request->meta_title;
        $category_translation->meta_description     = $request->meta_description;
        $category_translation->meta_keyword         = $request->meta_keywords;
        $category_translation->og_title             = $request->og_title;
        $category_translation->og_description       = $request->og_description;
        $category_translation->twitter_title        = $request->twitter_title;
        $category_translation->twitter_description  = $request->twitter_description;
        $category_translation->save();

        flash(translate('Category has been inserted successfully'))->success();
        return redirect()->route('categories.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        $lang = $request->lang;
        $category = Category::findOrFail($id);
        $categories = Category::where('parent_id', 0)
            ->with('childrenCategories')
            ->whereNotIn('id', CategoryUtility::children_ids($category->id, true))->where('id', '!=', $category->id)
            ->orderBy('name', 'asc')
            ->get();

        return view('backend.product.categories.edit', compact('category', 'categories', 'lang'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $request->validate([
            'name' => 'required',
            'slug' => 'required',
        ]);

        if ($request->lang == env("DEFAULT_LANGUAGE")) {
            $category->name         = $request->name;
            $category->parent_id    = $request->parent_id;
            $category->icon         = $request->icon;
            $category->is_active    = $request->status;
            $category->save();
        }

        $slug = '';
        if ($request->slug != null) {
            $slug = strtolower(Str::slug($request->slug, '-'));
            $same_slug_count = CategoryTranslation::where('slug', 'LIKE', $slug . '%')->where('category_id', '!=', $category->id)->count();
            $slug_suffix = $same_slug_count > 0 ? '-' . $same_slug_count + 1 : '';
            $slug .= $slug_suffix;
        }

        $category_translation                       = CategoryTranslation::firstOrNew(['lang' => $request->lang, 'category_id' => $category->id]);
        $category_translation->name                 = $request->name;
        $category_translation->slug                 = $slug;
        $category_translation->meta_title           = $request->meta_title;
        $category_translation->meta_description     = $request->meta_description;
        $category_translation->meta_keyword         = $request->meta_keywords;
        $category_translation->og_title             = $request->og_title;
        $category_translation->og_description       = $request->og_description;
        $category_translation->twitter_title        = $request->twitter_title;
        $category_translation->twitter_description  = $request->twitter_description;
        $category_translation->save();

        Cache::forget('featured_categories');
        flash(translate('Category has been updated successfully'))->success();
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->attributes()->detach();

        // Category Translations Delete
        foreach ($category->category_translations as $key => $category_translation) {
            $category_translation->delete();
        }

        foreach (Product::where('category_id', $category->id)->get() as $product) {
            $product->category_id = null;
            $product->save();
        }

        CategoryUtility::delete_category($id);
        Cache::forget('featured_categories');

        flash(translate('Category has been deleted successfully'))->success();
        return redirect()->route('categories.index');
    }

    public function updateFeatured(Request $request)
    {
        $category = Category::findOrFail($request->id);
        $category->featured = $request->status;
        $category->save();
        Cache::forget('featured_categories');
        return 1;
    }

    public function updateStatus(Request $request)
    {
        $category = Category::findOrFail($request->id);
    
        $category->is_active = $request->status;
        $category->save();
        // $category->allChildCategories()->update(['is_active' => $request->status]);
        $category->updateChildStatuses($request->status);
        // Cache::forget('featured_categories');
        return 1;
    }

    public function generateSlug(Request $request){
        $slug = Str::slug($request->title);
        echo  $slug;
    }
}
