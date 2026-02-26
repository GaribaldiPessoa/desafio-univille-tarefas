import { createRouter, createWebHistory } from 'vue-router'
// O "../" diz para sair da pasta router e o "views/" diz para entrar na pasta views
import LoginView from '../views/LoginView.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'login',
      component: LoginView
    },
    {
      path: '/tasks',
      name: 'tasks',
      // Aqui ele vai procurar a TasksView que vamos criar agora
      component: () => import('../views/TasksView.vue')
    }
  ]
})

export default router