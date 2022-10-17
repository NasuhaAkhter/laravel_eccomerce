
<template>
    <div class="_main_content">
		<div class="row">
            <div class="col-12 col-md-12 col-lg-12">
                <div class=" _b_radious3 _padd20">
                    <Card > 
                        <p slot="title" >Order Details</p>
                        <div class="_text_right _padd_r50">
                            <Button @click="handleView(alldata.data.pimage)" type="info">Prescription</Button>
                            <Button @click="remarksModal= true" type="info">Remarks</Button>
                        </div>
                        <ul class="_padd_l10">
                            <li><span >Invoice Id:  {{ alldata.data.id }} </span></li>
                            <li><span v-if="alldata.data && alldata.data.users && alldata.data.users.name">Coustomer Name:  {{alldata.data.users.name}}</span></li>
                            <li><span v-if="alldata.data && alldata.data.users && alldata.data.users.phone">Coustomer Contact No:  {{alldata.data.users.phone}}</span></li>
                            <li v-if="alldata.data && alldata.data.address && alldata.data.address.address"><span >Address :  {{ alldata.data.address.address }}</span></li>
                            <li v-if="alldata.data && alldata.data.address && alldata.data.address.apartment_number"><span  > Appartment number :  {{ alldata.data.address.apartment_number }} </span></li>
                            <li v-if="alldata.data && alldata.data.paymentType && alldata.data.paymentType" ><span > Payment Type :  {{ alldata.data.paymentType }}</span></li>
                            <li v-if="alldata.data && alldata.data.delivery_time " ><span >Time : {{ alldata.data.orderDate |formatDateTimeAsMMDDYYYYHHMMSS}} ( {{ alldata.data.delivery_time }} )</span></li>
                            <li v-if="alldata.data && alldata.data.created_at" ><span >Order Placed On :  {{ alldata.data.created_at | formatOnlyTime }} ( {{ alldata.data.created_at | formatDateTimeAsMMDDYYYYHHMMSS }} )  </span></li>
                            <!-- <li v-if="alldata.data.remarks"><span  >Remarks:  {{ alldata.data.remarks }}</span></li> -->
                            <li v-if="driver != null"><span  >Delivery Man :  {{ driver.name }} ( {{ driver.phone }} )</span></li>
                            <li><span >Order Status:  {{ alldata.data.status }}</span></li>
                            <br> 
                            <Button @click="openStatusModal" type="primary">Change Status</Button>
                            <!-- <li  style="cursor:pointer" @click="openStatusModal" > <strong> <span >Order Status:  {{ alldata.data.status }}</span> <span>(<Icon type="ios-hammer" /> Edit)</span> </strong></li> -->
                        </ul>
                    </Card>
                </div> 
            </div>
            <div class="col-12 col-md-12 col-lg-12">
				<div class="_box _b_radious3 _padd20"> 
                    <div class="_1card_top _mar_b20">
                        <div class="_1card_top_left">
                            <p class="_1card_top_title">Order items</p>
                        </div>
                        <div class="_1card_top_right">
                            <ul class="_1card_top_right_list">
                                <li><Button type="primary" @click="openCreateModal">Edit item</Button></li>
                                <li ><Button type="primary" v-if=" alldata.data && alldata.data.category_id == 3"  @click="openItemEditModal">Edit Price</Button></li>
                            </ul>
                        </div>
                    </div>
                    <div class="_table_responsive">
                        <Table class="_table800" border :columns="columns1" :data="alldata.data.items">
                            <template slot="status" slot-scope="{ row }">
                                <p  > {{alldata.data.status}}</p>
                            </template>
                            <template slot="product" slot-scope="{ row }">
                                <p v-if="row.product && row.product.name"> {{row.product.name}}  <span v-if="row.brand && row.brand.name"> ( {{row.brand.name}} ) </span></p>
                            </template>
                            <!-- <template slot="brand" slot-scope="{ row }">
                                <p v-if="row.brand && row.brand.name"> {{row.brand.name}}</p>
                            </template> -->
                            <template slot="image" slot-scope="{ row }">
                                <div>
                                    <div v-if="row.pimage" class="demo-upload-list">  
                                        <img :src="row.pimage" alt="">
                                        <div class="demo-upload-list-cover">
                                            <Icon type="ios-eye-outline"  @click.native="handleView(row.pimage)"></Icon>
                                        </div>
                                    </div>
                                </div>
                            </template>
                            <!-- <template slot="category" slot-scope="{ row }">
                                <p v-if="row.category && row.category.name"> {{row.category.name}}</p>
                            </template>
                            <template slot="sub_category" slot-scope="{ row }">
                                <p v-if="row.sub_category && row.sub_category.name"> {{row.sub_category.name}}</p>
                            </template> -->
                            
                          
                            <template slot="price" slot-scope="{ row }">
                                <p v-if="row.price "> ৳ {{row.price}}</p>
                            </template>
                            <template slot="total_price" slot-scope="{ row }">
                                <p v-if="row.total_price "> ৳ {{row.total_price}}</p>
                            </template>
                            <template slot="variation" slot-scope="{ row }">
                                <p v-if="row.variations && row.variations.name"> {{row.variations.name}}</p>
                            </template>
                            <template slot="shop" slot-scope="{ row }">
                                <p v-if="row.pshop && row.pshop.name"> {{row.pshop.name}}</p>
                            </template>
                            <!-- <template slot="action" slot-scope="{ row ,index}">
                                <Button type="error" @click="deleteSigleOrderItem(row.id,index)">Remove</Button>
                            </template> --> 
                        </Table> 
                    </div>
                    <div class="_text_right _padd_r50">
                        <br>
                        <h4>SubTotal :  {{ alldata.data.subTotal }} ৳ </h4>
                        <!-- <h4>Discount from Coupon : {{ alldata.data.coupon_amount }} ৳ </h4>
                        <h4>Discount from Product : {{ discount_from_product }} ৳ </h4> -->
                        <!-- <h4>Cashback : {{ alldata.data.discount_amount }} ৳ </h4> -->
                        <h4 v-if="alldata.data && alldata.data.paymentType == 'Redeem Cashback'"> Redeem Cash Back: {{  alldata.data.subTotal }} ৳ </h4>
                        <h4 v-else>Cash Back: {{  alldata.data.discount_amount }} ৳ </h4>
                        <h4>Delivery Charge : {{ alldata.data.shipingCharge }} ৳ </h4>
                        <hr>
                        <h4>Grand Total : {{ alldata.data.total }} ৳ </h4>
                    </div>
  
                </div>  
            </div>
            <div class="col-12 col-md-12 col-lg-12">
                 <!-- <div  v-if="alldata.data && alldata.data.address" >
                    <deleveryAddress :data1="alldata.data.address"/>
                </div> -->
                <!-- <div class="row"> -->
                    <!-- <div class="col-12 col-md-6 col-lg-6">
                        <div class=" _b_radious3 _padd20">
                            <Card >
                                <p slot="title" >Order Details</p>
                                <ul class="_padd_l10">
                                    <li><span v-if="alldata.data && alldata.data.users && alldata.data.users.name">Coustomer Name:  {{alldata.data.users.name}}</span></li>
                                    <li><span >Order Id:  {{ alldata.data.id }}</span></li>
                                    <li><span >Order Status:  {{ alldata.data.status }}</span></li>
                                    <li v-if="alldata.data && alldata.data.franchise && alldata.data.franchise.name"><span >Franchise Name:  {{ alldata.data.franchise.name }}</span></li>
                                    <li v-if="alldata.data && alldata.data.total && alldata.data.total" ><span >Total Amount:  {{ alldata.data.total }}</span></li>
                                    <li  style="cursor:pointer" @click="openStatusModal" > <strong> <span >Order Status:  {{ alldata.data.status }}</span> <span>(<Icon type="ios-hammer" /> Edit)</span> </strong></li>
                                </ul>
                            </Card>
                        </div>
                    </div> -->
                    <!-- <div class="col-12 col-md-6 col-lg-6">
                        <orderImages :id="id" />
                    </div> -->
                <!-- </div> -->
            </div>
            
        </div>
        <Modal title="Select product"  :width="800"  v-model="productModal" :mask-closable="false">
            <Form  :model="form" label-position="top" >
                <FormItem prop="name" :error="error.product_id" label="Product Name">
                    <Select
                            v-model="productIndex"
                            filterable
                            @on-select="selectProduct"
                            :remote-method="getAllProducts"
                        >
                          <Option v-for="(item, index) in products" :value="index" :key="index" :label="item.name">{{item.name}} <span v-if="item.pshop && item.pshop.name"> ( {{ item.pshop.name }} ) </span></Option>
                    </Select>
                </FormItem>
                <FormItem prop="variation_id" :error="error.variation_id" label="Select Variation" v-if="singleItem && singleItem.variations">
                    <Select
                            v-model="variationIndex"
                            @on-select="selectProductVariation"
                            filterable
                        >
                            <!-- :remote-method="getAllProducts" -->
                          <Option v-for="(item, index) in variations" :value="index" :key="index" :label="item.name">{{item.name}}</Option>
                    </Select>
                </FormItem>
                <FormItem prop="name" :error="error.quantity"    label="Quantity"   v-if="singleItem &&singleItem.variations &&  singleItem.variations[variationIndex]">
                     <InputNumber    :min="1" v-model="form.quantity"
                         :editable="false"></InputNumber>
                </FormItem>
                <Button   type="primary" @click="addProduct" :loading="isLoading" :disabled="isLoading">{{isLoading? 'Please wait' : 'Add Item'}}</Button>
               
            </Form> 
             <div class="row" style="padding: 20px 0px" >
                     <div class="col-md-12" >
                        <Table  border :columns="columns3" :data="items"   >
                            <template slot="variationName" slot-scope="{ row }">
                                <p v-if="row.variations && row.variations.name"> {{row.variations.name}}</p>
                            </template>
                            <template slot="name" slot-scope="{ row }">
                                <p v-if="row.product && row.product.name"> {{row.product.name}}  <span v-if="row.brand && row.brand.name"> ( {{row.brand.name}} ) </span></p>
                             </template>                             
                            <template slot="total" slot-scope="{ row }">
                                {{row.quantity * row.price}}
                            </template>
                            <template slot="quantity" slot-scope="{ row , index}">
                                <InputNumber  :min="1" v-model="row.quantity" @on-change="memo(row.quantity, index)"
                                :editable="false"></InputNumber>
                            </template>
                            <template slot="action" slot-scope="{ row , index }">
                                <Button type="error" @click="removeItem(index)">Remove</Button>
                            </template>
                        </Table>
                    </div>
                    <div  class="col-md-12">
                         <div class="_text_right _padd_r50">
                            <br>
                            <h4>SubTotal :  {{subTotal}} ৳ </h4>
                            <h4 v-if="alldata.data && alldata.data.paymentType == 'Redeem Cashback'"> Redeem Cash Back: {{ subTotal }} ৳ </h4>
                            <h4 v-else>Cash Back: {{ cashBack }} ৳ </h4>
                            <h4>Delivery Charge : {{ alldata.data.shipingCharge }} ৳ </h4>
                            <hr>
                            <h4>Grand Total : {{ totalCost }} ৳ </h4>
                        </div>
                    </div>                     
                </div>
            <div slot="footer">
                <Button   @click="productModal=false" >Cancel</Button>
                <Button type="primary" @click="storeProduct"  :loading="isUpdating" :disabled="isUpdating">{{isUpdating? 'Please wait' : 'Update'}}</Button>
            </div>
        </Modal>
        <Modal title="Image"  v-model="imageModal" :mask-closable="false">
            <Form  label-position="top" >
				 <FormItem prop="uploadlist"  label="Image" :error="error.image" >
                        <div class="demo-upload-list" v-for="(item, i) in uploadedList" :key="i">
                            <template v-if="item.image">
                                <img :src="item.image">
                                <div class="demo-upload-list-cover">
                                    <Icon type="ios-eye-outline" @click.native="handleView(item.image)"></Icon>
                                    <Icon type="ios-trash-outline" @click.native="handleRemove(i)"></Icon>
                                </div>
                            </template>
                            <template v-else>
                                <Progress v-if="item.showProgress" :percent="item.percentage" hide-info></Progress>
                            </template>
                            </div>
                        <Upload
                            ref="upload"
                            name="img"
                            :show-upload-list="false"
                            :format="['jpg','jpeg','png']"
                            :max-size="20480"
                            :on-success="handleSuccess"
                            :on-format-error="handleFormatError"
                            :on-exceeded-size="handleMaxSize"
                            :before-upload="handleBeforeUpload"
                            :on-progress="handleProgress"
                            multiple
                            type="drag"
                            action="/usermodule/upload/images"
                            style="width: 100% !important"
                            >
                            <div style="padding: 20px 0">
                                <Icon type="ios-cloud-upload" size="52" style="color: #3399ff"></Icon>
                                <p>Click or drag files here to upload</p>
                            </div>
                            <!-- <i class="fas fa-images"></i> attachement -->
                        </Upload>
                    </FormItem>
            </Form>
            <div slot="footer">
                <Button   @click="storeImages" >store</Button>
                <Button   @click="closes" >close</Button>
            </div>
        </Modal>
        <Modal title="Update Status"  v-model="statusModal" :mask-closable="false">
            <Form  label-position="top" >
				<FormItem   label="Status" :error="error.image" >
                    <Select v-model="statusOb.status" filterable>
                        <Option v-for="(item, index) in statuses" :value="item" :key="index" >{{ item }}</Option>
                    </Select> 
                </FormItem>
            </Form>
            <div slot="footer">
                 <Button type="primary" @click="updateOrderStatus"  :loading="isLoading" :disabled="isLoading">{{isLoading? 'Please wait' : 'Update'}}</Button>
                <Button   @click="statusModal = false" >close</Button>
            </div>
        </Modal>
        <Modal title="Edit Product Price "  v-model="itemEditModal" :mask-closable="false" :width="800">
            <Form  :model="form" label-position="top" >
                <div class="row" >
                    <div class="col-md-12" >
                        <Table  border :columns="columns2"  :data="orderItemEditObj">
                            <template slot="product" slot-scope="{ row }">
                                <p v-if="row.product && row.product.name"> {{row.product.name}}</p>
                            </template>
                            <template slot="variation" slot-scope="{ row }">
                                <p v-if="row.variations && row.variations.name"> {{row.variations.name}}</p>
                            </template>
                            <template slot="unitPrice" slot-scope="{ row , index}">
                                <InputNumber :disabled="row.isDisable"  :min="1" v-model="row.variations.price" @on-change="priceChangeEditTable(index,row.variations.price)"  ></InputNumber>
                            </template>
                            <template slot="purchase_price" slot-scope="{ row , index}">
                                <InputNumber :disabled="row.isDisable"  :min="1" v-model="row.variations.purchase_price" @on-change="purchase_priceChangeEditTable(index,row.variations.purchase_price)"  ></InputNumber>
                            </template>
                            <template slot="price" slot-scope="{ row }">
                                <p > {{row.price * row.quantity}}</p>
                            </template>
                        </Table>
                    </div>
                               
                </div>
            </Form>
            <div slot="footer">
                <Button   @click="itemEditModal=false" >Cancel</Button>
                <Button type="primary" @click="updateItems"  :loading="isLoading" :disabled="isLoading">{{isLoading? 'Please wait' : 'Update'}}</Button>
            </div>
        </Modal>
        <Modal title="Image" :width="800" v-model="singleImageModal" :closable="false" :mask-closable="false">
			<div>
				<img v-if="image" :src="image" alt="">
                <p v-else> No image found </p>
			</div>
			 <div slot="footer">
                <Button  type="success"  size='large' long   @click="singleImageModal=false" >Close</Button>
            </div>
		</Modal>
        <Modal title="Remarks" :width="600" v-model="remarksModal" :closable="false" :mask-closable="false">
			<div>
				<p v-if="alldata.data.remarks">{{ alldata.data.remarks }}</p>
                <p v-else> Nothing to show</p>
			</div>
			<div slot="footer">
                <Button  type="success"  size='large' long  @click="remarksModal=false" >Close</Button>
            </div>
		</Modal>
    </div>
</template>
<script>
import {mapGetters} from 'vuex'
import orderImages from "./orderImages.vue";
import deleveryAddress from "./delevery_address.vue";
export default {
    components:{
        orderImages, 
        deleveryAddress
    },
    data(){
        return{
            id:'',
            cashBack : 0,
            discount_from_product : 0,
            isUpdating:false,
            imageModal:false,
            statusModal:false,
            itemEditModal:false,
            orderItemEditObj:[],
            orderData:{
            },
            couponOb:null,
            singleUser:{},
            userIndex:'',
            user_id:'',
            address_id:'',
            deliverIndex:-1,
            productIndex:'',
            variationIndex:'',
            productModal:false,
            isLoading:false,
            products:[],
            statuses:['Processing','Ready to Pick','Picked','Delivered','Canceled'],
            shop_statuses:['Processing','Ready to Pick'],
             users:[],
            createModal:false,
            pageOption:[5,10,15,20],
            columns1: [
				{
					title: 'Product name',
                    slot: 'product',
                    width: 320
				},
			
                // {
				// 	title: 'Category',
                //     slot: 'category',
                //     width: 120
				// },
                // {
				// 	title: 'Prescription',
				// 	slot: 'image',
				// 	width: 150
				// },
                // {
				// 	title: 'Brand',
                //     slot: 'brand',
                //     // width: 120
				// },
                {
					title: 'Variation',
                    slot: 'variation',
                    // width: 120
				},
                {
					title: 'Shop name',
                    slot: 'shop',
                    // width: 180
				},
				{
					title: 'Price',
                    slot: 'price',
                    // width: 100
				},
				{
					title: 'Total',
                    slot: 'total_price',
                    // width: 100
				},
				
				{
					title: 'Quantity',
					key: 'quantity',
					// width: 100
                },
				// {
				// 	title: 'Status',
				// 	slot: 'status',
				// 	width: 100
                // },
				// {
				// 	title: 'Action',
				// 	slot: 'action',
				// 	width: 120
                // },
                
            ],
            columns2: [
				{
					title: 'Product name',
                    slot: 'product',
				},
                {
					title: 'Variation',
                    slot: 'variation',
                    
				},
				{
					title: 'Unit-Price',
                    slot: 'unitPrice',
                    width: 150
				},
				{
					title: 'Purchse-Price',
                    slot: 'purchase_price',
                    width: 150
				},
				{
					title: 'Quantity',
					key: 'quantity',
					width: 100
                },
                {
					title: 'Total',
                    slot: 'price',
                    width: 100
				},
            ],
            columns3: [
				{
					title: 'Name',
                    slot: 'name',
                    width: 200
				},
                {
					title: 'Variation',
                    slot: 'variationName',
                    width: 200
				},
				{
					title: 'Total',
                    slot: 'total',
                    width: 200
				},
				{
					title: 'Quantity',
					slot: 'quantity',
					width: 200
                },
                {
					title: 'Action',
					slot: 'action',
					width: 120
                },
            ],
            form:{
                product_id:'',
                variation_id:'',
                partner_shop_id:'',
                product_category_id:'',
                product_subcategory_id:'',
                schedule_id:'',
                band_id:'',
                franchise_id:'',
                price:0,
                discount:0,
                name:'',
                user_id:'',
                stock:0,
                variationName:'',
                delivery_time:'',
                quantity:1
            },
            error:{
                product_id:'',
                variation_id:'',
                partner_shop_id:'',
                product_category_id:'',
                product_subcategory_id:'',
                band_id:'',
                franchise_id:'',
                price:'',
                name:'',
                user_id:'',
                stock:'',
                variationName:'',
                quantity:''
            },
            alldata:{
                data:[],
                total:0
            },
            str:'',
            str2:'',
            page:1,
            pageSize:10,
            shopId:'',
            singleItem:{},
            variations:[],
            uploadedList:[],
            originalList : [],
            isUploadError:false,
            uploadErrorText:'',
            image : '',
            remarksModal : false,
            singleImageModal : false,
            order : {
                items:[],
                images:[],
                user_id:'',
                address_id:'',
                shipingCharge:'',
                orderDate:'',
                orderType:''
            },
            error2 : {
                items:'',
                images:'',
                user_id:'',
                address_id:'', 
                shipingCharge:'',
                orderDate:'',
                orderType:''
            },
            propsData:{
                address_id:'',
                order_id:'',
                user_id:'',
                name:'',
            },
            statusOb:{
                status:'',
            },
            driver:{},
            orderDetails:{},
            items:[],
            total_amount:0
        }
    },
    async created(){
        this.id = this.$route.params.id
        this.getAllOrders()
        if(this.authInfo.userType == 'ShopOwner'){
            this.statuses = this.shop_statuses
        }
        this.getDriver()
    },
    methods:{
        memo(quantity, product_index){
            this.items[product_index].quantity = quantity
            //  for(let i of this.items){
            //     i.quantity = quantity
            //  }
        },
        openStatusModal(){
            this.statusOb.status = this.alldata.data.status
            this.statusOb.order_id = this.id
            this.statusModal = true
        },
        async updateOrderStatus(){
           this.isLoading = true
           if(this.statusOb.status == 'Delivered' ){
                const res  = await this.callApi('post','/ordermodule/deliveredStatusUpdate',this.statusOb)
                if(res.status == 200){
                    this.alldata.data.status = this.statusOb.status
                    this.s("Status has been updated.")
                    this.statusIndex = -1
                    this.statusModal = false
                }
                else this.swr();
            }else{
                const res  = await this.callApi('post','/ordermodule/updateOrderStatus',this.statusOb)
                if(res.status == 200){
                    this.alldata.data.status = this.statusOb.status
                    this.statusModal = false
                }
                else this.swr();
            }
            this.isLoading = false
        },
        priceChangeEditTable(index,value){
            this.orderItemEditObj[index].variations.price = value
        },
        purchase_priceChangeEditTable(index,value){
            this.orderItemEditObj[index].variations.purchase_price = value
        },
        openItemEditModal(){
            this.orderItemEditObj = [];
            this.orderItemEditObj = _.clone(this.alldata.data.items)
            for(let d of this.orderItemEditObj){
                d.isDisable = false;
                d.variations.price = parseFloat(d.variations.price)
                d.variations.purchase_price = parseFloat(d.variations.purchase_price)
                if(this.authInfo.userType == 'ShopOwner'){
                    d.isDisable = true;
                    if(this.authInfo.shop_id == d.partner_shop_id) d.isDisable = false
                }
            }
            this.itemEditModal = true;
        },
        async updateItems(){
            this.isLoading = true;
            let ob = {
                id:this.alldata.data.id,
                shipingCharge:this.alldata.data.shipingCharge,
                items:this.orderItemEditObj,
                paymentType:this.alldata.data.paymentType,
                orderType:this.alldata.data.orderType,
            }
            const res = await this.callApi('post','/ordermodule/updateProductPriceInOrder',ob)
            // const res = await this.callApi('post','http://127.0.0.1:3100/shopownermodule/editOrder',ob)
            if(res.status == 200){
                this.alldata.data = res.data
                this.itemEditModal = false
            }
            else this.swr();
             this.isLoading = false;
        },
        formateDate(e){
            this.order.orderDate = e
        },
        handleRemove1(i){
            this.originalList.splice(i, 1)
        },
          handleRemove(i){
          this.uploadedList.splice(i, 1)
        },
          storeImages(){
            this.originalList = _.cloneDeep(this.uploadedList) 
            this.imageModal=false
        },
        imageModalOn(){
             this.uploadedList =  _.cloneDeep(this.originalList) 
			this.imageModal=true
		},
        closes(){
            this.imageModal=false
            this.uploadedList = []
        },
        selectedDeleveryIndex(id){
            this.order.address_id = id
        },
        async deleteSigleOrderItem(id,index){
            if (!confirm("Are you sure to delete?")) {
                return;
            }
            let d = _.cloneDeep(this.alldata.data.items[index]); 
            const res = await this.callApi('post',`/ordermodule/deleteOrderSignleItem`,{id:id})
            if(res.status == 200){
                this.alldata.data.items.splice(index,1);
                this.alldata.data.total = this.alldata.data.total - (d.price*d.quantity)
                this.s("Order item removed")
            }
            else if(res.status==422){
                this.e(res.data.message)
            }
            else{
                this.swr()
            }
        },
        // add product
        async storeProduct() {
            this.order = _.clone(this.orderDetails)
            this.order.subTotal = this.subTotal  
            this.order.total = this.totalCost  
            this.order.id = this.orderDetails.id
            this.order.items = _.clone(this.items)
            // console.log(this.order.items)
            // console.log(this.items)
            console.log(this.order)
            this.isUpdating = true 
            const res = await this.callApi('post', '/ordermodule/storeSingleItem', this.order)
            if(res.status == 200 || res.status == 201){
                // for(let i of this.items){
                //     i.total_price = i.price * i.quantity
                //     i.variations = i.variation
                //     i.brand   = i.product.brand
                //     i.pshop   = i.product.pshop
                //     this.alldata.data.items.unshift(i)
                //     // console.log(i)
                // }
                // this.alldata.data.subTotal = this.order.subTotal
                this.alldata.data = res.data
                this.discount_from_product = parseFloat(this.alldata.data.discount_amount) - parseFloat(this.alldata.data.coupon_amount)
                // this.alldata.data.items.unshift
                this.s("Product Has been added successfully.") 
                this.items = []
                this.form = {}
            }
            else{
                this.swr()
            }
            this.productModal = false
            this.isUpdating = false 
        },
        async  addProduct(){
            if(!this.form.product_id || this.form.product_id ==null || this.form.product_id ==''){
                this.error.product_id = "please select a Product"
                return
            }
            if( this.form.quantity ==null || this.form.quantity<1){
                this.error.quantity = "please select a quantity must be getter than 0"
                return
            }
            if(!this.form.variation_id || this.form.variation_id ==null || this.form.variation_id ==''){
                this.error.variation_id = "Please select a variation"
                return
            }
            for(let i of this.items){ 
                if(i.variation_id == this.form.variation_id){
                    i.quantity = parseInt(i.quantity) + parseInt(this.form.quantity)
                    // this.productModal = false
                    this.clearALLproductData()
                    return
                }
            }
             if ( this.orderDetails && this.orderDetails.orderType == "Food" && this.items.length >0){
                // console.log(this.form)
                if(this.form.product.partner_shop_id != this.items[0].partner_shop_id)
                return this.e("If you wanna add this product than you have to clear your cart as your selected product gonna be in diffrent shop!")
            }
            this.form.order_id = parseInt(this.id)
            this.form.id  = 0
            this.items.push(this.form)
            this.clearALLproductData()
            
        },
        removeItem(index){
            this.items.splice(index, 1)
        },
         
        async openCreateModal(item){
            this.productModal = true
            await this.addCouponToOrder()
            this.items = _.clone(this.orderDetails.items)
        },
        async getAllOrders(item){
            const res = await this.callApi('get', `/ordermodule/getAllSingleOrders/${this.id}`)
            if(res.status == 200){
                this.alldata.data = res.data
                this.cashBack = this.alldata.data.discount_amount
                this.discount_from_product = parseFloat(this.alldata.data.discount_amount) - parseFloat(this.alldata.data.coupon_amount)
                this.orderDetails = res.data
                if(res.data){
                    // this.total_amount = parseFloat(res.data.coupon_amount) +  parseFloat(res.data.discount_amount)
                    this.propsData.name = res.data.users.name
                    this.propsData.user_id = res.data.user_id
                    this.propsData.order_id = res.data.id
                    this.propsData.address_id = res.data.address_id
                }
            }
            else if(res.status==422){
                this.e(res.data.message)
            }
            else{
                this.swr()
            }
           // this.createModal = true;
        },
        selectProduct(item){
            this.form ={
                quantity:1
            }
            this.variations = []
            this.singleItem = _.cloneDeep(this.products[item.value]) 
            // console.log(this.singleItem , "single item")
            this.variations = _.cloneDeep(this.singleItem.variations) 
            // this.pshop = _.cloneDeep(this.singleItem.pshop) 
            // this.brand = _.cloneDeep(this.singleItem.brand) 
            this.variationIndex =''
            this.form.product_id = this.singleItem.id
            this.form.product = this.singleItem
            this.form.name = this.singleItem.name
            this.form.category_id = this.singleItem.category_id
            this.form.discount = this.singleItem.discount
            this.form.partner_shop_id = this.singleItem.partner_shop_id
            this.form.product_category_id = this.singleItem.product_category_id
            this.form.product_subcategory_id = this.singleItem.product_subcategory_id
            this.form.band_id = this.singleItem.band_id
        },
        
        selectProductVariation(item){
            // console.log(this.variations, "variations item")
            // console.log(this.variationIndex, "variations index")
            if(!this.variations ||  !this.variations[item.value] || !this.variations[item.value].availability ||  this.variations[item.value].availability == 0){
                return this.e("This product has stock out please select another variant.")
            }
            // if(this.variations &&  this.variations[item.value] && this.variations[item.value].availability &&  this.variations[item.value].availability == 0)
            else{
                this.form.variations= this.variations[item.value]
                this.form.variation_id= this.variations[item.value].id
                this.form.price= this.variations[item.value].price
                this.form.variationName= this.variations[item.value].name
                this.form.stock= this.variations[item.value].stock
                this.form.variation_id= this.variations[item.value].id
              return
            }
            // else {
            //     return this.e("This product has stock out please select another variant")
            // }
        },
        clearALLproductData(){
            this.singleItem={},
            this.form={},
            this.variationIndex =''
            this.productIndex =''
            this.products=[]
        },
        clearErrorData(){
             this.error2 ={
                items:'',
                images:'',
                user_id:'',
                address_id:'',
                shipingCharge:'',
                orderDate:'',
                orderType:''
            }
        },
        validateData(){
            this.clearErrorData()
            let flag = true
            if(!this.order.orderDate || this.order.orderDate=='' || this.order.orderDate==null){
               this.error2.orderDate= "This field is required!"
               flag = false
            }
            if(!this.order.user_id || this.order.user_id=='' || this.order.user_id==null){
               this.error2.user_id= "This field is required!"
               flag = false
            }
            if(!this.order.address_id || this.order.address_id=='' || this.order.address_id==null){
               this.error2.address_id= "This field is required!"
               flag = false
            }
           
            if(!this.items || this.items.length<1){
               this.error2.items= "This field is required!"
               flag = false
            }
            return flag 
        },
        async storeOrder(){
            if(!this.validateData()){
                return
            }
            this.order.items = this.items
            this.order.subTotal = this.subTotal
            this.order.total = this.subTotal
            this.order.images = this.uploadedList
            const res = await this.callApi('post','/ordermodule/storeOrder', this.order)
            if(res.status == 200 || res.status == 201){
                this.alldata.data.unshift(res.data)
                this.order = {
                    items:[],
                    images:[],
                    user_id:'',
                    address_id:'',
                    shipingCharge:'',
                    orderDate:'',
                    orderType:'',
                    total:'',
                    subTotal:'',
                }
                this.clearErrorData()
                this.uploadedList =[]
                this.items =[]
                this.createModal = false
            }
            else if(res.status==422){
                return this.e(res.data.message)
            }
            else{
                this.swr()
            }
        
            
        },
        async getAllProducts(e=''){
            if(e){
                this.str = e
            }  
            this.pageSize = 20
           const res = await this.callApi('get', `/productmodule/getAllProductByCategoryWithVariationPrice?shopId=${this.shopId}&franchise_id=${this.orderDetails.franchise_id}&category_id=${this.orderDetails.category_id}&page=${this.page}&pageSize=${this.pageSize}&str=${this.str}&availability=true`)
            if(res.status==200){
                this.products = res.data.data
            } 
         },
         async selectCoustomer(item){
             this.singleUser = this.users[item.value]
             this.order.user_id = this.singleUser.id 
        },
         async getAllCoustomer(e=''){
            if(e){
                this.str2 = e
            }
           const res = await this.callApi('get', `/usermodule/getAllCoustomers?page=${this.page}&pageSize=${this.pageSize}&str=${this.str2}`)
            if(res.status==200){
                this.users = res.data.data
            } 
        },
        handleSuccess(res, file) {
             this.uploadedList.unshift({image:res.image, product_id:this.id})
        },
        handleFormatError(file) {
            this.isUploadError = true;
            this.uploadErrorText = "Supported files types: .JPG .PNG .JPEG ";
        },
        handleMaxSize(file) {
            if (file) {
                this.$Notice.warning({
                title: "Exceeding file size limit",
                desc: "File  " + file.name + " is too large, no more than 20Mb.",
                });
            }
        },
        handleProgress(event, file, fileList) {
            this.isUploadError = false;
            this.uploadErrorText = "";
            // this.uploadlist = fileList;
        },
        handleBeforeUpload() {
            // const check = this.uploadlist.length < 1;
            // if (!check) {
            //     this.$Notice.warning({
            //     title: "Up to 1 files can be uploaded.",
            //     });
            // }
            // return check;
		},
		 async handleView(item){
           this.image = item
           this.singleImageModal = true
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
        async addCouponToOrder(){
            if(this.orderDetails.coupon == null){
                 return
            }
            let ob = {
                code:this.orderDetails.coupon,
                user_id:this.orderDetails.user_id,
                category_id:this.orderDetails.category_id,
                subTotal:this.subTotal + this.orderDetails.subTotal,
            }
            this.isLoading=true;
            const res = await this.callApi('post','/ordermodule/checkCoupon',ob);
            if(res.status == 200){
                this.couponOb = res.data.data
                let amount = this.couponOb.discount;
                amount = this.couponOb.validity_type == 1 ? ((this.subTotal * amount)/100) : amount;
                amount = amount.toFixed(2) 
                this.orderDetails.coupon_amount = amount 
                this.couponModal = false;
            } 
            else if (res.status == 422){
                this.couponError = res.data.message
            }
            else this.swr();
            this.isLoading=false; 
        },
       
    },
    computed: {
    
        subTotal(){
            let price =0
            for(let it of this.items){
                if(it.price){
                    price += parseFloat(it.quantity) *  parseFloat(it.price)
                }
            }
            return price
        }
        ,
        totalCost(){
            let price = 0
            let amount = 0
            for(let it of this.items){
                if(it.price){
                    price += parseFloat(it.quantity) *  parseFloat(it.price)
                }
            }
            if(this.couponOb){
                amount = this.couponOb.discount;
                amount = this.couponOb.validity_type==1? ((price * amount)/100) : amount;
                amount = amount.toFixed(2)
                console.log(amount)
            }
            this.cashBack = amount
            if(this.alldata.data.paymentType == 'Redeem Cashback'){
                return price = this.alldata.data.shipingCharge
            }
            // price = price - amount;
            price += this.alldata.data.shipingCharge
            return price
        }
    },
    watch: {
     subTotal: function (newValue, oldValue) {
       this.addCouponToOrder()
    }
  },
}
</script>
<style>
.btn_extend{
    color: #f6f6f6;
    background: #8f5582;
}

</style>

 