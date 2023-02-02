<template>
  <h2>Dashboard</h2>
  <hr>
  <div class="p-2">
    <div class="p-3">
      <p class="text-2xl">2. Doanh thu theo khoảng thời gian</p>
      <span class="text-gray-700 text-xl">Tổng cộng: </span>
    </div>
    <div class="p-3 mt-2 w-1/2">
      <Bar
        id="my-chart-id"
        :options="chartOptions"
        :data="chartData"
      />
    </div>
  </div>
</template>

<script setup>
import {MODULE_STORE} from "@/const";
import {getCustomerCurrentDebtFromApi, getRevenuesFromApi} from "@/api";
import {inject, ref} from "vue";
import {useStore} from "vuex";
import { Bar } from 'vue-chartjs'
import { Chart as ChartJS, Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale } from 'chart.js'

const toast = inject('$toast');
const store = useStore();
const chartData = ref({
  labels: [ 'January', 'February', 'March' ],
  datasets: [ { data: [40, 20, 12] } ]
})
const chartOptions = ref({
  responsive: true
})

ChartJS.register(Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale)

const getRevenues = async (customerId) => {
  try {
    store.state[MODULE_STORE.COMMON.NAME].isLoadingPage = true
    const response = await getRevenuesFromApi(customerId)
    const data = response.data
    console.log(data)

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

// getRevenues()
</script>

<style scoped>

</style>
