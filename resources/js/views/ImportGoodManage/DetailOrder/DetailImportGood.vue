<template>
  <!--  <FormUserManage :edit="true" />-->
  <div class="w-full h-full relative">
    <div class="w-[720px] pt-14 h-full absolute left-20">
      <div class="w-full py-6 py-auto text-xl">
        <span class="text-gray-500">Chi tiết đơn hàng</span>
      </div>
      <div class="w-full py-1 text-sm flex justify-between items-center">
        <span class="text-gray-900">Tên khách hàng: {{ importGoodDetail.customer_name }}</span>
        <div class="flex items-center">
          <span>
            Ngày tạo đơn:
          </span>
          <div class="inline ml-2">
            <Datepicker class="inline p-2 border border-gray-200" v-model="picked" :style="styleDatePicker"/>
          </div>
        </div>
      </div>
      <hr>
      <div class="w-full py-4 text-sm">
        <div class="w-full py-2 flex border-b border-gray-50 text-xs">
          <div class="inline-block w-[4%]"><span>#</span></div>
          <div class="inline-block w-[22%]"><span>Tên sản phẩm</span></div>
          <div class="inline-block w-[8%]"><span>ĐVT</span></div>
          <div class="inline-block w-[20%]"><span>Số lượng</span></div>
<!--          <div class="inline-block w-[20%]"><span>Đơn giá</span></div>-->
<!--          <div class="inline-block w-[22%]"><span>Thành tiền</span></div>-->
        </div>
        <div v-for="(item, index) in importGoodProduct" class="w-full py-2 flex border-b border-gray-50">
          <div class="inline-block w-[4%]"><span>{{ ++index }}</span></div>
          <div class="inline-block w-[22%]"><span>{{ item.product_name + ' ' + item.product_attribute_value_code }}</span></div>
          <div class="inline-block w-[8%]"><span>{{ item.measure_unit_type }}</span></div>
          <div class="inline-block w-[20%]"><span>{{ item.count }}</span></div>
<!--          <div class="inline-block w-[20%]"><span>{{ item.weight }}</span></div>-->
<!--          <div class="inline-block w-[20%]"><span>{{ item.price.toLocaleString('it-IT', {style : 'currency', currency : 'VND'}) }}</span></div>-->
<!--          <div class="inline-block w-[22%]"><span>{{ item.cost.toLocaleString('it-IT', {style : 'currency', currency : 'VND'}) }}</span></div>-->
        </div>
<!--        <div class="flex justify-end mt-5"><span>Tổng cộng: {{ importGoodDetail.total_cost.toLocaleString('it-IT', {style : 'currency', currency : 'VND'}) }}</span></div>-->
      </div>
      <div>
        <div class="ml-2 my-4">
          <ButtonAddNew @clickBtn="handleRestoreImportGood(importGoodDetail.import_good_id)" :text="'Xóa đơn nhập kho'"/>
        </div>
        <div class="ml-2 my-4">
          <ButtonAddNew @clickBtn="handleUpdateImportGood" :text="'Cập nhật nhập kho'"/>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import {inject, ref} from "vue";
import { useRoute,useRouter } from 'vue-router'
import {MODULE_STORE, ROUTER_PATH} from "@/const";
import {
  getCustomerDetailFromApi,
  updateCustomerFormApi,
  getOrderDetailFromApi,
  getImportGoodDetailFromApi, restoreImportGoodFromApi
} from "@/api";
import {useStore} from "vuex";
import Datepicker from "vue3-datepicker";
import ButtonAddNew from "@/components/Buttons/ButtonAddNew";

export default {
  name: "DetailImportGood",
  components: { Datepicker, ButtonAddNew },

  data() {
    return {};
  },

  mounted() {},

  methods: {},
    setup() {
      const route = useRoute()
      const router = useRouter()
      const store = useStore()
      const importGoodId = ref(route.params.id)
      const importGoodDetail = ref({})
      const importGoodProduct = ref([])
      const toast = inject('$toast')
      const picked = ref(new Date())
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

      const getImportGoodDetail = async (id) => {
        try {
          store.state[MODULE_STORE.COMMON.NAME].isLoadingPage = true;
          const response = await getImportGoodDetailFromApi(id);
          importGoodDetail.value = {
            ...response.data,
          };
          importGoodProduct.value = [...response.data.import_good_products]
          picked.value = new Date(response.data.import_good_date * 1000)
          console.log(response.data.import_good_date * 1000)
        } catch (errors) {
          const error = errors.message || this.$t("common.has_error");
          toast.error(error);
        } finally {
          store.state[MODULE_STORE.COMMON.NAME].isLoadingPage = false;
        }
      }

      const handleRestoreImportGood = async  (id) => {
        try {
          const res = await restoreImportGoodFromApi(id)
          router.push(`${ROUTER_PATH.ADMIN}/${ROUTER_PATH.IMPORT_GOOD_MANAGE}`)
          toast.success("Hủy đơn nhập hàng thành công!", {duration:3000})
        } catch (errors) {
          const error = errors.message || this.$t("common.has_error");
          toast.error(error);
        }
      }

      const handleUpdateImportGood = () => {

      }

      getImportGoodDetail(importGoodId.value)

      return {
        importGoodId,
        importGoodDetail,
        importGoodProduct,
        picked,
        styleDatePicker,
        handleRestoreImportGood,
        handleUpdateImportGood,
      }
    }
};
</script>

<style scoped></style>
