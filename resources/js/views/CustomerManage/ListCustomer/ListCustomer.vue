<template>
  <div class="p-5 mt-8 mx-5 bg-white">
    <div class="w-full h-8 flex justify-between">
      <div class="mr-1 relative">
        <input type="text" @input="onInput" class="outline-none min-w-[150px] h-full border border-gray-300 rounded-sm px-10" placeholder="Tìm khách hàng">
        <div class="absolute top-[50%] left-2 -translate-y-1/2">
          <SearchIcon />
        </div>
      </div>
      <ButtonAddNew @clickBtn="handleAddUserManage" :text="addNewCustomer" />
    </div>

    <!-- *********** -->
    <div class="mt-4">
      <table class="w-full">
        <thead>
          <tr class="">
            <th class="border py-1 w-[5%]">
                #
            </th>
            <th class="border py-1 w-[20%]">
              Tên
            </th>
<!--            <th class="border py-1 w-[20%]">-->
<!--              Email-->
<!--            </th>-->
            <th class="border py-1 w-[15%]">
              Địa chỉ
            </th>
            <th class="border py-1 w-[10%]">
              Số điên thoại
            </th>
            <th class="border py-1 w-[10%]">
              Trạng thái
            </th>
            <th class="border py-1 w-[10%]">
              Cập nhật
            </th>
          </tr>
        </thead>
        <tbody>
        <tr v-for="(item, index) in listCustomer" :key="index">
          <td class="border text-center">{{ ++index }}</td>
          <td class="border text-center">{{ item.customer_name }}</td>
          <td class="border text-center">{{ item.address }}</td>
          <td class="border text-center">{{ item.phone }}</td>
          <td class="border text-center">{{ item.status ? 'Hoạt động' : '-' }}</td>
          <td class="border text-center">
            <div class="flex justify-center ">
              <ButtonEdit @clickBtn="() => goToUpdate(item.customer_id)" :text="editCustomer"/>
            </div>
          </td>
          </tr>
        </tbody>
      </table>
    </div>

    <Pagination
      v-if="pagination"
      :pageCurrent="pagination.current_page"
      :totalPage="pagination.total"
      @onBack="handleBackPage"
      @onNext="handleNextPage"
    />
  </div>
</template>

<script setup>
import { ROUTER_PATH, MODULE_STORE, PAGE_DEFAULT } from "@/const";
import ButtonAddNew from "@/components/Buttons/ButtonAddNew";
import ButtonEdit from "@/components/Buttons/ButtonEdit";
import {getListCustomerFromApi} from "@/api";
import { ref, computed, watch, inject } from "vue";
import { useRouter, useRoute } from "vue-router";
import { useStore } from "vuex";
import Pagination from "@/components/Pagination";
import SearchIcon from "@/components/icons/SearchIcon.vue";

const filterKey = ref({});
const isShowSort = ref(false);
const timeDatePicker = ref(new Date());
const listCustomer = ref([]);
const route = useRoute();
const router = useRouter();
const store = useStore();
const toast = inject("$toast");
const pagination = ref(null);
const addNewCustomer = "Thêm người dùng";
const editCustomer = "Cập nhật";

const pageCurrent = computed(() => {
  if (!route.query.page) {
    return PAGE_DEFAULT;
  }
  return Number(route.query.page);
});

const handleClickSortFn = () => {
  isShowSort.value = !isShowSort.value;
};
const handleAddUserManage = () => {
  router.push(`${ROUTER_PATH.CUSTOMER_MANAGE}/${ROUTER_PATH.ADD}`);
};
const goToUpdate = (id) => {
  router.push(`${ROUTER_PATH.CUSTOMER_MANAGE}/${ROUTER_PATH.EDIT}/` + id);
};
const getListCustomer = async (page, keyword) => {
  try {
    store.state[MODULE_STORE.COMMON.NAME].isLoadingPage = true;
    const response = await getListCustomerFromApi(page, {params:{keyword: keyword}});
    pagination.value = response.pagination;
    listCustomer.value = {
      ...response.data,
    };
  } catch (errors) {
    toast.error(errors.message)
  } finally {
    store.state[MODULE_STORE.COMMON.NAME].isLoadingPage = false;
  }
};

const handleBackPage = (page) => {
  getListCustomer(page)
};
const handleNextPage = (page) => {
  getListCustomer(page)
};
const onInput = async (e) => {
  const keyword = e.target.value
  await getListCustomer(pageCurrent.value, keyword);
}

getListCustomer(pageCurrent.value);

store.state[MODULE_STORE.COMMON.NAME].breadcrumbCurrent = 'Khách hàng'
store.state[MODULE_STORE.COMMON.NAME].breadcrumbItems = [
  {
    label: 'Trang chủ',
    link: '/dashboard'
  },
]

</script>

<style scoped></style>
