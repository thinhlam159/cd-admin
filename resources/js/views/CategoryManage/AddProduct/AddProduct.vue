<template>
<!--  <FormUserManage />-->
    <div class="w-full h-full relative">
        <div class="w-[650px] pt-14 h-full absolute left-20">
            <div class="w-full py-6 py-auto text-xl">
                <span class="text-gray-500">Thêm danh mục</span>
            </div>

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
