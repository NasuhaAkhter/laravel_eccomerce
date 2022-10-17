<template>
    <div class="_main_content">
        <div class="row">
            <div class="col-12 col-md-6 col-lg-6 _mar_b20">
                <p class="_label _mar_b15">Product Name</p>
                <Input v-model="form.name" type="text" placeholder="Product name "/>
            </div>  
            <div class="col-12 col-md-6 col-lg-6 _mar_b20">
                <p class="_label _mar_b15">Category Name</p>
                <Select v-model="form.category_id" filterable @on-change="getAllProductCategoryOnChange" :disabled="isShopOwner">
                    <Option v-for="(item, index) in categories" :value="item.id" :key="index" >{{item.name}}</Option>
                </Select>
            </div>
            <div class="col-12 col-md-4 col-lg-4 _mar_b20" v-if="form.category_id && form.category_id !=2" >
                <p class="_label _mar_b15">Product Category Name</p>
                <Select v-model="form.product_category_id" filterable @on-change="getAllProductSubCategoryOnChange">
                    <Option v-for="(item, index) in product_category" :value="item.id" :key="index">{{item.name}}</Option>
                </Select>
            </div>
            <!-- <div class="col-12 col-md-2 col-lg-2 _mar_b20" v-if="form.category_id" >
                <p class="_label _mar_b15">Add  Product Category</p>
                <Button type="primary" @click="openCategoryModal">Add</Button>
            </div> -->
            <div class="col-12 col-md-4 col-lg-4 _mar_b20" v-if="form.category_id && form.category_id !=2">
                <p class="_label _mar_b15"> Product Sub Category Name</p>
                <Select v-model="form.product_subcategory_id" filterable>
                    <Option v-for="(item, index) in allSubCategories" :value="item.id" :key="index" >{{item.name}}</Option>
                </Select>
            </div>
             <!-- <div class="col-12 col-md-2 col-lg-2 _mar_b20" v-if="form.product_category_id" >
                <p class="_label _mar_b15">Add  Product Sub Category</p>
                <Button type="primary" @click="openSubCategoryModal">Add</Button>
            </div> -->
            <div class="col-12 col-md-4 col-lg-4 _mar_b20" v-if="form.category_id ">
                <p class="_label _mar_b15">Partner Shop Name</p>
                <Select v-model="form.partner_shop_id" filterable  @on-change="onShopSelect" :remote-method="getPernerShops" :disabled="isShopOwner">
                        <Option v-for="(option, index) in partnerShops" :value="option.id" :key="index" >{{option.name}}</Option>
                </Select>
            </div>
                   <!-- <div class="col-12 col-md-4 col-lg-4 _mar_b20"  >
                <p class="_label _mar_b15">Product Tags</p>
                <Select v-model="product_tags" multiple  filterable allow-create @on-create="handleTagCreate">
                    <Option v-for="item in tags" :value="item.name" :key="item.id">{{ item.name }}</Option>
                </Select>
            
            </div> -->
            <div class="col-12 col-md-4 col-lg-4 _mar_b20" v-if="form.category_id == 2 && form.partner_shop_id !='' && form.partner_shop_id !=null" >
                <p class="_label _mar_b15"> Restaurant Category Name</p>
                <Select v-model="restaurantValue" filterable allow-create @on-create="handleRCategoryCreate" placeholder="Lunch or Dinner Or Drinks ..." :remote-method="getAllRetaurant">
                    <Option v-for="(item, index) in restaurant_category" :value="item.category_name" :key="index" >{{item.category_name}}</Option>
                </Select>
            </div>  
            <div class="col-12 col-md-4 col-lg-4 _mar_b20" v-if="form.category_id && form.category_id !=2" >
                <p class="_label _mar_b15">Brand Name</p>
                <Select v-model="form.band_id" filterable :remote-method="getProductBand">
                    <Option v-for="(option, index) in allBrand" :value="option.id" :key="index" >{{option.name}}</Option>
                </Select>
            </div>  
            <div class="col-12 col-md-4 col-lg-4 _mar_b20"  >
                <p class="_label _mar_b15">Featured Product</p>
                <RadioGroup  v-model="form.isFeatured">
                    <Radio :label="0" >No</Radio>
                    <Radio :label="1">Yes</Radio>
                </RadioGroup>
            </div>  
            <div class="col-12 col-md-4 col-lg-4 _mar_b20" v-if="form.category_id == 3" >
                <p class="_label _mar_b15">Prescription Must </p>
                <RadioGroup  v-model="form.must_prescribed">
                    <Radio :label="0" >No</Radio>
                    <Radio :label="1">Yes</Radio>
                </RadioGroup>
            </div>  
            <!-- <div class="col-12 col-md-4 col-lg-4 _mar_b20"  > 
                <p class="_label _mar_b15">Discount Type</p>
                   <RadioGroup v-model="isDiscount">
                    <Radio :label="0" >Amount</Radio>
                    <Radio :label="1">Percentage</Radio>
                </RadioGroup>
            </div>   -->
            <div class="col-12 col-md-4 col-lg-4 _mar_b20" v-if="isDiscount==0" >
                <p class="_label _mar_b15">Discount</p>
                <InputNumber style="width:100%"  placeholder="৳ 0.00"  :min="0" :formatter="value => `৳ ${value}`" :parser="value => value.replace('৳ ', '')" v-model="form.discount" ></InputNumber>
                </div>  
            <!-- <div class="col-12 col-md-4 col-lg-4 _mar_b20"  v-else-if="isDiscount==1">
                <p class="_label _mar_b15">Percentage</p>
                    <InputNumber style="width:100%"  placeholder="0.00%" :max="100" :min="0" :formatter="value => `${value}%`" :parser="value => value.replace('', '%')" v-model="form.percentage" ></InputNumber>
            </div>   -->
            <div class="col-12 col-md-4 col-lg-4 _mar_b20"  >
                <p class="_label _mar_b15">Warranty</p>
                <Input v-model="form.warranty" type="text" placeholder="Product Warranty "/>
            </div>  
            <div class="col-12 col-md-12 col-lg-12 _mar_b20">
                <p class="_label _mar_b15">Product Description</p>
                <Input v-model="form.description" type="textarea" :autosize="{minRows: 2,maxRows: 5}" placeholder="Product Description"/>
            </div>  
            <div class="col-12 col-md-12 col-lg-12 _mar_b20">
                <p class="_label _mar_b15">Image</p>
                <div class="demo-upload-list" >
                    <template v-if="form.image">
                        <img :src="form.image">
                        <div class="demo-upload-list-cover">
                            <Icon type="ios-eye-outline" @click.native="handleView( )"></Icon>
                            <Icon type="ios-trash-outline" @click.native="handleRemove()"></Icon>
                        </div>
                    </template>
                    <!-- <template v-else>
                        <Progress v-if="item.showProgress" :percent="item.percentage" hide-info></Progress>
                    </template> -->
                </div>
                <div class="row">
                    <div class="col-12 col-md-6 col-lg-6 _mar_b20">
                    <Upload
                        ref="upload"
                        name="img"
                        :show-upload-list="false"
                        :format="['jpg','jpeg','png','webp']"
                        :max-size="20480"
                        :on-success="handleSuccess"
                        :on-format-error="handleFormatError"
                        :on-exceeded-size="handleMaxSize"
                        type="drag"
                        action="/usermodule/upload/images"
                        >
                        <div style="padding: 20px 0">
                            <Icon type="ios-cloud-upload" size="52" style="color: #3399ff"></Icon>
                            <p>Click or drag files here to upload</p>
                        </div>
                    </Upload>
                    </div>
                    <div class="col-12 col-md-6 col-lg-6 _mar_b20" style="background-color: white">
                        <div @click="galaryModalOn" style="padding: 20px 220px 0">
                            <Icon type="ios-cloud-upload" size="52" style="color: #3399ff"></Icon>
                            <p>Add Image From Gallery</p> 
                        </div>
                    </div>
                </div>                
            </div>  
        </div>
        <div >
            <Button @click="$router.push('/productmodule/product')"  >Cancel</Button>
            <Button type="primary"  @click="createNewProduct" :loading="isLoading" :disabled="isLoading">{{isLoading? 'Please wait' : 'Update'}}</Button>
        </div>
        <Modal title="Create Category"  v-model="categoryModal" :mask-closable="false">
            <Form  :model="form" label-position="top" >
				<FormItem prop="product_category_id" :error="category_error.category" label="Category name" >
					<Select v-model="category_form.category_id" filterable  disabled>
                        <Option v-for="(item, index) in categories" :value="item.id" :key="index" >{{item.name}}</Option>
                    </Select>
                </FormItem>
                <FormItem prop="name" :error="category_error.name" label="Product category Name">
                     <Input v-model="category_form.name" />
                </FormItem>
				<FormItem prop="image" :error="category_error.image"  label="Image">
					<div class="" v-if="category_form.image">
						<template v-if="category_form.image" >
							<img :src="category_form.image">
							<div class="newDelIcon" style="cursor:pointer">
								<Icon type="ios-trash-outline" @click.native="chandleRemove()"></Icon>
							</div>
						</template>
					</div>	
					 <div  class="row" v-else>
						 <div class="col-12 col-md-6 col-lg-6 _mar_b20">
						   <Button type="info"  @click="galaryModalOn">Upload From Galary</Button>
						 </div>
						  <div class="col-12 col-md-6 col-lg-6 _mar_b20">
							<Upload
							ref="upload"
							name="img"
							:show-upload-list="false"
							:format="['jpg','jpeg','png','webp']"
							:max-size="20480"
							:on-success="chandleSuccess"
                            :on-format-error="chandleFormatError"
                            :on-exceeded-size="chandleMaxSize"
                            :on-progress="chandleProgress"
							action="/usermodule/upload/images"
							> 
							<Button type="info" icon="ios-cloud-upload-outline">Upload files</Button>
						    </Upload>
						  </div>
					 </div>
                </FormItem>
            </Form>
            <div slot="footer">
                <Button   @click="categoryModal=false" >Cancel</Button>
                <Button type="primary"  @click="createProductCategory" :loading="isLoading" :disabled="isLoading">{{isLoading? 'Please wait' : 'Create'}}</Button>
            </div>
        </Modal>
        <Modal title="Create sub category"  v-model="subcategoryModal" :mask-closable="false">
            <Form  :model="subcategory_form" label-position="top" >
                <FormItem  label="Category name" >
					<Select v-model="subcategory_form.product_category_id" filterable disabled>
                        <Option v-for="(item, index) in product_category" :value="item.id" :key="index">{{item.name}}</Option>
                    </Select>
                </FormItem>
                <FormItem prop="name" :error="subcategory_error.name"    label="Sub category name">
                     <Input  v-model="subcategory_form.name" />
                </FormItem>
            
				<FormItem prop="image" :error="subcategory_error.image"  label="Image">
					<div class="" v-if="subcategory_form.image">
						<template v-if="subcategory_form.image" >
							<img :src="subcategory_form.image">
							<div class="newDelIcon" style="cursor:pointer">
								<Icon type="ios-trash-outline" @click.native="subhandleRemove()"></Icon>
							</div>
						</template>
					</div>	
					 <div  class="row" v-else>
						 <div class="col-12 col-md-6 col-lg-6 _mar_b20">
						   <Button type="info"  @click="galaryModalOn">Upload From Galary</Button>
						 </div>
						  <div class="col-12 col-md-6 col-lg-6 _mar_b20">
							<Upload
							ref="upload"
							name="img"
							:show-upload-list="false"
							:format="['jpg','jpeg','png','webp']"
							:max-size="20480"
							:on-success="subhandleSuccess"
                            :on-format-error="subhandleFormatError"
                            :on-exceeded-size="subhandleMaxSize"
                            :on-progress="subhandleProgress"
							action="/usermodule/upload/images"
							> 
							<Button type="info" icon="ios-cloud-upload-outline">Upload files</Button>
						    </Upload>
						  </div>
					 </div>
                </FormItem>
            </Form>

            <div slot="footer">
                <Button   @click="subcategoryModal=false" >Cancel</Button>
                <Button type="primary"  @click="createSubCategory" :loading="isLoading" :disabled="isLoading">{{isLoading? 'Please wait' : 'Create'}}</Button>
            </div>
        </Modal>
        <Modal title="Image"  v-model="imageModal" :mask-closable="false">
            <Form  label-position="top" >
                <FormItem prop="image"  >
                     <img :src="viewImge">
                </FormItem>
            </Form>
            <div slot="footer">
                <Button   @click="imageModal=false" >close</Button>
            </div>
        </Modal>
      
    </div>
</template>
<script>
import { mapGetters } from 'vuex';

export default {
     computed: {
    ...mapGetters({
          getGalaryImages:'getGalaryImages',
        }),
        
    },
    data(){
        return{
            form:{
                name:'',
                image:'',
                description:'',
                category_id:'',
                product_category_id:'',
                franchise_id:'',
                product_subcategory_id:-1,
                restaurant_category_id:-1,
                partner_shop_id:'',
                percentage:0,
                discount:0,
                warranty:'',
                band_id:'',
                isFeatured:0,
                must_prescribed:1
            },
            error:{
                name:'',
                image:'',
                description:'',
                product_category_id:'',
                product_subcategory_id:'',
                franchise_id:'',
                partner_shop_id:'',
                band_id:'',
                isFeatured:''
            },
			isLoading:false,
            categories:[],
            product_tags:[],
            tags:[],
            product_category:[],
            allSubCategories:[],
            allBrand:[],
            partnerShops:[],
            restaurant_category:[],
            uploadlist : [],
            category_form:{
				name:'',
				image:'',
				category_id:-1
			},
           	category_error:{
				name:'',
				image:'',
				category:''
            },
            subcategory_form:{
				name:'',
				product_category_id:'',
				image:'',
			},
           	subcategory_error:{
				name:'',
				product_category_id:'',
				image:'',
            },
			categoryModal:false,
			subcategoryModal:false,
            restaurantValue:'',
			imageModal:false,
			isShopOwner:false,
            viewImge:'',
            isDiscount:1,
            imageProgress:{}
            // shopId:'',
            // isUploadError:'',
            // uploadErrorText : "",
            // uploadItem : [],
        }
	},
	methods:{
         galaryModalOn(){
			this.$store.commit('setGalaryModal', true);
		},
        openCategoryModal(){
            this.category_form={
				name:'',
				image:'',
				category_id:this.form.category_id
			}
            this.categoryModal = true
        },
        openSubCategoryModal(){
            this.subcategory_form={
				name:'',
				product_category_id:this.form.product_category_id,
				image:'',
			}
            this.subcategoryModal = true
        },
        async createProductCategory(){
			this.clearCategoryData(); 

            let flag =1
			if(!this.category_form.name || this.category_form.name ==null || this.category_form.name.trim()==''){
				this.category_error.name = "This field is required!"
				flag =0
			}
			if(!this.category_form.image || this.category_form.image ==null || this.category_form.image.trim()==''){
				this.category_error.image = "This field is required!"
				flag =0
			}

			if(flag == 0) return 
			this.isLoading = true
 			const res = await this.callApi('post', '/productmodule/storeProductCategory', this.category_form)
			if(res.status == 200 || res.status == 201){
				this.product_category.unshift(res.data)
                this.s("New Category has been created")
               
			}
			else if(res.status==422){
				this.e(res.data.message)
			}
			else {
				this.swr()
			}
			this.categoryModal = false;
			this.isLoading = false
		},
        async createSubCategory(item,index){
			this.clearSubCategoryErrorData()
			let flag =1
			if(!this.subcategory_form.name || this.subcategory_form.name ==null || this.subcategory_form.name.trim()==''){
				this.subcategory_error.name = "This field is required!"
				flag =0
			}
			if(!this.subcategory_form.image || this.subcategory_form.image ==null || this.subcategory_form.image.trim()==''){
				this.subcategory_error.image = "This field is required!"
				flag =0
			}
            if(flag == 0) return 
			this.isLoading = true
			const res = await this.callApi('post', '/productmodule/storeProductSubCategory', this.subcategory_form)
			if(res.status == 200 || res.status ==201){
				this.allSubCategories.unshift(res.data)
                this.s("New Sub Category has been created")
                this.subcategoryModal = false
			}
			else if(res.status==422){
				this.e(res.data.message)
			}
			else {
				this.swr()
			}

			this.subcategoryModal = false;
			this.isLoading = false
		},
   
        handleView( ){
           this.viewImge = this.form.image
           this.imageModal = true
        },
        async assingValues(){
            let i =1
            this.form.franchise_id =''
            for(let shop of this.partnerShops){
                if(this.form.partner_shop_id==shop.id){
                    this.form.franchise_id = shop.franchise_id
                    break
                }
            }
            if(!this.form.franchise_id || this.form.franchise_id=='') this.form.partner_shop_id =''
        },
        async getPernerShops(e=''){
            const res = await this.callApi('get', `/franchisemodule/getAllPertnerShopsbyCategory/${this.form.category_id}?str=${e}&franchise_id=${this.franchise_id}`)
            if(res.status==200){
                this.partnerShops = res.data.data
            } 
        },
        async getAllRetaurant(e=''){
            const res1 = await this.callApi('get', `/productmodule/getAllRestaurantCategory/${this.form.partner_shop_id}?str=${e}`)
            if(res1.status==200){
                this.restaurant_category = res1.data.data
            }  
        },
        async getProductBand(e=''){ 
            const res = await this.callApi('get', `/productmodule/getAllProductBand?str=${e}`)
            if(res.status==200){
                this.allBrand = res.data.data
            } 
        }, 
        async getAllProductCategory(e=''){
            this.form.product_category_id = null
            Vue.set(this.form, 'product_subcategory_id', null)
            this.form.product_subcategory_id = null
            const res = await this.callApi('get', `/productmodule/getAllProductCategoryById/${this.form.category_id}`)
            if(res.status==200){
                this.product_category = res.data
            } 
        },
        async getOnlyProductCategory(e=''){
    
            const res = await this.callApi('get', `/productmodule/getAllProductCategoryById/${this.form.category_id}`)
            if(res.status==200){
                this.product_category = res.data
            } 
        },
        async getAllProductCategoryOnChange(e=''){
            this.form.partner_shop_id = null
            this.productc_category = []
            this.allSubCategories = []
            this.partnerShops = []
            this.getAllProductCategory('')
        },
        async getTagsByCategory(){
            const res = await this.callApi('get', `/productmodule/getTagsByCategory/${this.franchise_id}/${this.form.category_id}`)
            if(res.status==200){
                this.tags = res.data
            } 
        },
        async getAllCategory(){
            const res = await this.callApi('get', `/productmodule/getAllCategory`)
            if(res.status==200){
                this.categories = res.data.data.data
            } 
        },
        async getAllBrand(){
            const res = await this.callApi('get', `/productmodule/getAllBrand`)
            if(res.status==200){
                this.allBrand = res.data
            } 
        },
        async getAllProductSubCategory(e=''){
            this.form.product_subcategory_id = null
            if(!this.form.product_category_id) return;
            const res = await this.callApi('get', `/productmodule/getAllProductSubCategoryById/${this.form.product_category_id}`)
            if(res.status==200){
                this.allSubCategories = res.data 
            } 
        },
        async getOnlyProductSubCategory(e=''){
            
            const res = await this.callApi('get', `/productmodule/getAllProductSubCategoryById/${this.form.product_category_id}`)
            if(res.status==200){
                this.allSubCategories = res.data 
            } 
        },
        async getAllProductSubCategoryOnChange(){
            this.getAllProductSubCategory('')
        },
		clearCategoryData(){
			this.category_error ={
				name:'',
				image:'',
				category:''
            }
        },
		clearSubCategoryErrorData(){
			this.subcategory_error ={
				name:'',
				product_category_id:'',
				image:'',
            }
        },
 
		validateData(s){
			let flag =1
			if(!this.form.name || this.form.name ==null || this.form.name.trim()==''){
				this.error.name = "This field is required!"
				flag =0
			}
			// if(!this.form.description || this.form.description==null || this.form.description.trim()==''){
			// 	this.error.description = "This field is required!"
			// 	flag =0
			// }
			if(!this.form.product_category_id || this.form.product_category_id=='' || this.form.product_category_id==null){
				this.error.product_category_id = "This field is required!"
				flag =0
			}
			if(!this.form.product_subcategory_id || this.form.product_subcategory_id=='' || this.form.product_subcategory_id==null){
				this.error.product_subcategory_id = "This field is required!"
				flag =0
			}
			if(!this.form.band_id || this.form.band_id=='' || this.form.band_id==null){
				this.error.band_id = "This field is required!"
				flag =0
			}
			if(!this.form.partner_shop_id || this.form.partner_shop_id=='' || this.form.partner_shop_id==null ){
				this.error.partner_shop_id = "This field is required!"
				flag =0
            }
            if(s==1){
                if(!this.uploadlist.length || this.uploadlist.length<1 ){
                    this.error.image = "This field is required!"
                    flag =0
			    }
            }
			
	
			return flag
		},
        handleRCategoryCreate(value){
            console.log(value);
            // if(this.form.category_id == '') return this.i("Please Select a Category!")
            // if(this.form.partner_shop_id == '') return this.i("Please Select a Shop!")
            let ob = {
                shop_id:this.form.partner_shop_id,
                category_name:value
            };
            this.restaurant_category.push(ob)
        },
	
		async createNewProduct(){                 
            if(!this.form.name || this.form.name ==null || this.form.name.trim()=='') return this.e("Product name is required.") 
            if(!this.form.category_id || this.form.category_id=='' || this.form.category_id==null) return this.e("Category name is required.") 
            if(!this.form.partner_shop_id || this.form.partner_shop_id=='' || this.form.partner_shop_id == null ) return this.e("Shop is required.") 
            if(this.form.category_id != 2){

                if(!this.form.product_category_id || this.form.product_category_id=='' || this.form.product_category_id==null) return this.e("Product category name is required.") 
                if(!this.form.product_subcategory_id || this.form.product_subcategory_id=='' || this.form.product_subcategory_id==null) return this.e("Product sub category name is required.") 
                if(!this.form.band_id || this.form.band_id=='' || this.form.band_id==null) return this.e("Brand name is required.") 
            }  
            if(this.form.category_id == 2){
                // if(!this.form.restaurant_category_id || this.form.restaurant_category_id ==null ) return this.e("Restaurant category is required.")
                if(this.restaurantValue == '') return this.e("Restaurant category is required.")
                this.form.restaurant_category = this.restaurant_category
                this.form.restaurantValue = this.restaurantValue
            }
            this.form.images = [];
             this.form.product_tags = []
			this.isLoading = true
			const res = await this.callApi('post', '/productmodule/updateProduct', this.form)
			if(res.status == 200 || res.status ==201){
                this.s("New product has been Updated.")
                this.$router.push(`/productmodule/product?shopId=${form.partner_shop_id}`)
                
			}
			else if(res.status==422){
				this.e(res.data.message)
			}
			else {
				this.swr()
			}
			
			this.isLoading = false
		},

        handleRemove() {
            this.form.image = ''
            // this.uploadlist.splice(i,1) 
        },
        handleSuccess(res, file) {
            //  console.log(res, file, "hello")
            //  this.uploadItem = res
            //  this.uploadlist.unshift(res)
             this.form.image = res.image
        },
        handleFormatError(file) {
            this.isUploadError = true;
            this.uploadErrorText = "Supported files types: .JPG .PNG .JPEG ";
        },
        handleMaxSize(file) {
            if (file) {
                this.$Notice.warning({
                title: "Exceeding file size limit",
                desc: "File  " + file.name + " is too large, no more than 20Mb.",
                });
            }
        },
        handleProgress(event, file, fileList) {
            this.isUploadError = false;
            this.uploadErrorText = "";
        },


        chandleRemove(i) {
            this.category_form.image = ''; 
        },
        chandleSuccess(res, file) {
            
            
             this.category_form.image = res.image
        },
        chandleFormatError(file) {
            this.isUploadError = true;
            this.uploadErrorText = "Supported files types: .JPG .PNG .JPEG ";
        },
        chandleMaxSize(file) {
            if (file) {
                this.$Notice.warning({
                title: "Exceeding file size limit",
                desc: "File  " + file.name + " is too large, no more than 20Mb.",
                });
            }
        },
        chandleProgress(event, file, fileList) {
            this.isUploadError = false;
            this.uploadErrorText = "";
        },



        subhandleRemove(i) {
            this.subcategory_form.image = ''; 
        },
        subhandleSuccess(res, file) {
            
            
             this.subcategory_form.image = res.image
        },
        subhandleFormatError(file) {
            this.isUploadError = true;
            this.uploadErrorText = "Supported files types: .JPG .PNG .JPEG ";
        },
        subhandleMaxSize(file) {
            if (file) {
                this.$Notice.warning({
                title: "Exceeding file size limit",
                desc: "File  " + file.name + " is too large, no more than 20Mb.",
                });
            }
        },
        subhandleProgress(event, file, fileList) {
            this.isUploadError = false;
            this.uploadErrorText = "";
        },
        handleTagCreate(value){
            if(this.form.category_id == '') return this.i("Please Select a Category!")
            let ob = {
                franchise_id:this.franchise_id,
                category_id:this.form.category_id,
                name:value
            };
            this.tags.push(ob)
        },
        onShopSelect(value){
            // console.log('value');
            // console.log(value);
            this.getAllRetaurant();
        },
    },
    watch: {
		"$store.state.images": async function (newValu) {
            if(newValu=='' ||  newValu.length==0) return
            if(this.categoryModal == true){
                this.category_form.image = newValu[0].image
            }else if(this.subcategoryModal == true){
                this.subcategory_form.image = newValu[0].image
                // console.log(this.subcategory_form.image)
            }else{
                this.form.image = newValu[0].image
                // for(let it of newValu) {
                //     this.uploadlist.push({image:it.image,product_id:this.$route.params.id})
                // }
            }
            this.$store.commit('setGalaryImages', []);
 		}
    },
    async created(){
        this.$store.commit('setGalaryImages', []);
        let res = await this.callApi('get',`/productmodule/productDetails/${this.$route.params.id}`)
        if(res.status == 200){
            delete res.data.category
            if(res.data.productc_category) this.product_category.push(res.data.productc_category)
            delete res.data.productc_category
            if(res.data.sub_category) this.allSubCategories.push(res.data.sub_category)
            delete res.data.sub_category
            delete res.data.brand
            delete res.data.franchise
            delete res.data.created_at
            delete res.data.updated_at
            this.partnerShops.push(res.data.pshop)
            // this.uploadlist = res.data.photo
            delete res.data.pshop
            delete res.data.photo
            this.form = res.data
            if(this.form.discount > 0) this.isDiscount = 0
            else this.isDiscount = 1
            // this.form.isFeatured = (this.form.isFeatured ==1) ? true : false 
            // this.form.must_prescribed = (this.form.must_prescribed ==1) ? true : false 
            for(let d of this.form.tags){
                this.product_tags.push(d.name)
            }
            if(res.data.category_id == 2){
                this.restaurant_category = [];
                this.restaurant_category.push(res.data.restaurant_category)
                this.restaurantValue = res.data.restaurant_category.category_name
            }
            for(let d of this.form.tags){
                this.product_tags.push(d.name)
            }
            delete this.form.tags
            delete this.form.restaurant_category
        }
        if(this.authInfo.userType == 'ShopOwner'){
            this.isShopOwner = true;
            // const res = await this.callApi('get', `/franchisemodule/getAllPertnerShops`)
            // if(res.status==200){
            //     this.partnerShops = res.data.data
            //     this.form.category_id = res.data.data[0].category_id
            //     this.form.partner_shop_id = res.data.data[0].id
            //     this.getAllProductCategory('')
            // }
        }
        this.getOnlyProductCategory('')
        this.getAllCategory()
        this.getOnlyProductSubCategory('')
        this.getAllBrand()
        this.getTagsByCategory()
        this.getAllRetaurant();
    }
}
</script>
<style>
    .demo-upload-list{
        display: inline-block;
        width: 60px;
        height: 60px;
        text-align: center;
        line-height: 60px;
        border: 1px solid transparent;
        border-radius: 4px;
        overflow: hidden;
        background: #fff;
        position: relative;
        box-shadow: 0 1px 1px rgba(0,0,0,.2);
        margin-right: 4px;
    }
    .demo-upload-list img{
        width: 100%;
        height: 100%;
    }
    .demo-upload-list-cover{
        display: none;
        position: absolute;
        top: 0;
        bottom: 0;
        left: 0;
        right: 0;
        background: rgba(0,0,0,.6);
    }
    .demo-upload-list:hover .demo-upload-list-cover{
        display: block;
    }
    .demo-upload-list-cover i{
        color: #fff;
        font-size: 20px;
        cursor: pointer;
        margin: 0 2px;
    }
</style>