import { createRouter, createWebHistory } from 'vue-router'
import { routes } from './routes'
import { useAuthStore } from '@/stores/auth'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes,
})

// Global Navigation Guard
router.beforeEach(async (to, _from) => {
  const auth = useAuthStore()

  if (!auth.isInitialized) {
    await auth.getUser()
  }

  // Redirect logged-in users
  if (to.meta.guestOnly && auth.isAuthenticated) {
    return { name: 'index' }
  }

  // Redirect unauthenticated users to login
  if (to.meta.requiresAuth && !auth.isAuthenticated) {
    return { name: 'login', query: { redirect: to.fullPath } }
  }
})

export default router
