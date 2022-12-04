<template>
  <div class="p-5">
    <div class="w-full h-8 flex justify-between">
      <div class="flex">
        <ButtonFilter @clickBtn="listByCategory('all')" :text="'Tất cả'"/>
        <ButtonFilter @clickBtn="listByCategory('jumbo')" :text="'Jumbo'"/>
        <ButtonFilter @clickBtn="listByCategory('finishedProduct')" :text="'Thành phẩm'"/>
        <ButtonFilter @clickBtn="listByCategory('other')" :text="'Khác'"/>
      </div>
      <div class="flex justify-between">
        <ButtonAddNew @clickBtn="show" :text="'Báo giá'"/>
      </div>
      <div class="ml-2">
        <ButtonAddNew @clickBtn="handleAddProduct" :text="addNewUser"/>
      </div>
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
              #
            </th>
            <th rowspan="2" class="border py-1 w-[5%]">
              Tên sản phẩm
            </th>
            <th rowspan="2" class="border py-1 w-[5%]">
              Mã sản phẩm
            </th>
            <th rowspan="2" class="border py-1 w-[5%]">
              Danh mục
            </th>
            <th rowspan="2" class="border py-1 w-[5%]">
              Báo giá
            </th>
            <th colspan="3" class="border py-1 w-[20%]">
              Dòng sản phẩm
            </th>
            <th class="border py-1 w-[5%]">
              Cập nhật
            </th>
          </tr>
          <tr>
            <th class="border py-1">Mã</th>
            <th class="border py-1">Tồn kho</th>
            <th class="border py-1">Đơn giá / kg</th>
          </tr>
        </thead>
        <tbody>
        <template v-for="(item, index) in listProduct">
          <tr v-if="item.product_attribute_values.length === 0">
            <td class="border text-center">{{ ++index }}</td>
            <td class="border text-center">{{ item.name }}</td>
            <td class="border text-center">{{ item.code }}</td>
            <td class="border text-center">{{ item.category_name }}</td>
            <td class="border text-center"></td>
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
<!--            <td v-if="subIndex === 0" :rowspan="item.product_attribute_values.length" class="border text-center">-->
<!--              {{ item.description }}-->
<!--            </td>-->
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
            <td v-if="subIndex === 0" :rowspan="item.product_attribute_values.length" class="border text-center">
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
  <!--  <QuotePriceModal></QuotePriceModal>-->
</template>

<script>
import Datepicker from "vue3-datepicker";
import {ROUTER_PATH, MODULE_STORE, PAGE_DEFAULT} from "@/const";
import ButtonAddNew from "@/components/Buttons/ButtonAddNew";
import ButtonFilter from "@/components/Buttons/ButtonFilter";
import ButtonDownloadCSV from "@/components/Buttons/ButtonDownloadCSV";
import ButtonEdit from "@/components/Buttons/ButtonEdit";
import {
  getListProductFromApi,
  getListUserManagerFromApi,
  getListCategoryFromApi,
  exportOrderFromApi
} from "@/api";
import {convertDateByTimestamp} from "@/utils";
import {ref, computed, watch, inject} from "vue";
import {useRouter, useRoute} from "vue-router";
import {useStore} from "vuex";
import Pagination from "@/components/Pagination";
import {useI18n} from "vue-i18n";
// import QuotePriceModal from "@/views/ProductManage/ListProduct/QuotePriceModal";

export default {
  name: "ListProduct",
  components: {
    Datepicker,
    ButtonAddNew,
    ButtonFilter,
    ButtonDownloadCSV,
    ButtonEdit,
    Pagination,
    // QuotePriceModal
  },

  setup() {
    const filterKey = ref({});
    const isShowSort = ref(false);
    const timeDatePicker = ref(new Date());
    const listProduct = ref([]);
    const route = useRoute();
    const router = useRouter();
    const store = useStore();
    const {t} = useI18n();
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
    const getListProduct = async (page, categoryIds = []) => {
      try {
        store.state[MODULE_STORE.COMMON.NAME].isLoadingPage = true
        const response = await getListProductFromApi(page, categoryIds)
        pagination.value = response.pagination
        const productResult = response.data.map((product) => {
          const attvalue = product.product_attribute_values.map((attributeValue) => {
            return {
              ...attributeValue,
              price: attributeValue.price.toLocaleString('it-IT', {style : 'currency', currency : 'VND'})
            }
          })

          return {
            ...product,
            product_attribute_values : attvalue
          }
        })
        listProduct.value = {
          ...productResult,
        };
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
    const listByCategory = async (category) => {
      let res;
      switch (category) {
        case 'all':
          res = await getListProductFromApi(pageCurrent.value, )
          break
        case 'jumbo':
          res = await getListProductFromApi(pageCurrent.value, {
            params: {
              category_ids: ["01GFYRT4343YMNC6ZEJK7K7F54"]}
            }
          )
          break
        case 'finishedProduct':
          res = await getListProductFromApi(pageCurrent.value, {
            params: {
              category_ids: ["01GFYRT43ZEJ443YMNC6K7K7F5"]}
          })
          break
        case 'other':
          res = await getListProductFromApi(pageCurrent.value, {
            params: {
              category_ids: ["01GFYRT43ZEJK7K7F5443YMNC6"]}
          })
          break
      }

      const productResult = res.data.map((product) => {
        const attvalue = product.product_attribute_values.map((attributeValue) => {
          return {
            ...attributeValue,
            price: attributeValue.price.toLocaleString('it-IT', {style : 'currency', currency : 'VND'})
          }

        })
        return {
          ...product,
          product_attribute_values : attvalue
        }
      })
      listProduct.value = {
        ...productResult,
      };
    }

    getListProduct(pageCurrent.value);

    return {
      filterKey,
      isShowSort,
      timeDatePicker,
      listProduct,
      pagination,
      addNewUser,
      editUser,
      handleClickSortFn,
      handleAddProduct,
      goToAdd,
      getListProduct,
      handleBackPage,
      handleNextPage,
      goToAddProductAttributeValue,
      listByCategory,
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
