<?php

namespace App\Modules\ProductModule;
use App\Queries\ProductQuery;
use App\commonClass\commonService;
use App\commonClass\commonQuery;
use PhpParser\Node\Stmt\TryCatch;
use Auth;
class ProductService
{
    // sear by ("incomplete method") -> still have to work 
    use ProductQuery;
    use commonService;
    use commonQuery;
    // Product

    public function getAllProductWithVariationPrice(array $data)
    {
        $data = $this->formatedgetApiData($data);
        if(Auth::user()->userType == 'ShopOwner') {
            $data['shopId'] = Auth::user()->shop_id;
        }
        return $this->getAllProductWithVariationPriceQuery($data);
    }
    public function getAllProductByCategoryWithVariationPrice(array $data)
    {
        $data = $this->formatedgetApiData($data); 
        // $data['colName'] = 'name';
        // $data['order'] = 'asc';
        return $this->getAllProductByCategoryWithVariationPriceQuery($data);
    }
    
    /**    
     * getAllCupon
     * This method takes a array of keys ...
     *
     * @param  array $data
     * 
     * @return array 
     */
    public function getAllCupon(array $data)
    {
        $data = $this->formatedgetApiData($data);
        return $this->getAllCuponQuery($data);
    }
    public function storeCupon(array $data){
        $user_list = [];
            $user_list = $data['user_list'];
            unset($data['user_list']);   
         
        $d= $this->storeCuponQuery($data);
         return $d;
     }
    public function updateCupon(array $data){  
            $user_list = [];
            $user_list = $data['user_list'];
            // array_splice($data, 5, 1);
            unset($data['user_list']);
            $d= $this->updateCuponQuery($data);
        return $d;
    }
    public function deleteCupon(array $data){
        $id = $data['id'];
        $d= $this->deleteCuponQuery($id);
        return $d;
    }
    public function getAllMenuCategory(array $data)
    {
        $data = $this->formatedgetApiData($data);
        $data['colName'] = 'name';
        $data['order'] = 'asc';
        return $this->getAllMenuCategoryQuery($data);
    }
    
    public function storeRestaurentCategory(array $data){
        $d= $this->storeRestaurentCategoryQuery($data);
         return $d;
     }
    public function updateRestaurentCategory(array $data){  
        $d= $this->updateRestaurentCategoryQuery($data);
        return $d;
    }
    public function getAllDiscountAnnouncement(array $data)
    {
        $data = $this->formatedgetApiData($data);
        return $this->getAllDiscountAnnouncementQuery($data);
    }
    public function updateDiscountAnnouncement(array $data){  
        $d= $this->updateDiscountAnnouncementQuery($data);
        return $d;
    }
    public function updateProductColumn(array $data)
    {
        $ob = [
            'id' => $data['id'],
            'must_prescribed' => $data['must_prescribed'],
        ];
        return $this->updateProductColumnQuery($ob);
    }
    public function deleteRestaurentCategory(array $data){
        $id = $data['id'];
        $d= $this->deleteRestaurentCategoryQuery($id);
        return $d;
    }
    public function getRequestedProducts(array $data){ 
        $data = $this->formatedgetApiData($data);
        // if(Auth::user()->userType == 'ShopOwner') {
        //     $data['shopId'] = Auth::user()->shop_id;
        // }
        return $this->getRequestedProductsQuery($data);
    }
    public function getAllProduct(array $data){ 
        $data = $this->formatedgetApiData($data);
        if(Auth::user()->userType == 'ShopOwner') {
            $data['shopId'] = Auth::user()->shop_id;
        }
        return $this->getAllProductQuery($data);
    }
    public function productDetails($id){
        return $this->productDetailsQuery($id);
    }
    public function storeProduct(array $data){
        if(!isset($data['images']))
        return $this->coustomErrorMessage("Please upload an image");
        
        $images = [];
        $images = $data['images'];
        $imageOb = [];
        foreach($data['images'] as $value){
            $ob = [
                'image' => $value['image'],
            ];
            array_push($imageOb, $ob);
        }
        $this->storeGalleryQuery($imageOb);
        unset($data['images']);
        unset($data['image']);
        if(sizeof($images) > 0){

            $data['image'] = $images[0]['image'];
            array_splice($images, 0, 1);
        }
        
        $product_tags = $data['product_tags'];
        unset($data['product_tags']);

        if($data['category_id'] == 2){
            $restaurant_category = $data['restaurant_category'];
            unset($data['restaurant_category']);
            $restaurantValue = $data['restaurantValue'];
            unset($data['restaurantValue']);
            $data['restaurant_category_id'] = $this->storeAndgetRestaurantMenu($restaurant_category,$restaurantValue);
        }
        
        // $ob =[
        //     "name" => $data["name"],
        //     "description" => $data["description"],
        //     "category_id" => $data["category_id"],
        //     "must_prescribed" => $data["must_prescribed"],  
        //     "restaurant_category_id" => $data["restaurant_category_id"],
        //     "discount" => $data["discount"],
        //     "percentage" => $data["percentage"],
        //     "warranty" => $data["warranty"],
        //     "partner_shop_id" => $data["partner_shop_id"],
        //     "product_category_id" => $data["product_category_id"],
        //     "product_subcategory_id" => $data["product_subcategory_id"],
        //     "franchise_id" => $data["franchise_id"],
        //     "band_id" => $data["band_id"],
        //     "image" => $data["image"],
        //     "isFeatured" => $data["isFeatured"],
        // ];
        // $data["franchise_id"]= Auth::user()->franchise_id;
    //    \Log::info('data');
    //    \Log::info($data);
        $d= $this->storeProductQuery($data);
        if(sizeof($product_tags) >0){
            $allTags = [];
            $allformatTags = [];
            foreach($product_tags as $val){
                $allOb = [
                    'franchise_id' =>$data['franchise_id'],
                    'category_id' =>$data['category_id'],
                    'name' =>$val,
                ]; 
                $forOb = [
                    'product_id' =>$d->id,
                    'name' =>$val,
                    'created_at'=> date("Y-m-d h:i:s")
                ];
                array_push($allTags,$allOb);
                array_push($allformatTags,$forOb);
            }
            $this->addAllTagsQuery($allTags);
            $this->addProductTagsQuery($allformatTags);
        }
        $this->updateOrCreateProductImages($images,$d->id);
        // return $d;
        return $this->getProductByIdQuery($d->id);

    }
    /** incomplete method
     * updateProduct
     * This method takes a array of keys update product ...
     *
     * @param  array $data
     * 
     * @return object
     */
    public function updateProduct(array $data){
        // $ob = [
        //     "id" => $data["id"],
        //     "description" => $data["description"],
        //     "franchise_id" => $data["franchise_id"],
        //     "isFeatured" => $data["isFeatured"],
        //     "name" => $data["name"],
        //     "category_id" => $data["category_id"],
        //     "must_prescribed" => $data["must_prescribed"],  
        //     // "restaurant_category_id" => $data["restaurant_category_id"],
        //     "discount" => $data["discount"],
        //     "percentage" => $data["percentage"],
        //     "warranty" => $data["warranty"],
        //     "partner_shop_id" => $data["partner_shop_id"],
        //     "product_category_id" => $data["product_category_id"],
        //     "product_subcategory_id" => $data["product_subcategory_id"],
        //     "band_id" => $data["band_id"],
        //     "totalSale" => $data["totalSale"],
        // ];
        $images = $data['images'];
        unset($data['images']);
        if(sizeof($images) > 0){

            $data['image']=$images[0]['image'];
        }

        if($data['category_id'] == 2){
            $restaurant_category = $data['restaurant_category'];
            unset($data['restaurant_category']); 
            $restaurantValue = $data['restaurantValue'];
            unset($data['restaurantValue']);
            $data['restaurant_category_id'] = $this->storeAndgetRestaurantMenu($restaurant_category,$restaurantValue);
        }
        $product_tags = $data['product_tags'];
        unset($data['product_tags']);
         $d= $this->updateProductQuery($data);
        if(sizeof($product_tags) >0){
            $allTags = [];
            $allformatTags = [];
            foreach($product_tags as $val){
                $allOb = [
                    'franchise_id' =>$data['franchise_id'],
                    'category_id' =>$data['category_id'],
                    'name' =>$val,
                ];
                $forOb = [
                    'product_id' =>$data['id'],
                    'name' =>$val
                ];
                array_push($allTags,$allOb);
                array_push($allformatTags,$forOb);
            }
            $this->addAllTagsQuery($allTags);
            $this->addOrCreteProductTagsQuery($allformatTags);
        }
        if (!$d) return $this->updateFailMessage("Product");

        return $this->getProductByIdQuery($data["id"]);
    }
    public function getProductById(array $data){
        return $this->getProductByIdQuery($data["product_id"]);
    }
    /**incomplete method
     * deleteProduct
     * This method takes a array of keys delete product  ...
     *
     * @param  array $data
     * 
     * @return array
     */
    public function deleteProduct(array $data){
        $d = $this->deleteProductQuery($data);
        return $this->deleteMessage($d,"Product");
    }
    public function deleteRequestedProduct(array $data){
        return $this->deleteRequestedProductQuery($data['id']);
    }
    // product images methods
    /**
     * getAllProductImages
     * This method takes a int return product images ...
     *
     * @param  int $data
     * 
     * @return array
     */
    public function getAllProductImages(int $id)
    {
        return $this->getAllProductImagesQuery($id);
    }
    /**
     * deleteProductImage
     * This method takes a int return delete image ...
     *
     * @param  int $data
     * 
     * @return array
     */
    public function deleteProductImage(array $data)
    {
        $id = $data['id'];
        $d  = $this->deleteProductImageQuery($id);
        return $this->deleteMessage($d, "Product Image");
    }
    /**
     * storeProductImages
     * This method takes a int return delete image ...
     *
     * @param  int $data
     * 
     * @return array
     */
    public function storeProductImages(array $data)
    {
        
        return $this->storeProductImagesQuery($data);
    }
    // Product brand
    /**
     * storeProductBand
     * This method takes a array of keys get brand list ...
     *
     * @param  array $data
     * 
     * @return array
     */
    public function getAllProductBand($data){
        $data = $this->formatedgetApiData($data);
        $data['colName'] = 'name';
        $data['order'] = 'asc';
        return $this->getAllProductBandQuery($data);
    }
    public function getAllBrand( ){
         return $this->getAllBrandQuery( );
    }
    public function getPaginateRestaurantCategory( $data, $id){
        $data = $this->formatedgetApiData($data);
        $data['colName'] = 'id';
        $data['order'] = 'asc';
        return $this->getAllRestaurantCategoryQuery($data, $id);
    }
    public function getRestaurantMenuCategory( $id){
        
        return $this->getRestaurantMenuCategoryQuery($id);
    }
    /**
     * storeProductBand
     * This method takes a array and store brand ...
     * @param  array $data
     * 
     * @return object
     */
    public function storeProductBand($data){
        $ob =[
            "name" => $data["name"]
        ];
        return $this->storeBandQuery($ob);
    }

    /**
     * updateProductBand
     * This method takes a array and update brand ...
     *
     * @param  array $data
     * 
     * @return object
     */
    public function updateProductBand($data){
        $ob = [
            "id" => $data["id"],
            "name" => $data["name"]
        ];
        $d = $this->updateProductBandQuery($ob);
        if (!$d) return $this->updateFailMessage("Brand");
        return $this->getProductBandByIdQuery($data["id"]);
    }


    /**
     * deleteProductBand
     * This method takes a array and update brand ...
     *
     * @param  array $data
     * 
     * @return object
     */

    public function deleteProductBand($data){
        $id = $data['id'];
        $check = $this->getProductBySpecificColumn('band_id', $id);
        if ($check) return $this->coustomErrorMessage("You can not delete a brand with product");
        $d = $this->deleteProductBandQuery($id);
        return $this->deleteMessage($d, "Product brand");
    }
    // Categroy

    /**
     * getAllCategory
     * This method takes a array keys and get Categroy ...
     *
     * @param  array $data
     * 
     * @return array list 
     */
    public function getAllCategory(array $data)
    {
        $data = $this->formatedgetApiData($data);
        $data['colName'] = 'name';
        $data['order'] = 'asc';
        return $this->getAllCategoryQuery($data);
    }

    /**
     * getAllCategoryById
     * This method takes a array  and get single Categroy ...
     *
     * @param  int $id
     * 
     * @return object 
     */
    public function getAllCategoryById($id)
    {
        return $this->getAllCategoryByIdQuery($id);
    }
    /**
     * storeCategory
     * This method takes a array  and store  Categroy ...
     *
     * @param  array $data
     * 
     * @return object 
     */
    public function storeCategory($data)
    {
        $ob = [
            "name" => $data["name"],
            "image" => $data["image"],
            "status" => $data["status"]
        ];
        return $this->storeCategoryQuery($ob);
    }
    /**
     * updateCategory
     * This method takes a array  and store update Categroy ...
     *
     * @param  array $data
     * 
     * @return object 
     */
    public function updateCategory(array $data)
    {
        $ob = [
            "id" => $data["id"],
            "name" => $data["name"],
            "image" => $data["image"],
            "status" => $data["status"]
        ];
        $d = $this->updateCategoryQuery($ob);
        if (!$d) return $this->updateFailMessage("Category");
        return $this->getAllCategoryByIdQuery($data["id"]);
    }
    /**
     * deleteCategory
     * This method takes a array with key as id and  delete Product Categroy ...
     *
     * @param  array $data
     * 
     * @return string 
     */
    public function deleteCategory(array $data)
    {
        $id = $data['id'];
        $check = $this->getProductBySpecificColumn('category_id', $id);
        if ($check) return $this->coustomErrorMessage("You can not delete a category with product");
        $d = $this->deleteCategoryQuery($id);
        return $this->deleteMessage($d, "Category");
    }
    // Product Categroy
    /**
     * getAllProductCategory
     * This method takes a array keys and get  Product Categroy ...
     *
     * @param  array $data
     * 
     * @return array list
     */
    public function getAllProductCategory(array $data)
    {
        $data = $this->formatedgetApiData($data);
        $data['colName'] = 'id';
        $data['order'] = 'asc';
        return $this->getAllProductCategorys($data);
    }

    /**
     * getAllProductCategoryById
     * This method takes a array  and get single   Product Categroy ...
     *
     * @param  int $id
     * 
     * @return object 
     */
    public function getAllProductCategoryById($id){
       
        return $this->getAllProductCategoryByIdQuery($id);
    }
    /**
     * storeProductCategory
     * This method takes a array  and store  Product Categroy ...
     *
     * @param  array $data
     * 
     * @return object 
     */
    public function storeProductCategory($data){
        $ob =[
            "name" => $data["name"],
            "category_id" => $data['category_id']

        ];
        if(isset($data["image"])){
            $ob['image'] = $data['image'];
        }
        return $this->storeProductCategoryQuery($ob);
    }
    /**
     * updateProductCategory
     * This method takes a array  and store update Product Categroy ...
     *
     * @param  array $data
     * 
     * @return object 
     */
    public function updateProductCategory(array $data){
        $ob = [
            "id" => $data["id"],
            "name" => $data["name"],
            "image" => $data["image"],
            "category_id" => $data['category_id']
        ];
        $d = $this->updateProductCategoryQuery($ob);
        if (!$d) return $this->updateFailMessage("Product Category");
        return $this->getProductCategoryByIdQuery($data["id"]);
    }


    /**
     * deleteProductCategory
     * This method takes a array with key as id and  delete Product Categroy ...
     *
     * @param  array $data
     * 
     * @return string 
     */
    public function deleteProductCategory(array $data){
        $id = $data['id'];
        $check = $this->getProductBySpecificColumn('product_category_id', $id);
        if ($check) return $this->coustomErrorMessage("You can not delete a category with product");
        $d= $this->deleteProductCategoryQuery($id);
        return $this->deleteMessage($d, "Product Category");
    }
    // Product Sub Category
    /**
     * getAllProductSubCategory
     * This method takes a array of keys and  get Product sub Categries ...
     *
     * @param  array $data
     * 
     * @return array  
     */
    public function getAllProductSubCategory(array $data){
        $data = $this->formatedgetApiData($data);
        $data['colName'] = 'name';
        $data['order'] = 'asc';
        return $this->getAllProductSubCategoryQuery($data);
    }
    public function getAllProductSubCategoryById($data,$id){
        $data = $this->formatedgetApiData($data);
        return $this->getAllProductSubCategoryByIdQuery($data,$id);
    }
    /**
     * storeProductSubCategory
     * This method takes a array  and  store Product sub Categry ...
     *
     * @param  array $data
     * 
     * @return object  
     */
    public function storeProductSubCategory($data){
        $ob = [
            "name" => $data["name"],
            "product_category_id" => $data["product_category_id"],
            
        ];
        if(isset($data["image"])){
            $ob['image'] = $data['image'];
        }
        return $this->storeSubCategoryQuery($ob);
    }
    /**
     * updateProductSubCategory
     * This method takes a array  and  store Product sub Categry ...
     *
     * @param  array $data
     * 
     * @return object  
     */
    public function updateProductSubCategory($data)
    {
        $ob = [
            "id" => $data["id"],
            "name" => $data["name"],
            "product_category_id" => $data["product_category_id"],
            
        ];
        if(isset($data["image"])){
            $ob['image'] = $data['image'];
        }
        $d= $this->updateProductSubCategoryQuery($ob);
        if (!$d) return $this->updateFailMessage("Product Sub Category");
        return $this->productSubCategoryByIdQuery($data["id"]);
    }


    /**
     * deleteProductSubCategory
     * This method takes a array  and  store Product sub Categry ...
     *
     * @param  array $data
     * 
     * @return string  
     */
    public function deleteProductSubCategory($data)
    {
        $id = $data['id'];
        $check = $this->getProductBySpecificColumn('product_subcategory_id', $id);
        if ($check) return $this->coustomErrorMessage("You can not delete a sub category with product");
        $d = $this->deleteProductSubCategoryQuery($id);
        return $this->deleteMessage($d, " Product sub");
    }
    public function getAllProductVariation(array $data)
    {

        $check = $this->getProductBySpecificColumn('id', $data['product_id']);
        if (!$check) return $this->coustomErrorMessage("Invalid request");
        $data = $this->formatedgetApiData($data);
        return  $this->getAllProductVariationQuery($data);
    }
    public function storeProductVarition(array $data)
    {
        // try {
            $ob = [
                'product_id' => $data['product_id'],
                'price' => $data['price'],
                'purchase_price' => $data['purchase_price'],
                'stock' => $data['stock'],
                'availability' => $data['availability'],
                'percentage' => $data['percentage'],
                'discount' => $data['discount'],
                'name' => $data['name'],
            ];
            $ob['name']= ucfirst(strtolower($ob['name']));
            $d = $this->getProductVaritionBySpecificColumn(['name' => $ob['name'],'product_id' => $ob['product_id']]);
        //    return $d;
            if ($d) return  $this->coustomErrorMessage("This Product variation has already been taken");
            return  $this->storeProductVaritionQuery($ob);
        // } catch (\Exception $e) {
        //     return response()->json([
        //         "message" => $e
        //     ], 422);
        // }        
    }
    public function updateProductVariation(array $data)
    {

        try {
            $ob = [
                'product_id' => $data['product_id'],
                'price' => $data['price'],
                'purchase_price' => $data['purchase_price'],
                'stock' => $data['stock'],
                'availability' => $data['availability'],
                'percentage' => $data['percentage'],
                'discount' => $data['discount'],
                'name' => $data['name'],
                'id' => $data['id'],
            ];
            $d = $this->updateProductVariationQuery($ob);
            if (!$d) return $this->updateFailMessage("Product variation");
            return $this->getProductVariationByIdQuery($data["id"]);
        } catch (\Exception $e) {
            return response()->json([
                "message" => "Invalid request"
            ], 422);
        }

        
    }

    public function deleteProductVariation(array $data){
        $d = $this->deleteProductVariationQuery($data['id']);
        return $this->deleteMessage($d, " Product variation");  
    }

    public function getTagsByCategory($franchise_id,$category_id){
        return $this->getTagsByCategoryQuery($franchise_id,$category_id);
    }


    



}
