<?php

namespace App\Modules\OrderModule;
use App\Queries\OrderQuery;
use App\Models\OrderShopStatus;
use App\commonClass\commonService;
use App\commonClass\commonQuery;
use PhpParser\Node\Stmt\TryCatch;
use DateTime;
use Auth;
date_default_timezone_set('Asia/Dhaka');
class OrderService
{
    use OrderQuery;
    use commonService;
    use commonQuery;

    // public function storeSingleItem(array $data){
    //     $d = $this->getAllSingleOrdersItemQuery( $data['product_id'], $data['order_id'], $data['variation_id']);
    //     $temp = [
    //         'quantity' => $data['quantity'],
    //         'order_id' => $data['order_id'],
    //         'price' => $data['price'],
    //         'product_id' => $data['product_id'],
    //         'variation_id' => $data['variation_id']
    //     ]; 
    //     if($d){
    //         $ob =[
    //             'order_id' => $data['order_id'],
    //             'product_id' => $data['product_id'],
    //             'quantity' => $data['quantity'] + $d['quantity']
    //         ]; 
    //         $k = $this->updateSingleOrdersItemQuery($ob);
    //         if($k){
    //             $this->updateVariationStokeCommon($temp,+1);
    //             $this->updateOrderTotalCommon($temp,+1);
    //         }
    //         return $this->coustomErrorMessage("Invalid requiest");
    //     }
    //     try {
    //         $ob = [
    //             'order_id' => $data['order_id'],
    //             'product_id' => $data['product_id'],
    //             'quantity' => $data['quantity'],
    //             'partner_shop_id' => $data['partner_shop_id'],
    //             'product_category_id' => $data['product_category_id'],
    //             'product_subcategory_id' => $data['product_subcategory_id'],
    //             'franchise_id' => $data['franchise_id'],
    //             'band_id' => $data['band_id'],
    //             'variation_id' => $data['variation_id'],
    //             'price' => $data['price']
    //         ];
    //     }catch (\Exception $e) {
    //         return $this->coustomErrorMessage("Invalid requiest");
    //     }
    //     $ok = $this->storeOrderItems($data);
    //     if($ok){
    //         $this->updateVariationStokeCommon($temp,+1);
    //         $this->updateOrderTotalCommon($temp,+1);
    //         return $ok;
    //     }
    //     return $this->coustomErrorMessage("Invalid requiest");
    // }
    public function storeSingleItem1(array $data){
        if (sizeof($data['items']) == 0) return $this->coustomErrorMessage("Your cart is empty!");
        $storeCartItem = [];
        $orderShops = [];
        $subTotal = 0;
        $totalDuarePoints = 0;
        $duarePoints = [];
        $incomes = [];
        foreach($data['items'] as $value){
            $price = $value['variation']['price']*$value['quantity'];
            if($value['variation']['percentage']>0 || $value['variation']['discount']>0){
                 $points = 0;
                 if($value['variation']['percentage']> 0)  $points = ($price*$value['variation']['percentage'])/100;
                 else if($value['variation']['discount']> 0)  $points = $value['variation']['discount'];
                $totalDuarePoints = $totalDuarePoints + $points;
                $pointsOb=[
                    'user_id'=>$data['user_id'],
                    'amount'=>$points,
                    'type'=>'Product',
                    'item_id'=>$value['product_id'],
                    'created_at'=> date("Y-m-d h:i:s"),
                ];
                array_push($duarePoints,$pointsOb);
            }
            $subTotal += $price;     
        }
        
        
        $orderOb = [
            'id' =>$data['id'],
            'user_id' =>$data['user_id'],
            'category_id' =>$data['category_id'],
            'franchise_id' =>$data['franchise_id'],
            'address_id' =>$data['address_id'],
            'schedule_id' =>$data['schedule_id'],
            'driver_id' => isset($data['driver_id'])?$data['driver_id']: 0,
            'shipingCharge' =>$data['shipingCharge'],
            'subTotal' =>$data['subTotal'],
            'total' =>$data['total'],
            'discount_amount' => isset($data['discount_amount'])?$data['discount_amount']: 0,
            'delivery_time' =>$data['delivery_time'],
            'paymentType' => $data["paymentType"],
            'orderDate' => date("Y-m-d"),
            'remarks' =>$data['remarks'],
            'orderType' =>$data['orderType'],
            'status' =>$data['status'],
            'coupon' =>$data['coupon'],
            'isScheduled' =>$data['isScheduled'],
            'coupon_amount' => isset($data['coupon_amount'])?$data['coupon_amount']: 0,
        ];
        $order = $this->updateOrderQuery($orderOb);
        if($data['coupon']){
            $pointsOb=[
                'user_id'=>$data['user_id'],
                'amount'=>$data['coupon_amount'],
                'type'=>'Coupon',
                'item_id'=>$order['id'],
                'created_at'=> date("Y-m-d h:i:s"),
            ];
            $totalDuarePoints = $totalDuarePoints + $data['coupon_amount'];
        }
        foreach($data['items'] as $value){
            $cartOb=[
                'user_id'=>$data['user_id'],
                'order_id'=>$data['id'],
                'category_id'=>$data['category_id'],
                'product_id'=>$value['product_id'],
                'variation_id'=>$value['variation_id'],
                'partner_shop_id'=>$value['partner_shop_id'],
                'product_category_id'=>$value['product']['product_category_id'],
                'product_subcategory_id'=>$value['product']['product_subcategory_id'],
                'franchise_id'=>$value['product']['franchise_id'],
                'band_id'=>$value['product']['band_id'],
                'quantity'=>$value['quantity'],
                'price'=>$value['variation']['price'],
                'total_price'=>$value['variation']['price'] * $value['quantity'],
                'discount'=>0,
                'created_at'=> date("Y-m-d h:i:s"),
            ];
            $shopOb = [
                'shop_id'=>$value['partner_shop_id'],
                'order_id'=>$data['id'],
            ];
            $incomeOb = [
                'shop_id'=>$value['partner_shop_id'],
                'variation_id'=>$value['variation_id'],
                'order_id'=>$data['id'],
                'type' =>$data['orderType'],
                'amount' => ($value['variation']['price']-$value['variation']['purchase_price'])*$value['quantity'],
                'percentage' => ((($value['variation']['price']*$value['quantity'])*($value['product']['percentage']))/100),
                'created_at'=> date("Y-m-d h:i:s"),
            ];
            array_push($storeCartItem,$cartOb);
            array_push($orderShops,$shopOb);
            array_push($incomes,$incomeOb);
        }
        // return $storeCartItem;
        $this->storeOrderItems($storeCartItem);
        $this->storeOrderShopStatusQuery($orderShops);
        $this->updateProductStock($storeCartItem);
        if($data['paymentType'] != 'Redeem Cashback'){
            $this->storeIncomeItemsQuery($incomes);
        }
        return response()->json([
            "order" => $order,
        ], 200);
    }
    public function storeSingleItem($data){ 
        if (sizeof($data['items']) == 0) return $this->coustomErrorMessage("Your cart is empty!");
        $storeCartItem = [];
        $orderShops = [];
        $totalDuarePoints = 0;
        $incomes = [];
        $subTotal = 0;
        $total = 0;
        $itemId =[];
        $order = $this->getOrderDetailsCQuery($data['id']);

        foreach($data['items'] as $value){
            $price = $value['variations']['price']*$value['quantity'];
            $subTotal += $price;
            $points = 0;
            if($value['variations']['percentage']>0 || $value['variations']['discount'] > 0){
                if($value['variations']['percentage']> 0)  $points = ($price*$value['variations']['percentage'])/100;
                else if($value['variations']['discount']> 0)  $points = $value['variations']['discount'];
                $totalDuarePoints = $totalDuarePoints + $points;
            }
            if($value['id'] == 0){
                $cartOb=[
                    'user_id'=>$data['user_id'],
                    'order_id'=>$data['id'],
                    'category_id'=>$data['category_id'],
                    'product_id'=>$value['product_id'],
                    'variation_id'=>$value['variation_id'],
                    'partner_shop_id'=>$value['partner_shop_id'],
                    'product_category_id'=>$value['product']['product_category_id'],
                    'product_subcategory_id'=>$value['product']['product_subcategory_id'],
                    'franchise_id'=>$value['product']['franchise_id'],
                    'band_id'=>$value['product']['band_id'],
                    'quantity'=>$value['quantity'],
                    'price'=>$value['variations']['price'],
                    'total_price'=>$value['variations']['price'] * $value['quantity'],
                    'discount'=>0,
                    'created_at'=> date("Y-m-d h:i:s"),
                ];
                $shopOb = [
                    'shop_id'=>$value['partner_shop_id'],
                    'order_id'=>$data['id'],
                ];
                array_push($storeCartItem,$cartOb);
                array_push($orderShops,$shopOb);
            }else{
                $cartOb=[
                    'price'=>$value['variations']['price'],
                    'total_price'=>$value['variations']['price']*$value['quantity'],
                    'discount'=>$points,
                    'quantity'=>$value['quantity'],
                    'id'=>$value['id'],
                ];
                $ddd =  $this->updateOrderDetailsQuery($cartOb);
                array_push($itemId, $value['id']);
            }
            $incomeOb = [
                'shop_id'=>$value['partner_shop_id'],
                'variation_id'=>$value['variation_id'],
                'order_id'=>$data['id'],
                'type' =>$data['orderType'],
                'amount' => ($value['variations']['price']-$value['variations']['purchase_price'])*$value['quantity'],
                'percentage' => ((($value['variations']['price']*$value['quantity'])*($value['product']['percentage']))/100),
                'created_at'=> date("Y-m-d h:i:s"),
            ];
            array_push($incomes,$incomeOb);
        }
        isset($data['coupon_amount'])?$data['coupon_amount']: 0;
        $totalDuarePoints = $totalDuarePoints + $data['coupon_amount'];
        $orderOb = [
            'id' => $data['id'],
            'coupon_amount' => $data['coupon_amount'],
            'subTotal' => $subTotal,
            'discount_amount' => $totalDuarePoints,
            'total' => $subTotal + $data['shipingCharge'],
        ]; 
        if($data['paymentType'] ==  'Redeem Cashback'){
            $orderOb['total'] = $data['shipingCharge'];
            $this->updateUserDuarePointQuery( $order->subTotal - $subTotal ,$data['user_id']);
        } 
        foreach($storeCartItem as $value){
            $result = $this->storeOrderItemsQuery($value);
            array_push($itemId, $result['id']);
        }
        // return $this->storeOrderItems($storeCartItem);
        $this->deleteOrderItemQuery($itemId, $data['id']);
        $this->storeOrderShopStatusQuery($orderShops);
        $this->updateProductStock($storeCartItem);
        $this->updateOrderQuery($orderOb);
        if($data['paymentType'] != 'Redeem Cashback'){
            $this->deleteIncomeSheetQuery($data['id']);
            $this->storeIncomeItemsQuery($incomes);
        }
        return $this->getAllSingleOrdersQuery($data['id']);
    }
    public function checkCoupon($data){
        $today = date("Y-m-d");
        // return $data;
        $coupon= $this->checkCouponExistQuery($data);
        if(!$coupon) return $this->coustomErrorMessage("Invalid coupon");
        if($coupon['target_price'] != null){
            if($data['subTotal'] < $coupon['target_price']) return $this->coustomErrorMessage("Please order minimum ".$coupon['target_price'].".");
        }
        $val = $coupon['validity'];
        $validity = new DateTime("$val 00:00:00");
        $end = new DateTime("$today 00:00:00");
        if($validity < $end ) return $this->coustomErrorMessage("Coupon expired!");
        if($coupon['type']=='user'){
            $nob = [
                'coupon_id' => $coupon['id'],
                'user_id' => $data['user_id'],
            ];
            $userCouon = $this->checkUserCouponValidQuery($nob);
            if(!$userCouon) return $this->coustomErrorMessage("Invalid coupon!");

        }
        else if($coupon['type']=='all'){
            if($coupon['discount_type'] == 2){

                $nob = [
                    'coupon_id' => $coupon['id'],
                    'user_id' => $data['user_id'],
                ];
                $userCouon = $this->checkUserUsedCouponQuery($nob);
                if($userCouon) return $this->coustomErrorMessage("Coupon already used!");
            }

        }
        return response()->json([
            "data" => $coupon
        ], 200);
    }
    public function storeOrder($data){
       if (sizeof($data['items']) == 0) return $this->coustomErrorMessage("Your cart is empty!");
       $storeCartItem = [];
       $orderShops = [];
       $subTotal = 0;
       $totalDuarePoints = 0;
       $duarePoints = [];
       $incomes = [];
       foreach($data['items'] as $value){
           $price = $value['variation']['price']*$value['quantity'];
           if($value['variation']['percentage']>0 || $value['variation']['discount']>0){
                $points = 0;
                if($value['variation']['percentage']> 0)  $points = ($price*$value['variation']['percentage'])/100;
                else if($value['variation']['discount']> 0)  $points = $value['variation']['discount'];
               $totalDuarePoints = $totalDuarePoints + $points;
               $pointsOb=[
                   'user_id'=>$data['user_id'],
                   'amount'=>$points,
                   'type'=>'Product',
                   'item_id'=>$value['product_id'],
                   'created_at'=> date("Y-m-d h:i:s"),
               ];
               array_push($duarePoints,$pointsOb);
           }
           $subTotal += $price;     
       }
    //    $total = $subTotal + $data['shipingCharge'];
       if($data['paymentType'] ==  'Redeem Cashback'){
            $total = $data['shipingCharge'];
            $this->updateUserDuarePointQuery($subTotal*-1 ,$data['user_id']);
        }else{
            $total = $subTotal + $data['shipingCharge'];
        }
       isset($data['coupon_amount'])?$data['coupon_amount']: 0;
       $totalDuarePoints = $totalDuarePoints + $data['coupon_amount'];

       $orderOb = [
           'user_id' =>$data['user_id'],
           'pimage' =>$data['pimage'],
           'category_id' =>$data['category_id'],
           'franchise_id' =>$data['franchise_id'],
           'address_id' =>$data['address_id'],
           'schedule_id' =>$data['schedule_id'],
           'driver_id' => isset($data['driver_id'])?$data['driver_id']: 0,
           'shipingCharge' =>$data['shipingCharge'],
           'subTotal' =>$data['subTotal'],
           'total' =>$total,
           'discount_amount' => $totalDuarePoints ,
           'delivery_time' =>$data['delivery_time'],
           'paymentType' => $data["paymentType"],
           'orderDate' => date("Y-m-d"), 
           'remarks' =>$data['remarks'],
           'orderType' =>$data['orderType'],
           'status' =>'Pending',
           'coupon' =>$data['coupon'],
           'isScheduled' =>$data['isScheduled'],
           'coupon_amount' => isset($data['coupon_amount'])?$data['coupon_amount']: 0,
       ];
       \Log::info($orderOb);
       $order = $this->storeOrderQuery($orderOb);
       // Check Coupon
       if($data['coupon']){
           $pointsOb=[
               'user_id'=>$data['user_id'],
               'amount'=>$data['coupon_amount'],
               'type'=>'Coupon',
               'item_id'=>$order['id'],
               'created_at'=> date("Y-m-d h:i:s"),
           ];
           $totalDuarePoints = $totalDuarePoints + $data['coupon_amount'];
       }
       foreach($data['items'] as $value){
           $cartOb=[
               'user_id'=>$data['user_id'],
               'order_id'=>$order['id'],
               'category_id'=>$data['category_id'],
               'product_id'=>$value['product_id'],
               'variation_id'=>$value['variation_id'],
               'partner_shop_id'=>$value['partner_shop_id'],
               'product_category_id'=>$value['product']['product_category_id'],
               'product_subcategory_id'=>$value['product']['product_subcategory_id'],
               'franchise_id'=>$value['product']['franchise_id'],
               'band_id'=>$value['product']['band_id'],
               'quantity'=>$value['quantity'],
               'price'=>$value['variation']['price'],
               'total_price'=>$value['variation']['price'] * $value['quantity'],
               'discount'=>0,
               'created_at'=> date("Y-m-d h:i:s"),
           ];
           $shopOb = [
               'shop_id'=>$value['partner_shop_id'],
               'order_id'=>$order['id'],
           ];
           $incomeOb = [
               'shop_id'=>$value['partner_shop_id'],
               'variation_id'=>$value['variation_id'],
               'order_id'=>$order['id'],
               'type' =>$data['orderType'],
               'amount' => ($value['variation']['price']-$value['variation']['purchase_price'])*$value['quantity'],
               'percentage' => ((($value['variation']['price']*$value['quantity'])*($value['product']['percentage']))/100),
               'created_at'=> date("Y-m-d h:i:s"),
           ];
           array_push($storeCartItem,$cartOb);
           array_push($orderShops,$shopOb);
           array_push($incomes,$incomeOb);
       }
       // return $storeCartItem;
       $this->storeOrderItems($storeCartItem);
       $this->storeOrderShopStatusQuery($orderShops);
       $this->updateProductStock($storeCartItem);
       if($data['paymentType'] != 'Redeem Cashback'){
           $this->storeIncomeItemsQuery($incomes);
       }
       $idd= $order['id'];
       $name= $data['user_id'];
       $pushData = [
          "title"=>"New Order!",
          "body"=>"A new order($idd) has been Placed by Admin.",
          "orderId"=> $order['id']
       ];
        $this->sendPushNotification($order['id'],$pushData,$data['user_id']);
        if($order['driver_id']) $this->sendPushNotificationToDrivers($order['id'],$pushData);
        return response()->json([
            "order" => $order,
        ], 200);
        try { 
            $items['items'] = $data['items'];
            $id = $data['franchise_id'];
            $shipingCharge = $data['shipingCharge'];
            $items['user_id'] = $data['user_id'];
            $items['franchise_id'] = $id;
            $order = [
                'category_id' => $data['category_id'],
                'driver_id' => $data['driver_id'],
                'schedule_id' => $data['schedule_id'],
                'user_id' => $data['user_id'],
                'franchise_id'=> $id,
                'address_id'=>$data['address_id'],
                'total'=>$data['total'],
                'subTotal'=>$data['subTotal'],
                'shipingCharge'=> isset($shipingCharge)  && isset($shipingCharge['price'])? $shipingCharge['price']:0,
                'orderDate'=>$data['orderDate'],
                'status'=>"Pending"
            ];
        }catch (\Exception $e) {  
            return response()->json([
                'message' => "Invalid requiest",
                'e' => $e,
            ], 422);
        }
        $d = $this->storeOrderQuery($order);
        if($d){
            $items['order_id'] = $d['id'];
             $e = $this->storeOrderItems($items, $d['id']);
             if($e == 1){
                return $this->getAllSingleOrders($d['id']);
             }else{
                 return;
             }
        }
        return $this->coustomErrorMessage("Invalid requiest");
    }
   
    public function updateProductPriceInOrder($data){ 
        // $this->getTotalDuarePointByOrderQuery($data['id']);
        // $user = Auth::user();
        $totalDuarePoints = 0;
        // $duarePoints = [];
        $incomes = [];
        $subTotal = 0;
        foreach($data['items'] as $value){
            $price = $value['variations']['price']*$value['quantity'];
            $subTotal += $price;
            $points = 0;
            if($value['variations']['percentage']>0 || $value['variations']['discount'] > 0){
                if($value['variations']['percentage']> 0)  $points = ($price*$value['variations']['percentage'])/100;
                else if($value['variations']['discount']> 0)  $points = $value['variations']['discount'];
                $totalDuarePoints = $totalDuarePoints + $points;
            }
            $cartOb=[
                'price'=>$value['variations']['price'],
                'total_price'=>$value['variations']['price']*$value['quantity'],
                'discount'=>$points,
                'id'=>$value['id'],
            ];
            $incomeOb = [
                'shop_id'=>$value['partner_shop_id'],
                'variation_id'=>$value['variation_id'],
                'order_id'=>$data['id'],
                'type' =>$data['orderType'],
                'amount' => ($value['variations']['price']-$value['variations']['purchase_price'])*$value['quantity'],
                'percentage' => ((($value['variations']['price']*$value['quantity'])*($value['product']['percentage']))/100),
                'created_at'=> date("Y-m-d h:i:s"),
            ];
            $ddd =  $this->updateOrderDetailsQuery($cartOb);
            $variationOb = [
                'id' =>$value['variations']['id'],
                'price' =>$value['variations']['price'],
                'purchase_price' =>$value['variations']['purchase_price']
            ];
            $ddd =  $this->updateProductPriceQuery($variationOb);
            // array_push($duarePoints,$pointsOb);
            array_push($incomes,$incomeOb);
        } 
        $orderOb = [
            'id' => $data['id'],
            'subTotal' => $subTotal,
            'discount_amount' => $totalDuarePoints,
            'total' => $subTotal + $data['shipingCharge'],
        ]; 
        $this->updateOrderQuery($orderOb);

        if($data['paymentType'] != 'Redeem Cashback'){ 
            $this->deleteIncomeSheetQuery($data['id']);
            $this->storeIncomeItemsQuery($incomes);
            // if($totalDuarePoints > 0){
            //     $ppoint = $this->getTotalDuarePointByOrderQuery($data['id']);
            //     $totalDuarePoints = $totalDuarePoints - $ppoint;
            //     $this->deleteDuarePointSheetQuery($data['id']);
            //     $this->updateUserDuarePointQuery($totalDuarePoints,$user->id);
            //     $this->storeDuarePointSheet($duarePoints , $user->id);
            // }
        }
        return $this->getAllSingleOrdersQuery($data['id']);
    }
    public function getAllSingleOrders(int $id){
          return $this->getAllSingleOrdersQuery($id);
    }
    public function getAllOrders(array $data){  
          $data = $this->formatedgetApiData($data);
          if(Auth::user()->userType == 'ShopOwner') {
                $data['shopId'] = Auth::user()->shop_id;
            }
          return $this->getAllOrdersQuery($data);
    }
    public function getOrderLimit(){
        return $this->getOrderLimitQuery();
    }
    public function isAvailableSlot(array $data){
        $ob = [
            'schedule_id' => $data['schedule_id'],
            'date' => $data['date'],
            'max_order' => $data['max_order'],
        ];
        return $this->isAvailableSlotQuery($ob);
    }
    public function getAvailableSchedule(array $data){
        $ob = [
             'id' => $data['id']
        ];
        return $this->getAvailableScheduleQuery($ob);
    }
    public function getShippingPrice( $data)
    {
        $franchise_id = $data['franchise_id'];
        $address = $this->getUserAddress($data['id']);
        $franchiseAddress = $this->getfranchiseAddress($franchise_id);
    
        $distance = $this->circle_distance($address['lat'],$address['long'],$franchiseAddress['slat'],$franchiseAddress['slng']);
        if($distance > $franchiseAddress['sdistance']){
            return response()->json([
                "price" => $franchiseAddress['outside_price'],
                'place' =>"Outside"
            ], 200);
        }
        return response()->json([
            "price" => $franchiseAddress['price'],
            'place' =>"Inside"
        ], 200);
        return $this->getShippingPriceQuery($data);
    }
    public function circle_distance($lat1, $lon1, $lat2, $lon2) {
        $rad = M_PI / 180;
        return acos(sin($lat2*$rad) * sin($lat1*$rad) + cos($lat2*$rad) * cos($lat1*$rad) * cos($lon2*$rad - $lon1*$rad)) * 6371;// Kilometers
      }
    /**
     * deleteOrderSignleItem | delete order item a array with a ..
     *
     * @param  array $data
     * @return bool
     */
    public function deleteOrderSignleItem(array $data){
        $d = $this->getOrderSignleItemByIdQuery($data['id']);
        $check = $this->deleteOrderSignleItemQuery($data['id']);
        if($check && $d ){
            $temp = [
                'quantity' => $d['quantity'],
                'order_id' => $d['order_id'],
                'price' => $d['price'],
                'product_id' => $d['product_id'],
                'variation_id' => $d['variation_id']
            ]; 
            $this->updateVariationStokeCommon($temp,-1);
            $this->updateOrderTotalCommon($temp,-1);
            return $this->deleteMessage($check,"Order item");
        }
      
        return $this->coustomErrorMessage("Invalid requiest");
        
    }
    public function getOrderAddress(int $id)
    {
        return $this->getOrderAddressQuery($id);
    }
    public function getOrderDriver(int $id)
    {
        return $this->getOrderDriverQuery($id);
    }
    public function getAllDrivers(array $data)
    {
        $data = $this->formatedgetApiData($data);
        return $this->getAllDriversQuery($data);
    }
    /**
     * updateDeleveryAddress | delete order address a array with a ..
     *
     * @param  array $data
     * @return object
     */
    public function updateDeleveryAddress(array $data)
    {
        // return $data;
        $ob = [
            'id' => $data['order_id'],
            'address_id' => $data['address_id'],
        ];
     
        
        $d = $this->updateDeleveryAddressQuery($ob);
        if($d) return $this->getDeleveryAddressSpecificIdQuery($ob['address_id']);
        return $this->coustomErrorMessage("Invalid requiest");
    }
    /**
     * updateDriver | update order driver a array with a ..
     *
     * @param  array $data
     * @return object
     */

    public function updateDriver(array $data)
    {
        // return $data;
        $ob = [
            'id' => $data['order_id'],
            'driver_id' => $data['driver_id'],
        ];
     
        $d = $this->updateDriverQuery($ob);
        if($d) return $this->getUserByKeyValue('id',$ob['driver_id']);
        return $this->coustomErrorMessage("Invalid requiest");
    }
    /**
     * updateSingeleOrderItem | update order item  a array with a item values.
     *
     * @param  array $data
     * @return object
     */
    public function updateSingeleOrderItem(array $data){
        $ob = [
            'id'=>$data['id'],
            'band_id'=>$data['band_id'],
            'franchise_id'=>$data['franchise_id'],
            'order_id'=>$data['order_id'],
            'quantity'=>$data['quantity'],
        ];
        return $this->updateSingleOrdersItemQuery($data);
    }
    /**
     * updateOrderStatus | update order status  a array with a item values.
     *
     * @param  array $data
     * @return object
     */
    public function deliveredStatusUpdate(array $data){
        $user = Auth::user();
        if($user->userType == 'FranchiseOwner' || $user->userType == 'Admin'){
            $check = 1;
        }else{
            $check = $this->checkOrderStatusQuery($data['order_id'],"Picked");
        }
        if($check == 0) return $this->coustomErrorMessage("Order is not picked yet!");
        $ob = [
            'id'=>$data['order_id'],
            'status'=>"Delivered",
        ];
        $orders = $this->updateOrderStatusQuery($ob);
        $points = $this->calculateAndStoreDuarePoint($data['order_id']);
        // return $points;
        // Send Push Notifiction
        $id = $data['order_id'];
        $pushData = [
           "title"=>"Order($id) Status Changed!",
           "body"=>"Order($id) Status changed to Delivered",
           "orderId"=> $id 
        ];
        $this->sendPushNotification($id,$pushData,$user->id);
        if($points > 0){
            $pushData = [
                "title"=>"Duare Points!",
                "body"=>"You have been awarded $points Duare points from order($id)",
                "orderId"=> $id
             ];
        $this->sendPushNotificationToUser($id,$pushData,$user->id);
        }
        return response()->json([
            "data" => $orders
        ], 200);
    }
    public function updateOrderStatus(array $data){
        $user = Auth::user();
        $response = null;
        if($user->userType != "ShopOnwer"){
            $ob = [
                'id'=>$data['order_id'],
                'status'=>$data['status'],
            ];
            $response =  $this->updateOrderStatusQuery($ob);
            if($response == 1){
                $id = $data['order_id'];
                $status = $data['status'];
                $pushData = [
                    "title"=>"Order($id) Status Changed!",
                    "body"=>"Order($id) Status changed to $status",
                    "orderId"=> $id
                 ];
                $this->sendPushNotification($id,$pushData,$user->id);
            }
        }
        else {
            $allOrders =  $this->updateOrderShopStatusQuery($data);
            $response =   $this->checkOrderStatus($data['order_id'],$data['status'],$user->id);
        }
        return $response;
    }
    public function checkOrderStatus($id,$status,$user_id){
        $checkd = ['Processing','Ready to Pick','Picked','Delivered','Canceled'];
        if($status == 'Processing'){
            $checkd = ['Processing','Ready to Pick','Picked','Delivered','Canceled'];
        }
        else if($status == 'Ready to Pick'){
            $checkd = ['Ready to Pick','Picked','Delivered','Canceled'];
        }
        else if($status == 'Picked'){
            $checkd = ['Picked','Delivered','Canceled'];
        }
        else if($status == 'Delivered'){
            $checkd = ['Delivered','Canceled'];
        }
        else if($status == 'Canceled'){
            $checkd = ['Canceled'];
        }

        $isAllOrderAccepted = $this->checkIsAllOrderAcceptedQuery($id,$checkd);
        if($isAllOrderAccepted == false) return;
        $d= $this->updateOrderStatusQuery($id,$status); 
         // Send Push Notifiction
         $pushData = [
            "title"=>"Order($id) Status Changed!",
            "body"=>"Order($id) Status changed to $status",
            "orderId"=> $id
         ];
        
        $this->sendPushNotification($id,$pushData,$user_id);
        return $d;
    }

    // order images
    public function getAllOrderImages($id){
       return $this->getAllOrderImagesQuery($id);
    }
    public function deleteOrderImage(array $data){
        
       return $this->deleteOrderImageQuery($data['id']);
    }
    public function storeOrderImages(array $data){
       return $this->storeOrderImagesQuery($data);
    }
    public function saveOrderLimit(array $data){
       return $this->saveOrderLimitQuery($data);
    }

    public function checkAutoOrderSchedule($data){
        
        $franchise_id = $data['franchise_id'];
        // $franchise_id = 5;
        $totalDriver = $this->getTotalDriver($franchise_id);
        $raw_schedule =  $this->getFranciseScheduleQuery($franchise_id);
        $schedule = [];
        $today = date("Y-m-d");
        $todayTime = date("H:i");
        foreach($raw_schedule as $val){
            if(strtotime($todayTime)<=strtotime($val['endformatTime'])) {
                array_push($schedule,$val);
            } 
        }
        foreach($schedule as $val){
            $total = $totalDriver * $val['max_order'];
            $dd = [
                'date' => $today,
                'schedule_id' => $val['id']
            ];
            $totalOrdered = $this->CountOrderScheduleQuery($dd);
            if($total > $totalOrdered){

                return response()->json([
                    "data" => $val,
                ], 200);
            }
        }
        return $this->coustomErrorMessage('Today Schedule not  availble!');
        
    }
}
