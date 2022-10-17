  <template>
    <div class="_main_content">
		<div class="row">
            <div class="col-12 col-md-12 col-lg-12">
				<div class="_box _b_radious3 _padd20">
                    <div class="_1card_top _mar_b20">
							<div class="_1card_top_left">
								<p class="_1card_top_title">Frenchise Daily Schedule</p>
								<div class="_1card_top_search">
									<Input @on-change="serchResetlt" search  v-model="str" enter-button="Search" placeholder="Enter something..." />
								</div>
							</div>
							<div class="_1card_top_right">
								<ul class="_1card_top_right_list">
									<li><Button type="primary" @click="openCreateModal">Add</Button></li>
								</ul>
							</div>
						</div>
						<div class="_table_responsive">
							<Table class="" border :columns="columns" :data="alldata.data">
								<template slot="name" slot-scope="{ row }" >
									<p v-if="row.franchise">{{row.franchise.name}}</p>
								</template>
								<!-- <template slot="transaction" slot-scope="{ row }" >
									<Button type="primary" @click="$router.push('/usermodule/transaction/'+row.id)">Transaction</Button>
								</template> -->
								<template slot="action" slot-scope="{ row, index }">
									<Button type="primary" @click="openEditModal(row,index)">Edit</Button>
									<Button type="error" @click="deleteSchedule(row,index)">Delete</Button>
								</template>
							</Table>
						</div>
                        <div class="_pagination _padd_t20" >
							<Page :total="alldata.total" show-sizer :page-size-opts="pageOption"  @on-page-size-change="getSizeChange"  @on-change="paginateDataInfo"/>
						</div>
                </div>
            </div>
        </div>
		<Modal title="Create Franchise Daily Schedule" width="800" v-model="createModal" :mask-closable="false" >
            <div class="row">
                <div class="col-12 col-md-6 col-lg-6 _mar_b20" v-if="isAdmin">
                    <p class="_label _mar_b15">Select Franchise</p>
					<Select v-model="form.franchise_id" filterable :disabled="!isAdmin" >
                          <Option v-for="(item, index) in franchiseList" :value="item.id" :key="index" >{{item.name}}</Option>
                    </Select>
                </div> 
				<div class="col-12 col-md-6 col-lg-6 _mar_b20" >
                    <p class="_label _mar_b15">Start Time</p>
					<!-- <DatePicker type="date" v-model="form.startTime" @on-change="formatDate" placeholder="Select date" ></DatePicker> -->
					<TimePicker type="time" format="hh:mm A"  v-model="form.startTime" placeholder="Select start time"></TimePicker>

                </div>   
				<div class="col-12 col-md-6 col-lg-6 _mar_b20" >
                    <p class="_label _mar_b15">End Time</p>
					<TimePicker type="time" format="hh:mm A" v-model="form.endTime" placeholder="Select end time"></TimePicker>
                </div>  
				<div class="col-12 col-md-6 col-lg-6 _mar_b20" >
                    <p class="_label _mar_b15">Max Order</p>
                    <InputNumber style="width:100%"  placeholder="0" :min="0" 
                    v-model="form.max_order" >
                    </InputNumber>
                </div>  
            </div>
            <div slot="footer">
                <Button   @click="createModal=false" >Cancel</Button>
                <Button type="primary"  @click="createSchedule" :loading="isLoading" :disabled="isLoading">{{isLoading? 'Please wait' : 'Create'}}</Button>
            </div>
        </Modal>
		<Modal title="Edit Franchise Daily Schedule" width="800" v-model="updateModal" :mask-closable="false" >
            <div class="row">
                <div class="col-12 col-md-6 col-lg-6 _mar_b20" v-if="isAdmin">
                    <p class="_label _mar_b15">Select Franchise</p>
					<Select v-model="form.franchise_id" filterable  >
                          <Option v-for="(item, index) in franchiseList" :value="item.id" :key="index" >{{item.name}}</Option>
                    </Select>
                </div> 
				<div class="col-12 col-md-6 col-lg-6 _mar_b20" >
                    <p class="_label _mar_b15">Start Time</p>
					<!-- <DatePicker type="date" v-model="form.startTime" @on-change="formatDate" placeholder="Select date" ></DatePicker> -->
					<TimePicker type="time" format="hh:mm A" v-model="form.startTime" placeholder="Select start time"></TimePicker>

                </div>   
				<div class="col-12 col-md-6 col-lg-6 _mar_b20" >
                    <p class="_label _mar_b15">End Time</p>
					<TimePicker type="time" format="hh:mm A" v-model="form.endTime" placeholder="Select end time"></TimePicker>
                </div>  
				<div class="col-12 col-md-6 col-lg-6 _mar_b20" >
                    <p class="_label _mar_b15">Max Order</p>
                    <InputNumber style="width:100%"  placeholder="0" :min="0" 
                    v-model="form.max_order" >
                    </InputNumber>
                </div>  
            </div>
            <div slot="footer">
                <Button   @click="updateModal=false" >Cancel</Button>
                <Button type="primary"  @click="updateSchedule" :loading="isLoading" :disabled="isLoading">{{isLoading? 'Please wait' : 'Update'}}</Button>
            </div>
        </Modal>
    </div>
</template>


<script>
export default {
    data(){
        return{
			form:{
				franchise_id:-1,
        		startTime: "",
        		endTime: "",
        		max_order: 0 
			},
			
			modalOn:false,
			isLoading:false,
			createModal:false,
			updateModal:false,
			editIndex:-1,
			_rowKey:-1,
            pageOption:[5,10,15,20],
            columns: [
				 {
                    title: 'User ID',
                    sortable: true,
					key: 'id',
                },
				{
					title: 'Frenchise Name',
					slot: 'name',
 				},
				{
					title: 'Start Time',
					key: 'startTime',
 				},
				{
					title: 'End Time',
					key: 'endTime',
 				},
				{
					title: 'Max Time',
					key: 'max_order',
 				},
				{    
					title: 'Action',
                    slot: 'action',
                   	width: 200

 				}
			],
            alldata:{
                total:0,
                data:[]
			},
			all_franchise:[],
			str:'',
			page:1,
			pageSize:10
        }
	},
	methods:{
		formatDate(e){

		},
		async createSchedule(){
			// if(!this.form.franchise_id || this.form.franchise_id <= 0) return this.e("Please select a franchise.") 
			if(!this.form.startTime || this.form.startTime=='' || this.form.startTime==null) return this.e("Start time is required.") 
			if(!this.form.endTime || this.form.endTime=='' || this.form.endTime==null) return this.e("End time is required.") 
			if(!this.form.max_order || this.form.max_order=='' || this.form.max_order==null) return this.e("Max order is required.")
			this.isLoading = true
			const res = await this.callApi('post', '/franchisemodule/storeSchedule', this.form)
			if(res.status == 200 || res.status ==201){
				this.alldata.data.unshift(res.data)
                this.s("New schedule has been created.")
                this.createModal = false
			}
			else if(res.status==422){
				this.e(res.data.message)
			}
			else {
				this.swr()
			}
			this.createModal = false;
			this.isLoading = false
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
			this.getAlldailySchedule()
		},
		openCreateModal(){
			this.form = {
				franchise_id:this.franchise_id,
        		startTime: "",
        		endTime: "",
        		max_order: 0 
			}
 			this.createModal = true
		},
		clearData(){
			this.form={}
        },
        async getAlldailySchedule(){
			const res = await this.callApi('get', `/franchisemodule/getAlldailySchedule?page=${this.page}&pageSize=${this.pageSize}&str=${this.str}`)
			if(res.status==200){
				this.alldata = res.data
				this.alldata.total = res.total
			} 
		},
		openEditModal(item, index){
			this.form.id = item.id
			this.form.franchise_id = item.franchise_id
			this.form.startTime = item.startTime
			this.form.endTime = item.endTime
			this.form.max_order = item.max_order
			this.updateModal =  true
			this.editIndex = index
		},
		async deleteSchedule(item, index){
			if (!confirm("Are you sure to delete?")) {
                    return;
                }
			const res = await this.callApi('post', '/franchisemodule/deleteSchedule', {id:item.id})
            if(res.status == 200){
                this.alldata.data.splice(index, 1)
                this.s("Schedule has been deleted.")
            }
            else if(res.status==422){
				this.e(res.data.message)
			}
			else {
				this.swr()
			}
		},
		async updateSchedule(){
			if(!this.form.franchise_id || this.form.franchise_id <= 0) return this.e("Please select a franchise.") 
			if(!this.form.startTime || this.form.startTime=='' || this.form.startTime==null) return this.e("Start time is required.") 
			if(!this.form.endTime || this.form.endTime=='' || this.form.endTime==null) return this.e("End time is required.") 
			if(!this.form.max_order || this.form.max_order=='' || this.form.max_order==null) return this.e("Max order is required.")
			this.isLoading = true
			const res = await this.callApi('post', '/franchisemodule/updateSchedule', this.form)
			if(res.status == 200 || res.status ==201){
				this.alldata.data.splice(this.editIndex, 1)
				this.alldata.data[this.editIndex] = res.data
                this.s("Schedule has been update.")
                this.updateModal = false
			}
			else if(res.status==422){
				this.e(res.data.message)
			}
			else {
				this.swr()
			}
			this.updateModal = false;
			this.isLoading = false
		},
        async allfranchises(){
			const res = await this.callApi('get', `/franchisemodule/allfranchises?page=${this.page}&pageSize=${this.pageSize}`)
			if(res.status==200){
				this.all_franchise = res.data.data
 			} 
        },
		 
	},
    async created(){
		this.getAlldailySchedule()
		
    },
}
</script>
