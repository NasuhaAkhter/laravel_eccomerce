<?php

namespace App\Queries;

use App\Models\Order;
use App\Models\ShipingPrice;
use App\Models\Variation;
use App\Models\Franchise;
use App\Models\OrderDetails;
use App\Models\deliveryAddress;
use App\Models\OrderShopStatus;
use App\Models\Coupon;
use App\Models\CouponUsedUser;
use App\Models\CouponUser;
use App\Models\OrderImage;
use App\Models\Income;
use App\Models\PointSheet;
use App\Models\OrderLimits;
use App\Models\FranchiseDailySchedule;
use App\Models\User; 
use Auth;
use DB;
date_default_timezone_set('Asia/Dhaka');
trait OrderQuery
{
    /** 
     * storeOrderQuery | store a order  with a..
     *  
     * @param  array $data
     * @return object
     */
    public function storeOrderQuery(array $data) 
    {
         return Order::create($data); 
    }
    public function updateOrderQuery(array $data)
    {
        return Order::where('id', $data['id'])->update($data); 
    }
    public function saveOrderLimitQuery(array $data){
        $user = Auth::user();
        $result = OrderLimits::where('franchise_id',$user->franchise_id )->update(
            [
                'limits' => $data['limit'],
            ], 
        );
        if($result){
            return $result;
        }else{
            return OrderLimits::create([
                'limits' => $data['limit'],
                'franchise_id' => $user->franchise_id
            ]);
        }
    }
    public function storeIncomeItemsQuery(array $data){
        return  Income::insert($data);
    }
    public function CountOrderScheduleQuery($data){
        $count = Order::where([['schedule_id',$data['schedule_id']],['orderDate',$data['date']]])->count();
        return $count;
    }
    public function updateOrderDetailsQuery($data){
        return OrderDetails::where('id',$data['id'])->update($data);
    }
    public function deleteOrderItemQuery(array $itemId, int $orderId){
        return OrderDetails::whereNotIn('id',$itemId)->where('order_id', $orderId)->delete();
    }
    public function updateProductPriceQuery($data){
        return Variation::where('id',$data['id'])->update($data);
    }
    public function updateOrderShopStatusQuery($data){
        return OrderShopStatus::where([['shop_id', $data['shop_id']],['order_id',$data['order_id']]])->update([
            'status' => $data['status'],
        ]);
    }
    public function checkIsAllOrderAcceptedQuery($id,$status){
        $allStatus =  OrderShopStatus::where('order_id',$id)->whereIn('status',$status)->count();
        $all =  OrderShopStatus::where('order_id',$id)->count();
        return $allStatus == $all ? true : false;
    }
    // public function updateOrderQuery($data){
    //     return Order::where('id',$data['id'])->update($data);
    // }
    public function deleteIncomeSheetQuery($id){
        return  Income::where('order_id',$id)->delete();
    }
    public function getUserAddress($id){
        return  deliveryAddress::where('id', $id)->first();
    }
    public function getfranchiseAddress($id){
        return  Franchise::where('id',$id)->first();
    }
    public function storeDuarePointSheetQeury($data){
        return PointSheet::insert($data);
    }
    public function updateUserDuarePointQuery($point,$id){
        return User::where('id',$id)->update([
            'duare_points'=> DB::raw("duare_points + $point")
        ]);
    }
    public function getOrderLimitQuery(){
        $user = Auth::user();
        return OrderLimits::where('franchise_id',$user->franchise_id )->first();
    }
    public function isAvailableSlotQuery(array $data){
        $totalDriver = 2;
        $total = $totalDriver * $data['max_order'];
        $dd = [
            'date' => $data['date'], 
            'schedule_id' => $data['schedule_id']
        ];
        $totalOrdered =   Order::where([['schedule_id',$data['schedule_id']],['orderDate',$data['date']]])->count();
        if($total <= $totalOrdered){
            return $this->coustomErrorMessage("Schedule not availble. Try next one!");
        } 
        return response()->json([
            "data" => 'Schedule availble! '
        ], 200);

    }
    public function getShippingPriceQuery(array $data){
        // $franchise_id = Auth::user()->franchise_id;
        $data['franchise_id'] = isset($data['franchise_id']) ? $data['franchise_id'] : 0;
        $franchise_id = $data['franchise_id'];
        $address = deliveryAddress::where('id', $data['id'])->first();
        $franchiseAddress =  ShipingPrice::where('franchise_id', $franchise_id)->first();
        $distance = $this->circle_distance($address['lat'],$address['long'],$franchiseAddress['lat'],$franchiseAddress['long']);
        if($distance > $franchiseAddress['distance']){
            return response()->json([
                "price" => $franchiseAddress['outside_price']
            ], 200);
        }
        return response()->json([
            "price" => $franchiseAddress['price']
        ], 200);
    }
    public function circle_distance($lat1, $lon1, $lat2, $lon2) {
        $rad = M_PI / 180;
        return acos(sin($lat2*$rad) * sin($lat1*$rad) + cos($lat2*$rad) * cos($lat1*$rad) * cos($lon2*$rad - $lon1*$rad)) * 6371;// Kilometers
    }
    /**
     * getShippingQuery | get shipping price  with a..
     *
     * @param  array $data
     * @return object
     */
    public function getShippingQuery(int $id)
    {
        return ShipingPrice::where('franchise_id', $id)->first();
    }
    /**
     * storeOrderItems | get shipping price  with a..
     *
     * @param  array $data
     * @return array 
     */
    // public function storeOrderItems(array $data)
    // {
    //     // \Log::info($data);
    //     $massData = [];
    //     $singleData = [
    //         'user_id' =>$data['user_id'],
    //         'order_id' =>$data['order_id'],
    //         'franchise_id' =>$data['franchise_id'],
    //     ]; 
    //     foreach($data['items'] as $value){
    //         $singleData['product_id'] = $value['product_id'];
    //         $singleData['variation_id'] = $value['variation_id'];
    //         $singleData['category_id'] = $value['category_id'];
    //         $singleData['product_category_id'] = $value['product_category_id'];
    //         $singleData['product_subcategory_id'] = $value['product_subcategory_id'];
    //         $singleData['partner_shop_id'] = $value['partner_shop_id'];
    //         $singleData['band_id'] = $value['band_id'];
    //         $singleData['quantity'] = $value['quantity'];  
    //         $singleData['price'] = $value['price'];
    //         $singleData['total_price'] = $value['price']* $value['quantity'];
    //         $singleData['discount'] = $value['discount'];
    //         array_push($massData, $singleData);
    //     }
    //     $d = OrderDetails::insert($massData);   
    //     return $d;
    // }
    /**
     * getAllOrdersQuery | get all orders  with a..
     *
     * @param  array $data
     * @return array 
     */ 
    public function updateProductStock(array $data){
        foreach ($data as $value) {
            $q = $value['quantity'];
           Variation::where('id',$value['variation_id'])->update([
            'stock'=> DB::raw("stock - $q")
           ]);
        }
        return 'Done';  
    }
    public function storeOrderShopStatusQuery($data){
        foreach ($data as $value) {
            $q = OrderShopStatus::firstOrCreate([
                'shop_id' => $value['shop_id'],
                'order_id' => $value['order_id'],
            ]); 
        }
        return;
    }
    public function getAllOrdersQuery(array $data){  
        $user = Auth::user();
        $q = Order::where('franchise_id', $user->franchise_id)->orderBy($data['colName'], $data['order'])->with('items','items.product', 'franchise','users', 'address', 'category', 'schedule','driver');
        $str = $data['str'];
        $shopId= isset($data['shopId'])? $data['shopId']: '';
        if($str) {
            // $q->where('name', 'like', "%$str%");
            // $query->where('name', 'like', "%$str%");
            // $query->orWhere('description', 'like', "%$str%");
            $q->whereHas('items', function ($q) use ($str){
                $q->whereHas('product', function ($q) use ($str){
                    $q->where('name', 'like', "%$str%");
                });
            });
            $q->orWhereHas('users', function ($q) use ($str){
                $q->where('name', 'like', "%$str%");
            });
        }
        // return $shopId;
        if($shopId){ 
            // return "something";
            $q->whereHas('items', function ($q1) use ($shopId){
                $q1->where('partner_shop_id', $shopId);
            });
            // $q->with(['items' => function ($q)  use ($shopId) {
            //     $q->where('partner_shop_id', $shopId);
            // }]);
        }
        return $q->paginate($data['pageSize']);
        // if($str){
    }
    public function getAvailableScheduleQuery(array $data){
        $q = FranchiseDailySchedule::where('franchise_id', $data['id'] )->get();
        \Log::info($data['id']);
        return $q ;
    }
    /**
     * getAllSingleOrdersQuery | get all  signle order  .
     *
     * @param  array $data
     * @return object 
     */
    public function getAllSingleOrdersQuery(int $id){
        $d= Order::where('id', $id)->with('franchise', 'users', 'address')->first();
        $e = OrderDetails::where('order_id',$id)->with('photo', 'variations', 'category', 'subCategory', 'product', 'brand', 'pshop')->get();;
        $d['items'] = $e;
        return $d;
    }
    /**
     * getAllSingleOrdersItemQuery | get all  signle order  .
     *
     * @param  array $data
     * @return array 
     */
    public function getAllSingleOrdersItemQuery(int $product_id,int $order_id, int $variation_id)
    {
        return  OrderDetails::where('product_id', $product_id)->where('order_id', $order_id)->where('variation_id', $variation_id)->first();
    }
    /**
     * getAllSingleOrdersItemQuery | get all  signle order  .
     *
     * @param  array $data
     * @return array 
     */
    public function checkOrderStatusQuery($data,$status){
        return Order::where([["status" , $status],['id',$data]])->count();
    }
    public function getOrderDetailsCQuery($id){
        return Order::where('id',$id)->with('details.product','details.variations')->first();
    }
    public function calculateAndStoreDuarePoint($id){
        $order_id = $id;
        $orderDetails = $this->getOrderDetailsCQuery($order_id);
        $details = $orderDetails->details;
        $subTotal = 0;
        $totalDuarePoints = 0;
        $duarePoints = [];
        // return $details;
        // $details->toArray();
        foreach($details as $value){
            $price = $value['variations']['price']*$value['quantity'];
            if($value['variations']['percentage']>0 || $value['variations']['discount'] > 0){
                $points = 0;
                if($value['variations']['percentage']> 0)  $points = ($price*$value['variations']['percentage'])/100;
                else if($value['variations']['discount']> 0)  $points = $value['variations']['discount'];
                $totalDuarePoints = $totalDuarePoints + $points;
                $pointsOb=[
                    'user_id'=>$orderDetails['user_id'],
                    'amount'=>$points,
                    'type'=>'Product',
                    'item_id'=>$value['product_id'],
                    'order_id'=>$orderDetails['id'],
                    'created_at'=> date("Y-m-d h:i:s"),
                ];
                $this->storeSingleDuarePointSheetCQeury($pointsOb);
            }
            $subTotal += $price;
        }
        if($orderDetails['coupon']){
            $points = $orderDetails['discount_amount']-$totalDuarePoints;
            $pointsOb=[
                'user_id'=>$orderDetails['user_id'],
                'amount'=>$points,
                'type'=>'Coupon',
                'order_id'=>$orderDetails['id'],
                'created_at'=> date("Y-m-d h:i:s"),
            ];
            $totalDuarePoints = $totalDuarePoints + $points;
            array_push($duarePoints,$pointsOb);
        }
        if($orderDetails['discount_amount'] > 0){
            $this->updateUserDuarePointCQuery($orderDetails['discount_amount'],$orderDetails['user_id']);
            $this->storeDuarePointSheetCQeury($duarePoints);
        }
        return $totalDuarePoints;
       
    }

    public function updateSingleOrdersItemQuery($data){
        return OrderDetails::where('product_id', $data['product_id'])->where('order_id', $data['order_id'])->update($data);
    }
    public function updateOrderStatusQuery( $data){
        $d =   Order::where('id', $data['id'])->update($data);
        OrderShopStatus::where('order_id',$data['id'])->update([
            'status' => $data['status']
        ]);
        return $d; 
    }
    /**
     * storeOrderItemsQuery | get soter  signle order item  .
     *
     * @param  array $data
     * @return array 
     */
    public function storeOrderItemsQuery(array $data)
    {
        return  OrderDetails::create($data);
    }
    /**
     * getOrderAddressQuery | get soter  signle order item  .
     *
     * @param  array $data
     * @return array 
     */
    public function getOrderAddressQuery(int $id)
    {
        $order = Order::where('id', $id)->select('user_id')->first();
        return  deliveryAddress::where('user_id', $order['user_id'])->get();
    }
    /**
     * getOrderSignleItemByIdQuery | get soter  signle order item  .
     *
     * @param  int $data
     * @return int 
     */
    public function getOrderSignleItemByIdQuery(int $id)
    {
        return  OrderDetails::where('id', $id)->with('variations')->first();        
    }
    /**
     * getOrderDriverQuery | get soter  signle order item  .
     *
     * @param  int $data
     * @return int 
     */
    public function getOrderDriverQuery(int $id)
    {
        $order = Order::where('id', $id)->select('driver_id')->first();
         return  User::where('id', $order['driver_id'])->select('id', 'name', 'phone', 'countryCode', 'country', 'address', 'state')->first(); 
    }
    /**
     * getAllDriversQuery | get soter  all driver paginat  .
     *
     * @param  array $data
     * @return array
     */
    public function getAllDriversQuery(array $data)
    {
        $str = $data['str'];
        $q=  User::select('id', 'name', 'phone', 'countryCode', 'country', 'address', 'state')
        ->where('userType', 'Driver')
        ->orderBy($data['colName'], $data['order']);
        if($str){
            $q->where('name', 'like', "%$str%");
            $q->orWhere('country', 'like', "%$str%");
            $q->orWhere('phone', 'like', "%$str%");
            $q->orWhere('id', 'like', "%$str%");
            $q->orWhere('address', 'like', "%$str%");
        }
      return  $q->paginate($data['pageSize']);
    }
    /**
     * updateDeleveryAddressQuery | get soter  signle order item  .
     *
     * @param  int $data
     * @return int 
     */
    public function updateDeleveryAddressQuery(array $data)
    {
        return Order::where('id', $data['id'])->update($data); 
    }
    
    /**
     * updateDeleveryAddressQuery | get soter  signle order item  .
     *
     * @param  int $data
     * @return int 
     */
    public function updateDriverQuery(array $data)
    {
        return Order::where('id', $data['id'])->update($data); 
    }
    /**
     * deleteOrderSignleItemQuery | delete  signle order item  .
     *
     * @param  int $id
     * @return bool 
     */
    public function deleteOrderSignleItemQuery(int $id)
    {
        return OrderDetails::where('id', $id)->delete();
        
    }
    // order images
    public function getAllOrderImagesQuery(int $id)
    {
        return OrderImage::where('order_id', $id)->get();
    }
    public function deleteOrderImageQuery($id)
    {
        return OrderImage::where('id', $id)->delete();
    } 
    public function checkCouponExistQuery($data){ 
        return Coupon::where([['code',$data['code']],['category_id',$data['category_id']]])->first();
    }
    public function checkUserCouponValidQuery($data){
        return CouponUser::where([['coupon_id',$data['coupon_id']],['user_id',$data['user_id']]])->first();
    }
    public function checkUserUsedCouponQuery($data){
        return CouponUsedUser::where([['coupon_id',$data['coupon_id']],['user_id',$data['user_id']]])->first();
    }
    public function checkProductQuantity( $data){
        
        $q = $data['quantity'];
        return   Variation::where([['id',$data['variation_id']],['stock','>=',$q]])->count();
    }
 
    public function storeOrderItems(array $data){
        return  OrderDetails::insert($data);
    }
    public function storeOrderImagesQuery(array $data){
        $items = [];
        foreach ($data as $key => $value){
            
            $d=  OrderImage::create($value);
            array_push($items, $d);
        }
        return $items;
    }
}
