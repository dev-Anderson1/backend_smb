<template>
  <nav class="navbar fixed top-0 right-0 bg-white shadow-md transition-all duration-300">
    <!-- ...seu conteúdo (botão hambúrguer, título, usuário etc.) -->
  </nav>
</template>

<script>
// igual ao seu, sem mudanças na lógica
</script>

<style scoped>
.navbar {
  /* desloca o Navbar exatamente a largura atual do drawer */
  margin-left: var(--v-layout-left);
  width: calc(100% - var(--v-layout-left));
  height: 64px; /* combine com a pt-16 do v-main */
}
</style>
