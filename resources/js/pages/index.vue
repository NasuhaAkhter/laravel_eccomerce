<template>
	<div>
		<div class="_main_content">
			<div class="row">
				<!-- <div class="col-12 col-md-12 col-lg-12 _mar_b10">
					<Alert show-icon>
						Custom icon
						<Icon type="ios-bulb-outline" slot="icon"></Icon>
						<template slot="desc">Custom icon copywriting. Custom icon copywriting. Custom icon copywriting. </template>
					</Alert>
				</div>   -->
				<div class="col-12 col-md-6 col-lg-3 _mar_b20" v-if="auth_user_type == 'Admin'">
					<router-link to="/franchisemodule/franchise-list">
					<div class="_box _b_radious3">
						<div class="_dash_card">
							<div class="_dash_card_icon">
 								<Icon type="ios-locate" />
							</div>
							<div class="_dash_card_details"> 
								<p class="_dash_card_amm">{{ total_franchise }}</p>
								<p class="_dash_card_title">Total Franchise</p>
							</div>
						</div>
					</div>
					</router-link>
				</div>
					<div class="col-12 col-md-6 col-lg-3 _mar_b20">
				        <router-link to="/franchisemodule/shop-list">
						<div class="_box _b_radious3">
							<div class="_dash_card">
								<div class="_dash_card_icon">
									<Icon type="ios-home" />
								</div>
								<div class="_dash_card_details">
									<p class="_dash_card_amm">{{ total_shop }}</p>
									<p class="_dash_card_title">Total Shop</p>
								</div>
							</div>
						</div>
            		    </router-link>

					</div>
			
				<div class="col-12 col-md-6 col-lg-3 _mar_b20">
				<router-link to="/usermodule/user-list">
					<div class="_box _b_radious3">
						<div class="_dash_card">
							<div class="_dash_card_icon">
 								<Icon type="ios-people" />
							</div>
							<div class="_dash_card_details">
								<p class="_dash_card_amm">{{ total_customer }}</p>
								<p class="_dash_card_title">Total Customer</p>
							</div>
						</div>
					</div>
					</router-link>

				</div>
				
				<div class="col-12 col-md-6 col-lg-3 _mar_b20">
					<router-link to="/usermodule/driverList">
					<div class="_box _b_radious3">
						<div class="_dash_card">
							<div class="_dash_card_icon">
								<Icon type="ios-car" />
							</div>
							<div class="_dash_card_details">
								<p class="_dash_card_amm">{{ total_driver }}</p>
								<p class="_dash_card_title">Total Driver</p>
							</div>
						</div>
					</div>
					</router-link>
				</div>
                
				
				<div class="col-12 col-md-6 col-lg-3 _mar_b20">
									<router-link to="/productmodule/product">

					<div class="_box _b_radious3">
						<div class="_dash_card">
							<div class="_dash_card_icon">
								<!-- <Icon type="ios-cart" /> -->
								<Icon type="ios-basket" />
							</div>
							<div class="_dash_card_details">
								<p class="_dash_card_amm">{{ total_product }}</p>
								<p class="_dash_card_title">Total Product</p>
							</div>
						</div>
					</div>
					                </router-link>

				</div>
				
				<div class="col-12 col-md-6 col-lg-3 _mar_b20">
					<router-link to="/ordermodule/orders">

					<div class="_box _b_radious3">
						<div class="_dash_card">
							<div class="_dash_card_icon">
 								<!-- <Icon type="md-bookmarks" /> -->
								<!-- <i class="fas fa-bells"></i> -->
								<Icon type="ios-notifications" />
							</div>
							<div class="_dash_card_details">
								<p class="_dash_card_amm">{{ getNewOrder }}</p>
								<p class="_dash_card_title">New Order</p>
							</div>
						</div>
					</div>
					</router-link>

				</div>
				
				<div class="col-12 col-md-6 col-lg-3 _mar_b20">
					<router-link to="/ordermodule/orders">
					<div class="_box _b_radious3">
						<div class="_dash_card">
							<div class="_dash_card_icon">
 								<Icon type="md-bookmarks" />
							</div>
							<div class="_dash_card_details">
								<p class="_dash_card_amm">{{ total_order }}</p>
								<p class="_dash_card_title">Total Order</p>
							</div>
						</div>
					</div>
					</router-link>

				</div>
				
				<div class="col-12 col-md-6 col-lg-3 _mar_b20">
					<router-link to="/ordermodule/orders">
						<div class="_box _b_radious3">
							<div class="_dash_card">
								<div class="_dash_card_icon">
									<!-- <Icon type="ios-cart" /> -->
									<Icon type="md-checkmark-circle-outline" />
								</div>
								<div class="_dash_card_details">
									<p class="_dash_card_amm">{{ total_delivered_order }}</p>
									<p class="_dash_card_title">Total Delivered Order </p>
								</div>
							</div>
						</div>
					</router-link>
				</div>
				<div class="col-12 col-md-6 col-lg-3 _mar_b20">
					<router-link to="/usermodule/newMemberRequest">
						<div class="_box _b_radious3">
							<div class="_dash_card">
								<div class="_dash_card_icon">
										<Icon type="ios-person-add" />
									<!-- <Icon type="md-checkmark-circle-outline" /> -->
								</div>
								<div class="_dash_card_details">
									<p class="_dash_card_amm">{{ total_member_request }}</p>
									<p class="_dash_card_title">Member Request </p>
								</div>
							</div>
						</div>
					</router-link>
				</div> 
				<div class="col-12 col-md-4 col-lg-3 _mar_b20">
					<div class="_box _b_radious3">
						<div class="_dash_card">
							<div class="_dash_card_icon">
								<Icon type="md-cash" />
							</div>
							<div class="_dash_card_details">
								<p class="_dash_card_amm">৳ {{ total_ammount }}</p>
								<p class="_dash_card_title">Total Amount</p>
							</div>
						</div>
					</div>
				</div>
				<div class="col-12 col-md-4 col-lg-3 _mar_b20">
					<div class="_box _b_radious3">
						<div class="_dash_card">
							<div class="_dash_card_icon">
								<Icon type="md-cash" />
							</div>
							<div class="_dash_card_details">
								<p class="_dash_card_amm">৳ {{ todays_profit }}</p>
								<p class="_dash_card_title">Todays Profit</p>
							</div>
						</div>
					</div>
				</div>
				<div class="col-12 col-md-4 col-lg-3 _mar_b20">
					<div class="_box _b_radious3">
						<div class="_dash_card"> 
							<div class="_dash_card_icon">
								<Icon type="md-cash" />
							</div>
							<div class="_dash_card_details">
								<p class="_dash_card_amm">৳ {{ todays_sale }}</p>
								<p class="_dash_card_title">Todays Sale</p>
							</div>
						</div>
					</div>
				</div>
				<div class="col-12 col-md-4 col-lg-3 _mar_b20">
					<div class="_box _b_radious3">
						<div class="_dash_card">
							<div class="_dash_card_icon">
								<Icon type="md-cash" />
							</div>
							<div class="_dash_card_details">
								<p class="_dash_card_amm">৳ {{ todays_expense }}</p>
								<p class="_dash_card_title">Todays Expense</p>
							</div>
						</div>
					</div>
				</div>
				<div class="col-12 col-md-4 col-lg-3 _mar_b20">
					<div class="_box _b_radious3">
						<div class="_dash_card"> 
							<div class="_dash_card_icon">
								<Icon type="md-cash" />
							</div>
							<div class="_dash_card_details">
								<p class="_dash_card_amm">৳ {{ todays_cashback }}</p>
								<p class="_dash_card_title">Todays Cashback</p>
							</div>
						</div>
					</div>
				</div>
				<!-- 
				<div class="col-12 col-md-12 col-lg-12 _mar_b20">
					<div class="_1card">
						<div class="_1card_body">
							<h2 class="_1card_title">Accout details</h2>

							<line-chart :chart-data="datacollection"></line-chart>
						</div>
					</div>
				</div> 
				-->
				<div class="col-12 col-md-12 col-lg-12" v-if="auth_user_type == 'Admin'">
					<div class="_box _b_radious3 _padd20">
						<div class="_1card_top _mar_b20">
							<div class="_1card_top_left">
								<p class="_1card_top_title">Franchise List</p>
							</div>
						</div>
						<div class="_table_responsive">
							<Table class="_table700" border :columns="franchiseTitle" :data="franchiseData">
								<template slot="owner" slot-scope="{ row }">
									<p v-if="row.user" >{{row.user.name}}</p>
								</template>
								<template slot="franchiseContact" slot-scope="{ row }">
									<p >{{row.countryCode}}{{row.phone}}</p>
								</template>
							</Table>
						</div>
					</div>
				</div>
				<!-- <div class="col-12 col-md-12 col-lg-12">
					<div class="_box _b_radious3 _padd20">
						<div class="_1card_top _mar_b20">
							<div class="_1card_top_left">
								<p class="_1card_top_title">Shop List</p>
							</div>
						</div>
						<div class="_table_responsive">
							<Table class="_table700" border :columns="shopTitle" :data="shopData">
								<template slot="shopName" slot-scope="{ row }">
									<p  > {{row.name}} </p>
								</template>
								<template slot="shopCategory" slot-scope="{ row }">
									<p v-if="row.category" >{{ row.category.name }} </p>
								</template>
								<template slot="shopFranchise" slot-scope="{ row }">
									<p v-if="row.franchise" >{{row.franchise.name}}</p>
								</template>
								<template slot="shopOwner" slot-scope="{ row }">
									<p v-if="row.user" >{{row.user.name}}</p>
								</template>
							</Table>
						</div>
					</div>
				</div> -->
				<div class="col-12 col-md-12 col-lg-12">
					<div class="_box _b_radious3 _padd20"> 
						<div class="_1card_top _mar_b20">
							<div class="_1card_top_left">
								<p class="_1card_top_title"> Recent Order</p>
							</div> 
						</div> 
						<div class="_table_responsive">
							<Table class="_table700" border :columns="orderTitle" :data="orderData">
								<template slot="productName" slot-scope="{ row }">
									<router-link :to='`/ordermodule/orderDetails/${row.id}`' >
										<div>
											<p v-if="row.items" ><span v-if="row.items.product"> {{ row.items.product.name }}</span></p>
										</div>
									</router-link>
								</template>
								<template slot="customerName" slot-scope="{ row }">
									<p v-if="row.users" >{{ row.users.name }} </p>
								</template>
								<template slot="coupon_amount" slot-scope="{ row }">
									<p v-if="row.coupon_amount" >${{ row.coupon_amount }} </p>
								</template>
								<template slot="discount_amount" slot-scope="{ row }">
									<p v-if="row.discount_amount" >${{ row.discount_amount }} </p>
								</template>
								<template slot="total" slot-scope="{ row }">
									<p v-if="row.total" >${{ row.total }} </p>
								</template>
								<template slot="delivery_time" slot-scope="{ row }">
									<p v-if="row.delivery_time" >{{ row.delivery_time }} </p>
								</template>
							</Table>
						</div>
					</div>
				</div>
				<!-- <div class="col-12 col-md-12 col-lg-12">
					<div class="_box _b_radious3 _padd20">
						<div class="_1card_top _mar_b20">
							<div class="_1card_top_left">
								<p class="_1card_top_title"> Driver List</p>
							</div>
						</div>
						<div class="_table_responsive">
							<Table class="_table700" border :columns="driverTitle" :data="driverData">
								<template slot="phone" slot-scope="{ row }">
									<p  >{{row.countryCode}}{{row.phone}}</p>
								</template>
							</Table>
						</div>
					</div>
				</div> -->
				<!-- <div class="col-12 col-md-12 col-lg-12">
					<div class="_box _b_radious3 _padd20">
						<div class="_1card_top _mar_b20">
							<div class="_1card_top_left">
								<p class="_1card_top_title"> Customer List </p>
							</div>
						</div>
						<div class="_table_responsive">
							<Table class="_table700" border :columns="customerTitle" :data="customerData">
								<template slot="customerPhone" slot-scope="{ row }">
									<p  >{{row.countryCode}}{{row.phone}}</p>
								</template>
							</Table>
						</div>
					</div>
				</div> -->
			</div>
		</div>
	</div>
</template>

<script>
// import LineChart from "../components/charJS_component.vue";
import { mapGetters } from 'vuex'

export default {
	data () {
		return {
			auth_user_type:'',
			datacollection: null,
 			franchiseTitle: [
				{
					title: 'Name',
					key: 'name'
				},
				{
					title: 'Owner Name',
					slot: 'owner'
				},
				{
					title: 'Country',
					key: 'country'
				},
				{
					title: 'Address',
					key: 'address'
				},
				{
					title: 'Phone',
					slot: 'franchiseContact'
				}
			],
			franchiseData: [
				{
					name: 'Jon Snow',
					age: 26,
					address: 'Ottawa No. 2 Lake Park',
					date: '2016-10-04'
				},
				{
					name: 'Jim Green',
					age: 24,
					address: 'London No. 1 Lake Park',
					date: '2016-10-01'
				}
			],
			shopTitle: [
				{
					title: 'Shop Name',
					slot: 'shopName'
				},
				{
					title: 'Category',
					slot: 'shopCategory'
				},
				{
					title: 'Franchise',
					slot: 'shopFranchise'
				},
				{
					title: 'Shop Owner',
					slot: 'shopOwner'
				},
				{
					title: 'Address',
					key: 'address'
				}
			],
			shopData: [
				{
					name: 'Jon Snow',
					age: 26,
					address: 'Ottawa No. 2 Lake Park',
					date: '2016-10-04'
				},
				{
					name: 'Jim Green',
					age: 24,
					address: 'London No. 1 Lake Park',
					date: '2016-10-01'
				}
			],
			orderTitle: [
				{
					title: 'Product Name',
					slot: 'productName',
					width:'250px',
				},
				{
					title: 'Status',
					key: 'status',
					width:'130px',
				},
				{
					title: 'Customer Name',
					slot: 'customerName',
					width:'250px',
				},
				{
					title: 'Cupon Amount',
					slot: 'coupon_amount',
					width:'200px',
				},
				{
					title: 'Discount Amount',
					slot: 'discount_amount',
					width:'180px',
				},
				{
					title: 'Total',
					slot: 'total',
					width:'100px',
				},
				{
					title: 'Delivery Time',
					slot: 'delivery_time',
					width:'220px',
				}
			],
			orderData: [],
			driverTitle: [
				{
					title: 'Name',
					key: 'name'
				},
				{
					title: 'Contact Number',
					slot: 'phone'
				},
				{
					title: 'Country',
					key: 'country'
				},
				{
					title: 'Address',
					key: 'address'
				}
			],
			driverData: [
				{
					name: 'Jon Snow',
					age: 26,
					address: 'Ottawa No. 2 Lake Park',
					date: '2016-10-04'
				},
				{
					name: 'Jim Green',
					age: 24,
					address: 'London No. 1 Lake Park',
					date: '2016-10-01'
				}
			],
			customerTitle: [
				{
					title: 'Name',
					key: 'name'
				},
				{
					title: 'Contact Number',
					slot: 'customerPhone'
				},
				{
					title: 'Country',
					key: 'country'
				},
				{
					title: 'Address',
					key: 'address'
				}
			],
			customerData: [
				{
					name: 'Jon Snow',
					age: 26,
					address: 'Ottawa No. 2 Lake Park',
					date: '2016-10-04'
				},
				{
					name: 'Jim Green',
					age: 24,
					address: 'London No. 1 Lake Park',
					date: '2016-10-01'
				}
			],
			total_franchise:0,
			total_product:0,
			total_order:0,
			total_ammount:0,
			todays_sale:0,
			todays_profit:0,
			todays_expense:0,
			todays_cashback:0,
			total_delivered_order:0,
			total_member_request:0,
			total_shop:0,
			total_customer:0,
			total_driver:0,
		}
	},
	computed: {
        ...mapGetters([
        'getNewOrder', 
        ])
    },
	methods: {
		fillData () {
			this.datacollection = {
			// labels: [this.getRandomInt(), this.getRandomInt()],
			// datasets: [
			// 	{
			// 	label: 'Data One',
			// 	backgroundColor: '#f87979',
			// 	data: [this.getRandomInt(), this.getRandomInt()]
			// 	}, {
			// 	label: 'Data One',
			// 	backgroundColor: '#f87979',
			// 	data: [this.getRandomInt(), this.getRandomInt()]
			// 	}
			// ]
			}
		},
		// getRandomInt () {
		// 	return Math.floor(Math.random() * (50 - 5 + 1)) + 5
		// },
		async getOrderCount(){
            const res = await this.callApi('get', `/usermodule/getOrderCount`)
            if(res.status==200){
                this.total_order = res.data
            } 
        },
	 
		async getProductCount(){
            const res = await this.callApi('get', `/usermodule/getProductCount`)
            if(res.status==200){
                this.total_product = res.data
            } 
        },
		async getFranchiseCount(){
            const res = await this.callApi('get', `/usermodule/getFranchiseCount`)
            if(res.status==200){
                this.total_franchise = res.data
            } 
        },
		async getCustomerCount(){
            const res = await this.callApi('get', `/usermodule/getCustomerCount`)
            if(res.status==200){
                this.total_customer = res.data
            } 
        },
		async getShopCount(){
            const res = await this.callApi('get', `/usermodule/getShopCount`)
            if(res.status==200){
                this.total_shop = res.data
            } 
        },
		async getdeliveredOrderCount(){
            const res = await this.callApi('get', `/usermodule/getdeliveredOrderCount`)
            if(res.status==200){
                this.total_delivered_order = res.data
            } 
        },
		async getMemberRequestCount(){
            const res = await this.callApi('get', `/usermodule/getMemberRequestCount`)
            if(res.status==200){
                this.total_member_request = res.data
            } 
        },
		async getdriverCount(){
            const res = await this.callApi('get', `/usermodule/getdriverCount`)
            if(res.status==200){
                this.total_driver = res.data
            } 
        },
		async getTotalAmmount(){
            const res = await this.callApi('get', `/usermodule/getTotalAmmount`)
            if(res.status==200){
                this.total_ammount = res.data.total_ammount
            } 
        },
		// async getTodaysSale(){
        //     const res = await this.callApi('get', `/usermodule/getTodaysSale`)
        //     if(res.status==200){
        //         this.todays_sale = res.data.todays_sale
        //     } 
        // },
		// async getTodaysExpense(){
        //     const res = await this.callApi('get', `/usermodule/getTodaysExpense`)
        //     if(res.status==200){
        //         this.todays_expense = res.data.todays_expense
        //     } 
        // },
		// async getTodaysCashback(){
        //     const res = await this.callApi('get', `/usermodule/getTodaysCashback`)
        //     if(res.status==200){
        //         this.todays_cashback = res.data.todays_cashback
        //     } 
        // },
		async getTodaysProfit(){
            const res = await this.callApi('get', `/usermodule/getTodaysProfit`)
            if(res.status==200){
                this.todays_profit = res.data.netProfit
                this.todays_sale = res.data.sale
                this.todays_expense = res.data.expense
                this.todays_cashback = res.data.totalCashback
            } 
        },
		async getFranchiseTable(){
            const res = await this.callApi('get', `/usermodule/getFranchiseTable`)
            if(res.status==200){
                this.franchiseData = res.data
            } 
        },
		async getShopTable(){
            const res = await this.callApi('get', `/usermodule/getShopTable`)
            if(res.status==200){
                this.shopData = res.data
            } 
        },
		async getCustomerTable(){
            const res = await this.callApi('get', `/usermodule/getCustomerTable`)
            if(res.status==200){
                this.customerData = res.data
            } 
        },
		async getDriverTable(){
            const res = await this.callApi('get', `/usermodule/getDriverTable`)
            if(res.status==200){
                this.driverData = res.data
            } 
        },
		async getRecentOrderTable(){
            const res = await this.callApi('get', `/usermodule/getRecentOrderTable`)
            if(res.status==200){
                this.orderData = res.data
            } 
        },
	},
	created(){
		this.auth_user_type = authUser.userType 
 		
		this.getShopTable()
		this.getCustomerTable()
		this.getDriverTable()
		this.getRecentOrderTable()
		this.getFranchiseCount()
		this.getProductCount()
		this.getOrderCount() 
 		this.getTotalAmmount()
		this.getdriverCount()
		this.getdeliveredOrderCount()
		this.getMemberRequestCount()
		this.getShopCount()
		this.getCustomerCount()
		this.getFranchiseTable()
		// this.getTodaysSale()
		// this.getTodaysExpense()
		// this.getTodaysCashback()
		this.getTodaysProfit()
 	},
	mounted () {
		this.fillData()
	}
}
</script>