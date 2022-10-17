
<template>
    <div class="_main_content">
		<div class="row">
            <div class="col-12 col-md-12 col-lg-12">
				<div class="_box _b_radious3 _padd20">
                    <div class="_1card_top _mar_b20">
                        <div class="_1card_top_left">
                            <p class="_1card_top_title">Add Franchise</p>
                        </div> 
                    </div>
                    <div  class="row">
                        <div class="col-12 col-md-8 col-lg-8 _mar_b20">
                            <p class="_label _mar_b15">Name</p>
                            <input type="text" v-model="form.name" class="form-control" placeholder="Name">
                        </div>
                         <div class="col-12 col-md-4 col-lg-4 _mar_b20">
                            <p class="_label _mar_b15">Phone</p>
                            <input type="text" v-model="form.phone" class="form-control" placeholder="Phone">
                        </div> 
                         
                       
                        <div class="col-12 col-md-4 col-lg-4 _mar_b20">
                            <p class="_label _mar_b15">Franchise Owner</p>
                            <Select v-model="form.user_id" filterabluserse>
                                <Option v-for="(option, index) in users" :value="option.id" :key="index" >{{option.name}}</Option>
                            </Select>
                        </div>
                         <div class="col-12 col-md-8 col-lg-8 _mar_b20">
                            <p class="_label _mar_b15">City</p>
                            <gmap-autocomplete class="form-control"  @place_changed="autoCompleteChange" >
                                        <template v-slot:input="slotProps">
                                            <v-text-field outlined
                                                        prepend-inner-icon="place"
                                                        placeholder="Location Of Event"
                                                        ref="input"
                                                        v-on:listeners="slotProps.listeners"
                                                        v-on:attrs="slotProps.attrs">
                                            </v-text-field>
                                        </template>
                            </gmap-autocomplete>
                            <!-- <input type="text" v-model="form.city" class="form-control" placeholder="City" @keyup="autocompleteSearch" ref="maplocation" > -->
                        </div>
                        <div class="col-12 col-md-3 col-lg-3 _mar_b20">
                            <p class="_label _mar_b15">Country</p>
                            <input type="text" disabled v-model="form.country" class="form-control" placeholder="Country">
                        </div>
                        
                        <div class="col-12 col-md-3 col-lg-3 _mar_b20">
                            <p class="_label _mar_b15">Address</p>
                            <Input v-model="form.address" disabled type="textarea" :autosize="{minRows: 2,maxRows: 5}" placeholder="Address"/>
                        </div>
                        <div class="col-12 col-md-3 col-lg-3 _mar_b20">
                            <p class="_label _mar_b15">Radius Distance</p>
                            <InputNumber style="width:100%"   :min="0" v-model="form.radious" ></InputNumber>
                        </div> 
                        <div class="col-12 col-md-3 col-lg-3 _mar_b20"  >
                            <p class="_label _mar_b15">Target Price</p>
                            <InputNumber style="width:100%" 
                            :min="0" v-model="form.target_price" 
                            :formatter="value => `${value} ৳`"
                             
                            ></InputNumber>
                        </div>
                        <div class="col-12 col-md-12 col-lg-12" v-show="form.city">
                            <div>
                                <GmapMap
                                    :center="myCoordinates"
                                    :zoom = "zoom"
                                    style="width:100%;height:450px;border:0;"
                                    ref="mapRef"
                                > 
                                      <GmapCircle
                                        v-for="(pin, index) in markers"
                                        :key="index"
                                        :position="pin.position"
                                        :clickable="true"
                                        :draggable="true"
                                        @click="clicked_function"
                                        @drag="handleDrag"
                                        :center="pin.position"
                                        :radius="form.radious"
                                        :visible="true"
                                        :options="{fillColor:'blue',fillOpacity:0.4}"
                                        >
                                        </GmapCircle>
                                </GmapMap>
                             </div>
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
export default {
    data(){
        return{
            markers: [{
                position: {
                lat: 0,
                lng: 0
                }
            }],
            isLoading:false,
            map:null,
            form:{
				name:'',
                phone:'',
                countryCode:'+88', 
                address:'',
                city:'Bangladesh',
                lat:'',
                long:'',
                slat:'',
                slng:'',
                radious:1000,
                country:'',
                user_id:'',
                target_price:0,
            },
            users:[],
            allCountry:[],
			singleUser:{},
            zoom:12,
            myCoordinates: {
				lat: 0,
				lng:0
			},
        }
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
	},
    async created(){
        const [res,res2] = await Promise.all([
            this.callApi('get','/usermodule/getNewFranchiseOwner'),
            this.callApi('get','/usermodule/getAllCountry'),
        ]);
        if(res.status == 200 && res2.status ==200){
            this.allCountry = res2.data;
            this.users = res.data
        }
        else this.swr();
    },
    mounted(){ 
		this.$refs.mapRef.$mapPromise.then(map =>this.map =map);
	},
    methods:{
        autoCompleteChange(autocomplete){
            console.log("amCalling")
            console.log(autocomplete)
            this.myCoordinates = {
                lat: autocomplete.geometry.location.lat(),
				lng: autocomplete.geometry.location.lng()
            };
            
             let ob =  {
                position: {
                    lat: autocomplete.geometry.location.lat(),
				    lng: autocomplete.geometry.location.lng()
                }
            };
            this.markers = [];
            this.markers.push(ob);
            this.form.lat = autocomplete.geometry.location.lat(),
            this.form.slat = autocomplete.geometry.location.lat(),
            this.form.long = autocomplete.geometry.location.lng()
            this.form.slng = autocomplete.geometry.location.lng()
            this.form.city = autocomplete.name
            this.form.address = autocomplete.formatted_address
            this.form.country = autocomplete.address_components[autocomplete.address_components.length-1].long_name
            if(this.form.country){
                let index =this.allCountry.findIndex(d=>d.name == this.form.country);
                this.form.countryCode = this.allCountry[index].code
            }
        },
         async clicked_function(){
        //     let center ={
		// 		lat: this.map.getCenter().lat(),
		// 		lng: this.map.getCenter().lng()
        //     };
        //    console.log(this.map);
        },
        async handleDrag(value){
            // console.log(this.map.getCenter().lat() , 'center')
			let center ={
				lat: value.latLng.lat(),
				lng: value.latLng.lng()
            };
            this.form.lat =  value.latLng.lat(),
            this.form.long = value.latLng.lng()
            console.log(this.form)
		},
        async selectCoustomer(item){
			this.singleUser = this.users[item.value]
			this.form.user_id = this.singleUser.id 
        },
        async getAllCoustomer(e=''){
            if(e){
                this.str2 = e
            }
           const res = await this.callApi('get', ``)
           if(res.status==200){
                this.users = res.data
            } 
        },
        async addFranchise(){
            if(!this.form.name || this.form.name=='' || this.form.name == null ) return this.e("Franchise name is required.") 
            if(!this.form.country || this.form.country=='' || this.form.country == null ) return this.e("Country is required.") 
            if(!this.form.countryCode || this.form.countryCode=='' || this.form.countryCode == null ) return this.e("Country code is required.") 
            if(!this.form.phone || this.form.phone=='' || this.form.phone == null ) return this.e("Phone is required.") 
            if(!this.form.city || this.form.city=='' || this.form.city == null ) return this.e("City is required.")             
            if(!this.form.user_id || this.form.user_id=='' || this.form.user_id == null ) return this.e("Franchise owner is required.")             
            this.isLoading = true
			const res = await this.callApi('post', '/franchisemodule/createNewFranchises', this.form)
			if(res.status == 200 || res.status == 201){
                this.s("New Franchises has been created")
                this.$router.push('/franchisemodule/franchise-list')
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
