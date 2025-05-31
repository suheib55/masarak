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
  <div class="min-h-screen bg-white px-6 md:px-10 py-16 text-gray-800">
    <div class="max-w-3xl mx-auto">
      <h1 class="text-4xl font-extrabold text-purple-800 mb-6 border-b-4 border-purple-300 inline-block pb-1">Contact Us</h1>

      <p class="text-lg mb-8 leading-relaxed">
        If you have any questions, feedback, or suggestions, feel free to reach out to us using the contact details below.
      </p>

      <div class="space-y-4 bg-purple-50 p-6 rounded-lg shadow">
        <div class="flex items-center space-x-3">
          <i class="fas fa-envelope text-yellow-500 text-lg"></i>
          <span>info@massrak.com</span>
        </div>
        <div class="flex items-center space-x-3">
          <i class="fas fa-phone-alt text-green-500 text-lg"></i>
          <span>+962 345 6789</span>
        </div>
        <div class="flex items-center space-x-3">
          <i class="fas fa-map-marker-alt text-blue-500 text-lg"></i>
          <span>Jordan – JUST</span>
        </div>
      </div>
    </div>
  </div>
</template>