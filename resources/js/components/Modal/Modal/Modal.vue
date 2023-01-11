<template>
  <div class="flex w-full h-full top-0 left-0 items-center justify-center bg-[#d9d9d9]" @click="onClickOverlay" ref="overlay">
    <div class="modal">
      <slot />
    </div>
  </div>
</template>

<script setup>
const emit = defineEmits(['clickOutside'])
const onClickOverlay = (e) => {
  if (e.target === this.$refs.overlay) {
    emit('clickOutside')
  }
}
</script>

<style scoped>

</style>
