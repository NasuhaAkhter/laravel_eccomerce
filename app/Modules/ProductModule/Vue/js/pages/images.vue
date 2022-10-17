<template>
    <div class="_main_content"> 
		<div class="row">
            <div class="col-12 col-md-12 col-lg-12">
				<div class="_box _b_radious3 _padd20">
                    <div class="_1card_top _mar_b20">
							<div class="_1card_top_left"> 
								<p class="_1card_top_title">Product images</p>
							</div>
							<div class="_1card_top_right">
								<ul class="_1card_top_right_list">
									<li><Button type="primary" @click="imageModalOn">Add Image</Button></li>
                                    <li><Button type="primary"  @click="galaryModalOn">Add Image From Gallery</Button></li>
								</ul>
							</div>
						</div> 
                </div>  
            </div>
            <br>
			 <div class="col-12 col-md-4 col-lg-4" v-for="(item,index) in alldata.data" :key="index" >
				 <div class="product_image">
                     <br>
					 <img :src="item.image">
					 <span class="product_image_delete" @click="deleteProductImage(item.id,index)"> 
						 <Icon  type="ios-trash" />
					</span>
				 </div>
			 </div>
        </div>
		<Modal title="Image"  v-model="imageModal" :mask-closable="false">
            <Form  label-position="top" >
			
			
				 <FormItem prop="uploadedList"  label="Image" :error="error.image" >
                       <div class="" v-if="uploadedList.length>0 || getGalaryImages.length>0">
                            <template v-if="uploadedList.length>0" >
                                <div class="demo-upload-list" v-for="(item, i) in uploadedList" :key="i">
                                    <template v-if="item.image">
                                        <img :src="item.image">
                                        <div class="demo-upload-list-cover">
                                            <Icon type="ios-eye-outline" @click.native="handleView(item.image)"></Icon>
                                            <Icon type="ios-trash-outline" @click.native="handleRemove(i)"></Icon>
                                        </div>
                                    </template>
                                    <template v-else>
                                        <Progress v-if="item.showProgress" :percent="item.percentage" hide-info></Progress>
                                    </template>
                                </div>
                            </template>
                        </div>
                        <!-- <div class="demo-upload-list" v-for="(item, i) in uploadedList" :key="i">
                            <template v-if="item.image">
                                <img :src="item.image">
                                <div class="demo-upload-list-cover">
                                    <Icon type="ios-eye-outline" @click.native="handleView(item.image)"></Icon>
                                    <Icon type="ios-trash-outline" @click.native="handleRemove(i)"></Icon>
                                </div>
                            </template>
                            <template v-else>
                                <Progress v-if="item.showProgress" :percent="item.percentage" hide-info></Progress>
                            </template>
                        </div> -->
                        
                        <Upload
                            ref="upload"
                            name="img"
                            :show-upload-list="false"
                            :format="['jpg','jpeg','png','webp']"
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
                    </FormItem>
                        
            </Form>
            <div slot="footer">
                <Button   @click="storeProductImages" >store</Button>
                <Button   @click="close" >close</Button>
            </div>
        </Modal>
		<Modal title="Image"  v-model="singleImageModal" :mask-closable="false">
			<div>
				<img :src="image" alt="">
			</div>
			<div slot="footer">
                <Button   @click="singleImageModal=false" >close</Button>
            </div>
		</Modal>
    </div>
</template>


<script>
import { mapGetters } from 'vuex';
export default {
	computed: {
    ...mapGetters({
          getGalaryImages:'getGalaryImages',
        }),
    },
    data(){
        return{
			image:false,
			singleImageModal:false,
			imageModal:false,
			isUploadError:false,
			uploadErrorText:"",
            alldata:{
                total:0,
                data:[]
			},
			error:{image:''},
			listStoreImage:[],
			uploadedList : [],
			id:''
        }
	},
	methods:{
        close(){
            this.imageModal=false
            this.uploadedList = []
        },
		async storeProductImages(){
            this.error.image =''
             if(this.uploadedList.length<1) return this.error.image = "Please upload images"
			const res = await this.callApi('post', '/productmodule/storeProductImages', this.uploadedList)
			if(res.status == 200){
				for(let it of res.data) {
                    this.alldata.data.unshift(it)
                }
                this.imageModal = false
                this.uploadedList =[]

			}
		},
		galaryModalOn(){
			this.$store.commit('setGalaryModal', true);
		},
		 imageModalOn(){
			this.imageModal = true
		},
		 deleteProductImagelistStoreImage(index){
			this.listStoreImage.splice(index, 1);
		},
		 deleteProductImageupload(index){
			this.uploadedList.splice(index, 1);
		},
		async deleteProductImage(id,index){
			if (!confirm("Are you sure to delete?")) {
                return;
			}
			const res = await this.callApi('post', '/productmodule/deleteProductImage', {id:id})
			if(res.status == 200){
				this.alldata.data.splice(index, 1);
			}
			else if(res.status == 422){
				this.e(res.data.message)
			}
			else this.swr()
		},
		// upload methods
		   handleRemove(i) {
            this.uploadedList.splice(i,1) 
        },
         handleSuccess(res, file) {
             this.uploadedList.unshift({image:res.image, product_id:this.id})
        },
         handleFormatError(file) {
            this.isUploadError = true;
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
            this.isUploadError = false;
            this.uploadErrorText = "";
            // this.uploadedList = fileList;
        },
        handleBeforeUpload() {
            // const check = this.uploadedList.length < 1;
            // if (!check) {
            //     this.$Notice.warning({
            //     title: "Up to 1 files can be uploaded.",
            //     });
            // }
            // return check;
		},
		 async handleView(item){
           this.image = item
           this.singleImageModal = true
        },

	},
    async created(){
		this.id  = this.$route.params.id
		const res = await this.callApi('get',`/productmodule/getAllProductImages/${this.id}`)
		if(res.status == 200){
			this.alldata.data = res.data
			this.alldata.total = res.data.total
		}
	},
	watch: {
		"$store.state.images": async function (newValu, oldQuestion) {
			console.log(newValu)
			this.listImage = []
			if(newValu=='' ||  newValu.length==0) return
			for(let it of newValu) {
				this.listImage.push({image:it.image,product_id:this.$route.params.id})
			}
			const res = await this.callApi('post', '/productmodule/storeProductImages', this.listImage)
			if(res.status == 200){
				for(let it of res.data) {
					this.alldata.data.unshift(it)
			    }
			}
			this.$store.commit('setGalaryImages', []);
			this.listImage = []
		}
  },
    
}
</script>


<style>
.product_image{
	position: relative;
	
}
.product_image_delete{
	position: absolute;
    background: #e7eced;
    color: #d7010b;
    border: 1px solid #777;
    bottom: 0;
    right: 0;
    padding: 5px 5px;
    cursor: pointer;
}
</style>