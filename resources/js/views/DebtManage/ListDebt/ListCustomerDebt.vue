<template>
  <div class="p-5">
    <div class="w-full h-8 flex justify-between">
      <div class="ml-2">
        <ButtonAddNew @clickBtn="goToCreateDebt" :text="addNewDebt"/>
      </div>
      <div class="ml-2">
        <ButtonAddNew @clickBtn="goToCreatePaymnet" :text="addNewPayment"/>
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
          <th class="border py-1 w-[20%]">
            Cập nhật mới nhất
          </th>
          <th class="border py-1 w-[10%]">
            Chi tiết công nợ
          </th>
        </tr>
        </thead>
        <tbody>
        <template v-for="(item, index) in listDebt">
          <tr>
            <td class="border text-center">{{ ++index }}</td>
            <td class="border text-center">{{ item.customer_name }}</td>
            <td class="border text-center">{{ item.total_debt.toLocaleString('it-IT', {style : 'currency', currency : 'VND'}) }}</td>
            <td class="border text-center">{{ item.total_payment.toLocaleString('it-IT', {style : 'currency', currency : 'VND'}) }}</td>
            <td class="border text-center">{{ item.updated_date }}</td>
            <td class="border text-center">
              <div class="flex justify-center">
                <ButtonEdit @clickBtn="() => goToDetail(item.order_id)" :text="orderDetail"/>
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
import ButtonAddNew from "@/components/Buttons/ButtonAddNew/ButtonAddNew.vue";
import ButtonFilter from "@/components/Buttons/ButtonFilter/ButtonFilter.vue";
import ButtonDownloadCSV from "@/components/Buttons/ButtonDownloadCSV/ButtonDownloadCSV.vue";
import ButtonEdit from "@/components/Buttons/ButtonEdit/ButtonEdit.vue";
import Pagination from "@/components/Pagination/Pagination.vue";
import {computed, inject, ref} from "vue";
import {useRoute, useRouter} from "vue-router";
import {useStore} from "vuex";
import {MODULE_STORE, PAGE_DEFAULT, ROUTER_PATH} from "@/const";
import {exportOrderFromApi, getListDebtFromApi, getListOrderFromApi} from "@/api";

const listDebt = ref([]);
const route = useRoute();
const router = useRouter();
const store = useStore();
const pagination = ref(null);
const toast = inject('$toast');
const addNewDebt = "Thêm công nợ";
const orderDetail = "Chi tiết";
const exportExcel = "Xuất excel";
const addNewPayment = "Tạo thanh toán";

const getListCustomerDebt = async (page) => {
  try {
    const res = await getListDebtFromApi({page})
    pagination.value = res.pagination
    listDebt.value = {
      ...res.data
    }
  } catch (errors) {
    const error = errors.message
    toast.error(error);
  } finally {
    store.state[MODULE_STORE.COMMON.NAME].isLoadingPage = false
  }
}

const handleBackPage = (page) => {
  getListCustomerDebt({page})
}
const handleNextPage = (page) => {
  getListCustomerDebt({page})
}

getListCustomerDebt(pageCurrent.value);

</script>

<style scoped>

</style>
