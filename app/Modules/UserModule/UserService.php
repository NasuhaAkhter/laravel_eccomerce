<?php

namespace App\Modules\UserModule;
use App\Queries\UserQuery;
use App\commonClass\commonService;
use App\commonClass\commonQuery;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

use DateTime;

class UserService
{
    use UserQuery;
    use commonService;
    use commonQuery;
    private $userQuery;


    // dashboard start
    public function getRecentOrderTable( ){
        return $this->getRecentOrderTableQuery();
    }
    public function getDriverTable( ){
        return $this->getDriverTableQuery(  );
    }
    public function getCustomerTable( ){
        return $this->getCustomerTableQuery(  );
    }
    public function getShopTable( ){
        return $this->getShopTableQuery(  );
    }
    public function getFranchiseTable( ){
        return $this->getFranchiseTableQuery(  );
    }
    public function getProductCount( ){
        return $this->getProductCountQuery(  );
    }
    public function getFranchiseCount( ){
        return $this->getFranchiseCountQuery(  );
    }
    public function getOrderCount( ){
        return $this->getOrderCountQuery(  );
    }
    public function getCustomerCount( ){
        return $this->getCustomerCountQuery(  );
    }
    public function getShopCount(){
        return $this->getShopCountQuery();
    }
    public function getAllPackage($data){
        $data = $this->formatedgetApiData($data);
        $data['colName'] = 'name';
        $data['order'] = 'asc';
        return $this->getAllPackageQuery($data);
    }
    public function addPackage($data){
        $validator = Validator::make($data, [
            'price' => 'required|int',
            'name' => 'required|string',
            'duration' => 'required|string',
        ]);

       if($validator->fails()){
           return response()->json($validator->errors(), 422);
       }
       $d = $this->addPackageQuery($data);
       return $d;
    }
    public function updatePackage($data){
        $validator = Validator::make($data, [
            'price' => 'required|int',
            'name' => 'required|string',
            'duration' => 'required|string',
        ]);

       if($validator->fails()){
           return response()->json($validator->errors(), 422);
       }
       $d = $this->updatePackageQuery($data);
       return $d;
    }
    public function deletePackage($data){
        return $this->deletePackageQuery($data['id']);
    }
    public function getdeliveredOrderCount( ){
        return $this->getdeliveredOrderCountQuery(  );
    }
    public function getMemberRequestCount( ){
        return $this->getMemberRequestCountQuery(  );
    }
    public function get_total_pending_order( ){
        return $this->get_total_pending_orderQuery(  );
    }
    public function getdriverCount( ){
        return $this->getdriverCountQuery();
    }
    public function getTotalAmmount( ){
        return $this->getTotalAmmountQuery();
    }
    public function getTodaysProfit( ){
        $today = new DateTime();
        $today = $today->format('Y-m-d');
        $data['date_start'] = $today;
        $data['date_end'] = $today;
        return $this->getStatistics($data);


    }
    // public function getTodaysExpense( ){
    //     return $this->getTodaysExpenseQuery();
    // }
    // public function getTodaysSale( ){
    //     return $this->getTodaysSaleQuery();
    // }
    // public function getTodaysCashback( ){
    //     return $this->getTodaysCashbackQuery();
    // }
    // dashboard end
    /**
     * createUser
     * This method takes a array and store user  ...
     *
     * @param  array $data
     *
     * @return object $data
     */
    public function createUser($data){
    //    return $this->createUsers($data);
    $validator = Validator::make($data, [
         'phone' => 'required|unique:users',
     ]);

    if($validator->fails()){
        return response()->json($validator->errors(), 422);
    }
      try {
        // \Log::info($data);
        $data['password']=  Hash::make($data['password']);
        // $ob = [
        //     "name" => $data['name'],
        //     "phone" => $data['phone'],
        //     "password" => $data['password'],
        //     "countryCode" => $data['countryCode'],
        //     "userType" => $data['userType'],
        //     "country" => $data['country'],
        //     "state" => $data['state'],
        //     "address" => $data['address']
        // ];
        $d = $this->createUsers($data);
        return $d;
        // $ob['id'] = $d['id'];
        // $ob['lat'] = $d['lat'];
        // $ob['long'] = $d['long'];
        // return response()->json([
        //     'data' => $ob,
        // ], 200);
        // return $ob;
        } catch (\Exception $e){
            return response()->json([
                'message' => "Invalid requiest",
                'e' => $e,
            ], 422);
        }
    }
    /**
     * deleteUser
     * This method takes a array of keys get users  ...
     *
     * @param  array $data
     *
     * @return array $data
     */
    public function deleteUser($data){

        return $this->deleteUserQuery($data['id']);
    }
    /**
     * getAllCoustomers
     * This method takes a array of keys get users  ...
     *
     * @param  array $data
     *
     * @return array $data
     */
    public function getAllCoustomers($data){

        $data = $this->formatedgetApiData($data);
        return $this->getAllCoustomersQuery($data);
    }
    public function getAllFranchiseOwner($data){
        $data = $this->formatedgetApiData($data);
        $data['colName'] = 'name';
        $data['order'] = 'asc';
        return $this->getAllFranchiseOwnerQuery($data);
    }
    public function getNewFranchiseOwner($data){
        $data = $this->formatedgetApiData($data);
        $data['colName'] = 'name';
        $data['order'] = 'asc';
        return $this->getNewFranchiseOwnerQuery($data);
    }
    public function getFranchiseId($data){
        return $this->getFranchiseIdQuery($data);
    }
    public function insertPoints($data){
        return $this->insertPointsQuery($data);
    }
    public function getAllCountry($data){
        return $this->getAllCountryQuery($data);
    }
    public function getUsertype(){
         return $this->getUsertypeQuery();
    }
    public function getAllDriver($data){
        $data = $this->formatedgetApiData($data);
        $data['colName'] = 'name';
        $data['order'] = 'asc';
        return $this->getAllDriverQuery($data);
    }
    /**
     * getAllUsers
     * This method takes a array of keys get users  ...
     *
     * @param  array $data
     *
     * @return array $data
     */
    public function getAllUsers($data){
        $data = $this->formatedgetApiData($data);
        $data['colName'] = 'name';
        $data['order'] = 'asc';
        return $this->getAllUserQuery($data);
    }
    public function getSingleAddress($data){
        return $this->getSingleAddressQuery($data);
    }
    public function getAllShopOwner($data){
        $data = $this->formatedgetApiData($data);
        $data['colName'] = 'name';
        $data['order'] = 'asc';
        return $this->getAllShopOwnerQuery($data);
    }
    /**
     * updateUser
     * This method takes a array of data get users  ...
     *
     * @param  array $data
     *
     * @return array $data
     */
    public function updateUser(array $data){
        $ob = [];
        try {
            $ob = [
                "id" => $data['id'],
                "name" => $data['name'],
                "phone" => $data['phone'],
                "countryCode" => $data['countryCode'],
                "userType" => $data['userType'],
                "country" => $data['country'],
                "city" => $data['city'],
                "address" => $data['address'],
                "lat" => $data['lat'],
                "long" => $data['long']
            ];
        }catch (\Exception $e){
            return response()->json([
                'message' => "Invalid request",
            ], 422);
        }
        return $this->updatSingleUser($ob);
    }
    public function updateDriver(array $data){
        $ob = [];
        try {
            $ob = [
                "id" => $data['id'],
                "name" => $data['name'],
                "phone" => $data['phone'],
                "countryCode" => $data['countryCode'],
                "userType" => $data['userType'],
                "country" => $data['country'],
                "city" => $data['city'],
                "address" => $data['address'],
                "lat" => $data['lat'],
                "long" => $data['long']
            ];
        }catch (\Exception $e){
            return response()->json([
                'message' => "Invalid request",
            ], 422);
        }
        return $this->updatSingleUser($ob);
    }
    public function updateDeliveryAddress(array $data){
        $ob = [];
        try {
            $ob = [
                "id" => $data['id'],
                "apartment_number" => $data['apartment_number'],
                "title" => $data['title'],
                "phone" => $data['phone'],
                "countryCode" => $data['countryCode'],
                "city" => $data['city'],
                "country" => $data['country'],
                "user_id" => $data['user_id'],
                "address" => $data['address'],
                "lat" => $data['lat'],
                "long" => $data['long']
            ];
        }catch (\Exception $e){
            return response()->json([
                'message' => "Invalid request",
            ], 422);
        }
        return $this->updateDeliveryAddressQuery($ob);
    }
    public function updateShopOwner(array $data){
        $ob = [];
        try {
            $ob = [
                "id" => $data['id'],
                "name" => $data['name'],
                "phone" => $data['phone'],
                "countryCode" => $data['countryCode'],
                "userType" => $data['userType'],
                "country" => $data['country'],
                "city" => $data['city'],
                "address" => $data['address'],
                "lat" => $data['lat'],
                "long" => $data['long']
            ];
        }catch (\Exception $e){
            return response()->json([
                'message' => "Invalid request",
            ], 422);
        }
        return $this->updatSingleUser($ob);
    }
    public function addDeliveryAddress(array $data){
        $ob = [];
        try {
            $ob = [
                "apartment_number" => $data['apartment_number'],
                "title" => $data['title'],
                "phone" => $data['phone'],
                "countryCode" => $data['countryCode'],
                "city" => $data['city'],
                "country" => $data['country'],
                "user_id" => $data['user_id'],
                "address" => $data['address'],
                "lat" => $data['lat'],
                "long" => $data['long']
            ];
        }catch (\Exception $e){
            return response()->json([
                'message' => "Invalid request",
            ], 422);
        }
        return $this->addDeliveryAddressQuery($ob);
    }
    public function lightModeSave(){
        return $this->lightModeSaveQuery();
    }
    public function darkModeSave(){
        return $this->darkModeSaveQuery();
    }
    // Banner methods
    /**
     * getAllBanners
     * This method takes a array of data get Banner images  ...
     *
     * @param  no parameter
     *
     * @return array $data
     */
    public function getAllBanners(array $data)
    {
        $data = $this->formatedgetApiData($data);
        if(!isset($data['category_id'])) return $this->coustomErrorMessage("Please select a category");
        $data['category_id'] = $data['category_id'];
        return $this->getAllBannersQuery($data);
    }
    public function getAlluserBalance($data)
    {
        $data = $this->formatedgetApiData($data);
        // if(!isset($data['category_id'])) return $this->coustomErrorMessage("Please select a category");
        // $data['category_id'] = $data['category_id'];
        $data['colName'] = 'name';
        $data['order'] = 'asc';
        return $this->getAlluserBalanceQuery($data);
    }
    public function getAllScreens($data)
    {
        $data = $this->formatedgetApiData($data);
        $data['colName'] = 'id';
        $data['order'] = 'desc';
        return $this->getAllScreensQuery($data);
    }
    public function getPromotionScreens($data)
    {
        $data = $this->formatedgetApiData($data);
        $data['colName'] = 'id';
        $data['order'] = 'desc';
        return $this->getPromotionScreensQuery($data);
    }
    public function getAllrules($data)
    {
        return $this->getAllrulesQuery($data);
    }
    public function updateOnBoardScreen($data){
        $ob = [];
        try {
            $ob = [
                "id" => $data['id'],
                "image_url" => $data['image_url'],
                "subtitle" => $data['subtitle'],
                "title" => $data['title'],
            ];
        }catch (\Exception $e){
            return response()->json([
                'message' => "Invalid request",
            ], 422);
        }
        return $this->updateOnBoardScreenQuery($ob);
    }
    public function updatePromotionStatus($data){
        $ob = [];
        try {
            $ob = [
                "id" => $data['id'],
                "is_show" => $data['is_show'],
            ];
        }catch (\Exception $e){
            return response()->json([
                'message' => "Invalid request",
            ], 422);
        }
        return $this->updatePromotionStatusQuery($ob);
    }
    public function updateRules($data){
        $ob = [];
        try {
            $ob = [
                "id" => $data['id'],
                "privacy_policy" => $data['privacy_policy'],
                "return_refund" => $data['return_refund'],
                "terms_and_condition" => $data['terms_and_condition'],
            ];
        }catch (\Exception $e){
            return response()->json([
                'message' => "Invalid request",
            ], 422);
        }
        return $this->updateRulesQuery($ob);
    }
    public function getAllPlanRequest($data)
    {
        $data = $this->formatedgetApiData($data);
        // if(!isset($data['category_id'])) return $this->coustomErrorMessage("Please select a category");
        // $data['category_id'] = $data['category_id'];
        $data['colName'] = 'id';
        $data['order'] = 'desc';
        return $this->getAllPlanRequestQuery($data);
    }
    public function updatePlanRequestStatus($data){
        return $this->updatePlanRequestStatusQuery($data);
    }

    public function getAllTransaction(array $data,int $id){
        $data = $this->formatedgetApiData($data);
        return $this->getAllTransactionQuery($data, $id);
    }
    public function updateTransaction(array $data){
         return $this->updateTransactionQuery( $data );
    }
    public function getDeliveryAddress(array $data,int $id){
        $data = $this->formatedgetApiData($data);
        return $this->getDeliveryAddressQuery($data, $id);
    }
    /**
     * createBanner
     * This method takes a array of data create Banner images  ...
     *
     * @param  array $data
     *
     * @return array $data
     */
    public function createBanner($data)
    {
        // $franchise = $this->getFrachiseNameAndIdById();
        $ob = [
            'title' => $data['title'],
            // 'category_id' => $franchise['id'],
            'category_id' => $data['category_id'],
            'image' => $data['image']
        ];
        \Log::info($ob);
        return $this->createBannerQuery($ob);
    }
    /**
     * updateBanner
     * This method takes a array of data update Banner images  ...
     *
     * @param  array $data
     *
     * @return array $data
     */
    public function updateBanner(array $data)
    {
        $ob=[
            'id' => $data['id'],
            'title' => $data['title'],
            'image' => $data['image'],
            'category_id' => $data['category_id']
        ];

        return $this->updateBannerQuery($data);
    }
    /**
     * deleteBanner
     * This method takes a array  delete Banner image  ...
     *
     * @param  array $data
     *
     * @return massage
     */
    public function deleteBanner($data)
    {
        return $this->deleteBannerQuery($data['id']);
    }
    public function deleteAddress($data)
    {
        return $this->deleteAddressQuery($data['id']);
    }
    // profile
    public function profile_pic_update($data)
    {
        return $this->profile_pic_updateQuery($data);
    }
    public function password_update($data)
    {
        return $this->password_updateQuery($data);
    }
    public function info_update($data){
        return $this->info_updateQuery($data);
    }
    public function getUserInfo($id){
        return $this->getUserInfoQuery($id);
    }
    public function getGalary(){
        return $this->getGalaryQuery();
    }
    public function createGalary($data){
        return $this->createGalary($data);
    }
    public function updateGalary($data){
        return $this->updateGalary($data);
    }
    public function deleteGalary($data){
        return $this->deleteGalary($data);
    }
    // ************************ Expense **********************


    // Expense Category
    public function getExpenseCategory($data){
        return $this->getExpenseCategoryQuery($data);
    }
    public function addExpenseCategory($data){
        return $this->addExpenseCategoryQuery($data);
    }
    public function updateExpenseCategory($data){
        unset($data['_index']);
        unset($data['_rowKey']);
        unset($data['created_at']);
        unset($data['updated_at']);
        return $this->updateExpenseCategoryQuery($data);
    }
    public function deleteExpenseCategory($data){
        return $this->deleteExpenseCategoryQuery($data);
    }

    // Expense Sheets

    public function getExpenseSheets($data){
        $d = $this->getExpenseSheetsQuery($data);
        $total = $this->getTotalExpenseSheetsQuery($data);
        return response()->json([
            'data' => $d,
            'total' => $total,
        ], 200);
    }
    public function addExpenseSheets($data){
        return $this->addExpenseSheetsQuery($data);
    }
    public function updateExpenseSheets($data){
        unset($data['_index']);
        unset($data['_rowKey']);
        unset($data['created_at']);
        unset($data['updated_at']);
        return $this->updateExpenseSheetsQuery($data);
    }
    public function deleteExpenseSheets($data){
        return $this->deleteExpenseSheetsQuery($data);
    }

    // Statistics
    public function getStatistics($data){
        $today = new DateTime();
        $today = $today->format('Y-m-d');

        $date_start = isset($data['date_start'])? $data['date_start'] : $today;
        $date_end = isset($data['date_end'])? $data['date_end'] : $today;
        if( $data['date_start'] == $data['date_end']){
            $data['date_start'] =new DateTime($data['date_start']);
            $data['date_end'] = strtotime($data['date_end']);
            $data['date_end'] = strtotime("+1 day", $data['date_end']);
            $data['date_end'] = date('M d, Y', $data['date_end']);
            $data['date_end'] =new DateTime($data['date_end']);
            $data['date_start'] =  $data['date_start']->format('Y-m-d');
            $data['date_end'] =  $data['date_end']->format('Y-m-d');
        }
        // return $data;
        $sale =  $this->getSaleStatisticsQuery($data);
        $expense =  $this->getExpenseStatisticsQuery($data);
        // $profit= $this->getProfitStatisticsQuery($data);
        $grossProfit= $this->getGrossProfitStatisticsQuery($data);
        $totalCashback= $this->getCashbackStatisticsQuery($data);
        $netProfit = $grossProfit - $expense;
        return response()->json([
            'sale' => $sale,
            'expense' => $expense,
            // 'profit' => $profit,
            'grossProfit' => $grossProfit,
            'netProfit' => $netProfit,
            'totalCashback' => $totalCashback,
        ], 200);
    }

    // Send push notification to Customer
    public function sendPushNotificationToCustmer($data){
        $customers = [];
        if(sizeof($data['users']) >0){
            $customers = $this->getSelectedCustomerDeviceToken($data['users']);
        }else{
            $customers = $this->getCustomerDeviceToken($data['franchise_id']);
        }
        $all_tokens = [];
        foreach($customers as $value){
            $result = json_decode($value, true);
            // return $result;
            // $result =  $result->toArray();
            $all_tokens = array_merge($all_tokens,$result);
        }
        // return $all_tokens;
        $pushData = [
            "title"=>$data['title'],
            "body"=>$data['message'],
            "image"=>$data['image_url'],
            "type"=> 'general'
         ];
        // $userIds =
        //  \Log::info("Customers");
        //  \Log::info($customers);
        $this->sendPushNotificationToCustomersCommon($pushData,$all_tokens);
        return response()->json([
            'success' => 'true',
        ], 200);
    }

}
