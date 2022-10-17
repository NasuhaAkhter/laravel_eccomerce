
<template>
    <div class="_main_content">
		<div class="row">
            <div class="col-12 col-md-12 col-lg-12">
				<div class="_box _b_radious3 _padd20">
                    <div class="_1card_top _mar_b20">
                        <div class="_1card_top_left">
                            <p class="_1card_top_title">Add Shop</p>
                        </div> 
                        <div class="_1card_top_right">
                            <ul class="_1card_top_right_list">
                                <!-- <li><Button type="primary" @click="imageModalOn">Add Image</Button></li> -->
                                <li><Button type="primary"  v-if="!form.image || getGalaryImages.length<0" @click="galaryModalOn">Add Image From Gallery</Button></li>
                            </ul>
                        </div>
                    </div>
                    <div  class="row">
                        <div class="col-12 col-md-6 col-lg-6 _mar_b20" >
                            <p class="_label _mar_b15">Select franchise</p>
                            <Select v-model="form.franchise_id" @on-change="selectFranchise" :disabled="!isAdmin" >
                                <Option v-for="(item, index) in franchiseList" :value="item.id" :key="index">{{item.name}}</Option>
                            </Select>
                         </div>
                        <div class="col-12 col-md-6 col-lg-6 _mar_b20">
                            <p class="_label _mar_b15">Select category</p>
                            <Select v-model="form.category_id" >
                                <Option v-for="(item, index) in categories" :value="item.id" :key="index">{{item.name}}</Option>
                            </Select>
                         </div>
                        <div class="col-12 col-md-6 col-lg-6 _mar_b20">
                            <p class="_label _mar_b15">Name</p>
                            <input type="text" v-model="form.name" class="form-control" placeholder="Name">
                        </div> 
                        <div class="col-12 col-md-6 col-lg-6 _mar_b20">
                            <p class="_label _mar_b15">Country</p>
                            <input type="text" disabled v-model="form.country" class="form-control" placeholder="Country">
                        </div> 
                        
                        <div class="col-12 col-md-6 col-lg-6 _mar_b20">
                            <p class="_label _mar_b15">City</p>
                            <input type="text" disabled v-model="form.city" class="form-control" placeholder="City" @keyup="autocompleteSearch" ref="maplocation" >
                        </div>

                        <div class="col-12 col-md-6 col-lg-6 _mar_b20">
                            <p class="_label _mar_b15">Country Code</p>
                            <input type="text" disabled v-model="form.countryCode" class="form-control" placeholder="Country Code">
                        </div> 
                        <div class="col-12 col-md-6 col-lg-6 _mar_b20">
                            <p class="_label _mar_b15">Phone</p>
                            <input type="text" v-model="form.phone" class="form-control" placeholder="Phone">
                        </div> 
                        <div class="col-12 col-md-6 col-lg-6 _mar_b20">
                            <p class="_label _mar_b15">Shop Owner</p>
                            <Select
                                    v-model="form.user_id"
                                    filterable
                                    :remote-method="getAllCoustomer"
                                >
                                <Option v-for="(option, index) in users" :value="option.id" :key="index" >{{option.name}}</Option>
                            </Select>
                        </div>
                        <div class="col-12 col-md-6 col-lg-6 _mar_b20"   >
                            <p class="_label _mar_b15">Percentage</p>
                            <InputNumber style="width:100%"  placeholder="0.00%" :max="100" :min="0" :formatter="value => `${value}%`"
                            :parser="value => value.replace('', '%')"
                            v-model="form.percentage" > 
                            </InputNumber>    
                        </div>
                        <div class="col-12 col-md-6 col-lg-6 _mar_b20" v-if="form.category_id == 2"  >
                            <p class="_label _mar_b15">Restaurant Type</p>
                                <RadioGroup v-model="form.category_type">
                                    <Radio :label="1">Immediate Delivery</Radio>
                                    <Radio :label="0" >Next Day Delivery</Radio>
                                </RadioGroup>
                        </div>  
                        <div class="col-12 col-md-6 col-lg-6 _mar_b20"  >
                            <p class="_label _mar_b15">Opening Time</p>
                            <TimePicker format="hh:mm A" type="time" v-model="form.shop_start_time" placeholder="Select time" style="width: 415px"></TimePicker>
                        </div>  
                        <div class="col-12 col-md-6 col-lg-6 _mar_b20"  >
                            <p class="_label _mar_b15">Closing Time </p>
                            <TimePicker format="hh:mm A" type="time" v-model="form.shop_end_time" placeholder="Select time" style="width: 415px"></TimePicker>
                        </div>  
                        <!-- <div class="col-12 col-md-6 col-lg-6 _mar_b20"  >
                            <p class="_label _mar_b15">Delivery Time </p>
                            <TimePicker format="hh:mm A" type="time" v-model="form.delivery_time" placeholder="Select time" style="width: 415px"></TimePicker>
                        </div>   -->
                        <div class="col-12 col-md-12 col-lg-12 _mar_b20">
                            <p class="_label _mar_b15">Image</p>
                           <div class="" v-if="form.image || getGalaryImages.length>0">
                                <template v-if="form.image" >
                                    <img :src="form.image">
                                    <div style="cursor:pointer">
                                        <Icon type="ios-trash-outline" @click.native="handleRemove()"></Icon>
                                    </div>
                                </template>
                                <template v-else-if="getGalaryImages[0]" >
                                    <img :src="getGalaryImages[0].image">
                                    <div style="cursor:pointer">
                                        <Icon type="ios-trash-outline" @click.native="handleRemove()"></Icon>
                                    </div>
                                </template>
                            </div>
                            <Upload v-else
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
                        </div>  
                        <div class="col-12 col-md-12 col-lg-12 _mar_b20">
                            <p class="_label _mar_b15">Address</p>
                            <Input v-model="form.address"  type="textarea" :autosize="{minRows: 2,maxRows: 5}" placeholder="Address"/>
                        </div>  
                    </div>
                    <div class="_1card_top _mar_b20">
                        <div class="_1card_top_left">
                         </div> 
                        <div class="_1card_top_right">
                            <ul class="_1card_top_right_list">
                                <li>
                                    <Button type="primary"  @click="addFranchise" :loading="isLoading" :disabled="isLoading">{{isLoading? 'Please wait' : 'Create'}}</Button>
                                </li>
                            </ul>
                        </div>
                    </div>
                 </div>
            </div>
        </div>
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
            isLoading:false,
            form:{
				name:'',
                phone:'',
                countryCode:'+88',
                address:'',
                city:'',
                lat:'',
                long:'',
                country:'Bangladesh',
                user_id:'',
                category_id:'',
                franchise_id:'',
                image:'', 
                is_recommended:0, 
                shop_start_time:'', 
                shop_end_time:'', 
                percentage:0, 
                delivery_time:'',
                category_type:1
            },
            users:[],
            categories:[],
            singleUser:{},
            auth_user_type:''
        }
    },
    created(){
        this.$store.commit('setGalaryImages', []);
        this.auth_user_type = authUser.userType 
        this.getFranchiseId()
        this.getAllCategory()        
    },
    methods:{
        galaryModalOn(){
			this.$store.commit('setGalaryModal', true);
		},
        async getFranchiseId(){
            this.form.franchise_id = this.franchiseDetails.id
            this.form.city = this.franchiseDetails.city
            this.form.lat = this.franchiseDetails.lat
            this.form.long = this.franchiseDetails.long
		},
        handleView(image){
			this.singleImage = image
			this.imageModal = true
		},
		handleRemove() {
                this.form.image =""
                this.$store.commit('setGalaryImages', []);
        },
		handleSuccess(res, file) {
			this.form.image = res.image
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
 
        selectFranchise(item){
           if(item == undefined) return
            var index = this.franchiseList.findIndex(x => x.id === item);
            var franchise = this.franchiseList[index]
            this.form.franchise_id = franchise.id
            this.form.city = franchise.city
            this.form.lat = franchise.lat
            this.form.long = franchise.long
            // console.log(this.form.franchise_id, franchise)
		},
  
        clearData(){
             if(authUser.userType =='FranchiseOwner'){
				this.getFranchiseId()
			}
            this.form.user_id = ''
        },
         
        async getAllCategory(){
            const res = await this.callApi('get', `/productmodule/getAllCategory`)
            if(res.status==200){
                this.categories = res.data.data.data
            } 
        },
        async selectCoustomer(item){
			this.singleUser = this.users[item.value]
			this.form.user_id = this.singleUser.id 
        },
        async getAllCoustomer(e=''){
            console.log(this.form.franchise_id)
            if(!this.form.franchise_id || this.form.franchise_id=='' || this.form.franchise_id == null ) return this.e("Please select a franchise!")
            if(e){
                this.str2 = e
            }
           const res = await this.callApi('get', `/usermodule/getAllShopOwner?str=${this.str2}&franchise_id=${this.form.franchise_id}&shopId=1`)
           if(res.status==200){
                this.users = res.data.data
            }  
        },
        async addFranchise(){
            if(!this.form.image || this.form.image.trim()==''){
                if(this.getGalaryImages.length >0){
					this.form.image = this.getGalaryImages[0].image
					this.$store.commit('setGalaryImages', []);
				}
            }
            if(!this.form.franchise_id || this.form.franchise_id=='' || this.form.franchise_id == null ) return this.e("Please select a franchise!")
            if(!this.form.category_id || this.form.category_id=='' || this.form.category_id == null ) return this.e("Please select a category!")
            if(!this.form.name || this.form.name=='' || this.form.name == null ) return this.e("Shop name is required.") 
            if(!this.form.country || this.form.country=='' || this.form.country == null ) return this.e("Country is required.") 
            if(!this.form.countryCode || this.form.countryCode=='' || this.form.countryCode == null ) return this.e("Country code is required.") 
            if(!this.form.phone || this.form.phone=='' || this.form.phone == null ) return this.e("Phone is required.") 
            // if(!this.form.city || this.form.city=='' || this.form.city == null ) return this.e("City is required.")             
            if(!this.form.user_id || this.form.user_id=='' || this.form.user_id == null ) return this.e("Shop owner is required.")    
            if(!this.form.is_recommended || this.form.is_recommended=='' || this.form.is_recommended == null )this.form.is_recommended = 0
            if(!this.form.shop_start_time || this.form.shop_start_time=='' || this.form.shop_start_time == null ) return this.e("Opening time is required.")
            if(!this.form.shop_end_time || this.form.shop_end_time=='' || this.form.shop_end_time == null ) return this.e("Closing time is required.")
            if(!this.form.percentage || this.form.percentage=='' || this.form.percentage == null ) this.form.percentage = 0
            // if(!this.form.delivery_time || this.form.delivery_time=='' || this.form.delivery_time == null ) return this.e("Please give an average delivery time.")
            if(!this.form.image || this.form.image.trim()=='')return this.e("Please upload an image.")   
            if(!this.form.address || this.form.address=='' || this.form.address == null ) return this.e("Shop address is required.") 

            this.isLoading = true
			const res = await this.callApi('post', '/franchisemodule/createNewshop', this.form)
			if(res.status == 200 || res.status == 201){
                this.s("New shop has been created")
                this.$router.push('/franchisemodule/shop-list')
 			}
			else if(res.status==422){
				this.e(res.data.message)
			}
			else {
				this.swr()
			}
			this.isLoading = false
        },
        async autocompleteSearch(){
			if(this.form.city == '') return 
			var autocomplete = new google.maps.places.Autocomplete((this.$refs['maplocation']), {
				types: ['(regions)'],
			});
            var self = this 
			google.maps.event.addListener(autocomplete, 'place_changed', function (event) {
                var near_place = autocomplete.getPlace();
                self.changeData(near_place)
              }); 
        },
        changeData(data){
			this.form.city = data.formatted_address
			this.form.address = data.formatted_address
			this.form.lat = data.geometry.location.lat();
			this.form.long = data.geometry.location.lng();
			this.form.radious = 30
		},
    },
    
}
</script>
<style>
.btn_extend{
    color: #f6f6f6;
    background: #8f5582;
}

</style>
