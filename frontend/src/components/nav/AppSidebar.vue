<script setup lang="ts">
import type { SidebarProps } from '@/components/ui/sidebar'

import {
  BanknoteArrowDown,
  BanknoteArrowUp,
  ChartBarStacked,
  HandCoins,
  House,
  Wallet,
} from 'lucide-vue-next'

import NavMain from './NavMain.vue'
import NavUser from './NavUser.vue'
import {
  Sidebar,
  SidebarContent,
  SidebarFooter,
  SidebarHeader,
  SidebarMenu,
  SidebarMenuButton,
  SidebarMenuItem,
} from '@/components/ui/sidebar'
import { RouterLink } from 'vue-router'
import { useAuthStore } from '@/stores/auth'

const props = withDefaults(defineProps<SidebarProps>(), {
  variant: 'inset',
})

const auth = useAuthStore()

const user = auth.user!

const data = {
  user: {
    name: user.name,
    email: user.email,
    avatar: '/avatars/shadcn.jpg',
  },
  main: [
    {
      name: 'Dashboard',
      url: '/',
      icon: House,
    },
    {
      name: 'Category',
      url: '/category',
      icon: ChartBarStacked,
    },
    {
      name: 'Budget',
      url: '/budget',
      icon: HandCoins,
    },
    {
      name: 'Expense',
      url: '/expense',
      icon: BanknoteArrowUp,
    },
    {
      name: 'Income',
      url: '/income',
      icon: BanknoteArrowDown,
    },
  ],
}
</script>

<template>
  <Sidebar v-bind="props">
    <SidebarHeader>
      <SidebarMenu>
        <SidebarMenuItem>
          <SidebarMenuButton size="lg" as-child>
            <RouterLink to="/">
              <div
                class="flex aspect-square size-8 items-center justify-center rounded-lg bg-sidebar-primary text-sidebar-primary-foreground"
              >
                <Wallet class="size-4" />
              </div>
              <div class="grid flex-1 text-left text-sm leading-tight">
                <span class="truncate font-medium">Expense Tracker</span>
                <span class="truncate text-xs">Free plan</span>
              </div>
            </RouterLink>
          </SidebarMenuButton>
        </SidebarMenuItem>
      </SidebarMenu>
    </SidebarHeader>
    <SidebarContent>
      <NavMain :navs="data.main" />
    </SidebarContent>
    <SidebarFooter>
      <NavUser :user="data.user" />
    </SidebarFooter>
  </Sidebar>
</template>
