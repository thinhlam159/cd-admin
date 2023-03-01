<template>
  <div class="p-5 mt-8 mx-5 bg-white">
    <div class="w-full h-8 flex justify-end">
      <ButtonAddNew @clickBtn="goToAddCategory" :text="addNewUser" />
    </div>
    <!-- *********** -->
    <div class="mt-4">
      <table class="w-full">
        <thead>
          <tr class="">
            <th class="border py-1 w-[5%]">
                Stt
            </th>
            <th class="border py-1 w-[20%]">
              Tên danh mục
            </th>
              <th class="border py-1 w-[10%]">
                  Slug danh mục
              </th>
<!--              <th class="border py-1 w-[10%]">-->
<!--                  Danh mục cha-->
<!--              </th>-->
            <th class="border py-1 w-[10%]">
              Cập nhật
            </th>
<!--            <th class="border py-1 w-[5%]"></th>-->
          </tr>
        </thead>
        <tbody>
          <tr v-for="(item, index) in listCategory" :key="item.id">
              <td class="border text-center">{{ ++index }}</td>
              <td class="border text-center">{{ item.name }}</td>
              <td class="border text-center">{{ item.slug }}</td>
<!--              <td class="border text-center">{{ item.parent_id }}</td>-->
            <td class="border text-center">
                <div class="flex justify-center ">
                    <ButtonEdit @clickBtn="() => goToAdd(item.category_id)" :text="editUser"/>
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
import {getListCategoryFromApi} from "@/api";
import { ref, computed, watch, inject } from "vue";
import { useRouter, useRoute } from "vue-router";
import { useStore } from "vuex";
import Pagination from "@/components/Pagination";

const filterKey = ref({});
const isShowSort = ref(false);
const timeDatePicker = ref(new Date());
const listCategory = ref([]);
const route = useRoute();
const router = useRouter();
const store = useStore();
const toast = inject("$toast");
const pagination = ref(null);
const addNewUser = "Thêm danh mục sản phẩm";
const editUser = "Cập nhật";

const pageCurrent = computed(() => {
  if (!route.query.page) {
    return PAGE_DEFAULT;
  }
  return Number(route.query.page);
});

const handleClickSortFn = () => {
  isShowSort.value = !isShowSort.value;
};
const goToAdd = (id) => {
  router.push(`${ROUTER_PATH.CATEGORY_MANAGE}/${ROUTER_PATH.EDIT}/` + id);
};
const getListCategory = async (page) => {
  try {
    store.state[MODULE_STORE.COMMON.NAME].isLoadingPage = true;
    const res = await getListCategoryFromApi(page);
    pagination.value = res.pagination;
    listCategory.value = {
      ...res.data,
    };
  } catch (errors) {
    const error = errors.message;
    toast.error(error);
  } finally {
    store.state[MODULE_STORE.COMMON.NAME].isLoadingPage = false;
  }
};

watch(pageCurrent, (page) => {
  if (route.path === ROUTER_PATH.CATEGORY_MANAGE) {
    getListCategory(page);
  }
});
const handleBackPage = (page) => {
  router.push(`${ROUTER_PATH.CATEGORY_MANAGE}?page=${page}`);
};
const handleNextPage = (page) => {
  router.push(`${ROUTER_PATH.CATEGORY_MANAGE}?page=${page}`);
};

const goToAddCategory = () => {
  router.push(`${ROUTER_PATH.CATEGORY_MANAGE}/${ROUTER_PATH.ADD}`)
}

getListCategory(pageCurrent.value);

store.state[MODULE_STORE.COMMON.NAME].breadcrumbCurrent = 'Danh mục'
store.state[MODULE_STORE.COMMON.NAME].breadcrumbItems = [
  {
    label: 'Trang chủ',
    link: '/dashboard'
  },
]
</script>

<style scoped></style>
