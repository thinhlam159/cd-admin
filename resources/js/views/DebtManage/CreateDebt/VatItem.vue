<template>
  <div class="w-full p-5">
    <form
      @submit.prevent="handleSubmit"
    >
      <CurrencyInput
        name="price"
        type="text"
        v-model="price"
        label="Số tiền VAT"
        placeholder="Số tiền"
        success-message="Nice to meet you!"
        :options="{ currency: 'EUR', currencyDisplay: 'hidden' }"
      />
      <p v-if="!!priceMessageError" class="text-red-500">{{ priceMessageError }}</p>
      <div>
        <label for="" class="font-bold mb-3 text-lg text-gray-500">Ngày tạo</label>
        <Datepicker class="p-2 border border-gray-200 mt-3" v-model="picked" :style="styleDatePicker" />
      </div>
      <div>
        <label for="" class="font-bold mb-3 text-lg text-gray-500">Ghi chú</label>
        <textarea class="w-full h-auto border border-gray-200 min-h-[80px] outline-none text-sm" v-model="comment"></textarea>
      </div>

      <button class="submit-btn border border-gray-200 p-3 max-w-[80px]" type="submit">Submit</button>
    </form>
  </div>
</template>

<script setup>
import {inject, ref} from 'vue';
import * as Yup from 'yup';
import CurrencyInput from "@/views/DebtManage/CreateDebt/CurrencyInput.vue";
import Datepicker from 'vue3-datepicker'
import { createVatFromApi } from "@/api";
import {ROUTER_PATH} from "@/const";
import { useRouter } from 'vue-router';

const price = ref('')
const comment = ref('')
const priceMessageError = ref(null)
const picked = ref(new Date())
const router = useRouter()
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

const schema = Yup.object().shape({
  price: Yup.number().min(1000).typeError("Tối thiểu 1000đ"),
  customerId: Yup.string().required().typeError('Chọn khách hàng'),
});

const props = defineProps({
  customerId: {
    type: String,
    default: null,
  },
})

const emit = defineEmits(['customerIdError'])
const toast = inject('$toast')

async function handleSubmit() {
  try {
    await schema.validate({ price: price.value, customerId: props.customerId });
    priceMessageError.value = ''
    const postData = {
      cost: price.value,
      comment: comment.value,
      date: picked.value.getTime() / 1000 | 0,
      monetary_unit_type: 'vnd',
      customer_id: props.customerId
    }
    const res = await createVatFromApi(postData)
    console.log(postData)
    toast.success("Tạo công nợ VAT thành công!", {duration:3000})
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

function onInvalidSubmit() {
  const submitBtn = document.querySelector('.submit-btn');
  submitBtn.classList.add('invalid');
  setTimeout(() => {
    submitBtn.classList.remove('invalid');
  }, 1000);
}

</script>

<style scoped>
.submit-btn {
  background: #1DA1F2;
  outline: none;
  border: none;
  color: #fff;
  font-size: 18px;
  padding: 10px 15px;
  display: block;
  width: 100%;
  border-radius: 7px;
  margin-top: 40px;
  transition: transform 0.3s ease-in-out;
  cursor: pointer;
}

.submit-btn.invalid {
  animation: shake 0.5s;
  background: #9ca3af;
  /* When the animation is finished, start again */
  animation-iteration-count: infinite;
}
</style>
