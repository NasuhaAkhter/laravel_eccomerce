<template>
    <div class="_main_content">
		<div class="row"> 
            <div class="col-12 col-md-12 col-lg-12">
				<div class="_box _b_radious3 _padd20">
                    <div class="_1card_top _mar_b20">
							<div class="_1card_top_left">
								<p class="_1card_top_title">Franchise Owner list</p>
								<!-- <div class="_1card_top_search">
									<Input @on-change="serchResetlt" v-model="str" search enter-button="Search" placeholder="Enter something..." />
								</div> -->
							</div> 
							<div class="_1card_top_right">
								<ul class="_1card_top_right_list">
									<li><Button type="primary" @click="openCreateModal">Add</Button></li>
								</ul> 
							</div>
						</div>
						<div class="_table_responsive">
							<Table class="" border :columns="columns" :data="alldata">
								<template slot="phone" slot-scope="{ row }">
									<p  >{{row.countryCode}}{{row.phone}}</p>
								</template>
								<template slot="franchise" slot-scope="{ row }">
									<p  v-if="row.franchise">{{row.franchise.name}}</p>
								</template>
								<template slot="action" slot-scope="{row,index }">
									<Button type="primary" @click="openEditModal(row,index)">Edit</Button>
									<!-- <Button type="primary">Franchises</Button> -->
									<Button type="error" @click="deleteUser(row.id,index)">Delete</Button>
								</template>
							</Table>
						</div>
						<!-- <div class="_pagination _padd_t20" v-if="alldata && alldata.total!=null">
							<Page :total="alldata.total" show-sizer :page-size-opts="pageOption" @on-page-size-change="getSizeChange"  @on-change="paginateDataInfo" />
						</div> -->
                </div>
            </div>
        </div>
		<Modal title="Update Information"  v-model="modalOn" :mask-closable="false">
            <Form  :model="form" label-position="top" >
                <FormItem prop="name" :error="error.name"    label="First Name">
                     <Input v-model="form.name" />
                </FormItem>
              <FormItem  prop="phone" :error="error.phone" label="Contact">
                    <Input  v-model="form.phone"  />
                </FormItem>
               <FormItem  prop="country" :error="error.country" label="Country">
                    <Input disabled v-model="form.country"  />
                </FormItem>
               <FormItem  prop="countryCode" :error="error.countryCode" label="Country code">
                    <Input disabled v-model="form.countryCode"  />
                </FormItem>
               <!-- <FormItem  prop="state" :error="error.state" label="State">
                    <Input  v-model="form.state"  />
                </FormItem>  -->
               <FormItem  prop="userType" :error="error.userType" label="User Type">
                     <Select v-model="form.userType" >
						<Option v-for="(item, i) in userType" :value="item" :key="i">{{item}}</Option>
                     </Select >
                </FormItem>
               <FormItem  prop="address" :error="error.address" label="Address">
                    <Input v-model="form.address" type="textarea" :autosize="{minRows: 2,maxRows: 5}"/>
                </FormItem>
            </Form>

            <div slot="footer">
                <Button   @click="modalOn=false" >Cancel</Button>
                <Button type="primary"  @click="updateData" :loading="isLoading" :disabled="isLoading">{{isLoading? 'Please wait' : 'Update'}}</Button>
            </div>
        </Modal>
		<Modal title="Create Franchise Owner"  v-model="createModal" :mask-closable="false">
            <Form  :model="form" label-position="top" >
                <FormItem prop="name" :error="error.name"    label="First Name">
                     <Input v-model="form.name" />
                </FormItem>
              <FormItem  prop="phone" :error="error.phone" label="Contact">
                    <Input  v-model="form.phone"  />
                </FormItem>
               <FormItem  prop="password" :error="error.password" label="password">
                    <Input type="password"  v-model="form.password"  />
                </FormItem>
                <FormItem  prop="country" :error="error.country" label="Country">
                    <Input disabled v-model="form.country"  />
                </FormItem>
               <FormItem  prop="countryCode" :error="error.countryCode" label="Country code">
                    <Input disabled v-model="form.countryCode"  />
                </FormItem>
               <!-- <FormItem  prop="state" :error="error.state" label="State">
                    <Input  v-model="form.state"  />
                </FormItem> -->

               <FormItem  prop="userType" :error="error.userType" label="User Type">
                     <Select v-model="form.userType" >
						<Option v-for="(item, i) in userType" :value="item" :key="i">{{item}}</Option>
                     </Select >
                </FormItem>
               <FormItem  prop="address" :error="error.address" label="Address">
                    <Input v-model="form.address" type="textarea" :autosize="{minRows: 2,maxRows: 5}"/>
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
                phone:'',
                country:'Bangladesh',
                userType:'',
                franchise_id:0,
                countryCode:'+88', 
			 },
           	 error:{
                name:'',
                phone:'',
                address:'',
                phone:'',
                state:'',
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
					width: 60
                },
				{
					title: 'Name',
					key: 'name',
					width: 250
				},
				{
					title: 'Franchise Name', 
					slot: 'franchise',
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
					width: 200
				}
			],
            alldata:[],
			str:'',
			page:1,
			pageSize:10,
			userType:['General', 'Paid User', 'ShopOwner', 'Driver', 'FranchiseOwner'], 
        }
	},
	methods:{
		async getUsertype(){
            const res = await this.callApi('get', `/usermodule/getUsertype`)
            if(res.status==200){
                this.userType = res.data
            } 
        },
		clearData(){
			this.error= {
				name:'',
                phone:'',
                address:'',
                phone:'',
                state:'',
                country:'',
                userType:''
			},
			this.form={
				country:"Bangladesh",
                countryCode:"+88",
                franchise_id:0
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
			// if(!this.form.state || this.form.state.trim()==''){
			// 	this.error.state = "This field is required!"
			// 	flag =0
			// }
			// if(!this.form.userType || this.form.userType.trim()==''){
			// 	this.error.userType = "This field is required!"
			// 	flag =0
			// }
			if(!this.form.userType || this.form.userType.trim()==''){
				this.error.userType = "This field is required!"
				flag =0
			}
			return flag
		},
		async createUser(item,index){
			if(!this.validateData()) return 
            this.isLoading = true
            // this.form.userType = "FranchiseOwner"
            this.form.franchise_id = 0
			const res = await this.callApi('post', '/usermodule/createUser', this.form)
			if(res.status == 200 || res.status == 201){
				// window.location ='/usermodule/franchiseOwner'
                // this.$router.push('/usermodule/franchiseOwner')
				// this.alldata.data.push(res.data)
				if(res.data.userType == 'FranchiseOwner' ){
					this.alldata.push(res.data)
				}
				this.s("Franchise owner has been created.")
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
            this.editIndex = index 
            if(!this.validateData()) return 
            // this.form.userType = "FranchiseOwner"
			this.isLoading = true
			const res = await this.callApi('post', '/usermodule/updateUser', this.form)
			if(res.status == 200){
				// window.location ='/usermodule/franchiseOwner'

                // this.$router.push('/usermodule/franchiseOwner')
                // this.form._index = this.editIndex
				this.form._index = this.editIndex
				if(res.data.data.userType == 'FranchiseOwner' ){
					this.alldata[this.editIndex] = res.data.data
					Vue.set(this.alldata,this.editIndex,res.data.data)
				}else{
					this.alldata.splice(this.editIndex, 1)
				}
				// this.alldata[this.editIndex] = _.cloneDeep(res.data.data)
                //   console.log(this.alldata)
				this.s("Information has been updated!")
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
			  if (!confirm("Are you sure to delete?")) {
                    return;
                }
			const res = await this.callApi('post', '/usermodule/deleteUser', {id:id})
			if(res.status == 200){
				this.s("User has been deleted")
				this.alldata.splice(index, 1)
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
			const res = await this.callApi('get', `/usermodule/getAllFranchiseOwner?page=${this.page}&pageSize=${this.pageSize}&str=${this.str}`)
			if(res.status==200){
				this.alldata = res.data
				// this.alldata.total = res.data.total
			} 
		}
	},  
    async created(){
		this.getAllCoustomer()
		// this.getUsertype()
    },
    
}
</script>
