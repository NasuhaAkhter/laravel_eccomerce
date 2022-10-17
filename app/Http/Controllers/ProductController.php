<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Modules\ProductModule\ProductService;
use App\commonClass\commonController;
class ProductController extends Controller
{
    use commonController;
    private $productService;
    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;  
    }
    // Cupon 
    public function getAllCupon(Request $request)
    {
        return $this->productService->getAllCupon($request->all());
    }
    public function storeCupon(Request $request){
        return $this->productService->storeCupon($request->all());
    }
    public function updateCupon(Request $request){
      return $this->productService->updateCupon($request->all());
    }
    public function deleteCupon(Request $request){
       return $this->productService->deleteCupon($request->all()); 
    }
    // Menu Category 
    public function getAllMenuCategory(Request $request)
    {
        return $this->productService->getAllMenuCategory($request->all());
    }
    public function storeRestaurentCategory(Request $request){
        return $this->productService->storeRestaurentCategory($request->all());
    }
    public function updateRestaurentCategory(Request $request){
      return $this->productService->updateRestaurentCategory($request->all());
    }
    public function deleteRestaurentCategory(Request $request){
       return $this->productService->deleteRestaurentCategory($request->all()); 
    }
    // Menu Category
    public function updateProductColumn(Request $request){
        return $this->productService->updateProductColumn($request->all());
      } 
    public function getAllDiscountAnnouncement(Request $request)
    { 
        return $this->productService->getAllDiscountAnnouncement($request->all());
    }
    public function updateDiscountAnnouncement(Request $request){
      return $this->productService->updateDiscountAnnouncement($request->all());
    }
     
    //Requested Product 
    public function getRequestedProducts(Request $request){
        return $this->productService->getRequestedProducts($request->all());
    }
    public function deleteRequestedProduct(Request $request)
    {
        return $this->productService->deleteRequestedProduct($request->all());
    }
    
    // Product 
    public function getAllProduct(Request $request){
        return $this->productService->getAllProduct($request->all());
    }
    public function getProductById(Request $request){
        return $this->productService->getProductById($request->all());
    }
    public function productDetails($id){
        return $this->productService->productDetails($id);
    }
    public function getAllProductWithVariationPrice(Request $request)
    {
        return $this->productService->getAllProductWithVariationPrice($request->all());
    }
    public function getAllProductByCategoryWithVariationPrice(Request $request)
    {
        return $this->productService->getAllProductByCategoryWithVariationPrice($request->all());
    }
    
    public function storeProduct(Request $request){
       return $this->productService->storeProduct($request->all());
    }
    public function updateProduct(Request $request){
         // if ($this->checkIdExistOrNotCommon($request->all()))
        //     return $this->checkIdExistOrNotCommon($request->all());
       return $this->productService->updateProduct($request->all());
    }
   
    public function deleteProduct(Request $request){
        // if ($this->checkIdExistOrNotCommon($request->all()))
        //     return $this->checkIdExistOrNotCommon($request->all());
       return $this->productService->deleteProduct($request->all());
    }
    // Product Images getAllProductImages
    public function getAllProductImages($id)  {
       
        return $this->productService->getAllProductImages($id);
    }
    public function deleteProductImage(Request $request)  {
        if ($this->checkIdExistOrNotCommon($request->all()))
            return $this->checkIdExistOrNotCommon($request->all());
        return $this->productService->deleteProductImage($request->all());
    }
    public function storeProductImages(Request $request)  {

        return $this->productService->storeProductImages($request->all());
    }
    // Product Band
    public function storeProductBand(Request $request){
       return $this->productService->storeProductBand($request->all());
    }
    public function updateProductBand(Request $request){
        if ($this->checkIdExistOrNotCommon($request->all()))
            return $this->checkIdExistOrNotCommon($request->all());
       return $this->productService->updateProductBand($request->all());
    }
    public function getAllProductBand(Request $request){
       return $this->productService->getAllProductBand($request->all());
    }
    public function getAllBrand(Request $request){
       return $this->productService->getAllBrand($request->all());
    }
    public function deleteProductBand(Request $request){
        if ($this->checkIdExistOrNotCommon($request->all()))
            return $this->checkIdExistOrNotCommon($request->all());
       return $this->productService->deleteProductBand($request->all());
    }
    //Category 
    public function getAllCategory(Request $request){
        return $this->productService->getAllCategory($request->all());
    }
    public function updateCategory(Request $request){ 
       return $this->productService->updateCategory($request->all());
    }
    public function storeCategory(Request $request){
        return $this->productService->storeCategory($request->all());
    }
    public function getAllCategoryById($id){
        return $this->productService->getAllCategoryById($id);
    }
    // Product Categroy
    public function getAllProductCategoryById($id){
       return $this->productService->getAllProductCategoryById($id);
    }
    public function storeProductCategory(Request $request){
       return $this->productService->storeProductCategory($request->all());
    }
    public function updateProductCategory(Request $request){
        if ($this->checkIdExistOrNotCommon($request->all()))
            return $this->checkIdExistOrNotCommon($request->all());
       return $this->productService->updateProductCategory($request->all());
    }
    public function getAllProductCategory(Request $request){
       return $this->productService->getAllProductCategory($request->all());
    }
    public function deleteProductCategory(Request $request){
        if ($this->checkIdExistOrNotCommon($request->all()))
            return $this->checkIdExistOrNotCommon($request->all());
       return $this->productService->deleteProductCategory($request->all());
    }
    // Product Sub Category
    public function storeProductSubCategory(Request $request)
    {
        return $this->productService->storeProductSubCategory($request->all());
    } 
    public function updateProductSubCategory(Request $request)
    {
        if ($this->checkIdExistOrNotCommon($request->all()))
            return $this->checkIdExistOrNotCommon($request->all());
        return $this->productService->updateProductSubCategory($request->all());
    }
    public function getAllProductSubCategory(Request $request)
    {
        if ($this->checkIdExistOrNotCommon($request->all()))
            return $this->checkIdExistOrNotCommon($request->all());
        return $this->productService->getAllProductSubCategory($request->all());
    }
    public function getAllProductSubCategoryById(Request $request,$id)
    {
        // if ($this->checkIdExistOrNotCommon($request->all()))
        //     return $this->checkIdExistOrNotCommon($request->all());
        return $this->productService->getAllProductSubCategoryById($request->all(),$id);
    }
    public function deleteProductSubCategory(Request $request)
    {
       if($this->checkIdExistOrNotCommon($request->all())) 
            return $this->checkIdExistOrNotCommon($request->all());
        return $this->productService->deleteProductSubCategory($request->all());
    }
    // product Varition
    /**
     * getAllProductVariation
     * This method takes a arry...
     *
     * @param  array $data
     * 
     * @return false or object
     */
    public function getAllProductVariation(Request $request)
    {
        if ($this->checkProductIdExistOrNotCommon($request->all()))
            return $this->checkProductIdExistOrNotCommon($request->all());
        return $this->productService->getAllProductVariation($request->all());
    }
    public function getAllRestaurantCategory(Request $request)
    {
        return $this->productService->getPaginateRestaurantCategory($request->all(), $request->id);
    }
    public function getRestaurantMenuCategory(Request $request)
    {
        
        return $this->productService->getRestaurantMenuCategory($request->id);
    }
    /**
     * storeProductVarition
     * This method takes a arry...
     *
     * @param  array $data
     * 
     * @return false or object
     */
    public function storeProductVarition(Request $request)
    {
        if ($this->checkProductIdExistOrNotCommon($request->all()))
            return $this->checkProductIdExistOrNotCommon($request->all());
        return $this->productService->storeProductVarition($request->all());
    }
    /**
     * updateProductVariation
     * This method takes a arry...
     *
     * @param  array $data
     * 
     * @return false or object
     */
    public function updateProductVariation(Request $request)
    {
        if ($this->checkIdExistOrNotCommon($request->all()))
            return $this->checkIdExistOrNotCommon($request->all());
        if ($this->checkProductIdExistOrNotCommon($request->all()))
            return $this->checkProductIdExistOrNotCommon($request->all());
        return $this->productService->updateProductVariation($request->all());
    }
    /**
     * deleteProductVariation
     * This method takes a arry...
     *
     * @param  array $data
     * 
     * @return false or object
     */
    public function deleteProductVariation(Request $request)
    {
        if ($this->checkIdExistOrNotCommon($request->all()))
            return $this->checkIdExistOrNotCommon($request->all());
        return $this->productService->deleteProductVariation($request->all());
    }

    public function getTagsByCategory($franchise_id,$category_id){
        return $this->productService->getTagsByCategory($franchise_id,$category_id);
    }

   

    
}
