import {MODULE_STORE} from "@/const";

const importGood = {
  namespaced: true,
  state() {
    return {
      importGoodPostData: [
        // {
        //   category_id: "01GF2WV4414C8MYFAGPB4BQS2R",
        //   product_id: "01GJW7C7ER00TPMBTCBBS2B1F1",
        //   product_attribute_value_id: "01GKB3QQZF14CN313FBY0ZZ6GN",
        //   count: 100,
        //   price: 33000,
        //   index: 0
        // }
      ],
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
      if (state.importGoodPostData[payload.index] === payload) {
        state.importGoodPostData.splice(payload.index, 1)
      } else {
        let found = state.importGoodPostData.indexOf(payload)
        state.importGoodPostData.splice(found, 1)
      }

      return state.importGoodPostData;
    },

    [MODULE_STORE.IMPORT_GOOD.MUTATIONS.UPDATE_IMPORT_GOOD_DATA_ITEM](state, payload) {
      if (state.importGoodPostData[payload.index] === payload) {
        state.importGoodPostData.splice(payload.index, 1, payload)
      } else {
        let found = state.importGoodPostData.indexOf(payload)
        state.importGoodPostData.splice(found, 1, payload)
      }

      return state.importGoodPostData;
    },
  },
};

export default importGood;
