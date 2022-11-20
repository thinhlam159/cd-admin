import { MODULE_STORE } from "@/const";

const product = {
  namespaced: true,
  state() {
    return {
      orderPostData: {},
    };
  },
  getters: {
    getOrderPostData(state) {
      return state.orderPostData;
    },
  },
};
export default product;
