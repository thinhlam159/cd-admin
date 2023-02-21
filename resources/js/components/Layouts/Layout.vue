<template>
  <div class="flex w-full min-h-screen">
    <div class="w-[250px] bg-[#293846]">
      <SideBar />
    </div>
    <div class="w-[86%]">
      <Header :isShowHeader="isShowHeader" />
      <slot></slot>
    </div>
  </div>
</template>
<script>
import Header from "@/components/Header";
import SideBar from "@/components/SideBar";

export default {
  name: "Home",
  components: { Header, SideBar },
  setup() {
    const isShowHeader = true;
    return {
      isShowHeader,
    };
  },
};
</script>
