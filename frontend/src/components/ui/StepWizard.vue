<script setup>
defineProps({ steps: Array, current: Number })
</script>
<template>
  <div class="flex items-start mb-6" style="gap:0;">
    <div v-for="(step, i) in steps" :key="step" class="flex items-start flex-1">
      <div class="flex flex-col items-center flex-1">
        <div class="flex items-center w-full">
          <div
            class="flex items-center justify-center rounded-full font-bold flex-shrink-0"
            style="width:28px;height:28px;font-size:12px;border:2px solid transparent;transition:all .2s;"
            :style="i + 1 < current
              ? 'background:#2D7A50;color:white;border-color:#2D7A50;'
              : i + 1 === current
                ? 'background:#2D7A50;color:white;border-color:#2D7A50;'
                : 'background:white;color:#9FADA7;border-color:#E4E8E6;'"
          >{{ i + 1 < current ? '✓' : i + 1 }}</div>
          <div v-if="i < steps.length - 1" class="flex-1 h-0.5 mx-2" :style="i + 1 < current ? 'background:#2D7A50;' : 'background:#E4E8E6;'"></div>
        </div>
        <div class="text-center mt-1" style="font-size:11.5px;font-weight:600;" :style="i + 1 <= current ? 'color:#2D7A50;' : 'color:#9FADA7;'">{{ step }}</div>
      </div>
    </div>
  </div>
</template>
