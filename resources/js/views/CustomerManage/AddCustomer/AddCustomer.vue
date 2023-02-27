<template>
  <div class="p-5 mt-8 mx-5 bg-white">
    <div class="w-[650px] mt-5 ml-5 bg-white border border-t-[2px] border-[#e7eaec]">
      <div class="py-4 px-3 border-b border-[#e7eaec] text-md text-gray-700">
        Thêm khách hàng
      </div>
      <form @submit.prevent="handleSubmit" class="p-3">
        <div class="py-1">
          <label for="name" class="block py-2 font-bold text-lg">
            <span>Tên khách hàng</span>
            <span v-if="errors.name" class="ml-1 text-red-500">*</span>
          </label>
          <input type="text" name="name" placeholder="Nhập tên" v-model="formData.name" @input="onInputName"
                 class="w-full h-10 px-3 text-base text-gray-700 placeholder-gray-400 border border-gray-400 focus:border-[#8ddd8d] outline-none"
                 :class="!!errors['name'] ? 'border-red-500 border' : ''"
          >
        </div>
        <div class="py-1 hidden">
          <label for="email" class="block py-2 font-bold text-lg">
            <span>Email</span>
            <span class="text-xs text-gray-400 ml-1 font-extralight">(Tối thiểu 3 ký tự)</span>
            <span v-if="errors.email" class="ml-1 text-red-500">*</span>
          </label>
          <input type="text" name="email" placeholder="Nhập email" v-model="formData.email" @input="validInputEmail"
                 class="w-full h-10 px-3 text-base text-gray-700 placeholder-gray-400 border border-gray-400 focus:border-[#8ddd8d] outline-none"
          >
        </div>
        <div class="py-1 hidden">
          <label for="password" class="block py-2 font-bold text-lg">
            <span>Mật khẩu</span>
            <span class="text-xs text-gray-400 ml-1 font-extralight">(Tối thiểu 1000đ)</span>
            <span v-if="errors.password" class="ml-1 text-red-500">*</span>
          </label>
          <input type="number" name="password" placeholder="Nhập mật khẩu" v-model="formData.password" @input="validInputPassword"
                 class="w-full h-10 px-3 text-base text-gray-700 placeholder-gray-400 border border-gray-400 focus:border-[#8ddd8d] outline-none"
                 disabled
          >
        </div>
        <div class="py-1">
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
        <div class="py-1 hidden">
          <label for="status" class="block py-2 font-bold text-lg">
            <span>Trạng thái</span>
            <span class="text-xs text-gray-400 ml-1 font-extralight">(Tối thiểu 1000đ)</span>
            <span v-if="errors.status" class="ml-1 text-red-500">*</span>
          </label>
          <input type="checkbox" name="status" v-model="formData.status"
                 class="w-full h-10 px-3 text-base text-gray-700 placeholder-gray-400 border border-gray-400 focus:border-[#8ddd8d] outline-none"
          >
        </div>
        <div class="flex justify-end border-t border-[#e7eaec]">
          <input class="mt-3 p-2 text-base font-bold text-white bg-[#1ab394] hover:bg-[#18a689] cursor-pointer rounded-md"
                 type="submit" value="Thêm khách hàng">
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import {inject, reactive} from "vue";
import {useRouter} from "vue-router";
import {useStore} from "vuex";
import {MODULE_STORE, ROUTER_PATH} from "@/const";
import {createCustomerFromApi} from "@/api";
import * as Yup from "yup";

const router = useRouter()
const store = useStore()
const toast = inject("$toast");
const formData = reactive({
  name: '',
  email: 'thinhlv@gmail.com',
  password: '123456',
  phone: '',
  status: true
})
const errors = reactive({})

const nameSchema = Yup.object().shape({
  name: Yup.string().required().min(3, 'Tối thiểu 3 ký tự')
})
const phoneSchema = Yup.object().shape({
  phone: Yup.string().required().min(10, 'Tối thiểu 10 số')
})

const handleSubmit = async () => {
  try {
    await nameSchema.validate({name: formData.name}, { abortEarly: false })
    await phoneSchema.validate({phone: formData.phone}, { abortEarly: false })
    const res = await createCustomerFromApi({
      customer_name: formData.name,
      email: formData.email,
      password: formData.password,
      phone: formData.phone,
      status: formData.status,
    })
    await router.push(`${ROUTER_PATH.ADMIN}/${ROUTER_PATH.CUSTOMER_MANAGE}`)
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

store.state[MODULE_STORE.COMMON.NAME].breadcrumbCurrent = 'Thêm khách hàng'
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
</script>

<style scoped></style>
