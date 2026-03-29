<?php

namespace App\Services;

use App\Models\Budget;
use App\Models\Expense;
use Illuminate\Database\Eloquent\Collection;

class BudgetService
{
    public function getAll(string $userId): Collection
    {
        $data = Budget::where('user_id', $userId)
            ->with('category:id,name,icon')
            ->addSelect(['budgets.*',
                'spent' => Expense::selectRaw('COALESCE(SUM(amount), 0)')
                    ->whereColumn('expenses.user_id', 'budgets.user_id')
                    ->whereColumn('expenses.category_id', 'budgets.category_id')
                    ->whereRaw("expenses.expense_date >= CASE budgets.period
                        WHEN 'daily' THEN CURRENT_DATE
                        WHEN 'weekly' THEN CURRENT_DATE - (EXTRACT(ISODOW FROM CURRENT_DATE)::int - 1)
                        WHEN 'monthly' THEN DATE_TRUNC('month', CURRENT_DATE)::date
                    END")
                    ->whereRaw("expenses.expense_date <= CASE budgets.period
                        WHEN 'daily' THEN CURRENT_DATE
                        WHEN 'weekly' THEN CURRENT_DATE - (EXTRACT(ISODOW FROM CURRENT_DATE)::int - 1) + 6
                        WHEN 'monthly' THEN (DATE_TRUNC('month', CURRENT_DATE) + INTERVAL '1 month' - INTERVAL '1 day')::date
                    END"),
            ])
            ->get()
            ->each(function (Budget $budget) {
                $budget->spent = (float) $budget->spent;
                $budget->percentage = $budget->limit_amount > 0
                    ? round(($budget->spent / $budget->limit_amount) * 100, 2)
                    : 0;
            });

        return $data;
    }

    public function getById(string $userId, string $id): Budget
    {
        $data = Budget::where('user_id', $userId)->with('category:id,name,icon')->findOrFail($id);

        return $data;
    }

    public function create(string $userId, array $data): Budget
    {
        $data['user_id'] = $userId;
        $budget = Budget::create($data);
        $budget->load('category:id,name,icon');
        return $budget;
    }

    public function update(string $userId, string $id, array $input): Budget
    {
        $data = $this->getById($userId, $id);
        $data->update($input);
        $data->load('category:id,name,icon');
        return $data;
    }

    public function delete(string $userId, string $id): Budget
    {
        $data = $this->getById($userId, $id);
        $data->load('category:id,name,icon');
        $data->delete();
        return $data;
    }
}
