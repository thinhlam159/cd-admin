<template>
  <div class="p-5 mt-8 mx-5 bg-white min-h-[600px]">
    <div class="w-full h-8 flex justify-start">
      <div class="mr-1 relative">
        <input type="text" @input="onInput" class="outline-none min-w-[150px] h-full border border-gray-300 rounded-sm px-10" placeholder="Tìm khách hàng">
        <div class="absolute top-[50%] left-2 -translate-y-1/2">
          <SearchIcon />
        </div>
      </div>
      <div class="ml-2">
        <ButtonFilter @clickBtn="sortByTotalDebt" :text="sortTotalDebt"/>
      </div>
      <div class="ml-2">
        <ButtonFilter @clickBtn="sortByRestDebt" :text="sortRestDebt"/>
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
            Khách hàng
          </th>
          <th class="border py-1 w-[9%]">
            Tổng công nợ
          </th>
          <th class="border py-1 w-[9%]">
            Đã thanh toán
          </th>
          <th class="border py-1 w-[9%]">
            Nợ phải thu
          </th>
          <th class="border py-1 w-[20%]">
            Cập nhật mới nhất
          </th>
          <th class="border py-1 w-[10%]">
            Chi tiết công nợ
          </th>
        </tr>
        </thead>
        <tbody class="[&>*:nth-child(odd)]:bg-[#f9f9f9]">
        <template v-for="(item, index) in listDebt">
          <tr>
            <td class="border text-center">{{ (pagination.current_page - 1) * pagination.per_page + (parseInt(index) + 1) }}</td>
            <td class="border text-center">{{ item.customer_name }}</td>
            <td class="border text-center">{{ item.total_debt.toLocaleString('it-IT', {style : 'currency', currency : 'VND'}) }}</td>
            <td class="border text-center">{{ item.total_payment.toLocaleString('it-IT', {style : 'currency', currency : 'VND'}) }}</td>
            <td class="border text-center">{{ (item.total_debt - item.total_payment).toLocaleString('it-IT', {style : 'currency', currency : 'VND'}) }}</td>
            <td class="border text-center">{{ moment(item.updated_date).format('L') }}</td>
            <td class="border text-center">
              <div class="flex justify-center">
                <ButtonEdit @clickBtn="() => goToCustomerDebtList(item.customer_id)" :text="DebtDetail"/>
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
      :totalPage="pagination.total_page"
      @onBack="handleBackPage"
      @onNext="handleNextPage"
    />
  </div>
</template>

<script setup>
import ButtonFilter from "@/components/Buttons/ButtonFilter/ButtonFilter.vue";
import ButtonEdit from "@/components/Buttons/ButtonEdit/ButtonEdit.vue";
import Pagination from "@/components/Pagination/Pagination.vue";
import {computed, inject, ref} from "vue";
import {useRoute, useRouter} from "vue-router";
import {useStore} from "vuex";
import {MODULE_STORE, PAGE_DEFAULT, ROUTER_PATH} from "@/const";
import {getListDebtFromApi} from "@/api";
import SearchIcon from "@/components/icons/SearchIcon.vue";
import moment from "moment/moment";

const listDebt = ref([]);
const route = useRoute();
const router = useRouter();
const store = useStore();
const pagination = ref(null);
const toast = inject('$toast');
const addNewDebt = "Thêm công nợ";
const DebtDetail = "Chi tiết";
const exportExcel = "Xuất excel";
const addNewPayment = "Tạo thanh toán";
const sortTotalDebt = "Tổng công nợ";
const sortRestDebt = "Nợ phải thu";
const sortTotalDebtUp = ref(true);
const sortRestDebtUp = ref(true);

const pageCurrent = computed(() => {
  if (!route.query.page) {
    return PAGE_DEFAULT;
  }
  return Number(route.query.page);
});

const getListDebt = async (page, keyword = '',order = '', sort = '') => {
  try {
    const res = await getListDebtFromApi({page, keyword, order, sort})
    pagination.value = res.pagination
    listDebt.value = {
      ...res.data,
    }
  } catch (errors) {
    const error = errors.message
    toast.error(error);
  } finally {
    store.state[MODULE_STORE.COMMON.NAME].isLoadingPage = false
  }
}

const goToCustomerDebtList = (id) => {
  router.push(`${ROUTER_PATH.DEBT_MANAGE}/${ROUTER_PATH.LIST_CUSTOMER_DEBT}/${id}`);
}

const handleBackPage = (page) => {
  getListDebt(page)
}
const handleNextPage = (page) => {
  getListDebt(page)
}

const onInput = async (e) => {
  const keyword = e.target.value
  await getListDebt(pageCurrent.value, keyword);
}

const sortByTotalDebt = () => {
  sortTotalDebtUp.value = !sortTotalDebtUp.value
  const sort = sortTotalDebtUp.value ? 'ASC' : 'DESC'
  getListDebt(pageCurrent.value,'', 'total_debt', sort)
}

const sortByRestDebt = () => {
  sortRestDebtUp.value = !sortRestDebtUp.value
  const sort = sortRestDebtUp.value ? 'ASC' : 'DESC'
  getListDebt(pageCurrent.value,'', 'rest_debt', sort)
}

getListDebt(pageCurrent.value)

store.state[MODULE_STORE.COMMON.NAME].breadcrumbCurrent = 'Công nợ'
store.state[MODULE_STORE.COMMON.NAME].breadcrumbItems = [
  {
    label: 'Trang chủ',
    link: '/dashboard'
  },
]

</script>

<style scoped>

</style>
