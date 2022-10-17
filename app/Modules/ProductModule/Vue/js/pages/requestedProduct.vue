<template>
    <div class="_main_content">
		<div class="row">
            <div class="col-12 col-md-12 col-lg-12">
				<div class="_box _b_radious3 _padd20">
                    <div class="_1card_top _mar_b20">
							<div class="_1card_top_left">
								<p class="_1card_top_title">Requested Products</p>
								<div class="_1card_top_search">
                                    	<Input @on-change="filterResult" v-model="str" search enter-button="Search" placeholder="Enter something..." />
								</div>
							</div>
						</div>
						<div class="_table_responsive">
							<Table class="" border :columns="columns" :data="alldata.data">
								<template slot="user" slot-scope="{ row }">
									
									<p v-if="row.user && row.user.name">{{row.user.name}} ( {{row.user.phone}})</p>
 								</template>
								<template slot="text" slot-scope="{ row }">
									<span v-if="row.text.length <= 70"> {{ row.text }} </span>
                                    <span  v-else >{{ row.text.substring(1, 70) }}...</span> 
                        		</template>
                                 <template slot="franchise" slot-scope="{ row }">
									<p v-if="row.franchise && row.franchise.name">{{row.franchise.name}}</p>
								</template>
                                <template slot="action" slot-scope="{ row,index }">
									<Button type="success" @click="showDetails(row,index)">Show</Button>
									<Button  @click="deleteRequestedProduct(row,index)" type="error">Delete</Button>
 								</template>
							</Table>
						</div>
						<div class="_pagination _padd_t20" v-if="alldata && alldata.total!=null">
							<Page :total="alldata.total" show-sizer :page-size-opts="pageOption" @on-page-size-change="getSizeChange"  @on-change="paginateDataInfo" />
						</div>
                </div>
            </div>
        </div>
		<Modal title="Update" :closable="false"  v-model="updateModal" :mask-closable="false">
            <Form  :model="form" label-position="top" >
                <FormItem prop="title" :error="error.title"    label="Title">
                    <Input v-model="form.title"  type="textarea" :autosize="{minRows: 2,maxRows: 5}" placeholder="Title"/>
                </FormItem>
                <FormItem prop="subtitle" :error="error.subtitle"    label="Sub Title">
                    <Input v-model="form.subtitle"  type="textarea" :autosize="{minRows: 4,maxRows: 6}" placeholder="Sub Title"/>
                </FormItem>
                <FormItem prop="image_url" :error="error.image_url"  label="image_url">
					<div class="" v-if="form.image_url">
						<template >
							<img :src="form.image_url">
							<div class="newDelIcon">
								<Icon type="ios-trash-outline" @click.native="handleRemove()"></Icon>
							</div>
						</template>
					</div>
					<template v-if="form.image_url=='' ">
                        <!-- <Upload
							ref="upload"
							name="img"
							:show-upload-list="false"
							:format="['jpg','jpeg','png']"
							:max-size="20480"
							:on-success="handleSuccess"
							:on-format-error="handleFormatError"
							:on-exceeded-size="handleMaxSize"
							:before-upload="handleBeforeUpload"
							:on-progress="handleProgress"
							multiple
							type="drag"
							action="/productmodule/upload/images"
							style="width: 100% !important"
                        >
							<div style="padding: 20px 0">
								<Icon type="ios-cloud-upload" size="52" style="color: #3399ff"></Icon>
								<p>Click or drag files here to upload</p>
							</div>
						</Upload> -->
					</template>
                </FormItem>
            </Form>
            <div slot="footer">
                <Button   @click="updateModal = false" >Cancel</Button>
                <Button type="primary"  @click="updateData" :loading="isLoading" :disabled="isLoading">{{isLoading? 'Please wait' : 'Update'}}</Button>
            </div>
        </Modal>
		 <Modal title="Image"  v-model="imageModal" :mask-closable="false" :closable="false">
			<div>
				<img :src="singleImage" alt="">
			</div>
			 <div slot="footer">
                <Button  @click="imageModal=false" size='large' long  type="primary" >Close</Button>
            </div>
		</Modal>
		<Modal title="Details"  v-model="descriptionModal" :mask-closable="false" :closable="false">
            <Form   label-position="top" >
                <p v-if="description">{{description}}</p>
                <p v-else>No Description !</p>
            </Form>
            <div slot="footer">
                <Button  size='large' long  type="primary"  @click="descriptionModal=false" >close</Button>
            </div>
        </Modal>
    </div>
</template>


<script>
export default {
    data(){
        return{
			description:'',
			singleImage:'',
			 form:{
				title:'',
                subtitle:'',
                image_url:'' 
			},
			error:{
				title:'',
                subtitle:'',
                image_url:''
            },
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
					title: 'Message',
					slot: 'text',
					// width: 150
				},
				{
					title: 'Customer Name',
					slot: 'user',
					width: 200
				},
				{
					title: 'Franchise Name',
					slot: 'franchise',
					width: 200
				},
				{
					title: 'Action',
					slot: 'action',
					width: 250
				}
			],
            alldata:{
                total:0,
                data:[]
			},
			str:'',
			page:1,
			pageSize:10,
			userType:[],
			updateModal:false,
			imageModal:false,
			descriptionModal:false,
        }
	}, 
	methods:{
		 handleProgress( ) {
             
        },
        handleBeforeUpload() {
            
		},
		updateData(){

		},
		showDetails(item, index){
			this.description = item.text
			this.editIndex = index
            this.descriptionModal = true
		},
		async deleteRequestedProduct(item, index){
			if(!confirm("Are you sure to delete?")) {
                    return;
            }
			const res = await this.callApi('post', '/productmodule/deleteRequestedProduct', {id:item.id})
            if(res.status == 200){
                this.alldata.data.splice(index, 1)
                this.s("Deleted Successfully.")
            }
            else if(res.status==422){
				this.e(res.data.message)
			}
			else {
				this.swr()
			}
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
			const res = await this.callApi('get', `/productmodule/getRequestedProducts?page=${this.page}&pageSize=${this.pageSize}&str=${this.str}`)
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
