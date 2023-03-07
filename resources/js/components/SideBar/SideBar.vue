<template>
  <div class="">
    <div class="h-[140px] p-8">
      <div class="w-[50px] h-[50px] rounded-full overflow-hidden mb-2">
        <img :src="AvatarDefault" alt="none">
      </div>
      <span class="font-bold text-base text-gray-200 text-[#a7b1c2]">{{ userName }}</span>
    </div>
    <ul class="">
      <router-link
        v-for="(item, index) in list"
        :key="index"
        class="py-4 px-2 flex items-center hover:text-white text-[#a7b1c2] hover:bg-[#293846] bg-[#2f4050] rounded"
        :to="item.link"
      >
        <div class="mx-2 flex items-center text-current">
          <i class="font-medium text-current" :class="item.icon"></i>
        </div>
        <span class="text-sm font-medium text-current">{{ item.title }}</span>
      </router-link>
    </ul>
  </div>
</template>

<script setup>
import {ROUTER_PATH} from "@/const";
import {ref} from "vue";
import {useStore} from "vuex";
import AvatarDefault from "@/assets/images/avatar_default.png"
import {useI18n} from "vue-i18n";

const userName = ref('')
const store = useStore()
const t = useI18n()
const list = ref([
  {
    id: 1,
    title: 'Trang chủ admin',
    link: `${ROUTER_PATH.ADMIN}/${ROUTER_PATH.DASHBOARD}`,
    icon: "fa fa-lg fa-home",
  },
  {
    id: 2,
    title: 'Quản lý đơn hàng',
    link: `${ROUTER_PATH.ADMIN}/${ROUTER_PATH.ORDER_MANAGE}`,
    icon: "fa fa-lg fa-diamond",
  },
  {
    id: 3,
    title: 'Công nợ',
    link: `${ROUTER_PATH.ADMIN}/${ROUTER_PATH.DEBT_MANAGE}`,
    icon: "fa fa-lg fa-file-text-o",
  },
  {
    id: 4,
    title: 'Nhập hàng',
    link: `${ROUTER_PATH.ADMIN}/${ROUTER_PATH.IMPORT_GOOD_MANAGE}`,
    icon: "fa fa-lg fa-exchange",
  },
  {
    id: 5,
    title: 'Báo giá',
    link: `${ROUTER_PATH.ADMIN}/${ROUTER_PATH.PRODUCT_MANAGE}/${ROUTER_PATH.PRICE_MANAGE}`,
    icon: "fa fa-lg fa-pencil-square-o",
  },
  {
    id: 6,
    title: 'Quản lý sản phẩm',
    link: `${ROUTER_PATH.ADMIN}/${ROUTER_PATH.PRODUCT_MANAGE}`,
    icon: "fa fa-lg fa-product-hunt",
  },
  {
    id: 7,
    title: 'Khách hàng',
    link: `${ROUTER_PATH.ADMIN}/${ROUTER_PATH.CUSTOMER_MANAGE}`,
    icon: "fa fa-lg fa-users",
  },
  {
    id: 8,
    title: 'Danh mục sản phẩm',
    link: `${ROUTER_PATH.ADMIN}/${ROUTER_PATH.CATEGORY_MANAGE}`,
    icon: "fa fa-lg fa-tasks",
  },
  {
    id: 9,
    title: 'Quản lý người dùng',
    link: `${ROUTER_PATH.ADMIN}/${ROUTER_PATH.USER_MANAGER}`,
    icon: "fa fa-lg fa-user",
  },
])

const getUserName = () => {
  userName.value = window.localStorage.getItem('user_name')
}

getUserName()

</script>

<style scoped>
.router-link-active {
  background-color: #293846;
  color: white;
}
</style>
