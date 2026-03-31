<script setup lang="ts">
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card'
import { Progress } from '@/components/ui/progress'
import { Skeleton } from '@/components/ui/skeleton'
import { Badge } from '@/components/ui/badge'
import { Tooltip, TooltipContent, TooltipProvider, TooltipTrigger } from '@/components/ui/tooltip'
import { currencyFormatter } from '@/utils/currency'
import type { Budget } from '@/features/budget/types'

defineProps<{
  budgets: Budget[]
  loading: boolean
}>()
</script>

<template>
  <Card>
    <CardHeader>
      <CardTitle>All Budgets</CardTitle>
    </CardHeader>
    <CardContent class="flex flex-col gap-4">
      <!-- Loading -->
      <template v-if="loading">
        <Skeleton v-for="i in 3" :key="i" class="h-10 w-full" />
      </template>

      <!-- No budgets -->
      <p v-else-if="budgets.length === 0" class="text-sm text-muted-foreground">
        No budgets configured yet.
      </p>

      <!-- All budgets -->
      <TooltipProvider v-else>
        <div
          v-for="budget in budgets"
          :key="budget.id"
          class="flex flex-col gap-1.5"
        >
          <div class="flex items-center justify-between">
            <div class="flex items-center gap-2">
              <span class="text-lg">{{ budget.category.icon }}</span>
              <span class="text-sm font-semibold">{{ budget.category.name }}</span>
            </div>
            <Badge
              :variant="budget.percentage >= 100 ? 'destructive' : budget.percentage >= 75 ? 'secondary' : 'outline'"
              class="text-xs"
            >
              {{ budget.percentage }}%
            </Badge>
          </div>
          <Tooltip>
            <TooltipTrigger as-child>
              <Progress :model-value="Math.min(budget.percentage, 100)" />
            </TooltipTrigger>
            <TooltipContent>
              <p>{{ currencyFormatter(budget.spent) }} / {{ currencyFormatter(budget.limit_amount) }}</p>
            </TooltipContent>
          </Tooltip>
        </div>
      </TooltipProvider>
    </CardContent>
  </Card>
</template>
