<template>
    <div class="_main_content">
		<div class="row">
            <div class="col-12 col-md-12 col-lg-12">
				<div class="_box _b_radious3 _padd20">
                    <div class="_1card_top _mar_b20">
							<div class="_1card_top_left">
								<p class="_1card_top_title">Sub Category list</p>

								<div class="_1card_top_search">
									<!-- @on-change="serchResetlt"
									@on-search="serchResetlt"
									@on-keydown="serchResetlt"
									@on-keyup="serchResetlt" -->
									<Input  @on-change="serchResetlt" search enter-button="Search" v-model="str2" placeholder="Enter something..." />
								</div>
							</div>

							<div class="_1card_top_right">
								<ul class="_1card_top_right_list">
									<li><Button type="primary" @click="openCreateModal">Add</Button></li>
								</ul>
							</div>
						</div>
						<div class="_table_responsive">
							<Table class="_table1200" border :columns="columns" :data="alldata.data">
								<template slot="image" slot-scope="{ row }">
									<div>
										<div class="demo-upload-list">
													<img :src="row.image" alt="">
											<div class="demo-upload-list-cover">
												<Icon type="ios-eye-outline" @click="handleView(row.image)"></Icon>
											</div>
										</div>
									</div>
								</template>
								<template slot="action" slot-scope="{ row, index }">
									<Button type="primary" @click="openEditModal(row,index)">Edit</Button>
									<Button type="error" @click="deleteSubCategory(row,index)">Delete</Button>
								</template>
							</Table>
						</div>

                        <div class="_pagination _padd_t20" v-if="alldata && alldata.total!=null">
							<Page :total="alldata.total" show-sizer :page-size-opts="pageOption"  @on-page-size-change="getSizeChange"  @on-change="paginateDataInfo"   />
						</div>
                </div>
            </div>
        </div>
		<Modal title="Update sub category"  v-model="updateModal" :mask-closable="false">
            <Form  :model="form" label-position="top" >
                <FormItem prop="name" :error="error.name"    label="Sub category name">
                     <Input  v-model="form.name" />
                </FormItem>
                <FormItem prop="product_category_id" :error="error.product_category_id"    label="Category name">
                     <Input disabled v-if="category && category.name" v-model="category.name" />
                </FormItem>
				  <FormItem prop="image" :error="error.image"  label="Image">
					<div class="" v-if="form.image || getGalaryImages.length>0">
						<template v-if="form.image" >
							<img :src="form.image">
							<div class="newDelIcon" style="cursor:pointer">
								<Icon type="ios-trash-outline" @click.native="handleRemove()"></Icon>
							</div>
						</template>
						<template v-else-if="getGalaryImages[0]" >
							<img :src="getGalaryImages[0].image">
							<div style="cursor:pointer">
								<Icon type="ios-trash-outline" @click.native="handleRemove()"></Icon>
							</div>
						</template>
					</div>	
					 <div  class="row" v-else>
						 <div class="col-12 col-md-6 col-lg-6 _mar_b20">
						   <Button type="info"  @click="galaryModalOn">Upload From Gallery</Button>
						 </div>
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
							:before-upload="handleBeforeUpload"
							:on-progress="handleProgress"
							multiple
							action="/usermodule/upload/images"
							>
							<!-- <div style="padding: 20px 0">
								<Icon type="ios-cloud-upload" size="52" style="color: #3399ff"></Icon>
								<p>Click or drag files here to upload</p>
							</div> -->
							<Button type="info" icon="ios-cloud-upload-outline">Upload files</Button>
						 </Upload>
						  </div>
						 
					 </div>
					
                </FormItem>
            </Form>

            <div slot="footer">
                <Button   @click="updateModal=false" >Cancel</Button>
                <Button type="primary"  @click="updateData" :loading="isLoading" :disabled="isLoading">{{isLoading? 'Please wait' : 'Update'}}</Button>
            </div>
        </Modal>
		<Modal title="Create sub category"  v-model="createModal" :mask-closable="false">
            <Form  :model="form" label-position="top" >
                <FormItem prop="name" :error="error.name"    label="Sub category name">
                     <Input  v-model="form.name" />
                </FormItem>
                 <FormItem prop="product_category_id" label="Category name" >
					 <Input disabled v-if="category && category.name" v-model="category.name" />
                </FormItem>
				<FormItem prop="image" :error="error.image"  label="Image">
					<div class="" v-if="form.image || getGalaryImages.length>0">
						<template v-if="form.image" >
							<img :src="form.image">
							<div class="newDelIcon" style="cursor:pointer">
								<Icon type="ios-trash-outline" @click.native="handleRemove()"></Icon>
							</div>
						</template>
						<template v-else-if="getGalaryImages[0]" >
							<img :src="getGalaryImages[0].image">
							<div style="cursor:pointer">
								<Icon type="ios-trash-outline" @click.native="handleRemove()"></Icon>
							</div>
						</template>
					</div>	
					 <div  class="row" v-else>
						 <div class="col-12 col-md-6 col-lg-6 _mar_b20">
						   <Button type="info"  @click="galaryModalOn">Upload From Gallery</Button>
						 </div>
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
							:before-upload="handleBeforeUpload"
							:on-progress="handleProgress"
							multiple
							action="/usermodule/upload/images"
							>
							<!-- <div style="padding: 20px 0">
								<Icon type="ios-cloud-upload" size="52" style="color: #3399ff"></Icon>
								<p>Click or drag files here to upload</p>
							</div> -->
							<Button type="info" icon="ios-cloud-upload-outline">Upload files</Button>
						 </Upload>
						  </div>
						 
					 </div>
					
                </FormItem>
            </Form>

            <div slot="footer">
                <Button   @click="createModal=false" >Cancel</Button>
                <Button type="primary"  @click="createSubCategory" :loading="isLoading" :disabled="isLoading">{{isLoading? 'Please wait' : 'Create'}}</Button>
            </div>
        </Modal>
		<Modal title="Image"  v-model="imageModal" :mask-closable="false">
			<div>
				<img :src="singleImage" alt="">
			</div>
			 <div slot="footer">
                <Button   @click="imageModal=false" >Cancel</Button>
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
			imageModal:false,
			singleImage:'',
			uploadErrorText:'',
			 form:{
                name:'',
				product_category_id:'',
				image:'',
             },
           	 error:{
				name:'',
				product_category_id:'',
				image:'',
            },
			updateModal:false,
			isLoading:false,
			createModal:false,
			editModal:false,
			editIndex:-1,
			_rowKey:-1,
            pageOption:[5,10,15,20],
            columns: [
				 {
                    title: 'Sub category id',
                    key: 'id',
                },
				{
					title: 'Name',
					key: 'name'
				},
				{
					title: 'Image',
					slot: 'image'
				},
				{
					title: 'Action',
					slot: 'action',
					width: 300
				}
			],
            alldata:{
                total:0,
                data:[]
            },
            category:{},
			str2:'',
			id:'',
			page:1,
			pageSize:10
        }
	},
	methods:{
		galaryModalOn(){
			this.$store.commit('setGalaryModal', true);
		},
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
			   this.getAllSubCategoryCategory()
            },
		clearData(){
			this.error= {
				name:'',
				product_category_id:'',
				image:''
			}
			this.form={
				name:'',
				product_category_id:'',
				image:''
			}
		},
		validateData(){
			let flag =1
			if(!this.form.name || this.form.name.trim()==''){
				this.error.name = "This field is required!"
				flag =0
			}
			if(!this.form.image || this.form.image.trim()==''){
				this.error.image = "This field is required!"
				flag =0
			}
			this.form.product_category_id = this.id
			if(!this.form.product_category_id || this.form.product_category_id==null){
				this.error.product_category_id = "This field is required!"
				flag =0
			}
			return flag
		},
		clearErrorData(){
				this.error= {
					name:'',
					product_category_id:'',
					image:''
				}
		},
		async createSubCategory(item,index){
			this.clearErrorData()
			if(!this.form.image || this.form.image.trim()==''){
                if(this.getGalaryImages.length >0){
					this.form.image = this.getGalaryImages[0].image
					this.$store.commit('setGalaryImages', []);
				}
            }
			if(!this.validateData()) return 
			this.isLoading = true
			const res = await this.callApi('post', '/productmodule/storeProductSubCategory', this.form)
			if(res.status == 200 || res.status ==201){
				this.alldata.data.unshift(res.data)
                this.s("New Sub Category has been created")
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
			this.clearErrorData()
			if(!this.form.image || this.form.image.trim()==''){
                 
				if(this.getGalaryImages.length >0){
					this.form.image = this.getGalaryImages[0].image
					this.$store.commit('setGalaryImages', []);
				}
            }
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
		openCreateModal(){
			this.clearData()
			this.createModal = true
		},
		async openEditModal(rr,i){
			this.clearData()
			this.editIndex = i
			this.form = _.cloneDeep(rr)
			this.updateModal = true
		},
		async deleteSubCategory(item,index){
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
        async getAllCategory(e=''){
            const res = await this.callApi('get', `/productmodule/getAllProductCategoryById/${this.id}`)
            if(res.status==200){
                this.category = res.data
            } 
        },
        async getAllSubCategoryCategory(){
				const res = await this.callApi('get', `/productmodule/getAllProductSubCategory
				?id=${this.id}&page=${this.page}&pageSize=${this.pageSize}&str=${this.str2}`)
				if(res.status==200){
					this.alldata.data = res.data.data
					this.alldata.total = res.data.total

				} 
		},
		handleView(image){
			this.singleImage = image
			this.imageModal = true
		},
		handleRemove() {
				this.form.image =""
				this.$store.commit('setGalaryImages', []);
        },
		handleSuccess(res, file) {
			this.form.image = res.image
             console.log(res, file, "hello")
        },
         handleFormatError(file) {
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
            this.uploadErrorText = "";
        },
        handleBeforeUpload() {
		},
		 
	},
    async created(){
        this.$store.commit('setGalaryImages', []);
		this.id  = this.$route.params.id
		this.getAllSubCategoryCategory()
		this.getAllCategory()
    },
    mounted () {
          
    }
    
}
</script>
