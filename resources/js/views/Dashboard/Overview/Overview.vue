<template>
  <div class="bg-white px-5">
    <div class="p-3 text-2xl bg-gray-50">
      <p>Thống kê</p>
    </div>
    <div class="p-2 mt-2 bg-gray-50">
      <p class="text-xl">1. Doanh thu trong ngày Jumbo + hàng thành phẩm</p>
      <div class="mt-1 flex justify-start items-end">
        <span>Chọn ngày: </span>
        <Datepicker class="px-2 py-1 border border-gray-200 ml-1" v-model="picked" :style="styleDatePicker"/>
      </div>
      <p class="text-gray-700 text-lg mt-2">Tổng cộng: {{
          totalDebtByDay.toLocaleString('it-IT', {
            style: 'currency',
            currency: 'VND'
          })
        }}</p>
    </div>
    <div class="pt-2 mt-2">
      <PeriodRevenue />
    </div>
    <div class="pt-2 mt-2">
      <StatisticalJumbo />
    </div>
    <div class="pt-2 mt-2">
      <CustomerOrderAmount />
    </div>
  </div>
</template>

<script setup>
import {MODULE_STORE} from "@/const";
import {getRevenuesFromApi} from "@/api";
import {inject, ref, watch} from "vue";
import {useStore} from "vuex";
import {BarElement, CategoryScale, Chart as ChartJS, Legend, LinearScale, Title, Tooltip} from 'chart.js'
import Datepicker from 'vue3-datepicker'
import PeriodRevenue from "@/views/Dashboard/Overview/PeriodRevenue.vue";
import StatisticalJumbo from "@/views/Dashboard/Overview/StatisticalJumbo.vue";
import CustomerOrderAmount from "@/views/Dashboard/Overview/CustomerOrderAmount.vue";
import { styleDatePicker } from "@/const";

const toast = inject('$toast');
const store = useStore();
const chartData = ref({
  labels: [ 'January', 'February', 'March' ],
  datasets: [ { data: [40, 20, 12] } ]
})
const chartOptions = ref({
  responsive: true
})
const picked = ref(new Date())
const totalDebtByDay = ref(0)

ChartJS.register(Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale)

const getRevenueByDay = async () => {
  try {
    store.state[MODULE_STORE.COMMON.NAME].isLoadingPage = true
    const year = picked.value.getFullYear()
    const month = ('0' + (picked.value.getMonth() + 1)).slice(-2)
    const day = ('0' + picked.value.getDate()).slice(-2)
    const date = `${year}-${month}-${day}`
    const res = await getRevenuesFromApi({date: date})
    const data = res.data
    totalDebtByDay.value = res.total
  } catch (errors) {
    const error = errors.message
    toast.error(error);
  } finally {
    store.state[MODULE_STORE.COMMON.NAME].isLoadingPage = false
  }
}

watch(picked, getRevenueByDay)

getRevenueByDay()
store.state[MODULE_STORE.COMMON.NAME].breadcrumbCurrent = 'Trang chủ'
store.state[MODULE_STORE.COMMON.NAME].breadcrumbItems = []
</script>

<style scoped>

</style>
