<template>
  <!--  <FormUserManage :edit="true" />-->
  <div class="p-5 mt-8 mx-5 bg-white">
    <div class="w-full py-6 py-auto text-xl">
      <span class="text-gray-500">Chi tiết đơn hàng</span>
    </div>
    <div class="w-full py-1 text-sm flex justify-between">
      <span class="text-gray-900">Tên khách hàng: {{ orderResponse.customer_name }}</span>
      <span class="">Ngày tạo đơn: {{ orderResponse.update_at }}</span>
    </div>
    <hr>
    <div class="w-full py-4 text-sm">
      <div class="w-full py-2 flex border-b border-gray-50 text-xs">
        <div class="inline-block w-[4%]"><span>#</span></div>
        <div class="inline-block w-[22%]"><span>Tên sản phẩm</span></div>
        <div class="inline-block w-[8%]"><span>ĐVT</span></div>
        <div class="inline-block w-[20%]"><span>Số lượng</span></div>
        <div class="inline-block w-[20%]"><span>Đơn giá</span></div>
        <div class="inline-block w-[22%]"><span>Thành tiền</span></div>
      </div>
      <div v-for="(item, index) in orderProductResponse" class="w-full py-2 flex border-b border-gray-50">
        <div class="inline-block w-[4%]"><span>{{ ++index }}</span></div>
        <div class="inline-block w-[22%]">
          <span>{{ item.product_name + ' ' + item.product_attribute_value_code + item.attribute_display_index }}</span>
        </div>
        <div class="inline-block w-[8%]"><span>{{ item.measure_unit_type }}</span></div>
        <div class="inline-block w-[20%]"><span>{{ item.weight }}</span></div>
        <div class="inline-block w-[20%]"><span>{{
            item.price.toLocaleString('it-IT', {
              style: 'currency',
              currency: 'VND'
            })
          }}</span></div>
        <div class="inline-block w-[22%]"><span>{{
            item.cost.toLocaleString('it-IT', {
              style: 'currency',
              currency: 'VND'
            })
          }}</span></div>
      </div>
      <div class="flex justify-end mt-5"><span>Tổng cộng: {{
          orderResponse.total_cost.toLocaleString('it-IT', {style: 'currency', currency: 'VND'})
        }}</span></div>
    </div>
  </div>
</template>

<script>
import {ref} from "vue";
import { useRoute,useRouter } from 'vue-router'
import {MODULE_STORE, ROUTER_PATH} from "@/const";
import {getCustomerDetailFromApi, updateCustomerFormApi, getOrderDetailFromApi} from "@/api";
import {useStore} from "vuex";

export default {
  name: "DetailOrder",
  components: { },

  data() {
    return {};
  },

  mounted() {},

  methods: {},
    setup() {
      const route = useRoute()
      const router = useRouter()
      const store = useStore()
      const OrderId = ref(route.params.id)
      const orderResponse = ref({})
      const orderProductResponse = ref([])

      const getOrderDetail = async (id) => {
        try {
          console.log(id)
          store.state[MODULE_STORE.COMMON.NAME].isLoadingPage = true;
          const response = await getOrderDetailFromApi(id);
          orderResponse.value = {
            ...response.data,
          };
          orderProductResponse.value = [...response.data.order_products]
        } catch (errors) {
          const error = errors.message || this.$t("common.has_error");
          // this.$toast.error(error);
        } finally {
          store.state[MODULE_STORE.COMMON.NAME].isLoadingPage = false;
        }
      }

      getOrderDetail(OrderId.value)

      return {
        OrderId,
        orderResponse,
        orderProductResponse,
      }
    }
};
</script>

<style scoped></style>
