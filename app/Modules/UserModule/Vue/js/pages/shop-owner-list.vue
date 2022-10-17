<template>
    <div class="_main_content">
		<div class="row">
            <div class="col-12 col-md-12 col-lg-12">
				<div class="_box _b_radious3 _padd20">
                    <div class="_1card_top _mar_b20">
							<div class="_1card_top_left">
								<p class="_1card_top_title">Shop Owner list</p>

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
							<Table class="" border :columns="columns" :data="alldata.data">
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
				<FormItem  prop="userType" :error="error.userType" label="User Type">
                     <Select v-model="form.userType" >
						<Option v-for="(item, i) in userType" :value="item" :key="i">{{item}}</Option>
                     </Select >
                </FormItem>
               
            </Form>

            <div slot="footer">
                <Button   @click="modalOn=false" >Cancel</Button>
                <Button type="primary"  @click="updateData" :loading="isLoading" :disabled="isLoading">{{isLoading? 'Please wait' : 'Update'}}</Button>
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
				<FormItem  prop="userType" :error="error.userType" label="User Type">
                     <Select v-model="form.userType" >
						<Option v-for="(item, i) in userType" :value="item" :key="i">{{item}}</Option>
                     </Select >
                </FormItem>
               
            </Form>

            <div slot="footer">
                <Button   @click="createModal=false" >Cancel</Button>
                <Button type="primary"  @click="createUser" :loading="isLoading" :disabled="isLoading">{{isLoading? 'Please wait' : 'Create'}}</Button>
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
					width: 100
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
            alldata:{
                total:0,
                data:[]
			},
			str:'',
			page:1,
			pageSize:10,
			userType:['General', 'Paid User', 'ShopOwner', 'Driver', 'FranchiseOwner'], 
        }
	}, 
	methods:{
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
			if(!this.form.userType || this.form.userType.trim()==''){
				this.error.userType = "This field is required!"
				flag =0
			}
			return flag
		},
		async createUser(item,index){
			if(!this.validateData()) return 
			this.isLoading = true
 			// this.form.franchise_id = authUser.franchise_id
			// console.log(this.form)
			const res = await this.callApi('post', '/usermodule/createUser', this.form)
			if(res.status == 200){
				// window.location ='/usermodule/user-list'
				if(res.data.userType == 'ShopOwner' ){
					this.alldata.data.push(res.data)
				}
				this.s("New User has been created")
				this.clearData()
				this.createModal = false
			}
			else if(res.status==422){
				if(res.data.message){
					this.e(res.data.message) 
				}else{
				   for(let item in res.data){
					 this.e(res.data[item] ) 
				   }
				}
			}
			else {
				this.swr()
			}

			this.isLoading = false
		},
		async updateData(item,index){ 
			if(!this.validateData()) return 
			this.isLoading = true
			// this.form.franchise_id = authUser.franchise_id
			const res = await this.callApi('post', '/usermodule/updateUser', this.form)
			if(res.status == 200){
				// window.location ='/usermodule/user-list'
				// this.form._index = this.editIndex
				if(res.data.data.userType == 'ShopOwner' ){
					this.alldata.data[this.editIndex] = res.data.data
					Vue.set(this.alldata.data,this.editIndex,res.data.data)
				}else{
					this.alldata.data.splice(this.editIndex, 1)
				}
				// this.alldata.data[this.editIndex] = res.data.data
				// Vue.set(this.alldata.data,this.editIndex,res.data.data)
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
			const res = await this.callApi('get', `/usermodule/getAllShopOwner?page=${this.page}&pageSize=${this.pageSize}&str=${this.str}`)
			if(res.status==200){
				this.alldata = res.data
				// this.alldata.total = res.data.total

				
			}
			
		},
		
	},
	
    async created(){
		this.getAllCoustomer()
		
    },
    
}
</script>
