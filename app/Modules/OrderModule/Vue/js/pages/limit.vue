  <template>
    <div class="_main_content">
		<div class="row">
            <div class="col-12 col-md-12 col-lg-12">
				<div class="_box _b_radious3 _padd20">
                    <div class="_1card_top _mar_b20">
							<div class="_1card_top_left">
								<p class="_1card_top_title">Brand list</p>
								<div class="_1card_top_search">
									<Input @on-enter="serchResetlt" v-model="str" search enter-button="Search" placeholder="Enter something..." />
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
									<Button type="primary" @click="openEditModal(row,index)">Edit</Button>
									<Button type="error" @click="deleteBand(row,index)">Delete</Button>
								</template>
							</Table>
						</div>
                        <div class="_pagination _padd_t20" v-if="alldata && alldata.total!=null">
							<Page :total="alldata.total" show-sizer :page-size-opts="pageOption" @on-page-size-change="getSizeChange"  @on-change="paginateDataInfo" />
						</div>
                </div>
            </div>
        </div>
		<Modal title="Update brand"  v-model="updateModal" :mask-closable="false">
            <Form  :model="form" label-position="top" >
                <FormItem prop="name" :error="error.name"    label="brand  name">
                     <Input v-model="form.name" />
                </FormItem>
            </Form>
            <div slot="footer">
                <Button   @click="updateModal=false" >Cancel</Button>
                <Button type="primary"  @click="updateData" :loading="isLoading" :disabled="isLoading">{{isLoading? 'Please wait' : 'Update'}}</Button>
            </div>
        </Modal>
		<Modal title="Create brand "  v-model="createModal" :mask-closable="false">
            <Form  :model="form" label-position="top" >
                <FormItem prop="name" :error="error.name"    label="brand  Name">
                     <Input v-model="form.name" />
                </FormItem>
            </Form>
            <div slot="footer">
                <Button   @click="createModal=false" >Cancel</Button>
                <Button type="primary"  @click="createBand" :loading="isLoading" :disabled="isLoading">{{isLoading? 'Please wait' : 'Create'}}</Button>
            </div>
        </Modal>
    </div>
</template>
<script>
export default {
    data(){
        return{
			 form:{},
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
                    title: 'brand id',
                    key: 'id',
                },
				{
					title: 'Name',
					key: 'name'
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
			page:1,
			pageSize:10,
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
			   this.getAllProductBand()
        },
		clearData(){
			this.error= {
				name:'',
			},
			this.form={}
		},
		validateData(){
			let flag =1
			if(!this.form.name || this.form.name.trim()==''){
				this.error.name = "This field is required!"
				flag =0
			}
			return flag
		},
		async createBand(item,index){
			if(!this.validateData()) return 
			this.isLoading = true
			const res = await this.callApi('post', '/productmodule/storeProductBand', this.form)
			if(res.status == 200 || res.status == 201){
				this.alldata.data.unshift(res.data)
                this.s("New brand has been created")
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
			if(!this.validateData()) return 
			this.isLoading = true
			const res = await this.callApi('post', '/productmodule/updateProductBand', this.form)
			if(res.status == 200){
				this.form._index = this.editIndex
				this.form._rowKey =this._rowKey
                Vue.set(this.alldata.data,this.editIndex,this.form)
                this.s("Product brand has been updated!")
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
			// this._rowKey = item._rowKey
			// delete item._rowKey
			// delete item._index
			
			console.log(item)
			this.form = _.cloneDeep(item)
			this.updateModal = true;
		},
		openCreateModal(){
			this.clearData()
			this.createModal = true
		},
		async deleteBand(item,index){
             if (!confirm("Are you suer to delete?")) {
                    return;
                }
			const res = await this.callApi('post', '/productmodule/deleteProductBand', {id:item.id})
            if(res.status == 200){
                this.alldata.data.splice(index, 1)
                this.s("Band has been deleted")
            }
           else if(res.status==422){
				this.e(res.data.message)
			}
			else {
				this.swr()
			}
		},
		async getAllProductBand(){
			const res = await this.callApi('get', `/productmodule/getAllProductBand?page=${this.page}&pageSize=${this.pageSize}&str=${this.str}`)
			if(res.status==200){
				this.alldata.data = res.data.data
				this.alldata.total = res.data.total
			} 
		}
	},
    async created(){
		this.getAllProductBand()
    },
}
</script>
