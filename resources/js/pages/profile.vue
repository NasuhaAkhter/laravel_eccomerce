
<template>
    <div class="_main_content">
		<div class="row">
            <div class="col-12 col-md-12 col-lg-12">
				<div class="_box _b_radious3 _padd20">
                    <div class="_1card_top _mar_b20">
                        <div class="_1card_top_left">
                            <p class="_1card_top_title">Edit Profile</p>
                        </div> 
                    </div>
                    <div  class="row">
                        <div class="col-12 col-md-4 col-lg-4 _mar_b20"></div>
                        <div class="col-12 col-md-4 col-lg-4 _mar_b20"> 
                            <div class="_edit_profile_pic">
                                  <img v-if="authInfo.image" class="_left_sidebar_img" :src="authInfo.image" title="" alt="">
                                  <img v-else class="_left_sidebar_img" src="/img/profile.png" title="" alt="">
                            </div>
                            <br>
                            <Upload  
                                ref="upload" 
                                name="img"
                                :on-success="handleSuccess"          
                                action="/usermodule/upload/images"
                            >
                            &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
                                <Button icon="ios-cloud-upload-outline" :loading="isLoading" :disabled="isLoading">{{isLoading? 'Please wait' : 'Change Photo'}}</Button>
                            </Upload>
                        </div>
                        <div class="col-12 col-md-4 col-lg-4 _mar_b20"></div>
                        <div class="col-12 col-md-6 col-lg-6 _mar_b20">
                            <p class="_label _mar_b15">Name</p>
                            <input type="text" v-model="form.name" class="form-control" placeholder="Name">
                        </div> 
                        <div class="col-12 col-md-6 col-lg-6 _mar_b20">
                            <p class="_label _mar_b15">Country</p>
                            <input type="text" v-model="form.country" disabled class="form-control" placeholder="Country">
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
                            <p class="_label _mar_b15">City</p>
                            <input type="text" v-model="form.city" class="form-control" placeholder="City"  >
                        </div>
                        
                        <div class="col-12 col-md-6 col-lg-6 _mar_b20">
                            <p class="_label _mar_b15">Address</p>
                            <Input v-model="form.address" type="textarea" :autosize="{minRows: 2,maxRows: 5}" placeholder="Address"/>
                        </div>  
                    </div>
                    <div class="_1card_top _mar_b20">
                        <div class="_1card_top_left">
                         </div> 
                        <div class="_1card_top_right">
                            <ul class="_1card_top_right_list">
                                <li>
                                    <Button type="primary"  @click="infoUpdate" :loading="isLoadingforInfo" :disabled="isLoadingforInfo">{{isLoadingforInfo? 'Please wait' : 'Update'}}</Button>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
             &nbsp;
            <div class="col-12 col-md-12 col-lg-12">
                <div class="_box _b_radious3 _padd20">
                    <div class="_1card_top _mar_b20">
                        <div class="_1card_top_left">
                            <p class="_1card_top_title">Change Password</p>
                        </div> 
                    </div>
                    <div  class="row">
                        <div class="col-12 col-md-2 col-lg-2 _mar_b20"></div>
                        <div class="col-12 col-md-8 col-lg-8 _mar_b20">
                            <p class="_label _mar_b15">Current Password</p>
                            <input type="password" v-model="form2.current_password" class="form-control" placeholder="Password">
                        </div>
                        <div class="col-12 col-md-2 col-lg-2 _mar_b20"></div>
                        <div class="col-12 col-md-2 col-lg-2 _mar_b20"></div>
                        <div class="col-12 col-md-8 col-lg-8 _mar_b20">
                            <p class="_label _mar_b15">New Password</p>
                            <input type="password" v-model="form2.password" class="form-control" placeholder="Password">
                        </div>
                        <div class="col-12 col-md-2 col-lg-2 _mar_b20"></div>
                        <div class="col-12 col-md-2 col-lg-2 _mar_b20"></div>
                        <div class="col-12 col-md-8 col-lg-8 _mar_b20">
                            <p class="_label _mar_b15">Confirm Password</p>
                            <input type="password" v-model="form2.confirm_password" class="form-control" placeholder="Password">
                        </div>
                        <div class="col-12 col-md-2 col-lg-2 _mar_b20"></div>
                    </div>
                     <div class="_1card_top _mar_b20">
                        <div class="_1card_top_left">
                         </div> 
                        <div class="_1card_top_right">
                            <ul class="_1card_top_right_list">
                                <li>
                                    <Button type="primary" @click="passwordUpdate" :loading="isLoadingforPass" :disabled="isLoadingforPass">{{isLoadingforPass? 'Please wait' : 'Save'}}</Button>
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
export default {
    data(){
        return{
            isLoading:false,
            isLoadingforPass:false,
            isLoadingforInfo:false,
            form:{
				name:'',
                phone:'',
                countryCode:'+88',
                address:'',
                city:'',
                country:'Bangladesh',
             },
            form2:{
                current_password:'',
                password:'',
                confirm_password:'',
            },
            users:[],
            singleUser:{},
            image:''
        }
    },
    created(){
        this.form.name = authUser.name
        this.form.phone = authUser.phone
        this.form.countryCode = authUser.countryCode
        this.form.country = authUser.country
        this.form.city = authUser.city
        this.form.address = authUser.address
    },
    methods:{
        handleSuccess(res, file) {
            this.image = res
            this.image = res.image
            this.profile_pic_update()
        },
        async profile_pic_update(){
            this.isLoading = true
            const res = await this.callApi('post', '/usermodule/profile_pic_update', {image:this.image})
            if (res.status == 200) {
 					this.s("Great!", "Image has been updated successfully!");
					this.profileImageOn= false
 					this.$store.commit('setAuthuser',(res.data));

			}else {
			this.e("Oops!", "Something went wrong, please try again!");
            }
            this.isLoading = false

        },
        async passwordUpdate(){
            if (!this.form2.current_password) return this.e("Current Password can not be empty! !");
            if (!this.form2.password) return this.e("New Password can not be empty! !");
            if (!this.form2.confirm_password) return this.e("Confirm Password can not be empty! !");
            if (this.form2.password != this.form2.confirm_password) return this.e("Password doesn't match! !");
            this.isLoadingforPass = true
            const res = await this.callApi(
                "post",
                `/usermodule/password_update`,
                this.form2
            );
            if (res.status == 200) {
                this.form2.current_password = ''
                this.form2.password = ''
                this.form2.confirm_password = ''
                this.s("Great!", "Password has been updated successfully!");
            }
            else if (res.status == 422) {
                if (res.data.msg) {
                this.e(res.data.msg);
                }else if(res.data.password){
                    this.e(res.data.password);
                }
            }
            else {
                this.e("Oops!", "Something went wrong, please try again!");
            }
            this.isLoadingforPass = false
    },
    async infoUpdate() { 
            if(!this.form.name || this.form.name=='' || this.form.name == null ) return this.e("Franchise name is required.") 
            if(!this.form.country || this.form.country=='' || this.form.country == null ) return this.e("Country is required.") 
            if(!this.form.countryCode || this.form.countryCode=='' || this.form.countryCode == null ) return this.e("Country code is required.") 
            if(!this.form.phone || this.form.phone=='' || this.form.phone == null ) return this.e("Phone is required.") 
            if(!this.form.city || this.form.city=='' || this.form.city == null ) return this.e("City is required.")             
           this.isLoadingforInfo = true
            const res = await this.callApi(
            "post",
            `/usermodule/info_update`,
            this.form
        );
        if (res.status == 200) {
            this.$store.commit('setAuthuser',(res.data));
            this.s("Great!", "Profile info has been updated successfully!");
        }else if (res.status == 422) {
            if (res.data.msg) {
            this.e(res.data.msg);
            }else if(res.data.email){
                this.e(res.data.email);
            }
        }
        else {
            this.e("Oops!", "Something went wrong, please try again!");
        }
        this.isLoadingforInfo = false
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
