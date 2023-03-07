<template>
  <div class="modal-overlay" @click="$emit('close')"></div>
  <div class="modal-content">
    <button @click="$emit('close')">Close</button>
    <div class="relative p-5 bg-white text-xl">
      <span>
        {{ `${data.name} ${data.code} x ${data.noticePriceType}` }}
      </span>
      <span class="text-gray-400">
        x {{price.toLocaleString('it-IT', {style : 'currency', currency : 'VND'})}}
      </span>
      <br>
      <input type="text" v-model="price" class="outline-none border border-gray-400 p-1" placeholder="Nhập giá mới" @input="updateValue">
      <button @click="handleUpdatePrice" class="border-gray-400 border rounded-sm ml-1 p-1">Cập nhật giá</button>
    </div>
  </div>
</template>

<script setup>
import {inject, ref} from "vue";
import {updateProductAttributePriceFromApi} from "@/api";

const props = defineProps({
  data: Object
})
const toast = inject('$toast')
const emit = defineEmits(['close', 'update'])
const formatter = new Intl.NumberFormat("de-DE", {
  minimumFractionDigits: 0,
  maximumFractionDigits: 0,
});
const price = ref(formatter.format(props.data.price))
const handleUpdatePrice = async () => {
  try {
    const res = await updateProductAttributePriceFromApi({
      product_attribute_value_price: [
        {
          product_attribute_price_id: props.data.id,
          product_attribute_value_id: props.data.productAttributeValueId,
          price: parseInt(price.value.replace(/[^\d]/g, ""), 10),
          notice_price_type: props.data.noticePriceType,
          product_id: props.data.productId,
        },
      ]
    })
    emit('close')
    emit('update')
    toast.success('Cập nhật giá thành công!', {duration: 3000})
  } catch (errors) {
    toast.error(errors.message)
  }
}
const updateValue = (e) => {

}


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
</style>
