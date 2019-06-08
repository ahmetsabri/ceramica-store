<template>
  <v-content>

      <div class="text-xs-center">
        <h1>المخزن</h1>
      </div>

      <div class="text-xs-center">
          <v-btn round dark @click="showAddModal = true">
            <bdi>
              اضافة <v-icon color="white">add</v-icon>
            </bdi>
          </v-btn>
      </div>

      <v-dialog
        v-model="showAddModal"
        max-width="500px"
        transition="dialog-transition">

        <v-card>
          <div class="text-xs-center">
            <h2>اضافة صنف جديد</h2>
          </div>
          <div class="pa-3 ma-3">
              <v-form>
                  <v-select


                    solo-inverted
                    :items="allMarks"
                    item-text="name"
                    item-value="id"
                    v-model="selectedMark"
                    label="الماركة"
                  >
                  </v-select>

                  <v-select
                    solo-inverted

                      :items="allFilters"
                    item-text="name"
                    item-value="name"
                    v-model="selectedFilter"
                    label="الفرز"
                  ></v-select>

                  <v-text-field

                    solo-inverted

                    placeholder="اللون"
                    v-model="selctedColor"
                  ></v-text-field>

                  <v-text-field
                    solo-inverted

                    placeholder="الكمية بالكرتونة"
                    v-model="quantityInBox"
                  ></v-text-field>

                  <v-text-field
                    solo-inverted
                    placeholder="المقاس مثال 20 *20"
                    v-model="dimensions"
                  ></v-text-field>

                  <v-text-field
                    solo-inverted
                    placeholder="السعة المترية"
                    v-model="sizeInMeter"
                  ></v-text-field>

                  <v-text-field
                    solo-inverted
                    placeholder="السعر للمتر"
                    v-model="priceForMeter"
                  ></v-text-field>

                  <div class="text-xs-center">
                      <v-btn @click="addStock" class="white--text success" round>
                        <bdi>
                          اضافة <v-icon>done</v-icon>
                        </bdi>
                      </v-btn>
                  </div>
              </v-form>
          </div>
        </v-card>
      </v-dialog>

      <v-dialog
        v-model="showEditModal"
        max-width="500px"
        transition="dialog-transition">

        <v-card>
          <div class="text-xs-center">
            <h2>اضافة صنف جديد</h2>
          </div>
          <div class="pa-3 ma-3">
              <v-form>
                  <v-select
                    solo-inverted
                    :items="allMarks"
                    item-text="name"
                    item-value="id"
                    v-model="selectedMark"
                    label="الماركة"
                  >
                  </v-select>

                  <v-select
                    solo-inverted
                      :items="allFilters"
                    item-text="name"
                    item-value="name"
                    v-model="selectedFilter"
                    label="الفرز"
                  ></v-select>

                  <v-text-field

                    solo-inverted

                    placeholder="اللون"
                    v-model="selctedColor"
                  ></v-text-field>

                  <v-text-field
                    solo-inverted
                    placeholder="الكمية بالكرتونة"
                    v-model="quantityInBox"
                  ></v-text-field>

                  <v-text-field
                    solo-inverted
                    placeholder="المقاس"
                    v-model="dimensions"
                  ></v-text-field>

                  <v-text-field
                    solo-inverted
                    placeholder="السعة المترية"
                    v-model="sizeInMeter"
                  ></v-text-field>

                  <v-text-field
                    solo-inverted

                    placeholder="السعر للمتر"
                    v-model="priceForMeter"
                  ></v-text-field>

                  <div class="text-xs-center">
                      <v-btn @click="applyEditStock" class="white--text success" round>
                        <bdi>
                          حفظ <v-icon>done</v-icon>
                        </bdi>
                      </v-btn>
                  </div>
              </v-form>
          </div>
        </v-card>
      </v-dialog>
      <div class="text-xs-center">
      <v-select
        :items="allMarks"
        v-model="showMark"
        label="اختر الماركة"
        outline
        item-text="name"
        item-value="id"
      ></v-select>
      <h1 v-if="!!showMark">
        {{selectedMarkName}}
      </h1>
  </div>
    <v-data-table
        :headers="headers"
        :items="stock"
        hide-actions
        class="elevation-1">

      <template v-slot:items="props">
        <td class="text-xs-center">
          <v-btn round small color="error" @click="deleteStock(props.item.id)"> حذف </v-btn>
          <v-btn round small color="info" @click="editStock(props.item.id)"> تعديل </v-btn>
        </td>
        <td class="text-xs-center">{{ props.item.price }}</td>
        <td class="text-xs-center">{{ props.item.size }}</td>
        <td class="text-xs-center">{{ props.item.quantity }}</td>
        <td class="text-xs-center">{{ props.item.color }}</td>
        <td class="text-xs-center">{{ props.item.filter_id }}</td>
      </template>
      </v-data-table>
      <v-snackbar
        v-model="done"
        bottom
        right>
      <b>
           تم بنجاح
         </b>
        <v-btn class="green--text" flat dark @click.native="done = false">حسناً</v-btn>
      </v-snackbar>
  </v-content>
</template>

<script>
import axios from 'axios';
import Vue from 'vue';
export default {
  data(){
    return {
      showMark:null,
      showAddModal:false,
      showEditModal:false,
      allMarks:[],
      selectedMark:'',
      selctedColor:'',
      quantityInBox:'',
      sizeInMeter:'',
      priceForMeter:'',
      selectedFilter:'',
      dimensions:'',
      done:false,
      selectedId:null,
      stock:[],
      headers:[
        {'text':'تعديل','value':'id','align':'center'},
        {'text':'السعر للمتر','value':'id','align':'center'},
        {'text':'السعة المترية','value':'id','align':'center'},
        {'text':'الكمية','value':'id','align':'center'},
        {'text':'اللون','value':'id','align':'center'},
        {'text':'الفرزة','value':'id','align':'center'},
      ],
      allFilters:[
        {'id':1,name:'أولى'},
        {'id':2,name:'تانية'},
        {'id':3,name:'تالتة'},
        {'id':4,name:'رابعة'},
      ],
      selectedMarkName:null,
      };
  },
  watch:{
    showMark(n){
      let index = this.allMarks.findIndex((val)=>{
          return val.id == n;
      });
      this.selectedMarkName = this.allMarks[index]['name'];
      this.selectedMark = this.allMarks[index]['id'];
      this.loadMarkStock(n);
    },
    showEditModal(n){
      if (n == false) {
              this.selctedColor = '';
              this.quantityInBox='';
              this.sizeInMeter='';
              this.priceForMeter='';
              this.selectedFilter='';
      }
    },
    showAddModal(n){
      if (n == false) {
              this.selctedColor = '';
              this.quantityInBox='';
              this.sizeInMeter='';
              this.priceForMeter='';
              this.selectedFilter='';
      }
    }
  },
  created(){
    this.loadStock();
  },
  methods:{
    loadStock(){
      axios.post('/api/stock/read',{
      })
      .then((response)=>{
        console.log(response.data.stock);
        console.log(response.data.marks);
        this.allMarks = response.data.marks ;

       })
      .catch((errors)=>{
        alert('error in reading');
        console.log(errors);
        console.log(errors.response);
      })
    },
    loadMarkStock(id){
      axios.post('/api/stock/get-stock',{
        id,
      })
      .then((response)=>{
          console.log(response.data.stock);
          this.stock = response.data.stock;
       })
      .catch((errors)=>{
        alert('error in reading');
        console.log(errors);
        console.log(errors.response);
      })
    },

    addStock(){
      let index = this.allMarks.findIndex((val)=>{
          return val.id == this.selectedMark;
      });
      this.selectedMarkName = this.allMarks[index]['name'];

      axios.post('/api/stock/create',{
        mark_id:this.selectedMark,
        mark_name:this.selectedMarkName,
        dimension:this.dimensions,
        filter_id:this.selectedFilter,
        quantity:this.quantityInBox,
        color:this.selctedColor,
        size:this.sizeInMeter,
        price:this.priceForMeter,

      })
      .then((response)=>{
          console.log(response.data);
          this.showAddModal = false;
          this.done = true;
      })
      .catch((errors)=>{
        console.log(errors);
        console.log(errors.response);
      })
    },

    deleteStock(id){
      let sure = confirm ('هل أنت متأكد ؟');

      if (sure) {
          axios.post('/api/stock/delete',{
            id,
          })
          .then((response)=>{
            console.log(response.data);
            let index = this.stock.findIndex((val)=>{
                return val.id == id;
            });
            this.stock.splice(index,1);
            this.done = true;
          })
          .catch((errors)=>{
            alert('خطأ في الحذف');
            console.log(errors);
            console.log(errors.response);
            console.log(errors.response.data);
          })
      }
    },

    editStock(id){
      let index = this.stock.findIndex((val)=>{
          return val.id == id;
      });

      this.selectedId = this.stock[index];
      this.selectedMark = this.selectedId.mark_id;
      this.selectedFilter =   this.selectedId.filter_id;
      this.quantityInBox = this.selectedId.quantity;
      this.selctedColor = this.selectedId.color;
      this.sizeInMeter = this.selectedId.size;
      this.priceForMeter = this.selectedId.price;
      this.dimensions=this.selectedId.dimension;

      this.showEditModal = true;
    },

    applyEditStock(){
      let index = this.allMarks.findIndex((val)=>{
          return val.id == this.selectedId.id;
      });
      this.selectedMarkName = this.allMarks[index]['name'];

        axios.post('/api/stock/edit',{
          id:this.selectedId.id,
          mark_id:this.selectedMark,
          mark_name:this.selectedMarkName,
          dimension:this.dimensions,
          filter_id:this.selectedFilter,
          quantity:this.quantityInBox,
          color:this.selctedColor,
          size:this.sizeInMeter,
          price:this.priceForMeter,
        })
        .then((response)=>{
          console.log(response.data);
          let index = this.stock.findIndex((val)=>{
              return val.id == this.selectedId.id;
          });
            Vue.set(this.stock,index,response.data.new_stock)
            this.done = true;
            this.showEditModal = false;
            this.selectedId = null;
        })
        .catch((errors)=>{
          alert('خطأ في التعديل')
          console.log(errors);
          console.log(errors.response);
        })
    }
  }
}
</script>

<style lang="css" scoped>
</style>
