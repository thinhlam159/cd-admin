<template>
  <div class="px-2 py-3 bg-white mt-10 mx-5 min-h-[600px]">
    <div class="w-full h-8 flex justify-between">
      <div class="mr-1 relative">
        <input type="text" v-model="keyword" @input="onInput" class="outline-none min-w-[150px] h-full border border-gray-300 rounded-sm px-10" placeholder="Tìm khách hàng">
        <div class="absolute top-[50%] left-2 -translate-y-1/2">
          <SearchIcon />
        </div>
      </div>
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
            #
          </th>
          <th class="border py-1 w-[10%]">
            Nguời tạo đơn
          </th>
          <th class="border py-1 w-[10%]">
            Khách hàng
          </th>
          <th class="border py-1 w-[8%]">
            Ngày tạo đơn
          </th>
          <th class="border py-1 w-[14%]">
            Tổng giá
          </th>
          <th class="border py-1 w-[10%]">
            Chi Tiết
          </th>
          <th class="border py-1 w-[10%]">
            Xuất excel
          </th>
          <th class="border py-1 w-[20%]">
            id
          </th>
          <th class="border py-1 w-[6%]">
            Xóa
          </th>
        </tr>
        </thead>
        <tbody class="[&>*:nth-child(odd)]:bg-[#f9f9f9]">
        <template v-for="(item, index) in listOrder">
          <tr>
            <td class="border text-center">{{ ++index }}</td>
            <td class="border text-center">{{ item.user_name }}</td>
            <td class="border text-center">
              <span class="text-[#337ab7] cursor-pointer break-all" @click="goToCustomerDebt(item.customer_id)">
                {{ item.customer_name }}
              </span>
            </td>
            <td class="border text-center">{{ moment(item.updated_at).format('DD-MM-YYYY') }}</td>
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
            <td class="border text-center">{{ item.order_id }}</td>
            <td class="border text-center">
              <button class="p-2 bg-red-500 rounded-md cursor-pointer" @click="handleCancelOrder(item.order_id)"><span>Xóa</span></button>
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
    <ModalConfirm
      v-model="show"
      :modal-id="modalId"
      title="Xóa đơn!"
      @confirm="() => confirmCancel(modalId)"
      button-value="Xóa"
    >
      <p>Bạn muốn xóa đơn hàng</p>
    </ModalConfirm>
  </div>
</template>

<script setup>
import ButtonAddNew from "@/components/Buttons/ButtonAddNew/ButtonAddNew.vue";
import ButtonEdit from "@/components/Buttons/ButtonEdit/ButtonEdit.vue";
import Pagination from "@/components/Pagination/Pagination.vue";
import {computed, inject, reactive, ref} from "vue";
import {useRoute, useRouter} from "vue-router";
import {useStore} from "vuex";
import {MODULE_STORE, PAGE_DEFAULT, ROUTER_PATH} from "@/const";
import {cancelOrderFromApi, exportOrderFromApi, getListOrderFromApi, updateResolvedOrderFromApi} from "@/api";
import SearchIcon from "@/components/icons/SearchIcon.vue";
import moment from "moment/moment";
import _ from 'lodash';
import ModalConfirm from "@/components/Modal/Modal/ModalConfirm.vue";

const route = useRoute();
const router = useRouter();
const store = useStore();
const toast = inject('$toast')
const pagination = ref(null);
const listOrder = reactive([]);
const addNewOrder = "Tạo đơn";
const orderDetail = "Chi tiết";
const exportExcel = "Xuất excel";
const keyword = ref(null);
const modalId = ref(null)
const show = ref(false)

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
    const res = await getListOrderFromApi(page, {params: {keyword: keyword.value}})
    pagination.value = res.pagination
    listOrder.length = 0
    res.data.forEach((item) => {
      listOrder.push(item)
    })
  } catch (errors) {
    const error = errors.message
    toast.error(error);
  } finally {
    store.state[MODULE_STORE.COMMON.NAME].isLoadingPage = false
  }
}

const goToDetail = (id) => {
  router.push(`${ROUTER_PATH.ORDER_MANAGE}/${ROUTER_PATH.DETAIL}/${id}`);
}

const exportOrder = async (id) => {
  const order = _.find(listOrder, (e) => e.order_id === id)
  const fileName = `${order.customer_name}-${moment(order.updated_at).format('DDMM')}-${_.takeRight(order.order_id, 8).join('')}.xlsx`
  const excelRes = await exportOrderFromApi({order_id: id})
  const url = URL.createObjectURL(new Blob([excelRes], {
    type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
  }))
  const link = document.createElement('a')
  link.href = url
  link.setAttribute('download', fileName)
  document.body.appendChild(link)
  link.click()
  link.remove()
}
const handleBackPage = (page) => {
  getListOrder(page)
}
const handleNextPage = (page) => {
  getListOrder(page)
}
const onInput = (e) => {
  keyword.value = e.target.value
  getListOrder(pageCurrent.value)
}
const handleCancelOrder = (orderId) => {
  show.value = true
  modalId.value = orderId
}
const confirmCancel = async (orderId) => {
  try {
    const res = await cancelOrderFromApi(orderId)
    await getListOrder(pageCurrent.value)
    show.value = false
  } catch (errors) {
    const error = errors.message
    toast.error(error);
  } finally {
    store.state[MODULE_STORE.COMMON.NAME].isLoadingPage = false
  }
}
const goToCustomerDebt = (customerId) => {
  router.push(`${ROUTER_PATH.ADMIN}/${ROUTER_PATH.DEBT_MANAGE}/${ROUTER_PATH.LIST_CUSTOMER_DEBT}/${customerId}`)
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
