<template>
    <div class="_main_content">
		<div class="row">
            <div class="col-12 col-md-12 col-lg-12">
				<div class="_box _b_radious3 _padd20">
                    <div class="_1card_top _mar_b20">
							<div class="_1card_top_left">
								<p class="_1card_top_title">Discount Announcement</p>
								<div class="_1card_top_search">
									<Input  @on-change="serchResetlt" v-model="str" search enter-button="Search" placeholder="Enter something..." />
								</div>
							</div>
							<!-- <div class="_1card_top_right">
								<ul class="_1card_top_right_list">
									<li><Button type="primary" @click="openCreateModal">Add</Button></li>
								</ul>
							</div> -->
						</div>
						<div class="_table_responsive">
							<Table class="_table700" border :columns="columns" :data="alldata.data">
								<template slot="franchise" slot-scope="{ row }" >
									<p v-if="row.franchise">{{row.franchise.name}}</p>
								</template>
								<template slot="status" slot-scope="{ row }" >
									<p v-if="row.status == 1">Active</p>
									<p v-if="row.status == 0">Deactive</p>
								</template>
								<template slot="action" slot-scope="{ row, index }">
									<Button type="primary" @click="openEditModal(row,index)">Edit</Button> 
									<!-- <Button type="error" @click="deleteAnnouncement(row,index)">Delete</Button> -->
								</template>
							</Table>
						</div>
                        <div class="_pagination _padd_t20" v-if="alldata && alldata.total!=null">
							<Page :total="alldata.total" show-sizer :page-size-opts="pageOption" @on-page-size-change="getSizeChange"  @on-change="paginateDataInfo" />
						</div>
                </div>
            </div>
        </div>
	 
		<Modal title="Edit Announcement" width="600" v-model="updateModal" :mask-closable="false" >
            <div class="row">
				<div class="col-12 col-md-12 col-lg-12 _mar_b20" v-if="user_type == 'Admin'">
                    <p class="_label _mar_b15">Select Franchise</p>
					<Select
                            v-model="form.franchise_id"
                            filterable 
                            :remote-method="allfranchises"
                         >
                          <Option v-for="(item, index) in all_franchise" :value="item.id" :key="index" >{{item.name}}</Option>
                    </Select>
                </div>
				<div class="col-12 col-md-12 col-lg-12 _mar_b20" >
                    <p class="_label _mar_b15">Status</p>
                    <Select v-model="form.status"  >
                          <Option :value="1" >Active</Option>
                          <Option :value="0" >Deactive</Option>
                    </Select>
                </div> 
                <div class="col-12 col-md-12 col-lg-12 _mar_b20">
                    <p class="_label _mar_b15">Details</p>
                    <Input v-model="form.details" type="textarea" :autosize="{minRows: 2,maxRows: 5}" placeholder="Details "/>
                </div> 
				   
            </div>
            <div slot="footer">
                <Button   @click="updateModal=false" >Cancel</Button>
                <Button type="primary"  @click="updateAnnouncement" :loading="isLoading" :disabled="isLoading">{{isLoading? 'Please wait' : 'Update'}}</Button>
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
				franchise_id:'' ,
				details:'' ,
				status:'' ,
 			},
			all_franchise:[],
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
						width: 60
					},
					{
						title: 'Franchaise Name',
						slot: 'franchise',
						width: 200

 					},				 
					{
						title: 'Announcement',
						key: 'details',
						width: 500

 					},				 
					{
						title: 'Status',
						slot: 'status',
						width: 100
 					},				 
					{
						title: 'Action',
						slot: 'action',
 					}
				],
            alldata:{
                total:0,
                data:[]
			},
			pageSize:10,
			page:1,
			str:'',
			user_type:''
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
               this.getAllDiscountAnnouncement()
        },
        async deleteAnnouncement(item,index){
			if (!confirm("Are you sure to delete?")) {
                    return;
                }
			const res = await this.callApi('post', '/productmodule/deleteDiscountAnnouncement', {id:item.id})
            if(res.status == 200){
                this.alldata.data.splice(index, 1)
                this.s("Announcement has been deleted.")
            }
            else if(res.status==422){
				this.e(res.data.message)
			}
			else {
				this.swr()
			}
        },		 
		async allfranchises(){
			const res = await this.callApi('get', `/franchisemodule/allfranchises?page=${this.page}&pageSize=${this.pageSize}`)
			if(res.status==200){
				this.all_franchise = res.data.data
 			} 
        }, 
		async updateAnnouncement(item,index){
			if(this.user_type == 'FranchiseOwner'){
				this.form.franchise_id = authUser.franchise_id
			}
			if(!this.form.franchise_id || this.form.franchise_id <= 0) return this.e("Please select a franchise.") 
			if(!this.form.details || this.form.details.trim()=='' || this.form.details == null ) return this.e("Announcement is required.") 
			if(!this.form.status<0) return this.e("Status is required.") 
 			this.isLoading = false
  			const res = await this.callApi('post', '/productmodule/updateDiscountAnnouncement', this.form)
			if(res.status == 200){
                Vue.set(this.alldata.data,this.editIndex, res.data)
                this.s("Announcement has been updated!")
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
			this.form.franchise_id = item.franchise_id
			this.form.details = item.details
			this.form.status = item.status
            this.updateModal = true; 
            this.editIndex = i
		},
		openCreateModal(){
            this.clearData()
 			this.createModal = true
        },
        clearData(){
			this.form={}
        },
        async getAllDiscountAnnouncement(){
            const res = await this.callApi('get', `/productmodule/getAllDiscountAnnouncement?page=${this.page}&pageSize=${this.pageSize}&str=${this.str}`)
			if(res.status==200){
				this.alldata = res.data
				this.alldata.total = res.data.total 
			} 
        },
       
	},
    async created(){  
		this.user_type = authUser.userType
		this.getAllDiscountAnnouncement()
     },
    
}
</script>
