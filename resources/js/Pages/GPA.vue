<template>
  <MainLayout>
    <div class="p-6 max-w-4xl mx-auto min-h-screen">
      <h1 class="text-3xl font-bold text-purple-800 mb-6">GPA Calculator (JUST)</h1>

     
      <div class="mb-6">
        <h2 class="text-purple-700 font-semibold mb-2">Previous Cumulative GPA (optional)</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <input type="number" v-model="previousGPA" step="0.01" placeholder="Previous GPA"
                 class="border px-4 py-2 rounded w-full" />
          <input type="number" v-model="previousHours" placeholder="Previous Credit Hours"
                 class="border px-4 py-2 rounded w-full" />
        </div>
      </div>

      
      <div class="overflow-x-auto">
        <table class="w-full border-collapse border text-center mt-4 mb-4">
          <thead class="bg-purple-100 text-purple-800">
            <tr>
              <th class="border px-4 py-2">#</th>
              <th class="border px-4 py-2">Credit Hours</th>
              <th class="border px-4 py-2">Grade</th>
              <th class="border px-4 py-2">Action</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(course, index) in courses" :key="index">
              <td class="border px-4 py-2">{{ index + 1 }}</td>
              <td class="border px-4 py-2">
                <input type="number" v-model="course.hours" min="1"
                       class="border px-2 py-1 rounded w-20" />
              </td>
              <td class="border px-4 py-2">
                <select v-model="course.grade" class="border px-2 py-1 rounded w-24">
                  <option value="">Select</option>
                  <option v-for="(val, key) in gradeMap" :key="key" :value="key">{{ key }}</option>
                </select>
              </td>
              <td class="border px-4 py-2">
                <button @click="removeCourse(index)"
                        class="bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600">
                  Delete
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      
      <div class="mb-4">
        <button @click="addCourse"
                class="bg-purple-700 text-white px-4 py-2 rounded hover:bg-purple-600">
          + Add Course
        </button>
      </div>

      
      <div class="mb-8">
        <button @click="calculateGPA"
                class="bg-red-600 text-white px-6 py-3 text-lg rounded-lg font-bold hover:bg-green-500 w-full sm:w-auto">
          Calculate GPA
        </button>
      </div>

     
      <div class="text-center mb-10">
        <div v-if="gpa !== null" class="text-xl text-purple-800 font-semibold">
          GPA for this semester: {{ gpa }}
        </div>
        <div v-if="cumulativeGPA !== null" class="text-lg text-purple-700 font-medium mt-2">
          Updated Cumulative GPA: {{ cumulativeGPA }}
        </div>
      </div>
    </div>
  </MainLayout>
</template>

<script setup>
import MainLayout from '@/Layouts/MainLayout.vue'
import { ref } from 'vue'

const courses = ref([{ hours: '', grade: '' }])
const gpa = ref(null)
const cumulativeGPA = ref(null)
const previousGPA = ref('')
const previousHours = ref('')

const gradeMap = {
  'A': 4.0, 'A-': 3.7, 'B+': 3.3, 'B': 3.0,
  'B-': 2.7, 'C+': 2.3, 'C': 2.0, 'C-': 1.7,
  'D+': 1.3, 'D': 1.0, 'F': 0.0
}

function addCourse() {
  courses.value.push({ hours: '', grade: '' })
}

function removeCourse(index) {
  courses.value.splice(index, 1)
}

function calculateGPA() {
  let totalPoints = 0
  let totalHours = 0

  for (const course of courses.value) {
    const hours = parseFloat(course.hours)
    const gradeValue = gradeMap[course.grade]

    if (!isNaN(hours) && gradeValue !== undefined) {
      totalPoints += hours * gradeValue
      totalHours += hours
    }
  }

  const currentGPA = totalHours ? totalPoints / totalHours : null
  gpa.value = currentGPA?.toFixed(2)

  const prevGPA = parseFloat(previousGPA.value)
  const prevHours = parseFloat(previousHours.value)

  if (!isNaN(prevGPA) && !isNaN(prevHours) && currentGPA !== null) {
    const totalCombinedPoints = (prevGPA * prevHours) + (currentGPA * totalHours)
    const totalCombinedHours = prevHours + totalHours
    cumulativeGPA.value = (totalCombinedPoints / totalCombinedHours).toFixed(2)
  } else {
    cumulativeGPA.value = null
  }
}
</script>