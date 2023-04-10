<template>
  <div class="bg-white px-5">
    <div class="p-3 text-2xl bg-gray-50">
      <p>Thống kê</p>
    </div>
    <div>
      <p class="text-xl">1. Doanh thu trong ngày Jumbo + hàng thành phẩm</p>
    </div>
    <div class="p-2 mt-2 bg-gray-50 flex">
      <div class="mr-5">
        <div class="mt-1 flex justify-start items-center h-[56px]">
          <p class="text-base">Chọn ngày: </p>
          <Datepicker class="px-2 py-1 bg-gray-50 outline-none ml-1 text-base" v-model="picked"
                      :style="styleDatePicker" inputFormat="dd-MM-yyyy"/>
        </div>
        <DP v-model="picked" locale="vi"/>
      </div>
      <div>
        <div class="w-[650px]">
          <Bar
            id="my-chart-id"
            :options="chartOptions"
            :data="chartData"
            v-if="isLoaded"
          />
        </div>
        <p class="text-gray-700 text-lg mt-2">Tổng cộng ({{ `${('0' + picked.getDate()).slice(-2)}/${('0' + (picked.getMonth() + 1)).slice(-2)}` }}): {{
            totalDebtByDay.toLocaleString('it-IT', {
              style: 'currency',
              currency: 'VND'
            })
          }}</p>
      </div>
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
import { Bar } from "vue-chartjs"
import {BarElement, CategoryScale, Chart as ChartJS, Legend, LinearScale, Title, Tooltip} from 'chart.js'
import Datepicker from 'vue3-datepicker'
import PeriodRevenue from "@/views/Dashboard/Overview/PeriodRevenue.vue";
import StatisticalJumbo from "@/views/Dashboard/Overview/StatisticalJumbo.vue";
import CustomerOrderAmount from "@/views/Dashboard/Overview/CustomerOrderAmount.vue";
import { styleDatePicker } from "@/const";
import { DatePicker as DP} from 'v-calendar';
import 'v-calendar/style.css';
import moment from "moment/moment";

const toast = inject('$toast');
const store = useStore();
const chartData = ref({
  labels: ['January', 'February', 'March'],
  datasets: [{
    label: 'Doanh thu ngày',
    data: [40, 20, 12]
  }]
})
const chartOptions = ref({
  responsive: true
})
const picked = ref(new Date())
const totalDebtByDay = ref(0)
const isLoaded = ref(false)

ChartJS.register(Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale)

const getRevenueByDay = async () => {
  try {
    isLoaded.value = false
    chartData.value.datasets[0].data = []
    chartData.value.labels = []

    store.state[MODULE_STORE.COMMON.NAME].isLoadingPage = true
    const year = picked.value.getFullYear()
    const month = ('0' + (picked.value.getMonth() + 1)).slice(-2)
    const day = ('0' + picked.value.getDate()).slice(-2)
    const date = `${year}-${month}-${day}`
    const res = await getRevenuesFromApi({date: date})

    const data = res.data
    totalDebtByDay.value = data[0].total
    data.reverse().forEach(item => {
      chartData.value.labels.push(moment(item.date).format('DD/MM'))
      chartData.value.datasets[0].data.push(item.total)
    })
    console.log('doanh thu: ',chartData.value.datasets[0].data)
    isLoaded.value = true
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
