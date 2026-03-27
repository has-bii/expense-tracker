<script setup lang="ts">
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card'
import { format } from 'date-fns'
import type { Budget } from '../types'
import { currencyFormatter } from '@/utils/currency'
import { Badge } from '@/components/ui/badge'
import { Progress } from '@/components/ui/progress'

defineProps<{
  data: Budget
}>()
</script>

<template>
  <Card role="button">
    <CardHeader class="gap-0">
      <div class="flex items-center justify-between mb-4">
        <!-- Icon -->
        <div class="size-10 bg-accent inline-flex items-center justify-center rounded-lg">
          <span>{{ data.category.icon }}</span>
        </div>

        <!-- Periode -->
        <Badge class="uppercase" variant="secondary">
          {{ data.period }}
        </Badge>
      </div>
      <CardTitle class="text-xl">{{ data.category.name }}</CardTitle>
      <CardDescription> Started {{ format(data.start_date, 'PP') }} </CardDescription>
    </CardHeader>
    <CardContent class="flex flex-col gap-2">
      <div class="flex items-end justify-between">
        <span class="text-2xl font-bold">
          {{ currencyFormatter(0) }}
          <span class="text-sm font-normal">/{{ currencyFormatter(data.limit_amount) }}</span>
        </span>
        <span class="font-semibold">0%</span>
      </div>
      <Progress :model-value="0" />
    </CardContent>
  </Card>
</template>
