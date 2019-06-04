<template>
  <v-content>
    <h1 class="text-xs-center">
      بسم الله الرحمن الرحيم
    </h1>
    <h2>{{allFilled}}</h2>
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

                :rules="nameRules"
                  width="120"
                  v-if="selectedItems.indexOf(item.id) > -1"
                  v-model="selectedItemsModel[selectedItems.indexOf(item.id)]"
                  label="الكمية بالكرتونة"
                  solo-inverted></v-text-field>
                </v-flex>
        </template>
        <v-divider></v-divider>
      </div>
    </template>
    <div class="text-xs-center">
      <v-btn class="indigo white--text" :disabled="!allFilled"> انشاء</v-btn>
    </div>
  </v-content>
</template>

<script>
  import axios from 'axios';
export default {
  data(){
    return {
      selectedItems:[],
      selectedItemsModel:[],
      selectedMarks:[],
      allMarks:null,
      nameRules: [
            v => !!v || 'يجب ملء هذه الخانة',
          ],

    };
  },
  computed:{
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
      selectedItems(n,o){
        console.log(this.selectedItemsModel);
          if (n.length < o.length) {
              console.log('old',o);
              console.log('new',n);
            for (let i = 0; i < o.length; i++) {
                if (n.indexOf(o[i])==-1) {
                      this.selectedItemsModel.splice(i,1);

                }
            }
          }
      },
  },
  created(){
    this.loadStock();
  },
  methods:{
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
        alert('ok man')
    },

  }
}
</script>

<style lang="css" scoped>
</style>
