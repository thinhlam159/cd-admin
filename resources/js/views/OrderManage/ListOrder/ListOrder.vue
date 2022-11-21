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
        </tr>
        </thead>
        <tbody>
        <template v-for="(item, index) in listOrder">
          <tr>
            <td class="border text-center">{{ ++index }}</td>
            <td class="border text-center">{{ item.customerName }}</td>
            <td class="border text-center">{{ item.delivery_status }}</td>
            <td class="border text-center">{{ item.payment_status }}</td>
            <td class="border text-center">{{ item.update_at }}</td>
            <td class="border text-center">{{ item.totalPrice }}</td>
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
import {computed, inject, ref} from "vue";
import {useRoute, useRouter} from "vue-router";
import {useStore} from "vuex";
import {useI18n} from "vue-i18n";
import {MODULE_STORE, PAGE_DEFAULT, ROUTER_PATH} from "@/const";
import {getListCustomerFromApi, getListOrderFromApi, getListProductFromApi, getListUserManagerFromApi} from "@/api";

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
    const filterKey = ref({});
    const isShowSort = ref(false);
    const timeDatePicker = ref(new Date());
    const listOrder = ref([]);
    const route = useRoute();
    const router = useRouter();
    const store = useStore();
    const {t} = useI18n();
    const toast = inject("$toast");
    const pagination = ref(null);
    const customers = ref({});
    const products = ref({});
    const listUser = ref({});
    const addNewOrder = "Tạo đơn";
    const editUser = "Cập nhật";


    const pageCurrent = computed(() => {
      if (!route.query.page) {
        return PAGE_DEFAULT;
      }
      return Number(route.query.page);
    });

    const handleCreateOrder = () => {
      router.push(`${ROUTER_PATH.ORDER_MANAGE}/${ROUTER_PATH.ADD}`);
    }

    // const getListOrder = async (page) => {
    //   try {
    //     const res = await getListOrderFromApi(page)
    //     console.log(res.data)
    //     pagination.value = res.pagination
    //     listOrder.value = {
    //       ...res.data
    //     }
    //   } catch (errors) {
    //     const error = errors.message
    //     // toast.error(error);
    //   } finally {
    //     store.state[MODULE_STORE.COMMON.NAME].isLoadingPage = false
    //   }
    // }
    const getListCustomer = async () => {
      const res = await getListCustomerFromApi();
      customers.value = {
        ...res.data
      }
    }
    const getListProduct = async () => {
      const res = await getListProductFromApi();
      products.value = res.data
    }
    const getListUserManager = async (page) => {
      try {
        store.state[MODULE_STORE.COMMON.NAME].isLoadingPage = true;
        const response = await getListUserManagerFromApi(page);
        pagination.value = response.pagination;
        listUser.value = {
          ...response.data,
        };
      } catch (errors) {
        const error = errors.message || t("common.has_error");
        toast.error(error);
      } finally {
        store.state[MODULE_STORE.COMMON.NAME].isLoadingPage = false;
      }
    };
    const initComponent = async (page) => {
      // const productResponse = await getListProductFromApi()
      // const userResponse = await getListUserManagerFromApi()
      const customerResponse = await getListCustomerFromApi()
      const orderResponse = await getListOrderFromApi(page)
      pagination.value = orderResponse.pagination

      listOrder.value = orderResponse.data.map((order) => {
        const customer = customerResponse.data.find((customer) => order.customer_id === customer.customer_id)

        return  {
          ...order,
          customerName: customer.customer_name
        }
      })

    }

    // getListOrder(pageCurrent.value);
    // getListCustomer();
    // getListProduct();
    // getListUserManager();
    initComponent(pageCurrent.value)

    return {
      pagination,
      addNewOrder,
      handleCreateOrder,
      listOrder
    }
  }
}
</script>

<style scoped>

</style>
