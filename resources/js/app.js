import './bootstrap';
import '../css/app.scss';
import {createApp} from "vue";
import App from './src/App';
import {createRouter, createWebHistory,} from 'vue-router';
// import axios from "axios";

import Home from './src/components/view/Home'
import Seller from './src/components/Seller'

const routes = [
    {
        path: '/',
        component: Home,
        name: 'sellers',
    },
    {
        path: '/create',
        component: Seller
    },
    {
        path: '/sellers/:id/edit',
        component: Seller,
        props: true
    }
]

const router = createRouter({
    routes,
    history: createWebHistory(),
})

createApp(App)
    .use(router)
    .mount("#app");
