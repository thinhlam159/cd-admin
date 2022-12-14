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
                      @update-display="forceUpdate"
                      :item="item"
                      :index="index"
                      :categories="categories"
                      :dealers="dealers"
                      :products="products"
          />
        </div>

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
import {ref, reactive, onMounted, nextTick} from "vue";
import logoTimeSharing from "@/assets/images/default-thumbnail.jpg";
import {
  createImportGoodFromApi,
  getListCategoryFromApi,
  getListDealerFromApi,
  getListProductFromApi
} from "@/api";
import {MODULE_STORE, ROUTER_PATH} from "@/const";
import ButtonAddNew from "@/components/Buttons/ButtonAddNew";
import ImportGoodItem from "@/views/ImportGoodManage/CreateOrder/ImportGoodItem.vue";

export default {
  name: "CreateImportGood",
  components: { ImportGoodItem, ButtonAddNew },
  methods: {
    forceUpdate() {
      this.renderComponent = false
      this.$nextTick(() => {
        this.renderComponent = true
      })
    }
  },
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

    const handleSubmit = async (data) => {
      try {
        const orderPostData = [...store.state[MODULE_STORE.IMPORT_GOOD.NAME].importGoodPostData]
        // const bodyFormData = new FormData()
        // bodyFormData.append('customer_id', data.customerId);
        // bodyFormData.append('order_products', orderPostData);
        const postData = {
          // customer_id: data.customerId,
          import_good_products: orderPostData
        }
        const res = await createImportGoodFromApi(postData)
        // router.push(`${ROUTER_PATH.ADMIN}/${ROUTER_PATH.PRODUCT_MANAGE}`)
      } catch (errors) {
        const error = errors.message;
        // this.$toast.error(error);
      } finally {
        store.state[MODULE_STORE.COMMON.NAME].isLoadingPage = false;
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
        // const error = errors.message;
        // console.log(error)
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
    const updateDisplay = () => {
      listImportGoodItem.value = store.state[MODULE_STORE.IMPORT_GOOD.NAME].importGoodPostData
    }

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
      handleSubmit,
      handleOnChangeCategorySelect,
      handleOnChangeProductSelect,
      handleAddToImportGood,
      handleRemoveInputItem,
      updateDisplay
    }
  }
}
</script>

<style scoped>

</style>
