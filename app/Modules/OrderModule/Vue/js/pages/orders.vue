
<template>
    <div class="_main_content">
		<div class="row">
            <div class="col-12 col-md-12 col-lg-12">
				<div class="_box _b_radious3 _padd20">
                        <div class="_1card_top _mar_b20">
							<div class="_1card_top_left">
								<p class="_1card_top_title">Orders</p>
								<div class="_1card_top_search">
									<Input search enter-button="Search" @on-change="serchResetlt" v-model="str" placeholder="Enter something..." />
								</div>  
                                <!-- <div class="_1card_top_limit"> 
                                    <p class="limit_header" >Select hourly order taking limit :</p>
                                    <InputNumber @on-change="order_limit_save" :min="1" v-model="form1.limit"></InputNumber>
                                </div> -->
							</div>
							<div class="_1card_top_right">
								<ul class="_1card_top_right_list">
									<li v-if="authInfo.userType != 'ShopOwner'" ><Button type="primary" @click="openCreateModal">Add</Button></li>
								</ul>
							</div>
						</div>
						<div class="_table_responsive">
							<Table class="_table700" border :columns="columns" :data="alldata.data">
                                <template slot="user" slot-scope="{ row }">
									<p v-if="row.users && row.users.name">
                                         {{row.users.name}}
                                    </p>
								</template>
                                <template slot="product" slot-scope="{ row }">
									<p v-if="row.items && row.items.product">
                                         {{row.items.product.name}}
                                    </p>
								</template>
                                <template slot="driver" slot-scope="{ row }">
									<p v-if="row.driver && row.driver.name">
                                         {{row.driver.name}}
                                    </p>
								</template> 
                                <template slot="total" slot-scope="{ row }">
									{{row.total}} ৳ 
								</template> 
                                <template slot="discount_amount" slot-scope="{ row }">
									{{row.discount_amount}} ৳ 
								</template>
                                <template slot="coupon_amount" slot-scope="{ row }">
									{{row.coupon_amount}} ৳ 
								</template>
                                <template slot="shipingCharge" slot-scope="{ row }">
									{{row.shipingCharge}} ৳ 
								</template>
                                <!-- <template slot="status" slot-scope="{ row }">
									{{row.status}}
								</template> -->
                                <template slot-scope="{row,index}" slot="status">
                                    <!-- <template v-if="status && index == statusIndex">
                                        <span class="dropdown_div">
                                            <select v-model="status_change.status" :placeholder="row.status" >
                                                <option value="Panding">Panding</option>
                                                <option value="Processing">Approve</option>
                                                <option value="Completed">Complete</option>
                                                <option value="Canceled">Cancel</option>
                                            </select> 
                                            <Select v-model="statusOb.status" filterable>
                                                <Option v-for="(item, index) in statuses" :value="item" :key="index" >{{item}}</Option>
                                            </Select>
                                            <Icon @click="statusIndex=-1" style="cursor:pointer" type="ios-close-circle" />
                                            <span @click="updateOrderStatus(row.id)" style="cursor:pointer" ><Icon type="md-checkbox" /></span>
                                        </span>
                                    </template> -->
                                    <template  >
                                        <Button v-if="authInfo.userType != 'ShopOwner'" :type="[row.status=='Delivered' ? 'success' : '' ]" @click="openStatusModal(row.status,index)">  <Icon type="md-open" />  {{row.status}}
                                        </Button>
                                        <Button v-else :type="[row.status=='Delivered' ? 'success' : '']"
                                         > {{row.status}}</Button>
                                         <!-- 
                                          :type="row.status=='Pending'? 'primary' : 'success'" -->
                                     </template>
                                </template>
                                <template slot="action" slot-scope="{ row }">
									<Button type="primary" @click="$router.push('/ordermodule/orderDetails/'+row.id)">See more details</Button>
								</template>
							</Table>
						</div>
                        <div class="_pagination _padd_t20" v-if="alldata && alldata.total!=null">
							<Page :total="alldata.total" show-sizer :page-size-opts="pageOption" @on-page-size-change="getSizeChange"  @on-change="paginateDataInfo" />
						</div>
                </div>
            </div>
        </div>
        <Modal title="Create Order "  v-model="createModal" :mask-closable="false" :width="800">
            <Form  :model="form" label-position="top" >
                <div class="row" >
                    <div class="col-md-6" >
                        <FormItem  :error="error2.franchise_id"  label="Select franchise">
                            <Select v-model="order.franchise_id" @on-change="selectFranchise" :disabled="!isAdmin">
                                <Option v-for="(item, index) in franchiseList" :value="item.id" :key="index">{{item.name}}</Option>
                            </Select>
                        </FormItem>
                    </div>
                    <div class="col-md-6">
                        <FormItem   label="Select category">
                            <Select v-model="order.category_id" @on-change="onSelectCategory">
                                <Option v-for="(item, index) in categories" :value="item.id" :key="index">{{item.name}}</Option>
                            </Select>
                        </FormItem>
                    </div>
                    <div class="col-md-12"  >
                        <FormItem  :error="error2.items" label="Products">
                          <Button v-if="order.category_id==''" type="primary" long @click="productModal=true" disabled  >Add Product</Button>
                          <Button v-else type="primary" long @click="productModal=true"   >Add Product</Button>
                        </FormItem>
                    </div>
                
                    <div class="col-md-12" >
                        <Table  border :columns="columns1" :data="items" v-if="items.length >0">
                            <template slot="total" slot-scope="{ row }">
                                {{row.quantity * row.price}}
                            </template>
                            <template slot="quantity" slot-scope="{ row , index}">
                                <InputNumber   :min="1" v-model="row.quantity" @on-change="memo(row.quantity, index)"
                                :editable="false"></InputNumber>
                            </template>
                        </Table>
                    </div>
                    <div class="col-md-6" >
                        <FormItem  :error="error.images"  v-if="order.category_id == 3"  label="Prescription">
                            <Button type="primary" @click="imageModalOn">Add images</Button>
                        </FormItem>
                    </div>
                    <div class="col-md-12"  style="margin-top: 15px;">
                        <FormItem  :error="error2.user_id" label="Customer Name" >
                            <Select v-model="userIndex"  filterable @on-select="selectCoustomer" :remote-method="getAllCoustomer" >
                                <Option v-for="(item, index) in users" :value="index" :key="index" :label="item.name">{{item.name}}</Option>
                            </Select>
                        </FormItem>
                    </div>
                    <div class="col-md-3">
                        <FormItem prop="orderDate" :error="error2.orderDate"  label="Is Schedule Order?">
                            <RadioGroup  v-model="order.isScheduled">
                                <Radio :label="0" >No</Radio>
                                <Radio :label="1">Yes</Radio>
                            </RadioGroup>
                        </FormItem>
                    </div>
                    <div class="col-md-3">
                        <FormItem prop="orderDate" :error="error2.orderDate"    label="Order date">
                            <DatePicker @on-change="formateDate" type="date" :disabled="order.isScheduled == 1? false : true" placeholder="Select date" :value="order.orderDate" ></DatePicker>
                        </FormItem>
                    </div>
                    <div class="col-md-6">
                        <FormItem   :error="error.product_id" label="Select Delivery Time ">
                            <Select v-model="slot.id" filterable @on-change="selectSlot" :disabled="order.isScheduled == 1? false : true">  
                                <Option v-for="(item, index) in slots" :value="item.id" :key="index" >{{item.startTime}} - {{item.endTime}}</Option>
                            </Select>
                        </FormItem>
                    </div>                         
                    <!-- <FormItem prop="originalList"  label="Images" :error="error.image" >
                        <div class="demo-upload-list" v-for="(item, i) in originalList" :key="i">
                            <template v-if="item.image">
                                <img :src="item.image">
                                <div class="demo-upload-list-cover">
                                    <Icon type="ios-eye-outline" @click.native="handleView(item.image)"></Icon>
                                    <Icon type="ios-trash-outline" @click.native="handleRemove1(i)"></Icon>
                                </div>
                            </template>
                        </div>
                    </FormItem> -->
                    <div class="col-md-12">
                        <FormItem  :error="error2.address_id"    label="Select Address" v-if="singleUser && singleUser.delivery_adddress">
                            <div  v-for="(item, indexx) in singleUser.delivery_adddress" :value="indexx" :key="indexx" >
                                <Card  :class="(order.address_id==item.id)?'card_active':''"  >
                                    <p slot="title">
                                        <!-- <Icon type="ios-checkmark-circle" :class="(order.address_id==item.id)?' ':'not_active'"/> -->
                                         <Checkbox v-model="order.address_id" :true-value="item.id" :false-value="''" @on-change="selectedDeleveryIndex(item.id)">{{ item.title }}</Checkbox>
                                    </p>
                                    <ul class="_padd_l10">
                                        <li>
                                            <span>
                                                {{ item.address }}
                                            </span>
                                        </li>
                                        <li v-if="item.city || item.country">
                                            <span v-if="item.city  && item.country">
                                                {{ item.city }}, {{ item.country }}
                                            </span>
                                            <span v-else>
                                                {{ item.city }} {{ item.country }}
                                            </span>
                                        </li>
                                    </ul>
                                </Card>
                            </div>
                        </FormItem>
                    </div>
                    <div class="col-md-6">
                        <FormItem  label="Payment type">
                            <Select v-model="order.paymentType" >
                                <Option value="Cash On Delivery" >Cash On Delivery</Option>
                                <Option value="Redeem Cashback" >Redeem Cashback</Option>
                            </Select>
                        </FormItem>
                    </div>
                    <div class="col-md-6">
                        <FormItem  label="Remarks">
                            <Input v-model="order.remarks" type="textarea" :autosize="{minRows: 2,maxRows: 5}" placeholder="Remarks"/>
                        </FormItem>
                    </div>
                    <div class="col-md-6">
                        <FormItem  label="coupon">
                            <Button type="primary" v-if="couponOb==null" @click="couponModalOn">Add Coupon</Button>
                            <Input  v-else-if="couponOb" v-model="couponOb.code"  disabled />
                        </FormItem>
                    </div>
                    <div class="col-md-6">
                        <FormItem  label="coupon Discount">
                            <span v-if="couponOb"> {{couponOb.discount}}{{couponOb.validity_type==1? '%' : ''}}</span>
                            <span v-else-if="couponOb==null">0</span>
                        </FormItem>
                    </div>
                    <div class="col-md-12" >
                        <FormItem  :error="error2.driver_id" label="Driver Name"> 
                            <Select v-model="order.driver_id"  filterable :remote-method="getAllDriver"  >
                                <Option v-for="(item, index) in drivers" :value="item.id" :key="index" :label="item.name">{{item.name}}</Option>
                            </Select>
                        </FormItem>
                    </div>
                    <div class="col-md-4">
                        <FormItem prop="subTotal"   label="Sub total:" >
                            {{subTotal}}
                         </FormItem>
                    </div>
                    <div class="col-md-4">
                        <FormItem prop="totalCost"   label="Grand total:">
                            {{ totalCost }}
                        </FormItem>
                    </div>
                    <div class="col-md-4">
                        <FormItem prop="shipingCost"   label="Shiping Cost:">
                            <p>{{ shippingCost }}</p>
                        </FormItem>
                    </div>
                </div>
            </Form>
            <div slot="footer"> 
                <Button   @click="createModal=false" >Cancel</Button>
                <Button @click="storeOrder" type="primary"  :loading="isLoading" :disabled="isLoading">{{isLoading? 'Please wait' : 'Create'}}</Button>
            </div>
        </Modal>
        <Modal title="Coupon"  v-model="couponModal" :mask-closable="false">
            <Form  label-position="top" >
                <FormItem  :error="couponError"    label="Code" >
                    <Input v-model="order.coupon" type="text" placeholder="coupon"/>
                </FormItem>
            </Form>
            <div slot="footer">
                <Button   @click="couponModal=false" >Cancel</Button>
                <Button type="primary" @click="addCouponToOrder"  :loading="isLoading" :disabled="isLoading">{{isLoading? 'Please wait' : 'add'}}</Button>
            </div>
        </Modal>
        <Modal title="Select product"  v-model="productModal" :mask-closable="false">
            <Form  :model="form" label-position="top" >
                <FormItem  :error="error.product_id" label="Product Name">
                    <Select v-model="productIndex" filterable @on-change="selectProduct" :remote-method="getAllProducts">
                        <Option v-for="(item, index) in products" :value="item.id" :key="index" :label="item.name">
                             {{item.name}} | 
                            <span v-if="item.productc_category"> {{item.productc_category.name}} | </span>
                            <span v-if="item.sub_category"> {{item.sub_category.name}} </span>
                            </Option>
                    </Select>
                </FormItem>
                <FormItem prop="variation_id" :error="error.variation_id" label="Select Variation" >
                    <Select
                        v-model="variationIndex"
                        @on-select="selectProductVariation"
                        filterable 
                        > 
                            <!-- :remote-method="getAllProducts" -->
                        <Option v-for="(item, index) in variations" :value="index" :key="index" :label="item.name">{{item.name}}</Option>
                    </Select>
                </FormItem>
                <FormItem  :error="error.quantity"    label="Quantity" v-if="singleItem &&singleItem.variations &&  singleItem.variations[variationIndex]">
                     <InputNumber    :min="1" v-model="form.quantity"
                         :editable="false"></InputNumber>
                </FormItem>
            </Form>
            <div slot="footer">
                <Button   @click="productModal=false" >Cancel</Button>
                <Button type="primary" @click="addProduct"  :loading="isLoading" :disabled="isLoading">{{isLoading? 'Please wait' : 'Add'}}</Button>
            </div>
        </Modal>
        <Modal title="Image"  v-model="imageModal" :mask-closable="false">
                        <div  v-if="order.pimage"> 
                            <img :src="order.pimage">
                            <div style="cursor:pointer" >
                                <!-- <Icon type="ios-eye-outline" @click.native="handleView()"></Icon> -->
                                <Icon type="ios-trash-outline" @click.native="handleRemove()"></Icon>
                            </div>
 						</div>
                        <Upload v-else
                                ref="upload"
                                name="img"
                                :show-upload-list="false"
                                :format="['jpg','jpeg','png','webp']"
                                :max-size="20480"
                                :on-success="handleSuccess"
                                :on-format-error="handleFormatError"
                                :on-exceeded-size="handleMaxSize"
                                :before-upload="handleBeforeUpload"
                                :on-progress="handleProgress"
                                type="drag"
                                action="/usermodule/upload/images"
                                style="width: 100% !important"
                                >
                                <div style="padding: 20px 0">
                                    <Icon type="ios-cloud-upload" size="52" style="color: #3399ff"></Icon>
                                    <p>Click or drag files here to upload</p>
                                </div>
                        </Upload>
                  
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
    </div>
</template>
<script>
import {mapGetters} from 'vuex'
export default {
    data(){
        return{
            target_price:0,
            form1:{
                limit:1
            },
            slot:{
                id:0,
                max_order:''
            },
            couponError:'',
            statusModal:false,
            imageModal:false,
            couponOb:null,
            orderData:{
            },
            singleUser:{},
            userIndex:'',
            franchiseIndex:'',
            categoryIndex:'',
            user_id:'',
            address_id:'',
            deliverIndex:-1,
            productIndex:'',
            variationIndex:'',
            productModal:false,
            isLoading:false,
            products:[],
            shippingCharge:[],
            items:[],
            users:[],
            drivers:[],
            createModal:false,
            couponModal:false,
            pageOption:[5,10,15,20],
            slots:[],
            columns: [
                //driver name, product name, cupon ammount , total ammount, discount ammount,
				 {
                    title: 'Id',
                    sortable: true, 
                    key: 'id',
                    width:100
                },
				{
					title: 'Product name',
                    slot: 'product',
                    width: 250
				},
				{
					title: 'Customer name',
                    slot: 'user',
                    width: 200
				},
				{
					title: 'Total amount',
                    slot: 'total',
                    width: 130
				},
				{
					title: 'Coupon amount',
                    slot: 'coupon_amount',
                    width: 150
				},
				{
					title: 'Total Discount',
                    slot: 'discount_amount',
                    width: 160
				},
				{
					title: 'Shipping  charge',
                    slot: 'shipingCharge',
                    width: 150 
				},
				{
					title: 'Status',
                    slot: 'status',
                    width: 170
				},
				{
					title: 'Driver',
                    slot: 'driver',
                    width: 110
				},
				{
					title: 'Action',
					slot: 'action',
					width: 180
				}
            ],
            columns1: [
				
				{
					title: 'Name',
                    key: 'name',
                    width: 200
				},
                {
					title: 'Variation',
                    key: 'variationName',
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
				}
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
            singleImageModal : true,
            order:{
                items:[],
                images:[],
                user_id:'',
                category_id:'',
                pimage:'',
                coupon:'',
                coupon_amount:'',
                schedule_id:'',
                discount_amount:'',
                address_id:'',
                delivery_time:'',
                estimate_delivery_time:'',
                shipingCharge:0,
                paymentType:'Cash On Delivery',
                orderDate:'',
                slot:'',
                status:'',
                orderType:'',
                remarks:'',
                statusUpdated:'',
                total:'',
                subTotal:0,
                franchise_id:'',
                driver_id:0,
                isScheduled:0,
            },
            error2 : {
                items:'',
                images:'',
                user_id:'',
                driver_id:'',
                address_id:'',
                shipingCharge:'',
                franchise_id:'',
                orderDate:'',
                orderType:''
            },
            statusIndex:-1,
            status:false,
            status_change:{
                status:'',
                order_id:''
            },
            is_slot_active:false,
            categories:[],
            franchises:[],
            auth_user_type:'',
            statuses:['Processing','Ready to Pick','Picked','Delivered','Canceled'],
            shop_statuses:['Processing','Ready to Pick'],
            statusOb:{
                status:'',
                order_id:-1
            },
        }
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
        },
        totalCost(){
            let price =0
            let amount =0
            for(let it of this.items){
                if(it.price){
                    price += parseFloat(it.quantity) *  parseFloat(it.price)
                }
            }
            if(this.couponOb){
                amount = this.couponOb.discount;
                amount = this.couponOb.validity_type == 1? ((price * amount)/100) : amount;
                amount = amount.toFixed(2)
            }
            if(this.order.paymentType == 'Redeem Cashback'){
                return price = 0 + this.order.shipingCharge
            }
            price = price - amount;
            return price + this.order.shipingCharge
        },
        shippingCost(){
            let price = 0
            if(this.order.shipingCharge){
                price = this.order.shipingCharge
            }
            return price
        },
    },
    methods:{
        openStatusModal( status,index ){
            this.statusOb.order_id = this.id
            this.statusOb.status = status
            this.statusIndex = index
            this.statusModal = true
        },
        couponModalOn(){
            if(!this.order.category_id)
            return this.e("Please select a category!")
            if(!this.order.user_id)
            return this.e("Please select a customer!")
            this.couponModal = true
        },
        onSelectCategory(id){
            let index = this.categories.findIndex(d => d.id == id)
            if(this.categories[index].name == 'Restaurants') this.order.orderType = 'Food';
            else this.order.orderType = 'General';
        },
        memo(quantity, product_index){
            // console.log(quantity)
            for(let i of this.items){
                i.quantity = quantity
                //  console.log(i)
            }
        },
        selectFranchise(){
            this.userIndex = '' 
            this.productIndex = ''
            this.items = []
            this.users = []
            this.drivers = []
            this.singleUser = {},
            this.order.address_id = ''
            this.order.shipingCharge = 0
            // this.getAllDriver()
            // this.getAllProducts()
        },
        async getShippingPrice(){
            const res = await this.callApi('post', `/ordermodule/getShippingPrice`,{id:this.order.address_id, franchise_id:this.order.franchise_id})
            if(res.status==200){
                if(res.data.place == 'Outside'){
                    if(this.order.category_id == 2){
                        this.e('Could not serve at this location.')
                        this.createModal = false
                    }
                }
                this.order.shipingCharge = res.data.price
                if(res.data.place == 'Inside'){
                    if(this.subTotal > 1000) this.order.shipingCharge = 0
                }
                // console.log(this.order.shipingCharge)
            }
        },
        async getAllCategory(){
            const res = await this.callApi('get', `/productmodule/getAllCategory`)
            if(res.status==200){
                this.categories = res.data.data.data
            } 
        },
        async getAllFranchise(){
            const res = await this.callApi('get', `/franchisemodule/allfranchises`)
            if(res.status==200){ 
                this.franchises = res.data.data
            } 
        },
        selectSlot(item){
            var index = this.slots.findIndex(x => x.id === item);
            var schedule = this.slots[index]
            // console.log(index)
            this.slot.id = schedule.id
            this.slot.max_order = schedule.max_order
            this.order.delivery_time = schedule.startTime +' - '+ schedule.endTime
            this.order.schedule_id = schedule.id
            // console.log(this.order.schedule_id)
            let flag = true
            if(!this.order.orderDate || this.order.orderDate=='' || this.order.orderDate==null){
               this.error2.orderDate= "This field is required!"
               flag = false
            } 
            if(!this.order.user_id || this.order.user_id=='' || this.order.user_id==null){
               this.error2.user_id= "This field is required!"
               flag = false
            }
            if(this.order.orderDate != '' && this.slot.id != 0){
                this.isAvailableSlot()
            }
            // this.isAvailableSlot()
        },
        async getAllDriver(e=''){
            if(e){
                this.str2 = e
            }
            if(!this.order.franchise_id || this.order.franchise_id=='' || this.order.franchise_id==null){
               this.error2.franchise_id= "This field is required!"
               flag = false
            }
            const res = await this.callApi('get', `/usermodule/getAllDriver?str=${this.str2}&franchise_id=${this.order.franchise_id}`)
            if(res.status==200){
                this.drivers = res.data.data
            } 
        },
        async isAvailableSlot(){
            const res = await this.callApi('post', `/ordermodule/isAvailableSlot`,{schedule_id:this.slot.id, date : this.order.orderDate , max_order:this.slot.max_order} )
            if(res.status == 200){
                this.order.slot = this.slot
            }
            else if(res.status==422){
                this.e(res.data.message)
            }
            else this.swr()
        },
        clickStatusOn(status,index){
            this.status = true
            this.statusOb.status = status
            this.statusIndex = index
        },
        async updateOrderStatus(){ 
            this.statusOb.order_id = this.alldata.data[this.statusIndex].id
            // console.log(this.statusOb)
            if(this.statusOb.status == 'Delivered' ){
                const res  = await this.callApi('post','/ordermodule/deliveredStatusUpdate',this.statusOb)
                if(res.status == 200){
                    this.alldata.data[this.statusIndex].status = this.statusOb.status
                    this.s("Status has been updated.")
                    this.statusIndex = -1
                }
                else this.swr();
            }else{
                const res  = await this.callApi('post','/ordermodule/updateOrderStatus',this.statusOb)
                if(res.status == 200){
                    this.alldata.data[this.statusIndex].status = this.statusOb.status
                    this.s("Status has been updated.")
                    this.statusIndex = -1
                }
                else this.swr();
            }
            
            this.statusModal = false
         },
        async changeStatus(id,index){
            this.status_change.order_id = id
            this.status = false
            const res = await this.callApi('post', `/ordermodule/updateOrderStatus`, this.status_change)
            if(res.status == 200){
                this.alldata.data[index].status = this.status_change.status
                this.s("Status has been updated")
                 this.statusIndex = -1
            }
            else if(res.status==422){
                this.e(res.data.message)
            }
            else this.swr()
        },
        // clickStatusOn(status,index){
        //     this.status_change.status = status
        //     this.statusIndex = index
        //     this.status = true
        // },
        formateDate(e){
            this.order.orderDate = e
            if(this.slot.id != 0){
                this.isAvailableSlot()
            }
        },
        handleRemove1(i){
            this.originalList.splice(i, 1)
        },
        handleRemove(i){
            this.order.pimage = ''
        //   this.uploadedList.splice(i, 1)
        },
        storeImages(){
            this.originalList = _.cloneDeep(this.uploadedList) 
            this.imageModal=false
        },
        imageModalOn(){
            //  this.uploadedList =  _.cloneDeep(this.originalList) 
			this.imageModal=true
		}, 
        closes(){
            this.imageModal=false
            this.uploadedList = []
        },
        selectedDeleveryIndex(id){
            this.order.address_id = id
            this.getShippingPrice()
        },
        async addCouponToOrder(){
            this.couponError = '';
            if(this.order.coupon==''){
                this.couponError = "Please enter a coupon code!"
                return
            }
            let ob = {
                code:this.order.coupon,
                user_id:this.order.user_id,
                category_id:this.order.category_id,
                subTotal:this.subTotal,
            }
            this.isLoading=true;
            const res = await this.callApi('post','/ordermodule/checkCoupon',ob);
            if(res.status == 200){
                this.couponOb = res.data.data
                // if(this.couponOb.target_price != null){
                //     this.target_price = this.couponOb.target_price
                // }
                let amount = this.couponOb.discount;
                amount = this.couponOb.validity_type==1? ((this.subTotal * amount)/100) : amount;
                amount = amount.toFixed(2)
                this.order.coupon_amount = amount
                this.couponModal = false;
            }
            else if (res.status == 422){
                this.couponError = res.data.message
            }
            else this.swr();
            this.isLoading=false; 
        },
        addProduct(){
            if(!this.form.product_id || this.form.product_id ==null || this.form.product_id ==''){
                this.error.product_id = "please select a Product."
                return
            }
            if( this.form.quantity ==null || this.form.quantity<1){
                this.error.quantity = "Product quantity must be getter than 0."
                return
            }
            if(!this.form.variation_id || this.form.variation_id ==null || this.form.variation_id ==''){
                this.error.variation_id = "Please select a variation."
                return
            }
            // console.log(this.items)
            for(let i of this.items){
                if(i.variation_id == this.form.variation_id){
                    i.quantity = parseInt(i.quantity) + parseInt(this.form.quantity)
                    this.productModal = false
                    this.clearALLproductData()
                    return
                }
            }
            if (this.order.orderType == 'Food'){
                 for(let i of this.items){
                    if(i.partner_shop_id != this.form.partner_shop_id)
                    return this.e("If you wanna add this product than you have to clear your cart as your selected product gonna be in diffrent shop!")
                }
            }
            this.items.unshift(this.form)
            // console.log(this.items)
            // console.log(this.items)
            this.productModal = false
            this.clearALLproductData()
        },
        serchResetlt(){
			this.pageSize = 10
			this.paginateDataInfo(1)
		},
		getSizeChange(e){
			this.pageSize =e
			this.paginateDataInfo(1)
		},
		paginateDataInfo(e){
			   this.page = e
			   this.getAllOrders()
        },
        openEditModal(index){
            this.items.splice(index, 1)
        },
        openCreateModal(item){
            
            this.createModal = true;
            this.order.franchise_id = this.franchise_id;
        },
        async getAllOrders(item){
            const res = await this.callApi('get', `/ordermodule/getAllOrders?page=${this.page}&pageSize=${this.pageSize}&str=${this.str}`)
            if(res.status == 200){
                this.alldata = res.data
            }
            else if(res.status==422){
                this.e(res.data.message)
            }
            else{
                this.swr()
            }
           // this.createModal = true;
        },
        async getOrderLimits(){
            const res = await this.callApi('get', `/ordermodule/getOrderLimit`)
            if(res.status == 200){
                this.form1.limit = res.data.limits
            }
            else if(res.status==422){
                this.e(res.data.message)
            }
            else{
                this.swr()
            }
        }, 
        selectProduct(item){
            this.form ={
                quantity:1
            }
            var index = this.products.findIndex(x => x.id === item);
            if(index >= 0){
                this.variations = []
                this.singleItem = _.cloneDeep(this.products[index]) 
                // console.log(this.singleItem)
                this.variations = _.cloneDeep(this.singleItem.variations) 
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
            }
        },
        selectProductVariation(item){
            this.singleItem = _.cloneDeep(this.variations[item.value]) 
            // console.log(this.singleItem, "variations item")
            if(!this.variations ||  !this.variations[item.value] || !this.variations[item.value].availability ||  this.variations[item.value].availability == 0){
                return this.e("This product has stock out please select another variant.")
            }
            this.form.variation_id= this.variations[item.value].id
            this.form.variation= this.variations[item.value]
            this.form.price= this.variations[item.value].price
            this.form.variationName= this.variations[item.value].name
            this.form.stock= this.variations[item.value].stock
        },
        clearALLproductData(){
            this.singleItem={},
            this.form={},
            this.variationIndex =''
            // this.productIndex =''
            this.products=[]
        },
        clearErrorData(){
             this.error2 ={
                items:'',
                images:'',
                user_id:'',
                address_id:'',
                shipingCharge:'',
                franchise_id:'',
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
            if(this.order.subTotal < this.target_price)
            return this.e(`Please order minimum ${this.target_price}!.`)
            this.order.total = this.totalCost
            this.order.images = this.uploadedList
            const res = await this.callApi('post','/ordermodule/storeOrder', this.order)
            if(res.status == 200 || res.status == 201){
                location.reload();
                this.alldata.data.unshift(res.data)
                this.order = {
                    items:[],
                    images:[],
                    user_id:'',
                    category_id:'',
                    pimage:'',
                    coupon:'',
                    coupon_amount:'',
                    schedule_id:'',
                    discount_amount:'', 
                    address_id:'',
                    delivery_time:'',
                    estimate_delivery_time:'',
                    shipingCharge:0,
                    paymentType:'',
                    orderDate:'',
                    slot:'',
                    status:'',
                    orderType:'',
                    remarks:'',
                    statusUpdated:'',
                    total:'',
                    subTotal:'',
                    franchise_id:'',
                    driver_id:'',
                }
                this.clearErrorData()
                this.uploadedList =[]
                this.items =[]
                this.slot = {
                    id:0,
                    max_order:''
                }
                this.createModal = false
                this.clearALLproductData()
            }
            else if(res.status==422){
                return this.e(res.data.message)
            }
            else{
                this.swr()
            }
        },
        async getAllProducts(query=''){
            // console.log(query)
            if(query){
                this.str = query
            }
           const res = await this.callApi('get', `/productmodule/getAllProductByCategoryWithVariationPrice?shopId=${this.shopId}&page=${this.page}&pageSize=20&str=${this.str}&franchise_id=${this.order.franchise_id}&category_id=${this.order.category_id}&availability=true`)
            if(res.status==200){
                this.products = res.data.data
                // console.log(this.products)
            }
            // if(this.products[0]) {
            //     let p = this.products[0]? this.products[0].name : ''
            // }
            // console.log("p- ",p)
        },
        async selectCoustomer(item){
            var today = new Date();
            var dd = String(today.getDate()).padStart(2, '0');
            var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
            var yyyy = today.getFullYear();

            today =  yyyy + '-' + mm + '-' + dd ;
            this.order.orderDate = today
            this.is_slot_active = true
            this.singleUser = this.users[item.value]
            this.form.franchise_id = this.singleUser.franchise_id
            this.order.user_id = this.singleUser.id 
            await this.getDaySlot();
            if(this.order.isScheduled == 0){

                let autoOb = {
                    franchise_id:this.order.franchise_id,
                }
                const res = await this.callApi('post', `/ordermodule/checkAutoOrderSchedule`, autoOb)
                if(res.status == 200){
                    var schedule = res.data.data
                    this.slot.id = schedule.id
                    this.slot.max_order = schedule.max_order
                    this.order.delivery_time = schedule.startTime +' - '+ schedule.endTime
                    this.order.schedule_id = schedule.id
                }
            }        
        },
        async getDaySlot(){
             const res = await this.callApi('post', `/ordermodule/getAvailableSchedule`, {id:this.singleUser.franchise_id})
            
            if(res.status == 200){
                this.slots =  res.data
            }
        },
        async getAllCoustomer(e=''){
            if(e){
                this.str2 = e
            }
            if(!this.order.franchise_id || this.order.franchise_id=='' || this.order.franchise_id==null){
               this.error2.franchise_id= "This field is required!"
               flag = false
            }
           const res = await this.callApi('get', `/usermodule/getAllCoustomers?page=${this.page}&pageSize=${this.pageSize}&str=${this.str2}&franchise_id=${this.order.franchise_id}`)
            if(res.status==200){
                this.users = res.data.data
            } 
        },
        handleSuccess(res, file) {
            this.order.pimage = res.image
            // console.log(this.order.pimage)
            //  this.uploadedList.unshift({image:res.image, product_id:this.id})
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
           this.image = this.order.pimage
           this.singleImageModal = true
        },
         
    },
    
    async created(){
        await this.getAllOrders()
         this.getAllCategory()
        
        this.auth_user_type =authUser.userType 
       
       
        
    },
}
</script>
