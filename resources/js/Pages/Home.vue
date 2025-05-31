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

<script setup>
import { Link } from '@inertiajs/vue3'//مهم للكل صفحة 
</script>

<template>
  <div>
   
    <div class="relative">
 <img
  src="https://media4.giphy.com/media/v1.Y2lkPTc5MGI3NjExcnAxM21jajYydTV2ZzZlZjNxZ2liZXQ3YzYydG01Y3lxMWthcDl3ZSZlcD12MV9pbnRlcm5hbF9naWZfYnlfaWQmY3Q9Zw/KDYB0cH4HW8xc3VIAx/giphy.gif"
  alt="Animated University Background"
  class="w-full h-[500px] object-cover"
/>
      <div class="absolute inset-0 flex flex-col justify-center items-center text-white bg-black bg-opacity-50 text-center">
        <p class="text-sm tracking-widest uppercase mb-2 text-purple-200">Welcome to</p>
        <h1 class="text-5xl font-extrabold leading-tight mb-2 text-white drop-shadow">Masarak </h1>
        <p class="text-lg text-purple-100 mb-6">Your smart university assistant</p>
        <Link
          href="/register"
          class="bg-purple-700 hover:bg-purple-800 text-white px-6 py-3 rounded-full text-lg font-semibold shadow-lg transition"
        >
          Explore Now
          <i class="fas fa-arrow-right ml-2"></i>
        </Link>
      </div>
    </div>

    
    <section class="bg-white py-14">
      <div class="max-w-6xl mx-auto px-6 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-10 text-center">
        <div class="p-6 rounded-xl shadow hover:shadow-md transition">
          <div class="text-3xl mb-4 text-purple-700">
            <i class="fas fa-graduation-cap"></i>
          </div>
          <h3 class="text-xl font-semibold mb-2 text-purple-800">Smart GPA</h3> <!-- 1-->
          <p class="text-gray-600 text-sm">Calculate your GPA easily based on JUST grading system.</p>
        </div>

        <div class="p-6 rounded-xl shadow hover:shadow-md transition">
          <div class="text-3xl mb-4 text-purple-700">
            <i class="fas fa-bullhorn"></i>
          </div>
          <h3 class="text-xl font-semibold mb-2 text-purple-800">Announcements</h3><!-- 2-->
          <p class="text-gray-600 text-sm">Stay updated with university & professors' announcements.</p>
        </div>

        <div class="p-6 rounded-xl shadow hover:shadow-md transition">
          <div class="text-3xl mb-4 text-purple-700">
            <i class="fas fa-car"></i>
          </div>
          <h3 class="text-xl font-semibold mb-2 text-purple-800">Rideshare</h3><!-- 3-->
          <p class="text-gray-600 text-sm">Connect with students for carpooling and transportation.</p>
        </div>
      </div>
    </section>

  
   
  </div>
</template>

<style>
@import '@fortawesome/fontawesome-free/css/all.min.css';
</style>