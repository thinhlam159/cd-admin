<template>
  <div class="relative flex items-center border border-gray-400 rounded-sm p-1 w-max w-max" ref="selectWithSearchRef">
    <div class="flex justify-center items-center">
      <SearchIcon />
    </div>
    <div class="flex flex-wrap ml-2" @click="onFocusSearch">
      <div v-if="selectedOption !== null" class="inline-block">
        <div class="selected-item flex border border-gray-400 h-full items-center px-2 rounded-md text-md">
          <div :title="selectedOption.text">{{ selectedOption.text }}</div>
          <div class="ml-1" @click="handleDeleteItem(selectedOption)">
            <CloseIconBold />
          </div>
        </div>
      </div>
      <input class="p-1 outline-none text-lg" v-model="searchTerm" placeholder="Search..." @focus="showDropdown = true"/>
    </div>
    <div v-if="showDropdown" class="absolute z-10 top-[100%] left-0 w-full m-0 p-0 border border-gray-400 bg-white">
      <ul class="w-full">
        <li v-for="option in filteredOptions"
            :key="option.id"
            :value="option.id"
            @click="handleOptionsSelected(option)"
            class="list-none w-full p-1 text-lg hover:bg-[#d9d9d9]"
        >
          {{ option.text }}
        </li>
      </ul>
    </div>
  </div>
</template>

<script>
import {ref, computed, onMounted, nextTick, onUnmounted} from 'vue';
import CloseIconBold from "@/components/MultiSelect/CloseIconBold.vue";
import SearchIcon from "@/components/icons/SearchIcon.vue";

export default {
  components: {SearchIcon, CloseIconBold},
  props: ['options'],
  setup(props, { emit }) {
    const selectedOption = ref(null);
    const searchTerm = ref('');
    const showDropdown = ref(false);
    const selectWithSearchRef = ref(null);
    const filteredOptions = computed(() => {
      return props.options.filter(option => option.text.toLowerCase().includes(searchTerm.value.toLowerCase()));
    });

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


    return {
      selectedOption,
      searchTerm,
      filteredOptions,
      showDropdown,
      selectWithSearchRef,
      handleOptionsSelected,
      handleDeleteItem,
      onFocusSearch
    };
  }
};
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
