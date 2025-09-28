<template>
  <v-navigation-drawer
    :model-value="true"
    :rail="!isOpen"
    permanent
    elevation="4"
    rail-width="64"
    :expand-on-hover="false"
  >
    <!-- Cabeçalho que sempre cabe -->
    <div class="px-2 py-2 flex items-center justify-between">
      <v-avatar
        v-if="isOpen"
        size="36"
        image="/logo.png"
      />
      <v-btn
        variant="text"
        icon
        @click.stop="toggleSidebar"
        :aria-label="isOpen ? 'Recolher menu' : 'Expandir menu'"
      >
        <v-icon :icon="chevronIcon" />
      </v-btn>
    </div>

    <v-divider />

    <v-list>
      <v-list-item
        v-for="item in menuItems"
        :key="item.path"
        :to="item.path"
        :title="item.name"
        :value="item.path"
        active-color="primary"
        link
      >
        <template #prepend>
          <v-icon :icon="item.icon" />
        </template>
      </v-list-item>
    </v-list>

    <template #append>
      <v-divider />
      <v-list>
        <v-list-item
          prepend-avatar="https://cdn.vuetifyjs.com/images/lists/1.jpg"
          :title="userName"
          :subtitle="userRole"
          density="comfortable"
        >
          <template #append>
            <v-menu>
              <template #activator="{ props }">
                <v-btn icon v-bind="props">
                  <v-icon>mdi-dots-vertical</v-icon>
                </v-btn>
              </template>
              <v-list>
                <v-list-item prepend-icon="mdi-account" title="Perfil" @click="goToProfile" />
                <v-list-item prepend-icon="mdi-logout"  title="Sair"   @click="logout" />
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

const props = defineProps({ isOpen: { type: Boolean, required: true } })
const emit  = defineEmits(['toggle-sidebar'])

const store = useStore()
const router = useRouter()

const toggleSidebar = () => emit('toggle-sidebar')

const chevronIcon = computed(() => props.isOpen ? 'mdi-chevron-left' : 'mdi-chevron-right')

const menuItems = [
  { name: 'Dashboard',   path: '/',             icon: 'mdi-view-dashboard' },
  { name: 'Armas',       path: '/armas',        icon: 'mdi-shield' },
  { name: 'Cautelas',    path: '/cautelas',     icon: 'mdi-file-document' },
  { name: 'Munições',    path: '/municoes',     icon: 'mdi-bullet' }, // troque se não existir
  { name: 'Usuários',    path: '/usuarios',     icon: 'mdi-account-group' },
  { name: 'Relatórios',  path: '/relatorios',   icon: 'mdi-chart-box' },
  { name: 'Configurações', path: '/configuracoes', icon: 'mdi-cog' }
]

const userName = computed(() => store.state.user?.name || 'Usuário')
const userRole = computed(() => store.state.user?.role || 'Admin')

const goToProfile = () => router.push('/profile')
const logout = async () => { await store.dispatch('logout'); router.push('/login') }
</script>

<style scoped>
.v-navigation-drawer { transition: width 0.3s ease; }
.router-link-active { position: relative; }
.router-link-active::before {
  content: ''; position: absolute; left: 0; top: 0; bottom: 0;
  width: 4px; background-color: #2563eb; border-radius: 0 4px 4px 0;
}
</style>
