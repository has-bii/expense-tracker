<script setup lang="ts">
import TablePagination from '@/components/TablePagination.vue'
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card'
import {
  Table,
  TableBody,
  TableCell,
  TableHead,
  TableHeader,
  TableRow,
} from '@/components/ui/table'
import { useQuery } from '@tanstack/vue-query'
import { computed } from 'vue'
import { getIncomesQueryOption } from '../api/get-incomes'
import { format } from 'date-fns'
import { currencyFormatter } from '@/utils/currency'
import { Skeleton } from '@/components/ui/skeleton'
import { Button } from '@/components/ui/button'
import { Pencil, Trash2 } from 'lucide-vue-next'
import { useQueryPagination } from '@/hooks/use-query-pagination'
import IncomeUpsertDialog from './IncomeUpsertDialog.vue'
import IncomeDeleteDialog from './IncomeDeleteDialog.vue'
import { useIncomeDelete } from '../hooks/use-income-delete'
import type { Income } from '../types'
import { ref } from 'vue'
import { useQuerySort } from '@/hooks/use-query-sort'
import TableHeadWithSort from '@/components/TableHeadWithSort.vue'

const { sorts, updateSort } = useQuerySort()
const { pagination } = useQueryPagination()

const selectedIncome = ref<
  Pick<Income, 'id' | 'amount' | 'source' | 'description' | 'income_date'> | undefined
>()
const isEditOpen = ref(false)

const openEdit = (
  income: Pick<Income, 'id' | 'amount' | 'source' | 'description' | 'income_date'>,
) => {
  selectedIncome.value = income
  isEditOpen.value = true
}

const onEditOpenChange = (val: boolean) => {
  isEditOpen.value = val
  if (!val) {
    selectedIncome.value = undefined
  }
}

const incomeDelete = useIncomeDelete()

const queryOpts = computed(() =>
  getIncomesQueryOption({ ...pagination.value, ...sorts.value }),
)
const { data } = useQuery(queryOpts)
</script>

<template>
  <Card>
    <CardHeader>
      <div class="flex items-center justify-between">
        <CardTitle>Recent Records</CardTitle>
      </div>
    </CardHeader>
    <CardContent class="px-0">
      <Table class="w-full border-y">
        <TableHeader class="bg-accent">
          <TableRow>
            <TableHead class="font-semibold">source & description</TableHead>
            <TableHeadWithSort
              @sort="updateSort"
              sort_by="income_date"
              :sorts="sorts"
              class="font-semibold text-center"
              >date</TableHeadWithSort
            >
            <TableHeadWithSort
              @sort="updateSort"
              sort_by="amount"
              :sorts="sorts"
              class="font-semibold text-right"
              >amount</TableHeadWithSort
            >
            <TableHead><span class="sr-only">action</span></TableHead>
          </TableRow>
        </TableHeader>
        <TableBody>
          <template v-if="data">
            <template v-if="data.data.length > 0">
              <TableRow v-for="d in data.data" :key="d.id">
                <TableCell>
                  <div class="flex flex-col">
                    <span class="font-semibold">{{ d.source }}</span>
                    <span v-if="d.description" class="text-xs text-muted-foreground">{{
                      d.description
                    }}</span>
                  </div>
                </TableCell>
                <TableCell class="text-center">{{ format(d.income_date, 'PP') }}</TableCell>
                <TableCell class="font-semibold text-right text-lg">
                  {{ currencyFormatter(d.amount) }}
                </TableCell>
                <TableCell class="w-[120px]">
                  <div class="flex items-center gap-2 justify-end">
                    <Button size="icon-sm" variant="secondary" @click="openEdit(d)">
                      <Pencil />
                    </Button>
                    <Button size="icon-sm" variant="destructive" @click="incomeDelete.select(d)">
                      <Trash2 />
                    </Button>
                  </div>
                </TableCell>
              </TableRow>
            </template>
            <template v-else>
              <TableRow>
                <TableCell colspan="4">
                  <p class="text-center text-muted-foreground">There is no income recorded yet</p>
                </TableCell>
              </TableRow>
            </template>
          </template>
          <template v-else>
            <TableRow v-for="i in 10" :key="i">
              <TableCell colspan="4">
                <Skeleton class="h-8 w-full" />
              </TableCell>
            </TableRow>
          </template>
        </TableBody>
      </Table>
      <template v-if="data">
        <TablePagination :pagination="data" />
      </template>
    </CardContent>
  </Card>
  <IncomeUpsertDialog
    type="update"
    :old-value="selectedIncome"
    :open="isEditOpen"
    @update:open="onEditOpenChange"
  />
  <IncomeDeleteDialog
    :is-open="incomeDelete.isOpen"
    :income="incomeDelete.income"
    :close="incomeDelete.close"
  />
</template>
