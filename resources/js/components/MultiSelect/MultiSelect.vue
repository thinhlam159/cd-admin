<template>
  <div
    class="multi-select max-w-[300px]"
    :id="id"
    :class="['anchor-search', isFocusClass]"
    :style="{ border: hasSearch ? '' : 'none' }"
    v-click-outside="onClickOutside"
  >
    <div v-if="hasSearch" class="search-icon">
      <SearchIcon />
    </div>

    <div class="flex flex-wrap" @click="onFocusSearch" :class="[onlySelectOne ? 'handle-wrap' : '']">
<!--      <div-->
<!--        v-for="(item, index) in options"-->
<!--        :key="index"-->
<!--      >-->
<!--        <div-->
<!--          class="selected-item"-->
<!--        >-->
<!--          <div :title="item.customer_name">{{ item.customer_name }}</div>-->
<!--          <div v-if="hasSearch" class="close-icon" @click="handleDeleteItem(item)">-->
<!--            <CloseIconBold />-->
<!--          </div>-->
<!--        </div>-->
<!--      </div>-->
      <div v-if="hasSearch" class="inline-block max-w-[300px]" :style="{ flex: '1 1 0%' }">
        <input
          type="text"
          :placeholder="!value.length ? placeholder : ''"
          :readonly="disabled"
          v-model="textSearch"
          @input="onSearchItem"
          @focus="onFocusSearch"
          :class="[isFocusClass]"
          class="outline-none w-[200px] min-h-[36px]"
        />
        <div class="h-full flex justify-center items-center absolute top-0 right-3" @click="onFocusSearch">
          <CaretDownBold />
        </div>
      </div>
    </div>

    <div id="listOption" class="bg-white text-[#337ab7] absolute py-1 w-full z-10 max-h-[300px] overflow-auto rounded-sm top-0 left-0 max-w-[300px]" v-show="toggleOptions">
      <div
        v-for="(option, indexOption) in optionsRender"
        :key="indexOption"
        class="list-option__element border border-gray-400 bg-[#d6d6d6] cursor-pointer h-[36px] px-2 py-1"
        :class="{ selected: onSelected(option) }"
        @click="handleSelected(option, option[keyActive])"
        :title="option.customer_name"
      >
        {{ option.customer_name }}
      </div>
    </div>
  </div>
</template>

<script setup>
import ClickOutside from 'vue-click-outside'
import {computed, ref, onUpdated, toRefs, watch} from "vue";
import SearchIcon from "@/components/icons/SearchIcon.vue";
import CaretDownBold from "@/components/MultiSelect/CaretDownBold.vue";
import CloseIconBold from "@/components/MultiSelect/CloseIconBold.vue";

const props = defineProps({
  value: Array,
  options: {
    type: Array,
    required: true,
    default: Array,
  },
  disabled: Boolean,
  placeholder: String,
  keyId: String,
  // keyName: String,
  keyActive: String,
  id: String,
  hasSearch: Boolean,
  onlySelectOne: {
    type: Boolean,
    default: false,
  },
});

const emit = defineEmits(['change'])

const listSelected = ref([])
const optionsRender = ref([])
const textSearch = ref('')
const toggleOptions = ref(false)
const isFocus = ref(false)
const target = ref(null)
const { options, value, id, keyId, onlySelectOne, hasSearch } = toRefs(props)
// const isShowListSearch = refs(props)

const handleSelected = (option) => {
  const listId = listSelected.value
  // remove item selected
  if (listId.includes(option[keyId.value])) {
    const index = listId.indexOf(option[keyId.value])
    // case only select one
    if (onlySelectOne.value) {
      this.listSelected = []
      this.toggleOptions = false
    } else listSelected.value.splice(index, 1)
  }
  // add item selected
  else {
    // case only select one
    if (onlySelectOne.value) {
      this.listSelected = [option[keyId.value]]
      this.toggleOptions = false
    } else onlySelectOne.value.push(option[keyId.value])
  }
  this.textSearch = ''
  setTimeout(() => {
    this.setPositionOptions()
  }, 1)
  emit('change', listSelected.value)
}

const onSelected = (option) => {
  if (listSelected.value?.length) return false
  return listSelected.value.includes(option[keyId.value])
}

const onSearchItem = (e) => {
  if (e.target.value)
    optionsRender.value = options.value.filter(item =>
      item[keyName.value].includes(e.target.value),
    )
  else {
    optionsRender.value = options.value
  }
}

const onFocusSearch = () => {
  if (!hasSearch.value) return
  isFocus.value = true
  toggleOptions.value = true
  setTimeout(() => {
    setPositionOptions()
  }, 1)
}

const onClickOutside = () => {
  console.log(123)
  toggleOptions.value = false
  isFocus.value = false
  setTimeout(() => {
    setPositionOptions()
  }, 1)
}

const handleDeleteItem = (optionSelected) => {
  if (!hasSearch.value) return
  const listId = listSelected.value
  // remove item selected
  if (listId.includes(optionSelected[keyId.value])) {
    const index = listId.indexOf(optionSelected[keyId.value])
    listSelected.value.splice(index, 1)
  }
  emit('change', listSelected.value)
  setTimeout(() => {
    setPositionOptions()
  }, 1)
}

// const handleSubString = (item) => {
//   if (item.length > 50) {
//     return `${item.substring(0, 50)}...`
//   }
//   return item
// }

const isFocusClass = computed(() => {
  return isFocus.value ? 'multi-select--focus' : null
})

const isCheckInput = computed(() => {
  return options.value.length === 0
})

const setPositionOptions = () => {
  const domParent = document.getElementById(id.value)
  const listOption = document.querySelectorAll(`#${id.value} #listOption`)
  if (listOption && listOption[0]) {
    listOption[0].style.top = domParent.offsetHeight + 'px'
  }
}

onUpdated(() => {
  listSelected.value = JSON.parse(JSON.stringify(value.value))
  optionsRender.value = JSON.parse(JSON.stringify(options.value))
})
watch(value, () => {
  listSelected.value = JSON.parse(JSON.stringify(value.value))
})
watch(options, () => {
  optionsRender.value = JSON.parse(JSON.stringify(options.value))
})

// onClickOutside(target, (event) => console.log(event))

</script>

<style scoped>
.anchor-search {
  background-color: #ffffff;
  border: 1px solid #dcdcdc;
  border-radius: 4px;
  padding: 4px 12px 0;
  display: flex;
  gap: 14px;
  position: relative;
}
.anchor-search--on {
  border-color: #999999;
  background: #f0f4f8;
}
.anchor-search input {
  background: #f0f4f8;
}

input {
  border: none;
  height: unset;
  padding-left: unset;
}

.anchor-search .list-selected .selected-item {
  background: #f0f4f8;
  border-radius: 4px;
  display: flex;
  padding: 6px 12px 6px 8px;
  gap: 8px;
  align-items: center;
  color: #627d98;
  font-size: 14px;
  display: inline-flex;
  margin-right: 8px;
  margin-bottom: 4px;
  border: 1px solid #dbdbdb;
  height: 32px;
  max-width: 100%;
}
.anchor-search .list-selected .selected-item div {
  max-width: 300px;
  overflow: hidden;
  text-overflow: ellipsis;
  word-break: keep-all;
  -webkit-line-clamp: 1 !important;
  -webkit-box-orient: vertical !important;
  white-space: nowrap;
}

.anchor-search .list-selected .selected-item .close-icon {
  align-items: center;
  cursor: pointer;
  display: flex;
  margin-top: 2px;
  width: max-content;
}

.anchor-search .list-selected .selected-item .active {
  border: solid 1px #ef4e4e;
  opacity: 0.4;
}

.list-option__element:hover {
  background: #d9d9d9;
  color: #fff;
}
</style>
