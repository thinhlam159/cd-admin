require('./bootstrap');
import { createApp } from "vue";
import App from "./App.vue";
import router from "./router";
import store from "./store";
import i18n from "./i18n";
import VueToast from 'vue-toast-notification'
import "./assets/styles/base.css"
import "../../public/css/app.css"
import "./assets/styles/font-awesome-4.5.0-master/css/font-awesome.css"
import * as VeeValidate from "vee-validate";
import { plugin, defaultConfig } from "@formkit/vue";
import 'vue-toast-notification/dist/theme-sugar.css';

const app = createApp(App);

app.use(router);
app.use(store);
app.use(i18n);
app.use(plugin, defaultConfig);
app.use(VeeValidate, {
    inject: true,
    fieldsBagName: "$veeFields",
    errorBagName: "$veeErrors",
});

app.use(VueToast, {
  position: "top-right",
  duration: 3000
});

app.mount("#app");

export default app;
