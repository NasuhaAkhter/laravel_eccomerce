<?php

namespace App\Queries;

use App\Models\OnBoardScreen;
use App\Models\PrivacyPolicy;
use App\Models\Variation;
use App\Models\User;
use App\Models\Order;
use App\Models\PartnerShop;
use App\Models\Franchise;
use App\Models\Product;
use App\Models\PointSheet;
use App\Models\Slider;
use App\Models\Galary;
use App\Models\UserType;
use App\Models\Country;
use App\Models\deliveryAddress;
use App\Models\ExpenseCategory;
use App\Models\ExpenseSheet;
use App\Models\OrderDetails;
use App\Models\PlanRequest;
use App\Models\Plan;
use Auth;
use DB;
use Validator;
use DateTime;

use Illuminate\Support\Facades\Hash;
date_default_timezone_set('Asia/Dhaka');
trait UserQuery
{
    // dashboard start
    public function getRecentOrderTableQuery(){
        $user = Auth::user();
        if($user->userType == 'Admin'){
            return Order::with('items.product','schedule','users')->limit(5)->orderBy('id', 'desc')->get();
        }else{
            return Order::with('items.product','schedule','users')->where('franchise_id', $user->franchise_id)
            ->limit(5)->orderBy('id', 'desc')->get();
        }
    }
    public function getDriverTableQuery(){
        $user = Auth::user();
        if($user->userType == 'Admin'){
            return User::where('userType', 'Driver')->limit(5)->orderBy('id', 'desc')->get();
        }else{
            return User::where('franchise_id', $user->franchise_id)
            ->where('userType', 'Driver')->limit(5)->orderBy('id', 'desc')->get();
        }
    }
    public function getCustomerTableQuery(){
        $user = Auth::user();
        if($user->userType == 'Admin'){
            return User::where('userType', 'General')->limit(5)->orderBy('id', 'desc')->get();
        }else{
            return User::where('franchise_id', $user->franchise_id)->where('userType', 'Driver')->limit(5)->orderBy('id', 'desc')->get();
        }
    }
    public function getShopTableQuery(){
        $user = Auth::user();
        if($user->userType == 'Admin'){
            return PartnerShop::with('category', 'franchise','user')->limit(5)->orderBy('id', 'desc')->get();
        }else{
            return PartnerShop::with('category', 'franchise','user')->where('franchise_id', $user->franchise_id)
            ->limit(5)->orderBy('id', 'desc')->get();
        }
    }
    public function getFranchiseTableQuery(){
        $user = Auth::user();
        if($user->userType == 'Admin'){
            return Franchise::with('user')->limit(5)->orderBy('id', 'desc')->get();
        }else{
            return 0;
        }
    }
    public function getProductCountQuery(){
        $user = Auth::user();
        if($user->userType == 'Admin'){
            return Product::count();
        }else{
            return Product::where('franchise_id', $user->franchise_id)->count();
        }
    }
    public function getFranchiseCountQuery(){
        $user = Auth::user();
        if($user->userType == 'Admin'){
            return Franchise::count();
        }else{
            return 0;
        }
    }
    public function getOrderCountQuery(){
        $user = Auth::user();
        if($user->userType == 'Admin'){
            return Order::count();
        }else{
            return Order::where('franchise_id', $user->franchise_id)->count();
        }
    }
    public function getCustomerCountQuery(){
        $user = Auth::user();
        if($user->userType == 'Admin'){
            return User::where('userType', 'General')->count();
        }else{
            return User::where('franchise_id', $user->franchise_id)
            ->where('userType', 'General')->count();
        }
    }
    public function getShopCountQuery(){
        $user = Auth::user();
        if($user->userType == 'Admin'){
            return PartnerShop::count();
        }else{
            return PartnerShop::where('franchise_id', $user->franchise_id)->count();
        }
    }
    public function getAllPackageQuery($data){
      $str = $data['str'];
        $id = Auth::user()->id;
        $q = Plan::where('id', '!=', 0)
        ->orderBy($data['colName'], $data['order']);
        if ($str) {
            $q->where(function ($query) use ($str) {
                $query->where('name', 'like', "%$str%");
            });
        }
        return $q->paginate($data['pageSize']);
    }
    public function addPackageQuery($data){
        return Plan::create($data);  
    }
    public function updatePackageQuery($data){
        return Plan::where('id', $data['id'])->update($data);
    }
    public function deletePackageQuery(int $id){
        return Plan::where('id', $id)->delete();
    }
    public function getdeliveredOrderCountQuery(){
        $user = Auth::user();
        if($user->userType == 'Admin'){
            return Order::where('status','Delivered')->count();
        }else{
            return Order::where('franchise_id', $user->franchise_id)
                  ->where('status','Delivered')->count();
        }
    }
    public function getMemberRequestCountQuery(){
        $user = Auth::user();
        if($user->userType == 'Admin'){
            return PlanRequest::where('status','Pending')->count();
        }else{
            return PlanRequest::where('franchise_id', $user->franchise_id)
                  ->where('status','Pending')->count();
        }
        
    }
    public function get_total_pending_orderQuery(){
        $user = Auth::user();
        if($user->userType == 'Admin'){
            return Order::where('status','Pending')->count();
        }else{
            return Order::where('franchise_id', $user->franchise_id)
                  ->where('status','Pending')->count();
        }
    }
    public function getdriverCountQuery(){
        $user = Auth::user();
        if($user->userType == 'Admin'){
            return User::where('userType', 'Driver')->count();
        }else{
            return User::where('franchise_id', $user->franchise_id)
            ->where('userType', 'Driver')->count();
        }
    }
    public function getTotalAmmountQuery(){
        $user = Auth::user();
        if($user->userType == 'Admin'){
            return Order::select(DB::raw('sum(subTotal) as total_ammount'))
            // ->groupBy('id')
            ->first();
        }else{
            return Order::where('franchise_id', $user->franchise_id)
            ->select( DB::raw('sum(subTotal) as total_ammount'))
            // ->groupBy('subTotal')
            ->first();
        } 
    }
    // public function getTodaysProfitQuery(){
    //     $user = Auth::user();
         
    // }
    // public function getTodaysExpenseQuery(){
    //     $user = Auth::user();
         
    // }
    // public function getTodaysSaleQuery(){
    //     $user = Auth::user();
         
    // }
    // public function getTodaysCashbackQuery(){
    //     $user = Auth::user();
         
    // }
    // dashboard end
    /**
     * getUserWithLimit | Get a list of users with a limit..
     *
     * @param  int $limit
     * @return array
     */
    public function lightModeSaveQuery(){
        $user = Auth::user();
        $save = User::where('id', $user->id)->update([
            'dark_mode' => 0
        ]);
        if($save == 1){
            return response()->json([
                'success' =>true
            ],200);
        }else{
            return response()->json([
                'msg' =>"Something went wrong .",
                'success' => false
            ], 401);
        }
    }
    public function darkModeSaveQuery(){  
        $user = Auth::user();
        $save = User::where('id', $user->id)->update([
            'dark_mode' => 1
        ]);
        if($save == 1){
            return response()->json([
                'success' =>true
            ],200);
        }else{
            return response()->json([
                'msg' =>"Something went wrong .",
                'success' => false
            ], 401);
        }
    }

    public function getUserWithLimit(int $limit){
        return User::limit($limit)->get();
    }
    public function getFranchiseIdQuery($data){
        $user = Auth::user();
        return Franchise::where('user_id', $user->id)->first();
    }
    /**
     * getAllCoustomersQuery | Get a list of users with a limit..
     *
     * @param  array  $keys optional
     * @return array
     */
    public function getAllCoustomersQuery($data){
        $str =$data['str'];
        // $user = Auth::user();
        // $auth_user = User::where('id', $user->id)->first();
        if($data['franchise_id'] == 0){
            $q = User::where('userType', 'General')->where('franchise_id','!=', 0 )->select('id','name', 'phone', 'countryCode', 'userType', 'country', 'state','address', 'franchise_id','lat', 'long')->with('deliveryAdddress');
        }else{
            $q = User::where('userType', 'General')->where('franchise_id', $data['franchise_id'] )->select('id','name', 'phone', 'countryCode', 'userType', 'country', 'state','address', 'franchise_id','lat', 'long')->with('deliveryAdddress');
        }
        if($str){ 
            $q->where(function($query) use ($str){
                $query->where('name' , 'like' ,"%$str%");
                $query->orWhere('phone' , 'like' ,"%$str%");
                $query->orWhere('city' , 'like' ,"%$str%");
                $query->orWhere('address' , 'like' ,"%$str%");
            });
        }
        $user =  $q->orderBy($data['colName'], $data['order'])->paginate($data['pageSize']);
        return $user;
    }
    public function getNewFranchiseOwnerQuery($data){
        return User::where([['userType', 'FranchiseOwner'],['franchise_id', 0 ]])
            ->select('id','name', 'phone', 'countryCode', 'userType', 'country', 'city','address', 'franchise_id','lat', 'long')
            ->orderBy($data['colName'], $data['order'])->get();
    }
    public function getAllFranchiseOwnerQuery($data){
        $user = Auth::user();
        // $auth_user = User::where('id', $user->id)->first();
        // if($user->userType == 'Admin'){
        $user = User::where([['userType', 'FranchiseOwner'],['franchise_id', $user->franchise_id ]])
            ->select('id','name', 'phone', 'countryCode', 'userType', 'country', 'city','address', 'franchise_id','lat', 'long')->with('franchise')
            ->orderBy($data['colName'], $data['order'])->get();
        // }
        // else{
        //     $q = User::where('userType', 'FranchiseOwner')
        //     ->where('franchise_id', $user->franchise_id)
        //     ->select('id','name', 'phone', 'countryCode', 'userType', 'country', 'state','address', 'franchise_id','lat', 'long')->with('deliveryAdddress');
        // }
        // $user =  $q->get(); 
        return $user;
    }
    public function getAllCountryQuery($data){
       
        return Country::all();
    }
    public function getAllDriverQuery($data){
        $str =$data['str'];
        $user = Auth::user();
        // $auth_user = User::where('id', $user->id)->first();
        if($user->userType == 'Admin'){
            $q = User::where('userType', 'Driver')
            // ->where('franchise_id', $data['franchise_id'] )
            ->select('id','name', 'phone', 'countryCode', 'userType', 'country', 'city','address', 'franchise_id','lat', 'long','franchise_id')
            ->with('deliveryAdddress');
            if($str){
                $q->where(function($query) use ($str){
                    $query->where('name' , 'like' ,"%$str%");
                    $query->orWhere('phone' , 'like' ,"%$str%");
                    $query->orWhere('city' , 'like' ,"%$str%");
                    $query->orWhere('address' , 'like' ,"%$str%");
                });
            }
            $user =  $q->orderBy($data['colName'], $data['order'])->paginate($data['pageSize']);
            return $user;
        }else{
            $q = User::where('franchise_id', $user->franchise_id)
            ->where('userType', 'Driver')
            // ->where('franchise_id', $data['franchise_id'] )
            ->select('id','name', 'phone', 'countryCode', 'userType', 'country', 'city','address', 'franchise_id','lat', 'long','franchise_id')
            ->with('deliveryAdddress');
            if($str){
                $q->where(function($query) use ($str){
                    $query->where('name' , 'like' ,"%$str%");
                    $query->orWhere('phone' , 'like' ,"%$str%");
                    $query->orWhere('city' , 'like' ,"%$str%");
                    $query->orWhere('address' , 'like' ,"%$str%");
                });
            }
            $user =  $q->orderBy($data['colName'], $data['order'])->paginate($data['pageSize']);
            return $user;
        }
    }
    public function getAllShopOwnerQuery($data){  
        $str =$data['str'];
        $shopId = isset($data['shopId'])? $data['shopId'] : '' ;
        $user = Auth::user();
        if($user->userType == 'Admin'){
            $q = User::where('userType', 'ShopOwner')
            ->select('id','name', 'phone', 'countryCode', 'userType', 'country', 'city','address', 'lat', 'long','franchise_id');
            if($str){
                $q->where(function($query) use ($str){
                    $query->where('name' , 'like' ,"%$str%");
                    $query->orWhere('phone' , 'like' ,"%$str%");
                    $query->orWhere('city' , 'like' ,"%$str%");
                    $query->orWhere('address' , 'like' ,"%$str%");
                });
            }
            if($shopId) $q->where('shop_id', 0);
            $user =  $q->orderBy($data['colName'], $data['order'])->paginate($data['pageSize']);
            return $user;
        }else{
            $q = User::where('franchise_id', $user->franchise_id)
            ->where('userType', 'ShopOwner')
            ->select('id','name', 'phone', 'countryCode', 'userType', 'country', 'city','address', 'lat', 'long','franchise_id');
            if($str){
                $q->where(function($query) use ($str){
                    $query->where('name' , 'like' ,"%$str%");
                    $query->orWhere('phone' , 'like' ,"%$str%");
                    $query->orWhere('city' , 'like' ,"%$str%");
                    $query->orWhere('address' , 'like' ,"%$str%");
                });
            }
            if($shopId) $q->where('shop_id', 0);
            $user =  $q->orderBy($data['colName'], $data['order'])->paginate($data['pageSize']);
            return $user;
        }
    }
    public function getAllUserQuery($data){  
        $str =$data['str'];
        //  if(isset($data['paid_status'])){
            $paid_status = $data['paid_status'];
        // }
        $user = Auth::user();
        $q = User::whereIn('userType', ['General', 'Paid User'])->select('id','name', 'phone', 'countryCode', 'userType', 'country', 'city','address', 'lat', 'long','franchise_id');
        if($str){
            $q->where(function($query) use ($str){
                $query->where('name' , 'like' ,"%$str%");
                $query->orWhere('phone' , 'like' ,"%$str%");
                $query->orWhere('city' , 'like' ,"%$str%");
                $query->orWhere('address' , 'like' ,"%$str%");
            });
        }
        if($paid_status){
            if($paid_status == 'paid'){
                $q->where('userType', 'Paid User');
            }else if($paid_status == 'non-paid'){
                $q->where('userType', '!=', 'Paid User');
            }
        }
        if($user->userType == 'FranchiseOwner'){
            $franchise = Franchise::where('user_id', $user->id)->first();
            $q->where('franchise_id', $franchise->id);
        }
        else if($user->userType != 'Admin'){
            $q = User::where('franchise_id', $user->franchise_id);
        }
        return $q->orderBy($data['colName'], $data['order'])->paginate($data['pageSize']);
       
    }
    public function updatSingleUser($data){
       $d =  User::where('id', $data['id'])->update($data);
       if(!$d){
            return response()->json([
                'message' => "Update failed",
            ], 422);
       }
       $d= User::where('id', $data['id'])
       ->select('id', 'name', 'phone', 'countryCode', 'userType','city', 'country', 'state', 'address', 'lat', 'long','franchise_id')->first();
        return response()->json([
            'data' => $d,
        ], 200);
    }
    public function addDeliveryAddressQuery($data){
       return deliveryAddress::create($data);  
    }
    public function updateDeliveryAddressQuery($data){
       return deliveryAddress::where('id', $data['id'])->update($data);  
    }
    public function deleteAddressQuery (int $id){
        return deliveryAddress::where('id', $id)->delete();
    }
    public function getSingleAddressQuery($data){
       return deliveryAddress::where('id',$data['id'])->first();  
    }
    // profile
    public function profile_pic_updateQuery($data){
        $user = Auth::user();
        $result = User::where('id',$user->id)->update([
           'image' => $data['image'],
        ]); 
        if($result)
        return  User::where('id',$user->id)->first();  
    }
    public function password_updateQuery($data){ 
        $user = Auth::user();
        $current_password = $data['current_password'];
        //$authPass = Auth::user()->makeVisible('password');
         if(!Hash::check ($current_password , $user->password)){
            return response()->json([
                'msg' => "Current password doesn't match. Please try again!",
                'success' => false
            ],422);
        }else {
            if( $data['password'] == $data['confirm_password']){
                unset($data['current_password']);
                unset($data['confirm_password']);
                $validator = Validator::make($data, [ 
                    'password' => 'required|string|min:6|max:20',
                ]);
                if($validator->fails()){
                    return response()->json($validator->errors(), 422);
                }
                $data['password'] = Hash::make($data['password']);
                return User::where('id',$user->id)->update($data);
            }else{
                return response()->json([
                    'msg' => "Password doesn't match. Please try again!",
                    'success' => false
                ],422);
            }
        }
        
    }
    public function info_updateQuery($data){
         $user = Auth::user();
        // if($user->email != $data['email'] ){
        //     $validator = Validator::make($data, [ 
        //         'email' => 'required|string|email|max:255|unique:users',
        //     ]);
        //     if($validator->fails()){
        //         return response()->json($validator->errors(), 422);
        //     }
        // }
        $result = User::where('id',$user->id)->update( $data); 
        if($result)
        return  User::where('id',$user->id)->first();     
    }
    public function createUsers($data){
        $userCreate = User::create($data);  
        if($userCreate){
            return User::where('id', $userCreate->id)->select('id','name', 'phone', 'countryCode', 'userType', 'country', 'city','address', 'lat', 'long','franchise_id')->first();
        }
        return 0;
    }
    public function deleteUserQuery($id){
       return User::where('id', $id)->delete();  
    }
    // Galary 
    public function getGalaryQuery(){
        // $str = $data['str'];
        return Galary::orderBy('id','desc')->get();
        // if($str){
        //     where
        // }
        
    }
    public function getAlluserBalanceQuery($data){
        $str =$data['str']; 
        // $id = Auth::user()->id;
        $q = User::where('userType', 'General');
        if($str){
            $q->where(function($query) use ($str){
                $query->where('name' , 'like' ,"%$str%");
            });
        }
        $user =  $q->orderBy($data['colName'], $data['order'])
        ->paginate($data['pageSize']);
        return $user;
        // return User::where('userType',"General")->orderBy('id','desc')->paginate($data['pageSize']);
    }
    public function getAllScreensQuery($data){
        $planRequest =  OnBoardScreen::whereIn('id', [1,2,3])->orderBy($data['colName'], $data['order'])
        ->paginate($data['pageSize']);
        return $planRequest;
    }
    public function getPromotionScreensQuery($data){
        $planRequest =  OnBoardScreen::whereIn('id', [4])->orderBy($data['colName'], $data['order'])
        ->paginate($data['pageSize']);
        return $planRequest;
    }
    public function getAllrulesQuery($data){
        return PrivacyPolicy::first();
    }
    public function updateOnBoardScreenQuery($data){
        return OnBoardScreen::where('id', $data['id'])->update($data);
    }
    public function updatePromotionStatusQuery($data){
        return OnBoardScreen::where('id', $data['id'])->update($data);
    }
    public function updateRulesQuery($data){
        return PrivacyPolicy::where('id', $data['id'])->update($data);
    }
    public function getAllPlanRequestQuery($data){
        $str = isset($data['str']) &&  $data['str']!= '' &&  $data['str']!= 'undefined' ? $data['str'] : 'Pending' ;
        $q = PlanRequest::where('status', $str);
        $planRequest =  $q->orderBy($data['colName'], $data['order'])->with('franchise', 'user')
        ->paginate($data['pageSize']);
        return $planRequest;
    }
    public function insertPointsQuery($data){
        $d = $data['add_points'];
        User::where('id',$data['user_id'])->update([
            'duare_points' => DB::raw("duare_points+$d")
        ]);
        PointSheet::create([
            'user_id' => $data['user_id'],
            'amount' => DB::raw("amount+$d"),
            'type' => "Admin"
        ]);
        return User::where('id', $data['user_id'])->first();
    }
    public function updatePlanRequestStatusQuery($data){
        if($data['status'] == "Approved"){
            $plan= Plan::where('name', $data['package'])->first();
            $now = date("Y-m-d");
            $till = date('Y-m-d', strtotime("+"."$plan->duration", strtotime($now)));
            $user = User::where('id', $data['user_id'])->update([
                'isMember' => 1,
                'member_start_at' => $now,
                'member_end_at' => $till
            ]);
        }
        return PlanRequest::where('id', $data['id'])->update([
            'status' => $data['status']
        ]);
    }
    public function createGalaryQuery($data){
        return Galary::create($data);
    }
    public function deleteGalaryQuery($data){
        return Galary::where('id', $data['id'])->delete();
    }

    public function getAllBannersQuery(array $data){
        // return Slider::all();
        $str = $data['str'];
        $q = Slider::with('category');
        if($str){
            $q->where('title' , 'like' ,"%$str%");
        }
        // return $q->get();
        $banner =  $q->orderBy($data['colName'], $data['order'])->paginate($data['pageSize']);
        return $banner;
    }

    public function createBannerQuery(array $data){
        $d = Slider::create($data);
        return Slider::where('id', $d->id)->with('category')->first();
    }
    public function updateBannerQuery (array $data){
        $d = Slider::where('id', $data['id'])->update($data);
        return Slider::where('id', $data['id'])->with('category')->first();

    }
    public function deleteBannerQuery (int $id){
        return Slider::where('id', $id)->delete();
    }
    public function getUsertypeQuery(){         
        return UserType::all();
    }
    public function getAllTransactionQuery(array $data,int $id){         
        return PointSheet::where('user_id', $id)
        ->with('product', 'user', 'cupon')->paginate($data['pageSize']);
    } 
    
    public function updateTransactionQuery(array $data){
        // return $data;  
        $transaction = PointSheet::where('id', $data['id'])->first();
        $amount =  $data['amount'] - $transaction->amount;
        $balance = User::where('id', $transaction->user_id)
        ->increment('duare_points', $amount);
        // ->update([
        //     'duare_points' => DB::raw('`duare_points` - $transaction->amount') 
        // ]);       
        return PointSheet::where('id', $data['id'])->update([
            'type' =>$data['type'],
            'amount' =>$data['amount']
        ]); 
        
    } 
    public function getDeliveryAddressQuery(array $data,int $id){         
        return deliveryAddress::where('user_id', $id)->paginate($data['pageSize']);
    }
    public function getUserInfoQuery(int $id){         
        return User::where('id', $id)->with('franchise')->first();
    }

     // ************************ Expense **********************

         // Expense Category
    public function getExpenseCategoryQuery($data){
        $str = isset($data['str'])? $data['str'] : '';
        $franchise_id = isset($data['franchise_id'])? $data['franchise_id'] : '';
        $d = ExpenseCategory::orderBy('name','asc');
        if($str) $d->where('name' , 'like' ,"%$str%");
        if($franchise_id) $d->where('franchise_id'  ,$franchise_id);
        return $d->get();
    }
    public function addExpenseCategoryQuery($data){
        return  ExpenseCategory::create($data);
    }
    public function updateExpenseCategoryQuery($data){
        return  ExpenseCategory::where('id',$data['id'])->update($data);
    }
    public function deleteExpenseCategoryQuery($data){
        return ExpenseCategory::where('id',$data['id'])->delete();
    }

    // Expense Sheets

    public function getExpenseSheetsQuery($data){
        $franchise_id = isset($data['franchise_id'])? $data['franchise_id'] : '';
        $expense_category_id = isset($data['expense_category_id'])? $data['expense_category_id'] : '';
        $date_start = isset($data['date_start'])? $data['date_start'] : '';
        $date_end = isset($data['date_end'])? $data['date_end'] : '';
        $d = ExpenseSheet::orderBy('id','desc')->with('category');
        
        if($expense_category_id) $d->where('expense_category_id'  ,$expense_category_id);
        if($franchise_id) $d->where('franchise_id'  ,$franchise_id);
        if($date_start && $date_end) $d->whereBetween('created_at', array($date_start, $date_end));
        return $d->paginate(20);
    }
    public function getTotalExpenseSheetsQuery($data){
        $franchise_id = isset($data['franchise_id'])? $data['franchise_id'] : '';
        $expense_category_id = isset($data['expense_category_id'])? $data['expense_category_id'] : '';
        $date_start = isset($data['date_start'])? $data['date_start'] : '';
        $date_end = isset($data['date_end'])? $data['date_end'] : '';
        $d = ExpenseSheet::orderBy('id','desc');
        
        if($expense_category_id) $d->where('expense_category_id'  ,$expense_category_id);
        if($franchise_id) $d->where('franchise_id'  ,$franchise_id);
        if($date_start && $date_end) $d->whereBetween('created_at', array($date_start, $date_end));
        return $d->sum('amount');
    }
    public function addExpenseSheetsQuery($data){
        return  ExpenseSheet::create($data);
    }
    public function updateExpenseSheetsQuery($data){
        ExpenseSheet::where('id',$data['id'])->update($data);
        return  ExpenseSheet::where('id',$data['id'])->with('category')->first();;
    }
    public function deleteExpenseSheetsQuery($data){
        return ExpenseSheet::where('id',$data['id'])->delete();
    }
    // Statistics
    public function getExpenseStatisticsQuery($data){ 
        $franchise_id = isset($data['franchise_id'])? $data['franchise_id'] : '';
        $date_start = isset($data['date_start'])? $data['date_start'] : '';
        $date_end = isset($data['date_end'])? $data['date_end'] : '';
        $d = ExpenseSheet::orderBy('id','desc');
        if($franchise_id) $d->where('franchise_id',$franchise_id);
        if($date_start && $date_end) $d->whereBetween('created_at', array($date_start, $date_end));
        return $d->sum('amount');  
    }
    public function getSaleStatisticsQuery($data){
        $range = [];
        if( $data['date_start'] && $data['date_end']){
            $range =[
                $data['date_start'], $data['date_end']
            ];
        }
        // return $range
        $franchise_id = isset($data['franchise_id'])? $data['franchise_id'] : '';
        // $d = OrderDetails::with('order')
        //                 ->whereHas('order', function ($query){
        //                         $query->where('status','Delivered');
        //                     })
        //                 ->orderBy('id','desc');
        // if($franchise_id) $d->where('franchise_id'  ,$franchise_id);
        // $d->whereHas('order', function ($query)  use($range){
        //      $query->whereBetween('orderDate', $range);
        // });
        // // if($data['date_start'] && $data['date_end']) $d->whereBetween('created_at', array($data['date_start'], $data['date_end']));
        // return $d->sum('total_price');
        $d = Order::where('status','Delivered')->whereBetween('orderDate', $range)->orderBy('id','desc');
        if($franchise_id) $d->where('franchise_id' ,$franchise_id);
        return $d->sum('total');
    }
    public function getGrossProfitStatisticsQuery($data){
        $range = [];
        if( $data['date_start'] && $data['date_end']){
            $range =[
                $data['date_start'], $data['date_end']
            ];
        }
        $franchise_id = isset($data['franchise_id'])? $data['franchise_id'] : '';
        // $date_start = isset($data['date_start'])? $data['date_start'] : '';
        // $date_end = isset($data['date_end'])? $data['date_end'] : '';
        // $d = OrderDetails::with('order')
        //                 ->whereHas('order', function ($query){
        //                         $query->where('status','Delivered');
        //                      })
        //                 ->orderBy('id','desc');
        // if($franchise_id) $d->where('franchise_id'  ,$franchise_id);
        // $d->whereHas('order', function ($query)  use($range){
        //     $query->whereBetween('orderDate', $range);
        // });
        $d = Order::where('status','Delivered')->whereBetween('orderDate', $range)->orderBy('id','desc');
        if($franchise_id) $d->where('franchise_id' ,$franchise_id);
        $t = OrderDetails::whereIn('order_id', $d->pluck('id'))->pluck('variation_id');
        $V = Variation::whereIn('id', $t)->get();
        $orderSum = $d->sum('total');
        $variationSum = $V->sum('purchase_price');
        $grossProfit = $orderSum - $variationSum;
        return $grossProfit;
    }
    public function getProfitStatisticsQuery($data){
        $franchise_id = isset($data['franchise_id'])? $data['franchise_id'] : '';
        $date_start = isset($data['date_start'])? $data['date_start'] : '';
        $date_end = isset($data['date_end'])? $data['date_end'] : '';
        $d = OrderDetails::orderBy('id','desc');
        
        if($franchise_id) $d->where('franchise_id'  ,$franchise_id);
        if($date_start && $date_end) $d->whereBetween('created_at', array($date_start, $date_end));
        return $d->sum('total_price');
    }
    
    public function getCashbackStatisticsQuery($data){
        $range = [];
        if( $data['date_start'] && $data['date_end']){
            $range =[
                $data['date_start'], $data['date_end']
            ];
        }
        $franchise_id = isset($data['franchise_id'])? $data['franchise_id'] : '';
         
        $d = Order::where([['status','Delivered'], ['paymentType', 'Redeem Cashback']])->whereBetween('orderDate', $range)->orderBy('id','desc');
        if($franchise_id) $d->where('franchise_id' ,$franchise_id);
        return $d->sum('subTotal');
    }

    // Send push notifiction
    
    public function getCustomerDeviceToken($franchise_id){
        return User::where([['franchise_id',$franchise_id],['appToken','!=',null],['userType','General']])->pluck('appToken');
        // return User::whereIn('id',[34,171])->pluck('appToken');
    }
    public function getSelectedCustomerDeviceToken($user_id){
         return User::whereIn('id',$user_id)->where([['appToken','!=',null],['userType','General']])->pluck('appToken');
    }


}
 