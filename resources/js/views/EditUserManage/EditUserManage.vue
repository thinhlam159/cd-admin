<template>
<!--  <FormUserManage :edit="true" />-->
    <div class="w-full flex justify-center h-full">
        <div class="w-[650px] bg-gray-50 pt-4">
        </div>
    </div>
</template>

<script>
import FormUserManage from "@/components/FormUserManage";
import {ref} from "vue";
import { useRoute } from 'vue-router'
import {MODULE_STORE} from "@/const";
import {getUserDetailFromApi} from "@/api";
// import {FormKit} from "@formkit/vue";

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
      const userId = ref(route.params.id)
      const formData = ref({
          userName: '',
          email: '',
          phone: '',
          status: true
      })
      const getUserDetail = async (userId) => {
          try {
              // this.$store.state[MODULE_STORE.COMMON.NAME].isLoadingPage = true;
              const response = await getUserDetailFromApi(userId);
              this.formData = {
                  ...this.formData,
                  userName: response.user_name,
                  email: response.email,
              };
          } catch (errors) {
              const error = errors.message || this.$t("common.has_error");
              // this.$toast.error(error);
          } finally {
              // this.$store.state[MODULE_STORE.COMMON.NAME].isLoadingPage = false;
          }
      }

      getUserDetail(userId.value)
      return {
          userId
      }
    }
};
</script>

<style>
.only-my-class {
    color: red
}
</style>
