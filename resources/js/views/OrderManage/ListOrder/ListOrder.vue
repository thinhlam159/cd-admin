<template>
  <div class="px-2 py-3 bg-white mt-10 mx-5 min-h-[600px]">
    <div class="w-full h-8 flex justify-between">
      <div class="ml-2">
        <ButtonAddNew @clickBtn="handleCreateOrder" :text="addNewOrder"/>
      </div>
    </div>

    <!-- *********** -->
    <div class="mt-4">
      <table class="w-full">
        <thead>
        <tr class="">
          <th class="border py-1 w-[2%]">
            STT
          </th>
          <th class="border py-1 w-[12%]">
            Nguời tạo đơn
          </th>
          <th class="border py-1 w-[12%]">
            Khách hàng
          </th>
          <th class="border py-1 w-[9%]">
            Ngày tạo đơn
          </th>
          <th class="border py-1 w-[20%]">
            Tổng giá
          </th>
          <th class="border py-1 w-[20%]">
            Chi Tiết
          </th>
          <th class="border py-1 w-[20%]">
            Xuất excel
          </th>
        </tr>
        </thead>
        <tbody class="[&>*:nth-child(odd)]:bg-[#f9f9f9]">
        <template v-for="(item, index) in listOrder">
          <tr>
            <td class="border text-center">{{ ++index }}</td>
            <td class="border text-center">{{ item.user_name }}</td>
            <td class="border text-center">{{ item.customer_name }}</td>
            <td class="border text-center">{{ item.updated_at }}</td>
            <td class="border text-center">{{ item.total_cost.toLocaleString('it-IT', {style : 'currency', currency : 'VND'}) }}</td>
            <td class="border text-center">
              <div class="flex justify-center ">
                <ButtonEdit @clickBtn="() => goToDetail(item.order_id)" :text="orderDetail"/>
              </div>
            </td>
            <td class="border text-center">
              <div class="flex justify-center ">
                <ButtonEdit @clickBtn="() => exportOrder(item.order_id)" :text="exportExcel"/>
              </div>
            </td>
          </tr>
        </template>
        </tbody>
      </table>
    </div>

    <Pagination
      v-if="pagination"
      :pageCurrent="pagination.current_page"
      :totalPage="pagination.total"
      @onBack="handleBackPage"
      @onNext="handleNextPage"
    />
  </div>
</template>

<script setup>
import Datepicker from "vue3-datepicker";
import ButtonAddNew from "@/components/Buttons/ButtonAddNew/ButtonAddNew.vue";
import ButtonFilter from "@/components/Buttons/ButtonFilter/ButtonFilter.vue";
import ButtonDownloadCSV from "@/components/Buttons/ButtonDownloadCSV/ButtonDownloadCSV.vue";
import ButtonEdit from "@/components/Buttons/ButtonEdit/ButtonEdit.vue";
import Pagination from "@/components/Pagination/Pagination.vue";
import {computed, ref} from "vue";
import {useRoute, useRouter} from "vue-router";
import {useStore} from "vuex";
import {MODULE_STORE, PAGE_DEFAULT, ROUTER_PATH} from "@/const";
import {exportOrderFromApi, getListOrderFromApi} from "@/api";

const listOrder = ref([]);
const route = useRoute();
const router = useRouter();
const store = useStore();
const pagination = ref(null);
const addNewOrder = "Tạo đơn";
const orderDetail = "Chi tiết";
const exportExcel = "Xuất excel";

const pageCurrent = computed(() => {
  if (!route.query.page) {
    return PAGE_DEFAULT;
  }
  return Number(route.query.page);
});

const handleCreateOrder = () => {
  router.push(`${ROUTER_PATH.ORDER_MANAGE}/${ROUTER_PATH.ADD}`);
}

const getListOrder = async (page) => {
  try {
    const res = await getListOrderFromApi(page)
    pagination.value = res.pagination
    listOrder.value = {
      ...res.data
    }
  } catch (errors) {
    const error = errors.message
    // toast.error(error);
  } finally {
    store.state[MODULE_STORE.COMMON.NAME].isLoadingPage = false
  }
}

const goToDetail = (id) => {
  router.push(`${ROUTER_PATH.ORDER_MANAGE}/${ROUTER_PATH.DETAIL}/${id}`);
}

const exportOrder = async (id) => {
  const excelRes = await exportOrderFromApi({order_id: id})
  const url = URL.createObjectURL(new Blob([excelRes], {
    type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
  }))
  const link = document.createElement('a')
  link.href = url
  link.setAttribute('download', 'fileName')
  document.body.appendChild(link)
  link.click()
  link.remove()
}

store.state[MODULE_STORE.COMMON.NAME].breadcrumbCurrent = 'Đơn hàng'
store.state[MODULE_STORE.COMMON.NAME].breadcrumbItems = [
  {
    label: 'Trang chủ',
    link: '/dashboard'
  },
]

getListOrder(pageCurrent.value);
</script>

<style scoped>

</style>
