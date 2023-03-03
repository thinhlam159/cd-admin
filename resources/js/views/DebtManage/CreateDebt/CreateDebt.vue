<template>
  <div class="p-5 mt-8 mx-5 bg-white">
    <div class="mr-4 mb-5">
      <div class="flex items-end mb-2">
        <label for="customer" class="text-base text-gray-500">Khách hàng: </label>
        <span v-show="!customerMessageError" class="text-base font-bold ml-1">{{
            currentCustomer?.customer_name
          }}</span>
      </div>
    </div>
    <div class="w-[600px]">
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
import {reactive, ref} from "vue";
import {getCustomerDetailFromApi} from "@/api";
import TabsWrapper from "@/views/DebtManage/CreateDebt/TabsWrapper.vue";
import TabItem from "@/views/DebtManage/CreateDebt/TabItem.vue";
import ContainerOrderItem from "@/views/DebtManage/CreateDebt/ContainerOrderItem.vue";
import VatItem from "@/views/DebtManage/CreateDebt/VatItem.vue";
import {MODULE_STORE} from "@/const";
import {useStore} from "vuex";

const route = useRoute()
const selectedCustomers = ref([])
const currentCustomer = reactive({
  customer_id: '',
  customer_name: '',
})
const customerMessageError = ref(null)
const store = useStore();

const getCustomerDetail = async (customerId) => {
  try {
    store.state[MODULE_STORE.COMMON.NAME].isLoadingPage = true;
    const res = await getCustomerDetailFromApi(customerId);
    const data = res.data
    currentCustomer.customer_name = data.customer_name
    currentCustomer.customer_id = data.customer_id
  } catch (errors) {
    toast.error(errors.message);
  } finally {
    store.state[MODULE_STORE.COMMON.NAME].isLoadingPage = false;
  }
}
const handleCustomerIdError = (value) => {
  customerMessageError.value = value
}

getCustomerDetail(route.params.id)
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
    link: `/debt-manage/list-customer-debt/${route.params.id}`
  },
]
</script>

<style scoped>

</style>
