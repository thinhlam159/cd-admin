<template>
<!--  <FormUserManage :edit="true" />-->
    <div class="w-full h-full relative">
        <div class="w-[650px] pt-14 h-full absolute left-20">
            <div class="w-full py-6 py-auto text-xl">
                <span class="text-gray-500">Chi tiết đơn hàng</span>
            </div>

        </div>
    </div>
</template>

<script>
import {ref} from "vue";
import { useRoute,useRouter } from 'vue-router'
import {MODULE_STORE, ROUTER_PATH} from "@/const";
import {getCustomerDetailFromApi, updateCustomerFormApi, getOrderDetailFromApi} from "@/api";
import {useStore} from "vuex";

export default {
  name: "DetailOrder",
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
      const OrderId = ref(route.params.id)
      const formData = ref({})

      const getOrderDetail = async (id) => {
        try {
          console.log(id)
          store.state[MODULE_STORE.COMMON.NAME].isLoadingPage = true;
          const response = await getOrderDetailFromApi(id);
          formData.value = {
            ...formData.value,
          };
          console.log(response)
        } catch (errors) {
          const error = errors.message || this.$t("common.has_error");
          // this.$toast.error(error);
        } finally {
          store.state[MODULE_STORE.COMMON.NAME].isLoadingPage = false;
        }
      }
      const handleSubmit = async (CustomerId, data) => {
        try {
          const res = await updateCustomerFormApi(CustomerId, {
            user_name: data.userName,
            email: data.email,
            status: data.status,
            phone: data.phone
          })
          const response = await getCustomerDetailFromApi(CustomerId);
          formData.value = {
            ...formData.value,
            userName: response.customer_name,
            email: response.email,
          };
        } catch (errors) {
          const error = errors.message;
          // this.$toast.error(error);
        } finally {
          store.state[MODULE_STORE.COMMON.NAME].isLoadingPage = false;
        }

      }

      getOrderDetail(OrderId.value)

      return {
        OrderId,
        formData,
        handleSubmit
      }
    }
};
</script>

<style scoped></style>
