import { createRouter, createWebHistory } from 'vue-router';
import store from '@/store/index';

// Views
import Login from '@/views/Login.vue';
import Dashboard from '@/components/dashboard/Dashboard.vue';

// Outras páginas
import Armas from '@/views/Armas.vue';
import Cautelas from '@/views/Cautelas.vue';
import Municoes from '@/views/Municoes.vue';
import Usuarios from '@/views/Usuarios.vue';
import Relatorios from '@/views/Relatorios.vue';
//import Configuracoes from '@/views/Configuracoes.vue';

const routes = [
  // Login (visitante)
  { 
    path: '/login', 
    name: 'Login', 
    component: Login, 
    meta: { requiresGuest: true, title: 'Login' } 
  },

  // Área logada
  { 
    path: '/', 
    redirect: '/dashboard',
    meta: { requiresAuth: true }
  },
  { 
    path: '/dashboard', 
    name: 'Dashboard', 
    component: Dashboard, 
    meta: { requiresAuth: true } 
  },
  { 
    path: '/armas', 
    name: 'Armas', 
    component: Armas, 
    meta: { requiresAuth: true } 
  },
  { 
    path: '/cautelas', 
    name: 'Cautelas', 
    component: Cautelas, 
    meta: { requiresAuth: true } 
  },
  { 
    path: '/municoes', 
    name: 'Municoes', 
    component: Municoes, 
    meta: { requiresAuth: true } 
  },
  { 
    path: '/usuarios', 
    name: 'Usuarios', 
    component: Usuarios, 
    meta: { requiresAuth: true } 
  },
  { 
    path: '/relatorios', 
    name: 'Relatorios', 
    component: Relatorios, 
    meta: { requiresAuth: true } 
  },
  // { 
  //   path: '/configuracoes', 
  //   name: 'Configuracoes', 
  //   component: Configuracoes, 
  //   meta: { requiresAuth: true } 
  // },

  // Qualquer rota inexistente → Login
  { path: '/:catchAll(.*)', redirect: '/login' },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

// Guard de autenticação
router.beforeEach(async (to, from, next) => {
  const token = store.state.auth.token

  if (token && !store.state.auth.user) {
    try {
      await store.dispatch('auth/fetchUser')
    } catch {
      await store.dispatch('auth/logout')
    }
  }

  const isAuth = store.getters['auth/isAuthenticated']
  console.log('[GUARD]', to.fullPath, 'isAuth=', isAuth)

  if (to.meta.requiresAuth && !isAuth) {
    next({ name: 'Login', query: { redirect: to.fullPath } })
  } else if (to.meta.requiresGuest && isAuth) {
    next({ name: 'Dashboard' })
  } else {
    next()
  }
})

export default router;
