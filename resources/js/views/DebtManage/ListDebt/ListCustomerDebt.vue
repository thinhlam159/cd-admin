<template>
  <div class="p-5 mt-8 mx-5 bg-white">
    <div>
      <div class="w-full px-3 h-auto">
        <div class="">
          <span class="text-base text-gray-500">Tên: </span>
          <span class="text-lg font-semibold">{{ customerCurrentDebt.customerName }}</span>
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
              Ngày phát sinh
            </th>
            <th class="border py-1 w-[9%]">
              Nợ phải thu
            </th>
            <th class="border py-1 w-[9%]">
              Thanh toán
            </th>
            <th class="border py-1 w-[6%]">
              Phải thu tăng
            </th>
            <th class="border py-1 w-[20%]">
              Ghi Chú
            </th>
          </tr>
          </thead>
          <tbody class="[&>*:nth-child(odd)]:bg-[#f9f9f9]">
          <template v-for="(item, index) in listDebt">
            <tr>
              <td class="border text-center">{{ parseInt(index) + 1 }}</td>
              <td class="border text-center">{{ moment(item.updated_date).format('DD-MM-YYYY') }}</td>
              <td class="border text-center">{{ item.restDebt }}</td>
              <td class="border text-center">{{ item.payment }}</td>
              <td class="border text-center">{{ item.incrementDebt }}</td>
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
      </div>
    </div>
    <div>
      <div class="flex flex-wrap w-full border border-gray-200 p-3">
        <div class="w-1/2">
          <div class="w-[90%] p-5 border border-gray-200">
            <ListCustomerPayment :customer-id="customerId" @update-list-debt="getListCustomerDebt" @update-customer-debt="getCustomerCurrentDebt(customerId)"/>
          </div>
        </div>
        <div class="w-1/2">
          <div class="w-[90%] p-5 border border-gray-200">
            <ListCustomerOrder :customer-id="customerId" @update-list-debt="getListCustomerDebt" @update-customer-debt="getCustomerCurrentDebt(customerId)"/>
          </div>
        </div>
        <div class="w-1/2">
          <div class="w-[90%] p-5 border border-gray-200">
            <ListCustomerContainerOrder :customer-id="customerId" @update-list-debt="getListCustomerDebt" @update-customer-debt="getCustomerCurrentDebt(customerId)"/>
          </div>
        </div>
        <div class="w-1/2">
          <div class="w-[90%] p-5 border border-gray-200">
            <ListCustomerVAT :customer-id="customerId" @update-list-debt="getListCustomerDebt" @update-customer-debt="getCustomerCurrentDebt(customerId)"/>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import ButtonAddNew from "@/components/Buttons/ButtonAddNew/ButtonAddNew.vue";
import {computed, inject, ref} from "vue";
import {useRoute, useRouter} from "vue-router";
import {useStore} from "vuex";
import {MODULE_STORE, PAGE_DEFAULT} from "@/const";
import {exportCustomerDebtHistoryFromApi, getCustomerCurrentDebtFromApi, getListCustomerDebtFromApi} from "@/api";
import ButtonEdit from "@/components/Buttons/ButtonEdit/ButtonEdit.vue";
import moment from "moment/moment";
import ListCustomerPayment from "@/views/DebtManage/ListDebt/ListCustomerPayment.vue";
import ListCustomerOrder from "@/views/DebtManage/ListDebt/ListCustomerOrder.vue";
import ListCustomerContainerOrder from "@/views/DebtManage/ListDebt/ListCustomerContainerOrder.vue";
import ListCustomerVAT from "@/views/DebtManage/ListDebt/ListCustomerVAT.vue";

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

const getListCustomerDebt = async (page) => {
  try {
    const res = await getListCustomerDebtFromApi(customerId.value,{page})
    listDebt.value = {
      ...res.data.map(item => {
        return {
          ...item,
          restDebt: formatNumber((item.total_debt - item.total_payment).toString()),
          payment: item.is_payment ? formatNumber(item.number_of_money.toString()) : '-',
          incrementDebt: item.is_payment ? '-' : formatNumber(item.number_of_money.toString()),
        }
      }),
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

const exportCustomerDebt = async (customerId) => {
  const excelRes = await exportCustomerDebtHistoryFromApi(customerId)
  const url = URL.createObjectURL(new Blob([excelRes], {
    type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
  }))
  const link = document.createElement('a')
  link.href = url
  link.setAttribute('download', `${customerCurrentDebt.value.customerName}-${moment().format('DDMM')}`)
  document.body.appendChild(link)
  link.click()
  link.remove()
}

const formatNumber = (n) => {
  return n.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",")
}

getListCustomerDebt(pageCurrent.value);
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
