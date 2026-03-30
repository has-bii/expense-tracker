<script setup lang="ts">
import { useQuery } from '@tanstack/vue-query'
import { getIncomeDetailQueryOption } from '../api/get-income-detail'
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card'
import { currencyFormatter } from '@/utils/currency'
import { Skeleton } from '@/components/ui/skeleton'
import { Badge } from '@/components/ui/badge'
import { TrendingDown, TrendingUp } from 'lucide-vue-next'
import { computed } from 'vue'
import { Progress } from '@/components/ui/progress'
import { Tooltip, TooltipContent, TooltipProvider, TooltipTrigger } from '@/components/ui/tooltip'

const { data } = useQuery({ ...getIncomeDetailQueryOption() })

const currentTotal = computed(() => (data.value ? Number(data.value.current_total) : 0))

const trendPercentage = computed(() =>
  data.value ? Number((data.value.percentage - 100).toFixed(1)) : 0,
)

const isPositiveTrend = computed(() => trendPercentage.value > 0)

const sources = computed(() => {
  if (!data.value || !currentTotal.value) return []

  return data.value.sources.map((source) => ({
    source: source.source,
    total: source.total,
    percentage: (Number(source.total) / currentTotal.value) * 100,
  }))
})
</script>

<template>
  <div class="grid grid-cols-1 gap-4 lg:grid-cols-2">
    <!-- Overview -->
    <Card>
      <CardHeader>
        <CardTitle>Total Monthly Revenue</CardTitle>
      </CardHeader>
      <CardContent class="flex flex-col gap-3">
        <Skeleton v-if="!data" class="h-10 w-full" />
        <data v-else :value="data.current_total" class="text-4xl font-bold">
          {{ currencyFormatter(data.current_total) }}
        </data>

        <div class="inline-flex items-center gap-2">
          <Skeleton v-if="!data" class="h-8 w-full" />
          <Badge v-else :variant="isPositiveTrend ? 'secondary' : 'destructive'">
            <TrendingUp v-if="isPositiveTrend" />
            <TrendingDown v-else-if="trendPercentage < 0" />
            {{ trendPercentage }}%
          </Badge>
          <span v-if="data" class="block text-sm text-muted-foreground">vs last month</span>
        </div>
      </CardContent>
    </Card>

    <!-- Source -->
    <Card>
      <CardHeader>
        <CardTitle>Percentage by Source</CardTitle>
      </CardHeader>
      <CardContent class="flex flex-col gap-3">
        <TooltipProvider>
          <Skeleton v-if="!data" class="w-full h-8" />
          <template v-for="source in sources" :key="source.source">
            <div class="flex flex-col gap-1.5">
              <div class="inline-flex items-center w-full justify-between">
                <span class="text-sm font-semibold">{{ source.source }}</span>
                <span class="text-sm font-semibold">{{ source.percentage.toFixed(1) }}%</span>
              </div>
              <Tooltip>
                <TooltipTrigger as-child>
                  <Progress :model-value="source.percentage" />
                </TooltipTrigger>
                <TooltipContent>
                  <p>{{ currencyFormatter(source.total) }}</p>
                </TooltipContent>
              </Tooltip>
            </div>
          </template>
        </TooltipProvider>
      </CardContent>
    </Card>
  </div>
</template>
