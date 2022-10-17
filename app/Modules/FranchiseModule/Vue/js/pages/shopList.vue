<template>
    <div class="_main_content">
		<div class="row"> 
            <div class="col-12 col-md-12 col-lg-12">
				<div class="_box _b_radious3 _padd20">
                    <div class="_1card_top _mar_b20">
							<div class="_1card_top_left">
								<p class="_1card_top_title">Partner shops</p>
								<div class="_1card_top_search">
									<Input @on-change="serchResetlt" v-model="str" search enter-button="Search" placeholder="Enter something..." />
								</div>
							</div>
							<div class="_1card_top_right">
								<ul class="_1card_top_right_list">
									<li>
										<Button type="primary" @click="$router.push('/franchisemodule/addShop')">Add</Button>
										<!-- <Button type="primary" @click="openCreateModal">Add</Button> -->
									</li>
								</ul>
							</div>
						</div>
						<div class="_table_responsive"> 
							<Table class="_table1200" border :columns="columns" :data="alldata.data">
								<template slot="user" slot-scope="{ row }">
									<p v-if="row.user && row.user.name">{{row.user.name}}</p>
								</template>
								<template slot="franchise" slot-scope="{ row }">
									<p v-if="row.franchise && row.franchise.name">{{row.franchise.name}}</p>
								</template>
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
								<template slot="status" slot-scope="{ row , index }">
									  <i-switch :loading="switchLoading" v-model="row.status" @on-change="availabilityChange(row,index)" :true-value="1" :false-value="0"  true-color="#13ce66" false-color="#ff4949"  size="large">
										<span slot="open" >ON</span>
										<span slot="close"  >OFF</span>
									</i-switch>
								</template>
								<template slot="action" slot-scope="{row,index }">
									<Button type="info" @click="$router.push(`/productmodule/product?shopId=${row.id}`)">Products</Button>
									<Button v-if="row.category.name  && row.category.name == 'Restaurants'" type="info" @click="$router.push(`/productmodule/menu_categories?shopId=${row.id}`)">Restaurant Menus</Button>
									<Button type="primary" @click="$router.push(`/franchisemodule/editShop?id=${row.id}`)">Edit</Button>
									<Button type="error" @click="deleteShops(row.id, index)">Delete</Button>
								</template>
							</Table>
						</div>  
                        <div class="_pagination _padd_t20" v-if="alldata && alldata.total!=null">
							<Page :total="alldata.total" show-sizer :page-size-opts="pageOption" @on-page-size-change="getSizeChange"  @on-change="paginateDataInfo" />
						</div>
                </div>
            </div>
        </div>
		<Modal title="Update Shop"  v-model="modalOn" :mask-closable="false">
            <Form  :model="form" label-position="top" >
                <FormItem prop="name" :error="error.name"    label="Shop name">
                     <Input v-model="form.name" />
                </FormItem>
				<FormItem prop="name" :error="error.user_id"    label="Owner Name">
                     <Select
                            v-model="userIndex" 
                            filterable
                            @on-select="selectCoustomer"
                            :remote-method="getAllCoustomer"
                        >
                          <Option v-for="(item, index) in users" :value="index" :key="index" :label="item.name">{{item.name}}</Option>
                    </Select>
                </FormItem>
              
               <FormItem  prop="country" :error="error.country" label="country">
                    <Input disabled v-model="form.country"  />
                </FormItem>
               
               <!-- <FormItem  prop="state" :error="error.state" label="State">
                    <Input  v-model="form.state" />
                </FormItem> -->
               <FormItem  prop="state" :error="error.city" label="City">
                    <Input disabled v-model="form.city"  />
                </FormItem>
               <FormItem  prop="address" :error="error.address" label="Address">
                    <Input v-model="form.address" type="textarea" :autosize="{minRows: 2,maxRows: 5}"/>
                </FormItem>
				<FormItem  prop="countryCode" :error="error.countryCode" label="Country code">
                    <Input disabled v-model="form.countryCode"  />
                </FormItem>
				<FormItem  prop="phone" :error="error.phone" label="Contact">
                    <Input  v-model="form.phone"  />
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
                <Button   @click="modalOn=false" >Cancel</Button>
                <Button type="primary"  @click="updateData" :loading="isLoading" :disabled="isLoading">{{isLoading? 'Please wait' : 'Update'}}</Button>
            </div>
        </Modal>
		<Modal title="Create New Shop"  v-model="createModal" :mask-closable="false">
            <Form  :model="form" label-position="top" >
                <FormItem prop="name" :error="error.name"    label="Shop name">
                     <Input v-model="form.name" />
                </FormItem>
				<FormItem prop="name" :error="error.user_id"    label="Owner Name">
                     <Select
                            v-model="userIndex" 
                            filterable
                            @on-select="selectCoustomer"
                            :remote-method="getAllCoustomer"
                        >
                          <Option v-for="(item, index) in users" :value="index" :key="index" :label="item.name">{{item.name}}</Option>
                    </Select>
                </FormItem>
              
               <FormItem  prop="country" :error="error.country" label="country">
                    <Input  disabled v-model="form.country"  />
                </FormItem>
				 <FormItem  prop="city" :error="error.city" label="City">
                    <Input disabled v-model="form.city"  />
                </FormItem>
               <FormItem  prop="address" :error="error.address" label="Address">
                    <Input v-model="form.address" type="textarea" :autosize="{minRows: 2,maxRows: 5}"/>
                </FormItem>
               <FormItem  prop="countryCode" :error="error.countryCode" label="Country code">
                    <Input disabled  v-model="form.countryCode"  />
                </FormItem>
				<FormItem  prop="phone" :error="error.phone" label="Contact">
                    <Input  v-model="form.phone"  />
                </FormItem>
               <!-- <FormItem  prop="state" :error="error.state" label="State">
                    <Input  v-model="form.state"  />
                </FormItem> -->
              
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
                <Button type="primary"  @click="createShops" :loading="isLoading" :disabled="isLoading">{{isLoading? 'Please wait' : 'Create'}}</Button>
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
                phone:'',
                address:'',
                country:'Bangladesh',
                countryCode:'+88',
                city:'',
                state:'',
                percentage:'',
				user_id:'',
				image:''
			 },
           	 error:{
                name:'',
                phone:'',
                address:'',
                country:'',
                countryCode:'',
                city:'',
                state:'',
                percentage:'',
				user_id:'',
				image:''
            },
			modalOn:false,
			isLoading:false,
			createModal:false,
			editIndex:-1,
			_rowKey:-1,
            pageOption:[5,10,15,20],
            columns: [
				 {
                    title: 'ID',
					key: 'id',
					width: 60
                },
				{
					title: 'Shop name',
					key: 'name',
					width: 180
				},
				{
					title: 'Franchise Name',
					slot: 'franchise',
					width: 180
				},
				{
					title: 'Owner Name',
					slot: 'user',
					width: 180
				},
				{
					title: 'Contact',
					key: 'phone',
					width: 150
				},
				{
					title: 'Address',
					key: 'address',
					width: 180
				},
				{
					title: 'Status',
					slot: 'status',
					width: 150
				},
				{
					title: 'Image',
					slot: 'image',
					width: 100
				},
				{
					title: 'Action',
					slot: 'action',
					width: 500
				}
			],
            alldata:{
                total:0,
                data:[]
			},
			franchiseId:'',
			pageSize:10,
			page:1,
			str:'',
			str2:'',
			users: [], 
			userIndex:'',
			singleUser:{},
			switchLoading:false,
        }
	},
	methods:{
		async availabilityChange(row,index){
			
			
			this.switchLoading = true
			let ob = {
				id:row.id,
				status:row.status
			}
			const res = await this.callApi('post','/franchisemodule/updateshopStatus',ob)
			if(res.status == 200){
				
			}
			else this.swr();

			
			this.switchLoading = false
		},
	   async deleteShops(id, index){
			 if (!confirm("are you sure to delete?")) {
				return;
			}
			const res = await this.callApi('post', '/franchisemodule/deleteshop',{id:id})
			if(res.status == 200){
				this.alldata.data.splice(index, 1)
			}
			else if(res.status == 422){
				this.e(res.data.message)
			}
			else{
				this.swr() 
			}
        },
		 async selectCoustomer(item){
             this.singleUser = this.users[item.value]
             this.form.user_id = this.singleUser.id 
        },
        async getAllCoustomer(e=''){
            if(e){
                this.str2 = e
            }
           const res = await this.callApi('get', `/usermodule/getAllCoustomers?page=${this.page}&pageSize=${this.pageSize}&str=${this.str2}`)
            if(res.status==200){
                this.users = res.data.data
			}
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
			   this.getShopingList()
        },
		clearData(){
			this.form={
				name:'',
                phone:'',
                address:'',
                country:'Bangladesh',
                countryCode:'+880',
                city:'',
                state:'',
                percentage:'',
				user_id:'',
				image:''
			}
		},
		clearErrorData(){
			this.error= {
				name:'',
                phone:'',
                address:'',
                country:'',
                countryCode:'',
                city:'',
                state:'',
                percentage:'',
				user_id:'',
				image:''
			}
		
		},
		validateData(){
			let flag =1
			if(!this.form.name || this.form.name.trim()==''){
				this.error.name = "This field is required!"
				flag =0
			}
			if(!this.form.phone || this.form.phone.trim()==''){
				this.error.phone = "This field is required!"
				flag =0
			}
			if(!this.form.state || this.form.state.trim()==''){
				this.error.state = "This field is required!"
				flag =0
			}
			if(!this.form.countryCode || this.form.countryCode.trim()==''){
				this.error.countryCode = "This field is required!"
				flag =0
			}
			if(!this.form.address || this.form.address.trim()==''){
				this.error.address = "This field is required!"
				flag =0
			}
			if(!this.form.image || this.form.image.trim()==''){
				this.error.image = "This field is required!"
				flag =0
			}
			if(!this.form.user_id || this.form.user_id=='' || this.form.user_id==null){
				this.error.user_id = "This field is required!"
				flag =0
			}
			if(!this.form.city || this.form.city.trim()==''){
				this.error.city = "This field is required!"
				flag =0
			}
			return flag
		},
		async createShops(item,index){
            this.clearErrorData()
			if(!this.validateData()) return 
			this.isLoading = true
			const res = await this.callApi('post', '/franchisemodule/createNewshop', this.form)
			if(res.status == 200 || res.status == 201){
				this.alldata.data.unshift(res.data)
				this.s("New Shop has been created")
				this.createModal = false
			}
			else if(res.status==422){
				this.e(res.data.message)
			}
			else {
				this.swr()
			}
			this.isLoading = false
		},
		async updateData(item,index){
            this.clearErrorData()
            if(!this.validateData()) return 
			this.isLoading = true
			const res = await this.callApi('post', '/franchisemodule/updateshop', this.form)
			if(res.status == 200 ){
				// this.alldata[this.editIndex] = _.cloneDeep(this.form)
				Vue.set(this.alldata.data,this.editIndex,res.data)
				this.s("Shop information has been updated!")
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
			// this.clearErrorData()
			// this.clearData()
			this.editIndex = index
			this.form = _.cloneDeep(item)
			this.users = [] 
			this.userIndex=0
			this.users.push(item.user)
			this.modalOn = true;
		},
		openCreateModal(){
			this.clearErrorData()
			this.clearData()
			this.createModal = true

		},
		async getShopingList(){
			// if(this.$route.query && this.$route.query.franchiseId){
			// 	this.franchiseId = this.$route.query.franchiseId
			// }
			// if(this.franchiseId == ''){
			// 	this.franchiseId = authUser.franchise_id
			// }
			const res = await this.callApi('get', `/franchisemodule/getshops?franchiseId=${this.franchise_id}&page=${this.page}&pageSize=${this.pageSize}&str=${this.str}`);
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
		this.getShopingList()
		
    },
    
}
</script>
