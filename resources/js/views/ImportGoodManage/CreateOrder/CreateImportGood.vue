<template>
  <div class="w-full h-full">
    <div class="w-full pt-14 h-full pl-10">
      <div class="w-full py-6 py-auto text-xl">
        <span class="text-gray-500">Thêm sản phẩm</span>
        <hr>
      </div>
      <form @submit.prevent="handleSubmit(formData)">
<!--        <div class="mr-4 w-[14%] mb-5">-->
<!--          <label for="customer" class="block mb-1 font-bold text-sm">Khách hàng</label>-->
<!--          <select name="customer" class="p-3 w-full" v-model="formData.dealer_id">-->
<!--            <option v-for="item in dealers" :value="item.dealer_id"-->
<!--                    class="w-full h-10 px-3 text-base text-gray-700">{{ item.dealer_name }}-->
<!--            </option>-->
<!--          </select>-->
<!--        </div>-->
        <div class="mt-5 py-3 flex">
          <div class="mr-4 w-[18%]">
            <span>Danh mục</span>
          </div>
          <div class="mr-4 w-[18%]">
            <span>Sản phẩm</span>
          </div>
          <div class="mr-4 w-[18%]">
            <span>Mã sản phẩm</span>
          </div>
          <div class="mr-4 w-[10%]">
            <span>Tên sản phẩm</span>
          </div>
          <div class="mr-4 w-[10%]">
            <span>Số lượng</span>
          </div>
          <div class="mr-4 w-[10%]">
            <span>Xóa sp</span>
          </div>
        </div>

        <div v-if="renderComponent">
          <import-good-item v-for="(item, index) in listImportGoodItem" :key="index"
                      @handle-remove-input-item="handleRemoveInputItem({item, index})"
                      @item-valid="checkItemValid(error)"
                      :item="item"
                      :index="index"
                      :categories="categories"
                      :dealers="dealers"
                      :products="products"
          />
        </div>
        <Datepicker class="p-2 border border-gray-200 mt-3" v-model="picked" :style="styleDatePicker">
        </Datepicker>
        <div class="ml-2 my-4">
          <ButtonAddNew @clickBtn="handleAddToImportGood" :text="'Sản phẩm'"/>
        </div>
        <div>
          <input class="w-25 h-10 mt-5 px-3 text-base text-gray-700 placeholder-gray-400 bg-green-400 cursor-pointer" type="submit" value="Tạo đơn nhập hàng">
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import {useRouter} from "vue-router";
import {useStore} from "vuex";
import {ref, nextTick, inject} from "vue";
import {
  createImportGoodFromApi,
  getListCategoryFromApi,
  getListDealerFromApi,
  getListProductFromApi
} from "@/api";
import {MODULE_STORE, ROUTER_PATH} from "@/const";
import ButtonAddNew from "@/components/Buttons/ButtonAddNew";
import ImportGoodItem from "@/views/ImportGoodManage/CreateOrder/ImportGoodItem.vue";
import Datepicker from 'vue3-datepicker'

import {convertDateByTimestamp} from "@/utils";

export default {
  name: "CreateImportGood",
  components: { ImportGoodItem, ButtonAddNew, Datepicker },
  data() {
    return {
      renderComponent: true
    }
  },
  setup() {
    const router = useRouter()
    const store = useStore()
    const formData = ref({})
    const categories = ref({})
    const dealers = ref({})
    const products = ref({})
    const productsByCategory = ref({})
    const productSelected = ref({})
    const productAttributeValues = ref({})
    const productAttributeValuesByProduct = ref({})
    const listImportGoodItem = ref(store.state[MODULE_STORE.IMPORT_GOOD.NAME].importGoodPostData)
    const importGoodItemErrors = ref([])
    const toast = inject("$toast");
    const picked = ref(new Date())
    const styleDatePicker = ref({
      "--vdp-bg-color": "#ffffff",
      "--vdp-text-color": "#e21818",
      "--vdp-box-shadow": "0 4px 10px 0 rgba(128, 144, 160, 0.1), 0 0 1px 0 rgba(128, 144, 160, 0.81)",
      "--vdp-border-radius": "10px",
      "--vdp-heading-size": "2.5em",
      "--vdp-heading-weight": "bold",
      "--vdp-heading-hover-color": "#eeeeee",
      "--vdp-arrow-color": "currentColor",
      "--vdp-elem-color": "currentColor",
      "--vdp-disabled-color": "#d5d9e0",
      "--vdp-hover-color": "#ffffff",
      "--vdp-hover-bg-color": "#0baf74",
      "--vdp-selected-color": "#ffffff",
      "--vdp-selected-bg-color": "#0baf74",
      "--vdp-current-date-outline-color": "#888888",
      "--vdp-current-date-font-weight": "bold",
      "--vdp-elem-font-size": "1em",
      "--vdp-elem-border-radius": "3px",
      "--vdp-divider-color": "#ffffff"
    })

    const handleSubmit = async () => {
      try {
        store.state[MODULE_STORE.COMMON.NAME].isLoadingPage = true
        const orderPostData = [...store.state[MODULE_STORE.IMPORT_GOOD.NAME].importGoodPostData]
        const postData = {
          import_good_products: orderPostData,
          date: picked.value.getTime() / 1000 | 0
        }
        const res = await createImportGoodFromApi(postData)
        console.log(res)
        toast.success("Tạo đơn nhập thành công!", {duration:3000})
        router.push(`${ROUTER_PATH.ADMIN}/${ROUTER_PATH.IMPORT_GOOD_MANAGE}`)
        store.state[MODULE_STORE.COMMON.NAME].isLoadingPage = false
      } catch (errors) {
        const error = errors.message;
        toast.error(error)
      } finally {
        store.state[MODULE_STORE.COMMON.NAME].isLoadingPage = false
      }
    }

    const getListCategory = async () => {
      try {
        const res = await getListCategoryFromApi()
        categories.value = res.data.reduce( (option, data) => {
          return [
            ...option,
            {
              name: data.name,
              id: data.category_id
            }
          ]
        }, [])
        formData.value.category = res.data[0].category_id
        store.state[MODULE_STORE.IMPORT_GOOD.NAME].categories = res.data
      } catch (errors) {
        toast.error(errors.message)
      }
    }

    const getListProduct = async () => {
      const res = await getListProductFromApi();
      products.value = res.data
      store.state[MODULE_STORE.IMPORT_GOOD.NAME].products = res.data
    }

    const getListDealer = async () => {
      const res = await getListDealerFromApi();
      dealers.value = {
        ...res.data
      }
    }

    const handleOnChangeCategorySelect = () => {
      productsByCategory.value = products.value.filter((product) => {
        return product.category_id === formData.value.category
      })
    }
    const handleOnChangeProductSelect = () => {
      productSelected.value = products.value.filter((product) => {
        return product.product_id === formData.value.product
      })[0]

      productAttributeValuesByProduct.value = productSelected.value.product_attribute_values
    }
    const handleAddToImportGood = () => {
      const payload = {
        category_id: '',
        product_id: '',
        product_attribute_value_id: '',
        product_attribute_price_id: '',
        count: '',
        measure_unit_type: '',
        checked: false
      }
      store.commit(`${MODULE_STORE.IMPORT_GOOD.NAME}/${MODULE_STORE.IMPORT_GOOD.MUTATIONS.ADD_IMPORT_GOOD_DATA}`, payload)
      listImportGoodItem.value = []
      nextTick(() => {listImportGoodItem.value = store.state[MODULE_STORE.IMPORT_GOOD.NAME].importGoodPostData})
    }
    const handleRemoveInputItem = (item) => {
      store.commit(`${MODULE_STORE.IMPORT_GOOD.NAME}/${MODULE_STORE.IMPORT_GOOD.MUTATIONS.REMOVE_IMPORT_GOOD_DATA_ITEM}`, item)
      listImportGoodItem.value = []
      nextTick(() => {listImportGoodItem.value = store.state[MODULE_STORE.IMPORT_GOOD.NAME].importGoodPostData})
    }

    // const checkItemValid = (error) => {
    //   if (error.isValid === true) {
    //     importGoodItemErrors.value[error.index] = error.isValid
    //   } else {
    //     importGoodItemErrors.value.splice(error.index, 1)
    //   }
    // }

    getListCategory()
    getListDealer()
    getListProduct()

    return {
      formData,
      categories,
      dealers,
      products,
      productAttributeValuesByProduct,
      productsByCategory,
      listImportGoodItem,
      picked,
      styleDatePicker,
      handleSubmit,
      handleOnChangeCategorySelect,
      handleOnChangeProductSelect,
      handleAddToImportGood,
      // checkItemValid,
      handleRemoveInputItem
    }
  }
}
</script>

<style scoped>

</style>
