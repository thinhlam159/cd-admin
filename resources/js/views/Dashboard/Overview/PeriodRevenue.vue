<template>
  <div class="p-2 bg-gray-50">
    <p class="text-xl">2. Doanh thu theo khoảng thời gian</p>
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
    <p class="text-gray-700 text-lg mt-2">
      Tổng cộng:
      <span class="font-semibold text-gray-500">
        {{
          totalPeriodDebt.toLocaleString('it-IT', {
            style: 'currency',
            currency: 'VND'
          })
        }}
      </span>
    </p>
  </div>
</template>

<script setup>
import {MODULE_STORE} from "@/const";
import {getPeriodRevenuesFromApi} from "@/api";
import {inject, ref, watch} from "vue";
import {useStore} from "vuex";
import Datepicker from 'vue3-datepicker'
import { Chart as ChartJS, Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale } from 'chart.js'
import { DatePicker as DP } from 'v-calendar';
import { styleDatePicker } from "@/const";

const toast = inject('$toast');
const store = useStore();
const pickedFrom = ref(new Date())
const pickedTo = ref(new Date())
const totalPeriodDebt = ref(0)
const range = ref({
  start: pickedFrom,
  end: pickedTo,
});

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

const onDateSelected = () => {
  const startDate = range.value.start;
  const endDate = range.value.end;
  pickedFrom.value = new Date(startDate)
  pickedTo.value = new Date(endDate)
}

getPeriodRevenues()
watch(pickedTo, () => {
  range.value = {
    start: pickedFrom.value,
    end: pickedTo.value
  }
  getPeriodRevenues()
})
</script>

<style scoped>

</style>
