/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */


require('./bootstrap');

window.Vue = require('vue');

import Routes from '../js/routes.js';
import App from '../js/pages/App';
import VueAxios from 'vue-axios';
import axios from 'axios';

import Vuetify from "vuetify";
import 'vuetify/dist/vuetify.min.css'

import global from './mixins/global';

Vue.use(Vuetify);

Vue.mixin(global);

Vue.use(VueAxios, axios.create({
    baseURL: 'http://127.0.0.1:8000'
}));


const app = new Vue({
    el: '#app',
    vuetify: new Vuetify(),
    router: Routes,
    render: h => h(App),
});

export default app;

