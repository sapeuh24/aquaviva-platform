<script setup>
import { computed } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { storeToRefs } from 'pinia'
import { useAuthStore } from '@/stores/auth.js'
import { usePermissions } from '@/composables/usePermissions.js'

const route = useRoute()
const router = useRouter()
const authStore = useAuthStore()
const { user, userInitials } = storeToRefs(authStore)
const { isSuperAdmin, isAdmin, roleLabel } = usePermissions()

// ─── Navigation items grouped by section ──────────────────────────────────
const navSections = [
  {
    label: 'Principal',
    items: [
      {
        name: 'dashboard',
        path: '/dashboard',
        label: 'Dashboard',
        badge: null,
        icon: `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>`,
      },
      {
        name: 'alertas',
        path: '/alertas',
        label: 'Alertas',
        badge: 'alertBadge',
        icon: `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>`,
      },
    ],
  },
  {
    label: 'Estructura Ambiental',
    items: [
      {
        name: 'compromisos',
        path: '/compromisos',
        label: 'Compromisos',
        icon: `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>`,
      },
      {
        name: 'programas',
        path: '/programas',
        label: 'Prog. Ambientales',
        icon: `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg>`,
      },
      {
        name: 'indicadores',
        path: '/indicadores',
        label: 'Indicadores',
        icon: `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>`,
      },
    ],
  },
  {
    label: 'Seguimiento',
    items: [
      {
        name: 'proyectos',
        path: '/proyectos',
        label: 'Proy./Instalación',
        icon: `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/></svg>`,
      },
      {
        name: 'acciones',
        path: '/acciones',
        label: 'Acciones',
        icon: `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg>`,
      },
      {
        name: 'avances',
        path: '/avances',
        label: 'Avances',
        icon: `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>`,
      },
      {
        name: 'evidencias',
        path: '/evidencias',
        label: 'Evidencias',
        icon: `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>`,
      },
      {
        name: 'comunicados',
        path: '/comunicados',
        label: 'Comunicados',
        icon: `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>`,
      },
      {
        name: 'asignaciones',
        path: '/asignaciones',
        label: 'Asignaciones',
        icon: `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/><rect x="8" y="2" width="8" height="4" rx="1" ry="1"/><path d="M9 14l2 2 4-4"/></svg>`,
      },
    ],
  },
  {
    label: 'Administración',
    adminOnly: true,
    items: [
      {
        name: 'organigrama',
        path: '/organigrama',
        label: 'Organigrama',
        roles: ['admin', 'super_admin'],
        icon: `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="8" y="2" width="8" height="4" rx="1"/><rect x="1" y="17" width="6" height="4" rx="1"/><rect x="9" y="17" width="6" height="4" rx="1"/><rect x="17" y="17" width="6" height="4" rx="1"/><path d="M12 6v4M4 17v-4h16v4"/></svg>`,
      },
      {
        name: 'empresas',
        path: '/empresas',
        label: 'Empresas',
        roles: ['super_admin'],
        icon: `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>`,
      },
      {
        name: 'usuarios',
        path: '/usuarios',
        label: 'Usuarios',
        roles: ['admin', 'super_admin'],
        icon: `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>`,
      },
    ],
  },
]

// ─── Active route check ────────────────────────────────────────────────────
const isActive = (path) => route.path === path

// ─── Role visibility helpers ───────────────────────────────────────────────
const userRoleValue = computed(() => authStore.userRole)

function itemVisible(item) {
  if (!item.roles || item.roles.length === 0) return true
  return item.roles.includes(userRoleValue.value)
}

function sectionVisible(section) {
  if (!section.adminOnly) return true
  return isAdmin.value
}

// ─── Navigate ─────────────────────────────────────────────────────────────
function navigate(path) {
  router.push(path)
}

// ─── Logout ───────────────────────────────────────────────────────────────
async function handleLogout() {
  await authStore.logout()
  router.push({ name: 'login' })
}

// ─── Alert badge (placeholder — will come from a store later) ─────────────
const alertBadge = 3
</script>

<template>
  <aside
    class="fixed top-0 left-0 z-[100] flex flex-col"
    style="width: 252px; min-height: 100vh; background: #1A3D2B;"
  >
    <!-- Logo section -->
    <div class="px-4 pt-4 pb-[14px]" style="border-bottom: 1px solid rgba(255,255,255,.1);">
      <div class="flex items-center gap-2.5">
        <!-- Logo mark -->
        <div
          class="flex-shrink-0 flex items-center justify-center rounded-lg font-black text-white"
          style="width:34px;height:34px;background:rgba(255,255,255,.15);font-size:13px;letter-spacing:.5px;"
        >
          AV
        </div>
        <div>
          <div class="font-extrabold text-white" style="font-size:15px;letter-spacing:.3px;">
            AQUAVIVA
          </div>
          <div style="font-size:10.5px;color:rgba(255,255,255,.5);margin-top:1px;">
            Gestión Ambiental
          </div>
        </div>
      </div>
    </div>

    <!-- Company bar -->
    <div
      class="mx-3 mt-2.5 rounded-md flex items-center gap-2"
      style="background:rgba(0,0,0,.2);padding:8px 10px;"
    >
      <span
        class="flex-shrink-0 rounded-full"
        style="width:7px;height:7px;background:#5CB87A;"
      />
      <span
        class="truncate font-medium"
        style="font-size:11.5px;color:rgba(255,255,255,.8);"
      >
        {{ user?.company?.name ?? 'Aquaviva SAS' }}
      </span>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 overflow-y-auto px-2 py-2.5">
      <template v-for="section in navSections" :key="section.label">
        <!-- Section guard -->
        <template v-if="sectionVisible(section)">
          <!-- Section label -->
          <div
            class="px-2.5 uppercase font-bold"
            style="font-size:9.5px;letter-spacing:1.4px;color:rgba(255,255,255,.35);padding-top:12px;padding-bottom:4px;"
          >
            {{ section.label }}
          </div>

          <!-- Nav items -->
          <template v-for="item in section.items" :key="item.name">
            <div
              v-if="itemVisible(item)"
              class="flex items-center gap-[9px] rounded-md cursor-pointer relative select-none"
              :class="[
                isActive(item.path)
                  ? 'sb-item-active-indicator'
                  : '',
              ]"
              :style="[
                'padding: 8px 10px;',
                'font-size: 13px;',
                'font-weight: ' + (isActive(item.path) ? '600' : '500') + ';',
                'color: ' + (isActive(item.path) ? '#fff' : 'rgba(255,255,255,.65)') + ';',
                'background: ' + (isActive(item.path) ? 'rgba(255,255,255,.14)' : 'transparent') + ';',
                'transition: background .18s ease, color .18s ease;',
              ]"
              @click="navigate(item.path)"
              @mouseenter="(e) => { if (!isActive(item.path)) e.currentTarget.style.background = 'rgba(255,255,255,.08)'; e.currentTarget.style.color = 'rgba(255,255,255,.9)' }"
              @mouseleave="(e) => { e.currentTarget.style.background = isActive(item.path) ? 'rgba(255,255,255,.14)' : 'transparent'; e.currentTarget.style.color = isActive(item.path) ? '#fff' : 'rgba(255,255,255,.65)' }"
            >
              <!-- Icon -->
              <span
                class="flex-shrink-0"
                :style="'opacity:' + (isActive(item.path) ? '1' : '.8') + ';'"
                style="width:15px;height:15px;display:flex;align-items:center;"
                v-html="item.icon"
              />
              <!-- Label -->
              <span class="flex-1 leading-none">{{ item.label }}</span>
              <!-- Badge (Alertas only) -->
              <span
                v-if="item.badge && alertBadge > 0"
                class="flex items-center text-white font-bold rounded-[9px]"
                style="margin-left:auto;background:#DC2626;font-size:10px;padding:0 6px;height:18px;"
              >
                {{ alertBadge }}
              </span>
            </div>
          </template>

          <!-- Divider after section -->
          <div style="height:1px;background:rgba(255,255,255,.08);margin:6px 10px;" />
        </template>
      </template>
    </nav>

    <!-- User footer -->
    <div
      class="flex items-center gap-[9px] px-3 py-2.5"
      style="border-top:1px solid rgba(255,255,255,.08);"
    >
      <!-- Avatar initials -->
      <div
        class="flex-shrink-0 rounded-full flex items-center justify-center font-bold text-white"
        style="width:30px;height:30px;background:#246040;font-size:11px;border:2px solid rgba(255,255,255,.2);"
      >
        {{ userInitials }}
      </div>
      <!-- Name + role -->
      <div class="flex-1 min-w-0">
        <div class="truncate font-semibold text-white" style="font-size:12.5px;">
          {{ user?.name ?? 'Usuario' }}
        </div>
        <div class="truncate" style="font-size:10.5px;color:rgba(255,255,255,.45);">
          {{ roleLabel }}
        </div>
      </div>
      <!-- Logout button -->
      <button
        class="flex-shrink-0 flex items-center justify-center rounded-md transition-colors"
        style="width:26px;height:26px;background:rgba(255,255,255,.08);"
        title="Cerrar sesión"
        @click="handleLogout"
        @mouseenter="(e) => e.currentTarget.style.background = 'rgba(220,38,38,.3)'"
        @mouseleave="(e) => e.currentTarget.style.background = 'rgba(255,255,255,.08)'"
      >
        <!-- Logout icon (eject symbol) -->
        <svg
          width="12"
          height="12"
          viewBox="0 0 24 24"
          fill="none"
          stroke="rgba(255,255,255,.7)"
          stroke-width="2"
        >
          <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" />
          <polyline points="16 17 21 12 16 7" />
          <line x1="21" y1="12" x2="9" y2="12" />
        </svg>
      </button>
    </div>
  </aside>
</template>
