<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Admin;
use Illuminate\Http\Request;
use App\Modules\UserModule\UserService;
use App\commonClass\commonController;
use Auth;
use Session; 
class UserController extends Controller
{
    use commonController;
    private $userService;
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }
    // dashboard start
    public function getRecentOrderTable(Request $request){
         return $this->userService->getRecentOrderTable();
    }
    public function getDriverTable(Request $request){
         return $this->userService->getDriverTable();
    }
    public function getCustomerTable(Request $request){
         return $this->userService->getCustomerTable();
    }
    public function getShopTable(Request $request){
         return $this->userService->getShopTable();
    }
    public function getFranchiseTable(Request $request){
         return $this->userService->getFranchiseTable();
    }
    public function getProductCount(Request $request){
         return $this->userService->getProductCount();
    }
    public function getFranchiseCount(Request $request){
         return $this->userService->getFranchiseCount();
    }
    public function getOrderCount(Request $request){
         return $this->userService->getOrderCount();
    }
    public function getCustomerCount(Request $request){
         return $this->userService->getCustomerCount();
    }
    public function getShopCount(Request $request){
         return $this->userService->getShopCount();
    }
    
    public function getdeliveredOrderCount(Request $request){
         return $this->userService->getdeliveredOrderCount();
    }
    public function getMemberRequestCount(Request $request){
         return $this->userService->getMemberRequestCount();
    }
    public function get_total_pending_order(Request $request){
         return $this->userService->get_total_pending_order();
    }
    public function getdriverCount(Request $request){
         return $this->userService->getdriverCount();
    }
    public function getTotalAmmount(Request $request){
         return $this->userService->getTotalAmmount();
    }
    public function getTodaysProfit(Request $request){
         return $this->userService->getTodaysProfit();
    }
    // public function getTodaysExpense(Request $request){
    //      return $this->userService->getTodaysExpense();
    // }
    // public function getTodaysSale(Request $request){
    //      return $this->userService->getTodaysSale();
    // }
    // public function getTodaysCashback(Request $request){
    //     return $this->userService->getTodaysCashback();
    // }
    public function insertPoints(Request $request){
         return $this->userService->insertPoints($request->all());
    }
    // dashboard end
    public function getAllPackage(Request $request){
        return $this->userService->getAllPackage($request->all());
   }
   public function addPackage(Request $request){
        return $this->userService->addPackage($request->all());
    }
   public function updatePackage(Request $request){
        return $this->userService->updatePackage($request->all());
    }
   public function deletePackage(Request $request){
        return $this->userService->deletePackage($request->all());
    }
    public function upload(Request $request)
    {

        $png_url = "profile-" . time() . ".jpg";
        $path = public_path(). '/uploads/' . $png_url;
        $img = $request->img;
        $img = substr($img, strpos($img, ",") + 1);
        $data = base64_decode($img);
        $success = file_put_contents($path, $data);
        // request()->file('img')->store('uploads');
        //  $pic= $request->img->hashName();
        //  $pic= $this->url."/uploads/$pic";
        /*update the profile pic*/
        //return Gallery::create($data);
        $pic= $this->url."/uploads/$png_url";
         return response()->json([
             'imageUrl'=> $pic,
             'success'=> true
         ],200);
     }
    public function uploadImages(Request $request){
        request()->file('img')->store('uploads');
        $pic = $request->img->hashName();
        // $pic = "/uploads/$pic";
        $url = env('APP_URL');
        $pic= "$url/uploads/$pic";
        return response()->json([
            'image' => $pic
        ], 200); 
    }
    public function test(){
        return "hgff";
      //    return $this->userService->usermoduleMethod();
    }
    public function logout(){
        Auth::logout();
        Session::flush();
        return redirect("/");
    }
    public function getAllCoustomers(Request $request){
        $data = $request->all();
        return $this->userService->getAllCoustomers($data);
    }
    public function getAllFranchiseOwner(Request $request){
        $data = $request->all();
        return $this->userService->getAllFranchiseOwner($data);
    }
    public function getNewFranchiseOwner(Request $request){
        $data = $request->all();
        return $this->userService->getNewFranchiseOwner($data);
    }
    public function getFranchiseId(Request $request){
         return $this->userService->getFranchiseId($request->all());
    }
    public function getAllCountry(Request $request){
        $data = $request->all();
        return $this->userService->getAllCountry($data);
    }
    public function getAllDriver(Request $request){
        $data = $request->all();
        return $this->userService->getAllDriver($data);
    }
    public function getAllUsers(Request $request){
        $data = $request->all();
        return $this->userService->getAllUsers($data);  
    }
    public function getAllShopOwner(Request $request){
        $data = $request->all();
        return $this->userService->getAllShopOwner($data);  
    }
    public function getUsertype(Request $request){
         return $this->userService->getUsertype(); 
    }
    public function updateUser(Request $request){
        $data = $request->all();
        return $this->userService->updateUser($data);
    }
    public function updateShopOwner(Request $request){
        $data = $request->all();
        return $this->userService->updateShopOwner($data);
    }
    public function updateDriver(Request $request){
        $data = $request->all();
        return $this->userService->updateDriver($data);
    }
    public function createUser(Request $request){
        $data = $request->all();
        return $this->userService->createUser($data);
    }
    public function createShopOwner(Request $request){
        $data = $request->all();
        return $this->userService->createShopOwner($data);
    }
    public function createDriver(Request $request){
        $data = $request->all();
        return $this->userService->createDriver($data);
    }
    public function deleteUser(Request $request){
        if ($this->checkIdExistOrNotCommon($request->all())) {
            return  $this->checkIdExistOrNotCommon($request->all());
        }
        return $this->userService->deleteUser($request->all());
    }
    public function login(Request $request){
        // return $request->all();
        // return 1;
        $user = User::where('phone', $request->phone)->first();

        if(!$user){
            return response()->json([
                'message' => "Phone number doesn't exist!",
            ],422);
        }
        if($user){
            if($user->userType != 'Admin' && $user->userType != 'FranchiseOwner' && $user->userType != "ShopOwner"){

                return response()->json([
                    'message' => "You don't have authorization!",
                ],422);
            }
            if($user->userType== 'FranchiseOwner' && $user->franchise_id == 0){
                return response()->json([
                    'message' => "You don't have authorization!",
                ],422);
            }
            else if($user->userType== 'ShopOwner' && $user->franchise_id == 0){
                return response()->json([
                    'message' => "You don't have authorization!",
                ],422);
            }
        }
        if (Auth::attempt(['phone' => $request->phone, 'password' => $request->password ])) {
           return Auth::user();
         }
         else{
            return response()->json([
                'message' => "invalid login credentials.",
            ],422);
         }
    }
    public function lightModeSave(Request $request){
         return $this->userService->lightModeSave();
    }
    public function darkModeSave(Request $request){
         return $this->userService->darkModeSave();
    }
    // Galary 
    public function getGalary(){
        return $this->userService->getGalary();
    }
    public function createGalary(Request $request){
        return $this->userService->createGalary($request->all());
    }
    public function updateGalary(Request $request){
        return $this->userService->updateGalary($request->all());
    }
    public function deleteGalary(Request $request){
        return $this->userService->deleteGalary($request->all());
    }
    // Banner 
    public function getAllBanners(Request $request)
    {
        return $this->userService->getAllBanners($request->all());
    }
    public function createBanner(Request $request)
    {
        return $this->userService->createBanner($request->all());
    }
    public function updateBanner(Request $request)
    {
       if( $this->checkIdExistOrNotCommon($request->all())){
         return  $this->checkIdExistOrNotCommon($request->all());
       }

        return $this->userService->updateBanner($request->all());
    }
    public function addDeliveryAddress(Request $request)
    {
        return $this->userService->addDeliveryAddress($request->all());
    }
    public function updateDeliveryAddress(Request $request)
    {
        return $this->userService->updateDeliveryAddress($request->all());
    }
    // profile 
    public function profile_pic_update(Request $request)
    {
        return $this->userService->profile_pic_update($request->all());
    }
    public function password_update(Request $request)
    {
        return $this->userService->password_update($request->all());
    }
    public function info_update(Request $request)
    {
        return $this->userService->info_update($request->all());
    }
    public function deleteBanner(Request $request)
    {
        $this->checkIdExistOrNotCommon($request->all());
        return $this->userService->deleteBanner($request->all());
    }
    public function deleteAddress(Request $request)
    {
         return $this->userService->deleteAddress($request->all());
    }
    public function getAlluserBalance(Request $request){
        return $this->userService->getAlluserBalance($request->all());
    }
    public function getAllScreens(Request $request){
        return $this->userService->getAllScreens($request->all());
    }
    public function getPromotionScreens(Request $request){
        return $this->userService->getPromotionScreens($request->all());
    }
    public function getAllrules(Request $request){
        return $this->userService->getAllrules($request->all());
    }
    public function updateRules(Request $request){
        return $this->userService->updateRules($request->all());
    }
    public function updateOnBoardScreen(Request $request){
        return $this->userService->updateOnBoardScreen($request->all());
    }
    public function updatePromotionStatus(Request $request){
        return $this->userService->updatePromotionStatus($request->all());
    }
    public function getAllPlanRequest(Request $request){
        return $this->userService->getAllPlanRequest($request->all());
    }
    public function updatePlanRequestStatus(Request $request){
        return $this->userService->updatePlanRequestStatus($request->all());
    }
    
    public function getSingleAddress(Request $request){
        return $this->userService->getSingleAddress($request->all());
    }
    public function getAllTransaction(Request $request){
        return $this->userService->getAllTransaction($request->all(), $request->id);
    }
    public function updateTransaction(Request $request){
        return $this->userService->updateTransaction($request->all());
    }
    public function getDeliveryAddress(Request $request){
        return $this->userService->getDeliveryAddress($request->all(), $request->id);
    }
    public function getUserInfo($id){
        return $this->userService->getUserInfo($id);
    }

    // ******************************************* Expense ********************************************************************

    // Expense Category
    public function getExpenseCategory(Request $request){
        return $this->userService->getExpenseCategory($request->all());
    }
    public function addExpenseCategory(Request $request){
        return $this->userService->addExpenseCategory($request->all());
    }
    public function updateExpenseCategory(Request $request){
        return $this->userService->updateExpenseCategory($request->all());
    }
    public function deleteExpenseCategory(Request $request){
        return $this->userService->deleteExpenseCategory($request->all());
    }

    // Expense Sheets

    public function getExpenseSheets(Request $request){
        return $this->userService->getExpenseSheets($request->all());
    }
    public function addExpenseSheets(Request $request){
        return $this->userService->addExpenseSheets($request->all());
    }
    public function updateExpenseSheets(Request $request){
        return $this->userService->updateExpenseSheets($request->all());
    }
    public function deleteExpenseSheets(Request $request){
        return $this->userService->deleteExpenseSheets($request->all());
    }

    // statistics 
    public function getStatistics(Request $request){
        return $this->userService->getStatistics($request->all());
    }

    // Send push notification to Customers
    public function sendPushNotificationToCustmer(Request $request){
        return $this->userService->sendPushNotificationToCustmer($request->all());
    }



}
