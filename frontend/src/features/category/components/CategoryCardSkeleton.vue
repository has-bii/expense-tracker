<script setup lang="ts">
import { Card, CardHeader } from '@/components/ui/card'
import { Skeleton } from '@/components/ui/skeleton'
</script>

<template>
  <Card v-for="id in 4" :key="id">
    <CardHeader>
      <Skeleton class="w-1/3 h-6" />
      <Skeleton class="w-full h-6" />
    </CardHeader>
  </Card>
</template>
