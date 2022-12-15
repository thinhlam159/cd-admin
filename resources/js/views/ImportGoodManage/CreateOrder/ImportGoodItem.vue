<template>
  <div class="flex py-1 border-b border-gray-200">
    <div class="mr-4 w-[18%] flex items-center">
      <div class="inline p-0" v-for="(item, index) in categories" :key="index">
        <input class="hidden" :name="item.name + unique" type="radio" :id="item.name + unique" :value="item.id" v-model="formData.category" @change="handleOnChangeCategorySelect">
        <label class="mr-1 p-1 min-w-[60px] category-radio-label border border-gray-500 rounded-full cursor-pointer" :for="item.name + unique">{{ item.name }}</label><br>
      </div>
    </div>
    <div class="mr-4 w-[18%] flex items-center">
      <div class="inline p-0" v-for="(item, index) in productsByCategory" :key="index">
        <input :checked="item.checked" class="hidden" :name="item.name + unique" type="radio" :id="item.name + unique" :value="item.product_id" v-model="formData.product" @change="handleOnChangeProductSelect">
        <label class="mr-1 p-1 min-w-[60px] category-radio-label border border-gray-500 rounded-full cursor-pointer" :for="item.name + unique">{{ item.name }}</label><br>
      </div>
    </div>
    <div class="mr-4 w-[18%] flex items-center">
      <div class="inline p-0" v-for="(item, index) in productAttributeValuesByProduct" :key="index">
        <input :checked="item.checked" class="hidden" :name="item.code + unique" type="radio" :id="item.code + unique" :value="item.product_attribute_value_id" v-model="formData.productAttributeValue" @change="handleOnChangeProductAttributeValueSelect">
        <label class="mr-1 p-1 px-3 min-w-[60px] category-radio-label border border-gray-500 rounded-full cursor-pointer" :for="item.code + unique">{{ item.code }}</label><br>
      </div>
    </div>
    <div class="mr-4 w-[10%] flex">
      <span class="py-3 w-full">{{ productAttributeValueName }}</span>
    </div>
    <div class="mr-4 w-[10%] flex relative border border-gray-100">
      <input type="number" class="p-3 w-full" v-model="count" placeholder="Số lượng" :min="0">
      <span class="absolute top-[50%] right-10 -translate-y-1/2">{{ measureUnitType }}</span>
    </div>
    <div class="w-[4%] flex items-center">
      <ButtonRemove @clickBtn="$emit('handleRemoveInputItem')" :text="' '"/>
    </div>
    <div class="mr-4 w-[10%] flex items-center" v-show="!isValid"><span class="text-[red] text-sm">Số lượng lớn hơn 0</span></div>
  </div>
</template>

<script>
import {useStore} from "vuex";
import {ref, toRef, watch, nextTick} from "vue";
import ButtonRemove from "@/components/Buttons/ButtonRemove";
import {MODULE_STORE} from "@/const";

export default {
  name: "ImportGoodItem",
  components: {ButtonRemove},
  props: {
    categories: Object,
    customers: Object,
    products: Object,
    item: Object,
    index: String,
  },
  setup(props, { emit }) {
    const store = useStore()
    const categories = toRef(props, 'categories')
    const customers = toRef(props, 'customers')
    const products = toRef(props, 'products')
    const unique = toRef(props, 'index')
    const item = toRef(props, 'item')
    const productsByCategory = ref([])
    const productAttributeValuesByProduct = ref([])
    const productSelected = ref({
      product_id: null,
      product_attribute_values: []
    })
    const importGoodDataItem = ref({})
    const measureUnitType = ref('')
    const isValid = ref(false)
    const count = ref(item.value.count)
    const productAttributePriceId = ref('')
    const formData = ref({
      category : item.value.category_id,
      product : item.value.product_id,
      productAttributeValue : item.value.product_attribute_value_id,
      measureUnitType : item.value.measure_unit_type
    })
    const productAttributeValueName = ref('')
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

    const initItem = () => {
      if (item.value.category_id === '') return
      productsByCategory.value = products.value.filter((product) => {
        return product.category_id === item.value.category_id
      })
      productsByCategory.value = productsByCategory.value.map((product) => {
        return {
          ...product,
          checked : product.product_id === item.value.product_id
        }
      })

      productSelected.value = productsByCategory.value.find((product) => {
        return product.checked
      })

      productAttributeValuesByProduct.value = productSelected.value.product_attribute_values
      console.log(productAttributeValuesByProduct.value)
      productAttributeValuesByProduct.value = productAttributeValuesByProduct.value.map((productAttributeValue) => {
        return {
          ...productAttributeValue,
          checked : productAttributeValue.product_attribute_value_id === item.value.product_attribute_value_id
        }
      })

      const productAttributeValuesSelected = productAttributeValuesByProduct.value.find((product) => {
        return product.checked
      })
      productAttributeValueName.value = `${productSelected.value.name} ${productAttributeValuesSelected.code}`
      measureUnitType.value = listMeasureUnitType.value.find( item => productAttributeValuesSelected.measure_unit_name === item.type).name
      if (count.value <= 0) {
        isValid.value = false
      } else {
        isValid.value = true
      }
    }

    const handleOnChangeCategorySelect = () => {
      productAttributeValuesByProduct.value = []
      productsByCategory.value = []
      productAttributeValueName.value = ''
      count.value = 0
      nextTick(() => {
        productsByCategory.value = products.value.filter((product) => {
          return product.category_id === formData.value.category
        })
      })
    }
    const handleOnChangeProductSelect = () => {
      productAttributeValueName.value = ''
      productAttributeValuesByProduct.value = []
      count.value = 0
      productSelected.value = products.value.find((product) => {
        return product.product_id === formData.value.product
      })
      nextTick(() => {productAttributeValuesByProduct.value = productSelected.value.product_attribute_values})
    }

    const handleOnChangeProductAttributeValueSelect = () => {
      const productAttributeValuesSelected = productAttributeValuesByProduct.value.find((productAttributeValue) => {
        return productAttributeValue.product_attribute_value_id === formData.value.productAttributeValue
      })

      productAttributePriceId.value = productAttributeValuesSelected.product_attribute_price_id
      count.value = 0
      productAttributeValueName.value = `${productSelected.value.name} ${productAttributeValuesSelected.code}`
      measureUnitType.value = listMeasureUnitType.value.find( item => productAttributeValuesSelected.measure_unit_name === item.type)
      formData.value.measureUnitType = measureUnitType.value.type
      measureUnitType.value = measureUnitType.value.name
    }

    watch(count, () => {
      if (count.value <= 0) {
        isValid.value = false
      } else {
        isValid.value = true
      }
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
    })

    initItem()

    return {
      formData,
      categories,
      customers,
      products,
      productAttributeValueName,
      productAttributeValuesByProduct,
      productsByCategory,
      count,
      measureUnitType,
      unique,
      productSelected,
      isValid,
      handleOnChangeCategorySelect,
      handleOnChangeProductSelect,
      handleOnChangeProductAttributeValueSelect,
    }
  }
}
</script>

<style scoped>
.category-radio-label:checked {
  color: #1a202c;
}
input[type=radio]:checked ~ label {
  background: #6b7280;
  color: #fff;
}
</style>
