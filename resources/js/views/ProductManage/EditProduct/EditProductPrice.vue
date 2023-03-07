<template>
  <div class="mx-5 min-h-[600px] bg-white mt-10">
    <div class="py-3 text-lg px-5 border-[#e7eaec] border-b-[2px]">
      <p class="text-gray-500 font-semibold">Cập nhật báo giá</p>
    </div>
    <div class="px-10 py-3">
      <div class="flex justify-start border border-gray-200 rounded-md p-5 w-max bg-gray-50">
        <div class="w-[250px] bg-white">
          <input type="text" class="w-full px-2 py-3 border border-gray-200 outline-none" @input="handleFilter" placeholder="Lọc theo tên">
          <div class="w-full max-h-[300px] overflow-y-scroll border border-gray-200 min-h-[300px]">
            <ul class="w-full">
              <li v-for="item in filterItems" @click="handleSelect(item)" :key="item.productId" class="px-3 py-2 hover:bg-[#d9d9d9]">{{ item.productName }}</li>
            </ul>
          </div>
        </div>
        <div class="w-[400px] ml-10 bg-white">
          <div class="w-full max-h-[300px] border border-gray-200 min-h-[342px]">
            <div class="w-full">
              <div v-for="item in noticePriceUpdateItems" :key="item.productId" class="px-3 py-2 flex border-b border-gray-200 justify-around">
                <span class="w-[150px]">{{ item.productName }}</span>
<!--                <input type="text" class="w-[100px] outline-none border-b border-gray-200" v-model="item.price" @input="handleChangePrice(item)">-->
                <div class="w-[100px]">
                  <CurrencyInput
                    name="price"
                    type="text"
                    v-model="item.price"
                    :value="item.price"
                    placeholder="nhập giá"
                    :options="{ currency: 'VND', currencyDisplay: 'hidden' }"
                  />
                </div>
                <div class="w-[50px] text-center">
                  <span>đ</span>
                </div>
                <div class="w-[30px] cursor-pointer text-center" @click="handleRemove(item)">
                  <i class="fa-lg fa fa-times"></i>
                </div>
              </div>
            </div>
          </div>
          <div class="flex justify-end py-3">
            <button class="p-2 rounded-md hover:bg-[#d9d9d9] border border-gray-500" @click="updatePrices">Cập nhật</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import {inject, reactive, ref} from "vue";
import { useRoute,useRouter } from 'vue-router'
import {MODULE_STORE, ROUTER_PATH} from "@/const";
import {
  getCustomerDetailFromApi,
  getListProductPriceFromApi,
  updateCustomerFormApi, updateProductAttributePriceFromApi
} from "@/api";
import {useStore} from "vuex";
import {useCurrencyInput} from "vue-currency-input";
import _ from "lodash"
import CurrencyInput from "@/views/ProductManage/EditProduct/CurrencyInput.vue";

const route = useRoute()
const router = useRouter()
const store = useStore()
const toast = inject('$toast')
const CustomerId = ref(route.params.id)
const noticePriceItems = reactive([])
const filterItems = reactive([])
const noticePriceUpdateItems = reactive([])
const { inputRef } = useCurrencyInput({currency: 'VND', currencyDisplay: 'hidden'})

const getListProductPrice = async () => {
  try {
    const res = await getListProductPriceFromApi();
    const data = res.data
    data.forEach(productPrice => {
      noticePriceItems.push({
        productAttributeValueId: productPrice.product_attribute_value_id,
        noticePriceType: productPrice.notice_price_type,
        price: formatter.format(productPrice.price),
        productId: productPrice.product_id,
        productName: productPrice.product_name,
        productCode: productPrice.product_code,
        productAttributePriceId: productPrice.product_attribute_price_id,
      })
    })
    noticePriceItems.forEach((item) => {
      filterItems.push(item)
    })
  } catch (errors) {
    toast.error(errors.message)
  }
}
const handleSelect = (item) => {
  noticePriceUpdateItems.push(item)
  const index = filterItems.indexOf(item)
  if (filterItems[index] === item) {
    filterItems.splice(index, 1)
  }
}
const handleChangePrice = (item) => {
  item.price = formatNumber(item.price)
}
const formatter = new Intl.NumberFormat("de-DE", {
  minimumFractionDigits: 0,
  maximumFractionDigits: 0,
});
const formatNumber = (n) => {
  return n.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",")
}
const handleFilter = (e) => {
  const filterArray = noticePriceItems.filter(item => item.productName.includes(e.target.value))
  filterItems.length = 0
  filterArray.forEach(item => {
    if (_.findIndex(noticePriceUpdateItems, {productId: item.productId}) === -1) {
      filterItems.push(item)
    }
  })
}
const handleRemove = (item) => {
  filterItems.push(item)
  const index = noticePriceUpdateItems.indexOf(item)
  if(noticePriceUpdateItems[index] === item) {
    noticePriceUpdateItems.splice(index, 1)
  }
}
const updatePrices = async () => {
  try {
    const res = await updateProductAttributePriceFromApi({
      product_attribute_value_price: noticePriceUpdateItems.map(item => {
        return {
          product_attribute_price_id: item.productAttributePriceId,
          product_attribute_value_id: item.productAttributeValueId,
          price: item.price,
          notice_price_type: item.noticePriceType,
          product_id: item.productId,
        }
      })
    })
    toast.success('Cập nhật giá thành công!', {duration: 3000})
    noticePriceUpdateItems.length = 0
    noticePriceItems.length = 0
    filterItems.length = 0
    await getListProductPrice()
  } catch (errors) {
    toast.error(errors.message)
  }
}

getListProductPrice()

store.state[MODULE_STORE.COMMON.NAME].breadcrumbCurrent = 'Báo giá'
store.state[MODULE_STORE.COMMON.NAME].breadcrumbItems = [
  {
    label: 'Trang chủ',
    link: '/dashboard'
  },
]
</script>

<style scoped></style>
