import axios from 'axios'
import router from '@/router/index.js'

const api = axios.create({
  baseURL: import.meta.env.VITE_API_URL || 'http://localhost:8000',
  headers: {
    'Content-Type': 'application/json',
    Accept: 'application/json',
  },
  withCredentials: false,
})

// ─── Request interceptor: attach Bearer token ─────────────────────────────
api.interceptors.request.use(
  (config) => {
    const token = localStorage.getItem('aquaviva_token')
    if (token) {
      config.headers.Authorization = `Bearer ${token}`
    }
    return config
  },
  (error) => Promise.reject(error),
)

// ─── Response interceptor: handle 401 globally ───────────────────────────
api.interceptors.response.use(
  (response) => response,
  (error) => {
    if (error.response?.status === 401) {
      localStorage.removeItem('aquaviva_token')
      router.push({ name: 'login' })
    }
    return Promise.reject(error)
  },
)

export default api
