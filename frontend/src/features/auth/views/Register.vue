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
import { AlertCircleIcon, IdCard, Loader2, Mail } from 'lucide-vue-next'
import { RouterLink, useRouter } from 'vue-router'
import { ref } from 'vue'
import { Button } from '@/components/ui/button'
import { useForm } from '@tanstack/vue-form'
import { Alert, AlertDescription, AlertTitle } from '@/components/ui/alert'
import { registerSchema } from '../validation/register'
import { useAuthStore } from '@/stores/auth'
import api from '@/lib/api'
import type { Response } from '@/types/response'
import type { User } from '@/types/user'
import { parseAxiosError } from '@/utils/parse-axios-error'
import { token } from '@/utils/token'

const router = useRouter()

const auth = useAuthStore()

const error = ref('')

const form = useForm({
  defaultValues: {
    name: '',
    email: '',
    password: '',
    confirmPassword: '',
  },
  validators: {
    onSubmit: registerSchema,
  },
  onSubmit: async ({ value: { name, email, password } }) => {
    error.value = ''
    try {
      const { data: responseData } = await api.post<Response<{ user: User; token: string }>>(
        '/auth/register',
        { name, email, password },
      )

      if (!responseData.success) {
        error.value = responseData.message
        return
      }

      token.setToken(responseData.data.token)

      const { success, message } = await auth.getUser()

      if (!success) {
        error.value = message
        return
      }

      router.replace('/')
    } catch (e) {
      const { message } = parseAxiosError(e)

      error.value = message
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
        <CardTitle>Register new account</CardTitle>
        <CardDescription>Create new account to use our application</CardDescription>
      </CardHeader>
      <CardContent>
        <form @submit.prevent="form.handleSubmit">
          <FieldGroup>
            <!-- Error Display -->
            <Alert v-if="error" variant="destructive">
              <AlertCircleIcon />
              <AlertTitle>Failed to login.</AlertTitle>
              <AlertDescription>
                {{ error }}
              </AlertDescription>
            </Alert>

            <!-- Fullname -->
            <form.Field name="name">
              <template v-slot="{ field }">
                <Field :data-invalid="isInvalid(field)">
                  <FieldLabel :for="Field.name">Full Name</FieldLabel>
                  <InputGroup>
                    <InputGroupInput
                      :id="field.name"
                      :name="field.name"
                      :model-value="field.state.value"
                      :aria-invalid="isInvalid(field)"
                      placeholder="Alex"
                      autocomplete="off"
                      @blur="field.handleBlur"
                      @input="field.handleChange($event.target.value)"
                    />
                    <InputGroupAddon>
                      <IdCard />
                    </InputGroupAddon>
                  </InputGroup>
                  <FieldError v-if="isInvalid(field)" :errors="field.state.meta.errors" />
                </Field>
              </template>
            </form.Field>

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
                      @blur="field.handleBlur"
                      @input="field.handleChange($event.target.value)"
                      placeholder="m@example.com"
                      autocomplete="off"
                    />
                    <InputGroupAddon>
                      <Mail />
                    </InputGroupAddon>
                  </InputGroup>
                  <FieldError v-if="isInvalid(field)" :errors="field.state.meta.errors" />
                </Field>
              </template>
            </form.Field>

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
                    :aria-invalid="isInvalid"
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
                  <Button :disabled="!canSubmit || isSubmitting"
                    >Register

                    <Loader2 v-if="isSubmitting" class="animate-spin" />
                  </Button>
                  <FieldDescription class="text-center">
                    Already have an account?
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
