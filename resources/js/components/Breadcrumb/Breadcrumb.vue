<template>
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item" v-for="(item, index) in items" :key="index">
        <a @click="handleChangeRoute(item.link)" >{{ item.label }}</a>
      </li>
      <li>{{ currentItem }}</li>
    </ol>
  </nav>
</template>

<script setup>
const props = defineProps({
  items: {
    type: Array,
    required: true
  },
  currentItem: {
    type: String,
    required: true
  }
})
const emit = defineEmits(['changeRoute'])
const handleChangeRoute = (link) => {
  emit('changeRoute', link)
}
</script>

<style scoped>
/* Style the list */
ol.breadcrumb {
  list-style: none;
}

/* Display list items side by side */
ol.breadcrumb li {
  display: inline;
  font-size: 14px;
}

/* Add a slash symbol (/) before/behind each list item */
ol.breadcrumb li+li:before {
  padding: 8px;
  color: black;
  content: "/\00a0";
}

/* Add a color to all links inside the list */
ol.breadcrumb li a {
  color: #A7B1C2;
  text-decoration: none;
}

/* Add a color on mouse-over */
ol.breadcrumb li a:hover {
  cursor: pointer;
  text-decoration: underline;
}
</style>
