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
        <div class="p-4 text-base text-gray-700">
          <span>Mã sản phẩm: {{ product.code }}</span>
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
        <div class="flex">
          <div class="w-[50%]">
            <label for="category" class="block mb-1 font-bold text-sm">Thuộc tính sản phẩm</label>
            <select name="category" class="p-3" v-model="formData.product_attribute_id">
              <option disabled value="" class="w-full h-10 px-3 text-base text-gray-700" selected>Chọn danh mục</option>
              <option v-for="(item, index) in productAttributes" :value="item.id" :key="index"
                      class="w-full h-10 px-3 text-base text-gray-700">{{ item.name }}
              </option>
            </select>
          </div>
          <div class="w-[50%]">
            <label for="category" class="block mb-1 font-bold text-sm">Tên thuộc tính</label>
            <input type="text" name="name" placeholder="Tên thuộc tính" v-model="formData.value"
                   class="w-full h-10 px-3 text-base text-gray-700 placeholder-gray-400 border border-gray-400"
            >
          </div>
        </div>
        <div class="py-2">
          <label for="price" class="block mb-1 font-bold text-sm">Giá thành</label>
          <input type="text" name="price" placeholder="Nhập giá sp" v-model="formData.price"
                 class="w-full h-10 px-3 text-base text-gray-700 placeholder-gray-400 border border-gray-400"
          >
        </div>
        <div class="py-2">
          <label for="description" class="block mb-1 font-bold text-sm">Số lượng sản phẩm</label>
          <input name="price" placeholder="Số lượng sản phẩm ban đầu" v-model="formData.count"
                 class="w-full h-10 px-3 text-base text-gray-700 placeholder-gray-400 border border-gray-400"
          />
        </div>
        <div class="py-2">
          <label for="notice_price" class="block mb-1 font-bold text-sm">Loại báo giá</label>
          <select name="notice_price" class="p-3" v-model="formData.notice_price_type">
            <option disabled value="" class="w-full h-10 px-3 text-base text-gray-700" selected>Chọn đơn vị tính
            </option>
            <option v-for="(item, index) in noticePrice" :value="item" :key="index"
                    class="w-full h-10 px-3 text-base text-gray-700">{{ item }}
            </option>
          </select>
        </div>
        <div class="py-2">
          <label for="description" class="block mb-1 font-bold text-sm">Đơn vị tính</label>
          <select name="category" class="p-3" v-model="formData.measure_unit_type">
            <option disabled value="" class="w-full h-10 px-3 text-base text-gray-700" selected>Chọn đơn vị tính
            </option>
            <option v-for="(item, index) in measures" :value="item.name" :key="index"
                    class="w-full h-10 px-3 text-base text-gray-700">{{ item.name }}
            </option>
          </select>
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
// import FormUserManage from "@/components/FormUserManage";
import {inject, ref} from "vue";
import {useRoute, useRouter} from "vue-router";
import {useStore} from "vuex";
import {MODULE_STORE, ROUTER_PATH} from "@/const";
import {
  createProductAttributeValueFromApi,
  createProductFromApi,
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
  '290kg',
])

const handleSubmit = async (data) => {
  try {
    const bodyFormData = new FormData()
    bodyFormData.append('product_id', productId.value);
    bodyFormData.append('product_attribute_id', data.product_attribute_id);
    bodyFormData.append('measure_unit_id', data.measure_unit_type);
    bodyFormData.append('value', data.value);
    bodyFormData.append('code', data.code);
    bodyFormData.append('price', data.price);
    bodyFormData.append('count', data.count);
    bodyFormData.append('notice_price_type', data.notice_price_type);
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
    console.log(res.data)
    measures.value = res.data
  } catch (errors) {
    const error = errors.message;
  }
}

const onFileChanged = () => {
  let image = file.value.files[0];
  imageUrl.value = URL.createObjectURL(image)
  createImage(image)
}

const createImage = (file) => {
  const reader = new FileReader();
  reader.onload = (e) => {
    imageBuffer.value = e.target.result
  }
  reader.readAsDataURL(file)
}

getProduct(productId.value)
getProductAttributes('')
getMeasureUnits('')
</script>

<style scoped></style>
