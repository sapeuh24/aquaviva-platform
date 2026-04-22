<script setup>
import { ref, reactive } from 'vue'
import AppModal from '@/components/ui/AppModal.vue'

const currentPage = ref(1)

const showModal = ref(false)
const form = reactive({
  nombre: '',
  codigo: '',
  municipio: '',
  departamento: '',
  fase: '',
  descripcion: '',
  fechaInicio: '',
})

const badgeStyles = {
  success: { background: '#F0FDF4', color: '#065F46', borderColor: '#BBF7D0' },
  info:    { background: '#EFF6FF', color: '#1E40AF', borderColor: '#BFDBFE' },
  green:   { background: '#F2FAF5', color: '#246040', borderColor: '#DCF0E4' },
  gray:    { background: '#F0F2F1', color: '#4A5C55', borderColor: '#E4E8E6' },
}

const projects = [
  { code: 'PRY-001', name: 'Ampliación Planta Norte — Fase 2',        obligations: 4, municipality: 'Bogotá D.C.', phase: 'Construcción', phaseType: 'info',  status: 'Activo',     statusType: 'success' },
  { code: 'PRY-002', name: 'Operación Planta de Tratamiento PTAR-B',   obligations: 6, municipality: 'Bogotá D.C.', phase: 'Operación',    phaseType: 'green', status: 'Activo',     statusType: 'success' },
  { code: 'PRY-003', name: 'Cierre y Restauración Sector Sur',         obligations: 2, municipality: 'Soacha',      phase: 'Cierre',       phaseType: 'gray',  status: 'Finalizado', statusType: 'gray'    },
]

function btnPrimaryEnter(e) { e.currentTarget.style.background = '#246040' }
function btnPrimaryLeave(e) { e.currentTarget.style.background = '#2D7A50' }
function rowEnter(e)        { e.currentTarget.style.background = '#F2FAF5' }
function rowLeave(e)        { e.currentTarget.style.background = 'transparent' }
function ghostEnter(e)      { e.currentTarget.style.background = '#F2FAF5' }
function ghostLeave(e)      { e.currentTarget.style.background = 'transparent' }
</script>

<template>
  <div>
    <!-- Page header -->
    <div class="flex items-start justify-between mb-5">
      <div>
        <h1 class="font-extrabold" style="font-size:20px;color:#1C2925;line-height:1.2;">Proyectos</h1>
        <p class="mt-1" style="font-size:13px;color:#6B7D76;">Proyectos empresariales con obligaciones ambientales vinculadas</p>
      </div>
      <button
        style="height:36px;padding:0 14px;font-size:13px;background:#2D7A50;border:none;color:white;border-radius:8px;cursor:pointer;font-weight:600;"
        @mouseenter="btnPrimaryEnter"
        @mouseleave="btnPrimaryLeave"
        @click="showModal = true"
      >+ Nuevo proyecto</button>
    </div>

    <!-- Table -->
    <div class="rounded-lg overflow-hidden" style="background:white;border:1px solid #E4E8E6;box-shadow:0 1px 3px rgba(0,0,0,.08);">
      <table style="width:100%;border-collapse:collapse;">
        <thead style="background:#F7F9F8;">
          <tr>
            <th style="padding:10px 16px;font-size:11.5px;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:#6B7D76;text-align:left;">Código</th>
            <th style="padding:10px 16px;font-size:11.5px;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:#6B7D76;text-align:left;">Nombre del proyecto</th>
            <th style="padding:10px 16px;font-size:11.5px;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:#6B7D76;text-align:center;">Obligaciones</th>
            <th style="padding:10px 16px;font-size:11.5px;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:#6B7D76;text-align:left;">Municipio</th>
            <th style="padding:10px 16px;font-size:11.5px;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:#6B7D76;text-align:left;">Fase</th>
            <th style="padding:10px 16px;font-size:11.5px;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:#6B7D76;text-align:left;">Estado</th>
            <th style="padding:10px 16px;font-size:11.5px;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:#6B7D76;text-align:left;"></th>
          </tr>
        </thead>
        <tbody>
          <tr
            v-for="proj in projects"
            :key="proj.code"
            style="cursor:pointer;transition:background .12s;"
            @mouseenter="rowEnter"
            @mouseleave="rowLeave"
          >
            <td style="padding:12px 16px;font-size:13px;border-bottom:1px solid #F0F2F1;">
              <span style="background:#F2FAF5;color:#246040;border:1px solid #DCF0E4;border-radius:4px;padding:2px 7px;font-size:11.5px;font-weight:600;font-family:monospace;">{{ proj.code }}</span>
            </td>
            <td style="padding:12px 16px;font-size:13px;color:#1C2925;font-weight:500;border-bottom:1px solid #F0F2F1;">{{ proj.name }}</td>
            <td style="padding:12px 16px;font-size:13px;color:#4A5C55;border-bottom:1px solid #F0F2F1;text-align:center;font-weight:600;">{{ proj.obligations }}</td>
            <td style="padding:12px 16px;font-size:13px;color:#4A5C55;border-bottom:1px solid #F0F2F1;">{{ proj.municipality }}</td>
            <td style="padding:12px 16px;border-bottom:1px solid #F0F2F1;">
              <span class="inline-flex items-center gap-1 rounded-full font-semibold" style="font-size:11.5px;padding:2px 9px;border:1px solid transparent;"
                :style="{ background: badgeStyles[proj.phaseType].background, color: badgeStyles[proj.phaseType].color, borderColor: badgeStyles[proj.phaseType].borderColor }">
                <span style="width:5px;height:5px;border-radius:50%;background:currentColor;flex-shrink:0;"></span>
                {{ proj.phase }}
              </span>
            </td>
            <td style="padding:12px 16px;border-bottom:1px solid #F0F2F1;">
              <span class="inline-flex items-center gap-1 rounded-full font-semibold" style="font-size:11.5px;padding:2px 9px;border:1px solid transparent;"
                :style="{ background: badgeStyles[proj.statusType].background, color: badgeStyles[proj.statusType].color, borderColor: badgeStyles[proj.statusType].borderColor }">
                <span style="width:5px;height:5px;border-radius:50%;background:currentColor;flex-shrink:0;"></span>
                {{ proj.status }}
              </span>
            </td>
            <td style="padding:12px 16px;border-bottom:1px solid #F0F2F1;">
              <button style="height:30px;padding:0 12px;font-size:12px;background:transparent;border:1px solid #E4E8E6;color:#4A5C55;border-radius:6px;cursor:pointer;"
                @mouseenter="ghostEnter" @mouseleave="ghostLeave">Ver →</button>
            </td>
          </tr>
        </tbody>
      </table>

      <!-- Pagination -->
      <div class="flex items-center justify-between" style="padding:12px 16px;border-top:1px solid #F0F2F1;">
        <span style="font-size:13px;color:#6B7D76;">3 de 3 proyectos</span>
        <div class="flex items-center gap-1">
          <button style="height:30px;min-width:30px;padding:0 8px;font-size:12px;background:#F0F2F1;border:1px solid #E4E8E6;color:#4A5C55;border-radius:6px;cursor:pointer;">&#8249;</button>
          <button style="height:30px;min-width:30px;padding:0 8px;font-size:12px;border:1px solid #2D7A50;border-radius:6px;cursor:pointer;font-weight:600;background:#2D7A50;color:white;">1</button>
          <button style="height:30px;min-width:30px;padding:0 8px;font-size:12px;background:#F0F2F1;border:1px solid #E4E8E6;color:#4A5C55;border-radius:6px;cursor:pointer;">&#8250;</button>
        </div>
      </div>
    </div>

    <!-- ─── Nuevo Proyecto Modal ─── -->
    <AppModal
      :show="showModal"
      title="Nuevo Proyecto"
      subtitle="Cree un proyecto ambiental y vincule sus obligaciones"
      @close="showModal = false"
    >
      <div class="form-section">Información del proyecto</div>
      <div class="form-row">
        <div class="form-group">
          <label class="form-label">Nombre del proyecto<span class="req">*</span></label>
          <input v-model="form.nombre" type="text" class="form-control" placeholder="Ej: Ampliación Planta Norte — Fase 2" />
        </div>
        <div class="form-group">
          <label class="form-label">Código interno</label>
          <input v-model="form.codigo" type="text" class="form-control" placeholder="Ej: PRY-004" />
        </div>
      </div>
      <div class="form-row">
        <div class="form-group">
          <label class="form-label">Departamento<span class="req">*</span></label>
          <select v-model="form.departamento" class="form-control">
            <option value="">Seleccionar...</option>
            <option>Cundinamarca</option>
            <option>Antioquia</option>
            <option>Valle del Cauca</option>
            <option>Boyacá</option>
            <option>Santander</option>
            <option>Nariño</option>
          </select>
        </div>
        <div class="form-group">
          <label class="form-label">Municipio<span class="req">*</span></label>
          <input v-model="form.municipio" type="text" class="form-control" placeholder="Ej: Bogotá D.C." />
        </div>
      </div>
      <div class="form-row">
        <div class="form-group">
          <label class="form-label">Fase del proyecto<span class="req">*</span></label>
          <select v-model="form.fase" class="form-control">
            <option value="">Seleccionar...</option>
            <option>Pre-construcción</option>
            <option>Construcción</option>
            <option>Operación</option>
            <option>Cierre</option>
            <option>Post-cierre</option>
          </select>
        </div>
        <div class="form-group">
          <label class="form-label">Fecha de inicio</label>
          <input v-model="form.fechaInicio" type="date" class="form-control" />
        </div>
      </div>
      <div class="form-group">
        <label class="form-label">Descripción del proyecto</label>
        <textarea v-model="form.descripcion" class="form-control" rows="2" placeholder="Describa brevemente el alcance y los objetivos ambientales del proyecto..."></textarea>
      </div>
      <div class="info-banner info" style="margin-bottom:0;">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="flex-shrink:0;margin-top:1px;"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
        Una vez creado el proyecto, podrá vincular obligaciones ambientales desde la sección de Obligaciones.
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
        >Crear proyecto</button>
      </template>
    </AppModal>
  </div>
</template>
