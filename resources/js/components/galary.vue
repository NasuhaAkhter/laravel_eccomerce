<template>
    <div class="_main_content">
		<Modal title="Image"  v-model="galaryModal" :mask-closable="false">        
			<div class="row">
				<div class="col-3 col-md-3 col-lg-3" v-for="(item,index) in getAllGalaryImages" :key="index" style="padding: 5px;">
					<div class="product_image" @click="item.isSlect=!item.isSlect">
						<img :src="item.image">
						<span class="product_image_delete" :class="(item.isSlect)?'new_bal_color':''"> 
							<!-- <Icon @click="deleteProductImage(item.id,index)" type="ios-trash" /> -->
							<Icon  type="md-checkmark-circle" />
						</span>
					</div>	
					<div class="_pagination _padd_t20"  v-if="alldata && alldata.total!=null">
						<Page :total="alldata.total" show-sizer :page-size-opts="pageOption" @on-page-size-change="getSizeChange"  @on-change="paginateDataInfo" />
					</div>					
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
			// galaryModal:false,
            pageOption:[5,10,15,20],
            alldata:{
                total:0,
                data:[]
			},
			page:1,
			temData:[]
        }
	},  
	methods:{
	setGalry(){
		let temp = []
		for(let it of this.alldata.data){
			if(it.isSlect){
				// console.log(it)
				temp.push(it.image)
			}
		}
     	 this.$store.commit('setGalaryImages', temp);
	    this.alldata.data =_.cloneDeep(this.temData)
     	 this.$store.commit('setGalaryModal', false);  
    },
	galaryModalOf(){
		this.alldata.data =_.cloneDeep(this.temData) 
      this.$store.commit('setGalaryModal', false);
    },
		async deleteProductImage(id,index){
			if (!confirm("Are you sure to delete?")) {
                return;
			}
			const res = await this.callApi('post', '/productmodule/deleteProductImage', {id:id})
			if(res.status == 200){
				this.alldata.data.splice(index, 1);
			}
			else if(res.status == 422){
				this.e(res.data.message)   
			}
			else this.swr()
		},
		async getGallery(){
			// const res = await this.callApi('get',`/usermodule/getGalary`)
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
.new_bal_color{
	color: #2cd701 !important;
}
</style>