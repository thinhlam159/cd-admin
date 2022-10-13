<template>
  <div class="flex w-full h-[50px] border border-solid border-[#dbdbdb] border-t-0 border-l-0 border-r-0 bg-white">
    <div class="w-[14%] h-full">
      <div class="w-full h-full flex items-center">
        <img :src="logoTimeSharing" alt="logoTimeSharing" class="w-4/5 h-4/5 object-contain" />
      </div>
    </div>
    <div class="w-[86%] flex items-center justify-end">
      <div v-show="isShowHeader" class="w-full flex items-center">
        <div class="flex items-center mr-3">
          <FormKit
            type="text"
            :label="$t('header.space_id_search')"
            value=""
            placeholder="ID"
            wrapper-class="flex"
            label-class=""
            inner-class="mx-1 border border-solid  rounded border-[#4F4F4F]"
            input-class="w-[100px] h-6 px-2"
          />
          <button class="bg-[#ebeaea] hover:bg-[#d6d5d5] border border-solid rounded border-[#4F4F4F] px-1 h-6">
            {{ $t("header.search") }}
          </button>
        </div>
        <div class="flex items-center mr-3">
          <FormKit
            type="text"
            :label="$t('header.reservation_id_search')"
            value=""
            placeholder="ID"
            wrapper-class="flex"
            label-class=""
            inner-class="mx-1 border border-solid  rounded border-[#4F4F4F]"
            input-class="w-[100px] h-6 px-2"
          />
          <button class="bg-[#ebeaea] hover:bg-[#d6d5d5] border border-solid rounded border-[#4F4F4F] px-1 h-6">
            {{ $t("header.search") }}
          </button>
        </div>
      </div>
      <div class="cursor-pointer hover:bg-gray-300 p-1 rounded-md min-w-fit mr-4" @click="handleLogout">
<!--        {{ $t("header.logout") }}-->
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
