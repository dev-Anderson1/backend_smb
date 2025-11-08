// resources/js/store/index.js
import { createStore } from 'vuex'
import auth from './modules/auth'

const store = createStore({
  modules: { auth }, // namespace "auth"
})

// 🔹 Hidrata o estado a partir do localStorage ANTES de exportar
try {
  const t = localStorage.getItem('token')
  const u = localStorage.getItem('user')
  if (t) store.commit('auth/setToken', t)
  if (u) store.commit('auth/setUser', JSON.parse(u))
} catch (e) {
  console.warn('[STORE] falha ao hidratar:', e)
}

export default store
