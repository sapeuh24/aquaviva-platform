import { createRouter, createWebHistory } from 'vue-router'
import { useAuthStore } from '@/stores/auth.js'

// ─── Route meta titles and breadcrumbs ────────────────────────────────────
// title: shown in topbar
// section: breadcrumb parent label
// layout: 'auth' uses AuthLayout; default uses AppLayout
// requiresAuth: true enforces login guard
// roles: array of allowed roles (empty = all authenticated roles)

const routes = [
  // ── Auth ──────────────────────────────────────────────────────────────
  {
    path: '/login',
    name: 'login',
    component: () => import('@/views/auth/LoginView.vue'),
    meta: { layout: 'auth', title: 'Iniciar sesión', requiresAuth: false },
  },

  // ── Root redirect ─────────────────────────────────────────────────────
  {
    path: '/',
    redirect: '/dashboard',
  },

  // ── Principal ─────────────────────────────────────────────────────────
  {
    path: '/dashboard',
    name: 'dashboard',
    component: () => import('@/views/dashboard/DashboardView.vue'),
    meta: {
      requiresAuth: true,
      title: 'Panel de Control',
      section: 'Inicio',
      roles: [],
    },
  },
  {
    path: '/alertas',
    name: 'alertas',
    component: () => import('@/views/AlertsView.vue'),
    meta: {
      requiresAuth: true,
      title: 'Centro de Alertas',
      section: 'Inicio',
      roles: [],
    },
  },

  // ── Estructura Ambiental ───────────────────────────────────────────────
  {
    path: '/programas',
    name: 'programas',
    component: () => import('@/views/programs/ProgramsView.vue'),
    meta: {
      requiresAuth: true,
      title: 'Programas Ambientales',
      section: 'Estructura Ambiental',
      roles: [],
    },
  },
  {
    path: '/obligaciones',
    name: 'obligaciones',
    component: () => import('@/views/ObligationsView.vue'),
    meta: {
      requiresAuth: true,
      title: 'Obligaciones Ambientales',
      section: 'Estructura Ambiental',
      roles: [],
    },
  },
  {
    path: '/fichas',
    name: 'fichas',
    component: () => import('@/views/monitoring/WorksheetsView.vue'),
    meta: {
      requiresAuth: true,
      title: 'Fichas de Monitoreo',
      section: 'Estructura Ambiental',
      roles: [],
    },
  },
  {
    path: '/indicadores',
    name: 'indicadores',
    component: () => import('@/views/indicators/IndicatorsView.vue'),
    meta: {
      requiresAuth: true,
      title: 'Indicadores',
      section: 'Estructura Ambiental',
      roles: [],
    },
  },

  // ── Seguimiento ───────────────────────────────────────────────────────
  {
    path: '/proyectos',
    name: 'proyectos',
    component: () => import('@/views/projects/ProjectsView.vue'),
    meta: {
      requiresAuth: true,
      title: 'Proyectos',
      section: 'Seguimiento',
      roles: [],
    },
  },
  {
    path: '/actividades',
    name: 'actividades',
    component: () => import('@/views/ActivitiesView.vue'),
    meta: {
      requiresAuth: true,
      title: 'Actividades',
      section: 'Seguimiento',
      roles: [],
    },
  },
  {
    path: '/evidencias',
    name: 'evidencias',
    component: () => import('@/views/evidence/EvidencesView.vue'),
    meta: {
      requiresAuth: true,
      title: 'Evidencias',
      section: 'Seguimiento',
      roles: [],
    },
  },

  // ── Administración ────────────────────────────────────────────────────
  {
    path: '/organigrama',
    name: 'organigrama',
    component: () => import('@/views/admin/OrgChartView.vue'),
    meta: {
      requiresAuth: true,
      title: 'Organigrama',
      section: 'Administración',
      roles: ['admin', 'super_admin'],
    },
  },
  {
    path: '/empresas',
    name: 'empresas',
    component: () => import('@/views/admin/CompaniesView.vue'),
    meta: {
      requiresAuth: true,
      title: 'Empresas',
      section: 'Administración',
      roles: ['super_admin'],
    },
  },
  {
    path: '/usuarios',
    name: 'usuarios',
    component: () => import('@/views/admin/UsersView.vue'),
    meta: {
      requiresAuth: true,
      title: 'Usuarios',
      section: 'Administración',
      roles: ['admin', 'super_admin'],
    },
  },

  // ── Catch-all ─────────────────────────────────────────────────────────
  {
    path: '/:pathMatch(.*)*',
    redirect: '/dashboard',
  },
]

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes,
})

// ─── Navigation guards ────────────────────────────────────────────────────
router.beforeEach((to, _from, next) => {
  const authStore = useAuthStore()

  // Route requires authentication
  if (to.meta.requiresAuth && !authStore.isAuthenticated) {
    return next({ name: 'login', query: { redirect: to.fullPath } })
  }

  // Already logged in and trying to access login page
  if (to.name === 'login' && authStore.isAuthenticated) {
    return next({ name: 'dashboard' })
  }

  // Role-based guard
  const allowedRoles = to.meta.roles
  if (allowedRoles && allowedRoles.length > 0 && authStore.isAuthenticated) {
    if (!allowedRoles.includes(authStore.userRole)) {
      // Redirect to dashboard if role is insufficient
      return next({ name: 'dashboard' })
    }
  }

  next()
})

export default router
