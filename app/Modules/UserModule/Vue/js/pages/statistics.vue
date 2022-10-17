<template>
	<div>
		<div class="_main_content"> 
			<div class="row">
				<div class="col-12 col-md-12 col-lg-12 _mar_b10">
                    <div class="_box _b_radious3 _padd20">
                        <div class="_1card_top _mar_b20">
                            <div class="_1card_top_left" style="margin: 0 auto;">
                                <p class="_1card_top_title">Select Date</p>
                                <div class="_1card_top_search">
                                    <DatePicker type="daterange" :options="options2" placement="bottom-end" placeholder="Select date" @on-change="getDate" style="width: 200px"></DatePicker>
                                </div>
                            </div>
                        </div>
                    </div>
				</div>  
				<div class="col-12 col-md-6 col-lg-6 _mar_b20">
					<div class="_box _b_radious3">
						<div class="_dash_card">
							<div class="_dash_card_icon">
								<Icon type="md-cash" />
							</div>
							<div class="_dash_card_details">
								<p class="_dash_card_amm">৳ {{totaData.sale}}</p>
								<p class="_dash_card_title">Total Sale Amount</p>
							</div>
						</div>
					</div>
				</div>
				<div class="col-12 col-md-6 col-lg-6 _mar_b20">
					<div class="_box _b_radious3">
						<div class="_dash_card">
							<div class="_dash_card_icon">
								<Icon type="md-cash" />
							</div>
							<div class="_dash_card_details">
								<p class="_dash_card_amm">৳ {{totaData.expense}}</p>
								<p class="_dash_card_title">Total Expense Amount</p>
							</div>
						</div>
					</div>
				</div>
				<div class="col-12 col-md-6 col-lg-6 _mar_b20">
					<div class="_box _b_radious3">
						<div class="_dash_card">
							<div class="_dash_card_icon">
								<Icon type="md-cash" />
							</div>
							<div class="_dash_card_details">
								<p class="_dash_card_amm">৳ {{totaData.grossProfit}}</p>
								<p class="_dash_card_title">Total Gross Profit</p>
							</div>
						</div>
					</div>
				</div>
				<div class="col-12 col-md-6 col-lg-6 _mar_b20">
					<div class="_box _b_radious3">
						<div class="_dash_card">
							<div class="_dash_card_icon">
								<Icon type="md-cash" />
							</div>
							<div class="_dash_card_details">
								<p class="_dash_card_amm">৳ {{totaData.netProfit}}</p>
								<p class="_dash_card_title">Total Net Profit</p>
							</div>
						</div>
					</div>
				</div>
				<div class="col-12 col-md-6 col-lg-6 _mar_b20">
					<div class="_box _b_radious3">
						<div class="_dash_card">
							<div class="_dash_card_icon">
								<Icon type="md-cash" />
							</div>
							<div class="_dash_card_details">
								<p class="_dash_card_amm">৳ {{totaData.totalCashback}}</p>
								<p class="_dash_card_title">Total Cashback</p>
							</div>
						</div>
					</div>
				</div>
		
			
            </div>
		</div>
	</div>
</template>

<script>
// import LineChart from "../components/charJS_component.vue";

export default {
	data () {
		return {
			totaData:{
                sale:0,
                expense:0,
                profit:0,
            },
             options2: {
                shortcuts: [{
                        text: '1 week',
                        value() {

                            const end = new Date(); 
                            const start = new Date();
                            start.setTime(start.getTime() - 3600 * 1000 * 24 * 7);
                            return [start, end];
                        }
                    },
                    {
                        text: '1 month',
                        value() {
                            const end = new Date();
                            const start = new Date();
                            start.setTime(start.getTime() - 3600 * 1000 * 24 * 30);
                            return [start, end];
                        }
                    },
                    {
                        text: '3 months',
                        value() {
                            const end = new Date();
                            const start = new Date();
                            start.setTime(start.getTime() - 3600 * 1000 * 24 * 90);
                            return [start, end];
                        }
                    }
                ]
            },
            date_start:'',
			date_end:'',
		}
	},
	methods: {
        getDate(date){
			console.log(date)
            if(date){
                this.date_start = date[0]
                this.date_end = date[1]
            } 
            this.getShopTable(); 
        },
		async getShopTable(){
            const res = await this.callApi('get', `/usermodule/getStatistics?franschise_id=${this.franschise_id}&date_start=${this.date_start}&date_end=${this.date_end}`)
            if(res.status==200){
                this.totaData = res.data
            } 
        },
	},
	created(){
		 
		this.getShopTable()
	},
}
</script>