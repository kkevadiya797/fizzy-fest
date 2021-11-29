import router from "./router.js";
import { store } from "./store/store.js";

require("./bootstrap");
window.Vue = require("vue");
window.moment = require("moment");

import VueSweetalert2 from "vue-sweetalert2";
import JQuery from "jquery";
import { ClientTable, Event } from "vue-tables-2";
import Pagination from "vue-pagination-2";
import vueNumeralFilterInstaller from "vue-numeral-filter";

import "vue-loading-overlay/dist/vue-loading.css";
import "sweetalert2/dist/sweetalert2.min.css";

import VueNumber from "vue-number-animation";

Vue.use(VueSweetalert2);
Vue.use(ClientTable);
Vue.use(Pagination);
Vue.use(VueNumber);
Vue.use(vueNumeralFilterInstaller);

new Vue.use(JQuery);

const app = new Vue({
    router,
    store,
    el: "#app"
});
