<template>
  <div class="w-full h-full relative">
    <div class="w-full pt-14 h-full absolute px-10">
      <div class="w-full py-6 py-auto text-xl">
        <span class="text-gray-500">Thêm sản phẩm</span>
        <hr>
      </div>
      <div class="mr-4 w-full mb-5">
        <label for="customer" class="block mb-1 font-bold text-sm">Khách hàng</label>
        <div class="flex items-center">
          <MultiSelect
            only-select-one
            key-id="id"
            key-name="position_name"
            id="hrPositionAnalysis"
            :options="customers"
            :value="selectedCustomer"
            :placeholder="'Nhập tên'"
            :hasSearch="true"
            @change="handleSelectCustomer"
          />
          <div class="flex h-full items-center ml-2">
            <p v-if="!!customerMessageError" class="text-red-500">{{ customerMessageError }}</p>
          </div>
        </div>
        <div v-show="!customerMessageError">{{ selectedCustomer.customer_name }}</div>
      </div>
      <div class="w-1/2 h-full p-5">
        <TabsWrapper>
          <TabItem title="Container">
            <ContainerOrderItem :customer-id="formData.customerId" @customer-id-error="handleCustomerIdError"/>
          </TabItem>
          <TabItem title="Vat" :customer-id="formData.customerId" @customer-id-error="handleCustomerIdError">
            <VatItem :customer-id="formData.customerId" @customer-id-error="handleCustomerIdError"/>
          </TabItem>
          <TabItem title="Khác">Content from Tab 3 Lorem ipsum dolor sit amet consectetur, adipisicing elit. Voluptates, ipsa.</TabItem>
        </TabsWrapper>
      </div>
    </div>
  </div>
</template>

<script>
import {useRoute, useRouter} from "vue-router";
import {useStore} from "vuex";
import {ref, inject} from "vue";
import {
  getListCustomerFromApi,
} from "@/api";
import {MODULE_STORE, ROUTER_PATH} from "@/const";
import InputItem from "@/views/OrderManage/CreateOrder/InputItem";
import ButtonAddNew from "@/components/Buttons/ButtonAddNew";
import TabsWrapper from "@/views/DebtManage/CreateDebt/TabsWrapper.vue";
import TabItem from "@/views/DebtManage/CreateDebt/TabItem.vue";
import ContainerOrderItem from "@/views/DebtManage/CreateDebt/ContainerOrderItem.vue";
import VatItem from "@/views/DebtManage/CreateDebt/VatItem.vue";
import MultiSelect from "@/components/MultiSelect/MultiSelect.vue";

export default {
  name: "CreateDebt",
  components: {MultiSelect, InputItem, ButtonAddNew, TabsWrapper, TabItem, ContainerOrderItem, VatItem },
  methods: {
    forceUpdate() {
      this.renderComponent = false
      this.$nextTick(() => {
        this.renderComponent = true
      })
    }
  },
  data() {
    return {
      renderComponent: true
    }
  },
  setup() {
    const router = useRouter()
    const route = useRoute()
    const store = useStore()
    const formData = ref({})
    const customers = ref([])
    const selectedCustomer = ref({})
    const customerMessageError = ref(null)
    const customerName = ref('')
    const customerId = ref('')

    const getListCustomer = async () => {
      const res = await getListCustomerFromApi();
      customers.value = [
        ...res.data
      ]
    }

    const handleCustomerIdError = (value) => {
      customerMessageError.value = value
    }

    const handleSelectCustomer = (ids) => {
      customerMessageError.value = false
      selectedCustomer.value = customers.value.filter((e) => {
        return e.customer_id === ids[0]
      })
    }

    getListCustomer()

    return {
      formData,
      customers,
      customerMessageError,
      selectedCustomer,
      customerName,
      customerId,
      handleCustomerIdError,
      handleSelectCustomer,
    }
  }
}
</script>

<style scoped>

</style>
