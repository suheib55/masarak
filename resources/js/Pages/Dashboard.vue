<script setup>
import MainLayout from '@/Layouts/MainLayout.vue'
import ToolCard from '@/Components/ToolCard.vue'
</script>

<template>
  <MainLayout>
    
    <div class="text-center mt-10 mb-10">
      <h1 class="text-4xl font-extrabold text-purple-800">Welcome to Masarak!</h1>
      <p class="mt-2 text-lg text-gray-700">Choose a feature below</p>
    </div>

    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-8 px-6 pb-16">
      <ToolCard icon="book" label="Materials and test-bank" to="/materials" />
      <ToolCard icon="bullhorn" label="Announcements" to="/announcements" />
      <ToolCard icon="link" label="Links" to="/links" />
      <ToolCard icon="calculator" label="GPA" to="/gpa" />
      <ToolCard icon="comments" label="Posts" to="/posts" />
      <ToolCard icon="calendar" label="Events" to="/events" />
      <ToolCard icon="car" label="Rideshare" to="/rideshare" />
      <ToolCard icon="map" label="University Map" to="/map" />
      
    </div>
  </MainLayout>
</template>