<template>
  <div class="w-full">
    <label for="" class="font-bold mb-3 text-lg text-gray-500">{{ label }}</label>
    <div class="relative w-full">
      <input
        type="text"
        class="border border-gray-200 px-[60px] w-full p-2 text-sm text-end outline-none"
        ref="inputRef"
        :placeholder="placeholder"
      />
      <div class="absolute top-[30%] right-3 ml-2">
        <span>VND</span>
      </div>
    </div>
<!--    <p>{{ errorMessage }}</p>-->
  </div>
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
  label: {
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
