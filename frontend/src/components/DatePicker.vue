<script setup lang="ts">
import { Button } from './ui/button'
import { Calendar } from './ui/calendar'
import { Popover, PopoverContent, PopoverTrigger } from './ui/popover'
import { cn } from '@/lib/utils'
import { format } from 'date-fns'
import { parseDate } from '@internationalized/date'
import { CalendarIcon } from 'lucide-vue-next'

const model = defineModel<Date>()

defineProps<{
  placeholder?: string
}>()
</script>

<template>
  <Popover>
    <PopoverTrigger as-child>
      <Button
        variant="outline"
        :class="
          cn(
            'flex-1 shrink-0 justify-start text-left font-normal',
            !model && 'text-muted-foreground',
          )
        "
      >
        <CalendarIcon class="mr-2 h-4 w-4" />

        {{ model ? format(model, 'PP') : placeholder || 'Pick a date' }}
      </Button>
    </PopoverTrigger>
    <PopoverContent class="w-auto p-0">
      <Calendar
        :model-value="model ? parseDate(format(model, 'yyyy-MM-dd')) : undefined"
        :initial-focus="true"
        layout="month-and-year"
        @update:model-value="
          (v) => (model = v ? new Date(v.toString()) : undefined)
        "
      />
    </PopoverContent>
  </Popover>
</template>
