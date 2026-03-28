<script setup lang="ts">
import { Button } from '@/components/ui/button'
import {
  Dialog,
  DialogContent,
  DialogDescription,
  DialogHeader,
  DialogTitle,
  DialogTrigger,
} from '@/components/ui/dialog'
import { CalendarIcon, Loader2, Plus } from 'lucide-vue-next'
import { Field, FieldError, FieldGroup, FieldLabel } from '@/components/ui/field'
import { Input } from '@/components/ui/input'

import { ref } from 'vue'
import { useCreateBudgetForm } from '../api/create-budget'
import { useQuery } from '@tanstack/vue-query'
import { getCategoriesQueryOption } from '@/features/category/api/get-categories'
import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from '@/components/ui/select'
import type { Period } from '../types'

import { format } from 'date-fns'
import { Popover, PopoverContent, PopoverTrigger } from '@/components/ui/popover'
import { Calendar } from '@/components/ui/calendar'
import { parseDate } from '@internationalized/date'

const periods: Period[] = ['daily', 'weekly', 'monthly']

// Dialog state
const isOpen = ref(false)
const toggleOpen = () => {
  isOpen.value = !isOpen.value
}

// Form state
const { form, mutation } = useCreateBudgetForm({
  onSuccess: () => {
    isOpen.value = false
  },
})
// eslint-disable-next-line @typescript-eslint/no-explicit-any
const isInvalid = (field: any) => field.state.meta.isTouched && !field.state.meta.isValid
const { isPending } = mutation

// Get categories
const { data: categories = [] } = useQuery({ ...getCategoriesQueryOption() })
</script>

<template>
  <Dialog :open="isOpen" @update:open="toggleOpen">
    <DialogTrigger as-child>
      <Button size="sm">Add Budget <Plus /></Button>
    </DialogTrigger>
    <DialogContent>
      <DialogHeader>
        <DialogTitle>Add new budget</DialogTitle>
        <DialogDescription>
          To create a new budget, enter category, limit amount, period, and start date
        </DialogDescription>
      </DialogHeader>
      <form @submit.prevent="form.handleSubmit">
        <FieldGroup>
          <!-- Category -->
          <form.Field name="category_id">
            <template v-slot="{ field }">
              <Field :data-invalid="isInvalid(field)">
                <FieldLabel :for="field.name">Category</FieldLabel>
                <Select
                  :name="field.name"
                  :model-value="field.state.value"
                  @update:model-value="
                    (value) => {
                      field.handleChange(value?.toString() ?? '')
                    }
                  "
                >
                  <SelectTrigger :id="field.name" :aria-invalid="isInvalid(field)">
                    <SelectValue placeholder="Select" />
                  </SelectTrigger>
                  <SelectContent position="item-aligned">
                    <SelectItem
                      v-for="category in categories"
                      :key="category.id"
                      :value="category.id"
                    >
                      {{ category.name }}
                    </SelectItem>
                  </SelectContent>
                </Select>
                <FieldError v-if="isInvalid(field)" :errors="field.state.meta.errors" />
              </Field>
            </template>
          </form.Field>

          <!-- Limit amount -->
          <form.Field name="limit_amount">
            <template v-slot="{ field }">
              <Field :data-invalid="isInvalid(field)">
                <FieldLabel :for="field.name">Limit Amount</FieldLabel>
                <Input
                  :id="field.name"
                  :name="field.name"
                  :model-value="field.state.value"
                  :aria-invalid="isInvalid(field)"
                  @blur="field.handleBlur"
                  @input="field.handleChange(Number($event.target.value))"
                  placeholder="Rp 3000.000"
                  autocomplete="off"
                />
                <FieldError v-if="isInvalid(field)" :errors="field.state.meta.errors" />
              </Field>
            </template>
          </form.Field>

          <!-- Period -->
          <form.Field name="period">
            <template v-slot="{ field }">
              <Field :data-invalid="isInvalid(field)">
                <FieldLabel :for="field.name">Period</FieldLabel>
                <Select
                  :name="field.name"
                  :model-value="field.state.value"
                  @update:model-value="
                    (value) => {
                      field.handleChange(value?.toString() as Period)
                    }
                  "
                >
                  <SelectTrigger :id="field.name" :aria-invalid="isInvalid(field)">
                    <SelectValue placeholder="Select" />
                  </SelectTrigger>
                  <SelectContent position="item-aligned">
                    <SelectItem v-for="period in periods" :key="period" :value="period">
                      {{ period }}
                    </SelectItem>
                  </SelectContent>
                </Select>
                <FieldError v-if="isInvalid(field)" :errors="field.state.meta.errors" />
              </Field>
            </template>
          </form.Field>

          <!-- Start date -->
          <form.Field name="start_date">
            <template v-slot="{ field }">
              <Field :data-invalid="isInvalid(field)">
                <FieldLabel :for="field.name">Start date</FieldLabel>
                <Popover modal>
                  <PopoverTrigger as-child>
                    <Button variant="outline" class="justify-start text-left font-normal">
                      <CalendarIcon class="mr-2 h-4 w-4" />
                      {{ format(field.state.value, 'PP') }}
                    </Button>
                  </PopoverTrigger>
                  <PopoverContent class="w-auto p-0">
                    <Calendar
                      :model-value="parseDate(format(field.state.value, 'y-MM-dd'))"
                      :initial-focus="true"
                      layout="month-and-year"
                      @update:model-value="
                        (value) => field.handleChange(new Date(value!.toString()))
                      "
                    />
                  </PopoverContent>
                </Popover>
                <FieldError v-if="isInvalid(field)" :errors="field.state.meta.errors" />
              </Field>
            </template>
          </form.Field>

          <!-- Submit button -->
          <form.Subscribe>
            <template v-slot="{ canSubmit }">
              <Field>
                <Button :disabled="!canSubmit || isPending">
                  Add
                  <Loader2 v-if="isPending" class="animate-spin" />
                  <Plus v-else />
                </Button>
              </Field>
            </template>
          </form.Subscribe>
        </FieldGroup>
      </form>
    </DialogContent>
  </Dialog>
</template>
