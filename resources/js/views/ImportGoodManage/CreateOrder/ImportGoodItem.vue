<template>
  <div class="flex py-1">
    <div class="mr-4 w-[14%] border border-gray-500 flex">
<!--      <select name="category" class="p-3 w-full" v-model="formData.category" @change="handleOnChangeCategorySelect">-->
<!--        &lt;!&ndash;            <option disabled value="" class="w-full h-10 px-3 text-base text-gray-700" selected>Chọn danh mục</option>&ndash;&gt;-->
<!--        <option v-for="item in categories" :value="item.id" class="w-full h-10 px-3 text-base text-gray-700">{{ item.name }}</option>-->
<!--      </select>-->
      <div class="inline p-0" v-for="(item, index) in categories" :key="index">
        <input class="" type="radio" :id="item.name" :value="item.id" v-model="formData.category" :checked="index===0">
        <label class="p-2 category-radio-label " :for="item.name">{{ item.name }}</label><br>
      </div>
    </div>
    <div class="mr-4 w-[14%] border border-gray-500">
      <select name="product" class="p-3 w-full" v-model="formData.product" @change="handleOnChangeProductSelect">
        <!--            <option disabled value="" class="w-full h-10 px-3 text-base text-gray-700" selected>Chọn danh mục</option>-->
        <option v-for="item in productsByCategory" :value="item.product_id" class="w-full h-10 px-3 text-base text-gray-700">{{ item.name }}</option>
      </select>
    </div>
    <div class="mr-4 w-[14%] border border-gray-500">
      <select name="product_attribute_value" class="p-3 w-full" v-model="formData.productAttributeValue" @change="handleOnChangeProductAttributeValueSelect">
        <!--            <option disabled value="" class="w-full h-10 px-3 text-base text-gray-700" selected>Chọn danh mục</option>-->
        <option v-for="item in productAttributeValuesByProduct" :value="item.product_attribute_value_id" class="w-full h-10 px-3 text-base text-gray-700">{{ item.code }}</option>
      </select>
    </div>
    <div class="mr-4 w-[10%] border border-gray-500">
      <span class="p-3 w-full text-center">{{ productOrderName }}</span>
    </div>
    <div class="mr-4 w-[7%] border border-gray-500">
      <span class="p-3 w-full text-center">{{ noticePrice }}</span>
    </div>
    <div class="mr-4 w-[5%] border border-gray-500">
      <input type="number" class="p-3 w-full" v-model="count" placeholder="Số lượng">
    </div>
    <div class="mr-4 w-[10%] border border-gray-500">
      <span class="p-3 w-full text-center">{{ total?.toLocaleString('it-IT', {style : 'currency', currency : 'VND'}) }}</span>
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
    // const categories = ref(store.state[MODULE_STORE.ORDER.NAME].categories)
    // const customers = ref(store.state[MODULE_STORE.ORDER.NAME].customers)
    // const products = ref(store.state[MODULE_STORE.ORDER.NAME].products)
    const item = toRef(props, 'item')
    const productOrderName = ref(item.value.productOrderName)
    const productsByCategory = ref({})
    const productSelected = ref({})
    const productAttributeValuesByProduct = ref({})
    const noticePrice = ref('')
    const price = ref(item.value.price)
    const count = ref(item.value.count)
    const total = ref(item.value.total)
    const productAttributePriceId = ref('')
    const orderDataItem = ref({})
    const formData = ref({
      category : item.value.category_id,
      product : item.value.product_id,
      productAttributeValue : item.value.product_attribute_value_id
    })
    // const emit = defineEmits(['updateDisplay'])

    const handleOnChangeCategorySelect = () => {
      productsByCategory.value = products.value.filter((product) => {
        return product.category_id === formData.value.category
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
      count.value = 1
    }

    const getNoticePriceByAttribute = (attribute) => {
      return attribute.standard_price?.toLocaleString('it-IT', {style : 'currency', currency : 'VND'})
    }

    watch(price, () => {
      total.value = price.value * count.value
    })
    watch(count, () => {
      total.value = price.value * count.value
    })
    watch(total, () => {
      orderDataItem.value = {
        // category_id: formData.value.category,
        product_id: formData.value.product,
        product_attribute_value_id: formData.value.productAttributeValue,
        product_attribute_price_id: productAttributePriceId.value,
        count: count.value,
        index: item.value.index,
        price: price.value,
        total: total.value
      }

      let payload = store.state[MODULE_STORE.ORDER.NAME].orderPostData.splice(item.value.index, 1, orderDataItem.value)
      payload = payload.sort((x, y) => x.product_attribute_value_id > y.product_attribute_value_id ? 1 : -1)
       payload = payload.map((item, index) => {
        return {
          ...item,
          index
        }
      })
      payload = payload.reduce((result, item) => {
        length = result.filter(filterItem => {
          return filterItem.product_attribute_value_id === item.product_attribute_value_id
        }).length
        return [...result, {...item, productOrderName: length + 1}]
      }, [])
      store.commit(`${MODULE_STORE.ORDER.NAME}/${MODULE_STORE.ORDER.MUTATIONS.UPDATE_ORDER_DATA_ITEM}`, payload)
      // forceUpdate();
    })

    // onBeforeUpdate(() => {
    //   formData.value.category = item.value.category_id
    //   formData.value.product = item.value.product_id
    //   formData.value.productAttributeValue = item.value.product_attribute_value_id
    // })

    const init = () => {
      // formData.value.category = item.value.category_id
      // formData.value.product = item.value.product_id
      // formData.value.productAttributeValue = item.value.productAttributeValue_id
      // console.log(categories.value)
      // productsByCategory.value = products.value.filter((product) => {
      //   return product.category_id === formData.value.category
      // })

      // productSelected.value = products.value.find((product) => {
      //   return product.product_id === formData.value.product
      // })
      // productAttributeValuesByProduct.value = productSelected.value.product_attribute_values

      // const productAttributeValuesSelected = productAttributeValuesByProduct.value.find((productAttributeValue) => {
      //   return productAttributeValue.product_attribute_value_id === formData.value.productAttributeValue
      // })
      // noticePrice.value = getNoticePriceByAttribute(productAttributeValuesSelected)
      // price.value = productAttributeValuesSelected.standard_price
      // productAttributePriceId.value = productAttributeValuesSelected.product_attribute_price_id
    }

    // const forceUpdate = async () => {
    //   await nextTick()
    //   emit('updateDisplay')
    // }
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
      total,
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
