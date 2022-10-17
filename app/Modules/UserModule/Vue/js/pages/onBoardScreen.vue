<template>
    <div class="_main_content">
		<div class="row">
            <div class="col-12 col-md-12 col-lg-12">
				<div class="_box _b_radious3 _padd20">
                    <div class="_1card_top _mar_b20">
							<div class="_1card_top_left">
								<p class="_1card_top_title">On Board Screens</p>

								<!-- <div class="_1card_top_search">
 									<Select @on-change="filterResult" v-model="str" placeholder="Filter by status " filterable clearable>
 										<Option value="Approved">Approved</Option>
										<Option value="Canceled">Canceled</Option>
 									</Select> 
								</div> -->
							</div>
 
						</div>
						<div class="_table_responsive">
							<Table class="" border :columns="columns" :data="alldata.data">
								<template slot="slide" slot-scope="{ row }">
									<div>
										<div class="demo-upload-list">
											<img :src="row.image_url" alt="">
											<div class="demo-upload-list-cover">
												<Icon type="ios-eye-outline" @click="handleView(row.image_url)"></Icon>
											</div>
										</div>
									</div>
 								</template>
								 
                                <template slot="action" slot-scope="{row,index }">
									<Button type="primary" @click="openEditModal(row,index)">Edit</Button>
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
                        <Upload
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
							action="/usermodule/upload/images"
							style="width: 100% !important"
                        >
							<div style="padding: 20px 0">
								<Icon type="ios-cloud-upload" size="52" style="color: #3399ff"></Icon>
								<p>Click or drag files here to upload</p>
							</div>
						</Upload>
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
                <Button   @click="imageModal=false" size='long' type="primary" >Close</Button>
            </div>
		</Modal>
    </div>
</template>


<script>
export default {
    data(){
        return{
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
					width: 60
                },
				{
					title: 'Slide',
					slot: 'slide',
					width: 150
				},
				{
					title: 'Title',
					key: 'title',
					// width: 200
				},
				{
					title: 'Sub Title',
					key: 'subtitle',
					// width: 200
				},
				{
					title: 'Action',
					slot: 'action',
					width: 150
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
        }
	}, 
	methods:{
		clearData(){
			this.error= {
				name:'',
				image:'',
			},
			this.form={
				name:'',
				status:1,
				image:'',
			}
		},
		validateData(){
			let flag =1
			if(!this.form.title || this.form.title.trim()==''){
				this.error.title = "This field is required!"
				flag =0
			}
			if(this.form.subtitle==null || this.form.subtitle.trim()==''){
				this.error.subtitle = "This field is required!"
				flag =0
			}
			return flag
		},
		clearErrorData(){
			this.error.title =''
			this.error.subtitle =''
		},
		async updateData(){
			// if(!this.validateData()) return 
			this.isLoading = true
			const res = await this.callApi('post', '/usermodule/updateOnBoardScreen', this.form)
			if(res.status == 200){
				this.form._index = this.editIndex
				this.form._rowKey =this._rowKey
                Vue.set(this.alldata.data,this.editIndex,this.form)
                this.s("On Screen Board has been updated!")
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
		handleView(image){
			this.singleImage = image
			this.imageModal = true
		},
		handleRemove() {
            	this.form.image_url =""
        },
		handleSuccess(res, file) {
			this.form.image_url = res.image
             console.log(res, file, "hello")
        },
         handleFormatError(file) {
            this.uploadErrorText = "Supported files types: .JPG .PNG .JPEG ";
        },
        handleMaxSize(file) {
            if (file) {
                this.$Notice.warning({
                title: "Exceeding file size limit",
                desc: "File  " + file.name + " is too large, no more than 20Mb.",
                });
            }
        },
        handleProgress(event, file, fileList) {
            this.uploadErrorText = "";
        },
        handleBeforeUpload() {
		},
		
		async handleRemove(){
			this.form.image_url = ''
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
			const res = await this.callApi('get', `/usermodule/getAllScreens?page=${this.page}&pageSize=${this.pageSize}&str=${this.str}`)
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
