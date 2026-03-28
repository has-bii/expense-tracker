<script setup lang="ts">
import { useBreadcrumbStore } from '@/stores/breadcrumb'
import { useQuery } from '@tanstack/vue-query'
import { onBeforeMount } from 'vue'
import { getBudgetsQueryOption } from '../api/get-budgets'
import BudgetCard from '../components/BudgetCard.vue'
import { Card, CardHeader } from '@/components/ui/card'
import { Skeleton } from '@/components/ui/skeleton'
import BudgetCreateDialog from '../components/BudgetCreateDialog.vue'
import { Wallet, Target } from 'lucide-vue-next'

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
  <!-- Empty state -->
  <div
    v-if="!isLoading && data.length === 0"
    class="flex flex-col items-center justify-center rounded-xl border-2 border-dashed border-primary/40 bg-card/50 px-6 py-16 text-center animate-in fade-in-0 slide-in-from-bottom-4 duration-500"
  >
    <div class="relative mb-6">
      <div
        class="flex h-20 w-20 items-center justify-center rounded-2xl border border-primary/20 bg-accent shadow-md"
      >
        <Wallet class="h-10 w-10 text-accent-foreground" :stroke-width="1.5" />
      </div>
      <div
        class="absolute -top-2 -right-2 flex h-8 w-8 items-center justify-center rounded-lg border border-primary/20 bg-secondary shadow-sm"
      >
        <Target class="h-4 w-4 text-secondary-foreground" :stroke-width="2" />
      </div>
    </div>
    <h2 class="font-serif text-xl font-semibold text-foreground">No budgets yet</h2>
    <p class="mt-1.5 max-w-xs text-sm text-muted-foreground">
      Budgets help you set spending limits per category. Create one to start tracking your goals.
    </p>
  </div>

  <!-- Grid -->
  <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-2">
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
