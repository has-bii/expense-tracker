<script setup lang="ts">
import { Button, type ButtonVariants } from '@/components/ui/button'
import {
  Dialog,
  DialogContent,
  DialogDescription,
  DialogHeader,
  DialogTitle,
  DialogTrigger,
} from '@/components/ui/dialog'
import DatePicker from '@/components/DatePicker.vue'
import { Check, Loader2, Plus } from 'lucide-vue-next'
import { Field, FieldError, FieldGroup, FieldLabel } from '@/components/ui/field'
import { Input } from '@/components/ui/input'

import { computed, ref, toRef } from 'vue'
import { useCreateIncomeForm } from '../api/upsert-income'
import {
  InputGroup,
  InputGroupAddon,
  InputGroupInput,
  InputGroupText,
} from '@/components/ui/input-group'
import { Textarea } from '@/components/ui/textarea'
import type { Income } from '../types'

// Props
const props = withDefaults(
  defineProps<{
    oldValue?: Pick<Income, 'id' | 'amount' | 'source' | 'description' | 'income_date'>
    type?: 'create' | 'update'
    open?: boolean
    variant?: ButtonVariants['variant']
    size?: ButtonVariants['size']
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
  get: () => (isUpdate.value ? (props.open ?? false) : internalOpen.value),
  set: (val) => {
    if (isUpdate.value) {
      emit('update:open', val)
    } else {
      internalOpen.value = val
    }
  },
})

// Form state
const { form, mutation } = useCreateIncomeForm({
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
      <Button :size="size ? size : 'sm'" :variant="variant ? variant : 'default'"
        >Add Income <Plus
      /></Button>
    </DialogTrigger>
    <DialogContent>
      <DialogHeader>
        <DialogTitle>{{ isUpdate ? 'Update income' : 'Add new income' }}</DialogTitle>
        <DialogDescription>
          {{
            isUpdate
              ? 'Edit the details of this income'
              : 'Record a new revenue event to your account'
          }}
        </DialogDescription>
      </DialogHeader>
      <form @submit.prevent="form.handleSubmit">
        <FieldGroup>
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

          <!-- Source -->
          <form.Field name="source">
            <template v-slot="{ field }">
              <Field :data-invalid="isInvalid(field)">
                <FieldLabel :for="field.name">Source</FieldLabel>
                <Input
                  :id="field.name"
                  :name="field.name"
                  :model-value="field.state.value"
                  :aria-invalid="isInvalid(field)"
                  @blur="field.handleBlur"
                  @input="field.handleChange($event.target.value)"
                  placeholder="e.g., Salary"
                  autocomplete="off"
                />
                <FieldError v-if="isInvalid(field)" :errors="field.state.meta.errors" />
              </Field>
            </template>
          </form.Field>

          <!-- Income date -->
          <form.Field name="income_date">
            <template v-slot="{ field }">
              <Field :data-invalid="isInvalid(field)">
                <FieldLabel :for="field.name">Income Date</FieldLabel>
                <DatePicker
                  :model-value="field.state.value"
                  modal
                  @update:model-value="(val) => field.handleChange(val!)"
                />
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
                  placeholder="Optinal notes about this transaction.."
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
