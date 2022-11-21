<template>
<!--  <FormUserManage :edit="true" />-->
    <div class="w-full h-full relative">
        <div class="w-[650px] pt-14 h-full absolute left-20">
            <div class="w-full py-6 py-auto text-xl">
                <span class="text-gray-500">Cập nhật thông tin người dùng</span>
            </div>
            <FormKit type="form" v-model="data" @submit="handleSubmit(userId, formData)" :actions="false" submit-label="Register" :form-class="hide">
                <FormKit
                    type="text"
                    label="Tên"
                    name="name"
                    placeholder="..."
                    validation="required"
                    :classes="{
                      outer: 'mb-5',
                      label: 'block mb-1 font-bold text-sm',
                      inner: 'max-w-md border border-gray-400 rounded-lg mb-1 overflow-hidden focus-within:border-blue-500',
                      input: 'w-full h-10 px-3 border-none text-base text-gray-700 placeholder-gray-400',
                      help: 'text-xs text-gray-500'
                    }"
                    v-model="formData.userName"
                />
                <FormKit
                    type="text"
                    label="Email"
                    name="email"
                    placeholder="email@domain.com"
                    validation="required|email"
                    :classes="{
                      outer: 'mb-5',
                      label: 'block mb-1 font-bold text-sm',
                      inner: 'max-w-md border border-gray-400 rounded-lg mb-1 overflow-hidden focus-within:border-blue-500',
                      input: 'w-full h-10 px-3 border-none text-base text-gray-700 placeholder-gray-400',
                      help: 'text-xs text-gray-500'
                    }"
                    v-model="formData.email"
                />
                <FormKit
                    type="text"
                    label="Số điện thoại"
                    name="email"
                    placeholder="0000.000.000"
                    validation="number"
                    :classes="{
                      outer: 'mb-5',
                      label: 'block mb-1 font-bold text-sm',
                      inner: 'max-w-md border border-gray-400 rounded-lg mb-1 overflow-hidden focus-within:border-blue-500',
                      input: 'w-full h-10 px-3 border-none text-base text-gray-700 placeholder-gray-400',
                      help: 'text-xs text-gray-500'
                    }"
                    v-model="formData.phone"
                />
                <FormKit
                    v-model="formData.status"
                    type="checkbox"
                    label="Kích hoạt"
                    name="status"
                    validation="accepted"
                    :classes="{
                      outer: 'mb-0 mt-8',
                      label: 'font-bold text-sm',
                      input: 'bg-gray-400 border-gray-50 text-base text-gray-50 rounded-md',
                      inner: 'inline mr-5'
                    }"
                />
                <FormKit
                    type="submit"
                    label="Cập nhật"
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
import {ref} from "vue";
import {useRoute, useRouter} from 'vue-router'
import {MODULE_STORE, ROUTER_PATH} from "@/const";
import {getUserDetailFromApi, updateUserProfileFormApi} from "@/api";
import {useStore} from "vuex";

export default {
  name: "EditUserManage",
  components: { },

  data() {
    return {};
  },

  mounted() {},

  methods: {},
    setup() {
      const route = useRoute()
      const router = useRouter()
      const store = useStore()
      const userId = ref(route.params.id)
      const formData = ref({
        userName: '',
        email: '',
        phone: '',
        status: true
      })

      const getUserDetail = async (userId) => {
          try {
              store.state[MODULE_STORE.COMMON.NAME].isLoadingPage = true;
              const response = await getUserDetailFromApi(userId);
              formData.value = {
                  ...formData.value,
                  userName: response.user_name,
                  email: response.email,
              };
          } catch (errors) {
              const error = errors.message || this.$t("common.has_error");
              // this.$toast.error(error);
          } finally {
              store.state[MODULE_STORE.COMMON.NAME].isLoadingPage = false;
          }
      }
      const handleSubmit = async (userId, data) => {
          try {
              const res = await updateUserProfileFormApi(userId, {
                  user_name: data.userName,
                  email: data.email
              })
              const response = await getUserDetailFromApi(userId);
              formData.value = {
                  ...formData.value,
                  userName: response.user_name,
                  email: response.email,
              };
              router.push(`${ROUTER_PATH.ADMIN}/${ROUTER_PATH.USER_MANAGER}`)
          } catch (errors) {
              const error = errors.message;
              // this.$toast.error(error);
          } finally {
              store.state[MODULE_STORE.COMMON.NAME].isLoadingPage = false;
          }

      }

      getUserDetail(userId.value)

      return {
          userId,
          formData,
          handleSubmit
      }
    }
};
</script>

<style scoped></style>
