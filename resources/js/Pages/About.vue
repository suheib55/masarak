<script>
import GuestLayout from '@/Layouts/GuestLayout.vue'
import MainLayout from '@/Layouts/MainLayout.vue'

export default {
  layout: (h, page) => {
    return page.props.auth && page.props.auth.user
      ? h(MainLayout, () => page)
      : h(GuestLayout, () => page)
  }
}
</script>

<template>
  <div class="min-h-screen bg-gray-50 px-6 md:px-10 py-16 text-gray-800">
    <div class="max-w-5xl mx-auto">
      
      <h1 class="text-3xl font-extrabold text-purple-800 mb-6 border-b-4 border-purple-300 w-fit pb-2">
        About Masarak
      </h1>
      
      <div class="bg-white p-8 rounded-2xl shadow-md border border-purple-200">
        <p class="text-xl text-gray-700 leading-relaxed mb-6">
          “Masarak” is a modern educational platform developed for students at Jordan University of Science and Technology.
        </p>

        <p class="text-lg text-gray-700 leading-relaxed mb-4">
          Our goal is to simplify student life by offering smart tools like GPA calculators, access to learning materials, past exams, a basic transportation system, and event tracking.
        </p>

        <p class="text-lg text-gray-700 leading-relaxed">
          Our vision is to create an inclusive, interactive, and supportive educational environment for all university students.
        </p>
      </div>
    </div>
  </div>
</template>