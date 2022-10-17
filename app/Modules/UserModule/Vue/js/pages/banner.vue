<template>
    <div class="_main_content">
		<div class="row">
            <div class="col-12 col-md-12 col-lg-12">
				<div class="_box _b_radious3 _padd20">
                    <div class="_1card_top _mar_b20">
						<div class="_1card_top_left">
							<p class="_1card_top_title">Banners</p>

							<div class="_1card_top_search">
								<Input @on-change="serchResetlt" v-model="str" search enter-button="Search" placeholder="Enter something..." />
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
							<template slot="image" slot-scope="{row}">
								<div>
									<div class="demo-upload-list">
										<img :src="row.image" alt="">
										<div class="demo-upload-list-cover">
											<Icon type="ios-eye-outline" @click="handleView(row.image)"></Icon>
										</div>
									 </div>
								</div>
							</template>
							<template slot="category" slot-scope="{ row }">
								<p v-if="row.category && row.category.name">{{row.category.name}}</p>
							</template>
							<template slot="action" slot-scope="{row ,index}">
								<Button type="primary" @click="openEditModal(row,index)">Edit</Button>
								<Button type="error" @click="deleteBanner(row.id,index)">Delete</Button>
							</template>
						</Table>
					</div>
					<!-- <div class="_pagination _padd_t20" v-if="alldata && alldata.total!=null">
						<Page :total="alldata.total" show-sizer :page-size-opts="pageOption" />
					</div> -->
					<div class="_pagination _padd_t20" v-if="alldata && alldata.total!=null">
							<Page :total="alldata.total" show-sizer :page-size-opts="pageOption" @on-page-size-change="getSizeChange"  @on-change="paginateDataInfo" />
					</div>
                </div>
            </div>
        </div>
		<Modal title="Update banner"  v-model="modalOn" :mask-closable="false">
            <Form  :model="form" label-position="top" >
                <FormItem prop="title" :error="error.title"    label="Title">
                     <Input v-model="form.title" />
                </FormItem>
                <FormItem prop="category" :error="error.category"    label="Select Category">
					<Select
                            v-model="form.category_id"
                            filterable
                          >
                          <Option v-for="(item, index) in categories" :value="item.id" :key="index" >{{item.name}}</Option>
                    </Select>
                </FormItem>
                <FormItem prop="image" :error="error.image"  label="Image">
						<div class="" v-if="form.image">
							<template >
								<img :src="form.image">
								<div class="">
									<!-- <Icon type="ios-eye-outline" @click.native="handleView()"></Icon> -->
									<Icon type="ios-trash-outline" @click.native="handleRemove()"></Icon>
								</div>
							</template>
						</div>
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
                <Button   @click="modalOn=false" >Cancel</Button>
                <Button type="primary"  @click="updateData" :loading="isLoading" :disabled="isLoading">{{isLoading? 'Please wait' : 'Update'}}</Button>
            </div>
        </Modal>
		<Modal title="Create new banner"  v-model="createModal" :mask-closable="false">
            <Form  :model="form" label-position="top" >
                <FormItem prop="title" :error="error.title"    label="Banner title">
                     <Input v-model="form.title" />
                </FormItem>
				<FormItem prop="category" :error="error.category"    label="Select Category">
					<Select
                            v-model="form.category_id"
                            filterable
                          >
                          <Option v-for="(item, index) in categories" :value="item.id" :key="index" >{{item.name}}</Option>
                    </Select>
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
                                :format="['jpg','jpeg','png','webp']"
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
			categories:[],
			imageModal:false,
			singleImage:'',
			uploadErrorText:'',
			 form:{
                  title:'',
                   category_id:'',
                  image:'',
             },
           	 error:{
                title:'',
                category:'',
                 image:'',
            },
			modalOn:false,
			isLoading:false,
			createModal:false,
			editIndex:-1,
			_rowKey:-1,
            pageOption:[5,10,15,20],
            columns: [
				 {
                    title: 'User ID',
                    sortable: true,
                    key: 'id',
                },
				{
					title: 'Title',
					key: 'title'
				},
				{
					title: 'Image',
					slot: 'image'
				},
				{
					title: 'Category',
					slot: 'category'
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
			str:'',
			page:1,
			pageSize:10
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
			   this.getAllBanners()
        },
		async getAllCategory(){
            const res = await this.callApi('get', `/productmodule/getAllCategory`)
            if(res.status==200){
                this.categories = res.data.data.data
            } 
        },
		handleView(image){
			this.singleImage = image
			this.imageModal = true
		},
		clearData(){
			this.error= {
				title:'',
				franchise_id:'',
                image:''
			},
			this.form={
                title:'',
                franchise_id:'',
                image:''
            }
		},
		validateData(){
			let flag =1
			if(!this.form.title || this.form.title.trim()==''){
				this.error.title = "This field is required!"
				flag =0
			}
			if(!this.form.image || this.form.image.trim()==''){
				this.error.image = "This field is required!"
				flag =0
			}
			return flag
		},
		async createUser(item,index){
			this.error= {
				title:'',
				franchise_id:'',
                image:''
			}
			if(!this.validateData()) return 
			this.isLoading = true
			const res = await this.callApi('post', '/usermodule/createBanner', this.form)
			if(res.status == 200 || res.status == 201){
				this.alldata.data.unshift(res.data)
				this.s("New banner has been created.")
				// this.createModal = false
			}
			else if(res.status==422){
				this.e(res.data.message)
			}
			else {
				this.swr()
			}
			this.clearData()
			this.createModal = false;
			this.isLoading = false
		},
		async updateData(item,index){
			if(!this.validateData()) return 
			this.isLoading = true
			const res = await this.callApi('post', '/usermodule/updateBanner', this.form)
			if(res.status == 200){
				this.form._index = this.editIndex
				Vue.set(this.alldata.data,this.editIndex,res.data)
				this.alldata.data[this.editIndex] = _.cloneDeep(res.data)
				this.s("Banner information has been updated!")
			}
			else if(res.status==422){
				this.e(res.data.message)
			}
			else {
				this.swr() 
			}
			this.modalOn = false;
			this.isLoading = false
		},
		openEditModal(item,index){
			this.clearData()
			this.editIndex = index
			this.form.title = item.title
			this.form.category_id = item.category_id
			this.form.image = item.image
			this.form.franchise_id = item.franchise_id
			this.form.id = item.id
			this.modalOn = true;
		},
		async deleteBanner(id,index){
			if (!confirm("are you sure to delete?")) {
				return;
			}
			const res = await this.callApi('post', '/usermodule/deleteBanner', {id:id})
			if(res.status == 200){
				this.alldata.data.splice(index, 1)
				this.s("Banner has been deleted")
			}
			else if(res.status==422){
				this.e(res.data.message)
			}
			else{
				this.swr()
			}
		},
		openCreateModal(){
			this.clearData()
			this.createModal = true
		},
		handleRemove(i) {
            	this.form.image =""
        },
         handleSuccess(res, file) {
             console.log(res, file, "hello")
             this.form.image = res.image
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
		async getAllBanners(){
			
			const res = await this.callApi('get', `/usermodule/getAllBanners?str=${this.str}`)
			if(res.status==200){
				this.alldata = res.data
				// this.alldata.total = res.total
			} 
		}
	},
    async created(){
		this.getAllBanners();
		this.getAllCategory()

    },
    
}
</script>
