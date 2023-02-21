<template>
  <button class="increment-button" @click="incrementValue">
    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
      <path d="M19,13H13v6h-2V13H5V11h6V5h2v6h6Z"/>
    </svg>
  </button>
</template>

<script setup>
const props = defineProps({
  value: {
    type: Number,
    required: true
  },
  maxValue: {
    type: Number,
    default: Infinity
  },
  step: {
    type: Number,
    default: 1
  }
})
const emit = defineEmits(['input'])

const incrementValue = () => {
  const newValue = props.value + props.step;
  if (newValue <= props.maxValue) {
    emit('input', newValue);
  }
}
</script>

<style scoped>
.increment-button {
  display: inline-block;
  padding: 8px;
  border: none;
  background-color: #f5f5f5;
  border-radius: 4px;
  cursor: pointer;
}

.increment-button:hover {
  background-color: #e0e0e0;
}

.increment-button svg {
  fill: #555;
  vertical-align: middle;
}
</style>
