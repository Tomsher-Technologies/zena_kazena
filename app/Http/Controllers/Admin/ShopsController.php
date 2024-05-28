<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Shop;
use App\Models\ShopUsers;
use App\Models\User;
use Illuminate\Http\Request;
use Hash;
use Validator;

class ShopsController extends Controller
{

    public function __construct()
    {
        $this->middleware('admin');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $sort_search = null;
        if ($request->has('search')) {
            $sort_search = $request->search;
        }

        $query = Shop::select("*");
        if($sort_search){  
            $query->Where(function ($query) use ($sort_search) {
                    $query->orWhere('name', 'LIKE', "%$sort_search%")
                    ->orWhere('name_ar', 'LIKE', "%$sort_search%")
                    ->orWhere('address', 'LIKE', "%$sort_search%")
                    ->orWhere('address_ar', 'LIKE', "%$sort_search%")
                    ->orWhere('phone', 'LIKE', "%$sort_search%")
                    ->orWhere('email', 'LIKE', "%$sort_search%");
            });                    
        }
                        
        $query->orderBy('id','DESC')->get();

        $shops = $query->paginate(15);
        return view('backend.shop.index', compact('shops', 'sort_search'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.shop.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'name_ar' => 'required',
            'phone' => 'required',
            'email' => 'required|email|unique:users',
            'address' => 'required',
            'address_ar' => 'required',
            'lat' => 'required',
            'long' => 'required',
            'password' => 'required',
        ],[
            'name_ar.required' => 'Name in arabic is required',
            'address_ar.required' => 'Address in arabic is required',
        ]);
 
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $shop = Shop::create([
            'name' => $request->name,
            'name_ar' => $request->name_ar,
            'phone' => $request->phone,
            'address' => $request->address,
            'address_ar' => $request->address_ar,
            'email' => $request->email,
            'delivery_pickup_latitude' => $request->lat,
            'delivery_pickup_longitude' => $request->long,
            'status' => 1,
        ]);
        if($shop->id){
            $user = new User;
            $user->name = $request->name;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->user_type = "shop";
            $user->password = Hash::make($request->password);
            if($user->save()){
                $shopuser = new ShopUsers;
                $shopuser->user_id = $user->id;
                $shopuser->shop_id = $shop->id;
                $shopuser->save();
            }
        }
        

        flash(translate('Shop has been created successfully'))->success();
        return redirect()->route('admin.shops.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Shop  $shop
     * @return \Illuminate\Http\Response
     */
    public function show(Shop $shop)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Shop  $shop
     * @return \Illuminate\Http\Response
     */
    public function edit($shop)
    {
        $shops = Shop::select('shops.*','su.user_id')
                    ->leftJoin('shop_users as su','su.shop_id','=','shops.id')
                    ->where('shops.id','=',$shop)
                    ->get();
               
        return view('backend.shop.edit', compact('shops'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Shop  $shop
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'name_ar' => 'required',
            'phone' => 'required',
            'email' => 'required|email|unique:users,email,'.$request->user_id,
            'address' => 'required',
            'address_ar' => 'required',
            'lat' => 'required',
            'long' => 'required',
        ],[
            'name_ar.required' => 'The name in arabic field is required',
            'address_ar.required' => 'The address in arabic field is required',
        ]);
 
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        $shop = Shop::find($id);
        $shop->name = $request->name;
        $shop->name_ar = $request->name_ar;
        $shop->phone = $request->phone;
        $shop->email = $request->email;
        $shop->address = $request->address;
        $shop->address_ar = $request->address_ar;
        $shop->delivery_pickup_latitude = $request->lat;
        $shop->delivery_pickup_longitude = $request->long;
        $shop->save();

        $user = User::find($request->user_id);
        $user->name = $request->name;
        $user->phone = $request->phone;
        $user->email = $request->email;
        if(strlen($request->password) > 0) {
            $user->password = Hash::make($request->password);
        }
        if($user->save()) {
            flash(translate('Shop details has been updated successfully'))->success();
        }else{
            flash(translate('Something went wrong'))->error();
        }
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Shop  $shop
     * @return \Illuminate\Http\Response
     */
    public function destroy(Shop $shop)
    {
        $shop->delete();
        flash(translate('Shop has been successfully deleted'))->success();
        return redirect()->route('admin.shops.index');
    }

    public function bulk_shop_delete(Request $request)
    {
        $users = [];
        if ($request->id) {
            foreach ($request->id as $shop_id) {
                Shop::destroy($shop_id);
                $shopusers = ShopUsers::where('shop_id',$shop_id)->get();
                $user_id = $shopusers[0]->user_id;
                ShopUsers::where('shop_id',$shop_id)->delete();
                User::where('id',$user_id)->delete();
            }
        }

        return 1;
    }
    public function delete(Request $request)
    {
        $shop_id = $request->id;
        Shop::destroy($shop_id);
        $shopusers = ShopUsers::where('shop_id',$shop_id)->get();
        $user_id = $shopusers[0]->user_id;
        ShopUsers::where('shop_id',$shop_id)->delete();
        User::where('id',$user_id)->delete();
        return 1;
    }
}
