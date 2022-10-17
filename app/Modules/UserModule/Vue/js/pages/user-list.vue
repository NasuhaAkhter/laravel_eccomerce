<template>
    <div class="_main_content">
		<div class="row">
            <div class="col-12 col-md-12 col-lg-12">
				<div class="_box _b_radious3 _padd20">
                    <div class="_1card_top _mar_b20">
							<div class="_1card_top_left">
								<p class="_1card_top_title">Customer list</p>

								<div class="_1card_top_search">
									<Input @on-change="serchResetlt" v-model="str" search enter-button="Search" placeholder="Enter something..." />
								</div>
								<div class="_1card_top_search">
 									 <Select v-model="filtrBy" style="width:200px" @on-change="filterResult"  placeholder="Filter by..." > 
										<Option value="paid">Paid user</Option>
										<Option value="non-paid" >Non paid user</Option>
										<Option value="all">All</Option>
									</Select>
								</div>
							</div>

							<div class="_1card_top_right">
								<ul class="_1card_top_right_list">
									<li><Button type="primary" @click="openPushNotiModal">Send Push Notification</Button></li>
									<li><Button type="primary" @click="openCreateModal">Add</Button></li>
								</ul> 
							</div>
						</div>
						<div class="_table_responsive">
							<Table class="" border :columns="columns" :data="alldata.data">
								<template slot="check_box" slot-scope="{ row }">
								<CheckboxGroup v-model="selectedId">
									<Checkbox :label="row.id" ></Checkbox>
								</CheckboxGroup>
								</template>
								<template slot="phone" slot-scope="{ row }">
									<p  >{{row.countryCode}}{{row.phone}}</p>
								</template>

								<template slot="action" slot-scope="{row,index }">
									<Button type="primary" @click="openEditModal(row,index)">Edit</Button>
									<Button type="success"  @click="$router.push('/usermodule/delivery_address/'+row.id)">Delivery Address</Button>
									<Button type="error" @click="deleteUser(row.id,index)">Delete</Button>
								</template>
							</Table>
						</div>
						<div class="_pagination _padd_t20" v-if="alldata && alldata.total!=null">
							<Page :total="alldata.total" show-sizer :page-size-opts="pageOption" @on-page-size-change="getSizeChange"  @on-change="paginateDataInfo" />
						</div>
                </div>
            </div>
        </div>
		<Modal title="Update Information"  v-model="modalOn" :mask-closable="false">
            <Form  :model="form" label-position="top" >
                <FormItem prop="name" :error="error.name"    label="Full Name">
                     <Input v-model="form.name" />
                </FormItem>
				<FormItem  prop="phone" :error="error.phone" label="Contact">
                    <Input  v-model="form.phone"  />
                </FormItem>
              <FormItem  prop="franchise_id"  label="Select franchise"  >
						<Select v-model="form.franchise_id" @on-change="selectFranchise" :disabled="!isAdmin" >
							<Option v-for="(item, index) in franchiseList" :value="item.id" :key="index">{{item.name}}</Option>
						</Select>
                </FormItem>
               <FormItem  prop="country" :error="error.country" label="Country">
				   <Select v-model="form.country" @on-change="selectCountry" >
						<Option  value="Bangladesh" >Bangladesh</Option>
						<Option  value="UK" >UK</Option>
					</Select>
                </FormItem>
               <FormItem  prop="city" :error="error.city" label="city">
                    <Input disabled v-model="form.city"  />
                </FormItem>
				<FormItem  prop="address" :error="error.address" label="Address">
                    <Input v-model="form.address" type="textarea" :autosize="{minRows: 2,maxRows: 5}"/>
                </FormItem>
				 <FormItem  prop="countryCode" :error="error.countryCode" label="Country code">
                    <Input disabled v-model="form.countryCode"  />
                </FormItem>
				<!-- <FormItem  prop="userType" :error="error.userType" label="User Type">
                     <Select v-model="form.userType" >
						<Option v-for="(item, i) in userType" :value="item" :key="i">{{item}}</Option>
                     </Select >
                </FormItem> -->
               
            </Form>

            <div slot="footer">
                <Button   @click="modalOn=false" >Cancel</Button>
                <Button type="primary"  @click="updateData" :loading="isLoading" :disabled="isLoading">{{isLoading? 'Please wait' : 'Update'}}</Button>
            </div>
        </Modal>
		<Modal title="Push Notification" :width="800"  v-model="pushNotiModal" :mask-closable="false" :closable="false">
			 <div  class="row" >
				<div class="col-12 col-md-6 col-lg-6">
					 <p class="_label _mar_b15">Title</p>
 					<Input v-model="pushNotiOb.title" type="textarea" :autosize="{minRows: 3,maxRows: 5}"/>
				</div>
				<div class="col-12 col-md-6 col-lg-6">
					 <p class="_label _mar_b15">Message</p>
 					<Input v-model="pushNotiOb.message" type="textarea" :autosize="{minRows: 3,maxRows: 5}"/>
				</div>
				
				<div class="col-12 col-md-12 col-lg-12" style="margin-top: 15px;">
					<p class="_label _mar_b15">Select Customer</p>
					<Select v-model="userIndex" @on-change="selectUserOn">
						<Option value="all" > For All User</Option>
						<Option value="selectedUser" > For Seleced User</Option>
					</Select>
 				</div>
				<div class="col-12 col-md-12 col-lg-12" v-if="UserOn"  style="margin-top: 15px;">
					<p class="_label _mar_b15">Select User</p>
					<Select v-model="str13"  filterable @on-select="selectCoustomer" :remote-method="filterCoustomer" >
						<Option v-for="(item, index) in userList" :value="index" :key="index" :label="item.name">{{item.name}}</Option>
					</Select>
 				</div>
				<div class="col-12 col-md-12 col-lg-12" >
					<Table  border :columns="columns1" :data="pushNotiOb.users" v-if="pushNotiOb.users.length >0">
						<template slot="action" slot-scope="{row,index }">
							<Button type="error" @click="removeUser(row.id,index)">Remove</Button>
						</template>
					</Table>
				</div>
				<div class="col-12 col-md-12 col-lg-12" style="margin-top: 15px;">
					<p class="_label _mar_b15">Upload Image</p>
					<template v-if="pushNotiOb.image_url == ''">
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
					</template>  
					<template v-else  >
						<img :src="pushNotiOb.image_url">
						<div class="newDelIcon">
							<Icon type="ios-trash-outline" @click.native="handleRemove()"></Icon>
						</div>
					</template>
 					
					
 				</div>

			</div>
            <!-- <Form  :model="pushNotiOb" label-position="top" >
				<FormItem   :error="pushNotiOb.titleError" label="Title">
                    <Input v-model="pushNotiOb.title" type="textarea" :autosize="{minRows: 2,maxRows: 5}"/>
                </FormItem>
				<FormItem   :error="pushNotiOb.error" label="Message">
                    <Input v-model="pushNotiOb.message" type="textarea" :autosize="{minRows: 2,maxRows: 5}"/>
                </FormItem>
            </Form> -->
            <div slot="footer">
                <Button   @click="pushNotiModal=false" >Cancel</Button>
                <Button type="primary"  @click="sendPushNoti" :loading="isLoading" :disabled="isLoading">{{isLoading? 'Please wait' : 'Send'}}</Button>
            </div>
        </Modal>
		<Modal title="Create New Customer"  v-model="createModal" :mask-closable="false">
            <Form  :model="form" label-position="top" >
                <FormItem prop="name" :error="error.name"    label="Full Name">
                     <Input v-model="form.name" />
                </FormItem>
              
               <FormItem  prop="password" :error="error.password" label="password">
                    <Input type="password" v-model="form.password"  />
                </FormItem>
					<FormItem  prop="phone" :error="error.phone" label="Contact">
                    <Input  v-model="form.phone"  />
                </FormItem>
				<FormItem  prop="franchise_id"  label="Select franchise" >
                    <!-- <Input v-model="form.franchise_id"  /> -->
					<Select v-model="form.franchise_id" @on-change="selectFranchise" :disabled="!isAdmin" >
						<Option v-for="(item, index) in franchiseList" :value="item.id" :key="index">{{item.name}}</Option>
					</Select>
                </FormItem>
                <!-- <FormItem  prop="country" :error="error.country" label="Country">
                    <Select v-model="form.country" @on-change="selectCountry" >
						<Option  value="Bangladesh" >Bangladesh</Option>
						<Option  value="UK" >UK</Option>
					</Select>
                </FormItem> -->
				<FormItem  prop="city" :error="error.city" label="city">
                    <Input disabled v-model="form.city"  />
                </FormItem>
				<!-- <FormItem  prop="address" :error="error.address" label="Address">
                    <Input v-model="form.address" type="textarea" :autosize="{minRows: 2,maxRows: 5}"/>
                </FormItem> -->
               <FormItem  prop="countryCode" :error="error.countryCode" label="Country code">
                    <Input disabled v-model="form.countryCode"  />
                </FormItem>

               <!-- <FormItem  prop="userType" :error="error.userType" label="User Type">
                     <Select v-model="form.userType" >
						<Option v-for="(item, i) in userType" :value="item" :key="i">{{item}}</Option>
                     </Select >
                </FormItem> -->
			
                
               
            </Form>

            <div slot="footer">
                <Button   @click="createModal=false" >Cancel</Button>
                <Button type="primary"  @click="createUser" :loading="isLoading" :disabled="isLoading">{{isLoading? 'Please wait' : 'Create'}}</Button>
            </div>
        </Modal>
		<Modal title="Image"  v-model="imageModal" :mask-closable="false" :closable="false">
			<div>
				<img :src="singleImage" alt="">
			</div>
			 <div slot="footer">
                <Button   @click="imageModal=false" size='long' type="primary" >Close</Button>
            </div>
		</Modal>
    </div>
</template>


<script>
export default {
    data(){
        return{
			filtrBy:'all',
			form:{
				name:'',
				phone:'',
				address:'',
				city:'',
				country:'Bangladesh',
				userType:'',
				countryCode:'+88', 
				franchise_id:0
			},
			error:{
                name:'',
                phone:'',
                address:'',
                phone:'',
                city:'',
                country:'',
                userType:'',
                countryCode:'', 
            },
			modalOn:false,
			str13:'',
			pushNotiModal:false,
			isLoading:false,
			createModal:false,
			editIndex:-1,
			_rowKey:-1,
            pageOption:[5,10,15,20],
            columns: [
				 {
                    title: 'ID',
                    sortable: true,
					key: 'id',
                    width:100
                },
				{
					title: 'Name',
					key: 'name',
					width: 200
				},
				{
					title: 'Phone',
					slot: 'phone',
					width: 150
				},
				{
					title: 'Country',
					key: 'country',
					width: 120
				},
				{
					title: 'City',
					key: 'city',
					width: 120
				},
				{
					title: 'Address',
					key: 'address',
					// width: 250

				},
				{
					title: 'Action',
					slot: 'action',
					width: 315
				}
			],
            columns1: [
				 {
                    title: 'ID',
                    sortable: true,
					key: 'id',
                    width:100
                },
				{
					title: 'Name',
					key: 'name',
					// width: 200
				},
				{
					title: 'Phone',
					key: 'phone',
					// width: 150
				},
			 
				{
					title: 'Address',
					key: 'address',
					// width: 250

				},
				{
					title: 'Action',
					slot: 'action',
					// width: 315
				}
				 
			],
			userList:[],
			singleUser:{},
			userIndex:'',
			UserOn:false,
            alldata:{
                total:0,
                data:[]
			},
            pushNotiOb:{
                title :'',
                message :'',
				image_url :"",
				users:[],
 			},
			str:'',
			str1:'',
			page:1,
			pageSize:10,
			userType:['General', 'Paid User', 'ShopOwner', 'Driver', 'FranchiseOwner'], 
			selectedId:[],
			imageModal:false,
			singleImage:'',
        }
	}, 
	methods:{
		 filterResult(e){
			console.log(e)
			this.page = 1
			this.getAllCoustomer()
		},
		selectUserOn(){
			// console.log("function call hoise")
			if(this.userIndex == 'selectedUser'){ 
				// console.log("sjdgvghbj")
				this.UserOn = true
			}else{
				this.UserOn = false
				this.pushNotiOb.users =[]
			}
		},
		handleView(image){
			this.singleImage = image
			this.imageModal = true
		},
		handleRemove() {
            this.pushNotiOb.image_url = ""
        },
		handleSuccess(res, file) {
			this.pushNotiOb.image_url = res.image
			// console.log(this.pushNotiOb.image_url)
            //  console.log(res, file, "hello")
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
			this.pushNotiOb.image_url = ""
		},
		async selectCoustomer(item){
			this.str1 = ''
			// console.log(item)
			this.singleUser = this.userList[item.value]
			for(let i of this.pushNotiOb.users){
				if(i.id == this.singleUser.id){
					return  this.i("Already selected.")
				}
			}
			this.singleUser = this.userList[item.value]
			// console.log(this.singleUser)
			this.pushNotiOb.users.push(this.singleUser)
		},
		async removeUser(id, index){
			this.pushNotiOb.users.splice(index, 1)
		},
		async sendPushNoti(){
			 
			if(this.pushNotiOb.title == '' || this.pushNotiOb.title.trim() == ''){
				return this.i('Please Write a Title!');
			}
			if(this.pushNotiOb.message == '' || this.pushNotiOb.message.trim() == ''){
				return this.i('Please Write a Message!');
			}
			let user = []
			for(let i of this.pushNotiOb.users){
				user.push(i.id)
				// console.log(i)
			}
			this.pushNotiOb.users = user
			this.isLoading=true
			this.pushNotiOb.franchise_id = this.franchise_id
			console.log("hello", this.pushNotiOb)
			const res = await this.callApi('post','/usermodule/sendPushNotificationToCustmer',this.pushNotiOb)
			console.log("this.pushNotiOb", this.pushNotiOb)
			if(res.status == 200){
				this.s("Message sent successfully.");
				this.pushNotiOb = {
					message :'',
					title :'',
					image_url :'',
					users:[]
				}
			    this.pushNotiModal = false
			}
			else this.swr();
			this.isLoading=false
		},
		selectFranchise(item){
			if(item == undefined) return
   			var index = this.franchiseList.findIndex(x => x.id === item);
            var franchise = this.franchiseList[index]
            this.form.franchise_id = franchise.id
            this.form.city = franchise.city
		},
		selectCountry(item){
   			if(item == 'Bangladesh') this.form.countryCode = "+88"
   			else this.form.countryCode = "+44"
		},
		clearData(){
			this.error= {
				name:'',
                phone:'',
                address:'',
                phone:'',
                state:'',
                country:'',
				userType:'',
				franchise_id:0
			},
			this.form={ 
				name:'',
                phone:'',
                address:'',
                city:this.franchiseDetails.city,
                country:'Bangladesh',
                userType:'',
				countryCode:'+88', 
				franchise_id:this.franchise_id
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
			// if(!this.form.userType || this.form.userType.trim()==''){
			// 	this.error.userType = "This field is required!"
			// 	flag =0
			// }
			return flag
		},
		async createUser(item,index){
			if(!this.validateData()) return 
			this.isLoading = true
			this.form.userType = "General"
			// this.form.franchise_id = authUser.franchise_id
			// console.log(this.form)
			const res = await this.callApi('post', '/usermodule/createUser', this.form)
			if(res.status == 200){
				// window.location ='/usermodule/user-list'
				if(res.data.userType == 'General' ){
					this.alldata.data.push(res.data)
				}
				this.s("New User has been created")
				this.clearData()
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
			if(!this.validateData()) return 
			this.isLoading = true
			this.form.fuserType == 'General'
			const res = await this.callApi('post', '/usermodule/updateUser', this.form)
			if(res.status == 200){
				// window.location ='/usermodule/user-list'
				// this.form._index = this.editIndex
				if(res.data.data.userType == 'General' ){
					this.alldata.data[this.editIndex] = res.data.data
					Vue.set(this.alldata.data,this.editIndex,res.data.data)
				}else{
					this.alldata.data.splice(this.editIndex, 1)
				}
				this.s("User information has been updated!")
				this.modalOn = false;
			}
			else if(res.status==422){
				this.e(res.data.message)
			}
			else {
				this.swr()
			}
			this.isLoading = false
		},
		openEditModal(item,index){
			this.clearData()
			this.editIndex = index
			this.form = _.cloneDeep(item)
			this.modalOn = true;
		},
		openCreateModal(){
			this.clearData()
			this.createModal = true
		},
		openPushNotiModal(){
			this.pushNotiOb = {
				message :'',
				title :'',
				image_url :'',
				users:[],
			}
			this.pushNotiModal = true
		},
		async deleteUser(id, index){
			  if (!confirm("are you sure to delete?")) {
                    return;
                }
			const res = await this.callApi('post', '/usermodule/deleteUser', {id:id})
			if(res.status == 200){
				this.s("User has been deleted")
				this.alldata.data.splice(index, 1)
			}
			else if(res.status==422){
				this.e(res.data.message)
			}
			else {
				this.swr()
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
			   this.getAllCoustomer()
        },
		async getAllCoustomer(){
			const res = await this.callApi('get', `/usermodule/getAllUsers?page=${this.page}&paid_status=${this.filtrBy}&pageSize=${this.pageSize}&str=${this.str}`)
			if(res.status==200){
				this.alldata = res.data
			}
		},
		async filterCoustomer(e=''){
			if(e){
                this.str1 = e
            }
			const res = await this.callApi('get', `/usermodule/getAllUsers?page=${this.page}&paid_status=${this.filtrBy}&pageSize=${this.pageSize}&str=${this.str1}`)
			if(res.status==200){
				this.userList = res.data.data				
			}
		},
		
	},
	
    async created(){
		this.getAllCoustomer()
		
    },
    
}
</script>
