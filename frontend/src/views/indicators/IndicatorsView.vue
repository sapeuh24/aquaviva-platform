<script setup>
import { ref, reactive } from 'vue'
import AppModal from '@/components/ui/AppModal.vue'

const currentPage = ref(1)

const badgeStyles = {
  success: { background: '#F0FDF4', color: '#065F46', borderColor: '#BBF7D0' },
  warning: { background: '#FFFBEB', color: '#92400E', borderColor: '#FDE68A' },
  danger:  { background: '#FFF5F5', color: '#991B1B', borderColor: '#FECACA' },
}

const indicators = [
  {
    name: 'pH de aguas residuales tratadas',
    sheet: 'FM-PTAR-001',
    frequency: 'Mensual',
    unit: 'pH (6–9)',
    goal: 'Entre 6 y 9',
    nextMeasure: 'Vencida',
    nextMeasureWarning: 'danger',
    compliance: 28,
    complianceColor: '#DC2626',
    status: 'Vencido',
    statusType: 'danger',
  },
  {
    name: 'Volumen residuos sólidos generados',
    sheet: 'FM-RSD-003',
    frequency: 'Semanal',
    unit: 'ton/semana',
    goal: '< 2.5 ton/sem',
    nextMeasure: '12 May 2025',
    nextMeasureWarning: null,
    compliance: 91,
    complianceColor: '#065F46',
    status: 'En meta',
    statusType: 'success',
  },
  {
    name: 'Concentración PM10 zona productiva',
    sheet: 'FM-AIRE-002',
    frequency: 'Trimestral',
    unit: 'μg/m³',
    goal: '< 100 μg/m³',
    nextMeasure: '01 Jun 2025',
    nextMeasureWarning: 'warning',
    compliance: 55,
    complianceColor: '#D97706',
    status: 'En riesgo',
    statusType: 'warning',
  },
]

// Modal state
const showModal = ref(false)
const form = reactive({
  ficha: '',
  puntoMonitoreo: '',
  nombre: '',
  frecuencia: '',
  unidad: '',
  lineaBase: '',
  meta: '',
  referencia: '',
  proximaMedicion: '',
})

function btnPrimaryEnter(e) { e.currentTarget.style.background = '#246040' }
function btnPrimaryLeave(e) { e.currentTarget.style.background = '#2D7A50' }
function btnSecondaryEnter(e) { e.currentTarget.style.background = '#E4E8E6' }
function btnSecondaryLeave(e) { e.currentTarget.style.background = '#F0F2F1' }
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
          Indicadores
        </h1>
        <p class="mt-1" style="font-size:13px;color:#6B7D76;">
          Parámetros de medición y control de cumplimiento ambiental
        </p>
      </div>
      <div class="flex items-center gap-2">
        <button
          style="height:36px;padding:0 14px;font-size:13px;background:#F0F2F1;border:1px solid #E4E8E6;color:#991B1B;border-radius:8px;cursor:pointer;font-weight:600;transition:background .15s;"
          @mouseenter="btnSecondaryEnter"
          @mouseleave="btnSecondaryLeave"
        >
          ⚠ Solo en riesgo
        </button>
        <button
          style="height:36px;padding:0 14px;font-size:13px;background:#2D7A50;border:none;color:white;border-radius:8px;cursor:pointer;font-weight:600;transition:background .15s;"
          @mouseenter="btnPrimaryEnter"
          @mouseleave="btnPrimaryLeave"
          @click="showModal = true"
        >
          + Nuevo indicador
        </button>
      </div>
    </div>

    <!-- Stat cards -->
    <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:14px;margin-bottom:20px;">
      <!-- Cumplimiento global -->
      <div class="rounded-lg" style="background:white;border:1px solid #E4E8E6;box-shadow:0 1px 3px rgba(0,0,0,.08);padding:20px;">
        <div class="flex items-center gap-4">
          <svg width="72" height="72" viewBox="0 0 72 72" style="flex-shrink:0;">
            <circle cx="36" cy="36" r="27" fill="none" stroke="#E4E8E6" stroke-width="6"/>
            <circle
              cx="36" cy="36" r="27"
              fill="none"
              stroke="#3A9A64"
              stroke-width="6"
              stroke-linecap="round"
              stroke-dasharray="170"
              stroke-dashoffset="37"
              transform="rotate(-90 36 36)"
            />
            <text x="36" y="40" text-anchor="middle" font-size="13" font-weight="700" fill="#1C2925">78%</text>
          </svg>
          <div>
            <div style="font-size:13px;font-weight:700;color:#6B7D76;margin-bottom:2px;">Cumplimiento Global</div>
            <div style="font-size:12px;color:#6B7D76;margin-bottom:8px;">26 de 34 indicadores en meta</div>
            <span
              class="inline-flex items-center gap-1 rounded-full font-semibold"
              style="font-size:11.5px;padding:2px 9px;border:1px solid #BBF7D0;background:#F0FDF4;color:#065F46;"
            >
              <span style="width:5px;height:5px;border-radius:50%;background:currentColor;flex-shrink:0;"></span>
              Aceptable
            </span>
          </div>
        </div>
      </div>

      <!-- Próximos a vencer -->
      <div class="rounded-lg" style="background:white;border:1px solid #E4E8E6;box-shadow:0 1px 3px rgba(0,0,0,.08);padding:20px;">
        <div class="flex items-center gap-4">
          <div style="width:44px;height:44px;border-radius:10px;background:#FFFBEB;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#D97706" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
              <line x1="16" y1="2" x2="16" y2="6"/>
              <line x1="8" y1="2" x2="8" y2="6"/>
              <line x1="3" y1="10" x2="21" y2="10"/>
            </svg>
          </div>
          <div>
            <div style="font-size:28px;font-weight:800;color:#D97706;line-height:1;">5</div>
            <div style="font-size:13px;font-weight:700;color:#1C2925;margin-top:2px;">Próximos a vencer</div>
            <div style="font-size:12px;color:#6B7D76;margin-top:2px;">Vencen en los próximos 30 días — programe mediciones</div>
          </div>
        </div>
      </div>

      <!-- Indicadores vencidos -->
      <div class="rounded-lg" style="background:white;border:1px solid #E4E8E6;box-shadow:0 1px 3px rgba(0,0,0,.08);padding:20px;">
        <div class="flex items-center gap-4">
          <div style="width:44px;height:44px;border-radius:10px;background:#FFF5F5;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#DC2626" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <circle cx="12" cy="12" r="10"/>
              <line x1="12" y1="8" x2="12" y2="12"/>
              <line x1="12" y1="16" x2="12.01" y2="16"/>
            </svg>
          </div>
          <div>
            <div style="font-size:28px;font-weight:800;color:#DC2626;line-height:1;">3</div>
            <div style="font-size:13px;font-weight:700;color:#1C2925;margin-top:2px;">Indicadores vencidos</div>
            <div style="font-size:12px;color:#6B7D76;margin-top:2px;">Requieren acción inmediata — se generaron alertas</div>
          </div>
        </div>
      </div>
    </div>

    <!-- Table -->
    <div class="rounded-lg overflow-hidden" style="background:white;border:1px solid #E4E8E6;box-shadow:0 1px 3px rgba(0,0,0,.08);">
      <table style="width:100%;border-collapse:collapse;">
        <thead style="background:#F7F9F8;">
          <tr>
            <th style="padding:10px 16px;font-size:11.5px;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:#6B7D76;text-align:left;">Indicador</th>
            <th style="padding:10px 16px;font-size:11.5px;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:#6B7D76;text-align:left;">Ficha</th>
            <th style="padding:10px 16px;font-size:11.5px;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:#6B7D76;text-align:left;">Frecuencia</th>
            <th style="padding:10px 16px;font-size:11.5px;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:#6B7D76;text-align:left;">Unidad</th>
            <th style="padding:10px 16px;font-size:11.5px;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:#6B7D76;text-align:left;">Meta</th>
            <th style="padding:10px 16px;font-size:11.5px;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:#6B7D76;text-align:left;">Próx. medición</th>
            <th style="padding:10px 16px;font-size:11.5px;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:#6B7D76;text-align:center;">Cumplimiento</th>
            <th style="padding:10px 16px;font-size:11.5px;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:#6B7D76;text-align:left;">Estado</th>
            <th style="padding:10px 16px;font-size:11.5px;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:#6B7D76;text-align:left;"></th>
          </tr>
        </thead>
        <tbody>
          <tr
            v-for="ind in indicators"
            :key="ind.name"
            style="cursor:pointer;transition:background .12s;"
            @mouseenter="rowEnter"
            @mouseleave="rowLeave"
          >
            <td style="padding:12px 16px;font-size:13px;color:#1C2925;border-bottom:1px solid #F0F2F1;max-width:200px;">
              {{ ind.name }}
            </td>
            <td style="padding:12px 16px;font-size:13px;border-bottom:1px solid #F0F2F1;">
              <span style="background:#F2FAF5;color:#246040;border:1px solid #DCF0E4;border-radius:4px;padding:2px 7px;font-size:11.5px;font-weight:600;font-family:monospace;">
                {{ ind.sheet }}
              </span>
            </td>
            <td style="padding:12px 16px;font-size:13px;color:#4A5C55;border-bottom:1px solid #F0F2F1;">
              {{ ind.frequency }}
            </td>
            <td style="padding:12px 16px;font-size:13px;color:#4A5C55;border-bottom:1px solid #F0F2F1;">
              {{ ind.unit }}
            </td>
            <td style="padding:12px 16px;font-size:13px;color:#4A5C55;border-bottom:1px solid #F0F2F1;">
              {{ ind.goal }}
            </td>
            <td style="padding:12px 16px;font-size:13px;border-bottom:1px solid #F0F2F1;">
              <span
                :style="{
                  color: ind.nextMeasureWarning === 'danger' ? '#DC2626'
                       : ind.nextMeasureWarning === 'warning' ? '#D97706'
                       : '#4A5C55',
                  fontWeight: ind.nextMeasureWarning ? '700' : '400',
                }"
              >{{ ind.nextMeasure }}</span>
            </td>
            <td style="padding:12px 16px;font-size:13px;border-bottom:1px solid #F0F2F1;text-align:center;">
              <span :style="{ color: ind.complianceColor, fontWeight: '700' }">{{ ind.compliance }}%</span>
            </td>
            <td style="padding:12px 16px;border-bottom:1px solid #F0F2F1;">
              <span
                class="inline-flex items-center gap-1 rounded-full font-semibold"
                style="font-size:11.5px;padding:2px 9px;border:1px solid transparent;"
                :style="{
                  background: badgeStyles[ind.statusType].background,
                  color: badgeStyles[ind.statusType].color,
                  borderColor: badgeStyles[ind.statusType].borderColor,
                }"
              >
                <span style="width:5px;height:5px;border-radius:50%;background:currentColor;flex-shrink:0;"></span>
                {{ ind.status }}
              </span>
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
        <span style="font-size:13px;color:#6B7D76;">Mostrando 3 de 34 indicadores</span>
        <div class="flex items-center gap-1">
          <button style="height:30px;min-width:30px;padding:0 8px;font-size:12px;background:#F0F2F1;border:1px solid #E4E8E6;color:#4A5C55;border-radius:6px;cursor:pointer;">‹</button>
          <button
            v-for="p in [1,2,3,4,5]"
            :key="p"
            style="height:30px;min-width:30px;padding:0 8px;font-size:12px;border:1px solid #E4E8E6;border-radius:6px;cursor:pointer;font-weight:600;transition:all .12s;"
            :style="p === currentPage ? 'background:#2D7A50;color:white;border-color:#2D7A50;' : 'background:#F0F2F1;color:#4A5C55;'"
            @click="currentPage = p"
          >{{ p }}</button>
          <button style="height:30px;min-width:30px;padding:0 8px;font-size:12px;background:#F0F2F1;border:1px solid #E4E8E6;color:#4A5C55;border-radius:6px;cursor:pointer;">›</button>
        </div>
      </div>
    </div>

    <!-- ─── Nuevo Indicador Modal ─── -->
    <AppModal
      :show="showModal"
      title="Nuevo Indicador"
      subtitle="Defina el parámetro de medición y su configuración"
      @close="showModal = false"
    >
      <div class="form-section">Asociación</div>
      <div class="form-row">
        <div class="form-group">
          <label class="form-label">Ficha de monitoreo<span class="req">*</span></label>
          <select v-model="form.ficha" class="form-control">
            <option value="">Seleccionar...</option>
            <option value="FM-PTAR-001">FM-PTAR-001 — Calidad agua efluente PTAR</option>
            <option value="FM-RSD-003">FM-RSD-003 — Control residuos sólidos</option>
            <option value="FM-AIRE-002">FM-AIRE-002 — Emisiones fuentes fijas</option>
          </select>
        </div>
        <div class="form-group">
          <label class="form-label">Punto de monitoreo</label>
          <input v-model="form.puntoMonitoreo" type="text" class="form-control" placeholder="Ej: P1 — Aguas abajo PTAR" />
        </div>
      </div>
      <div class="form-group">
        <label class="form-label">Nombre del indicador<span class="req">*</span></label>
        <input v-model="form.nombre" type="text" class="form-control" placeholder="Ej: pH de aguas residuales tratadas en punto PTAR-001" />
        <div class="form-hint">Sea específico: parámetro + punto de medición + unidad.</div>
      </div>

      <div class="form-section">Medición y frecuencia</div>
      <div class="form-row">
        <div class="form-group">
          <label class="form-label">Frecuencia de medición<span class="req">*</span></label>
          <select v-model="form.frecuencia" class="form-control">
            <option value="">Seleccionar...</option>
            <option>Diaria</option>
            <option>Semanal</option>
            <option>Mensual</option>
            <option>Trimestral</option>
            <option>Semestral</option>
            <option>Anual</option>
          </select>
        </div>
        <div class="form-group">
          <label class="form-label">Unidad de medida</label>
          <input v-model="form.unidad" type="text" class="form-control" placeholder="Ej: pH, mg/L, m³/día, ton/mes, µg/m³, %" />
        </div>
      </div>
      <div class="form-row">
        <div class="form-group">
          <label class="form-label">Valor de línea base</label>
          <input v-model="form.lineaBase" type="number" class="form-control" placeholder="0" />
        </div>
        <div class="form-group">
          <label class="form-label">Valor meta / límite máximo</label>
          <input v-model="form.meta" type="number" class="form-control" placeholder="0" />
        </div>
      </div>
      <div class="form-row">
        <div class="form-group">
          <label class="form-label">Referencia normativa</label>
          <input v-model="form.referencia" type="text" class="form-control" placeholder="Ej: pH entre 6 y 9 — Res. 0631/2015 art. 8" />
        </div>
        <div class="form-group">
          <label class="form-label">Próxima fecha de medición</label>
          <input v-model="form.proximaMedicion" type="date" class="form-control" />
        </div>
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
        >Crear indicador</button>
      </template>
    </AppModal>
  </div>
</template>
