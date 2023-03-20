<template>
  <div class="p-2 bg-gray-50">
    <div>
      <p class="text-xl">3. So luong jumbo ban ra theo khoảng thời gian</p>
      <div class="flex mt-1 justify-start items-center text-base">
        <span>Từ ngày: </span>
        <Datepicker
          class="px-2 py-1 outline-none ml-1 bg-gray-50 text-base"
          v-model="pickedFrom"
          :upper-limit="pickedTo"
          :style="styleDatePicker"
        />
      </div>
      <div class="flex mt-1 justify-start items-center text-base">
        <span>Đến ngày: </span>
        <Datepicker
          class="px-2 py-1 outline-none ml-1 bg-gray-50 text-base"
          v-model="pickedTo"
          :lower-limit="pickedFrom"
          :style="styleDatePicker"
        />
      </div>
      <div class="mt-5">
        <DP
          locale="vi"
          v-model.range="range"
          @dayclick="onDateSelected"
        />
      </div>
      <p class="text-gray-700 text-lg mt-2">Tổng cộng: {{ totalRoll }} cây</p>
    </div>
  </div>
</template>

<script setup>
import {MODULE_STORE} from "@/const";
import { getProductSaleStatisticalFromApi } from "@/api";
import {inject, ref, watch} from "vue";
import {useStore} from "vuex";
import Datepicker from 'vue3-datepicker'
import { Chart as ChartJS, Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale } from 'chart.js'
import { styleDatePicker } from '@/const'
import { DatePicker as DP } from 'v-calendar';

const toast = inject('$toast');
const store = useStore();
const pickedFrom = ref(new Date())
const pickedTo = ref(new Date())
const isLoaded = ref(false)
const totalRoll = ref(0)
const chartData = ref({
  labels: [],
  datasets: [ { data: [] } ]
})
const chartOptions = ref({
  responsive: true
})
const range = ref({
  start: pickedFrom,
  end: pickedTo,
});

ChartJS.register(Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale)

const getProductSaleStatistical = async () => {
  try {
    store.state[MODULE_STORE.COMMON.NAME].isLoadingPage = true
    isLoaded.value = false
    totalRoll.value = 0
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
      category_id: '01GF2WV4414C8MYFAGPB4BQS2R'
    }
    const response = await getProductSaleStatisticalFromApi(params)
    const data = response.data
    const parameterData = []
    data.forEach(item => {
      chartData.value.labels.push(item.name)
      parameterData.push(item.count)
      totalRoll.value += item.count
    })
    chartData.value.datasets[0].data = [...parameterData]
    isLoaded.value = true
  } catch (errors) {
    const error = errors.message
    toast.error(error)
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
  getProductSaleStatistical()
})
watch(pickedTo, getProductSaleStatistical)

getProductSaleStatistical()
</script>

<style scoped>

</style>
