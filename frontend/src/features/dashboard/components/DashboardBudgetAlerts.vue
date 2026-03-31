<script setup lang="ts">
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card'
import { Progress } from '@/components/ui/progress'
import { Skeleton } from '@/components/ui/skeleton'
import { Badge } from '@/components/ui/badge'
import { currencyFormatter } from '@/utils/currency'
import { CircleCheck, TriangleAlert } from 'lucide-vue-next'
import { computed } from 'vue'
import type { Budget } from '@/features/budget/types'

const props = defineProps<{
  budgets: Budget[]
  loading: boolean
}>()

const alertBudgets = computed(() =>
  props.budgets
    .filter((b) => b.percentage >= 75)
    .sort((a, b) => b.percentage - a.percentage),
)
</script>

<template>
  <Card>
    <CardHeader>
      <CardTitle class="flex items-center gap-2">
        <TriangleAlert v-if="alertBudgets.length > 0" class="size-5 text-destructive" />
        <CircleCheck v-else-if="!loading && budgets.length > 0" class="size-5 text-emerald-500" />
        Budget Status
      </CardTitle>
    </CardHeader>
    <CardContent class="flex flex-col gap-4">
      <!-- Loading -->
      <template v-if="loading">
        <Skeleton v-for="i in 2" :key="i" class="h-12 w-full" />
      </template>

      <!-- No budgets at all -->
      <p v-else-if="budgets.length === 0" class="text-sm text-muted-foreground">
        No budgets configured yet.
      </p>

      <!-- All on track -->
      <p v-else-if="alertBudgets.length === 0" class="text-sm text-muted-foreground">
        All budgets are on track this period.
      </p>

      <!-- Budget alerts -->
      <template v-else>
        <div
          v-for="budget in alertBudgets"
          :key="budget.id"
          class="flex flex-col gap-2"
        >
          <div class="flex items-center justify-between">
            <div class="flex items-center gap-2">
              <span class="text-lg">{{ budget.category.icon }}</span>
              <span class="text-sm font-semibold">{{ budget.category.name }}</span>
              <Badge variant="secondary" class="uppercase text-xs">{{ budget.period }}</Badge>
            </div>
            <Badge :variant="budget.percentage >= 100 ? 'destructive' : 'secondary'">
              {{ budget.percentage }}%
            </Badge>
          </div>
          <div class="flex items-center justify-between text-sm text-muted-foreground">
            <span>{{ currencyFormatter(budget.spent) }} / {{ currencyFormatter(budget.limit_amount) }}</span>
          </div>
          <Progress :model-value="Math.min(budget.percentage, 100)" />
        </div>
      </template>
    </CardContent>
  </Card>
</template>
