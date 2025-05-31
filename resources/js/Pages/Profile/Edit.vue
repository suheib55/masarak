<script setup>
import MainLayout from '@/Layouts/MainLayout.vue'
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue'
import { Head, usePage, router } from '@inertiajs/vue3'
import { ref } from 'vue'

const user = usePage().props.auth.user

const name = ref(user?.name || '')
const email = ref(user?.email || '')
const faculty = ref(user.faculty)
const major = ref(user.major)
const image = ref(null)
const preview = ref(user.profile_image ? `/storage/${user.profile_image}` : '/default-avatar.png')
const successMessage = ref('')
const loading = ref(false)

function handleImageUpload(e) {
  const file = e.target.files[0]
  if (file) {
    image.value = file
    preview.value = URL.createObjectURL(file)
  }
}

function updateProfile() {
  if (!image.value) return

  loading.value = true
  const formData = new FormData()
  formData.append('image', image.value)


  router.post('/profile', formData, {
    preserveScroll: true,
    forceFormData: true,
    onSuccess: () => {
      successMessage.value = 'Profile image updated successfully.'
      loading.value = false
      setTimeout(() => (successMessage.value = ''), 3000)
    },
    onError: (errors) => {
      console.error(errors)
      loading.value = false
    }
  })
}
</script>

<template>
  <Head title="My Profile" />
<MainLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-purple-800 dark:text-purple-300 leading-tight">My Profile</h2>
    </template>

    <div class="py-12">
      <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <div class="bg-white dark:bg-gray-800 p-6 shadow rounded-lg transition">

          <div class="flex justify-center mb-6">
            <div class="relative w-36 h-36">
              <img
                :src="preview"
               
                class="w-36 h-36 rounded-full object-cover border-4 border-purple-300 shadow"
              />
              <input
                type="file"
                accept="image/*"
                @change="handleImageUpload"
                class="absolute inset-0 opacity-0 cursor-pointer"
              />
            </div>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label class="block font-medium text-gray-700 dark:text-gray-300 mb-1">Name</label>
              <input v-model="name" disabled type="text" class="w-full border rounded px-3 py-2 dark:bg-gray-700 dark:text-white" />
            </div>

            <div>
              <label class="block font-medium text-gray-700 dark:text-gray-300 mb-1">Email</label>
              <input v-model="email" disabled type="email" class="w-full border rounded px-3 py-2 dark:bg-gray-700 dark:text-white" />
            </div>

            <div>
              <label class="block font-medium text-gray-700 dark:text-gray-300 mb-1">College</label>
              <input v-model="faculty" disabled type="text" class="w-full border rounded px-3 py-2 dark:bg-gray-700 dark:text-white" />
            </div>

            <div>
              <label class="block font-medium text-gray-700 dark:text-gray-300 mb-1">Major</label>
              <input v-model="major" disabled type="text" class="w-full border rounded px-3 py-2 dark:bg-gray-700 dark:text-white" />
            </div>
          </div>

          <button
            @click="updateProfile"
            :disabled="loading || !image"
            class="mt-6 bg-purple-700 hover:bg-purple-800 text-white px-6 py-2 rounded flex items-center gap-2"
          >
            <svg v-if="loading" class="animate-spin h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path
                class="opacity-75"
                fill="currentColor"
                d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"
              ></path>
            </svg>
            <span>Save Changes</span>
          </button>

          <transition name="fade">
            <div v-if="successMessage" class="mt-4 bg-green-100 text-green-800 px-4 py-2 rounded shadow text-sm">
              {{ successMessage }}
            </div>
          </transition>
        </div>

        <div class="bg-white dark:bg-gray-800 p-6 shadow rounded-lg transition">
          <UpdatePasswordForm class="max-w-xl" />
        </div>
      </div>
    </div>
  </MainLayout>
</template>

<style scoped>
.fade-enter-active, .fade-leave-active {
  transition: opacity 0.5s;
}
.fade-enter-from, .fade-leave-to {
  opacity: 0;
}

</style>
