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
          <th rowspan="2" class="border py-1 w-[2%]">
            id
          </th>
          <th rowspan="2" class="border py-1 w-[7%]">
            Tên sản phẩm
          </th>
          <th rowspan="2" class="border py-1 w-[7%]">
            Mã sản phẩm
          </th>
          <th rowspan="2" class="border py-1 w-[7%]">
            Danh mục
          </th>
          <th rowspan="2" class="border py-1 w-[7%]">
            Báo giá
          </th>
          <th colspan="3" class="border py-1 w-[20%]">
            Dòng sản phẩm
          </th>
        </tr>
        <tr>
          <th class="border py-1">Mã</th>
          <th class="border py-1">Tồn kho</th>
          <th class="border py-1">Đơn giá</th>
        </tr>
        </thead>
        <tbody>
        <template v-for="(item, index) in listProduct">
          <tr v-if="item.product_attribute_values.length===0">
            <td class="border text-center">{{ ++index }}</td>
            <td class="border text-center">{{ item.name }}</td>
            <td class="border text-center">{{ item.code }}</td>
            <td class="border text-center">{{ item.category_name }}</td>
            <td class="border text-center"></td>
            <td class="border text-center"></td>
            <td class="border text-center"></td>
          </tr>
          <tr v-else v-for="(subItem, subIndex) in item.product_attribute_values"
              :key="subItem.product_attribute_value_id">
            <td v-if="subIndex === 0" :rowspan="item.product_attribute_values.length" class="border text-center">
              {{ ++index }}
            </td>
            <td v-if="subIndex === 0" :rowspan="item.product_attribute_values.length" class="border text-center">
              {{ item.name }}
            </td>
            <td v-if="subIndex === 0" :rowspan="item.product_attribute_values.length" class="border text-center">
              {{ item.code }}
            </td>
            <td v-if="subIndex === 0" :rowspan="item.product_attribute_values.length" class="border text-center">
              {{ item.category_name }}
            </td>
            <td class="border text-center h-full m-0 p-0">
              {{ `${subItem.code} x ${subItem.notice_price_type} x ${subItem.price}` }}
            </td>
            <td class="border text-center h-full m-0 p-0">
              {{ subItem.code }}
            </td>
            <td class="border text-center h-full m-0 p-0">
              {{ `${subItem.count} ${subItem.measure_unit_name}` }}
            </td>
            <td class="border text-center h-full m-0 p-0">
              {{ `${subItem.price} ${subItem.monetary_unit_name}` }}
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
import {computed, inject, ref} from "vue";
import {useRoute, useRouter} from "vue-router";
import {useStore} from "vuex";
import {useI18n} from "vue-i18n";
import {MODULE_STORE, PAGE_DEFAULT, ROUTER_PATH} from "@/const";
import {getListOrderFromApi} from "@/api";

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
    const addNewOrder = "Tạo đơn";
    const editUser = "Cập nhật";

    const pageCurrent = computed(() => {
      if (!route.query.page) {
        return PAGE_DEFAULT;
      }
      return Number(route.query.page);
    });

    const handleCreateOrder = () => {
      console.log(123)
      router.push(`${ROUTER_PATH.ORDER_MANAGE}/${ROUTER_PATH.ADD}`);
    }

    const getListOrder = async (page) => {
      try {
        const res = await getListOrderFromApi(page)
        console.log(res.data)
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
    getListOrder(pageCurrent.value);

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
