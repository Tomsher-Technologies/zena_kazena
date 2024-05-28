<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Page;
use App\Models\PageTranslation;


class PageController extends Controller
{

    function __construct()
    {
        $this->middleware('auth');
       
        $this->middleware('permission:website_setup',  ['only' => ['index','create','store','edit','update','destroy','show_custom_page','mobile_custom_page']]);
    }
 
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.website_settings.pages.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        $page = new Page;
        $page->title = $request->title;
        if (Page::where('slug', preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $request->slug)))->first() == null) {
            $page->slug             = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $request->slug));
            $page->type             = "custom_page";
            $page->content          = $request->content;
            $page->meta_title       = $request->meta_title;
            $page->meta_description = $request->meta_description;
            $page->keywords         = $request->keywords;
            $page->meta_image       = $request->meta_image;
            $page->save();

            $page_translation           = PageTranslation::firstOrNew(['lang' => env('DEFAULT_LANGUAGE'), 'page_id' => $page->id]);
            $page_translation->title    = $request->title;
            $page_translation->content  = $request->content;
            $page_translation->save();

            flash(translate('New page has been created successfully'))->success();
            return redirect()->route('website.pages');
        }

        flash(translate('Slug has been used already'))->warning();
        return back();
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
        $page_name = $request->page;
        $page = Page::where('type', $id)->first();
        if($page != null){
          if ($id == 'home') {
            return view('backend.website_settings.pages.home_page_edit', compact('page','lang'));
          }else if ($id == 'find_us' || $id == 'news' || $id == 'faq') {
            return view('backend.website_settings.pages.find_us', compact('page','lang'));
          }else if ($id == 'contact_us') {
            return view('backend.website_settings.pages.contact_us', compact('page','lang'));
          }else if ($id == 'about_us') {
            return view('backend.website_settings.pages.about_us', compact('page','lang'));
          }else{
            return view('backend.website_settings.pages.edit', compact('page','lang'));
          }
        }
        abort(404);
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
        $page = Page::findOrFail($id);
        if ($page) {
            $page_translation                       = PageTranslation::firstOrNew(['lang' => $request->lang, 'page_id' => $page->id]);
            $page_translation->title                = $request->title;
            $page_translation->content              = $request->has('content') ? $request->content : NULL;
            $page_translation->sub_title            = $request->has('sub_title') ? $request->sub_title : NULL;
            $page_translation->heading1             = $request->has('heading1') ? $request->heading1 : NULL;
            $page_translation->content1             = $request->has('content1') ? $request->content1 : NULL;
            $page_translation->heading2             = $request->has('heading2') ? $request->heading2 : NULL;
            $page_translation->content2             = $request->has('content2') ? $request->content2 : NULL;
            $page_translation->heading3             = $request->has('heading3') ? $request->heading3 : NULL;
            $page_translation->content3             = $request->has('content3') ? $request->content3 : NULL;
            $page_translation->heading4             = $request->has('heading4') ? $request->heading4 : NULL;
            $page_translation->meta_title           = $request->meta_title;
            $page_translation->meta_description     = $request->meta_description;
            $page_translation->og_title             = $request->og_title;
            $page_translation->og_description       = $request->og_description;
            $page_translation->twitter_title        = $request->twitter_title;
            $page_translation->twitter_description  = $request->twitter_description;
            $page_translation->keywords             = $request->keywords;
            $page_translation->meta_image           = $request->meta_image;
            $page_translation->image1               = $request->has('image1') ? $request->image1 : NULL;
            $page_translation->save();

            flash(translate('Page data has been updated successfully'))->success();
            return redirect()->route('website.pages');
        }

        flash(translate('Page details not found'))->warning();
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
        $page = Page::findOrFail($id);
        foreach ($page->page_translations as $key => $page_translation) {
            $page_translation->delete();
        }
        if(Page::destroy($id)){
            flash(translate('Page has been deleted successfully'))->success();
            return redirect()->back();
        }
        return back();
    }

    public function show_custom_page($slug){
        $page = Page::where('slug', $slug)->first();
        if($page != null){
            return view('frontend.custom_page', compact('page'));
        }
        abort(404);
    }
    public function mobile_custom_page($slug){
        $page = Page::where('slug', $slug)->first();
        if($page != null){
            return view('frontend.m_custom_page', compact('page'));
        }
        abort(404);
    }
}
