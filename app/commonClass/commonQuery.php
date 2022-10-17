<?php

namespace App\commonClass;

use App\Models\User;
use App\Models\Franchise;
use App\Models\Variation;
use App\Models\Order;
use App\Models\FranchiseDailySchedule;
use App\Models\deliveryAddress;
use App\Models\PointSheet;
use Auth;
use DB;
trait commonQuery
{
    /**
     * updateVariationStokeCommon | update variations sotke of product  .
     *
     * @param  array $data quantity,product_id,variation_id
     * @return bool 
     */
    public function updateVariationStokeCommon(array $data,int $sign)
    {
        $vari = Variation::where('product_id', $data['product_id'])->where('id', $data['variation_id'])->select('stock')->first();
        return  Variation::where('product_id', $data['product_id'])->where('id', $data['variation_id'])->update(['stock' => $vari['stock'] - ($data['quantity']*$sign)]);

    }
    public function updateUserDuarePointCQuery($point,$id){
        return User::where('id',$id)->update([
            'duare_points'=> DB::raw("duare_points + $point")
        ]);
    }
    public function storeDuarePointSheetCQeury($data){
        return PointSheet::insert($data);
    }
    public function storeSingleDuarePointSheetCQeury($data){
        return PointSheet::create($data);
    }
    /**
     * updateOrderTotalCommon | update order total   .
     *
     * @param  array $data
     * @return array 
     */
    public function updateOrderTotalCommon(array $data,int $sign)
    {
        $order = Order::where('id', $data['order_id'])->select('total')->first();
        return  Order::where('id', $data['order_id'])->update(['total' => ($data['price']* $data['quantity'] * $sign) + $order['total']]);
    }
    public function getTotalDriver($id){
        return User::where([['franchise_id', $id],['userType','Driver']])->count();
    }
    public function getFranciseScheduleQuery(int $id){
        // return FranchiseDailySchedule::where('franchise_id', $id)->get();
       return FranchiseDailySchedule::where('franchise_id', $id)->select('*',DB::raw("STR_TO_DATE(REPLACE(startTime, ':', '.'),'%h.%i%p') as startformatTime"),DB::raw("STR_TO_DATE(REPLACE(endTime, ':', '.'),'%h.%i%p') as endformatTime"))->orderBy('startformatTime','asc')->get();
    }
    /**
     * getUserByKeyValue
     * This method takes a key and search the key with the given value...
     *
     * @param  string $key
     * @param  int|string $value
     * @return object
     */
    public function getUserByKeyValue(string $key, string $value)
    {
        return User::where($key, $value)->first();
    }
    /**
     * getFrachiseNameAndIdById
     * This method takes a key and search the key with the given value...
     *
     
     * @return object
     */
    public function getFrachiseNameAndIdById()  
    {
        $franchise_id = Auth::user()->franchise_id;
        return Franchise::where('id',$franchise_id)->select('name','id', 'lat','long','city')->first();
    }
    /**
     * getFrachiseByColmandValue
     * This method takes a key and search the key with the given value...
     *
     * @param  string $key
     * @param  int|string $value
     * @return object
     */
    public function getFrachiseByColmandValue(string $key, string $value)
    {
        return Franchise::where($key,$value)->select('name','id', 'lat','long')->first();
    }
    /**
     * updateDeleveryAddressQuery | get soter  signle order item  .
     *
     * @param  int $data
     * @return int 
     */
    public function getDeleveryAddressSpecificIdQuery(int $id)
    {
        return deliveryAddress::where('id', $id)->first();
    }

}