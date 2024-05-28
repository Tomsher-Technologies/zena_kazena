<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WebsiteController extends Controller
{
	function __construct()
    {
        $this->middleware('auth');
       
        $this->middleware('permission:website_setup', ['only' => ['header','footer','pages','appearance']]);
    }
	public function header(Request $request)
	{
		return view('backend.website_settings.header');
	}
	public function footer(Request $request)
	{	
		$lang = $request->lang;
		return view('backend.website_settings.footer', compact('lang'));
	}
	public function pages(Request $request)
	{
		$pages = \App\Models\Page::orderBy('slug', 'asc')->get();
		return view('backend.website_settings.pages.index', compact('pages'));
	}
	public function appearance(Request $request)
	{
		return view('backend.website_settings.appearance');
	}
}