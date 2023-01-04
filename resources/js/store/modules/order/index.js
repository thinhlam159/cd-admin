import {MODULE_STORE} from "@/const";

const product = {
  namespaced: true,
  state() {
    return {
      orderPostData: [],
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
      if (state.orderPostData[payload.index] === payload.time) {
        state.orderPostData.splice(payload.index, 1)
      } else {
        let found = state.orderPostData.indexOf(payload.item)
        state.orderPostData.splice(found, 1)
      }
      let sortOrderPostData = state.orderPostData.sort((x, y) => x.product_attribute_value_id > y.product_attribute_value_id ? 1 : -1)
      state.orderPostData = sortOrderPostData.reduce((result, item) => {
        length = result.filter(filterItem => {
          return filterItem.product_attribute_value_id === item.product_attribute_value_id
        }).length

        return [...result, {...item, attribute_display_index: length + 1}]
      }, [])

      return state.orderPostData;
    },

    [MODULE_STORE.ORDER.MUTATIONS.UPDATE_ORDER_DATA_ITEM](state, payload) {
      state.orderPostData[payload.index] = {
        ...payload.data
      }
      let sortOrderPostData = state.orderPostData.sort((x, y) => x.product_attribute_value_id > y.product_attribute_value_id ? 1 : -1)
      state.orderPostData = sortOrderPostData.reduce((result, item) => {
        // length = result.filter(filterItem => {
        //   return filterItem.product_attribute_value_id === item.product_attribute_value_id
        // }).length
          const arrayLength = result.length
          let cnt = 0;
          for (let i = 0; i < arrayLength; i++) {
            if (item.product_attribute_value_id === result[i].product_attribute_value_id) {
              cnt += 1
            }
          }

        return [...result, {...item, attribute_display_index: cnt + 1}]
      }, [])

      // state.orderPostData = sortOrderPostData.map((item, index, array) => {
      //   const arrayLength = array.length
      //   let cnt = 0;
      //   for (let i = 0; i < arrayLength; i++) {
      //     if (item.product_attribute_value_id === array[i].product_attribute_value_id) {
      //       cnt += 1
      //     }
      //   }
      //   return {...item, attribute_display_index: cnt}
      // })
      console.log(state.orderPostData)

      return state.orderPostData;
    },
  },
};

export default product;
