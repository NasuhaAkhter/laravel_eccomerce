<template>
    <div class="_main_content">
		<div class="row">
            <div class="col-12 col-md-12 col-lg-12">
				<div class="_box _b_radious3 _padd20">
                    <div class="_1card_top _mar_b20">
							<div class="_1card_top_left">
								<p class="_1card_top_title">Coupon list</p>
								<div class="_1card_top_search">
									<Input  @on-change="serchResetlt" v-model="str" search enter-button="Search" placeholder="Enter something..." />
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
								<template slot="discount" slot-scope="{ row }">
									 <p v-if="row.validity_type == 1"> {{ row.discount }}%</p>
									 <p v-else-if="row.validity_type == 2"> {{ row.discount }} ৳</p>
								</template>
								<template slot="target_price" slot-scope="{ row }">
									 <p v-if="row.target_price"> {{ row.target_price }} ৳</p>
									 <p v-else> 0 ৳</p>
								</template>
								<template slot="category" slot-scope="{ row }">
									 <p v-if="row.category"> {{ row.category.name }}</p>
								</template>
								<template slot="type" slot-scope="{ row }">
									 <p v-if="row.type == 'all'"> All </p>
									 <p v-else-if="row.type == 'user'"> Specific User </p>
								</template>
								<template slot="discount_type" slot-scope="{ row }">
									 <p v-if="row.discount_type == 1"> Recurring </p>
									 <p v-else-if="row.discount_type == 2"> One Time </p>
								</template>
							
								<template slot="action" slot-scope="{ row, index }">
									<Button type="primary" @click="openEditModal(row,index)">Edit</Button> 
									<Button type="error" @click="deleteCupon(row,index)">Delete</Button>
								</template>
							</Table>
						</div>
                        <div class="_pagination _padd_t20" v-if="alldata && alldata.total!=null">
							<Page :total="alldata.total" show-sizer :page-size-opts="pageOption" @on-page-size-change="getSizeChange"  @on-change="paginateDataInfo" />
						</div>
                </div>
            </div>
        </div>
		<Modal title="Create cupon" width="800" v-model="createModal" :mask-closable="false" >
            <div class="row">
                <div class="col-12 col-md-6 col-lg-6 _mar_b20">
                    <p class="_label _mar_b15">Code</p>
                    <Input v-model="form.code" type="text" placeholder="Code "/>
                </div>
				<div class="col-12 col-md-6 col-lg-6 _mar_b20">
                	<p class="_label _mar_b15">Category Name</p>
					<Select v-model="form.category_id" filterable >
						<Option v-for="(item, index) in categories" :value="item.id" :key="index" >{{item.name}}</Option>
					</Select>
				</div>
                <div class="col-12 col-md-6 col-lg-6 _mar_b20" >
                    <p class="_label _mar_b15">User Type</p>
                    <Select
                            v-model="form.type"
                        >
                     <Option value="user" >For specific user</Option>
					 <Option value="all" >For all user</Option>

                    </Select>
                </div>   
                <div class="col-12 col-md-6 col-lg-6 _mar_b20" v-if="form.type == 'user'"  >
                    <p class="_label _mar_b15">Users</p>
                    <Select v-model="form.user_list" filterable multiple :remote-method="getAllUsers">
                          <Option v-for="(item, index) in user_list" :value="item.id" :key="index" >{{item.name}}</Option>
                    </Select>
                </div>  
                <div class="col-12 col-md-6 col-lg-6 _mar_b20" >
                    <p class="_label _mar_b15">Discount Amount Type</p>
                    <Select v-model="form.validity_type">
                          <Option :value="1" >Percentage</Option>
                          <Option :value="2" >Amount</Option>
                    </Select>
                </div>  
                <div class="col-12 col-md-6 col-lg-6 _mar_b20" v-if="form.validity_type == 1" >
                    <p class="_label _mar_b15">Percentage</p>
                    <InputNumber style="width:100%"  placeholder="0%" :max="100" :min="0" 
					:formatter="value => `${value}%`"
                    :parser="value => value.replace('%', '')"
                    v-model="form.discount" >
                    </InputNumber>
                </div>  
                <div class="col-12 col-md-6 col-lg-6 _mar_b20" v-if="form.validity_type == 2" >
                    <p class="_label _mar_b15">Amount</p>
                    <InputNumber style="width:100%"  :min="0"
					:formatter="value => `${value} ৳`"
                    :parser="value => value.replace('', ' ৳ ')"
					  v-model="form.discount" ></InputNumber>
                </div> 
				<div class="col-12 col-md-6 col-lg-6 _mar_b20" >
                    <p class="_label _mar_b15">Coupon Type</p>
                    <Select v-model="form.discount_type"  > 
                          <Option :value="1" >Recurring</Option>
                          <Option :value="2" >One Time</Option>
                    </Select>
                </div>   
				<div class="col-12 col-md-6 col-lg-6 _mar_b20" >
                    <p class="_label _mar_b15">Validity</p>
					<DatePicker type="date" v-model="form.validity" @on-change="formatDate" placeholder="Select date" ></DatePicker>
                </div>
				<div class="col-12 col-md-6 col-lg-6 _mar_b20"  >
					<p class="_label _mar_b15">Target Price</p>
					<InputNumber style="width:100%" 
					 :min="0" v-model="form.target_price" 
					 :formatter="value => `${value} ৳`"
                     
					 ></InputNumber>
				</div>
				<div class="col-12 col-md-12 col-lg-12 _mar_b20">
                	<p class="_label _mar_b15">Announcement</p>
					<Input v-model="form.details"  placeholder="Announcement"><span slot="prepend">Use {{form.code}} </span></Input>
				</div>   
            </div>
            <div slot="footer">
                <Button   @click="createModal=false" >Cancel</Button>
                <Button type="primary"  @click="createCupon" :loading="isLoading" :disabled="isLoading">{{isLoading? 'Please wait' : 'Create'}}</Button>
            </div>
        </Modal>
		<Modal title="Edit cupon" width="800" v-model="updateModal" :mask-closable="false" >
            <div class="row">
                <div class="col-12 col-md-6 col-lg-6 _mar_b20">
                    <p class="_label _mar_b15">Code</p>
                    <Input v-model="form.code" type="text" placeholder="Code "/>
                </div>
				<div class="col-12 col-md-6 col-lg-6 _mar_b20">
                	<p class="_label _mar_b15">Category Name</p>
					<Select v-model="form.category_id" filterable >
						<Option v-for="(item, index) in categories" :value="item.id" :key="index" >{{item.name}}</Option>
					</Select>
				</div>
                <div class="col-12 col-md-6 col-lg-6 _mar_b20" >
                    <p class="_label _mar_b15">User Type</p>
                    <Select
                            v-model="form.type"
                        >
                     <Option value="user" >For specific user</Option>
					 <Option value="all" >For all user</Option>

                    </Select>
                </div>  
                <div class="col-12 col-md-6 col-lg-6 _mar_b20" v-if="form.type == 'user'"  >
                    <p class="_label _mar_b15">Users</p>
                    <Select
                            v-model="form.user_list"
                            filterable multiple
                            :remote-method="getAllUsers"
                         >
                          <Option v-for="(item, index) in user_list" :value="item.id" :key="index" >{{item.name}}</Option>
                    </Select>
                </div>  
                <div class="col-12 col-md-6 col-lg-6 _mar_b20" >
                    <p class="_label _mar_b15">Discount Amount Type</p>
                    <Select
                            v-model="form.validity_type"
                        >
                          <Option :value="1" >Percentage</Option>
                          <Option :value="2" >Amount</Option>
                    </Select>
                </div>  
                <div class="col-12 col-md-6 col-lg-6 _mar_b20" v-if="form.validity_type == 1" >
                    <p class="_label _mar_b15">Percentage</p>
                    <InputNumber style="width:100%"  placeholder="0%" :max="100" :min="0" :formatter="value => `${value}%`"
                    :parser="value => value.replace('%', '')"
                    v-model="form.discount" >
                    </InputNumber>
                </div>  
                <div class="col-12 col-md-6 col-lg-6 _mar_b20" v-if="form.validity_type == 2" >
                    <p class="_label _mar_b15">Amount</p>
                    <InputNumber style="width:100%"  placeholder="$0.00" :max="100" :min="0"
					 :formatter="value => `${value} ৳`"
                    :parser="value => value.replace('', ' ৳ ')"
                    v-model="form.discount" >
                    </InputNumber>
                </div> 
				<div class="col-12 col-md-6 col-lg-6 _mar_b20" >
                    <p class="_label _mar_b15">Coupon Type</p>
                    <Select v-model="form.discount_type"  >
                          <Option :value="1" >Recurring</Option>
                          <Option :value="2" >One Time</Option>
                    </Select>
                </div>   
				<div class="col-12 col-md-6 col-lg-6 _mar_b20"  >
                    <p class="_label _mar_b15">Validity</p>
					<DatePicker type="date" @on-change="formatDate" v-model="form.validity" placeholder="Select date" ></DatePicker>
                </div>
				<div class="col-12 col-md-6 col-lg-6 _mar_b20"  >
					<p class="_label _mar_b15">Target Price</p>
					<InputNumber style="width:100%" 
					:formatter="value => `${value} ৳`"
                     
					:min="0" v-model="form.target_price" ></InputNumber>
				</div>
				<div class="col-12 col-md-12 col-lg-12 _mar_b20">
                	<p class="_label _mar_b15">Announcement</p>
					<Input v-model="form.details"  placeholder="Announcement"><span slot="prepend">Use {{form.code}} </span></Input>
				</div>   
            </div>
            <div slot="footer">
                <Button   @click="updateModal=false" >Cancel</Button>
                <Button type="primary"  @click="updateCupon" :loading="isLoading" :disabled="isLoading">{{isLoading? 'Please wait' : 'Update'}}</Button>
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
				code:'',
				category_id:'',
				details:'',
                target_price:0,
                discount:0,
				validity:'',
				user_list:[],
                type:'',
                validity_type:'',
                discount_type:''
 			},
           	error:{
				code:'',
				category_id:'',
				details:'',
                discount:'',
				validity:'',
				user_list:'',
                type:'',
                validity_type:'',
                discount_type:'',
            },
			updateModal:false,
			isLoading:false,
			createModal:false,
			editIndex:-1,
			_rowKey:-1,
            pageOption:[5,10,15,20],
            columns: [
				    {
						title: 'Id',
						sortable: true,
						key: 'id',
						width: 100
					},
					{
						title: 'Code',
						key: 'code',
						width: 120
					},
					{
						title: 'Discount',
						slot: 'discount',
						width: 120
 					},
					{
						title: 'Category',
						slot: 'category',
						width: 120
 					},
					{
						title: 'Announcement',
						key: 'details',
						width: 170
 					},
					{
						title: 'Validity',
						key: 'validity',
						width: 120
 					},
					{
						title: 'Target Price',
						slot: 'target_price',
						width: 120
 					},
					{
						title: 'Type',
						key: 'type',
						width: 120
 					},
					{
						title: 'Discount Type',
						slot: 'discount_type',
						width: 150
 					},
					{
						title: 'Action',
						slot: 'action',
						// width: 300
					}
				],
            alldata:{
                total:0,
                data:[]
			},
			pageSize:10,
			page:1,
			str:'',
			user_list:[],
			categories:[],
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
			   this.getAllCupon()
        },
		clearData(){
			this.error= {
				code:'',
                discount:'',
				validity:'',
				user_list:'',
                type:'',
                validity_type:'',
                discount_type:''
			},
			this.form={
				code:'',
                discount:0,
				validity:'',
				user_list:[],
                type:'',
                validity_type:'',
                discount_type:''
			}
        },
        async deleteCupon(item,index){
			if (!confirm("Are you sure to delete?")) {
                    return;
                }
			const res = await this.callApi('post', '/productmodule/deleteCupon', {id:item.id})
            if(res.status == 200){
                this.alldata.data.splice(index, 1)
                this.s("Cupon has been deleted.")
            }
            else if(res.status==422){
				this.e(res.data.message)
			}
			else {
				this.swr()
			}
        },
		validateData(){
			let flag =1
			if(!this.form.code || this.form.code.trim()==''){
				this.error.code = "This field is required!"
				flag =0
			}
			 
			return flag
		},
		clearErrorData(){
			this.error= {
                code:'',
                discount:'',
				validity:'',
				user_list:'',
                type:'',
                validity_type:'',
                discount_type:'',
                category_id:'',
                details:''
            }
		},
		formatDate(e){
            this.form.validity = e
        },
		async createCupon(item,index){
			if(!this.form.code || this.form.code.trim()=='' || this.form.code == null ) return this.e("Code is required.") 
			if(this.form.category_id =='' ) return this.e("Category is required.") 
			if(!this.form.details || this.form.details.trim()=='' || this.form.details == null ) return this.e("Announcement is required.") 
			if(!this.form.type || this.form.type=='' || this.form.type==null) return this.e("Type is required.") 
 			if(this.form.type == 'user'){
				if(!this.user_list.length || this.user_list.length<=0) return this.e("Please select an user.") 
			}
			if(!this.form.validity_type || this.form.validity_type=='' || this.form.validity_type==null) return this.e("Type is required.") 
			if(!this.form.discount || this.form.discount=='' || this.form.discount==null) return this.e("Discount is required.") 
			if(!this.form.discount_type || this.form.discount_type=='' || this.form.discount_type==null) return this.e("Discount type is required.")
			if(!this.form.validity || this.form.validity =='' || this.form.validity == null ) return this.e("Validity is required.") 
			this.isLoading = true
			if(!this.form.user_list){
				this.form.user_list = [];
			}
			this.form.franchise_id = this.franchise_id;
			const res = await this.callApi('post', '/productmodule/storeCupon', this.form)
			if(res.status == 200 || res.status ==201){
				this.alldata.data.unshift(res.data)
                this.s("New cupon has been created.")
                this.user_list =[]
                this.createModal = false
			}
			else if(res.status==422){
				this.e(res.data.message)
			}
			else {
				this.swr()
			}
			this.createModal = false;
			this.isLoading = false		
		},
		async updateCupon(item,index){
			if(!this.form.code || this.form.code.trim()=='' || this.form.code == null ) return this.e("Code is required.") 
			if(!this.form.type || this.form.type=='' || this.form.type==null) return this.e("Type is required.") 
 			if(this.form.type == 'user'){
				if(!this.user_list.length || this.user_list.length<=0) return this.e("Please select an user.") 
			}
			if(this.form.category_id =='' ) return this.e("Category is required.") 
			if(!this.form.details || this.form.details.trim()=='' || this.form.details == null ) return this.e("Announcement is required.") 
			if(!this.form.validity_type || this.form.validity_type=='' || this.form.validity_type==null) return this.e("Type is required.") 
			if(!this.form.discount || this.form.discount=='' || this.form.discount==null) return this.e("Discount is required.") 
			if(!this.form.discount_type || this.form.discount_type=='' || this.form.discount_type==null) return this.e("Discount type is required.")
			if(!this.form.validity || this.form.validity =='' || this.form.validity == null ) return this.e("Validity is required.") 
			this.isLoading = false
			if(!this.form.user_list){
				this.form.user_list = [];
			}
			if(this.form.validity){
                    let item2 =new Date(this.form.validity) 
                    let month = '' + (item2.getMonth() + 1),
                    day = '' + item2.getDate(),
                    year = item2.getFullYear();
                    if (month.length < 2) 
                        month = '0' + month;
                    if (day.length < 2) 
                        day = '0' + day;
                    this.form.validity =[year, month, day].join('-')
            }else{
                this.form.validity = '';
			}
			this.editIndex  = index
  			const res = await this.callApi('post', '/productmodule/updateCupon', this.form)
			if(res.status == 200){
				// Vue.set(this.alldata.data, this.editIndex, this.form)
				// console.log(this.alldata.data[this.editIndex] )
				this.s("Cupon has been updated!")
				window.location = '/productmodule/cupon'
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
			this.form.id = item.id
			this.form.code = item.code
			this.form.discount = item.discount
			this.form.validity = item.validity
 			this.form.type = item.type
			this.form.discount_type = item.discount_type
			this.form.validity_type = item.validity_type
			this.form.category_id = item.category_id
			this.form.details = item.details
			this.updateModal = true; 
		},
		openCreateModal(){
			this.clearData()
			this.createModal = true
        },
        async getAllCupon(){
            const res = await this.callApi('get', `/productmodule/getAllCupon?page=${this.page}&pageSize=${this.pageSize}&str=${this.str}`)
			if(res.status==200){
				this.alldata = res.data
				// this.alldata.total = res.data.total 
			} 
        },
        async getAllUsers(query){
 			if(query){
				this.str = query
			}
            const res = await this.callApi('get', `/usermodule/getAllUsers?page=${this.page}&pageSize=${this.pageSize}&str=${this.str}`)
			if(res.status==200){
				this.user_list = res.data.data
				// this.alldata.total = res.data.total 
			} 
        },
		async getAllCategory(){
            const res = await this.callApi('get', `/productmodule/getAllCategory`)
            if(res.status==200){
                this.categories = res.data.data.data
            } 
        },
	},
    async created(){
		this.getAllCupon()
		this.getAllUsers()
		 this.getAllCategory()
    },
    
}
</script>
