import { createStore } from "vuex";
import { MODULE_STORE } from "@/const";
// import rentalSpaces from "./modules/rentalSpaces";
import auth from "./modules/auth";
import common from "./modules/common";
import product from "./modules/product";
import order from "@/store/modules/order";

const store = createStore({
    modules: {
        // [MODULE_STORE.RENTAL_SPACES.NAME]: rentalSpaces,
        [MODULE_STORE.AUTH.NAME]: auth,
        [MODULE_STORE.COMMON.NAME]: common,
        [MODULE_STORE.PRODUCT.NAME]: product,
        [MODULE_STORE.ORDER.NAME]: order,
    },
});

export default store;
