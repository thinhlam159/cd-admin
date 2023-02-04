<template>
  <h2>Dashboard</h2>
  <hr>
  <div class="p-2">
    <div class="p-3">
      <p class="text-2xl">1. Doanh thu trong ngày Jumbo + hàng thành phẩm</p>
      <span>Chọn ngày: </span>
      <Datepicker class="p-2 border border-gray-200 mt-3" v-model="picked" :style="styleDatePicker" />
      <span class="text-gray-700 text-xl mt-2">Tổng cộng: {{totalDebtByDay.toLocaleString('it-IT', {style : 'currency', currency : 'VND'})}}</span>
    </div>
<!--    <div class="p-3 mt-2 w-1/2">-->
<!--      <Bar-->
<!--        id="my-chart-id"-->
<!--        :options="chartOptions"-->
<!--        :data="chartData"-->
<!--      />-->
<!--    </div>-->
  </div>
  <div class="pt-2">
    <PeriodRevenue />
  </div>
  <div class="pt-2">
    <StatisticalJumbo />
  </div>
  <div class="pt-2">
    <CustomerOrderAmount />
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

    // customerCurrentDebt.value = {
    //   ...data,
    //   customerName: data.customer_name,
    //   totalDebt: data.total_debt,
    //   restDebt: data.rest_debt
    // }

  } catch (errors) {
    const error = errors.message
    toast.error(error);
  } finally {
    store.state[MODULE_STORE.COMMON.NAME].isLoadingPage = false
  }
}

watch(picked, getRevenueByDay)

getRevenueByDay()
</script>

<style scoped>

</style>
