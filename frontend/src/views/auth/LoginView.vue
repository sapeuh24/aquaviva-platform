<script setup>
import { ref, reactive } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import { useAuthStore } from '@/stores/auth.js'

const router = useRouter()
const route = useRoute()
const authStore = useAuthStore()

const form = reactive({
  email: '',
  password: '',
})

const demoUsers = [
  { email: 'admin@aquaviva.com',        label: 'Super Admin'  },
  { email: 'coordinador@aquaviva.com',  label: 'Coordinador'  },
  { email: 'analista@aquaviva.com',     label: 'Analista'     },
]

function fillDemo(u) {
  form.email    = u.email
  form.password = 'Aquaviva2025!'
}
const loading = ref(false)
const error = ref(null)

async function handleLogin() {
  error.value = null
  loading.value = true
  try {
    await authStore.login({ email: form.email, password: form.password })
    const redirect = route.query.redirect ?? '/dashboard'
    router.push(redirect)
  } catch (err) {
    const msg = err.response?.data?.message ?? err.response?.data?.error
    error.value = msg ?? 'Credenciales incorrectas. Intente de nuevo.'
  } finally {
    loading.value = false
  }
}
</script>

<template>
  <div
    class="rounded-xl p-8"
    style="background:white;border:1px solid #E4E8E6;box-shadow:0 4px 24px rgba(0,0,0,.18);"
  >
    <h2 class="font-bold mb-1" style="font-size:20px;color:#1C2925;">
      Iniciar sesión
    </h2>
    <p class="mb-6" style="font-size:13px;color:#6B7D76;">
      Accede a tu cuenta de gestión ambiental
    </p>

    <!-- Error message -->
    <div
      v-if="error"
      class="mb-4 rounded-md px-3 py-2.5 text-sm"
      style="background:#FFF5F5;border:1px solid #FECACA;color:#991B1B;"
    >
      {{ error }}
    </div>

    <form @submit.prevent="handleLogin" class="space-y-4">
      <!-- Email -->
      <div>
        <label class="block mb-1 font-medium" style="font-size:12.5px;color:#4A5C55;">
          Correo electrónico
        </label>
        <input
          v-model="form.email"
          type="email"
          required
          autocomplete="email"
          placeholder="usuario@empresa.com"
          class="w-full rounded-md px-3 border outline-none transition-all"
          style="height:38px;font-size:13px;background:#F7F9F8;border-color:#D1D9D5;color:#1C2925;"
          @focus="(e) => { e.target.style.borderColor='#3A9A64'; e.target.style.background='#fff'; e.target.style.boxShadow='0 0 0 3px #DCF0E4'; }"
          @blur="(e) => { e.target.style.borderColor='#D1D9D5'; e.target.style.background='#F7F9F8'; e.target.style.boxShadow='none'; }"
        />
      </div>

      <!-- Password -->
      <div>
        <label class="block mb-1 font-medium" style="font-size:12.5px;color:#4A5C55;">
          Contraseña
        </label>
        <input
          v-model="form.password"
          type="password"
          required
          autocomplete="current-password"
          placeholder="••••••••"
          class="w-full rounded-md px-3 border outline-none transition-all"
          style="height:38px;font-size:13px;background:#F7F9F8;border-color:#D1D9D5;color:#1C2925;"
          @focus="(e) => { e.target.style.borderColor='#3A9A64'; e.target.style.background='#fff'; e.target.style.boxShadow='0 0 0 3px #DCF0E4'; }"
          @blur="(e) => { e.target.style.borderColor='#D1D9D5'; e.target.style.background='#F7F9F8'; e.target.style.boxShadow='none'; }"
        />
      </div>

      <!-- Submit -->
      <button
        type="submit"
        :disabled="loading"
        class="w-full rounded-md font-semibold text-white transition-all mt-2"
        style="height:40px;font-size:13px;background:#2D7A50;border:none;"
        :style="loading ? 'opacity:.7;cursor:not-allowed;' : 'cursor:pointer;'"
        @mouseenter="(e) => { if (!loading) e.target.style.background='#246040'; }"
        @mouseleave="(e) => { e.target.style.background='#2D7A50'; }"
      >
        {{ loading ? 'Iniciando sesión...' : 'Ingresar' }}
      </button>
    </form>

    <!-- Demo credentials hint -->
    <div class="mt-5 rounded-md" style="background:#F2FAF5;border:1px solid #DCF0E4;padding:12px 14px;">
      <p style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.8px;color:#3A9A64;margin-bottom:8px;">
        🔑 Credenciales de prueba
      </p>
      <div style="display:grid;gap:4px;">
        <div
          v-for="u in demoUsers"
          :key="u.email"
          style="display:flex;align-items:center;justify-content:space-between;cursor:pointer;padding:5px 8px;border-radius:5px;transition:background .12s;"
          @mouseenter="(e) => e.currentTarget.style.background='#DCF0E4'"
          @mouseleave="(e) => e.currentTarget.style.background='transparent'"
          @click="fillDemo(u)"
        >
          <span style="font-size:11.5px;color:#246040;font-weight:600;">{{ u.email }}</span>
          <span style="font-size:10.5px;color:#6B7D76;background:#E8F5EE;padding:1px 6px;border-radius:4px;">{{ u.label }}</span>
        </div>
      </div>
      <p style="font-size:10.5px;color:#6B7D76;margin-top:6px;">Contraseña: <strong style="color:#1C2925;">Aquaviva2025!</strong> — clic en usuario para autocompletar</p>
    </div>
  </div>
</template>
