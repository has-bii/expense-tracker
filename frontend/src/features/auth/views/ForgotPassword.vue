<script setup lang="ts">
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card'
import AuthTemplate from '../template/AuthTemplate.vue'
import { Field, FieldDescription, FieldError, FieldGroup, FieldLabel } from '@/components/ui/field'
import { InputGroup, InputGroupAddon, InputGroupInput } from '@/components/ui/input-group'
import { AlertCircleIcon, Loader2, Mail } from 'lucide-vue-next'
import { RouterLink } from 'vue-router'
import { ref } from 'vue'
import { Button } from '@/components/ui/button'
import { useForm } from '@tanstack/vue-form'
import { Alert, AlertDescription, AlertTitle } from '@/components/ui/alert'
import { forgotPasswordSchema } from '../validation/forgotPassword'
import api from '@/lib/api'
import type { Response } from '@/types/response'
import { parseAxiosError } from '@/utils/parse-axios-error'

const info = ref<{ success: boolean; message: string } | null>(null)

const form = useForm({
  defaultValues: {
    email: '',
  },
  validators: {
    onSubmit: forgotPasswordSchema,
  },
  onSubmit: async ({ value: input }) => {
    info.value = null
    try {
      const { data: responseData } = await api.post<Response>('/auth/forgot-password', input)

      if (responseData.success) {
        info.value = {
          success: true,
          message: responseData.message,
        }
        return
      }

      info.value = {
        success: false,
        message: responseData.message,
      }
    } catch (e) {
      const { message } = parseAxiosError(e)

      info.value = {
        success: false,
        message,
      }
    } finally {
      form.reset()
    }
  },
})

// eslint-disable-next-line @typescript-eslint/no-explicit-any
const isInvalid = (field: any) => field.state.meta.isTouched && !field.state.meta.isValid
</script>

<template>
  <AuthTemplate>
    <Card class="w-full max-w-sm">
      <CardHeader>
        <CardTitle>Login to your account</CardTitle>
        <CardDescription>Enter your email below to login to your account</CardDescription>
      </CardHeader>
      <CardContent>
        <form @submit.prevent="form.handleSubmit">
          <FieldGroup>
            <!-- Error Display -->
            <Alert v-if="info !== null" :variant="info ? 'default' : 'destructive'">
              <AlertCircleIcon />
              <AlertTitle>{{ info.success ? 'Succeed' : 'Failed' }}</AlertTitle>
              <AlertDescription>
                {{ info.message }}
              </AlertDescription>
            </Alert>

            <!-- Email -->
            <form.Field name="email">
              <template v-slot="{ field }">
                <Field :data-invalid="isInvalid(field)">
                  <FieldLabel :for="Field.name">Email</FieldLabel>
                  <InputGroup>
                    <InputGroupInput
                      :id="field.name"
                      :name="field.name"
                      :model-value="field.state.value"
                      :aria-invalid="isInvalid(field)"
                      placeholder="m@example.com"
                      autocomplete="off"
                      @blur="field.handleBlur"
                      @input="field.handleChange($event.target.value)"
                    />
                    <InputGroupAddon>
                      <Mail />
                    </InputGroupAddon>
                  </InputGroup>
                  <FieldError v-if="isInvalid(field)" :errors="field.state.meta.errors" />
                </Field>
              </template>
            </form.Field>

            <!-- Submit Button -->
            <Field>
              <form.Subscribe>
                <template v-slot="{ canSubmit, isSubmitting }">
                  <Button :disabled="!canSubmit || isSubmitting">
                    Send Reset Code
                    <Loader2 v-if="isSubmitting" class="animate-spin" />
                  </Button>
                </template>
              </form.Subscribe>
              <FieldDescription class="text-center">
                Back to
                <RouterLink class="text-sm no-underline hover:underline" to="/login"
                  >Login</RouterLink
                >
              </FieldDescription>
            </Field>
          </FieldGroup>
        </form>
      </CardContent>
    </Card>
  </AuthTemplate>
</template>
