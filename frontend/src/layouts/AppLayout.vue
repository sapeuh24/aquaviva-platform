<script setup>
import AppSidebar from '@/components/layout/AppSidebar.vue'
import AppTopbar from '@/components/layout/AppTopbar.vue'
import { useDb } from '@/composables/useDb.js'

const { dbStatus } = useDb()
</script>

<template>
  <div class="flex min-h-screen" style="background: #F0F2F1;">
    <!-- Fixed sidebar -->
    <AppSidebar />

    <!-- Main column: topbar + scrollable content -->
    <div class="flex flex-col min-h-screen flex-1" style="margin-left: 252px;">
      <!-- Sticky topbar -->
      <AppTopbar />

      <!-- Scrollable page content -->
      <main class="flex-1 overflow-y-auto p-6">
        <div
          v-if="dbStatus === 'pending'"
          style="margin-bottom:16px;padding:9px 14px;background:#FFFBEB;border:1px solid #FDE68A;border-radius:8px;font-size:12.5px;color:#92400E;display:flex;align-items:center;gap:8px;"
        >
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 9v4"/><path d="M12 17h.01"/><path d="M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/></svg>
          Cambios guardados en este dispositivo. Se sincronizarán con la nube al guardar de nuevo o al recargar.
        </div>
        <slot />
      </main>
    </div>
  </div>
</template>
