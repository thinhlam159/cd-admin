<template>
  <div class="p-5 mt-8 mx-5 bg-white">
    <div class="mr-4 mb-5">
      <div class="flex items-end mb-2">
        <label for="customer" class="text-base text-gray-500">Khách hàng: </label>
        <span v-show="!customerMessageError" class="text-base font-bold ml-1">{{
            currentCustomer.customer_name
          }}</span>
      </div>
    </div>
    <div class="w-[600px] p-5 border-gray-400 border rounded-md">
      <form
        @submit.prevent="handleSubmit"
      >
        <CurrencyInput
          name="price"
          type="text"
          v-model="price"
          label="Số tiền thanh toán"
          placeholder="nhập giá"
          success-message="Nice to meet you!"
          :options="{ currency: 'EUR', currencyDisplay: 'hidden' }"
        />
        <p v-if="!!priceMessageError" class="text-red-500">{{ priceMessageError }}</p>
        <div class="mt-4">
          <label for="" class="font-bold mb-1 text-lg text-gray-500">Ngày thanh toán</label>
          <Datepicker class="p-2 border border-gray-200 mt-3" v-model="picked" :style="styleDatePicker" :locale="vi"
                      inputFormat="dd/MM/yyyy"/>
        </div>
        <div class="mt-4">
          <label for="" class="font-bold mb-1 text-lg text-gray-500">Ghi chú</label>
          <textarea class="w-full h-auto border border-gray-200 min-h-[80px] outline-none text-sm"
                    v-model="comment"></textarea>
        </div>
        <button class="submit-btn border border-gray-200 p-2 text-gray-400 mt-3 font-semibold hover:bg-[#d9d9d9] hover:text-white rounded-md bg-gray-100" type="submit">Submit</button>
      </form>
    </div>
  </div>
</template>

<script setup>
import {useRoute, useRouter} from "vue-router";
import {inject, reactive, ref} from "vue";
import {
  createPaymentFromApi,
  getListCustomerFromApi,
} from "@/api";
import {MODULE_STORE, ROUTER_PATH} from "@/const";
import CurrencyInput from "@/views/DebtManage/CreateDebt/CurrencyInput.vue";
import Datepicker from 'vue3-datepicker'
import * as Yup from 'yup';
import { styleDatePicker } from "@/const";
import { vi } from "date-fns/locale"
import {useStore} from "vuex";

const router = useRouter()
const route = useRoute()
const store = useStore();
const customers = reactive([])
const selectedCustomers = reactive([])
const currentCustomer = reactive({
  customer_id: '',
  customer_name: '',
})
const customerMessageError = ref(null)
const priceMessageError = ref(null)
const price = ref(null)
const comment = ref('')
const picked = ref(new Date())
const toast = inject('$toast')

const handleSubmit = async () => {
  try {
    await schema.validate({ price: price.value, customerId: currentCustomer.customer_id });
    priceMessageError.value = ''
    const year = picked.value.getFullYear()
    const month = ('0' + (picked.value.getMonth() + 1)).slice(-2)
    const day = ('0' + picked.value.getDate()).slice(-2)
    const date = `${year}-${month}-${day}`
    const postData = {
      cost: price.value,
      comment: comment.value,
      date: date,
      monetary_unit_type: 'vnd',
      customer_id: currentCustomer.customer_id
    }
    const res = await createPaymentFromApi(postData)
    toast.success("Tạo đơn container thành công!", {duration:3000})
    await router.push(`${ROUTER_PATH.ADMIN}/${ROUTER_PATH.DEBT_MANAGE}`)
  } catch (err) {
    toast.error(err.message)
    switch (err.path) {
      case 'price':
        priceMessageError.value = err.errors[0];
        break
    }
  }
}

const getListCustomer = async () => {
  const res = await getListCustomerFromApi();
  const customers = [
    ...res.data
  ]

  const customer = customers.find(e => {
    return e.customer_id === route.params.id
  })
  currentCustomer.customer_name = customer.customer_name
  currentCustomer.customer_id = customer.customer_id
}

const handleCustomerIdError = (value) => {
  customerMessageError.value = value
}

const schema = Yup.object().shape({
  price: Yup.number().min(1000).typeError("Tối thiểu 1000đ"),
  customerId: Yup.string().required().typeError('Chọn khách hàng'),
});

getListCustomer()
store.state[MODULE_STORE.COMMON.NAME].breadcrumbCurrent = 'Tạo thanh toán'
store.state[MODULE_STORE.COMMON.NAME].breadcrumbItems = [
  {
    label: 'Trang chủ',
    link: '/dashboard'
  },
  {
    label: 'Công nợ',
    link: '/debt-manage'
  },
  {
    label: 'Công nợ khách',
    link: `/list-customer-debt/${route.params.id}`
  },
]
</script>

<style scoped>

</style>
