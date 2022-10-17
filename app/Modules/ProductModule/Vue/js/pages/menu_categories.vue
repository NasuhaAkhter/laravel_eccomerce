<template>
    <div class="_main_content">
		<div class="row">
            <div class="col-12 col-md-12 col-lg-12">
				<div class="_box _b_radious3 _padd20">
                    <div class="_1card_top _mar_b20">
							<div class="_1card_top_left">
								<p class="_1card_top_title">Restaurant Menu list</p>
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
								<template slot="action" slot-scope="{ row, index }">
									<Button type="info" @click="$router.push(`/productmodule/product?menuCategoryId=${row.id}`)">Product</Button> 
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
		<Modal title="Create Manu" width="400" v-model="createModal" :mask-closable="false" >
            <div class="row">
                <div class="col-12 col-md-12 col-lg-12 _mar_b20">
                    <p class="_label _mar_b15">Name</p>
                    <Input v-model="form.category_name" type="text" placeholder="Manu name "/>
                </div>                    
            </div>
            <div slot="footer">
                <Button   @click="createModal=false" >Cancel</Button>
                <Button type="primary"  @click="createCategory" :loading="isLoading" :disabled="isLoading">{{isLoading? 'Please wait' : 'Create'}}</Button>
            </div>
        </Modal>
		<Modal title="Edit Manu" width="400" v-model="updateModal" :mask-closable="false" >
            <div class="row">
                <div class="col-12 col-md-12 col-lg-12 _mar_b20">
                    <p class="_label _mar_b15"> Name</p>
                    <Input v-model="form.category_name" type="text" placeholder="Manu name "/>
                </div> 
            </div>
            <div slot="footer">
                <Button   @click="updateModal=false" >Cancel</Button>
                <Button type="primary"  @click="updateCategory" :loading="isLoading" :disabled="isLoading">{{isLoading? 'Please wait' : 'Update'}}</Button>
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
				category_name:'' ,
				shop_id:''
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
						key: 'category_name',
 					},				 
					{
						title: 'Action',
						slot: 'action',
						width: 300
					}
			],
            alldata:{
				data:[],
				total:0
			},
			pageSize:10,
			page:1,
			str:'',
			id:0
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
               this.getAllRestaurentCategory()
        },
        async deleteCategory(item,index){
			if (!confirm("Are you sure to delete?")) {
                    return;
                }
			const res = await this.callApi('post', '/productmodule/deleteRestaurentCategory', {id:item.id})
            if(res.status == 200){
                this.alldata.data.splice(index, 1)
                this.s("Manu has been deleted.")
            }
            else if(res.status==422){
				this.e(res.data.message)
			}
			else {
				this.swr()
			}
        },		 
		async createCategory(item,index){
			this.form.shop_id = this.id
			if(!this.form.category_name || this.form.category_name.trim()=='' || this.form.category_name == null ) return this.e("Code is required.") 
 			this.isLoading = true
			const res = await this.callApi('post', '/productmodule/storeRestaurentCategory', this.form)
			if(res.status == 200 || res.status ==201){
				this.alldata.data.push(res.data)
                this.s("New Category has been created.")
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
		async updateCategory(item,index){
			this.form.shop_id = this.id
			if(!this.form.category_name || this.form.category_name.trim()=='' || this.form.category_name == null ) return this.e("Code is required.") 
 			this.isLoading = false
  			const res = await this.callApi('post', '/productmodule/updateRestaurentCategory', this.form)
			if(res.status == 200){
                Vue.set(this.alldata.data,this.editIndex,this.form)
                this.s("Manu has been updated!")
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
			this.form.category_name = item.category_name
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
        async getAllRestaurentCategory(){
            const res = await this.callApi('get', `/productmodule/getAllRestaurantCategory/${this.id}?page=${this.page}&pageSize=${this.pageSize}&str=${this.str}`)
			if(res.status==200){
				this.alldata = res.data
				// this.alldata.total = res.data.total 
			} 
        },
       
	},
    async created(){  
		this.id  = this.$route.query.shopId
		// console.log(this.id)
		this.getAllRestaurentCategory()
     },
    
}
</script>
