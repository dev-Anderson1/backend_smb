import axios from 'axios';
import store from '@/store';
import router from '@/router';

const api = axios.create({
  baseURL: '/api', // se não usar proxy do Vite, troque para http://SEU_BACKEND/api
  timeout: 20000,
  headers: { Accept: 'application/json', 'Content-Type': 'application/json' },
});

api.interceptors.request.use((config) => {
  const token = store.state.auth?.token || localStorage.getItem('token');
  if (token) config.headers.Authorization = `Bearer ${token}`;
  return config;
});

api.interceptors.response.use(
  (r) => r,
  (err) => {
    if (err?.response?.status === 401) {
      try { store.dispatch('auth/logout'); } catch {}
      router.replace({ name: 'Login' });
    }
    return Promise.reject(err);
  }
);

export default api;
