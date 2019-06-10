<template>
  <v-content>
    <v-data-table
      v-if="!!details"
      :headers="headers"
      :items="details"
      hide-actions
      class="hidden-print-only">
      <template v-slot:items="props">
        <td class="text-xs-center">
          <h4>
            {{props.item.stock.mark.name}}
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

      <v-btn class="hidden-print-only"  @click="addItem = !addItem"  round color="success">اضافة عنصر<v-icon>add</v-icon></v-btn>
    <v-dialog
      class=""
      v-if="!!details"
      v-model="dialog"
      max-width="500px"
      transition="dialog-transition">
        <v-card>
          <v-card-title class="indigo white--text" direction="rtl">
            <h2 class="text-xs-right">
                 تعديل
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

    <template  v-for="item in stock" dir="rtl">
        <div  v-show="addItem"  class="">

        <h1>{{item.mark.name}}</h1>
                  <v-flex xs12>
                    <v-divider class="ma-3"></v-divider>
                        <v-checkbox color="indigo" v-model="selectedItems" label="John" :value="item.id">
                          <template v-slot:label>
                            <bdi class="black--text">
                                فرزة {{item.filter_id}} - {{item.color}} - مقاس {{item.dimension}}
                            </bdi>
                          </template>
                        </v-checkbox>
      <v-text-field
        :hint="`سعر المتر  : ${item.price} جنيه - السعة: ${item.size} متر - الكمية المتاحة ${item.quantity} كرتونة`"
       :rules="nameRules"
        width="120"
        v-if="selectedItems.indexOf(item.id) > -1"
        v-model="selectedItemsModel[selectedItems.indexOf(item.id)]"
        @keyup="calculateTotal(item.id,item.price , item.size , selectedItemsModel[selectedItems.indexOf(item.id)],item.quantity)"
        label="الكمية بالكرتونة"
        solo-inverted>

        </v-text-field>
                          <h3
                          class="error--text"
                          v-show="selectedItemsModel[selectedItems.indexOf(item.id)] > item.quantity" >
                              الكمية اكبر من الكمية الموجودة في المخزن
                          </h3>

                          <h5 v-if="selectedItems.indexOf(item.id) > -1">

                            <bdi>
                              السعر :
                              <span class="green--text">
                                  {{isNaN(selectedItemsModel[selectedItems.indexOf(item.id)] * item.size * item.price) ? 0 : selectedItemsModel[selectedItems.indexOf(item.id)] * item.size * item.price}}
                              </span>
                          </bdi>

                        </h5>
                        </v-flex>
                <v-divider></v-divider>
              </div>
            </div>

            </template>
            <v-btn class="hidden-print-only" color="primary" :disabled="bigQuantity" v-if="!!addItem" @click="addTodetails">اضافة</v-btn>
            <v-snackbar
              class=""
              v-model="done"
              bottom
              right>
            <b>
                 تم بنجاح
               </b>
              <v-btn class="hidden-print-only green--text" flat dark @click.native="done = false">حسناً</v-btn>
            </v-snackbar>
            <v-container grid-list-xs class="hidden-screen-only">

            <v-layout row wrap>
              <v-flex xs6>
                <div class="text-xs-left">
                    <h4 >
                      <bdi>
                        0123456789
                      </bdi>
                    </h4>
                </div>
              </v-flex>
              <v-flex xs6>
                <div class="text-xs-right">
                    <h4 >
                      <bdi>
                        معرض الرحمة للسيراميك - فرع دنشواي
                      </bdi>
                    </h4>
                </div>
              </v-flex>

        <v-flex xs12>
            <div class="text-xs-center" v-if="!!customer">
              <h1>بيانات العميل</h1>
              <h3>
                <bdi>
                    الاسم : {{customer.name}}
                </bdi>
              </h3>
              <h3>
                <bdi>
                  العنوان : {{customer.address}}
                </bdi>
              </h3>
              <h3>
                <bdi>
                  الهاتف : {{customer.phone}}
                </bdi>
              </h3>
            </div>
            <v-divider class="black black--text mt-3"></v-divider>
          </v-flex>
            <v-flex xs12>
              <h1 class="text-xs-center">ملخص الفاتورة</h1>
            </v-flex>
          <v-flex xs6>
              <h2 class="text-xs-left">
                <bdi>
                  تاريخ اخر عملية دفع :   {{bill.created_at}}
                </bdi>
              </h2>
          </v-flex>
      <v-flex xs6>
          <h2 class="text-xs-right">
            <bdi>
              تاريخ الشراء :   {{bill.created_at}}
            </bdi>
          </h2>
      </v-flex>

      <v-flex xs4>
        <div class="text-xs-left mt-4">
            <h2>
              <bdi>
                الباقي : {{bill.remain}}
              </bdi>
            </h2>
        </div>
      </v-flex>
      <v-flex xs4>
        <div class="text-xs-center mt-4">
            <h2>
              <bdi>
                المدفوع : {{bill.paid}}
              </bdi>
            </h2>
        </div>
      </v-flex>
      <v-flex xs4>
        <div class="text-xs-right mt-4">
            <h2>
              <bdi>
                الاجمالي : {{bill.total}}
              </bdi>
            </h2>
        </div>
      </v-flex>
      <v-flex xs12>
        <v-divider class="black black--text mt-3"></v-divider>

      </v-flex>
      <v-flex xs12>
          <h1 class="text-xs-center">تفاصيل الفاتورة</h1>
      </v-flex>

        <v-flex xs12>
          <v-data-table
            v-if="!!details"
            :headers="headersPrint"
            :items="details"
            hide-actions
            class="elevation-1">
            <template v-slot:items="props">
              <td class="text-xs-center">
                <h4>
                  {{props.item.stock.mark.name}}
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
        كرتونة
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
        متر
                  </bdi>
                </h4>
              </td>
            </template>
          </v-data-table>

        </v-flex>
        </v-layout>
      </v-container>
  </v-content>
</template>

<script>

import axios from 'axios';

export default {

  data(){
    return {
      customer:null,
      bill:null,
      done:false,
      bigQuantity:false,
      addItem:false,
      stock:null,
      dialog:false,
      details:null,
      quantity:'',
      selectedDetails:null,
      selectedItems:[],
      selectedItemsModel:[],
      selectedMarks:[],
      searchResults:[],
      allMarks:null,
      sumTotal:0,
      total:0,
      paid:0,
      total:0,
      remain:0,
      totalSum:[],
      selectedBill:null,
      addPaid:0,
      nameRules: [
            v => !!v || 'يجب ملء هذه الخانة',
          ],
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
      headersPrint:[
          {text:'الماركة',align:'center',value:'id'},
          {text:'الفرزة',align:'center',value:'id'},
          {text:'المقاس',align:'center',value:'id'},
          {text:'الكمية',align:'center',value:'id'},
          {text:'الاجمالي',align:'center',value:'id'},
          {text:'السعر للمتر',align:'center',value:'id'},
          {text:'السعة المترية',align:'center',value:'id'},      ],
    };
  },
  computed:{
    id(){
      return this.$route.params.id;
    },
  },

  created(){
      this.loadDetails();
      this.loadStock();

  },
  watch:{
    addItem(){
      this.loadStock();
    },
    selectedItems(n,o){
      console.log(this.selectedItemsModel);
        if (n.length < o.length) {
            console.log('old',o);
            console.log('new',n);
          for (let i = 0; i < o.length; i++) {
              if (n.indexOf(o[i])==-1) {
                    this.selectedItemsModel.splice(i,1);
                    this.totalSum.splice(i,1)
              }
          }
          if (this.totalSum.length > 0) {

            let sum =  this.totalSum.reduce((a,b)=>{
                      return a + b;
                  });
                  this.total = sum;

          }
          else{
            this.total = 0;
            this.paid = 0;
          }
}
}
  },
  methods:{
    calculateTotal(id,price,size,quantity,allQuantity){

        let index = this.selectedItems.indexOf(id);

        this.totalSum[index] = price * size * quantity;

        let sum =  this.totalSum.reduce((a,b)=>{
                  return a + b;
              });


        console.log(this.selectedItems);
        console.log(this.totalSum);
        console.log(sum);

        this.total = sum;

        this.remain = this.total - this.paid;
          console.log(allQuantity);
        if (quantity > allQuantity || quantity <= 0) {
            this.bigQuantity = true;
        }
        else{
          this.bigQuantity = false;
        }
      },

    addTodetails(){
      const self = this;
      axios.post('/api/bill/add-to-bill',{
        bill_id:this.$route.params.id,
        stock:this.selectedItems,
        quantity:this.selectedItemsModel,
        total:this.total
      })
      .then((response)=>{
        console.log(response.data);

        self.addItem=false;
        self.stock=null;
        self.dialog=false;
        self.quantity='';
        self.selectedItems=[];
        self.selectedItemsModel=[];
        self.selectedMarks=[];
        self.searchResults=[];
        self.allMarks=null;
        self.sumTotal=0;
        self.total=0;
        self.paid=0;
        self.total=0;
        self.remain=0;
        self.totalSum=[];
        self.selectedBill=null;
        self.addPaid=0;
        self.done = true;
        self.loadDetails();

       })
      .catch((errors)=>{
        alert('error in loading stock');
        console.log(errors);
        console.log(errors.response);
      })
    },
    loadStock(){
      axios.post('/api/bill/stock',{
        bill_id:this.$route.params.id,

      })
      .then((response)=>{
        console.log(response.data);
        this.stock = response.data.stock;
       })
      .catch((errors)=>{
        alert('error in loading marks');
        console.log(errors);
        console.log(errors.response);
      })
    },
    editDetails(id){

      let index = this.details.findIndex((val)=>{
        return val.id == id;
      });
      this.selectedDetails = this.details[index];
      this.quantity = this.selectedDetails.quantity;
      this.dialog = true;
    },

    applyEditDetail(){
      axios.post('/api/bill/edit-details',{
        quantity:this.quantity,
        detail_id:this.selectedDetails.id,
        bill_id:this.id,
      })
      .then((response)=>{
        console.log(response.data);
        this.dialog = false;
        this.done = true;
      })
      .catch((errors)=>{
        alert('الكمية المدخلة أكبر من الكمية الموجودة في المخزن');
        console.log(errors);
        console.log(errors.response);
      })
    },
    loadDetails(){
        axios.post('/api/bill/show-details',{
          bill_id:this.$route.params.id,
        })
        .then((response)=>{
          console.log('-2-2-2-2-2--2');
          console.log(response.data);
          this.details = response.data.data;
          this.customer = response.data.customer;
          this.bill = response.data.bill;

        })
        .catch((errors)=>{
          console.log(errors);
        })
    },
    deleteDetails(detailId,stockId){
      const self = this;
      let sure = confirm ('هل أنت متأكد؟');
      if (sure) {
        axios.post('/api/bill/delete-details',{
          id:detailId,
          stock_id:stockId,
          bill_id:this.id,

        })
        .then((response)=>{
          self.addItem=false;
          self.stock=null;
          self.dialog=false;
          self.quantity='';
          self.selectedItems=[];
          self.selectedItemsModel=[];
          self.selectedMarks=[];
          self.searchResults=[];
          self.allMarks=null;
          self.sumTotal=0;
          self.total=0;
          self.paid=0;
          self.total=0;
          self.remain=0;
          self.totalSum=[];
          self.selectedBill=null;
          self.addPaid=0;
          let index = self.details.findIndex((val)=>{
            return val.id == detailId;
          });
          self.details.splice(index,1);
          self.done = true;
          this.loadDetails();
        })
        .catch((errors)=>{
          alert('errror in delete')
          console.log(errors);
          console.log(errors.response);
        })

      }
          }
  }

}
</script>

<style lang="css" scoped>
</style>
