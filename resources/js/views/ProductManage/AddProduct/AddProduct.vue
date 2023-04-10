<template>
  <div class="w-full">
    <div class="w-[650px] mt-5 ml-5 bg-white border-t-[2px] border-[#e7eaec]">
      <div class="py-4 px-3 border-b border-[#e7eaec] text-md text-gray-700">
        Tạo sản phẩm mới
      </div>
      <form @submit.prevent="handleSubmit" class="p-3">
        <div class="py-1">
          <label for="category" class="block py-2 font-bold text-lg">
            <span>Chọn danh mục</span>
            <span v-if="errors.category" class="ml-1 text-red-500">*</span>
          </label>
          <select name="category" class="p-3 w-full outline-none" v-model="categoryId" @change="validSelectCategory">
            <option disabled value="" class="w-full h-10 px-3 text-base text-gray-700" selected>Chọn danh mục</option>
            <option v-for="item in categories" :value="item.id" class="w-full px-3 text-base text-gray-700 py-2">
              {{ item.name }}
            </option>
          </select>
        </div>
        <div class="py-1">
          <label for="name" class="block py-2 font-bold text-lg">
            <span>Nhập tên sản phẩm</span>
            <span class="text-xs text-gray-400 ml-1 font-extralight">(Tối thiểu 3 ký tự)</span>
            <span v-if="errors.name" class="ml-1 text-red-500">*</span>
          </label>
          <input type="text" name="name" placeholder="Nhập tên sản phẩm" v-model="name" @input="validInputName"
                 class="w-full h-10 px-3 text-base text-gray-700 placeholder-gray-400 border border-gray-400 focus:border-[#8ddd8d] outline-none"
          >
        </div>
        <div class="py-1">
          <label for="price" class="block py-2 font-bold text-lg">
            <span>Giá thành</span>
            <span class="text-xs text-gray-400 ml-1 font-extralight">(Tối thiểu 1000đ)</span>
            <span v-if="errors.price" class="ml-1 text-red-500">*</span>
          </label>
          <input type="number" name="price" placeholder="Nhập giá sản phẩm" v-model="price" @input="validInputPrice"
                 class="w-full h-10 px-3 text-base text-gray-700 placeholder-gray-400 border border-gray-400 focus:border-[#8ddd8d] outline-none"
          >
        </div>
        <div class="py-1 flex">
          <div class="w-1/3">
            <label for="description" class="block py-2 font-bold text-lg">
              <span>Đơn vị tính</span>
              <span v-if="errors.measureUnitType" class="ml-1 text-red-500">*</span>
            </label>
            <select name="measure" class="p-3 outline-none" v-model="measureUnitType" @change="handleSelectMeasureUnit" @chang="validSelectMeasureUnitType">
              <option disabled value="" class="w-full h-10 px-3 text-base text-gray-700" selected>Chọn đơn vị tính</option>
              <option v-for="(item, index) in measures" :value="item.name" :key="index"
                      class="w-full h-10 px-3 text-base text-gray-700">{{ t(`measure_unit_type.${item.name}`) }}
              </option>
            </select>
          </div>
          <div class="w-1/3" v-if="isShowNoticePrice">
            <label for="notice_price" class="block py-2 font-bold text-lg">
              <span>Loại báo giá</span>
              <span v-if="errors.noticePriceType" class="ml-1 text-red-500">*</span>
            </label>
            <select name="notice_price" class="p-3 outline-none" v-model="noticePriceType" @chang="validSelectNoticePrice">
              <option disabled value="" class="w-full h-10 px-3 text-base text-gray-700" selected>Khối lượng cuộn</option>
              <option v-for="(item, index) in noticePrices" :value="item" :key="index"
                      class="w-full h-10 px-3 text-base text-gray-700">{{ item }}
              </option>
            </select>
          </div>
        </div>
        <div class="flex justify-end border-t border-[#e7eaec]">
          <input class="mt-3 p-2 text-base font-bold text-white bg-[#1ab394] hover:bg-[#18a689] cursor-pointer rounded-md"
                 type="submit" value="Tạo sản phẩm">
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import {inject, reactive, ref} from "vue"
import {useRouter} from "vue-router"
import {useStore} from "vuex"
import {MODULE_STORE, ROUTER_PATH} from "@/const"
import {createProductFromApi, getListCategoryFromApi, getListMeasureUnitFromApi} from "@/api"
import { useI18n } from "vue-i18n"
import * as Yup from "yup";

const router = useRouter()
const store = useStore()
const { t } = useI18n();
const categories = ref()
const toast = inject('$toast')
const name = ref('')
const price = ref(null)
const categoryId = ref(null)
const noticePriceType = ref(null)
const measureUnitType = ref(null)
const measures = ref([])
const isShowNoticePrice = ref(false)
const errors = reactive({})
const noticePrices = ref([
  '298kg',
  '273kg',
  '248kg',
  '224kg',
  '214kg',
  '190kg'
])

const schema = Yup.object().shape({
  name: Yup.string().required().min(3, 'Tối thiểu 3 ký tự'),
  price: Yup.number().min(1000).typeError("Tối thiểu 1000đ"),
  category: Yup.string().required(),
  measureUnitType: Yup.string().required(),
  noticePriceType: Yup.string().when('measureUnitType', {
    is: (measureUnitType) => measureUnitType && measureUnitType.trim() === 'roll',
    then: Yup.string().required(),
    otherwise: Yup.string().nullable().notRequired(),
  })
})
const categorySchema = Yup.object().shape({
  category: Yup.string().required()
})
const priceSchema = Yup.object().shape({
  price: Yup.number().min(1000).typeError("Tối thiểu 1000đ")
})
const nameSchema = Yup.object().shape({
  name: Yup.string().required().min(3, 'Tối thiểu 3 ký tự')
})
const measureSchema = Yup.object().shape({
  measureUnitType: Yup.string().required()
})
const noticePriceSchema = Yup.object().shape({
  measureUnitType: Yup.string().required(),
  noticePriceType: Yup.string().when('measureUnitType', {
    is: (measureUnitType) => measureUnitType && measureUnitType.trim() === 'roll',
    then: Yup.string().required(),
    otherwise: Yup.string().nullable().notRequired()
  })
})

const handleSubmit = async () => {
  try {
    await schema.validate(
      { name: name.value, price: price.value, category: categoryId.value, measureUnitType: measureUnitType.value, noticePriceType: noticePriceType.value },
      { abortEarly: false }
    )
    const data = {
      category_id: categoryId.value,
      name: name.value,
      price: price.value,
      measure_unit_type: measureUnitType.value,
      notice_price_type: noticePriceType.value,
    }
    const res = await createProductFromApi(data)
    await router.push(`${ROUTER_PATH.ADMIN}/${ROUTER_PATH.PRODUCT_MANAGE}`)
  } catch (validationErrors) {
    if (validationErrors.hasOwnProperty('inner')) {
      validationErrors.inner.forEach((error) => {
        errors[error.path] = error.message;
      });
    }

    toast.error(validationErrors.message)
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
  } catch (errors) {
    const error = errors.message;
    toast.error(error, {duration: 3000})
  }
}
const getMeasureUnits = async (page) => {
  try {
    const res = await getListMeasureUnitFromApi(page)
    measures.value = res.data
  } catch (errors) {
    const error = errors.message;
  }
}
const handleSelectMeasureUnit = () => {
  isShowNoticePrice.value = measureUnitType.value === 'roll'
}

const validSelectCategory = async () => {
  try {
    await categorySchema.validate(
      { category: categoryId.value },
      { abortEarly: false }
    )
    delete errors['category'];
  } catch (validationErrors) {
    validationErrors.inner.forEach((error) => {
      errors[error.path] = error.message;
    });
  }
}

const validInputName = async () => {
  try {
    await nameSchema.validate(
      { name: name.value },
      { abortEarly: false }
    )
    delete errors['name'];
  } catch (validationErrors) {
    validationErrors.inner.forEach((error) => {
      errors[error.path] = error.message;
    });
  }
}

const validInputPrice = async () => {
  try {
    await priceSchema.validate(
      { price: price.value },
      { abortEarly: false }
    )
    delete errors['price'];
  } catch (validationErrors) {
    validationErrors.inner.forEach((error) => {
      errors[error.path] = error.message;
    });
  }
}

const validSelectMeasureUnitType = async () => {
  try {
    await measureSchema.validate(
      { measureUnitType: measureUnitType.value },
      { abortEarly: false }
    )
    delete errors['measureUnitType'];
  } catch (validationErrors) {
    validationErrors.inner.forEach((error) => {
      errors[error.path] = error.message;
    });
  }
}

const validSelectNoticePrice = async () => {
  try {
    await noticePriceSchema.validate(
      { measureUnitType: measureUnitType.value, noticePriceType: noticePriceType.value },
      { abortEarly: false }
    )
    delete errors['noticePriceType'];
  } catch (validationErrors) {
    validationErrors.inner.forEach((error) => {
      errors[error.path] = error.message;
    });
  }
}
store.state[MODULE_STORE.COMMON.NAME].breadcrumbCurrent = 'Tạo sản phẩm'
store.state[MODULE_STORE.COMMON.NAME].breadcrumbItems = [
  {
    label: 'Trang chủ',
    link: '/dashboard'
  },
  {
    label: 'Sản phẩm',
    link: '/product-manage'
  }
]

getListCategory()
getMeasureUnits()
</script>

<style scoped></style>
