<template>
    <div>
        <div class="row">
            <div class="col-12 col-md-12 col-lg-12" v-if="address"> 
                <div class=" _b_radious3 _padd20">
                     <Card >
                        <p slot="title" > Order Address </p>
                            <div class="row" >
                                <div class="col-12 col-md-6 col-lg-6">
                                </div>
                                <div class="col-12 col-md-6 col-lg-6" v-if="authInfo.userType != 'ShopOwner'"  @click="openAddressModal(address.id)">
                                    <Button class="" >Change address</Button>
                                </div>
                            </div>
                        <ul class="_padd_l10" v-if="address">
                            <li>
                                <span >
                                City Name:  {{address.city}}
                                </span>
                            </li>
                            <li>
                                <span>
                                Phone Number:  {{address.phone}}
                                </span>
                            </li>
                            <li>
                                <span>
                                    {{ address.country }}
                                </span>
                            </li>
                            <li>
                                <span>
                                State  Name:  {{address.state}}
                                </span>
                            </li>
                            
                            <li>
                                <span>
                                Address :  {{address.address}}
                                </span>
                            </li>
                            
                        </ul>
                     </Card>
                </div>
            </div>
            <p v-else>{{"Loading.."}}</p>
            <!-- <div class="col-12 col-md-6 col-lg-6" v-if="driver">
                <div class=" _b_radious3 _padd20">
                     <Card >
                        <p slot="title" >
                            Driver Details                            
                        </p>
                            <div class="row" v-if="driver">
                                    <div class="col-12 col-md-6 col-lg-6">
                                        
                                    </div>
                                    <div class="col-12 col-md-6 col-lg-6" v-if="authInfo.userType != 'ShopOwner'"  @click="
                                    (driver.id)">
                                        <Button class="" >Change Deriver</Button>
                                    </div>

                            </div>
                        <ul class="_padd_l10" v-if="driver">
                            <li>
                                <span >
                                Driver Name:  {{driver.name}}
                                </span>
                            </li>
                            <li>
                                <span >
                                City Name:  {{driver.city}}
                                </span>
                            </li>
                            <li>
                                <span>
                                Phone Number:  {{driver.phone}}
                                </span>
                            </li>
                            <li>
                                <span>
                                   Country Name: {{ driver.country }}
                                </span>
                            </li>
                            <li>
                                <span>
                                State  Name:  {{driver.state}}
                                </span>
                            </li>
                            
                            <li>
                                <span>
                                Address :  {{driver.address}}
                                </span>
                            </li>
                            
                        </ul>
                     </Card>
                </div>
            </div> -->
            <!-- <p v-else>{{ "Loading.."}}</p> -->
        </div>
        <Modal title="Coustomer address"  v-model="driverModal"  :mask-closable="false" >
                <Form>
                         <FormItem prop="name" :error="error.driver_id"    label="Driver Name">
                            <Select
                                    v-model="driver_id"
                                    filterable
                                    :remote-method="getAllDrivers"
                                >
                                <Option v-for="(item, index) in users" :value="item.id" :key="index" :label="item.name">{{item.name}}</Option>
                            </Select>
                </FormItem>
                </Form>
                <div slot="footer">
            <Button   @click="driverModal=false" >Cancel</Button>
            <Button type="primary" @click="updateDriver"  :loading="isLoading" :disabled="isLoading">{{isLoading? 'Please wait' : 'add'}}</Button>
        </div>
        </Modal>
        <Modal title="Coustomer address"  v-model="addressModal" fullscreen  :mask-closable="false" >
            <Form>

            <FormItem prop="name"     label="" v-if="addressData ">
                        <div class="row" >
                        <div class="col-6 col-md-6 col-lg-6" v-for="(item, indexx) in addressData" :value="indexx" :key="indexx" @click="selectedDeleveryIndex(item.id)">
                            
                                    <!-- deliverIndex=index,  -->

                                <Card style="width:350px" :class="(item.id==address_id)?'card_active':''"  >
                                
                                
                                    <ul class="_padd_l10">
                                        <li>
                                            <span >
                                            City Name:  {{item.city}}
                                            </span>
                                        </li>
                                        <li>
                                            <span>
                                            Phone Number:  {{item.phone}}
                                            </span>
                                        </li>
                                        <li>
                                            <span>
                                                {{ item.country }}
                                            </span>
                                        </li>
                                        <li>
                                            <span>
                                            State  Name:  {{item.state}}
                                            </span>
                                        </li>
                                        
                                        <li>
                                            <span>
                                            Address :  {{item.address}}
                                            </span>
                                        </li>
                                    </ul>
                                </Card>
                            
                        </div>
                        </div>

                        
                    
                    </FormItem>
            </Form>
            
            <div slot="footer">
                <Button   @click="addressModal=false" >Cancel</Button>
                <Button type="primary" @click="updateAddress"  :loading="isLoading" :disabled="isLoading">{{isLoading? 'Please wait' : 'add'}}</Button>
            </div>
        </Modal>
    </div>
</template>
<script>

export default {
    props:['data1'],
    data(){
        return {
            form:{
                order_id:'',
                address_id:'',
            },
            error:{driver_id:''},
            addressModal:false,
            isLoading:false,
            driverModal:false,
            address_id:'',
            driver_id:'',
            addressData:{},
            adIndex:-1,
            address:{}, 
            driver:{},
            users:[]
        }
    },
    computed:{
    },
    methods:{
        openAddressModal(id){
            this.address_id = id
            // this.form.address_id = id
            this.addressModal = true
        },
        orderDataIndex(){
            for(let i in this.addressData){
                if(this.address_id == this.addressData[i].address_id){
                    this.adIndex = i
                //    console.log(this.addressData[i], "dkjfksdjklfjksldfjl 985")
                   break
                }
            }
        },
        async assingValues1(id){
            // this.i("dkldsjflkjkls")
           this.address_id = id
        },
        async getOrderAddress(){
            this.address =this.data1
             const res = await this.callApi('get', `/ordermodule/getOrderAddress/${this.$route.params.id}`);
            if(res.status == 200){
                this.addressData = res.data
            }
            else if(res.status==422){
                this.e(res.data.message)
            }
            else{
                this.swr()
            }
        },
        async getDriver(){
            const res = await this.callApi('get', `/ordermodule/getOrderDriver/${this.$route.params.id}`);
            if(res.status == 200){
                // console.log("hello")
                this.driver = res.data
            }
            else if(res.status==422){
                this.e(res.data.message)
            }
            else{
                this.swr()
            }
        },
        async getAllDrivers(){
             const res = await this.callApi('get', `/ordermodule/getAllDrivers`);
            if(res.status == 200){
                this.users = res.data.data
            }
            else if(res.status==422){
                this.e(res.data.message)
            }
            else{
                this.swr()
            }
        },
        async updateDriver(){
            this.isLoading = true
             const res = await this.callApi('post', `/ordermodule/updateDriver`, {order_id:this.$route.params.id,driver_id:this.driver_id });
            if(res.status == 200){
                this.driver = res.data
                this.s("Order driver has been updated")
                this.driverModal = false;
            }
            else if(res.status==422){
                this.e(res.data.message)
            }
            else{
                this.swr()
            }
            this.isLoading = false
        },
        async updateAddress(){
            
            const res = await this.callApi('post', `/ordermodule/updateDeleveryAddress`,{order_id:this.$route.params.id,address_id:this.address_id });
            if(res.status == 200){
                this.address = res.data 
                this.s("Order address has been updated")
                this.addressModal = false;
            }
        },
        selectedDeleveryIndex(id){
            this.address_id = id
        },
        openDriverModal(id){
            this.driver_id = id
            this.driverModal = true;
        }
    },
   async mounted () {
       this.address =this.data1

 
    },

   async created(){
       
        this.getOrderAddress()
        this.getDriver()
    }
}
</script>