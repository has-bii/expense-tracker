<script setup lang="ts">
import { useBreadcrumbStore } from '@/stores/breadcrumb'
import { onBeforeMount } from 'vue'

import IncomeTable from '../components/IncomeTable.vue'
import IncomeUpsertDialog from '../components/IncomeUpsertDialog.vue'

const breadcrumb = useBreadcrumbStore()
onBeforeMount(() => {
  breadcrumb.setNavs([{ name: 'Income Overview' }])
})
</script>

<template>
  <div class="flex items-center justify-between">
    <h1 class="text-2xl font-bold">Income Overview</h1>
    <IncomeUpsertDialog />
  </div>
  <IncomeTable />
</template>
