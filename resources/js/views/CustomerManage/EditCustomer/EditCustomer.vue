<template>
<!--  <FormUserManage :edit="true" />-->
  <div class="w-full h-full relative">
    <div class="w-[650px] pt-14 h-full absolute left-20">
      <div class="w-full py-6 py-auto text-xl">
        <span class="text-gray-500">Cập nhật thông tin người dùng</span>
      </div>
      <form @submit.prevent="handleSubmit()">
        <div class="py-1">
          <p>Tên khách hàng </p>
          <input type="text" class="outline-none p-1 border border-gray-400" v-model="name">
        </div>
        <div class="py-1">
          <p>Email </p>
          <input type="text" class="outline-none p-1 border border-gray-400" v-model="email">
        </div>
        <div class="py-1">
          <p>Phone </p>
          <input type="text" class="outline-none p-1 border border-gray-400" v-model="phone">
        </div>
        <div class="py-1">
          <p>Trạng thái </p>
          <input type="checkbox" class="outline-none p-1 border border-gray-400" v-model="status">
        </div>
        <button type="submit" class="p-2 border-gray-400 border bg-[#00FF00]">Lưu</button>
      </form>

    </div>
  </div>
</template>

<script setup>
import {inject, nextTick, ref} from "vue";
import { useRoute,useRouter } from 'vue-router'
import {MODULE_STORE, ROUTER_PATH} from "@/const";
import {getCustomerDetailFromApi, updateCustomerFormApi} from "@/api";
import {useStore} from "vuex";

const route = useRoute()
const router = useRouter()
const store = useStore()
const toast = inject('$toast')
const customerId = ref(route.params.id)
const formData = ref({})
const name = ref('')
const email = ref('')
const phone = ref('')
const status = ref(true)
const getCustomerDetail = async (customerId) => {
  try {
    store.state[MODULE_STORE.COMMON.NAME].isLoadingPage = true;
    const res = await getCustomerDetailFromApi(customerId);
    const data = res.data
    name.value = data.customer_name
    email.value = data.email
    phone.value = data.phone
    status.value = data.status
  } catch (errors) {
    toast.error(errors.message);
  } finally {
    store.state[MODULE_STORE.COMMON.NAME].isLoadingPage = false;
  }
}
const handleSubmit = async () => {
  try {
    const res = await updateCustomerFormApi(customerId.value, {
      customer_name: name.value,
      email: email.value,
      status: status.value,
      phone: phone.value
    })
    await router.push(`${ROUTER_PATH.ADMIN}/${ROUTER_PATH.CUSTOMER_MANAGE}`)
    toast.success('Cập nhật khách hàng thành công!', {duration:3000})
  } catch (errors) {
    const error = errors.message;
    toast.error(error);
  } finally {
    store.state[MODULE_STORE.COMMON.NAME].isLoadingPage = false;
  }

}

getCustomerDetail(customerId.value)
</script>

<style scoped></style>
