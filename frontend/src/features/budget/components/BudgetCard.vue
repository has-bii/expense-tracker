<script setup lang="ts">
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card'
import type { Budget } from '../types'
import { currencyFormatter } from '@/utils/currency'
import { Badge } from '@/components/ui/badge'
import { Progress } from '@/components/ui/progress'
import {
  DropdownMenu,
  DropdownMenuContent,
  DropdownMenuItem,
  DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu'
import { Button } from '@/components/ui/button'
import { EllipsisVertical, Pencil, Trash2 } from 'lucide-vue-next'

defineProps<{
  data: Budget
}>()

defineEmits<{
  edit: [budget: Budget]
  delete: [budget: Budget]
}>()
</script>

<template>
  <Card>
    <CardHeader class="gap-0">
      <div class="flex items-center justify-between mb-4">
        <!-- Icon -->
        <div class="size-10 bg-accent inline-flex items-center justify-center rounded-lg">
          <span>{{ data.category.icon }}</span>
        </div>

        <div class="flex items-center gap-1">
          <!-- Period -->
          <Badge class="uppercase" variant="secondary">
            {{ data.period }}
          </Badge>

          <!-- Actions -->
          <DropdownMenu>
            <DropdownMenuTrigger as-child>
              <Button variant="ghost" size="icon" class="size-8">
                <EllipsisVertical class="size-4" />
              </Button>
            </DropdownMenuTrigger>
            <DropdownMenuContent align="end">
              <DropdownMenuItem @click="$emit('edit', data)">
                <Pencil class="size-4 mr-2" /> Edit
              </DropdownMenuItem>
              <DropdownMenuItem class="text-destructive" @click="$emit('delete', data)">
                <Trash2 class="size-4 mr-2" /> Delete
              </DropdownMenuItem>
            </DropdownMenuContent>
          </DropdownMenu>
        </div>
      </div>
      <CardTitle class="text-xl">{{ data.category.name }}</CardTitle>
      <CardDescription v-if="data.percentage > 80" class="text-destructive">
        {{
          data.percentage > 100
            ? 'Your expenses exceed the budget limit'
            : data.percentage === 100
              ? 'Your expenses at the budget limit'
              : 'Your expenses near the budget limit'
        }}
      </CardDescription>
    </CardHeader>
    <CardContent class="flex flex-col gap-2">
      <div class="flex items-end justify-between">
        <span class="text-2xl font-bold">
          {{ currencyFormatter(data.spent) }}
          <span class="text-sm font-normal">/{{ currencyFormatter(data.limit_amount) }}</span>
        </span>
        <span class="font-semibold">{{ data.percentage }}%</span>
      </div>
      <Progress :model-value="data.percentage" />
    </CardContent>
  </Card>
</template>
