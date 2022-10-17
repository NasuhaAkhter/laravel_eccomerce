<?php

namespace App\Queries;

use App\Models\User;
use App\Models\Franchise;
use App\Models\FranchiseDailySchedule;
use App\Models\Galary;
use App\Models\Product;
use App\Models\PartnerShop;
use App\Models\ShipingPrice;
use Auth;
date_default_timezone_set('Asia/Dhaka');
trait FranchiseQuery      
{ 
    public function getGalleryListQuery($data){
      $q = Galary::orderBy('id', "desc");
        $srt = $data['str'];
        if($srt){
        }   
        return $q->paginate($data['pageSize']);
    }
    public function AddProductImageQuery($data){
      $product = Product::where('id', '>=', $data['id'])->limit(500)->get();
      foreach($product as $value){
        $ob = [
          'image' => $value['image'],
        ];
        Galary::firstOrCreate($ob);
      }
      return Galary::orderBy('id', "desc")->get();
    }
    public function getAlldailyScheduleQuery($data){
      $q = FranchiseDailySchedule::with('franchise');
      $str = $data['str'];
      if($str){
        $q->whereHas('franchise', function ($q) use ($str){
          $q->where('name', 'like', "%$str%");
        });
      } 
      return $q->orderBy('id', "desc")->paginate($data['pageSize']);
    }
    public function postGalleryQuery($data){
       return Galary::create($data);
    }
    public function getFranchiesListQuery($data){
    $str = $data['str'];
    $user = Auth::user();
    $q = Franchise::with('user')
    ->select('id', 'name', 'phone', 'countryCode', 'target_price','user_id', 'country', 'city', 'address', 'lat', 'long', 'radious','slat','slng','sdistance');
    if ($str) {
      $q->where(function ($query) use ($str) {
        $query->where('name', 'like', "%$str%");
        $query->orWhere('phone', 'like', "%$str%");
        $query->orWhere('city', 'like', "%$str%");
        $query->orWhere('address', 'like', "%$str%");
      });
    } 
    // if($user->userType !='Admin'){
    //   $q->where('user_id', $user->id);
    // };
    return  $q->orderBy($data['colName'], $data['order'])->paginate($data['pageSize']);
    }
  // 
  public function updateFranchisesQuery(array $data){ 
     Franchise::where('id', $data['id'])->update($data);
    return Franchise::where('id', $data['id'])->with('user')
    ->select('id', 'name', 'phone', 'countryCode','target_price', 'user_id', 'country', 'city', 'address', 'lat', 'long', 'radious','slat','slng','sdistance')
    ->first();
  }
  public function createNewFranchisesQuery( $data){
    $d= Franchise::create($data);
    User::where('id',$d->user_id)->update([
      'franchise_id'=>$d->id
    ]);
    return $d;

  }
  public function storeScheduleQuery(array $data){
    $user = Auth::user();
    if($user->userType == 'Admin'){
      $data['franchise_id'] = $data['franchise_id'];
    }else{
      $data['franchise_id'] = $user->franchise_id;
    }
    $d = FranchiseDailySchedule::create($data);
    return FranchiseDailySchedule::where('id', $d->id)->with('franchise')->first();
  }
  public function updateScheduleQuery(array $data){
    FranchiseDailySchedule::where('id', $data['id'])->update($data);
    return FranchiseDailySchedule::where('id', $data['id'])->with('franchise')->first();
  }
  public function deleteFranchisesQuery(int $id){
     return Franchise::where('id', $id)->delete();
  }
  public function deleteScheduleQuery(int $id){
     return FranchiseDailySchedule::where('id', $id)->delete();
  }

    // shops 

    public function getAllPertnerShopsQuery($data){
        $str =$data['str']; 
        $shopId =$data['shopId']; 
        $q = PartnerShop::select('id', 'name','user_id', 'franchise_id','category_id');
        if($str){
           $q->where('name', 'like',"%$str%");
        }
        if($shopId){
           $q->where('id',$shopId);
        }
        // if(Auth::user()->userType != 'Admin'){
        //   $q->where('user_id', Auth::user()->id);
        // }
        return $q->orderBy($data['colName'], $data['order'])->paginate($data['pageSize']);
    }
    public function getAllPertnerShopsbyCategoryQuery($data,$id){
        $str =$data['str']; 
        $franchise_id =$data['franchise_id']; 
        $q = PartnerShop::where('category_id',$id)->where('franchise_id',$franchise_id)->select('id', 'name','user_id', 'franchise_id');
        if($str){
           $q->where('name', 'like',"%$str%");
        }
        // if(Auth::user()->userType != 'Admin'){
        //   $q->where('user_id', Auth::user()->id);
        // }
        return $q->orderBy($data['colName'], $data['order'])->paginate($data['pageSize']);
    } 
      
    public function getshopsQuery($data){
        $str =$data['str'];
        $franchiseId =isset($data['franchise_id']) ? $data['franchise_id'] : 0;
        $user = Auth::user();
        $q = PartnerShop::with('user','franchise','category') 
        ->select('id', 'name', 'phone','image', 'countryCode','user_id', 'category_id','country', 'address','city', 'franchise_id','status');

        // if(Auth::user()->userType != 'Admin'){
        //   $q->where('user_id', Auth::user()->id);
        // }
        if($user->userType == 'Admin'){ 
          $q->where('franchise_id', '!=', 0);
        }else{
          $q->where('franchise_id', $franchiseId);
        }
        if ($str) {
          $q->where(function ($query) use ($str) {
            $query->where('name', 'like', "%$str%");
          });
        }
        // if($franchiseId){
        // }
        return $q->orderBy($data['colName'], $data['order'])->paginate($data['pageSize']);
      }
      public function getshopsByIdQuery(int $id){
          $q = PartnerShop::where('id', $id)
          ->with('user','franchise','category')
          ->first();
          return $q;
          // if(Auth::user()->userType != 'SuperAdmin') {
          //   $q->where('user_id', Auth::user()->id);
          // }
          // return $q->select('id', 'name', 'phone', 'countryCode', 'user_id', 'country', 'address');
      }


      public function createNewshopQuery(array $data){ 
          $d = PartnerShop::create($data);
          User::where('id',$d->user_id)->update([
            'shop_id'=>$d->id
          ]);
          return $d;
      }
      public function updateshopQuery(array $data){
        $id = $data['id'];
        unset($data['id']); 
        PartnerShop::where('id', $id)->update($data);
          return PartnerShop::
          where('id', $id)->with('user')->select('id', 'name', 'phone','image',
           'countryCode', 'user_id', 'country', 'address', 'city','status')->first();
      }
      public function deleteshopQuery($id){
          return PartnerShop::where('id', $id)->delete();
      }
      public function getSingleFranchiseQuery($id){
          return Franchise::where('id', $id)->with('user')->first();
      }
     
      public function addShippingPriceQuery($data){
          $d =  ShipingPrice::create($data);
         
          return $d;
      } 
      public function editShippingPriceQuery($data){
          $d =  Franchise::where('id',$data['id'])->update([
            'slat' =>$data['slat'],
            'slng' =>$data['slng'],  
            'price' =>$data['price'],
            'outside_price' =>$data['outside_price'],
            'sdistance' =>$data['sdistance'],
          ]);
         
          return $d;
      }
      public function shippingPriceQuery($data){
        $user = Auth::user();
        $q = Franchise::where('id', '!=', 0);
        $str =$data['str']; 
        if($str) {
            $q->where(function ($query) use ($str) {
                $query->where('name', 'like', "%$str%");
            });
        }
        if($user->userType == "Admin"){
          return $q->paginate($data['pageSize']);
          // shop owner -> franchise_validation
          // if($str){
          //   Franchise::where('name', 'like',"%$str%")->paginate($data['pageSize']);
          // }
          // return Franchise::paginate($data['pageSize']);
        }
        else {
          return $q->where('id',$user->franchise_id)->paginate($data['pageSize']);
        }
      }


}
