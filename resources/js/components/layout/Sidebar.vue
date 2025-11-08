<!-- src/components/layout/Sidebar.vue -->
<template>
  <v-navigation-drawer
    :model-value="isOpen"
    :rail="!isOpen"
    permanent
    elevation="4"
    rail-width="64"
    width="256"
    app
  >
    <!-- Cabeçalho -->
    <div class="d-flex align-center justify-space-between pa-2">
      <v-avatar
        v-if="isOpen"
        size="36"
        color="primary"
      >
        <v-icon color="white">mdi-shield</v-icon>
      </v-avatar>
      
      <v-btn
        variant="text"
        icon
        @click="toggleSidebar"
        size="small"
        :class="{ 'mx-auto': !isOpen }"
      >
        <v-icon>{{ chevronIcon }}</v-icon>
      </v-btn>
    </div>

    <v-divider />

    <!-- Menu Items -->
    <v-list nav>
      <v-list-item
        v-for="item in menuItems"
        :key="item.path"
        :to="item.path"
        :title="item.name"
        :value="item.path"
        active-color="primary"
        rounded="xl"
        class="ma-1"
      >
        <template #prepend>
          <v-icon>{{ item.icon }}</v-icon>
        </template>
      </v-list-item>
    </v-list>

    <!-- User section no final -->
    <template #append>
      <v-divider />
      <v-list>
        <v-list-item
          :title="userName"
          :subtitle="userRole"
          density="comfortable"
        >
          <template #prepend>
            <v-avatar color="primary" size="32">
              <v-icon color="white">mdi-account</v-icon>
            </v-avatar>
          </template>
          
          <template #append>
            <v-menu v-if="isOpen">
              <template>
                <v-btn icon size="small" v-bind="props">
                  <v-icon>mdi-dots-vertical</v-icon>
                  

                </v-btn>
              </template>
              <v-list>
                <v-list-item 
                  prepend-icon="mdi-account" 
                  title="Perfil" 
                  @click="goToProfile"
                />
                <v-list-item 
                  prepend-icon="mdi-logout"  
                  title="Sair"   
                  @click="logout"
                />
              </v-list>
            </v-menu>
          </template>
        </v-list-item>
      </v-list>
    </template>
  </v-navigation-drawer>
</template>

<script setup>
import { computed } from 'vue'
import { useRouter } from 'vue-router'
import { useStore } from 'vuex'

const props = defineProps({
  isOpen: { 
    type: Boolean, 
    required: true 
  }
})

const emit = defineEmits(['toggle-sidebar'])
const store = useStore()
const router = useRouter()

const toggleSidebar = () => emit('toggle-sidebar')

const chevronIcon = computed(() => 
  props.isOpen ? 'mdi-chevron-left' : 'mdi-chevron-right'
)

const menuItems = [
  { name: 'Dashboard', path: '/dashboard', icon: 'mdi-view-dashboard' },
  { name: 'Armas', path: '/armas', icon: 'mdi-pistol' },
  { name: 'Cautelas', path: '/cautelas', icon: 'mdi-file-document' },
  { name: 'Munições', path: '/municoes', icon: 'mdi-ammunition' },
  { name: 'Usuários', path: '/usuarios', icon: 'mdi-account-group' },
  { name: 'Relatórios', path: '/relatorios', icon: 'mdi-chart-box' },
  { name: 'Configurações', path: '/configuracoes', icon: 'mdi-cog' }
]

const userName = computed(() => store.state.auth?.user?.name || 'Usuário')
const userRole = computed(() => store.state.auth?.user?.role || 'Admin')

const goToProfile = () => router.push('/profile')

const logout = async () => {
  await store.dispatch('auth/logout')
  router.replace({ name: 'Login' })
}
</script>
