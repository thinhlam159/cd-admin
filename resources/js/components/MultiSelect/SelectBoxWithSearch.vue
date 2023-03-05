<template>
  <div class="relative border border-gray-300 rounded-sm w-full" ref="selectWithSearchRef">
    <div class="flex w-full h-[36px] items-center p-2 bg-white" @click="onFocusSearch">
      <div v-if="selectedOption !== null" class="inline-block">
        <div class="selected-item text-base text-center">
          <span :title="selectedOption.text">{{ selectedOption.text }}</span>
        </div>
      </div>
    </div>
    <div v-if="showDropdown" class="absolute z-10 top-[100%] left-0 w-full m-0 p-0 border border-gray-200 bg-white">
      <div class="relative py-3 border-gray-300 border-b">
        <div class="absolute top-3 left-1 flex justify-center items-center">
          <SearchIcon/>
        </div>
        <input class="px-10 outline-none text-lg w-full" v-model="searchTerm" placeholder="Tìm..." @focus="showDropdown = true"/>
      </div>
      <div class="max-h-[300px] overflow-y-scroll overscroll-y-auto">
        <ul class="w-full">
          <li v-for="option in filteredOptions"
              :key="option.id"
              :value="option.id"
              @click="handleOptionsSelected(option)"
              class="list-none w-full p-2 text-lg hover:bg-[#d9d9d9]"
          >
            {{ option.text }}
          </li>
        </ul>
      </div>
    </div>
  </div>
</template>

<script setup>
import {ref, computed, onMounted, nextTick, onUnmounted} from 'vue';
import SearchIcon from "@/components/icons/SearchIcon.vue";

const selectedOption = ref(null);
const searchTerm = ref('');
const showDropdown = ref(false);
const selectWithSearchRef = ref(null);
const filteredOptions = computed(() => {
  return props.options.filter(option => option.text.toLowerCase().includes(searchTerm.value.toLowerCase()));
});
const props = defineProps(['options'])
const emit = defineEmits(['optionSelected'])

const handleOptionsSelected = (selectedItem) => {
  showDropdown.value = false
  selectedOption.value = selectedItem
  searchTerm.value = ''
  emit('optionSelected', selectedOption.value);
};
const onFocusSearch = () => {
  showDropdown.value = true
}
const handleDeleteItem = () => {
  selectedOption.value = null
  showDropdown.value = true
}
const hiddenSuggestDropDown = () => {
  nextTick(() => {
    showDropdown.value = false
  })
}

const handleClickOutside = (event) => {
  if (!selectWithSearchRef.value.contains(event.target)) {
    showDropdown.value = false
  }
}

onMounted(() => {
  document.addEventListener('click', handleClickOutside)
})

onUnmounted(() => {
  document.removeEventListener('click', handleClickOutside)
})
</script>

<style scoped>
.multi-select-with-search {
  position: relative;
  display: flex;
  flex-direction: row;
  align-items: center;
  margin: 20px;
}


.multi-select {
  position: absolute;
  z-index: 1;
  padding: 10px;
  font-size: 16px;
  border-radius: 0 5px 5px 0;
  border: 1px solid lightgray;
  appearance: none;
}
</style>
