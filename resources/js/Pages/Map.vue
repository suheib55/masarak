<script setup>
import MainLayout from '@/Layouts/MainLayout.vue'
import { ref } from 'vue'

const showLocations = ref(false)
const showMaps = ref(false)

const toggleLocations = () => {
  showLocations.value = !showLocations.value
  if (showLocations.value) showMaps.value = false
}

const toggleMaps = () => {
  showMaps.value = !showMaps.value
  if (showMaps.value) showLocations.value = false
}

const locations = [
  { name: 'قاعة SA1 من المول', url: 'https://youtu.be/IMxeXozxAhU' },
  { name: 'قاعة 10H4 من المول', url: 'https://youtu.be/VH-EIZnA9EY' },
  { name: 'قاعة N2 من المول', url: 'https://youtu.be/72KrSUzJhQg' },
  { name: 'قاعة NB66+NB63 من المول', url: 'https://youtu.be/y2t5aCGq2Kw' },
  { name: 'قاعات المدرجات الطبية', url: 'https://youtu.be/9NWcggrWpLI' },
  { name: 'قاعة M7L2 من كافيتيريا الهندسية', url: 'https://youtu.be/fpU_lMJ7ikI' },
  { name: 'قاعة N1L0 من كافيتيريا الهندسية', url: 'https://youtu.be/srK5CZoSF5Q' },
  { name: 'قاعة M4 من كافيتيريا الطبية', url: 'https://youtu.be/NioZ_YGupMA' },
  { name: 'قاعة N2L-1 من جمعية التصوير الطبية', url: 'https://youtu.be/u5U2LhJzMaU' },
  { name: 'قاعة M4L-3 من سكوير الطبM', url: 'https://youtu.be/6IL0zE6D0xQ' },
  { name: 'قاعات CIS01/CIS02/CS01 من المول', url: 'https://youtu.be/nZlw0tkDmq8' },
  { name: 'قاعات CIS03/CS02/CS03 من المول', url: 'https://youtu.be/vtk5eI37AlA' },
  { name: 'قاعات N1L0-SE01 /2 /3 من مدرج الفاروق', url: 'https://youtu.be/3pDNhP7AuuA' },
  { name: 'قاعة M7L2-CPE06', url: 'https://youtu.be/WxM-7fU2UkI' },
  { name: 'قاعتي E4-L2 CPE09 + E4-L2 CPE08 من الكافيتيريا الهندسية', url: 'https://youtu.be/fsVYhGzbHhE' },
  { name: 'قاعة E1L3-NES02 من الكافيتيريا الهندسية', url: 'https://youtu.be/HJCuFhI4SY4' },
  { name: 'قاعة N2L1-CIS06 من الكافيتيريا الهندسية', url: 'https://youtu.be/3TkYvrsWypY' },
  { name: 'قاعتي M6L2-cad1+ M5L2-cad1 من الكافيتيريا الهندسية', url: 'https://youtu.be/YttZpijam4I' },
  { name: 'قاعة G2L2-NES04 من الكافيتيريا الهندسية', url: 'https://youtu.be/hSV4Z2YyFm8' },
  { name: 'قاعتي C1L2 - se04 + C5l2-gis من مجمع الباصات', url: 'https://youtu.be/YlrZYyz1hLM' },
]

const maps = [
  {
    title: 'الخريطة العامة للجامعة',
    description: 'عرض الخريطة التفاعلية الرسمية لجامعة العلوم والتكنولوجيا.',
    url: 'https://www.just.edu.jo/ar/aboutjust/map',
  },
  {
    title: 'موقع الجامعة على الخارطة',
    description: 'اعرف مكان الجامعة وموقعها الجغرافي بالتفصيل.',
    url: 'https://www.just.edu.jo/ar/aboutjust/pages/just_location.aspx',
  },
  {
    title: 'خريطة الوصول لذوي الإعاقة',
    description: 'عرض خريطة تسهيلات الوصول لذوي الاحتياجات الخاصة داخل الحرم.',
    url: 'https://www.just.edu.jo/ar/aboutjust/Map/Pages/DisabledAccessMap.aspx',
  },
]
</script>

<template>
  <MainLayout>
    
    <div class="text-center mt-10 mb-10">
      <h1 class="text-4xl font-extrabold text-purple-800">Campus Map</h1>
      <p class="mt-2 text-lg text-gray-700">Explore university locations and maps</p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-8 px-6 pb-10">
      <div
        @click="toggleLocations"
        class="cursor-pointer bg-white border rounded-lg shadow p-6 text-center hover:shadow-lg transition"
      >
        <div class="text-purple-600 text-4xl mb-2">
          <i class="fa-solid fa-location-dot"></i>
        </div>
        <div class="text-lg font-semibold text-gray-800">Locations of Classes</div>
      </div>

      <div
        @click="toggleMaps"
        class="cursor-pointer bg-white border rounded-lg shadow p-6 text-center hover:shadow-lg transition"
      >
        <div class="text-purple-600 text-4xl mb-2">
          <i class="fa-solid fa-map-location-dot"></i>
        </div>
        <div class="text-lg font-semibold text-gray-800">University Map</div>
      </div>
    </div>

    
    <transition name="fade">
      <div v-if="showLocations" class="px-6 pb-20 max-w-6xl mx-auto">
        <h2 class="text-2xl font-bold text-purple-700 mb-6">قائمة القاعات</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
          <div
            v-for="(location, index) in locations"
            :key="index"
            class="bg-white border rounded-lg shadow hover:shadow-lg transition p-5"
          >
            <h3 class="text-lg font-semibold text-gray-800 mb-3">{{ location.name }}</h3>
            <a
              :href="location.url"
              target="_blank"
              class="inline-block bg-purple-600 text-white px-4 py-2 rounded hover:bg-purple-700 text-sm"
            >
              مشاهدة الفيديو
            </a>
          </div>
        </div>
      </div>
    </transition>

    
    <transition name="fade">
      <div v-if="showMaps" class="px-6 pb-20 max-w-6xl mx-auto">
        <h2 class="text-2xl font-bold text-purple-700 mb-6">خرائط الجامعة الرسمية</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
          <div
            v-for="(map, index) in maps"
            :key="index"
            class="bg-white border rounded-lg shadow hover:shadow-lg transition p-5 text-center"
          >
            <div class="text-3xl text-purple-600 mb-3">
              <i class="fa-solid fa-map"></i>
            </div>
            <h3 class="text-lg font-semibold text-gray-800 mb-2">{{ map.title }}</h3>
            <p class="text-gray-600 text-sm mb-4">{{ map.description }}</p>
            <a
              :href="map.url"
              target="_blank"
              class="inline-block bg-purple-600 text-white px-5 py-2 rounded hover:bg-purple-700 text-sm"
            >
              عرض الخريطة
            </a>
          </div>
        </div>
      </div>
    </transition>
  </MainLayout>
</template>

<style>
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s ease;
}
.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>