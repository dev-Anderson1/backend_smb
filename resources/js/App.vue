<template>
  <v-app>
    <Navbar :is-sidebar-open="isOpen" @toggle-sidebar="isOpen = !isOpen" />
    <Sidebar :is-open="isOpen" @toggle-sidebar="isOpen = !isOpen" />

    <!-- Altura do Navbar = 64px (h-16). Empurra o conteúdo pra baixo -->
    <v-main class="pt-16">
      <router-view />
    </v-main>
  </v-app>
</template>

<script setup>
import { ref } from 'vue'
import Navbar from '@/components/layout/Navbar.vue'
import Sidebar from '@/components/layout/Sidebar.vue'

const isOpen = ref(true) // true = aberto (256px). false = rail (56px por padrão)
</script>
