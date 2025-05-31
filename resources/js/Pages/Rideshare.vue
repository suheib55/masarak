<script setup>
import MainLayout from '@/Layouts/MainLayout.vue'
import { ref, computed } from 'vue'
import { usePage, router } from '@inertiajs/vue3'

const page = usePage()
const authUser = page.props.auth?.user || {}

const props = defineProps({
  rides: Array
})

const cities = {
  Irbid: [
    'Al-Husn',
    'Culture Roundabout',
    'Eastern Neighborhood',
    'University Street',
    'Al-Sareeh',
    'Kufr Yuba',
    'Idoun'
  ],
  Zarqa: [
    'New Zarqa',
    'Downtown',
    'Jabal Tareq',
    'Ramzi District',
    'Prince Mohammad City',
    'Street 36',
    'Al-Madina District'
  ],
  Amman: [
    'Abdali',
    'Swifeih',
    'Shmeisani',
    'Rabyeh',
    'Jabal Al-Hussein',
    'Bayader',
    'Marj Al-Hamam',
    'Tabarbour'
  ]
}

const selectedCity = ref('')
const selectedArea = ref('')
const showResults = ref(false)

const showJoinModal = ref(false)
const showDetailsModal = ref(false)
const selectedRide = ref(null)
const passengerPhone = ref('')

const filteredRides = computed(() =>
  Array.isArray(props.rides)
    ? props.rides.filter(
        ride =>
          ride.city.toLowerCase() === selectedCity.value.toLowerCase() &&
          ride.area.toLowerCase() === selectedArea.value.toLowerCase()
      )
    : []
)

function searchRides() {
  showResults.value = true
}

function openJoinModal(ride) {
  selectedRide.value = ride
  passengerPhone.value = ''
  showJoinModal.value = true
}

function openDetailsModal(ride) {
  selectedRide.value = ride
  showDetailsModal.value = true
}

function closeModals() {
  showJoinModal.value = false
  showDetailsModal.value = false
  selectedRide.value = null
}

function confirmJoin() {
  if (!authUser.name || !passengerPhone.value || !selectedRide.value) return

  const formData = new FormData()
  formData.append('ride_id', selectedRide.value.id)
  formData.append('passenger_name', authUser.name)
  formData.append('phone', passengerPhone.value)

  router.post('/rides/join', formData, {
    onSuccess: () => {
      closeModals()
      router.reload({ only: ['rides'] })
    },
    onError: () => {
      alert('Error joining ride')
    }
  })
}

function removePassenger(passengerId) {
  if (!confirm('Are you sure you want to remove this passenger?')) return

  router.delete(`/rides/${passengerId}/passenger`, {
    onSuccess: () => router.reload({ only: ['rides'] }),
    onError: () => alert('Failed to remove passenger')
  })
}

function deleteRide(rideId) {
  if (!confirm('Are you sure you want to delete this ride?')) return

  router.delete(`/rides/${rideId}`, {
    onSuccess: () => {
      closeModals()
      router.reload({ only: ['rides'] })
    },
    onError: () => alert('Failed to delete ride')
  })
}

const showCreateModal = ref(false)
const newCity = ref('')
const newArea = ref('')
const newTime = ref('')
const newPhone = ref('')
const newSeats = ref(4)

const availableAreas = computed(() => cities[newCity.value] || [])

function submitNewRide() {
  const formData = new FormData()
  formData.append('city', newCity.value)
  formData.append('area', newArea.value)
  formData.append('time', newTime.value)
  formData.append('seats', newSeats.value)
  formData.append('phone', newPhone.value)

  router.post('/rides', formData, {
    onSuccess: () => {
      showCreateModal.value = false
      clearNewRideForm()
      router.reload({ only: ['rides'] })
    },
    onError: () => {
      alert('Error creating ride')
    }
  })
}

function clearNewRideForm() {
  newCity.value = ''
  newArea.value = ''
  newTime.value = ''
  newSeats.value = 4
  newPhone.value = ''
}
</script>

<template>
  <MainLayout>
    <div class="p-6 max-w-xl mx-auto">
      <h1 class="text-3xl font-bold text-purple-800 mb-6 text-center">Find a Rideshare</h1>

      <div class="text-center mb-6">
        <button @click="showCreateModal = true"
                class="bg-green-600 text-white px-6 py-2 rounded hover:bg-green-500">
          + Create Ride
        </button>
      </div>

      <div class="mb-4">
        <label class="block mb-1 font-medium text-gray-700">Select City:</label>
        <select v-model="selectedCity" class="w-full border rounded px-4 py-2">
          <option value="">-- Choose a city --</option>
          <option v-for="(areas, city) in cities" :key="city" :value="city">{{ city }}</option>
        </select>
      </div>

      <div class="mb-4" v-if="selectedCity">
        <label class="block mb-1 font-medium text-gray-700">Select Area:</label>
        <select v-model="selectedArea" class="w-full border rounded px-4 py-2">
          <option value="">-- Choose an area --</option>
          <option v-for="area in cities[selectedCity]" :key="area" :value="area">{{ area }}</option>
        </select>
      </div>

      <div class="text-center mb-6" v-if="selectedCity && selectedArea">
        <button @click="searchRides"
                class="bg-purple-700 text-white px-6 py-2 rounded hover:bg-purple-600">
          Search for Rides
        </button>
      </div>

      <div v-if="showResults">
        <h2 class="text-xl font-semibold text-purple-800 mb-4 text-center">Available Rides</h2>

        <div v-if="filteredRides.length" class="space-y-4">
          <div v-for="ride in filteredRides" :key="ride.id" class="bg-white border p-4 rounded shadow">
            <p class="text-lg font-semibold text-purple-700">Driver: {{ ride.user?.name || 'Unknown' }}</p>
            <p class="text-gray-700">From: {{ ride.area }}, {{ ride.city }}</p>
            <p class="text-gray-700">Time: {{ ride.time }}</p>
            <p class="text-gray-700">Phone: {{ ride.phone }}</p>
            <p class="text-gray-700 mb-2">
              Available Seats: 
              <span :class="{'text-red-600 font-semibold': ride.seats === 0}">
                {{ ride.seats === 0 ? 'Full' : ride.seats }}
              </span>
            </p>

            <div class="flex gap-3">
              <button v-if="ride.seats > 0"
                      @click="openJoinModal(ride)"
                      class="border border-green-600 text-green-600 px-4 py-1 rounded hover:bg-green-100 text-sm">
                Join
              </button>
              <span v-else class="text-red-600 font-semibold text-sm">Full</span>

              <button @click="openDetailsModal(ride)"
                      class="bg-gray-200 text-sm px-4 py-1 rounded hover:bg-gray-300">
                Show Details
              </button>
            </div>
          </div>
        </div>

        <div v-else class="text-center text-gray-500 mt-4">
          No rides found for selected area.
        </div>
      </div>

      <!-- Join Modal -->
      <div v-if="showJoinModal" class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 w-full max-w-md shadow-lg">
          <h2 class="text-xl font-bold text-purple-700 mb-4 text-center">
            Join Ride with {{ selectedRide.user?.name }}
          </h2>

          <input v-model="passengerPhone" type="text" placeholder="Your Phone Number"
                 class="w-full mb-4 px-4 py-2 border rounded" />

          <div class="flex justify-end gap-3">
            <button @click="closeModals"
                    class="bg-gray-300 px-4 py-2 rounded hover:bg-gray-400 text-gray-800">
              Cancel
            </button>
            <button @click="confirmJoin"
                    :disabled="!passengerPhone"
                    class="bg-purple-700 text-white px-4 py-2 rounded hover:bg-purple-600 disabled:opacity-50">
              Confirm
            </button>
          </div>
        </div>
      </div>

      <!-- Show Details Modal -->
      <div v-if="showDetailsModal" class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 w-full max-w-lg shadow-lg">
          <h2 class="text-xl font-bold text-purple-700 mb-4 text-center">Ride Details</h2>

          <p><strong>Driver:</strong> {{ selectedRide.user?.name }}</p>
          <p><strong>City:</strong> {{ selectedRide.city }}</p>
          <p><strong>Area:</strong> {{ selectedRide.area }}</p>
          <p><strong>Time:</strong> {{ selectedRide.time }}</p>
          <p><strong>Phone:</strong> {{ selectedRide.phone }}</p>

          <div class="mt-4">
            <h3 class="font-semibold text-gray-800 mb-2">Passengers:</h3>
            <ul v-if="selectedRide.passengers && selectedRide.passengers.length">
              <li v-for="p in selectedRide.passengers" :key="p.id" class="text-sm text-gray-700 flex justify-between items-center">
                <span>• {{ p.passenger_name }} ({{ p.phone }})</span>
                <button
                  v-if="authUser.id === selectedRide.user_id"
                  @click="removePassenger(p.id)"
                  class="text-red-600 hover:text-red-800 text-xs ml-2"
                >
                  Remove
                </button>
              </li>
            </ul>
            <p v-else class="text-sm text-gray-500">No passengers yet.</p>
          </div>

          <div class="flex justify-between items-center mt-6">
            <button v-if="authUser.id === selectedRide.user_id"
                    @click="deleteRide(selectedRide.id)"
                    class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-500">
              Delete Ride
            </button>
            <button @click="closeModals"
                    class="bg-purple-700 text-white px-6 py-2 rounded hover:bg-purple-600">
              Close
            </button>
          </div>
        </div>
      </div>

      <!-- Create Ride Modal -->
      <div v-if="showCreateModal" class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 w-full max-w-md shadow-lg">
          <h2 class="text-xl font-bold text-green-700 mb-4 text-center">Create New Ride</h2>

          <input v-model="newPhone" type="text" placeholder="Your Phone Number"
                 class="w-full mb-3 px-4 py-2 border rounded" />

          <select v-model="newCity" class="w-full mb-3 px-4 py-2 border rounded">
            <option value="">Select City</option>
            <option v-for="(areas, city) in cities" :key="city" :value="city">{{ city }}</option>
          </select>

          <select v-model="newArea" class="w-full mb-3 px-4 py-2 border rounded" :disabled="!newCity">
            <option value="">Select Area</option>
            <option v-for="area in availableAreas" :key="area" :value="area">{{ area }}</option>
          </select>

          <input v-model="newTime" type="time"
                 class="w-full mb-3 px-4 py-2 border rounded" />

          <input v-model="newSeats" type="number" min="1" max="10" placeholder="Seats"
                 class="w-full mb-4 px-4 py-2 border rounded" />

          <div class="flex justify-end gap-3">
            <button @click="showCreateModal = false"
                    class="bg-gray-300 px-4 py-2 rounded hover:bg-gray-400 text-gray-800">
              Cancel
            </button>
            <button @click="submitNewRide"
                    class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-500">
              Create
            </button>
          </div>
        </div>
      </div>
    </div>
  </MainLayout>
</template>