import { MODULE_STORE } from "@/const";

const common = {
  namespaced: true,
  state() {
    return {
      isLoadingPage: false,
      breadcrumbItems: [],
      breadcrumbCurrent: '',
    };
  },
  getters: {
    [MODULE_STORE.COMMON.GETTERS.GET_IS_LOADING](state) {
      return state.isLoadingPage;
    },
    [MODULE_STORE.COMMON.GETTERS.GET_BREADCRUMB_CURRENT](state) {
      return state.breadcrumbCurrent;
    },
    [MODULE_STORE.COMMON.GETTERS.GET_BREADCRUMB_ITEMS](state) {
      return state.breadcrumbItems;
    },
  },
};
export default common;
