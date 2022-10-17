<template>
    <div class="_main_content">
		<div class="row">
            <div class="col-12 col-md-12 col-lg-12">
				<div class="_box _b_radious3 _padd20">
                    <div class="_1card_top _mar_b20">
							<div class="_1card_top_left">
								<p class="_1card_top_title">Category list</p>

								<div class="_1card_top_search">
									<Input  @on-enter="serchResetlt" v-model="str" search enter-button="Search" placeholder="Enter something..." />
								</div>
							</div>

							<div class="_1card_top_right">
								<ul class="_1card_top_right_list">
									<li><Button type="primary" @click="openCreateModal">Add</Button></li>
								</ul>
							</div>
						</div>
						<div class="_table_responsive">
							<Table class="_table700" border :columns="columns" :data="alldata.data">
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
									<Button type="primary" @click="$router.push('/productmodule/product-sub-category/'+row.id)">sub categories</Button>
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
		<Modal title="Update Category"  v-model="updateModal" :mask-closable="false">
            <Form  :model="form" label-position="top" >
                <FormItem prop="name" :error="error.name"    label="Category name">
                     <Input v-model="form.name" />
                </FormItem>
           <FormItem prop="image" :error="error.image"  label="Image">
					
						<div class="" v-if="form.image">
							<template >
								<img :src="form.image">
								<div class="">
									<Icon type="ios-trash-outline" @click.native="handleRemove()"></Icon>
								</div>
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
                            </Upload>
                </FormItem>
            </Form>

            <div slot="footer">
                <Button   @click="updateModal=false" >Cancel</Button>
                <Button type="primary"  @click="updateData" :loading="isLoading" :disabled="isLoading">{{isLoading? 'Please wait' : 'Update'}}</Button>
            </div>
        </Modal>
		<Modal title="Create Category"  v-model="createModal" :mask-closable="false">
            <Form  :model="form" label-position="top" >
                <FormItem prop="name" :error="error.name"    label="Category Name">
                     <Input v-model="form.name" />
                </FormItem>
				      <FormItem prop="image" :error="error.image"  label="Image">
					
						<div class="" v-if="form.image">
							<template >
								<img :src="form.image">
								<div class="">
									<Icon type="ios-trash-outline" @click.native="handleRemove()"></Icon>
								</div>
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
                            </Upload>
                </FormItem>
				
            
            </Form>

            <div slot="footer">
                <Button   @click="createModal=false" >Cancel</Button>
                <Button type="primary"  @click="createUser" :loading="isLoading" :disabled="isLoading">{{isLoading? 'Please wait' : 'Create'}}</Button>
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
export default {
    data(){
        return{
			imageModal:false,
			singleImage:'',
			uploadErrorText:'',
			 form:{
				 name:'',
				image:''
			 },
           	 error:{
				name:'',
				image:''
            },
			updateModal:false,
			isLoading:false,
			createModal:false,
			editIndex:-1,
			_rowKey:-1,
            pageOption:[5,10,15,20],
            columns: [
				 {
                    title: 'Category id',
                    sortable: true,
                    key: 'id',
                },
				{
					title: 'Name',
					key: 'name'
				},
				{
					title: 'Image',
					slot: 'image',
					width: 200
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
			pageSize:10,
			page:1,
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
			   this.getShopingList()
        },
		clearData(){
			this.error= {
				name:'',
				image:'',
			},
			this.form={
				name:'',
				image:'',
			}
		},
		validateData(){
			let flag =1
			if(!this.form.name || this.form.name.trim()==''){
				this.error.name = "This field is required!"
				flag =0
			}
			if(this.form.image==null || this.form.image.trim()==''){
				this.error.image = "This field is required!"
				flag =0
			}
			return flag
		},
		clearErrorData(){
			this.error.name =''
			this.error.image =''
		},
		async createUser(item,index){
			this.clearErrorData();
			if(!this.validateData()) return 
			this.isLoading = true
			const res = await this.callApi('post', '/productmodule/storeProductCategory', this.form)
			if(res.status == 200 || res.status == 201){
				this.alldata.data.unshift(res.data)
                this.s("New Category has been created")
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
			if(!this.validateData()) return 
			this.isLoading = true
			const res = await this.callApi('post', '/productmodule/updateProductCategory', this.form)
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
			this.form = _.cloneDeep(item)
			this.updateModal = true;
		},
		openCreateModal(){
			this.clearData()
			this.createModal = true
		},
		async deleteCategory(item,index){
             if (!confirm("All the subcategories under this category will be deleted?")) {
                    return;
                }
			const res = await this.callApi('post', '/productmodule/deleteProductCategory', {id:item.id})
            if(res.status == 200){
                this.alldata.data.splice(index, 1)
                this.s("Category has been deleted")
            }
           else if(res.status==422){
				this.e(res.data.message)
			}
			else {
				this.swr()
			}
		},
		async getAllProductCategory(){
			const res = await this.callApi('get', `/productmodule/getAllProductCategory?page=${this.page}&pageSize=${this.pageSize}&str=${this.str}`)
			if(res.status==200){
				this.alldata = res.data.data
				this.alldata.total = res.data.total
			} 
		},
		handleView(image){
			this.singleImage = image
			this.imageModal = true
		},
		handleRemove() {
            	this.form.image =""
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
		
		async handleRemove(){
			this.form.image = ''
		},
	},
    async created(){
		this.getAllProductCategory()
    },
    
}
</script>
