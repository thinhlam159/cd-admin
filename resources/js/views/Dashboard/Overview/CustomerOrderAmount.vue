<template>
  <div class="p-2 bg-gray-50">
    <div>
      <p class="text-xl">4. Số lượng đơn hàng của khách khoảng thời gian</p>
      <div class="flex mt-1 justify-start items-center text-base">
        <span>Từ ngày: </span>
        <Datepicker
          class="px-2 py-1 outline-none ml-1 bg-gray-50 text-base"
          v-model="pickedFrom"
          :upper-limit="pickedTo"
          :style="styleDatePicker"
          inputFormat="dd-MM-yyyy"
        />
      </div>
      <div class="flex mt-1 justify-start items-center text-base">
        <span>Đến ngày: </span>
        <Datepicker
          class="px-2 py-1 outline-none ml-1 bg-gray-50 text-base"
          v-model="pickedTo"
          :lower-limit="pickedFrom"
          :style="styleDatePicker"
          inputFormat="dd-MM-yyyy"
        />
      </div>
      <div class="mt-5">
        <DP
          locale="vi"
          v-model.range="range"
          @dayclick="onDateSelected"
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
import { DatePicker as DP } from 'v-calendar';
import { styleDatePicker } from '@/const'
import moment from "moment";

const toast = inject('$toast');
const store = useStore();
const keyword = ref('')
const pickedFrom = ref(new Date(moment().startOf('month').format('YYYY-MM-DD')))
const pickedTo = ref(new Date())
const isLoaded = ref(false)
const chartData = ref({
  labels: [],
  datasets: [ {
    label: 'Số lượng đơn',
    data: []
  } ]
})
const chartOptions = ref({
  responsive: true
})
const totalOrder = ref(0)
const tableData = ref([])
const range = ref({
  start: pickedFrom,
  end: pickedTo,
});

ChartJS.register(Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale)

const getCountCustomerOrders = async (customerId) => {
  try {
    store.state[MODULE_STORE.COMMON.NAME].isLoadingPage = true
    isLoaded.value = false
    totalOrder.value = 0
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
const onDateSelected = () => {
  const startDate = range.value.start;
  const endDate = range.value.end;
  pickedFrom.value = new Date(startDate)
  pickedTo.value = new Date(endDate)
}
watch(pickedFrom, () => {
  range.value = {
    start: pickedFrom.value,
    end: pickedTo.value
  }
  getCountCustomerOrders()
})

watch(keyword, getCountCustomerOrders)

getCountCustomerOrders()
</script>

<style scoped>

</style>
