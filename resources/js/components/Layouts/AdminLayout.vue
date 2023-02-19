<template>
    <div class="text-xs min-h-screen bg-[#f3f3f3]">
        <SpinLoading v-if="isLoading" />
        <div v-if="isShowLayout" class="">
            <Layout>
                <router-view class="w-full"/>
            </Layout>
        </div>
        <div class="" v-else>
            <router-view />
        </div>
    </div>
</template>

<script>
import Layout from "@/components/Layouts/Layout.vue";
import SpinLoading from "@/components/SpinLoading";
import { MODULE_STORE } from "@/const";
import { computed } from "vue";
import { useStore } from "vuex";

export default {
    name: "AdminLayout",
    components: {
        Layout,
        SpinLoading,
    },
    setup() {
        const store = useStore();
        const isLoading = computed(
            () => store.getters[`${MODULE_STORE.COMMON.NAME}/${MODULE_STORE.COMMON.GETTERS.GET_IS_LOADING}`]
        );
        const isShowLayout = computed(() => store.state[MODULE_STORE.AUTH.NAME].isAuthenticated);
        // const isShowLayout = true;
        return { isLoading, isShowLayout };
        // return { isShowLayout };
    },
}
</script>

<style scoped>

</style>
