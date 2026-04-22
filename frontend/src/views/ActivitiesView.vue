<script setup>
import { ref, reactive } from 'vue'
import AppModal from '@/components/ui/AppModal.vue'

const columns = [
  {
    key: 'pending',
    label: 'Pendiente',
    color: '#D97706',
    cards: [
      {
        title: 'Toma de muestras agua punto P1 — sector norte río Bogotá',
        date: '15 May',
        dateColor: '#4A5C55',
        dateIcon: '📅',
        assignee: 'L. Torres',
        tag: 'PTAH-002',
        tagType: 'warning',
        borderColor: '#D97706',
        progress: null,
        opacity: 1,
      },
      {
        title: 'Registro fotográfico zona de almacenamiento transitorio',
        date: '18 May',
        dateColor: '#4A5C55',
        dateIcon: '📅',
        assignee: 'C. López',
        tag: 'PMR-001',
        tagType: 'success',
        borderColor: null,
        progress: null,
        opacity: 1,
      },
      {
        title: 'Informe técnico emisiones atmosféricas Q1 2025',
        date: 'Vencida',
        dateColor: '#DC2626',
        dateIcon: '📅',
        assignee: 'Sin asignar',
        tag: 'CEA-003',
        tagType: 'danger',
        borderColor: '#DC2626',
        progress: null,
        opacity: 1,
      },
    ],
  },
  {
    key: 'inprogress',
    label: 'En Progreso',
    color: '#2563EB',
    cards: [
      {
        title: 'Análisis laboratorio turbidez efluente Q2',
        date: '20 May',
        dateColor: '#4A5C55',
        dateIcon: '📅',
        assignee: 'M. Ramírez',
        tag: null,
        tagType: null,
        borderColor: '#2563EB',
        progress: 60,
        progressColor: '#2563EB',
        opacity: 1,
      },
      {
        title: 'Caracterización fisicoquímica aguas subterráneas',
        date: '22 May',
        dateColor: '#4A5C55',
        dateIcon: '📅',
        assignee: 'A. Vargas',
        tag: null,
        tagType: null,
        borderColor: '#2563EB',
        progress: 30,
        progressColor: '#2563EB',
        opacity: 1,
      },
    ],
  },
  {
    key: 'done',
    label: 'Cumplida',
    color: '#059669',
    cards: [
      {
        title: 'Informe mensual caudal tratado PTAR — Abril',
        date: '30 Abr',
        dateColor: '#059669',
        dateIcon: '✅',
        assignee: 'J. Martínez',
        tag: null,
        tagType: null,
        borderColor: null,
        progress: null,
        opacity: 0.8,
      },
      {
        title: 'Calibración equipo medición PM10',
        date: '28 Abr',
        dateColor: '#059669',
        dateIcon: '✅',
        assignee: 'R. Gómez',
        tag: null,
        tagType: null,
        borderColor: null,
        progress: null,
        opacity: 0.8,
      },
      {
        title: 'Pesaje y registro de residuos semana 13',
        date: '25 Abr',
        dateColor: '#059669',
        dateIcon: '✅',
        assignee: 'C. López',
        tag: null,
        tagType: null,
        borderColor: null,
        progress: null,
        opacity: 0.8,
      },
    ],
  },
  {
    key: 'failed',
    label: 'No Cumplida',
    color: '#DC2626',
    cards: [
      {
        title: 'Monitoreo semestral de flora y fauna área intervenida',
        date: '15 Mar',
        dateColor: '#DC2626',
        dateIcon: '❌',
        assignee: 'Sin asignar',
        tag: null,
        tagType: null,
        borderColor: '#DC2626',
        progress: null,
        opacity: 0.75,
      },
    ],
  },
]

const tagStyles = {
  success: { background: '#F0FDF4', color: '#065F46', borderColor: '#BBF7D0' },
  warning: { background: '#FFFBEB', color: '#92400E', borderColor: '#FDE68A' },
  danger:  { background: '#FFF5F5', color: '#991B1B', borderColor: '#FECACA' },
}

// Modal state
const showModal = ref(false)
const form = reactive({
  indicador: '',
  nombre: '',
  descripcion: '',
  fechaProgramada: '',
  responsable: '',
  estado: 'Pendiente',
  prioridad: 'Normal',
})

function btnPrimaryEnter(e) { e.currentTarget.style.background = '#246040' }
function btnPrimaryLeave(e) { e.currentTarget.style.background = '#2D7A50' }
function btnSecondaryEnter(e) { e.currentTarget.style.background = '#E4E8E6' }
function btnSecondaryLeave(e) { e.currentTarget.style.background = '#F0F2F1' }
function cardEnter(e) {
  e.currentTarget.style.boxShadow = '0 4px 12px rgba(0,0,0,.12)'
  e.currentTarget.style.transform = 'translateY(-1px)'
}
function cardLeave(e) {
  e.currentTarget.style.boxShadow = '0 1px 3px rgba(0,0,0,.06)'
  e.currentTarget.style.transform = 'translateY(0)'
}
</script>

<template>
  <div>
    <!-- Page header -->
    <div class="flex items-start justify-between mb-5">
      <div>
        <h1 class="font-extrabold" style="font-size:20px;color:#1C2925;line-height:1.2;">
          Actividades
        </h1>
        <p class="mt-1" style="font-size:13px;color:#6B7D76;">
          Tablero de seguimiento por estado de cumplimiento
        </p>
      </div>
      <div class="flex items-center gap-2">
        <button
          style="height:36px;padding:0 14px;font-size:13px;background:#F0F2F1;border:1px solid #E4E8E6;color:#4A5C55;border-radius:8px;cursor:pointer;font-weight:600;transition:background .15s;"
          @mouseenter="btnSecondaryEnter"
          @mouseleave="btnSecondaryLeave"
        >
          ☰ Vista lista
        </button>
        <button
          style="height:36px;padding:0 14px;font-size:13px;background:#2D7A50;border:none;color:white;border-radius:8px;cursor:pointer;font-weight:600;transition:background .15s;"
          @mouseenter="btnPrimaryEnter"
          @mouseleave="btnPrimaryLeave"
          @click="showModal = true"
        >
          + Nueva actividad
        </button>
      </div>
    </div>

    <!-- Kanban board -->
    <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:14px;">
      <div
        v-for="col in columns"
        :key="col.key"
        class="rounded-lg"
        style="background:#F7F9F8;border:1px solid #E4E8E6;min-height:200px;"
      >
        <!-- Column header -->
        <div style="padding:10px 14px;display:flex;justify-content:space-between;align-items:center;margin-bottom:8px;border-bottom:1px solid #E4E8E6;">
          <span style="font-size:13px;font-weight:700;" :style="{ color: col.color }">
            {{ col.label }}
          </span>
          <span
            style="min-width:22px;height:22px;background:rgba(0,0,0,.06);border-radius:20px;font-size:12px;font-weight:700;display:inline-flex;align-items:center;justify-content:center;padding:0 6px;"
            :style="{ color: col.color }"
          >
            {{ col.cards.length }}
          </span>
        </div>

        <!-- Cards -->
        <div style="padding:0 10px 10px;">
          <div
            v-for="(card, ci) in col.cards"
            :key="ci"
            style="background:white;border:1px solid #E4E8E6;border-radius:8px;padding:12px 14px;margin-bottom:8px;cursor:pointer;box-shadow:0 1px 3px rgba(0,0,0,.06);transition:all .18s;"
            :style="{
              opacity: card.opacity,
              borderLeft: card.borderColor ? `3px solid ${card.borderColor}` : '1px solid #E4E8E6',
            }"
            @mouseenter="cardEnter"
            @mouseleave="cardLeave"
          >
            <!-- Tag badge -->
            <div v-if="card.tag" class="mb-2">
              <span
                class="inline-flex items-center gap-1 rounded font-semibold"
                style="font-size:11px;padding:2px 7px;border:1px solid transparent;"
                :style="{
                  background: tagStyles[card.tagType].background,
                  color: tagStyles[card.tagType].color,
                  borderColor: tagStyles[card.tagType].borderColor,
                }"
              >{{ card.tag }}</span>
            </div>

            <!-- Title -->
            <p style="font-size:12.5px;color:#1C2925;font-weight:500;line-height:1.4;margin-bottom:10px;">
              {{ card.title }}
            </p>

            <!-- Progress bar -->
            <div v-if="card.progress !== null" class="mb-2">
              <div class="flex items-center justify-between mb-1">
                <span style="font-size:11px;color:#6B7D76;">Progreso</span>
                <span style="font-size:11px;font-weight:700;" :style="{ color: card.progressColor }">{{ card.progress }}%</span>
              </div>
              <div style="height:4px;background:#E4E8E6;border-radius:2px;overflow:hidden;">
                <div
                  style="height:100%;border-radius:2px;transition:width .3s;"
                  :style="{ width: card.progress + '%', background: card.progressColor }"
                ></div>
              </div>
            </div>

            <!-- Meta row -->
            <div class="flex items-center justify-between">
              <span style="font-size:11.5px;" :style="{ color: card.dateColor }">
                {{ card.dateIcon }} {{ card.date }}
              </span>
              <span style="font-size:11.5px;color:#6B7D76;">
                👤 {{ card.assignee }}
              </span>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- ─── Nueva Actividad Modal ─── -->
    <AppModal
      :show="showModal"
      title="Nueva Actividad"
      subtitle="Asocie la actividad a un indicador del plan de manejo"
      @close="showModal = false"
    >
      <div class="form-section">Asociación al plan de manejo</div>
      <div class="form-group">
        <label class="form-label">Indicador asociado<span class="req">*</span></label>
        <select v-model="form.indicador" class="form-control">
          <option value="">Seleccionar indicador...</option>
          <option>pH de aguas residuales tratadas (FM-PTAR-001)</option>
          <option>Volumen residuos sólidos generados (FM-RSD-003)</option>
          <option>Concentración PM10 zona productiva (FM-AIRE-002)</option>
        </select>
      </div>
      <div class="form-group">
        <label class="form-label">Nombre de la actividad<span class="req">*</span></label>
        <input v-model="form.nombre" type="text" class="form-control" placeholder="Ej: Toma de muestras agua en punto de monitoreo P1" />
      </div>
      <div class="form-group">
        <label class="form-label">Descripción técnica</label>
        <textarea v-model="form.descripcion" class="form-control" rows="2" placeholder="Describa el procedimiento o alcance de la actividad..."></textarea>
      </div>

      <div class="form-section">Programación</div>
      <div class="form-row">
        <div class="form-group">
          <label class="form-label">Fecha programada<span class="req">*</span></label>
          <input v-model="form.fechaProgramada" type="date" class="form-control" />
        </div>
        <div class="form-group">
          <label class="form-label">Responsable</label>
          <select v-model="form.responsable" class="form-control">
            <option value="">-- Sin asignar --</option>
            <option>Laura Torres Guzmán</option>
            <option>Juan Martínez López</option>
            <option>Mario Ramírez</option>
          </select>
        </div>
      </div>
      <div class="form-row">
        <div class="form-group">
          <label class="form-label">Estado inicial</label>
          <select v-model="form.estado" class="form-control">
            <option>Pendiente</option>
            <option>En progreso</option>
          </select>
        </div>
        <div class="form-group">
          <label class="form-label">Prioridad</label>
          <select v-model="form.prioridad" class="form-control">
            <option>Normal</option>
            <option>Alta</option>
            <option>Crítica</option>
          </select>
        </div>
      </div>
      <div class="info-banner warn" style="margin-bottom:0;">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="flex-shrink:0;margin-top:1px;"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
        Las actividades que superen su fecha programada generarán una alerta automática visible para coordinadores y administradores.
      </div>

      <template #footer>
        <button
          style="height:36px;padding:0 14px;font-size:13px;background:#F0F2F1;border:1px solid #E4E8E6;color:#4A5C55;border-radius:6px;cursor:pointer;"
          @click="showModal = false"
        >Cancelar</button>
        <button
          style="height:36px;padding:0 14px;font-size:13px;background:#2D7A50;border:none;color:white;border-radius:6px;cursor:pointer;font-weight:600;"
          @mouseenter="(e) => e.currentTarget.style.background='#246040'"
          @mouseleave="(e) => e.currentTarget.style.background='#2D7A50'"
          @click="showModal = false"
        >Crear actividad</button>
      </template>
    </AppModal>
  </div>
</template>
