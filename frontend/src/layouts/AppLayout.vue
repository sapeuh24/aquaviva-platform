<script setup>
import AppSidebar from '@/components/layout/AppSidebar.vue'
import AppTopbar from '@/components/layout/AppTopbar.vue'
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
        <slot />
      </main>
    </div>
  </div>
</template>
