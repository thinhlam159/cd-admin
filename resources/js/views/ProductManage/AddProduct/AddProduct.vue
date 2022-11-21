<template>
<!--  <FormUserManage />-->
    <div class="w-full h-full relative">
        <div class="w-[650px] pt-14 h-full absolute left-20">
            <div class="w-full py-6 py-auto text-xl">
                <span class="text-gray-500">Thêm sản phẩm</span>
            </div>
            <form @submit.prevent="handleSubmit(formData)">
                <div>
                    <label for="name" class="block mb-1 font-bold text-sm">Nhập tên sản phẩm</label>
                    <input type="text" name="name" placeholder="Nhập tên sản phẩm" v-model="formData.name"
                        class="w-full h-10 px-3 text-base text-gray-700 placeholder-gray-400 border border-gray-400"
                    >
                </div>
                <div>
                    <label for="code" class="block mb-1 font-bold text-sm">Mã sản phẩm</label>
                    <input type="text" name="name" placeholder="Nhập mã sản phẩm" v-model="formData.code"
                           class="w-full h-10 px-3 text-base text-gray-700 placeholder-gray-400 border border-gray-400"
                    >
                </div>
                <div>
                    <label for="category" class="block mb-1 font-bold text-sm">Danh Mục</label>
                    <select name="category" class="p-3" v-model="formData.category">
                        <option disabled value="" class="w-full h-10 px-3 text-base text-gray-700" selected>Chọn danh mục</option>
                        <option v-for="item in categories" :value="item.id" class="w-full h-10 px-3 text-base text-gray-700">{{ item.name }}</option>
                    </select>
                </div>
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
                <div>
                    <label for="description" class="block mb-1 font-bold text-sm">Mô tả</label>
                    <textarea name="price" placeholder="Nhập giá sp" v-model="formData.description"
                              class="w-full h-10 px-3 text-base text-gray-700 placeholder-gray-400 border border-gray-400"
                    ></textarea>
                </div>
                <div>
                    <input class="w-25 h-10 mt-5 px-3 text-base text-gray-700 placeholder-gray-400 bg-green-400 cursor-pointer" type="submit" value="Thêm sp">
                </div>
            </form>
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
      const formData = ref({})
      const imageUrl = ref(logoTimeSharing)
      const imageBuffer = ref(null)
      const file = ref(null)
      const categories = ref()


      const handleSubmit = async (data) => {
          try {
              const bodyFormData = new FormData()
              bodyFormData.append('name', data.name);
              bodyFormData.append('price', data.price);
              bodyFormData.append('code', data.code);
              bodyFormData.append('category_id', data.category);
              bodyFormData.append('description', data.description);
              bodyFormData.append('file', file.value.files[0]);
              const res = await createProductFromApi(bodyFormData)
              router.push(`${ROUTER_PATH.ADMIN}/${ROUTER_PATH.PRODUCT_MANAGE}`)
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
                          name: data.name,
                          id: data.category_id
                      }
                  ]
              }, [])
              formData.value.category = res.data[0].category_id
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
