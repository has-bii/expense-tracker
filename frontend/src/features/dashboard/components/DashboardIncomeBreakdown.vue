<script setup lang="ts">
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card'
import { Progress } from '@/components/ui/progress'
import { Skeleton } from '@/components/ui/skeleton'
import { Tooltip, TooltipContent, TooltipProvider, TooltipTrigger } from '@/components/ui/tooltip'
import { currencyFormatter } from '@/utils/currency'
import { computed } from 'vue'
import type { IncomeDetail } from '@/features/income/types'

const props = defineProps<{
  sources: IncomeDetail['sources']
  currentTotal: number
  loading: boolean
}>()

const breakdown = computed(() => {
  if (!props.sources.length || !props.currentTotal) return []

  const mapped = props.sources
    .map((s) => ({
      name: s.source,
      total: Number(s.total),
      percentage: (Number(s.total) / props.currentTotal) * 100,
    }))
    .sort((a, b) => b.total - a.total)

  if (mapped.length <= 5) return mapped

  const top5 = mapped.slice(0, 5)
  const rest = mapped.slice(5)
  const otherTotal = rest.reduce((sum, item) => sum + item.total, 0)

  return [
    ...top5,
    {
      name: 'Other',
      total: otherTotal,
      percentage: (otherTotal / props.currentTotal) * 100,
    },
  ]
})
</script>

<template>
  <Card>
    <CardHeader>
      <CardTitle>Income by Source</CardTitle>
    </CardHeader>
    <CardContent class="flex flex-col gap-3">
      <TooltipProvider>
        <template v-if="loading">
          <Skeleton v-for="i in 3" :key="i" class="w-full h-8" />
        </template>
        <template v-else-if="breakdown.length === 0">
          <p class="text-sm text-muted-foreground">No income data this month.</p>
        </template>
        <template v-else>
          <div v-for="item in breakdown" :key="item.name" class="flex flex-col gap-1.5">
            <div class="inline-flex items-center w-full justify-between">
              <span class="text-sm font-semibold">{{ item.name }}</span>
              <span class="text-sm font-semibold">{{ item.percentage.toFixed(1) }}%</span>
            </div>
            <Tooltip>
              <TooltipTrigger as-child>
                <Progress :model-value="item.percentage" />
              </TooltipTrigger>
              <TooltipContent>
                <p>{{ currencyFormatter(item.total) }}</p>
              </TooltipContent>
            </Tooltip>
          </div>
        </template>
      </TooltipProvider>
    </CardContent>
  </Card>
</template>
