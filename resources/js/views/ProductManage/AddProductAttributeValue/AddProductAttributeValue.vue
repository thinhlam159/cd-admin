<template>
  <!--  <FormUserManage />-->
  <div class="w-full h-full relative">
    <div class="w-[650px] pt-14 h-full absolute left-20">
      <div class="w-full py-6 py-auto text-xl">
        <span class="text-gray-500">Thêm sản mã cho sản phẩm</span>
      </div>
      <hr>

      <div>
        <div class="p-4 text-base text-gray-700">
          <span>Tên sản phẩm: {{ product.name }}</span>
        </div>
      </div>
      <hr>
      <form @submit.prevent="handleSubmit(formData)">
        <div class="py-2">
          <label for="code" class="block mb-1 font-bold text-sm">Mã loại sp</label>
          <input type="text" name="name" placeholder="Nhập mã loại sản phẩm" v-model="formData.code"
                 class="w-full h-10 px-3 text-base text-gray-700 placeholder-gray-400 border border-gray-400"
          >
        </div>
        <div class="py-2">
          <label for="description" class="block mb-1 font-bold text-sm">Số lượng sản phẩm</label>
          <input name="price" placeholder="Số lượng sản phẩm ban đầu" v-model="formData.count"
                 class="w-full h-10 px-3 text-base text-gray-700 placeholder-gray-400 border border-gray-400"
          />
        </div>
        <div>
          <input class="w-25 h-10 mt-5 px-3 text-base text-gray-700 placeholder-gray-400 bg-green-400 cursor-pointer"
                 type="submit" value="Thêm mã sản phẩm">
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import {inject, ref} from "vue";
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
const noticePrice = ref([
  '298kg',
  '273kg',
  '248kg',
  '224kg',
  '214kg',
])

const handleSubmit = async (data) => {
  try {
    if (data.notice_price_type === 'Mặc định') {
      data.notice_price_type = 'default'
    }
    const bodyFormData = new FormData()
    bodyFormData.append('product_id', productId.value);
    bodyFormData.append('product_attribute_id', productAttributes.value[0].id);
    bodyFormData.append('measure_unit_type', product.value.product_attribute_values[0].measure_unit);
    bodyFormData.append('value', data.code);
    bodyFormData.append('code', data.code);
    bodyFormData.append('price', product.value.product_attribute_values[0].price);
    bodyFormData.append('count', data.count);
    bodyFormData.append('notice_price_type', product.value.product_attribute_values[0].notice_price_type);
    const res = await createProductAttributeValueFromApi(bodyFormData)
    await router.push(`${ROUTER_PATH.ADMIN}/${ROUTER_PATH.PRODUCT_MANAGE}`)
    toast.success('Thêm mã sản phẩm thành công', {duration: 3000});
  } catch (errors) {
    const error = errors.message;
    toast.error(error);
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

getProduct(productId.value)
getProductAttributes('')
getMeasureUnits('')
</script>

<style scoped></style>
