<template>
    <div class="_main_content">
		<div class="row"> 
            <div class="col-12 col-md-12 col-lg-12">
				<div class="_box _b_radious3 _padd20">
                    <div class="_1card_top _mar_b20"> 
							<div class="_1card_top_left">
								<p class="_1card_top_title">Franchise lists</p>
								<div class="_1card_top_search">	  
									<Input search enter-button="Search" @on-change="serchResetlt" v-model="str2" placeholder="Enter something..." />
								</div>
							</div>
							<div class="_1card_top_right">
								<ul class="_1card_top_right_list">
									<li>
										<Button type="primary" @click="$router.push('/franchisemodule/addFranchise')">Add</Button>
										<!-- <Button type="primary" @click="openCreateModal">Add</Button> -->
									</li>
									
								</ul>
							</div> 
						</div>
						<div class="_table_responsive">
							<Table class="_table1200" border :columns="columns" :data="alldata.data">
								<template slot="user" slot-scope="{ row }">
									<p v-if="row.user && row.user.name">{{row.user.name}}</p>
								</template>
								<template slot="phone" slot-scope="{ row }">
									<p  >{{row.countryCode}}{{row.phone}}</p>
								</template>
								<template slot="target_price" slot-scope="{ row }">
									 <p v-if="row.target_price"> {{ row.target_price }} ৳</p>
									 <p v-else> 0 ৳</p>
								</template>
								<template slot="action" slot-scope="{ row ,index}">
									<Button type="primary" @click="$router.push(`/franchisemodule/editFranchise?franchiseId=${row.id}`)">Edit</Button>
								 
									<Button type="primary" @click="$router.push(`/franchisemodule/shop-list?franchiseId=${row.id}`)">Shops</Button>
									<Button type="error" @click="deleteFranchises(row.id,index)">Delete</Button>
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
                    title: 'Franchise ID',
                    key: 'id',
                },
				{
					title: 'Franchise Name',
					key: 'name'
				},
				{
					title: 'Owner Name',
					slot: 'user'
				},
				{
					title: 'Contact',
					slot: 'phone'
				},
				{
					title: 'Address',
					key: 'address'
				},
				{
					title: 'Target Price',
					slot: 'target_price',
					width: 120
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
			   this.getAllFrachinse()
        },
		async getAllFrachinse(){
			const res = await this.callApi('get', `/franchisemodule/franchises?page=${this.page}&pageSize=${this.pageSize}&str=${this.str2}`);
			if(res.status==200){
				this.alldata.data = res.data.data
				this.alldata.total = res.data.total
			}  
		},
		async deleteFranchises(id,index){
			  if (!confirm("Are you sure to delete?")) {
                    return;
                }
			const res = await this.callApi('post', `/franchisemodule/deleteFranchises`, {id:id});
			if(res.status==200){
				this.alldata.data.splice(index, 1) 
			} 
			else if(res.status==422){ 
				this.e(res.data.message)
			}
			else this.swr()
		}
	},
    async created(){
		await this.getAllFrachinse()
     },
    
}
</script>
