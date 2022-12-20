<template>
  <div class="w-full rounded-full bg-[#F1F5F9] relative h-[16px]">
    <div class="absolute top-0 left-0 rounded-full w-1/4 bg-[#627D98] h-full transition" :style="ratio"></div>
  </div>
</template>

<script>
import {computed, ref, toRef} from "vue";

export default {
  name: "progressBar",
  props: {
    totalQuestion: Number,
    currentQuestion: Number
  },
  setup(props) {
    const totalQuestion = toRef(props, 'totalQuestion')
    const currentQuestion = toRef(props, 'currentQuestion')
    const ratio = computed(
      () => `width:${currentQuestion.value / totalQuestion.value * 100}%`,
      // set: (modifiedValue) => {
      //   width.value = `width:${modifiedValue}%`
      // }
    )
    return {
      ratio
    }
  }

}
</script>

<style scoped>
/*.transition_123{*/
/*  transition: width 0.5s ease;*/
/*}*/
</style>
