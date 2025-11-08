import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
  plugins: [
    laravel({
      input: ['resources/css/app.css', 'resources/js/app.js'],
      refresh: true,
    }),
    vue({
      template: {
        transformAssetUrls: {
          base: null,
          includeAbsolute: false,
        },
      },
    }),
  ],
  server: {
    host: '0.0.0.0',
    port: 5173,
    hmr: {
      host: 'localhost', // se acessar de outro device/rede, troque para o IP da máquina
      // clientPort: 5173, // ative se o HMR reclamar atrás de proxy/docker
    },
    proxy: {
      // Tudo que começar com /api vai para o Laravel
      '/api': {
        target: 'http://localhost:8000', // <-- AJUSTE AQUI (sua API Laravel)
        changeOrigin: true,
        // se sua API responde em /api mesmo, mantenha. Se a API está na raiz, use:
        // rewrite: (path) => path.replace(/^\/api/, ''),
      },
    },
  },
  resolve: {
    alias: {
      '@': '/resources/js',
    },
  },
});
