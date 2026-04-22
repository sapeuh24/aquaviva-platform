import { createApp } from 'vue'
import { createPinia } from 'pinia'
import PrimeVue from 'primevue/config'
import Aura from '@primeuix/themes/aura'
import ToastService from 'primevue/toastservice'
import ConfirmationService from 'primevue/confirmationservice'

import App from './App.vue'
import router from './router/index.js'
import { useAuthStore } from './stores/auth.js'
import './assets/main.css'

const app = createApp(App)
const pinia = createPinia()

// ─── v-click-outside directive ────────────────────────────────────────────
const clickOutside = {
  beforeMount(el, binding) {
    el._clickOutsideHandler = (event) => {
      if (!el.contains(event.target)) {
        binding.value(event)
      }
    }
    document.addEventListener('mousedown', el._clickOutsideHandler)
  },
  unmounted(el) {
    document.removeEventListener('mousedown', el._clickOutsideHandler)
  },
}

app.use(pinia)
app.use(router)
app.directive('click-outside', clickOutside)
app.use(PrimeVue, {
  theme: {
    preset: Aura,
    options: {
      darkModeSelector: '.dark', // disabled by default
    },
  },
})
app.use(ToastService)
app.use(ConfirmationService)

// Restore auth state from localStorage before mounting
const authStore = useAuthStore()
authStore.restoreSession()

app.mount('#app')
