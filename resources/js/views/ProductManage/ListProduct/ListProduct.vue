<template>
  <div class="px-5 mt-10 min-h-[600px]">
    <div class="bg-white p-2 h-auto mb-20">
      <div class="w-full h-8 flex justify-between">
        <div class="flex">
          <div class="mr-1 relative">
            <input
              type="text"
              class="outline-none border-gray-300 border min-w-[150px] h-full px-10 rounded-sm"
              @input="handleFilter"
              v-model="filterText"
              placeholder="Tìm theo tên"
            >
            <div class="absolute top-[50%] left-2 -translate-y-1/2">
              <SearchIcon />
            </div>
          </div>
          <ButtonFilter @clickBtn="listByCategory('all')" text='Tất cả'/>
          <ButtonFilter @clickBtn="listByCategory('jumbo')" text='Jumbo'/>
          <ButtonFilter @clickBtn="listByCategory('finishedProduct')" text='Thành phẩm'/>
          <ButtonFilter @clickBtn="listByCategory('other')" text='Khác'/>
        </div>
        <div class="ml-2">
          <ButtonAddNew @clickBtn="handleAddProduct" :text="addNewProduct"/>
        </div>
      </div>

      <!-- *********** -->
      <div class="mt-4">
        <table class="w-full">
          <thead class="bg-gray-100">
            <tr>
              <th rowspan="2" class="border py-1 w-[2%]">
                #
              </th>
              <th rowspan="2" class="border py-1 w-[5%]">
                Tên sản phẩm
              </th>
              <th rowspan="2" class="border py-1 w-[5%]">
                Danh mục
              </th>
              <th colspan="3" class="border py-1 w-[10%]">
                Báo giá
              </th>
              <th colspan="2" class="border py-1 w-[14%]">
                Mã sản phẩm
              </th>
              <th rowspan="2" class="border py-1 w-[5%]">
                Cập nhật
              </th>
            </tr>
            <tr>
              <th class="border py-1">Giá</th>
              <th class="border py-1">Đơn giá / kg</th>
              <th class="border py-1">Sửa</th>
              <th class="border py-1">Mã</th>
              <th class="border py-1">Tồn kho</th>
            </tr>
          </thead>
          <tbody class="[&>tr:nth-child(odd)]:bg-[#f9f9f9]">
          <template v-for="(item, index) in listProduct">
            <tr v-if="item.product_attribute_values.length === 0">
              <td class="border text-center">{{ ++index }}</td>
              <td class="border text-center">{{ item.name }}</td>
              <td class="border text-center">{{ item.category_name }}</td>
              <td class="border text-center">{{ `${item.name} x ${item.notice_price_type} x ${item.price.toLocaleString('it-IT', {style : 'currency', currency : 'VND'})}` }}</td>
              <td class="border text-center">{{ `${item.standard_price.toLocaleString('it-IT', {style : 'currency', currency : 'VND'})}` }}</td>
              <td class="border text-center"></td>
              <td class="border text-center"></td>
              <td class="border text-center"></td>
              <td class="border text-center">
                <div class="flex justify-center ">
                  <ButtonAddNew @clickBtn="() => goToAddProductAttributeValue(item.product_id)" text='Thêm mã'/>
                  <!--                <ButtonEdit @clickBtn="() => goToAdd(item.product_id)" :text="editProduct"/>-->
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
                {{ item.category_name }}
              </td>
              <td v-if="subIndex === 0" :rowspan="item.product_attribute_values.length" class="border text-center h-full m-0 py-1">
                {{ `${item.name} ${subItem.code} x ${item.notice_price_type} x ${item.price.toLocaleString('it-IT', {style : 'currency', currency : 'VND'})}` }}
              </td>
              <td v-if="subIndex === 0" :rowspan="item.product_attribute_values.length" class="border text-center h-full m-0 py-1">
                {{ `${item.standard_price.toLocaleString('it-IT', {style : 'currency', currency : 'VND'})}` }}
              </td>
              <td v-if="subIndex === 0" :rowspan="item.product_attribute_values.length" class="border text-center h-full m-0 py-1">
                <div class="flex justify-center ">
                  <ButtonEdit
                    @clickBtn="() => openQuoteModal(item.original_product_attribute_price_id, item.name, subItem.code, item.notice_price_type, item.price, item.original_product_attribute_value_id, item.product_id)"
                    text=' '
                  />
                </div>
              </td>
              <td class="border text-center h-full m-0 py-1">
                {{ `${item.code} ${subItem.code}` }}
              </td>
              <td class="border text-center h-full m-0 py-1">
                {{ subItem.count + ' ' + t(`measure_unit_type.${subItem.measure_unit_name}`) }}
              </td>
              <td v-if="subIndex === 0" :rowspan="item.product_attribute_values.length" class="border text-center">
                <div class="flex justify-center ">
                  <ButtonAddNew @clickBtn="() => goToAddProductAttributeValue(item.product_id)" text="Thêm mã"/>
                  <!--                <ButtonEdit @clickBtn="() => goToAdd(item.product_id)" :text="editProduct"/>-->
                </div>
              </td>
            </tr>
          </template>
          </tbody>
        </table>
      </div>

<!--      <Pagination-->
<!--        v-if="pagination"-->
<!--        :pageCurrent="pagination.current_page"-->
<!--        :totalPage="pagination.total"-->
<!--        @onBack="handleBackPage"-->
<!--        @onNext="handleNextPage"-->
<!--      />-->
    </div>
  </div>
    <QuotePriceModal
      v-if="showModal"
      @close="showModal = false"
      @update="handleUpdatePriceSuccess"
      :data="quotePriceData"
    />
</template>

<script setup>
import {ROUTER_PATH, MODULE_STORE, PAGE_DEFAULT} from "@/const";
import ButtonAddNew from "@/components/Buttons/ButtonAddNew";
import ButtonFilter from "@/components/Buttons/ButtonFilter";
import ButtonEdit from "@/components/Buttons/ButtonEdit";
import {getListProductFromApi,} from "@/api";
import {ref, computed, inject} from "vue";
import {useRouter, useRoute} from "vue-router";
import {useStore} from "vuex";
import Pagination from "@/components/Pagination";
import {useI18n} from "vue-i18n";
import QuotePriceModal from "@/views/ProductManage/ListProduct/QuotePriceModal.vue";
import SearchIcon from "@/components/icons/SearchIcon.vue";

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
const addNewProduct = "Thêm sản phẩm mới";
const editProduct = "Sửa";
const filterText = ref('')
const showModal = ref(false);
const quotePriceData = ref({})

const pageCurrent = computed(() => {
  if (!route.query.page) {
    return PAGE_DEFAULT;
  }
  return Number(route.query.page);
});
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
    page = null
    store.state[MODULE_STORE.COMMON.NAME].isLoadingPage = true
    const response = await getListProductFromApi(page, categoryIds)
    pagination.value = response.pagination
    const productResult = response.data.map((product) => {
      const attvalue = product.product_attribute_values.map((attributeValue) => {
        return {
          ...attributeValue,
          price: attributeValue.price.toLocaleString('it-IT', {style : 'currency', currency : 'VND'}),
          originPrice: attributeValue.price
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
// const handleBackPage = (page) => {
//   router.push(`${ROUTER_PATH.USER_MANAGER}?page=${page}`);
// }
// const handleNextPage = (page) => {
//   router.push(`${ROUTER_PATH.USER_MANAGER}?page=${page}`);
// }
const handleFilter = async () => {
  const res = await getListProductFromApi(pageCurrent.value, {params: {keyword: filterText.value}})
  const productResult = res.data.map((product) => {
    const attvalue = product.product_attribute_values.map((attributeValue) => {
      return {
        ...attributeValue,
        price: attributeValue.price.toLocaleString('it-IT', {style : 'currency', currency : 'VND'}),
        originPrice: attributeValue.price
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
const listByCategory = async (category) => {
  let res;
  switch (category) {
    case 'all':
      res = await getListProductFromApi(pageCurrent.value, )
      break
    case 'jumbo':
      res = await getListProductFromApi(pageCurrent.value, {
          params: {
            category_ids: ["01GF2WV4414C8MYFAGPB4BQS2R"]}
        }
      )
      break
    case 'finishedProduct':
      res = await getListProductFromApi(pageCurrent.value, {
        params: {
          category_ids: ["01GF2WV441GPB4BQS2R4C8MYFA"]}
      })
      break
    case 'other':
      res = await getListProductFromApi(pageCurrent.value, {
        params: {
          category_ids: ["01GF2W4BQS2R4C8MYFAV441GPB"]}
      })
      break
  }

  const productResult = res.data.map((product) => {
    const attvalue = product.product_attribute_values.map((attributeValue) => {
      return {
        ...attributeValue,
        price: attributeValue.price.toLocaleString('it-IT', {style : 'currency', currency : 'VND'}),
        originPrice: attributeValue.price
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

const openQuoteModal = (id, name, code, noticePriceType, price, productAttributeValueId, productId) => {
  quotePriceData.value = {
    id: id,
    name: name,
    code: code,
    noticePriceType: noticePriceType,
    price: price,
    productAttributeValueId: productAttributeValueId,
    productId: productId
  }
  showModal.value = true
}
const handleUpdatePriceSuccess = async () => {
  const res = await getListProductFromApi(pageCurrent.value, {params: {keyword: filterText.value}})
  const productResult = res.data.map((product) => {
    const attvalue = product.product_attribute_values.map((attributeValue) => {
      return {
        ...attributeValue,
        price: attributeValue.price.toLocaleString('it-IT', {style : 'currency', currency : 'VND'}),
        originPrice: attributeValue.price
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

store.state[MODULE_STORE.COMMON.NAME].breadcrumbCurrent = 'Sản phẩm'
store.state[MODULE_STORE.COMMON.NAME].breadcrumbItems = [
  {
    label: 'Trang chủ',
    link: '/dashboard'
  },
]

getListProduct(pageCurrent.value);
</script>

<style scoped>
</style>
