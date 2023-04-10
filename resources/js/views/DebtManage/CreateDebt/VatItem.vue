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
        :options="{ currency: 'VND', currencyDisplay: 'hidden' }"
      />
      <p v-if="!!priceMessageError" class="text-red-500">{{ priceMessageError }}</p>
      <div>
        <label for="" class="font-bold mb-3 text-lg text-gray-500">Ngày tạo</label>
        <Datepicker class="p-3 border border-gray-200 mt-3 outline-none" v-model="picked" :style="styleDatePicker" :locale="vi" inputFormat="dd-MM-yyyy"/>
      </div>
      <div>
        <label for="" class="font-bold mb-3 text-lg text-gray-500">Ghi chú</label>
        <textarea class="w-full h-auto border border-gray-200 min-h-[80px] outline-none text-sm" v-model="comment"></textarea>
      </div>

      <button class="submit-btn border border-gray-200 p-3 max-w-[80px]" type="submit">Submit</button>
    </form>
    <ModalConfirm
      v-model="show"
      :modal-id="modalId"
      title="Công nợ!"
      @confirm="() => createVatDebt()"
      button-value="Tạo"
    >
      <p>Tạo VAT ?</p>
    </ModalConfirm>
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
import {vi} from "date-fns/locale";
import { styleDatePicker } from "@/const";
import ModalConfirm from "@/components/Modal/Modal/ModalConfirm.vue";

const price = ref('')
const comment = ref('')
const priceMessageError = ref(null)
const picked = ref(new Date())
const router = useRouter()
const modalId = ref(null)
const show = ref(false)

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
    show.value = true
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

const createVatDebt = async () => {
  try {
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
      customer_id: props.customerId
    }
    const res = await createVatFromApi(postData)
    toast.success("Tạo công nợ VAT thành công!", {duration: 3000})
    await router.push(`${ROUTER_PATH.ADMIN}/${ROUTER_PATH.DEBT_MANAGE}/${ROUTER_PATH.LIST_CUSTOMER_DEBT}/${props.customerId}`)
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
