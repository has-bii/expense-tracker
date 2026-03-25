<script setup lang="ts">
import { Button } from '@/components/ui/button'
import { useAuthStore } from '@/stores/auth'
import { Loader2 } from 'lucide-vue-next'

const auth = useAuthStore()
</script>

<template>
  <section class="w-screen min-h-dvh flex flex-col items-center justify-center">
    <h1 class="text-center text-4xl font-bold">Welcome</h1>
    <Button @click="auth.logout" :disabled="auth.isLoggingOut">
      Logout
      <Loader2 v-if="auth.isLoggingOut" class="animate-spin" />
    </Button>
  </section>
</template>
