<template>
  <div class="w-full h-full relative">
    <div class="w-full pt-14 h-full absolute px-10">
      <div class="w-full py-6 py-auto text-xl">
        <span class="text-gray-500">Thêm sản phẩm</span>
        <hr>
      </div>
      <div class="mr-4 w-[14%] mb-5">
        <label for="customer" class="block mb-1 font-bold text-sm">Khách hàng</label>
        <select name="customer" class="p-3 w-full" v-model="formData.customerId" @change="handleSelectCustomer">
          <option v-for="item in customers" :value="item.customer_id"
                  class="w-full h-10 px-3 text-base text-gray-700">{{ item.customer_name }}
          </option>
        </select>
        <p v-if="!!customerMessageError" class="text-red-500">{{ customerMessageError }}</p>
      </div>
      <div class="w-1/2 h-full p-5">
        <form
          @submit.prevent="handleSubmit"
        >
          <CurrencyInput
            name="price"
            type="text"
            v-model="price"
            label="Giá thành container"
            placeholder="nhập giá"
            success-message="Nice to meet you!"
            :options="{ currency: 'EUR', currencyDisplay: 'hidden' }"
          />
          <p v-if="!!priceMessageError" class="text-red-500">{{ priceMessageError }}</p>
          <div>
            <label for="" class="font-bold mb-3 text-lg text-gray-500">Ngày bán</label>
            <Datepicker class="p-2 border border-gray-200 mt-3" v-model="picked" :style="styleDatePicker" />
          </div>
          <div>
            <label for="" class="font-bold mb-3 text-lg text-gray-500">Ghi chú</label>
            <textarea class="w-full h-auto border border-gray-200 min-h-[80px] outline-none text-sm" v-model="comment"></textarea>
          </div>

          <button class="submit-btn border border-gray-200 p-3 max-w-[80px]" type="submit">Submit</button>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
import {useRouter} from "vue-router";
import {useStore} from "vuex";
import {inject, ref} from "vue";
import {
  createPaymentFromApi,
  getListCustomerFromApi,
} from "@/api";
import {MODULE_STORE, ROUTER_PATH} from "@/const";
import InputItem from "@/views/OrderManage/CreateOrder/InputItem";
import ButtonAddNew from "@/components/Buttons/ButtonAddNew";
import CurrencyInput from "@/views/DebtManage/CreateDebt/CurrencyInput.vue";
import Datepicker from 'vue3-datepicker'
import * as Yup from 'yup';

export default {
  name: "CreateDebt",
  components: {CurrencyInput, InputItem, ButtonAddNew, Datepicker },
  setup(emit) {
    const router = useRouter()
    const store = useStore()
    const formData = ref({})
    const customers = ref({})
    const customerMessageError = ref(null)
    const priceMessageError = ref(null)
    const price = ref(null)
    const comment = ref(null)
    const picked = ref(new Date())
    const toast = inject('$toast')
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

    const handleSubmit = async (data) => {
      try {
        await schema.validate({ price: price.value, customerId: formData.value.customerId });
        priceMessageError.value = ''
        const postData = {
          cost: price.value,
          comment: comment.value,
          date: picked.value.getTime() / 1000 | 0,
          monetary_unit_type: 'vnd',
          customer_id: formData.value.customerId
        }
        const res = await createPaymentFromApi(postData)
        toast.success("Tạo đơn container thành công!", {duration:3000})
        router.push(`${ROUTER_PATH.ADMIN}/${ROUTER_PATH.DEBT_MANAGE}`)
      } catch (err) {
        switch (err.path) {
          case 'price':
            priceMessageError.value = err.errors[0];
            break
          case 'customerId':
            emit('customerIdError', err.errors[0])
            break
        }
      }
    }

    const getListCustomer = async () => {
      const res = await getListCustomerFromApi();
      customers.value = {
        ...res.data
      }
      store.state[MODULE_STORE.ORDER.NAME].customers = res.data
    }
    const handleOnChangeCategorySelect = () => {
      productsByCategory.value = products.value.filter((product) => {
        return product.category_id === formData.value.category
      })
    }

    const handleCustomerIdError = (value) => {
      customerMessageError.value = value
    }

    const handleSelectCustomer = () => {
      customerMessageError.value = false
    }

    const schema = Yup.object().shape({
      price: Yup.number().min(1000).typeError("Tối thiểu 1000đ"),
      customerId: Yup.string().required().typeError('Chọn khách hàng'),
    });

    getListCustomer()

    return {
      formData,
      customers,
      customerMessageError,
      priceMessageError,
      price,
      styleDatePicker,
      picked,
      handleSubmit,
      handleOnChangeCategorySelect,
      handleCustomerIdError,
      handleSelectCustomer
    }
  }
}
</script>

<style scoped>

</style>
