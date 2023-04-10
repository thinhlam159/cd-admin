<template>
  <VueFinalModal
    class="flex justify-center items-center"
    content-class="flex flex-col max-w-xl mx-4 bg-white dark:bg-gray-900 border dark:border-gray-700 rounded-lg space-y-2"
  >
    <div class="min-w-[400px]">
      <div class="py-2 px-3 bg-gray-400">
        <h1 class="text-2xl font-semibold text-white">
          {{ title }}
        </h1>
      </div>
      <div class="py-6 text-xl px-4">
        <slot />
      </div>
      <div class="flex justify-end px-5 py-2 border-gray-200 border-t-[2px]">
        <button class="mt-1 ml-auto px-3 py-2 border rounded-lg cursor-pointer bg-red-500 hover:bg-red-700 text-center text-white min-w-[100px] font-semibold" @click="emit('confirm')">
          {{buttonValue}}
        </button>
      </div>
    </div>
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
