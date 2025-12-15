import { createApp } from 'vue'
import { ZiggyVue } from 'ziggy-js'
import { Ziggy } from './ziggy'
import { createPinia } from 'pinia'
import piniaPluginPersistedstate from 'pinia-plugin-persistedstate'
import App from './App.vue'
import router from './router'
import './assets/global.css'

const pinia = createPinia()
pinia.use(piniaPluginPersistedstate)

const app = createApp(App)
app.use(ZiggyVue, Ziggy)

app.use(pinia)
app.use(router)

app.mount('#app')
