<template>

  <!-- Modal toggle -->
  <button class="block text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800" type="button" data-modal-toggle="defaultModal">
    Toggle modal
  </button>

  <!-- Main modal -->
  <div id="defaultModal" tabindex="-1" aria-hidden="true" class="overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 w-full md:inset-0 h-modal md:h-full justify-center items-center">
    <div class="absolute w-full h-full bg-gray-700 opacity-60"></div>
    <div class="relative h-full p-4 mx-auto w-full max-w-2xl h-full md:h-auto">
      <!-- Modal content -->
      <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">



        <!-- Modal header -->
        <div class="flex justify-between items-start p-4 rounded-t border-b dark:border-gray-600">
          <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
            Terms of Service
          </h3>

          <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-toggle="defaultModal">
            <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
            <span class="sr-only">Close modal</span>
          </button>
        </div>
        <!-- Modal body -->
        <div class="p-6 space-y-6">
          <div>
            <label class="typo__label">Select with search</label>
            <!--          <multiselect v-model="value" :options="options" :custom-label="nameWithLang" placeholder="Select one" label="name" track-by="name"></multiselect>-->
            <!--          <pre class="language-json"><code>{{ value  }}</code></pre>-->
          </div>
          <div>
            <form action="">
              <input type="text" @keyup="handleOnKeyUp" @focus="handleFocusInput" class="border border-gray-500 w-full" :value="selectedValue.name">
              <div>
                <select v-bind="selectedValue" @change="handleSelected">
                  <option v-for="(item, index) in currentProductAttributeValues" :value="item.id" :key="index">{{ item.name }}</option>
                </select>
              </div>
            </form>
          </div>
          <p class="text-base leading-relaxed text-gray-500 dark:text-gray-400">
            With less than a month to go before the European Union enacts new consumer privacy laws for its citizens, companies around the world are updating their terms of service agreements to comply.
          </p>
          <p class="text-base leading-relaxed text-gray-500 dark:text-gray-400">
            The European Union’s General Data Protection Regulation (G.D.P.R.) goes into effect on May 25 and is meant to ensure a common set of data rights in the European Union. It requires organizations to notify users as soon as possible of high-risk data breaches that could personally affect them.
          </p>
        </div>
        <!-- Modal footer -->
        <div class="flex items-center p-6 space-x-2 rounded-b border-t border-gray-200 dark:border-gray-600">
          <button data-modal-toggle="defaultModal" type="button" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">I accept</button>
          <button data-modal-toggle="defaultModal" type="button" class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">Decline</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import {ref} from "vue";
import Multiselect from 'vue-multiselect'

export default {
  name: "QuotePriceModal",
  components: {
    Multiselect
  },

  setup() {

    const keyWork = ref('');
    const selectedValue = ref({
      id: 'id1',
      name: 'name',
      price: 500
    },)
    const productAttributeValues = ref([])
    const listInput = ref([])

    const selectedOptionItems = ref([])
    //from API
    const currentProductAttributeValues = ref([
      {
        id: 'id1',
        name: 'name',
        price: 500
      },
      {
        id: 'id2',
        name: 'name2',
        price: 1000
      }
    ])

    const getListProductAttributeValueFromApi = async () => {
      currentProductAttributeValues.value = [
        {
          id: 'id1',
          name: 'name',
          price: 500
        },
        {
          id: 'id2',
          name: 'name2',
          price: 1000
        }
      ]
    }

    const handleOnKeyUp = (e) => {
      console.log(e.target.value)
    }

    const handleSelected = (e) => {
      console.log(e.target.key)
    }

    const handleFocusInput = (e) => {
      e.target.value = ''
    }

    const handleAddInput = () => {
      listInput.value.push(
        {
          id: '',

        }
      )
    }

    return {
      handleOnKeyUp,
      handleFocusInput,
      handleSelected,
      currentProductAttributeValues,
      selectedValue,
      keyWork,

    }
  }
}
</script>

<style scoped>

</style>
