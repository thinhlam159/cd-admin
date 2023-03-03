<template>
  <div class="p-5 mt-8 mx-5 bg-white max-w-[600px]">
    <div class="w-full py-6 py-auto text-xl">
      <span class="text-gray-500">Chi tiết nhập kho</span>
    </div>
    <div class="w-full py-1 text-sm flex justify-between items-center border-b border-gray-200">
      <span class="text-gray-900">Tên container: {{ importGoodDetail.container_name }}</span>
      <div class="flex items-center">
          <span>
            Ngày tạo đơn:
          </span>
        <div class="inline ml-2">
          <span>{{ moment(importGoodDetail.import_good_date).format('DD-MM-yyyy')}}</span>
        </div>
      </div>
    </div>

    <div class="w-full py-4 text-sm border-b border-gray-20">
      <div class="w-full py-2 flex border-b border-gray-50 text-xs">
        <div class="inline-block w-[10%]"><span>#</span></div>
        <div class="inline-block w-[35%]"><span>Tên sản phẩm</span></div>
        <div class="inline-block w-[25%]"><span>ĐVT</span></div>
        <div class="inline-block w-[30%]"><span>Số lượng</span></div>
      </div>
      <div v-for="(item, index) in importGoodProduct" class="w-full py-2 flex border-b border-gray-50">
        <div class="inline-block w-[10%]"><span>{{ ++index }}</span></div>
        <div class="inline-block w-[35%]"><span>{{ item.product_name + ' ' + item.product_attribute_value_code }}</span>
        </div>
        <div class="inline-block w-[25%]"><span>{{ t(`measure_unit_type.${item.measure_unit_type}`) }}</span></div>
        <div class="inline-block w-[30%]"><span>{{ item.count }}</span></div>
      </div>
    </div>
    <div>
      <div class="ml-2 my-4">
        <ButtonAddNew @clickBtn="handleRestoreImportGood(importGoodDetail.import_good_id)" :text="'Xóa đơn nhập kho'"/>
      </div>
    </div>
  </div>
</template>

<script setup>
import {inject, ref} from "vue";
import { useRoute,useRouter } from 'vue-router'
import {MODULE_STORE, ROUTER_PATH} from "@/const";
import {getImportGoodDetailFromApi, restoreImportGoodFromApi} from "@/api";
import {useStore} from "vuex";
import ButtonAddNew from "@/components/Buttons/ButtonAddNew";
import moment from "moment/moment";
import {useI18n} from "vue-i18n";

const route = useRoute()
const router = useRouter()
const store = useStore()
const {t} = useI18n()
const importGoodId = ref(route.params.id)
const importGoodDetail = ref({})
const importGoodProduct = ref([])
const toast = inject('$toast')

const getImportGoodDetail = async (id) => {
  try {
    store.state[MODULE_STORE.COMMON.NAME].isLoadingPage = true;
    const response = await getImportGoodDetailFromApi(id);
    importGoodDetail.value = {
      ...response.data,
    };
    importGoodProduct.value = [...response.data.import_good_products]
  } catch (errors) {
    const error = errors.message || this.$t("common.has_error");
    toast.error(error);
  } finally {
    store.state[MODULE_STORE.COMMON.NAME].isLoadingPage = false;
  }
}
const handleRestoreImportGood = async (id) => {
  try {
    const res = await restoreImportGoodFromApi(id)
    await router.push(`${ROUTER_PATH.ADMIN}/${ROUTER_PATH.IMPORT_GOOD_MANAGE}`)
    toast.success("Hủy đơn nhập hàng thành công!", {duration:3000})
  } catch (errors) {
    const error = errors.message;
    toast.error(error);
  }
}

getImportGoodDetail(importGoodId.value)

store.state[MODULE_STORE.COMMON.NAME].breadcrumbCurrent = 'Chi tiết nhập kho'
store.state[MODULE_STORE.COMMON.NAME].breadcrumbItems = [
  {
    label: 'Trang chủ',
    link: '/dashboard'
  },
  {
    label: 'Nhập kho',
    link: '/import-good-manage'
  },
]
</script>

<style scoped></style>
