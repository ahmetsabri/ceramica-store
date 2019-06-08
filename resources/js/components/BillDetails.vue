<template>
  <v-content>

    <v-data-table
      v-if="!!details"
      :headers="headers"
      :items="details"
      hide-actions
      class="elevation-1">
      <template v-slot:items="props">
        <td class="text-xs-center">
          <h4>
            {{props.item.stock.mark_name}}
          </h4>
        </td>
        <td class="text-xs-center">
          <h4>
            {{props.item.stock.filter_id}}
          </h4>
        </td>
        <td class="text-xs-center">
          <h4>
            {{props.item.stock.dimension}}
          </h4>
        </td>
        <td class="text-xs-center">
          <h4>
            <bdi>
              {{props.item.quantity}}
ك
            </bdi>
          </h4>
        </td>
        <td class="text-xs-center">
          <h4>
          <bdi>
            {{props.item.quantity * props.item.stock.size * props.item.stock.price }}
جنيه
          </bdi>
          </h4>
        </td>
        <td class="text-xs-center">
          <h4>
            <bdi>
              {{props.item.stock.price}}
جنيه
            </bdi>
          </h4>
        </td>
        <td class="text-xs-center">
          <h4>
            <bdi>
              {{props.item.stock.size}}
م
            </bdi>
          </h4>
        </td>
        <td class="text-xs-center">
          <v-btn @click="editDetails(props.item.id,props.item.stock.id)" color="primary" small round>تعديل</v-btn>
          <v-btn @click="deleteDetails(props.item.id,props.item.stock.id)" color="error" small round>حذف</v-btn>
        </td>
      </template>

    </v-data-table>
    <v-dialog
      v-if="!!details"
      v-model="dialog"
      max-width="500px"
      transition="dialog-transition">
        <v-card>
          <v-card-title class="indigo white--text" direction="rtl">
            <h2 class="text-xs-right">            تعديل
</h2>
          </v-card-title>
            <div class="ma-4">
            <v-text-field
            outline
            v-model="quantity"
            label="الكمية"
            ></v-text-field>
          </div>
            <div class="text-xs-center">
                <v-btn color="primary" @click="applyEditDetail">تم</v-btn>
          </div>
        </v-card>
    </v-dialog>
  </v-content>
</template>

<script>

import axios from 'axios';

export default {

  data(){
    return {
      dialog:false,
      details:null,
      quantity:'',
      selectedDetails:null,
      headers:[
          {text:'الماركة',align:'center',value:'id'},
          {text:'الفرزة',align:'center',value:'id'},
          {text:'المقاس',align:'center',value:'id'},
          {text:'الكمية',align:'center',value:'id'},
          {text:'الاجمالي',align:'center',value:'id'},
          {text:'السعر للمتر',align:'center',value:'id'},
          {text:'السعة المترية',align:'center',value:'id'},
          {text:'اجراء',align:'center',value:'id'},
      ],
    };
  },
  computed:{
    id(){
      return this.$route.params.id;
    }
  },

  created(){
      this.loadDetails();
  },

  methods:{

    editDetails(id){

      let index = this.details.findIndex((val)=>{
        return val.id == id;
      });
      this.selectedDetails = this.details[index];
      this.quantity = this.selectedDetails.quantity;
      this.dialog = true;
    },
    deleteDetails(id){
      alert(id);
    },
    applyEditDetail(){
      axios.post('/api/bill/edit-details',{
        quantity:this.quantity,
        detail_id:this.selectedDetails.id,
        bill_id:this.id,
      })
      .then((response)=>{
        console.log(response.data);
      })
      .catch((errors)=>{
        alert('error in details edit');
        console.log(errors);
        console.log(errors.response);
      })
    },
    loadDetails(){
        axios.post('/api/bill/show-details',{
          bill_id:this.$route.params.id,
        })
        .then((response)=>{
          this.details = response.data.data
        })
        .catch((errors)=>{
          console.log(errors);
        })
    },
    deleteDetails(detailId,stockId){
        axios.post('/api/bill/delete-details',{
          id:detailId,
          stock_id:stockId,
          bill_id:this.id,

        })
        .then((response)=>{
          console.log(response.data);
        })
        .catch((errors)=>{
          alert('errror in delete')
          console.log(errors);
          console.log(errors.response);
        })
    }
  }

}
</script>

<style lang="css" scoped>
</style>
