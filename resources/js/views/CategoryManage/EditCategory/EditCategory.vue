<template>
  <!--  <FormUserManage :edit="true" />-->
  <div class="w-full h-full relative">
    <div class="w-[650px] pt-14 h-full absolute left-20">
      <div class="w-full py-6 py-auto text-xl">
        <span class="text-gray-500">Cập nhật danh mục</span>
      </div>
      <form @submit.prevent="handleSubmit()">
        <div class="py-1">
          <p>Tên danh mục: </p>
          <input type="text" class="outline-none p-1 border border-gray-400" v-model="categoryName">
        </div>
        <button type="submit" class="p-2 border-gray-400 border bg-[#00FF00]">Lưu</button>
      </form>
    </div>
  </div>
</template>

<script setup>
import {inject, ref} from "vue";
import { useRoute, useRouter } from 'vue-router'
import {MODULE_STORE, ROUTER_PATH} from "@/const";
import {updateCategoryFromApi, getCategoryDetailFromApi,} from "@/api";
import {useStore} from "vuex";

const router = useRouter()
const route = useRoute()
const store = useStore()
const categoryName = ref('')
const toast = inject('$toast')
const categoryId = ref(route.params.id)

const handleSubmit = async () => {
  try {
    const res = await updateCategoryFromApi(categoryId.value,{
      name: categoryName.value,
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

getCategoryDetail()

</script>

<style scoped></style>
