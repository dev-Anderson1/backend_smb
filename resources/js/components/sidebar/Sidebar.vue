<template>
 <main>
  <div>
    <!-- Botão hambúrguer mobile -->
    <button
      @click="toggleMenu"
      class="md:hidden p-2 m-2 text-gray-200 bg-gray-800 rounded focus:outline-none"
      aria-label="Toggle menu"
    >
      <svg v-if="!isOpen" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
        viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
          d="M4 6h16M4 12h16M4 18h16" />
      </svg>
      <svg v-else xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
        viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
          d="M6 18L18 6M6 6l12 12" />
      </svg>
    </button>

    <!-- Sidebar -->
    <aside
      :class="[
        'fixed top-0 left-0 h-full bg-gray-800 text-gray-100 p-4 flex flex-col transition-transform transform z-40',
        isOpen ? 'translate-x-0' : '-translate-x-full',
        'md:static md:translate-x-0 md:flex md:w-64',
        'w-full md:w-64'
      ]"
    >
      <div class="text-2xl font-bold mb-6">
        Minha Sidebar
      </div>
      <nav class="flex flex-wrap flex-col gap-2 flex-1">
        <router-link
          v-for="item in menuItems"
          :key="item.name"
          :to="item.route"
          class="block py-2 px-4 rounded hover:bg-gray-700"
          :class="{ 'bg-gray-700': isActive(item.route) }"
          @click="closeMenuOnMobile"
        >
          {{ item.name }}
        </router-link>
      </nav>
      <footer class="mt-auto text-sm text-gray-400">
        © 2025 Meu Projeto
      </footer>
    </aside>

    <!-- Fundo sombra quando menu aberto no mobile -->
    <div
      v-if="isOpen"
      @click="toggleMenu"
      class="fixed inset-0 bg-black opacity-50 z-30 md:hidden"
    ></div>
  </div>
</main>
</template>

<script>
import { ref } from 'vue'
import { useRoute } from 'vue-router'

export default {
  setup() {
    const isOpen = ref(false)
    const route = useRoute()

    const menuItems = [
      { name: 'Dashboard', route: '/dashboard' },
      { name: 'Usuários', route: '/users' },
      { name: 'Configurações', route: '/settings' }
    ]

    function toggleMenu() {
      isOpen.value = !isOpen.value
    }

    function closeMenuOnMobile() {
      if (window.innerWidth < 768) {
        isOpen.value = false
      }
    }

    function isActive(routePath) {
      return route.path === routePath
    }

    return { menuItems, isActive, isOpen, toggleMenu, closeMenuOnMobile }
  }
}
</script>
