import User from './components/User.vue';
import Mark from './components/Mark.vue';
import Stock from './components/Stock.vue';
import Bills from './components/Bills.vue';
import Customers from './components/Customers.vue';
export const routes = [

  {
    path:'/customers',
    component:Customers,
  },
  {
    path:'/marks',
    component:Mark,
  },
  {
    path:'/bills',
    component:Bills,
  },
  {
    path:'/stock',
    component:Stock,
  },

]
