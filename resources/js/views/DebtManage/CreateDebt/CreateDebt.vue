<template>
  <div class="w-full h-full relative">
    <div class="w-full pt-14 h-full absolute px-10">
      <div class="w-full py-6 py-auto text-xl">
        <span class="text-gray-500">Thêm sản phẩm</span>
        <hr>
      </div>
      <div class="mr-4 w-[14%] mb-5">
        <label for="customer" class="block mb-1 font-bold text-sm">Khách hàng</label>
        <select name="customer" class="p-3 w-full" v-model="formData.customerId">
          <option v-for="item in customers" :value="item.customer_id"
                  class="w-full h-10 px-3 text-base text-gray-700">{{ item.customer_name }}
          </option>
        </select>
      </div>
      <TabsWrapper>
        <TabItem title="Container">
          <CurrencyInput />
        </TabItem>
        <TabItem title="Vat">Content from Tab 2 Lorem ipsum dolor sit amet.</TabItem>
        <TabItem title="Khác">Content from Tab 3 Lorem ipsum dolor sit amet consectetur, adipisicing elit. Voluptates, ipsa.</TabItem>
      </TabsWrapper>
    </div>
  </div>
</template>

<script>
import {useRouter} from "vue-router";
import {useStore} from "vuex";
import {ref, onMounted} from "vue";
import {
  createOrderFromApi,
  createProductFromApi,
  getListCategoryFromApi,
  getListCustomerFromApi,
  getListProductFromApi
} from "@/api";
import {MODULE_STORE, ROUTER_PATH} from "@/const";
import InputItem from "@/views/OrderManage/CreateOrder/InputItem";
import ButtonAddNew from "@/components/Buttons/ButtonAddNew";
import TabsWrapper from "@/views/DebtManage/CreateDebt/TabsWrapper.vue";
import TabItem from "@/views/DebtManage/CreateDebt/TabItem.vue";
import ContainerOrderItem from "@/views/DebtManage/CreateDebt/ContainerOrderItem.vue";
import CurrencyInput from "@/views/DebtManage/CreateDebt/CurrencyInput.vue";

export default {
  name: "CreateDebt",
  components: { InputItem, ButtonAddNew, TabsWrapper, TabItem, ContainerOrderItem, CurrencyInput },
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
    const customers = ref({})
    const products = ref({})
    const productsByCategory = ref({})
    const productSelected = ref({})
    const productAttributeValues = ref({})
    const productAttributeValuesByProduct = ref({})
    const listInputItem = ref(store.state[MODULE_STORE.ORDER.NAME].orderPostData)

    const handleSubmit = async (data) => {
      try {
        const orderPostData = [...store.state[MODULE_STORE.ORDER.NAME].orderPostData]
        const bodyFormData = new FormData()
        bodyFormData.append('customer_id', data.customerId);
        // bodyFormData.append('order_products', orderPostData);
        const postData = {
          customer_id: data.customerId,
          order_products: orderPostData
        }
        const res = await createOrderFromApi(postData)
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
        store.state[MODULE_STORE.ORDER.NAME].categories = res.data
      } catch (errors) {
        // const error = errors.message;
        // console.log(error)
      }
    }

    const getListProduct = async () => {
      const res = await getListProductFromApi();
      products.value = res.data
      store.state[MODULE_STORE.ORDER.NAME].products = res.data
    }
    const getListCustomer = async () => {
      const res = await getListCustomerFromApi();
      customers.value = {
        ...res.data
      }
      console.log(res)
      store.state[MODULE_STORE.ORDER.NAME].customers = res.data
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
    const handleAddToOrder = () => {
      const index = store.state[MODULE_STORE.ORDER.NAME].orderPostData.length
      store.commit(`${MODULE_STORE.ORDER.NAME}/${MODULE_STORE.ORDER.MUTATIONS.ADD_ORDER_DATA}`, {index})
      // listInputItem.value = store.state[MODULE_STORE.ORDER.NAME].orderPostData
    }
    const handleRemoveInputItem = (item) => {
      store.commit(`${MODULE_STORE.ORDER.NAME}/${MODULE_STORE.ORDER.MUTATIONS.REMOVE_ORDER_DATA_ITEM}`, item)
      // listInputItem.value = store.state[MODULE_STORE.ORDER.NAME].orderPostData
    }
    const updateDisplay = () => {
      listInputItem.value = store.state[MODULE_STORE.ORDER.NAME].orderPostData
    }
    onMounted(() => {console.log(store.state[MODULE_STORE.ORDER.NAME].customers)})

    getListCategory()
    getListCustomer()
    getListProduct()

    return {
      formData,
      categories,
      customers,
      products,
      productAttributeValuesByProduct,
      productsByCategory,
      listInputItem,
      handleSubmit,
      handleOnChangeCategorySelect,
      handleOnChangeProductSelect,
      handleAddToOrder,
      handleRemoveInputItem,
      updateDisplay
    }
  }
}
</script>

<style scoped>

</style>
