import router from "./router-web.js";

require("./bootstrap");
window.Vue = require("vue");

const app = new Vue({
    router,
    el: "#app"
});
