<script setup lang="ts">
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card'
import AuthTemplate from '../template/AuthTemplate.vue'
import { Field, FieldDescription, FieldError, FieldGroup, FieldLabel } from '@/components/ui/field'
import {
  InputGroup,
  InputGroupAddon,
  InputGroupInput,
  InputGroupPassword,
} from '@/components/ui/input-group'
import { AlertCircleIcon, Loader2, Mail } from 'lucide-vue-next'
import { RouterLink, useRoute, useRouter } from 'vue-router'
import { onBeforeMount, ref } from 'vue'
import { Button } from '@/components/ui/button'
import { useForm } from '@tanstack/vue-form'
import { Alert, AlertDescription, AlertTitle } from '@/components/ui/alert'
import api from '@/lib/api'
import type { Response } from '@/types/response'
import type { User } from '@/types/user'
import { parseAxiosError } from '@/utils/parse-axios-error'
import { resetPasswordSchema } from '../validation/resetPassword'

const router = useRouter()

const route = useRoute()
const q = {
  email: route.query.email as string | undefined,
  token: route.query.token as string | undefined,
}

const info = ref<{ success: boolean; message: string } | null>(null)

const form = useForm({
  defaultValues: {
    password: '',
    confirmPassword: '',
  },
  validators: {
    onSubmit: resetPasswordSchema,
  },
  onSubmit: async ({ value: { password } }) => {
    info.value = null
    try {
      const { data: responseData } = await api.post<Response<{ user: User; token: string }>>(
        '/auth/reset-password',
        { password, email: q.email, token: q.token },
      )

      if (!responseData.success) {
        info.value = {
          success: false,
          message: responseData.message,
        }
        form.reset()
        return
      }

      info.value = {
        success: true,
        message: 'Reset password successfully.',
      }

      form.reset()
    } catch (e) {
      const { message } = parseAxiosError(e)

      info.value = {
        success: false,
        message,
      }
      form.reset()
    } finally {
      form.reset()
    }
  },
})

// eslint-disable-next-line @typescript-eslint/no-explicit-any
const isInvalid = (field: any) => field.state.meta.isTouched && !field.state.meta.isValid

onBeforeMount(() => {
  if (!q.email || !q.token) {
    router.replace('/login')
  }
})
</script>

<template>
  <AuthTemplate>
    <Card class="w-full max-w-sm">
      <CardHeader>
        <CardTitle>Reset Password</CardTitle>
        <CardDescription>Please enter your new password</CardDescription>
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
            <Field>
              <FieldLabel for="email">Email</FieldLabel>
              <InputGroup>
                <InputGroupInput
                  id="email"
                  name="email"
                  :model-value="q.email"
                  placeholder="m@example.com"
                  autocomplete="off"
                  readOnly
                />
                <InputGroupAddon>
                  <Mail />
                </InputGroupAddon>
              </InputGroup>
            </Field>

            <!-- Password -->
            <form.Field name="password">
              <template v-slot="{ field }">
                <Field :data-invalid="isInvalid(field)">
                  <FieldLabel :for="field.name">Password</FieldLabel>
                  <InputGroupPassword
                    :id="field.name"
                    :name="field.name"
                    :model-value="field.state.value"
                    :aria-invalid="isInvalid(field)"
                    @blur="field.handleBlur"
                    @input="field.handleChange($event.target.value)"
                    autocomplete="off"
                    placeholder="New password"
                  />
                  <FieldError v-if="isInvalid(field)" :errors="field.state.meta.errors" />
                </Field>
              </template>
            </form.Field>

            <!-- Confirm Password -->
            <form.Field name="confirmPassword">
              <template v-slot="{ field }">
                <Field :data-invalid="isInvalid(field)">
                  <FieldLabel :for="field.name">Confirm Password</FieldLabel>
                  <InputGroupPassword
                    :id="field.name"
                    :name="field.name"
                    :model-value="field.state.value"
                    :aria-invalid="isInvalid(field)"
                    @blur="field.handleBlur"
                    @input="field.handleChange($event.target.value)"
                    autocomplete="off"
                    placeholder="Confirm password"
                  />
                  <FieldError v-if="isInvalid(field)" :errors="field.state.meta.errors" />
                </Field>
              </template>
            </form.Field>

            <!-- Submit Button -->
            <form.Subscribe>
              <template v-slot="{ canSubmit, isSubmitting }">
                <Field>
                  <Button :disabled="!canSubmit || isSubmitting">
                    Reset Password
                    <Loader2 v-if="isSubmitting" class="animate-spin" />
                  </Button>
                  <FieldDescription class="text-center">
                    Back to
                    <RouterLink class="text-sm no-underline hover:underline" to="/login"
                      >Login</RouterLink
                    >
                  </FieldDescription>
                </Field>
              </template>
            </form.Subscribe>
          </FieldGroup>
        </form>
      </CardContent>
    </Card>
  </AuthTemplate>
</template>
