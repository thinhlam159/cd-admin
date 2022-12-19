<template>
  <div class="tabs__content h-full" v-show="selectedTitle === title">
    <slot/>
  </div>
</template>

<script setup>
import {inject} from 'vue'

const props = defineProps({
  title: String
})
const selectedTitle = inject('selectedTitle')
</script>
