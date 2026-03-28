<script setup lang="ts">
import { useBreadcrumbStore } from '@/stores/breadcrumb'
import { onBeforeMount } from 'vue'

import ExpenseTable from '../components/ExpenseTable.vue'
import ExpenseUpsertDialog from '../components/ExpenseUpsertDialog.vue'

const breadcrumb = useBreadcrumbStore()
onBeforeMount(() => {
  breadcrumb.setNavs([{ name: 'Expense Overview' }])
})
</script>

<template>
  <div class="flex items-center justify-between">
    <h1 class="text-2xl font-bold">Expense Overview</h1>
    <ExpenseUpsertDialog />
  </div>
  <ExpenseTable />
</template>
