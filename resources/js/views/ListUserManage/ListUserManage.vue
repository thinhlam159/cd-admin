<template>
  <div class="p-3">
    <div class="w-full h-8 flex justify-end">
      <ButtonAddNew @clickBtn="handleAddUserManage" :text="$t('list_user_manage_page.new_addition')" />
    </div>

    <!-- *********** -->
    <div class="flex h-8 justify-between mt-1">
      <ButtonFilter @clickBtn="handleClickSortFn" />
      <ButtonDownloadCSV />
    </div>
    <!-- *********** -->

    <div class="my-4 border-b pl-4 pb-4" v-show="isShowSort">
      <FormKit type="group" v-model="filterKey">
        <div class="w-full flex items-center my-2">
          <div class="mr-20 w-32">
            <i class="fa fa-sort"></i>
            <span class="font-bold">
              {{ $t("list_user_manage_page.sort_by") }}
            </span>
          </div>
          <FormKit
            type="select"
            name="state"
            label=""
            :options="{
              ca: 'California',
              ny: 'New York',
              va: 'Virginia',
            }"
            wrapper-class=""
            label-class=""
            inner-class=""
            input-class="bg-white w-96 h-8 px-2 border border-solid border-[#dbdbdb] rounded outline-none focus:shadow-lg  focus:shadow-cyan-500/50 focus:border-cyan-500/50"
            class=""
          />
        </div>
        <div class="flex items-center my-2">
          <div class="mr-20 w-32">
            <span class="font-bold">
              {{ $t("list_user_manage_page.name") }}
            </span>
          </div>
          <FormKit
            label=""
            name="street"
            type="text"
            wrapper-class=""
            label-class=""
            inner-class=""
            input-class="bg-white w-96 h-8 px-2 border border-solid border-[#dbdbdb] rounded outline-none focus:shadow-lg  focus:shadow-cyan-500/50 focus:border-cyan-500/50"
            class=""
          />
        </div>
        <div class="flex items-center my-2">
          <div class="mr-20 w-32">
            <i class="fa fa-envelope-o"></i>
            <span class="font-bold">
              {{ $t("list_user_manage_page.email_address") }}
            </span>
          </div>
          <FormKit
            label=""
            name="street1"
            type="text"
            wrapper-class=""
            label-class=""
            inner-class=""
            input-class="bg-white w-96 h-8 px-2 border border-solid border-[#dbdbdb] rounded outline-none focus:shadow-lg  focus:shadow-cyan-500/50 focus:border-cyan-500/50"
            class=""
          />
        </div>
        <div class="flex items-center my-2">
          <div class="mr-20 w-32">
            <span class="font-bold">
              {{ $t("list_user_manage_page.company_organization") }}
            </span>
          </div>
          <FormKit
            label=""
            name="street2"
            type="text"
            wrapper-class=""
            label-class=""
            inner-class=""
            input-class="bg-white w-96 h-8 px-2 border border-solid border-[#dbdbdb] rounded outline-none focus:shadow-lg  focus:shadow-cyan-500/50 focus:border-cyan-500/50"
            class=""
          />
        </div>
        <div class="flex items-center my-4">
          <div class="mr-20 w-32">
            <i class="fa fa-level-up"></i>
            <span class="font-bold">
              {{ $t("list_user_manage_page.user_type") }}
            </span>
          </div>
          <FormKit
            type="radio"
            label=""
            :options="[
              {
                label: '全て',
                value: '全て',
              },
              {
                label: 'スペマネ管理者',
                value: 'スペマネ管理者',
              },
              {
                label: 'オーナー',
                value: 'オーナー',
              },
            ]"
            wrapper-class="flex"
            options-class="flex"
            outer-class="w-96"
            label-class=" font-bold ml-1 mr-4"
          />
        </div>
        <div class="flex items-center my-4">
          <div class="mr-20 w-32">
            <i class="fa fa-level-up"></i>
            <span class="font-bold">
              {{ $t("list_user_manage_page.enabled_disabled") }}
            </span>
          </div>
          <FormKit
            type="radio"
            label=""
            :options="[
              {
                label: '有効',
                value: '有効',
              },
              {
                label: '無効',
                value: '無効',
              },
              {
                label: 'すべて',
                value: 'すべて',
              },
            ]"
            wrapper-class="flex"
            options-class="flex"
            outer-class="w-96"
            label-class=" font-bold ml-1 mr-4"
          />
        </div>
        <div class="flex items-center my-4">
          <div class="mr-20 w-32">
            <i class="fa fa-level-up"></i>
            <span class="font-bold">
              {{ $t("list_user_manage_page.email_newsletter") }}
            </span>
          </div>
          <FormKit
            type="radio"
            label=""
            :options="[
              {
                label: '受信する',
                value: '受信する',
              },
              {
                label: '受信しない',
                value: '受信しない',
              },
              {
                label: 'すべて',
                value: 'すべて',
              },
            ]"
            wrapper-class="flex"
            options-class="flex"
            outer-class="w-96"
            label-class=" font-bold ml-1 mr-4"
          />
        </div>

        <div class="flex items-center my-4">
          <div class="mr-20 w-32">
            <span class="font-bold">
              {{ $t("list_user_manage_page.registration_date_period") }}
            </span>
          </div>

          <div class="flex h-8 border rounded mr-4">
            <div class="h-full px-2 bg-[#eee] border-r border-[#dbdbdb] flex items-center justify-center">
              <i class="fa fa-calendar"></i>
            </div>
            <div class="h-full px-2 bg-[#eee] border-r border-[#dbdbdb] flex items-center justify-center">
              {{ $t("list_user_manage_page.start_date") }}
            </div>
            <div class="flex items-center justify-center">
              <datepicker class="px-2 w-40 outline-none text-center" v-model="timeDatePicker" />
            </div>
          </div>

          <div class="flex h-8 border rounded">
            <div class="h-full px-2 bg-[#eee] border-r border-[#dbdbdb] flex items-center justify-center">
              <i class="fa fa-calendar"></i>
            </div>
            <div class="h-full px-2 bg-[#eee] border-r border-[#dbdbdb] flex items-center justify-center">
              {{ $t("list_user_manage_page.end_date") }}
            </div>
            <div class="flex items-center justify-center">
              <datepicker class="px-2 w-40 outline-none text-center" v-model="timeDatePicker" />
            </div>
          </div>
        </div>
      </FormKit>
      <button class="px-2 py-1 rounded hover:bg-[rgba(0,0,0,.06)] border mt-3">
        <i class="fa fa-search mr-2" aria-hidden="true"></i>
        <span class="">
          {{ $t("list_user_manage_page.search") }}
        </span>
      </button>
    </div>

    <!-- *********** -->

    <div class="mt-4">
      <table class="w-full">
        <thead>
          <tr class="">
            <th class="border py-1 w-[5%]"></th>
            <th class="border py-1 w-[20%]">
              {{ $t("list_user_manage_page.email_address") }}
            </th>
            <th class="border py-1 w-[20%]">
              {{ $t("list_user_manage_page.name") }}
            </th>
            <th class="border py-1 w-[10%]">
              {{ $t("list_user_manage_page.company_organization_name") }}
            </th>
            <th class="border py-1 w-[10%]">
              {{ $t("list_user_manage_page.user_type") }}
            </th>
            <th class="border py-1 w-[10%]">
              {{ $t("list_user_manage_page.enabled_disabled") }}
            </th>
            <th class="border py-1 w-[10%]">
              {{ $t("list_user_manage_page.registered_date") }}
            </th>
            <th class="border py-1 w-[10%]">
              {{ $t("list_user_manage_page.last_login_date") }}
            </th>
            <th class="border py-1 w-[5%]"></th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="item in listUserManage" :key="item.id">
            <td class="border text-center">{{ item.user_id }}</td>
            <td class="border text-center">
              <span class="text-[#337ab7] cursor-pointer break-all" @click="() => goToAdd(item.user_id)">
                {{ item.user_email }}
              </span>
            </td>
            <td class="border text-center">{{ item.user_name }}</td>
            <td class="border text-center">{{ item.company_name }}</td>
            <td class="border text-center">
              {{ $t(`list_user_manage_page.${item.user_type}`) }}
            </td>
            <td class="border text-center">
              <span class="text-[green]" v-if="item.user_active">{{ $t("common.effectiveness") }}</span>
              <span class="text-[red]" v-else>{{ $t("common.invalid") }}</span>
            </td>
            <td class="border text-center p-2">
              {{ item.register_date }}
            </td>
            <td class="border text-center p-2">
              {{ item.login_last_date }}
            </td>
            <td class="border text-center">{{ item.field9 }}</td>
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
    Pagination,
  },

  setup(props, context) {
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
    // const getListUserManager = async (page) => {
    //   try {
    //     store.state[MODULE_STORE.COMMON.NAME].isLoadingPage = true;
    //     const response = await getListUserManagerFromApi(page);
    //     pagination.value = response.pagination;
    //     listUserManage.value = {
    //       ...response.data,
    //     };
    //     listUserManage.value = response.data.map((item) => {
    //       return {
    //         ...item,
    //         register_date: convertDateByTimestamp(item.register_date),
    //         login_last_date: convertDateByTimestamp(item.login_last_date),
    //       };
    //     });
    //   } catch (errors) {
    //     const error = errors.message || t("common.has_error");
    //     toast.error(error);
    //   } finally {
    //     store.state[MODULE_STORE.COMMON.NAME].isLoadingPage = false;
    //   }
    // };

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
    // getListUserManager(pageCurrent.value);
    return {
      filterKey,
      isShowSort,
      timeDatePicker,
      listUserManage,
      handleClickSortFn,
      handleAddUserManage,
      goToAdd,
      // getListUserManager,
      pagination,
      handleBackPage,
      handleNextPage,
    };
  },
};
</script>

<style scoped></style>
