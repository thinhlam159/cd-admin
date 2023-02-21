<template>
  <div class="mx-5 min-h-[600px] bg-white mt-10">
    <div class="py-3 text-lg px-5 border-[#e7eaec] border-b-[2px]">
      <p class="text-gray-500 font-semibold">Tạo đơn hàng</p>
    </div>
    <div class="pl-5 pt-3 mt-3">
      <div class="w-[330px]">
        <div class="w-full">
          <p class="text-gray-400 text-base">Khách hàng</p>
          <div class="flex items-end w-full">
            <SelectBoxWithSearch :options="customers" @option-selected="handleSelectCustomer"/>
<!--            <span class="ml-5 text-sm">{{ selectedCustomer ? selectedCustomer.customer_name : ''}}</span>-->
          </div>
        </div>
        <div class="w-full mt-3">
          <p class="text-gray-400 text-base">Ngày tạo đơn</p>
          <Datepicker class="p-2 border border-gray-200 w-full h-[40px] text-base outline-none" v-model="picked" :style="styleDatePicker"/>
        </div>
      </div>
    </div>
    <div class="px-5 pt-3 mt-3">
      <p class="text-gray-400 text-base">Chọn sản phẩm</p>
      <AddOrderItemBlock @addProductItem="handleAddProductItem"/>
    </div>
    <div class="px-5 pt-3 mt-3">
      <p class="text-gray-400 text-base">Đơn hàng</p>
      <div class="border border-gray-300 rounded-sm w-full">
        <div class="flex text-md items-center bg-gray-100 border-b border-gray-300">
          <span class="border-r border-gray-300 text-center py-2 w-[10%]">STT</span>
          <span class="border-r border-gray-300 text-center py-2 w-[25%]">Mã</span>
          <span class="border-r border-gray-300 text-center py-2 w-[10%]">Tên</span>
          <span class="border-r border-gray-300 text-center py-2 w-[10%]">Đơn giá</span>
          <span class="border-r border-gray-300 text-center py-2 w-[10%]">Số lượng</span>
          <span class="border-r border-gray-300 text-center py-2 w-[25%]">Thành tiền</span>
          <span class="border-r border-gray-300 text-center py-2 w-[10%]">Xóa</span>
        </div>
        <div class="text-md min-h-[300px]">
          <form @submit.prevent="handleSubmit()">
            <div class="border-b border-gray-300">
              <div v-for="(orderItem, index) in listOrderItem" :key="index" class="flex items-center">
                <div class="border-r border-gray-300 text-center py-2 w-[10%]">{{ index + 1 }}</div>
                <div class="border-r border-gray-300 text-center py-2 w-[25%]">{{
                    `${orderItem.productCode} ${orderItem.code} x ${orderItem.noticePriceType} x ${orderItem.price.toLocaleString('it-IT', {
                      style: 'currency',
                      currency: 'VND'
                    })}`
                  }}
                </div>
                <div class="border-r border-gray-300 text-center py-2 w-[10%]">{{ orderItem.code + orderItem.order }}</div>
                <div class="border-r border-gray-300 text-center py-2 w-[10%]">
                  {{ orderItem.standardPrice.toLocaleString('it-IT', {style: 'currency', currency: 'VND'}) }}</div>
                <div class="border-r border-gray-300 text-center py-2 w-[10%]">
                  <input type="number" class="outline-none border-b border-gray-400 w-1/2 text-center" min="0"
                         v-model="orderItem.weight">
                </div>
                <div class="border-r border-gray-300 text-center py-2 w-[25%]">{{
                    (orderItem.standardPrice * orderItem.weight).toLocaleString('it-IT', {
                      style: 'currency',
                      currency: 'VND'
                    })
                  }}
                </div>
                <div class="flex justify-center items-center border-r border-gray-300 py-2 w-[10%]">
                  <ButtonRemove @clickBtn="handleRemoveOrderItem(index)" text='Xóa'/>
                </div>
              </div>
            </div>
            <div class="mt-2">
              <button type="submit" class="border-gray-400 border hover:bg-[#d6d5d5] p-2 rounded-md">Tạo đơn</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import {useRouter} from "vue-router";
import {useStore} from "vuex";
import {ref, inject} from "vue";
import {
  createOrderFromApi,
  getListCategoryFromApi,
  getListCustomerFromApi,
  getListProductFromApi
} from "@/api";
import {MODULE_STORE, ROUTER_PATH, styleDatePicker} from "@/const";
import InputItem from "@/views/OrderManage/CreateOrder/InputItem";
import ButtonAddNew from "@/components/Buttons/ButtonAddNew";
import SelectBoxWithSearch from "@/components/MultiSelect/SelectBoxWithSearch.vue";
import MultiSelect from "@/components/MultiSelect/MultiSelect.vue";
import AddOrderItemBlock from "@/views/OrderManage/CreateOrder/AddOrderItemBlock.vue";
import ButtonRemove from "@/components/Buttons/ButtonRemove/ButtonRemove.vue";
import Datepicker from 'vue3-datepicker'

const router = useRouter()
const store = useStore()
const toast = inject('$toast')
const formData = ref({})
const categories = ref({})
const customers = ref([])
const products = ref({})
const productsByCategory = ref({})
const productSelected = ref({})
const productAttributeValuesByProduct = ref({})
const listInputItem = ref(store.state[MODULE_STORE.ORDER.NAME].orderPostData)
const selectedCustomer = ref(null)
const customerMessageError = ref(null)
const listOrderItem = ref([])
const picked = ref(new Date())

const handleSubmit = async () => {
  try {
    const orderPostData = listOrderItem.value.map(orderItem => {
      return {
        product_id: orderItem.productId,
        product_attribute_value_id: orderItem.productAttributeValueId,
        product_attribute_price_id: orderItem.productAttributePriceId,
        attribute_display_index: orderItem.order,
        count: 1,
        measure_unit_type: orderItem.measureUnitName,
        weight: orderItem.weight,
        notice_price_type: orderItem.noticePriceType,
      }
    })
    const year = picked.value.getFullYear()
    const month = ('0' + (picked.value.getMonth() + 1)).slice(-2)
    const day = ('0' + picked.value.getDate()).slice(-2)
    const date = `${year}-${month}-${day}`
    const postData = {
      customer_id: selectedCustomer.value.customer_id,
      order_products: orderPostData,
      date: date
    }
    const res = await createOrderFromApi(postData)
    await router.push(`${ROUTER_PATH.ADMIN}/${ROUTER_PATH.ORDER_MANAGE}`)
    toast.success('Tạo đơn hàng thành công', {duration: 3500})
  } catch (errors) {
    const error = errors.message;
    toast.error(error);
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
    const error = errors.message;
    toast.error(error)
  }
}

const getListProduct = async () => {
  try {
    const res = await getListProductFromApi();
    const data = res.data
    products.value = res.data
    store.state[MODULE_STORE.ORDER.NAME].products = res.data
    data.forEach(product => {
      product.product_attribute_values.forEach(attributeValue => {
        const payload = {
          productAttributeValueId: attributeValue.product_attribute_value_id,
          code: attributeValue.code,
          measureUnitName: attributeValue.measure_unit_name,
          monetaryUnitName: attributeValue.monetary_unit_name,
          noticePriceType: attributeValue.notice_price_type,
          price: attributeValue.price,
          standardPrice: attributeValue.standard_price,
          productId: product.product_id,
          productName: product.name,
          productCode: product.code,
          productAttributePriceId: product.product_attribute_price_id,
        }
        store.commit(`${MODULE_STORE.ORDER.NAME}/${MODULE_STORE.ORDER.MUTATIONS.ADD_PRODUCT_ATTRIBUTE_VALUE}`, payload)
      })
    })
  } catch (errors) {
    toast.error(errors.message)
  }
}
const getListCustomer = async () => {
  const res = await getListCustomerFromApi();
  customers.value = [
    ...res.data.map(item => {
      return {
        ...item,
        text: item.customer_name
      }
    })
  ]
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
const handleSelectCustomer = (selectedItem) => {
  customerMessageError.value = false
  selectedCustomer.value = {...selectedItem}
}
const handleAddProductItem = (item) => {
  const listProductAttributeValue = [...store.state[MODULE_STORE.ORDER.NAME].productAttributeValues]
  const itemOrder = listProductAttributeValue.find(i => i.productAttributeValueId === item.id)
  for(let i = 0; i < item.amount; i++) {
    const lastItemOfSameAttributeValue = listOrderItem.value.slice().reverse().find(i => i.productAttributeValueId === item.id)
    const order = lastItemOfSameAttributeValue ? lastItemOfSameAttributeValue.order + 1 : 1
    listOrderItem.value.push({
      ...itemOrder,
      weight: 0,
      cost: 0,
      order: order
    })
  }
  listOrderItem.value.sort((a, b) => {
    if (a.productAttributeValueId === b.productAttributeValueId) {
      return a.order - b.order;
    }
    return a.productAttributeValueId.localeCompare(b.productAttributeValueId);
  })
}
const handleRemoveOrderItem = (index) => {
  listOrderItem.value.splice(index, 1)
}

store.state[MODULE_STORE.COMMON.NAME].breadcrumbCurrent = 'Tạo đơn'
store.state[MODULE_STORE.COMMON.NAME].breadcrumbItems = [
  {
    label: 'Trang chủ',
    link: '/dashboard'
  },
  {
    label: 'Đơn hàng',
    link: '/order-manage'
  },
]

getListCategory()
getListCustomer()
getListProduct()
</script>

<style scoped>

</style>
