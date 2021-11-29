import Vue from "vue";
import VueRouter from "vue-router";
import VueAxios from "vue-axios";
import Axios from "axios";

// visitor pages
import HomePage from "./views/visitor/HomePage.vue";
import PageNotFound from "./views/errors/404.vue";

Vue.use(VueRouter, VueAxios, Axios);

const router = new VueRouter({
    mode: "history",
    routes: [
        // visitor pages
        {
            path: "/",
            component: HomePage
        },
        {
            path: "/home",
            name: "homepage",
            component: HomePage
        },
        {
            path: "*",
            name: "404",
            component: PageNotFound,
        }
    ]
});

export default router;
