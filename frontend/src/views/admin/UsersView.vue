<script setup>
import { ref, reactive } from 'vue'
import AppModal from '@/components/ui/AppModal.vue'

const search = ref('')
const filterRol = ref('')
const filterEstado = ref('')

const users = [
  { name: 'Juan Martínez López', email: 'j.martinez@constructora.com.co', doc: 'CC 79.456.123', rol: 'coordinator', rolLabel: 'Coordinador', rolType: 'info', lastAccess: 'Hoy 09:14', status: 'success', statusLabel: 'Activo', initials: 'JM', avatarBg: '#2D7A50' },
  { name: 'Laura Torres Guzmán', email: 'l.torres@constructora.com.co', doc: 'CC 53.789.456', rol: 'analyst', rolLabel: 'Analista', rolType: 'green', lastAccess: 'Ayer 16:30', status: 'success', statusLabel: 'Activo', initials: 'LT', avatarBg: '#246040' },
  { name: 'Mario Ramírez', email: 'm.ramirez@constructora.com.co', doc: 'CC 80.123.789', rol: 'analyst', rolLabel: 'Analista', rolType: 'green', lastAccess: 'Hace 3 días', status: 'success', statusLabel: 'Activo', initials: 'MR', avatarBg: '#6B7D76' },
  { name: 'Carlos López', email: 'c.lopez@constructora.com.co', doc: 'CC 71.234.567', rol: 'viewer', rolLabel: 'Visualizador', rolType: 'gray', lastAccess: 'Hace 1 semana', status: 'gray', statusLabel: 'Inactivo', initials: 'CL', avatarBg: '#D97706' },
]

const badgeStyles = {
  success: 'background:#F0FDF4;color:#065F46;border-color:#BBF7D0;',
  warn:    'background:#FFFBEB;color:#92400E;border-color:#FDE68A;',
  danger:  'background:#FFF5F5;color:#991B1B;border-color:#FECACA;',
  info:    'background:#EFF6FF;color:#1E40AF;border-color:#BFDBFE;',
  green:   'background:#F2FAF5;color:#246040;border-color:#DCF0E4;',
  gray:    'background:#F0F2F1;color:#4A5C55;border-color:#E4E8E6;',
}

// Modal state
const showModal = ref(false)
const form = reactive({
  nombres: '',
  apellidos: '',
  tipoDoc: 'Cédula de Ciudadanía',
  numDoc: '',
  email: '',
  telefono: '',
  rol: '',
  cargo: '',
})
</script>

<template>
  <div>
    <!-- Page header -->
    <div class="flex items-start justify-between mb-4">
      <div>
        <h1 class="font-extrabold" style="font-size:20px;color:#1C2925;line-height:1.2;">Usuarios</h1>
        <p class="mt-1" style="font-size:13px;color:#6B7D76;">Gestión de usuarios y roles dentro de la empresa</p>
      </div>
      <button
        class="flex items-center gap-2 rounded-md font-semibold text-white"
        style="height:36px;padding:0 14px;font-size:13px;background:#2D7A50;border:none;cursor:pointer;"
        @mouseenter="(e) => e.currentTarget.style.background='#246040'"
        @mouseleave="(e) => e.currentTarget.style.background='#2D7A50'"
        @click="showModal = true"
      >
        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        Nuevo usuario
      </button>
    </div>

    <!-- Info banner -->
    <div class="flex items-start gap-2 rounded-lg mb-4" style="padding:12px 16px;background:#EFF6FF;border:1px solid #BFDBFE;color:#1E40AF;">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="flex-shrink:0;margin-top:1px;"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
      <span style="font-size:13px;">Los usuarios son asignados a una empresa específica. Solo los administradores pueden crear y gestionar usuarios de su empresa.</span>
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
        <input v-model="search" type="text" placeholder="Buscar por nombre o correo..." class="bg-transparent border-none outline-none flex-1" style="font-size:13px;color:#2E3D38;" />
      </div>
      <select v-model="filterRol" class="rounded-md border outline-none" style="height:36px;padding:0 10px;font-size:13px;background:#F0F2F1;border-color:#E4E8E6;color:#4A5C55;cursor:pointer;">
        <option value="">Todos los roles</option>
        <option>Administrador</option><option>Coordinador</option><option>Analista</option><option>Visualizador</option>
      </select>
      <select v-model="filterEstado" class="rounded-md border outline-none" style="height:36px;padding:0 10px;font-size:13px;background:#F0F2F1;border-color:#E4E8E6;color:#4A5C55;cursor:pointer;">
        <option value="">Todos los estados</option><option>Activos</option><option>Inactivos</option>
      </select>
    </div>

    <!-- Table -->
    <div class="rounded-lg overflow-hidden" style="background:white;border:1px solid #E4E8E6;box-shadow:0 1px 3px rgba(0,0,0,.08);">
      <div style="overflow-x:auto;">
        <table style="width:100%;border-collapse:collapse;">
          <thead>
            <tr style="background:#F8F9F8;border-bottom:1px solid #E4E8E6;">
              <th style="padding:10px 16px;font-size:11.5px;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:#6B7D76;text-align:left;">Nombre</th>
              <th style="padding:10px 16px;font-size:11.5px;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:#6B7D76;text-align:left;white-space:nowrap;">Correo institucional</th>
              <th style="padding:10px 16px;font-size:11.5px;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:#6B7D76;text-align:left;">Documento</th>
              <th style="padding:10px 16px;font-size:11.5px;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:#6B7D76;text-align:left;">Rol</th>
              <th style="padding:10px 16px;font-size:11.5px;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:#6B7D76;text-align:left;white-space:nowrap;">Último acceso</th>
              <th style="padding:10px 16px;font-size:11.5px;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:#6B7D76;text-align:left;">Estado</th>
              <th style="padding:10px 16px;"></th>
            </tr>
          </thead>
          <tbody>
            <tr
              v-for="user in users"
              :key="user.email"
              style="border-bottom:1px solid #F0F2F1;transition:background .18s;"
              @mouseenter="(e) => e.currentTarget.style.background='#F2FAF5'"
              @mouseleave="(e) => e.currentTarget.style.background='transparent'"
            >
              <td style="padding:12px 16px;">
                <div class="flex items-center gap-2">
                  <div
                    class="flex-shrink-0 flex items-center justify-center rounded-full font-bold text-white"
                    style="width:28px;height:28px;font-size:10px;"
                    :style="`background:${user.avatarBg};`"
                  >{{ user.initials }}</div>
                  <span class="font-semibold" style="font-size:13px;color:#1C2925;">{{ user.name }}</span>
                </div>
              </td>
              <td style="padding:12px 16px;font-size:13px;color:#4A5C55;">{{ user.email }}</td>
              <td style="padding:12px 16px;font-size:13px;color:#6B7D76;">{{ user.doc }}</td>
              <td style="padding:12px 16px;">
                <span class="inline-flex items-center rounded-full font-semibold" style="font-size:11.5px;padding:2px 9px;border:1px solid transparent;" :style="badgeStyles[user.rolType]">{{ user.rolLabel }}</span>
              </td>
              <td style="padding:12px 16px;font-size:13px;color:#6B7D76;white-space:nowrap;">{{ user.lastAccess }}</td>
              <td style="padding:12px 16px;">
                <span class="inline-flex items-center gap-1 rounded-full font-semibold" style="font-size:11.5px;padding:2px 9px;border:1px solid transparent;" :style="badgeStyles[user.status]">
                  <span style="width:5px;height:5px;border-radius:50%;background:currentColor;flex-shrink:0;"></span>
                  {{ user.statusLabel }}
                </span>
              </td>
              <td style="padding:12px 16px;">
                <div class="flex items-center gap-1">
                  <button class="flex items-center justify-center rounded-md" style="width:28px;height:28px;background:#F0F2F1;border:1px solid #E4E8E6;color:#4A5C55;cursor:pointer;" title="Ver detalle" @mouseenter="(e) => e.currentTarget.style.background='#E4E8E6'" @mouseleave="(e) => e.currentTarget.style.background='#F0F2F1'">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                  </button>
                  <button class="flex items-center justify-center rounded-md" style="width:28px;height:28px;background:#F0F2F1;border:1px solid #E4E8E6;color:#4A5C55;cursor:pointer;" title="Editar" @mouseenter="(e) => e.currentTarget.style.background='#E4E8E6'" @mouseleave="(e) => e.currentTarget.style.background='#F0F2F1'">
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
        <span style="font-size:12.5px;color:#6B7D76;">4 de 14 usuarios</span>
        <div class="flex items-center gap-1">
          <button class="flex items-center justify-center rounded-md" style="width:30px;height:30px;background:white;border:1px solid #E4E8E6;color:#4A5C55;font-size:13px;cursor:pointer;">‹</button>
          <button class="flex items-center justify-center rounded-md font-bold" style="width:30px;height:30px;background:#2D7A50;border:1px solid #2D7A50;color:white;font-size:13px;cursor:pointer;">1</button>
          <button v-for="n in [2,3,4]" :key="n" class="flex items-center justify-center rounded-md" style="width:30px;height:30px;background:white;border:1px solid #E4E8E6;color:#4A5C55;font-size:13px;cursor:pointer;">{{ n }}</button>
          <button class="flex items-center justify-center rounded-md" style="width:30px;height:30px;background:white;border:1px solid #E4E8E6;color:#4A5C55;font-size:13px;cursor:pointer;">›</button>
        </div>
      </div>
    </div>

    <!-- ─── Nuevo Usuario Modal ─── -->
    <AppModal
      :show="showModal"
      title="Nuevo Usuario"
      subtitle="Complete los datos para crear el acceso al sistema"
      size="lg"
      @close="showModal = false"
    >
      <div class="info-banner info">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="flex-shrink:0;margin-top:1px;"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
        Se enviará una contraseña temporal al correo del usuario. Deberá cambiarla en su primer inicio de sesión.
      </div>

      <div class="form-section">Identificación personal</div>
      <div class="form-row">
        <div class="form-group">
          <label class="form-label">Nombres<span class="req">*</span></label>
          <input v-model="form.nombres" type="text" class="form-control" placeholder="Ej: Laura Sofía" />
        </div>
        <div class="form-group">
          <label class="form-label">Apellidos<span class="req">*</span></label>
          <input v-model="form.apellidos" type="text" class="form-control" placeholder="Ej: Torres Guzmán" />
        </div>
      </div>
      <div class="form-row">
        <div class="form-group">
          <label class="form-label">Tipo de documento</label>
          <select v-model="form.tipoDoc" class="form-control">
            <option>Cédula de Ciudadanía</option>
            <option>Cédula de Extranjería</option>
            <option>Pasaporte</option>
            <option>NIT</option>
          </select>
        </div>
        <div class="form-group">
          <label class="form-label">Número de documento<span class="req">*</span></label>
          <input v-model="form.numDoc" type="text" class="form-control" placeholder="Ej: 53.789.456" />
        </div>
      </div>

      <div class="form-section">Contacto y acceso al sistema</div>
      <div class="form-row">
        <div class="form-group">
          <label class="form-label">Correo electrónico<span class="req">*</span></label>
          <input v-model="form.email" type="email" class="form-control" placeholder="usuario@empresa.com.co" />
        </div>
        <div class="form-group">
          <label class="form-label">Teléfono</label>
          <input v-model="form.telefono" type="text" class="form-control" placeholder="Ej: 300 123 4567" />
        </div>
      </div>
      <div class="form-row">
        <div class="form-group">
          <label class="form-label">Rol en el sistema<span class="req">*</span></label>
          <select v-model="form.rol" class="form-control">
            <option value="">Seleccionar rol...</option>
            <option>Administrador</option>
            <option>Coordinador</option>
            <option>Analista</option>
            <option>Visualizador</option>
          </select>
        </div>
        <div class="form-group">
          <label class="form-label">Cargo en la empresa</label>
          <input v-model="form.cargo" type="text" class="form-control" placeholder="Ej: Coordinador ambiental" />
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
        >Crear usuario</button>
      </template>
    </AppModal>
  </div>
</template>
