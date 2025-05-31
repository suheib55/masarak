<script setup>
import { ref, computed } from 'vue'
import MainLayout from '@/Layouts/MainLayout.vue'
import { usePage, router } from '@inertiajs/vue3'

const user = usePage().props.user

const role = computed(() => {
  if (!user) return 'guest'
  if (user.email === 'smhmwdabwalshykh@gmail.com') return 'admin'
  if (user.email.endsWith('@just.edu.jo')) return 'instructor'
  if (user.email.endsWith('@cit.just.edu.jo')) return 'student'
  return 'guest'
})

const events = ref(usePage().props.events || [])
const announcedLinks = ref(usePage().props.announcedLinks || [])

const showAddEventModal = ref(false)
const showAnnounceEventModal = ref(false)
const showRegisterModal = ref(false)

const newEvent = ref({
  title: '',
  date: '',
  location: '',
  note: '',
  imagePreview: '',
  imageFile: null,
})

const selectedEvent = ref(null)
const registration = ref({
  name: '',
  email: '',
  studentId: ''
})

const announceLinkInput = ref('')

function handleImageUpload(event) {
  const file = event.target.files[0]
  if (file) {
    newEvent.value.imagePreview = URL.createObjectURL(file)
    newEvent.value.imageFile = file
  }
}

function addEvent() {
  if (!newEvent.value.title || !newEvent.value.date) return

  const formData = new FormData()
  formData.append('title', newEvent.value.title)
  formData.append('date', newEvent.value.date)
  formData.append('location', newEvent.value.location)
  formData.append('note', newEvent.value.note)

  if (newEvent.value.imageFile) {
    formData.append('image', newEvent.value.imageFile)
  }

  router.post('/events', formData, {
    preserveScroll: true,
    onSuccess: () => {
      showAddEventModal.value = false
      newEvent.value = {
        title: '',
        date: '',
        location: '',
        note: '',
        imagePreview: '',
        imageFile: null
      }
    },
    onError: () => {
      alert('Error adding event')
    }
  })
}

function announceEvent(link) {
  if (link.trim() === '') return

  router.post('/announce-link', { link }, {
    preserveScroll: true,
    onSuccess: () => {
      announcedLinks.value.push(link)
      announceLinkInput.value = ''
      showAnnounceEventModal.value = false
    },
    onError: () => {
      alert('Error announcing link')
    }
  })
}

function openRegisterModal(event) {
  selectedEvent.value = event
  showRegisterModal.value = true
  registration.value = { name: '', email: '', studentId: '' }
}

function submitRegistration() {
  router.post('/event-register', {
    event_id: selectedEvent.value.id,
    name: registration.value.name,
    email: registration.value.email,
    student_id: registration.value.studentId
  }, {
    preserveScroll: true,
    onSuccess: () => {
      showRegisterModal.value = false
      alert('Registration successful!')
    },
    onError: () => {
      alert('Error registering for event')
    }
  })
}
</script>

<template>
  <MainLayout>
    <div class="p-6 max-w-5xl mx-auto">
      <h1 class="text-3xl font-bold text-purple-800 mb-6 text-center">Events</h1>

      <div v-if="role === 'admin' || role === 'instructor'" class="mb-6 flex gap-4 justify-center">
        <button @click="showAddEventModal = true"
                class="bg-purple-700 text-white px-4 py-2 rounded hover:bg-purple-600">
          Add Event
        </button>
        <button @click="showAnnounceEventModal = true"
                class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-500">
          Announce Event
        </button>
      </div>

      <div class="grid gap-6 md:grid-cols-2">
        <div v-for="event in events" :key="event.id"
             class="bg-white border border-gray-200 rounded-lg shadow hover:shadow-md transition overflow-hidden flex flex-col">
          <img
            :src="event.image_url || 'https://data.textstudio.com/output/sample/animated/6/3/2/5/event-3-5236.gif'"
            alt="Event Image"
            class="w-full h-48 object-cover"
          />
          <div class="p-5 flex flex-col flex-grow">
            <h2 class="text-xl font-semibold text-purple-700 mb-2">{{ event.title }}</h2>
            <p class="text-gray-600 mb-1"><strong>Date:</strong> {{ event.date }}</p>
            <p class="text-gray-600 mb-1"><strong>Location:</strong> {{ event.location }}</p>
            <p class="text-gray-700 mb-2">{{ event.note }}</p>
            
            <div class="mt-2 pt-2 border-t border-gray-100 text-sm text-gray-500">
              <p>Added by: {{ event.created_by_name }}</p>
              <p>{{ event.created_by_email }}</p>
            </div>

            <button v-if="role === 'student'" @click="openRegisterModal(event)"
                    class="mt-4 bg-green-600 text-white px-4 py-2 rounded hover:bg-green-500 transition w-full">
              Register
            </button>
          </div>
        </div>
      </div>

      <div v-if="!events.length" class="text-center text-gray-500 mt-10">No events yet.</div>

      <div v-if="announcedLinks.length" class="mt-12 max-w-2xl mx-auto">
        <h2 class="text-2xl font-bold text-blue-900 mb-6 text-center">Announced Links</h2>
        <div class="space-y-4">
          <div v-for="(link, index) in announcedLinks" :key="index"
               class="flex items-center justify-between bg-white border border-gray-200 shadow-sm hover:shadow-md transition p-4 rounded-lg">
            <div class="flex items-center gap-3 max-w-[85%]">
              <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                <path d="M12.293 7.293a1 1 0 011.414 1.414l-3.586 3.586a1 1 0 01-1.414-1.414l3.586-3.586z" />
                <path d="M7.293 12.707a1 1 0 01-1.414-1.414l3.586-3.586a1 1 0 011.414 1.414l-3.586 3.586z" />
              </svg>
              <a :href="link" target="_blank" class="text-blue-700 font-medium hover:underline truncate">
                {{ link }}
              </a>
            </div>
            <span class="text-sm text-gray-400">#{{ index + 1 }}</span>
          </div>
        </div>
      </div>

      <!-- Add Event Modal -->
      <div v-if="showAddEventModal"
           class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50">
        <div class="bg-white rounded-lg p-6 w-full max-w-md">
          <h2 class="text-xl font-bold text-purple-700 mb-4 text-center">Add New Event</h2>
          <input v-model="newEvent.title" placeholder="Title"
                 class="w-full mb-3 px-4 py-2 border rounded" />
          <input v-model="newEvent.date" type="date" placeholder="Date"
                 class="w-full mb-3 px-4 py-2 border rounded" />
          <input v-model="newEvent.location" placeholder="Location"
                 class="w-full mb-3 px-4 py-2 border rounded" />
          <input type="file" accept="image/*" @change="handleImageUpload"
                 class="w-full mb-3 px-4 py-2 border rounded" />
          <textarea v-model="newEvent.note" placeholder="Note"
                    class="w-full mb-3 px-4 py-2 border rounded"></textarea>
          <div class="flex justify-end gap-2">
            <button @click="showAddEventModal = false"
                    class="px-4 py-2 bg-gray-300 rounded">Cancel</button>
            <button @click="addEvent"
                    class="px-4 py-2 bg-purple-700 text-white rounded">Save Event</button>
          </div>
        </div>
      </div>

      <!-- Announce Event Modal -->
      <div v-if="showAnnounceEventModal"
           class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50">
        <div class="bg-white rounded-lg p-6 w-full max-w-md">
          <h2 class="text-xl font-bold text-blue-700 mb-4 text-center">Announce Event</h2>
          <input type="text" v-model="announceLinkInput" placeholder="Paste event link..."
                 class="w-full mb-4 px-4 py-2 border rounded" />
          <div class="flex justify-end gap-2">
            <button @click="showAnnounceEventModal = false"
                    class="px-4 py-2 bg-gray-300 rounded">Cancel</button>
            <button @click="announceEvent(announceLinkInput)"
                    class="px-4 py-2 bg-blue-600 text-white rounded">Announce</button>
          </div>
        </div>
      </div>

      <!-- Registration Modal -->
      <div v-if="showRegisterModal"
           class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50">
        <div class="bg-white rounded-lg p-6 w-full max-w-md">
          <h2 class="text-xl font-bold text-green-700 mb-4 text-center">
            Register for {{ selectedEvent.title }}
          </h2>
          <input v-model="registration.name" placeholder="Your Name"
                 class="w-full mb-3 px-4 py-2 border rounded" />
          <input v-model="registration.email" placeholder="University Email"
                 class="w-full mb-3 px-4 py-2 border rounded" />
          <input v-model="registration.studentId" placeholder="Student ID"
                 class="w-full mb-4 px-4 py-2 border rounded" />
          <div class="flex justify-end gap-2">
            <button @click="showRegisterModal = false"
                    class="px-4 py-2 bg-gray-300 rounded">Cancel</button>
            <button @click="submitRegistration"
                    class="px-4 py-2 bg-green-600 text-white rounded">Register</button>
          </div>
        </div>
      </div>
    </div>
  </MainLayout>
</template>