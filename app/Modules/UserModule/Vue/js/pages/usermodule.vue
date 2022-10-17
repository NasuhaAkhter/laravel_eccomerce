<template>
    <div class="_main_content">
		<div class="row">
            <div class="col-12 col-md-12 col-lg-12">
				<div class="_box _b_radious3 _padd20">
                    <div class="_1card_top _mar_b20">
							<div class="_1card_top_left">
								<p class="_1card_top_title">Age table</p>

								<div class="_1card_top_search">
									<Input search enter-button="Search" placeholder="Enter something..." />
								</div>
							</div>

							<div class="_1card_top_right">
								<ul class="_1card_top_right_list">
									<li><Button>Default</Button>
    								<li><Button type="dashed">Dashed</Button></li>
									<li><Button type="primary">Primary</Button></li>
								</ul>
							</div>
						</div>
						<div class="_table_responsive">
							<Table class="_table700" border :columns="columns" :data="alldata.data"></Table>
						</div>

                        <div class="_pagination _padd_t20" v-if="alldata && alldata.total!=null">
							<Page :total="alldata.total" show-sizer :page-size-opts="pageOption" />
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
            pageOption:[5,10,15,20],
            columns: [
				{
					title: 'Name',
					key: 'name'
				},
				{
					title: 'Age',
					key: 'age'
				},
				{
					title: 'Address',
					key: 'address'
				}
            ],
            alldata:{
                total:0,
                data:[]
            }
        }
    },
    created(){
    },
    
}
</script>
