<script setup>
import { ref, reactive, computed, onMounted } from 'vue'
import AppModal from '@/components/ui/AppModal.vue'
import { useDb } from '@/composables/useDb.js'

const { db, loadDb } = useDb()
onMounted(() => loadDb())

const search = ref('')
const filterMedio = ref('')

const COMPROMISOS_LISTA = computed(() =>
  db.compromisos.map(c => ({
    id: c.id,
    label: `${c.tipo_compromiso} — ${c.no_expediente} (${c.autoridad})`,
  }))
)

const TIPOS_PLAN  = ['Plan de manejo ambiental','Plan de seguimiento y monitoreo','Plan de gestión del riesgo','Plan de desmantelamiento y abandono','Plan de inversión 1%','Plan de compensación']
const TIPOS_MEDIDA = ['prevención','mitigación','corrección','compensación']
const MEDIOS = ['Abiotico','Biotico','Social']

const badgeMedio = {
  Abiotico: { background:'#EFF6FF', color:'#1E40AF', borderColor:'#BFDBFE' },
  Biotico:  { background:'#F0FDF4', color:'#065F46', borderColor:'#BBF7D0' },
  Social:   { background:'#FFF7ED', color:'#9A3412', borderColor:'#FDBA74' },
}
const badgeMedida = {
  'prevención':   { background:'#EFF6FF', color:'#1E40AF', borderColor:'#BFDBFE' },
  'mitigación':   { background:'#F0FDF4', color:'#065F46', borderColor:'#BBF7D0' },
  'corrección':   { background:'#FFFBEB', color:'#92400E', borderColor:'#FDE68A' },
  'compensación': { background:'#FDF4FF', color:'#7E22CE', borderColor:'#E9D5FF' },
}

// ─── Programas demo ───────────────────────────────────────────────────────────
const programs = ref([
  { id:'VSM37-PMA-AB-S-1', compromiso_id:1, tipo_plan:'Plan de manejo ambiental',        medio:'Abiotico', nombre_programa:'Manejo y disposición de material sobrante de excavación', nombre_medida_ficha:'Disposición adecuada de materiales sobrantes',        tipo_medida:'prevención' },
  { id:'VSM37-PMA-AB-S-2', compromiso_id:1, tipo_plan:'Plan de manejo ambiental',        medio:'Abiotico', nombre_programa:'Manejo y control de procesos erosivos',                   nombre_medida_ficha:'Control de erosión en taludes y zonas intervenidas',  tipo_medida:'mitigación' },
  { id:'VSM37-PMA-AB-S-3', compromiso_id:1, tipo_plan:'Plan de manejo ambiental',        medio:'Abiotico', nombre_programa:'Manejo de aguas superficiales y subterráneas',             nombre_medida_ficha:'Control de escorrentía y protección de cuerpos hídricos', tipo_medida:'mitigación' },
  { id:'VSM37-PMA-AB-S-4', compromiso_id:1, tipo_plan:'Plan de seguimiento y monitoreo', medio:'Abiotico', nombre_programa:'Manejo de suelos y procesos de revegetalización',          nombre_medida_ficha:'Restauración de cobertura vegetal en áreas disturbadas', tipo_medida:'corrección' },
  { id:'VSM37-PMA-AB-S-5', compromiso_id:2, tipo_plan:'Plan de manejo ambiental',        medio:'Abiotico', nombre_programa:'Manejo de residuos sólidos y líquidos',                   nombre_medida_ficha:'Gestión integral de residuos generados en obra',         tipo_medida:'prevención' },
  { id:'VSM37-PMA-B-EP-1', compromiso_id:2, tipo_plan:'Plan de manejo ambiental',        medio:'Biotico',  nombre_programa:'Manejo de ecosistemas y especies de flora',               nombre_medida_ficha:'Rescate y reubicación de flora en zona de intervención', tipo_medida:'compensación' },
  { id:'VSM37-PMA-B-EP-2', compromiso_id:2, tipo_plan:'Plan de manejo ambiental',        medio:'Biotico',  nombre_programa:'Manejo de fauna silvestre',                               nombre_medida_ficha:'Rescate, ahuyentamiento y reubicación de fauna',         tipo_medida:'mitigación' },
])

// ─── Acciones por programa ────────────────────────────────────────────────────
const acciones = ref([
  { id:1,  programa_id:'VSM37-PMA-AB-S-1', descripcion:'Verificación de plan de disposición aprobado' },
  { id:2,  programa_id:'VSM37-PMA-AB-S-1', descripcion:'Contratación de empresa gestora de residuos' },
  { id:3,  programa_id:'VSM37-PMA-AB-S-1', descripcion:'Registro de volúmenes dispuestos mensualmente' },
  { id:4,  programa_id:'VSM37-PMA-AB-S-2', descripcion:'Instalación de barreras de contención en taludes' },
  { id:5,  programa_id:'VSM37-PMA-AB-S-2', descripcion:'Monitoreo mensual de estabilidad de taludes' },
  { id:6,  programa_id:'VSM37-PMA-AB-S-2', descripcion:'Siembra de cobertura vegetal en zonas críticas' },
  { id:7,  programa_id:'VSM37-PMA-AB-S-3', descripcion:'Instalación de estructuras de manejo de escorrentía' },
  { id:8,  programa_id:'VSM37-PMA-AB-S-3', descripcion:'Monitoreo de calidad de agua en puntos de control' },
  { id:9,  programa_id:'VSM37-PMA-AB-S-4', descripcion:'Levantamiento de suelos en zonas afectadas' },
  { id:10, programa_id:'VSM37-PMA-AB-S-4', descripcion:'Aplicación de enmiendas y fertilizantes orgánicos' },
  { id:11, programa_id:'VSM37-PMA-AB-S-5', descripcion:'Clasificación y disposición de residuos peligrosos' },
  { id:12, programa_id:'VSM37-PMA-AB-S-5', descripcion:'Registro de manifiestos de residuos' },
  { id:13, programa_id:'VSM37-PMA-B-EP-1', descripcion:'Inventario de flora en área de intervención' },
  { id:14, programa_id:'VSM37-PMA-B-EP-1', descripcion:'Rescate y translocación de individuos amenazados' },
  { id:15, programa_id:'VSM37-PMA-B-EP-2', descripcion:'Ahuyentamiento de fauna antes del descapote' },
  { id:16, programa_id:'VSM37-PMA-B-EP-2', descripcion:'Rescate y reubicación de fauna silvestre' },
])

// ─── Avances registrados ─────────────────────────────────────────────────────
const avances = ref([
  { id:1,  accion_id:1,  fecha:'2024-04-10', pct:100, notas:'Plan aprobado por autoridad competente.' },
  { id:2,  accion_id:2,  fecha:'2024-05-01', pct:100, notas:'Contrato firmado con empresa gestora certificada.' },
  { id:3,  accion_id:3,  fecha:'2024-06-01', pct:65,  notas:'Registros del Q1 completos, Q2 en proceso.' },
  { id:4,  accion_id:4,  fecha:'2024-04-20', pct:80,  notas:'Barreras instaladas en los 3 taludes críticos.' },
  { id:5,  accion_id:5,  fecha:'2024-06-15', pct:50,  notas:'Monitoreo de abril y mayo ejecutados.' },
  { id:6,  accion_id:6,  fecha:'2024-05-30', pct:30,  notas:'Siembra iniciada en talud norte.' },
  { id:7,  accion_id:7,  fecha:'2024-05-10', pct:90,  notas:'Cunetas y disipadores instalados.' },
  { id:8,  accion_id:8,  fecha:'2024-06-20', pct:60,  notas:'Muestreos de Q1 completos.' },
  { id:9,  accion_id:9,  fecha:'2024-03-15', pct:100, notas:'Levantamiento completo entregado.' },
  { id:10, accion_id:10, fecha:'2024-05-20', pct:40,  notas:'Aplicación parcial en zona A.' },
  { id:11, accion_id:11, fecha:'2024-06-01', pct:75,  notas:'Residuos peligrosos al día.' },
  { id:12, accion_id:12, fecha:'2024-06-05', pct:80,  notas:'Manifiestos de Q1 registrados.' },
  { id:13, accion_id:13, fecha:'2024-04-01', pct:100, notas:'Inventario finalizado.' },
  { id:14, accion_id:14, fecha:'2024-05-15', pct:90,  notas:'45 individuos rescatados.' },
  { id:15, accion_id:15, fecha:'2024-04-05', pct:100, notas:'Ahuyentamiento completado previo al descapote.' },
  { id:16, accion_id:16, fecha:'2024-04-10', pct:85,  notas:'38 individuos reubicados en hábitat receptor.' },
])

// ─── % cumplimiento por programa (promedio del último avance de cada acción) ─
function pctPrograma(programaId) {
  const acs = acciones.value.filter(a => a.programa_id === programaId)
  if (!acs.length) return 0
  const pcts = acs.map(a => {
    const avs = avances.value.filter(v => v.accion_id === a.id)
    if (!avs.length) return 0
    return avs[avs.length - 1].pct
  })
  return Math.round(pcts.reduce((s, p) => s + p, 0) / pcts.length)
}

function pctColor(pct) {
  if (pct >= 80) return '#059669'
  if (pct >= 50) return '#D97706'
  return '#DC2626'
}
function pctBg(pct) {
  if (pct >= 80) return '#F0FDF4'
  if (pct >= 50) return '#FFFBEB'
  return '#FFF5F5'
}

// ─── Último avance % por acción ───────────────────────────────────────────────
function lastPct(accionId) {
  const avs = avances.value.filter(v => v.accion_id === accionId)
  return avs.length ? avs[avs.length - 1].pct : 0
}

// ─── Modal Nuevo / Editar programa ───────────────────────────────────────────
const showModal = ref(false)
const editingId = ref(null)
const form = reactive({ compromiso_id:'', id:'', tipo_plan:'', medio:'', nombre_programa:'', nombre_medida_ficha:'', tipo_medida:'' })

function openNew() {
  editingId.value = null
  Object.assign(form, { compromiso_id:'', id:'', tipo_plan:'', medio:'', nombre_programa:'', nombre_medida_ficha:'', tipo_medida:'' })
  showModal.value = true
}
function openEdit(prog) {
  editingId.value = prog.id
  Object.assign(form, { ...prog })
  showModal.value = true
}
function savePrograma() {
  if (editingId.value) {
    const idx = programs.value.findIndex(p => p.id === editingId.value)
    if (idx >= 0) Object.assign(programs.value[idx], { ...form })
  } else {
    programs.value.push({ ...form })
  }
  showModal.value = false
}

// ─── Modal Avances ────────────────────────────────────────────────────────────
const showAvancesModal = ref(false)
const avancesPrograma = ref(null)
const formAvance = reactive({ accion_id:'', fecha:'', pct:0, notas:'' })

function openAvances(prog) {
  avancesPrograma.value = prog
  Object.assign(formAvance, { accion_id:'', fecha:'', pct:0, notas:'' })
  showAvancesModal.value = true
}
function accionesDeProg(programaId) {
  return acciones.value.filter(a => a.programa_id === programaId)
}
function registrarAvance() {
  if (!formAvance.accion_id || !formAvance.fecha) return
  avances.value.push({
    id: avances.value.length + 1,
    accion_id: Number(formAvance.accion_id),
    fecha: formAvance.fecha,
    pct: Math.min(100, Math.max(0, Number(formAvance.pct))),
    notas: formAvance.notas,
  })
  Object.assign(formAvance, { accion_id:'', fecha:'', pct:0, notas:'' })
}

function rowEnter(e) { e.currentTarget.style.background = '#F2FAF5' }
function rowLeave(e) { e.currentTarget.style.background = 'transparent' }
</script>

<template>
  <div>
    <!-- Page header -->
    <div class="flex items-start justify-between mb-5">
      <div>
        <h1 class="font-extrabold" style="font-size:20px;color:#1C2925;line-height:1.2;">Programas Ambientales</h1>
        <p class="mt-1" style="font-size:13px;color:#6B7D76;">Fichas y programas del Plan de Manejo Ambiental (PMA)</p>
      </div>
      <div class="flex items-center gap-2">
        <button
          style="height:36px;padding:0 14px;font-size:13px;background:#F0F2F1;border:1px solid #E4E8E6;color:#4A5C55;border-radius:8px;cursor:pointer;"
          @mouseenter="(e) => e.currentTarget.style.background='#E4E8E6'"
          @mouseleave="(e) => e.currentTarget.style.background='#F0F2F1'"
        >Exportar Excel</button>
        <button
          style="height:36px;padding:0 14px;font-size:13px;background:#2D6A4F;border:none;color:white;border-radius:8px;cursor:pointer;font-weight:600;"
          @mouseenter="(e) => e.currentTarget.style.background='#1A3D2B'"
          @mouseleave="(e) => e.currentTarget.style.background='#2D6A4F'"
          @click="openNew"
        >
          <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right:6px;vertical-align:middle;"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
          Nuevo programa
        </button>
      </div>
    </div>

    <!-- Filters -->
    <div class="flex items-center gap-2 mb-4 flex-wrap">
      <div class="flex items-center gap-2 rounded-md flex-1" style="min-width:220px;max-width:300px;background:#F0F2F1;border:1px solid #E4E8E6;padding:0 11px;height:36px;">
        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#9FADA7" stroke-width="2" style="flex-shrink:0;"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
        <input v-model="search" type="text" placeholder="Buscar por código o nombre..." class="bg-transparent border-none outline-none flex-1" style="font-size:13px;color:#2E3D38;" />
      </div>
      <select v-model="filterMedio" class="rounded-md border outline-none" style="height:36px;padding:0 10px;font-size:13px;background:#F0F2F1;border-color:#E4E8E6;color:#4A5C55;cursor:pointer;">
        <option value="">Todos los medios</option>
        <option v-for="m in MEDIOS" :key="m" :value="m">{{ m }}</option>
      </select>
    </div>

    <!-- Table -->
    <div class="rounded-lg overflow-hidden" style="background:white;border:1px solid #E4E8E6;box-shadow:0 1px 3px rgba(0,0,0,.08);">
      <div style="overflow-x:auto;">
        <table style="width:100%;border-collapse:collapse;">
          <thead>
            <tr style="background:#1A3D2B;">
              <th style="padding:10px 16px;font-size:11.5px;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:white;text-align:left;white-space:nowrap;">No. (Código)</th>
              <th style="padding:10px 16px;font-size:11.5px;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:white;text-align:left;">Compromiso</th>
              <th style="padding:10px 16px;font-size:11.5px;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:white;text-align:left;white-space:nowrap;">Tipo Plan</th>
              <th style="padding:10px 16px;font-size:11.5px;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:white;text-align:left;">Medio</th>
              <th style="padding:10px 16px;font-size:11.5px;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:white;text-align:left;">Nombre Programa</th>
              <th style="padding:10px 16px;font-size:11.5px;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:white;text-align:left;">Nombre Medida / Ficha</th>
              <th style="padding:10px 16px;font-size:11.5px;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:white;text-align:left;white-space:nowrap;">Tipo Medida</th>
              <th style="padding:10px 16px;font-size:11.5px;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:white;text-align:center;white-space:nowrap;">% Cumplimiento</th>
              <th style="padding:10px 16px;font-size:11.5px;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:white;text-align:center;">Acciones</th>
            </tr>
          </thead>
          <tbody>
            <tr
              v-for="prog in programs"
              :key="prog.id"
              style="border-bottom:1px solid #F0F2F1;transition:background .18s;"
              @mouseenter="rowEnter"
              @mouseleave="rowLeave"
            >
              <td style="padding:12px 16px;font-family:monospace;font-size:11.5px;color:#246040;font-weight:600;white-space:nowrap;">{{ prog.id }}</td>
              <td style="padding:12px 16px;font-size:12px;color:#4A5C55;max-width:160px;">{{ COMPROMISOS_LISTA.find(c => c.id === prog.compromiso_id)?.label ?? '—' }}</td>
              <td style="padding:12px 16px;font-size:12.5px;color:#4A5C55;max-width:160px;">{{ prog.tipo_plan }}</td>
              <td style="padding:12px 16px;">
                <span class="inline-flex items-center rounded-full font-semibold" style="font-size:11.5px;padding:2px 9px;border:1px solid transparent;white-space:nowrap;"
                  :style="{ background:badgeMedio[prog.medio].background, color:badgeMedio[prog.medio].color, borderColor:badgeMedio[prog.medio].borderColor }">
                  {{ prog.medio }}
                </span>
              </td>
              <td style="padding:12px 16px;font-size:13px;font-weight:600;color:#1C2925;max-width:200px;">{{ prog.nombre_programa }}</td>
              <td style="padding:12px 16px;font-size:13px;color:#4A5C55;max-width:200px;">{{ prog.nombre_medida_ficha }}</td>
              <td style="padding:12px 16px;">
                <span class="inline-flex items-center rounded-full font-semibold" style="font-size:11.5px;padding:2px 9px;border:1px solid transparent;white-space:nowrap;"
                  :style="{ background:badgeMedida[prog.tipo_medida].background, color:badgeMedida[prog.tipo_medida].color, borderColor:badgeMedida[prog.tipo_medida].borderColor }">
                  {{ prog.tipo_medida.charAt(0).toUpperCase() + prog.tipo_medida.slice(1) }}
                </span>
              </td>

              <!-- % Cumplimiento -->
              <td style="padding:12px 20px;min-width:130px;">
                <div style="display:flex;align-items:center;gap:8px;">
                  <div style="flex:1;height:6px;background:#E4E8E6;border-radius:99px;overflow:hidden;">
                    <div :style="{ width: pctPrograma(prog.id) + '%', background: pctColor(pctPrograma(prog.id)), height:'100%', borderRadius:'99px', transition:'width .4s' }"></div>
                  </div>
                  <span style="font-size:12px;font-weight:700;min-width:32px;text-align:right;"
                    :style="{ color: pctColor(pctPrograma(prog.id)) }">
                    {{ pctPrograma(prog.id) }}%
                  </span>
                </div>
              </td>

              <!-- Botones -->
              <td style="padding:12px 16px;">
                <div style="display:flex;align-items:center;justify-content:center;gap:6px;">
                  <!-- Editar -->
                  <button
                    title="Editar programa"
                    style="width:30px;height:30px;border-radius:6px;border:1px solid #E4E8E6;background:transparent;cursor:pointer;display:flex;align-items:center;justify-content:center;color:#4A5C55;transition:all .12s;"
                    @mouseenter="(e) => { e.currentTarget.style.background='#EFF6FF'; e.currentTarget.style.color='#1E40AF'; e.currentTarget.style.borderColor='#BFDBFE'; }"
                    @mouseleave="(e) => { e.currentTarget.style.background='transparent'; e.currentTarget.style.color='#4A5C55'; e.currentTarget.style.borderColor='#E4E8E6'; }"
                    @click="openEdit(prog)"
                  >
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                      <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                      <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                    </svg>
                  </button>

                  <!-- Registrar avances -->
                  <button
                    title="Registrar avance de acciones"
                    style="width:30px;height:30px;border-radius:6px;border:1px solid #DCF0E4;background:#F2FAF5;cursor:pointer;display:flex;align-items:center;justify-content:center;color:#246040;transition:all .12s;"
                    @mouseenter="(e) => { e.currentTarget.style.background='#2D6A4F'; e.currentTarget.style.color='white'; e.currentTarget.style.borderColor='#2D6A4F'; }"
                    @mouseleave="(e) => { e.currentTarget.style.background='#F2FAF5'; e.currentTarget.style.color='#246040'; e.currentTarget.style.borderColor='#DCF0E4'; }"
                    @click="openAvances(prog)"
                  >
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                      <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/>
                    </svg>
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      <!-- Pagination -->
      <div class="flex items-center justify-between" style="padding:12px 16px;border-top:1px solid #F0F2F1;background:#F8F9F8;">
        <span style="font-size:12.5px;color:#6B7D76;">Mostrando {{ programs.length }} de {{ programs.length }} programas</span>
        <div class="flex items-center gap-1">
          <button style="width:30px;height:30px;background:white;border:1px solid #E4E8E6;color:#4A5C55;font-size:13px;border-radius:6px;cursor:pointer;">‹</button>
          <button style="width:30px;height:30px;background:#2D6A4F;border:1px solid #2D6A4F;color:white;font-size:13px;border-radius:6px;cursor:pointer;font-weight:600;">1</button>
          <button style="width:30px;height:30px;background:white;border:1px solid #E4E8E6;color:#4A5C55;font-size:13px;border-radius:6px;cursor:pointer;">›</button>
        </div>
      </div>
    </div>

    <!-- ─── Modal: Nuevo / Editar Programa ───────────────────────────────────── -->
    <AppModal
      :show="showModal"
      :title="editingId ? 'Editar Programa Ambiental' : 'Nuevo Programa Ambiental'"
      subtitle="Complete la información de la ficha del plan de manejo"
      @close="showModal = false"
    >
      <div class="form-section">Compromiso asociado</div>
      <div class="form-group">
        <label class="form-label">Compromiso<span class="req">*</span></label>
        <select v-model="form.compromiso_id" class="form-control">
          <option value="">Seleccionar compromiso...</option>
          <option v-for="c in COMPROMISOS_LISTA" :key="c.id" :value="c.id">{{ c.label }}</option>
        </select>
      </div>

      <div class="form-section">Identificación</div>
      <div class="form-row">
        <div class="form-group">
          <label class="form-label">Código (ID)<span class="req">*</span></label>
          <input v-model="form.id" type="text" class="form-control" placeholder="Ej: VSM37-PMA-AB-S-1" :disabled="!!editingId" />
        </div>
        <div class="form-group">
          <label class="form-label">Medio<span class="req">*</span></label>
          <select v-model="form.medio" class="form-control">
            <option value="">Seleccionar...</option>
            <option v-for="m in MEDIOS" :key="m" :value="m">{{ m }}</option>
          </select>
        </div>
      </div>
      <div class="form-group">
        <label class="form-label">Tipo de plan<span class="req">*</span></label>
        <select v-model="form.tipo_plan" class="form-control">
          <option value="">Seleccionar...</option>
          <option v-for="tp in TIPOS_PLAN" :key="tp" :value="tp">{{ tp }}</option>
        </select>
      </div>

      <div class="form-section">Contenido</div>
      <div class="form-group">
        <label class="form-label">Nombre del programa<span class="req">*</span></label>
        <input v-model="form.nombre_programa" type="text" class="form-control" placeholder="Ej: Manejo y control de procesos erosivos" />
      </div>
      <div class="form-group">
        <label class="form-label">Nombre medida / ficha<span class="req">*</span></label>
        <input v-model="form.nombre_medida_ficha" type="text" class="form-control" placeholder="Ej: Control de erosión en taludes" />
      </div>
      <div class="form-group">
        <label class="form-label">Tipo de medida<span class="req">*</span></label>
        <select v-model="form.tipo_medida" class="form-control">
          <option value="">Seleccionar...</option>
          <option v-for="tm in TIPOS_MEDIDA" :key="tm" :value="tm">{{ tm.charAt(0).toUpperCase() + tm.slice(1) }}</option>
        </select>
      </div>

      <template #footer>
        <button style="height:36px;padding:0 14px;font-size:13px;background:#F0F2F1;border:1px solid #E4E8E6;color:#4A5C55;border-radius:6px;cursor:pointer;" @click="showModal = false">Cancelar</button>
        <button style="height:36px;padding:0 14px;font-size:13px;background:#2D6A4F;border:none;color:white;border-radius:6px;cursor:pointer;font-weight:600;"
          @mouseenter="(e) => e.currentTarget.style.background='#1A3D2B'"
          @mouseleave="(e) => e.currentTarget.style.background='#2D6A4F'"
          @click="savePrograma">
          {{ editingId ? 'Guardar cambios' : 'Guardar programa' }}
        </button>
      </template>
    </AppModal>

    <!-- ─── Modal: Avances de Acciones ──────────────────────────────────────── -->
    <AppModal
      v-if="avancesPrograma"
      :show="showAvancesModal"
      :title="`Avances — ${avancesPrograma.nombre_programa}`"
      subtitle="Registre el progreso de cada acción del programa para actualizar el % de cumplimiento"
      @close="showAvancesModal = false"
    >
      <!-- % general del programa -->
      <div style="background:#F2FAF5;border:1px solid #DCF0E4;border-radius:8px;padding:12px 16px;margin-bottom:16px;display:flex;align-items:center;gap:12px;">
        <div style="flex:1;">
          <p style="font-size:12px;color:#4A5C55;margin-bottom:6px;font-weight:600;">% Cumplimiento general del programa</p>
          <div style="height:8px;background:#DCF0E4;border-radius:99px;overflow:hidden;">
            <div :style="{ width: pctPrograma(avancesPrograma.id) + '%', background: pctColor(pctPrograma(avancesPrograma.id)), height:'100%', borderRadius:'99px', transition:'width .5s' }"></div>
          </div>
        </div>
        <span style="font-size:22px;font-weight:800;" :style="{ color: pctColor(pctPrograma(avancesPrograma.id)) }">
          {{ pctPrograma(avancesPrograma.id) }}%
        </span>
      </div>

      <!-- Lista de acciones con su % actual -->
      <div class="form-section">Estado de acciones</div>
      <div style="display:flex;flex-direction:column;gap:8px;margin-bottom:16px;">
        <div
          v-for="ac in accionesDeProg(avancesPrograma.id)"
          :key="ac.id"
          style="background:#F8F9F8;border:1px solid #E4E8E6;border-radius:6px;padding:10px 12px;"
        >
          <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:5px;">
            <span style="font-size:12.5px;color:#1C2925;font-weight:500;">{{ ac.descripcion }}</span>
            <span style="font-size:12px;font-weight:700;margin-left:12px;flex-shrink:0;" :style="{ color: pctColor(lastPct(ac.id)) }">{{ lastPct(ac.id) }}%</span>
          </div>
          <div style="height:5px;background:#E4E8E6;border-radius:99px;overflow:hidden;">
            <div :style="{ width: lastPct(ac.id) + '%', background: pctColor(lastPct(ac.id)), height:'100%', borderRadius:'99px', transition:'width .4s' }"></div>
          </div>
        </div>
        <p v-if="!accionesDeProg(avancesPrograma.id).length" style="font-size:13px;color:#9FADA7;text-align:center;padding:12px 0;">No hay acciones registradas para este programa.</p>
      </div>

      <!-- Formulario nuevo avance -->
      <div class="form-section">Registrar nuevo avance</div>
      <div class="form-group">
        <label class="form-label">Acción<span class="req">*</span></label>
        <select v-model="formAvance.accion_id" class="form-control">
          <option value="">Seleccionar acción...</option>
          <option v-for="ac in accionesDeProg(avancesPrograma.id)" :key="ac.id" :value="ac.id">{{ ac.descripcion }}</option>
        </select>
      </div>
      <!-- Avance actual de la acción seleccionada -->
      <div v-if="formAvance.accion_id" style="display:flex;align-items:center;gap:10px;background:#F8F9F8;border:1px solid #E4E8E6;border-radius:6px;padding:10px 14px;margin-bottom:14px;">
        <span style="font-size:12px;color:#4A5C55;font-weight:600;flex:1;">Avance actual de esta acción:</span>
        <div style="flex:1;height:6px;background:#E4E8E6;border-radius:99px;overflow:hidden;">
          <div :style="{ width: lastPct(Number(formAvance.accion_id)) + '%', background: pctColor(lastPct(Number(formAvance.accion_id))), height:'100%', borderRadius:'99px' }"></div>
        </div>
        <span style="font-size:13px;font-weight:800;min-width:34px;text-align:right;" :style="{ color: pctColor(lastPct(Number(formAvance.accion_id))) }">{{ lastPct(Number(formAvance.accion_id)) }}%</span>
      </div>
      <div class="form-row">
        <div class="form-group">
          <label class="form-label">Fecha del avance<span class="req">*</span></label>
          <input v-model="formAvance.fecha" type="date" class="form-control" />
        </div>
        <div class="form-group">
          <label class="form-label">Nuevo % acumulado<span class="req">*</span></label>
          <div style="position:relative;">
            <input v-model.number="formAvance.pct" type="number" min="0" max="100" class="form-control" placeholder="0" style="padding-right:28px;" @input="formAvance.pct = Math.min(100, Math.max(0, formAvance.pct))" />
            <span style="position:absolute;right:10px;top:50%;transform:translateY(-50%);font-size:13px;color:#6B7D76;pointer-events:none;">%</span>
          </div>
        </div>
      </div>
      <div class="form-group">
        <label class="form-label">Observaciones / Notas</label>
        <textarea v-model="formAvance.notas" class="form-control" rows="2" placeholder="Describa brevemente lo ejecutado en este avance..."></textarea>
      </div>

      <template #footer>
        <button style="height:36px;padding:0 14px;font-size:13px;background:#F0F2F1;border:1px solid #E4E8E6;color:#4A5C55;border-radius:6px;cursor:pointer;" @click="showAvancesModal = false">Cerrar</button>
        <button
          style="height:36px;padding:0 14px;font-size:13px;background:#2D6A4F;border:none;color:white;border-radius:6px;cursor:pointer;font-weight:600;"
          @mouseenter="(e) => e.currentTarget.style.background='#1A3D2B'"
          @mouseleave="(e) => e.currentTarget.style.background='#2D6A4F'"
          @click="registrarAvance"
        >Registrar avance</button>
      </template>
    </AppModal>
  </div>
</template>
