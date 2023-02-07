import {MODULE_STORE} from "@/const";

const importGood = {
  namespaced: true,
  state() {
    return {
      importGoodPostData: [],
      categories: [],
      products: [],
      customers: [],
    };
  },
  getters: {
    [MODULE_STORE.IMPORT_GOOD.GETTERS.GET_IMPORT_GOOD_DATA](state) {
      return state.importGoodPostData;
    },
  },
  mutations: {
    [MODULE_STORE.IMPORT_GOOD.MUTATIONS.ADD_IMPORT_GOOD_DATA](state, payload) {
      return state.importGoodPostData.push(payload)
    },

    [MODULE_STORE.IMPORT_GOOD.MUTATIONS.REMOVE_IMPORT_GOOD_DATA_ITEM](state, payload) {
      if (state.importGoodPostData[payload.index] === payload.item) {
        state.importGoodPostData.splice(payload.index, 1)
      } else {
        let found = state.importGoodPostData.indexOf(payload.item)
        state.importGoodPostData.splice(found, 1)
      }

      return state.importGoodPostData;
    },

    [MODULE_STORE.IMPORT_GOOD.MUTATIONS.UPDATE_IMPORT_GOOD_DATA_ITEM](state, payload) {
      state.importGoodPostData[payload.index] = {
        ...payload.data
      }

      return state.importGoodPostData;
    },
    [MODULE_STORE.IMPORT_GOOD.MUTATIONS.CLEAR_IMPORT_GOOD_DATA_ITEM](state, payload) {
      state.importGoodPostData = [...payload]

      return state.importGoodPostData;
    },

  },
};

export default importGood;
