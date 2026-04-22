<script setup>
const props = defineProps({
  show: Boolean,
  title: String,
  subtitle: String,
  size: { type: String, default: 'lg' }, // 'sm' | 'lg'
})
const emit = defineEmits(['close'])
</script>

<template>
  <Teleport to="body">
    <Transition name="modal">
      <div v-if="show" class="fixed inset-0 flex items-start justify-center overflow-y-auto" style="z-index:300;background:rgba(15,25,18,.55);backdrop-filter:blur(3px);padding:40px 20px;" @click.self="emit('close')">
        <div class="rounded-xl w-full flex flex-col my-auto" :style="`background:white;border:1px solid #E4E8E6;box-shadow:0 12px 32px rgba(0,0,0,.12),0 4px 8px rgba(0,0,0,.06);max-width:${size === 'sm' ? '400px' : '700px'};`">
          <!-- Header -->
          <div class="flex items-start justify-between" style="padding:18px 22px 14px;border-bottom:1px solid #F0F2F1;">
            <div>
              <h2 class="font-extrabold" style="font-size:16px;color:#1C2925;">{{ title }}</h2>
              <p v-if="subtitle" style="font-size:12.5px;color:#6B7D76;margin-top:3px;">{{ subtitle }}</p>
            </div>
            <button class="flex items-center justify-center rounded-md flex-shrink-0 ml-3" style="width:30px;height:30px;background:#F0F2F1;border:none;color:#6B7D76;font-size:15px;cursor:pointer;" @mouseenter="(e) => { e.currentTarget.style.background='#FFF5F5'; e.currentTarget.style.color='#DC2626'; }" @mouseleave="(e) => { e.currentTarget.style.background='#F0F2F1'; e.currentTarget.style.color='#6B7D76'; }" @click="emit('close')">✕</button>
          </div>
          <!-- Body slot -->
          <div style="padding:22px;">
            <slot />
          </div>
          <!-- Footer slot -->
          <div v-if="$slots.footer" class="flex items-center justify-end gap-2" style="padding:14px 22px;border-top:1px solid #F0F2F1;background:#F8F9F8;border-radius:0 0 12px 12px;">
            <slot name="footer" />
          </div>
        </div>
      </div>
    </Transition>
  </Teleport>
</template>

<style scoped>
.modal-enter-active { animation: modalIn .22s cubic-bezier(.34,1.4,.64,1); }
.modal-leave-active { animation: modalIn .15s ease reverse; }
@keyframes modalIn { from { opacity:0; transform:scale(.94) translateY(16px); } to { opacity:1; transform:none; } }
</style>
