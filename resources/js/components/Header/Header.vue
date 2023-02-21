<template>
  <div class="h-[140px]">
    <div class="h-[56px] flex justify-between w-full bg-[#f3f3f3] p-3 border-b border-[#e7eaec]">
      <div class="flex h-full">
        <div class="flex items-center bg-[#1ab394] hover:bg-[#18a689] rounded-md px-1 cursor-pointer mr-3">
          <Hamburger />
        </div>
        <div class="flex justify-center items-center">
          <div class="mr-2 flex items-center">
            <i class="fa fa-lg fa-cogs"></i>
          </div>
          <span class="font-bold text-2xl">CD-admin</span>
        </div>
      </div>
      <div class="flex items-center justify-end">
        <div v-show="isShowHeader" class="w-full flex items-center">
        </div>
        <div class="cursor-pointer hover:bg-gray-300 p-1 rounded-md min-w-fit mr-4 text-lg" @click="handleLogout">
          Đăng xuất
        </div>
      </div>
    </div>
    <div class="h-[84px] py-4 px-5 bg-white border-b border-[#e7eaec]">
      <Breadcrumb />
    </div>
  </div>
</template>

<script setup>
import logoTimeSharing from "@/assets/images/time_sharing_logo.png";
import { logout } from "@/api";
import { removeToken } from "@/utils/authToken";
import { TYPE_USER } from "@/const";
import Hamburger from "@/components/icons/Hamburger.vue";
import Breadcrumb from "@/components/Breadcrumb/Breadcrumb.vue";

const props = defineProps({
  isShowHeader: {
    type: Boolean,
    default: true,
  },
})

const handleLogout = async () => {
  try {
    await logout();
    removeToken(TYPE_USER.ADMIN);
    window.location.reload();
  } catch (error) {
    console.log("error: ", error);
  }
}
</script>
