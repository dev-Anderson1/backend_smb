<!-- src/components/layout/Navbar.vue -->
<template>
  <v-app-bar
    app
    fixed
    color="white"
    elevation="2"
    height="64"
  >
    <v-app-bar-nav-icon
      @click="$emit('toggle-sidebar')"
      class="d-lg-none"
    />
      <v-app-bar-nav-icon
  class="d-none d-lg-flex"
  @click="$emit('toggle-sidebar')"
/>
    
    <v-app-bar-title>
      Sistema SMB
    </v-app-bar-title>

    <v-spacer />

    <!-- Botão de logout -->
    <v-btn
     :loading="leaving"
      color="error"
      variant="outlined"
      @click="logout"
      prepend-icon="mdi-logout"
    >
      Sair
    </v-btn>
  </v-app-bar>
</template>

<script setup>
import { useStore } from 'vuex'
import { useRouter } from 'vue-router'
import { ref } from 'vue'

defineEmits(['toggle-sidebar'])

const store = useStore()
const router = useRouter()
const leaving = ref(false)   // <-- FALTAVA

const logout = async () => {
  if (leaving.value) return
  leaving.value = true
  try {
    await store.dispatch('auth/logout')     // limpa token/user + tenta invalidar no back
    await router.replace({ name: 'Login' }) // sai sem deixar histórico
  } catch (error) {
    console.error('Erro no logout:', error)
    window.location.href = '/login'         // fallback hard
  } finally {
    leaving.value = false
  }
}
</script>

<!-- <script setup>
import { useStore } from 'vuex'
import { useRouter } from 'vue-router'
import { ref } from 'vue'

defineEmits(['toggle-sidebar'])

const store = useStore()
const router = useRouter()
const leaving = ref(false)

const logout = async () => {
  if (leaving.value) return
  leaving.value = true
  try {
    await store.dispatch('auth/logout')
    await router.replace({ name: 'Login' })
  } catch (error) {
    console.error('Erro no logout:', error)
    // fallback hard se algo travar o router:
    window.location.href = '/login'
  } finally {
    leaving.value = false
  }
}
</script>

<template>
  <v-btn
    :loading="leaving"
    color="error"
    variant="outlined"
    @click="logout"
    prepend-icon="mdi-logout"
  >
    Sair
  </v-btn>
</template> -->
