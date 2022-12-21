<template>
  <div>
    <Form
      @submit="handleSubmit"
      :validation-schema="schema"
      @invalid-submit="onInvalidSubmit"
    >
<!--      <Field name="price" type="number" :rules="validatePrice" class="border border-gray-200" @change="formatPrice" :value="price"/>-->
<!--      <ErrorMessage name="price" />-->
<!--      <Field name="comment" type="text" :rules="validateComment" class="border border-gray-200"/>-->
      <CurrencyInput
        name="price"
        type="text"
        class="border border-gray-200"
        label="Full Name"
        placeholder="Your Name"
        success-message="Nice to meet you!"
      />
      <button class="submit-btn border border-gray-200 p-3" type="submit">Submit</button>
    </Form>
  </div>
</template>

<script setup>
import {ErrorMessage, Field, Form, useField} from "vee-validate";
import {defineProps, ref, toRef} from 'vue';
import * as Yup from 'yup';
import CurrencyInput from "@/views/DebtManage/CreateDebt/CurrencyInput.vue";
import {setLocale} from "yup";


setLocale({
  // use constant translation keys for messages without values
  mixed: {
    default: 'field_invalid',
  },
  // use functions to generate an error object that includes the value from the schema
  number: {
    min: ({ min }) => ({ key: 'field_too_short', values: { min } }),
    max: ({ max }) => ({ key: 'field_too_big', values: { max } }),
  },
});

const schema = Yup.object().shape({
  price: Yup.number().min(3).required().typeError("Custom not a number message!"),
});

// setLocale({
//   price: {
//     default: 'field_invalid',
//   },
// });

function handleSubmit(values) {
  alert(JSON.stringify(values, null, 2));
}

function onInvalidSubmit() {
  const submitBtn = document.querySelector('.submit-btn');
  submitBtn.classList.add('invalid');
  setTimeout(() => {
    submitBtn.classList.remove('invalid');
  }, 1000);
}

// export default {
//   name: "ContainerOrderItem",
//   components: {Form, Field, ErrorMessage, CurrencyInput},
//   setup() {
//     const price = ref(0)
//     const handleSubmit = () => {
//       console.log('Submitting :(');
//     }
//     //
//     // const formatPrice = () => {
//     //   price.value.toLocaleString('it-IT', {style : 'currency', currency : 'VND'})
//     // }
//     //
//     // const validatePrice = (value) => {
//     //   // if the field is empty
//     //   if (!value) {
//     //     return 'This field is required';
//     //   }
//     //   // if the field is not a valid email
//     //   const regex = /^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$/i;
//     //   if (!regex.test(value)) {
//     //     return 'This field must be a valid email';
//     //   }
//     //   // All is good
//     //   return true;
//     // }
//
//     const onInvalidSubmit = () => {
//       const submitBtn = document.querySelector('.submit-btn');
//       submitBtn.classList.add('invalid');
//       setTimeout(() => {
//         submitBtn.classList.remove('invalid');
//       }, 1000);
//     }
//
//     const validateComment = () => {
//
//     }
//
//     const schema = Yup.object().shape({
//       price: Yup.number().required(),
//     });
//
//     return {
//       price,
//       schema,
//       handleSubmit,
//       onInvalidSubmit,
//       // validatePrice,
//       // formatPrice,
//       validateComment
//     }
//   }
// }
</script>

<style scoped>
.submit-btn {
  background: #1DA1F2;
  outline: none;
  border: none;
  color: #fff;
  font-size: 18px;
  padding: 10px 15px;
  display: block;
  width: 100%;
  border-radius: 7px;
  margin-top: 40px;
  transition: transform 0.3s ease-in-out;
  cursor: pointer;
}

.submit-btn.invalid {
  animation: shake 0.5s;
  background: #9ca3af;
  /* When the animation is finished, start again */
  animation-iteration-count: infinite;
}
</style>
