
<template>
    <div class="_main_content">
		<div class="row">
            <div class="col-12 col-md-12 col-lg-12">
				<div class="_box _b_radious3 _padd20">
                    <div class="_1card_top _mar_b20">
							<div class="_1card_top_left">
								<p class="_1card_top_title">Transactions</p>
								<!-- <div class="_1card_top_search">
									<Input  @on-change="serchResetlt" v-model="str" search enter-button="Search" placeholder="Enter something..." />
								</div> -->
							</div> 
							<!-- <div class="_1card_top_right">
								<ul class="_1card_top_right_list">
									<li><Button type="primary" @click="openCreateModal">Add</Button></li>
								</ul>
							</div> -->
						</div>
						<div class="_table_responsive">
							<Table class="_table700" border :columns="columns" :data="alldata.data">
								<template slot="name" slot-scope="{ row }">
									<p v-if="row.product && row.type == 'Product'">
										{{ row.product.name}}
									</p>
									<p v-else-if="row.cupon && row.type == 'Coupon'">
										{{ row.cupon.code}}
									</p>
								</template>
								<template slot="customer_name" slot-scope="{ row }">
									<p v-if="row.user && row.user.name && row.user.phone ">
										{{ row.user.name}} ( {{ row.user.phone }} )
									</p>
									 
								</template>

								<template slot="action" slot-scope="{ row, index }">
									<Button type="primary" @click="openEditModal(row,index)">Edit</Button>
 									<!-- <Button type="error" @click="deleteTransaction(row,index)">Delete</Button> -->
								</template>
							</Table>
						</div>
                        <div class="_pagination _padd_t20" >
							<Page :total="alldata.total" show-sizer :page-size-opts="pageOption" @on-page-size-change="getSizeChange"  @on-change="paginateDataInfo" />
						</div>
                </div>
            </div> 
        </div>
		<Modal title="Edit"  v-model="updateModal" :mask-closable="false" :width="500">
            <Form  :model="form" label-position="top" >
                <div class="row" >
					 
					<div class="col-md-12" >
                        <FormItem  label="Type" :error="error.type">
							<Input v-model="form.type"  type="text"   placeholder="Type"/>
                        </FormItem>
                    </div>
					<div class="col-md-12" >
                        <FormItem  label="Amount" :error="error.amount">
							<InputNumber v-model="form.amount" :min="0" ></InputNumber>
                        </FormItem>
                    </div>
				</div>
			</Form>
			<div slot="footer" >
				<Button type="warning" @click="updateModal= false">Cancel</Button>
				<Button type="primary" @click="updateTransaction" :loading="isLoading" :disabled="isLoading">{{isLoading?'Updating...':'Update'}}</Button>
			</div>
		</Modal>
    </div>
</template>
<script>
export default {
    data(){
        return{
			id:0,
			category:{},
			imageModal:false,
			singleImage:'',
			uploadErrorText:'',
			form:{
                amount:'',
				type:'',
				id:-1
			},
			error:{
                amount:'',
				type:'',
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
					title: 'Name',
					slot: 'customer_name',
					width: 250

				},
				{
					title: 'Item',
					slot: 'name'
				},
				{
					title: 'Amount',
					key: 'amount',
 				},
				{
					title: 'Type',
					key: 'type',
 				},
				{
					title: 'Order Id',
					key: 'order_id',
 				},
				{
					title: 'Action',
					slot: 'action',
					width: 200
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
		async openEditModal(item, index){
 			this.editIndex = index
			this.form.amount =item.amount
			this.form.type =item.type
			this.form.id =item.id
 			this.updateModal = true
		},
		validateData(){
			let flag =1
			if(this.form.type==null || this.form.type.trim()==''){
				this.error.type = "This field is required!"
				flag =0
			}
			if(!this.form.amount){
				this.error.amount = "This field is required!"
				flag =0
			}
			return flag
		},
		async updateTransaction( ){
			if(!this.validateData()) return 
  			const res = await this.callApi('post', '/usermodule/updateTransaction',this.form)
			if(res.status == 200){
				this.s("Transaction has been updated successfully.")
				this.alldata.data[this.editIndex].amount = this.form.amount
				this.alldata.data[this.editIndex].type = this.form.type
			}
			else if(res.status==422){
				this.e(res.data.message)
			}
			else {
				this.swr()
			}
			this.updateModal = false
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
			this.getAllTransaction()
		},
		 
        async getAllTransaction(){
            const res = await this.callApi('get', `/usermodule/getAllTransaction/${this.$route.params.id}?page=${this.page}&pageSize=${this.pageSize}&str=${this.str}`)
			if(res.status==200){
				this.alldata = res.data
				// this.alldata.total = res.total
			} 
        }
	},
    async created(){
 		this.id  = this.$route.params.id
  		this.getAllTransaction() 
    },
    
}
</script>
