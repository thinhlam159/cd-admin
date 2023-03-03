<template>
  <div class="p-5 mt-8 mx-5 bg-white">
    <div class="w-full px-3 h-auto">
      <div class="">
        <span class="text-base text-gray-500">Tên: </span>
        <span class="text-base italic">{{ customerCurrentDebt.customerName }}</span>
      </div>
      <div class="">
        <span class="text-base text-gray-500">Tổng công nợ: </span>
        <span class="text-base">{{ customerCurrentDebt.totalDebt?.toLocaleString('it-IT', {style : 'currency', currency : 'VND'}) }}</span>
      </div>
      <div class="">
        <span class="text-base text-gray-500">Nợ phải thu: </span>
        <span class="text-base">{{ customerCurrentDebt.restDebt?.toLocaleString('it-IT', {style : 'currency', currency : 'VND'}) }}</span>
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
            <td class="border text-center">{{ (pagination.current_page - 1) * pagination.per_page + (parseInt(index) + 1) }}</td>
            <td class="border text-center">{{ item.total_debt.toLocaleString('it-IT', {style : 'currency', currency : 'VND'}) }}</td>
            <td class="border text-center">{{ item.total_payment.toLocaleString('it-IT', {style : 'currency', currency : 'VND'}) }}</td>
            <td class="border text-center">{{ (item.total_debt - item.total_payment).toLocaleString('it-IT', {style : 'currency', currency : 'VND'}) }}</td>
            <td class="border text-center">{{ moment(item.updated_date).format('DD-MM-YYYY') }}</td>
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
        :totalPage="pagination.total_page"
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
import {MODULE_STORE, PAGE_DEFAULT} from "@/const";
import {
  exportCustomerDebtHistoryFromApi,
  getCustomerCurrentDebtFromApi,
  getListCustomerDebtFromApi
} from "@/api";
import ButtonEdit from "@/components/Buttons/ButtonEdit/ButtonEdit.vue";
import moment from "moment/moment";

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
  const excelRes = await exportCustomerDebtHistoryFromApi(customerId)
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

store.state[MODULE_STORE.COMMON.NAME].breadcrumbCurrent = 'Công nợ khách'
store.state[MODULE_STORE.COMMON.NAME].breadcrumbItems = [
  {
    label: 'Trang chủ',
    link: '/dashboard'
  },
  {
    label: 'Công nợ',
    link: '/debt-manage'
  },
]

</script>

<style scoped>

</style>
