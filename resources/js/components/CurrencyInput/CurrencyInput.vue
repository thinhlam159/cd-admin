<template>
  <input
    type="text"
    class="border-b border-gray-100 text-center outline-none"
    ref="inputRef"
    :placeholder="placeholder"
    :value="value"
  />
</template>

<script setup>
import {useCurrencyInput} from "vue-currency-input";

const props = defineProps({
  type: {
    type: String,
    default: 'text',
  },
  value: {
    type: String,
    default: '',
  },
  name: {
    type: String,
    required: true,
  },
  successMessage: {
    type: String,
    default: '',
  },
  placeholder: {
    type: String,
    default: '',
  },
  options: Object,
});
const { inputRef } = useCurrencyInput(props.options)

</script>

<style scoped>

</style>
