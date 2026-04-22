<script setup>
import { ref, reactive } from 'vue'
import AppModal from '@/components/ui/AppModal.vue'

const searchQuery = ref('')
const filterAuthority = ref('')
const filterStatus = ref('')
const currentPage = ref(1)

const badgeStyles = {
  success: { background: '#F0FDF4', color: '#065F46', borderColor: '#BBF7D0' },
  warning: { background: '#FFFBEB', color: '#92400E', borderColor: '#FDE68A' },
  danger:  { background: '#FFF5F5', color: '#991B1B', borderColor: '#FECACA' },
  info:    { background: '#EFF6FF', color: '#1E40AF', borderColor: '#BFDBFE' },
}

const obligations = [
  {
    code: 'OB-2023-001',
    description: 'Permiso de vertimientos industriales al río Tunjuelo',
    authority: 'ANLA',
    resolution: 'Res. 0234/2023',
    expiry: 'Dic 2026',
    expiryWarning: false,
    sheets: 3,
    status: 'Vigente',
    statusType: 'success',
  },
  {
    code: 'OB-2022-004',
    description: 'Licencia de aprovechamiento forestal zona norte',
    authority: 'CAR',
    resolution: 'Res. 1102/2022',
    expiry: 'Jun 2025',
    expiryWarning: true,
    sheets: 2,
    status: 'Por vencer',
    statusType: 'warning',
  },
  {
    code: 'OB-2024-007',
    description: 'Concesión de aguas superficiales cuenca alta',
    authority: 'CAR',
    resolution: 'Res. 0089/2024',
    expiry: 'Dic 2027',
    expiryWarning: false,
    sheets: 4,
    status: 'Vigente',
    statusType: 'success',
  },
]

const pages = [1, 2, 3, 4]

// Modal state
const showModal = ref(false)
const form = reactive({
  tipoInstrumento: '',
  autoridad: '',
  resolucion: '',
  expediente: '',
  objeto: '',
  fechaExpedicion: '',
  fechaVencimiento: '',
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
          Obligaciones Ambientales
        </h1>
        <p class="mt-1" style="font-size:13px;color:#6B7D76;">
          Actos administrativos, licencias y permisos ambientales activos
        </p>
      </div>
      <button
        style="height:36px;padding:0 14px;font-size:13px;background:#2D7A50;border:none;color:white;border-radius:8px;cursor:pointer;font-weight:600;transition:background .15s;"
        @mouseenter="btnPrimaryEnter"
        @mouseleave="btnPrimaryLeave"
        @click="showModal = true"
      >
        + Nueva obligación
      </button>
    </div>

    <!-- Warning banner -->
    <div class="rounded-lg flex items-start gap-2 mb-4" style="padding:12px 16px;background:#FFFBEB;border:1px solid #FDE68A;color:#92400E;">
      <span style="font-size:15px;flex-shrink:0;margin-top:1px;">⚠️</span>
      <span style="font-size:13px;font-weight:500;">
        <strong>1 obligación próxima a vencer</strong> — Licencia de aprovechamiento forestal vence en 45 días. Contacte a la autoridad ambiental con anticipación.
      </span>
    </div>

    <!-- Filters -->
    <div class="flex items-center gap-2 mb-4">
      <input
        v-model="searchQuery"
        type="text"
        placeholder="Buscar obligación..."
        style="height:36px;background:#F0F2F1;border:1px solid #E4E8E6;border-radius:8px;padding:0 12px;font-size:13px;color:#1C2925;outline:none;flex:1;max-width:280px;"
      />
      <select
        v-model="filterAuthority"
        style="height:36px;background:#F0F2F1;border:1px solid #E4E8E6;border-radius:8px;padding:0 10px;font-size:13px;color:#4A5C55;outline:none;cursor:pointer;"
      >
        <option value="">Todas las autoridades</option>
        <option value="ANLA">ANLA</option>
        <option value="CAR">CAR</option>
        <option value="CVC">CVC</option>
        <option value="CORANTIOQUIA">CORANTIOQUIA</option>
      </select>
      <select
        v-model="filterStatus"
        style="height:36px;background:#F0F2F1;border:1px solid #E4E8E6;border-radius:8px;padding:0 10px;font-size:13px;color:#4A5C55;outline:none;cursor:pointer;"
      >
        <option value="">Todos los estados</option>
        <option value="vigente">Vigente</option>
        <option value="por_vencer">Por vencer</option>
        <option value="vencida">Vencida</option>
      </select>
    </div>

    <!-- Table -->
    <div class="rounded-lg overflow-hidden" style="background:white;border:1px solid #E4E8E6;box-shadow:0 1px 3px rgba(0,0,0,.08);">
      <table style="width:100%;border-collapse:collapse;">
        <thead style="background:#F7F9F8;">
          <tr>
            <th style="padding:10px 16px;font-size:11.5px;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:#6B7D76;text-align:left;">Código</th>
            <th style="padding:10px 16px;font-size:11.5px;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:#6B7D76;text-align:left;">Descripción</th>
            <th style="padding:10px 16px;font-size:11.5px;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:#6B7D76;text-align:left;">Autoridad</th>
            <th style="padding:10px 16px;font-size:11.5px;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:#6B7D76;text-align:left;">N.º Resolución</th>
            <th style="padding:10px 16px;font-size:11.5px;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:#6B7D76;text-align:left;">Vencimiento</th>
            <th style="padding:10px 16px;font-size:11.5px;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:#6B7D76;text-align:center;">Fichas</th>
            <th style="padding:10px 16px;font-size:11.5px;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:#6B7D76;text-align:left;">Estado</th>
            <th style="padding:10px 16px;font-size:11.5px;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:#6B7D76;text-align:left;"></th>
          </tr>
        </thead>
        <tbody>
          <tr
            v-for="ob in obligations"
            :key="ob.code"
            style="cursor:pointer;transition:background .12s;"
            @mouseenter="rowEnter"
            @mouseleave="rowLeave"
          >
            <td style="padding:12px 16px;font-size:13px;border-bottom:1px solid #F0F2F1;">
              <span style="background:#F2FAF5;color:#246040;border:1px solid #DCF0E4;border-radius:4px;padding:2px 7px;font-size:11.5px;font-weight:600;font-family:monospace;">
                {{ ob.code }}
              </span>
            </td>
            <td style="padding:12px 16px;font-size:13px;color:#1C2925;border-bottom:1px solid #F0F2F1;max-width:260px;">
              {{ ob.description }}
            </td>
            <td style="padding:12px 16px;font-size:13px;color:#4A5C55;border-bottom:1px solid #F0F2F1;font-weight:600;">
              {{ ob.authority }}
            </td>
            <td style="padding:12px 16px;font-size:13px;color:#4A5C55;border-bottom:1px solid #F0F2F1;">
              {{ ob.resolution }}
            </td>
            <td style="padding:12px 16px;font-size:13px;border-bottom:1px solid #F0F2F1;">
              <span :style="ob.expiryWarning ? 'color:#D97706;font-weight:700;' : 'color:#4A5C55;'">{{ ob.expiry }}</span>
            </td>
            <td style="padding:12px 16px;font-size:13px;color:#4A5C55;border-bottom:1px solid #F0F2F1;text-align:center;">
              {{ ob.sheets }}
            </td>
            <td style="padding:12px 16px;border-bottom:1px solid #F0F2F1;">
              <span
                class="inline-flex items-center gap-1 rounded-full font-semibold"
                style="font-size:11.5px;padding:2px 9px;border:1px solid transparent;"
                :style="{
                  background: badgeStyles[ob.statusType].background,
                  color: badgeStyles[ob.statusType].color,
                  borderColor: badgeStyles[ob.statusType].borderColor,
                }"
              >
                <span style="width:5px;height:5px;border-radius:50%;background:currentColor;flex-shrink:0;"></span>
                {{ ob.status }}
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
        <span style="font-size:13px;color:#6B7D76;">Mostrando 3 de 12 obligaciones</span>
        <div class="flex items-center gap-1">
          <button
            style="height:30px;min-width:30px;padding:0 8px;font-size:12px;background:#F0F2F1;border:1px solid #E4E8E6;color:#4A5C55;border-radius:6px;cursor:pointer;"
          >‹</button>
          <button
            v-for="p in pages"
            :key="p"
            style="height:30px;min-width:30px;padding:0 8px;font-size:12px;border:1px solid #E4E8E6;border-radius:6px;cursor:pointer;font-weight:600;transition:all .12s;"
            :style="p === currentPage
              ? 'background:#2D7A50;color:white;border-color:#2D7A50;'
              : 'background:#F0F2F1;color:#4A5C55;'"
            @click="currentPage = p"
          >{{ p }}</button>
          <button
            style="height:30px;min-width:30px;padding:0 8px;font-size:12px;background:#F0F2F1;border:1px solid #E4E8E6;color:#4A5C55;border-radius:6px;cursor:pointer;"
          >›</button>
        </div>
      </div>
    </div>

    <!-- ─── Nueva Obligación Modal ─── -->
    <AppModal
      :show="showModal"
      title="Nueva Obligación Ambiental"
      subtitle="Registre el acto administrativo o instrumento ambiental"
      @close="showModal = false"
    >
      <div class="form-section">Tipo e instrumento</div>
      <div class="form-row">
        <div class="form-group">
          <label class="form-label">Tipo de instrumento<span class="req">*</span></label>
          <select v-model="form.tipoInstrumento" class="form-control">
            <option value="">Seleccionar...</option>
            <option>Licencia ambiental</option>
            <option>Concesión de aguas superficiales</option>
            <option>Permiso de vertimientos</option>
            <option>Permiso de emisiones atmosféricas</option>
            <option>Aprovechamiento forestal</option>
            <option>Plan de Manejo Ambiental (PMA)</option>
            <option>Auto de seguimiento</option>
          </select>
        </div>
        <div class="form-group">
          <label class="form-label">Autoridad ambiental<span class="req">*</span></label>
          <select v-model="form.autoridad" class="form-control">
            <option value="">Seleccionar...</option>
            <option>ANLA</option>
            <option>CAR</option>
            <option>CVC</option>
            <option>CORANTIOQUIA</option>
            <option>CDMB</option>
            <option>CORMACARENA</option>
            <option>CORNARE</option>
            <option>CORPONARIÑO</option>
          </select>
        </div>
      </div>
      <div class="form-row">
        <div class="form-group">
          <label class="form-label">Número de resolución/acto<span class="req">*</span></label>
          <input v-model="form.resolucion" type="text" class="form-control" placeholder="Ej: Res. 00234 de 2023" />
        </div>
        <div class="form-group">
          <label class="form-label">Número de expediente</label>
          <input v-model="form.expediente" type="text" class="form-control" placeholder="Ej: LAM2847" />
        </div>
      </div>

      <div class="form-section">Descripción y vigencia</div>
      <div class="form-group">
        <label class="form-label">Objeto de la obligación<span class="req">*</span></label>
        <textarea v-model="form.objeto" class="form-control" rows="2" placeholder="Ej: Permiso de vertimientos al cuerpo de agua superficial..."></textarea>
      </div>
      <div class="form-row">
        <div class="form-group">
          <label class="form-label">Fecha de expedición</label>
          <input v-model="form.fechaExpedicion" type="date" class="form-control" />
        </div>
        <div class="form-group">
          <label class="form-label">Fecha de vencimiento</label>
          <input v-model="form.fechaVencimiento" type="date" class="form-control" />
        </div>
      </div>
      <div class="info-banner warn" style="margin-bottom:0;">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="flex-shrink:0;margin-top:1px;"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
        Los permisos activos con fecha de vencimiento generan alertas automáticas. Adjunte el documento soporte desde la sección de Evidencias.
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
        >Guardar obligación</button>
      </template>
    </AppModal>
  </div>
</template>
