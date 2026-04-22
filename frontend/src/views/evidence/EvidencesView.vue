<script setup>
import { ref } from 'vue'

const selectedActivity = ref('')
const description = ref('')
const searchEvidence = ref('')
const isDragging = ref(false)

const evidences = [
  {
    icon: 'pdf',
    name: 'Análisis_Laboratorio_Abr2025.pdf',
    meta: 'J. Martínez · 30 Abr · Actividad: Toma muestras P1 · 3.2 MB',
    canDelete: true,
  },
  {
    icon: 'image',
    name: 'Foto_Zona_Almacenamiento_01.jpg',
    meta: 'C. López · 28 Abr · Actividad: Registro fotográfico · 1.8 MB',
    canDelete: false,
  },
  {
    icon: 'excel',
    name: 'Reporte_Emisiones_Q1_2025.xlsx',
    meta: 'R. Gómez · 25 Abr · Actividad: Informe emisiones · 890 KB',
    canDelete: false,
  },
]

function btnPrimaryEnter(e) { e.currentTarget.style.background = '#246040' }
function btnPrimaryLeave(e) { e.currentTarget.style.background = '#2D7A50' }
function ghostEnter(e) { e.currentTarget.style.background = '#F2FAF5' }
function ghostLeave(e) { e.currentTarget.style.background = 'transparent' }
</script>

<template>
  <div>
    <!-- Page header -->
    <div class="flex items-start justify-between mb-5">
      <div>
        <h1 class="font-extrabold" style="font-size:20px;color:#1C2925;line-height:1.2;">
          Evidencias
        </h1>
        <p class="mt-1" style="font-size:13px;color:#6B7D76;">
          Cargue y consulte archivos de soporte de las actividades de monitoreo
        </p>
      </div>
    </div>

    <!-- Two-column layout -->
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;align-items:start;">

      <!-- Left: Upload card -->
      <div class="rounded-lg" style="background:white;border:1px solid #E4E8E6;box-shadow:0 1px 3px rgba(0,0,0,.08);padding:20px;">
        <h2 style="font-size:14px;font-weight:700;color:#1C2925;margin-bottom:14px;">Cargar nueva evidencia</h2>

        <!-- Blue info banner -->
        <div class="rounded-lg flex items-start gap-2 mb-4" style="padding:12px 16px;background:#EFF6FF;border:1px solid #BFDBFE;color:#1E40AF;">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;margin-top:1px;">
            <circle cx="12" cy="12" r="10"/>
            <line x1="12" y1="16" x2="12" y2="12"/>
            <line x1="12" y1="8" x2="12.01" y2="8"/>
          </svg>
          <span style="font-size:12.5px;font-weight:500;">
            Formatos permitidos: PDF, JPG, PNG, XLSX, DOCX. Tamaño máximo: 50 MB por archivo.
          </span>
        </div>

        <!-- Activity select -->
        <div class="mb-4">
          <label style="display:block;font-size:12.5px;font-weight:600;color:#1C2925;margin-bottom:6px;">
            Actividad asociada <span style="color:#DC2626;">*</span>
          </label>
          <select
            v-model="selectedActivity"
            style="background:#F7F9F8;border:1px solid #D1D9D5;border-radius:6px;padding:8px 10px;font-size:13px;width:100%;outline:none;color:#1C2925;cursor:pointer;"
          >
            <option value="">Seleccionar actividad...</option>
            <option value="1">Toma de muestras agua punto P1</option>
            <option value="2">Análisis laboratorio turbidez Q2</option>
            <option value="3">Informe mensual PTAR</option>
          </select>
        </div>

        <!-- Upload zone -->
        <div
          style="border:2px dashed #E4E8E6;border-radius:8px;padding:32px;text-align:center;cursor:pointer;transition:border-color .15s,background .15s;"
          :style="isDragging ? 'border-color:#2D7A50;background:#F2FAF5;' : ''"
          class="mb-4"
          @dragover.prevent="isDragging = true"
          @dragleave="isDragging = false"
          @drop.prevent="isDragging = false"
        >
          <div style="display:flex;justify-content:center;margin-bottom:10px;">
            <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="#9FADA7" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
              <polyline points="16 16 12 12 8 16"/>
              <line x1="12" y1="12" x2="12" y2="21"/>
              <path d="M20.39 18.39A5 5 0 0 0 18 9h-1.26A8 8 0 1 0 3 16.3"/>
            </svg>
          </div>
          <p style="font-size:13px;font-weight:600;color:#4A5C55;margin-bottom:4px;">Haga clic o arrastre el archivo aquí</p>
          <p style="font-size:12px;color:#9FADA7;">PDF · JPG · PNG · XLSX · DOCX — Máximo 50 MB</p>
        </div>

        <!-- Description textarea -->
        <div class="mb-5">
          <label style="display:block;font-size:12.5px;font-weight:600;color:#1C2925;margin-bottom:6px;">
            Descripción
          </label>
          <textarea
            v-model="description"
            placeholder="Ej: Análisis fisicoquímico Q1 — resultados pH y turbidez del efluente PTAR..."
            rows="3"
            style="background:#F7F9F8;border:1px solid #D1D9D5;border-radius:6px;padding:8px 10px;font-size:13px;width:100%;outline:none;color:#1C2925;resize:vertical;font-family:inherit;"
          ></textarea>
        </div>

        <!-- Submit button -->
        <button
          style="height:38px;width:100%;font-size:13px;font-weight:600;background:#2D7A50;border:none;color:white;border-radius:8px;cursor:pointer;transition:background .15s;"
          @mouseenter="btnPrimaryEnter"
          @mouseleave="btnPrimaryLeave"
        >
          Cargar evidencia
        </button>
      </div>

      <!-- Right: Evidences list card -->
      <div class="rounded-lg" style="background:white;border:1px solid #E4E8E6;box-shadow:0 1px 3px rgba(0,0,0,.08);">
        <!-- Card header -->
        <div class="flex items-center justify-between" style="padding:16px 20px;border-bottom:1px solid #F0F2F1;">
          <h2 style="font-size:14px;font-weight:700;color:#1C2925;">Evidencias registradas</h2>
          <input
            v-model="searchEvidence"
            type="text"
            placeholder="Buscar..."
            style="height:32px;background:#F0F2F1;border:1px solid #E4E8E6;border-radius:6px;padding:0 10px;font-size:12.5px;color:#1C2925;outline:none;width:160px;"
          />
        </div>

        <!-- File rows -->
        <div>
          <div
            v-for="(ev, idx) in evidences"
            :key="idx"
            class="flex items-center gap-3"
            style="padding:14px 20px;transition:background .12s;"
            :style="idx < evidences.length - 1 ? 'border-bottom:1px solid #F0F2F1;' : ''"
            @mouseenter="e => e.currentTarget.style.background = '#F7F9F8'"
            @mouseleave="e => e.currentTarget.style.background = 'transparent'"
          >
            <!-- File icon -->
            <div
              style="width:38px;height:38px;border-radius:8px;display:flex;align-items:center;justify-content:center;flex-shrink:0;"
              :style="{
                background: ev.icon === 'pdf' ? '#FFF5F5'
                          : ev.icon === 'image' ? '#EFF6FF'
                          : '#F0FDF4',
              }"
            >
              <!-- PDF -->
              <svg v-if="ev.icon === 'pdf'" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#DC2626" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                <polyline points="14 2 14 8 20 8"/>
                <line x1="9" y1="13" x2="15" y2="13"/>
                <line x1="9" y1="17" x2="12" y2="17"/>
              </svg>
              <!-- Image -->
              <svg v-else-if="ev.icon === 'image'" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#2563EB" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect x="3" y="3" width="18" height="18" rx="2" ry="2"/>
                <circle cx="8.5" cy="8.5" r="1.5"/>
                <polyline points="21 15 16 10 5 21"/>
              </svg>
              <!-- Excel -->
              <svg v-else width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#059669" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                <polyline points="14 2 14 8 20 8"/>
                <line x1="8" y1="13" x2="16" y2="13"/>
                <line x1="8" y1="17" x2="16" y2="17"/>
              </svg>
            </div>

            <!-- File info -->
            <div style="flex:1;min-width:0;">
              <p style="font-size:13px;font-weight:600;color:#1C2925;margin-bottom:2px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                {{ ev.name }}
              </p>
              <p style="font-size:11.5px;color:#6B7D76;">{{ ev.meta }}</p>
            </div>

            <!-- Actions -->
            <div class="flex items-center gap-1" style="flex-shrink:0;">
              <!-- Download -->
              <button
                style="width:30px;height:30px;border-radius:6px;border:1px solid #E4E8E6;background:transparent;cursor:pointer;display:flex;align-items:center;justify-content:center;transition:background .12s;color:#4A5C55;"
                @mouseenter="ghostEnter"
                @mouseleave="ghostLeave"
                title="Descargar"
              >
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                  <polyline points="7 10 12 15 17 10"/>
                  <line x1="12" y1="15" x2="12" y2="3"/>
                </svg>
              </button>
              <!-- Delete -->
              <button
                style="width:30px;height:30px;border-radius:6px;border:1px solid #E4E8E6;background:transparent;display:flex;align-items:center;justify-content:center;transition:all .12s;"
                :style="ev.canDelete
                  ? 'cursor:pointer;color:#991B1B;'
                  : 'cursor:not-allowed;color:#CDD5D0;border-color:#F0F2F1;'"
                :disabled="!ev.canDelete"
                title="Eliminar"
              >
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <polyline points="3 6 5 6 21 6"/>
                  <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
                  <path d="M10 11v6"/>
                  <path d="M14 11v6"/>
                  <path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/>
                </svg>
              </button>
            </div>
          </div>
        </div>

        <!-- Pagination footer -->
        <div class="flex items-center justify-between" style="padding:12px 20px;border-top:1px solid #F0F2F1;">
          <span style="font-size:13px;color:#6B7D76;">3 de 24 evidencias</span>
          <div class="flex items-center gap-1">
            <button style="height:28px;min-width:28px;padding:0 8px;font-size:12px;background:#F0F2F1;border:1px solid #E4E8E6;color:#4A5C55;border-radius:6px;cursor:pointer;">‹</button>
            <button style="height:28px;min-width:28px;padding:0 8px;font-size:12px;background:#2D7A50;border:1px solid #2D7A50;color:white;border-radius:6px;cursor:pointer;font-weight:600;">1</button>
            <button style="height:28px;min-width:28px;padding:0 8px;font-size:12px;background:#F0F2F1;border:1px solid #E4E8E6;color:#4A5C55;border-radius:6px;cursor:pointer;">2</button>
            <button style="height:28px;min-width:28px;padding:0 8px;font-size:12px;background:#F0F2F1;border:1px solid #E4E8E6;color:#4A5C55;border-radius:6px;cursor:pointer;">›</button>
          </div>
        </div>
      </div>

    </div>
  </div>
</template>
