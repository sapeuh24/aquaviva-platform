<script setup>
import { ref } from 'vue'

const criticals = ref([
  { id: 1, title: 'Indicador vencido — pH aguas residuales PTAR', desc: 'Ficha FM-PTAR-001 · Límite: 30 Abr 2025 · Sin medición registrada', time: 'Hace 2 días · Sin atender', dismissed: false },
  { id: 2, title: 'Actividad no cumplida — Monitoreo flora y fauna', desc: 'Obligación OB-2022-004 · Vencida 15 Mar · Sin responsable', time: 'Hace 18 días · Sin atender', dismissed: false },
])

const warnings = ref([
  { id: 3, title: 'Actividad próxima a vencer mañana', desc: 'Toma de muestras sector norte · L. Torres', time: 'Vence: 15 May 2025', dismissed: false },
])

const infos = [
  { title: 'Nueva obligación asignada al proyecto', desc: 'Resolución 0472/2025 — Ampliación Norte', time: 'Hace 1 día · Leída' },
  { title: 'Usuario asignado a actividad', desc: 'M. Ramírez asignado como analista ambiental', time: 'Hace 3 días · Leída' },
  { title: 'Indicador actualizado — Objetivo alcanzado', desc: 'Volumen residuos sólidos alcanzó meta del 91%', time: 'Hace 5 días · Leída' },
]

function dismiss(list, id) {
  const item = list.value.find(a => a.id === id)
  if (item) item.dismissed = true
}
</script>

<template>
  <div>
    <!-- Page header -->
    <div class="flex items-start justify-between mb-5">
      <div>
        <h1 class="font-extrabold" style="font-size:20px;color:#1C2925;line-height:1.2;">Centro de Alertas</h1>
        <p class="mt-1" style="font-size:13px;color:#6B7D76;">Notificaciones y alertas automáticas del sistema de gestión ambiental</p>
      </div>
      <button
        class="flex items-center gap-2 rounded-md font-semibold"
        style="height:36px;padding:0 14px;font-size:13px;background:#F0F2F1;border:1px solid #E4E8E6;color:#4A5C55;cursor:pointer;"
        @mouseenter="(e) => e.currentTarget.style.background='#E4E8E6'"
        @mouseleave="(e) => e.currentTarget.style.background='#F0F2F1'"
      >
        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
        Marcar informativas leídas
      </button>
    </div>

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;align-items:start;">
      <!-- Left column: Criticals + Warnings -->
      <div class="flex flex-col gap-4">
        <!-- Critical alerts -->
        <div class="rounded-lg overflow-hidden" style="background:white;border:1px solid #E4E8E6;border-left:4px solid #DC2626;box-shadow:0 1px 3px rgba(0,0,0,.08);">
          <div class="flex items-center justify-between" style="padding:14px 20px;border-bottom:1px solid #F0F2F1;">
            <div>
              <div class="font-bold" style="font-size:14px;color:#DC2626;">Alertas Críticas</div>
              <div style="font-size:12px;color:#9FADA7;margin-top:2px;">Requieren acción inmediata</div>
            </div>
            <span class="inline-flex items-center rounded-full font-semibold" style="font-size:11.5px;padding:2px 9px;background:#FFF5F5;color:#991B1B;border:1px solid #FECACA;">
              {{ criticals.filter(a => !a.dismissed).length }} sin atender
            </span>
          </div>
          <div>
            <template v-for="(alert, i) in criticals" :key="alert.id">
              <div
                v-if="!alert.dismissed"
                class="flex items-start gap-3 cursor-pointer"
                style="padding:14px 20px;transition:background .18s;"
                :style="i < criticals.length - 1 ? 'border-bottom:1px solid #F0F2F1;' : ''"
                @mouseenter="(e) => e.currentTarget.style.background='#FFF5F5'"
                @mouseleave="(e) => e.currentTarget.style.background='transparent'"
              >
                <div class="flex-shrink-0 flex items-center justify-center rounded-md" style="width:32px;height:32px;background:#FFF5F5;color:#DC2626;">
                  <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                </div>
                <div class="flex-1 min-w-0">
                  <div class="font-semibold" style="font-size:13px;color:#1C2925;">{{ alert.title }}</div>
                  <div style="font-size:11.5px;color:#6B7D76;margin-top:2px;">{{ alert.desc }}</div>
                  <div style="font-size:11px;color:#9FADA7;margin-top:2px;">{{ alert.time }}</div>
                </div>
                <button
                  class="flex-shrink-0 rounded-md font-semibold"
                  style="height:28px;padding:0 10px;font-size:12px;background:#F0F2F1;border:1px solid #E4E8E6;color:#4A5C55;cursor:pointer;white-space:nowrap;"
                  @click.stop="dismiss(criticals, alert.id)"
                  @mouseenter="(e) => e.currentTarget.style.background='#E4E8E6'"
                  @mouseleave="(e) => e.currentTarget.style.background='#F0F2F1'"
                >Atender</button>
              </div>
            </template>
            <div v-if="criticals.every(a => a.dismissed)" class="text-center" style="padding:24px;color:#9FADA7;font-size:13px;">
              Sin alertas críticas pendientes
            </div>
          </div>
        </div>

        <!-- Warnings -->
        <div class="rounded-lg overflow-hidden" style="background:white;border:1px solid #E4E8E6;border-left:4px solid #D97706;box-shadow:0 1px 3px rgba(0,0,0,.08);">
          <div class="flex items-center justify-between" style="padding:14px 20px;border-bottom:1px solid #F0F2F1;">
            <div class="font-bold" style="font-size:14px;color:#D97706;">Advertencias</div>
            <span class="inline-flex items-center rounded-full font-semibold" style="font-size:11.5px;padding:2px 9px;background:#FFFBEB;color:#92400E;border:1px solid #FDE68A;">
              {{ warnings.filter(a => !a.dismissed).length }}
            </span>
          </div>
          <div>
            <template v-for="alert in warnings" :key="alert.id">
              <div
                v-if="!alert.dismissed"
                class="flex items-start gap-3"
                style="padding:14px 20px;transition:background .18s;"
                @mouseenter="(e) => e.currentTarget.style.background='#FFFBEB'"
                @mouseleave="(e) => e.currentTarget.style.background='transparent'"
              >
                <div class="flex-shrink-0 flex items-center justify-center rounded-md" style="width:32px;height:32px;background:#FFFBEB;color:#D97706;">
                  <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                </div>
                <div class="flex-1 min-w-0">
                  <div class="font-semibold" style="font-size:13px;color:#1C2925;">{{ alert.title }}</div>
                  <div style="font-size:11.5px;color:#6B7D76;margin-top:2px;">{{ alert.desc }}</div>
                  <div style="font-size:11px;color:#9FADA7;margin-top:2px;">{{ alert.time }}</div>
                </div>
                <button
                  class="flex-shrink-0 rounded-md font-semibold"
                  style="height:28px;padding:0 10px;font-size:12px;background:#F0F2F1;border:1px solid #E4E8E6;color:#4A5C55;cursor:pointer;white-space:nowrap;"
                  @click="dismiss(warnings, alert.id)"
                  @mouseenter="(e) => e.currentTarget.style.background='#E4E8E6'"
                  @mouseleave="(e) => e.currentTarget.style.background='#F0F2F1'"
                >Descartar</button>
              </div>
            </template>
            <div v-if="warnings.every(a => a.dismissed)" class="text-center" style="padding:24px;color:#9FADA7;font-size:13px;">
              Sin advertencias pendientes
            </div>
          </div>
        </div>
      </div>

      <!-- Right column: Info notifications -->
      <div class="rounded-lg overflow-hidden" style="background:white;border:1px solid #E4E8E6;box-shadow:0 1px 3px rgba(0,0,0,.08);">
        <div class="flex items-center justify-between" style="padding:14px 20px;border-bottom:1px solid #F0F2F1;">
          <div>
            <div class="font-bold" style="font-size:14px;color:#1C2925;">Informativas</div>
            <div style="font-size:12px;color:#9FADA7;margin-top:2px;">Notificaciones del sistema</div>
          </div>
          <span class="inline-flex items-center rounded-full font-semibold" style="font-size:11.5px;padding:2px 9px;background:#F0F2F1;color:#4A5C55;border:1px solid #E4E8E6;">3 leídas</span>
        </div>
        <div>
          <div
            v-for="(info, i) in infos"
            :key="info.title"
            class="flex items-start gap-3"
            style="padding:14px 20px;opacity:.65;transition:background .18s,opacity .18s;cursor:pointer;"
            :style="i < infos.length - 1 ? 'border-bottom:1px solid #F0F2F1;' : ''"
            @mouseenter="(e) => { e.currentTarget.style.background='#F2FAF5'; e.currentTarget.style.opacity='1'; }"
            @mouseleave="(e) => { e.currentTarget.style.background='transparent'; e.currentTarget.style.opacity='.65'; }"
          >
            <div class="flex-shrink-0 flex items-center justify-center rounded-md" style="width:32px;height:32px;background:#EFF6FF;color:#2563EB;">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
            </div>
            <div class="flex-1 min-w-0">
              <div class="font-semibold" style="font-size:13px;color:#1C2925;">{{ info.title }}</div>
              <div style="font-size:11.5px;color:#6B7D76;margin-top:2px;">{{ info.desc }}</div>
              <div style="font-size:11px;color:#9FADA7;margin-top:2px;">{{ info.time }}</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
