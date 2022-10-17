
<template>
    <div class="_main_content">
		<div class="row">
            <div class="col-12 col-md-12 col-lg-12">
				<div class="_box _b_radious3 _padd20">
                    <div class="_1card_top _mar_b20">
                        <div class="_1card_top_left">
                            <p class="_1card_top_title">Add Delivery Address</p>
                        </div> 
                    </div>
                    <div  class="row" v-show="singleUser.franchise">
                        <div class="col-12 col-md-4 col-lg-4">
                            <div class="_chose_bulding_info">
                                <p class="_label _mar_b15">Selected Location</p>
                                <ul>
                                    <li>  
                                        Location: <span v-if="!form.address">Please, Select a place</span><span v-if="form.address">{{ form.address }}</span>
                                    </li>
                                </ul>
                            </div>
					    </div>
                        <div class="col-12 col-md-8 col-lg-8">
                            <div>
                                <GmapMap
                                    :center="myCoordinates" 
                                    :zoom ="zoom"
                                    style="width:100%;height:450px;border:0;"
                                    ref="mapRef"
                                   
                                >
                                    <GmapMarker
                                        v-for="(pin, index) in markers"
                                        :key="index"
                                        :position="pin.position"
                                        
                                        :clickable="true"
                                        :draggable="true"
                                         @dragend ="handleDrag"
                                    >
                                    </GmapMarker>
                                      <GmapCircle
                                        v-for="(pin, index) in circle"
                                        :key="index+1"
                                        :center="pin.position"
                                        :radius="pin.distance"
                                        :visible="true"
                                        :options="{fillColor:'red',fillOpacity:.5}"
                                    ></GmapCircle>
                                </GmapMap>
                             </div>
                        </div>
                        <div class="col-12 col-md-6 col-lg-6 _mar_b20"  >
                            <p class="_label _mar_b15">Title</p>
                            <Input type="text" v-model="form.title"  />
                        </div>
                        <div class="col-12 col-md-6 col-lg-6 _mar_b20"  >
                            <p class="_label _mar_b15">City</p>
                            <Input type="text" disabled v-model="form.city"  />
                        </div>
                        <div class="col-12 col-md-6 col-lg-6 _mar_b20"  >
                            <p class="_label _mar_b15">Country</p>
                            <Input disabled v-model="form.country"  />
                        </div>
                        <div class="col-12 col-md-6 col-lg-6 _mar_b20"  >
                            <p class="_label _mar_b15">Country Code</p>
                            <Input disabled v-model="form.countryCode"  />
                        </div>
                        <div class="col-12 col-md-6 col-lg-6 _mar_b20"  >
                            <p class="_label _mar_b15">Phone</p>
                            <!-- <InputNumber style="width:100%"  placeholder="$0.00" :max="100" :min="0" :formatter="value => `+880${value}`"
                            :parser="value => value.replace('', '+880')"
                            v-model="form.phone"  :editable=true>
                            </InputNumber> -->
                            <Input   style="width:100%" :min="0" v-model="form.phone"/> 
                           
                        </div>
                        <div class="col-12 col-md-6 col-lg-6 _mar_b20">
                            <p class="_label _mar_b15">Address</p>
                            <Input v-model="form.address" disabled type="textarea" :autosize="{minRows: 2,maxRows: 5}" placeholder="Address"/>
                        </div>  
                        <div class="col-12 col-md-6 col-lg-6 _mar_b20"  >
                            <p class="_label _mar_b15">Apartment number</p>
                            <Input type="text" v-model="form.apartment_number"  />
                        </div>
                    </div>
                    <div class="_1card_top _mar_b20">
                        <div class="_1card_top_left">
                         </div> 
                        <div class="_1card_top_right">
                            <ul class="_1card_top_right_list">
                                <li>
                                    <Button type="primary"  @click="addAddress" :loading="isLoading" :disabled="isLoading">{{isLoading? 'Please wait' : 'Create'}}</Button>
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
            // markers:[],
            isLoading:false,
            form:{
                countryCode:'+880',
                phone:0,
                country:'Bangladesh',
                title:'',
                city:'',
                lat:'',
                long:'',
                apartment_number:'',
                address:'',
                user_id:'',
            },
            address:'',
            markers : [],
            circle : [],
            franchises:[],
            singleUser:{},
            addressDistance:0,
            map:null,
			myCoordinates: {
				lat: 0,
				lng:0
			},
			zoom:15,
			addrres:false,
            isLoading:false,
            key:'AIzaSyBU7GqUxT98kSlVbD0iFMijQOQFZUbgA7Q',
            auth_user_type:'',
            id:0
        }
    },
    mounted(){ 
		this.$refs.mapRef.$mapPromise.then(map =>this.map =map);
	},
    async  created(){
        if(this.$route.query && this.$route.query.userId){
                this.id = this.$route.query.userId
		}
        this.auth_user_type =authUser.userType
        const res = await this.callApi('get',`/usermodule/getUserInfo/${this.id}`)
        if(res.status == 200){
            this.singleUser=res.data

            this.myCoordinates = {
                lat: parseFloat(this.singleUser.franchise.lat) ,
				lng : parseFloat(this.singleUser.franchise.long)
            };
            
             let ob =  {
                position: {
                    lat:parseFloat(this.singleUser.franchise.lat) ,
				    lng:  parseFloat(this.singleUser.franchise.long)
                }
            };
             let obb =  {
                position: {
                    lat:parseFloat(this.singleUser.franchise.lat) ,
				    lng:  parseFloat(this.singleUser.franchise.long)
                },
                distance:  parseInt(this.singleUser.franchise.radious)
            };
            this.markers = [];
            this.markers.push(ob);
            this.circle = [];
            this.circle.push(obb);
        }
        else this.swr();
         
    },
    methods:{
         
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
            this.form.city = response.data.results[0].address_components[3].long_name
            this.form.address = response.data.results[2].formatted_address
            // console.log(this.address, 'address')           
            }).catch(er => {
                console.log(er)
            })
        },
        async handleDrag(value){
            // console.log(this.map.getCenter().lat() , 'center')
			let center ={
				lat: value.latLng.lat(),
				lng: value.latLng.lng()
            };
            //  (Math.acos(Math.sin(this.singleUser.franchise.lat)*Math.sin(center.lat)+Math.cos(this.singleUser.franchise.lat)*Math.cos(center.lat)*Math.cos(this.singleUser.franchise.long-center.lng)));
            let d =((((Math.acos(Math.sin((this.singleUser.franchise.lat*Math.PI/180)) * Math.sin((center.lat*Math.PI/180))+Math.cos((this.singleUser.franchise.lat*Math.PI/180)) * Math.cos((center.lat*Math.PI/180)) * Math.cos(((this.singleUser.franchise.long-center.lng)*Math.PI/180))))*180/Math.PI)*60*1.1515*1.609344)*0.621371)
            d = d * 1.6
            console.log('distance',d);
            this.addressDistance = d;
            if(this.addressDistance > this.singleUser.franchise.radious) return this.i("Delivery address is out of franchise bounds!")
			axios.get(`https://maps.googleapis.com/maps/api/geocode/json?latlng=${center.lat},${center.lng}&key=${this.key}`)
			.then((response) => {
                this.form.lat =  center.lat
                this.form.long =  center.lng
            this.form.city = response.data.results[0].address_components[3].long_name
            this.form.address = response.data.results[2].formatted_address
            console.log(this.address, 'address')
            }).catch(er => {
                console.log(er)
            })
		},
        async addAddress(){
            if(!this.form.title || this.form.title=='' || this.form.title == null ) return this.e("Title is required.") 
            if(!this.form.address || this.form.address=='' || this.form.address == null ) return this.e("Select an address.")             
            if(!this.form.phone || this.form.phone=='' || this.form.phone == null ) return this.e("Phone number is required.")             
            if(this.addressDistance > this.singleUser.franchise.radious) return this.e("Delivery address is out of franchise bounds!")         
            let d = ((((Math.acos(Math.sin((this.franchiseDetails.lat*Math.PI/180)) * Math.sin((this.form.lat*Math.PI/180))+Math.cos((this.franchiseDetails.lat*Math.PI/180)) * Math.cos((this.form.lat*Math.PI/180)) * Math.cos(((this.franchiseDetails.long-this.form.long)*Math.PI/180))))*180/Math.PI)*60*1.1515*1.609344)*0.621371);
            d = d * 1.6 *1000;
            console.log('distance',d);
            console.log('this.franchiseDetails.radious',this.franchiseDetails.radious);
            if(d > this.franchiseDetails.radious) return this.i("Delivery address is out of franchise bounds!");
           
            this.isLoading = true
            this.form.user_id = this.id

			const res = await this.callApi('post', '/usermodule/addDeliveryAddress', this.form)
			if(res.status == 200 || res.status == 201){
                this.s("New delivery address has been added.")
                this.$router.push(`/usermodule/delivery_address/${this.id}`)
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
}
</script>
<style>
.btn_extend{
    color: #f6f6f6;
    background: #8f5582;
}

</style>
