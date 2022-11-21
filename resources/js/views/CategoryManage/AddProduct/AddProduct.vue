<template>
<!--  <FormUserManage />-->
    <div class="w-full h-full relative">
        <div class="w-[650px] pt-14 h-full absolute left-20">
            <div class="w-full py-6 py-auto text-xl">
                <span class="text-gray-500">Thêm sản phẩm</span>
            </div>
            <FormKit type="form" @submit="handleSubmit(formData)" :actions="false" submit-label="Register" :form-class="hide">
                <FormKit
                    type="text"
                    label="Tên sản phẩm"
                    name="name"
                    placeholder="Nhập tên sản phẩm"
                    validation="required"
                    :classes="{
                      outer: 'mb-5',
                      label: 'block mb-1 font-bold text-sm',
                      inner: 'max-w-md border border-gray-400 rounded-lg mb-1 overflow-hidden focus-within:border-blue-500',
                      input: 'w-full h-10 px-3 border-none text-base text-gray-700 placeholder-gray-400',
                      help: 'text-xs text-gray-500'
                    }"
                    v-model="formData.name"
                />
                <FormKit
                    type="select"
                    label="Danh mục"
                    name="category"
                    placeholder="Nhập giá sản phẩm"
                    validation="number"
                    :classes="{
                      outer: 'mb-5',
                      label: 'block mb-1 font-bold text-sm',
                      inner: 'max-w-md border border-gray-400 rounded-lg mb-1 overflow-hidden focus-within:border-blue-500',
                      input: 'w-full h-10 px-3 border-none text-base text-gray-700 placeholder-gray-400',
                      help: 'text-xs text-gray-500'
                    }"
                    v-model="formData.categories"
                />
                <FormKit
                    type="number"
                    label="Giá thành"
                    name="price"
                    placeholder="Nhập giá sản phẩm"
                    validation="number"
                    :classes="{
                      outer: 'mb-5',
                      label: 'block mb-1 font-bold text-sm',
                      inner: 'max-w-md border border-gray-400 rounded-lg mb-1 overflow-hidden focus-within:border-blue-500',
                      input: 'w-full h-10 px-3 border-none text-base text-gray-700 placeholder-gray-400',
                      help: 'text-xs text-gray-500'
                    }"
                    v-model="formData.price"
                />
                <FormKit
                    type="file"
                    label="Hình ảnh"
                    name="image"
                    placeholder="hình ảnh"
                    :classes="{
                      outer: 'mb-5',
                      label: 'block mb-1 font-bold text-sm',
                      inner: 'max-w-md border border-gray-400 rounded-lg mb-1 overflow-hidden focus-within:border-blue-500',
                      input: 'w-full h-10 px-3 border-none text-base text-gray-700 placeholder-gray-400',
                      help: 'text-xs text-gray-500'
                    }"
                    v-model="formData.image"
                />
                <FormKit
                    type="textarea"
                    label="Mô tả"
                    name="image"
                    placeholder="Mô tả sản phẩm"
                    :classes="{
                      outer: 'mb-5',
                      label: 'block mb-1 font-bold text-sm',
                      inner: 'max-w-md border border-gray-400 rounded-lg mb-1 overflow-hidden focus-within:border-blue-500',
                      input: 'w-full h-10 px-3 border-none text-base text-gray-700 placeholder-gray-400',
                      help: 'text-xs text-gray-500'
                    }"
                    v-model="formData.image"
                />
                <FormKit
                    type="submit"
                    label="Thêm mới"
                    :classes="{
                      outer: 'mb-0 mt-8',
                      label: 'block mb-1 font-bold text-sm text-white',
                      input: 'bg-gray-400 h-10 px-3 border-gray-50 text-base text-gray-50 rounded-md',
                    }"
                />
            </FormKit>
        </div>
    </div>
</template>

<script>
// import FormUserManage from "@/components/FormUserManage";
import {ref} from "vue";
import {useRouter} from "vue-router";
import {useStore} from "vuex";
import {MODULE_STORE, ROUTER_PATH} from "@/const";
import {createCustomerFromApi, createUserFromApi} from "@/api";

export default {
  name: "AddProduct",
  components: {  },
  setup() {
      const router = useRouter()
      const store = useStore()
      const formData = ref({
          name: '',
          email: '',
          password: '123132',
          phone: '',
          status: true
      })

      const handleSubmit = async (data) => {
          try {
              const res = await createCustomerFromApi({
                  customer_name: data.name,
                  email: data.email,
                  password: data.password,
                  phone: data.phone,
                  status: data.status,
              })
              router.push(`${ROUTER_PATH.ADMIN}/${ROUTER_PATH.CUSTOMER_MANAGE}`)
          } catch (errors) {
              const error = errors.message;
              // this.$toast.error(error);
          } finally {
              store.state[MODULE_STORE.COMMON.NAME].isLoadingPage = false;
          }
      }

      return {
          formData,
          handleSubmit
      }
  }
};
</script>

<style scoped></style>
