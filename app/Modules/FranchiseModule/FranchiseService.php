<?php

namespace App\Modules\FranchiseModule;
use App\Queries\FranchiseQuery;
use App\commonClass\commonService;
use App\commonClass\commonQuery;
use Auth;

class FranchiseService
{ 
    use FranchiseQuery;
    use commonService;
    use commonQuery;
    public function getSingleFranchise($id){
         return $this->getSingleFranchiseQuery($id);
    }
    public function getMyFranchise(){
        $franchise_id = Auth::user()->franchise_id;
         return $this->getSingleFranchiseQuery($franchise_id);
    }
    public function shippingPrice($data){
        $data = $this->formatedgetApiData($data);
        $data['colName'] = 'name';
        $data['order'] = 'asc';
        return $this->shippingPriceQuery($data);
    }
    public function addShippingPrice($data){
         return $this->addShippingPriceQuery($data);
    }
    public function editShippingPrice($data){
         return $this->editShippingPriceQuery($data);
    }
    public function getFranchiesList($data){
    //     $check = $this->checkKeyExistOrNot(['id'],$data);
    //    if(!$check) return $this->coustomErrorMessage("Invalid request 1");
    //    $check = $this->getUserByKeyValue('id',$data['id']);
    //    if(!$check) return $this->coustomErrorMessage("Invalid request");
        $data = $this->formatedgetApiData($data);
        $data['colName'] = 'name';
        $data['order'] = 'asc';
       return $this->getFranchiesListQuery($data);
    }
    public function getGalleryList($data){
        $data = $this->formatedgetApiData($data);
        return $this->getGalleryListQuery($data);
    }
    public function AddProductImage($data){
         return $this->AddProductImageQuery($data);
    }
    public function postGallery($data){
        $ob = [
            'image' => $data['image'],
        ];
        return $this->postGalleryQuery($ob);
    }
    /**
     * updateFranchises
     * This method takes a array of keys  ...
     *
     * @param  array $data
     * 
     * @return object 
     */
    public function updateFranchises($data){
        $ob = [
            'id' => $data['id'],
            'target_price'=>$data['target_price'],
            'address' => $data['address'],
            'city' => $data['city'],
            'country' => $data['country'],
            'countryCode' => $data['countryCode'],
            'name' => $data['name'],
            'phone' => $data['phone'],
            'lat' => $data['lat'],
            'slat' => $data['slat'],
            'long' => $data['long'],
            'slng' => $data['slng'],
            'radious' => $data['radious'],
            'user_id' => $data['user_id'],
            'whatsapp_number' => $data['whatsapp_number'],
            'bkash' => $data['bkash'],
            'nogod' => $data['nogod'],
            'roket' => $data['roket'],
            'support_number' => $data['support_number'],
            'isWhatsapp' => $data['isWhatsapp'],
            'status' => $data['status'],
        ];
        return $this->updateFranchisesQuery($ob); 
    }
    // public function updateMyFranchises($data){
    //     $ob = [
    //         'id' => $data['id'],
    //         'whatsapp_number' => $data['whatsapp_number'],
    //         'status' => $data['status'],
    //     ];
    //     return $this->updateFranchisesQuery($ob);
    // }
    /**
     * createNewFranchises
     * This method takes a array of keys  ...
     *
     * @param  array $data
     * 
     * @return object 
     */
    
    public function createNewFranchises($data){
        $ob = [
            'address'=>$data['address'],
            'target_price'=>$data['target_price'],
            'city'=>$data['city'],
            'country'=>$data['country'],
            'countryCode'=>$data['countryCode'],
            'name'=>$data['name'],
            'phone'=>$data['phone'],
            'user_id' => $data['user_id'],
            'lat' => $data['lat'],
            'slat' => $data['slat'],
            'long' => $data['long'],
            'slng' => $data['slng'],
            'radious' => $data['radious'],
            'whatsapp_number' => $data['phone']
        ];
        return $this->createNewFranchisesQuery($ob);
    }
    public function storeSchedule($data){  
        $ob = [
            'franchise_id'=>$data['franchise_id'],
            'startTime'=>$data['startTime'],
            'endTime'=>$data['endTime'],
            'max_order'=>$data['max_order']
        ];
        return $this->storeScheduleQuery($ob);
    }
    public function updateSchedule($data){
        $ob = [ 
            'id'=>$data['id'],
            'franchise_id'=>$data['franchise_id'],
            'startTime'=>$data['startTime'],
            'endTime'=>$data['endTime'],
            'max_order'=>$data['max_order']
        ];
        return $this->updateScheduleQuery($ob);
    }
    /**
     * deleteFranchises
     * This method takes a array of keys  ...
     *
     * @param  array $data
     * 
     * @return object 
     */
    public function deleteFranchises($data){
  
        return $this->deleteFranchisesQuery($data['id']);
    }
    public function deleteSchedule($data){
        return $this->deleteScheduleQuery($data['id']);
    }

    /**
     * getshops
     * This method takes a array of keys  ...
     *
     * @param  array $data
     *   
     * @return array 
     */
    public function getAllPertnerShops(array $data){ 
        $data = $this->formatedgetApiData($data);
        if(Auth::user()->userType == 'ShopOwner') {
            $data['shopId'] = Auth::user()->shop_id;
        }
        return $this->getAllPertnerShopsQuery($data);
    }
    public function getAllPertnerShopsbyCategory(array $data,$id){ 
        $data = $this->formatedgetApiData($data);
        return $this->getAllPertnerShopsbyCategoryQuery($data,$id);
    }
    public function getAlldailySchedule(array $data){ 
        $data = $this->formatedgetApiData($data);
        $data['colName'] = 'id';
        $data['order'] = 'asc';
        return $this->getAlldailyScheduleQuery($data);
    }
    public function getshops(array $data){
        $data = $this->formatedgetApiData($data);
        $user = Auth::user();
        $data['colName'] = 'name';
        $data['order'] = 'asc';
        if($user->userType == 'SuperAdmin'){
            $data['franchise_id'] = 0;
        }else{
            $fr = $this->getFrachiseNameAndIdById();
            $data['franchise_id'] = $fr['id'];
        }
        // return $data;
        return $this->getshopsQuery($data);
    }
    public function getshopsById(int $id){
        return $this->getshopsByIdQuery($id);
    }
    public function formatedgetApiDataChekck(array $data){
        return $this->formatedgetApiData($data);
    }
    // shops
    public function createNewshop(array $data){

        $fr = $this->getFrachiseNameAndIdById();
        
        if(!$fr){
            
            return $this->coustomErrorMessage("This user has no frachise");
        }
        $ob = [
            'name' => $data['name'],
            'phone' => $data['phone'],
            'countryCode' => $data['countryCode'],
            'country' => $data['country'],
            'city' => $fr['city'],
            'lat' => $data['lat'],
            'long' => $data['long'],
            'address' => $data['address'],
            'franchise_id' => $data['franchise_id'],
            'image' => $data['image'],
            'user_id' => $data['user_id'],
            'category_id' => $data['category_id'],
            'is_recommended' => $data['is_recommended'],
            'shop_start_time' => $data['shop_start_time'],
            'shop_end_time' => $data['shop_end_time'],
            'delivery_time' => $data['delivery_time'],
            'percentage' => $data['percentage'],
            'category_type' => $data['category_type'],
        ]; 
                
        return $this->createNewshopQuery($ob);
    }
    public function updateshop(array $data){
        // $fr = $this->getFrachiseNameAndIdById();
        // if(!$fr) return $this->coustomErrorMessage("Your are not authenticated!");
        
        // if($fr['id']!=$data['franchise_id']){
        //     return $this->coustomErrorMessage("Your are not authenticated!");
        // }
        $ob = [
            'id' => $data['id'],
            'name' => $data['name'],
            'phone' => $data['phone'],
            'countryCode' => $data['countryCode'],
            'country' => $data['country'],
            'city' => $data['city'],
            'shop_start_time' => $data['shop_start_time'], 
            'shop_end_time' => $data['shop_end_time'], 
            'percentage' => $data['percentage'], 
            'address' => $data['address'],
            'franchise_id' => $data['franchise_id'],
            'user_id' => $data['user_id'],
            'image' => $data['image'],
            'category_type' => $data['category_type'],
        ];
        return $this->updateshopQuery($ob);
    }
    public function updateshopStatus(array $data){
        // $fr = $this->getFrachiseNameAndIdById();
        // if(!$fr) return $this->coustomErrorMessage("Your are not authenticated!");
        
        // if($fr['id']!=$data['franchise_id']){
        //     return $this->coustomErrorMessage("Your are not authenticated!");
        // }
        $ob = [
            'id' => $data['id'],
            'status' => $data['status'],
        ];
        return $this->updateshopQuery($ob);
    }
    public function deleteshop(array $data){
        return $this->deleteshopQuery($data['id']);
    }
    
}
