import User from './components/User.vue';
import Mark from './components/Mark.vue';
import Stock from './components/Stock.vue';
import Bills from './components/Bills.vue';
import Customers from './components/Customers.vue';
import BillDetails from './components/BillDetails.vue';
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
  {
    path:'/details/:id',
    component:BillDetails,
    name:'show-bill-details',
  },

]
