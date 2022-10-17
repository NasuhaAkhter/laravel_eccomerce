<?php

namespace App\Http\Controllers;

use App\Modules\OrderModule\OrderService;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    //
 
    private $orderService;
    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }
    public function order_limit_save(Request $request){
      //  \Log::info($request->all());
        return $this->orderService->saveOrderLimit($request->all());
    }
    
    public function getOrderLimit(){
      return $this->orderService->getOrderLimit();
    }
    public function isAvailableSlot(Request $request){
      return $this->orderService->isAvailableSlot($request->all());
    }
    public function checkAutoOrderSchedule(Request $request){
      return $this->orderService->checkAutoOrderSchedule($request->all());
    }
    public function updateProductPriceInOrder(Request $request){
      return $this->orderService->updateProductPriceInOrder($request->all());
    }
   
    public function getAvailableSchedule(Request $request){
      return $this->orderService->getAvailableSchedule($request->all());
    }
    public function getShippingPrice(Request $request)  
    {
        return $this->orderService->getShippingPrice($request->all());
    } 
    public function getAllOrders(Request $request){
       return $this->orderService->getAllOrders($request->all()); 
    }
    public function getAllSingleOrders($id){
      
       return $this->orderService->getAllSingleOrders($id);
    }
    public function storeOrder(Request $request){
      
       return $this->orderService->storeOrder($request->all());
    }
    public function checkCoupon(Request $request){
       return $this->orderService->checkCoupon($request->all());
    }
    public function storeSingleItem(Request $request){
       return $this->orderService->storeSingleItem($request->all());
    }
    public function deleteOrderSignleItem(Request $request){
      
       return $this->orderService->deleteOrderSignleItem($request->all());
    }
    public function getOrderAddress($id){
      
       return $this->orderService->getOrderAddress($id);
    }
    public function getOrderDriver($id){
      
       return $this->orderService->getOrderDriver($id);  
    }
    public function getAllDrivers(Request $request){
      
       return $this->orderService->getAllDrivers($request->all());
    }
    public function updateDeleveryAddress(Request $request){
      
       return $this->orderService->updateDeleveryAddress($request->all());
    }
   public function updateDriver(Request $request){
      return $this->orderService->updateDriver($request->all());
   }
   public function updateSingeleOrderItem(Request $request){
      return $this->orderService->updateSingeleOrderItem($request->all());
   }
   public function updateOrderStatus(Request $request){
      return $this->orderService->updateOrderStatus($request->all());
   }
   public function deliveredStatusUpdate(Request $request){
      return $this->orderService->deliveredStatusUpdate($request->all());
   }
   // order images
   public function getAllOrderImages($id)
   {
   
      return $this->orderService->getAllOrderImages($id);
   }
   public function deleteOrderImage(Request $request)
   {
      return $this->orderService->deleteOrderImage($request->all());
   }
   public function storeOrderImages(Request $request)
   {
      return $this->orderService->storeOrderImages($request->all());
   }
    

  

}
