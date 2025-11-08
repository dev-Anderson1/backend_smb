// resources/js/store/modules/auth.js
import api from '@/services/api'

export default {
  namespaced: true,
  state: () => ({ token: null, user: null }),
  getters: { isAuthenticated: (s) => !!s.token },
  mutations: {
    setToken(s,t){ s.token=t },
    setUser(s,u){ s.user=u },
    clearAuth(s){ s.token=null; s.user=null },
  },
  actions: {
    async logout({ commit, state }) {
      try {
        // tenta invalidar no backend (opcionalmente ignore o erro)
        await api.post('/logout')
      } catch (e) {
        // ok ignorar; vamos limpar client-side de qualquer jeito
        console.warn('[auth/logout] backend falhou ou sem header Authorization', e?.response?.status)
      }
      // limpa estado e storage
      commit('clearAuth')
      localStorage.removeItem('token')
      localStorage.removeItem('user')
      // se você setou Authorization default em algum lugar, remova:
      try { delete api.defaults.headers.common.Authorization } catch {}
    },
  },
}
