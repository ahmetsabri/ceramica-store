<template>
  <v-content>

    <div class="text-xs-center">
      <v-btn dark round @click="addMarkDialog = true">
        <bdi>
          ماركة جديدة
          <v-icon>add</v-icon>
        </bdi>
      </v-btn>
    </div>
    <v-dialog
      v-model="showEditDialoge"
      max-width="500px"
      transition="dialog-transition">
        <v-card>
          <div color="indigo pa-4" v-if="showEditDialoge">
            <h1 class="text-xs-center indigo--text">
              <bdi>
                تعديل ماركة : {{selectedId.name}}

              </bdi>
            </h1>
          </div>
          <div class="ma-3 pa-4">
          <v-form @submit.prevent="applyEdit">
                    <v-text-field
                      v-model="markName"
                      solo-inverted
                      placeholder="اسم الماركة"
                      >
                      </v-text-field>
                      <div class="text-xs-center">
                        <v-btn color="indigo" dark @click="applyEdit" :disabled="markName.length === 0" round >تم</v-btn>
                      </div>
          </v-form>
        </div>
        </v-card>
    </v-dialog>
      <v-dialog
        v-model="addMarkDialog"
        :overlay="false"
        max-width="500px"
        transition="dialog-transition">
        <v-card>

          <div color="indigo pa-4">
            <h1 class="text-xs-center indigo--text">
              ادخال ماركة جديدة
            </h1>
          </div>

          <div class="ma-3 pa-4">

          <v-form @submit.prevent="createMark">
                    <v-text-field
                      v-model="markName"
                      solo-inverted
                      placeholder="اسم الماركة"
                      >
                      </v-text-field>
                      <div class="text-xs-center">
                        <v-btn color="indigo" dark @click="createMark" :disabled="markName.length === 0" round >تم</v-btn>
                      </div>
          </v-form>
        </div>

        </v-card>
      </v-dialog>
      <v-data-table
        v-if="!loading"
        :headers="headers"
        :items="marks"
        hide-actions
        >
      <template v-slot:items="props">
        <td class="text-xs-center">
          <v-btn small class="error" round @click="deleteMark(props.item.id)">حذف</v-btn>
          <v-btn small class="info" round @click="editMark(props.item.id)">تعديل</v-btn>
        </td>
        <td class="text-xs-center">{{ props.item.name }}</td>
   <td class="text-xs-center">{{ props.index +1 }}</td>
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
          markName:'',
          addMarkDialog:false,
          done:false,
          offset:2,
          loading:false,
          showEditDialoge:false,
          selectedId:null,
          headers:[
            {align:'center','text':'تعديل',value:'id'},
            {align:'center','text':'اسم الماركة',value:'name'},
            {align:'center','text':'مسلسل',value:'id'},
          ],
          marks:[],
        };
  },
  watch:{
    showEditDialoge(n){
      if (n == false) {

        this.markName = '';
      }
    },
    addMarkDialog(){
      if (n == false) {
        this.markName = '';

      }
    }
  },
    created(){
      this.loadMarks();
    },
    methods:{
      createMark(){
          axios.post(`/api/mark/create`,{
              name:this.markName
          }
        )
        .then((response)=>{
          this.marks.push(response.data.new_mark);
          this.addMarkDialog = false;
          this.done = true;
          this.markName = '';
        })
        .catch((error)=>{
          alert('error');
          console.log(error.response);
          console.log(error.response.data);
        })
      },

      loadMarks(){
        this.loading = true;
        axios.post(`/api/mark/show`,{})
        .then((response)=>{

        this.marks = response.data.marks;
        this.loading = false;
        this.marks = this.marks.reverse();

        })
        .catch((errors)=>{
          console.log(errors);
          console.log(errors.response);
          console.log(errors.response.data);
        })
      },

      deleteMark(id){
        let sure = confirm ('تأكيد الحذف؟');
        if (sure) {

        axios.post('/api/mark/delete',{
          id,
        })
        .then((response)=>{
              this.done = true;
              let index = this.marks.findIndex((val)=>{
                  return  val.id == id;
              });
              this.marks.splice(index,1);

        })
        .catch((errors)=>{
          alert('خطأ في الحذف');
          console.log(errors.response.data);
          console.log(errors.response);
        })
      }

      },
      editMark(id){
          let index = this.marks.findIndex((val)=>{
              return  val.id == id;
          });
          this.selectedId = this.marks[index];
          this.markName = this.selectedId.name;
          this.showEditDialoge = true ;

      },

      applyEdit(){
          let id = this.selectedId.id;
          let index = this.marks.findIndex((val)=>{
              return  val.id == id;
          });
          axios.post('/api/mark/edit',{
            id,
            name:this.markName
          })
          .then((response)=>{
            let id = this.selectedId.id;
            let index = this.marks.findIndex((val)=>{
                return  val.id == id;
            });
            Vue.set(this.marks,index,response.data.new_mark);
                this.done = true;
                this.markName = '';
                this.selectedId = null;
                this.showEditDialoge = false;
          })
          .catch((errors)=>{
            alert('خطأ في الحذف');
            console.log(errors.response.data);
            console.log(errors.response);
          });

      }
    }

}
</script>

<style lang="css" scoped>
</style>
