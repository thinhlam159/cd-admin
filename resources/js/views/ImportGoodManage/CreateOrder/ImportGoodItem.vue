<template>
  <div class="flex py-1">
    <div class="mr-4 w-[20%] border border-gray-500 flex">
<!--      <select name="category" class="p-3 w-full" v-model="formData.category" @change="handleOnChangeCategorySelect">-->
<!--        &lt;!&ndash;            <option disabled value="" class="w-full h-10 px-3 text-base text-gray-700" selected>Chọn danh mục</option>&ndash;&gt;-->
<!--        <option v-for="item in categories" :value="item.id" class="w-full h-10 px-3 text-base text-gray-700">{{ item.name }}</option>-->
<!--      </select>-->
      <div class="inline p-0" v-for="(item, index) in categories" :key="index">
        <input class="" name="category" type="radio" :id="item.name" :value="item.id" v-model="formData.category" @change="handleOnChangeCategorySelect">
        <label class="p-2 category-radio-label " :for="item.name">{{ item.name }}</label><br>
      </div>
    </div>
    <div class="mr-4 w-[20%] border border-gray-500">
      <div class="inline p-0" v-for="(item, index) in productsByCategory" :key="index">
        <input class="" name="product" type="radio" :id="item.name" :value="item.product_id" v-model="formData.product" @change="handleOnChangeProductSelect">
        <label class="p-2 category-radio-label " :for="item.name">{{ item.name }}</label><br>
      </div>
    </div>
    <div class="mr-4 w-[20%] border border-gray-500">
      <div class="inline p-0" v-for="(item, index) in productAttributeValuesByProduct" :key="index">
        <input class="" name="product" type="radio" :id="item.code" :value="item.product_attribute_value_id" v-model="formData.productAttributeValue" @change="handleOnChangeProductAttributeValueSelect">
        <label class="p-2 category-radio-label " :for="item.code">{{ item.code }}</label><br>
      </div>
    </div>
    <div class="mr-4 w-[20%] border border-gray-500">
      <span class="p-3 w-full text-center">{{ productAttributeValueName }}</span>
    </div>
    <div class="mr-4 w-[10%] border border-gray-500">
      <div class="inline p-0" v-for="(item, index) in listMeasureUnitType" :key="index">
        <input class="" name="measure-unit-type" type="radio" :id="item.name" :value="item.type" v-model="formData.measureUnitType">
        <label class="p-2 category-radio-label " :for="item.name">{{ item.name }}</label><br>
      </div>
    </div>
    <div class="mr-4 w-[2%] border border-gray-500">
      <input type="number" class="p-3 w-full" v-model="count" placeholder="Số lượng">
    </div>
    <div class="">
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
  name: "ImportGoodItem",
  components: {ButtonRemove},
  props: {
    categories: Object,
    customers: Object,
    products: Object,
    item: Object,
  },
  setup(props, { emit }) {
    const router = useRouter()
    const store = useStore()

    const categories = toRef(props, 'categories')
    const customers = toRef(props, 'customers')
    const products = toRef(props, 'products')

    const item = toRef(props, 'item')
    const productsByCategory = ref({})
    const productSelected = ref({})
    const productAttributeValuesByProduct = ref({})
    const importGoodDataItem = ref({})
    const measureUnitType = ref('')
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

    const count = ref(item.value.count)
    const productAttributePriceId = ref('')
    const formData = ref({
      category : item.value.category_id,
      product : item.value.product_id,
      productAttributeValue : item.value.product_attribute_value_id,
      measureUnitType : ''
    })
    const productAttributeValueName = ref('')

    const handleOnChangeCategorySelect = () => {
      productAttributeValuesByProduct.value = {}
      productAttributeValueName.value = ''
      productsByCategory.value = products.value.filter((product) => {
        return product.category_id === formData.value.category
      })
    }
    const handleOnChangeProductSelect = () => {
      productAttributeValueName.value = ''
      productSelected.value = products.value.find((product) => {
        return product.product_id === formData.value.product
      })
      productAttributeValuesByProduct.value = productSelected.value.product_attribute_values
    }

    const handleOnChangeProductAttributeValueSelect = () => {
      const productAttributeValuesSelected = productAttributeValuesByProduct.value.find((productAttributeValue) => {
        return productAttributeValue.product_attribute_value_id === formData.value.productAttributeValue
      })

      productAttributePriceId.value = productAttributeValuesSelected.product_attribute_price_id
      count.value = 1
      productAttributeValueName.value = `${productSelected.value.name} ${productAttributeValuesSelected.code}`
    }

    watch(count, () => {
      importGoodDataItem.value = {
        // category_id: formData.value.category,
        product_id: formData.value.product,
        product_attribute_value_id: formData.value.productAttributeValue,
        product_attribute_price_id: productAttributePriceId.value,
        count: count.value,
        index: item.value.index,
        measure_unit_type: formData.value.measureUnitType
      }

      // let payload = store.state[MODULE_STORE.ORDER.NAME].orderPostData.splice(item.value.index, 1, importGoodDataItem.value)
      // payload = payload.sort((x, y) => x.product_attribute_value_id > y.product_attribute_value_id ? 1 : -1)
      // payload = payload.map((item, index) => {
      //   return {
      //     ...item,
      //     index
      //   }
      // })
      // payload = payload.reduce((result, item) => {
      //   length = result.filter(filterItem => {
      //     return filterItem.product_attribute_value_id === item.product_attribute_value_id
      //   }).length
      //   return [...result, {...item, productOrderName: length + 1}]
      // }, [])
      store.commit(`${MODULE_STORE.IMPORT_GOOD.NAME}/${MODULE_STORE.IMPORT_GOOD.MUTATIONS.UPDATE_IMPORT_GOOD_DATA_ITEM}`, importGoodDataItem.value)
    })

    return {
      formData,
      categories,
      customers,
      products,
      productAttributeValueName,
      productAttributeValuesByProduct,
      productsByCategory,
      count,
      listMeasureUnitType,
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
</style>
