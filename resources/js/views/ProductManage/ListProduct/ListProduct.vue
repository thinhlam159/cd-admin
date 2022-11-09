<template>
  <div class="p-5">
    <div class="w-full h-8 flex justify-end">
      <ButtonAddNew @clickBtn="handleAddProduct" :text="addNewUser" />
    </div>

<!--    &lt;!&ndash; *********** &ndash;&gt;-->
<!--    <div class="flex h-8 justify-between mt-1">-->
<!--      <ButtonFilter @clickBtn="handleClickSortFn" />-->
<!--      <ButtonDownloadCSV />-->
<!--    </div>-->

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

            <th rowspan="2" class="border py-1 w-[8%]">
                Hình ảnh
            </th>

            <th rowspan="2" class="border py-1 w-[8%]">
                Mô tả
            </th>
            <th colspan="3" class="border py-1 w-[20%]">
                Dòng sản phẩm
            </th>
            <th rowspan="2" class="border py-1 w-[5%]">
                Cập nhật
            </th>
            <!--            <th class="border py-1 w-[5%]"></th>-->
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
                <td class="border text-center">
                    <div class="h-16 w-20 object-contain mx-auto py-1">
                        <img class="h-full" :src="item.image_path" alt="">
                    </div>
                </td>
                <td class="border text-center">{{ item.description }}</td>
                <td class="border text-center"></td>
                <td class="border text-center"></td>
                <td class="border text-center"></td>
                <td class="border text-center">
                    <div class="flex justify-center ">
                        <ButtonAddNew @clickBtn="() => goToAddProductAttributeValue(item.product_id)" :text="' '"/>
                        <ButtonEdit @clickBtn="() => goToAdd(item.product_id)" :text="editUser"/>
                    </div>
                </td>
            </tr>
          <tr v-else v-for="(subItem, subIndex) in item.product_attribute_values" :key="subItem.product_attribute_value_id">
              <td v-if="subIndex === 0" :rowspan="item.product_attribute_values.length" class="border text-center">{{ ++index }}</td>
              <td v-if="subIndex === 0" :rowspan="item.product_attribute_values.length" class="border text-center">{{ item.name }}</td>
              <td v-if="subIndex === 0" :rowspan="item.product_attribute_values.length" class="border text-center">{{ item.code }}</td>
              <td v-if="subIndex === 0" :rowspan="item.product_attribute_values.length" class="border text-center">{{ item.category_name }}</td>
              <td v-if="subIndex === 0" :rowspan="item.product_attribute_values.length" class="border text-center">
                  <div class="h-16 w-20 object-contain mx-auto py-1">
                      <img class="h-full" :src="item.image_path" alt="">
                  </div>
              </td>
              <td v-if="subIndex === 0" :rowspan="item.product_attribute_values.length" class="border text-center">{{ item.description }}</td>
<!--              <td class="border text-center">{{ item.description }}</td>-->
<!--              <td class="border text-center">{{ item.description }}</td>-->
              <td class="border text-center h-full m-0 p-0">
                  {{ subItem.code }}
              </td>
              <td class="border text-center h-full m-0 p-0">
                  {{ `${subItem.count} ${subItem.measure_unit_name}` }}
              </td>
              <td class="border text-center h-full m-0 p-0">
                  {{ `${subItem.price} ${subItem.monetary_unit_name}`}}
              </td>
            <td v-if="subIndex === 0" :rowspan="item.product_attribute_values.length"  class="border text-center">
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
  <QuotePriceModal></QuotePriceModal>
</template>

<script>
import Datepicker from "vue3-datepicker";
import { ROUTER_PATH, MODULE_STORE, PAGE_DEFAULT } from "@/const";
import ButtonAddNew from "@/components/Buttons/ButtonAddNew";
import ButtonFilter from "@/components/Buttons/ButtonFilter";
import ButtonDownloadCSV from "@/components/Buttons/ButtonDownloadCSV";
import ButtonEdit from "@/components/Buttons/ButtonEdit";
import {getListProductFromApi, getListUserManagerFromApi} from "@/api";
import { convertDateByTimestamp } from "@/utils";
import { ref, computed, watch, inject } from "vue";
import { useRouter, useRoute } from "vue-router";
import { useStore } from "vuex";
import Pagination from "@/components/Pagination";
import { useI18n } from "vue-i18n";
import QuotePriceModal from "@/views/ProductManage/ListProduct/QuotePriceModal";

export default {
  name: "ListProduct",
  components: {
    Datepicker,
    ButtonAddNew,
    ButtonFilter,
    ButtonDownloadCSV,
    ButtonEdit,
    Pagination,
    QuotePriceModal
  },

  setup() {
    const filterKey = ref({});
    const isShowSort = ref(false);
    const timeDatePicker = ref(new Date());
    const listProduct = ref([]);
    const route = useRoute();
    const router = useRouter();
    const store = useStore();
    const { t } = useI18n();
    const toast = inject("$toast");
    const pagination = ref(null);
    const addNewUser = "Thêm sản phẩm mới";
    const editUser = "Cập nhật";

    const pageCurrent = computed(() => {
      if (!route.query.page) {
        return PAGE_DEFAULT;
      }
      return Number(route.query.page);
    });

    const handleClickSortFn = () => {
      isShowSort.value = !isShowSort.value;
    };
    const handleAddProduct = () => {
      router.push(`${ROUTER_PATH.PRODUCT_MANAGE}/${ROUTER_PATH.ADD}`);
    };
    const goToAdd = (id) => {
      router.push(`${ROUTER_PATH.PRODUCT_MANAGE}/${ROUTER_PATH.EDIT}/` + id);
    };
      const goToAddProductAttributeValue = (id) => {
          router.push(`${ROUTER_PATH.PRODUCT_MANAGE}/${ROUTER_PATH.ADD_PRODUCT_ATTRIBUTE_VALUE}/` + id);
      };
    const getListProduct = async (page) => {
      try {
        store.state[MODULE_STORE.COMMON.NAME].isLoadingPage = true;
        const response = await getListProductFromApi(page);
        pagination.value = response.pagination;
          listProduct.value = {
          ...response.data,
        };

        // listUserManage.value = response.data.map((item) => {
        //   return {
        //     ...item,
        //     register_date: convertDateByTimestamp(item.register_date),
        //     login_last_date: convertDateByTimestamp(item.login_last_date),
        //   };
        // });
      } catch (errors) {
        const error = errors.message;
        // toast.error(error);
      } finally {
        store.state[MODULE_STORE.COMMON.NAME].isLoadingPage = false;
      }
    };

    // watch(pageCurrent, (page) => {
    //   if (route.path == ROUTER_PATH.USER_MANAGER) {
    //     getListUserManager(page);
    //   }
    // });
    const handleBackPage = (page) => {
      router.push(`${ROUTER_PATH.USER_MANAGER}?page=${page}`);
    };
    const handleNextPage = (page) => {
      router.push(`${ROUTER_PATH.USER_MANAGER}?page=${page}`);
    };
      getListProduct(pageCurrent.value);

    return {
      filterKey,
      isShowSort,
      timeDatePicker,
      listProduct,
      handleClickSortFn,
      handleAddProduct,
      goToAdd,
        getListProduct,
      pagination,
      handleBackPage,
      handleNextPage,
      addNewUser,
      editUser,
      goToAddProductAttributeValue
    };
  },
};
</script>

<style scoped>
/*table {*/
/*    border-collapse: collapse;*/
/*    width: 100%;*/
/*    text-align: center;*/
/*}*/

/*table, tr, td, th {*/
/*    border: 1px solid black;*/
/*}*/

/*th {*/
/*    vertical-align: top;*/
/*}*/

/*td:empty:after {*/
/*    content: "\00a0"; !* HTML entity of &nbsp; *!*/
/*}*/
</style>
