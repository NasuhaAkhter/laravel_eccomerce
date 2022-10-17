<template>
    <div class="_main_content">
		<div class="row">
            <div class="col-12 col-md-12 col-lg-12">
				<div class="_box _b_radious3 _padd20">
                    <div class="_1card_top _mar_b20">
							<div class="_1card_top_left">
								<p class="_1card_top_title"><span v-if="productDetails.name"> {{productDetails.name}}</span> <span v-else>Product</span> - Variations</p>

								<div class="_1card_top_search">
									<Input search enter-button="Search" placeholder="Enter something..." />
								</div>
							</div>

							<div class="_1card_top_right">
								<ul class="_1card_top_right_list">
									<li><Button type="primary" @click="openCreateModal">Add New Variations</Button></li>
								</ul>
							</div>
						</div>
						<div class="_table_responsive">
							<Table class="_table700" border :columns="columns" :data="alldata.data">
								<template slot="price" slot-scope="{ row }">
									<p>৳ {{ row.price}}</p>
								</template>
								<template slot="purchase_price" slot-scope="{ row }">
									<p>৳ {{ row.purchase_price}}</p>
								</template>
								<template slot="discount" slot-scope="{ row }">
									<p v-if="row.percentage>0">{{ row.percentage}}%</p>
									<p v-else>৳ {{ row.discount}}</p>
								</template>
								<template slot="stock" slot-scope="{ row , index }">
									  <i-switch :loading="switchLoading" v-model="row.availability" @on-change="availabilityChange(row,index)" :true-value="1" :false-value="0"  true-color="#13ce66" false-color="#ff4949"  size="large">
										<span slot="open" >Yes</span>
										<span slot="close"  >No</span>
									</i-switch>
								</template>
								<template slot="action" slot-scope="{ row, index }">
									<Button type="primary" @click="openEditModal(row,index)"> Edit</Button>
									<Button type="error" @click="deleteProductVariation(row.id,index)">Delete</Button>
								</template>
							</Table>
						</div>
                </div>
            </div>
        </div>
		<Modal title="Create New Variation"  v-model="singleImageModal" :mask-closable="false">
			 <Form  label-position="top" >
				   <FormItem prop="name" :error="errror.name"  label="Variation Name">
					   <Input v-model="form.name" />
					</FormItem>
				   <FormItem prop="price" :error="errror.price"  label="Variation price">
					   <Input v-model="form.price" />
					</FormItem>
				   <FormItem prop="purchase_price" :error="errror.purchase_price"  label="Purchase price">
					   <Input v-model="form.purchase_price" />
					</FormItem>
				   <FormItem prop="stock" :error="errror.stock"  label="Variation stock Available">
					    <i-switch :loading="switchLoading" v-model="form.availability"  :true-value="1" :false-value="0"  true-color="#13ce66" false-color="#ff4949"  size="large">
							<span slot="open" >Yes</span>
							<span slot="close"  >No</span>
						</i-switch>
					</FormItem>
					<FormItem   label="Discount Type">
					   <RadioGroup v-model="isDiscount">
						<Radio :label="0" >Amount</Radio>
						<Radio :label="1">Percentage</Radio>
					</RadioGroup>
					</FormItem>
					<template v-if="isDiscount==1" >
						<FormItem   label="Parcentage">
							<InputNumber style="width:100%"  placeholder="0.00%" :max="100" :min="0" :formatter="value => `${value}%`" :parser="value => value.replace('', '%')" v-model="form.percentage" ></InputNumber>
						</FormItem>
					</template>
					<template v-else-if="isDiscount==0" >
						<FormItem  label="Amount">
							<InputNumber style="width:100%"  placeholder="৳ 0.00"  :min="0" :formatter="value => `৳ ${value}`" :parser="value => value.replace('৳ ', '')" v-model="form.discount" ></InputNumber>
						</FormItem>
					</template>
				   	
				   	
			 </Form>
			<div slot="footer">
                <Button  @click="storeVariation" >store</Button>
                <Button  @click="singleImageModal=false" >close</Button>
            </div>
		</Modal>
		<Modal title="Update Variation"  v-model="editModal" :mask-closable="false"> 
			 <Form  label-position="top" >
				   <FormItem prop="name" :error="errror.name"  label="Variation Name">
					   <Input v-model="form.name" />
					</FormItem>
				   <FormItem prop="price" :error="errror.price"  label="Variation price">
					   <Input v-model="form.price" />
					</FormItem>
					<FormItem prop="purchase_price" :error="errror.purchase_price"  label="Purchase price">
					   <Input v-model="form.purchase_price" />
					</FormItem>
				   <FormItem prop="stock" :error="errror.stock"  label="Variation stock Available">
					    <i-switch :loading="switchLoading" v-model="form.availability"  :true-value="1" :false-value="0"  true-color="#13ce66" false-color="#ff4949"  size="large">
							<span slot="open" >Yes</span>
							<span slot="close"  >No</span>
						</i-switch>
					</FormItem>
				   	<FormItem   label="Discount Type">
					   <RadioGroup v-model="isDiscount">
						<Radio :label="0" >Amount</Radio>
						<Radio :label="1">Percentage</Radio>
					</RadioGroup>
					</FormItem>
				   	<template v-if="isDiscount==1" >
						<FormItem   label="Parcentage">
							<InputNumber style="width:100%"  placeholder="0.00%" :max="100" :min="0" :formatter="value => `${value}%`" :parser="value => value.replace('', '%')" v-model="form.percentage" ></InputNumber>
						</FormItem>
					</template>
					<template v-else-if="isDiscount==0" >
						<FormItem  label="Amount">
							<InputNumber style="width:100%"  placeholder="৳ 0.00"  :min="0" :formatter="value => `৳ ${value}`" :parser="value => value.replace('৳ ', '')" v-model="form.discount" ></InputNumber>
						</FormItem>
					</template>
					
			 </Form>
			<div slot="footer">
                <Button  @click="updateVariation" >update</Button>
                <Button  @click="editModal=false" >close</Button>
            </div>
		</Modal>
    </div>
</template>


<script>
export default {
    data(){
        return{
			singleImageModal:false,
			editModal:false,
			productDetails:{},
            pageOption:[5,10,15,20],
            columns: [
				{
					title: 'Name',
					key: 'name'
				},
				{
					title: 'Price',
					slot: 'price'
				},
				{
					title: 'Purchase Price',
					slot: 'purchase_price'
				},
				{
					title: 'Stock Available',
					slot: 'stock'
				},
				{
					title: 'Discount',
					slot: 'discount'
				},
				{
					title: 'Action',
					slot: 'action'
				}
			],
			form:{
				name:'',
				product_id:'',
				price:0,
				purchase_price:0,
				availability:1,
				stock:0,
				percentage:0,
				discount:0,
			},
			errror:{
				name:'',
				product_id:'',
				stock:'',
				price:'',
				purchase_price:'',
				discount:'',
			},
            alldata:{
                total:0,
                data:[]
			},
			id:'',
			editIndex:-1,
			isDiscount:1,
			switchLoading:false,
			
        }
	},
	methods:{
		async availabilityChange(row,index){
			console.log(row.availability)
			this.editIndex = index
			this.switchLoading = true
			this.form = row
			await this.updateVariation();
			this.switchLoading = false
		},
		 validateData(){
			this.clearErrorData()
			let flag = true
				if(!this.form.name || this.form.name.trim()==''){
				this.errror.name = "This field is required!"
				flag = false
			}	
			if(this.form.price==null || this.form.price==''){
				this.errror.price = "This field is required!"
				flag = false
			}	
			if(this.form.purchase_price==null || this.form.purchase_price==''){
				this.errror.purchase_price = "This field is required!"
				flag = false
			}	
			// if(this.form.stock==null || this.form.stock==''){
			// 	this.errror.stock = "This field is required!"
			// 	flag = false
			// }	
			return flag 
		},
		async storeVariation(){
			if(!this.validateData()) return
			this.form.product_id = this.id
			const res = await this.callApi('post', '/productmodule/storeProductVarition',this.form)
			if(res.status == 200 || res.status == 201){
				this.alldata.data.unshift(res.data)
				this.singleImageModal = false
			}
			else if(res.status==422){
				this.e(res.data.message)
			}
			else{
				this.swr()
			}
		},
		async updateVariation(){
			if(!this.validateData()) return
			
			this.form.product_id = this.id
			const res = await this.callApi('post', '/productmodule/updateProductVariation',this.form)
			if(res.status == 200 || res.status == 201){
				Vue.set(this.alldata.data, this.editIndex, res.data)
				this.editModal = false
			}
			else if(res.status==422){
				this.e(res.data.message)
			}
			else{
				this.swr()
			}
		},
		clearErrorData(){
			this.errror={
				name:'',
				product_id:'',
				price:'',
				purchase_price:'',
				discount:0,
			}
		},
		clearData(){
			this.form={
				name:'',
				product_id:'',
				price:0,
				purchase_price:0,
				percentage:0,
				discount:0,
				stock:0,
				availability:1,
			}
		},
		openCreateModal(){
			this.clearErrorData()
			this.clearData()
			this.singleImageModal = true
		},
		async getAllProductVariation(){
			const res = await this.callApi('get',`/productmodule/getAllProductVariation?product_id=${this.id}`)
			if(res.status == 200){
				this.alldata.data = res.data
			}
			else if(res.status==422){
				this.e(res.data.message)
			}
			else this.swr()
		},
		openEditModal(item,index) {
			this.clearData()
			this.clearErrorData()
			this.editIndex = index
			this.form = _.cloneDeep(item)
			this.editModal = true
		},
		async deleteProductVariation(id,index){
			 if (!confirm("Are you sure to delete product variation?")) {
                return;
            }
			const res = await this.callApi('post','/productmodule/deleteProductVariation', {id:id})
			if(res.status == 200){
				this.alldata.data.splice(index, 1)
			}
			else if(res.status==422){
				this.e(res.data.message)
			}
			else
			this.swr()  
		}
	},
    async created(){
		this.id =this.$route.params.id
		await this.getAllProductVariation()
		const res = await this.callApi('get',`/productmodule/productDetails/${this.id}`)
		if(res.status == 200){
			this.productDetails = res.data
		}
		
    },
    
}
</script>
