// The Vue build version to load with the `import` command
// (runtime-only or standalone) has been set in webpack.base.conf with an alias.

import Vue from "vue";
import App from "./App";
import Vuetify from "vuetify";
import "vuetify/dist/vuetify.min.css";
import router from "./router";
import store from "./store";

import colors from "vuetify/es5/util/colors";
import ApiService from "@/services/api.service";
import { TokenService, TokenTurnos } from "@/services/storage.service";
// Vuetify Object (as described in the Vuetify 2 documentation)

const vuetifyOptions = {};
Vue.use(Vuetify);

if (TokenService.getToken()) {
  ApiService.setHeader();
}

new Vue({
  el: "#app",
  store,
  router,
  components: { App },
  template: "<App/>",
  vuetify: new Vuetify(vuetifyOptions)
});
