<script setup>
import { ref, reactive, computed, onMounted } from 'vue'
import AppModal from '@/components/ui/AppModal.vue'
import { useDb } from '@/composables/useDb.js'

const { db, dbStatus, loadDb, saveDb, nextId } = useDb()
onMounted(() => loadDb())

const currentPage = ref(1)

const FRECUENCIAS = ['única', 'diaria', 'semanal', 'mensual', 'bimensual', 'trimestral', 'semestral', 'anual']

const COMPROMISOS = computed(() =>
  db.compromisos.map(c => ({
    id: c.id,
    label: `${c.tipo_compromiso} — ${c.no_expediente} (${c.autoridad})`,
  }))
)

const PROGRAMAS = computed(() => {
  const all = db.programas ?? []
  if (!form.compromiso_id) return all.map(p => ({ id: p.id, label: `${p.id} — ${p.nombre_programa}` }))
  return all
    .filter(p => p.compromiso_id === Number(form.compromiso_id))
    .map(p => ({ id: p.id, label: `${p.id} — ${p.nombre_programa}` }))
})

const badgeTipo = {
  normativa: { background: '#EFF6FF', color: '#1E40AF', borderColor: '#BFDBFE' },
  programa:  { background: '#F0FDF4', color: '#065F46', borderColor: '#BBF7D0' },
}

const badgeEstado = {
  abierta: { background: '#F0FDF4', color: '#065F46', borderColor: '#BBF7D0' },
  cerrada: { background: '#F0F2F1', color: '#4A5C55', borderColor: '#E4E8E6' },
}

const showModal = ref(false)
const form = reactive({
  compromiso_id: '',
  programa_id: '',
  tipo_accion: '',
  nombre_accion: '',
  referencia_accion: '',
  accion: '',
  fecha_inicio: '',
  puntual_o_periodico: '',
  plazo_ejecucion: '',
  periodicidad: '',
  estado: 'abierta',
})

const esPuntual = computed(() => form.puntual_o_periodico === 'Puntual')
const esPeriodica = computed(() => form.puntual_o_periodico === 'Periódica')

function openModal() {
  Object.assign(form, {
    compromiso_id: '', programa_id: '', tipo_accion: '', nombre_accion: '',
    referencia_accion: '', accion: '', fecha_inicio: '',
    puntual_o_periodico: '', plazo_ejecucion: '', periodicidad: '', estado: 'abierta',
  })
  showModal.value = true
}

async function guardarAccion() {
  if (!form.nombre_accion || !form.tipo_accion) return
  db.acciones.push({
    id: nextId(db.acciones),
    compromiso_id: Number(form.compromiso_id) || null,
    programa_id: form.tipo_accion === 'programa' && form.programa_id ? form.programa_id : null,
    programa: null,
    tipo: form.tipo_accion,
    tipo_accion: form.tipo_accion,
    descripcion: form.nombre_accion,
    nombre_accion: form.nombre_accion,
    referencia_accion: form.referencia_accion,
    accion: form.accion,
    fecha_inicio: form.fecha_inicio,
    puntual_o_periodico: form.puntual_o_periodico,
    plazo_ejecucion: form.plazo_ejecucion ? Number(form.plazo_ejecucion) : null,
    periodicidad: form.periodicidad || null,
    estado: form.estado,
  })
  showModal.value = false
  await saveDb()
}

function compromisoLabel(id) {
  return COMPROMISOS.value.find(c => c.id === id)?.label ?? '—'
}

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
          Acciones
        </h1>
        <p class="mt-1" style="font-size:13px;color:#6B7D76;">
          Acciones normativas y de programa asociadas a los compromisos ambientales
        </p>
      </div>
      <button
        style="height:36px;padding:0 14px;font-size:13px;background:#2D6A4F;border:none;color:white;border-radius:8px;cursor:pointer;font-weight:600;transition:background .15s;"
        @mouseenter="(e) => e.currentTarget.style.background='#1A3D2B'"
        @mouseleave="(e) => e.currentTarget.style.background='#2D6A4F'"
        @click="openModal"
      >
        + Nueva acción
      </button>
    </div>

    <!-- Saving indicator -->
    <div v-if="dbStatus === 'saving'" style="margin-bottom:12px;padding:8px 14px;background:#EFF6FF;border:1px solid #BFDBFE;border-radius:6px;font-size:12.5px;color:#1E40AF;display:flex;align-items:center;gap:6px;">
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="animation:spin 1s linear infinite;"><path d="M21 12a9 9 0 1 1-6.219-8.56"/></svg>
      Guardando en la base de datos...
    </div>

    <!-- Table -->
    <div class="rounded-lg overflow-hidden" style="background:white;border:1px solid #E4E8E6;box-shadow:0 1px 3px rgba(0,0,0,.08);">
      <div style="overflow-x:auto;">
        <table style="width:100%;border-collapse:collapse;">
          <thead>
            <tr style="background:#1A3D2B;">
              <th style="padding:10px 16px;font-size:11.5px;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:white;text-align:left;width:40px;">No.</th>
              <th style="padding:10px 16px;font-size:11.5px;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:white;text-align:left;white-space:nowrap;">Compromiso</th>
              <th style="padding:10px 16px;font-size:11.5px;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:white;text-align:left;white-space:nowrap;">Tipo</th>
              <th style="padding:10px 16px;font-size:11.5px;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:white;text-align:left;">Nombre Acción</th>
              <th style="padding:10px 16px;font-size:11.5px;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:white;text-align:left;white-space:nowrap;">Referencia</th>
              <th style="padding:10px 16px;font-size:11.5px;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:white;text-align:left;white-space:nowrap;">Fecha Inicio</th>
              <th style="padding:10px 16px;font-size:11.5px;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:white;text-align:left;white-space:nowrap;">Puntual/Periódica</th>
              <th style="padding:10px 16px;font-size:11.5px;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:white;text-align:left;">Estado</th>
              <th style="padding:10px 16px;"></th>
            </tr>
          </thead>
          <tbody>
            <tr v-if="!db.acciones.length">
              <td colspan="9" style="padding:40px;text-align:center;font-size:13px;color:#9FADA7;">
                {{ dbStatus === 'loading' ? 'Cargando datos...' : 'No hay acciones registradas. Cree la primera.' }}
              </td>
            </tr>
            <tr
              v-for="(accion, idx) in db.acciones"
              :key="accion.id"
              style="cursor:pointer;transition:background .12s;"
              @mouseenter="rowEnter"
              @mouseleave="rowLeave"
            >
              <td style="padding:12px 16px;font-size:13px;color:#6B7D76;border-bottom:1px solid #F0F2F1;font-weight:600;">
                {{ idx + 1 }}
              </td>
              <td style="padding:12px 16px;border-bottom:1px solid #F0F2F1;max-width:160px;">
                <span style="font-size:11.5px;color:#4A5C55;">
                  {{ compromisoLabel(accion.compromiso_id) }}
                </span>
              </td>
              <td style="padding:12px 16px;border-bottom:1px solid #F0F2F1;">
                <span
                  class="inline-flex items-center rounded-full font-semibold"
                  style="font-size:11.5px;padding:2px 9px;border:1px solid transparent;white-space:nowrap;"
                  :style="{
                    background: badgeTipo[accion.tipo_accion]?.background ?? '#F0F2F1',
                    color: badgeTipo[accion.tipo_accion]?.color ?? '#4A5C55',
                    borderColor: badgeTipo[accion.tipo_accion]?.borderColor ?? '#E4E8E6',
                  }"
                >
                  {{ accion.tipo_accion ? accion.tipo_accion.charAt(0).toUpperCase() + accion.tipo_accion.slice(1) : '—' }}
                </span>
              </td>
              <td style="padding:12px 16px;border-bottom:1px solid #F0F2F1;max-width:280px;">
                <p style="font-size:13px;color:#1C2925;font-weight:500;margin-bottom:2px;">{{ accion.nombre_accion }}</p>
                <p v-if="accion.accion" style="font-size:11.5px;color:#6B7D76;line-height:1.4;overflow:hidden;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;">{{ accion.accion }}</p>
              </td>
              <td style="padding:12px 16px;font-size:13px;border-bottom:1px solid #F0F2F1;">
                <span style="background:#F2FAF5;color:#246040;border:1px solid #DCF0E4;border-radius:4px;padding:2px 7px;font-size:11px;font-weight:600;font-family:monospace;white-space:nowrap;">
                  {{ accion.referencia_accion || '—' }}
                </span>
              </td>
              <td style="padding:12px 16px;font-size:13px;color:#4A5C55;border-bottom:1px solid #F0F2F1;white-space:nowrap;">
                {{ accion.fecha_inicio || '—' }}
              </td>
              <td style="padding:12px 16px;font-size:13px;color:#4A5C55;border-bottom:1px solid #F0F2F1;white-space:nowrap;">
                {{ accion.puntual_o_periodico || '—' }}
                <span v-if="accion.puntual_o_periodico === 'Periódica' && accion.periodicidad" style="font-size:11px;color:#9FADA7;margin-left:4px;">({{ accion.periodicidad }})</span>
                <span v-if="accion.puntual_o_periodico === 'Puntual' && accion.plazo_ejecucion" style="font-size:11px;color:#9FADA7;margin-left:4px;">{{ accion.plazo_ejecucion }} días</span>
              </td>
              <td style="padding:12px 16px;border-bottom:1px solid #F0F2F1;">
                <span
                  class="inline-flex items-center gap-1 rounded-full font-semibold"
                  style="font-size:11.5px;padding:2px 9px;border:1px solid transparent;"
                  :style="{
                    background: badgeEstado[accion.estado]?.background ?? '#F0F2F1',
                    color: badgeEstado[accion.estado]?.color ?? '#4A5C55',
                    borderColor: badgeEstado[accion.estado]?.borderColor ?? '#E4E8E6',
                  }"
                >
                  <span style="width:5px;height:5px;border-radius:50%;background:currentColor;flex-shrink:0;"></span>
                  {{ accion.estado ? accion.estado.charAt(0).toUpperCase() + accion.estado.slice(1) : '—' }}
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
      </div>

      <!-- Pagination -->
      <div class="flex items-center justify-between" style="padding:12px 16px;border-top:1px solid #F0F2F1;">
        <span style="font-size:13px;color:#6B7D76;">Mostrando {{ db.acciones.length }} de {{ db.acciones.length }} acciones</span>
        <div class="flex items-center gap-1">
          <button style="height:30px;min-width:30px;padding:0 8px;font-size:12px;background:#F0F2F1;border:1px solid #E4E8E6;color:#4A5C55;border-radius:6px;cursor:pointer;">‹</button>
          <button style="height:30px;min-width:30px;padding:0 8px;font-size:12px;background:#2D6A4F;border:1px solid #2D6A4F;color:white;border-radius:6px;cursor:pointer;font-weight:600;">1</button>
          <button style="height:30px;min-width:30px;padding:0 8px;font-size:12px;background:#F0F2F1;border:1px solid #E4E8E6;color:#4A5C55;border-radius:6px;cursor:pointer;">›</button>
        </div>
      </div>
    </div>

    <!-- ─── Nueva Acción Modal ─── -->
    <AppModal
      :show="showModal"
      title="Nueva Acción"
      subtitle="Registre la acción normativa o de programa ambiental"
      @close="showModal = false"
    >
      <div class="form-section">Compromiso asociado</div>
      <div class="form-group">
        <label class="form-label">Compromiso<span class="req">*</span></label>
        <select v-model="form.compromiso_id" class="form-control">
          <option value="">Seleccionar compromiso...</option>
          <option v-for="c in COMPROMISOS" :key="c.id" :value="c.id">{{ c.label }}</option>
        </select>
      </div>

      <div class="form-section">Tipo y nombre</div>
      <div class="form-row">
        <div class="form-group">
          <label class="form-label">Tipo de acción<span class="req">*</span></label>
          <select v-model="form.tipo_accion" class="form-control" @change="form.programa_id = ''">
            <option value="">Seleccionar...</option>
            <option value="normativa">Normativa</option>
            <option value="programa">Programa</option>
          </select>
        </div>
        <div class="form-group">
          <label class="form-label">Referencia de la acción</label>
          <input v-model="form.referencia_accion" type="text" class="form-control" placeholder="Ej: Resolución No. 002163 de 2024" />
        </div>
      </div>
      <div v-if="form.tipo_accion === 'programa'" class="form-group">
        <label class="form-label">Programa ambiental<span class="req">*</span></label>
        <select v-model="form.programa_id" class="form-control">
          <option value="">Seleccionar programa...</option>
          <option v-for="p in PROGRAMAS" :key="p.id" :value="p.id">{{ p.label }}</option>
        </select>
        <p v-if="!PROGRAMAS.length" style="font-size:11.5px;color:#9FADA7;margin-top:4px;">
          {{ form.compromiso_id ? 'No hay programas para este compromiso.' : 'Seleccione un compromiso primero para filtrar los programas.' }}
        </p>
      </div>
      <div class="form-group">
        <label class="form-label">Nombre de la acción<span class="req">*</span></label>
        <input v-model="form.nombre_accion" type="text" class="form-control" placeholder="Ej: Manejo de erosión y estabilización de taludes" />
      </div>
      <div class="form-group">
        <label class="form-label">Descripción de la acción (texto de la obligación)</label>
        <textarea v-model="form.accion" class="form-control" rows="3" placeholder="Describa en detalle la acción a ejecutar..."></textarea>
      </div>

      <div class="form-section">Programación</div>
      <div class="form-row">
        <div class="form-group">
          <label class="form-label">Fecha de inicio</label>
          <input v-model="form.fecha_inicio" type="date" class="form-control" />
        </div>
        <div class="form-group">
          <label class="form-label">Puntual o periódica</label>
          <select v-model="form.puntual_o_periodico" class="form-control">
            <option value="">Seleccionar...</option>
            <option value="Puntual">Puntual</option>
            <option value="Periódica">Periódica</option>
          </select>
        </div>
      </div>
      <div v-if="esPuntual" class="form-group">
        <label class="form-label">Plazo de ejecución (días)</label>
        <input v-model="form.plazo_ejecucion" type="number" class="form-control" placeholder="Ej: 30" />
      </div>
      <div v-if="esPeriodica" class="form-group">
        <label class="form-label">Periodicidad</label>
        <select v-model="form.periodicidad" class="form-control">
          <option value="">Seleccionar...</option>
          <option v-for="f in FRECUENCIAS" :key="f" :value="f">{{ f.charAt(0).toUpperCase() + f.slice(1) }}</option>
        </select>
      </div>
      <div class="form-group">
        <label class="form-label">Estado</label>
        <select v-model="form.estado" class="form-control">
          <option value="abierta">Abierta</option>
          <option value="cerrada">Cerrada</option>
        </select>
      </div>

      <template #footer>
        <button
          style="height:36px;padding:0 14px;font-size:13px;background:#F0F2F1;border:1px solid #E4E8E6;color:#4A5C55;border-radius:6px;cursor:pointer;"
          @click="showModal = false"
        >Cancelar</button>
        <button
          style="height:36px;padding:0 14px;font-size:13px;background:#2D6A4F;border:none;color:white;border-radius:6px;cursor:pointer;font-weight:600;"
          :disabled="!form.nombre_accion || !form.tipo_accion || dbStatus === 'saving'"
          :style="(!form.nombre_accion || !form.tipo_accion || dbStatus === 'saving') ? 'opacity:.6;cursor:not-allowed;' : ''"
          @mouseenter="(e) => { if (form.nombre_accion && form.tipo_accion) e.currentTarget.style.background='#1A3D2B' }"
          @mouseleave="(e) => e.currentTarget.style.background='#2D6A4F'"
          @click="guardarAccion"
        >{{ dbStatus === 'saving' ? 'Guardando...' : 'Guardar acción' }}</button>
      </template>
    </AppModal>
  </div>
</template>
