<template>
  <div class="w-full p-1">
    <div class="flex p-1 text-md">
      <div class="mr-1 py-1 bg-white w-[30%]">
        <span>Danh mục</span>
      </div>
      <div class="mr-1 py-1 bg-white w-[25%]">
        <span>Sản phẩm</span>
      </div>
      <div class="mr-1 py-1 bg-white w-[25%]">
        <span>Mã sản phẩm</span>
      </div>
      <div class="mr-1 py-1 bg-white w-[10%]">
        <span>Số lượng</span>
      </div>
      <div class="py-1 w-[5%]">
      </div>
    </div>
    <div class="flex px-1 text-md mt-1">
      <div class="mr-1 bg-white w-[30%] flex items-center flex-wrap">
        <div class="inline m-1" v-for="(item, index) in categories" :key="index">
          <input class="hidden" name="category" type="radio" :id="item.name" :value="item.category_id" v-model="categorySelectedId" @change="handleSelectCategory">
          <label class="p-1 min-w-[60px] category-radio-label border border-gray-500 rounded-full cursor-pointer focus:bg-[#d9d9d9]" :for="item.name">{{ item.name }}</label><br>
        </div>
      </div>
      <div class="mr-1 bg-white w-[25%] flex items-center flex-wrap">
        <div class="inline m-1" v-for="(item, index) in productsByCategory" :key="index">
          <input class="hidden" name="product" type="radio" :id="item.name" :value="item.product_id" v-model="productSelectedId" @change="handleSelectProduct">
          <label class="mr-1 p-1 min-w-[60px] category-radio-label border border-gray-500 rounded-full cursor-pointer" :for="item.name">{{ item.name }}</label><br>
        </div>
      </div>
      <div class="mr-1 bg-white w-[25%] flex items-center flex-wrap">
        <div class="inline m-1" v-for="(item, index) in productAttributeValuesByProduct" :key="index">
          <input class="hidden" name="attribute" type="radio" :id="item.code" :value="item.product_attribute_value_id" v-model="productAttributeValueSelectedId" @change="handleSelectProductAttribute">
          <label class="mr-1 p-1 px-3 min-w-[60px] category-radio-label border border-gray-500 rounded-full cursor-pointer" :for="item.code">{{ item.code }}</label><br>
        </div>
      </div>
      <div class="mr-1 w-[10%] flex items-center">
        <input type="number" class="outline-none border-gray-400 border text-md p-2 w-full max-h-[45px]" min="0" placeholder="Số lượng" v-model="amount" @change="handleChangeAmount">
      </div>
      <div class="m4-1 w-[5%] flex items-center">
        <button @click="handleAddItem" :disabled="!disableSubmit" class="p-2 hover:bg-[#d9d9d9] border border-gray-400 rounded-md cursor-pointer max-h-[45px]">Tạo</button>
      </div>
    </div>
  </div>
</template>

<script setup>
import {inject, nextTick, ref} from "vue";
import {getListCategoryFromApi, getListProductFromApi} from "@/api";

const props = defineProps({
  data: Object
})

const toast = inject('$toast')
const emit = defineEmits(['addProductItem'])
const categories = ref([])
const categorySelectedId = ref('')
const products = ref([])
const productSelectedId = ref('')
const productsByCategory = ref([])
const productAttributeValuesByProduct = ref([])
const productAttributeValueSelectedId = ref('')
const amount = ref(0)
const disableSubmit = ref(false)

const getListCategory = async () => {
  try {
    const res = await getListCategoryFromApi()
    categories.value = res.data
  } catch (errors) {
    const error = errors.message;
    toast.error(error)
  }
}
const getListProduct = async () => {
  try {
    const res = await getListProductFromApi();
    products.value = res.data
  } catch (errors) {
    toast.error(errors.message)
  }
}
const handleSelectCategory = () => {
  productsByCategory.value = []
  productAttributeValuesByProduct.value = []
  productAttributeValueSelectedId.value = ''
  disableSubmit.value = productAttributeValueSelectedId.value !== '' && amount.value !== 0
  nextTick(() => {
    productsByCategory.value = products.value.filter((product) => {
      return product.category_id === categorySelectedId.value
    })
  })
}
const handleSelectProduct = () => {
  productAttributeValueSelectedId.value = ''
  disableSubmit.value = productAttributeValueSelectedId.value !== '' && amount.value !== 0
  nextTick(() => {
    const productSelected = products.value.find((product) => {
      return product.product_id === productSelectedId.value
    })
    productAttributeValuesByProduct.value = productSelected.product_attribute_values
  })
}
const handleSelectProductAttribute = () => {
  disableSubmit.value = productAttributeValueSelectedId.value !== '' && amount.value !== 0
}
const handleChangeAmount = () => {
  disableSubmit.value = productAttributeValueSelectedId.value !== '' && amount.value !== 0
}

const handleAddItem = () => {
  emit('addProductItem', {id: productAttributeValueSelectedId.value, amount: amount.value})
}

getListCategory()
getListProduct()
</script>

<style scoped>
.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: rgba(0, 0, 0, 0.5);
}

.modal-content {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  background-color: white;
  padding: 20px;
}
input[type=radio]:checked ~ label {
  background: #6b7280;
  color: #fff;
}
</style>
