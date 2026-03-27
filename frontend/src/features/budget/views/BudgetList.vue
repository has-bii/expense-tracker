<script setup lang="ts">
import { useBreadcrumbStore } from '@/stores/breadcrumb'
import { useQuery } from '@tanstack/vue-query'
import { onBeforeMount } from 'vue'
import { getBudgetsQueryOption } from '../api/get-budgets'
import BudgetCard from '../components/BudgetCard.vue'
import { Card, CardHeader } from '@/components/ui/card'
import { Skeleton } from '@/components/ui/skeleton'
import BudgetCreateDialog from '../components/BudgetCreateDialog.vue'

const breadcrumb = useBreadcrumbStore()
onBeforeMount(() => {
  breadcrumb.setNavs([{ name: 'Budget List' }])
})

const { data = [], isLoading } = useQuery({ ...getBudgetsQueryOption() })
</script>

<template>
  <div class="flex items-center justify-between">
    <h1 class="text-2xl font-bold">Budget</h1>

    <BudgetCreateDialog />
  </div>
  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-2">
    <BudgetCard v-for="budget in data" :key="budget.id" :data="budget" />

    <!-- Show skeleton -->
    <template v-if="isLoading">
      <Card v-for="id in 4" :key="id">
        <CardHeader>
          <Skeleton class="w-1/3 h-6" />
          <Skeleton class="w-full h-6" />
        </CardHeader>
      </Card>
    </template>
  </div>
</template>
