import Vue from 'vue'
import VueRouter from 'vue-router';
Vue.use(VueRouter);

import ExampleComponent from "./components/ExampleComponent";
import Login from "./pages/Login";
import SignUp from "./pages/SignUp";

const routes = [
    {
        name: 'example',
        path: '/',
        component: ExampleComponent,
        meta:{
        }
    },
    {
        name: 'login',
        path: '/login',
        component: Login,
        meta:{    
        }
    },
    {
        name: 'signup',
        path: '/signup',
        component: SignUp
    }
];

const router = new VueRouter({
    mode: 'history',
    routes: routes
});
export default router;
