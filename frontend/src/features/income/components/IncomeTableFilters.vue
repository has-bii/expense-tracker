<script setup lang="ts">
import DatePicker from '@/components/DatePicker.vue'
import { Button } from '@/components/ui/button'
import { Field, FieldGroup, FieldLabel } from '@/components/ui/field'
import { Input } from '@/components/ui/input'
import { InputGroup, InputGroupAddon, InputGroupInput } from '@/components/ui/input-group'
import { Popover, PopoverContent, PopoverTrigger } from '@/components/ui/popover'
import { format } from 'date-fns'
import { Minus, Search, SlidersHorizontal, X } from 'lucide-vue-next'
import { computed, ref, watch } from 'vue'
import { useRoute, useRouter } from 'vue-router'

const route = useRoute()
const router = useRouter()
let debounceTimer: ReturnType<typeof setTimeout> | null = null

const source = ref((route.query.source as string) || '')
const amount_from = ref(Number(route.query.amount_from as string) || '')
const amount_to = ref(Number(route.query.amount_to as string) || '')
const income_date_from = ref(
  route.query.income_date_from ? new Date(route.query.income_date_from as string) : undefined,
)
const income_date_to = ref(
  route.query.income_date_to ? new Date(route.query.income_date_to as string) : undefined,
)

const formatDate = (date?: Date) => (date ? format(date, 'yyyy-MM-dd') : undefined)

const hasActiveFilters = computed(
  () =>
    !!source.value ||
    !!amount_from.value ||
    !!amount_to.value ||
    !!income_date_from.value ||
    !!income_date_to.value,
)

const clearFilters = () => {
  source.value = ''
  amount_from.value = ''
  amount_to.value = ''
  income_date_from.value = undefined
  income_date_to.value = undefined
}

const filters = computed(() => ({
  source,
  amount_from,
  amount_to,
  income_date_from,
  income_date_to,
}))

const updateQuery = () => {
  router.push({
    query: {
      ...route.query,
      cursor: undefined,
      source: source.value ?? undefined,
      amount_from: amount_from.value ?? undefined,
      amount_to: amount_to.value ?? undefined,
      income_date_from: formatDate(income_date_from.value),
      income_date_to: formatDate(income_date_to.value),
    },
  })
}

watch(
  filters,
  () => {
    if (debounceTimer) clearTimeout(debounceTimer)

    debounceTimer = setTimeout(() => updateQuery(), 500)
  },
  { deep: true },
)
</script>

<template>
  <div class="flex items-center gap-3 w-fit">
    <InputGroup>
      <InputGroupInput v-model:model-value="source" placeholder="Search income..." />
      <InputGroupAddon>
        <Search />
      </InputGroupAddon>
    </InputGroup>
    <Button v-if="hasActiveFilters" variant="outline" @click="clearFilters">
      <X class="size-4" />
      Clear filters
    </Button>
    <Popover>
      <PopoverTrigger as-child>
        <Button size="icon">
          <SlidersHorizontal />
        </Button>
      </PopoverTrigger>
      <PopoverContent align="end" class="min-w-md">
        <FieldGroup class="gap-4">
          <Field>
            <FieldLabel>Amount</FieldLabel>
            <div class="flex items-center gap-2 w-full">
              <Input v-model:model-value="amount_from" type="number" placeholder="1.000.000" />
              <Minus class="size-4" />
              <Input v-model:model-value="amount_to" type="number" placeholder="10.000.000" />
            </div>
          </Field>
          <Field>
            <FieldLabel>Date</FieldLabel>
            <div class="flex items-center gap-2 w-full">
              <DatePicker v-model="income_date_from" />
              <Minus class="size-4 shrink-0" />
              <DatePicker v-model="income_date_to" />
            </div>
          </Field>
        </FieldGroup>
      </PopoverContent>
    </Popover>
  </div>
</template>
