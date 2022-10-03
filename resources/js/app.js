require('./bootstrap');
import { createApp } from "vue";
import App from "./App.vue";
import router from "./router";
import store from "./store";
import VueAxios from 'vue-axios';
import axios from 'axios';

const app = createApp(App);

app.use(router);
app.use(store);

app.mount("#app");

export default app;
