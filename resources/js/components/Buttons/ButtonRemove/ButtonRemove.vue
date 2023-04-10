<template>
  <button class="block bg-red-500 p-2 rounded" @click.prevent="handleClick">
    <i class="fa fa-times text-white mr-1" aria-hidden="true"></i>
    <span class="text-white">
      {{ text || "No-data" }}
    </span>
  </button>
</template>

<script>
export default {
  name: "ButtonRemove",

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
