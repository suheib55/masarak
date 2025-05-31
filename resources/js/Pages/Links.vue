<script setup>
import MainLayout from '@/Layouts/MainLayout.vue'

const links = [
  {
    title: 'JUST Official Website',
    url: 'https://www.just.edu.jo/Pages/Default.aspx',
    img: 'https://play-lh.googleusercontent.com/BG_7cYxV59O9i3hjU0USOJV4kpHJOEs_a4R19-UHgtbqPyNjWzSBFEEMkX9y7GM_sEYH'
  },
  {
    title: 'JUST eLearning',
    url: 'https://elearn.just.edu.jo',
    img: 'https://tse1.mm.bing.net/th/id/OIP.hn5k1uGhtw9mG80mJdh3JgAAAA?rs=1&pid=ImgDetMain/150?text=eLearning'
  },
  {
    title: 'Moodle Platform',
    url: 'https://moodle.org',
    img: 'https://th.bing.com/th/id/R.b5984832c80eab7df1ca08ebca9eb1ee?rik=7IQqyMmjtY%2fTsg&riu=http%3a%2f%2fthebridgelifeinthemix.info%2fwp-content%2fuploads%2f2018%2f01%2fmoodle-2.jpg&ehk=eWldKiBIBk3nARvUNCdQ309TmXEZxvDmZNqXFi%2fIyKI%3d&risl=&pid=ImgRaw&r=0/150?text=Moodle'
  },
  {
    title: 'Outlook Mail',
    url: 'https://outlook.live.com',
    img: 'https://tse2.mm.bing.net/th/id/OIP.TSoTouGeKNKuRKWwh0z1gAHaE8?rs=1&pid=ImgDetMain/150?text=Outlook'
  },
  {
    title: 'Student Services',
    url: 'https://services.just.edu.jo/stuservices/Student/Reg/StudentCard.aspx',
    img: 'https://static.vecteezy.com/system/resources/previews/020/934/164/non_2x/graduation-icon-for-your-website-design-logo-app-ui-free-vector.jpg'
  },
  {
    title: 'LinkedIn',
    url: 'https://www.linkedin.com',
    img: 'https://images.unsplash.com/photo-1704382002666-5dc4fbb522c0?fm=jpg&q=60&w=3000&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MTZ8fGxpbmtlZGluJTIwcHJvZmlsZXxlbnwwfHwwfHx8MA%3D%3D'
  },
  {
    title: 'JUST App (iOS)',
    url: 'https://apps.apple.com/us/app/just-students/id1115178610',
    img: 'https://is1-ssl.mzstatic.com/image/thumb/Purple116/v4/e4/46/e8/e446e82f-7da6-2553-2669-ad11b7fac512/AppIcon-0-0-1x_U007emarketing-0-7-0-0-85-220.png/1200x600wa.png'
  }
]
</script>

<template>
  <MainLayout>
    <div class="p-6 max-w-7xl mx-auto">
      <h1 class="text-3xl font-bold text-purple-800 mb-10 text-center">Useful Links</h1>

      <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-6">
        <div
          v-for="link in links"
          :key="link.title"
          class="rounded-xl overflow-hidden shadow-lg hover:shadow-2xl transition duration-300"
        >
          <a :href="link.url" target="_blank" class="block">
            <img
              :src="link.img"
              alt="Link Image"
              class="w-full h-48 object-cover"
            />
            <div class="bg-purple-100 text-purple-800 text-center py-3 font-medium text-sm">
              {{ link.title }}
            </div>
          </a>
        </div>
      </div>
    </div>
  </MainLayout>
</template>