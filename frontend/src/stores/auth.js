import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import api from '@/services/api.js'

const TOKEN_KEY = 'aquaviva_token'

// Role hierarchy for permission checks
const ROLE_HIERARCHY = ['viewer', 'analyst', 'coordinator', 'admin', 'super_admin']

// ─── Demo users (funciona sin backend) ────────────────────────────────────────
const DEMO_USERS = [
  {
    email: 'admin@aquaviva.com',
    password: 'Aquaviva2025!',
    user: { id: 1, name: 'Admin Aquaviva', email: 'admin@aquaviva.com', role: 'super_admin', company_id: 1, company: 'Aquaviva SAS' },
    token: 'demo-token-super-admin',
  },
  {
    email: 'coordinador@aquaviva.com',
    password: 'Aquaviva2025!',
    user: { id: 2, name: 'Laura Torres', email: 'coordinador@aquaviva.com', role: 'coordinator', company_id: 2, company: 'Constructora Bogotá SAS' },
    token: 'demo-token-coordinator',
  },
  {
    email: 'analista@aquaviva.com',
    password: 'Aquaviva2025!',
    user: { id: 3, name: 'Mario Ramírez', email: 'analista@aquaviva.com', role: 'analyst', company_id: 2, company: 'Constructora Bogotá SAS' },
    token: 'demo-token-analyst',
  },
]

export const useAuthStore = defineStore('auth', () => {
  // ─── State ─────────────────────────────────────────────────────────────
  const user = ref(null)
  const token = ref(null)
  const isAuthenticated = ref(false)

  // ─── Getters ───────────────────────────────────────────────────────────
  const userRole = computed(() => user.value?.role ?? null)
  const userCompanyId = computed(() => user.value?.company_id ?? null)
  const userInitials = computed(() => {
    if (!user.value?.name) return 'AV'
    return user.value.name
      .split(' ')
      .slice(0, 2)
      .map((n) => n[0].toUpperCase())
      .join('')
  })

  /** Returns true if the current user's role is equal to or higher than the given role */
  const hasRole = computed(() => (role) => {
    if (!userRole.value) return false
    const userIdx = ROLE_HIERARCHY.indexOf(userRole.value)
    const requiredIdx = ROLE_HIERARCHY.indexOf(role)
    return userIdx >= requiredIdx
  })

  /** Check a named permission (maps to role checks for now; expand when backend returns permissions array) */
  const can = computed(() => (permission) => {
    if (!userRole.value) return false
    const permissionMap = {
      'manage_companies': ['super_admin'],
      'manage_users': ['admin', 'super_admin'],
      'manage_programs': ['admin', 'super_admin', 'coordinator'],
      'manage_obligations': ['admin', 'super_admin', 'coordinator'],
      'manage_worksheets': ['admin', 'super_admin', 'coordinator'],
      'manage_indicators': ['admin', 'super_admin', 'coordinator', 'analyst'],
      'manage_activities': ['admin', 'super_admin', 'coordinator', 'analyst'],
      'upload_evidence': ['admin', 'super_admin', 'coordinator', 'analyst'],
      'view_reports': ['admin', 'super_admin', 'coordinator', 'analyst', 'viewer'],
    }
    const allowed = permissionMap[permission] ?? []
    return allowed.includes(userRole.value)
  })

  // ─── Actions ───────────────────────────────────────────────────────────

  /**
   * Restore session from localStorage on app init.
   * Sets the token and attempts to fetch the current user.
   */
  function restoreSession() {
    const stored = localStorage.getItem(TOKEN_KEY)
    if (!stored) return

    // Demo token: restore user from DEMO_USERS without hitting the API
    const demo = DEMO_USERS.find((u) => u.token === stored)
    if (demo) {
      token.value = demo.token
      user.value = demo.user
      isAuthenticated.value = true
      return
    }

    // Real token: attempt to refresh user from API
    token.value = stored
    isAuthenticated.value = true
    fetchCurrentUser().catch(() => {
      logout()
    })
  }

  /** POST /api/v1/auth/login — con fallback demo para preview sin backend */
  async function login(credentials) {
    // Demo mode: credentials match a DEMO_USERS entry → no API call needed
    const demo = DEMO_USERS.find(
      (u) => u.email === credentials.email && u.password === credentials.password
    )
    if (demo) {
      token.value = demo.token
      user.value = demo.user
      isAuthenticated.value = true
      localStorage.setItem(TOKEN_KEY, demo.token)
      return demo.user
    }

    // Normal flow: hit the real API
    const response = await api.post('/api/v1/auth/login', credentials)
    const { access_token, user: userData } = response.data.data ?? response.data

    token.value = access_token
    user.value = userData
    isAuthenticated.value = true

    localStorage.setItem(TOKEN_KEY, access_token)
    return userData
  }

  /** POST /api/v1/auth/logout */
  async function logout() {
    try {
      if (token.value) {
        await api.post('/api/v1/auth/logout')
      }
    } catch {
      // Ignore errors — clear local state regardless
    } finally {
      token.value = null
      user.value = null
      isAuthenticated.value = false
      localStorage.removeItem(TOKEN_KEY)
    }
  }

  /** GET /api/v1/auth/me — fetch current user profile */
  async function fetchCurrentUser() {
    const response = await api.get('/api/v1/auth/me')
    user.value = response.data.data ?? response.data
    return user.value
  }

  return {
    // State
    user,
    token,
    isAuthenticated,
    // Getters
    userRole,
    userCompanyId,
    userInitials,
    hasRole,
    can,
    // Actions
    login,
    logout,
    restoreSession,
    fetchCurrentUser,
  }
})
