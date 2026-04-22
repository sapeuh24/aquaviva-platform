<script setup>
import { ref, reactive } from 'vue'
import AppModal from '@/components/ui/AppModal.vue'
import StepWizard from '@/components/ui/StepWizard.vue'

const search = ref('')
const filterMedio = ref('')
const filterEstado = ref('')
const filterFase = ref('')

const programs = [
  { code: 'PMR-001', name: 'Manejo de Residuos Sólidos', medio: 'Suelo y subsuelo', fase: 'Operación', faseType: 'green', pct: 87, pctType: 'success', indicators: 6, status: 'success', statusLabel: 'Activo' },
  { code: 'PTAH-002', name: 'Tratamiento de Aguas Residuales', medio: 'Hídrico superficial', fase: 'Operación', faseType: 'green', pct: 64, pctType: 'warn', indicators: 9, status: 'warn', statusLabel: 'En riesgo' },
  { code: 'CEA-003', name: 'Control de Emisiones Atmosféricas', medio: 'Atmosférico', fase: 'Construcción', faseType: 'info', pct: 42, pctType: 'danger', indicators: 4, status: 'danger', statusLabel: 'Crítico' },
  { code: 'GSC-004', name: 'Gestión de Suelos Contaminados', medio: 'Suelo y subsuelo', fase: 'Cierre', faseType: 'gray', pct: 93, pctType: 'success', indicators: 3, status: 'success', statusLabel: 'Activo' },
  { code: 'BIO-005', name: 'Biodiversidad y Ecosistemas', medio: 'Flora y vegetación', fase: 'Operación', faseType: 'green', pct: 71, pctType: 'warn', indicators: 5, status: 'success', statusLabel: 'Activo' },
]

const badgeStyles = {
  success: 'background:#F0FDF4;color:#065F46;border-color:#BBF7D0;',
  warn:    'background:#FFFBEB;color:#92400E;border-color:#FDE68A;',
  danger:  'background:#FFF5F5;color:#991B1B;border-color:#FECACA;',
  info:    'background:#EFF6FF;color:#1E40AF;border-color:#BFDBFE;',
  green:   'background:#F2FAF5;color:#246040;border-color:#DCF0E4;',
  gray:    'background:#F0F2F1;color:#4A5C55;border-color:#E4E8E6;',
}

const barColors = { success: '#3A9A64', warn: '#D97706', danger: '#DC2626' }
const pctColors = { success: '#059669', warn: '#D97706', danger: '#DC2626' }

// Modal state
const showModal = ref(false)
const step = ref(1)
const form = reactive({
  name: '', code: '', medio: '', description: '',
  fase: 'Operación', herramienta: 'Muestreo físico-químico', estado: '1',
})

function openModal() {
  step.value = 1
  showModal.value = true
}

// Side panel state
const selectedItem = ref(null)

function openPanel(prog) {
  selectedItem.value = prog
}

function closePanel() {
  selectedItem.value = null
}
</script>

<template>
  <div>
    <!-- Page header -->
    <div class="flex items-start justify-between mb-5">
      <div>
        <h1 class="font-extrabold" style="font-size:20px;color:#1C2925;line-height:1.2;">Programas Ambientales</h1>
        <p class="mt-1" style="font-size:13px;color:#6B7D76;">Planes de manejo ambiental aprobados y sus indicadores de cumplimiento</p>
      </div>
      <div class="flex items-center gap-2">
        <button
          class="flex items-center gap-2 rounded-md font-semibold"
          style="height:36px;padding:0 14px;font-size:13px;background:#F0F2F1;border:1px solid #E4E8E6;color:#4A5C55;cursor:pointer;"
          @mouseenter="(e) => { e.currentTarget.style.background='#E4E8E6'; }"
          @mouseleave="(e) => { e.currentTarget.style.background='#F0F2F1'; }"
        >Exportar Excel</button>
        <button
          class="flex items-center gap-2 rounded-md font-semibold text-white"
          style="height:36px;padding:0 14px;font-size:13px;background:#2D7A50;border:none;cursor:pointer;"
          @mouseenter="(e) => e.currentTarget.style.background='#246040'"
          @mouseleave="(e) => e.currentTarget.style.background='#2D7A50'"
          @click="openModal"
        >
          <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
          Nuevo programa
        </button>
      </div>
    </div>

    <!-- Filters -->
    <div class="flex items-center gap-2 mb-4 flex-wrap">
      <div
        class="flex items-center gap-2 rounded-md flex-1"
        style="min-width:220px;max-width:300px;background:#F0F2F1;border:1px solid #E4E8E6;padding:0 11px;height:36px;"
        @focusin="(e) => { e.currentTarget.style.borderColor='#3A9A64'; e.currentTarget.style.background='#fff'; e.currentTarget.style.boxShadow='0 0 0 3px #DCF0E4'; }"
        @focusout="(e) => { e.currentTarget.style.borderColor='#E4E8E6'; e.currentTarget.style.background='#F0F2F1'; e.currentTarget.style.boxShadow='none'; }"
      >
        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#9FADA7" stroke-width="2" style="flex-shrink:0;"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
        <input v-model="search" type="text" placeholder="Buscar por nombre o código..." class="bg-transparent border-none outline-none flex-1" style="font-size:13px;color:#2E3D38;" />
      </div>
      <select v-model="filterMedio" class="rounded-md border outline-none" style="height:36px;padding:0 10px;font-size:13px;background:#F0F2F1;border-color:#E4E8E6;color:#4A5C55;cursor:pointer;">
        <option value="">Todos los medios</option>
        <option>Hídrico superficial</option><option>Hídrico subterráneo</option>
        <option>Atmosférico</option><option>Suelo y subsuelo</option><option>Flora y vegetación</option>
      </select>
      <select v-model="filterEstado" class="rounded-md border outline-none" style="height:36px;padding:0 10px;font-size:13px;background:#F0F2F1;border-color:#E4E8E6;color:#4A5C55;cursor:pointer;">
        <option value="">Todos los estados</option><option>Activos</option><option>Inactivos</option>
      </select>
      <select v-model="filterFase" class="rounded-md border outline-none" style="height:36px;padding:0 10px;font-size:13px;background:#F0F2F1;border-color:#E4E8E6;color:#4A5C55;cursor:pointer;">
        <option value="">Todas las fases</option><option>Construcción</option><option>Operación</option><option>Cierre</option>
      </select>
    </div>

    <!-- Table -->
    <div class="rounded-lg overflow-hidden" style="background:white;border:1px solid #E4E8E6;box-shadow:0 1px 3px rgba(0,0,0,.08);">
      <div style="overflow-x:auto;">
        <table style="width:100%;border-collapse:collapse;">
          <thead>
            <tr style="background:#F8F9F8;border-bottom:1px solid #E4E8E6;">
              <th style="padding:10px 16px;font-size:11.5px;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:#6B7D76;text-align:left;white-space:nowrap;">Código</th>
              <th style="padding:10px 16px;font-size:11.5px;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:#6B7D76;text-align:left;">Nombre del programa</th>
              <th style="padding:10px 16px;font-size:11.5px;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:#6B7D76;text-align:left;white-space:nowrap;">Medio ambiental</th>
              <th style="padding:10px 16px;font-size:11.5px;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:#6B7D76;text-align:left;">Fase</th>
              <th style="padding:10px 16px;font-size:11.5px;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:#6B7D76;text-align:left;white-space:nowrap;">Cumplimiento</th>
              <th style="padding:10px 16px;font-size:11.5px;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:#6B7D76;text-align:left;white-space:nowrap;">Indicadores</th>
              <th style="padding:10px 16px;font-size:11.5px;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:#6B7D76;text-align:left;">Estado</th>
              <th style="padding:10px 16px;"></th>
            </tr>
          </thead>
          <tbody>
            <tr
              v-for="prog in programs"
              :key="prog.code"
              style="border-bottom:1px solid #F0F2F1;transition:background .18s;cursor:pointer;"
              @mouseenter="(e) => e.currentTarget.style.background='#F2FAF5'"
              @mouseleave="(e) => e.currentTarget.style.background='transparent'"
            >
              <td style="padding:12px 16px;font-family:monospace;font-size:12px;color:#246040;font-weight:600;">{{ prog.code }}</td>
              <td style="padding:12px 16px;font-size:13px;font-weight:600;color:#1C2925;">{{ prog.name }}</td>
              <td style="padding:12px 16px;font-size:13px;color:#4A5C55;">{{ prog.medio }}</td>
              <td style="padding:12px 16px;">
                <span class="inline-flex items-center rounded-full font-semibold" style="font-size:11.5px;padding:2px 9px;border:1px solid transparent;" :style="badgeStyles[prog.faseType]">{{ prog.fase }}</span>
              </td>
              <td style="padding:12px 16px;">
                <div class="flex items-center gap-2">
                  <div style="width:90px;background:#E4E8E6;border-radius:20px;height:6px;overflow:hidden;flex-shrink:0;">
                    <div style="height:100%;border-radius:20px;" :style="`width:${prog.pct}%;background:${barColors[prog.pctType]};`" />
                  </div>
                  <span class="font-bold" style="font-size:12px;" :style="`color:${pctColors[prog.pctType]}`">{{ prog.pct }}%</span>
                </div>
              </td>
              <td style="padding:12px 16px;font-size:13px;color:#4A5C55;">{{ prog.indicators }} activos</td>
              <td style="padding:12px 16px;">
                <span class="inline-flex items-center gap-1 rounded-full font-semibold" style="font-size:11.5px;padding:2px 9px;border:1px solid transparent;" :style="badgeStyles[prog.status]">
                  <span style="width:5px;height:5px;border-radius:50%;background:currentColor;flex-shrink:0;"></span>
                  {{ prog.statusLabel }}
                </span>
              </td>
              <td style="padding:12px 16px;">
                <div class="flex items-center gap-1">
                  <button
                    class="flex items-center justify-center rounded-md transition-colors"
                    style="width:28px;height:28px;background:#F0F2F1;border:1px solid #E4E8E6;color:#4A5C55;cursor:pointer;"
                    title="Ver detalle"
                    @mouseenter="(e) => e.currentTarget.style.background='#E4E8E6'"
                    @mouseleave="(e) => e.currentTarget.style.background='#F0F2F1'"
                    @click.stop="openPanel(prog)"
                  >
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                  </button>
                  <button class="flex items-center justify-center rounded-md transition-colors" style="width:28px;height:28px;background:#F0F2F1;border:1px solid #E4E8E6;color:#4A5C55;cursor:pointer;" title="Editar" @mouseenter="(e) => e.currentTarget.style.background='#E4E8E6'" @mouseleave="(e) => e.currentTarget.style.background='#F0F2F1'">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      <!-- Pagination -->
      <div class="flex items-center justify-between" style="padding:12px 16px;border-top:1px solid #F0F2F1;background:#F8F9F8;">
        <span style="font-size:12.5px;color:#6B7D76;">Mostrando 5 de 8 programas</span>
        <div class="flex items-center gap-1">
          <button class="flex items-center justify-center rounded-md" style="width:30px;height:30px;background:white;border:1px solid #E4E8E6;color:#4A5C55;font-size:13px;cursor:pointer;">‹</button>
          <button class="flex items-center justify-center rounded-md font-bold" style="width:30px;height:30px;background:#2D7A50;border:1px solid #2D7A50;color:white;font-size:13px;cursor:pointer;">1</button>
          <button class="flex items-center justify-center rounded-md" style="width:30px;height:30px;background:white;border:1px solid #E4E8E6;color:#4A5C55;font-size:13px;cursor:pointer;">2</button>
          <button class="flex items-center justify-center rounded-md" style="width:30px;height:30px;background:white;border:1px solid #E4E8E6;color:#4A5C55;font-size:13px;cursor:pointer;">›</button>
        </div>
      </div>
    </div>

    <!-- ─── New Program Modal ─── -->
    <AppModal
      :show="showModal"
      title="Nuevo Programa Ambiental"
      subtitle="Complete la información del programa de manejo"
      @close="showModal = false"
    >
      <StepWizard :steps="['Información básica', 'Configuración']" :current="step" />

      <!-- Step 1 -->
      <div v-if="step === 1">
        <div class="form-row">
          <div class="form-group">
            <label class="form-label">Nombre del programa<span class="req">*</span></label>
            <input v-model="form.name" type="text" class="form-control" placeholder="Ej: Manejo de aguas residuales" />
          </div>
          <div class="form-group">
            <label class="form-label">Código interno<span class="req">*</span></label>
            <input v-model="form.code" type="text" class="form-control" placeholder="Ej: PMR-006" />
          </div>
        </div>
        <div class="form-group">
          <label class="form-label">Medio ambiental<span class="req">*</span></label>
          <select v-model="form.medio" class="form-control">
            <option value="">Seleccionar...</option>
            <option>Hídrico superficial</option>
            <option>Hídrico subterráneo</option>
            <option>Atmosférico</option>
            <option>Suelo y subsuelo</option>
            <option>Flora y vegetación</option>
            <option>Fauna silvestre</option>
            <option>Paisaje</option>
          </select>
        </div>
        <div class="form-group">
          <label class="form-label">Descripción y objetivos</label>
          <textarea v-model="form.description" class="form-control" rows="3" placeholder="Describa el objetivo principal del programa..."></textarea>
        </div>
      </div>

      <!-- Step 2 -->
      <div v-if="step === 2">
        <div class="info-banner success">
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="flex-shrink:0;margin-top:1px;"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
          Información básica completada. Configure ahora la fase y las herramientas de seguimiento.
        </div>
        <div class="form-row">
          <div class="form-group">
            <label class="form-label">Fase del proyecto<span class="req">*</span></label>
            <select v-model="form.fase" class="form-control">
              <option>Pre-construcción</option>
              <option>Construcción</option>
              <option>Operación</option>
              <option>Cierre y abandono</option>
              <option>Post-cierre</option>
            </select>
          </div>
          <div class="form-group">
            <label class="form-label">Estado inicial</label>
            <select v-model="form.estado" class="form-control">
              <option value="1">Activo</option>
              <option value="0">Inactivo</option>
            </select>
          </div>
        </div>
        <div class="form-group">
          <label class="form-label">Herramienta de monitoreo</label>
          <select v-model="form.herramienta" class="form-control">
            <option>Muestreo físico-químico</option>
            <option>Monitoreo continuo automático</option>
            <option>Inspección visual en campo</option>
            <option>Registro fotográfico</option>
            <option>Encuesta y entrevista</option>
            <option>Análisis de laboratorio</option>
          </select>
        </div>
        <div class="info-banner info" style="margin-bottom:0;">
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="flex-shrink:0;margin-top:1px;"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
          Tras crear el programa, podrá agregar indicadores de seguimiento desde la sección Indicadores.
        </div>
      </div>

      <template #footer>
        <button
          v-if="step > 1"
          style="height:36px;padding:0 14px;font-size:13px;background:#F0F2F1;border:1px solid #E4E8E6;color:#4A5C55;border-radius:6px;cursor:pointer;"
          @click="step--"
        >← Atrás</button>
        <div class="flex-1" />
        <button
          style="height:36px;padding:0 14px;font-size:13px;background:#F0F2F1;border:1px solid #E4E8E6;color:#4A5C55;border-radius:6px;cursor:pointer;"
          @click="showModal = false"
        >Cancelar</button>
        <button
          style="height:36px;padding:0 14px;font-size:13px;background:#2D7A50;border:none;color:white;border-radius:6px;cursor:pointer;font-weight:600;"
          @mouseenter="(e) => e.currentTarget.style.background='#246040'"
          @mouseleave="(e) => e.currentTarget.style.background='#2D7A50'"
          @click="step < 2 ? step++ : showModal = false"
        >{{ step < 2 ? 'Siguiente →' : 'Guardar programa' }}</button>
      </template>
    </AppModal>

    <!-- ─── Detail Side Panel ─── -->
    <Teleport to="body">
      <Transition name="panel">
        <div v-if="selectedItem" style="position:fixed;inset:0;z-index:200;" @click.self="closePanel">
          <!-- Backdrop -->
          <div style="position:absolute;inset:0;background:rgba(15,25,18,.3);" @click="closePanel" />
          <!-- Panel -->
          <div
            style="position:absolute;top:0;right:0;height:100%;width:380px;background:white;box-shadow:-4px 0 24px rgba(0,0,0,.14);display:flex;flex-direction:column;overflow:hidden;"
          >
            <!-- Panel header -->
            <div style="padding:18px 20px 14px;border-bottom:1px solid #F0F2F1;background:#F8F9F8;">
              <div class="flex items-start justify-between">
                <div>
                  <div style="font-family:monospace;font-size:11.5px;color:#246040;font-weight:600;margin-bottom:4px;">{{ selectedItem.code }}</div>
                  <h3 style="font-size:15px;font-weight:800;color:#1C2925;line-height:1.3;">{{ selectedItem.name }}</h3>
                </div>
                <button
                  style="width:30px;height:30px;background:#F0F2F1;border:none;border-radius:6px;color:#6B7D76;font-size:15px;cursor:pointer;flex-shrink:0;margin-left:10px;"
                  @click="closePanel"
                  @mouseenter="(e) => { e.currentTarget.style.background='#FFF5F5'; e.currentTarget.style.color='#DC2626'; }"
                  @mouseleave="(e) => { e.currentTarget.style.background='#F0F2F1'; e.currentTarget.style.color='#6B7D76'; }"
                >✕</button>
              </div>
            </div>

            <!-- Panel body -->
            <div style="flex:1;overflow-y:auto;padding:20px;">
              <div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:1px;color:#9FADA7;margin-bottom:12px;">Información del programa</div>

              <!-- Info rows -->
              <div style="display:flex;flex-direction:column;gap:0;">
                <div style="display:flex;justify-content:space-between;align-items:center;padding:10px 0;border-bottom:1px solid #F0F2F1;">
                  <span style="font-size:12.5px;color:#6B7D76;font-weight:500;">Código</span>
                  <span style="font-family:monospace;font-size:12px;color:#246040;font-weight:600;background:#F2FAF5;padding:2px 8px;border-radius:4px;border:1px solid #DCF0E4;">{{ selectedItem.code }}</span>
                </div>
                <div style="display:flex;justify-content:space-between;align-items:center;padding:10px 0;border-bottom:1px solid #F0F2F1;">
                  <span style="font-size:12.5px;color:#6B7D76;font-weight:500;">Medio ambiental</span>
                  <span style="font-size:13px;color:#1C2925;font-weight:600;">{{ selectedItem.medio }}</span>
                </div>
                <div style="display:flex;justify-content:space-between;align-items:center;padding:10px 0;border-bottom:1px solid #F0F2F1;">
                  <span style="font-size:12.5px;color:#6B7D76;font-weight:500;">Fase</span>
                  <span class="inline-flex items-center rounded-full font-semibold" style="font-size:11.5px;padding:2px 9px;border:1px solid transparent;" :style="badgeStyles[selectedItem.faseType]">{{ selectedItem.fase }}</span>
                </div>
                <div style="display:flex;justify-content:space-between;align-items:center;padding:10px 0;border-bottom:1px solid #F0F2F1;">
                  <span style="font-size:12.5px;color:#6B7D76;font-weight:500;">Cumplimiento</span>
                  <div class="flex items-center gap-2">
                    <div style="width:80px;background:#E4E8E6;border-radius:20px;height:6px;overflow:hidden;">
                      <div style="height:100%;border-radius:20px;" :style="`width:${selectedItem.pct}%;background:${barColors[selectedItem.pctType]};`" />
                    </div>
                    <span style="font-size:13px;font-weight:700;" :style="`color:${pctColors[selectedItem.pctType]}`">{{ selectedItem.pct }}%</span>
                  </div>
                </div>
                <div style="display:flex;justify-content:space-between;align-items:center;padding:10px 0;border-bottom:1px solid #F0F2F1;">
                  <span style="font-size:12.5px;color:#6B7D76;font-weight:500;">Indicadores activos</span>
                  <span style="font-size:13px;color:#1C2925;font-weight:600;">{{ selectedItem.indicators }}</span>
                </div>
                <div style="display:flex;justify-content:space-between;align-items:center;padding:10px 0;">
                  <span style="font-size:12.5px;color:#6B7D76;font-weight:500;">Estado</span>
                  <span class="inline-flex items-center gap-1 rounded-full font-semibold" style="font-size:11.5px;padding:2px 9px;border:1px solid transparent;" :style="badgeStyles[selectedItem.status]">
                    <span style="width:5px;height:5px;border-radius:50%;background:currentColor;flex-shrink:0;"></span>
                    {{ selectedItem.statusLabel }}
                  </span>
                </div>
              </div>
            </div>

            <!-- Panel footer -->
            <div style="padding:14px 20px;border-top:1px solid #F0F2F1;background:#F8F9F8;">
              <button
                style="width:100%;height:36px;font-size:13px;font-weight:600;background:#2D7A50;border:none;color:white;border-radius:6px;cursor:pointer;"
                @mouseenter="(e) => e.currentTarget.style.background='#246040'"
                @mouseleave="(e) => e.currentTarget.style.background='#2D7A50'"
              >Editar programa</button>
            </div>
          </div>
        </div>
      </Transition>
    </Teleport>
  </div>
</template>

<style scoped>
.panel-enter-active { animation: panelIn .22s cubic-bezier(.34,1.1,.64,1); }
.panel-leave-active { animation: panelIn .18s ease reverse; }
@keyframes panelIn {
  from { opacity: 0; transform: translateX(40px); }
  to   { opacity: 1; transform: translateX(0); }
}
</style>
