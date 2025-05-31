<script setup>
import MainLayout from '@/Layouts/MainLayout.vue'
import { ref, computed } from 'vue'
import { usePage, useForm, router } from '@inertiajs/vue3'

const props = defineProps({
  posts: Array,
  auth_user_id: Number
})

const user = usePage().props.auth.user

const role = computed(() => {
  if (!user) return 'guest'
  if (user.email === 'smhmwdabwalshykh@gmail.com') return 'admin'
  if (user.email.endsWith('@just.edu.jo')) return 'instructor'
  if (user.email.endsWith('@cit.just.edu.jo')) return 'student'
  return 'guest'
})

const showForm = ref(false)
const editingPost = ref(null)
const shownComments = ref([])
const commentInputs = ref({})

const form = useForm({
  title: '',
  content: ''
})

function submitPost() {
  form.post(route('posts.store'), {
    preserveScroll: true,
    onSuccess: () => {
      form.reset()
      showForm.value = false
    }
  })
}

function deletePost(id) {
  if (confirm('Are you sure you want to delete this post?')) {
    router.delete(route('posts.destroy', id), { preserveScroll: true })
  }
}

const editForm = useForm({
  title: '',
  content: ''
})

function startEdit(post) {
  editingPost.value = post
  editForm.title = post.title
  editForm.content = post.content
}

function saveEdit() {
  if (editingPost.value) {
    editForm.put(route('posts.update', editingPost.value.id), {
      preserveScroll: true,
      onSuccess: () => {
        editingPost.value = null
      }
    })
  }
}

function cancelEdit() {
  editingPost.value = null
  editForm.reset()
}

function toggleComments(postId) {
  if (shownComments.value.includes(postId)) {
    shownComments.value = shownComments.value.filter(id => id !== postId)
  } else {
    shownComments.value.push(postId)
  }
}

function sendComment(postId) {
  const content = commentInputs.value[postId]
  if (!content) return

  router.post(route('posts.comment', postId), {
    content: content
  }, {
    preserveScroll: true,
    onSuccess: () => {
      commentInputs.value[postId] = ''
    }
  })
}

function formatDate(dateString) {
  return new Date(dateString).toLocaleDateString()
}
</script>

<template>
  <MainLayout>
    <div class="p-6 max-w-2xl mx-auto">
      <div class="w-full flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-purple-800">Community Feed</h1>
        <button @click="showForm = !showForm"
                class="bg-green-600 text-white font-semibold px-6 py-2 rounded-lg hover:bg-green-500 transition-colors">
          {{ showForm ? 'Cancel' : '+ New Post' }}
        </button>
      </div>

      <!-- New Post Form -->
      <div v-if="showForm" class="bg-white border rounded-lg p-6 mb-8 shadow-md">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Create New Post</h2>
        <input v-model="form.title" type="text" placeholder="Post title"
               class="w-full mb-4 px-4 py-2 border rounded-lg">
        <textarea v-model="form.content" rows="4" placeholder="What's on your mind?"
                  class="w-full mb-4 px-4 py-2 border rounded-lg resize-none"></textarea>
        <div class="flex justify-end">
          <button @click="submitPost"
                  class="bg-purple-600 text-white px-6 py-2 rounded-lg hover:bg-purple-700 transition-colors">
            Post
          </button>
        </div>
      </div>

      <!-- Edit Post Form -->
      <div v-if="editingPost" class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 mb-8 shadow-md">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Edit Post</h2>
        <input v-model="editForm.title" type="text" placeholder="Post title"
               class="w-full mb-4 px-4 py-2 border rounded-lg">
        <textarea v-model="editForm.content" rows="4" placeholder="Content"
                  class="w-full mb-4 px-4 py-2 border rounded-lg resize-none"></textarea>
        <div class="flex justify-end gap-3">
          <button @click="cancelEdit"
                  class="bg-gray-400 text-white px-4 py-2 rounded-lg hover:bg-gray-500">
            Cancel
          </button>
          <button @click="saveEdit"
                  class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
            Save Changes
          </button>
        </div>
      </div>

      <!-- Posts List -->
      <div v-if="posts.length" class="space-y-6">
        <div v-for="post in posts" :key="post.id"
             class="bg-white border rounded-lg p-6 shadow-md hover:shadow-lg transition-shadow">
          <div class="flex items-center mb-4">
            <img :src="post.user?.profile_image ? '/storage/' + post.user.profile_image : '/images/default-profile.png'"
                 class="w-10 h-10 rounded-full mr-3 object-cover">
            <div>
              <p class="font-semibold text-gray-800">{{ post.user?.name }}</p>
              <p class="text-sm text-gray-500">{{ formatDate(post.created_at) }}</p>
            </div>
          </div>

          <h2 class="text-xl font-bold text-gray-800 mb-3">{{ post.title }}</h2>
          <p class="text-gray-700 mb-4 whitespace-pre-line">{{ post.content }}</p>

          <!-- Action Buttons (for owner or admin) -->
          <div v-if="post.user_id === auth_user_id || role === 'admin'" class="flex gap-3 mb-4">
            <button @click="startEdit(post)" class="text-blue-600 hover:text-blue-800 transition-colors flex items-center gap-1">
              Edit
            </button>
            <button @click="deletePost(post.id)" class="text-red-600 hover:text-red-800 transition-colors flex items-center gap-1">
              Delete
            </button>
          </div>

          <!-- Comments Section -->
          <div class="border-t pt-4">
            <button @click="toggleComments(post.id)"
                    class="text-purple-600 hover:text-purple-800 flex items-center gap-1 mb-3">
              {{ shownComments.includes(post.id) ? 'Hide Comments' : 'Show Comments' }}
              <span class="text-sm bg-gray-200 px-2 py-1 rounded-full ml-1">{{ post.comments.length }}</span>
            </button>

            <div v-if="shownComments.includes(post.id)">
              <!-- Comments List -->
              <div v-if="post.comments.length" class="space-y-3 mb-4">
                <div v-for="comment in post.comments" :key="comment.id" class="flex items-start gap-3">
                  <img :src="comment.user?.profile_image ? '/storage/' + comment.user.profile_image : '/images/default-profile.png'"
                       class="w-8 h-8 rounded-full mt-1 object-cover">
                  <div class="bg-gray-50 p-3 rounded-lg flex-1">
                    <div class="flex justify-between items-start">
                      <p class="font-semibold text-sm text-gray-800">{{ comment.user?.name }}</p>
                      <p class="text-xs text-gray-500">{{ formatDate(comment.created_at) }}</p>
                    </div>
                    <p class="text-gray-700 text-sm mt-1">{{ comment.content }}</p>
                  </div>
                </div>
              </div>
              <div v-else class="text-gray-500 text-sm italic mb-4">No comments yet.</div>

              <!-- Add Comment Form -->
              <div class="flex gap-2">
                <input v-model="commentInputs[post.id]" type="text" placeholder="Write a comment..."
                       class="flex-1 border px-4 py-2 rounded-lg">
                <button @click="sendComment(post.id)"
                        class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700">
                  Send
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div v-else class="text-center py-12 text-gray-500">
        No posts yet. Be the first to post!
      </div>
    </div>
  </MainLayout>
</template>