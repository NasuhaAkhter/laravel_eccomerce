
<template>
    <div class="_main_content">
		<div class="row">
            <div class="col-12 col-md-12 col-lg-12">
				<div class="_box _b_radious3 _padd20">
                    <div class="_1card_top _mar_b20">
							<div class="_1card_top_left">
								<p class="_1card_top_title">Delivery Address</p>
								<!-- <div class="_1card_top_search">
									<Input  @on-change="serchResetlt" v-model="str" search enter-button="Search" placeholder="Enter something..." />
								</div> -->
							</div> 
							<div class="_1card_top_right">
								<ul class="_1card_top_right_list">
									<li><Button type="primary" @click="$router.push(`/usermodule/addAddress?userId=${id}`)">Add</Button></li>
								</ul>
							</div>
						</div>
						<div class="_table_responsive">
							<Table class="_table700" border :columns="columns" :data="alldata.data">
								<template slot="phone" slot-scope="{ row }">
                                        <p  >{{row.countryCode}}{{row.phone}}</p>
                                </template>
								<template slot="action" slot-scope="{ row, index }">
									<Button type="primary" @click="$router.push(`/usermodule/editAddress?userId=${id}&address_id=${row.id}`)">Edit</Button>
 									<Button type="error" @click="deleteAddress(row.id,index)">Delete</Button>
								</template>
							</Table>
						</div>
                        <div class="_pagination _padd_t20" >
							<Page :total="alldata.total" show-sizer :page-size-opts="pageOption" @on-page-size-change="getSizeChange"  @on-change="paginateDataInfo" />
						</div>
                </div>
            </div>
        </div>
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
				item_id:-1
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
                    width:100
                },
				{
					title: 'Address',
                    key: 'address',
                    // width: 250
				},
				{
					title: 'Country',
                    key: 'country',
                    width:120
 				},
				{
					title: 'City',
                    key: 'city',
                    width:120
 				},
				{
					title: 'Phone',
                    slot: 'phone',
                    width:150
 				},
				{
					title: 'Apartment Number',
                    key: 'apartment_number',
                    width :180
 				},
				{
					title: 'Action',
					slot: 'action',
					// width: 180
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
			this.getDeliveryAddressById()
		},
		openCreateModal(){
			this.clearData()
			this.createModal = true
		},
		clearData(){ 
			this.form={}
        },
        async deleteAddress(id,index){
            if (!confirm("Are you sure to delete this address?")) {
                    return;
                }
			const res = await this.callApi('post', `/usermodule/deleteAddress`, {id:id});
			if(res.status==200){
				this.alldata.data.splice(index, 1) 
			} 
			else if(res.status==422){ 
				this.e(res.data.message)
			}
			else this.swr()
        },
        async getDeliveryAddressById(){
            const res = await this.callApi('get', `/usermodule/getDeliveryAddress/${this.$route.params.id}?page=${this.page}&pageSize=${this.pageSize}&str=${this.str}`)
			if(res.status==200){
				this.alldata = res.data
				this.alldata.total = res.total
			} 
        }
	},
    async created(){
 		this.id  = this.$route.params.id
  		this.getDeliveryAddressById() 
    },
    
}
</script>
