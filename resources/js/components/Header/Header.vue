<template>
  <div class="flex w-full bg-[#f3f3f3] py-4 px-1 border-b border-[#e7eaec]">
    <div class="w-[14%] h-full">
        <div class="flex justify-center items-center">
            <div class="mr-2 flex items-center">
                <i class="fa fa-lg fa-cogs"></i>
            </div>
            <span class="font-bold text-2xl">CD-admin</span>
        </div>
    </div>
    <div class="w-[86%] flex items-center justify-end">
      <div v-show="isShowHeader" class="w-full flex items-center">
      </div>
      <div class="cursor-pointer hover:bg-gray-300 p-1 rounded-md min-w-fit mr-4 text-lg" @click="handleLogout">
        Đăng xuất
      </div>
    </div>
  </div>
</template>

<script>
import logoTimeSharing from "@/assets/images/time_sharing_logo.png";
import { logout } from "@/api";
import { removeToken } from "@/utils/authToken";
import { TYPE_USER } from "@/const";

export default {
  name: "Header",

  data() {
    return {};
  },
  props: {
    isShowHeader: {
      type: Boolean,
      default: true,
    },
  },
  computed: {
    logoTimeSharing() {
      return logoTimeSharing;
    },
  },
  mounted() {},

  methods: {
    async handleLogout() {
        console.log(123)
      try {
        await logout();
        removeToken(TYPE_USER.ADMIN);
        window.location.reload();
      } catch (error) {
        console.log("error: ", error);
      }
    },
  },
};
</script>
