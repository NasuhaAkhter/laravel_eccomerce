<template>
    <div class="_main_content">
		<div class="row">
            <div class="col-12 col-md-12 col-lg-12">
				<div class="_box _b_radious3 _padd20">
                    <div class="_1card_top _mar_b20">
							<div class="_1card_top_left">
								<p class="_1card_top_title">User Balance</p>
								<div class="_1card_top_search">
									<Input @on-change="serchResetlt" v-model="str" search enter-button="Search" placeholder="Enter something..." />
								</div>
							</div>
							<!-- <div class="_1card_top_right">
								<ul class="_1card_top_right_list">
									<li><Button type="primary" @click="openCreateModal">Add Balance</Button></li>
								</ul>
							</div> -->
						</div>
						<div class="_table_responsive">
							<Table class="" border :columns="columns" :data="alldata.data">
								<template slot="duare_points" slot-scope="{ row }" >
									<p v-if="row">${{row.duare_points}}</p>
								</template>
								<template slot="transaction" slot-scope="{ row , index }" >
									<Button type="primary" @click="$router.push('/usermodule/transaction/'+row.id)">Transaction</Button>
									<Button type="primary" @click="openCreateModal(index)">Add Balance</Button>
								</template>
								<template slot="action" slot-scope="{ row, index }">
									<Button type="primary" @click="openEditModal(row,index)">Edit</Button>
									<Button type="error" @click="deleteCategory(row,index)">Delete</Button>
								</template>
							</Table>
						</div>
                        <div class="_pagination _padd_t20" >
							<Page :total="alldata.total" show-sizer :page-size-opts="pageOption"  @on-page-size-change="getSizeChange"  @on-change="paginateDataInfo"/>
						</div>
                </div>
            </div>
        </div>
		<Modal title="Add Points"  v-model="createModal" :mask-closable="false" :width="500">
            <Form  :model="form" label-position="top" >
                <div class="row" >
					<div class="col-md-12" >
                        <FormItem   label="Customer Name">
                            <Input v-model="formValue.name" disabled />
                        </FormItem>
                    </div>
					<div class="col-md-12" >
                        <FormItem  label="Current Balance">
							 <InputNumber v-model="formValue.duare_points" :disabled="true"></InputNumber>
                        </FormItem>
                    </div>
					<div class="col-md-12" >
                        <FormItem  label="Add Amount">
							<InputNumber v-model="formValue.add_points" :min="0" ></InputNumber>
                        </FormItem>
                    </div>
				</div>
			</Form>
			<div slot="footer" >
				<Button type="warning" @click="createModal= false">Cancel</Button>
				<Button type="primary" @click="insertPoints" :loading="isLoading" :disabled="isLoading">{{isLoading?'Adding...':'Add'}}</Button>
			</div>
		</Modal>
    </div>
</template>


<script>
export default {
    data(){
        return{
			form:{},
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
					key: 'name',
 				},
				{
					title: 'Phone',
					key: 'phone',
 				},
				{
					title: 'User Balance',
					slot: 'duare_points',
 				},
				{
					title: 'Transaction',
					slot: 'transaction',
 				},
				// {
				// 	title: 'Action',
				// 	slot: 'action',
 				// }
			],
            alldata:{
                total:0,
                data:[]
			},
			str:'',
			userIndex:'',
			page:1,
			users:[],
			pageSize:10,
			formValue:{
				user_id:-1,
				name:'',
				duare_points:0,
				add_points:0,
				index:-1,
			}
        }
	},
	methods:{
		openCreateModal(index){
			this.formValue ={
				user_id:this.alldata.data[index].id,
				duare_points: parseInt(this.alldata.data[index].duare_points),
				name:this.alldata.data[index].name,
				add_points:0,
				index:index
			};
		
			this.createModal = true;

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
			this.getAlluserBalance()
		},

		clearData(){
			this.form={}
        },
        async getAlluserBalance(){
			const res = await this.callApi('get', `/usermodule/getAlluserBalance?page=${this.page}&pageSize=${this.pageSize}&str=${this.str}`)
			if(res.status==200){
				this.alldata = res.data
				// this.alldata.total = res.total
			} 
        }, 
        async insertPoints(){
			this.isLoading = true;
			const res = await this.callApi('post', `/usermodule/insertPoints`, this.formValue)
			if(res.status==200){
				Vue.set(this.alldata.data,this.formValue.index,res.data)
			}
			this.isLoading = false;
			this.createModal = false;
        },
	},
    async created(){
		this.getAlluserBalance()
    },
}
</script>
