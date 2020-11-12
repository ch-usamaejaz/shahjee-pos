import Vue from 'vue'
import VueRouter from 'vue-router';
Vue.use(VueRouter);

import ExampleComponent from "./components/ExampleComponent";
import Login from "./pages/Login";
import SignUp from "./pages/SignUp";
import Dashboard from './pages/Dashboard';
import Orders from './pages/Orders';
import Inventory from './pages/Inventory';

const routes = [
    // {
    //     name: 'example',
    //     path: '/',
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
    {
        name: 'signup',
        path: '/signup',
        component: SignUp,
        meta:{

        }
    },
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
    }
];

const router = new VueRouter({
    mode: 'history',
    routes: routes
});
export default router;
