<template>
  <div class="p-5 mt-8 mx-5 bg-white">
    <div class="w-full py-6 py-auto text-xl">
      <span class="text-gray-500">Thêm sản phẩm</span>
    </div>
    <div class="mr-4 w-full mb-5">
      <div class="flex items-end mb-2">
        <label for="customer" class="block mb-1 font-bold text-sm">Khách hàng: </label>
        <span v-show="!customerMessageError" class="text-lg text-gray-400 ml-1">{{
            currentCustomer.customer_name
          }}</span>
      </div>
    </div>
    <div class="w-1/2 p-5">
      <TabsWrapper>
        <TabItem title="Container">
          <ContainerOrderItem :customer-id="currentCustomer.customer_id" @customer-id-error="handleCustomerIdError"/>
        </TabItem>
        <TabItem title="Vat" :customer-id="currentCustomer.customer_id" @customer-id-error="handleCustomerIdError">
          <VatItem :customer-id="currentCustomer.customer_id" @customer-id-error="handleCustomerIdError"/>
        </TabItem>
        <TabItem title="Khác">Content from Tab 3 Lorem ipsum dolor sit amet consectetur, adipisicing elit. Voluptates,
          ipsa.
        </TabItem>
      </TabsWrapper>
    </div>
  </div>
</template>

<script setup>
import {useRoute} from "vue-router";
import {ref} from "vue";
import {getListCustomerFromApi} from "@/api";
import InputItem from "@/views/OrderManage/CreateOrder/InputItem";
import ButtonAddNew from "@/components/Buttons/ButtonAddNew";
import TabsWrapper from "@/views/DebtManage/CreateDebt/TabsWrapper.vue";
import TabItem from "@/views/DebtManage/CreateDebt/TabItem.vue";
import ContainerOrderItem from "@/views/DebtManage/CreateDebt/ContainerOrderItem.vue";
import VatItem from "@/views/DebtManage/CreateDebt/VatItem.vue";
import MultiSelect from "@/components/MultiSelect/MultiSelect.vue";
import {MODULE_STORE} from "@/const";
import {useStore} from "vuex";

const route = useRoute()
const customers = ref([])
const selectedCustomers = ref([])
const currentCustomer = ref({})
const customerMessageError = ref(null)
const store = useStore();

const getListCustomer = async () => {
  const res = await getListCustomerFromApi();
  customers.value = [
    ...res.data
  ]
  currentCustomer.value = customers.value.find(e => {
    return e.customer_id === route.params.id
  })
}

const handleCustomerIdError = (value) => {
  customerMessageError.value = value
}

const handleSelectCustomer = (ids) => {
  customerMessageError.value = false
  currentCustomer.value = customers.value.find((e) => {
    return e.customer_id === ids[0]
  })
  selectedCustomers.value = ids
}

getListCustomer()
store.state[MODULE_STORE.COMMON.NAME].breadcrumbCurrent = 'Thêm công nợ'
store.state[MODULE_STORE.COMMON.NAME].breadcrumbItems = [
  {
    label: 'Trang chủ',
    link: '/dashboard'
  },
  {
    label: 'Công nợ',
    link: '/debt-manage'
  },
  {
    label: 'Công nợ khách',
    link: `/list-customer-debt/${route.params.id}`
  },
]
</script>

<style scoped>

</style>
