<?php
namespace App\Http\Controllers;

use App\Models\Faqs;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\File;
use Illuminate\Support\Facades\Auth;

class FaqController extends Controller
{
    function __construct()
    {
        $this->middleware('auth');
       
        $this->middleware('permission:website_setup', ['only' => ['index','create','store','edit','update','destroy','change_status']]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $request->session()->put('last_url', url()->full());

        $faqs = Faqs::orderBy('sort_order','asc')->paginate(15);
        return view('backend.faq.index', compact('faqs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backend.faq.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title'         => 'required',
            'ar_title'      => 'required',
            'content'       => 'required',
            'ar_content'    => 'required'
        ],['*.required' => 'This field is required']);

    
        $saveData = [
            'title'                 => $request->title,
            'ar_title'              => $request->ar_title,
            'content'               => $request->content,
            'ar_content'            => $request->ar_content,
            'sort_order'            => $request->sort_order
        ];
        
        $faq = Faqs::create($saveData);
    
        flash(translate('Faq Created Successfully'))->success();
        return redirect()->route('faq.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Blog $faq)
    {
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $faq = Faqs::find($id);
        return view('backend.faq.edit', compact('faq'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'title'         => 'required',
            'ar_title'      => 'required',
            'content'       => 'required',
            'ar_content'    => 'required'
        ],['*.required' => 'This field is required']);

        $faq                = Faqs::find($id);
        $faq->title         = $request->title;
        $faq->ar_title      = $request->ar_title;
        $faq->content       = $request->content;
        $faq->ar_content    = $request->ar_content;
        $faq->sort_order    = $request->sort_order;
        $faq->status        = $request->status;
        $faq->save();

        flash(translate('Faq details updated successfully'))->success();
        return redirect()->route('faq.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $faq = Faqs::find($request->id);
        $faq->delete();
        flash(translate('Faq deleted successfully'))->success();
        return redirect()->route('faq.index');
    }

    public function change_status(Request $request){
        $id = $request->id;
        $status = $request->status;

        $faq = Faqs::find($id);
        $faq->status = $status;
        if($faq->save()){
            echo 1;
        }else{
            echo 0;
        }
    }
}
