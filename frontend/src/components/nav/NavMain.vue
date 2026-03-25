<script setup lang="ts">
import type { LucideIcon } from 'lucide-vue-next'

import {
  SidebarGroup,
  SidebarMenu,
  SidebarMenuButton,
  SidebarMenuItem,
} from '@/components/ui/sidebar'
import { RouterLink, useRoute } from 'vue-router'
import { computed } from 'vue'

const props = defineProps<{
  navs: {
    name: string
    url: string
    icon: LucideIcon
  }[]
}>()

const route = useRoute()

const mapped = computed(() => {
  return props.navs.map((nav) => ({
    ...nav,
    isActive: nav.url === '/' ? route.fullPath === '/' : route.fullPath.startsWith(nav.url),
  }))
})
</script>

<template>
  <SidebarGroup class="group-data-[collapsible=icon]:hidden">
    <SidebarMenu>
      <SidebarMenuItem v-for="item in mapped" :key="item.name">
        <SidebarMenuButton as-child :is-active="item.isActive">
          <RouterLink :to="item.url">
            <component :is="item.icon" />
            <span>{{ item.name }}</span>
          </RouterLink>
        </SidebarMenuButton>
      </SidebarMenuItem>
    </SidebarMenu>
  </SidebarGroup>
</template>
