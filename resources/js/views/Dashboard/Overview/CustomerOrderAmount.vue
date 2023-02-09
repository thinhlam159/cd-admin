<template>
  <div class="p-2 bg-gray-50">
    <div>
      <p class="text-xl">4. Số lượng đơn hàng của khách khoảng thời gian</p>
      <div class="flex mt-1 justify-start items-end">
        <span>Từ ngày: </span>
        <Datepicker
          class="px-2 py-1 border border-gray-200 ml-1"
          v-model="pickedFrom"
          :upper-limit="pickedTo"
          :style="styleDatePicker"
          lang="vi"
          format="dd-MM-YYYY"
        />
      </div>
      <div class="flex mt-1 justify-start items-end">
        <span>Đến ngày: </span>
        <Datepicker
          class="px-2 py-1 border border-gray-200 ml-1"
          v-model="pickedTo"
          :lower-limit="pickedFrom"
          :style="styleDatePicker"
          lang="vi"
          format="dd-MM-YYYY"
        />
      </div>
      <div class="py-2 text-md">
        <input type="text" v-model="keyword" class="p-2 border border-gray-400 outline-none" placeholder="Tìm theo tên">
      </div>
      <p class="text-gray-700 text-lg mt-2">Tổng cộng: {{ totalOrder }} đơn</p>
    </div>
    <div class="pt-2 mt-2 w-1/2">
      <Bar
        id="my-chart-id"
        :options="chartOptions"
        :data="chartData"
        v-if="isLoaded"
      />
    </div>
    <div class="mt-2 py-2 text-md">
      <table>
        <tr>
          <th>Khách hàng</th>
          <th>Số lượng đơn hàng</th>
        </tr>
        <template v-for="(item, index) in tableData" :key="index">
          <tr>
            <td class="border text-center p-1">{{ item.name }}</td>
            <td class="border text-center p-1">{{ item.count }}</td>
          </tr>
        </template>
      </table>
    </div>
  </div>
</template>

<script setup>
import {MODULE_STORE} from "@/const";
import {getCountCustomerOrderFromApi} from "@/api";
import {inject, ref, watch} from "vue";
import {useStore} from "vuex";
import Datepicker from 'vue3-datepicker'
import { Bar } from 'vue-chartjs'
import { Chart as ChartJS, Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale } from 'chart.js'

const toast = inject('$toast');
const store = useStore();
const keyword = ref('')
const pickedFrom = ref(new Date())
const pickedTo = ref(new Date())
const isLoaded = ref(false)
const chartData = ref({
  labels: [],
  datasets: [ { data: [] } ]
})
const chartOptions = ref({
  responsive: true
})
const totalOrder = ref(0)
const tableData = ref([])
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

const getCountCustomerOrders = async (customerId) => {
  try {
    store.state[MODULE_STORE.COMMON.NAME].isLoadingPage = true
    isLoaded.value = false
    chartData.value.datasets[0].data = []
    chartData.value.labels = []
    const year = pickedFrom.value.getFullYear()
    const month = ('0' + (pickedFrom.value.getMonth() + 1)).slice(-2)
    const day = ('0' + pickedFrom.value.getDate()).slice(-2)
    const startDate = `${year}-${month}-${day}`
    const endDateYear = pickedTo.value.getFullYear()
    const endDateMonth = ('0' + (pickedTo.value.getMonth() + 1)).slice(-2)
    const endDateDay = ('0' + pickedTo.value.getDate()).slice(-2)
    const endDate = `${endDateYear}-${endDateMonth}-${endDateDay}`
    const params = {
      start_date: startDate,
      end_date: endDate,
      keyword: keyword.value
    }
    const response = await getCountCustomerOrderFromApi(params)
    const data = response.data
    const parameterData = []
    data.forEach(item => {
      chartData.value.labels.push(item.name)
      parameterData.push(item.count)
      totalOrder.value += item.count
    })
    chartData.value.datasets[0].data = [...parameterData]
    tableData.value = [...data]
    isLoaded.value = true
  } catch (errors) {
    const error = errors.message
    toast.error(error);
  } finally {
    store.state[MODULE_STORE.COMMON.NAME].isLoadingPage = false
  }
}
watch(pickedFrom, getCountCustomerOrders)
watch(pickedTo, getCountCustomerOrders)
watch(keyword, getCountCustomerOrders)

getCountCustomerOrders()
</script>

<style scoped>

</style>
