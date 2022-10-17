
<template>
    <div class="_main_content">
		<div class="row">
            <div class="col-12 col-md-12 col-lg-12">
				<div class="_box _b_radious3 _padd20">
                    <div class="_1card_top _mar_b20">
                        <div class="_1card_top_left">
                            <p class="_1card_top_title">My Franchise - <span v-if="form.name" >{{form.name}}</span></p>
                        </div> 
                    </div>
                    <div  class="row">
                        <div class="col-12 col-md-8 col-lg-8 _mar_b20">
                            <p class="_label _mar_b15">Name</p>
                            <input type="text" v-model="form.name" class="form-control" placeholder="Name" disabled >
                        </div>
                        <div class="col-12 col-md-4 col-lg-4 _mar_b20">
                            <p class="_label _mar_b15">Phone</p>
                            <input type="text" v-model="form.phone" class="form-control" placeholder="Phone" disabled>
                        </div> 
                        <div class="col-12 col-md-4 col-lg-4 _mar_b20">
                            <p class="_label _mar_b15">Whatspp Phone Number</p>
                            <input type="text" v-model="form.whatsapp_number" class="form-control" placeholder="whatsapp_number">
                        </div> 
                        <div class="col-12 col-md-4 col-lg-4 _mar_b20">
                            <p class="_label _mar_b15">Franchise Status</p>
                            <RadioGroup v-model="form.status">
                                <Radio :label="1">Active</Radio>
                                <Radio :label="0" >Deactive</Radio>
                            </RadioGroup>
                        </div> 
                    </div>
                    <div class="_1card_top _mar_b20">
                        <div class="_1card_top_left">
                         </div> 
                        <div class="_1card_top_right">
                            <ul class="_1card_top_right_list">
                                <li>
                                    <Button type="primary"  @click="addFranchise" :loading="isLoading" :disabled="isLoading">{{isLoading? 'Please wait' : 'Update'}}</Button>
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
            form:{
				id:'',
                name:'',
                phone:'',
                whatsapp_number:'',
                status:''
            },
           
        }
    },
    async created(){
          
        await this.getSingleFranchise()
       
    },
   
    methods:{
        async getSingleFranchise(){
			const res = await this.callApi('get', `/franchisemodule/getMyFranchise`);
            if(res.status == 200){
                let form ={
                    id:res.data.id,
                    name:res.data.name,
                    phone:res.data.phone,
                    whatsapp_number:res.data.whatsapp_number,
                    status:res.data.status,
                };

                this.form = form
               
            };
                
            },
        
       
      
      
      
        async addFranchise(){
            if(!this.form.whatsapp_number || this.form.whatsapp_number=='' || this.form.whatsapp_number == null ) return this.e("Whatsapp Number is required.") 
            this.isLoading = true
			const res = await this.callApi('post', '/franchisemodule/updateMyFranchises', this.form)
			if(res.status == 200 || res.status == 201){
                this.s("Franchises information has been updated!")
                // this.$router.push('/franchisemodule/franchise-list')
 			}
			else if(res.status==422){
				this.e(res.data.message)
			}
			else {
				this.swr()
			}
			this.isLoading = false
        },
    }
    
}
</script>
<style>
.btn_extend{
    color: #f6f6f6;
    background: #8f5582;
}

</style>
