<script setup>
/**
 * AppBadge — status indicator pill
 *
 * Props:
 *   variant: 'success' | 'warn' | 'danger' | 'info' | 'gray' | 'green'  (default: 'gray')
 *   dot:     boolean — show a small dot prefix                             (default: false)
 */
defineProps({
  variant: {
    type: String,
    default: 'gray',
    validator: (v) => ['success', 'warn', 'danger', 'info', 'gray', 'green'].includes(v),
  },
  dot: {
    type: Boolean,
    default: false,
  },
})

const variantStyles = {
  success: 'background:#F0FDF4;color:#065F46;border-color:#BBF7D0;',
  warn:    'background:#FFFBEB;color:#92400E;border-color:#FDE68A;',
  danger:  'background:#FFF5F5;color:#991B1B;border-color:#FECACA;',
  info:    'background:#EFF6FF;color:#1E40AF;border-color:#BFDBFE;',
  gray:    'background:#F0F2F1;color:#4A5C55;border-color:#E4E8E6;',
  green:   'background:#F2FAF5;color:#246040;border-color:#DCF0E4;',
}
</script>

<template>
  <span
    class="inline-flex items-center gap-[5px] rounded-[20px] font-semibold whitespace-nowrap"
    style="padding:2px 9px;font-size:11.5px;border:1px solid transparent;"
    :style="variantStyles[variant]"
  >
    <!-- Dot prefix -->
    <span
      v-if="dot"
      class="rounded-full flex-shrink-0"
      style="width:5px;height:5px;background:currentColor;"
    />
    <slot />
  </span>
</template>
