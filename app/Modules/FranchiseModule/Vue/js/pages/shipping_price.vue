<template>
    <div class="_main_content">
		<div class="row"> 
            <div class="col-12 col-md-12 col-lg-12">
				<div class="_box _b_radious3 _padd20">
                    <div class="_1card_top _mar_b20">
							<div class="_1card_top_left">
								<p class="_1card_top_title">Shipping Prices</p>
								<div class="_1card_top_search">	
									<Input search enter-button="Search" @on-change="serchResetlt" v-model="str2" placeholder="Enter something..." />
								</div>
							</div>  
							<div class="_1card_top_right">
								<ul class="_1card_top_right_list">
									<li>
										<!-- <Button type="primary" @click="$router.push('/franchisemodule/addshippingPrice')">Add</Button> -->
 									</li>
								</ul>
							</div>
						</div>
						<div class="_table_responsive">
							<Table class="_table1200" border :columns="columns" :data="alldata.data">
								<template slot="franchise" slot-scope="{ row }">
									<p >{{row.name}}</p>
								</template>								 
								<template slot="action" slot-scope="{ row }">
									<Button type="primary" @click="$router.push(`/franchisemodule/editShippingPrice/${row.id}`)">Edit</Button> 
								</template>								 
							</Table>
						</div>

                        <div class="_pagination _padd_t20" v-if="alldata && alldata.total!=null">
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
            pageOption:[5,10,15,20],
            columns: [
				 {
                    title: 'ID',
					key: 'id',
					width:60
                },
				{
					title: 'Franchise Name',
					slot: 'franchise'
				},
 				{
					title: 'Distance',
					key: 'sdistance'
				},
				{
					title: 'Price',
					key: 'price'
				},
				{
					title: 'Outside Price',
					key: 'outside_price'
				},
				{
					title: 'Action',
					slot: 'action'
				},
				
				 
			],
            alldata:{
                total:0,
                data:[]
			},
			pageSize:10,
			page:1,
			str2:'',
			userIndex:''
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
			   this.getAllShippingPrice()
        },
		async getAllShippingPrice(){
			const res = await this.callApi('get', `/franchisemodule/shippingPrice?page=${this.page}&pageSize=${this.pageSize}&str=${this.str2}`);
			if(res.status==200){
				this.alldata.data = res.data.data
				this.alldata.total = res.data.total
			} 
		},
	},
    async created(){
		await this.getAllShippingPrice()
     },
    
}
</script>
