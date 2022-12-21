<template>
  <div>
    <input
      type="text"
      :value="inputValue"
      @input="handleChange"
      @blur="handleBlur"
      class="border border-gray-200"
    />
    <span>VND</span>
    <p>{{ errorMessage }}</p>
  </div>
</template>

<script setup>
import {computed, ref, toRef} from "vue";
import {useField} from "vee-validate";
import * as yup from "yup";

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
});

const name = toRef(props, 'name');
const {
  value: inputValue,
  errorMessage,
  handleBlur,
  handleChange,
  meta,
} = useField(name, undefined, {
  initialValue: props.value,
});
console.log(props.name)
// export default {
//   name: "CurrencyInput",
//   props: {
//     value: {
//     type: Number,
//     default: 0,
//   },
//   },
//   setup(props) {
//     const isInputActive = ref(false)
//     const initialValue = toRef(props, 'value')
//     const {
//       value: inputValue,
//       errorMessage,
//       handleBlur,
//       handleChange,
//       meta,
//     } = useField(name, undefined, {
//       initialValue: initialValue,
//     });
//     console.log(inputValue.value)
//     // const displayValue = computed({
//     //   get: function() {
//     //     if (isInputActive.value) {
//     //       // Cursor is inside the input field. unformat display value for user
//     //       return inputValue.value.toString()
//     //     } else {
//     //       // User is not modifying now. Format display value for user interface
//     //       return inputValue.value.toFixed(0).replace(/(\d)(?=(\d{3})+(?:\.\d+)?$)/g, "$1,").toLocaleString('it-IT', {style : 'currency', currency : 'VND'})
//     //     }
//     //   },
//     //   set: function(modifiedValue) {
//     //     // Recalculate value after ignoring "$" and "," in user input
//     //     let newValue = parseFloat(modifiedValue.replace(/[^\d\.]/g, ""))
//     //     // Ensure that it is not NaN
//     //     if (isNaN(newValue)) {
//     //       newValue = 0
//     //     }
//     //     // Note: we cannot set this.value as it is a "prop". It needs to be passed to parent component
//     //     // $emit the event so that parent component gets it
//     //     inputValue.value = newValue
//     //   }
//     // })
//
//     return {
//       isInputActive,
//       inputValue,
//       value,
//       // displayValue
//     }
//   }
// }
</script>

<style scoped>

</style>
