// resources/js/router.js

import { createRouter, createWebHistory } from 'vue-router'

// Importe os componentes das páginas (crie esses arquivos também)
import Dashboard from './components/dashboard/Dashboard.vue'
import Users from './components/users/Users.vue'
import Settings from './components/settings/Settings.vue'

const routes = [
  { path: '/dashboard', component: Dashboard },
  { path: '/users', component: Users },
  { path: '/settings', component: Settings },
  { path: '/', redirect: '/dashboard' }
]

const router = createRouter({
  history: createWebHistory(),
  routes
})

export default router
