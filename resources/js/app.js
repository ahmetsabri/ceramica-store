import Vue from 'vue';
import axios from 'axios';
import VueRouter from 'vue-router';
import Vuetify from 'vuetify';
import {routes} from './routes';
import Home from './components/Home.vue'
Vue.use(Vuetify);
Vue.use(VueRouter);
const router = new VueRouter({
  routes,
  mode:'history'
});

const app = new Vue({
    el: '#app',
    router,
    components:{
      Home
    }
});
