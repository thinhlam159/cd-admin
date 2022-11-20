<template>
  <div class="p-5">
    <div class="w-full h-8 flex justify-end">
      <ButtonAddNew @clickBtn="handleAddUserManage" :text="addNewUser" />
    </div>

<!--    &lt;!&ndash; *********** &ndash;&gt;-->
<!--    <div class="flex h-8 justify-between mt-1">-->
<!--      <ButtonFilter @clickBtn="handleClickSortFn" />-->
<!--      <ButtonDownloadCSV />-->
<!--    </div>-->

    <!-- *********** -->
    <div class="mt-4">
      <table class="w-full">
        <thead>
          <tr class="">
            <th class="border py-1 w-[5%]">
                id
            </th>
            <th class="border py-1 w-[20%]">
              Tên
            </th>
            <th class="border py-1 w-[20%]">
              Email
            </th>
            <th class="border py-1 w-[10%]">
              Số điên thoại
            </th>
            <th class="border py-1 w-[10%]">
              Trạng thái
            </th>
<!--            <th class="border py-1 w-[10%]">-->
<!--              {{ $t("list_user_manage_page.enabled_disabled") }}-->
<!--            </th>-->
<!--            <th class="border py-1 w-[10%]">-->
<!--              {{ $t("list_user_manage_page.registered_date") }}-->
<!--            </th>-->
            <th class="border py-1 w-[10%]">
              Cập nhật
            </th>
<!--            <th class="border py-1 w-[5%]"></th>-->
          </tr>
        </thead>
        <tbody>
          <tr v-for="item in listUserManage" :key="item.id">
              <td class="border text-center">{{ item.user_id }}</td>
              <td class="border text-center">{{ item.user_name }}</td>
              <td class="border text-center">
              <span class="text-[#337ab7] cursor-pointer break-all" @click="() => goToAdd(item.user_id)">
                {{ item.user_email }}
              </span>
              </td>
              <td class="border text-center">{{ item.phone }}</td>
              <td class="border text-center">{{ item.status ? 'Hoạt động' : '-' }}</td>
<!--            <td class="border text-center">{{ item.company_name }}</td>-->
<!--            <td class="border text-center">-->
<!--              {{ $t(`list_user_manage_page.${item.user_type}`) }}-->
<!--            </td>-->
<!--            <td class="border text-center">-->
<!--              <span class="text-[green]" v-if="item.user_active">{{ $t("common.effectiveness") }}</span>-->
<!--              <span class="text-[red]" v-else>{{ $t("common.invalid") }}</span>-->
<!--            </td>-->
<!--            <td class="border text-center p-2">-->
<!--              {{ item.register_date }}-->
<!--            </td>-->
<!--            <td class="border text-center p-2">-->
<!--              {{ item.login_last_date }}-->
<!--            </td>-->
<!--            <td class="border text-center">{{ item.field9 }}</td>-->
            <td class="border text-center">
                <div class="flex justify-center ">
                    <ButtonEdit @clickBtn="() => goToAdd(item.user_id)" :text="editUser"/>
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

<script>
import Datepicker from "vue3-datepicker";
import { ROUTER_PATH, MODULE_STORE, PAGE_DEFAULT } from "@/const";
import ButtonAddNew from "@/components/Buttons/ButtonAddNew";
import ButtonFilter from "@/components/Buttons/ButtonFilter";
import ButtonDownloadCSV from "@/components/Buttons/ButtonDownloadCSV";
import ButtonEdit from "@/components/Buttons/ButtonEdit";
import { getListUserManagerFromApi } from "@/api";
import { convertDateByTimestamp } from "@/utils";
import { ref, computed, watch, inject } from "vue";
import { useRouter, useRoute } from "vue-router";
import { useStore } from "vuex";
import Pagination from "@/components/Pagination";
import { useI18n } from "vue-i18n";

export default {
  name: "UserManage",
  components: {
    Datepicker,
    ButtonAddNew,
    ButtonFilter,
    ButtonDownloadCSV,
    ButtonEdit,
    Pagination,
  },

  setup() {
    const filterKey = ref({});
    const isShowSort = ref(false);
    const timeDatePicker = ref(new Date());
    const listUserManage = ref([]);
    const route = useRoute();
    const router = useRouter();
    const store = useStore();
    const { t } = useI18n();
    const toast = inject("$toast");
    const pagination = ref(null);
    const addNewUser = "Thêm người dùng";
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
    const handleAddUserManage = () => {
      router.push(`${ROUTER_PATH.USER_MANAGER}/${ROUTER_PATH.ADD}`);
    };
    const goToAdd = (id) => {
      router.push(`${ROUTER_PATH.USER_MANAGER}/${ROUTER_PATH.EDIT}/` + id);
    };
    const getListUserManager = async (page) => {
      try {

        store.state[MODULE_STORE.COMMON.NAME].isLoadingPage = true;
        const response = await getListUserManagerFromApi(page);
        pagination.value = response.pagination;
        listUserManage.value = {
          ...response.data,
        };

        // listUserManage.value = response.data.map((item) => {
        //   return {
        //     ...item,
        //     register_date: convertDateByTimestamp(item.register_date),
        //     login_last_date: convertDateByTimestamp(item.login_last_date),
        //   };
        // });
      } catch (errors) {
        const error = errors.message || t("common.has_error");
        toast.error(error);
      } finally {
        store.state[MODULE_STORE.COMMON.NAME].isLoadingPage = false;
      }
    };

    // watch(pageCurrent, (page) => {
    //   if (route.path == ROUTER_PATH.USER_MANAGER) {
    //     getListUserManager(page);
    //   }
    // });
    const handleBackPage = (page) => {
      router.push(`${ROUTER_PATH.USER_MANAGER}?page=${page}`);
    };
    const handleNextPage = (page) => {
      router.push(`${ROUTER_PATH.USER_MANAGER}?page=${page}`);
    };
    getListUserManager(pageCurrent.value);

    return {
      filterKey,
      isShowSort,
      timeDatePicker,
      listUserManage,
      handleClickSortFn,
      handleAddUserManage,
      goToAdd,
      getListUserManager,
      pagination,
      handleBackPage,
      handleNextPage,
      addNewUser,
      editUser
    };
  },
};
</script>

<style scoped></style>
