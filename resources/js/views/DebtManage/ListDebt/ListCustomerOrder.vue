<template>
  <div>
    <div class="mb-3">
      <span class="text-base font-semibold text-gray-500">Lịch sử đơn hàng</span>
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
        <th class="border py-1 w-[10%]">

        </th>
      </tr>
      </thead>
      <tbody>
      <template v-for="(item, index) in listOrder" :key="index">
        <tr>
          <td class="border text-center">{{ (pagination.current_page - 1) * pagination.per_page + (parseInt(index) + 1) }}</td>
          <td class="border text-center">{{ item.total_cost.toLocaleString('it-IT', {style : 'currency', currency : 'VND'}) }}</td>
          <td class="border text-center">{{ moment(item.order_date).format('DD-MM-yyyy') }}</td>
          <td class="border text-center">{{ item.comment }}</td>
          <td class="border text-center">
            <div class="flex justify-center">
              <ButtonRemove @clickBtn="() => handleCancelOrder(item.order_id)" text="Xóa"/>
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
    <ModalConfirm
      v-model="show"
      :modal-id="modalId"
      title="Xóa đơn!"
      @confirm="() => confirmCancel(modalId)"
      button-value="Xóa"
    >
      <p>Bạn muốn xóa đơn hàng</p>
    </ModalConfirm>
  </div>
</template>

<script setup>
import Pagination from "@/components/Pagination/Pagination.vue"
import {computed, inject, reactive, ref} from "vue"
import {MODULE_STORE, PAGE_DEFAULT} from "@/const"
import {useRoute, useRouter} from "vue-router"
import {useStore} from "vuex"
import {
  cancelOrderFromApi,
  getListCustomerOrderFromApi,
} from "@/api"
import moment from "moment/moment";
import ButtonRemove from "@/components/Buttons/ButtonRemove/ButtonRemove.vue";
import ModalConfirm from "@/components/Modal/Modal/ModalConfirm.vue";

const route = useRoute()
const router = useRouter()
const store = useStore()
const toast = inject('$toast')
const pagination = ref(null)
const modalId = ref(null)
const show = ref(false)
const pageCurrent = computed(() => {
  if (!route.query.page) {
    return PAGE_DEFAULT
  }
  return Number(route.query.page)
});
const listOrder = reactive([])
const props = defineProps({
  customerId : String,
})
const emit = defineEmits(['updateListDebt', 'updateCustomerDebt'])

const getListCustomerOrder = async (page) => {
  try {
    const res = await getListCustomerOrderFromApi({customer_id: props.customerId, page})
    pagination.value = res.pagination
    listOrder.length = 0
    res.data.forEach(item => {
      listOrder.push(item)
    })
  } catch (errors) {
    const error = errors.message
    toast.error(error);
  } finally {
    store.state[MODULE_STORE.COMMON.NAME].isLoadingPage = false
  }
}

const handleBackPage = (page) => {
  getListCustomerOrder(page)
}
const handleNextPage = (page) => {
  getListCustomerOrder(page)
}
const handleCancelOrder = async (orderId) => {
  show.value = true
  modalId.value = orderId
}

const confirmCancel = async (orderId) => {
  try {
    const res = await cancelOrderFromApi(orderId)
    listOrder.length = 0
    await getListCustomerOrder(pageCurrent.value)
    show.value = false
  } catch (errors) {
    const error = errors.message
    toast.error(error);
  } finally {
    store.state[MODULE_STORE.COMMON.NAME].isLoadingPage = false
  }
}

getListCustomerOrder()
</script>

<style scoped>

</style>
