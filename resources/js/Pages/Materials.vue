<script setup>
import MainLayout from '@/Layouts/MainLayout.vue'
import { ref, computed } from 'vue'
import { usePage, router } from '@inertiajs/vue3'

defineProps(['materials', 'filters'])

const page = usePage()
const uploadedMaterials = ref(page.props.materials || [])
const authUserId = ref(page.props.auth_user_id)
const initialFilters = page.props.filters || JSON.parse(localStorage.getItem('materialSearch')) || {}

const user = usePage().props.auth.user

const role = computed(() => {
  if (!user) return 'guest'
  if (user.email === 'smhmwdabwalshykh@gmail.com') return 'admin'
  if (user.email.endsWith('@just.edu.jo')) return 'instructor'
  if (user.email.endsWith('@cit.just.edu.jo')) return 'student'
  return 'guest'
})

const faculties = {
  'كلية الطب': ['دكتور في الطب', 'الصحة العامة', 'الإدارة والسياسات الصحية'],
  'كلية العلوم الطبية التطبيقية': [
    'الإسعافات الأولية والطوارئ', 'تكنولوجيا الأشعة', 'العلوم الطبية المخبرية',
    'تكنولوجيا صناعة الأسنان', 'علوم طب الأسنان والمساندة', 'البصريات',
    'العلاج الطبيعي', 'العلاج الوظيفي', 'السمع والنطق', 'علوم التأهيل السريري',
    'علاج الأسنان', 'تكنولوجيا التخدير'
  ],
  'كلية الهندسة': [
    'الهندسة الكيميائية', 'الهندسة المدنية', 'الهندسة الكهربائية',
    'الهندسة الميكانيكية', 'الهندسة الطبية الحيوية', 'الهندسة الصناعية',
    'هندسة الطيران', 'تكنولوجيا صيانة الطائرات', 'دبلوماسية المياه', 'الهندسة النووية'
  ],
  'كلية الصيدلة': ['الصيدلة', 'دكتور صيدلة', 'التصنيع الدوائي والبيولوجي'],
  'كلية التمريض': ['التمريض'],
  'كلية طب الأسنان': ['طب وجراحة الأسنان'],
  'كلية تكنولوجيا معلومات الحاسوب': [
    'هندسة الحاسوب', 'علوم الحاسوب', 'نظم المعلومات الحاسوبية',
    'هندسة وأمن شبكات الحاسوب', 'هندسة البرمجيات', 'الأمن السيبراني',
    'علم البيانات', 'الذكاء الاصطناعي', 'إنترنت الأشياء', 'تصميم وتطوير ألعاب الحاسوب'
  ],
  'الطب البيطري': ['دكتور في الطب البيطري'],
  'كلية الزراعة': ['الإنتاج الحيواني', 'الإنتاج النباتي', 'التغذية وتكنولوجيا الغذاء', 'الموارد الطبيعية والبيئية'],
  'شعبة العلوم العسكرية': ['علوم عسكرية'],
  'كلية العلوم والآداب': [
    'اللغة العربية', 'اللغة الإنجليزية واللغويات', 'الرياضيات',
    'الكيمياء', 'الفيزياء', 'العلوم الحياتية التطبيقية', 'التقانات الحيوية والهندسة'
  ],
  'مركز اللغات': ['مركز اللغات'],
  'كلية العمارة والتصميم': [
    'التخطيط والدراسات الحضرية', 'العمارة', 'تخطيط وتصميم المدن',
    'التصميم والتواصل البصري', 'هندسة التخطيط الحضري والبيئي'
  ],
  'معهد النانوتكنولوجي': ['هندسة وعلوم النانو', 'علوم المياه والطاقة والغذاء']
}

const selectedFaculty = ref(initialFilters.college || '')
const selectedMajor = ref(initialFilters.major || '')
const searchQuery = ref(initialFilters.query || '')
const showResults = ref(false)
const isLoading = ref(false)

const title = ref('')
const note = ref('')
const file = ref(null)
const showUploadForm = ref(false)

const majors = computed(() => faculties[selectedFaculty.value] || [])

function formatDate(dateStr) {
  const date = new Date(dateStr)
  return date.toLocaleDateString()
}

function handleFileUpload(event) {
  const selected = event.target.files[0]
  if (selected && [
    'application/pdf', 'application/msword',
    'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
    'application/vnd.ms-powerpoint',
    'application/vnd.openxmlformats-officedocument.presentationml.presentation',
    'image/png', 'image/jpeg'
  ].includes(selected.type)) {
    file.value = selected
  } else {
    alert('Allowed file types: PDF, Word, PowerPoint, JPG, PNG')
  }
}

function getFileIcon(type) {
  if (type.includes('pdf')) return '📄'
  if (type.includes('word')) return '📝'
  if (type.includes('powerpoint')) return '📊'
  if (type.includes('image')) return '🖼️'
  return '📁'
}

function searchMaterials() {
  if (!searchQuery.value && !selectedFaculty.value && !selectedMajor.value) {
    alert('Please enter a search query or select filters.')
    return
  }

  isLoading.value = true

  localStorage.setItem('materialSearch', JSON.stringify({
    query: searchQuery.value,
    college: selectedFaculty.value,
    major: selectedMajor.value
  }))

  router.get('/materials', {
    college: selectedFaculty.value,
    major: selectedMajor.value,
    query: searchQuery.value
  }, {
    preserveState: true,
    replace: true,
    onSuccess: (res) => {
      uploadedMaterials.value = res.props.materials || []
      showResults.value = true
      isLoading.value = false
    },
    onError: () => {
      isLoading.value = false
    }
  })
}

function clearSearch() {
  searchQuery.value = ''
  selectedFaculty.value = ''
  selectedMajor.value = ''
  showResults.value = false
  localStorage.removeItem('materialSearch')
}

function addMaterial() {
  if (selectedFaculty.value && selectedMajor.value && file.value && title.value) {
    const formData = new FormData()
    formData.append('faculty', selectedFaculty.value)
    formData.append('major', selectedMajor.value)
    formData.append('title', title.value)
    formData.append('note', note.value)
    formData.append('file', file.value)

    isLoading.value = true

    router.post('/materials', formData, {
      forceFormData: true,
      onSuccess: () => {
        isLoading.value = false
        file.value = null
        note.value = ''
        title.value = ''
        document.getElementById('fileInput').value = ''
        selectedFaculty.value = ''
        selectedMajor.value = ''
        showUploadForm.value = false
        window.location.reload()
      },
      onError: () => {
        isLoading.value = false
        alert('Failed to upload. Check your input.')
      }
    })
  } else {
    alert('Please complete all fields and upload a valid file.')
  }
}

function deleteMaterial(id) {
  if (confirm('Are you sure you want to delete this material?')) {
    isLoading.value = true
    router.delete(`/materials/${id}`, {
      onSuccess: () => {
        isLoading.value = false
        uploadedMaterials.value = uploadedMaterials.value.filter(m => m.id !== id)
      },
      onError: () => {
        isLoading.value = false
        alert('Failed to delete material.')
      }
    })
  }
}
</script>

<template>
  <MainLayout>
    <div class="p-6 max-w-5xl mx-auto space-y-8">

      <!-- Search -->
      <div class="bg-white border rounded-lg p-6 shadow">
        <h2 class="text-xl font-bold text-purple-800 mb-4">🔍 Search Materials</h2>

        <input type="text" v-model="searchQuery" class="w-full border px-4 py-2 rounded mb-4"
               placeholder="e.g., physics, summary, final..." />

        <div class="grid gap-4 md:grid-cols-2 mb-4">
          <select v-model="selectedFaculty" class="w-full border px-4 py-2 rounded">
            <option value="">Select Faculty</option>
            <option v-for="(majors, faculty) in faculties" :key="faculty" :value="faculty">{{ faculty }}</option>
          </select>
          <select v-model="selectedMajor" class="w-full border px-4 py-2 rounded">
            <option value="">Select Major</option>
            <option v-for="major in majors" :key="major" :value="major">{{ major }}</option>
          </select>
        </div>

        <div class="flex justify-between items-center">
          <button @click="searchMaterials" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-500">
            {{ isLoading ? 'Searching...' : 'Search' }}
          </button>
          <button v-if="showResults" @click="clearSearch" class="text-gray-600 hover:underline text-sm">
            Clear
          </button>
        </div>
      </div>

      <!-- Results -->
      <div v-if="showResults">
        <h2 class="text-xl font-bold text-purple-800 text-center mt-6">📚 Search Results</h2>

        <div v-if="uploadedMaterials.length" class="grid md:grid-cols-2 gap-6 mt-6">
          <div v-for="material in uploadedMaterials" :key="material.id"
               class="bg-white border rounded-lg shadow p-4 space-y-2">
            <div class="flex justify-between items-center">
              <p class="text-purple-800 font-bold text-lg">
                {{ getFileIcon(material.file_type) }} {{ material.title || material.file_name }}
              </p>
              <button v-if="material.user_id === authUserId || role === 'admin'"
                      @click="deleteMaterial(material.id)"
                      class="text-red-600 hover:underline text-sm">Delete</button>
            </div>
            <div class="flex flex-wrap gap-2">
              <span class="bg-purple-100 text-purple-700 text-xs font-semibold px-2 py-1 rounded">
                {{ material.faculty }}
              </span>
              <span class="bg-blue-100 text-blue-700 text-xs font-semibold px-2 py-1 rounded">
                {{ material.major }}
              </span>
              <span class="text-gray-500 text-xs ml-auto">📅 {{ formatDate(material.created_at) }}</span>
            </div>
            <p class="text-sm text-gray-600">
              👤 Uploaded by: <span class="font-semibold text-gray-800">{{ material.user_name }}</span>
            </p>
            <p v-if="material.note" class="text-gray-700 text-sm">📝 {{ material.note }}</p>
            <div>
              <template v-if="material.file_type.startsWith('image/')">
                <img :src="`/storage/${material.file_path}`" alt="Material image"
                     class="w-full max-h-64 object-contain rounded border mb-2" />
                <a :href="`/storage/${material.file_path}`" download
                   class="text-blue-600 hover:underline text-sm">📥 Download image</a>
              </template>
              <template v-else>
                <a :href="`/storage/${material.file_path}`" download
                   class="text-blue-600 hover:underline text-sm">📥 Download file</a>
              </template>
            </div>
          </div>
        </div>

        <div v-else class="text-center text-gray-500 mt-6">
          No materials found.
        </div>
      </div>

      <!-- Upload Button -->
      <div>
        <button @click="showUploadForm = !showUploadForm"
                class="bg-green-600 text-white px-6 py-2 rounded hover:bg-green-500">
          {{ showUploadForm ? 'Hide Upload Form' : '➕ Upload Material' }}
        </button>
      </div>

      <!-- Upload Form -->
      <div v-if="showUploadForm" class="bg-white border rounded-lg p-6 shadow">
        <h2 class="text-xl font-semibold text-purple-700 mb-4">⬆️ Upload Material</h2>

        <div class="grid gap-4 md:grid-cols-2 mb-4">
          <select v-model="selectedFaculty" class="w-full border px-4 py-2 rounded">
            <option value="">Select Faculty</option>
            <option v-for="(majors, faculty) in faculties" :key="faculty" :value="faculty">{{ faculty }}</option>
          </select>
          <select v-model="selectedMajor" class="w-full border px-4 py-2 rounded">
            <option value="">Select Major</option>
            <option v-for="major in majors" :key="major" :value="major">{{ major }}</option>
          </select>
        </div>

        <input type="text" v-model="title" class="w-full border px-4 py-2 rounded mb-4"
               placeholder="Material Title" />

        <input type="file" id="fileInput" @change="handleFileUpload"
               accept=".pdf,.doc,.docx,.ppt,.pptx,.png,.jpg,.jpeg"
               class="w-full border px-4 py-2 rounded mb-4" />

        <textarea v-model="note" rows="2" class="w-full border px-4 py-2 rounded mb-4"
                  placeholder="Notes (optional)"></textarea>

        <button @click="addMaterial"
                class="bg-purple-700 text-white px-6 py-2 rounded hover:bg-purple-600" :disabled="isLoading">
          {{ isLoading ? 'Uploading...' : 'Upload' }}
        </button>
      </div>
    </div>
  </MainLayout>
</template>