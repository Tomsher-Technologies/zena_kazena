<?php
namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\File;
use Illuminate\Support\Facades\Auth;

class BlogController extends Controller
{
    function __construct()
    {
        // $this->middleware('permission:news');
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $request->session()->put('last_url', url()->full());

        $blogs = Blog::orderBy('id','desc')->paginate(15);
        return view('backend.blog_system.blog.index', compact('blogs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backend.blog_system.blog.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'image'         => 'required',
            'title'         => 'required',
            'ar_title'      => 'required',
            'content'       => 'required',
            'ar_content'    => 'required',
            'news_date'     => 'required'
        ],['*.required' => 'This field is required']);

        $slug = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $request->title));
        $same_slug_count = Blog::where('slug', 'LIKE', $slug . '%')->count();
        $slug_suffix = $same_slug_count ? '-' . $same_slug_count + 1 : '';
        $slug .= $slug_suffix;

        $saveData = [
            'slug'                  => $slug,
            'title'                 => $request->title,
            'ar_title'              => $request->ar_title,
            'content'               => $request->content,
            'ar_content'            => $request->ar_content,
            'blog_date'             => $request->news_date,
            'image'                 => $request->image,
            'seo_title'             => $request->meta_title,
            'og_title'              => $request->og_title, 
            'twitter_title'         => $request->twitter_title, 
            'seo_description'       => $request->meta_description, 
            'og_description'        => $request->og_description, 
            'twitter_description'   => $request->twitter_description, 
            'keywords'              => $request->keywords,
            'meta_image'            => $request->meta_image
        ];
        
        $blog = Blog::create($saveData);
        // die;
        flash(translate('News Created Successfully'))->success();
        return redirect()->route('news.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Blog $blog)
    {
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $blog = Blog::find($id);
        return view('backend.blog_system.blog.edit', compact('blog'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'image'         => 'nullable',
            'title'         => 'required',
            'ar_title'      => 'required',
            'content'       => 'required',
            'ar_content'    => 'required',
            'news_date'     => 'required'
        ],['*.required' => 'This field is required']);

        $blog                       = Blog::find($id);
        $blog->title                = $request->title;
        $blog->ar_title             = $request->ar_title;
        $blog->content              = $request->content;
        $blog->ar_content           = $request->ar_content;
        $blog->status               = $request->status;
        $blog->blog_date            = $request->news_date;
        $blog->seo_title            = $request->meta_title;
        $blog->og_title             = $request->og_title; 
        $blog->twitter_title        = $request->twitter_title;
        $blog->seo_description      = $request->meta_description;
        $blog->og_description       = $request->og_description;
        $blog->twitter_description  = $request->twitter_description; 
        $blog->keywords             = $request->keywords;
        $blog->image                = $request->image;
        $blog->meta_image           = $request->meta_image;
        $blog->save();

        flash(translate('News details updated successfully'))->success();
        return redirect()->route('news.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $blog = Blog::find($request->id);
        $blog->delete();
        flash(translate('News deleted successfully'))->success();
        return redirect()->route('news.index');
    }

    public function change_status(Request $request){
        $id = $request->id;
        $status = $request->status;

        $blog = Blog::find($id);
        $blog->status = $status;
        if($blog->save()){
            echo 1;
        }else{
            echo 0;
        }
    }
}
