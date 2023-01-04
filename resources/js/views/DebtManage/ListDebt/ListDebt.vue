<template>
  <div class="p-5">
    <div class="w-full h-8 flex justify-between">
      <div class="ml-2">
        <ButtonAddNew @clickBtn="goToCreateDebt" :text="addNewDebt"/>
      </div>
      <div class="ml-2">
        <ButtonAddNew @clickBtn="goToCreatePayment" :text="addNewPayment"/>
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
        <tbody>
        <template v-for="(item, index) in listDebt">
          <tr>
            <td class="border text-center">{{ ++index }}</td>
            <td class="border text-center">{{ item.customer_name }}</td>
            <td class="border text-center">{{ item.total_debt.toLocaleString('it-IT', {style : 'currency', currency : 'VND'}) }}</td>
            <td class="border text-center">{{ item.total_payment.toLocaleString('it-IT', {style : 'currency', currency : 'VND'}) }}</td>
            <td class="border text-center">{{ (item.total_debt - item.total_payment).toLocaleString('it-IT', {style : 'currency', currency : 'VND'}) }}</td>
            <td class="border text-center">{{ convertDateByTimestamp()(item.updated_date) }}</td>
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
      :totalPage="pagination.total"
      @onBack="handleBackPage"
      @onNext="handleNextPage"
    />
  </div>
</template>

<script>
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
import { convertDateByTimestamp } from "@/utils";

export default {
  name: "ListOrder",
  methods: {
    convertDateByTimestamp() {
      return convertDateByTimestamp
    }
  },
  components: {
    ButtonAddNew,
    ButtonFilter,
    ButtonDownloadCSV,
    ButtonEdit,
    Pagination,
  },
  setup() {
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

    const pageCurrent = computed(() => {
      if (!route.query.page) {
        return PAGE_DEFAULT;
      }
      return Number(route.query.page);
    });

    const goToCreateDebt = () => {
      router.push(`${ROUTER_PATH.DEBT_MANAGE}/${ROUTER_PATH.ADD}`);
    }

    const goToCreatePayment = () => {
      router.push(`${ROUTER_PATH.DEBT_MANAGE}/${ROUTER_PATH.PAYMENT}/${ROUTER_PATH.ADD}`);
    }

    const getListDebt = async (page) => {
      try {
        const res = await getListDebtFromApi({page})
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

    const exportOrder = async (id) => {
      console.log(12313)
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

    const handleBackPage = (page) => {
      getListDebt({page})
    }
    const handleNextPage = (page) => {
      getListDebt({page})
    }

    getListDebt(pageCurrent.value);

    return {
      pagination,
      addNewDebt,
      DebtDetail,
      listDebt,
      exportExcel,
      addNewPayment,
      goToCustomerDebtList,
      goToCreateDebt,
      goToCreatePayment,
      handleBackPage,
      handleNextPage,
      exportOrder
    }
  }
}
</script>

<style scoped>

</style>
