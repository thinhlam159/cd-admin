<template>
  <VueFinalModal
    class="flex justify-center items-center"
    content-class="flex flex-col max-w-xl mx-4 p-4 bg-white dark:bg-gray-900 border dark:border-gray-700 rounded-lg space-y-2"
  >
    <h1 class="text-xl">
      {{ title }}
    </h1>
    <slot />
    <button class="mt-1 ml-auto px-2 border rounded-lg" @click="emit('confirm')">
      {{buttonValue}}
    </button>
  </VueFinalModal>
</template>
<script setup>
 import { VueFinalModal } from 'vue-final-modal'

  const props = defineProps({
    title: {
      type: String,
      require: true
    },
    buttonValue: {
      type: String,
      require: true
    }
  })
  const emit = defineEmits(['confirm'])
</script>

<style scoped>

</style>
