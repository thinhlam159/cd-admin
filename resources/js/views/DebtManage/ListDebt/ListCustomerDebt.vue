<template>
  <div class="p-5">
    <div class="p-5">
      <h1 class="text-2xl text-gray-700">Công nợ khách hàng</h1>
    </div>
    <div class="w-full px-3 h-auto">
      <div class="">
        <span class="text-lg text-gray-400">Khách hàng: </span>
        <span class="text-2xl">{{ customerCurrentDebt.customerName }}</span>
      </div>
      <div class="">
        <span class="text-lg text-gray-400">Tổng công nợ: </span>
        <span class="text-2xl">{{ customerCurrentDebt.totalDebt?.toLocaleString('it-IT', {style : 'currency', currency : 'VND'}) }}</span>
      </div>
      <div class="">
        <span class="text-lg text-gray-400">Nợ phải thu: </span>
        <span class="text-2xl">{{ customerCurrentDebt.restDebt?.toLocaleString('it-IT', {style : 'currency', currency : 'VND'}) }}</span>
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
          <th class="border py-1 w-[6%]">
            Thanh toán
          </th>
          <th class="border py-1 w-[9%]">
            Đơn lẻ
          </th>
          <th class="border py-1 w-[6%]">
            Container
          </th>
          <th class="border py-1 w-[4%]">
            Vat
          </th>
          <th class="border py-1 w-[5%]">
            Khác
          </th>
          <th class="border py-1 w-[20%]">
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
            <td class="border text-center">{{ item.updated_date }}</td>
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
    <div class="flex justify-center items-center p-3">
      <div class="w-full h-8 flex">
        <div class="ml-2">
          <ButtonAddNew @clickBtn="goToCreateDebt" :text="addNewDebt"/>
        </div>
        <div class="ml-2">
          <ButtonAddNew @clickBtn="goToCreatePayment" :text="addNewPayment"/>
        </div>
        <div class="ml-2">
          <ButtonEdit @clickBtn="exportCustomerDebt(customerId)" :text="exportExcel"/>
        </div>
      </div>
      <Pagination
        v-if="pagination"
        :pageCurrent="pagination.current_page"
        :totalPage="pagination.total"
        @onBack="handleBackPage"
        @onNext="handleNextPage"
      />
    </div>
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
  exportCustomerDebtHistoryFromApi, exportOrderFromApi,
  getCustomerCurrentDebtFromApi,
  getListCustomerDebtFromApi
} from "@/api";
import { convertDateByTimestamp } from '@/utils'
import ButtonEdit from "@/components/Buttons/ButtonEdit/ButtonEdit.vue";

const route = useRoute();
const router = useRouter();
const store = useStore();
const pagination = ref(null);
const toast = inject('$toast');
const listDebt = ref([]);
const addNewDebt = "Thêm công nợ";
const orderDetail = "Chi tiết";
const exportExcel = "Xuất excel";
const addNewPayment = "Tạo thanh toán";
const customerId = ref(route.params.id)
const customerCurrentDebt = ref({})

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

const getCustomerCurrentDebt = async (customerId) => {
  try {
    store.state[MODULE_STORE.COMMON.NAME].isLoadingPage = true;
    const response = await getCustomerCurrentDebtFromApi(customerId);
    const data = response.data

    customerCurrentDebt.value = {
      ...data,
      customerName: data.customer_name,
      totalDebt: data.total_debt,
      restDebt: data.rest_debt
    }

  } catch (errors) {
    const error = errors.message
    toast.error(error);
  } finally {
    store.state[MODULE_STORE.COMMON.NAME].isLoadingPage = false
  }
}

const goToCreateDebt = () => {
  router.push({name: 'CreateDebt', params: {id: customerId.value}})
}

const goToCreatePayment = () => {
  router.push({name: `CreatePayment`, params: {id: customerId.value}})
}

const handleBackPage = (page) => {
  getListCustomerDebt({page})
}
const handleNextPage = (page) => {
  getListCustomerDebt({page})
}

const exportCustomerDebt = async (customerId) => {
  const excelRes = await exportCustomerDebtHistoryFromApi({customer_id: customerId})
  const url = URL.createObjectURL(new Blob([excelRes], {
    type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
  }))
  const link = document.createElement('a')
  link.href = url
  link.setAttribute('download', 'fileName')
  document.body.appendChild(link)
  link.click()
  link.remove()
}

getListCustomerDebt(customerId.value, pageCurrent.value);
getCustomerCurrentDebt(customerId.value);

</script>

<style scoped>

</style>
