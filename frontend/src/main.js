import '@/assets/main.css'
import './assets/main.css'

import { createApp } from 'vue'
import App from './App.vue'
import router from './router' // Importa a configuração que criamos

const app = createApp(App)

app.use(router) // Diz ao Vue para usar o roteador

app.mount('#app')