<script setup>
import MainLayout from '@/Layouts/MainLayout.vue';
import { ref, onMounted, watch, nextTick } from 'vue';
import { usePage } from '@inertiajs/vue3';
import axios from 'axios';

const authUser = usePage().props.auth.user;
const search = ref('');
const users = ref([]);
const messages = ref([]);
const selectedUser = ref(null);
const newMessage = ref('');
const chatContainer = ref(null);
const unreadCounts = ref({});

const fetchUsers = async () => {
  if (!search.value.trim()) {
    users.value = [];
    return;
  }

  try {
    const { data } = await axios.get('/messages/search', {
      params: { q: search.value }
    });
    users.value = data;
  } catch (error) {
    console.error('Error searching users:', error);
  }
};

const selectUser = async (user) => {
  selectedUser.value = user;
  unreadCounts.value[user.id] = 0;
  
  try {
    const { data } = await axios.get(`/messages/${user.id}/fetch`);
    messages.value = data.map(msg => ({
      ...msg,
      isMine: msg.sender_id === authUser.id
    }));
    nextTick(scrollToBottom);
  } catch (error) {
    console.error('Error fetching messages:', error);
  }
};

const sendMessage = async () => {
  if (!newMessage.value.trim() || !selectedUser.value) return;

  try {
    const { data } = await axios.post('/messages/send', {
      receiver_id: selectedUser.value.id,
      body: newMessage.value
    });

    messages.value.push({
      ...data,
      isMine: true
    });

    newMessage.value = ''; // 🧼 يمسح الرسالة من خانة الإدخال
    nextTick(scrollToBottom);
  } catch (error) {
    console.error('Error sending message:', error);
  }
};

const scrollToBottom = () => {
  if (chatContainer.value) {
    chatContainer.value.scrollTop = chatContainer.value.scrollHeight;
  }
};

onMounted(() => {
  // لا يوجد بث مباشر، فقط استدعاء يدوي للرسائل
});

watch(search, fetchUsers);
</script>

<template>
  <MainLayout>
    <div class="max-w-4xl mx-auto p-4">
      <h1 class="text-2xl font-bold mb-4">Messages</h1>
      
      <div class="bg-white rounded-lg shadow overflow-hidden">
        <!-- Search input -->
        <div class="p-4 border-b">
          <input
            v-model="search"
            type="text"
            placeholder="Search users..."
            class="w-full p-2 border rounded"
          />
        </div>
        
        <div class="flex h-[500px]">
          <!-- User list -->
          <div class="w-1/3 border-r overflow-y-auto">
            <div
              v-for="user in users"
              :key="user.id"
              @click="selectUser(user)"
              class="p-3 border-b hover:bg-gray-50 cursor-pointer flex justify-between items-center"
              :class="{ 'bg-gray-100': selectedUser?.id === user.id }"
            >
              <div>
                <p class="font-medium">{{ user.name }}</p>
                <p class="text-sm text-gray-500">{{ user.email }}</p>
              </div>
              <span
                v-if="unreadCounts[user.id]"
                class="bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center"
              >
                {{ unreadCounts[user.id] }}
              </span>
            </div>
          </div>
          
          <!-- Chat area -->
          <div class="w-2/3 flex flex-col">
            <!-- Header -->
            <div v-if="selectedUser" class="p-3 border-b bg-gray-50">
              <p class="font-medium">Chat with {{ selectedUser.name }}</p>
            </div>
            
            <!-- Messages -->
            <div
              v-if="selectedUser"
              ref="chatContainer"
              class="flex-1 p-4 overflow-y-auto"
            >
              <div
                v-for="(message, index) in messages"
                :key="index"
                :class="message.isMine ? 'text-right' : 'text-left'"
                class="mb-3"
              >
                <div
                  :class="[
                    'inline-block px-4 py-2 rounded-lg max-w-[75%]',
                    message.isMine ? 'bg-blue-100 text-blue-800' : 'bg-gray-200 text-gray-800'
                  ]"
                >
                  {{ message.body }}
                  <div class="text-xs mt-1 text-gray-500">
                    {{ new Date(message.created_at).toLocaleTimeString() }}
                  </div>
                </div>
              </div>
            </div>
            
            <!-- Message input -->
            <div v-if="selectedUser" class="p-3 border-t">
              <div class="flex gap-2">
                <input
                  v-model="newMessage"
                  @keyup.enter="sendMessage"
                  type="text"
                  placeholder="Type a message..."
                  class="flex-1 p-2 border rounded"
                />
                <button
                  @click="sendMessage"
                  class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600"
                  :disabled="!newMessage.trim()"
                >
                  Send
                </button>
              </div>
            </div>
            
            <!-- Placeholder -->
            <div v-else class="flex-1 flex items-center justify-center text-gray-500">
              Select a user to start chatting
            </div>
          </div>
        </div>
      </div>
    </div>
  </MainLayout>
</template>