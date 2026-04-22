<script setup>
// Dashboard — mock data for presentation; will be wired to real API
const stats = [
  {
    label: 'Programas activos',
    value: 8,
    delta: '↑ 2 este mes',
    deltaType: 'up',
    color: 'green',
    path: '/programas',
    icon: `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg>`,
  },
  {
    label: 'Obligaciones vigentes',
    value: 12,
    delta: '1 próxima a vencer',
    deltaType: 'flat',
    color: 'info',
    path: '/obligaciones',
    icon: `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>`,
  },
  {
    label: 'Indicadores monitoreados',
    value: 34,
    delta: '78% en meta',
    deltaType: 'up',
    color: 'green',
    path: '/indicadores',
    icon: `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>`,
  },
  {
    label: 'Actividades pendientes',
    value: 12,
    delta: '↓ 3 vencidas',
    deltaType: 'down',
    color: 'warn',
    path: '/actividades',
    icon: `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg>`,
  },
  {
    label: 'Alertas sin atender',
    value: 3,
    delta: 'Acción requerida',
    deltaType: 'down',
    color: 'danger',
    path: '/alertas',
    icon: `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>`,
  },
]

const programs = [
  { name: 'Manejo de Residuos Sólidos', pct: 87, type: 'success' },
  { name: 'Tratamiento de Aguas Residuales', pct: 64, type: 'warn' },
  { name: 'Control de Emisiones Atmosféricas', pct: 42, type: 'danger' },
  { name: 'Gestión de Suelos Contaminados', pct: 93, type: 'success' },
  { name: 'Biodiversidad y Ecosistemas', pct: 71, type: 'warn' },
]

const alerts = [
  { type: 'danger', title: 'Indicador vencido: pH aguas residuales', desc: 'Ficha PTAR-002 · Fecha límite superada', time: 'Hace 2 días · Sin atender', badge: 'Crítico' },
  { type: 'warn',   title: 'Actividad próxima a vencer', desc: 'Toma de muestras sector norte — vence mañana', time: 'Hace 5 horas', badge: 'Advertencia' },
  { type: 'info',   title: 'Nueva obligación asignada al proyecto', desc: 'Resolución 0472/2025 — Ampliación Norte', time: 'Hace 1 día', badge: 'Info' },
]

const activities = [
  { name: 'Toma de muestras agua punto P1', indicator: 'pH aguas residuales', code: 'PTAH-002', date: '15 May 2025', responsible: 'L. Torres', status: 'warn', statusLabel: 'Pendiente' },
  { name: 'Análisis laboratorio turbidez Q2', indicator: 'Turbidez efluente', code: 'PTAH-002', date: '20 May 2025', responsible: 'M. Ramírez', status: 'info', statusLabel: 'En progreso' },
  { name: 'Informe mensual caudal PTAR', indicator: 'Caudal tratado m³/día', code: 'PTAH-002', date: '30 Abr 2025', responsible: 'J. Martínez', status: 'success', statusLabel: 'Cumplida' },
  { name: 'Registro fotográfico zona almacenamiento', indicator: 'Vol. residuos generados', code: 'PMR-001', date: '28 Abr 2025', responsible: 'C. López', status: 'success', statusLabel: 'Cumplida' },
]

// Color maps
const iconColors = {
  green:  { bg: '#F2FAF5', border: '#DCF0E4', stroke: '#2D7A50' },
  info:   { bg: '#EFF6FF', border: '#BFDBFE', stroke: '#2563EB' },
  warn:   { bg: '#FFFBEB', border: '#FDE68A', stroke: '#D97706' },
  danger: { bg: '#FFF5F5', border: '#FECACA', stroke: '#DC2626' },
}

const badgeStyles = {
  success: 'background:#F0FDF4;color:#065F46;border-color:#BBF7D0;',
  warn:    'background:#FFFBEB;color:#92400E;border-color:#FDE68A;',
  danger:  'background:#FFF5F5;color:#991B1B;border-color:#FECACA;',
  info:    'background:#EFF6FF;color:#1E40AF;border-color:#BFDBFE;',
}

const barColors = {
  success: '#3A9A64',
  warn:    '#D97706',
  danger:  '#DC2626',
}

const alertIcons = {
  danger: { color: '#DC2626', icon: `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>` },
  warn:   { color: '#D97706', icon: `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>` },
  info:   { color: '#2563EB', icon: `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>` },
}
</script>

<template>
  <div>
    <!-- Page header -->
    <div class="mb-5 flex items-start justify-between">
      <div>
        <h1 class="font-extrabold" style="font-size:20px;color:#1C2925;line-height:1.2;">
          Panel de Control
        </h1>
        <p class="mt-1" style="font-size:13px;color:#6B7D76;">
          Resumen de cumplimiento ambiental · Actualizado hoy
        </p>
      </div>
      <button
        class="flex items-center gap-2 rounded-md font-semibold text-white"
        style="height:36px;padding:0 14px;font-size:13px;background:#2D7A50;border:none;"
        @mouseenter="(e) => e.currentTarget.style.background='#246040'"
        @mouseleave="(e) => e.currentTarget.style.background='#2D7A50'"
      >
        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/>
        </svg>
        Exportar reporte
      </button>
    </div>

    <!-- KPI Stat Cards -->
    <div class="mb-5" style="display:grid;grid-template-columns:repeat(auto-fit,minmax(190px,1fr));gap:14px;">
      <div
        v-for="stat in stats"
        :key="stat.label"
        class="rounded-lg flex items-start gap-3 cursor-pointer"
        style="background:white;border:1px solid #E4E8E6;padding:18px;box-shadow:0 1px 3px rgba(0,0,0,.08);transition:all .18s ease;"
        @mouseenter="(e) => { e.currentTarget.style.borderColor='#A8D9BA'; e.currentTarget.style.boxShadow='0 4px 12px rgba(0,0,0,.10)'; e.currentTarget.style.transform='translateY(-1px)'; }"
        @mouseleave="(e) => { e.currentTarget.style.borderColor='#E4E8E6'; e.currentTarget.style.boxShadow='0 1px 3px rgba(0,0,0,.08)'; e.currentTarget.style.transform='none'; }"
      >
        <!-- Icon -->
        <div
          class="flex-shrink-0 flex items-center justify-center rounded-lg"
          :style="`width:44px;height:44px;background:${iconColors[stat.color].bg};border:1px solid ${iconColors[stat.color].border};color:${iconColors[stat.color].stroke};`"
        >
          <span style="width:20px;height:20px;display:flex;" v-html="stat.icon" />
        </div>
        <!-- Content -->
        <div class="flex-1 min-w-0">
          <div class="font-extrabold leading-none mb-0.5" style="font-size:28px;color:#1C2925;">
            {{ stat.value }}
          </div>
          <div class="font-medium" style="font-size:12px;color:#6B7D76;">
            {{ stat.label }}
          </div>
          <div
            class="mt-1 font-medium"
            style="font-size:11.5px;"
            :style="stat.deltaType === 'up' ? 'color:#059669' : stat.deltaType === 'down' ? 'color:#DC2626' : 'color:#9FADA7'"
          >
            {{ stat.delta }}
          </div>
        </div>
      </div>
    </div>

    <!-- Two-column: Compliance + Alerts -->
    <div class="mb-4" style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
      <!-- Compliance by program -->
      <div class="rounded-lg overflow-hidden" style="background:white;border:1px solid #E4E8E6;box-shadow:0 1px 3px rgba(0,0,0,.08);">
        <div class="flex items-start justify-between" style="padding:16px 20px;border-bottom:1px solid #F0F2F1;">
          <div>
            <div class="font-bold" style="font-size:14px;color:#1C2925;">Cumplimiento por Programa</div>
            <div style="font-size:12px;color:#9FADA7;margin-top:2px;">% actividades completadas en el período</div>
          </div>
          <button class="font-semibold transition-colors" style="font-size:12.5px;color:#2D7A50;background:none;border:none;cursor:pointer;" @mouseenter="(e) => e.target.style.color='#246040'" @mouseleave="(e) => e.target.style.color='#2D7A50'">Ver todos</button>
        </div>
        <div style="padding:20px;">
          <div v-for="(prog, i) in programs" :key="prog.name" :style="i < programs.length - 1 ? 'margin-bottom:16px' : ''">
            <div class="flex items-center justify-between mb-1">
              <span style="font-size:12.5px;color:#2E3D38;font-weight:500;">{{ prog.name }}</span>
              <span
                class="font-bold"
                style="font-size:12.5px;"
                :style="prog.type === 'success' ? 'color:#059669' : prog.type === 'warn' ? 'color:#D97706' : 'color:#DC2626'"
              >{{ prog.pct }}%</span>
            </div>
            <div style="background:#E4E8E6;border-radius:20px;height:6px;overflow:hidden;">
              <div
                style="height:100%;border-radius:20px;transition:width .6s ease;"
                :style="`width:${prog.pct}%;background:${barColors[prog.type]};`"
              />
            </div>
          </div>
        </div>
      </div>

      <!-- Recent Alerts -->
      <div class="rounded-lg overflow-hidden" style="background:white;border:1px solid #E4E8E6;box-shadow:0 1px 3px rgba(0,0,0,.08);">
        <div class="flex items-start justify-between" style="padding:16px 20px;border-bottom:1px solid #F0F2F1;">
          <div>
            <div class="font-bold" style="font-size:14px;color:#1C2925;">Alertas Recientes</div>
            <div style="font-size:12px;color:#9FADA7;margin-top:2px;">Notificaciones que requieren atención</div>
          </div>
          <button class="font-semibold transition-colors" style="font-size:12.5px;color:#2D7A50;background:none;border:none;cursor:pointer;" @mouseenter="(e) => e.target.style.color='#246040'" @mouseleave="(e) => e.target.style.color='#2D7A50'">Ver todas</button>
        </div>
        <div>
          <div
            v-for="(alert, i) in alerts"
            :key="alert.title"
            class="flex items-start gap-3 cursor-pointer"
            style="padding:14px 20px;transition:background .18s;"
            :style="i < alerts.length - 1 ? 'border-bottom:1px solid #F0F2F1;' : ''"
            @mouseenter="(e) => e.currentTarget.style.background='#F2FAF5'"
            @mouseleave="(e) => e.currentTarget.style.background='transparent'"
          >
            <!-- Alert icon -->
            <div
              class="flex-shrink-0 flex items-center justify-center rounded-md"
              style="width:32px;height:32px;"
              :style="`color:${alertIcons[alert.type].color};background:${alert.type === 'danger' ? '#FFF5F5' : alert.type === 'warn' ? '#FFFBEB' : '#EFF6FF'};`"
            >
              <span style="width:16px;height:16px;display:flex;" v-html="alertIcons[alert.type].icon" />
            </div>
            <!-- Content -->
            <div class="flex-1 min-w-0">
              <div class="font-semibold truncate" style="font-size:13px;color:#1C2925;">{{ alert.title }}</div>
              <div class="truncate" style="font-size:11.5px;color:#6B7D76;margin-top:1px;">{{ alert.desc }}</div>
              <div style="font-size:11px;color:#9FADA7;margin-top:2px;">{{ alert.time }}</div>
            </div>
            <!-- Badge -->
            <span
              class="flex-shrink-0 flex items-center rounded-full font-semibold"
              style="font-size:11px;padding:2px 9px;border:1px solid transparent;"
              :style="alert.type === 'danger' ? badgeStyles.danger : alert.type === 'warn' ? badgeStyles.warn : badgeStyles.info"
            >{{ alert.badge }}</span>
          </div>
        </div>
      </div>
    </div>

    <!-- Recent Activities Table -->
    <div class="rounded-lg overflow-hidden" style="background:white;border:1px solid #E4E8E6;box-shadow:0 1px 3px rgba(0,0,0,.08);">
      <div class="flex items-start justify-between" style="padding:16px 20px;border-bottom:1px solid #F0F2F1;">
        <div>
          <div class="font-bold" style="font-size:14px;color:#1C2925;">Actividades Recientes</div>
          <div style="font-size:12px;color:#9FADA7;margin-top:2px;">Últimas actividades registradas en el sistema</div>
        </div>
        <button
          class="flex items-center gap-1.5 rounded-md font-semibold"
          style="height:30px;padding:0 12px;font-size:12px;background:#F0F2F1;border:1px solid #E4E8E6;color:#4A5C55;cursor:pointer;"
          @mouseenter="(e) => { e.currentTarget.style.background='#E4E8E6'; e.currentTarget.style.borderColor='#CDD5D0'; }"
          @mouseleave="(e) => { e.currentTarget.style.background='#F0F2F1'; e.currentTarget.style.borderColor='#E4E8E6'; }"
        >
          Ver tablero completo
        </button>
      </div>
      <div style="overflow-x:auto;">
        <table style="width:100%;border-collapse:collapse;">
          <thead>
            <tr style="background:#F8F9F8;border-bottom:1px solid #E4E8E6;">
              <th style="padding:10px 16px;font-size:11.5px;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:#6B7D76;text-align:left;white-space:nowrap;">Actividad</th>
              <th style="padding:10px 16px;font-size:11.5px;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:#6B7D76;text-align:left;white-space:nowrap;">Indicador</th>
              <th style="padding:10px 16px;font-size:11.5px;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:#6B7D76;text-align:left;white-space:nowrap;">Programa</th>
              <th style="padding:10px 16px;font-size:11.5px;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:#6B7D76;text-align:left;white-space:nowrap;">Fecha</th>
              <th style="padding:10px 16px;font-size:11.5px;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:#6B7D76;text-align:left;white-space:nowrap;">Responsable</th>
              <th style="padding:10px 16px;font-size:11.5px;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:#6B7D76;text-align:left;white-space:nowrap;">Estado</th>
              <th style="padding:10px 16px;"></th>
            </tr>
          </thead>
          <tbody>
            <tr
              v-for="act in activities"
              :key="act.name"
              style="border-bottom:1px solid #F0F2F1;transition:background .18s;"
              @mouseenter="(e) => e.currentTarget.style.background='#F2FAF5'"
              @mouseleave="(e) => e.currentTarget.style.background='transparent'"
            >
              <td style="padding:12px 16px;font-size:13px;font-weight:600;color:#1C2925;max-width:220px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ act.name }}</td>
              <td style="padding:12px 16px;font-size:13px;color:#4A5C55;">{{ act.indicator }}</td>
              <td style="padding:12px 16px;">
                <span style="background:#F2FAF5;color:#246040;border:1px solid #DCF0E4;border-radius:4px;padding:2px 7px;font-size:11.5px;font-weight:600;font-family:monospace;">{{ act.code }}</span>
              </td>
              <td style="padding:12px 16px;font-size:13px;color:#6B7D76;white-space:nowrap;">{{ act.date }}</td>
              <td style="padding:12px 16px;font-size:13px;color:#4A5C55;">{{ act.responsible }}</td>
              <td style="padding:12px 16px;">
                <span
                  class="flex items-center gap-1 rounded-full font-semibold"
                  style="display:inline-flex;font-size:11.5px;padding:2px 9px;border:1px solid transparent;"
                  :style="badgeStyles[act.status]"
                >
                  <span style="width:5px;height:5px;border-radius:50%;background:currentColor;flex-shrink:0;"></span>
                  {{ act.statusLabel }}
                </span>
              </td>
              <td style="padding:12px 16px;">
                <button
                  style="font-size:12px;color:#2D7A50;background:none;border:none;cursor:pointer;font-weight:600;"
                  @mouseenter="(e) => e.target.style.color='#246040'"
                  @mouseleave="(e) => e.target.style.color='#2D7A50'"
                >Ver →</button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>
