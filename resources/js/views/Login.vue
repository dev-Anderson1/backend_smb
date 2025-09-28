<template>
  <div class="login py-10 px-4 flex justify-center">
    <form @submit.prevent="handleSubmit" class="w-full max-w-md space-y-4">
      <h2 class="text-2xl font-bold">Login</h2>

      <!-- Erro global -->
      <p
        v-if="formError"
        class="text-sm text-red-600 bg-red-50 border border-red-200 rounded p-2"
        role="alert"
      >
        {{ formError }}
      </p>

      <div>
        <label for="email" class="block text-gray-700">Email</label>
        <input
          type="email"
          id="email"
          v-model.trim="email"
          required
          autocomplete="email"
          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
          :class="{'border-red-500': emailError}"
        />
        <small v-if="emailError" class="text-red-600">{{ emailError }}</small>
      </div>

      <div>
        <label for="password" class="block text-gray-700">Senha</label>
        <div class="mt-1 relative">
          <input
            :type="showPassword ? 'text' : 'password'"
            id="password"
            v-model="password"
            required
            autocomplete="current-password"
            class="block w-full rounded-md border-gray-300 shadow-sm pr-10 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
            :class="{'border-red-500': passwordError}"
          />
          <button
            type="button"
            class="absolute inset-y-0 right-0 px-3 text-gray-500 hover:text-gray-700"
            @click="showPassword = !showPassword"
            :aria-label="showPassword ? 'Ocultar senha' : 'Mostrar senha'"
          >
            {{ showPassword ? 'Ocultar' : 'Mostrar' }}
          </button>
        </div>
        <small v-if="passwordError" class="text-red-600">{{ passwordError }}</small>
      </div>

      <button
        type="submit"
        class="w-full bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 disabled:opacity-60"
        :disabled="loading"
        :aria-busy="loading ? 'true' : 'false'"
      >
        {{ loading ? 'Entrando...' : 'Entrar' }}
      </button>
    </form>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useStore } from 'vuex'
import { useRouter, useRoute } from 'vue-router'

const store = useStore()
const router = useRouter()
const route = useRoute()

const email = ref('')
const password = ref('')
const loading = ref(false)
const formError = ref('')
const emailError = ref('')
const passwordError = ref('')
const showPassword = ref(false)

const validate = () => {
  emailError.value = ''
  passwordError.value = ''
  formError.value = ''

  // validação bem simples
  const emailOk = /\S+@\S+\.\S+/.test(email.value)
  if (!emailOk) emailError.value = 'Informe um e-mail válido.'

  if (!password.value || password.value.length < 6) {
    passwordError.value = 'A senha deve ter pelo menos 6 caracteres.'
  }

  return !emailError.value && !passwordError.value
}

const handleSubmit = async () => {
  if (!validate()) return
  loading.value = true
  formError.value = ''

  try {
    await store.dispatch('login', {
      email: email.value,
      password: password.value
    })

    // se tiver ?redirect=/rota, usa; senão vai para dashboard
    const redirect = route.query.redirect || '/'
    router.push(String(redirect))
  } catch (err) {
    // tente capturar mensagem da API (Laravel)
    formError.value =
      err?.response?.data?.message ||
      err?.message ||
      'Não foi possível entrar. Verifique suas credenciais.'
  } finally {
    loading.value = false
  }
}
</script>

<style scoped>
/* opcional: centralizar verticalmente se quiser
.login { min-height: calc(100vh - 4rem); }
*/
</style>
