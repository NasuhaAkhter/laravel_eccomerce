<?php

namespace App\Http\Controllers;

use App\Modules\FranchiseModule\FranchiseService;
// use App\Modules\FranchiseModule\FranchiseService;
use Auth;
use Session;
use Illuminate\Http\Request;

class FranchiseController extends Controller 
{
    // 
    private $franchiseService;
    
    public function __construct(FranchiseService $franchiseService)
    {
        $this->franchiseService = $franchiseService;
    } 
    
    public function getFranchiesList(Request $request)
    {
        $data = $request->all();
       return $this->franchiseService->getFranchiesList($data);
    }
    public function shippingPrice(Request $request)
    {
        $data = $request->all();
       return $this->franchiseService->shippingPrice($data);
    }
    public function addShippingPrice(Request $request)
    {
        $data = $request->all();
       return $this->franchiseService->addShippingPrice($data);
    }
    public function editShippingPrice(Request $request)
    {
        $data = $request->all();
       return $this->franchiseService->editShippingPrice($data);
    }
    public function getAllFranchiesList(Request $request)
    {
        $data = $request->all();
       return $this->franchiseService->getFranchiesList($data);
    }
    public function getAllGallery(Request $request)
    { 
       $data = $request->all();
       return $this->franchiseService->getGalleryList($data);
    }
    public function AddProductImage(Request $request)
    { 
        $data = $request->all();
       return $this->franchiseService->AddProductImage($data);
    }
    public function postGallery(Request $request)
    {
        $data = $request->all();
       return $this->franchiseService->postGallery($data);
    }
    public function updateFranchises(Request $request){
        $data = $request->all();
       return $this->franchiseService->updateFranchises($data);
    }
    public function updateMyFranchises(Request $request){
        $data = $request->all();
       return $this->franchiseService->updateMyFranchises($data);
    }
    public function createNewFranchises(Request $request)
    {
        $data = $request->all();
       return $this->franchiseService->createNewFranchises($data);
    }
    public function storeSchedule(Request $request)
    {
       $data = $request->all();
       return $this->franchiseService->storeSchedule($data);
    }
    public function updateSchedule(Request $request)
    {
       $data = $request->all();
       return $this->franchiseService->updateSchedule($data);
    }
    public function deleteFranchises(Request $request)
    {
        $data = $request->all();
       return $this->franchiseService->deleteFranchises($data);
    }
    public function deleteSchedule(Request $request)
    {
        $data = $request->all();
       return $this->franchiseService->deleteSchedule($data);
    }

  //  dashboard
  public function getOrderCount(Request $request){
    return $this->orderService->getOrderCount($request->all());
 }
 public function statusWiseOrderCount(Request $request){
    return $this->orderService->statusWiseOrderCount($request->all());
 }

    // shops    
    /**
     * getshopsById
     * This method takes a $id   ...
     *
     * @param  int 
     * 
     * @return object 
     */
    public function getshopsById($id){
        return $this->franchiseService->getshopsById($id);
    }
    public function getSingleFranchise(Request $request){
         $id = $request->franchiseId;
        return $this->franchiseService->getSingleFranchise($id);
    }
    public function getMyFranchise(Request $request){
        return $this->franchiseService->getMyFranchise();
    }
    /**
     * getshops
     * This method takes a Request   ...
     *
     * @param  array $data
     * 
     * @return array 
     */
    public function getshops(Request $request){
        return $this->franchiseService->getshops($request->all());
    }
    public function getAllPertnerShops(Request $request){
        return $this->franchiseService->getAllPertnerShops($request->all());
    }
    public function getAllPertnerShopsbyCategory(Request $request,$id){
        return $this->franchiseService->getAllPertnerShopsbyCategory($request->all(),$id);
    }
    public function getAlldailySchedule(Request $request){
        return $this->franchiseService->getAlldailySchedule($request->all());
    }
    public function getAllPartnerShop(Request $request){
        return $this->franchiseService->getAllPartnerShop($request->all());
    }
    public function formatedgetApiDataChekck(Request $request){
        return $this->franchiseService->formatedgetApiDataChekck($request->all());
    }
    // shops
    public function createNewshop(Request $request){
        return $this->franchiseService->createNewshop($request->all());
    }
    public function updateshop(Request $request){
        return $this->franchiseService->updateshop($request->all());
    }
    public function updateshopStatus(Request $request){
        return $this->franchiseService->updateshopStatus($request->all());
    }
    public function deleteshop(Request $request){
        return $this->franchiseService->deleteshop($request->all());
    }
 
}
