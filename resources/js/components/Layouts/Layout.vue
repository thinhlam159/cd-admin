<template>
<!--  <Header :isShowHeader="isShowHeader" />-->
  <div class="flex w-full">
    <div class="w-[14%] bg-white">
      <SideBar />
    </div>
    <div class="flex">
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
