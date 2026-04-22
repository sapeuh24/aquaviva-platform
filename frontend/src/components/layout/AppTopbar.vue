<script setup>
import { ref, computed } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { storeToRefs } from 'pinia'
import { useAuthStore } from '@/stores/auth.js'

const route = useRoute()
const router = useRouter()
const authStore = useAuthStore()
const { user, userInitials } = storeToRefs(authStore)

// ─── Page title & breadcrumb from route meta ────────────────────────────
const pageTitle = computed(() => route.meta.title ?? '')
const pageSection = computed(() => route.meta.section ?? 'Inicio')

// ─── User dropdown ────────────────────────────────────────────────────────
const dropdownOpen = ref(false)

function toggleDropdown() {
  dropdownOpen.value = !dropdownOpen.value
}

function closeDropdown() {
  dropdownOpen.value = false
}

async function handleLogout() {
  closeDropdown()
  await authStore.logout()
  router.push({ name: 'login' })
}

// ─── Notification badge (placeholder) ────────────────────────────────────
const notifCount = 3
</script>

<template>
  <header
    class="sticky top-0 z-50 flex items-center justify-between bg-white"
    style="height:56px;padding:0 24px;border-bottom:1px solid #E4E8E6;box-shadow:0 1px 3px rgba(0,0,0,.08),0 1px 2px rgba(0,0,0,.04);"
  >
    <!-- Left: title + breadcrumb -->
    <div class="flex flex-col">
      <span class="font-bold" style="font-size:15px;color:#1C2925;">
        {{ pageTitle }}
      </span>
      <span style="font-size:11.5px;color:#9FADA7;margin-top:1px;">
        <span style="color:#2D7A50;">Inicio</span>
        <template v-if="pageSection !== 'Inicio'"> / {{ pageSection }}</template>
        <template v-if="pageTitle"> / {{ pageTitle }}</template>
      </span>
    </div>

    <!-- Right: search + notifications + user -->
    <div class="flex items-center gap-2.5">
      <!-- Search input -->
      <div
        class="flex items-center gap-[7px] rounded-md transition-all"
        style="background:#F0F2F1;border:1px solid #E4E8E6;padding:0 11px;height:34px;width:210px;"
        @focusin="(e) => { e.currentTarget.style.borderColor = '#3A9A64'; e.currentTarget.style.background = '#fff'; e.currentTarget.style.boxShadow = '0 0 0 3px #DCF0E4'; }"
        @focusout="(e) => { e.currentTarget.style.borderColor = '#E4E8E6'; e.currentTarget.style.background = '#F0F2F1'; e.currentTarget.style.boxShadow = 'none'; }"
      >
        <svg
          width="13"
          height="13"
          viewBox="0 0 24 24"
          fill="none"
          stroke="#9FADA7"
          stroke-width="2"
          style="flex-shrink:0;"
        >
          <circle cx="11" cy="11" r="8" />
          <path d="m21 21-4.35-4.35" />
        </svg>
        <input
          type="text"
          placeholder="Buscar..."
          class="bg-transparent border-none outline-none flex-1"
          style="color:#2E3D38;font-size:13px;"
        />
      </div>

      <!-- Notification bell -->
      <button
        class="relative flex items-center justify-center rounded-md transition-all"
        style="width:34px;height:34px;background:#F0F2F1;border:1px solid #E4E8E6;color:#4A5C55;"
        title="Alertas"
        @click="router.push('/alertas')"
        @mouseenter="(e) => { e.currentTarget.style.borderColor = '#CDD5D0'; e.currentTarget.style.background = '#E4E8E6'; }"
        @mouseleave="(e) => { e.currentTarget.style.borderColor = '#E4E8E6'; e.currentTarget.style.background = '#F0F2F1'; }"
      >
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9" />
          <path d="M13.73 21a2 2 0 0 1-3.46 0" />
        </svg>
        <!-- Notification dot -->
        <span
          v-if="notifCount > 0"
          class="absolute rounded-full"
          style="top:5px;right:6px;width:7px;height:7px;background:#DC2626;border:2px solid #fff;"
        />
      </button>

      <!-- User avatar + dropdown -->
      <div class="relative">
        <button
          class="flex items-center gap-2 rounded-md px-2 py-1 transition-all"
          style="border:1px solid transparent;"
          @click="toggleDropdown"
          @mouseenter="(e) => e.currentTarget.style.background = '#F0F2F1'"
          @mouseleave="(e) => { if (!dropdownOpen) e.currentTarget.style.background = 'transparent'; }"
        >
          <!-- Avatar -->
          <div
            class="rounded-full flex items-center justify-center font-bold text-white flex-shrink-0"
            style="width:30px;height:30px;background:#246040;font-size:11px;"
          >
            {{ userInitials }}
          </div>
          <!-- Name (hidden on small screens) -->
          <span class="hidden sm:block font-medium" style="font-size:13px;color:#2E3D38;">
            {{ user?.name?.split(' ')[0] ?? 'Usuario' }}
          </span>
          <!-- Chevron -->
          <svg
            width="12"
            height="12"
            viewBox="0 0 24 24"
            fill="none"
            stroke="#9FADA7"
            stroke-width="2"
            class="transition-transform"
            :style="dropdownOpen ? 'transform:rotate(180deg)' : ''"
          >
            <polyline points="6 9 12 15 18 9" />
          </svg>
        </button>

        <!-- Dropdown menu -->
        <transition name="page">
          <div
            v-if="dropdownOpen"
            v-click-outside="closeDropdown"
            class="absolute right-0 mt-1 rounded-lg bg-white overflow-hidden"
            style="width:180px;border:1px solid #E4E8E6;box-shadow:0 4px 12px rgba(0,0,0,.10),0 2px 4px rgba(0,0,0,.06);top:100%;"
          >
            <!-- User info -->
            <div class="px-3 py-2.5" style="border-bottom:1px solid #F0F2F1;">
              <div class="font-semibold text-sm truncate" style="color:#1C2925;">
                {{ user?.name ?? 'Usuario' }}
              </div>
              <div class="text-xs truncate" style="color:#9FADA7;">
                {{ user?.email ?? '' }}
              </div>
            </div>

            <!-- Actions -->
            <div class="py-1">
              <button
                class="w-full flex items-center gap-2 px-3 py-2 text-left text-sm transition-colors"
                style="color:#2E3D38;"
                @mouseenter="(e) => e.currentTarget.style.background = '#F0F2F1'"
                @mouseleave="(e) => e.currentTarget.style.background = 'transparent'"
                @click="closeDropdown"
              >
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                  <circle cx="12" cy="7" r="4" />
                </svg>
                Mi perfil
              </button>
              <button
                class="w-full flex items-center gap-2 px-3 py-2 text-left text-sm transition-colors"
                style="color:#DC2626;"
                @mouseenter="(e) => e.currentTarget.style.background = '#FFF5F5'"
                @mouseleave="(e) => e.currentTarget.style.background = 'transparent'"
                @click="handleLogout"
              >
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" />
                  <polyline points="16 17 21 12 16 7" />
                  <line x1="21" y1="12" x2="9" y2="12" />
                </svg>
                Cerrar sesión
              </button>
            </div>
          </div>
        </transition>
      </div>
    </div>
  </header>
</template>
