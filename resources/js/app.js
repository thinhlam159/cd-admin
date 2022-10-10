require('./bootstrap');
import { createApp } from "vue";
import App from "./App.vue";
import router from "./router";
import store from "./store";
import VueAxios from 'vue-axios';
import axios from 'axios';
import i18n from "./i18n";

const app = createApp(App);

app.use(router);
app.use(store);
app.use(i18n);

app.mount("#app");

export default app;
