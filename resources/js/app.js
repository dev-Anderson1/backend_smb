import { createApp } from 'vue';
import { createVuetify } from 'vuetify';
import * as components from 'vuetify/components';
import * as directives from 'vuetify/directives';
import 'vuetify/styles';
import '@mdi/font/css/materialdesignicons.css';

import App from './App.vue';
import router from './router';
import store from './store';

const vuetify = createVuetify({ components, directives });

const app = createApp(App);
app.use(router);
app.use(store);
app.use(vuetify);
app.mount('#app');

// DEBUG (opcional): ajuda a verificar no console
window.__router = router;
window.__store = store;
console.log('[BOOT] rotas:', router.getRoutes().map(r => ({name:r.name, path:r.path})));
