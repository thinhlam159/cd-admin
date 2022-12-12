<template>
  <div class="p-5">
    <div class="w-full h-8 flex justify-between">
      <div class="ml-2">
        <ButtonAddNew @clickBtn="goToAdd" :text="addNewImportGood"/>
      </div>
    </div>

    <!-- *********** -->
    <div class="mt-4">
      <table class="w-full">
        <thead>
        <tr class="">
          <th rowspan="2" class="border py-1 w-[2%]">
            STT
          </th>
          <th rowspan="2" class="border py-1 w-[12%]">
            Nguời tạo
          </th>
          <th rowspan="2" class="border py-1 w-[12%]">
            Nhà phân phối
          </th>
          <th rowspan="2" class="border py-1 w-[9%]">
            Thanh toán
          </th>
          <th rowspan="2" class="border py-1 w-[9%]">
            Ngày tạo đơn
          </th>
          <th rowspan="2" class="border py-1 w-[20%]">
            Tổng giá
          </th>
          <th colspan="3" class="border py-1 w-[20%]">
            Chi Tiết
          </th>
          <th rowspan="2" class="border py-1 w-[10%]">
            Xuất excel
          </th>
        </tr>
        </thead>
        <tbody>
        <template v-for="(item, index) in listImportGood">
          <tr v-if="item.import_good_products.length === 0">
            <td class="border text-center">{{ ++index }}</td>
            <td class="border text-center">{{ item.user_name }}</td>
            <td class="border text-center">{{ item.dealer_name }}</td>
            <td class="border text-center">{{ item.payment_status }}</td>
            <td class="border text-center">{{ item.updated_at }}</td>
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
          <tr v-else v-for="(subItem, subIndex) in item.import_good_products"
              :key="subItem.product_attribute_value_id">
            <td v-if="subIndex === 0" :rowspan="item.import_good_products.length" class="border text-center">
              {{ ++index }}
            </td>
            <td v-if="subIndex === 0" :rowspan="item.import_good_products.length" class="border text-center">
              {{ item.user_name }}
            </td>
            <td v-if="subIndex === 0" :rowspan="item.import_good_products.length" class="border text-center">
              {{ item.dealer_name }}
            </td>
            <td v-if="subIndex === 0" :rowspan="item.import_good_products.length" class="border text-center">
              {{ item.payment_status }}
            </td>
            <td v-if="subIndex === 0" :rowspan="item.import_good_products.length" class="border text-center">
              {{ item.updated_at }}
            </td>

            <td class="border text-center h-full m-0 p-0">
              {{ `${subItem.product_code} ${subItem.product_attribute_value_code}` }}
            </td>
            <td class="border text-center h-full m-0 p-0">
              {{ `${subItem.import_good_product_price.toLocaleString('it-IT', {style : 'currency', currency : 'VND'})}` }}
            </td>
            <td class="border text-center h-full m-0 p-0">
              {{ `${subItem.count} ${subItem.measure_unit_name}` }}
            </td>

            <td v-if="subIndex === 0" :rowspan="item.import_good_products.length" class="border text-center">
              <div class="flex justify-center ">
                <ButtonAddNew @clickBtn="() => goToAddProductAttributeValue(item.product_id)" :text="' '"/>
                <ButtonEdit @clickBtn="() => goToAdd(item.product_id)" :text="editUser"/>
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
import { getListImportGoodFromApi } from "@/api";
import listImportGood from "@/views/ImportGoodManage/ListImportGood/ListImportGood.vue";

export default {
  name: "ListImportGood",
  methods: {
    listImportGood() {
      return listImportGood
    }
  },
  components: {
    Datepicker,
    ButtonAddNew,
    ButtonFilter,
    ButtonDownloadCSV,
    ButtonEdit,
    Pagination,
  },
  setup() {
    const route = useRoute();
    const router = useRouter();
    const store = useStore();
    const pagination = ref(null);
    const addNewImportGood = "Tạo đơn nhập kho";
    const orderDetail = "Chi tiết";
    const listImportGood = ref([]);

    const pageCurrent = computed(() => {
      if (!route.query.page) {
        return PAGE_DEFAULT;
      }
      return Number(route.query.page);
    });

    const handleCreateOrder = () => {
      router.push(`${ROUTER_PATH.ORDER_MANAGE}/${ROUTER_PATH.ADD}`);
    }

    const goToAdd = () => {
      router.push(`${ROUTER_PATH.IMPORT_GOOD_MANAGE}/${ROUTER_PATH.ADD}`);
    }

    const goToDetail = (id) => {
      router.push(`${ROUTER_PATH.ORDER_MANAGE}/${ROUTER_PATH.DETAIL}/${id}`);
    }

    const getListImportGood = async (param) => {
      try {
        console.log(11111111)
        const res = await getListImportGoodFromApi(param)
        listImportGood.value = res.data
      } catch (error) {
        console.log(error)
        store.state[MODULE_STORE.COMMON.NAME].isLoadingPage = false;
      }
    }

    getListImportGood(pageCurrent.value);

    return {
      pagination,
      addNewImportGood,
      orderDetail,
      handleCreateOrder,
      goToDetail,
      getListImportGood,
      goToAdd
    }
  }
}
</script>

<style scoped>

</style>
