<script setup>
const currentYear = new Date().getFullYear()

const features = [
  'Alertas automáticas de vencimiento',
  'Seguimiento de indicadores en tiempo real',
  'Gestión de evidencias y archivos',
  'Multi-empresa con roles granulares',
  'Reportes de cumplimiento ambiental',
]
</script>

<template>
  <div class="min-h-screen flex" style="background: #1A3D2B;">
    <!-- Left branding panel -->
    <div
      class="hidden md:flex w-[380px] flex-col justify-between p-10"
      style="background: #0F2D1A; flex-shrink: 0;"
    >
      <div>
        <div
          class="w-12 h-12 rounded-xl flex items-center justify-center font-black text-white text-lg mb-5"
          style="background: rgba(255,255,255,.15);"
        >
          AV
        </div>
        <h1 class="text-white text-2xl font-bold leading-tight">
          Plataforma de<br />Gestión Ambiental
        </h1>
        <p class="text-sm mt-3 leading-relaxed" style="color: rgba(255,255,255,.5);">
          Monitoreo, cumplimiento y seguimiento de obligaciones ambientales en un solo lugar.
        </p>

        <ul class="mt-8 space-y-3">
          <li
            v-for="feature in features"
            :key="feature"
            class="flex items-center gap-3 text-sm"
            style="color: rgba(255,255,255,.7);"
          >
            <span
              class="w-1.5 h-1.5 rounded-full flex-shrink-0"
              style="background: #5CB87A;"
            />
            {{ feature }}
          </li>
        </ul>
      </div>

      <p class="text-xs" style="color: rgba(255,255,255,.3);">
        © {{ currentYear }} Aquaviva SAS · v2.1.0 · Todos los derechos reservados
      </p>
    </div>

    <!-- Right panel: slot content -->
    <div class="flex-1 flex items-center justify-center p-6">
      <div class="w-full max-w-sm">
        <!-- Mobile logo -->
        <div class="lg:hidden mb-8 text-center">
          <div
            class="inline-flex w-12 h-12 rounded-xl items-center justify-center font-black text-white text-lg mb-2"
            style="background: rgba(255,255,255,.15);"
          >
            AV
          </div>
          <p class="text-sm" style="color: rgba(255,255,255,.6);">Gestión Ambiental</p>
        </div>

        <slot />
      </div>
    </div>
  </div>
</template>
