import type { RouteRecordRaw } from 'vue-router'

export const routes: RouteRecordRaw[] = [
  {
    path: '/login',
    component: () => import('@/features/auth/views/Login.vue'),
    meta: { guestOnly: true },
  },
  {
    path: '/register',
    component: () => import('@/features/auth/views/Register.vue'),
    meta: { guestOnly: true },
  },
  {
    path: '/forgot-password',
    component: () => import('@/features/auth/views/ForgotPassword.vue'),
    meta: { guestOnly: true },
  },
  {
    path: '/reset-password',
    component: () => import('@/features/auth/views/ResetPassword.vue'),
    meta: { guestOnly: true },
  },
  {
    path: '/',
    meta: { requiresAuth: true },
    component: () => import('@/features/dashboard/layout/DashboardLayout.vue'),
    children: [
      {
        path: '',
        component: () => import('@/features/dashboard/views/Dashboard.vue'),
      },
      {
        path: 'category',
        component: () => import('@/features/category/views/CategoryList.vue'),
      },
      {
        path: 'budget',
        component: () => import('@/features/budget/views/BudgetList.vue'),
      },
      {
        path: 'income',
        component: () => import('@/features/income/views/IncomeOverview.vue'),
      },
    ],
  },
  {
    path: '/category',
    component: () => import('@/features/category/views/CategoryList.vue'),
    meta: { requiresAuth: true },
  },
  {
    path: '/error',
    component: () => import('@/features/global/view/error.vue'),
  },
  {
    path: '/:pathMatch(.*)*',
    component: () => import('@/features/global/view/NotFound.vue'),
  },
]
