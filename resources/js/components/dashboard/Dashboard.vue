<!-- src/layouts/DefaultLayout.vue -->
<template>
  <v-app>
   
    <v-main>
      <router-view />
     
    </v-main>
   
  </v-app>
</template>

<script setup>
import { ref } from 'vue'


const isOpen = ref(true)
const toggleSidebar = () => { isOpen.value = !isOpen.value }
</script>
