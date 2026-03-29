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
import { Check, Loader2, Plus } from 'lucide-vue-next'
import { Field, FieldError, FieldGroup, FieldLabel } from '@/components/ui/field'
import { Input } from '@/components/ui/input'

import { computed, ref, toRef } from 'vue'
import { useCreateBudgetForm } from '../api/upsert-budget'
import { useQuery } from '@tanstack/vue-query'
import { getCategoriesQueryOption } from '@/features/category/api/get-categories'
import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from '@/components/ui/select'
import type { Budget, Period } from '../types'

const periods: Period[] = ['daily', 'weekly', 'monthly']

// Props
const props = withDefaults(
  defineProps<{
    oldValue?: Pick<Budget, 'id' | 'category_id' | 'limit_amount' | 'period'>
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

// Form state
const { form, mutation } = useCreateBudgetForm({
  onSuccess: () => {
    isOpen.value = false
  },
  oldValue: toRef(() => props.oldValue),
})
// eslint-disable-next-line @typescript-eslint/no-explicit-any
const isInvalid = (field: any) => field.state.meta.isTouched && !field.state.meta.isValid
const { isPending } = mutation

// Get categories
const { data: categories = [] } = useQuery({ ...getCategoriesQueryOption() })
</script>

<template>
  <Dialog v-model:open="isOpen">
    <DialogTrigger v-if="!isUpdate" as-child>
      <Button size="sm">Add Budget <Plus /></Button>
    </DialogTrigger>
    <DialogContent>
      <DialogHeader>
        <DialogTitle>{{ isUpdate ? 'Update budget' : 'Add new budget' }}</DialogTitle>
        <DialogDescription>
          {{
            isUpdate
              ? 'Edit the limit amount and period of this budget'
              : 'To create a new budget, enter category, limit amount, period, and start date'
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
                  :name="field.name"
                  :model-value="field.state.value"
                  :disabled="isUpdate"
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
