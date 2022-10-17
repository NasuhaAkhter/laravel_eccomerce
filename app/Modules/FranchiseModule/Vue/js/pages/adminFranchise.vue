<template>
    <div class="_main_content">
		<div class="row">
            <div class="col-12 col-md-12 col-lg-12">
				<div class="_box _b_radious3 _padd20">
                    <div class="_1card_top _mar_b20">
							<div class="_1card_top_left">
								<p class="_1card_top_title">Age table</p>

								<div class="_1card_top_search">
									<Input search enter-button="Search" placeholder="Enter something..." />
								</div>
							</div>

							<div class="_1card_top_right">
								<ul class="_1card_top_right_list">
									<li><Button>Default</Button>
    								<li><Button type="dashed">Dashed</Button></li>
									<li><Button type="primary" @click="openCreateModal">Add</Button></li>
								</ul>
							</div>
						</div>
						<div class="_table_responsive">
							<Table class="_table1200" border :columns="columns" :data="alldata.data">
								<template slot="action" slot-scope="{ row }">
									<Button type="primary" @click="openEditModal(row)">Edit</Button>
									<Button type="primary">Franchises</Button>
									<Button type="error">Delete</Button>
								</template>
							</Table>
						</div>

                        <div class="_pagination _padd_t20" v-if="alldata && alldata.total!=null">
							<Page :total="alldata.total" show-sizer :page-size-opts="pageOption" />
						</div>
                </div>
            </div>
        </div>
		<Modal title="Update User Data"  v-model="modalOn" :mask-closable="false">
            <Form  :model="form" label-position="top" >
                <FormItem prop="name" :error="error.name"    label="First Name">
                     <Input v-model="form.name" />
                </FormItem>
              <FormItem  prop="phone" :error="error.phone" label="Contact">
                    <Input  v-model="form.phone"  />
                </FormItem>
               <FormItem  prop="country" :error="error.country" label="country">
                    <Input  v-model="form.country"  />
                </FormItem>
               <FormItem  prop="state" :error="error.state" label="State">
                    <Input  v-model="form.state"  />
                </FormItem>

               <FormItem  prop="userType" :error="error.userType" label="User Type">
                     <Select v-model="form.userType" >
                        <Option value="General">General</Option>
                        <Option value="Admin">Admin</Option>
                        <Option value="PaidUser">Paid User</Option>
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
		<Modal title="Create New User"  v-model="createModal" :mask-closable="false">
            <Form  :model="form" label-position="top" >
                <FormItem prop="name" :error="error.name"    label="First Name">
                     <Input v-model="form.name" />
                </FormItem>
              <FormItem  prop="phone" :error="error.phone" label="Contact">
                    <Input  v-model="form.phone"  />
                </FormItem>
               <FormItem  prop="password" :error="error.password" label="password">
                    <Input  v-model="form.password"  />
                </FormItem>
               <FormItem  prop="country" :error="error.country" label="country">
                    <Input   v-model="form.country"  />
                </FormItem>
               <FormItem  prop="state" :error="error.state" label="State">
                    <Input  v-model="form.state"  />
                </FormItem>

               <FormItem  prop="userType" :error="error.userType" label="User Type">
                     <Select v-model="form.userType" >
                        <Option value="General">General</Option>
                        <Option value="Admin">Admin</Option>
                        <Option value="PaidUser">Paid User</Option>
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
			 form:{},
           	 error:{
                name:'',
                phone:'',
                address:'',
                phone:'',
                state:'',
                country:'',
                userType:'',
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
					title: 'Name',
					key: 'name'
				},
				{
					title: 'Phone',
					key: 'phone'
				},

				{
					title: 'Country Code',
					key: 'countryCode'
				},
				{
					title: 'Country',
					key: 'country'
				},
				{
					title: 'State',
					key: 'state'
				},
				{
					title: 'Address',
					key: 'address'
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
            }
        }
	},
	methods:{
		clearErrorData(){
			this.error= {
				name:'',
                phone:'',
                address:'',
                phone:'',
                state:'',
                country:'',
                userType:''
			},
			this.form={}
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
			if(!this.form.password || this.form.password.trim()==''){
				this.error.password = "This field is required!"
				flag =0
			}
			return flag
		},
		async createUser(item,index){
            this.clearErrorData()
			if(!this.validateData()) return 
			this.isLoading = true
			const res = await this.callApi('post', '/usermodule/createNewFranchises', this.form)
			if(res.status == 200){
				this.alldata.data.push(res.data)
				this.s("New Franchises has been created")
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
		async updateData(item,index){
            this.clearErrorData()
            if(!this.validateData()) return 
			this.isLoading = true
			const res = await this.callApi('post', '/usermodule/updateFranchises', this.form)
			if(res.status == 200){
				this.form._index = this.editIndex
				this.form._rowKey =this._rowKey
				this.alldata[this.editIndex] = _.cloneDeep(this.form)
				this.s("Franchises information has been updated!")
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
		openEditModal(item){
			this.clearErrorData()
			this.editIndex = item._index
			this._rowKey = item._rowKey
			delete item._rowKey
			delete item._index
			
			console.log(item)
			this.form = _.cloneDeep(item)
			this.modalOn = true;
		},
		openCreateModal(){
			this.clearErrorData()
			this.createModal = true
		},
		async getallFrachise(){
			const res = await this.callApi('get', `/usermodule/allfranchises`);
			if(res.status==200){
				this.alldata = res.data
				this.alldata.total = res.total
			} 
		}
	},
    async created(){
		this.getallFrachise()
    },
    
}
</script>
