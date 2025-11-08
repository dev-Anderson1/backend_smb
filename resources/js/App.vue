<template>
  <v-app>
    <template v-if="isAuthenticated">
      <Navbar :is-sidebar-open="isOpen" @toggle-sidebar="isOpen = !isOpen" />
      <Sidebar :is-open="isOpen" @toggle-sidebar="isOpen = !isOpen" />
      <v-main class="pt-16"><router-view /></v-main>
    </template>
    <template v-else>
      <v-main><router-view /></v-main>
    </template>
  </v-app>
</template>

<script setup>
import { ref, computed } from 'vue';
import { useStore } from 'vuex';
import Navbar from '@/components/layout/Navbar.vue';
import Sidebar from '@/components/layout/Sidebar.vue';

const store = useStore();
const isOpen = ref(true);
const isAuthenticated = computed(() => store.getters['auth/isAuthenticated']);
</script>
