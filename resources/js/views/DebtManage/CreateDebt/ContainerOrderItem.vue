<template>
  <div>
    <Form @submit="handleSubmit">
      <Field name="price" type="number" :rules="validatePrice" class="border border-gray-200" @change="formatPrice" :value="price"/>
      <ErrorMessage name="price" />
      <Field name="comment" type="text" :rules="validateComment" class="border border-gray-200"/>
      <button>Sign up for newsletter</button>
    </Form>
  </div>
</template>

<script>
import {ErrorMessage, Field, Form, useField} from "vee-validate";
import {defineProps, ref, toRef} from 'vue';
import * as yup from 'yup';

export default {
  name: "ContainerOrderItem",
  components: {Form, Field, ErrorMessage},
  setup() {
    const price = ref(0)
    const handleSubmit = () => {
      console.log('Submitting :(');
    }

    const formatPrice = () => {
      console.log(234234)
      price.value.toLocaleString('it-IT', {style : 'currency', currency : 'VND'})
    }

    const validatePrice = (value) => {
      // if the field is empty
      if (!value) {
        return 'This field is required';
      }
      // if the field is not a valid email
      const regex = /^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$/i;
      if (!regex.test(value)) {
        return 'This field must be a valid email';
      }
      // All is good
      return true;
    }

    const validateComment = () => {

    }

    return {
      price,
      handleSubmit,
      validatePrice,
      formatPrice,
      validateComment
    }
  }
}
</script>

<style scoped>

</style>
