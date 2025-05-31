<script setup>
import MainLayout from '@/Layouts/MainLayout.vue'
import { ref } from 'vue'
import { usePage, router } from '@inertiajs/vue3'

const announcements = ref(usePage().props.announcements || [])
const title = ref('')
const content = ref('')
const showForm = ref(false)
const loading = ref(false)
const editingId = ref(null)

const userEmail = usePage().props.auth.user.email
const isProfessor = userEmail.includes('@just.edu.jo')

function submitAnnouncement() {
  if (!title.value || !content.value) {
    alert('Please fill in all fields')
    return
  }

  loading.value = true
  const data = { title: title.value, content: content.value }

  if (editingId.value) {
    router.put(`/announcements/${editingId.value}`, data, {
      onFinish: () => resetForm()
    })
  } else {
    router.post('/announcements', data, {
      onFinish: () => resetForm()
    })
  }
}

function resetForm() {
  title.value = ''
  content.value = ''
  editingId.value = null
  showForm.value = false
  loading.value = false
}

function startEdit(announcement) {
  title.value = announcement.title
  content.value = announcement.content
  editingId.value = announcement.id
  showForm.value = true
}

function deleteAnnouncement(id) {
  if (confirm('Are you sure you want to delete this announcement?')) {
    router.delete(`/announcements/${id}`)
  }
}
</script>

<template>
  <MainLayout>
    <div class="max-w-4xl mx-auto p-6">
      <h1 class="text-3xl font-bold text-purple-800 mb-6 text-center">University Announcements</h1>

      <!-- Form -->
      <div v-if="isProfessor" class="mb-8 bg-white border rounded-lg p-5 shadow-sm">
        <button @click="showForm = !showForm"
                class="bg-purple-700 text-white px-4 py-2 rounded hover:bg-purple-600 mb-4">
          {{ showForm ? 'Cancel' : (editingId ? 'Cancel Edit' : '+ New Announcement') }}
        </button>

        <div v-if="showForm">
          <input v-model="title" type="text" placeholder="Title"
                 class="w-full border px-4 py-2 rounded mb-3" />
          <textarea v-model="content" rows="4" placeholder="Content"
                    class="w-full border px-4 py-2 rounded mb-3"></textarea>
          <button @click="submitAnnouncement"
                  class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-500"
                  :disabled="loading">
            {{ loading ? 'Saving...' : (editingId ? 'Update Announcement' : 'Post Announcement') }}
          </button>
        </div>
      </div>

      <!-- List -->
      <div v-if="announcements.length" class="space-y-4">
        <div v-for="a in announcements" :key="a.id"
             class="bg-white border rounded-lg p-5 shadow-sm hover:shadow transition">
          <div class="flex justify-between items-center mb-2">
            <h2 class="text-xl font-semibold text-purple-800">{{ a.title }}</h2>
            <span class="text-xs text-gray-500">
              {{ a.created_at ? new Date(a.created_at).toLocaleDateString() : 'No date available' }}
            </span>
          </div>
          <p class="text-gray-700">{{ a.content }}</p>
          <p class="text-sm text-gray-500 mt-2 italic">Posted by: {{ a.posted_by }}</p>

          <div v-if="a.posted_by === userEmail" class="flex gap-4 mt-3">
            <button @click="startEdit(a)" class="text-blue-600 text-sm hover:underline">Edit</button>
            <button @click="deleteAnnouncement(a.id)" class="text-red-600 text-sm hover:underline">Delete</button>
          </div>
        </div>
      </div>

      <div v-else class="text-center text-gray-500 mt-10">No announcements yet.</div>
    </div>
  </MainLayout>
</template>