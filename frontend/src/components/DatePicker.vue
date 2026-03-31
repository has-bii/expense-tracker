<script setup lang="ts">
import { Button } from './ui/button'
import { Calendar } from './ui/calendar'
import { Popover, PopoverContent, PopoverTrigger } from './ui/popover'
import { cn } from '@/lib/utils'
import { format } from 'date-fns'
import { parseDate, type DateValue } from '@internationalized/date'
import { CalendarIcon } from 'lucide-vue-next'
import { ref, watch, type Ref } from 'vue'

const model = defineModel<Date>()

defineProps<{
  placeholder?: string
  modal?: boolean
}>()

// Stable ref to prevent Calendar from resetting displayed month on re-render
const calendarValue = ref<DateValue>() as Ref<DateValue | undefined>
watch(
  model,
  (date) => {
    calendarValue.value = date ? parseDate(format(date, 'yyyy-MM-dd')) : undefined
  },
  { immediate: true },
)
</script>

<template>
  <Popover :modal="modal">
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
        :model-value="calendarValue"
        :initial-focus="true"
        layout="month-and-year"
        @update:model-value="
          (v) => {
            calendarValue = v
            model = v ? new Date(v.toString()) : undefined
          }
        "
      />
    </PopoverContent>
  </Popover>
</template>
