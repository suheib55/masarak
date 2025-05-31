<script setup>
import MainLayout from '@/Layouts/MainLayout.vue'
import { ref } from 'vue'

const questions = ref([])

const newQuestion = ref({
  title: '',
  course: '',
  year: '',
  notes: '',
  file: null,
  previewUrl: null
})

function handleFileUpload(event) {
  const file = event.target.files[0]
  if (file) {
    newQuestion.value.file = file

   
    const reader = new FileReader()
    reader.onload = () => {
      newQuestion.value.previewUrl = reader.result
    }
    reader.readAsDataURL(file)
  }
}

function addQuestion() {
  if (newQuestion.value.title && newQuestion.value.course && newQuestion.value.year) {
    questions.value.unshift({ ...newQuestion.value })
    newQuestion.value = {
      title: '',
      course: '',
      year: '',
      notes: '',
      file: null,
      previewUrl: null
    }
   
    document.getElementById('fileInput').value = ''
  }
}
</script>

<template>
  <MainLayout>
    <div class="p-6 max-w-3xl mx-auto">
      <h1 class="text-3xl font-bold text-purple-800 mb-6 text-center">Test Bank</h1>

    
      <div class="bg-white border border-gray-200 rounded-lg p-6 shadow mb-8">
        <h2 class="text-xl font-semibold text-purple-700 mb-4">Add Previous Exam Question</h2>

        <input v-model="newQuestion.title" type="text" placeholder="Question Title"
               class="w-full mb-3 px-4 py-2 border rounded" />

        <input v-model="newQuestion.course" type="text" placeholder="Course Name"
               class="w-full mb-3 px-4 py-2 border rounded" />

        <input v-model="newQuestion.year" type="number" placeholder="Year"
               class="w-full mb-3 px-4 py-2 border rounded" />

        <textarea v-model="newQuestion.notes" rows="3" placeholder="Notes (optional)"
                  class="w-full mb-3 px-4 py-2 border rounded resize-none"></textarea>

        <input type="file" id="fileInput"
               @change="handleFileUpload"
               accept=".pdf,image/*"
               class="w-full mb-4" />

        <button @click="addQuestion"
                class="bg-purple-700 text-white px-6 py-2 rounded hover:bg-purple-600">
          Add Question
        </button>
      </div>

      <div v-if="questions.length">
        <h2 class="text-xl font-semibold text-purple-700 mb-4">Saved Questions</h2>

        <div v-for="(q, index) in questions" :key="index"
             class="bg-white border border-gray-300 rounded-lg p-4 mb-4 shadow">
          <p class="font-semibold text-purple-800">{{ q.title }}</p>
          <p class="text-gray-700 text-sm">Course: {{ q.course }} | Year: {{ q.year }}</p>
          <p class="text-gray-600 mt-1" v-if="q.notes">Notes: {{ q.notes }}</p>

          
          <div v-if="q.previewUrl" class="mt-3">
            <template v-if="q.file.type.startsWith('image/')">
              <img :src="q.previewUrl" class="max-h-48 rounded border" />
            </template>
            <template v-else-if="q.file.type === 'application/pdf'">
              <a :href="q.previewUrl" target="_blank" class="text-blue-600 hover:underline">
                View PDF File
              </a>
            </template>
          </div>
        </div>
      </div>

      <div v-else class="text-center text-gray-500">
        No questions added yet.
      </div>
    </div>
  </MainLayout>
</template>