<script setup>
import { ref, reactive } from 'vue'
import AppModal from '@/components/ui/AppModal.vue'

const searchQuery = ref('')
const filterObligation = ref('')
const currentPage = ref(1)

const worksheets = [
  {
    code: 'FM-PTAR-001',
    name: 'Monitoreo de calidad de agua efluente PTAR',
    obligation: 'OB-2023-001',
    tool: 'Muestreo fisicoquímico',
    indicators: 5,
    lastUpdate: '30 Abr 2025',
  },
  {
    code: 'FM-AIRE-002',
    name: 'Seguimiento emisiones fuentes fijas',
    obligation: 'OB-2024-007',
    tool: 'Monitor continuo automático',
    indicators: 3,
    lastUpdate: '28 Abr 2025',
  },
  {
    code: 'FM-RSD-003',
    name: 'Control de generación de residuos sólidos',
    obligation: 'OB-2023-001',
    tool: 'Registro y pesaje',
    indicators: 4,
    lastUpdate: '25 Abr 2025',
  },
]

// Modal state
const showModal = ref(false)
const form = reactive({
  obligacion: '',
  nombre: '',
  codigo: '',
  medio: '',
  herramienta: '',
  descripcion: '',
})

function btnPrimaryEnter(e) { e.currentTarget.style.background = '#246040' }
function btnPrimaryLeave(e) { e.currentTarget.style.background = '#2D7A50' }
function rowEnter(e) { e.currentTarget.style.background = '#F2FAF5' }
function rowLeave(e) { e.currentTarget.style.background = 'transparent' }
function ghostEnter(e) { e.currentTarget.style.background = '#F2FAF5' }
function ghostLeave(e) { e.currentTarget.style.background = 'transparent' }
</script>

<template>
  <div>
    <!-- Page header -->
    <div class="flex items-start justify-between mb-5">
      <div>
        <h1 class="font-extrabold" style="font-size:20px;color:#1C2925;line-height:1.2;">
          Fichas de Monitoreo
        </h1>
        <p class="mt-1" style="font-size:13px;color:#6B7D76;">
          Instrumentos técnicos para el seguimiento de cada obligación ambiental
        </p>
      </div>
      <button
        style="height:36px;padding:0 14px;font-size:13px;background:#2D7A50;border:none;color:white;border-radius:8px;cursor:pointer;font-weight:600;transition:background .15s;"
        @mouseenter="btnPrimaryEnter"
        @mouseleave="btnPrimaryLeave"
        @click="showModal = true"
      >
        + Nueva ficha
      </button>
    </div>

    <!-- Filters -->
    <div class="flex items-center gap-2 mb-4">
      <input
        v-model="searchQuery"
        type="text"
        placeholder="Buscar ficha..."
        style="height:36px;background:#F0F2F1;border:1px solid #E4E8E6;border-radius:8px;padding:0 12px;font-size:13px;color:#1C2925;outline:none;flex:1;max-width:280px;"
      />
      <select
        v-model="filterObligation"
        style="height:36px;background:#F0F2F1;border:1px solid #E4E8E6;border-radius:8px;padding:0 10px;font-size:13px;color:#4A5C55;outline:none;cursor:pointer;"
      >
        <option value="">Todas las obligaciones</option>
        <option value="OB-2023-001">OB-2023-001</option>
        <option value="OB-2022-004">OB-2022-004</option>
      </select>
    </div>

    <!-- Table -->
    <div class="rounded-lg overflow-hidden" style="background:white;border:1px solid #E4E8E6;box-shadow:0 1px 3px rgba(0,0,0,.08);">
      <table style="width:100%;border-collapse:collapse;">
        <thead style="background:#F7F9F8;">
          <tr>
            <th style="padding:10px 16px;font-size:11.5px;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:#6B7D76;text-align:left;">Código</th>
            <th style="padding:10px 16px;font-size:11.5px;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:#6B7D76;text-align:left;">Nombre</th>
            <th style="padding:10px 16px;font-size:11.5px;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:#6B7D76;text-align:left;">Obligación</th>
            <th style="padding:10px 16px;font-size:11.5px;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:#6B7D76;text-align:left;">Herramienta</th>
            <th style="padding:10px 16px;font-size:11.5px;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:#6B7D76;text-align:center;">Indicadores</th>
            <th style="padding:10px 16px;font-size:11.5px;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:#6B7D76;text-align:left;">Últ. actualización</th>
            <th style="padding:10px 16px;font-size:11.5px;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:#6B7D76;text-align:left;"></th>
          </tr>
        </thead>
        <tbody>
          <tr
            v-for="ws in worksheets"
            :key="ws.code"
            style="cursor:pointer;transition:background .12s;"
            @mouseenter="rowEnter"
            @mouseleave="rowLeave"
          >
            <td style="padding:12px 16px;font-size:13px;border-bottom:1px solid #F0F2F1;">
              <span style="background:#F2FAF5;color:#246040;border:1px solid #DCF0E4;border-radius:4px;padding:2px 7px;font-size:11.5px;font-weight:600;font-family:monospace;">
                {{ ws.code }}
              </span>
            </td>
            <td style="padding:12px 16px;font-size:13px;color:#1C2925;border-bottom:1px solid #F0F2F1;max-width:240px;">
              {{ ws.name }}
            </td>
            <td style="padding:12px 16px;font-size:13px;border-bottom:1px solid #F0F2F1;">
              <span style="background:#F2FAF5;color:#246040;border:1px solid #DCF0E4;border-radius:4px;padding:2px 7px;font-size:11.5px;font-weight:600;font-family:monospace;">
                {{ ws.obligation }}
              </span>
            </td>
            <td style="padding:12px 16px;font-size:13px;color:#4A5C55;border-bottom:1px solid #F0F2F1;">
              {{ ws.tool }}
            </td>
            <td style="padding:12px 16px;font-size:13px;color:#4A5C55;border-bottom:1px solid #F0F2F1;text-align:center;">
              {{ ws.indicators }}
            </td>
            <td style="padding:12px 16px;font-size:13px;color:#4A5C55;border-bottom:1px solid #F0F2F1;">
              {{ ws.lastUpdate }}
            </td>
            <td style="padding:12px 16px;border-bottom:1px solid #F0F2F1;">
              <button
                style="height:30px;padding:0 12px;font-size:12px;background:transparent;border:1px solid #E4E8E6;color:#4A5C55;border-radius:6px;cursor:pointer;transition:background .12s;"
                @mouseenter="ghostEnter"
                @mouseleave="ghostLeave"
              >
                Ver →
              </button>
            </td>
          </tr>
        </tbody>
      </table>

      <!-- Pagination -->
      <div class="flex items-center justify-between" style="padding:12px 16px;border-top:1px solid #F0F2F1;">
        <span style="font-size:13px;color:#6B7D76;">Mostrando 3 de 12 fichas</span>
        <div class="flex items-center gap-1">
          <button style="height:30px;min-width:30px;padding:0 8px;font-size:12px;background:#F0F2F1;border:1px solid #E4E8E6;color:#4A5C55;border-radius:6px;cursor:pointer;">‹</button>
          <button
            v-for="p in [1,2,3,4]"
            :key="p"
            style="height:30px;min-width:30px;padding:0 8px;font-size:12px;border:1px solid #E4E8E6;border-radius:6px;cursor:pointer;font-weight:600;transition:all .12s;"
            :style="p === currentPage ? 'background:#2D7A50;color:white;border-color:#2D7A50;' : 'background:#F0F2F1;color:#4A5C55;'"
            @click="currentPage = p"
          >{{ p }}</button>
          <button style="height:30px;min-width:30px;padding:0 8px;font-size:12px;background:#F0F2F1;border:1px solid #E4E8E6;color:#4A5C55;border-radius:6px;cursor:pointer;">›</button>
        </div>
      </div>
    </div>

    <!-- ─── Nueva Ficha Modal ─── -->
    <AppModal
      :show="showModal"
      title="Nueva Ficha de Monitoreo"
      subtitle="Instrumento técnico vinculado a una obligación ambiental"
      @close="showModal = false"
    >
      <div class="form-group">
        <label class="form-label">Obligación vinculada<span class="req">*</span></label>
        <select v-model="form.obligacion" class="form-control">
          <option value="">Seleccionar obligación...</option>
          <option value="OB-2023-001">OB-2023-001 — Permiso de vertimientos ANLA</option>
          <option value="OB-2022-004">OB-2022-004 — Licencia aprovechamiento forestal CAR</option>
          <option value="OB-2024-007">OB-2024-007 — Concesión aguas superficiales CAR</option>
        </select>
      </div>
      <div class="form-row">
        <div class="form-group">
          <label class="form-label">Nombre de la ficha<span class="req">*</span></label>
          <input v-model="form.nombre" type="text" class="form-control" placeholder="Ej: Monitoreo de calidad de agua" />
        </div>
        <div class="form-group">
          <label class="form-label">Código de ficha</label>
          <input v-model="form.codigo" type="text" class="form-control" placeholder="Ej: FM-PTAR-001" />
        </div>
      </div>
      <div class="form-row">
        <div class="form-group">
          <label class="form-label">Medio ambiental<span class="req">*</span></label>
          <select v-model="form.medio" class="form-control">
            <option value="">Seleccionar...</option>
            <option>Hídrico superficial</option>
            <option>Hídrico subterráneo</option>
            <option>Atmosférico / calidad del aire</option>
            <option>Suelo y subsuelo</option>
            <option>Flora y vegetación</option>
            <option>Fauna silvestre</option>
            <option>Paisaje y nivel de ruido</option>
            <option>Socioeconómico</option>
          </select>
        </div>
        <div class="form-group">
          <label class="form-label">Herramienta de seguimiento</label>
          <select v-model="form.herramienta" class="form-control">
            <option value="">Seleccionar...</option>
            <option>Muestreo físico-químico</option>
            <option>Monitor continuo automático</option>
            <option>Inspección visual en campo</option>
            <option>Registro y pesaje</option>
            <option>Análisis de laboratorio externo</option>
            <option>Registro fotográfico</option>
          </select>
        </div>
      </div>
      <div class="form-group">
        <label class="form-label">Descripción / alcance</label>
        <textarea v-model="form.descripcion" class="form-control" rows="2" placeholder="Describa el alcance y los parámetros que cubre esta ficha..."></textarea>
      </div>
      <div class="info-banner info" style="margin-bottom:0;">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="flex-shrink:0;margin-top:1px;"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
        Después de crear la ficha podrá agregar indicadores de seguimiento con sus valores meta, frecuencias y unidades de medida.
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
        >Crear ficha</button>
      </template>
    </AppModal>
  </div>
</template>
