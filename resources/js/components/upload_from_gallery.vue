<template>
    <div class="_main_content">
		<Modal title="Image"  v-model="galaryModal" :mask-closable="false" :closable="false">        
			<div class="row">
				<div class="col-3 col-md-3 col-lg-3" v-for="(item,index) in getAllGalaryImages" :key="index" style="padding: 5px;">
					<div class="product_image" @click="selectedIndex = index">
						<img :src="item.image">
						<span v-if="selectedIndex == index" class="product_image_delete new_color" > 
 							<Icon type="md-checkmark-circle" />
						</span>
					</div>						
				</div>
				<div class="_pagination _padd_t20"  v-if="alldata && alldata.total!=null">
					<Page :total="alldata.total" show-sizer :page-size-opts="pageOption" @on-page-size-change="getSizeChange"  @on-change="paginateDataInfo" />
				</div>
			</div> 
		  <div slot="footer">
              <Button   @click="galaryModalOf">close</Button>
              <Button   @click="setGalry">Add Image</Button>
          </div>
        </Modal>
    </div>
</template>
<script>
import { mapActions, mapGetters } from 'vuex';
export default {
computed: {
    ...mapGetters({
		  galaryModal:'getGalaryModal',
		  getAllGalaryImages:'getAllGalaryImages',
        }),
    },
    data(){
        return{
			pageOption:[5,10,15,20,30],
            alldata:{
                total:0,
                data:[]
			},
			page:1,
		    pageSize:10,
            temData:[],
            selectedIndex : -1
        }
	},  
	methods:{
		 getSizeChange(e){
			this.selectedIndex =  -1
			this.pageSize =e
			this.paginateDataInfo(1)
		},
		paginateDataInfo(e){
			this.selectedIndex =  -1
			this.page = e
			this.getGallery()
        },
	setGalry(){
		let temp = []
		 temp.push(this.getAllGalaryImages[this.selectedIndex])
     	 this.$store.commit('setGalaryImages', temp);
	    this.alldata.data =_.cloneDeep(this.temData)
     	 this.$store.commit('setGalaryModal', false);  
    },
	galaryModalOf(){
		this.alldata.data =_.cloneDeep(this.temData) 
      this.$store.commit('setGalaryModal', false);
    },
		 
		async getGallery(){
			// const res = await this.callApi('get',`/usermodule/getGalary`)
			// if(res.status == 200){
			// 	// for(let item of res.data){
			// 	// 	item.isSlect = false
			// 	// }
			// 	this.alldata.data = _.cloneDeep(res.data)
			// 	this.temData = _.cloneDeep(res.data)
			// 	this.alldata.total = res.data.total
			// 	this.$store.commit('setAllGalaryImages', res.data);
			// }
			const res = await this.callApi('post', `/franchisemodule/getAllGallery?page=${this.page}&pageSize=${this.pageSize}`)
			if(res.status == 200){
				for(let item of res.data.data){
					item.isSlect = false
				}
				this.alldata.data = _.cloneDeep(res.data.data)
				this.temData = _.cloneDeep(res.data.data)
				this.alldata.total = res.data.total
				this.$store.commit('setAllGalaryImages', res.data.data);
			}
		}
	},
    async created(){
		let id  = this.$route.params.id
		this.getGallery()
	},
	watch: {
		"this.galaryModal": function () {
				
				
		},
	}
    
}
</script>


<style>
.product_image{
	position: relative;
	
}
.product_image_delete{
	position: absolute;
    background: #e7eced;
    border: 1px solid #777;
	color: #777;
    bottom: 0;
    right: 0;
    padding: 5px 5px;
    cursor: pointer;
}
.new_color{
	color: #2cd701 !important;
}
</style>