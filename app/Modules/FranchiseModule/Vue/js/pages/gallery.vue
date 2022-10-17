<template>
    <div class="_main_content">
		<div class="row">
            <div class="col-12 col-md-12 col-lg-12">
				<div class="_box _b_radious3 _padd20">
                    <div class="_1card_top _mar_b20">
							<div class="_1card_top_left">
								<p class="_1card_top_title">Gallery</p>
								<div class="_1card_top_search">
                                    <!-- <Button @click="AddProductImage"> Add Product Images</Button> -->
									<!-- <Input  @on-enter="serchResetlt" v-model="str" search enter-button="Search" placeholder="Enter something..." /> -->
								</div>
							</div>
							<div class="_1card_top_right">
								<ul class="_1card_top_right_list">
									<li> 
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
                                    action="/usermodule/upload/images"                                    >
                                    <Button icon="ios-cloud-upload-outline">Upload</Button>
                                    </Upload>
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
                                            action="/usermodule/upload/images"
                                            style="width: 100% !important"
                                            >
                                            <div style="padding: 20px 0">
                                                <Icon type="ios-cloud-upload" size="52" style="color: #3399ff"></Icon>
                                                <p>Click or drag files here to upload</p>
                                            </div>
                                        </Upload> -->
                                    </li>
								</ul>
							</div>
						</div >
                        <div class="gallery_main">
                            <div class="gallery_picture" v-for="(item, index) in alldata.data" :key="index">
                                <div v-if="item" class="gallery_picture_main" @click="handleView(item.image)">
                                    <img class="gallery_image" :src="item.image" alt="image">
                                </div>
                            </div>
                        </div> 
                        <div class="_pagination _padd_t20"  v-if="alldata && alldata.total!=null">
							<Page :total="alldata.total" show-sizer :page-size-opts="pageOption" @on-page-size-change="getSizeChange"  @on-change="paginateDataInfo" />
						</div>
                </div>
            </div>
        </div>		 
		<Modal title="Image"  v-model="imageModal" :mask-closable="false">
			<div>
				<img :src="singleImage" alt="">
			</div>
			<div slot="footer">
                <Button   @click="imageModal=false" >Close</Button>
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
 				image:''
			 },
           	 error:{
 				image:''
            },
			updateModal:false,
			isLoading:false,
			createModal:false,
			editIndex:-1,
			_rowKey:-1,
            pageOption:[5,10,15,20,30],
            alldata:{
                data:[],
                total:0,
            },
            // gallery:[],
			pageSize:10,
			page:1,
			str:'',
            firstId : 1
        }
	},
	methods:{ 
        getSizeChange(e){
			this.pageSize =e
			this.paginateDataInfo(1)
		},
		paginateDataInfo(e){
               this.page = e
               this.getAllGallery()
        },
        async AddProductImage(){
            const res = await this.callApi('post', `/franchisemodule/AddProductImage?id=${this.firstId}`)
			if(res.status==200){
                this.firstId  = this.firstId + 500
			} 
        },
		async getAllGallery(){
			const res = await this.callApi('post', `/franchisemodule/getAllGallery?page=${this.page}&pageSize=${this.pageSize}&str=${this.str}`)
			if(res.status==200){
                this.alldata  = res.data
			} 
        },
		async save_upload(){
            const res = await this.callApi('post', `/franchisemodule/postGallery`, this.form)
            if(res.status == 200 || res.status == 201){
				this.alldata.data.unshift(res.data)
                this.s("File has been uploaded successfully")
 			}
			else if(res.status==422){
				this.e(res.data.message)
			}
			else {
				this.swr()
			}
         },
        handleView(image){
			this.singleImage = image
			this.imageModal = true 
		},
		handleRemove() {
            	this.form.image =""
        },
		handleSuccess(res, file) {
            this.form.image = res.image
            this.save_upload()
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
			this.form.image = ''
		},
	},
    async created(){
		this.getAllGallery()
    },
    
}
</script>
