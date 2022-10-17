
<template>
    <div class="_main_content">
		<div class="row">
            <div class="col-12 col-md-12 col-lg-12">
				<div class="_box _b_radious3 _padd20">
                    <div class="_1card_top _mar_b20">
                        <div class="_1card_top_left">
                            <p class="_1card_top_title">Edit Shipping Price <span v-if="singleFranchise.name">{{singleFranchise.name}}</span></p>
                        </div> 
                    </div>
                    <div  class="row">
                        <div class="col-12 col-md-12 col-lg-12" >
                            <div class="_chose_bulding_info">
                                <p class="_label _mar_b15">Selected Location</p>
                                <ul>
                                    <li style="list-style: none;">  
                                        Location:<span v-if="singleFranchise.address">{{ singleFranchise.address }}</span>
                                    </li>
                                </ul>
                            </div>
					    </div>
                        <div class="col-12 col-md-12 col-lg-12" v-show="markers.length>0" >
                            <div>
                                <GmapMap
                                    :center="myCoordinates"
                                    :zoom ="zoom"
                                    style="width:100%;height:450px;border:0;"
                                    ref="mapReff"
                                > 
                                      <GmapCircle
                                        v-for="(pin, index) in markers"
                                        :key="index"
                                        :position="pin.position"
                                        :draggable="pin.draggable"
                                        @drag="handleDrag"
                                        :center="pin.position"
                                        :radius="pin.distance"
                                        :visible="true"
                                        :options="{fillColor:pin.color,fillOpacity:pin.trans}"
                                        >
                                        </GmapCircle>
                                </GmapMap>
                             </div>
                        </div>
                      
                        <div class="col-12 col-md-4 col-lg-4 _mar_b20" v-if="markers[1]" >
                            <p class="_label _mar_b15">Radius Distance</p>
                            <InputNumber style="width:100%"   :min="0" v-model="markers[1].distance" @on-change="form.sdistance=markers[1].distance"></InputNumber>
                        </div>
                        <div class="col-12 col-md-4 col-lg-4 _mar_b20"  >
                            <p class="_label _mar_b15">Inside Circle Price</p>
                            <InputNumber style="width:100%"  :min="0" v-model="form.price" ></InputNumber>
                        </div>
                        <div class="col-12 col-md-4 col-lg-4 _mar_b20"  >
                            <p class="_label _mar_b15">Outside Circle Price</p>
                            <InputNumber style="width:100%"  :min="0"  v-model="form.outside_price" ></InputNumber>
                        </div>
                    </div>
                    <div class="_1card_top _mar_b20">
                        <div class="_1card_top_left">
                         </div> 
                        <div class="_1card_top_right">
                            <ul class="_1card_top_right_list">
                                <li>
                                    <Button type="primary"  @click="addShippingPrice" :loading="isLoading" :disabled="isLoading">{{isLoading? 'Please wait' : 'Update'}}</Button>
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
        markers: [],
            isLoading:false,
            form:{
                outside_price:0,
                price:0,
                sdistance:0,
                slat:'',
                slng:'',
                id:'',
            },
            address:'',
            franchises:[],
            singleUser:{},
            singleFranchise:{},
            map:null,
			myCoordinates: {
				lat:0,
				lng:0
			},
			zoom:12,
			addrres:false,
            isLoading:false,
            key:'AIzaSyBU7GqUxT98kSlVbD0iFMijQOQFZUbgA7Q',
            auth_user_type:''
        }
    },
    mounted(){ 
		this.$refs.mapReff.$mapPromise.then(map =>this.map =map);
	},
    async created(){
        await this.getAllFranchise()
        

               
    },
    methods:{

        gmapMakerOnClick(){
            console.log("from gmap-onclick")
        },
        async clicked_function(){

        },
        async getAllFranchise(){
            const res = await this.callApi('get', `/franchisemodule/getSingleFranchise?franchiseId=${this.$route.params.id}`)
            if(res.status==200){
                this.singleFranchise = res.data;

            this.myCoordinates = {
                lat:parseFloat(this.singleFranchise.lat),
                lng: parseFloat(this.singleFranchise.long)
            };
            let ob =  {
                position: {
                    lat: parseFloat(this.singleFranchise.lat),
                    lng: parseFloat(this.singleFranchise.long)
                },
                color: 'blue',
                draggable: false,
               
                trans: 0.3,
                distance: parseInt(this.singleFranchise.radious)
                
            };
            let obb =  {
                position: {
                    lat: parseFloat(this.singleFranchise.slat),
                    lng: parseFloat(this.singleFranchise.slng)
                },
                 color: 'red',
                  draggable: true,
                rans: 0.6,
                distance: parseInt(this.singleFranchise.sdistance) == 0 ? 100 :  parseInt(this.singleFranchise.sdistance)
            };
            this.form.sdistance = parseInt(this.singleFranchise.sdistance) == 0 ? 100 :  parseInt(this.singleFranchise.sdistance)
            this.form.slat = parseFloat( this.singleFranchise.slat)
            this.form.slng = parseFloat(this.singleFranchise.slng)
            this.form.price = parseFloat( this.singleFranchise.price)
            this.form.outside_price = parseFloat(this.singleFranchise.outside_price)
            this.form.id = this.singleFranchise.id
            this.markers = [];
            this.markers.push(ob);
            this.markers.push(obb);
            } 
        },
        async handleDrag(value){
           
            
            this.markers[1].position.lat =  value.latLng.lat(),
            this.markers[1].position.lng = value.latLng.lng()
            this.form.slat =  value.latLng.lat(),
            this.form.slng = value.latLng.lng()
		},
        async addShippingPrice(){
            if(!this.form.price || this.form.price=='' || this.form.price == null ) 
            this.form.price = 0 
            if(!this.form.outside_price || this.form.outside_price=='' || this.form.outside_price == null ) 
            this.form.outside_price = 0 
            // if(!this.form.franchise_id || this.form.franchise_id=='' || this.form.franchise_id == null ) return this.e("Select a franchise.")             
            this.isLoading = true
			const res = await this.callApi('post', '/franchisemodule/editShippingPrice', this.form)
			if(res.status == 200 || res.status == 201){
                this.s("Shipping price has been Updated.")
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
		// mapCoordinates(){
		// 	if(!this.map){
		// 		return{
		// 			lat:0,
		// 			lng:0
		// 		}
		// 	}
		// 	return{
		// 		lat:this.map.getCenter().lat(),
		// 		lng:this.map.getCenter().lng()
		// 	}
		// }
	}
    
}
</script>
<style>
.btn_extend{
    color: #f6f6f6;
    background: #8f5582;
}

</style>
