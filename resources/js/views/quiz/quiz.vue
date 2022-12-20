<template>
  <div class="w-[1122px] h-[280px] py-12">
    <div class="mb-5">
      <progress-bar :total-question="questions.length" :current-question="queTag.queNum"/>
    </div>
    <div>
      <div class="bg-[#F0F4F8] py-12">
        <div class="flex justify-center mb-15">
          <div class="mr-5 flex">
            <div><img :src="icon" alt="" class="z-0"></div>
            <div class="relative"><span class="absolute top-3 -right-3">{{ queTag.queNum }}</span></div>
          </div>
          <div class="py-6"><span>{{ queTag.que_text }}</span></div>
        </div>
        <div class="flex w-full px-10 justify-between">
          <div
            v-for="(item, index) in optionTag"
            :key="index"
            class="w-[20%] flex justify-center items-center h-[78px] w-[213px] cursor-pointer bg-[#ffffff] border-gray-200"
            @click="handleSelectOption(item, queTag.queNum)"
          >
            <span>{{ item }}</span>
          </div>
        </div>
      </div>
    </div>
    <div v-if="isShowAnswer" class="mt-2 bg-[#F0F4F8] rounded-md p-5">
      <div class="bg-white">
        <div v-for="(item, index) in userAnswer" :key="index">
          <span>Question Number: {{ item.numb }}</span>
          <p>Answer: {{ item.answer }}</p>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import {ref} from "vue";
import icon from "@/assets/images/Group 494924.png"
import progressBar from "@/views/quiz/progressBar.vue";

export default {
  name: "quiz",
  components: { progressBar },
  computed: {
    icon() {
      return icon;
    },
  },
  setup() {
    const questions = ref([
      {
        numb: 1,
        question: "元気がいっぱいだ?",
        answer: "Hyper Text Markup Language",
        options: [
          "ちがう",
          "ややちがう",
          "まあまあそうだ",
          "そうだ"
        ]
      },
      {
        numb: 2,
        question: "元気になれる?",
        answer: "Cascading Style Sheet",
        options: [
          "ちがう",
          "ややちがう",
          "まあまあそうだ ",
          "そうだ              "
        ]
      },
      {
        numb: 3,
        question: "力がみなぎっている?",
        answer: "Hypertext Preprocessor",
        options: [
          "ちがう",
          "ややちがう",
          "まあまあそうだ",
          "そうだ "
        ]
      },
      {
        numb: 4,
        question: "そうだ?",
        answer: "Structured Query Language",
        options: [
          "ちがう",
          "ややちがう",
          "まあまあそうだ",
          "そうだ"
        ]
      },
      {
        numb: 5,
        question: "ワクワクし、楽しい気分だ?",
        answer: "eXtensible Markup Language",
        options: [
          "ちがう",
          "ややちがう",
          "まあまあそうだ",
          "そうだ"
        ]
      },
    ])

    const isShowAnswer = ref(false)
    const queTag = ref({
      que_text: '',
      queNum: 0
    })
    const optionTag = ref([])

    const userAnswer = ref([])

    const handleSelectOption = (answer, numb) => {
      userAnswer.value.push({
        numb,
        answer
      })
      if (numb === questions.value.length) {
        isShowAnswer.value = true
        return
      }
      optionTag.value = questions.value[numb].options
      queTag.value.que_text = questions.value[numb].question
      queTag.value.queNum = questions.value[numb].numb
    }

    const init = () => {
      optionTag.value = questions.value[0].options
      queTag.value.que_text = questions.value[0].question
      queTag.value.queNum = questions.value[0].numb
    }

    init()

    return {
      questions,
      queTag,
      optionTag,
      isShowAnswer,
      userAnswer,
      handleSelectOption
    }
  }
}
</script>

<style scoped>

</style>
