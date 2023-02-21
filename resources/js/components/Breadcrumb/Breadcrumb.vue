<template>
  <div class="mb-1">
    <span class="text-gray-500 text-xl font-medium">{{ currentItem }}</span>
  </div>
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
import {MODULE_STORE} from "@/const";
import {computed, nextTick, reactive, ref} from "vue";
import {useStore} from "vuex";

const store = useStore()
const emit = defineEmits(['changeRoute'])

const items = computed(() => store.getters[`${MODULE_STORE.COMMON.NAME}/${MODULE_STORE.COMMON.GETTERS.GET_BREADCRUMB_ITEMS}`])
const currentItem = computed(() => store.getters[`${MODULE_STORE.COMMON.NAME}/${MODULE_STORE.COMMON.GETTERS.GET_BREADCRUMB_CURRENT}`])
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
