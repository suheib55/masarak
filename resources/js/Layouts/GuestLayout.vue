<template>
  <div class="min-h-screen flex flex-col">
    <!-- Top Bar -->
    <div class="bg-purple-800 text-white text-base px-8 py-3 flex justify-between items-center shadow-md">
      <div class="flex space-x-8 items-center">
        <div class="flex items-center space-x-2">
          <i class="fas fa-envelope text-yellow-300 text-lg"></i>
          <span class="font-medium">info@massrak.com</span>
        </div>
        <div class="flex items-center space-x-2">
          <i class="fas fa-phone-alt text-green-300 text-lg"></i>
          <span class="font-medium">+962 345 6789</span>
        </div>
        <div class="flex items-center space-x-2">
          <i class="fas fa-map-marker-alt text-blue-300 text-lg"></i>
          <span class="font-medium">Jordan – JUST</span>
        </div>
      </div>
      <slot name="top-right" />
    </div>

    <!-- Navbar -->
    <nav class="bg-white shadow-md py-4 px-6 flex justify-between items-center">
      <div class="flex items-center space-x-2 text-3xl font-extrabold bg-gradient-to-r from-purple-700 via-pink-500 to-yellow-400 text-transparent bg-clip-text">
        <i class="fas fa-graduation-cap text-purple-700"></i>
        <span>Masarak</span>
      </div>

      <div class="flex items-center space-x-6 text-sm font-medium text-gray-700">
        <Link href="/" class="hover:text-purple-600 flex items-center space-x-1">
          <i class="fas fa-home text-purple-600"></i>
          <span>Home</span>
        </Link>

        <Link href="/about" class="hover:text-purple-600 flex items-center space-x-1">
          <i class="fas fa-info-circle text-purple-600"></i>
          <span>About</span>
        </Link>

        <Link href="/contact" class="hover:text-purple-600 flex items-center space-x-1">
          <i class="fas fa-envelope-open-text text-purple-600"></i>
          <span>Contact</span>
        </Link>

        <Link href="/user-guide" class="hover:text-blue-600 flex items-center space-x-1">
          <i class="fas fa-book text-blue-600"></i>
          <span>User Guide</span>
        </Link>

        <Link href="/register" class="bg-purple-600 text-white px-4 py-2 rounded hover:bg-purple-700 transition flex items-center space-x-1">
          <i class="fas fa-user-plus text-white"></i>
          <span>Join Now</span>
        </Link>
      </div>
    </nav>

    <!-- Content -->
    <main class="flex-1">
      <slot />
    </main>

    <!-- Footer -->
    <footer class="bg-purple-800 text-white py-6 text-sm text-center mt-10">
      ©️ 2025 Masarak. All rights reserved.
    </footer>
  </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
</script>

<style>
@import '@fortawesome/fontawesome-free/css/all.min.css';
</style>