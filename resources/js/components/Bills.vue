<template>
  <v-content>
    <h1 class="text-xs-center">
      بسم الله الرحمن الرحيم
    </h1>
    <div class="text-xs-center">
      <v-btn large round @click="newCustomer = false" class="indigo white--text">عميل حالي</v-btn>
        <v-btn large @click="newCustomer = true" class="indigo white--text" round>عميل جديد</v-btn>
        <v-text-field v-if="!newCustomer" placeholder="بحث عن عميل" solo-inverted v-model="search"></v-text-field>
        <v-list dark>
            <template v-for="result in searchResults">

          <v-list-tile @click="showCustomer(result.id)">
              {{result.name}}
          </v-list-tile>

        </template>
        </v-list>
        <v-data-table
          v-if="!!selectedCustomer"
          :headers="headers"
          :items="selectedCustomer"
          hide-actions>
                <template v-slot:items="props">
                  <td class="text-xs-center">
                    {{props.item.address}}
                  </td>
                  <td class="text-xs-center">
                    {{props.item.phone}}

                  </td>
                  <td class="text-xs-center">
                    {{props.item.name}}
                  </td>
                </template>
        </v-data-table>
        <h1 class="text-xs-center ma-3" v-if="!!selectedCustomerBills">الفواتير</h1>
        <v-data-table
          v-if="!!selectedCustomerBills"
          :headers="billsHeaders"
          :items="selectedCustomerBills"
          hide-actions
          class="elevation-1">
          <template v-slot:items="props">




            <td class="text-xs-center">
              <v-btn round class="indigo white--text" @click="()=>showBill(props.item.id)"> عرض التفاصيل</v-btn>
            </td>

            <td class="text-xs-center">
              {{props.item.updated_at}}
            </td>

            <td class="text-xs-center">
                {{props.item.created_at}}
            </td>



            <td class="text-xs-center">
              {{props.item.remain}}
            </td>

            <td class="text-xs-center">
              {{props.item.paid}}
            </td>

            <td class="text-xs-center">
              {{props.item.total}}
            </td>




          </template>
        </v-data-table>
    </div>

    <v-stepper v-model="e1" v-if="newCustomer">
    <v-stepper-header>
      <v-stepper-step class="indigo--text" :complete="e1 > 1" step="1">بيانات العميل</v-stepper-step>

      <v-divider></v-divider>

      <v-stepper-step class="indigo--text" :complete="e1 > 2" step="2">تفاصيل الفاتورة</v-stepper-step>

      <v-divider></v-divider>
    </v-stepper-header>

    <v-stepper-items>
      <v-stepper-content step="1">

        <v-text-field
          label="اسم العميل"
          v-model="name"
          outline
          >
          </v-text-field>

          <v-text-field
            label="العنوان"
            v-model="address"
            outline

            >
            </v-text-field>

          <v-text-field
            label="رقم التلفون"
            v-model="phone"
            outline

            >
            </v-text-field>
        <v-btn
          :disabled="!customerDataOk"
          color="primary"
          @click="e1 = 2">
          Continue
        </v-btn>

        <v-btn flat>Cancel</v-btn>
      </v-stepper-content>

      <v-stepper-content step="2">
        <template v-for="mark in allMarks">
          <div dir="rtl">
          <h1 class="indigo--text">
             {{mark.name}}
           </h1>

            <template v-for="(item,index) in mark.stock">
              <v-flex xs3>
                <v-divider class="ma-3"></v-divider>
                    <v-checkbox color="indigo" v-model="selectedItems" label="John" :value="item.id">
                      <template v-slot:label>
                        <bdi class="black--text">
                            فرزة {{item.filter_id}} - {{item.color}} - مقاس {{item.dimension}}
                        </bdi>
                      </template>
                    </v-checkbox>
                    <v-text-field
                      :hint="`سعر المتر  : ${item.price} جنيه - السعة: ${item.size} متر`"
                     :rules="nameRules"
                      width="120"
                      v-if="selectedItems.indexOf(item.id) > -1"
                      v-model="selectedItemsModel[selectedItems.indexOf(item.id)]"
                      @keyup="calculateTotal(item.id,item.price , item.size , selectedItemsModel[selectedItems.indexOf(item.id)])"
                      label="الكمية بالكرتونة"
                      solo-inverted>

                      </v-text-field>
                      <h5 v-if="selectedItems.indexOf(item.id) > -1">
                        <bdi>
                          السعر :
                          <span class="green--text">
                              {{isNaN(selectedItemsModel[selectedItems.indexOf(item.id)] * item.size * item.price) ? 0 : selectedItemsModel[selectedItems.indexOf(item.id)] * item.size * item.price}}
                          </span>
                      </bdi>

                    </h5>
                    </v-flex>
            </template>
            <v-divider></v-divider>
          </div>
        </template>
        <v-text-field v-model="paid" label="المدفوع" outline></v-text-field>
        <v-text-field readonly :value="total"  label="الاجمالي" outline></v-text-field>
        <v-text-field readonly :value="remain"  label="المتبقي" outline></v-text-field>
        <div class="text-xs-center">
          <v-btn @click="createNewBill" class="indigo white--text" :disabled="!allFilled"> انشاء</v-btn>
        </div>
      </v-stepper-content>
    </v-stepper-items>
  </v-stepper>


  </v-content>
</template>

<script>
  import axios from 'axios';
export default {
  data(){
    return {
      search:"",
      billsHeaders:[
        {'text':'تفاصيل الفاتورة','align':'center','value':'id'},
        {'text':'اخر تعديل','align':'center','value':'id'},
        {'text':'تاريخ الفاتورة','align':'center','value':'id'},
        {'text':'باقي','align':'center','value':'id'},
        {'text':'مدفوع','align':'center','value':'id'},
        {'text':'الاجمالي','align':'center','value':'id'},
      ],
      headers:[
        {'text':'العنوان','align':'center','value':'id'},
        {'text':'رقم الهاتف','align':'center','value':'id'},
        {'text':'الاسم','align':'center','value':'id'},
      ],
      newCustomer:false,
      e1:0,
      selectedCustomer:null,
      selectedCustomerBills:null,
      name:'',
      phone:'',
      address:'',
      selectedItems:[],
      selectedItemsModel:[],
      selectedMarks:[],
      searchResults:[],
      sumTotal:0,
      allMarks:null,
      total:0,
      paid:'',
      total:0,
      remain:0,
      totalSum:[],
      nameRules: [
            v => !!v || 'يجب ملء هذه الخانة',
          ],

    };
  },
  computed:{
    customerDataOk(){

        return ((this.name.length > 0 ) && (this.address.length > 0) && (this.phone.length > 0));
    },
    allFilled(){
      if (this.selectedItemsModel.length > 0) {

      if (this.selectedItemsModel.length == this.selectedItems.length) {
          let isFilled  = this.selectedItemsModel.map((val)=>{
              return val > 0;
          });
          return  isFilled.indexOf(false) == -1;
        }
      }
      else{
        return false;
      }
    }
  },
  watch:{

    paid(n){
      this.remain = this.total - n ;
      console.log(n);
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
                    this.remain = this.total - this.paid;

            }
            else{
              this.total = 0;
              this.paid = 0;
              this.remain = 0;


            }

          }
      },
      search(n,o){
        if (n.length > 0) {
        this.searchCustomer();
      }

      }
  },
  created(){
    this.loadStock();
  },
  methods:{
      showBill(id){
        alert(id)
      },
      showCustomer(id){

          let index = this.searchResults.findIndex((val)=>{
            return val.id == id;
          })

          this.selectedCustomer = [this.searchResults[index]];
          this.selectedCustomerBills = this.selectedCustomer[0]['bills'];
          console.log(this.selectedCustomerBills);
      },
    calculateTotal(id,price,size,quantity){

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
      },

    loadStock(){
      axios.post('/api/stock/read',{
      })
      .then((response)=>{
        console.log(response.data.marks);

        this.allMarks = response.data.marks ;

       })
      .catch((errors)=>{
        alert('error in loading marks');
        console.log(errors);
        console.log(errors.response);
      })
    },

    createNewBill(){

      axios.post('/api/bill/create',{
        name:this.name,
        address:this.address,
        phone:this.phone,
        remain:this.remain,
        total:this.total,
        paid:this.paid,

        stock:this.selectedItems,
        quantity:this.selectedItemsModel,

      })
      .then((response)=>{
        console.log(response.data);
      })
      .catch((errors)=>{

        alert('error in create bill');
        console.log(errors);
        console.log(errors.response.data);

      })
    },

    searchCustomer(){
      let data = {search:this.search} ;

      axios.post('/api/bill/search-customer',data)
      .then((response)=>{
        console.log(response.data.customer);
          this.searchResults = response.data.customer;
      })
      .catch((errors)=>{
        console.log(error);
          console.log(error.response);
      })
    }
  }
}
</script>

<style lang="css" scoped>
</style>
