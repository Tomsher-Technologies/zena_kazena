<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Notifications\ForgotPassword;
use App\Notifications\EmailVerificationNotification;
use App\Models\Customer;
use App\Models\Address;
use App\Models\Brand;
use App\Models\BrandTranslation;
use App\Models\Category;
use App\Models\Product;
use Carbon\Carbon;
use App\Models\User;
use Validator;
use Hash;
use Str;
use File;
use Storage;
use DB;

class ApiAuthController extends Controller 
{
  
    public function signup(Request $request) 
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|between:2,100',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|min:6',
            'phone_number' => 'required|unique:users,phone',
        ]);
        if($validator->fails()){
            if($request->name == '' || $request->email == '' || $request->password == '' || $request->phone_number == ''){
                return response()->json(['status' => false, 'message' => 'Please make sure that you fill out all the required fields..', 'data' => []  ], 200);
            }else{
                $errors = $validator->errors();
                if ($errors->has('name')) {
                    return response()->json(['status' => false, 'message' => $errors->first('name'), 'data' => []  ], 200);
                }
                if ($errors->has('email')) {
                    return response()->json(['status' => false, 'message' => $errors->first('email'), 'data' => []  ], 200);
                }
                if ($errors->has('password')) {
                    return response()->json(['status' => false, 'message' => $errors->first('password'), 'data' => []  ], 200);
                }
                if ($errors->has('phone_number')) {
                    return response()->json(['status' => false, 'message' => $errors->first('phone_number'), 'data' => []  ], 200);
                }
                return response()->json(['status' => false, 'message' => 'Something went wrong', 'data' => []  ], 200);
            }
        }

        $user = new User([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone_number,
            'password' => Hash::make($request->password),
            'verification_code' => rand(100000, 999999)
        ]);
        $user->save();
        $details = [
            'name' => $request->name,
            'subject' => 'Welcome to '.env('APP_NAME').'!',
            'body' => " <p> We are thrilled to welcome you to ".env('APP_NAME').".</p><br> 
            <p>To start exploring, simply log in to your account using the credentials you provided during registration. If you have any questions or need assistance, please don't hesitate to reach out to our customer support team.</p><br>
            <p>Thank you for choosing ".env('APP_NAME').". We're here to make your shopping experience extraordinary, and we can't wait to see what you discover.</p><br><p>Welcome aboard, and happy shopping!</p><br> "
        ];
       
        \Mail::to($request->email)->send(new \App\Mail\SendMail($details));

        $otp = generateOTP($user);
        $data['message'] = generateOTPMessage($user->name, $otp['otp']); 
        $data['phone'] = $user->phone;   
        $sendStatus = sendOTP($data); 
        
        $customer = new Customer; 
        $customer->user_id = $user->id; 
        $customer->save(); 

        return response()->json([
            'status' => true,
            'message' => translate('Registration Successful. OPT has been sent to your phone, please verify and log in to your account.'),
            'data' => $user->id,
            'otp' => $otp['otp']
        ], 200);
    }

    public function login(Request $request){
        $email      = $request->email;
        $password   = $request->password;

        $user = User::whereIn('user_type', ['customer'])->where('email', $email)->first();
        if ($user != null) {
            if (Hash::check($password, $user->password)) {
                return $this->loginSuccess($user);
            } else {
                return response()->json(['status' => false, 'message' => 'Incorrect password.','data' => []], 200);
            }
        } else {
            return response()->json(['status' => false, 'message' => translate('User not found'), 'data' => []], 200);
        }
    }

    protected function loginSuccess($user)
    {
        $token = $user->createToken('API Token')->plainTextToken;
        return response()->json([
            'status' => true,
            'message' => translate('Successfully logged in'),
            'access_token' => $token,
            'token_type' => 'Bearer',
            'expires_at' => null,
            'user' => [
                'id' => $user->id,
                'type' => $user->user_type,
                'name' => $user->name,
                'email' => $user->email,
                'avatar' => storage_link($user->avatar),
                'avatar_original' => api_upload_asset($user->avatar_original),
                'phone' => $user->phone
            ]
        ],200);
    }

    public function loginWithOTP(Request $request){
        $phone = $request->phone;

        $user = User::whereIn('user_type', ['customer'])->where('phone', $phone)->first();
        if ($user != null) {
            $otp = generateOTP($user);

            $data['message'] = generateOTPMessage($user->name, $otp['otp']); 
            $data['phone'] = $phone;
            $sendStatus = sendOTP($data);
            $sendStatus = true;
            return response()->json([
                                'status' => true,
                                'message' => translate('An OTP has been sent to the provided mobile number. Please check your messages.'),
                                'data' => [
                                    'sent' => $sendStatus ? true : false,
                                    'user_id' => $user->id,
                                    'expiry' => date('Y-m-d H:i:s',strtotime($otp['otp_expiry']))
                                ]
                            ], 200);
        } else {
            return response()->json(['status' => false, 'message' => translate('User not found'), 'data' => []], 200);
        }
    }

    public function verifyOTP(Request $request){
        $userId = $request->user_id;
        $otp = $request->otp;

        if ($userId == '' || $otp == '') {
            return response()->json(['status'=>false,'message'=>'Invalid details.','data' => []],200);
        }else{
            $user = User::find($userId);
            if($user){
                $verify = verifyUserOTP($user, $otp);
                if($verify){
                    return $this->loginSuccess($user);
                }else{
                    return response()->json(['status' => false, 'message' => translate('Invalid or expired OTP.'), 'data' => null], 200);
                }
            }else{
                return response()->json(['status' => false, 'message' => translate('User not found'), 'data' => []], 200);
            }
        }
    }

    public function resendOTP(Request $request){
        $userId = $request->user_id;
        $user = User::find($userId);
        if ($user != null) {
            $otp = generateOTP($user);
            $data['message'] = generateOTPMessage($user->name, $otp['otp']); 
            $data['phone'] = $user->phone;
            $sendStatus = sendOTP($data);
            $sendStatus = true;
            return response()->json([
                                'status' => true,
                                'message' => translate('An OTP has been resend sent to the provided mobile number. Please check your messages.'),
                                'data' => [
                                    'sent' => $sendStatus ? true : false,
                                    'user_id' => $user->id,
                                    'expiry' => date('Y-m-d H:i:s',strtotime($otp['otp_expiry']))
                                ]
                            ], 200);
        } else {
            return response()->json(['status' => false, 'message' => translate('User not found'), 'data' => []], 200);
        }
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return response()->json([  
            'status' => true,
            'message' => translate('Successfully logged out'),
            'data' => []
        ],200);
    }

    public function user(Request $request)
    {
        $user = User::with(['addresses'])->find($request->user());
                    
        if(isset($user[0])){
            $data['id']             = $user[0]['id'] ?? '';
            $data['name']           = $user[0]['name'] ?? '';
            $data['email']          = $user[0]['email'] ?? '';
            $data['phone']          = $user[0]['phone'] ?? '';
            $data['phone_verified'] = $user[0]['is_phone_verified'] ?? '';
            $dataAddress            = $user[0]['addresses'] ?? [];
            $address = [];
            if($dataAddress){
                foreach($dataAddress as $adds){
                    $address[] = [
                        'id'            => $adds['id'],
                        'type'          => $adds['type'],
                        'name'          => $adds['name'],
                        'address'       => $adds['address'],
                        'country_id'    => $adds['country_id'],
                        'country_name'  => ($adds['country_id'] != NULL) ? $adds['country']['name'] : $adds['country_name'],
                        'state_id'      => $adds['state_id'],
                        'state_name'    => ($adds['state_id'] != NULL) ? $adds['state']['name'] : $adds['state_name'],
                        'city_id'       => $adds['city_id'],
                        'city_name'     => $adds['city'],
                        'latitude'      => $adds['latitude'],
                        'longitude'     => $adds['longitude'],
                        'phone'         => $adds['phone'],
                        'is_default'    => $adds['set_default']
                    ];
                }
            }

            $data['address'] = $address;
            return response()->json([ 'status' => true, 'message' => 'Success', 'data' => $data]);
        }else{
            return response()->json([ 'status' => false, 'message' => 'User details not found.', 'data' => []]);
        }                                                           
    }
    public function updateProfile(Request $request){
        $id = $request->user()->id;
        $validator = Validator::make($request->all(), [
            'phone_number' => 'nullable|unique:users,phone,'.$id,
        ]);
        
        if($validator->fails()){
            $errors = $validator->errors();
            if ($errors->has('phone_number')) {
                return response()->json(['status' => false, 'message' => $errors->first('phone_number'), 'data' => []  ], 200);
            }
        }
        
        $name   = $request->name;
        $phone  = $request->phone_number;
       
        $user = User::find($id);

        $old_phone = $user->phone;
        if($old_phone != $phone){
            $user->is_phone_verified = 0;
        }
        $user->phone = $phone;
        $user->name = $name;
        $user->save();

        $data['id']             = $user->id ?? '';
        $data['name']           = $user->name ?? '';
        $data['email']          = $user->email ?? '';
        $data['phone']          = $user->phone ?? '';
        $data['phone_verified'] = $user->is_phone_verified ?? '';
        return response()->json(['status' => true,'message' => 'User details updated successfully', 'data' => $data],200);
    }

      // Update user profile image
      public function updateProfileImage(Request $request){
        $userId = $request->user()->id;

        $userdata = User::find($userId);
     
        if($userdata){
            $presentImage = $userdata->avatar;

            $profileImage = '';
            if ($request->hasFile('profile_image')) {
                $uploadedFile = $request->file('profile_image');
                $filename =    strtolower(Str::random(2)).time().'.'. $uploadedFile->getClientOriginalName();
                $name = Storage::disk('public')->putFileAs(
                    'users/'.$userId,
                    $uploadedFile,
                    $filename
                );
                $profileImage = Storage::url($name);
                
                if($presentImage != '' && File::exists(public_path($presentImage))){
                    unlink(public_path($presentImage));
                }
                $userdata->avatar = $profileImage;
                $userdata->save();
            
                return response()->json(['status' => true,'message' => 'User image updated successfully', 'data' => ['profile_image' => asset($profileImage)]]);
            }else{
                return response()->json(['status' => false,'message' => 'Failed to update user image', 'data' => []]);
            }
        }else{
            return response()->json(['status' => false,'message' => 'User not found', 'data' => []]);
        } 
    }

    public function changePassword(Request $request)
    {
        $userId = $request->user()->id;
        $user = User::find($userId);
        if (!Hash::check($request->current_password, $user->password)){
            return response()->json(['status' => false,'message' => 'Old password is incorrect', 'data' => []],200);
        }
 
        // Current password and new password same
        if (strcmp($request->get('current_password'), $request->new_password) == 0){
            return response()->json(['status' => false,'message' => 'New Password cannot be same as your current password.', 'data' => []],200);
        }

        $user->password =  Hash::make($request->new_password);
        $user->save();
        return response()->json(['status' => true,'message' => 'Password Changed Successfully', 'data' => []],200);
    }

    public function addAddress(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'address' => 'required',
            'country_id' => 'required',
            'state_id' => 'required',
            'city_id' => 'required',
            'postal_code' => 'required',
            'phone' => 'required'
        ]);
        if($validator->fails()){
            return response()->json(['status' => false, 'message' => 'Please make sure that you fill out all the required fields..', 'data' => []  ], 200);
        }

        $userId = $request->user()->id;
        $user = User::find($userId);
        
        if($user){
            $address                = new Address;
            $address->user_id       = $userId;
            $address->name          = $request->name;
            $address->address       = $request->address;
            $address->country_id    = $request->country_id;
            $address->state_id      = $request->state_id;
            $address->city_id       = $request->city_id;
            $address->longitude     = $request->longitude;
            $address->latitude      = $request->latitude;
            $address->postal_code   = $request->postal_code;
            $address->phone         = $request->phone;
            $address->save();
            return response()->json(['status' => true,'message' => 'Address added Successfully', 'data' => []],200);
        }else {
            return response()->json(['status' => false, 'message' => 'User not found', 'data' => []], 200);
        }
    }

    public function updateAddress(Request $request)
    {
        $userId = $request->user()->id;
        $user = User::find($userId);
        $id = $request->address_id;
        if($user){
            $address = Address::findOrFail($id);
            $address->name          = $request->name;
            $address->address       = $request->address;
            $address->country_id    = $request->country_id;
            $address->state_id      = $request->state_id;
            $address->city_id       = $request->city_id;
            $address->longitude     = $request->longitude;
            $address->latitude      = $request->latitude;
            $address->postal_code   = $request->postal_code;
            $address->phone         = $request->phone;
            $address->save();
            return response()->json(['status' => true,'message' => 'Address updated Successfully', 'data' => []],200);
        }else {
            return response()->json(['status' => false, 'message' => 'User not found', 'data' => []], 200);
        }
    }

    public function setDefaultAddress(Request $request){
        $userId = $request->user()->id;
        $user = User::find($userId);
        $id = $request->address_id;
        if($user){
            // Update all addresses to non-default first.
            Address::where('user_id',$userId)->update(['set_default'=>0]);
            // Make the selected address default.
            $address = Address::findOrFail($id);
            $address->set_default = 1;
            $address->save();
            return response()->json(['status' => true,'message' => 'Default address set Successfully', 'data' => []],200);
        }else{
            return response()->json(['status' => false, 'message' => 'User not found', 'data' => []], 200);
        }
    }

    public function deleteAddress(Request $request){
        $userId = $request->user()->id;
        $user = User::find($userId);
        $id = $request->address_id;
        if($user){
            $address = Address::findOrFail($id);
            $address->is_deleted = 1;
            $address->save();
            return response()->json(['status' => true,'message' => 'Address deleted successfully', 'data' => []],200);
        }else{
            return response()->json(['status' => false, 'message' => 'User not found', 'data' => []], 200);
        }
    }

    public function categories(Request $request){
        $lang = $request->lang;
        $limit = $request->limit;
        $offset = $request->offset;
        $parent_id = $request->parent_id;

        $categories = Category::where('parent_id', $parent_id)
                                ->orderBy('name','ASC')->skip($offset)->take($limit)->get();
        if($categories){
            $category = [];
            foreach($categories as $key=>$categ){
                $category[$key]['id'] = $categ->id;
                $category[$key]['name'] = $categ->getTranslation('name', $lang);
                $category[$key]['logo'] =  uploaded_asset($categ->icon);
            }
            $categories = $category;

            return response()->json(['status' => true,'message' => 'Data fetched successfully', 'data' => $categories, 'offset' => $offset + $limit],200);
        }else{
            return response()->json(['status' => false, 'message' => 'No data found', 'data' => []], 200);
        }
    }

    public function getAllBrands(){
        $brands = Brand::orderBy('id','asc')->get();
        if($brands){
            $logos = [];
            foreach ($brands as $brand) {
                $logos[] =  uploaded_asset($brand->logo);
            }
            return response()->json(['status' => true,'message' => 'Data fetched successfully', 'data' => $logos],200);
        }else{
            return response()->json(['status' => false, 'message' => 'No brands found', 'data' => []], 200);
        }
    }

    public function homeProducts(Request $request){
        $lang = $request->lang;
        $limit = $request->limit;

        $trending = Product::where('published', 1)->orderBy('num_of_sale', 'desc')->limit($limit)->get();

        $data['deal_week'] = $data['trending'] = [];
        if(isset($trending[0])){
            foreach($trending as $key=>$trend){
                $data['trending'][$key]['id'] = $trend->id;
                $data['trending'][$key]['name'] = $trend->getTranslation('name', $lang);
                $data['trending'][$key]['unit_price'] = $trend->unit_price;
                $data['trending'][$key]['unit'] = $trend->unit;
                $data['trending'][$key]['image'] =  uploaded_asset($trend->thumbnail_img);
            }
        }
       
        $deal_week = Product::where('todays_deal', 1)->orderBy('updated_at', 'desc')->limit($limit)->get();

        if(isset($deal_week[0])){
            foreach($deal_week as $key=>$deal){
                $data['deal_week'][$key]['id'] = $deal->id;
                $data['deal_week'][$key]['name'] = $deal->getTranslation('name', $lang);
                $data['deal_week'][$key]['unit_price'] = $deal->unit_price;
                $data['deal_week'][$key]['unit'] = $deal->unit;
                $data['deal_week'][$key]['image'] =  uploaded_asset($deal->thumbnail_img);
            }
        }
        $logos = [];
        $brands = Brand::orderBy('id','asc')->get();
        if($brands){
            $logos = [];
            foreach ($brands as $key1 => $brand) {
                $logos[$key1]['id'] =  $brand->id;
                $logos[$key1]['logo'] =  uploaded_asset($brand->logo);
            }
        }
        $data['brands'] = $logos;
        return response()->json(['status' => true,'message' => 'Data fetched successfully', 'data' => $data],200);
    }

    public function deal_trend_Listing(Request $request){
        $lang   = $request->lang;
        $limit  = $request->limit;
        $offset = $request->offset;
        $type   = $request->type;

        $data = [];
        if($type == 'deal'){
            $details = Product::where('todays_deal', 1)->orderBy('updated_at', 'desc')->skip($offset)->take($limit)->get();
        }elseif($type == 'trend'){
            $details = Product::where('published', 1)->orderBy('num_of_sale', 'desc')->skip($offset)->take($limit)->get();
        }

        if(isset($details[0])){
            foreach($details as $key=>$det){
                $data[$key]['id'] = $det->id;
                $data[$key]['name']         = $det->getTranslation('name', $lang);
                $data[$key]['unit_price']   = $det->unit_price;
                $data[$key]['unit']         = $det->unit;
                $data[$key]['image']        =  uploaded_asset($det->thumbnail_img);
            }
            return response()->json(['status' => true,'message' => 'Data fetched successfully', 'data' => $data, 'offset' => $offset + $limit],200);
        }else{
            return response()->json(['status' => false, 'message' => 'No data found', 'data' => []], 200);
        }
    }

    public function menuCategories(Request $request){
        $lang = $request->lang;
        $data = Category::with(['childrenRecursive'])->where('parent_id',0)->get();
        
        $categories = [];
        if(isset($data[0])){
            foreach($data as $key=>$dt){
                $categories[$key]['id'] = $dt->id;
                $categories[$key]['name'] = $dt->getTranslation('name', $lang);
                $categories[$key]['sub_category'] = getSubCategory($dt->childrenRecursive,$lang);
            }
        }
       
        return response()->json(['status' => true,'message' => 'Data fetched successfully', 'data' => $categories],200);
    }

    public function categoryProducts(Request $request){
        $category_id    = $request->category_id;
        $lang           = $request->lang;
        $limit          = $request->limit;
        $offset         = $request->offset;
        $sort           = $request->has('sort_by') ? $request->sort_by : '';

        if($category_id == 0){
            $subcategoryIds = [];
        }else{
            $subcategory = Category::where('id', $category_id)->first();
            $subcategoryIds = $subcategory->getAllChildren()->pluck('id')->toArray();
            array_push($subcategoryIds , $category_id );
        }
        $products = Product::whereIn('category_id', $subcategoryIds)->where('published', 1);
        if($sort == 'HL'){
            $products->orderBy('unit_price','DESC');
        }elseif($sort == 'LH'){
            $products->orderBy('unit_price','ASC');
        }
        $catProducts = $products->skip($offset)->take($limit)->get();

        if(isset($catProducts[0])){
            foreach($catProducts as $key=>$prod){
                $data[$key]['id'] = $prod->id;
                $data[$key]['name'] = $prod->getTranslation('name', $lang);
                $data[$key]['unit_price'] = $prod->unit_price;
                $data[$key]['unit'] = $prod->unit;
                $data[$key]['image'] =  uploaded_asset($prod->thumbnail_img);
            }
        }
        return response()->json(['status' => true,'message' => 'Data fetched successfully', 'data' => $data, 'offset' => ($offset + $limit) ],200);
    }
    
    public function forgetRequest(Request $request)
    {
        $email = $request->has('email') ? $request->email : '';
        if($email){
            $user = User::where('email', $request->email)->first();
            if (!$user) {
                return response()->json([
                    'status' => false,
                    'message' => translate('User is not found')], 200);
            }else{
                $user->verification_code = rand(100000, 999999);
                $user->save();
                $user->notify(new ForgotPassword($user));
                return response()->json([
                    'status' => true,
                    'message' => translate('Verification code is sent')
                ], 200);
            }
        }else{
            return response()->json([
                'status' => false,
                'message' => translate('Email is not found')], 200);
        }
    }

    public function resetRequest(Request $request)
    {
        $request->validate([
            'password' => 'required|string|min:6'
        ]);
        $code = $request->has('code') ? $request->code : '';
        $email = $request->has('email') ? $request->email : '';
        $password = $request->has('password')? trim($request->password): '';

        if($code != '' && $email != '' &&  $password != ''){
            $user = User::where('email', $email)->where('verification_code', $code)->first();
            if ($user != null) {
                $user->verification_code = null;
                $user->password = Hash::make($password);
                $user->save();
                return response()->json([
                    'status' => true,
                    'message' => translate('Your password is reset.Please login'),
                ], 200);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => translate('Invalid verification code'),
                ], 200);
            }
        }else {
            return response()->json([
                'status' => false,
                'message' => translate('Please fill all the fields'),
            ], 200);
        }
    }
}
