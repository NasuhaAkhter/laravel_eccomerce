<template>
    <div class="_main_content">
		<div class="row">
            <div class="col-12 col-md-12 col-lg-12">
				<div class="_box _b_radious3 _padd20">
                    <div class="_1card_top _mar_b20">
							<div class="_1card_top_left">
								<p class="_1card_top_title">Expense Category list</p>
							</div>
							<div class="_1card_top_right">
								<ul class="_1card_top_right_list">
									<li><Button type="primary" @click="openCreateModal">Add</Button></li>
								</ul>
							</div>
						</div>
						<div class="_table_responsive">
							<Table class="_table700" border :columns="columns" :data="alldata">
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
                <FormItem prop="name" :error="error.name"    label="Category name">
                     <Input v-model="form.name"  />
                </FormItem>
            </Form>
            <div slot="footer">
                <Button   @click="updateModal=false" >Cancel</Button>
                <Button type="primary"  @click="updateData" :loading="isLoading" :disabled="isLoading">{{isLoading? 'Please wait' : 'Update'}}</Button>
            </div>
        </Modal>
		<Modal title="Create Category"  v-model="createModal" :mask-closable="false">
            <Form :model="form" label-position="top" >
                <FormItem prop="name" :error="error.name"    label="Category Name">
                     <Input v-model="form.name" />
                </FormItem>
            </Form>
            <div slot="footer">
                <Button   @click="createModal=false" >Cancel</Button>
                <Button type="primary"  @click="createCategory" :loading="isLoading" :disabled="isLoading">{{isLoading? 'Please wait' : 'Create'}}</Button>
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
			},
           	error:{
				name:'',
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
						key: 'name'
					},
					{
						title: 'Action',
						slot: 'action',
						width: 350
					}
				],
            alldata:[],
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
			   this.getShopingList()
        },
		clearData(){
			this.error= {
				name:'',
			},
			this.form={
				name:'',
			}
		},
		validateData(){
			let flag =1
			if(!this.form.name || this.form.name.trim()==''){
				this.error.name = "This field is required!"
				flag =0
			}
			return flag
		},
		clearErrorData(){
			this.error.name =''
		},
		async createCategory(item,index){
			this.clearErrorData();
			if(!this.validateData()) return 
			this.isLoading = true
            let ob = {
                name:this.form.name,
                franchise_id:this.franchise_id
            }
			const res = await this.callApi('post', '/usermodule/addExpenseCategory', ob)
			if(res.status == 200 || res.status == 201){
				this.alldata.unshift(res.data)
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
			const res = await this.callApi('post', '/usermodule/updateExpenseCategory', this.form)
			if(res.status == 200){
				this.form._index = this.editIndex
				this.form._rowKey =this._rowKey
                Vue.set(this.alldata,this.editIndex,this.form)
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
			this.updateModal = true;
		},
		openCreateModal(){
			this.clearData()
			this.createModal = true
		},
		async deleteCategory(item,index){
            if (!confirm("Are you sure delete this category , all the related transaction will be deleted!")) {
                return;
            }
			const res = await this.callApi('post', '/usermodule/deleteExpenseCategory', {id:item.id})
            if(res.status == 200){
                this.alldata.splice(index, 1)
                this.s("Category has been deleted")
            }
           else if(res.status==422){
				this.e(res.data.message)
			}
			else {
				this.swr()
			}
		},
		async getAllCategory(){
			const res = await this.callApi('get', `/usermodule/getExpenseCategory?page=${this.page}&pageSize=${this.pageSize}&str=${this.str}&franchise_id=${this.franchise_id}`)
			if(res.status==200){
				this.alldata = res.data
			} 
		},
	},
    async created(){
		this.getAllCategory()
    },
    
}
</script>
