import Vue from 'vue'
import VueRouter from 'vue-router';
Vue.use(VueRouter);

import ExampleComponent from "./components/ExampleComponent";
import Login from "./pages/Login";
import SignUp from "./pages/SignUp";
import Dashboard from './pages/Dashboard';
import Orders from './pages/Orders';
import Inventory from './pages/Inventory';
import Profile from './pages/Profile';
import Print from './components/ordersPage/printTicket'
import Store from './pages/Store';

const routes = [
    // {
    //     name: 'example',
    //     path: '/example',
    //     component: ExampleComponent,
    //     meta:{
    //     }
    // },
    {
        name: 'login',
        path: '/',
        component: Login,
        meta:{
        }
    },
    // {
    //     name: 'signup',
    //     path: '/signup',
    //     component: SignUp,
    //     meta:{

    //     }
    // },
    {
        name: 'dashboard',
        path: '/dashboard',
        component: Dashboard,
        meta: {
            layout: ''
        }
    },
    {
        name: 'orders',
        path: '/orders',
        component: Orders,
        meta:{
            layout: ''
        }
    },
    {
        name: 'inventory',
        path: '/inventory',
        component: Inventory,
        meta: {
            layout: ''
        }
    },
    {
        name: 'store',
        path: '/store',
        component: Store,
        meta: {
            layout: ''
        }
    },
    {
        name: 'profile',
        path: '/profile',
        component: Profile,
        meta: {
            layout: ''
        }
    },
    {
        name: 'print_order',
        path: '/print_order/:id',
        component: Print
    }
];


const router = new VueRouter({
    mode: 'history',
    routes: routes
});

router.beforeEach((to, from, next) => {
    if (to.name !== 'login' && !localStorage.getItem(1)) next({ name: 'login' });
    else if(to.name === 'login' && localStorage.getItem(1)) next({ name: 'dashboard' });
    else next()
});
console.log(localStorage.getItem(1))
export default router;
