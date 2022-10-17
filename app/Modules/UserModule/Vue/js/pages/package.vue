<template>
    <div class="_main_content">
		<div class="row">
            <div class="col-12 col-md-12 col-lg-12">
				<div class="_box _b_radious3 _padd20">
                    <div class="_1card_top _mar_b20">
							<div class="_1card_top_left">
								<p class="_1card_top_title">Package list</p>
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
								<template slot="price" slot-scope="{ row }">
									 <p v-if="row.price"> {{ row.price }} ৳</p>
									 <p v-else> 0 ৳</p>
								</template>
								<template slot="action" slot-scope="{ row, index }">
									<Button type="primary" @click="openEditModal(row,index)">Edit</Button> 
									<Button type="error" @click="deletePackage(row,index)">Delete</Button>
								</template>
							</Table>
						</div>
                        <div class="_pagination _padd_t20" v-if="alldata && alldata.total!=null">
							<Page :total="alldata.total" show-sizer :page-size-opts="pageOption" @on-page-size-change="getSizeChange"  @on-change="paginateDataInfo" />
						</div>
                </div>
            </div>
        </div>
		<Modal title="Create package" width="800" v-model="createModal" :mask-closable="false" >
            <div class="row">
                <div class="col-12 col-md-4 col-lg-4 _mar_b20">
                    <p class="_label _mar_b15">Name</p>
                    <Input v-model="form.name" type="text" placeholder="Name "/>
                </div>
                <div class="col-12 col-md-4 col-lg-4 _mar_b20"  >
                    <p class="_label _mar_b15">Price</p>
                    <InputNumber style="width:100%"  :min="0" :editable="true" @on-change="getPrice()"
					:formatter="value => `${value} ৳`"
					:parser="value => value.replace(' ৳', '')"  v-model="form.price" ></InputNumber>
                </div> 
                <div class="col-12 col-md-4 col-lg-4 _mar_b20">
                    <p class="_label _mar_b15">Duration</p>
                    <Input v-model="form.duration" type="text" placeholder=" 0 month "/>
                </div>
				<div class="col-12 col-md-12 col-lg-12 _mar_b20">
                	<p class="_label _mar_b15"> Description</p>
					<Input v-model="form.description"  maxlength="500" type="textarea" placeholder="Description "/>
				</div> 
                  
            </div>
            <div slot="footer">
                <Button   @click="createModal=false" >Cancel</Button>
                <Button type="primary"  @click="createPackage" :loading="isLoading" :disabled="isLoading">{{isLoading? 'Please wait' : 'Create'}}</Button>
            </div>
        </Modal>
		<Modal title="Edit package" width="800" v-model="updateModal" :mask-closable="false" >
            <div class="row">
               <div class="col-12 col-md-4 col-lg-4 _mar_b20">
                    <p class="_label _mar_b15">Name</p>
                    <Input v-model="form.name" type="text" placeholder="Name "/>
                </div>
                <div class="col-12 col-md-4 col-lg-4 _mar_b20"  >
                    <p class="_label _mar_b15">Price</p>
                    <InputNumber style="width:100%"  :min="0" controls-outside
					:formatter="value => `${value} ৳`"
					:parser="value => value.replace(' ৳', '')"
					  v-model="form.price" ></InputNumber>
                </div> 
                <div class="col-12 col-md-4 col-lg-4 _mar_b20">
                    <p class="_label _mar_b15">Duration</p>
                    <Input v-model="form.duration" type="text" placeholder="Duration "/>
                </div>
				<div class="col-12 col-md-12 col-lg-12 _mar_b20">
                	<p class="_label _mar_b15"> Description</p>
					<Input v-model="form.description" maxlength="500" type="textarea" placeholder="Description "/>
				</div> 
            </div>
            <div slot="footer">
                <Button   @click="updateModal=false" >Cancel</Button>
                <Button type="primary"  @click="updatePackage" :loading="isLoading" :disabled="isLoading">{{isLoading? 'Please wait' : 'Update'}}</Button>
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
                name: '',
                description: '',
                price: 0, 
                duration:''
  			},
           	error:{
				name: '',
                description: '',
                price: 0, 
                duration:''
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
						key: 'name',
						// width: 120
					},
					{
						title: 'Description',
						key: 'description',
						width: 400
 					},
					{
						title: 'Duration',
						key: 'duration',
						width: 120
 					},
					{
						title: 'Price',
						slot: 'price',
						width: 170
 					},
					{
						title: 'Action',
						slot: 'action',
						// width: 300
					}
				],
            alldata:{
                total:0,
                data:[]
			},
			pageSize:10,
			page:1,
			str:'',
			user_list:[],
			categories:[],
        }
	},
	methods:{
        getPrice(query){
            this.form.price = query
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
			   this.getAllPackage()
        },
		clearData(){
			this.error= {
				 name: '',
                description: '',
                price: 0, 
                duration:''
			},
			this.form={
				name: '',
                description: '',
                price: 0, 
                duration:''
			}
        },
        async deletePackage(item,index){
			if (!confirm("Are you sure to delete?")) {
                    return;
                }
			const res = await this.callApi('post', '/usermodule/deletePackage', {id:item.id})
            if(res.status == 200){
                this.alldata.data.splice(index, 1)
                this.s("Package has been deleted.")
            }
            else if(res.status==422){
				this.e(res.data.message)
			}
			else {
				this.swr()
			}
        },
		clearErrorData(){
			this.error= {
                name: '',
                description: '',
                price: 0, 
                duration:''
            }
		},
		formatDate(e){
            this.form.validity = e
        },
		async createPackage(item,index){
			if(!this.form.name || this.form.name.trim()=='' || this.form.name == null ) return this.e("Package is required.") 
			if(!this.form.description || this.form.description.trim()=='' || this.form.description == null ) return this.e("Description is required.") 
			if(!this.form.duration || this.form.duration=='' || this.form.duration==null) return this.e("Duration is required.") 
			if(!this.form.price ) return this.e("Price is required.") 
        	this.isLoading = true
 			const res = await this.callApi('post', '/usermodule/addPackage', this.form)
			if(res.status == 200 || res.status ==201){
				this.alldata.data.unshift(res.data)
                this.s("New package has been created.")
                this.user_list =[]
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
		async updatePackage(item,index){
			if(!this.form.name || this.form.name.trim()=='' || this.form.name == null ) return this.e("Package is required.") 
			if(!this.form.description|| this.form.description.trim()=='' || this.form.description == null ) return this.e("Description is required.") 
			if(!this.form.duration || this.form.duration=='' || this.form.duration==null) return this.e("Duration is required.") 
			if(!this.form.price ) return this.e("Price is required.") 
			this.isLoading = false
			this.editIndex  = index
  			const res = await this.callApi('post', '/usermodule/updatePackage', this.form)
			if(res.status == 200){
				this.s("Package has been updated!")
				window.location = '/usermodule/packages'
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
			this.form.name = item.name
			this.form.description = item.description
			this.form.price = item.price
 			this.form.duration = item.duration
			this.updateModal = true; 
		},
		openCreateModal(){
			this.clearData()
			this.createModal = true
        },
        async getAllPackage(){
            const res = await this.callApi('get', `/usermodule/getAllPackage?page=${this.page}&pageSize=${this.pageSize}&str=${this.str}`)
			if(res.status==200){
                this.alldata = res.data
			} 
        },
        
	},
    async created(){
		this.getAllPackage()
		 
    },
    
}
</script>
