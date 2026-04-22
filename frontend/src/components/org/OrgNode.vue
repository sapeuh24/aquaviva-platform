<script setup>
import { computed, inject, defineAsyncComponent } from 'vue'

const OrgNodeSelf = defineAsyncComponent(() => import('./OrgNode.vue'))

const props = defineProps({
  node: Object,
  nodes: Array,
  dragId: [Number, null],
  dropTargetId: [Number, null],
})

// Handlers injected from OrgChartView — no event propagation needed
const org = inject('org')

const kids = computed(() => props.nodes.filter(n => n.parent === props.node.id))
const isDropTarget = computed(() => props.dropTargetId === props.node.id)
const isDragging = computed(() => props.dragId === props.node.id)
</script>

<template>
  <div style="display:flex;flex-direction:column;align-items:center;">
    <!-- Card container (draggable) -->
    <div
      draggable="true"
      style="width:160px;user-select:none;cursor:grab;position:relative;"
      :style="isDragging ? 'opacity:.4;' : ''"
      @dragstart.stop="org.dragStart(node.id)"
      @dragover.prevent.stop="org.dragOver($event, node.id)"
      @dragleave.stop="org.dragLeave()"
      @drop.prevent.stop="org.drop($event, node.id)"
    >
      <!-- Card face -->
      <div
        class="rounded-lg"
        style="background:white;padding:11px 12px;transition:all .18s;cursor:pointer;"
        :style="isDropTarget
          ? 'border:2px solid #3A9A64;box-shadow:0 0 0 3px #DCF0E4;'
          : 'border:1px solid #E4E8E6;box-shadow:0 1px 3px rgba(0,0,0,.08);'"
        @click="org.openPanel(node)"
        @mouseenter="(e) => { if (!isDropTarget) { e.currentTarget.style.borderColor='#CDD5D0'; e.currentTarget.style.boxShadow='0 4px 12px rgba(0,0,0,.10)'; } }"
        @mouseleave="(e) => { if (!isDropTarget) { e.currentTarget.style.borderColor='#E4E8E6'; e.currentTarget.style.boxShadow='0 1px 3px rgba(0,0,0,.08)'; } }"
      >
        <div class="flex items-center gap-2">
          <div
            class="flex-shrink-0 flex items-center justify-center rounded-full font-bold text-white"
            style="width:34px;height:34px;font-size:11px;"
            :style="`background:${node.bg};`"
          >{{ node.initials }}</div>
          <div class="flex-1 min-w-0">
            <div style="font-size:12.5px;font-weight:700;color:#1C2925;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ node.name }}</div>
            <div style="font-size:10.5px;color:#9FADA7;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;margin-top:1px;">{{ node.title }}</div>
          </div>
        </div>
      </div>

      <!-- Add child (+) button -->
      <button
        style="position:absolute;bottom:-11px;left:50%;transform:translateX(-50%);width:22px;height:22px;border-radius:50%;background:#2D7A50;border:2px solid white;color:white;font-size:16px;line-height:1;display:flex;align-items:center;justify-content:center;cursor:pointer;z-index:10;padding:0;"
        @click.stop="org.addChild(node.id)"
        @mouseenter="(e) => e.currentTarget.style.background='#246040'"
        @mouseleave="(e) => e.currentTarget.style.background='#2D7A50'"
        title="Agregar subordinado"
      >+</button>
    </div>

    <!-- Children branch -->
    <div v-if="kids.length" style="display:flex;align-items:flex-start;padding-top:28px;position:relative;">
      <!-- Vertical stub from parent card down to horizontal bar -->
      <div style="position:absolute;top:0;left:50%;transform:translateX(-50%);width:2px;height:14px;background:#CDD5D0;"></div>
      <!-- Horizontal connecting bar -->
      <div
        v-if="kids.length > 1"
        style="position:absolute;top:14px;height:2px;background:#CDD5D0;"
        :style="`left:calc(${100 / (kids.length * 2)}% );right:calc(${100 / (kids.length * 2)}% );`"
      ></div>

      <!-- Each child column -->
      <div
        v-for="child in kids"
        :key="child.id"
        style="display:flex;flex-direction:column;align-items:center;padding:0 12px;"
      >
        <!-- Vertical stub from horizontal bar to child -->
        <div style="width:2px;height:14px;background:#CDD5D0;"></div>
        <OrgNodeSelf
          :node="child"
          :nodes="nodes"
          :drag-id="dragId"
          :drop-target-id="dropTargetId"
        />
      </div>
    </div>
  </div>
</template>
