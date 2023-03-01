<template>
<!--  <FormUserManage :edit="true" />-->
  <div class="p-5 mt-8 mx-5 bg-white">
    <div class="w-[650px] mt-5 ml-5 bg-white border border-t-[2px] border-[#e7eaec]">
      <div class="py-4 px-3 border-b border-[#e7eaec] text-md text-gray-700">
        Thêm khách hàng
      </div>
      <form @submit.prevent="handleSubmit()">
        <div class="py-1 mx-3">
          <label for="name" class="block py-2 font-bold text-lg">
            <span>Tên khách hàng</span>
            <span v-if="errors.name" class="ml-1 text-red-500">*</span>
          </label>
          <input type="text" name="name" placeholder="Nhập tên" v-model="formData.name" @input="onInputName"
                 class="w-full h-10 px-3 text-base text-gray-700 placeholder-gray-400 border border-gray-400 focus:border-[#8ddd8d] outline-none"
                 :class="!!errors['name'] ? 'border-red-500 border' : ''"
          >
        </div>
        <div class="py-1 mx-3">
          <label for="address" class="block py-2 font-bold text-lg">
            <span>Địa chỉ</span>
            <span class="text-xs text-gray-400 ml-1 font-extralight"></span>
            <span v-if="errors.address" class="ml-1 text-red-500">*</span>
          </label>
          <input type="text" name="address" placeholder="Nhập địa chỉ" v-model="formData.address"
                 class="w-full h-10 px-3 text-base text-gray-700 placeholder-gray-400 border border-gray-400 focus:border-[#8ddd8d] outline-none"
                 :class="!!errors['address'] ? 'border-red-500 border' : ''"
          >
        </div>
        <div class="py-1 mx-3">
          <label for="phone" class="block py-2 font-bold text-lg">
            <span>Số điện thoại</span>
            <span class="text-xs text-gray-400 ml-1 font-extralight"></span>
            <span v-if="errors.phone" class="ml-1 text-red-500">*</span>
          </label>
          <input type="text" name="phone" placeholder="Nhập số điện thoại" v-model="formData.phone" @input="onInputPhone"
                 class="w-full h-10 px-3 text-base text-gray-700 placeholder-gray-400 border border-gray-400 focus:border-[#8ddd8d] outline-none"
                 :class="!!errors['phone'] ? 'border-red-500 border' : ''"
          >
        </div>
        <div class="flex justify-end border-t border-[#e7eaec] mt-4 p-2">
          <input class="mt-3 p-2 text-base font-bold text-white bg-[#1ab394] hover:bg-[#18a689] cursor-pointer rounded-md"
                 type="submit" value="Cập nhật">
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import {inject, reactive, ref} from "vue";
import { useRoute,useRouter } from 'vue-router'
import {MODULE_STORE, ROUTER_PATH} from "@/const";
import {getCustomerDetailFromApi, updateCustomerFormApi} from "@/api";
import {useStore} from "vuex";
import * as Yup from "yup";

const route = useRoute()
const router = useRouter()
const store = useStore()
const toast = inject('$toast')
const customerId = ref(route.params.id)
const formData = reactive({
  name: '',
  email: 'thinhlv@gmail.com',
  password: '123456',
  address: '',
  phone: '',
  status: true
})
const errors = reactive({})

const nameSchema = Yup.object().shape({
  name: Yup.string().required().min(3, 'Tối thiểu 3 ký tự')
})
const phoneSchema = Yup.object().shape({
  phone: Yup.string().min(10, 'Tối thiểu 10 số')
})

const getCustomerDetail = async (customerId) => {
  try {
    store.state[MODULE_STORE.COMMON.NAME].isLoadingPage = true;
    const res = await getCustomerDetailFromApi(customerId);
    const data = res.data
    formData.name = data.customer_name
    formData.phone = data.phone
  } catch (errors) {
    toast.error(errors.message);
  } finally {
    store.state[MODULE_STORE.COMMON.NAME].isLoadingPage = false;
  }
}
const handleSubmit = async () => {
  try {
    await nameSchema.validate({name: formData.name}, { abortEarly: false })
    // await phoneSchema.validate({phone: formData.phone}, { abortEarly: false })
    const res = await updateCustomerFormApi(customerId.value, {
      customer_name: formData.name,
      email: formData.email,
      password: formData.password,
      address: formData.address,
      phone: formData.phone,
      status: formData.status,
    })
    await router.push(`${ROUTER_PATH.ADMIN}/${ROUTER_PATH.CUSTOMER_MANAGE}`)
    toast.success('Cập nhật khách hàng thành công!', {duration:3000})
  } catch (validateErrors) {
    if (validateErrors.hasOwnProperty('inner')) {
      validateErrors.inner.forEach((e) => {
        errors[e.path] = e.message
      })
      return
    }
    toast.error(validateErrors.message)
  } finally {
    store.state[MODULE_STORE.COMMON.NAME].isLoadingPage = false;
  }
}
const onInputPhone = async (e) => {
  const number = e.target.value;
  const cleanValue = number.replace(/[^\d\s-]/g, '');
  formData.phone = cleanValue.replace(/(\d{3})\s?-?(\d{3})\s?-?(\d{4})/, '$1 $2 $3')

  delete errors['phone'];
}

const onInputName = async () => {
  delete errors['name'];
}

store.state[MODULE_STORE.COMMON.NAME].breadcrumbCurrent = 'Cập nhật'
store.state[MODULE_STORE.COMMON.NAME].breadcrumbItems = [
  {
    label: 'Trang chủ',
    link: '/dashboard'
  },
  {
    label: 'Khách hàng',
    link: '/customer_manage'
  },
]

getCustomerDetail(customerId.value)
</script>

<style scoped></style>
