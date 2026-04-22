<script setup>
import { ref, reactive } from 'vue'
import AppModal from '@/components/ui/AppModal.vue'
import StepWizard from '@/components/ui/StepWizard.vue'

const companies = [
  { name: 'Constructora Bogotá SAS',  nit: '900.234.567-1', city: 'Bogotá D.C.',  ciiu: '4111', programs: 8,  users: 14, status: 'success', statusLabel: 'Activa' },
  { name: 'Minería del Caribe SAS',   nit: '800.123.456-2', city: 'Barranquilla', ciiu: '0510', programs: 3,  users: 7,  status: 'success', statusLabel: 'Activa' },
  { name: 'Agroindustria Valle SAS',  nit: '890.345.678-3', city: 'Cali',         ciiu: '0111', programs: 5,  users: 10, status: 'success', statusLabel: 'Activa' },
  { name: 'Petrolífera Andina SAS',   nit: '860.456.789-4', city: 'Yopal',        ciiu: '0610', programs: 6,  users: 12, status: 'warn',    statusLabel: 'En revisión' },
  { name: 'Constructora del Norte SA',nit: '830.567.890-5', city: 'Medellín',     ciiu: '4111', programs: 4,  users: 8,  status: 'gray',    statusLabel: 'Inactiva' },
]

const badgeStyles = {
  success: 'background:#F0FDF4;color:#065F46;border-color:#BBF7D0;',
  warn:    'background:#FFFBEB;color:#92400E;border-color:#FDE68A;',
  gray:    'background:#F0F2F1;color:#4A5C55;border-color:#E4E8E6;',
}

// Modal state
const showModal = ref(false)
const step = ref(1)
const logoPreview = ref(null)
const logoInput = ref(null)

const form = reactive({
  // Step 1
  razonSocial: '',
  nit: '',
  ciiu: '',
  actividadEconomica: '',
  departamento: '',
  municipio: '',
  // Step 2
  adminNombres: '',
  adminApellidos: '',
  adminEmail: '',
  adminTelefono: '',
})

function openModal() {
  step.value = 1
  logoPreview.value = null
  showModal.value = true
}

function handleLogoClick() {
  logoInput.value?.click()
}

function handleLogoChange(e) {
  const file = e.target.files[0]
  if (!file) return
  const reader = new FileReader()
  reader.onload = (ev) => { logoPreview.value = ev.target.result }
  reader.readAsDataURL(file)
}
</script>

<template>
  <div>
    <div class="flex items-start justify-between mb-5">
      <div>
        <h1 class="font-extrabold" style="font-size:20px;color:#1C2925;line-height:1.2;">Empresas</h1>
        <p class="mt-1" style="font-size:13px;color:#6B7D76;">Administración de empresas registradas en la plataforma Aquaviva</p>
      </div>
      <button
        class="flex items-center gap-2 rounded-md font-semibold text-white"
        style="height:36px;padding:0 14px;font-size:13px;background:#2D7A50;border:none;cursor:pointer;"
        @mouseenter="(e) => e.currentTarget.style.background='#246040'"
        @mouseleave="(e) => e.currentTarget.style.background='#2D7A50'"
        @click="openModal"
      >
        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        Nueva empresa
      </button>
    </div>

    <div class="rounded-lg overflow-hidden" style="background:white;border:1px solid #E4E8E6;box-shadow:0 1px 3px rgba(0,0,0,.08);">
      <div style="overflow-x:auto;">
        <table style="width:100%;border-collapse:collapse;">
          <thead>
            <tr style="background:#F8F9F8;border-bottom:1px solid #E4E8E6;">
              <th style="padding:10px 16px;font-size:11.5px;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:#6B7D76;text-align:left;">Empresa</th>
              <th style="padding:10px 16px;font-size:11.5px;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:#6B7D76;text-align:left;">NIT</th>
              <th style="padding:10px 16px;font-size:11.5px;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:#6B7D76;text-align:left;">Municipio</th>
              <th style="padding:10px 16px;font-size:11.5px;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:#6B7D76;text-align:left;">CIIU</th>
              <th style="padding:10px 16px;font-size:11.5px;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:#6B7D76;text-align:center;">Programas</th>
              <th style="padding:10px 16px;font-size:11.5px;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:#6B7D76;text-align:center;">Usuarios</th>
              <th style="padding:10px 16px;font-size:11.5px;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:#6B7D76;text-align:left;">Estado</th>
              <th style="padding:10px 16px;"></th>
            </tr>
          </thead>
          <tbody>
            <tr
              v-for="co in companies"
              :key="co.nit"
              style="border-bottom:1px solid #F0F2F1;transition:background .18s;"
              @mouseenter="(e) => e.currentTarget.style.background='#F2FAF5'"
              @mouseleave="(e) => e.currentTarget.style.background='transparent'"
            >
              <td style="padding:12px 16px;font-size:13px;font-weight:600;color:#1C2925;">{{ co.name }}</td>
              <td style="padding:12px 16px;font-size:13px;color:#6B7D76;font-family:monospace;">{{ co.nit }}</td>
              <td style="padding:12px 16px;font-size:13px;color:#4A5C55;">{{ co.city }}</td>
              <td style="padding:12px 16px;font-size:13px;color:#4A5C55;">{{ co.ciiu }}</td>
              <td style="padding:12px 16px;font-size:13px;color:#4A5C55;text-align:center;">{{ co.programs }}</td>
              <td style="padding:12px 16px;font-size:13px;color:#4A5C55;text-align:center;">{{ co.users }}</td>
              <td style="padding:12px 16px;">
                <span class="inline-flex items-center gap-1 rounded-full font-semibold" style="font-size:11.5px;padding:2px 9px;border:1px solid transparent;" :style="badgeStyles[co.status]">
                  <span style="width:5px;height:5px;border-radius:50%;background:currentColor;flex-shrink:0;"></span>
                  {{ co.statusLabel }}
                </span>
              </td>
              <td style="padding:12px 16px;">
                <button
                  class="rounded-md font-semibold"
                  style="height:28px;padding:0 10px;font-size:12px;background:#F0F2F1;border:1px solid #E4E8E6;color:#4A5C55;cursor:pointer;"
                  @mouseenter="(e) => e.currentTarget.style.background='#E4E8E6'"
                  @mouseleave="(e) => e.currentTarget.style.background='#F0F2F1'"
                >Gestionar →</button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      <div class="flex items-center justify-between" style="padding:12px 16px;border-top:1px solid #F0F2F1;background:#F8F9F8;">
        <span style="font-size:12.5px;color:#6B7D76;">5 empresas registradas</span>
        <div class="flex items-center gap-1">
          <button class="flex items-center justify-center rounded-md font-bold" style="width:30px;height:30px;background:#2D7A50;border:1px solid #2D7A50;color:white;font-size:13px;cursor:pointer;">1</button>
        </div>
      </div>
    </div>

    <!-- ─── Nueva Empresa Modal (3 steps) ─── -->
    <AppModal
      :show="showModal"
      title="Nueva Empresa"
      subtitle="Complete los pasos para registrar la empresa en la plataforma"
      @close="showModal = false"
    >
      <StepWizard :steps="['Datos empresa', 'Usuario admin', 'Confirmación']" :current="step" />

      <!-- Step 1: Company data -->
      <div v-if="step === 1">
        <div class="form-section">Logo de la empresa</div>
        <div class="form-group" style="align-items:flex-start;">
          <!-- Hidden file input -->
          <input ref="logoInput" type="file" accept="image/*" style="display:none;" @change="handleLogoChange" />
          <!-- Upload area -->
          <div
            style="width:80px;height:80px;border-radius:10px;border:2px dashed #D1D9D5;background:#F7F9F8;display:flex;align-items:center;justify-content:center;cursor:pointer;overflow:hidden;transition:border-color .18s;"
            @click="handleLogoClick"
            @mouseenter="(e) => e.currentTarget.style.borderColor='#3A9A64'"
            @mouseleave="(e) => e.currentTarget.style.borderColor='#D1D9D5'"
          >
            <img v-if="logoPreview" :src="logoPreview" style="width:100%;height:100%;object-fit:cover;" alt="Logo preview" />
            <div v-else class="flex flex-col items-center gap-1">
              <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#9FADA7" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
              <span style="font-size:10px;color:#9FADA7;font-weight:600;text-align:center;line-height:1.2;">Logo</span>
            </div>
          </div>
          <span class="form-hint" style="margin-top:6px;">Haga clic para subir. PNG o JPG, máx. 2MB.</span>
        </div>

        <div class="form-section">Datos de la empresa</div>
        <div class="form-row">
          <div class="form-group">
            <label class="form-label">Razón social<span class="req">*</span></label>
            <input v-model="form.razonSocial" type="text" class="form-control" placeholder="Ej: Constructora Bogotá SAS" />
          </div>
          <div class="form-group">
            <label class="form-label">NIT<span class="req">*</span></label>
            <input v-model="form.nit" type="text" class="form-control" placeholder="900.123.456-7" />
          </div>
        </div>
        <div class="form-row">
          <div class="form-group">
            <label class="form-label">Código CIIU<span class="req">*</span></label>
            <input v-model="form.ciiu" type="text" class="form-control" placeholder="Ej: 4111" />
          </div>
          <div class="form-group">
            <label class="form-label">Descripción actividad económica</label>
            <input v-model="form.actividadEconomica" type="text" class="form-control" placeholder="Ej: Construcción de edificios residenciales" />
          </div>
        </div>
        <div class="form-row">
          <div class="form-group">
            <label class="form-label">Departamento</label>
            <select v-model="form.departamento" class="form-control">
              <option value="">Seleccionar...</option>
              <option>Cundinamarca</option>
              <option>Antioquia</option>
              <option>Valle del Cauca</option>
              <option>Atlántico</option>
              <option>Santander</option>
            </select>
          </div>
          <div class="form-group">
            <label class="form-label">Municipio</label>
            <select v-model="form.municipio" class="form-control">
              <option value="">Seleccionar...</option>
              <option>Bogotá D.C.</option>
              <option>Soacha</option>
              <option>Facatativá</option>
              <option>Medellín</option>
              <option>Cali</option>
              <option>Barranquilla</option>
            </select>
          </div>
        </div>
      </div>

      <!-- Step 2: Admin user -->
      <div v-if="step === 2">
        <div class="form-section">Datos del administrador de la empresa</div>
        <div class="info-banner info">
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="flex-shrink:0;margin-top:1px;"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
          Se creará automáticamente un usuario administrador y se le enviará por correo electrónico las credenciales de acceso temporales.
        </div>
        <div class="form-row">
          <div class="form-group">
            <label class="form-label">Nombres<span class="req">*</span></label>
            <input v-model="form.adminNombres" type="text" class="form-control" placeholder="Ej: Carlos Alberto" />
          </div>
          <div class="form-group">
            <label class="form-label">Apellidos<span class="req">*</span></label>
            <input v-model="form.adminApellidos" type="text" class="form-control" placeholder="Ej: Mendoza Ruiz" />
          </div>
        </div>
        <div class="form-group">
          <label class="form-label">Correo electrónico institucional<span class="req">*</span></label>
          <input v-model="form.adminEmail" type="email" class="form-control" placeholder="admin@empresa.com.co" />
        </div>
        <div class="form-group">
          <label class="form-label">Teléfono de contacto</label>
          <input v-model="form.adminTelefono" type="text" class="form-control" placeholder="Ej: 301 456 7890" />
        </div>
      </div>

      <!-- Step 3: Confirmation -->
      <div v-if="step === 3">
        <div class="info-banner success">
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="flex-shrink:0;margin-top:1px;"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
          Toda la información está lista. Revise y confirme para crear la empresa en el sistema.
        </div>
        <!-- Summary card -->
        <div style="background:#F7F9F8;border:1px solid #E4E8E6;border-radius:8px;padding:16px 18px;">
          <div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:1px;color:#9FADA7;margin-bottom:12px;">Resumen</div>
          <div style="display:flex;flex-direction:column;gap:0;">
            <div style="display:flex;justify-content:space-between;padding:8px 0;border-bottom:1px solid #F0F2F1;">
              <span style="font-size:12.5px;color:#6B7D76;">Razón social</span>
              <span style="font-size:12.5px;color:#1C2925;font-weight:600;">{{ form.razonSocial || '—' }}</span>
            </div>
            <div style="display:flex;justify-content:space-between;padding:8px 0;border-bottom:1px solid #F0F2F1;">
              <span style="font-size:12.5px;color:#6B7D76;">NIT</span>
              <span style="font-size:12.5px;color:#1C2925;font-weight:600;font-family:monospace;">{{ form.nit || '—' }}</span>
            </div>
            <div style="display:flex;justify-content:space-between;padding:8px 0;border-bottom:1px solid #F0F2F1;">
              <span style="font-size:12.5px;color:#6B7D76;">Municipio</span>
              <span style="font-size:12.5px;color:#1C2925;font-weight:600;">{{ form.municipio || '—' }}</span>
            </div>
            <div style="display:flex;justify-content:space-between;padding:8px 0;border-bottom:1px solid #F0F2F1;">
              <span style="font-size:12.5px;color:#6B7D76;">Administrador</span>
              <span style="font-size:12.5px;color:#1C2925;font-weight:600;">{{ (form.adminNombres + ' ' + form.adminApellidos).trim() || '—' }}</span>
            </div>
            <div style="display:flex;justify-content:space-between;padding:8px 0;border-bottom:1px solid #F0F2F1;">
              <span style="font-size:12.5px;color:#6B7D76;">Correo admin</span>
              <span style="font-size:12.5px;color:#1C2925;font-weight:600;">{{ form.adminEmail || '—' }}</span>
            </div>
            <div style="display:flex;justify-content:space-between;padding:8px 0;">
              <span style="font-size:12.5px;color:#6B7D76;">Contraseña temporal</span>
              <span style="font-size:12.5px;color:#2D7A50;font-weight:700;">Se generará al guardar</span>
            </div>
          </div>
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
          @click="step < 3 ? step++ : showModal = false"
        >{{ step < 3 ? 'Siguiente →' : 'Crear empresa' }}</button>
      </template>
    </AppModal>
  </div>
</template>
