<template>
  <div class="p-5 mt-8 mx-5 bg-white">
    <div class="w-full pt-14 h-full pl-10">
      <div class="w-full py-6 py-auto text-xl">
        <span class="text-gray-500">Thêm sản phẩm</span>
        <hr>
      </div>
      <form @submit.prevent="handleSubmit(formData)">
        <div class="mt-5 py-3 flex">
          <div class="mr-4 w-[18%]">
            <span>Danh mục</span>
          </div>
          <div class="mr-4 w-[18%]">
            <span>Sản phẩm</span>
          </div>
          <div class="mr-4 w-[18%]">
            <span>Mã sản phẩm</span>
          </div>
          <div class="mr-4 w-[10%]">
            <span>Tên sản phẩm</span>
          </div>
          <div class="mr-4 w-[10%]">
            <span>Số lượng</span>
          </div>
          <div class="mr-4 w-[10%]">
            <span>Xóa sp</span>
          </div>
        </div>

        <div v-if="renderComponent">
          <import-good-item v-for="(item, index) in listImportGoodItem" :key="index"
                      @handle-remove-input-item="handleRemoveInputItem({item, index})"
                      @item-valid="checkItemValid(error)"
                      :item="item"
                      :index="index"
                      :categories="categories"
                      :dealers="dealers"
                      :products="products"
          />
        </div>
        <div class="ml-2 my-4">
          <ButtonAddNew @clickBtn="handleAddToImportGood" :text="'Sản phẩm'"/>
        </div>
        <div class="flex mt-3 items-end">
          <span>Ngày nhập: </span>
          <Datepicker class="p-3 border border-gray-200 mt-3 outline-none" v-model="picked" :style="styleDatePicker" :locale="vi" inputFormat="dd-MM-yyyy"/>
        </div>
        <div class="flex mt-3 items-end">
          <span>Tên container: </span>
          <input type="text" class="outline-none border-gray-300 border ml-3 min-w-[150px] p-2" v-model="containerName">
        </div>
        <div>
          <input class="w-25 h-10 mt-5 px-3 text-base text-gray-700 placeholder-gray-400 bg-green-400 cursor-pointer" type="submit" value="Tạo đơn nhập hàng">
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import {useRouter} from "vue-router";
import {useStore} from "vuex";
import {ref, nextTick, inject, onUnmounted} from "vue";
import {
  createImportGoodFromApi,
  getListCategoryFromApi,
  getListDealerFromApi,
  getListProductFromApi
} from "@/api";
import {MODULE_STORE, ROUTER_PATH} from "@/const";
import ButtonAddNew from "@/components/Buttons/ButtonAddNew";
import ImportGoodItem from "@/views/ImportGoodManage/CreateImportGood/ImportGoodItem.vue";
import Datepicker from 'vue3-datepicker'
import {vi} from "date-fns/locale";
import { styleDatePicker } from "@/const";

const router = useRouter()
const store = useStore()
const formData = ref({})
const categories = ref({})
const dealers = ref({})
const products = ref({})
const productsByCategory = ref({})
const productSelected = ref({})
const productAttributeValues = ref({})
const productAttributeValuesByProduct = ref({})
const listImportGoodItem = ref(store.state[MODULE_STORE.IMPORT_GOOD.NAME].importGoodPostData)
const importGoodItemErrors = ref([])
const containerName = ref('')
const toast = inject("$toast");
const picked = ref(new Date())

const handleSubmit = async () => {
  try {
    store.state[MODULE_STORE.COMMON.NAME].isLoadingPage = true
    const orderPostData = [...store.state[MODULE_STORE.IMPORT_GOOD.NAME].importGoodPostData]
    const year = picked.value.getFullYear()
    const month = ('0' + (picked.value.getMonth() + 1)).slice(-2)
    const day = ('0' + picked.value.getDate()).slice(-2)
    const date = `${year}-${month}-${day}`
    const postData = {
      import_good_products: orderPostData,
      date: date,
      container_name: containerName.value
    }
    const res = await createImportGoodFromApi(postData)
    toast.success("Tạo đơn nhập thành công!", {duration:3000})
    store.commit(`${MODULE_STORE.IMPORT_GOOD.NAME}/${MODULE_STORE.IMPORT_GOOD.MUTATIONS.CLEAR_IMPORT_GOOD_DATA_ITEM}`, [])
    await router.push(`${ROUTER_PATH.ADMIN}/${ROUTER_PATH.IMPORT_GOOD_MANAGE}`)
    store.state[MODULE_STORE.COMMON.NAME].isLoadingPage = false
  } catch (errors) {
    const error = errors.message;
    toast.error(error)
  } finally {
    store.state[MODULE_STORE.COMMON.NAME].isLoadingPage = false
  }
}

const getListCategory = async () => {
  try {
    const res = await getListCategoryFromApi()
    categories.value = res.data.reduce( (option, data) => {
      return [
        ...option,
        {
          name: data.name,
          id: data.category_id
        }
      ]
    }, [])
    formData.value.category = res.data[0].category_id
    store.state[MODULE_STORE.IMPORT_GOOD.NAME].categories = res.data
  } catch (errors) {
    toast.error(errors.message)
  }
}

const getListProduct = async () => {
  const res = await getListProductFromApi();
  products.value = res.data
  store.state[MODULE_STORE.IMPORT_GOOD.NAME].products = res.data
}

const getListDealer = async () => {
  const res = await getListDealerFromApi();
  dealers.value = {
    ...res.data
  }
}

const handleOnChangeCategorySelect = () => {
  productsByCategory.value = products.value.filter((product) => {
    return product.category_id === formData.value.category
  })
}
const handleOnChangeProductSelect = () => {
  productSelected.value = products.value.filter((product) => {
    return product.product_id === formData.value.product
  })[0]

  productAttributeValuesByProduct.value = productSelected.value.product_attribute_values
}
const handleAddToImportGood = () => {
  const payload = {
    category_id: '',
    product_id: '',
    product_attribute_value_id: '',
    product_attribute_price_id: '',
    count: '',
    measure_unit_type: '',
    checked: false
  }
  store.commit(`${MODULE_STORE.IMPORT_GOOD.NAME}/${MODULE_STORE.IMPORT_GOOD.MUTATIONS.ADD_IMPORT_GOOD_DATA}`, payload)
  listImportGoodItem.value = []
  nextTick(() => {listImportGoodItem.value = store.state[MODULE_STORE.IMPORT_GOOD.NAME].importGoodPostData})
}
const handleRemoveInputItem = (item) => {
  store.commit(`${MODULE_STORE.IMPORT_GOOD.NAME}/${MODULE_STORE.IMPORT_GOOD.MUTATIONS.REMOVE_IMPORT_GOOD_DATA_ITEM}`, item)
  listImportGoodItem.value = []
  nextTick(() => {listImportGoodItem.value = store.state[MODULE_STORE.IMPORT_GOOD.NAME].importGoodPostData})
}

// const checkItemValid = (error) => {
//   if (error.isValid === true) {
//     importGoodItemErrors.value[error.index] = error.isValid
//   } else {
//     importGoodItemErrors.value.splice(error.index, 1)
//   }
// }
onUnmounted(() => {
  store.commit(`${MODULE_STORE.IMPORT_GOOD.NAME}/${MODULE_STORE.IMPORT_GOOD.MUTATIONS.CLEAR_IMPORT_GOOD_DATA_ITEM}`, [])
})

store.state[MODULE_STORE.COMMON.NAME].breadcrumbCurrent = 'Chi tiết nhập kho'
store.state[MODULE_STORE.COMMON.NAME].breadcrumbItems = [
  {
    label: 'Trang chủ',
    link: '/dashboard'
  },
  {
    label: 'Nhập kho',
    link: '/dashboard'
  },
]

getListCategory()
getListDealer()
getListProduct()
</script>

<style scoped>

</style>
