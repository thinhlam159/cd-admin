import {MODULE_STORE} from "@/const";

const product = {
  namespaced: true,
  state() {
    return {
      orderPostData: [
        {
          category_id: "01GF2WV4414C8MYFAGPB4BQS2R",
          product_id: "01GJW7C7ER00TPMBTCBBS2B1F1",
          product_attribute_value_id: "01GKB3QQZF14CN313FBY0ZZ6GN",
          product_attribute_price_id: "01GKB3QQZF14CN313FBY0ZZ6GQ",
          count: 100,
          index: 0,
          price: 33000,
          productOrderName: 1
        }
      ],
      categories: [],
      products: [],
      customers: [],
    };
  },
  getters: {
    [MODULE_STORE.ORDER.GETTERS.GET_ORDER_DATA](state) {
      return state.orderPostData;
    },
  },
  mutations: {
    [MODULE_STORE.ORDER.MUTATIONS.ADD_ORDER_DATA](state, payload) {
      return state.orderPostData.push(payload)
    },
    [MODULE_STORE.ORDER.MUTATIONS.REMOVE_ORDER_DATA_ITEM](state, payload) {
      if (state.orderPostData[payload.index] === payload) {
        state.orderPostData.splice(payload.index, 1)
      } else {
        let found = state.orderPostData.indexOf(payload)
        state.orderPostData.splice(found, 1)
      }

      let sortOrderPostData = state.orderPostData.sort((x, y) => x.product_attribute_value_id > y.product_attribute_value_id ? 1 : -1)
      sortOrderPostData = sortOrderPostData.map((item, index) => {
        return {
          ...item,
          index
        }
      })
      state.orderPostData = sortOrderPostData.reduce((result, item) => {
        length = result.filter(filterItem => {
          return filterItem.product_attribute_value_id === item.product_attribute_value_id
        }).length
        return [...result, {...item, productOrderName: length + 1}]
      }, [])

      return state.orderPostData;
    },

    [MODULE_STORE.ORDER.MUTATIONS.UPDATE_ORDER_DATA_ITEM](state, payload) {
      state.orderPostData = payload

      return state.orderPostData;
    },
  },
};

export default product;
