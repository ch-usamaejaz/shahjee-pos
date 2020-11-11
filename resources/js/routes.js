import Vue from 'vue'
import VueRouter from 'vue-router';
Vue.use(VueRouter);

import ExampleComponent from "./components/ExampleComponent";

const routes = [
    {
        name: 'example',
        path: '/',
        component: ExampleComponent,
        meta:{
        }
    }
];

const router = new VueRouter({
    mode: 'history',
    routes: routes
});
export default router;
