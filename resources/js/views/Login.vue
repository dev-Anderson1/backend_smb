<template>
  <div style="min-height: 100vh; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: flex; align-items: center; justify-content: center; padding: 20px;">
    <div style="background: white; padding: 40px; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.3); width: 100%; max-width: 400px;">
      <h1 style="text-align: center; margin-bottom: 30px; color: #333;">Login</h1>
      
      <div v-if="formError" style="background: #fee; border: 1px solid #fcc; color: #c33; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
        {{ formError }}
      </div>

      <form @submit.prevent="handleSubmit">
        <div style="margin-bottom: 20px;">
          <label style="display: block; margin-bottom: 8px; font-weight: bold; color: #555;">Email:</label>
          <input 
            type="email" 
            v-model="email" 
            required
            style="width: 100%; padding: 12px; border: 2px solid #ddd; border-radius: 8px; font-size: 16px;"
            :style="{ borderColor: emailError ? '#f56565' : '#ddd' }"
          />
          <div v-if="emailError" style="color: #f56565; font-size: 14px; margin-top: 5px;">
            {{ emailError }}
          </div>
        </div>

        <div style="margin-bottom: 25px;">
          <label style="display: block; margin-bottom: 8px; font-weight: bold; color: #555;">Senha:</label>
          <input 
            :type="showPassword ? 'text' : 'password'"
            v-model="password" 
            required
            style="width: 100%; padding: 12px; border: 2px solid #ddd; border-radius: 8px; font-size: 16px;"
            :style="{ borderColor: passwordError ? '#f56565' : '#ddd' }"
          />
          <button 
            type="button" 
            @click="showPassword = !showPassword"
            style="margin-top: 5px; background: none; border: none; color: #667eea; cursor: pointer; font-size: 14px;"
          >
            {{ showPassword ? 'Ocultar' : 'Mostrar' }} senha
          </button>
          <div v-if="passwordError" style="color: #f56565; font-size: 14px; margin-top: 5px;">
            {{ passwordError }}
          </div>
        </div>

        <button 
          type="submit" 
          :disabled="loading"
          style="width: 100%; padding: 15px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; border-radius: 8px; font-size: 16px; font-weight: bold; cursor: pointer;"
          :style="{ opacity: loading ? 0.6 : 1 }"
        >
          {{ loading ? 'Entrando...' : 'Entrar' }}
        </button>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useStore } from 'vuex'
import { useRouter, useRoute } from 'vue-router'
import api from '@/services/api'

const router = useRouter()
const route = useRoute()
const store = useStore()

// state
const email = ref('')
const password = ref('')
const loading = ref(false)
const formError = ref('')
const emailError = ref('')
const passwordError = ref('')
const showPassword = ref(false)

// validação simples
const validate = () => {
  emailError.value = ''
  passwordError.value = ''
  formError.value = ''

  let ok = true
  if (!email.value) {
    emailError.value = 'Email é obrigatório.'
    ok = false
  } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.value)) {
    emailError.value = 'Email inválido.'
    ok = false
  }

  if (!password.value) {
    passwordError.value = 'Senha é obrigatória.'
    ok = false
  } else if (password.value.length < 6) {
    passwordError.value = 'Senha deve ter pelo menos 6 caracteres.'
    ok = false
  }

  return ok
}

const handleSubmit = async () => {
  try {
    if (!validate()) return
    loading.value = true
    formError.value = ''

    const { data } = await api.post('/login', {
      email: email.value,
      password: password.value
    })

    if (!data?.success) {
      formError.value = data?.message || 'Credenciais inválidas.'
      return
    }

    // Atualiza Vuex + localStorage
    store.commit('auth/setToken', data.token)
    store.commit('auth/setUser',  data.user)
    localStorage.setItem('token', data.token)
    localStorage.setItem('user', JSON.stringify(data.user))

    // Redireciona (ajuste o name/path conforme seu router)
    const redirect = route.query.redirect || '/'
    await router.replace(redirect)
  } catch (error) {
    console.error('Erro no login:', {
      message: error?.message,
      code: error?.code,
      url: error?.config?.baseURL + (error?.config?.url || ''),
      status: error?.response?.status,
      data: error?.response?.data,
    })
    formError.value = error?.response?.data?.message || 'Erro de conexão. Tente novamente.'
  } finally {
    loading.value = false
  }
}
</script>


