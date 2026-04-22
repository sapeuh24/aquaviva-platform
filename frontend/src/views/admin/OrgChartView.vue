<script setup>
import { ref, computed, provide } from 'vue'
import OrgNode from '@/components/org/OrgNode.vue'

// ─── Org data ─────────────────────────────────────────────────────────────
const nodes = ref([
  { id: 1,  name: 'Carlos Rodríguez', title: 'Gerente General',          parent: null, initials: 'CR', bg: '#1A3D2B' },
  { id: 2,  name: 'María Arbeláez',   title: 'Directora Ambiental',      parent: 1,    initials: 'MA', bg: '#2D7A50' },
  { id: 3,  name: 'Pedro Suárez',     title: 'Director Operativo',       parent: 1,    initials: 'PS', bg: '#246040' },
  { id: 4,  name: 'Andrés López',     title: 'Director Administrativo',  parent: 1,    initials: 'AL', bg: '#4A5C55' },
  { id: 5,  name: 'Juan Martínez',    title: 'Coordinador PMA',          parent: 2,    initials: 'JM', bg: '#3A9A64' },
  { id: 6,  name: 'Laura Torres',     title: 'Coordinadora Monitoreo',   parent: 2,    initials: 'LT', bg: '#2563EB' },
  { id: 7,  name: 'Ana Ospina',       title: 'Coordinadora de Campo',    parent: 3,    initials: 'AO', bg: '#059669' },
  { id: 8,  name: 'Mario Ramírez',    title: 'Analista Ambiental',       parent: 5,    initials: 'MR', bg: '#9FADA7' },
  { id: 9,  name: 'Sofía Castro',     title: 'Analista de Datos',        parent: 5,    initials: 'SC', bg: '#9FADA7' },
  { id: 10, name: 'Diego Mora',       title: 'Inspector de Campo',       parent: 7,    initials: 'DM', bg: '#9FADA7' },
])

const root = computed(() => nodes.value.find(n => n.parent === null))

const orgCount = computed(() => {
  if (!root.value) return '0 personas · 0 niveles'
  return `${nodes.value.length} personas · ${getMaxDepth(root.value.id, 0)} niveles`
})

function getMaxDepth(id, depth) {
  const children = nodes.value.filter(n => n.parent === id)
  if (!children.length) return depth + 1
  return Math.max(...children.map(c => getMaxDepth(c.id, depth + 1)))
}

// ─── Drag & drop ──────────────────────────────────────────────────────────
const dragId = ref(null)
const dropTargetId = ref(null)

function isDescendant(nodeId, ancestorId) {
  let current = nodes.value.find(n => n.id === nodeId)
  while (current?.parent !== null && current?.parent !== undefined) {
    if (current.parent === ancestorId) return true
    current = nodes.value.find(n => n.id === current.parent)
  }
  return false
}

// ─── Panel ────────────────────────────────────────────────────────────────
const panelNode = ref(null)

const avatarColors = ['#1A3D2B', '#2D7A50', '#246040', '#3A9A64', '#2563EB', '#059669', '#D97706', '#9FADA7']

// ─── Provide org handlers to all OrgNode descendants ─────────────────────
provide('org', {
  dragStart: (id) => { dragId.value = id },
  dragOver:  (e, id) => { if (id !== dragId.value) dropTargetId.value = id },
  dragLeave: () => { dropTargetId.value = null },
  drop: (e, targetId) => {
    dropTargetId.value = null
    if (!dragId.value || dragId.value === targetId) return
    if (isDescendant(targetId, dragId.value)) return
    const node = nodes.value.find(n => n.id === dragId.value)
    if (node) node.parent = targetId
    dragId.value = null
  },
  openPanel: (node) => { panelNode.value = node },
  addChild:  (parentId) => {
    const newId = Math.max(...nodes.value.map(n => n.id)) + 1
    nodes.value.push({ id: newId, name: 'Nuevo colaborador', title: 'Cargo pendiente', parent: parentId, initials: 'NC', bg: avatarColors[newId % avatarColors.length] })
  },
  removeNode: (id) => {
    const node = nodes.value.find(n => n.id === id)
    if (!node) return
    nodes.value.filter(n => n.parent === id).forEach(c => { c.parent = node.parent })
    nodes.value = nodes.value.filter(n => n.id !== id)
    if (panelNode.value?.id === id) panelNode.value = null
  },
})

function addRoot() {
  const newId = Math.max(...nodes.value.map(n => n.id), 0) + 1
  const hasRoot = nodes.value.find(n => n.parent === null)
  nodes.value.push({ id: newId, name: 'Nueva persona', title: 'Cargo pendiente', parent: hasRoot ? 1 : null, initials: 'NP', bg: '#9FADA7' })
}

function closePanel() { panelNode.value = null }

function childrenOf(id) { return nodes.value.filter(n => n.parent === id) }
</script>

<template>
  <div>
    <!-- Page header -->
    <div class="flex items-start justify-between mb-4">
      <div>
        <h1 class="font-extrabold" style="font-size:20px;color:#1C2925;line-height:1.2;">Estructura Organizacional</h1>
        <p class="mt-1" style="font-size:13px;color:#6B7D76;">Organigrama jerárquico de la empresa — arrastre y suelte para reorganizar</p>
      </div>
      <div class="flex items-center gap-2">
        <button
          class="flex items-center gap-2 rounded-md font-semibold"
          style="height:36px;padding:0 14px;font-size:13px;background:#F0F2F1;border:1px solid #E4E8E6;color:#4A5C55;cursor:pointer;"
          @mouseenter="(e) => e.currentTarget.style.background='#E4E8E6'"
          @mouseleave="(e) => e.currentTarget.style.background='#F0F2F1'"
        >
          <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
          Exportar PDF
        </button>
        <button
          class="flex items-center gap-2 rounded-md font-semibold text-white"
          style="height:36px;padding:0 14px;font-size:13px;background:#2D7A50;border:none;cursor:pointer;"
          @mouseenter="(e) => e.currentTarget.style.background='#246040'"
          @mouseleave="(e) => e.currentTarget.style.background='#2D7A50'"
          @click="addRoot"
        >
          <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
          Agregar persona
        </button>
      </div>
    </div>

    <!-- Info banner -->
    <div class="flex items-start gap-2 rounded-lg mb-4" style="padding:12px 16px;background:#EFF6FF;border:1px solid #BFDBFE;color:#1E40AF;">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="flex-shrink:0;margin-top:1px;"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
      <span style="font-size:13px;">Arrastre una tarjeta <strong>sobre otra</strong> para reasignar el jefe directo. Haga clic en <strong>+</strong> para agregar un subordinado. Click en la tarjeta para ver el perfil.</span>
    </div>

    <!-- Legend + count -->
    <div class="rounded-lg mb-4" style="background:white;border:1px solid #E4E8E6;padding:14px 18px;box-shadow:0 1px 3px rgba(0,0,0,.08);">
      <div class="flex items-center justify-between flex-wrap gap-3">
        <div class="flex items-center gap-5 flex-wrap">
          <div v-for="item in [
            { color:'#1A3D2B', label:'Gerencia' },
            { color:'#2D7A50', label:'Dirección' },
            { color:'#3A9A64', label:'Coordinación' },
            { color:'#2563EB', label:'Técnico/Analista' },
            { color:'#9FADA7', label:'Operativo' },
          ]" :key="item.label" class="flex items-center gap-1.5">
            <div class="rounded-full flex-shrink-0" :style="`width:10px;height:10px;background:${item.color};`" />
            <span style="font-size:12px;color:#4A5C55;">{{ item.label }}</span>
          </div>
        </div>
        <span style="font-size:12px;color:#9FADA7;">{{ orgCount }}</span>
      </div>
    </div>

    <!-- Org tree -->
    <div class="rounded-lg" style="background:white;border:1px solid #E4E8E6;box-shadow:0 1px 3px rgba(0,0,0,.08);overflow:auto;">
      <div style="padding:40px 32px;min-width:800px;min-height:300px;">
        <div v-if="root" class="flex justify-center">
          <OrgNode
            :node="root"
            :nodes="nodes"
            :drag-id="dragId"
            :drop-target-id="dropTargetId"
          />
        </div>
        <div v-else class="flex items-center justify-center" style="height:200px;color:#9FADA7;font-size:13px;">
          Sin estructura definida. Haga clic en "Agregar persona" para comenzar.
        </div>
      </div>
    </div>

    <!-- Profile panel -->
    <transition name="page">
      <div
        v-if="panelNode"
        class="fixed inset-0"
        style="z-index:200;background:rgba(15,25,18,.45);"
        @click.self="closePanel"
      >
        <div
          class="absolute right-0 top-0 h-full flex flex-col"
          style="width:320px;background:white;box-shadow:-4px 0 24px rgba(0,0,0,.15);overflow-y:auto;"
        >
          <div class="flex items-center justify-between" style="padding:18px 20px;border-bottom:1px solid #F0F2F1;">
            <div class="font-bold" style="font-size:15px;color:#1C2925;">Perfil del colaborador</div>
            <button class="flex items-center justify-center rounded-md" style="width:28px;height:28px;background:#F0F2F1;border:none;color:#4A5C55;cursor:pointer;" @click="closePanel">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
          </div>
          <div style="padding:24px;">
            <div class="flex flex-col items-center mb-6">
              <div class="flex items-center justify-center rounded-full font-bold text-white mb-3" style="width:72px;height:72px;font-size:22px;" :style="`background:${panelNode.bg};`">
                {{ panelNode.initials }}
              </div>
              <div class="font-bold text-center" style="font-size:16px;color:#1C2925;">{{ panelNode.name }}</div>
              <div class="text-center" style="font-size:13px;color:#6B7D76;margin-top:3px;">{{ panelNode.title }}</div>
            </div>
            <div style="background:#F8F9F8;border-radius:8px;padding:12px 14px;margin-bottom:16px;">
              <div class="flex justify-between" style="padding:8px 0;border-bottom:1px solid #F0F2F1;">
                <span style="font-size:12.5px;color:#9FADA7;">Jefe directo</span>
                <span class="font-medium" style="font-size:12.5px;color:#1C2925;">{{ panelNode.parent ? (nodes.find(n => n.id === panelNode.parent)?.name ?? '—') : 'Nivel raíz' }}</span>
              </div>
              <div class="flex justify-between" style="padding:8px 0;border-bottom:1px solid #F0F2F1;">
                <span style="font-size:12.5px;color:#9FADA7;">Subordinados</span>
                <span class="font-medium" style="font-size:12.5px;color:#1C2925;">{{ childrenOf(panelNode.id).length }}</span>
              </div>
              <div class="flex justify-between" style="padding:8px 0;">
                <span style="font-size:12.5px;color:#9FADA7;">ID en sistema</span>
                <span class="font-medium" style="font-size:12.5px;color:#1C2925;">#{{ panelNode.id }}</span>
              </div>
            </div>
            <div class="flex flex-col gap-2">
              <button
                class="w-full flex items-center justify-center gap-2 rounded-md font-semibold"
                style="height:36px;font-size:13px;background:#F0F2F1;border:1px solid #E4E8E6;color:#4A5C55;cursor:pointer;"
                @mouseenter="(e) => e.currentTarget.style.background='#E4E8E6'"
                @mouseleave="(e) => e.currentTarget.style.background='#F0F2F1'"
                @click="() => { const org = inject('org'); /* handled via provide */ }"
              >
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                Agregar subordinado
              </button>
              <button
                class="w-full flex items-center justify-center gap-2 rounded-md font-semibold"
                style="height:36px;font-size:13px;background:#FFF5F5;border:1px solid #FECACA;color:#991B1B;cursor:pointer;"
                @mouseenter="(e) => e.currentTarget.style.background='#FECACA'"
                @mouseleave="(e) => e.currentTarget.style.background='#FFF5F5'"
                @click="() => { nodes.value = nodes.value.filter(n => { if (n.id === panelNode.id) return false; if (n.parent === panelNode.id) { n.parent = panelNode.parent; } return true; }); panelNode = null; }"
              >
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/></svg>
                Eliminar del organigrama
              </button>
            </div>
          </div>
        </div>
      </div>
    </transition>
  </div>
</template>
