<template>
  <div class="p-5">
    <div class="w-full h-8 flex justify-between">
      <div class="ml-2">
        <ButtonAddNew @clickBtn="goToCreateDebt" :text="addNewDebt"/>
      </div>
      <div class="ml-2">
        <ButtonAddNew @clickBtn="goToCreatePaymnet" :text="addNewPayment"/>
      </div>
    </div>
    <div class="w-full px-3 h-auto">
      <div class="">
        <span class="text-lg text-gray-400">Khách hàng: </span>
        <span class="text-2xl">{{ customerName }}</span>
      </div>
      <div class="">
        <span class="text-lg text-gray-400">Tổng công nợ: </span>
        <span class="text-2xl">{{ customerName }}</span>
      </div>
      <div class="">
        <span class="text-lg text-gray-400">Nợ phải thu: </span>
        <span class="text-2xl">{{ customerName }}</span>
      </div>
    </div>
    <!-- *********** -->
    <div class="mt-4">
      <table class="w-full">
        <thead>
        <tr class="">
          <th class="border py-1 w-[2%]">
            STT
          </th>
          <th class="border py-1 w-[9%]">
            Tổng công nợ
          </th>
          <th class="border py-1 w-[9%]">
            Tổng thanh toán
          </th>
          <th class="border py-1 w-[9%]">
            Nợ phải thu
          </th>
          <th class="border py-1 w-[9%]">
            Ngày tạo
          </th>
          <th class="border py-1 w-[9%]">
            Thay đổi
          </th>
          <th class="border py-1 w-[9%]">
            Thanh toán
          </th>
          <th class="border py-1 w-[9%]">
            Đơn thông thường
          </th>
          <th class="border py-1 w-[9%]">
            Container
          </th>
          <th class="border py-1 w-[9%]">
            Vat
          </th>
          <th class="border py-1 w-[9%]">
            Khác
          </th>
          <th class="border py-1 w-[9%]">
            Ghi Chú
          </th>
        </tr>
        </thead>
        <tbody>
        <template v-for="(item, index) in listDebt">
          <tr>
            <td class="border text-center">{{ ++index }}</td>
            <td class="border text-center">{{ item.total_debt.toLocaleString('it-IT', {style : 'currency', currency : 'VND'}) }}</td>
            <td class="border text-center">{{ item.total_payment.toLocaleString('it-IT', {style : 'currency', currency : 'VND'}) }}</td>
            <td class="border text-center">{{ (item.total_debt - item.total_payment).toLocaleString('it-IT', {style : 'currency', currency : 'VND'}) }}</td>
            <td class="border text-center">{{ convertDateByTimestamp(item.updated_date) }}</td>
            <td class="border text-center">{{ item.number_of_money.toLocaleString('it-IT', {style : 'currency', currency : 'VND'}) }}</td>
            <td class="border text-center">
              <div class="flex w-full h-full items-center justify-center" v-show="!!item.payment_id">
                <div class="w-[5px] h-[5px] border border-[#000] rounded-full"></div>
              </div>
            </td>
            <td class="border text-center">
              <div class="flex w-full h-full items-center justify-center" v-show="!!item.order_id">
                <div class="w-[5px] h-[5px] border border-[#000] rounded-full"></div>
              </div>
            </td>
            <td class="border text-center">
              <div class="flex w-full h-full items-center justify-center" v-show="!!item.container_order_id">
                <div class="w-[5px] h-[5px] border border-[#000] rounded-full"></div>
              </div>
            </td>
            <td class="border text-center">
              <div class="flex w-full h-full items-center justify-center" v-show="!!item.vat_id">
                <div class="w-[5px] h-[5px] border border-[#000] rounded-full"></div>
              </div>
            </td>
            <td class="border text-center">
              <div class="flex w-full h-full items-center justify-center" v-show="!!item.other_debt_id">
                <div class="w-[5px] h-[5px] border border-[#000] rounded-full"></div>
              </div>
            </td>
            <td class="border text-center">{{ item.comment }}</td>
          </tr>
        </template>
        </tbody>
      </table>
    </div>

    <Pagination
      v-if="pagination"
      :pageCurrent="pagination.current_page"
      :totalPage="pagination.total"
      @onBack="handleBackPage"
      @onNext="handleNextPage"
    />
  </div>
</template>

<script setup>
import ButtonAddNew from "@/components/Buttons/ButtonAddNew/ButtonAddNew.vue";
import Pagination from "@/components/Pagination/Pagination.vue";
import {computed, inject, ref} from "vue";
import {useRoute, useRouter} from "vue-router";
import {useStore} from "vuex";
import {MODULE_STORE, PAGE_DEFAULT, ROUTER_PATH} from "@/const";
import {
  getCustomerDetailFromApi,
  getListCustomerDebtFromApi
} from "@/api";
import { convertDateByTimestamp } from '@/utils'

const listDebt = ref([]);
const route = useRoute();
const router = useRouter();
const store = useStore();
const pagination = ref(null);
const toast = inject('$toast');
const addNewDebt = "Thêm công nợ";
const orderDetail = "Chi tiết";
const exportExcel = "Xuất excel";
const addNewPayment = "Tạo thanh toán";
const customerId = ref(route.params.id)
const customerName = ref('')

const pageCurrent = computed(() => {
  if (!route.query.page) {
    return PAGE_DEFAULT;
  }
  return Number(route.query.page);
});

const getListCustomerDebt = async (customerId, page) => {
  try {
    const res = await getListCustomerDebtFromApi(customerId,{page})
    pagination.value = res.pagination
    listDebt.value = {
      ...res.data
    }
  } catch (errors) {
    const error = errors.message
    toast.error(error);
  } finally {
    store.state[MODULE_STORE.COMMON.NAME].isLoadingPage = false
  }
}

const getCustomerDetail = async (customerId) => {
  try {
    store.state[MODULE_STORE.COMMON.NAME].isLoadingPage = true;
    const response = await getCustomerDetailFromApi(customerId);
    customerName.value = response.customer_name
    console.log(response)
  } catch (errors) {
    const error = errors.message
    toast.error(error);
  } finally {
    store.state[MODULE_STORE.COMMON.NAME].isLoadingPage = false;
  }
}

const handleBackPage = (page) => {
  getListCustomerDebt({page})
}
const handleNextPage = (page) => {
  getListCustomerDebt({page})
}

getListCustomerDebt(customerId.value, pageCurrent.value);
getCustomerDetail(customerId.value);

</script>

<style scoped>

</style>
