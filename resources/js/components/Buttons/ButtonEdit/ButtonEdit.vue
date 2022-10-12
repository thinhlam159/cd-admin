<template>
  <button class="block bg-[#00FF00] p-2 rounded" @click="handleClick">
    <i class="fa fa-pencil-square-o text-white mr-1" aria-hidden="true"></i>
    <span class="text-white">
      {{ text || "No-data" }}
    </span>
  </button>
</template>

<script>
export default {
  name: "ButtonEdit",

  data() {
    return {};
  },
  props: { text: String },
  mounted() {},

  methods: {
    handleClick() {
      this.$emit("clickBtn");
    },
  },
};
</script>

<style scoped></style>
