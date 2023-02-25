<template>
  <div class="flex border-b-none border border-gray-200">
    <div class="border-r border-gray-100 w-[25%] flex flex-wrap items-center pl-1">
      <div class="inline p-0" v-for="(item, index) in categories" :key="index">
        <input class="hidden" :name="item.name + unique" type="radio" :id="item.name + unique" :value="item.id" v-model="formData.category" @change="handleOnChangeCategorySelect">
        <label class="mr-1 p-1 min-w-[60px] category-radio-label border border-gray-500 rounded-full cursor-pointer" :for="item.name + unique">{{ item.name }}</label><br>
      </div>
    </div>
    <div class="border-r border-gray-100 w-[20%] flex flex-wrap items-center pl-1">
      <div class="inline p-0" v-for="(item, index) in productsByCategory" :key="index">
        <input :checked="item.checked" class="hidden" :name="item.name + unique" type="radio" :id="item.name + unique" :value="item.product_id" v-model="formData.product" @change="handleOnChangeProductSelect">
        <label class="mr-1 p-1 min-w-[60px] category-radio-label border border-gray-500 rounded-full cursor-pointer" :for="item.name + unique">{{ item.name }}</label><br>
      </div>
    </div>
    <div class="border-r border-gray-100 w-[20%] flex flex-wrap items-center pl-1">
      <div class="inline p-0" v-for="(item, index) in productAttributeValuesByProduct" :key="item.product_attribute_value_id">
        <input :checked="item.checked" class="hidden" :name="item.code + unique" type="radio" :id="item.code + unique" :value="item.product_attribute_value_id" v-model="formData.productAttributeValue" @change="handleOnChangeProductAttributeValueSelect">
        <label class="mr-1 p-2 px-3 min-w-[60px] category-radio-label border border-gray-500 rounded-full cursor-pointer" :for="item.code + unique">{{ item.code }}</label><br>
      </div>
    </div>
    <div class="border-r border-gray-100 w-[13%] flex items-center pl-1 text-center">
      <span class="py-3 w-full">{{ productAttributeValueName }}</span>
    </div>
    <div class="border-r border-gray-100 w-[15%] flex items-center">
      <input type="number" class="outline-none w-[25%] text-center" v-model="count" placeholder="Số lượng" min="0">
      <span class="ml-1 w-[25%]">{{ measureUnitType }}</span>
      <div class="inline-flex justify-around w-[50%]">
        <PlusIcon :value="count" :max-value="100" :step="1" @input="count = $event"/>
        <MinusIcon :value="count" :min-value="0" :step="1" @input="count = $event"/>
      </div>
    </div>
    <div class="w-[7%] flex items-center justify-center">
      <ButtonRemove @clickBtn="$emit('handleRemoveInputItem', item)" :text="' '"/>
    </div>
  </div>
</template>

<script setup>
import {useStore} from "vuex";
import {ref, toRef, watch, reactive} from "vue";
import ButtonRemove from "@/components/Buttons/ButtonRemove";
import {MODULE_STORE} from "@/const";
import PlusIcon from "@/components/icons/PlusIcon.vue";
import MinusIcon from "@/components/icons/MinusIcon.vue";
import {useI18n} from "vue-i18n";

const props = defineProps({
  categories: Object,
  customers: Object,
  products: Object,
  item: Object,
  index: Number,
  count: Number,
})
const emit = defineEmits(['checkValid'])
const store = useStore()
const {t} = useI18n()
const categories = toRef(props, 'categories')
const customers = toRef(props, 'customers')
const products = toRef(props, 'products')
const unique = toRef(props, 'index')
const item = toRef(props, 'item')
const count = ref(0)
const productsByCategory = reactive([])
const productAttributeValuesByProduct = reactive([])
const productSelected = ref({})
const importGoodDataItem = ref({})
const measureUnitType = ref('')

const productAttributePriceId = ref('')
const formData = ref({
  category : item.value.category_id,
  product : item.value.product_id,
  productAttributeValue : item.value.product_attribute_value_id,
  measureUnitType : item.value.measure_unit_type
})
const productAttributeValueName = ref('')

const handleOnChangeCategorySelect = () => {
  productsByCategory.splice(0, productsByCategory.length)
  productAttributeValuesByProduct.splice(0, productAttributeValuesByProduct.length)
  productAttributeValueName.value = ''
  count.value = 0
  props.products.filter((product) => {
    return product.category_id === formData.value.category
  }).forEach((product) => {productsByCategory.push(product)})
}
const handleOnChangeProductSelect = () => {
  productAttributeValueName.value = ''
  productAttributeValuesByProduct.splice(0, productAttributeValuesByProduct.length)
  count.value = 0
  productSelected.value = products.value.find((product) => {
    return product.product_id === formData.value.product
  })
  productSelected.value.product_attribute_values.forEach((item) => {productAttributeValuesByProduct.push({...item, checked: false})})
}
const handleOnChangeProductAttributeValueSelect = () => {
  const productAttributeValuesSelected = productAttributeValuesByProduct.find((productAttributeValue) => {
    return productAttributeValue.product_attribute_value_id === formData.value.productAttributeValue
  })

  productAttributePriceId.value = productAttributeValuesSelected.product_attribute_price_id
  count.value = 0
  productAttributeValueName.value = `${productSelected.value.name} ${productAttributeValuesSelected.code}`
  measureUnitType.value = t(`measure_unit_type.${productAttributeValuesSelected.measure_unit_name}`)
  formData.value.measureUnitType = productAttributeValuesSelected.measure_unit_name
}

watch(count, () => {
  importGoodDataItem.value = {
    category_id: formData.value.category,
    product_id: formData.value.product,
    product_attribute_value_id: formData.value.productAttributeValue,
    product_attribute_price_id: productAttributePriceId.value,
    count: count.value,
    measure_unit_type: formData.value.measureUnitType
  }

  const payload = {
    index: unique.value,
    data: importGoodDataItem.value
  }
  store.commit(`${MODULE_STORE.IMPORT_GOOD.NAME}/${MODULE_STORE.IMPORT_GOOD.MUTATIONS.UPDATE_IMPORT_GOOD_DATA_ITEM}`, payload)
  emit('checkValid')
})

</script>

<style scoped>
.category-radio-label:checked {
  color: #1a202c;
}
input[type=radio]:checked ~ label {
  background: #6b7280;
  color: #fff;
}
input[type=number]::-webkit-inner-spin-button,
input[type=number]::-webkit-outer-spin-button {
  -webkit-appearance: none;
  margin: 0;
}
input[type=number] {
  -moz-appearance: textfield;
}
</style>
