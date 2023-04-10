<template>
  <div class="p-5 mt-8 mx-5 bg-white">
    <div class="">
      <div class="text-xl">
        <span class="text-gray-500">Xuất kho</span>
      </div>
      <div class="mb-5">
        <form @submit.prevent="handleSubmit()">
          <div class="mt-5 flex bg-gray-100 border border-gray-300">
            <div class="w-[5%] py-2 border-r border-gray-300 text-center">
              <span>Danh mục</span>
            </div>
            <div class="w-[35%] py-2 border-r border-gray-300 text-center">
              <span>Sản phẩm</span>
            </div>
            <div class="w-[20%] py-2 border-r border-gray-300 text-center">
              <span>Mã sản phẩm</span>
            </div>
            <div class="w-[13%] py-2 border-r border-gray-300 text-center">
              <span>Tên sản phẩm</span>
            </div>
            <div class="w-[15%] py-2 border-r border-gray-300 text-center">
              <span>Số lượng</span>
            </div>
            <div class="w-[7%] py-2 border-gray-300 text-center">
              <span>Xóa</span>
            </div>
          </div>
          <div>
            <export-good-item v-for="(item, index) in listImportGoodItem" :key="item.key"
                              @handle-remove-input-item="handleRemoveInputItem(item, index)"
                              @check-valid="handleCheckValid"
                              :item="item"
                              :index="index"
                              :count="item.count"
                              :categories="categories"
                              :products="products"
            />
          </div>
          <div class="ml-2 my-4">
            <ButtonAddNew @clickBtn="handleAddToImportGood" :text="'Sản phẩm'"/>
          </div>
          <div class="flex mt-3">
            <span class="text-base text-gray-500 font-semibold">Ngày xuất: </span>
            <Datepicker class="border-b border-gray-200 outline-none ml-2 text-base" v-model="picked" :style="styleDatePicker" :locale="vi" inputFormat="dd-MM-yyyy"/>
          </div>
          <div class="text-base font-bold text-gray-500">
            <input class="mt-5 p-2 border border-gray-500 cursor-pointer hover:bg-gray-500 hover:text-gray-50 rounded-md text-current bg-gray-100"
                   type="submit"
                   value="Tạo đơn xuất hàng"
                   :class="!isAllValid ? 'hover:bg-red-500' : '' "
                   :disabled="!isAllValid"
            >
            <span v-show="!isAllValid" class="text-red-500 text-sm ml-2">Danh sách nhập không hợp lệ!</span>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import {useRouter} from "vue-router";
import {useStore} from "vuex";
import {ref, inject, reactive, onUnmounted} from "vue";
import {
  createExportGoodFromApi,
  createImportGoodFromApi,
  getListCategoryFromApi,
  getListProductFromApi
} from "@/api";
import {MODULE_STORE, ROUTER_PATH} from "@/const";
import ButtonAddNew from "@/components/Buttons/ButtonAddNew";
import ExportGoodItem from "@/views/ExportGoodManage/CreateExport/ExportGoodItem.vue";
import Datepicker from 'vue3-datepicker'
import {vi} from "date-fns/locale";
import { styleDatePicker } from "@/const";
import * as Yup from "yup";

const router = useRouter()
const store = useStore()
const categories = reactive([])
const products = reactive([])
const listImportGoodItem = reactive([])
const importGoodItemErrors = reactive([])
const containerName = ref('')
const toast = inject("$toast")
const picked = ref(new Date())
const isAllValid = ref(true)

const schema = Yup.object().shape({
  category_id: Yup.string().required(),
  product_id: Yup.string().required(),
  product_attribute_value_id: Yup.string().required(),
  product_attribute_price_id: Yup.string().required(),
  count: Yup.number().min(1).typeError("Tối thiểu 1 đơn vị").required(),
  measure_unit_type: Yup.string().required(),
})

const listImportGoodSchema = Yup.object().shape({
  listImportGoodItem: Yup.array().min(1, 'Tối thiểu 1 sản phẩm')
})
const importGoodPostDataSchema = Yup.array().of(schema)

const handleSubmit = async () => {
  try {
    store.state[MODULE_STORE.COMMON.NAME].isLoadingPage = true
    const orderPostData = [...store.state[MODULE_STORE.IMPORT_GOOD.NAME].importGoodPostData]
    await importGoodPostDataSchema.validate(orderPostData, { abortEarly: false })
    await listImportGoodSchema.validate({listImportGoodItem: orderPostData}, { abortEarly: false })
    const year = picked.value.getFullYear()
    const month = ('0' + (picked.value.getMonth() + 1)).slice(-2)
    const day = ('0' + picked.value.getDate()).slice(-2)
    const date = `${year}-${month}-${day}`
    const postData = {
      export_good_products: orderPostData,
      date: date,
    }
    const res = await createExportGoodFromApi(postData)
    toast.success("Tạo đơn nhập thành công!", {duration:3000})
    await router.push(`${ROUTER_PATH.ADMIN}/${ROUTER_PATH.EXPORT_GOOD_MANAGE}`)
    store.state[MODULE_STORE.COMMON.NAME].isLoadingPage = false
  } catch (validateErrors) {
    const listValidateErrors = {};
    if(validateErrors.hasOwnProperty('inner')) {
      validateErrors.inner.forEach((errors) => {
        listValidateErrors[errors.path] = errors.message
      })
      isAllValid.value = false
    }
    toast.error(validateErrors.message)
  } finally {
    store.state[MODULE_STORE.COMMON.NAME].isLoadingPage = false
  }
}

const getListCategory = async () => {
  try {
    const res = await getListCategoryFromApi()
    res.data.forEach((category) => {
      if (category.category_id === '01GF2WV4414C8MYFAGPB4BQS2R')
        categories.push({...category, id: category.category_id})
    })
  } catch (errors) {
    toast.error(errors.message)
  }
}

const getListProduct = async () => {
  const res = await getListProductFromApi();
  res.data.forEach((product) => {
    if(!!product.product_attribute_values[0] && product.product_attribute_values[0].measure_unit_name === 'roll')
      products.push(product)
  })
}

const handleAddToImportGood = () => {
  const payload = {
    category_id: '',
    product_id: '',
    product_attribute_value_id: '',
    product_attribute_price_id: '',
    count: 0,
    measure_unit_type: '',
    key: Math.floor(Math.random() * 10000)
  }
  store.commit(`${MODULE_STORE.IMPORT_GOOD.NAME}/${MODULE_STORE.IMPORT_GOOD.MUTATIONS.ADD_IMPORT_GOOD_DATA}`, payload)
  listImportGoodItem.push(payload)
}
const handleRemoveInputItem = (item, index) => {
  if (listImportGoodItem[index] === item) {
    listImportGoodItem.splice(index, 1)
  } else {
    const found = listImportGoodItem.indexOf(item)
    listImportGoodItem.splice(found, 1)
  }
  store.commit(`${MODULE_STORE.IMPORT_GOOD.NAME}/${MODULE_STORE.IMPORT_GOOD.MUTATIONS.REMOVE_IMPORT_GOOD_DATA_ITEM}`, {item, index})
}
const handleCheckValid = () => {
  const orderPostData = [...store.state[MODULE_STORE.IMPORT_GOOD.NAME].importGoodPostData]
  let listValidateErrors = {};
  importGoodPostDataSchema.validate(orderPostData, {abortEarly: false})
    .catch((validateErrors) => {
      isAllValid.value = false
      if (validateErrors.hasOwnProperty('inner')) {
        listValidateErrors = validateErrors.inner
        validateErrors.inner.forEach((errors) => {
          listValidateErrors[errors.path] = errors.message
        })
      }
    })
  isAllValid.value = !listValidateErrors.hasOwnProperty('inner') && listImportGoodItem.length > 0
}

onUnmounted(() => {
  store.commit(`${MODULE_STORE.IMPORT_GOOD.NAME}/${MODULE_STORE.IMPORT_GOOD.MUTATIONS.CLEAR_IMPORT_GOOD_DATA_ITEM}`, [])
})

store.state[MODULE_STORE.COMMON.NAME].breadcrumbCurrent = 'Tạo xuất kho'
store.state[MODULE_STORE.COMMON.NAME].breadcrumbItems = [
  {
    label: 'Trang chủ',
    link: '/dashboard'
  },
  {
    label: 'Xuất kho',
    link: '/export-good-manage'
  },
]

getListCategory()
getListProduct()
</script>

<style scoped>

</style>
