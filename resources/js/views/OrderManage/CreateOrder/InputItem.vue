<template>
  <div class="flex py-1 border-b border-gray-50">
    <div class="mr-4 w-[14%]">
      <select name="category" class="p-3 w-full" v-model="formData.category" @change="handleOnChangeCategorySelect">
        <!--            <option disabled value="" class="w-full h-10 px-3 text-base text-gray-700" selected>Chọn danh mục</option>-->
        <option v-for="item in categories" :value="item.id" class="w-full h-10 px-3 text-base text-gray-700">{{ item.name }}</option>
      </select>
    </div>
    <div class="mr-4 w-[14%]">
      <select name="product" class="p-3 w-full" v-model="formData.product" @change="handleOnChangeProductSelect">
        <!--            <option disabled value="" class="w-full h-10 px-3 text-base text-gray-700" selected>Chọn danh mục</option>-->
        <option v-for="item in productsByCategory" :value="item.product_id" class="w-full h-10 px-3 text-base text-gray-700">{{ item.name }}</option>
      </select>
    </div>
    <div class="mr-4 w-[14%]">
      <select name="product_attribute_value" class="p-3 w-full" v-model="formData.productAttributeValue" @change="handleOnChangeProductAttributeValueSelect">
        <!--            <option disabled value="" class="w-full h-10 px-3 text-base text-gray-700" selected>Chọn danh mục</option>-->
        <option v-for="item in productAttributeValuesByProduct" :value="item.product_attribute_value_id" class="w-full h-10 px-3 text-base text-gray-700">{{ item.code }}</option>
      </select>
    </div>
    <div class="mr-4 w-[14%]">
      <span class="p-3 w-full text-center">{{ noticePrice }}</span>
    </div>
    <div class="mr-4 w-[14%]">
      <input type="number" class="p-3 w-full" v-model="count" placeholder="Số lượng">
    </div>
    <div class="mr-4 w-[14%]">
      <span class="p-3 w-full text-center">{{ total.toLocaleString('it-IT', {style : 'currency', currency : 'VND'}) }}</span>
    </div>
    <div class="mr-4 w-[5%]">
      <ButtonRemove @clickBtn="$emit('handleRemoveInputItem')" :text="' '"/>
    </div>
  </div>
</template>

<script>
import {useRouter} from "vue-router";
import {useStore} from "vuex";
import {ref, toRef, watch} from "vue";
import ButtonRemove from "@/components/Buttons/ButtonRemove";
import {MODULE_STORE} from "@/const";

export default {
  name: "InputItem",
  components: {ButtonRemove},
  props: {
    categories: Object,
    customers: Object,
    products: Object,
    index: Number,
  },
  setup(props) {
    const router = useRouter()
    const store = useStore()
    const formData = ref({})
    const categories = toRef(props, 'categories')
    const customers = toRef(props, 'customers')
    const products = toRef(props, 'products')
    const productsByCategory = ref({})
    const productSelected = ref({})
    const productAttributeValuesByProduct = ref({})
    const noticePrice = ref('')
    const price = ref(0)
    const count = ref(1)
    const total = ref(0)
    const productAttributePriceId = ref('')
    const orderDataItem = ref({})

    const handleOnChangeCategorySelect = () => {
      productsByCategory.value = products.value.filter((product) => {
        return product.category_id === formData.value.category
      })
    }
    const handleOnChangeProductSelect = () => {
      productSelected.value = products.value.filter((product) => {
        return product.product_id === formData.value.product
      })[0]
      productAttributeValuesByProduct.value = productSelected.value.product_attribute_values
    }

    const handleOnChangeProductAttributeValueSelect = () => {
      const productAttributeValuesSelected = productAttributeValuesByProduct.value.filter((productAttributeValue) => {
        return productAttributeValue.product_attribute_value_id === formData.value.productAttributeValue
      })[0]
      noticePrice.value = getNoticePriceByAttribute(productAttributeValuesSelected)
      price.value = productAttributeValuesSelected.price
      productAttributePriceId.value = productAttributeValuesSelected.product_attribute_price_id
    }

    const getNoticePriceByAttribute = (attribute) => {
      const price = attribute.price.toLocaleString('it-IT', {style : 'currency', currency : 'VND'})
      return `${attribute.code} x ${attribute.notice_price_type} x ${price}`
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
        count: count.value
      }
      const payload = {
        index: props.index,
        data: orderDataItem.value
      }
      store.commit(`${MODULE_STORE.ORDER.NAME}/${MODULE_STORE.ORDER.MUTATIONS.ADD_ORDER_DATA}`, payload)
    })

    return {
      formData,
      categories,
      customers,
      products,
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

</style>
