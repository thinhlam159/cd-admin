<template>
  <div class="p-2">
    <div class="p-3">
      <p class="text-2xl">2. Doanh thu theo khoảng thời gian</p>
      <div class="flex">
        <span>Từ ngày: </span>
        <Datepicker
          class="p-2 border border-gray-200 mt-3"
          v-model="pickedFrom"
          :upper-limit="pickedTo"
          :style="styleDatePicker"
        />
      </div>
      <div class="flex">
        <span>Đến ngày: </span>
        <Datepicker
          class="p-2 border border-gray-200 mt-3"
          v-model="pickedTo"
          :lower-limit="pickedFrom"
          :style="styleDatePicker"
        />
      </div>

      <span class="text-gray-700 text-xl">Tổng cộng: {{totalPeriodDebt.toLocaleString('it-IT', {style : 'currency', currency : 'VND'})}}</span>
    </div>
  </div>
</template>

<script setup>
import {MODULE_STORE} from "@/const";
import {getPeriodRevenuesFromApi} from "@/api";
import {inject, ref} from "vue";
import {useStore} from "vuex";
import Datepicker from 'vue3-datepicker'
import { Bar } from 'vue-chartjs'
import { Chart as ChartJS, Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale } from 'chart.js'

const toast = inject('$toast');
const store = useStore();
const pickedFrom = ref(new Date())
const pickedTo = ref(new Date())
const totalPeriodDebt = ref(0)
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

const getPeriodRevenues = async () => {
  try {
    store.state[MODULE_STORE.COMMON.NAME].isLoadingPage = true
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
    }
    const response = await getPeriodRevenuesFromApi(params)
    const data = response.data
    totalPeriodDebt.value = data.total
  } catch (errors) {
    const error = errors.message
    toast.error(error);
  } finally {
    store.state[MODULE_STORE.COMMON.NAME].isLoadingPage = false
  }
}

getPeriodRevenues()
</script>

<style scoped>

</style>
