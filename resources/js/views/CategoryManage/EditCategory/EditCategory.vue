<template>
  <div class="p-5 mt-8 mx-5 bg-white">
    <div class="w-[650px] mt-5 ml-5 bg-white border border-t-[2px] border-[#e7eaec]">
      <div class="py-4 px-3 border-b border-[#e7eaec] text-md text-gray-700">
        <span class="text-gray-500">Thêm danh mục</span>
      </div>
      <form @submit.prevent="handleSubmit">
        <div class="py-1 mx-3">
          <label for="name" class="block py-2 font-bold text-lg">
            <span>Tên danh mục</span>
            <span v-if="errors.name" class="ml-1 text-red-500">*</span>
          </label>
          <input type="text" name="name" placeholder="Nhập tên" v-model="categoryName" @input="onInputName"
                 class="w-full h-10 px-3 text-base text-gray-700 placeholder-gray-200 border border-gray-300 focus:border-[#8ddd8d] outline-none"
                 :class="!!errors['name'] ? 'border-red-500 border' : ''"
          >
        </div>
        <div class="py-1 mx-3">
          <label for="name" class="block py-2 font-bold text-lg">
            <span>Danh mục cha</span>
          </label>
          <SelectBoxWithSearch :options="listCategory" @option-selected="handleSelectCategory"/>
        </div>
        <div class="flex justify-end border-t border-[#e7eaec] mt-5 p-3">
          <input class="p-2 text-base font-bold text-white bg-[#1ab394] hover:bg-[#18a689] cursor-pointer rounded-md"
                 type="submit" value="Thêm khách hàng">
        </div>
      </form>
    </div>
  </div>
</template>
<script setup>
import {inject, reactive, ref} from "vue";
import { useRoute, useRouter } from 'vue-router'
import {MODULE_STORE, ROUTER_PATH} from "@/const";
import {updateCategoryFromApi, getCategoryDetailFromApi, getListCategoryFromApi,} from "@/api";
import {useStore} from "vuex";
import SelectBoxWithSearch from "@/components/MultiSelect/SelectBoxWithSearch.vue";

const router = useRouter()
const route = useRoute()
const store = useStore()
const categoryName = ref('')
const toast = inject('$toast')
const categoryId = ref(route.params.id)
const name = ref('')
const parentId = ref(null)
const errors = reactive({})
const listCategory = ref([]);

const handleSubmit = async () => {
  try {
    const res = await updateCategoryFromApi(categoryId.value,{
      name: categoryName.value,
      parent_id: parentId.value
    })
    await router.push(`${ROUTER_PATH.ADMIN}/${ROUTER_PATH.CATEGORY_MANAGE}`)
    toast.success("Cập nhật danh mục nhập thành công!", {duration:3000})
  } catch (errors) {
    toast.error(errors.message)
  } finally {
    store.state[MODULE_STORE.COMMON.NAME].isLoadingPage = false;
  }
}

const getCategoryDetail = async () => {
  try {
    const res = await getCategoryDetailFromApi(categoryId.value)
    const data = res.data
    categoryName.value = data.name
  } catch (errors) {
    toast.error(errors.message)
  } finally {
    store.state[MODULE_STORE.COMMON.NAME].isLoadingPage = false;
  }
}
const getListCategory = async (page) => {
  try {
    store.state[MODULE_STORE.COMMON.NAME].isLoadingPage = true
    const res = await getListCategoryFromApi(page)
    const detailRes = await getCategoryDetailFromApi(categoryId.value)
    listCategory.value = [...res.data.map((e) => {
      return {id: e.category_id, text: e.name}
    })]
  } catch (errors) {
    const error = errors.message
    toast.error(error)
  } finally {
    store.state[MODULE_STORE.COMMON.NAME].isLoadingPage = false
  }
}
const onInputName = async () => {
  delete errors['name'];
}
const handleSelectCategory = (itemSelected) => {
  parentId.value = itemSelected.id
}
getListCategory(1)
getCategoryDetail()

store.state[MODULE_STORE.COMMON.NAME].breadcrumbCurrent = 'Cập nhật'
store.state[MODULE_STORE.COMMON.NAME].breadcrumbItems = [
  {
    label: 'Trang chủ',
    link: '/dashboard'
  },
  {
    label: 'Danh mục',
    link: '/category-manage'
  },
]

</script>

<style scoped></style>
