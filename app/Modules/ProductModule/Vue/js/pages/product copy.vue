<template>
    <div class="_main_content">
		<div class="row">
            <div class="col-12 col-md-12 col-lg-12">
				<div class="_box _b_radious3 _padd20">
                    <div class="_1card_top _mar_b20">
							<div class="_1card_top_left">
								<p class="_1card_top_title">Product List</p>

								<div class="_1card_top_search">
									<Input @on-enter="serchResetlt" v-model="str" search enter-button="Search" placeholder="Enter something..." />
								</div>
							</div>

							<div class="_1card_top_right">
								<ul class="_1card_top_right_list">
									<li><Button type="primary" @click="openCreateModal">Add</Button></li>
								</ul>
							</div>
						</div>
						<div class="_table_responsive">
							<Table class="_table1700" border :columns="columns" :data="alldata.data">
								
								<template slot="shop" slot-scope="{ row }">
									<p>{{row.name}}</p>
								</template>
								<template slot="category" slot-scope="{ row }">
									<p v-if="row.category && row.category.name">{{row.category.name}}</p>
								</template>
								
								<template slot="subCategory" slot-scope="{ row }">
									<p v-if="row.sub_category && row.sub_category.name">{{row.sub_category.name}}</p>
								</template>
								<template slot="brand" slot-scope="{ row }">
									<p v-if="row.brand && row.brand.name">{{row.brand.name}}</p>
								</template>
								
								<template slot="pshop" slot-scope="{ row }">
									<p v-if="row.pshop && row.pshop.name">{{row.pshop.name}}</p>
								</template>
								<template slot="franchise" slot-scope="{ row }">
									<p v-if="row.franchise && row.franchise.name">{{row.franchise.name}}</p>
								</template>
						
								
                                <template slot="descritpion" slot-scope="{ row }">
									<Button @click="showDescriptions(row.description)">Show</Button>
								</template>
								<template slot="action" slot-scope="{ row, index }">
									<Button type="primary" @click="openEditModal(row,index)">Edit</Button>
									<Button type="primary" @click="$router.push('/productmodule/product-images/'+row.id)">Images</Button>
									<Button type="primary" @click="$router.push('/productmodule/product-variation/'+row.id)">Variations</Button>
									<Button type="error" @click="deleteCategory(row,index)">Delete</Button>
								</template>
							</Table>
						</div>

                        <div class="_pagination _padd_t20" v-if="alldata && alldata.total!=null">
							<Page :total="alldata.total" show-sizer :page-size-opts="pageOption" @on-page-size-change="getSizeChange"  @on-change="paginateDataInfo" />
						</div>
                </div>
            </div>
        </div>
		<Modal title="Update product"  v-model="updateModal" :mask-closable="false">
             <Form  :model="form" label-position="top" >
                <FormItem prop="name" :error="error.name"    label="Product name">
                     <Input v-model="form.name" />
                </FormItem>
                <FormItem prop="name" :error="error.description"    label="Product description">
                     <Input type="textarea" :autosize="{minRows: 2,maxRows: 5}" v-model="form.description" />
                </FormItem>
                 <FormItem :error="error.product_category_id"    label="Category name">
                      <Select
                            v-model="form.product_category_id"
                            filterable
                            :remote-method="getAllProductCategory"
                        >
                          <Option v-for="(item, index) in categories" :value="item.id" :key="index" :label="item.name">{{item.name}}</Option>
                    </Select>
                </FormItem>
                 <FormItem v-if="form.product_category_id!=null" :error="error.product_subcategory_id"    label="Sub category name">
                      <Select
                            v-model="form.product_subcategory_id"
                            filterable
                            :remote-method="getAllProductSubCategory"
                        >
                          <Option v-for="(option1, index) in allSubCategories" :value="option1.id" :key="index" >{{option1.name}}</Option>
                    </Select>
                </FormItem>
                 <FormItem prop="band_id" :error="error.band_id"    label="Brand name">
                      <Select
                            v-model="form.band_id"
                            filterable
                            :remote-method="getProductBand"
                        >
                          <Option v-for="(option, index) in allBand" :value="option.id" :key="index" >{{option.name}}</Option>
                    </Select>
                </FormItem>
                 <FormItem prop="partner_shop_id" :error="error.partner_shop_id"    label="Partner Shop name">
                      <Select
                            v-model="form.partner_shop_id"
                            filterable
                            @on-select="assingValues"
                            :remote-method="getPernerShops"
                        >
                          <Option v-for="(option, index) in partnerShops" :value="option.id" :key="index" >{{option.name}}</Option>
                    </Select>
                </FormItem>
                 <FormItem prop="isFeatured" :error="error.isFeatured"    label="Featured product">
                    <Radio v-model="form.isFeatured">Featured</Radio>
                </FormItem>
                 
                        <FormItem prop="uploadlist"  label="Image" :error="error.image" >

                            <div class="demo-upload-list" v-for="(item, i) in uploadlist" :key="i">
                                <template v-if="item.image">
                                    <img :src="item.image">
                                    <div class="demo-upload-list-cover">
                                        <Icon type="ios-eye-outline" @click.native="handleView(item.image)"></Icon>
                                        <Icon type="ios-trash-outline" @click.native="handleRemove(i)"></Icon>
                                    </div>
                                </template>
                                <template v-else>
                                    <Progress v-if="item.showProgress" :percent="item.percentage" hide-info></Progress>
                                </template>
                             </div>
                            <Upload
                                ref="upload"
                                name="img"
                                :show-upload-list="false"
                                :format="['jpg','jpeg','png']"
                                :max-size="20480"
                                :on-success="handleSuccess"
                                :on-format-error="handleFormatError"
                                :on-exceeded-size="handleMaxSize"
                                :before-upload="handleBeforeUpload"
                                :on-progress="handleProgress"
                                multiple
                                type="drag"
                                action="/usermodule/upload/images"
                                style="width: 100% !important"
                                >
                                <div style="padding: 20px 0">
                                    <Icon type="ios-cloud-upload" size="52" style="color: #3399ff"></Icon>
                                    <p>Click or drag files here to upload</p>
                                </div>
                                <!-- <i class="fas fa-images"></i> attachement -->
                            </Upload>
                        </FormItem>
            </Form>
            <div slot="footer">
                <Button   @click="updateModal=false" >Cancel</Button>
                <Button type="primary"  @click="updateData" :loading="isLoading" :disabled="isLoading">{{isLoading? 'Please wait' : 'Update'}}</Button>
            </div>
        </Modal>
		<Modal title="Create product"  v-model="createModal" :mask-closable="false">
            <Form  :model="form" label-position="top" >
                <FormItem prop="name" :error="error.name"    label="Product name">
                     <Input v-model="form.name" />
                </FormItem>
                <FormItem prop="name" :error="error.description"    label="Product description">
                     <Input type="textarea" :autosize="{minRows: 2,maxRows: 5}" v-model="form.description" />
                </FormItem>
                 <FormItem :error="error.product_category_id"    label="Category name">
                      <Select
                            v-model="form.product_category_id"
                            filterable
                            :remote-method="getAllProductCategory"
                        >
                          <Option v-for="(item, index) in categories" :value="item.id" :key="index" :label="item.name">{{item.name}}</Option>
                    </Select>
                </FormItem>
                 <FormItem v-if="form.product_category_id!=null" :error="error.product_subcategory_id"    label="Sub category name">
                      <Select
                            v-model="form.product_subcategory_id"
                            filterable
                            :remote-method="getAllProductSubCategory"
                        >
                          <Option v-for="(option1, index) in allSubCategories" :value="option1.id" :key="index" >{{option1.name}}</Option>
                    </Select>
                </FormItem>
                 <FormItem prop="band_id" :error="error.band_id"    label="Brand name">
                      <Select
                            v-model="form.band_id"
                            filterable
                            :remote-method="getProductBand"
                        >
                          <Option v-for="(option, index) in allBand" :value="option.id" :key="index" >{{option.name}}</Option>
                    </Select>
                </FormItem>
                 <FormItem prop="partner_shop_id" :error="error.partner_shop_id"    label="Partner Shop name">
                      <Select
                            v-model="form.partner_shop_id"
                            filterable
                            @on-select="assingValues"
                            :remote-method="getPernerShops"
                        >
                          <Option v-for="(option, index) in partnerShops" :value="option.id" :key="index" >{{option.name}}</Option>
                    </Select>
                </FormItem>
                 <FormItem prop="isFeatured" :error="error.isFeatured"    label="Featured product">
                    <Radio v-model="form.isFeatured">Featured</Radio>
                </FormItem>
                 
                        <FormItem prop="uploadlist"  label="Image" :error="error.image" >

                            <div class="demo-upload-list" v-for="(item, i) in uploadlist" :key="i">
                                <template v-if="item.image">
                                    <img :src="item.image">
                                    <div class="demo-upload-list-cover">
                                        <Icon type="ios-eye-outline" @click.native="handleView(item.image)"></Icon>
                                        <Icon type="ios-trash-outline" @click.native="handleRemove(i)"></Icon>
                                    </div>
                                </template>
                                <template v-else>
                                    <Progress v-if="item.showProgress" :percent="item.percentage" hide-info></Progress>
                                </template>
                             </div>
                            <Upload
                                ref="upload"
                                name="img"
                                :show-upload-list="false"
                                :format="['jpg','jpeg','png']"
                                :max-size="20480"
                                :on-success="handleSuccess"
                                :on-format-error="handleFormatError"
                                :on-exceeded-size="handleMaxSize"
                                :before-upload="handleBeforeUpload"
                                :on-progress="handleProgress"
                                multiple
                                type="drag"
                                action="/usermodule/upload/images"
                                style="width: 100% !important"
                                >
                                <div style="padding: 20px 0">
                                    <Icon type="ios-cloud-upload" size="52" style="color: #3399ff"></Icon>
                                    <p>Click or drag files here to upload</p>
                                </div>
                                <!-- <i class="fas fa-images"></i> attachement -->
                            </Upload>
                        </FormItem>
            </Form>

            <div slot="footer">
                <Button   @click="createModal=false" >Cancel</Button>
                <Button type="primary"  @click="createNewProduct" :loading="isLoading" :disabled="isLoading">{{isLoading? 'Please wait' : 'Create'}}</Button>
            </div>
        </Modal>
        <Modal title="Product Details"  v-model="descriptionModal" :mask-closable="false">
            <Form  :model="form" label-position="top" >
                <p>{{description}}</p>
            </Form>
            <div slot="footer">
                <Button   @click="descriptionModal=false" >close</Button>
            </div>
        </Modal>
        <Modal title="Image"  v-model="imageModal" :mask-closable="false">
            <Form  label-position="top" >
                <FormItem prop="image"  >
                     <img :src="image">
                </FormItem>
            </Form>
            <div slot="footer">
                <Button   @click="imageModal=false" >close</Button>
            </div>
        </Modal>
    </div>
</template>


<script>
export default {
    data(){
        return{
			 form:{
                name:'',
                image:'',
                description:'',
                product_category_id:'',
                product_subcategory_id:'',
                franchise_id:'',
                partner_shop_id:'',
                band_id:'',
                isFeatured:false,
             },
			 editData:{
                name:'',
                image:'',
                description:'',
                product_category_id:'',
                product_subcategory_id:'',
                franchise_id:'',
                partner_shop_id:'',
                band_id:'',
                isFeatured:false,
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
			updateModal:false,
			isLoading:false,
			createModal:false,
			editIndex:-1,
			_rowKey:-1,
            pageOption:[5,10,15,20],
            columns: [
				 {
                    title: 'Product id',
                    sortable: true,
                    key: 'id',
                    width:150
                },
				{
					title: 'Name',
                    key: 'name',
                    width:150
				},
				{
					title: 'Descritpion',
                    slot: 'descritpion',
                    width:150
				},
				{
					title: 'Category Name',
					slot: 'category',
                    width:150
                },
				{
					title: 'Sub Category Name',
					slot: 'subCategory',
                    width:250
                },
				{
					title: 'Shop Name',
					slot: 'shop',
                    width:150
                },
				{
					title: 'Franchise Name',
					slot: 'franchise',
                    width:150
                },
				{
					title: 'Brand Name',
					slot: 'brand',
                    width:150
                },
				{
					title: 'Action',
					slot: 'action',
					width: 350
				}
			],
            alldata:{
                total:0,
                data:[]
            },
            categories:[],
            allSubCategories:[],
            allBand:[],
            partnerShops:[],
            str2:'',
            description:'',
            image:'',
            imageModal:false,
            descriptionModal:false,
            shopId:'',
            isUploadError:'',
            uploadErrorText : "",
            uploadlist : [],
            uploadItem : [],
            page:1,
			pageSize:10,
			str:''
        }
	},
	methods:{
        serchResetlt(){
			this.pageSize = 10
			this.paginateDataInfo(1)
		},
		getSizeChange(e){
			this.pageSize =e
			this.paginateDataInfo(1)
		},
		paginateDataInfo(e){
			   this.page = e
			   this.getAllProductList()
        },
        async handleView(item){
           this.image = item
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
        async showDescriptions(description){
            this.description = description
            this.descriptionModal = true
        },
        async getProductBand(e=''){
            const res = await this.callApi('get', `/productmodule/getAllProductBand?str=${e}`)
            if(res.status==200){
                this.allBand = res.data.data
            } 
        },
        async getPernerShops(e=''){
            const res = await this.callApi('get', `/franchisemodule/getAllPertnerShops?str=${e}`)
            if(res.status==200){
                this.partnerShops = res.data.data
            } 
        },
        async getProductBand(e=''){
            const res = await this.callApi('get', `/productmodule/getAllProductBand?str=${e}`)
            if(res.status==200){
                this.allBand = res.data.data
            } 
        },
        async getAllProductCategory(e=''){
            const res = await this.callApi('get', `/productmodule/getAllProductCategory?str=${e}`)
            if(res.status==200){
                this.categories = res.data.data.data
            } 
        },
        async getAllProductSubCategory(e){
            if(!this.form.product_category_id || this.form.product_category_id=='') 
                return this.e("please select a category")
            const res = await this.callApi('get', `/productmodule/getAllProductSubCategory?str=${e}&id=${this.form.product_category_id}`)
            if(res.status==200){
                this.allSubCategories = res.data.data.data
            } 
        },

        // new 
        async getAllProduct(e=''){
            const res = await this.callApi('get', `/productmodule/getAllProductSubCategory?id=${id}`)
            if(res.status==200){
                this.alldata = res.data.data
                this.alldata.total = res.data.total
            } 
        },
		clearErrorData(){
			this.error= {
				name:'',
                image:'',
                description:'',
                product_category_id:'',
                product_subcategory_id:'',
                franchise_id:'',
                partner_shop_id:'',
                band_id:'',
                isFeatured:''
			}
        },
        
		clearData(){
	
			this.form={}
        },

        
		validateData(){
			let flag =1
			if(!this.form.name || this.form.name ==null || this.form.name.trim()==''){
				this.error.name = "This field is required!"
				flag =0
			}
			if(!this.form.description || this.form.description==null || this.form.description.trim()==''){
				this.error.description = "This field is required!"
				flag =0
			}
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
			if(!this.uploadlist.length || this.uploadlist.length<1 ){
				this.error.image = "This field is required!"
                flag =0
			}
	
			return flag
		},
		async createNewProduct(){
            this.clearErrorData()
            console.log(this.form)
            if(!this.validateData()) return 
            this.form.images = this.uploadlist
			this.isLoading = true
			const res = await this.callApi('post', '/productmodule/storeProduct', this.form)
			if(res.status == 200 || res.status ==201){
				this.alldata.data.unshift(res.data.data)
                this.s("New Product has been created")
                this.uploadlist =[]
                this.createModal = false
			}
			else if(res.status==422){
				this.e(res.data.message)
			}
			else {
				this.swr()
			}

			this.updateModal = false;
			this.isLoading = false
		},
		async updateData(item,index){
			if(!this.validateData()) return 
			this.isLoading = true
			const res = await this.callApi('post', '/productmodule/updateProductSubCategory', this.form)
			if(res.status == 200){
				this.form._index = this.editIndex
				this.form._rowKey =this._rowKey
                Vue.set(this.alldata.data,this.editIndex,this.form)
                this.s("Product Category has been updated!")
                 this.updateModal = false
                 this.isLoading = false
			}
			else if(res.status==422){
				this.e(res.data.message)
			}
			else {
				this.swr()
			}
			this.updateModal = false;
			this.isLoading = false
		},
		openEditModal(item,i){
            this.clearData()
            
			this.editIndex = i
			// this._rowKey = item._rowKey
			// delete item._rowKey
			// delete item._index
			
            console.log(item)
			this.form = _.cloneDeep(item)
             this.categories=[]
            this.allSubCategories=[]
            this.allBand=[]
            this.partnerShops=[]
            this.categories.push(this.form.category)
            this.allSubCategories.push(this.form.sub_category)
            this.allBand.push(this.form.brand)
            this.partnerShops.push(this.form.pshop)

			this.updateModal = true;
		},
		openCreateModal(){
			this.clearData()
			this.createModal = true
		},
		async deleteCategory(item,index){
             if (!confirm("are you sure to delete?")) {
                    return;
                }
			const res = await this.callApi('post', '/productmodule/deleteProductSubCategory', {id:item.id})
            if(res.status == 200){
                this.alldata.data.splice(index, 1)
                this.s("Sub category has been deleted")
            }
           else if(res.status==422){
				this.e(res.data.message)
			}
			else {
				this.swr()
			}
        },
        // upload methods:
         handleRemove(i) {
            this.uploadlist.splice(i,1) 
        },
         handleSuccess(res, file) {
             console.log(res, file, "hello")
             this.uploadItem = res
             this.uploadlist.unshift(res)
             this.form.image = res.image
            //  console.log(this.form.image)
            //  this.form.url=res
            // this.singleProject.files.push(res.id);
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
            // this.uploadlist = fileList;
        },
        handleBeforeUpload() {
            // const check = this.uploadlist.length < 1;
            // if (!check) {
            //     this.$Notice.warning({
            //     title: "Up to 1 files can be uploaded.",
            //     });
            // }
            // return check;
        },
        async getAllProductList(){
        if(this.$route.query && this.$route.query.shopId){
            this.shopId = this.$route.query.shopId
            console.log(this.shopId)
		}
      
		const res = await this.callApi('get', `/productmodule/getAllProduct?shopId=${this.shopId}&page=${this.page}&pageSize=${this.pageSize}&str=${this.str}`)
		if(res.status==200){
			this.alldata = res.data
			this.alldata.total = res.data.total
		} 
        }
       
	},
    async created(){
            this.getAllProductList()
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