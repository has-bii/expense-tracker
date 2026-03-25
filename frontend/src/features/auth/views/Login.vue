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
import { ref } from 'vue'
import { Button } from '@/components/ui/button'
import { useForm } from '@tanstack/vue-form'
import { loginSchema } from '../validation/login'
import { Alert, AlertDescription, AlertTitle } from '@/components/ui/alert'
import { useAuthStore } from '@/stores/auth'
import { parseQuery } from '@/utils/parse-query'

const router = useRouter()

const route = useRoute()
const redirectPath = parseQuery(route.query.redirect, '/')

const auth = useAuthStore()

const error = ref('')

const form = useForm({
  defaultValues: {
    email: '',
    password: '',
  },
  validators: {
    onSubmit: loginSchema,
  },
  onSubmit: async ({ value }) => {
    error.value = ''

    const { success, message } = await auth.login(value)

    form.reset()

    if (!success) {
      error.value = message
      return
    }

    router.replace(redirectPath)
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
            <Alert v-if="error" variant="destructive">
              <AlertCircleIcon />
              <AlertTitle>Failed to login.</AlertTitle>
              <AlertDescription>
                {{ error }}
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

            <!-- Password -->
            <form.Field name="password">
              <template v-slot="{ field }">
                <Field :data-invalid="isInvalid(field)">
                  <div class="flex items-center justify-between">
                    <FieldLabel :for="field.name">Password</FieldLabel>
                    <RouterLink class="text-sm no-underline hover:underline" to="/forgot-password"
                      >Forgot password?</RouterLink
                    >
                  </div>
                  <InputGroupPassword
                    :id="field.name"
                    :name="field.name"
                    :model-value="field.state.value"
                    :aria-invalid="isInvalid(field)"
                    @blur="field.handleBlur"
                    @input="field.handleChange($event.target.value)"
                    autocomplete="off"
                    placeholder="Your password"
                  />
                  <FieldError v-if="isInvalid(field)" :errors="field.state.meta.errors" />
                </Field>
              </template>
            </form.Field>

            <!-- Login Button -->
            <form.Subscribe>
              <template v-slot="{ canSubmit, isSubmitting }">
                <Field>
                  <Button :disabled="!canSubmit || isSubmitting">
                    Login
                    <Loader2 v-if="isSubmitting" class="animate-spin" />
                  </Button>
                  <FieldDescription class="text-center">
                    Don't have an account?
                    <RouterLink class="text-sm no-underline hover:underline" to="/register"
                      >Register</RouterLink
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
