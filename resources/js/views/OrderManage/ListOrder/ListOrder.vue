<template>
  <div class="p-5">
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
            Giao hàng
          </th>
          <th class="border py-1 w-[9%]">
            Thanh toán
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
        <tbody>
        <template v-for="(item, index) in listOrder">
          <tr>
            <td class="border text-center">{{ ++index }}</td>
            <td class="border text-center">{{ item.user_name }}</td>
            <td class="border text-center">{{ item.customer_name }}</td>
            <td class="border text-center">{{ item.delivery_status }}</td>
            <td class="border text-center">{{ item.payment_status }}</td>
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

<script>
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

export default {
  name: "ListOrder",
  components: {
    Datepicker,
    ButtonAddNew,
    ButtonFilter,
    ButtonDownloadCSV,
    ButtonEdit,
    Pagination,
  },
  setup() {
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

    getListOrder(pageCurrent.value);

    return {
      pagination,
      addNewOrder,
      orderDetail,
      listOrder,
      exportExcel,
      handleCreateOrder,
      goToDetail,
      exportOrder
    }
  }
}
</script>

<style scoped>

</style>
