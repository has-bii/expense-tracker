import type { RouteRecordRaw } from 'vue-router'

export const routes: RouteRecordRaw[] = [
  {
    path: '/login',
    name: 'login',
    component: () => import('@/features/auth/views/Login.vue'),
    meta: { guestOnly: true },
  },
  {
    path: '/register',
    name: 'register',
    component: () => import('@/features/auth/views/Register.vue'),
    meta: { guestOnly: true },
  },
  {
    path: '/forgot-password',
    name: 'forgot-password',
    component: () => import('@/features/auth/views/ForgotPassword.vue'),
    meta: { guestOnly: true },
  },
  {
    path: '/reset-password',
    name: 'reset-password',
    component: () => import('@/features/auth/views/ResetPassword.vue'),
    meta: { guestOnly: true },
  },
  {
    path: '/',
    name: 'index',
    component: () => import('@/features/dashboard/views/Dashboard.vue'),
    meta: { requiresAuth: true },
  },
  {
    path: '/error',
    name: 'error',
    component: () => import('@/features/global/view/error.vue'),
  },
]
