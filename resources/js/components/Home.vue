<template>
    <v-app>
      <v-container grid-list-md>
        <v-layout row wrap>
          <v-flex xs12>
      <v-toolbar app dark class="hidden-print-only">
      <v-toolbar-side-icon @click="drawer=!drawer"></v-toolbar-side-icon>
      <v-toolbar-title>خيارات</v-toolbar-title>
      <v-spacer></v-spacer>
      <v-toolbar-items class="hidden-sm-and-down">
      </v-toolbar-items>
    </v-toolbar>
    <v-navigation-drawer
      v-model="drawer"
      dark
      app>
              <v-list two-line>
                <v-list-tile router to="/marks">
                  <h3>
                    <v-icon>loyalty</v-icon>
                    الماركات
                  </h3>
                </v-list-tile>
                <v-list-tile router to="/stock">
                  <h3>
                    <v-icon>store</v-icon>
                    المخزن
                  </h3>
                </v-list-tile>


                <v-list-tile router to="/bills">
                  <h3>
                    <v-icon>library_books</v-icon>

                                      الفواتير
                  </h3>
                </v-list-tile>

                <v-list-tile router to="/Customers">
                  <h3>
                    <v-icon>account_box</v-icon>
                    العملاء
                  </h3>
                </v-list-tile>
              </v-list>
          </v-navigation-drawer>
        </v-flex>

        <v-flex xs12>
          <router-view></router-view>
        </v-flex>

        </v-layout>

      </v-container>

      <v-bottom-nav
      class="hidden-print-only"
      app
     :active.sync="bottomNav"
     :value="true"
     color="indigo"
   >
   <marquee   direction="right"  behavior="alternate" scrollamount="1.5" class="qus">
     <h1 class="">
       <bdi>
         <span class="white--text">
         مرحباً بكم في معرض الرحمة للسيراميك
       </span>
         <span class="yellow--text">01017260199</span>
       </bdi>
     </h1>
   </marquee>
   </v-bottom-nav>

    </v-app>
</template>

<script>
    export default {
        mounted() {
            console.log('Component mounted.')
        },
        data(){
          return {
            drawer:false,
                    bottomNav: 'recent'
          };
        },

        methods:{
          user(){
            this.$router.push('/user')
          }
        }
    }
</script>
