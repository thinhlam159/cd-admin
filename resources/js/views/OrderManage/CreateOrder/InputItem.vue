<template>
  <div class="flex py-1 border-b border-gray-200">
    <div class="mr-4 w-[14%] flex items-center">
      <div class="inline p-0" v-for="(item, index) in categories" :key="index">
        <input class="hidden" :name="item.name + unique" type="radio" :id="item.name + unique" :value="item.id" v-model="formData.category" @change="handleOnChangeCategorySelect">
        <label class="mr-1 p-1 min-w-[60px] category-radio-label border border-gray-500 rounded-full cursor-pointer" :for="item.name + unique">{{ item.name }}</label><br>
      </div>
    </div>
    <div class="mr-4 w-[14%] flex items-center">
      <div class="inline p-0" v-for="(item, index) in productsByCategory" :key="index">
        <input :checked="item.checked" class="hidden" :name="item.name + unique" type="radio" :id="item.name + unique" :value="item.product_id" v-model="formData.product" @change="handleOnChangeProductSelect">
        <label class="mr-1 p-1 min-w-[60px] category-radio-label border border-gray-500 rounded-full cursor-pointer" :for="item.name + unique">{{ item.name }}</label><br>
      </div>
    </div>
    <div class="mr-4 w-[10%] flex items-center">
      <div class="inline p-0" v-for="(item, index) in productAttributeValuesByProduct" :key="index">
        <input :checked="item.checked" class="hidden" :name="item.code + unique" type="radio" :id="item.code + unique" :value="item.product_attribute_value_id" v-model="formData.productAttributeValue" @change="handleOnChangeProductAttributeValueSelect">
        <label class="mr-1 p-1 px-3 min-w-[60px] category-radio-label border border-gray-500 rounded-full cursor-pointer" :for="item.code + unique">{{ item.code }}</label><br>
      </div>
    </div>
    <div class="mr-4 w-[10%] flex items-center">
      <span class="p-3 w-full text-center">{{ productOrderName }}</span>
    </div>
    <div class="mr-4 w-[5%] flex items-center">
      <span class="w-full text-center">{{ noticePrice }}</span>
    </div>
    <div class="mr-4 w-[10%] flex relative border border-gray-100">
      <input type="number" class="p-3 w-full" v-model="weight" placeholder="Số lượng" :min="0">
<!--      <span class="absolute top-[50%] right-10 -translate-y-1/2">{{ measureUnitType }}</span>-->
    </div>
    <div class="mr-4 w-[10%] flex items-center">
      <span class="p-3 w-full text-center">{{ total?.toLocaleString('it-IT', {style : 'currency', currency : 'VND'}) }}</span>
    </div>
    <div class="w-[5%]">
      <ButtonRemove @clickBtn="$emit('handleRemoveInputItem')" :text="' '"/>
    </div>
  </div>
</template>

<script>
import {useRouter} from "vue-router";
import {useStore} from "vuex";
import {ref, toRef, watch, nextTick, onBeforeUpdate} from "vue";
import ButtonRemove from "@/components/Buttons/ButtonRemove";
import {MODULE_STORE} from "@/const";

export default {
  name: "InputItem",
  components: {ButtonRemove},
  props: {
    categories: Object,
    customers: Object,
    products: Object,
    item: Object,
    index: String,
  },
  setup(props, { emit }) {
    const router = useRouter()
    const store = useStore()
    const categories = toRef(props, 'categories')
    const customers = toRef(props, 'customers')
    const products = toRef(props, 'products')
    const item = toRef(props, 'item')
    const productOrderName = ref('')
    const productsByCategory = ref({})
    const productSelected = ref({
      product_id: null,
      product_attribute_values: []
    })
    const productAttributeValuesByProduct = ref({})
    const noticePrice = ref('')
    const price = ref(item.value.price)
    const count = ref(item.value.count)
    const weight = ref(item.value.weight)
    const total = ref(item.value.total)
    const productAttributePriceId = ref('')
    const orderDataItem = ref({})
    const measureUnitType = ref({})
    const formData = ref({
      category : item.value.category_id,
      product : item.value.product_id,
      productAttributeValue : item.value.product_attribute_value_id
    })
    const unique = toRef(props, 'index')
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

    const init = () => {
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
      productAttributeValuesByProduct.value = productAttributeValuesByProduct.value.map((productAttributeValue) => {
        return {
          ...productAttributeValue,
          checked : productAttributeValue.product_attribute_value_id === item.value.product_attribute_value_id
        }
      })

      const productAttributeValuesSelected = productAttributeValuesByProduct.value.find((product) => {
        return product.checked
      })
      productOrderName.value = `${productAttributeValuesSelected.code}${item.value.attribute_display_index}`
      measureUnitType.value = listMeasureUnitType.value.find( item => productAttributeValuesSelected.measure_unit_name === item.type)
      noticePrice.value = getNoticePriceByAttribute(productAttributeValuesSelected)
      // isValid.value = count.value > 0;
    }

    const handleOnChangeCategorySelect = () => {
      productAttributeValuesByProduct.value = []
      productsByCategory.value = []
      productOrderName.value = ''
      nextTick(() => {
        productsByCategory.value = products.value.filter((product) => {
          return product.category_id === formData.value.category
        })
      })
    }
    const handleOnChangeProductSelect = () => {
      productSelected.value = products.value.find((product) => {
        return product.product_id === formData.value.product
      })
      productAttributeValuesByProduct.value = productSelected.value.product_attribute_values
    }

    const handleOnChangeProductAttributeValueSelect = () => {
      const productAttributeValuesSelected = productAttributeValuesByProduct.value.find((productAttributeValue) => {
        return productAttributeValue.product_attribute_value_id === formData.value.productAttributeValue
      })
      noticePrice.value = getNoticePriceByAttribute(productAttributeValuesSelected)
      price.value = productAttributeValuesSelected.standard_price
      productAttributePriceId.value = productAttributeValuesSelected.product_attribute_price_id
      measureUnitType.value = listMeasureUnitType.value.find( item => productAttributeValuesSelected.measure_unit_name === item.type)
      weight.value = 1
    }

    const getNoticePriceByAttribute = (attribute) => {
      return attribute.standard_price?.toLocaleString('it-IT', {style : 'currency', currency : 'VND'})
    }

    watch(price, () => {
      total.value = price.value * weight.value
    })
    watch(weight, () => {
      total.value = price.value * weight.value
    })
    watch(total, () => {
      orderDataItem.value = {
        category_id: formData.value.category,
        product_id: formData.value.product,
        product_attribute_value_id: formData.value.productAttributeValue,
        product_attribute_price_id: productAttributePriceId.value,
        count: count.value,
        measure_unit_type: measureUnitType.value.type,
        weight: weight.value,
        // index: item.value.index,
        price: price.value,
        total: total.value
      }
      const payload = {
        index: unique.value,
        data: orderDataItem.value
      }

      store.commit(`${MODULE_STORE.ORDER.NAME}/${MODULE_STORE.ORDER.MUTATIONS.UPDATE_ORDER_DATA_ITEM}`, payload)
    })

    init()

    return {
      formData,
      categories,
      customers,
      products,
      productOrderName,
      productAttributeValuesByProduct,
      productsByCategory,
      noticePrice,
      count,
      weight,
      total,
      unique,
      measureUnitType,
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
