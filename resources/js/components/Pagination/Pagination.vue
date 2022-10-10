<template>
  <div class="w-full flex justify-center mt-3">
    <div class="px-2 py-1 bg-[#ddd] rounded cursor-pointer hover:opacity-60" @click="handleBack">Prev</div>
    <div
      v-if="pageCurrent - 1 > 0"
      class="px-2 py-1 bg-[#ddd] rounded ml-1 cursor-pointer hover:opacity-60"
      @click="handleBack"
    >
      {{ pageCurrent - 1 }}
    </div>
    <div class="px-2 py-1 bg-[#2a8ab1] text-white rounded ml-1 cursor-pointer hover:opacity-60">
      {{ pageCurrent }}
    </div>
    <div
      v-if="pageCurrent + 1 <= totalPage"
      class="px-2 py-1 bg-[#ddd] rounded ml-1 cursor-pointer hover:opacity-60"
      @click="handleNext"
    >
      {{ pageCurrent + 1 }}
    </div>
    <div class="px-2 py-1 bg-[#ddd] rounded ml-1 cursor-pointer hover:opacity-60" @click="handleNext">Next</div>
  </div>
</template>

<script>
export default {
  name: "Pagination",
  components: {},
  props: {
    pageCurrent: Number,
    totalPage: Number,
  },
  setup(props, context) {
    const handleBack = () => {
      if (props.pageCurrent > 1) {
        context.emit("onBack", props.pageCurrent - 1);
      }
    };
    const handleNext = () => {
      if (props.pageCurrent < props.totalPage) {
        context.emit("onNext", props.pageCurrent + 1);
      }
    };
    return { handleBack, handleNext };
  },
};
</script>

<style scoped></style>
