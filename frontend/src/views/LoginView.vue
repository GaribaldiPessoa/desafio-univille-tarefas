<template>
  <div class="min-h-screen flex items-center justify-center bg-gray-50 px-4">
    <div class="max-w-md w-full bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
      
      <div class="bg-green-700 p-8 text-center relative">
        <div class="mx-auto bg-white p-3 rounded-full shadow-sm w-16 h-16 flex items-center justify-center mb-4">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-green-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
          </svg>
        </div>
        <h2 class="text-3xl font-bold text-white mb-1">Portal Univille</h2>
        <p class="text-green-100 text-sm">{{ isLogin ? 'Acesso ao Gerenciador de Tarefas' : 'Crie sua conta no Portal' }}</p>
      </div>

      <form @submit.prevent="handleSubmit" class="p-8 space-y-5">
        
        <div v-if="!isLogin">
          <label class="block text-sm font-semibold text-gray-700 mb-2">Nome Completo</label>
          <input v-model="name" type="text" required class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-green-600 outline-none transition">
        </div>

        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-2">E-mail Institucional</label>
          <input v-model="email" type="email" required class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-green-600 outline-none transition">
        </div>

        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-2">Senha</label>
          <div class="relative">
            <input v-model="password" :type="showPassword ? 'text' : 'password'" required class="w-full border border-gray-300 rounded-lg p-3 pr-10 focus:ring-2 focus:ring-green-600 outline-none transition">
            <button type="button" @click="showPassword = !showPassword" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-500 hover:text-green-700 transition">
              <svg v-if="!showPassword" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
              <svg v-else xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" /></svg>
            </button>
          </div>
        </div>

        <div v-if="!isLogin">
          <label class="block text-sm font-semibold text-gray-700 mb-2">Confirme a Senha</label>
          <div class="relative">
            <input v-model="confirmPassword" :type="showPassword ? 'text' : 'password'" required class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-green-600 outline-none transition" :class="{'border-red-500 focus:ring-red-500': !passwordsMatch && confirmPassword}">
          </div>
          <p v-if="!passwordsMatch && confirmPassword" class="text-red-500 text-xs mt-2 font-medium">As senhas não coincidem.</p>
        </div>

        <button type="submit" :disabled="!isLogin && !passwordsMatch" class="w-full bg-green-700 text-white p-3 rounded-lg font-bold text-lg hover:bg-green-800 disabled:bg-gray-400 disabled:cursor-not-allowed transition shadow-md mt-4">
          {{ isLogin ? 'Acessar Portal' : 'Cadastrar e Entrar' }}
        </button>
        
        <div class="text-center mt-4">
          <button type="button" @click="toggleMode" class="text-green-700 font-semibold text-sm hover:underline">
            {{ isLogin ? 'Ainda não tem conta? Cadastre-se' : 'Já tem uma conta? Faça Login' }}
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import axios from 'axios'
import { useRouter } from 'vue-router'

const name = ref('')
const email = ref('')
const password = ref('')
const confirmPassword = ref('')
const isLogin = ref(true)
const showPassword = ref(false) // Controla o olhinho da senha

const router = useRouter()

// Validador em tempo real: verifica se as senhas são iguais
const passwordsMatch = computed(() => {
  return password.value === confirmPassword.value
})

const toggleMode = () => {
  isLogin.value = !isLogin.value
  name.value = ''
  email.value = ''
  password.value = ''
  confirmPassword.value = ''
  showPassword.value = false
}

const handleSubmit = async () => {
  // Trava extra de segurança antes de enviar para o Laravel
  if (!isLogin.value && !passwordsMatch.value) {
    return
  }

  try {
    const endpoint = isLogin.value ? '/login' : '/register'
    const payload = isLogin.value 
      ? { email: email.value, password: password.value }
      : { name: name.value, email: email.value, password: password.value }

    const response = await axios.post(`http://localhost/api${endpoint}`, payload)
    
    localStorage.setItem('token', response.data.access_token)
    router.push('/tasks')
  } catch (error) {
    alert(isLogin.value ? 'Erro ao entrar. Verifique suas credenciais.' : 'Erro ao criar conta. Este e-mail pode já estar em uso.')
  }
}
</script>