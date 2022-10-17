<template>
    <div>  
        <div class="_login">
            <div class="_login_left">
                <div class="_login_overlay"></div> 

                <div class="authentic_nav_logo">
                    <a href="" class="authentic_nav_logo_a">
                        <img src="/img/duare-logo.png" alt="" title="" class="_navbar_logo_img" style="width: 170px;"> 
                        <!-- <img src="/img/logo-text.png" alt="" title="" class="_navbar_logo_img_text"> -->
                    </a>
                </div>

                <h3 class="_login_left_text">Duare (A home delivery service)</h3>

                <!-- <Button type="primary">Learn More</Button> -->
            </div>

            <div class="_login_right">
                <div class="_login_main">
                    <div class="_login_top _1border_color">
                        <p class="_login_top_text">Login your account</p> 
                    </div>

                    <div class="_login_form">
                          <Alert type="error" v-if="errorMassge">{{errorMassge}}</Alert>
                        <Form>
                            <FormItem prop="user" :error="errorData.phone">
                                <Input  size="large" type="text" v-model="formInline.phone" placeholder="Phone number">
                                    <Icon type="ios-person-outline" slot="prepend"></Icon>
                                </Input>
                            </FormItem>
                            <FormItem prop="password" :error="errorData.password">
                                <Input size="large" type="password" v-model="formInline.password" placeholder="Password">
                                    <Icon type="ios-lock-outline" slot="prepend"></Icon>
                                </Input>
                            </FormItem>
                            <p class="_forget"><router-link class="_1link" to="/forgetPassword">Forget password?</router-link></p>
                            <FormItem>
                                <Button class="_log_btn" @click="handleSubmit"  :loading="isLoading" :disabled="isLoading">{{isLoading? 'Please wait' : 'Signin'}}</Button>
                            </FormItem>
                        </Form>
                    </div>

                    <div class="_login_bottom">
                        <!-- <p class="_login_do">Don't have Account?  <router-link class="_3link _mar_l5" to="/register">Register Now</router-link></p> -->
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
      formInline: {
            phone: '',
            password: ''
        },
      errorData: {
            phone: '',
            password: ''
        },
        errorMassge:'',
        isLoading:false,
        
    }
  },

  methods:{
      async handleSubmit() { 
        this.errorMassge =""
        this.errorData={  
                phone: '',
                password: ''
            }
            if(!this.formInline.phone   || this.formInline.phone==null || this.formInline.phone.trim()==''){
                return  this.errorData.phone = "Phone number is required!"
            }
            if(!this.formInline.password   || this.formInline.password==null || this.formInline.password.trim()==''){
                return  this.errorData.password = "Password is required!"
            }
            this.isLoading = true
            const res = await this.callApi('post', '/usermodule/login', this.formInline)
            if(res.status==200){
                if(res.data.userType == 'ShopOwner')  window.location ='/productmodule/product'
                else window.location ='/'
            }
            else if(res.status==422){
                this.errorMassge = res.data.message 
            }
            else{
                this.swr()
            }
            this.isLoading = false
        }
  },
  created(){
    if(this.authInfo) window.location = '/'
  },
  mounted() {
    window.addEventListener('keyup', event => {
      if (event.keyCode === 13) { 
        this.handleSubmit()
      }
    })
  }
}
</script>