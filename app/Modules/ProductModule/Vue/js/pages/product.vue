<template>
    <div class="_main_content">
		<div class="row">
            <div class="col-12 col-md-12 col-lg-12">
				<div class="_box _b_radious3 _padd20">
                    <div class="_1card_top _mar_b20">
							<div class="_1card_top_left">
								<p class="_1card_top_title">Product List</p>
								<div class="_1card_top_search">
									<Input @on-change="searchResult" v-model="str" search enter-button="Search" placeholder="Enter something..." />
								</div>
								<div class="_1card_top_search">
 									 <Select v-model="filtrBy" style="width:200px" @on-change="filterResult"  placeholder="Filter by..." > 
										<Option value="featured">Featured</Option>
										<Option value="non-featured" >Non featured</Option>
										<Option value="all">All</Option>
									</Select>
								</div>
							</div>
							<div class="_1card_top_right">
								<ul class="_1card_top_right_list">
									<li><Button type="primary" @click="$router.push('/productmodule/addProduct')">Add</Button></li>
								</ul>
							</div> 
						</div>
						<div class="_table_responsive">
							<Table class="_table1700" border :columns="columns" :data="alldata.data">
								<template slot="shop" slot-scope="{ row }">
									<p >{{row.name}} </p>
 								</template>
								<template slot="product" slot-scope="{ row }">
									<p v-if="row.brand && row.brand.name">{{row.name}} ( {{row.brand.name}} )</p>
                                    <p v-else>{{row.name}}</p>
								</template>
								<template slot="category" slot-scope="{ row }">
									<p v-if="row.category && row.category.name">{{row.category.name}}</p>
								</template>
								<template slot="subCategory" slot-scope="{ row }">
									<p v-if="row.sub_category && row.sub_category.name">{{row.sub_category.name}}</p>
								</template>
								<template slot="status" slot-scope="{ row , index }">
									  <i-switch :loading="switchLoading" v-model="row.must_prescribed" @on-change="availabilityChange(row,index)" :true-value="1" :false-value="0"  true-color="#13ce66" false-color="#ff4949"    size="large">
										<span slot="open" >YES</span>
										<span slot="close"  >NO</span>
									</i-switch>
								</template>
								<!-- <template slot="featured" slot-scope="{ row  }">
									  <i-switch  disabled v-model="row.isFeatured"  :true-value="1" :false-value="0"  true-color="#13ce66" false-color="#ff4949" size="large">
										<span slot="open" >YES</span>
										<span slot="close"  >NO</span>
									</i-switch>
								</template> -->
								<!-- <template slot="brand" slot-scope="{ row }">
									<p v-if="row.brand && row.brand.name">{{row.brand.name}}</p>
								</template> -->
								<template slot="pshop" slot-scope="{ row }">
									<p v-if="row.pshop && row.pshop.name">{{row.pshop.name}}</p>
								</template>
								<template slot="franchise" slot-scope="{ row }">
									<p v-if="row.franchise && row.franchise.name">{{row.franchise.name}}</p>
								</template>
                                <template slot="descritpion" slot-scope="{ row }">
									<Button @click="showDescriptions(row.description)">Show</Button>
								</template>
								<template slot="action" slot-scope="{ row, index }">
									<Button type="primary" @click="$router.push('/productmodule/product-images/'+row.id)">Images</Button>
									<!-- <Button type="primary" @click="showDetails(row)">Details</Button> -->
									<a :href="`/productmodule/product-variation/${row.id}`" target="_blank" ><Button type="primary" >Variations</Button></a>
                                    <Button type="primary" @click="$router.push(`/productmodule/editProduct/${row.id}`)">Edit</Button>
									<Button type="error" @click="deleteCategory(row,index)">Delete</Button>
								</template>
							</Table>
						</div>
                        <div class="_pagination _padd_t20" v-if="alldata && alldata.total!=null">
							<Page :total="alldata.total" show-sizer :page-size-opts="pageOption" @on-page-size-change="getSizeChange"  @on-change="paginateDataInfo" />
						</div>
                </div>
            </div>
        </div>
		
	
        <Modal title="Description"  v-model="descriptionModal" :mask-closable="false">
            <Form   label-position="top" >
                <p v-if="description">{{description}}</p>
                <p v-else>No Description !</p>
            </Form>
            <div slot="footer">
                <Button   @click="descriptionModal=false" >close</Button>
            </div>
        </Modal>
        <Modal title="Image"  v-model="imageModal" :mask-closable="false">
            <Form  label-position="top" >
                <FormItem prop="image"  >
                     <img :src="image">
                </FormItem>
            </Form>
            <div slot="footer">
                <Button   @click="imageModal=false" >close</Button>
            </div>
        </Modal>
    </div>
</template>
<script>
export default {
    data(){
        return{
			isLoading:false,
			filtrBy:'',
			product_id:0,
            pageOption:[5,10,15,20],
            columns: [
				 { 
                    title: 'Id',
                    sortable: true,
                    key: 'id',
                    width:100
                },
				{
					title: 'Name',
                    slot: 'product',
                    width:200
				},
				{
					title: 'Descritpion',
                    slot: 'descritpion',
                    width:120
				},
				{
					title: 'Category Name',
					slot: 'category', 
                    width:150
                },
				{
					title: 'Sub Category Name',
					slot: 'subCategory',
                    width:180
				},
				// {
				// 	title: `Is Featured`,
				// 	slot: 'featured',
				// 	width: 170
				// },
				{
					title: `Prescription's Need`,
					slot: 'status',
					width: 170
				},
				{
					title: 'Shop Name',
					slot: 'pshop',
                    width:200
				},
				
				// {
				// 	title: 'Franchise Name',
				// 	slot: 'franchise',
                //     width:180
                // },
				{
					title: 'Action',
					slot: 'action',
					width: 430
				}
			],
            alldata:{
                total:0,
                data:[]
            },
            str2:'',
            description:'',
            shopId:'',
            image:'',
            imageModal:false,
            descriptionModal:false,
            page:1,
			pageSize:10,
			str:'',
			switchLoading:false,
			menuCategoryId:0
        }
	},
	methods:{
		async availabilityChange(row,index){			
			this.switchLoading = true
			let ob = {
				id:row.id,
				must_prescribed:row.must_prescribed
			}
			const res = await this.callApi('post','/productmodule/updateProductColumn',ob)
			if(res.status == 200){
				
			}
			else this.swr();
			this.switchLoading = false
		},
        searchResult(){
			this.pageSize = 10
			this.paginateDataInfo(1)
		},
        filterResult(e){
			console.log(e)
			this.page = 1
			this.getAllProductList()
		},
		getSizeChange(e){
			this.pageSize =e
			this.paginateDataInfo(1)
		},
		paginateDataInfo(e){
			   this.page = e
			   this.getAllProductList()
        },
        async handleView(item){
           this.image = item
           this.imageModal = true
        },

        async showDescriptions(description){
            this.description = description
            this.descriptionModal = true
        },
		async deleteCategory(item,index){
			// console.log(item)
            if(!confirm(`Are you sure to delete ${item.name} ?`)) {
                return;
            }
			const res = await this.callApi('post', '/productmodule/deleteProduct', {id:item.id})
            if(res.status == 200){
                this.alldata.data.splice(index, 1)
                this.s("Product has been deleted.")
            }
            else if(res.status==422){  
				this.e(res.data.message)
			}
			else {
				this.swr()
			} 
        },
        async getAllProductList(){
            if(this.$route.query && this.$route.query.shopId){
                this.shopId = this.$route.query.shopId
            }
            if(this.$route.query && this.$route.query.menuCategoryId){
                this.menuCategoryId = this.$route.query.menuCategoryId
            }
            // if(this.product_id == 0){
			// 	const res = await this.callApi('get', `/productmodule/getAllProduct?product_id=${this.product_id}&menuCategoryId=${this.menuCategoryId}&shopId=${this.shopId}&page=${this.page}&pageSize=${this.pageSize}&str=${this.str}`)
			// 	if(res.status==200){
			// 		this.alldata = res.data
			// 		this.alldata.total = res.data.total
			// 	} 
			// }
            // const res = await this.callApi('get', `/productmodule/getProductById?product_id=${this.product_id}`)
			// 	if(res.status==200){
			// 		this.alldata.data.push(res.data)
			// 		this.alldata.total = 1
			// 	}
			const res = await this.callApi('get', `/productmodule/getAllProduct?product_id=${this.product_id}&featured=${this.filtrBy}&menuCategoryId=${this.menuCategoryId}&shopId=${this.shopId}&page=${this.page}&pageSize=${this.pageSize}&str=${this.str}`)
				if(res.status==200){
					this.alldata = res.data
					this.alldata.total = res.data.total
				} 
        }
	},
    async created(){ 
		this.id  = this.$route.params.id
		if(this.$route.query && this.$route.query.product_id){
			this.product_id = this.$route.query.product_id
		}
 		// console.log(this.product_id)
            this.getAllProductList()
           
     }
}
</script>
