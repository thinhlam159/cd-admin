import {MODULE_STORE} from "@/const";

const product = {
  namespaced: true,
  state() {
    return {
      orderPostData: [],
    };
  },
  getters: {
    [MODULE_STORE.ORDER.GETTERS.GET_ORDER_DATA](state) {
      return state.orderPostData;
    },
  },
  mutations: {
    [MODULE_STORE.ORDER.MUTATIONS.ADD_ORDER_DATA](state, payload) {
      console.log(payload)
      console.log(state.orderPostData)
      return state.orderPostData.splice(payload.index, 1, payload.data);
    },
    [MODULE_STORE.ORDER.MUTATIONS.REMOVE_ORDER_DATA_ITEM](state, index) {
      console.log(state.orderPostData)
      return state.orderPostData.splice(index, 1);
    },
  }
};
export default product;
