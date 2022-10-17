<?php

namespace App\Queries;

use App\Models\User;
use App\Models\RequestedProduct;
use App\Models\ProductCategory;
use App\Models\ProductSubCategory;
use App\Models\ProductImage;
use App\Models\Variation;
use App\Models\RestaurantMenuCategory;
use App\Models\Band;
use App\Models\Product;
use App\Models\Category;
use App\Models\Coupon;
use App\Models\DiscountAnnouncement;
use App\Models\MenuCategory;
use App\Models\ProductTags;
use App\Models\Tags;
use App\Models\Galary;

use Auth;
date_default_timezone_set('Asia/Dhaka');
trait ProductQuery
{

    public function updateProductColumnQuery(array $data)
    {
        return Product::where('id', $data['id'])->update($data); 
    }
    public function getProductBySpecificColumn(string $colName, $value){
        return Product::where($colName,$value)->first();
    }
    public function addProductTagsQuery($data){
        return ProductTags::insert($data);
    }
    public function addAllTagsQuery($data){
        foreach($data as $value){
            Tags::firstOrCreate($value);
        }
        return;
       
    }
    public function addOrCreteProductTagsQuery($data){
        foreach($data as $value){
            ProductTags::firstOrCreate($value);
        }
        return;
       
    }
    public function getTagsByCategoryQuery($franchise_id,$category_id){
        return Tags::where([['franchise_id',$franchise_id],['category_id',$category_id]])->get();
       
    }


    public function updateOrCreateProductImages(array $data,int $id)
    {
        $images =[];
        ProductImage::where('product_id', $id)->delete();
        foreach ($data as $key => $value) {
            $image = ProductImage::create(["image"=> $value['image'], "product_id"=>$id]);
            array_push($images, $image);
        }
       return $images;
    }




    public function getRequestedProductsQuery(array $data){
        $str = $data['str'];
        $q = RequestedProduct::with('user','franchise')
        ->orderBy($data['colName'], $data['order']);
        if($str) {
            $q->where('text', 'like', "%$str%");
        }
        return $q->paginate($data['pageSize']);

    }
    public function deleteRequestedProductQuery(int $id)
    {
        return RequestedProduct::where('id', $id)->delete();  
    }
    public function getAllProductQuery(array $data){
        $str = $data['str'];
        $shopId= isset($data['shopId'])? $data['shopId']:'';
        $featured= isset($data['featured'])? $data['featured']:'all';
        $menuCategoryId= isset($data['menuCategoryId'])? $data['menuCategoryId']:'';
        $user = Auth::user();
        $q = Product::with('category', 'subCategory', 'brand', 'pshop', 'franchise')
        ->orderBy($data['colName'], $data['order']);
        if ($str) {
            $q->where(function ($query) use ($str) {
                $query->where('name', 'like', "%$str%");
                $query->orWhere('description', 'like', "%$str%");
                $query->orWhereHas('category', function ($query1) use ($str){
                    $query1->where('name', 'like', "%$str%");
                });
                $query->orWhereHas('subcategory', function ($query2) use ($str){
                    $query2->where('name', 'like', "%$str%");
                });
                $query->orWhereHas('brand', function ($query2) use ($str){
                    $query2->where('name', 'like', "%$str%");
                });
                $query->orWhereHas('pshop', function ($query2) use ($str){
                    $query2->where('name', 'like', "%$str%");
                });
            });
        }
        if($menuCategoryId){
            $q->where('restaurant_category_id', $menuCategoryId);
        }
        if($featured){
            if($featured == 'featured'){
                $q->where('isFeatured', 1);
            }else if($featured == 'non-featured'){
                $q->where('isFeatured', 0);
            }
        }
        if($shopId){
            $q->where('partner_shop_id', $shopId);
        }
        if($user->userType != "Admin"){
            $franchise_id = $user->franchise_id;
            $q->where('franchise_id', $franchise_id); 
        }
        return $q->paginate($data['pageSize']);
    }
    public function productDetailsQuery($id){
        return  Product::where('id',$id)->with('category','productcCategory', 'subCategory', 'brand', 'pshop', 'franchise','photo','tags','restaurantCategory')->first();
    }
    public function getAllCuponQuery(array $data) 
    {
        $str = $data['str'];
        $id = Auth::user()->id;
        $q = Coupon::with('category')->where('id', '!=', 0)
        ->orderBy($data['colName'], $data['order']);
        if ($str) {
            $q->where(function ($query) use ($str) {
                $query->where('code', 'like', "%$str%");
            });
        }
        return $q->paginate($data['pageSize']);
    }
    public function storeCuponQuery(array $data){
        $d = Coupon::create($data);
        return Coupon::with('category')->first();
    }
    public function updateCuponQuery(array $data){
        return Coupon::where('id', $data['id'])->update($data);
    }
    public function deleteCuponQuery(int $id){
        return Coupon::where('id', $id)->delete();
    }
    public function getAllMenuCategoryQuery(array $data){
        $str = $data['str'];
        $id = Auth::user()->id;
        $q = MenuCategory::where('id', '!=', 0)
        ->orderBy($data['colName'], $data['order']);
        if ($str) {
            $q->where(function ($query) use ($str) {
                $query->where('name', 'like', "%$str%");
            });
        }
        return $q->paginate($data['pageSize']);
    } 
    public function storeRestaurentCategoryQuery(array $data){
        return RestaurantMenuCategory::firstOrCreate($data);
    }
    public function updateRestaurentCategoryQuery(array $data){
        return RestaurantMenuCategory::where('id', $data['id'])->update($data);
    }
    public function deleteRestaurentCategoryQuery(int $id){
        return RestaurantMenuCategory::where('id', $id)->delete();
    }

    public function getAllDiscountAnnouncementQuery(array $data){
        $str = $data['str'];
        $user = Auth::user();
        if($user->userType == 'Admin'){
            $q = DiscountAnnouncement::where('franchise_id','!=', 0)->with('franchise')
        ->orderBy($data['colName'], $data['order']);
        }else{
            $q = DiscountAnnouncement::where('franchise_id', $user->franchise_id)->with('franchise')
        ->orderBy($data['colName'], $data['order']);
        }
        if ($str) { 
            $q->where(function ($query) use ($str) {
                $query->where('details', 'like', "%$str%");
            });
            $q->orWhereHas('franchise', function ($q) use ($str){
                $q->where('name', 'like', "%$str%");
            });
        }
        return $q->paginate($data['pageSize']);
    }
    public function updateDiscountAnnouncementQuery(array $data){
        $d = DiscountAnnouncement::where('id', $data['id'])->update($data);
        if($d == 1){
            return DiscountAnnouncement::where('id',$data['id'] )->with('franchise')->first();
        }else{
            return 0;
        }
    }
    /**
     * getAllProductQuery
     * This method takes a array of keys ...
     * 
     * @param  array $data
     *
     * @return array
     */

    public function getAllProductWithVariationPriceQuery(array $data)
    {
        $str = $data['str'];
        $shopId= isset($data['shopId'])? $data['shopId']:'';
        $id = Auth::user()->id;
        // $auth_user = User::where('id', $id)->first();
        $q = Product::where('franchise_id',$data['franchise_id'])
        ->with('category', 'subCategory', 'brand', 'pshop', 'franchise','variations')
        ->orderBy($data['colName'], $data['order']);
        if($str){
            $q->where(function ($query) use ($str) {
                $query->where('name', 'like', "%$str%");
                $query->orWhere('description', 'like', "%$str%");
                $query->orWhereHas('category', function ($query1) use ($str){
                    $query1->where('name', 'like', "%$str%");
                });
                $query->orWhereHas('subcategory', function ($query2) use ($str){
                    $query2->where('name', 'like', "%$str%");
                });
                $query->orWhereHas('brand', function ($query2) use ($str){
                    $query2->where('name', 'like', "%$str%");
                });
                $query->orWhereHas('pshop', function ($query2) use ($str){
                    $query2->where('name', 'like', "%$str%");
                });
            });
        }
        if($shopId){
            $q->where('partner_shop_id', $shopId);
        }
        // $q->whereHas('variations');
        $q->whereHas('franchise', function ($query1) use ($id) {
            $query1->where('user_id', $id);
        });
        return $q->paginate($data['pageSize']);
    }
    public function getAllProductByCategoryWithVariationPriceQuery(array $data)
    {
        $str = $data['str'];
        $availability = $data['availability'];
        $shopId= isset($data['shopId'])? $data['shopId']:'';

        $id = Auth::user()->id; 

        $q = Product::where('franchise_id',$data['franchise_id'])
            ->where('category_id', $data['category_id'])
            ->with('category', 'productcCategory','subCategory', 'brand', 'pshop', 'franchise','variations','restaurantCategory')
            ->orderBy($data['colName'], $data['order']);
        if($str){
            // $q->where(function ($query) use ($str) {
            //     $query->where('name', 'like', "%$str%");
            //     $query->orWhere('description', 'like', "%$str%");
            //     $query->orWhereHas('category', function ($query1) use ($str){
            //         $query1->where('name', 'like', "%$str%");
            //     });
            //     $query->orWhereHas('subcategory', function ($query2) use ($str){
            //         $query2->where('name', 'like', "%$str%");
            //     });
            //     $query->orWhereHas('brand', function ($query2) use ($str){
            //         $query2->where('name', 'like', "%$str%");
            //     });
            //     $query->orWhereHas('pshop', function ($query2) use ($str){
            //         $query2->where('name', 'like', "%$str%");
            //     });
            // });
            $q->where(function ($query) use($str){
                // $query->where($conditions);
                $words = explode(' ', $str);
                $index = 0;
                $nameCondition = [];
                $brandCondition = [];
                $categoryCondition = [];
                $subCategoryCondition = [];
                $restaurantCondition = [];
                $descriptionCondition = [];
                foreach($words as $w){
                    $nameob = ['name', 'like', "%$w%"];
                    array_push($nameCondition,$nameob);
                    array_push($brandCondition,$nameob);
                    array_push($subCategoryCondition,$nameob);
                    array_push($categoryCondition,$nameob);
                    $resob = ['category_name', 'like', "%$w%"];
                    array_push($restaurantCondition,$resob);
                    $desob = ['description', 'like', "%$w%"];
                    array_push($descriptionCondition,$desob);
                    // if($index == 0)  $query->where('name', 'like', "%$w%");
                    // else  $query->orWhere('name', 'like', "%$w%");
                    // $index++;
                }
                // \Log::info($nameCondition);
                $query->where($nameCondition);
                $query->orWhere($descriptionCondition);

                $query->orWhereHas('brand',function ($query1) use ($brandCondition){
                    $query1->where($brandCondition);
                });
                $query->orWhereHas('productcCategory',function ($query1) use ($categoryCondition){
                    $query1->where($categoryCondition);
                });
                $query->orWhereHas('subCategory',function ($query1) use ($subCategoryCondition){
                    $query1->where($subCategoryCondition);
                });
                $query->orWhereHas('restaurantCategory',function ($query1) use ($restaurantCondition){
                    $query1->where($restaurantCondition);
                });
            });


        }
        if($shopId){
            $q->where('partner_shop_id', $shopId);
        }
        if($availability){
            $q->whereHas('variations', function ($query){
                $query->where('availability',1);
            });
        }
        // $q->whereHas('variations');
        // $q->whereHas('franchise', function ($query1) use ($id) {
        //     $query1->where('user_id', $id);
        // });
        return $q->paginate($data['pageSize']);
    }
    public function storeProductQuery(array $data){
        return Product::create($data); 
    }
    public function storeGalleryQuery(array $data){
        foreach($data  as $value){
            Galary::firstOrCreate($value);
        }
        return "Successfully Uploaded"; 
    }
    public function updateProductQuery($data){
        
        return Product::where('id', $data['id'])->update($data);
    }
    public function getProductByIdQuery(int $id){

        return Product::where('id', $id)->first();
    }
    public function deleteProductQuery($id)
    {
        return Product::where('id', $id)->delete();
    }
    public function storeProductImagesQuery(array $data)
    {
        $images = [];
        foreach ($data as $key => $value) {
            $image = ProductImage::create($value);
            array_push($images, $image);
        }
        $imageOb = [];
        foreach($data  as $value){
            $ob = [
                'image' => $value['image'],
            ];
            array_push($imageOb, $ob);
        }
        $this->storeGalleryQuery($imageOb);
        return $images;
    }
    public function getAllProductImagesQuery(int $id){
        return ProductImage::where('product_id', $id)->get();
    }
    /**
     * deleteProductImageQuery
     * This method takes a id and delete  product image...
     *
     * @param  int $id
     * 
     * @return boolen 1|0
     */
    public function deleteProductImageQuery(int $id){
        return ProductImage::where('id', $id)->delete();
    }

    // Product Band
    /**
     * getAllProductBandQuery
     * This method takes a array of keys and search the key with the given value
     *
     * @param  array $data => str, pageSize,colName,order,page,
     * 
     * @return array list of Product brands
     */
    public function getAllProductBandQuery($data)
    {
        $str = $data['str'];
        $q = Band::orderBy($data['colName'], $data['order']);
        if ($str) {
            $q->where('name', 'like', "%$str%");
        }
        return $q->paginate($data['pageSize']);
    }
    public function getAllRestaurantCategoryQuery( $data, $id){
        // return $data;
        $str = $data['str'];
        $q = RestaurantMenuCategory::where('shop_id',$id);
        if($str) {
            $q->where('category_name', 'like', "%$str%");
        }
        return $q->orderBy($data['colName'], $data['order'])->paginate($data['pageSize']);
        // $q = RestaurantMenuCategory::where('shop_id',$id)->get();
        // return $q;
    }
    public function getRestaurantMenuCategoryQuery( $id){
        // return $id;
        return RestaurantMenuCategory::where('shop_id',$id)->get();
    }
    public function storeAndgetRestaurantMenu($data, $menu){ 
        $category_id = '';
        foreach($data as $value){

            $q = RestaurantMenuCategory::firstOrCreate([
                'shop_id' => $value['shop_id'],
                'category_name' => $value['category_name'],
            ]);
            if($q->category_name == $menu) $category_id = $q->id;
        }
        return $category_id;
    }
    public function getAllBrandQuery( )
    {
        // $str = $data['str'];

        $q = Band::orderby('id','asc')->get();
        return $q ;
    }
    /**
     * storeBandQuery
     * This method takes a array and store brand...
     *
     * @param  array $data
     * 
     * @return object  
     */
    public function storeBandQuery($data){
        return Band::create($data);
    }
    /**
     * updateProductBandQuery
     * This method takes a array and store update brand...
     *
     * @param  array $data
     * 
     * @return boolean 1|0 
     */
    public function updateProductBandQuery($data){
        return  Band::where('id', $data['id'])->update($data);
       
    }
    /**
     * updateProductBandByIdQuery
     * This method takes a id and get brand ...
     *
     * @param  int $id
     * 
     * @return object
     */
    public function getProductBandByIdQuery(int $id){
       return Band::where('id', $id)->first();
    }

    /**
     * deleteProductBandQuery
     * This method takes a key and delete brand ...
     *
     * @param  int $id
     * 
     * @return boolean 1|0 
     */
    public function deleteProductBandQuery($id)
    {
        return Band::where('id', $id)->delete();
    }
    //  Categroy

    /**
     * getAllCategoryByIdQuery
     * This method takes a array and get single cateogry...
     *
     * @param  array $data
     * 
     * @return object  
     */
    public function getAllCategoryByIdQuery($id)
    {  
        return  Category::where('id', $id)->first();
    }
    /**
     * storeCategoryQuery
     * This method takes a array and store  cateogry...
     *
     * @param  array $data
     * 
     * @return object  
     */
    public function storeCategoryQuery($data)
    {
        return  Category::create($data);
    }
    /**
     * updateCategoryQuery
     * This method takes a array and store update cateogry...
     *
     * @param  array $data
     * 
     * @return boolean 1|0 
     */
    public function updateCategoryQuery($data)
    {
        return Category::where('id', $data['id'])->update($data);
    }
    /**
     * deleteCategoryQuery
     * This method takes a id and store delete  cateogry...
     *
     * @param  int $id
     * 
     * @return boolean 1|0 
     */
    public function deleteCategoryQuery($id)
    {
        return  Category::where('id', $id)->delete();
    }

    /**
     * getAllCategoryQuery
     * This method takes a array of keys and search the key with the given value
     *
     * @param  array $data => str, pageSize,colName,order,page,
     * 
     * @return array list of categories
     */
    public function getAllCategoryQuery($data)
    {
        $str = $data['str'];
        $q = Category::orderBy($data['colName'], $data['order']);
        if ($str) {
            $q->where('name', 'like', "%$str%");
        }
        $d = $q->paginate($data['pageSize']);
        return response()->json([
            "data" => $d
        ], 200);
    }
    // Product Categroy
    /**
     * getAllProductCategoryByIdQuery
     * This method takes a array and get single product cateogry...
     *
     * @param  array $data
     * 
     * @return object  
     */
    public function getAllProductCategoryByIdQuery($id){
        return  ProductCategory::where('category_id', $id)->get();
    }
    /**
     * storeProductCategoryQuery
     * This method takes a array and store product cateogry...
     *
     * @param  array $data
     * 
     * @return object  
     */
    public function storeProductCategoryQuery($data){
        return  ProductCategory::create($data);
    }

    /**
     * updateProductCategoryQuery
     * This method takes a array and store update cateogry...
     *
     * @param  array $data
     * 
     * @return boolean 1|0 
     */
    public function updateProductCategoryQuery($data){
        return ProductCategory::where('id', $data['id'])->update($data);
        
    }
    public function getProductCategoryByIdQuery(int $id){
        return ProductCategory::where('id', $id)->first();
    }

    /**
     * deleteProductCategoryQuery
     * This method takes a id and store delete product cateogry...
     *
     * @param  int $id
     * 
     * @return boolean 1|0 
     */
    public function deleteProductCategoryQuery($id)
    {
        return  ProductCategory::where('id', $id)->delete();
       
    }
    /**
     * getAllProductCategorys
     * This method takes a array of keys and search the key with the given value
     *
     * @param  array $data => str, pageSize,colName,order,page,
     * 
     * @return array list of Product categories
     */
    public function getAllProductCategorys($data)
    {
        // return $data;
        $str = $data['str'];
        $q = ProductCategory::where('category_id', $data['id'])->orderBy($data['colName'], $data['order']);
        if($str){
            $q->where('name', 'like', "%$str%");
        }
        $d = $q->paginate($data['pageSize']);
        return response()->json([
            "data" => $d
        ], 200);
    }

    // Product Sub Category
    /**
     * getAllProductSubCategoryQuery
     * This method takes a array of keys and search the key with the given value
     *
     * @param  array $data => str, pageSize,colName,order,page,
     * 
     * @return array list of Product sub categories
     */
    public function getAllProductSubCategoryQuery(array $data)
    {
        $str = $data['str'];
        $q = ProductSubCategory::where('product_category_id', $data['id'])
            ->orderBy($data['colName'], $data['order']);
        if ($str) {
            $q->where('name', 'like', "%$str%");
        }
        return $q->paginate($data['pageSize']);
    }
    public function getAllProductSubCategoryByIdQuery($data,$id){
        $str = $data['str'];
        $q = ProductSubCategory::where('product_category_id', $id);
        if ($str) {
            $q->where('name', 'like', "%$str%");
        }
        return $q->get();
    }
    /**
     * storeSubCategoryQuery
     * This method takes a array and store new sub cateogry...
     *
     * @param  array $data
     * 
     * @return object 
     */
    public function storeSubCategoryQuery(array $data)
    {
        return ProductSubCategory::create($data);
    }

    /**
     * updateProductSubCategoryQuery
     * This method takes a array and  update sub cateogry...
     *
     * @param  array $data
     * 
     * @return boolean 1|0 
     */
    public function updateProductSubCategoryQuery(array $data)
    {
        return ProductSubCategory::where('id', $data['id'])->update($data);
    }
    /**
     * productSubCategoryByIdQuery
     * This method takes a id get sub cateogry...
     *
     * @param  int $id
     * 
     * @return object 
     */
    public function productSubCategoryByIdQuery(int $id){
       return ProductSubCategory::where('id', $id)->first();
    }


    /**
     * deleteProductSubCategoryQuery
     * This method takes a id and store delete sub cateogry...
     *
     * @param  int $id
     * 
     * @return boolean 1|0 
     */
    public function deleteProductSubCategoryQuery($id)
    {
        return ProductSubCategory::where('id', $id)->delete();
    }
    /**
     * getAllProductVariationQuery
     * This method takes a data...
     *
     * @param  array $data
     * 
     * @return array
     */
    public function getAllProductVariationQuery(array $data)
    {
        $str = $data['str'];
        $q = Variation::orderBy($data['colName'], $data['order'])->where('product_id',$data['product_id']);
        if($str){
            $q->where('name', 'like', "%$str%");
        }
       return $q->get();
    }
    /**
     * storeProductVaritionQuery
     * This method takes a data...
     *
     * @param  array $data
     * 
     * @return object
     */
    public function storeProductVaritionQuery(array $data)
    {
         return Variation::create($data);
    }
    /**
     * getProductVaritionBySpecificColumn
     * This method takes array of object string...
     *
     * @param  array ({string $colName, string $value})
     * @param  string $value
     * 
     * @return object
     */
    public function getProductVaritionBySpecificColumn(array $data)
    {
      
        $q = Variation::orderBy('id', 'desc');
        foreach ($data as $key => $value) {
            $q->where($key, $value);
        }
        return $q->first();
    }
    /**
     * updateProductVariationQuery
     * This method takes a data...
     *
     * @param  array $data
     * 
     * @return bolean
     */
    public function updateProductVariationQuery(array $data)
    {
        $d = Variation::where('id', $data['id'])->update($data);
        return $d;
    }

    /**
     * getProductVariationByIdQuery
     * This method takes a data...
     *
     * @param  array $data
     * 
     * @return object
     */
    public function getProductVariationByIdQuery(int $id)
    {
        
        return Variation::where('id', $id)->first();
       
    }
    /**
     * deleteProductVariationQuery
     * This method takes a id...
     *
     * @param  int $id
     * 
     * @return bool
     */
    public function deleteProductVariationQuery(int $id)
    {
        return Variation::where('id', $id)->delete();  
    }
    
   
   
}
