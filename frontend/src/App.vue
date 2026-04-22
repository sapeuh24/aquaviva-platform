<script setup>
import { computed } from 'vue'
import { useRoute } from 'vue-router'
import AppLayout from '@/layouts/AppLayout.vue'
import AuthLayout from '@/layouts/AuthLayout.vue'

const route = useRoute()

const layout = computed(() => {
  return route.meta.layout === 'auth' ? AuthLayout : AppLayout
})
</script>

<template>
  <component :is="layout">
    <router-view v-slot="{ Component, route: currentRoute }">
      <transition name="page" mode="out-in">
        <component :is="Component" :key="currentRoute.path" />
      </transition>
    </router-view>
  </component>
</template>
