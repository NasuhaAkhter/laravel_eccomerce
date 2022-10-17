<template>
    <div class="_main_content">
		<div class="row">
            <div class="col-12 col-md-12 col-lg-12">
				<div class="_box _b_radious3 _padd20">
                    <div class="_1card_top _mar_b20">
							<div class="_1card_top_left">
								<p class="_1card_top_title">New Member Request</p>

								<div class="_1card_top_search">
 									<Select @on-change="filterResult" v-model="str" placeholder="Filter by status " filterable clearable>
 										<Option value="Approved">Approved</Option>
										<Option value="Canceled">Canceled</Option>
 									</Select> 
								</div>
							</div>
 
						</div>
						<div class="_table_responsive">
							<Table class="" border :columns="columns" :data="alldata.data">
								<template slot="user" slot-scope="{ row }">
									<p  v-if="row.user" >{{row.user.name }} ( {{row.user.phone}} )</p>
								</template>
								<template slot="franchise" slot-scope="{ row }">
									<p v-if="row.franchise" >{{row.franchise.name}}</p>
								</template>
                                <template slot-scope="{row,index}" slot="status">
                                    <template v-if="status && index == statusIndex">
                                        <span class="dropdown_div"> 
                                            <Select v-model="statusOb.status" filterable>
                                                <Option v-for="(item, index) in statuses" :value="item" :key="index" >{{item}}</Option>
                                            </Select>
                                            <Icon @click="statusIndex=-1" style="cursor:pointer" type="ios-close-circle" />
                                            <span @click="updateOrderStatus(row)" style="cursor:pointer" ><Icon type="md-checkbox" /></span>
                                        </span>
                                    </template>
                                    <template v-else>
                                        <!-- <Button v-if="authInfo.userType != 'ShopOwner'" :type="[row.status=='Approved' ? 'success' : '' ]" @click="clickStatusOn(row.status,index)">
                                            
                                         {{row.status}}
                                        </Button> -->
                                        <Button :type="[row.status=='Approved' ? 'success' : 'info']"
                                         > {{row.status}}</Button>                          
                           				<Button @click="openStatusModal(row.status,index)" type="primary"> <Icon type="md-open" /> Change</Button>


                                    </template>
                                </template>
							</Table>
						</div>
						<div class="_pagination _padd_t20" v-if="alldata && alldata.total!=null">
							<Page :total="alldata.total" show-sizer :page-size-opts="pageOption" @on-page-size-change="getSizeChange"  @on-change="paginateDataInfo" />
						</div>
                </div>
            </div>
        </div>
		<Modal title="Update Status"  v-model="statusModal" :mask-closable="false">
            <Form  label-position="top" >
				<FormItem  label="Status" >
                    <Select v-model="statusOb.status" filterable>
                        <Option v-for="(item, index) in statuses" :value="item" :key="index" >{{ item }}</Option>
                    </Select> 
                </FormItem>
            </Form>
            <div slot="footer">
                 <Button type="primary" @click="updateOrderStatus"  :loading="isLoading" :disabled="isLoading">{{isLoading? 'Please wait' : 'Update'}}</Button>
                <Button   @click="statusModal = false" >close</Button>
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
			statusModal:false,
			modalOn:false,
			isLoading:false,
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
					title: 'User',
					slot: 'user',
					// width: 250
				},
				{
					title: 'Franchise',
					slot: 'franchise',
					// width: 200
				},
				{
					title: 'Package Name',
					key: 'package',
					// width: 200
				},
				{
					title: 'Payment Type',
					key: 'payment',
					// width: 150
				},
				{
					title: 'Status',
					slot: 'status',
					width: 300
				},
			],
            alldata:{
                total:0,
                data:[]
			},
			str:'',
			page:1,
			pageSize:10,
			userType:[],
			statusIndex:-1,
            status:false,
            status_change:{
                status:'',
                id:''
            },
            statuses:['Pending','Approved','Canceled'],
            // shop_statuses:['Processing','Ready to Pick'],
            statusOb:{
				id:0,
				user_id:'',
				package:'',
                status:'',
            },
        }
	}, 
	methods:{
		openStatusModal(status,index){
            this.statusOb.status = status
            this.statusIndex = index
            this.statusModal = true
        },
		async updateOrderStatus(){
		    this.statusOb.id = this.alldata.data[this.statusIndex].id
            this.statusOb.package =this.alldata.data[this.statusIndex].package
            this.statusOb.user_id =this.alldata.data[this.statusIndex].user_id
            this.isLoading = true
            const res  = await this.callApi('post','/usermodule/updatePlanRequestStatus',this.statusOb)
            if(res.status == 200){
				this.alldata.data[this.statusIndex].status = this.statusOb.status
                this.s("Status has been updated")
				this.statusIndex = -1
                this.statusModal = false
            }
            else this.swr();
             this.isLoading = false
        },
		 
		filterResult(){
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
			const res = await this.callApi('get', `/usermodule/getAllPlanRequest?page=${this.page}&pageSize=${this.pageSize}&str=${this.str}`)
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
