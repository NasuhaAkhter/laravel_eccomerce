
<template>
    <div class="_main_content">
		<div class="row">
            <div class="col-12 col-md-12 col-lg-12">
				<div class="_box _b_radious3 _padd20">
                    <div class="_1card_top _mar_b20">
                        <div class="_1card_top_left">
                            <p class="_1card_top_title">Add Shipping Price</p>
                        </div> 
                    </div>
                    <div  class="row">
                          <div class="col-12 col-md-12 col-lg-12 _mar_b20"  v-if="auth_user_type == 'Admin'">
                            <p class="_label _mar_b15">Select Franchise</p>
                            <Select v-model="form.franchise_id" filterable @on-change="onFranchiseChange">
                                <Option v-for="(option, index) in franchises" :value="option.id" :key="index" >{{option.name}}</Option>
                            </Select>
                        </div>
                        <div class="col-12 col-md-4 col-lg-4" v-if="form.franchise_id">
                            <div class="_chose_bulding_info">
                                <p class="_label _mar_b15">Selected Location</p>
                                <ul>
                                    <li>  
                                        Location: <span v-if="!address">Please, Select a place</span><span v-if="address">{{ address }}</span>
                                    </li>
                                </ul>
                            </div>
					    </div>
                        <div class="col-12 col-md-8 col-lg-8" v-show="form.franchise_id">>
                            <div>
                                <GmapMap
                                    :center="myCoordinates"
                                    :zoom = "zoom"
                                    style="width:100%;height:450px;border:0;"
                                    ref="mapRef"
                                > 
                                      <!-- <GmapMarker
                                        :key="index"
                                        v-for="(pin, index) in markers"
                                        :position="pin.position"
                                        
                                        :clickable="true"
                                        :draggable="true"
                                        @click="clicked_function"
                                        @drag="handleDrag"
                                        >
                                        </GmapMarker> -->
                                      <GmapCircle
                                        :key="index"
                                        v-for="(pin, index) in markers"
                                        :position="pin.position"
                                        
                                        :clickable="true"
                                        :draggable="true"
                                        @click="clicked_function"
                                        @drag="handleDrag"
                                        :center="pin.position"
                                        :radius="form.distance"
                                        :visible="true"
                                        :options="{fillColor:'red',fillOpacity:0.5}"
                                        >
                                        </GmapCircle>
                                </GmapMap>
                             </div>
                        </div>
                      
                        <div class="col-12 col-md-6 col-lg-6 _mar_b20"  >
                            <p class="_label _mar_b15">Radius Distance</p>
                            <InputNumber style="width:100%"   :min="0" v-model="form.distance" ></InputNumber>
                        </div>
                        <div class="col-12 col-md-6 col-lg-6 _mar_b20"  >
                            <p class="_label _mar_b15">Inside Circle Price</p>
                            <InputNumber style="width:100%"  :min="0" v-model="form.price" ></InputNumber>
                        </div>
                        <div class="col-12 col-md-6 col-lg-6 _mar_b20"  >
                            <p class="_label _mar_b15">Outside Circle Price</p>
                            <InputNumber style="width:100%"  :min="0" 
                            
                            v-model="form.outside_price" >
                            </InputNumber>
                        </div>
                        <div class="col-12 col-md-6 col-lg-6 _mar_b20">
                            <p class="_label _mar_b15">Address</p>
                            <Input v-model="address" disabled type="textarea" :autosize="{minRows: 2,maxRows: 5}" placeholder="Address"/>
                        </div>  
                    </div>
                    <div class="_1card_top _mar_b20">
                        <div class="_1card_top_left">
                         </div> 
                        <div class="_1card_top_right">
                            <ul class="_1card_top_right_list">
                                <li>
                                    <Button type="primary"  @click="addShippingPrice" :loading="isLoading" :disabled="isLoading">{{isLoading? 'Please wait' : 'Create'}}</Button>
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
    // components: {GmapMarker},
    data(){
        return{
        markers: [{
            position: {
              lat: 0,
              lng: 0
            }
          }],
            isLoading:false,
            form:{
                outside_price:0,
                price:0,
                distance:30,
                lat:'',
                long:'',
                franchise_id:'',
            },
            address:'',
            franchises:[],
            singleUser:{},
            map:null,
			myCoordinates: {
				lat: 0,
				lng:0
			},
			zoom:7,
			addrres:false,
            isLoading:false,
            key:'AIzaSyBU7GqUxT98kSlVbD0iFMijQOQFZUbgA7Q',
            auth_user_type:''
        }
    },
    mounted(){ 
		this.$refs.mapRef.$mapPromise.then(map =>this.map =map);
	},
    created(){
        this.auth_user_type =authUser.userType 
        if(this.auth_user_type !='Admin'){
            this.form.franchise_id = authUser.franchise_id
        }else{
            this.getAllFranchise()
        }
        // localStorage.setItem('center',{lat:'25.39740399993989',lng:'90.93275639065861'})
        // localStorage.setItem('zoom', 7)
        // localStorage.removeItem('map')
      if(localStorage.center){
 			 this.myCoordinates = JSON.parse(localStorage.center);
		 }else{
             this.$getLocation({})
            .then(coordinates =>{
                this.myCoordinates =coordinates;
			})
			.catch(error => alert(error));
		 }
		 if(localStorage.zoom){
			 this.zoom = parseInt(localStorage.zoom);
		 }  
    },
    methods:{
        async onFranchiseChange(id){
            let index = this.franchises.findIndex(d=>d.id == id);
            this.coordinates = {
                lat: this.franchises[index].lat,
                lng: this.franchises[index].long
            };
            let ob =  {
                position: {
                    lat: this.franchises[index].lat,
                    lng: this.franchises[index].long
                }
            };
            this.markers = [];
            this.markers.push(ob);
        },
        gmapMakerOnClick(){
            console.log("from gmap-onclick")
        },
        async clicked_function(){
            let center ={
				lat: this.map.getCenter().lat(),
				lng: this.map.getCenter().lng()
            };
            let zoom =this.map.getZoom();
			localStorage.center =JSON.stringify(center);
            localStorage.zoom =zoom;
            axios.get(`https://maps.googleapis.com/maps/api/geocode/json?latlng=${this.mapCoordinates.lat},${this.mapCoordinates.lng}&key=${this.key}`)
			.then((response) => {
                this.form.lat =  this.mapCoordinates.lat
                this.form.long =  this.mapCoordinates.lng
            // this.address = response.data.results[0].address_components[0].long_name
            this.address = response.data.results[2].formatted_address
            console.log(this.address, 'address')            }).catch(er => {
                console.log(er)
            })
        },
        async getAllFranchise(){
            const res = await this.callApi('get', `/franchisemodule/allfranchises`)
            if(res.status==200){
                this.franchises = res.data.data
            } 
        },
        async handleDrag(){
            // console.log(this.map.getCenter().lat() , 'center')
			let center ={
				lat: this.map.getCenter().lat(),
				lng: this.map.getCenter().lng()
            };
 			let zoom =this.map.getZoom();
			localStorage.center =JSON.stringify(center);
            localStorage.zoom =zoom;
			axios.get(`https://maps.googleapis.com/maps/api/geocode/json?latlng=${this.mapCoordinates.lat},${this.mapCoordinates.lng}&key=${this.key}`)
			.then((response) => {
                this.form.lat =  this.mapCoordinates.lat
                this.form.long =  this.mapCoordinates.lng
            // this.address = response.data.results[0].address_components[0].long_name
            this.address = response.data.results[2].formatted_address
            console.log(this.address, 'address')
            }).catch(er => {
                console.log(er)
            })
		},
        async addShippingPrice(){
            if(!this.form.price || this.form.price=='' || this.form.price == null ) return this.e("Price is required.") 
            if(!this.form.franchise_id || this.form.franchise_id=='' || this.form.franchise_id == null ) return this.e("Select a franchise.")             
            this.isLoading = true
			const res = await this.callApi('post', '/franchisemodule/addShippingPrice', this.form)
			if(res.status == 200 || res.status == 201){
                this.s("Shipping price has been created.")
                this.$router.push('/franchisemodule/shipping_price')
 			}
			else if(res.status==422){
				this.e(res.data.message)
			}
			else {
				this.swr()
			}
			this.isLoading = false
        },
    },
    computed:{
		mapCoordinates(){
			if(!this.map){
				return{
					lat:0,
					lng:0
				}
			}
			return{
				lat:this.map.getCenter().lat(),
				lng:this.map.getCenter().lng()
			}
		}
	}
    
}
</script>
<style>
.btn_extend{
    color: #f6f6f6;
    background: #8f5582;
}

</style>
