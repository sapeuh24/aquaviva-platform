import { computed } from 'vue'
import { storeToRefs } from 'pinia'
import { useAuthStore } from '@/stores/auth.js'

/**
 * Composable for role-based access control.
 *
 * Usage:
 *   const { isAdmin, isSuperAdmin, hasRole, can } = usePermissions()
 *   v-if="isAdmin"
 *   v-if="hasRole('coordinator')"
 *   v-if="can('upload_evidence')"
 */
export function usePermissions() {
  const authStore = useAuthStore()
  const { userRole, hasRole, can } = storeToRefs(authStore)

  const isSuperAdmin = computed(() => userRole.value === 'super_admin')
  const isAdmin = computed(
    () => userRole.value === 'admin' || userRole.value === 'super_admin',
  )
  const isCoordinator = computed(
    () => ['coordinator', 'admin', 'super_admin'].includes(userRole.value),
  )
  const isAnalyst = computed(
    () => ['analyst', 'coordinator', 'admin', 'super_admin'].includes(userRole.value),
  )
  const isViewer = computed(() => userRole.value === 'viewer')

  /**
   * Role label in Spanish for display purposes.
   */
  const roleLabel = computed(() => {
    const labels = {
      super_admin: 'Super Administrador',
      admin: 'Administrador',
      coordinator: 'Coordinador Ambiental',
      analyst: 'Analista Ambiental',
      viewer: 'Solo Lectura',
    }
    return labels[userRole.value] ?? userRole.value
  })

  return {
    userRole,
    isSuperAdmin,
    isAdmin,
    isCoordinator,
    isAnalyst,
    isViewer,
    roleLabel,
    hasRole,
    can,
  }
}
