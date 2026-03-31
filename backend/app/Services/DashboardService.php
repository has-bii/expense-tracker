<?php

namespace App\Services;

use App\Models\Expense;
use App\Models\Income;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class DashboardService
{
    public function getSummary(string $userId): array
    {
        return Cache::remember("dashboard_summary_{$userId}", 600, function () use ($userId) {
            $currentStart = Carbon::now()->startOfMonth()->toDateTimeString();
            $currentEnd = Carbon::now()->endOfMonth()->toDateTimeString();
            $prevStart = Carbon::now()->subMonth()->startOfMonth()->toDateTimeString();
            $prevEnd = Carbon::now()->subMonth()->endOfMonth()->toDateTimeString();

            $expenseTotals = Expense::where('user_id', $userId)
                ->selectRaw("
                    COALESCE(SUM(amount), 0) as all_time_total,
                    COALESCE(SUM(CASE WHEN expense_date BETWEEN ? AND ? THEN amount ELSE 0 END), 0) as current_total,
                    COALESCE(SUM(CASE WHEN expense_date BETWEEN ? AND ? THEN amount ELSE 0 END), 0) as prev_total
                ", [$currentStart, $currentEnd, $prevStart, $prevEnd])
                ->first();

            $incomeTotals = Income::where('user_id', $userId)
                ->selectRaw("
                    COALESCE(SUM(amount), 0) as all_time_total,
                    COALESCE(SUM(CASE WHEN income_date BETWEEN ? AND ? THEN amount ELSE 0 END), 0) as current_total,
                    COALESCE(SUM(CASE WHEN income_date BETWEEN ? AND ? THEN amount ELSE 0 END), 0) as prev_total
                ", [$currentStart, $currentEnd, $prevStart, $prevEnd])
                ->first();

            $topCategories = Expense::where('expenses.user_id', $userId)
                ->join('categories', 'expenses.category_id', '=', 'categories.id')
                ->selectRaw('expenses.category_id, categories.name as category_name, categories.icon, COALESCE(SUM(expenses.amount), 0) as total')
                ->whereBetween('expense_date', [$currentStart, $currentEnd])
                ->groupBy('expenses.category_id', 'categories.name', 'categories.icon')
                ->orderByDesc('total')
                ->limit(5)
                ->get();

            $topSources = Income::where('user_id', $userId)
                ->selectRaw('source, COALESCE(SUM(amount), 0) as total')
                ->whereBetween('income_date', [$currentStart, $currentEnd])
                ->groupBy('source')
                ->orderByDesc('total')
                ->limit(5)
                ->get();

            $allTimeIncome = (float) $incomeTotals->all_time_total;
            $allTimeExpense = (float) $expenseTotals->all_time_total;
            $currentIncome = (float) $incomeTotals->current_total;
            $currentExpense = (float) $expenseTotals->current_total;
            $prevIncome = (float) $incomeTotals->prev_total;
            $prevExpense = (float) $expenseTotals->prev_total;

            $currentNet = $currentIncome - $currentExpense;
            $prevNet = $prevIncome - $prevExpense;

            return [
                'all_time_net_balance' => $allTimeIncome - $allTimeExpense,
                'all_time_income' => $allTimeIncome,
                'all_time_expense' => $allTimeExpense,
                'net_balance' => $currentNet,
                'total_income' => $currentIncome,
                'total_expense' => $currentExpense,
                'prev_net_balance' => $prevNet,
                'percentage_change' => $prevNet == 0 ? null : round(($currentNet - $prevNet) / abs($prevNet) * 100, 2),
                'top_categories' => $topCategories->toArray(),
                'top_sources' => $topSources->toArray(),
            ];
        });
    }

    public static function clearCache(string $userId): void
    {
        Cache::forget("dashboard_summary_{$userId}");
    }
}
