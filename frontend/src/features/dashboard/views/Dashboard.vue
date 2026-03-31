<script setup lang="ts">
import { useBreadcrumbStore } from '@/stores/breadcrumb'
import { onBeforeMount, computed } from 'vue'
import { useQuery } from '@tanstack/vue-query'
import { getExpenseDetailQueryOption } from '@/features/expense/api/get-expense-detail'
import { getIncomeDetailQueryOption } from '@/features/income/api/get-income-detail'
import { getBudgetsQueryOption } from '@/features/budget/api/get-budgets'
import { getCategoriesQueryOption } from '@/features/category/api/get-categories'
import DashboardSummaryCard from '../components/DashboardSummaryCard.vue'
import DashboardBudgetAlerts from '../components/DashboardBudgetAlerts.vue'
import DashboardBudgetOverview from '../components/DashboardBudgetOverview.vue'
import DashboardExpenseBreakdown from '../components/DashboardExpenseBreakdown.vue'
import DashboardIncomeBreakdown from '../components/DashboardIncomeBreakdown.vue'
import IncomeUpsertDialog from '@/features/income/components/IncomeUpsertDialog.vue'
import ExpenseUpsertDialog from '@/features/expense/components/ExpenseUpsertDialog.vue'

const breadcrumb = useBreadcrumbStore()

onBeforeMount(() => {
  breadcrumb.setNavs([])
})

const { data: expenseData, isLoading: expenseLoading } = useQuery({
  ...getExpenseDetailQueryOption(),
})
const { data: incomeData, isLoading: incomeLoading } = useQuery({
  ...getIncomeDetailQueryOption(),
})
const { data: budgets, isLoading: budgetLoading } = useQuery({
  ...getBudgetsQueryOption(),
})
const { data: categories } = useQuery({
  ...getCategoriesQueryOption(),
})

const summaryLoading = computed(() => expenseLoading.value || incomeLoading.value)

const incomeTotal = computed(() => (incomeData.value ? Number(incomeData.value.current_total) : 0))
const expenseTotal = computed(() =>
  expenseData.value ? Number(expenseData.value.current_total) : 0,
)
const netBalance = computed(() => incomeTotal.value - expenseTotal.value)

const incomeTrend = computed(() =>
  incomeData.value ? Number((incomeData.value.percentage - 100).toFixed(1)) : 0,
)
const expenseTrend = computed(() =>
  expenseData.value ? Number((expenseData.value.percentage - 100).toFixed(1)) : 0,
)
const netTrend = computed(() => {
  if (!incomeData.value || !expenseData.value) return 0
  const prevNet = Number(incomeData.value.prev_total) - Number(expenseData.value.prev_total)
  if (prevNet === 0) return 0
  return Number((((netBalance.value - prevNet) / Math.abs(prevNet)) * 100).toFixed(1))
})
</script>

<template>
  <div class="flex flex-col lg:flex-row lg:justify-between lg:items-end gap-4 mb-6">
    <div class="space-y-0.5">
      <h1 class="text-4xl font-bold">Dashboard Overview</h1>
      <p class="text-muted-foreground">Refining your financial growth trajectory.</p>
    </div>

    <div class="inline-flex items-center gap-2">
      <IncomeUpsertDialog type="create" size="lg" variant="secondary" />
      <ExpenseUpsertDialog type="create" size="lg" variant="default" />
    </div>
  </div>
  <div class="flex flex-col gap-4">
    <!-- Summary Cards -->
    <div class="grid auto-rows-min gap-4 md:grid-cols-3">
      <DashboardSummaryCard
        title="Total Income"
        :amount="incomeTotal"
        :trend-percentage="incomeTrend"
        trend-type="up-good"
        :loading="summaryLoading"
      />
      <DashboardSummaryCard
        title="Total Expenses"
        :amount="expenseTotal"
        :trend-percentage="expenseTrend"
        trend-type="up-bad"
        :loading="summaryLoading"
      />
      <DashboardSummaryCard
        title="Net Balance"
        :amount="netBalance"
        :trend-percentage="netTrend"
        trend-type="up-good"
        :loading="summaryLoading"
      />
    </div>

    <!-- Budgets -->
    <div class="grid auto-rows-min gap-4 md:grid-cols-2">
      <DashboardBudgetAlerts :budgets="budgets ?? []" :loading="budgetLoading" />
      <DashboardBudgetOverview :budgets="budgets ?? []" :loading="budgetLoading" />
    </div>

    <!-- Breakdowns -->
    <div class="grid auto-rows-min gap-4 md:grid-cols-2">
      <DashboardExpenseBreakdown
        :detail="expenseData?.detail ?? []"
        :categories="categories ?? []"
        :current-total="expenseTotal"
        :loading="summaryLoading"
      />
      <DashboardIncomeBreakdown
        :sources="incomeData?.sources ?? []"
        :current-total="incomeTotal"
        :loading="summaryLoading"
      />
    </div>
  </div>
</template>
