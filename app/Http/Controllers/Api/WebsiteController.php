<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\BusinessSetting;
use App\Models\Category;
use App\Models\Product;
use App\Models\Frontend\Banner;
use App\Models\Frontend\HomeSlider;
use App\Models\Shop;
use App\Models\Page;
use App\Models\PageTranslation;
use App\Models\Faqs;
use App\Models\Blog;
use App\Models\Contacts;
use Illuminate\Http\Request;
use App\Mail\ContactEnquiry;
use Cache;
use Mail;
use Validator;

class WebsiteController extends Controller
{
    public function websiteFooter(){
        $data = [];

        $pageData['facebook']   = get_setting('facebook_link');
        $pageData['instagram']  = get_setting('instagram_link');
        $pageData['twitter']    = get_setting('twitter_link');
        $pageData['youtube']    = get_setting('youtube_link');
        $pageData['linkedin']   = get_setting('linkedin_link');
        $pageData['whatsapp']   = get_setting('whatsapp_link');
        $pageData['dribbble']   = get_setting('dribbble_link');

        
        $data['newsletter_title'] = get_setting('newsletter_title');
        $data['app_section_title'] = get_setting('app_title');
        $data['play_store_link'] = get_setting('play_store_link');
        $data['app_store_link'] = get_setting('app_store_link');
        $data['social_title'] = get_setting('social_title');
        $data['social_links'] = $pageData;
        $data['address'] = get_setting('contact_address');
        $data['phone1'] = get_setting('contact_phone');
        $data['phone2'] = get_setting('contact_phone2');
        $data['email'] = get_setting('contact_email');
        $data['copyright'] = get_setting('frontend_copyright_text');

        $payments = explode(',',get_setting('payment_method_images'));
        $images = [];
        if(!empty($payments)){
            foreach($payments as $pay){
                $images[] = uploaded_asset($pay);
            }
        }
        $data['payment_methods'] = $images;

        return $data;
    }

    public function footer()
    {
        return response()->json([
            'result' => true,
            'app_links' => array([
                'play_store' => array([
                    'link' => get_setting('play_store_link'),
                    'image' => api_asset(get_setting('play_store_image')),
                ]),
                'app_store' => array([
                    'link' => get_setting('app_store_link'),
                    'image' => api_asset(get_setting('app_store_image')),
                ]),
            ]),
            'social_links' => array([
                'facebook' => get_setting('facebook_link'),
                'twitter' => get_setting('twitter_link'),
                'instagram' => get_setting('instagram_link'),
                'youtube' => get_setting('youtube_link'),
                'linkedin' => get_setting('linkedin_link'),
            ]),
            'copyright_text' => get_setting('frontend_copyright_text'),
            'contact_phone' => get_setting('contact_phone'),
            'contact_email' => get_setting('contact_email'),
            'contact_address' => get_setting('contact_address'),
        ]);
    }

    public function storeLocations(Request $request){
        $lang       = $request->has('lang') ? (($request->lang == 'ar') ? 'ae' : $request->lang) : 'en';

        if($lang == 'ae'){
            $selectFaq = ['id', 'name_ar as name', 'address_ar as address', 'phone', 'email', 'delivery_pickup_latitude as latitude', 'delivery_pickup_longitude as longitude'];
        }else{
            $selectFaq = ['id', 'name', 'address', 'phone', 'email', 'delivery_pickup_latitude as latitude', 'delivery_pickup_longitude as longitude'];
        }
        $shops = Shop::without(['user'])->select($selectFaq)->where('status',1)->orderBy('name','asc')->get();

        $meta = PageTranslation::leftJoin('pages as p','p.id', '=' ,'page_translations.page_id')
                                    ->where('lang', $lang)
                                    ->where('p.type', 'find_us')
                                    ->select('page_translations.title', 'page_translations.meta_title', 'page_translations.meta_description', 'page_translations.keywords', 'page_translations.og_title', 'page_translations.og_description', 'page_translations.twitter_title', 'page_translations.twitter_description', 'page_translations.meta_image')
                                    ->first();
        // $shops['page_data'] = $query;
        if($meta){
            $meta->meta_image       = ($meta->meta_image != NULL) ? uploaded_asset($meta->meta_image) : '';
        }
        $meta = ($meta) ? $meta : [];
        return response()->json(['status' => true,"message"=>"Success","data" => $shops,"page_data" => $meta],200);
    }

    public function pageContents(Request $request){
        $page_type  = $request->has('page') ? $request->page : null;
        $lang       = $request->has('lang') ? (($request->lang == 'ar') ? 'ae' : $request->lang) : 'en';

        $faqs = [];
        if($page_type){
            $query = PageTranslation::leftJoin('pages as p','p.id', '=' ,'page_translations.page_id')
                                    ->where('lang', $lang)
                                    ->where('p.type', $page_type);


            if($page_type == 'terms' || $page_type == 'refund_return' || $page_type == 'privacy_policy' || $page_type == 'shipping_delivery'){
                $query->select('title', 'content', 'meta_title', 'meta_description', 'keywords', 'og_title', 'og_description', 'twitter_title', 'twitter_description', 'meta_image');
            }

            if($page_type == 'find_us' || $page_type == 'news'){
                $query->select('title', 'meta_title', 'meta_description', 'keywords', 'og_title', 'og_description', 'twitter_title', 'twitter_description', 'meta_image');
            }
            
            if($page_type == 'faq'){
                $query->select('title', 'meta_title', 'meta_description', 'keywords', 'og_title', 'og_description', 'twitter_title', 'twitter_description', 'meta_image');

                if($lang == 'ae'){
                    $selectFaq = ['id', 'ar_title as title', 'ar_content as content', 'sort_order'];
                }else{
                    $selectFaq = ['id', 'title', 'content', 'sort_order'];
                }
                $faqs = Faqs::select($selectFaq)->orderBy('sort_order','asc')->where('status', 1)->get();
            }
            if($page_type == 'contact_us'){
                $query->select('title', 'sub_title', 'content as address', 'heading1 as whatsapp', 'heading2 as phone', 'heading3 as email', 'meta_title', 'meta_description', 'keywords', 'og_title', 'og_description', 'twitter_title', 'twitter_description', 'meta_image');
            }
            if($page_type == 'about_us'){
                $query->select('title', 'sub_title', 'content', 'heading1', 'content1', 'image1', 'heading2', 'content2','meta_title', 'meta_description', 'keywords', 'og_title', 'og_description', 'twitter_title', 'twitter_description', 'meta_image');
            }

            $pageData = $query->first();

            if($pageData){
                $pageData->meta_image       = ($pageData->meta_image != NULL) ? uploaded_asset($pageData->meta_image) : '';
                if($pageData->image1){
                    $pageData->image1       = ($pageData->image1 != NULL) ? uploaded_asset($pageData->image1) : '';
                }
            }

            if($page_type == 'faq'){
                $pageData['faqs'] = $faqs;
            }
            $pageData = ($pageData) ? $pageData : [];
            return response()->json(['status' => true,"message"=>"Success","data" => $pageData],200);
        }else{
            return response()->json(['status' => false,"message"=>"No data found","data" => []],200);
        }
    }

    public function contactUs(Request $request){

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'phone' => 'required|numeric',
            'message' => 'required'
        ],[
            'name.required' => 'Please enter your name',
            'email.required' => 'Please enter your email',
            'phone.required' => 'Please enter your phone',
            'message.required' => 'Please enter your message'
        ]);

        if($validator->fails()){
            if($request->name == '' || $request->email == '' || $request->phone == '' || $request->message == ''){
                return response()->json(['status' => false, 'message' => 'Please make sure that you fill out all the required fields..', 'data' => []  ], 200);
            }else{
                $errors = $validator->errors();
                if ($errors->has('email')) {
                    return response()->json(['status' => false, 'message' => $errors->first('email'), 'data' => []  ], 200);
                }
                if ($errors->has('phone')) {
                    return response()->json(['status' => false, 'message' => $errors->first('phone'), 'data' => []  ], 200);
                }
                return response()->json(['status' => false, 'message' => 'Something went wrong', 'data' => []  ], 200);
            }
        }

        $con                = new Contacts;
        $con->name          = $request->name;
        $con->email         = $request->email;
        $con->phone         = $request->phone;
        $con->subject       = $request->subject;
        $con->message       = $request->message;
        $con->save();

        Mail::to(env('MAIL_ADMIN'))->queue(new ContactEnquiry($con));

        return response()->json(['status' => true,"message"=>"Thank you for getting in touch. Our team will contact you shortly.","data" => []],200);
    }

    public function news(Request $request){
        $limit = $request->limit ? $request->limit : 9;
        $offset = $request->offset ? $request->offset : 0;
        $lang       = $request->has('lang') ? (($request->lang == 'ar') ? 'ae' : $request->lang) : 'en';

        if($lang == 'ae'){
            $select= ['id', 'slug', 'ar_title as title', 'ar_content as content', 'image', 'blog_date', 'seo_title', 'seo_description', 'og_title', 'og_description', 'twitter_title', 'twitter_description', 'keywords', 'meta_image'];
        }else{
            $select = ['id', 'slug', 'title', 'content', 'image', 'blog_date', 'seo_title', 'seo_description', 'og_title', 'og_description', 'twitter_title', 'twitter_description', 'keywords', 'meta_image'];
        }

        $newsQuery = Blog::where('status',1)->orderBy('blog_date','desc')->select($select);
        
        $total_count = $newsQuery->count();
        $news = $newsQuery->skip($offset)->take($limit)->get();

        if($news){
            foreach($news as $new){
                $new->image = ($new->image != NULL) ? uploaded_asset($new->image) : '';
            }
        }


        $next_offset = $offset + $limit;

        $meta = PageTranslation::leftJoin('pages as p','p.id', '=' ,'page_translations.page_id')
                                    ->where('lang', $lang)
                                    ->where('p.type', 'news')
                                    ->select('page_translations.title', 'page_translations.meta_title', 'page_translations.meta_description', 'page_translations.keywords', 'page_translations.og_title', 'page_translations.og_description', 'page_translations.twitter_title', 'page_translations.twitter_description', 'page_translations.meta_image')
                                    ->first();
      
        if($meta){
            $meta->meta_image       = ($meta->meta_image != NULL) ? uploaded_asset($meta->meta_image) : '';
        }

        $meta = ($meta) ? $meta : [];
        $news = ($news) ? $news : [];
        return response()->json(['status' => true,"message"=>"Success","data" => $news, "total_count" => $total_count, "next_offset" => $next_offset,"page_data" => $meta],200);
    }

    public function newsDetails(Request $request){
        $slug = $request->has('slug') ? $request->slug : null;
        $lang       = $request->has('lang') ? (($request->lang == 'ar') ? 'ae' : $request->lang) : 'en';

        if($lang == 'ae'){
            $select= ['id', 'slug', 'ar_title as title', 'ar_content as content', 'image', 'blog_date', 'seo_title', 'seo_description', 'og_title', 'og_description', 'twitter_title', 'twitter_description', 'keywords', 'meta_image'];
        }else{
            $select = ['id', 'slug', 'title', 'content', 'image', 'blog_date', 'seo_title', 'seo_description', 'og_title', 'og_description', 'twitter_title', 'twitter_description', 'keywords', 'meta_image'];
        }
        
        if($slug != null){
            $newsQuery = Blog::where('slug', $slug)
                                ->where('status',1)
                                ->select($select)
                                ->orderBy('blog_date','desc')
                                ->first();
            if($newsQuery){
                $newsQuery->image = ($newsQuery->image != NULL) ? uploaded_asset($newsQuery->image) : '';
            }
            $newsQuery = ($newsQuery) ? $newsQuery : [];
            return response()->json(['success' => true,"message"=>"Success","data" => $newsQuery],200);
        }else{
            return response()->json(['success' => false,"message"=>"No data","data" => []],200);
        }
    }
}
