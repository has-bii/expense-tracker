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
import { CalendarIcon, Check, Loader2, Plus } from 'lucide-vue-next'
import { Field, FieldError, FieldGroup, FieldLabel } from '@/components/ui/field'
import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from '@/components/ui/select'

import { computed, ref, toRef } from 'vue'
import { useCreateExpenseForm } from '../api/upsert-expense'

import { format } from 'date-fns'
import { Popover, PopoverContent, PopoverTrigger } from '@/components/ui/popover'
import { Calendar } from '@/components/ui/calendar'
import { parseDate } from '@internationalized/date'
import {
  InputGroup,
  InputGroupAddon,
  InputGroupInput,
  InputGroupText,
} from '@/components/ui/input-group'
import { Textarea } from '@/components/ui/textarea'
import type { Expense } from '../types'
import { getCategoriesQueryOption } from '@/features/category/api/get-categories'
import { useQuery } from '@tanstack/vue-query'

// Props
const props = withDefaults(
  defineProps<{
    oldValue?: Pick<Expense, 'id' | 'amount' | 'category_id' | 'description' | 'expense_date'>
    type?: 'create' | 'update'
    open?: boolean
  }>(),
  { type: 'create' },
)

const emit = defineEmits<{
  'update:open': [value: boolean]
}>()

const isUpdate = computed(() => props.type === 'update')

// Dialog state
const internalOpen = ref(false)
const isOpen = computed({
  get: () => (isUpdate.value ? props.open ?? false : internalOpen.value),
  set: (val) => {
    if (isUpdate.value) {
      emit('update:open', val)
    } else {
      internalOpen.value = val
    }
  },
})

// Categories
const { data: categories } = useQuery(getCategoriesQueryOption())

// Form state
const { form, mutation } = useCreateExpenseForm({
  onSuccess: () => {
    isOpen.value = false
  },
  oldValue: toRef(() => props.oldValue),
})

// eslint-disable-next-line @typescript-eslint/no-explicit-any
const isInvalid = (field: any) => field.state.meta.isTouched && !field.state.meta.isValid
const { isPending } = mutation
</script>

<template>
  <Dialog v-model:open="isOpen">
    <DialogTrigger v-if="!isUpdate" as-child>
      <Button size="sm">Add Expense <Plus /></Button>
    </DialogTrigger>
    <DialogContent>
      <DialogHeader>
        <DialogTitle>{{ isUpdate ? 'Update expense' : 'Add new expense' }}</DialogTitle>
        <DialogDescription>
          {{
            isUpdate
              ? 'Edit the details of this expense'
              : 'Record a new expense to your account'
          }}
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
                  :model-value="field.state.value"
                  @update:model-value="(val) => field.handleChange(String(val))"
                >
                  <SelectTrigger class="w-full">
                    <SelectValue placeholder="Select a category" />
                  </SelectTrigger>
                  <SelectContent>
                    <SelectItem v-for="c in categories" :key="c.id" :value="c.id">
                      {{ c.icon }} {{ c.name }}
                    </SelectItem>
                  </SelectContent>
                </Select>
                <FieldError v-if="isInvalid(field)" :errors="field.state.meta.errors" />
              </Field>
            </template>
          </form.Field>

          <!-- Amount -->
          <form.Field name="amount">
            <template v-slot="{ field }">
              <Field :data-invalid="isInvalid(field)">
                <FieldLabel :for="field.name">Amount</FieldLabel>
                <InputGroup>
                  <InputGroupInput
                    :id="field.name"
                    :name="field.name"
                    :model-value="field.state.value"
                    :aria-invalid="isInvalid(field)"
                    placeholder="0.00"
                    autocomplete="off"
                    @blur="field.handleBlur"
                    @input="field.handleChange(parseFloat($event.target.value))"
                  />
                  <InputGroupAddon>
                    <InputGroupText>Rp</InputGroupText>
                  </InputGroupAddon>
                </InputGroup>
                <FieldError v-if="isInvalid(field)" :errors="field.state.meta.errors" />
              </Field>
            </template>
          </form.Field>

          <!-- Expense date -->
          <form.Field name="expense_date">
            <template v-slot="{ field }">
              <Field :data-invalid="isInvalid(field)">
                <FieldLabel :for="field.name">Expense Date</FieldLabel>
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

          <!-- Description -->
          <form.Field name="description">
            <template v-slot="{ field }">
              <Field :data-invalid="isInvalid(field)">
                <FieldLabel :for="field.name">Description</FieldLabel>
                <Textarea
                  :id="field.name"
                  :name="field.name"
                  :model-value="field.state.value"
                  :aria-invalid="isInvalid(field)"
                  @blur="field.handleBlur"
                  @input="field.handleChange($event.target.value)"
                  placeholder="Optional notes about this transaction.."
                  autocomplete="off"
                  class="min-h-[120px]"
                />
                <FieldError v-if="isInvalid(field)" :errors="field.state.meta.errors" />
              </Field>
            </template>
          </form.Field>

          <!-- Submit button -->
          <form.Subscribe>
            <template v-slot="{ canSubmit }">
              <Field>
                <Button :disabled="!canSubmit || isPending">
                  {{ isUpdate ? 'Update' : 'Add' }}
                  <Loader2 v-if="isPending" class="animate-spin" />
                  <Check v-else-if="isUpdate" />
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
