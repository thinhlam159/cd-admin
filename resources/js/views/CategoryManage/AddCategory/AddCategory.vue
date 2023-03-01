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
            <input type="text" name="name" placeholder="Nhập tên" v-model="name" @input="onInputName"
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
                   type="submit" value="Tạo danh mục">
          </div>
        </form>
    </div>
  </div>
</template>

<script setup>
import {inject, reactive, ref} from "vue";
import {useRouter} from "vue-router";
import {useStore} from "vuex";
import {MODULE_STORE, ROUTER_PATH} from "@/const";
import {createCategoryFromApi, getListCategoryFromApi} from "@/api";
import SelectBoxWithSearch from "@/components/MultiSelect/SelectBoxWithSearch.vue";
import * as Yup from "yup";

const router = useRouter()
const store = useStore()
const toast = inject('$toast')
const name = ref('')
const parentId = ref(null)
const errors = reactive({})
const listCategory = ref([]);

const nameSchema = Yup.object().shape({
  name: Yup.string().required().min(3, 'Tối thiểu 3 ký tự')
})

const handleSubmit = async () => {
  try {
    await nameSchema.validate({name: name.value}, { abortEarly: false })
    const res = await createCategoryFromApi({
      name: name.value,
      parent_id: parentId.value,
    })
    await router.push(`${ROUTER_PATH.ADMIN}/${ROUTER_PATH.CATEGORY_MANAGE}`)
    toast.success("Tạo danh mục nhập thành công!", {duration:3000})
  } catch (validateErrors) {
    if (validateErrors.hasOwnProperty('inner')) {
      validateErrors.inner.forEach((e) => {
        errors[e.path] = e.message
      })
      return
    }
    toast.error(validateErrors.message)
  } finally {
    store.state[MODULE_STORE.COMMON.NAME].isLoadingPage = false
  }
}
const getListCategory = async (page) => {
  try {
    store.state[MODULE_STORE.COMMON.NAME].isLoadingPage = true
    const res = await getListCategoryFromApi(page)
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

const handleSelectCategory = (itemSelected) => {
  parentId.value = itemSelected.id
}

const onInputName = async () => {
  delete errors['name'];
}
getListCategory(1)

store.state[MODULE_STORE.COMMON.NAME].breadcrumbCurrent = 'Thêm danh mục'
store.state[MODULE_STORE.COMMON.NAME].breadcrumbItems = [
  {
    label: 'Trang chủ',
    link: '/dashboard'
  },
  {
    label: 'Danh mục hàng',
    link: '/category-manage'
  },
]

</script>

<style scoped></style>
