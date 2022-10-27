<template>
  <div class="bg-[e5e7eb]">
    <div class="flex justify-center items-center py-3">
      <span class="font-bold text-2xl">{{ userName }}</span>
    </div>
    <ul class="">
      <router-link
        v-for="(item, index) in list"
        :key="index"
        class="flex items-center hover:bg-[#dbdbdb] py-2 rounded px-2"
        :to="item.link"
      >
        <div class="mx-2 flex items-center">
          <i class="" :class="item.icon"></i>
        </div>
        <span class="text-sm">{{ item.title }}</span>
      </router-link>
    </ul>
  </div>
</template>
<script>
import {MODULE_STORE, ROUTER_PATH} from "@/const";
import {ref} from "vue";
import {useStore} from "vuex";

export default {
  name: "SideBar",

  data() {
    return {
      list: [
        {
          id: 1,
          // title: this.$t("side_bar_components.dashboard"),
          title: 'Trang chủ admin',
          link: `${ROUTER_PATH.ADMIN}/${ROUTER_PATH.DASHBOARD}`,
          icon: "fa fa-lg fa-home",
        },
          {
          id: 2,
          // title: this.$t("side_bar_components.dashboard"),
          title: 'Quản lý người dùng',
          link: `${ROUTER_PATH.ADMIN}/${ROUTER_PATH.USER_MANAGER}`,
          icon: "fa fa-lg fa-user",
        },
          {
          id: 3,
          // title: this.$t("side_bar_components.dashboard"),
          title: 'Quản lý sản phẩm',
          link: `${ROUTER_PATH.ADMIN}/${ROUTER_PATH.PRODUCT_MANAGE}`,
          icon: "fa fa-lg fa-product-hunt",
        },
          {
              id: 7,
              // title: this.$t("side_bar_components.dashboard"),
              title: 'Danh mục sản phẩm',
              link: `${ROUTER_PATH.ADMIN}/${ROUTER_PATH.CATEGORY_MANAGE}`,
              icon: "fa fa-lg fa-tasks",
          },
          {
              id: 4,
              // title: this.$t("side_bar_components.dashboard"),
              title: 'Khách hàng',
              link: `${ROUTER_PATH.ADMIN}/${ROUTER_PATH.CUSTOMER_MANAGE}`,
              icon: "fa fa-lg fa-users",
          },
          {
              id: 5,
              // title: this.$t("side_bar_components.dashboard"),
              title: 'Quản lý đơn hàng',
              link: `${ROUTER_PATH.ADMIN}/${ROUTER_PATH.ROLE_MANAGE}`,
              icon: "fa fa-lg fa-diamond",
          },
          {
              id: 6,
              // title: this.$t("side_bar_components.dashboard"),
              title: 'Quản lý vai trò',
              link: `${ROUTER_PATH.ADMIN}/${ROUTER_PATH.ROLE_MANAGE}`,
              icon: "fa fa-lg fa-sitemap",
          },
        // {
        //   id: 3,
        //   title: this.$t("side_bar_components.external_reservation"),
        //   link: `${ROUTER_PATH.ADMIN}/${ROUTER_PATH.EXTERNAL_RESERVATION}`,
        //   icon: "fa fa-building-o i_br",
        // },
        // {
        //   id: 4,
        //   title: this.$t("side_bar_components.tour_management"),
        //   link: `${ROUTER_PATH.ADMIN}/${ROUTER_PATH.TOUR}`,
        //   icon: "fa fa-lg fa-star",
        // },
        // {
        //   id: 5,
        //   title: this.$t("side_bar_components.reservation_calendar"),
        //   link: "/home",
        //   icon: "fa fa-lg fa-calendar",
        // },
        // {
        //   id: 7,
        //   title: this.$t("side_bar_components.google_calendar_sync"),
        //   link: "/home",
        //   icon: "fa fa-lg fa-calendar-plus-o",
        // },
        // {
        //   id: 8,
        //   title: this.$t("side_bar_components.inquiries_to_the_space"),
        //   link: `${ROUTER_PATH.ADMIN}/${ROUTER_PATH.INQUIRIES}`,
        //   icon: "fa fa-lg fa-comment",
        // },
        // {
        //   id: 9,
        //   title: this.$t("side_bar_components.space_list"),
        //   link: `${ROUTER_PATH.ADMIN}/${ROUTER_PATH.ORDER}`,
        //   icon: "fa fa-lg fa-home",
        // },
        // {
        //   id: 10,
        //   title: this.$t("side_bar_components.user"),
        //   link: `${ROUTER_PATH.ADMIN}/${ROUTER_PATH.LIST_CUSTOMER}`,
        //   icon: "fa fa-lg fa-user",
        // },
        // {
        //   id: 13,
        //   title: this.$t("side_bar_components.responsible_person"),
        //   link: `${ROUTER_PATH.ADMIN}/${ROUTER_PATH.USER_MANAGER}`,
        //   icon: "fa fa-lg fa-user",
        // },
        // {
        //   id: 14,
        //   title: this.$t("side_bar_components.company_organization"),
        //   link: `/test`,
        //   icon: "fa fa-lg fa-star",
        // },
        // {
        //   id: 15,
        //   title: this.$t("side_bar_components.withdrawal"),
        //   link: "/home",
        //   icon: "fa fa-lg fa-inbox",
        // },
        // {
        //   id: 16,
        //   title: this.$t("side_bar_components.manage_settings"),
        //   link: `${ROUTER_PATH.ADMIN}/${ROUTER_PATH.CONFIGURATION}`,
        //   icon: "fa fa-lg fa-cogs",
        // },
      ],
    };
  },
  setup() {
    const userName = ref('')
    const store = useStore()
    const getUserName = () => {
      userName.value = store.state[MODULE_STORE.AUTH.NAME].userName
    }

    getUserName()

    return {userName}
  }
};
</script>
<style scoped>
.router-link-active {
  background-color: #dbdbdb;
}
</style>
