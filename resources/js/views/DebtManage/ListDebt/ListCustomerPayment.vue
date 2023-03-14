<template>
  <div>
    <div class="mb-3">
      <span class="text-base font-semibold text-gray-500">Lịch sử thanh toán</span>
    </div>
    <table>
      <thead>
      <tr class="">
        <th class="border py-1 w-[4%]">
          STT
        </th>
        <th class="border py-1 w-[20%]">
          Số tiền
        </th>
        <th class="border py-1 w-[20%]">
          Ngày
        </th>
        <th class="border py-1 w-[26%]">
          Ghi chú
        </th>
        <th class="border py-1 w-[20%]">
          Trạng thái
        </th>
        <th class="border py-1 w-[10%]">

        </th>
      </tr>
      </thead>
      <tbody>
      <template v-for="(item, index) in listPayment" :key="index">
        <tr>
          <td class="border text-center">{{ (pagination.current_page - 1) * pagination.per_page + (parseInt(index) + 1) }}</td>
          <td class="border text-center">{{ item.cost.toLocaleString('it-IT', {style : 'currency', currency : 'VND'}) }}</td>
          <td class="border text-center">{{ moment(item.date).format('DD-MM-yyyy') }}</td>
          <td class="border text-center">{{ item.comment }}</td>
          <td class="border text-center">
            <div class="flex justify-center">
              <span v-if="item.payment_status === 'resolved'">Hoàn thành</span>
              <ButtonEdit v-if="item.payment_status === 'in_progress'" @clickBtn="() => handleUpdateResolvedPayment(item.payment_id)" text="Chưa hoàn thành"/>
            </div>
          </td>
          <td class="border text-center">
            <div class="flex justify-center">
              <span v-if="item.payment_status === 'resolved'"></span>
              <ButtonRemove v-if="item.payment_status ==='in_progress'" @clickBtn="() => handleCancelPayment(item.payment_id)" text="Xóa"/>
            </div>
          </td>
        </tr>
      </template>
      </tbody>
    </table>
    <Pagination
      v-if="pagination"
      :pageCurrent="pagination.current_page"
      :totalPage="pagination.total_page"
      @onBack="handleBackPage"
      @onNext="handleNextPage"
    />
  </div>
</template>

<script setup>
import Pagination from "@/components/Pagination/Pagination.vue"
import {computed, inject, reactive, ref} from "vue"
import {MODULE_STORE, PAGE_DEFAULT} from "@/const"
import {useRoute, useRouter} from "vue-router"
import {useStore} from "vuex"
import {cancelPaymentFromApi, getListCustomerPaymentFromApi, updateResolvedPaymentFromApi} from "@/api"
import moment from "moment/moment";
import ButtonEdit from "@/components/Buttons/ButtonEdit/ButtonEdit.vue";
import ButtonRemove from "@/components/Buttons/ButtonRemove/ButtonRemove.vue";

const route = useRoute()
const router = useRouter()
const store = useStore()
const toast = inject('$toast')
const pagination = ref(null)
const pageCurrent = computed(() => {
  if (!route.query.page) {
    return PAGE_DEFAULT
  }
  return Number(route.query.page)
});
const listPayment = reactive([])
const props = defineProps({
  customerId : String,
})
const emit = defineEmits(['updateListDebt', 'updateCustomerDebt'])

const getListCustomerPayment = async (page) => {
  try {
    const res = await getListCustomerPaymentFromApi({customer_id: props.customerId, page})
    pagination.value = res.pagination
    listPayment.length = 0
    res.data.forEach(item => {
      listPayment.push(item)
    })
  } catch (errors) {
    const error = errors.message
    toast.error(error);
  } finally {
    store.state[MODULE_STORE.COMMON.NAME].isLoadingPage = false
  }
}

const handleBackPage = (page) => {
  getListCustomerPayment(page)
}
const handleNextPage = (page) => {
  getListCustomerPayment(page)
}
const handleCancelPayment = async (id) => {
  try {
    const res = await cancelPaymentFromApi({payment_id: id})
    listPayment.length = 0
    await getListCustomerPayment(pageCurrent.value)
  } catch (errors) {
    const error = errors.message
    toast.error(error);
  } finally {
    store.state[MODULE_STORE.COMMON.NAME].isLoadingPage = false
  }
}
const handleUpdateResolvedPayment = async (id) => {
  try {
    const res = await updateResolvedPaymentFromApi({payment_id: id})
    emit('updateListDebt')
    emit('updateCustomerDebt')
    listPayment.length = 0
    await getListCustomerPayment(pageCurrent.value)
  } catch (errors) {
    const error = errors.message
    toast.error(error);
  } finally {
    store.state[MODULE_STORE.COMMON.NAME].isLoadingPage = false
  }
}

getListCustomerPayment()
</script>

<style scoped>

</style>
