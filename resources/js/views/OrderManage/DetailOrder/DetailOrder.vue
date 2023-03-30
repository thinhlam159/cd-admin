<template>
  <div class="px-5 mt-10 min-h-[600px] bg-white">
    <div class="w-[720px]">
      <div class="w-full py-6 py-auto text-xl">
        <span class="text-gray-500">Chi tiết đơn hàng</span>
      </div>
      <div class="w-full py-1 text-sm flex justify-between">
        <span class="text-gray-900 font-semibold text-base">Tên khách hàng: {{ orderResponse.customer_name }}</span>
        <span class="font-semibold text-base">Ngày tạo đơn: {{ moment(orderResponse.update_at).format('DD/MM/yyyy') }}</span>
      </div>
      <hr>
      <div class="w-full py-4 text-sm text-center">
        <div class="w-full flex border bg-gray-100">
          <div class="py-2 border-r inline-block w-[4%]"><span class="text-xs font-semibold">#</span></div>
          <div class="py-2 border-r inline-block w-[22%]"><span class="text-xs font-semibold">Tên sản phẩm</span></div>
          <div class="py-2 border-r inline-block w-[8%]"><span class="text-xs font-semibold">ĐVT</span></div>
          <div class="py-2 border-r inline-block w-[20%]"><span class="text-xs font-semibold">Số lượng</span></div>
          <div class="py-2 border-r inline-block w-[20%]"><span class="text-xs font-semibold">Đơn giá</span></div>
          <div class="py-2 inline-block w-[22%]"><span class="text-xs font-semibold">Thành tiền</span></div>
        </div>
        <div class="[&>div:nth-child(odd)]:bg-gray-50 border">
          <div v-for="(item, index) in orderProductResponse" class="w-full py-2 flex border-b border-gray-50">
            <div class="inline-block w-[4%]"><span>{{ ++index }}</span></div>
            <div class="inline-block w-[22%]">
              <span v-if="item.notice_price_type !== 'default'">{{ item.product_name + ' ' + item.product_attribute_value_code + item.attribute_display_index }}</span>
              <span v-else>{{ item.product_name }}</span>
            </div>
            <div class="inline-block w-[8%]"><span>{{ t(`measure_unit_type.${item.measure_unit_type}`) }}</span></div>
            <div class="inline-block w-[20%]"><span>{{ item.weight }}</span></div>
            <div class="inline-block w-[20%]"><span>{{ item.price.toLocaleString('it-IT', {style : 'currency', currency : 'VND'}) }}</span></div>
            <div class="inline-block w-[22%]"><span>{{ item.cost.toLocaleString('it-IT', {style : 'currency', currency : 'VND'}) }}</span></div>
          </div>
        </div>
        <div class="flex justify-end mt-5 font-semibold text-base"><span>Tổng cộng: {{ orderResponse.total_cost?.toLocaleString('it-IT', {style : 'currency', currency : 'VND'}) }}</span></div>
      </div>
    </div>
  </div>
</template>

<script setup>
import {inject, ref} from "vue";
import { useRoute,useRouter } from 'vue-router'
import {MODULE_STORE} from "@/const";
import {getOrderDetailFromApi} from "@/api";
import {useStore} from "vuex";
import moment from "moment/moment";
import { useI18n } from "vue-i18n";

const route = useRoute()
const router = useRouter()
const store = useStore()
const {t} = useI18n()
const OrderId = ref(route.params.id)
const orderResponse = ref({})
const orderProductResponse = ref([])
const toast = inject('$toast')

const getOrderDetail = async (id) => {
  try {
    store.state[MODULE_STORE.COMMON.NAME].isLoadingPage = true;
    const response = await getOrderDetailFromApi(id);
    orderResponse.value = {
      ...response.data,
    };
    orderProductResponse.value = [...response.data.order_products]
  } catch (errors) {
    const error = errors.message || this.$t("common.has_error");
    toast.error(error);
  } finally {
    store.state[MODULE_STORE.COMMON.NAME].isLoadingPage = false;
  }
}

getOrderDetail(OrderId.value)

store.state[MODULE_STORE.COMMON.NAME].breadcrumbCurrent = 'Chi tiết đơn hàng'
store.state[MODULE_STORE.COMMON.NAME].breadcrumbItems = [
  {
    label: 'Trang chủ',
    link: '/dashboard'
  },
  {
    label: 'Đơn hàng',
    link: '/order-manage'
  },
]
</script>

<style scoped></style>
