<template>
  <!--  <FormUserManage />-->
  <div class="w-full">
    <div class="w-[650px] mt-5 ml-5 bg-white border-t-[2px] border-[#e7eaec]">
      <div class="py-4 px-3 border-b border-[#e7eaec] text-md text-gray-700">
        <span class="text-gray-500">Thêm sản mã cho sản phẩm</span>
      </div>
      <div class="px-4">
        <div class="py-4 text-lg text-gray-700">
          <span>Tên sản phẩm: {{ product.name }}</span>
        </div>
        <form @submit.prevent="handleSubmit(formData)">
          <div class="py-1">
            <label for="code" class="block py-2 font-bold text-lg">
              <span>Mã loại sp</span>
              <span class="text-xs text-gray-400 ml-1 font-extralight">(Tối thiểu 1 ký tự)</span>
              <span v-if="errors.code" class="ml-1 text-red-500">*</span>
            </label>
            <input type="text" name="name" placeholder="Nhập mã loại sản phẩm" v-model="formData.code" @input="validInputCode(formData.code)"
                   class="w-full h-10 px-3 text-base text-gray-700 placeholder-gray-400 border border-gray-400 focus:border-[#8ddd8d] outline-none"
            >
          </div>
          <div class="py-1">
            <label for="description" class="block py-2 font-bold text-lg">
              <span>Số lượng sản phẩm</span>
              <span class="text-xs text-gray-400 ml-1 font-extralight">(Tối thiểu 1 sản phẩm)</span>
              <span v-if="errors.count" class="ml-1 text-red-500">*</span>
            </label>
            <input name="price" placeholder="Số lượng sản phẩm ban đầu" v-model="formData.count" @input="validInputCount(formData.count)"
                   class="w-full h-10 px-3 text-base text-gray-700 placeholder-gray-400 border border-gray-400 focus:border-[#8ddd8d] outline-none"
            />
          </div>
          <div class="flex justify-end mt-3 py-3 border-t border-[#e7eaec] items-center">
            <input class="p-2 text-base font-bold text-white bg-[#1ab394] hover:bg-[#18a689] cursor-pointer rounded-md"
                   type="submit" value="Tạo sản phẩm">
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import {inject, reactive, ref} from "vue";
import {useRoute, useRouter} from "vue-router";
import {useStore} from "vuex";
import {MODULE_STORE, ROUTER_PATH} from "@/const";
import {
  createProductAttributeValueFromApi,
  getListMeasureUnitFromApi,
  getListProductAttributeFromApi,
  getProductDetailFromApi
} from "@/api";
import logoTimeSharing from "@/assets/images/default-thumbnail.jpg";
import Breadcrumb from "@/components/Breadcrumb/Breadcrumb.vue";
import * as Yup from "yup";

const router = useRouter()
const route = useRoute()
const store = useStore()
const toast = inject('$toast')
const formData = ref({})
const imageUrl = ref(logoTimeSharing)
const imageBuffer = ref(null)
const file = ref(null)
const product = ref({})
const productId = ref(route.params.id)
const measures = ref([])
const productAttributes = ref([])
const errors = reactive({})

const schema = Yup.object().shape({
  code: Yup.string().required(),
  count: Yup.number().required().min(1).typeError("Tối thiểu 1 sản phẩm"),
})
const codeSchema = Yup.object().shape({
  code: Yup.string().required()
})
const countSchema = Yup.object().shape({
  count: Yup.number().required().min(1).typeError("Tối thiểu 1 sản phẩm"),
})

const handleSubmit = async (data) => {
  try {
    await schema.validate(
      { code: data.code, count: data.count },
      { abortEarly: false }
    )
    const bodyFormData = new FormData()
    bodyFormData.append('product_id', productId.value);
    bodyFormData.append('product_attribute_id', productAttributes.value[0].id);
    bodyFormData.append('measure_unit_type', product.value.measure_unit_type);
    bodyFormData.append('value', data.code);
    bodyFormData.append('code', data.code);
    bodyFormData.append('price', product.value.price);
    bodyFormData.append('count', data.count);
    bodyFormData.append('notice_price_type', product.value.notice_price_type);
    const res = await createProductAttributeValueFromApi(bodyFormData)
    await router.push(`${ROUTER_PATH.ADMIN}/${ROUTER_PATH.PRODUCT_MANAGE}`)
    toast.success('Thêm mã sản phẩm thành công', {duration: 3000});
  } catch (validationErrors) {
    if (validationErrors.hasOwnProperty('inner')) {
      validationErrors.inner.forEach((error) => {
        errors[error.path] = error.message;
      });
    }
    console.log(errors[0])
    toast.error(errors.message);
  } finally {
    store.state[MODULE_STORE.COMMON.NAME].isLoadingPage = false;
  }
}

const getProduct = async (id) => {
  try {
    const res = await getProductDetailFromApi(id)
    product.value = {
      ...res.data
    }
  } catch (errors) {
    const error = errors.message;
  }
}

const getProductAttributes = async (page) => {
  try {
    const res = await getListProductAttributeFromApi(page)
    productAttributes.value = res.data
  } catch (errors) {
    const error = errors.message;
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

const validInputCode = async (code) => {
  try {
    await codeSchema.validate(
      { code: code },
      { abortEarly: false }
    )
    delete errors['code'];
  } catch (validationErrors) {
    validationErrors.inner.forEach((error) => {
      errors[error.path] = error.message;
    });
  }
}

const validInputCount = async (count) => {
  try {
    await countSchema.validate(
      { count: count },
      { abortEarly: false }
    )
    delete errors['count'];
  } catch (validationErrors) {
    validationErrors.inner.forEach((error) => {
      errors[error.path] = error.message;
    });
  }
}

store.state[MODULE_STORE.COMMON.NAME].breadcrumbCurrent = 'Tạo mã sản phẩm'
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

getProduct(productId.value)
getProductAttributes('')
getMeasureUnits('')
</script>

<style scoped></style>
