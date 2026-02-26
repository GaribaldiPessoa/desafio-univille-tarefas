<template>
  <div class="min-h-screen bg-gray-50 py-10 px-4 relative">
    <div class="max-w-4xl mx-auto bg-white rounded-xl shadow-lg overflow-hidden border border-gray-100">
      
      <div class="bg-green-700 p-6 flex justify-between items-center">
        <div class="flex items-center gap-3">
          <div class="bg-white p-2 rounded-md shadow-sm">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-700" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
          </div>
          <h1 class="text-2xl font-bold text-white tracking-wide">Portal de Tarefas | Univille</h1>
        </div>
        <button @click="logout" class="border border-white/40 hover:bg-white/10 text-white px-5 py-2 rounded-lg transition font-semibold flex items-center gap-2 shadow-sm">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
          <span class="hidden sm:inline">Sair</span>
        </button>
      </div>

      <div class="p-8">
        <div class="flex flex-wrap gap-4 mb-6 bg-gray-50 p-4 rounded-lg border border-gray-200">
          <div class="flex-1 min-w-[200px]">
            <label class="block text-sm font-semibold text-green-800 mb-1">Status da Tarefa</label>
            <select v-model="statusFilter" @change="loadTasks(1)" class="w-full border border-gray-300 rounded-lg p-2.5 focus:ring-2 focus:ring-green-600 outline-none shadow-sm">
              <option value="all">📚 Todas as Tarefas</option>
              <option value="pending">⏳ Pendentes</option>
              <option value="completed">✅ Concluídas</option>
            </select>
          </div>
          
          <div class="flex-1 min-w-[200px]">
            <label class="block text-sm font-semibold text-green-800 mb-1">Ordenar por data</label>
            <select v-model="sortOrder" @change="loadTasks(1)" class="w-full border border-gray-300 rounded-lg p-2.5 focus:ring-2 focus:ring-green-600 outline-none shadow-sm">
              <option value="desc">🔽 Mais recentes primeiro</option>
              <option value="asc">🔼 Mais antigas primeiro</option>
            </select>
          </div>
        </div>

        <div class="flex gap-3 mb-8">
          <input v-model="newTask" @keyup.enter="addTask" type="text" placeholder="Adicionar nova atividade acadêmica..." class="flex-1 border border-gray-300 rounded-lg p-3 outline-none focus:border-green-600 focus:ring-2 focus:ring-green-600/30 transition shadow-sm">
          <button @click="addTask" class="bg-green-700 text-white px-8 py-3 rounded-lg hover:bg-green-800 font-bold shadow-md transition flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
            <span class="hidden sm:inline">Adicionar</span>
          </button>
        </div>

        <ul class="space-y-3">
          <li v-for="task in tasks" :key="task.id" class="flex flex-col sm:flex-row sm:items-center justify-between border border-gray-100 bg-white hover:bg-gray-50 rounded-lg p-4 shadow-sm transition gap-4">
            
            <label class="flex items-center gap-4 flex-1 cursor-pointer group select-none">
              <div class="relative flex items-center justify-center">
                <input type="checkbox" :checked="task.is_completed" @change="toggleTask(task)" class="w-6 h-6 cursor-pointer accent-green-600 transition-transform hover:scale-110">
              </div>
              <span :class="{'line-through text-gray-400': task.is_completed, 'text-gray-800 font-semibold group-hover:text-green-700 transition': !task.is_completed}" class="text-lg">
                {{ task.title }}
              </span>
            </label>
            
            <div class="flex gap-2 justify-end border-t sm:border-t-0 pt-3 sm:pt-0 border-gray-100">
              <button @click="openTransferModal(task)" class="text-blue-600 hover:text-blue-800 bg-blue-50 hover:bg-blue-100 px-3 py-2 rounded-md text-sm font-semibold flex items-center gap-1.5 transition" title="Transferir para outro usuário">
                 <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" /></svg>
                 Transferir
              </button>
              <button @click="confirmDeletePrompt(task)" class="text-red-500 hover:text-red-700 bg-red-50 hover:bg-red-100 px-3 py-2 rounded-md text-sm font-semibold flex items-center gap-1.5 transition" title="Excluir tarefa">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                Excluir
              </button>
            </div>
          </li>
        </ul>
        
        <div v-if="tasks.length === 0" class="text-center py-12 bg-gray-50 rounded-lg border border-dashed border-gray-300 mt-4">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" /></svg>
          <p class="text-gray-500 font-medium text-lg">Nenhuma tarefa encontrada.</p>
          <p class="text-gray-400 text-sm mt-1">Experimente alterar os filtros ou adicionar uma nova.</p>
        </div>

        <div v-if="pagination.last_page > 1" class="flex justify-between items-center mt-6 pt-6 border-t border-gray-100">
          <button @click="loadTasks(pagination.current_page - 1)" :disabled="pagination.current_page === 1" class="px-4 py-2 text-sm font-semibold text-green-700 bg-green-50 rounded-md disabled:opacity-50 hover:bg-green-100 transition shadow-sm">&laquo; Anterior</button>
          <span class="text-sm text-gray-600 font-medium bg-gray-100 px-3 py-1 rounded-full">Página {{ pagination.current_page }} de {{ pagination.last_page }}</span>
          <button @click="loadTasks(pagination.current_page + 1)" :disabled="pagination.current_page === pagination.last_page" class="px-4 py-2 text-sm font-semibold text-green-700 bg-green-50 rounded-md disabled:opacity-50 hover:bg-green-100 transition shadow-sm">Próxima &raquo;</button>
        </div>
      </div>
    </div>

    <div v-if="isDeleteModalOpen" class="fixed inset-0 bg-black/60 flex items-center justify-center z-50 p-4">
      <div class="bg-white rounded-xl shadow-2xl max-w-sm w-full p-6 text-center transform transition-all">
        <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-red-100 mb-4">
          <svg class="h-8 w-8 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
        </div>
        <h3 class="text-xl font-bold text-gray-900 mb-2">Excluir Tarefa?</h3>
        <p class="text-sm text-gray-500 mb-6">Você tem certeza que deseja excluir a tarefa <br><strong class="text-gray-800">"{{ taskToDelete?.title }}"</strong>?<br> Esta ação não pode ser desfeita.</p>
        <div class="flex gap-3 justify-center">
          <button @click="isDeleteModalOpen = false" class="px-5 py-2.5 bg-gray-100 text-gray-800 rounded-lg hover:bg-gray-200 font-semibold transition w-full">Cancelar</button>
          <button @click="executeDelete" class="px-5 py-2.5 bg-red-600 text-white rounded-lg hover:bg-red-700 font-semibold shadow-md transition w-full">Sim, Excluir</button>
        </div>
      </div>
    </div>

    <div v-if="isModalOpen" class="fixed inset-0 bg-black/60 flex items-center justify-center z-50 p-4">
      <div class="bg-white rounded-xl shadow-2xl max-w-md w-full overflow-hidden flex flex-col max-h-[90vh]">
        <div class="bg-green-700 p-5 flex justify-between items-center text-white">
          <h2 class="font-bold text-lg flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" /></svg>
            Transferir Tarefa
          </h2>
          <button @click="closeModal" class="hover:bg-white/20 p-1.5 rounded-md transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
          </button>
        </div>
        
        <div class="p-6 overflow-y-auto">
          <p class="text-gray-600 mb-6 text-sm bg-gray-50 p-3 rounded-lg border border-gray-100">
            Tarefa selecionada:<br>
            <strong class="text-gray-900 text-base">"{{ taskToTransfer?.title }}"</strong>
          </p>

          <div class="relative mb-6">
            <label class="block text-sm font-semibold text-green-800 mb-1">Buscar destinatário por e-mail</label>
            <div class="relative">
              <input type="text" disabled :value="suggestionText" class="w-full border border-transparent rounded-lg p-3 absolute top-0 left-0 text-gray-400 bg-gray-50 z-0 pointer-events-none" />
              <input v-model="searchQuery" @input="loadUsers(1)" @keydown.tab.prevent="acceptSuggestion" type="text" placeholder="Comece a digitar..." class="w-full border border-gray-300 rounded-lg p-3 bg-transparent relative z-10 outline-none focus:border-green-600 focus:ring-2 focus:ring-green-600/30 transition shadow-sm" />
              <div v-if="suggestionText" class="absolute right-3 top-3.5 text-xs bg-gray-200 text-gray-700 px-2 py-1 rounded z-20 pointer-events-none font-bold shadow-sm">TAB ↹</div>
            </div>
          </div>

          <ul class="space-y-2">
            <li v-for="user in usersList" :key="user.id" class="flex justify-between items-center p-3 border rounded-lg hover:border-green-500 hover:shadow-sm transition border-gray-200 bg-white group/user">
              <div>
                <p class="font-semibold text-gray-800">{{ user.name || 'Usuário' }}</p>
                <p class="text-xs text-gray-500">{{ user.email }}</p>
              </div>
              <button @click="confirmTransfer(user)" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-bold shadow-md hover:bg-blue-700 transition transform group-hover/user:scale-105">
                Enviar
              </button>
            </li>
          </ul>

          <p v-if="usersList.length === 0" class="text-center text-gray-500 my-8 font-medium text-sm">Nenhum usuário encontrado com essa busca.</p>

          <div v-if="userPagination.last_page > 1" class="flex justify-between items-center mt-6 pt-4 border-t border-gray-100">
            <button @click="loadUsers(userPagination.current_page - 1)" :disabled="userPagination.current_page === 1" class="px-3 py-1.5 text-xs font-semibold text-green-700 bg-green-50 rounded shadow-sm disabled:opacity-50 hover:bg-green-100 transition">Anterior</button>
            <span class="text-xs text-gray-500 font-medium bg-gray-100 px-2 py-1 rounded">Pág {{ userPagination.current_page }} / {{ userPagination.last_page }}</span>
            <button @click="loadUsers(userPagination.current_page + 1)" :disabled="userPagination.current_page === userPagination.last_page" class="px-3 py-1.5 text-xs font-semibold text-green-700 bg-green-50 rounded shadow-sm disabled:opacity-50 hover:bg-green-100 transition">Próxima</button>
          </div>
        </div>
      </div>
    </div>

    <div v-if="toast.show" :class="['fixed bottom-5 right-5 px-6 py-4 rounded-xl shadow-2xl text-white font-bold flex items-center gap-3 z-50 transform transition-all', toast.type === 'success' ? 'bg-green-600' : 'bg-red-500']">
      <svg v-if="toast.type === 'success'" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
      <svg v-else xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
      {{ toast.message }}
    </div>

  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'
import axios from 'axios'
import { useRouter } from 'vue-router'

const tasks = ref([])
const newTask = ref('')
const statusFilter = ref('all')
const sortOrder = ref('desc')
const pagination = ref({ current_page: 1, last_page: 1 })

// Estados do Modal Transferência
const isModalOpen = ref(false)
const taskToTransfer = ref(null)
const usersList = ref([])
const userPagination = ref({ current_page: 1, last_page: 1 })
const searchQuery = ref('')

// Estados do Modal de Exclusão
const isDeleteModalOpen = ref(false)
const taskToDelete = ref(null)

// Estado do Toast de Notificação
const toast = ref({ show: false, message: '', type: 'success' })

const router = useRouter()
const token = localStorage.getItem('token')

const api = axios.create({
  baseURL: 'http://localhost/api',
  headers: { Authorization: `Bearer ${token}` }
})

// Função para disparar Toasts
const showToast = (message, type = 'success') => {
  toast.value = { show: true, message, type }
  setTimeout(() => { toast.value.show = false }, 3500)
}

const suggestionText = computed(() => {
  if (!searchQuery.value) return ''
  const match = usersList.value.find(u => u.email.toLowerCase().startsWith(searchQuery.value.toLowerCase()))
  if (match) return searchQuery.value + match.email.substring(searchQuery.value.length)
  return ''
})

const acceptSuggestion = () => {
  if (suggestionText.value) {
    searchQuery.value = suggestionText.value
    loadUsers(1)
  }
}

const loadTasks = async (page = pagination.value.current_page) => {
  try {
    const response = await api.get('/tasks', {
      params: { status: statusFilter.value, sort: sortOrder.value, page }
    })
    if (response.data && response.data.data) {
      tasks.value = response.data.data
      pagination.value = { current_page: response.data.current_page, last_page: response.data.last_page }
    } else {
      tasks.value = response.data
      pagination.value = { current_page: 1, last_page: 1 }
    }
  } catch (error) {
    if (error.response?.status === 401) logout()
  }
}

const addTask = async () => {
  if (!newTask.value.trim()) return
  try {
    await api.post('/tasks', { title: newTask.value })
    newTask.value = ''
    loadTasks(1)
    showToast('Atividade adicionada com sucesso!')
  } catch (error) {
    showToast('Erro ao criar atividade.', 'error')
  }
}

const toggleTask = async (task) => {
  try {
    await api.put(`/tasks/${task.id}`, { is_completed: !task.is_completed })
    loadTasks()
  } catch (error) {
    showToast('Erro ao atualizar status da tarefa.', 'error')
  }
}

// Abre o novo Modal de Exclusão
const confirmDeletePrompt = (task) => {
  taskToDelete.value = task
  isDeleteModalOpen.value = true
}

// Executa a Exclusão real
const executeDelete = async () => {
  if (!taskToDelete.value) return
  try {
    await api.delete(`/tasks/${taskToDelete.value.id}`)
    showToast('Tarefa removida com sucesso!')
    isDeleteModalOpen.value = false
    taskToDelete.value = null
    loadTasks()
  } catch (error) {
    showToast('Erro ao excluir tarefa.', 'error')
    isDeleteModalOpen.value = false
  }
}

const openTransferModal = (task) => {
  taskToTransfer.value = task
  isModalOpen.value = true
  searchQuery.value = ''
  loadUsers(1)
}

const closeModal = () => {
  isModalOpen.value = false
  taskToTransfer.value = null
  searchQuery.value = ''
}

const loadUsers = async (page = 1) => {
  try {
    const response = await api.get('/users', { params: { page, search: searchQuery.value } })
    usersList.value = response.data.data
    userPagination.value = { current_page: response.data.current_page, last_page: response.data.last_page }
  } catch (error) {
    console.error('Erro ao buscar usuários', error)
  }
}

const confirmTransfer = async (user) => {
  try {
    await api.put(`/tasks/${taskToTransfer.value.id}/transfer`, { email: user.email })
    showToast(`Tarefa transferida para ${user.name || user.email}!`)
    closeModal()
    loadTasks()
  } catch (error) {
    showToast(error.response?.data?.message || 'Erro ao transferir tarefa.', 'error')
  }
}

const logout = () => {
  localStorage.removeItem('token')
  router.push('/')
}

onMounted(() => loadTasks(1))
</script>