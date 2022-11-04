<template>
<!--  <FormUserManage />-->
    <div class="w-full h-full relative">
        <div class="w-[650px] pt-14 h-full absolute left-20">
            <div class="w-full py-6 py-auto text-xl">
                <span class="text-gray-500">Thêm sản phẩm</span>
            </div>
            <form @submit="handleSubmit(formData)">
                <div>
                    <label for="name" class="block mb-1 font-bold text-sm">Tên sản phẩm</label>
                    <input type="text" name="name" placeholder="Nhập tên sản phẩm" class="w-full h-10 px-3 border-none text-base text-gray-700 placeholder-gray-400">
                </div>
            </form>
            <FormKit type="form" @submit="handleSubmit(formData)" submit-label="Register" :form-class="hide">
                <FormKit
                    type="text"
                    label="Tên sản phẩm"
                    name="name"
                    placeholder="Nhập tên sản phẩm"
                    validation="required"
                    :classes="{
                      outer: 'mb-5',
                      label: 'block mb-1 font-bold text-sm',
                      inner: 'max-w-md border border-gray-400 rounded-lg mb-1 overflow-hidden focus-within:border-blue-500',
                      input: 'w-full h-10 px-3 border-none text-base text-gray-700 placeholder-gray-400',
                      help: 'text-xs text-gray-500'
                    }"
                    v-model="formData.name"
                />
                <FormKit
                    type="select"
                    label="Danh mục"
                    name="category"
                    placeholder="Nhập giá sản phẩm"
                    validation="number"
                    :classes="{
                      outer: 'mb-5',
                      label: 'block mb-1 font-bold text-sm',
                      inner: 'max-w-md border border-gray-400 rounded-lg mb-1 overflow-hidden focus-within:border-blue-500',
                      input: 'w-full h-10 px-3 border-none text-base text-gray-700 placeholder-gray-400',
                      help: 'text-xs text-gray-500'
                    }"
                    :options="categories"
                    v-model="formData.category"
                />
                <FormKit
                    type="number"
                    label="Giá thành"
                    name="price"
                    placeholder="Nhập giá sản phẩm"
                    validation="number"
                    :classes="{
                      outer: 'mb-5',
                      label: 'block mb-1 font-bold text-sm',
                      inner: 'max-w-md border border-gray-400 rounded-lg mb-1 overflow-hidden focus-within:border-blue-500',
                      input: 'w-full h-10 px-3 border-none text-base text-gray-700 placeholder-gray-400',
                      help: 'text-xs text-gray-500'
                    }"
                    v-model="formData.price"
                />
<!--                <FormKit-->
<!--                    type="file"-->
<!--                    label="Hình ảnh"-->
<!--                    name="image"-->
<!--                    placeholder="hình ảnh"-->
<!--                    :classes="{-->
<!--                      outer: 'mb-5',-->
<!--                      label: 'block mb-1 font-bold text-sm',-->
<!--                      inner: 'max-w-md border border-gray-400 rounded-lg mb-1 overflow-hidden focus-within:border-blue-500',-->
<!--                      input: 'w-full h-10 px-3 border-none text-base text-gray-700 placeholder-gray-400',-->
<!--                      help: 'text-xs text-gray-500'-->
<!--                    }"-->
<!--                    v-model="formData.image"-->
<!--                />-->
                <div class="h-48 my-4">
                    <img class="h-full w-auto .object-contain" id="blah" :src="imageUrl" alt="your image" />
                </div>
                <div>
                    <input
                        type="file"
                        @change="onFileChanged"
                        accept="image/*"
                        ref="file"
                    />
                </div>

<!--                <img id="blah" src="http://localhost:8032/storage/images/Ux9sxW6bQp.png" alt="your image" />-->
                <FormKit
                    type="textarea"
                    label="Mô tả"
                    name="description"
                    placeholder="Mô tả sản phẩm"
                    :classes="{
                      outer: 'mb-5',
                      label: 'block mb-1 font-bold text-sm',
                      inner: 'max-w-md border border-gray-400 rounded-lg mb-1 overflow-hidden focus-within:border-blue-500',
                      input: 'w-full h-10 px-3 border-none text-base text-gray-700 placeholder-gray-400',
                      help: 'text-xs text-gray-500'
                    }"
                    v-model="formData.description"
                />
                <FormKit
                    type="submit"
                    label="Thêm mới"
                    :classes="{
                      outer: 'mb-0 mt-8',
                      label: 'block mb-1 font-bold text-sm text-white',
                      input: 'bg-gray-400 h-10 px-3 border-gray-50 text-base text-gray-50 rounded-md',
                    }"
                />
            </FormKit>
        </div>
    </div>
</template>

<script>
// import FormUserManage from "@/components/FormUserManage";
import {ref} from "vue";
import {useRouter} from "vue-router";
import {useStore} from "vuex";
import {MODULE_STORE, ROUTER_PATH} from "@/const";
import {createCustomerFromApi, createProductFromApi, getListCategoryFromApi} from "@/api";
import logoTimeSharing from "@/assets/images/default-thumbnail.jpg";

export default {
  name: "AddProduct",
  components: {  },
  setup() {
      const router = useRouter()
      const store = useStore()
      const formData = ref({
          name: '',
          email: '',
          password: '123132',
          phone: '',
          status: true,
          image: null,
      })
      const imageUrl = ref(logoTimeSharing)
      const imageBuffer = ref(null)
      const file = ref(null)
      const categories = ref([])


      const handleSubmit = async (data) => {
          try {
              const bodyFormData = new FormData()
              bodyFormData.append('name', data.name);
              bodyFormData.append('price', data.price);
              bodyFormData.append('category_id', data.category);
              bodyFormData.append('description', data.description);
              // bodyFormData.append('file', imageBuffer.value);
              bodyFormData.append('file', file.value.files[0]);
              const res = await createProductFromApi(bodyFormData)
              router.push(`${ROUTER_PATH.ADMIN}/${ROUTER_PATH.CUSTOMER_MANAGE}`)
          } catch (errors) {
              const error = errors.message;
              // this.$toast.error(error);
          } finally {
              store.state[MODULE_STORE.COMMON.NAME].isLoadingPage = false;
          }
      }

      const getListCategory = async () => {
          try {
              const res = await getListCategoryFromApi()
              categories.value = res.data.reduce( (option, data) => {
                  return [
                      ...option,
                      {
                          label: data.name,
                          value: data.category_id
                      }
                  ]
              }, [])

          } catch (errors) {
              const error = errors.message;
              console.log(error)
          }
      }

      const onFileChanged = () => {
          let image = file.value.files[0];
          imageUrl.value = URL.createObjectURL(image)
          createImage(image)
      }

      const createImage = (file) => {
          const reader = new FileReader();
          reader.onload = (e) => {
              imageBuffer.value = e.target.result
          }
          reader.readAsDataURL(file)
      }

      getListCategory()

      return {
          formData,
          handleSubmit,
          onFileChanged,
          imageUrl,
          file,
          categories
      }
  }
};
</script>

<style scoped></style>
