<script setup lang="ts">
import { Card, CardDescription, CardHeader, CardTitle } from '@/components/ui/card'
import type { Category } from '../types'
import { format } from 'date-fns'
import { id } from 'date-fns/locale'
import { Button } from '@/components/ui/button'
import { Trash2 } from 'lucide-vue-next'

const props = defineProps<{
  data: Category
  update: (data: Category) => void
  delete: (data: Category) => void
}>()
</script>

<template>
  <Card role="button" @click="() => props.update(data)">
    <CardHeader>
      <div class="flex items-center justify-between">
        <CardTitle>{{ data.icon }} {{ data.name }}</CardTitle>
        <Button size="icon" variant="destructive" @click.stop="() => props.delete(data)">
          <Trash2 />
        </Button>
      </div>
      <CardDescription>{{ format(data.created_at, 'PP', { locale: id }) }}</CardDescription>
    </CardHeader>
  </Card>
</template>
