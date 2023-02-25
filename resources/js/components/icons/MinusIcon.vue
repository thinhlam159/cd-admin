<template>
  <div class="decrement-button" @click="decrementValue">
    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
      <path d="M19,13H5V11h14Z"/>
    </svg>
  </div>
</template>

<script setup>
const props = defineProps({
  value: {
    type: Number,
    required: true
  },
  minValue: {
    type: Number,
    default: -Infinity
  },
  step: {
    type: Number,
    default: 1
  }
})
const emit = defineEmits(['input'])
const decrementValue = () => {
  const newValue = props.value - props.step;
  if (newValue >= props.minValue) {
    emit('input', newValue)
  }
}
</script>

<style scoped>
.decrement-button {
  display: inline-block;
  padding: 8px;
  border: none;
  background-color: #f5f5f5;
  border-radius: 4px;
  cursor: pointer;
}

.decrement-button:hover {
  background-color: #e0e0e0;
}

.decrement-button svg {
  fill: #555;
  vertical-align: middle;
}
</style>
