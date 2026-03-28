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
import { getExpensesQueryOption } from '../api/get-expenses'
import { format } from 'date-fns'
import { currencyFormatter } from '@/utils/currency'
import { Skeleton } from '@/components/ui/skeleton'
import { Button } from '@/components/ui/button'
import { Pencil, Trash2 } from 'lucide-vue-next'
import { useQueryPagination } from '@/hooks/use-query-pagination'
import ExpenseUpsertDialog from './ExpenseUpsertDialog.vue'
import ExpenseDeleteDialog from './ExpenseDeleteDialog.vue'
import { useExpenseDelete } from '../hooks/use-expense-delete'
import type { Expense } from '../types'
import { ref } from 'vue'
import { useQuerySort } from '@/hooks/use-query-sort'
import TableHeadWithSort from '@/components/TableHeadWithSort.vue'
import ExpenseTableFilters from './ExpenseTableFilters.vue'
import { useRoute } from 'vue-router'
import { getCategoriesQueryOption } from '@/features/category/api/get-categories'

// Filters
const route = useRoute()
const category = computed(() => route.query.category as string | undefined)
const amount_from = computed(() => route.query.amount_from as string | undefined)
const amount_to = computed(() => route.query.amount_to as string | undefined)
const expense_date_from = computed(() => route.query.expense_date_from as string | undefined)
const expense_date_to = computed(() => route.query.expense_date_to as string | undefined)

const { sorts, updateSort } = useQuerySort()
const { pagination } = useQueryPagination()

const selectedExpense = ref<
  Pick<Expense, 'id' | 'amount' | 'category_id' | 'description' | 'expense_date'> | undefined
>()
const isEditOpen = ref(false)

const openEdit = (
  expense: Pick<Expense, 'id' | 'amount' | 'category_id' | 'description' | 'expense_date'>,
) => {
  selectedExpense.value = expense
  isEditOpen.value = true
}

const onEditOpenChange = (val: boolean) => {
  isEditOpen.value = val
  if (!val) {
    selectedExpense.value = undefined
  }
}

const expenseDelete = useExpenseDelete()

// Categories lookup
const { data: categories } = useQuery(getCategoriesQueryOption())
const categoriesById = computed(() => {
  const map = new Map<string, { name: string; icon: string }>()
  if (categories.value) {
    for (const c of categories.value) {
      map.set(c.id, { name: c.name, icon: c.icon })
    }
  }
  return map
})

const queryOpts = computed(() =>
  getExpensesQueryOption({
    ...pagination.value,
    ...sorts.value,
    category: category.value,
    amount_from: amount_from.value,
    amount_to: amount_to.value,
    expense_date_from: expense_date_from.value,
    expense_date_to: expense_date_to.value,
  }),
)
const { data } = useQuery(queryOpts)
</script>

<template>
  <Card>
    <CardHeader>
      <div class="flex items-center justify-between">
        <CardTitle>Recent Records</CardTitle>

        <ExpenseTableFilters />
      </div>
    </CardHeader>
    <CardContent class="px-0">
      <Table class="w-full border-y">
        <TableHeader class="bg-accent">
          <TableRow>
            <TableHead class="font-semibold">category & description</TableHead>
            <TableHeadWithSort
              @sort="updateSort"
              sort_by="expense_date"
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
                    <span class="font-semibold">
                      {{ categoriesById.get(d.category_id)?.icon }}
                      {{ categoriesById.get(d.category_id)?.name ?? 'Unknown' }}
                    </span>
                    <span v-if="d.description" class="text-xs text-muted-foreground">{{
                      d.description
                    }}</span>
                  </div>
                </TableCell>
                <TableCell class="text-center">{{ format(d.expense_date, 'PP') }}</TableCell>
                <TableCell class="font-semibold text-right text-lg">
                  {{ currencyFormatter(d.amount) }}
                </TableCell>
                <TableCell class="w-[120px]">
                  <div class="flex items-center gap-2 justify-end">
                    <Button size="icon-sm" variant="secondary" @click="openEdit(d)">
                      <Pencil />
                    </Button>
                    <Button size="icon-sm" variant="destructive" @click="expenseDelete.select(d)">
                      <Trash2 />
                    </Button>
                  </div>
                </TableCell>
              </TableRow>
            </template>
            <template v-else>
              <TableRow>
                <TableCell colspan="4">
                  <p class="text-center text-muted-foreground">There is no expense recorded yet</p>
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
  <ExpenseUpsertDialog
    type="update"
    :old-value="selectedExpense"
    :open="isEditOpen"
    @update:open="onEditOpenChange"
  />
  <ExpenseDeleteDialog
    :is-open="expenseDelete.isOpen"
    :expense="expenseDelete.expense"
    :close="expenseDelete.close"
  />
</template>
