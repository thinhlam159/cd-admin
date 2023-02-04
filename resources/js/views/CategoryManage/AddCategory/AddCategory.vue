<template>
  <!--  <FormUserManage />-->
  <div class="w-full h-full relative">
    <div class="w-[650px] pt-14 h-full absolute left-20">
      <div class="w-full py-6 py-auto text-xl">
        <span class="text-gray-500">Thêm danh mục</span>
      </div>
      <div class="py-3">
        <form @submit.prevent="handleSubmit()">
          <div class="py-1">
            <p>Tên danh mục: </p>
            <input type="text" class="outline-none p-1 border border-gray-400" v-model="categoryName">
          </div>
          <button type="submit" class="p-2 border-gray-400 border bg-[#00FF00]">Tạo danh mục</button>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
// import FormUserManage from "@/components/FormUserManage";
import {inject, ref} from "vue";
import {useRouter} from "vue-router";
import {useStore} from "vuex";
import {MODULE_STORE, ROUTER_PATH} from "@/const";
import {createCategoryFromApi} from "@/api";

const router = useRouter()
const store = useStore()
const categoryName = ref('')
const toast = inject('$toast')

const handleSubmit = async () => {
  try {
    const res = await createCategoryFromApi({
      name: categoryName.value,
    })
    await router.push(`${ROUTER_PATH.ADMIN}/${ROUTER_PATH.CATEGORY_MANAGE}`)
    toast.success("Tạo danh mục nhập thành công!", {duration:3000})
  } catch (errors) {
    toast.error(errors.message)
  } finally {
    store.state[MODULE_STORE.COMMON.NAME].isLoadingPage = false;
  }
}

</script>

<style scoped></style>
