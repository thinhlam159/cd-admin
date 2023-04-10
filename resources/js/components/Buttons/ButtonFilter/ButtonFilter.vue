<template>
  <div class="flex items-center">
    <div
      class="border border-solid border-[#dbdbdb] h-full px-2 ml-2 rounded hover:bg-[#f1efef] cursor-pointer"
      @click="handleClick"
    >
      <i v-if="!isShowIcon" class="fa fa-angle-double-down mr-1" aria-hidden="true"></i>
      <i v-else class="fa fa-angle-double-up mr-1" aria-hidden="true"></i>
      <span class="leading-8 select-none">
        {{ text }}
      </span>
    </div>
  </div>
</template>

<script>
export default {
  name: "ButtonFilter",

  data() {
    return {
      isShowIcon: true,
    };
  },

  mounted() {},
  props: { text: String },

  methods: {
    handleClick() {
      this.isShowIcon = !this.isShowIcon;
      this.$emit("clickBtn");
    },
  },
};
</script>

<style scoped></style>
