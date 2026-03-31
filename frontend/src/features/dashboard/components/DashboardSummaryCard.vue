<script setup lang="ts">
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card'
import { currencyFormatter } from '@/utils/currency'
import { Skeleton } from '@/components/ui/skeleton'
import { Badge } from '@/components/ui/badge'
import { TrendingDown, TrendingUp } from 'lucide-vue-next'
import { computed } from 'vue'

const props = defineProps<{
  title: string
  amount: number
  trendPercentage: number
  trendType: 'up-good' | 'up-bad'
  loading: boolean
}>()

const isUp = computed(() => props.trendPercentage > 0)

// TODO: Implement trend badge variant logic
// This computed should return the Badge variant ('secondary' or 'destructive')
// based on whether the trend direction is good or bad for the user.
//
// Consider:
//   - trendType 'up-good': going up is positive (e.g., income) → up = secondary, down = destructive
//   - trendType 'up-bad': going up is negative (e.g., expenses) → up = destructive, down = secondary
const badgeVariant = computed((): 'secondary' | 'destructive' => {
  // YOUR CODE HERE (~3 lines)
  return 'secondary'
})
</script>

<template>
  <Card>
    <CardHeader>
      <CardTitle>{{ title }}</CardTitle>
    </CardHeader>
    <CardContent class="flex flex-col gap-3">
      <Skeleton v-if="loading" class="h-10 w-full" />
      <data v-else :value="amount" class="text-4xl font-bold">
        {{ currencyFormatter(amount) }}
      </data>

      <div class="inline-flex items-center gap-2">
        <Skeleton v-if="loading" class="h-8 w-full" />
        <template v-else>
          <Badge :variant="badgeVariant">
            <TrendingUp v-if="isUp" />
            <TrendingDown v-else-if="trendPercentage < 0" />
            {{ trendPercentage }}%
          </Badge>
          <span class="block text-sm text-muted-foreground">vs last month</span>
        </template>
      </div>
    </CardContent>
  </Card>
</template>
