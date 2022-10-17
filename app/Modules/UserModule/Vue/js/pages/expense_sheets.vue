<template>
    <div class="_main_content">
		<div class="row">
            <div class="col-12 col-md-12 col-lg-12">
				<div class="_box _b_radious3 _padd20">
                    <div class="_1card_top _mar_b20">
                        <div class="_1card_top_left">
                            <p class="_1card_top_title">Expense Sheet</p>
                            <!-- <div class="_1card_top_search">
                                <Input  @on-enter="serchResetlt" v-model="str" search enter-button="Search" placeholder="Enter something..." />
                            </div> -->
                            <div class="_1card_top_search">
                                <Select v-model="expense_category_id" filterable clearable @on-change="onCategoryChange" placeholder="Select Category">
                                    <Option v-for="(item, index) in allExpenseCategory" :value="item.id" :key="index">{{item.name}}</Option>
                                </Select>
                            </div>
                            <div class="_1card_top_search">
                                    <DatePicker type="daterange" :options="options2" placement="bottom-end" placeholder="Select date" @on-change="getDate" style="width: 200px"></DatePicker>
                            </div>
                        </div>
                        <div class="_1card_top_right">
                            <ul class="_1card_top_right_list">
                                <li><Button type="primary" @click="openCreateModal">Add</Button></li>
                            </ul>
                        </div>
					</div>
                        <div class="_1card_top _mar_b20" style="text-align: center;">
                            <div class="_box _b_radious3" style="margin: 0 auto;" >
                                <div class="_dash_card">
                                    <div class="_dash_card_icon">
                                        <Icon type="md-cash" />
                                    </div>
                                    <div class="_dash_card_details">
                                        <p class="_dash_card_amm">৳ {{totalAmount}}</p>
                                        <p class="_dash_card_title">Total Expense Amount</p>
                                    </div>
                                </div>
                            </div>
                        </div>
						<div class="_table_responsive">
							<Table class="_table700" border :columns="columns" :data="alldata.data">
								<template slot="category" slot-scope="{ row }">
									<p v-if="row.category" >{{row.category.name}}</p>
								</template>
								<template slot="action" slot-scope="{ row, index }">
									<Button type="primary" @click="openEditModal(row,index)">Edit</Button>
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
                    <FormItem prop="product_category_id" :error="error.expense_category_id" label="Category name" >
                       <Select v-model="form.expense_category_id" >
                        <Option v-for="(item, index) in allExpenseCategory" :value="item.id" :key="index">{{item.name}}</Option>
                    </Select>
                    </FormItem>
                    <FormItem  :error="error.amount" label="Amount">
                        <InputNumber :min="1" v-model="form.amount"></InputNumber>
                    </FormItem>
                    <FormItem  :error="error.remarks" label="Remarks">
                         <Input v-model="form.remarks"  type="textarea" :autosize="{minRows: 2,maxRows: 5}" placeholder="Remarks"/>
                    </FormItem>
                </Form>
            <div slot="footer">
                <Button   @click="updateModal=false" >Cancel</Button>
                <Button type="primary"  @click="updateData" :loading="isLoading" :disabled="isLoading">{{isLoading? 'Please wait' : 'Update'}}</Button>
            </div>
        </Modal>
		<Modal title="Create Category"  v-model="createModal" :mask-closable="false">
            <Form  :model="form" label-position="top" >
				<FormItem prop="product_category_id" :error="error.expense_category_id" label="Category name" >
					<Select v-model="form.expense_category_id" >
                        <Option v-for="(item, index) in allExpenseCategory" :value="item.id" :key="index">{{item.name}}</Option>
                    </Select>
                </FormItem>
                <FormItem prop="name" :error="error.amount" label="Amount">
                     <InputNumber :min="1" v-model="form.amount"></InputNumber>
                </FormItem>
                <FormItem  :error="error.remarks" label="Remarks">
                    <Input v-model="form.remarks"  type="textarea" :autosize="{minRows: 2,maxRows: 5}" placeholder="Remarks"/>
                </FormItem>
            </Form>
            <div slot="footer">
                <Button   @click="createModal=false" >Cancel</Button>
                <Button type="primary"  @click="createProductCategory" :loading="isLoading" :disabled="isLoading">{{isLoading? 'Please wait' : 'Create'}}</Button>
            </div>
        </Modal>

    </div>
</template>
<script>
export default {
    data(){
        return{
			
			allExpenseCategory:[],
			form:{
				amount:0,
				expense_category_id:-1,
                remarks:''
			},
           	error:{
				amount:'',
				expense_category_id:'',
                remarks:''
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
                },
				{
					title: 'Type',
					slot: 'category'
				},
				{
					title: 'Amount',
					key: 'amount'
				},
				{
					title: 'Remarks',
					key: 'remarks'
				},
				{
					title: 'Action',
					slot: 'action',
					width: 300
				}
			],
            options2: {
                shortcuts: [{
                        text: '1 week',
                        value() {

                            const end = new Date();
                            const start = new Date();
                            start.setTime(start.getTime() - 3600 * 1000 * 24 * 7);
                            return [start, end];
                        }
                    },
                    {
                        text: '1 month',
                        value() {
                            const end = new Date();
                            const start = new Date();
                            start.setTime(start.getTime() - 3600 * 1000 * 24 * 30);
                            return [start, end];
                        }
                    },
                    {
                        text: '3 months',
                        value() {
                            const end = new Date();
                            const start = new Date();
                            start.setTime(start.getTime() - 3600 * 1000 * 24 * 90);
                            return [start, end];
                        }
                    }
                ]
            },
            alldata:{
                total:0,
                data:[]
			},
			pageSize:10,
			page:1,
			str:'',
			expense_category_id:'',
			date_start:'',
			date_end:'',
            totalAmount:0,
        }
	},
	methods:{
        getDate(date){
            if(date){

                this.date_start = date[0]
                this.date_end = date[1]
            }
            this.getAllProductCategory();
        },
        onCategoryChange(value){
            if(value == undefined) this.expense_category_id = ''
            this.getAllProductCategory()
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
			this.error= {
				amount:'',
				remarks:'',
				expense_category_id:''
			},
			this.form={
				amount:0,
				remarks:'',
				expense_category_id:-1
			}
			
		},
		validateData(){
			let flag =1
			if(!this.form.amount || this.form.amount <=  0){
				this.error.amount = "This field is required!"
				flag =0
			}
			if(this.form.expense_category_id==null || this.form.expense_category_id==''){
				this.error.expense_category_id = "This field is required!"
				flag =0
			}
		    
			if(this.form.remarks==null || this.form.remarks.trim() == ''){
				this.error.remarks = "This field is required!"
				flag =0
			}
			return flag
		},
		clearErrorData(){
			this.error.amount =''
			this.error.expense_category_id =''
			this.error.remarks = ''
		},
		async createProductCategory(item,index){
			this.clearErrorData(); 
			if(!this.validateData()) return 
			this.isLoading = true
            let ob = {
                amount:this.form.amount,
                expense_category_id:this.form.expense_category_id,
                remarks:this.form.remarks,
                franchise_id:this.franchise_id,
            }
 			const res = await this.callApi('post', '/usermodule/addExpenseSheets', ob)
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
			const res = await this.callApi('post', '/usermodule/updateExpenseSheets', this.form)
			if(res.status == 200){
				this.form._index = this.editIndex
				this.form._rowKey =this._rowKey
                Vue.set(this.alldata.data,this.editIndex,res.data)
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
            delete this.form.category
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
			const res = await this.callApi('post', '/usermodule/deleteExpenseSheets', {id:item.id})
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
			const res = await this.callApi('get', `/usermodule/getExpenseSheets?page=${this.page}&pageSize=${this.pageSize}&str=${this.str}&franchise_id=${this.franchise_id}&expense_category_id=${this.expense_category_id}&date_start=${this.date_start}&date_end=${this.date_end}`)
			if(res.status==200){
				this.alldata = res.data.data
				this.alldata.total = parseInt(res.data.data.total)
				this.totalAmount = parseInt(res.data.total)
			} 
		},
	 	async getCategory(){
			const res = await this.callApi('get', `/usermodule/getExpenseCategory?franchise_id=${this.franchise_id}`)
            if(res.status==200){
				this.allExpenseCategory = res.data
				this.form.category_id = this.id
            }
		},


	},
    async created(){
		this.getAllProductCategory() 
		this.getCategory() 
    },
    
}
</script>
