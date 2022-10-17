
<template>
    <div class="_main_content">
		<div class="row">
            <div class="col-12 col-md-12 col-lg-12">
				<div class="_box _b_radious3 _padd20">
                    <div class="_1card_top _mar_b20">
                        <div class="_1card_top_left">
                            <p class="_1card_top_title">Return Refund Policy</p>
                        </div> 
                        <div class="_1card_top_right" v-if="!QuilOn">
                            <ul class="_1card_top_right_list">
                                <li >
                                    <Button type="primary"  @click="QuilOn = true" > Edit </Button>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div  class="row" >
                       <div  v-if="QuilOn" class="col-12 col-md-12 col-lg-12 _mar_b20 _quel_editor">
                            <p class="_label _mar_b15">Content </p>
                                <quill-editor ref="myQuillEditor" 
                                        v-model="form.description"
                                      style="width: 100%; "
                                 @change="onEditorChange($event)"
                                    ></quill-editor>
                        </div>
                        <div v-else class="col-12 col-md-12 col-lg-12 _mar_b20">
                            <p v-html="form.description">{{ form.description }}</p>
                        </div>
                    </div>
                    <div class="_1card_top _mar_b20"  v-if="QuilOn">
                        <div class="_1card_top_left">
                         </div> 
                        <div class="_1card_top_right">
                            <ul class="_1card_top_right_list">
                                <li >
                                    <Button type="primary"  @click="updateData"  :loading="isLoading" :disabled="isLoading">{{isLoading? 'Please wait' : 'Update'}}</Button>
                                </li>
                            </ul>
                        </div>
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
            QuilOn:false,
            isLoading:false,
            form:{
                description:' ',
            },
            alldata:{}
        }
    },
    async  created(){
		this.getAllrules()
    },
    methods:{
      onEditorChange({ quill, html, text }) {
          this.form.description = html;
        },   
       async getAllrules(){
			const res = await this.callApi('get', `/usermodule/getAllrules`)
			if(res.status==200){
                this.alldata = res.data
                this.form.description = this.alldata.return_refund
			}
        },  
        async updateData(){
            if(this.form.description == '' || this.form.description.trim() == ''){
				return this.i('Please Write a Title!');
            }
            this.isLoading=true
            this.alldata.return_refund = this.form.description
            const res = await this.callApi('post','/usermodule/updateRules',this.alldata)
			if(res.status == 200){
                this.s("Return Refund Policy has been updated successfully");
                this.QuilOn = false

  			}
			else this.swr();
			this.isLoading=false
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
