import Vue from 'vue'
import App from './App'
import router from './router'
import axios from "axios";

Vue.config.productionTip = false
Vue.prototype.$http = axios;
Vue.prototype.$urlAPI = "http://127.0.0.1:8000/api/";

/* eslint-disable no-new */
new Vue({
  el: '#app',
  router,
  template: '<App/>',
  components: { App }
})
