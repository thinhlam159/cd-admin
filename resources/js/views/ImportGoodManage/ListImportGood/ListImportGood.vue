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
          <th rowspan="2" class="border py-1 w-[9%]">
            Ngày tạo đơn
          </th>
          <th colspan="2" class="border py-1 w-[20%]">
            Chi Tiết
          </th>
          <th rowspan="2" class="border py-1 w-[10%]">
            Cập nhật
          </th>
        </tr>
        <tr>
          <th class="border py-1">Mã</th>
          <th class="border py-1">Nhập kho</th>
        </tr>
        </thead>
        <tbody>
        <template v-for="(item, index) in listImportGood">
          <tr v-if="item.import_good_products.length === 0">
            <td class="border text-center">{{ ++index }}</td>
            <td class="border text-center">{{ item.user_name }}</td>
            <td class="border text-center">{{ item.date }}</td>
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
          <tr v-else v-for="(subItem, subIndex) in item.importGoodProducts"
              :key="subItem.product_attribute_value_id">
            <td v-if="subIndex === 0" :rowspan="item.import_good_products.length" class="border text-center">
              {{ ++index }}
            </td>
            <td v-if="subIndex === 0" :rowspan="item.import_good_products.length" class="border text-center">
              {{ item.user_name }}
            </td>
<!--            <td v-if="subIndex === 0" :rowspan="item.import_good_products.length" class="border text-center">-->
<!--              {{ item.import_good_date }}-->
<!--            </td>-->
            <td v-if="subIndex === 0" :rowspan="item.import_good_products.length" class="border text-center">
              {{ item.data }}
            </td>

            <td class="border text-center h-full m-0 p-0">
              {{ `${subItem.product_code} ${subItem.product_attribute_value_code}` }}
            </td>
            <td class="border text-center h-full m-0 p-0">
              {{ `${subItem.count} ${subItem.measureUnitType}` }}
            </td>
            <td v-if="subIndex === 0" :rowspan="item.import_good_products.length" class="border text-center">
              <div class="flex justify-center ">
                <ButtonEdit @clickBtn="() => goToDetail(item.import_good_id)" :text="editUser"/>
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
      @on-back="handleBackPage"
      @on-next="handleNextPage"
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
import {convertDateByTimestamp} from "@/utils";

export default {
  name: "ListImportGood",

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
    const pagination = ref();
    const addNewImportGood = "Tạo đơn nhập kho";
    const orderDetail = "Chi tiết";
    const listImportGood = ref([]);
    const listMeasureUnitType = ref([
      {
        name: 'kg',
        type: 'kg'
      },
      {
        name: 'met',
        type: 'met'
      },
      {
        name: 'cuộn',
        type: 'roll'
      },
    ])

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
      router.push(`${ROUTER_PATH.IMPORT_GOOD_MANAGE}/${ROUTER_PATH.DETAIL}/${id}`);
    }

    const getListImportGood = async (param) => {
      try {
        const res = await getListImportGoodFromApi(param)
        listImportGood.value = res.data.map((item) => {
          return {
            ...item,
            data: convertDateByTimestamp(item.import_good_date),
            importGoodProducts: item.import_good_products.map( product =>  {
              return {
                ...product,
                measureUnitType: listMeasureUnitType.value.find( type => type.type === product.measure_unit_type ).name
              }
            })
          }
        })
        pagination.value = res.pagination
      } catch (error) {
        store.state[MODULE_STORE.COMMON.NAME].isLoadingPage = false;
      }
    }
    const handleBackPage = (page) => {
      getListImportGood({page})
    }
    const handleNextPage = (page) => {
      getListImportGood({page})
    }

    getListImportGood({page: pageCurrent.value});

    return {
      pagination,
      addNewImportGood,
      orderDetail,
      listImportGood,
      handleCreateOrder,
      goToDetail,
      getListImportGood,
      handleBackPage,
      handleNextPage,
      goToAdd
    }
  }
}
</script>

<style scoped>
/*tr:nth-child(even){background-color: #f2f2f2;}*/
</style>
