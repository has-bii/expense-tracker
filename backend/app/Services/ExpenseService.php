<?php

namespace App\Services;

use App\Models\Expense;
use Carbon\Carbon;
use Illuminate\Pagination\CursorPaginator;

class ExpenseService
{
    public function getAll(string $userId, array $queries): CursorPaginator
    {
        $data = Expense::where('user_id', $userId)
            ->with('category:id,name,icon')
            ->when(isset($queries['expense_date_from']), fn($q) => $q->where('expense_date', '>=', $queries['expense_date_from']))
            ->when(isset($queries['expense_date_to']), fn($q) => $q->where('expense_date', '<=', $queries['expense_date_to']))
            ->when(isset($queries['category']), fn($q) => $q->where('category_id', $queries['category']))
            ->when(isset($queries['amount_from']), fn($q) => $q->where('amount', '>=', $queries['amount_from']))
            ->when(isset($queries['amount_to']), fn($q) => $q->where('amount', '<=', $queries['amount_to']))
            ->orderBy($queries['sort_by'], $queries['order']);

        return $data->cursorPaginate(
            perPage: $queries['limit'],
            columns: ['id', 'category_id', 'amount', 'description', 'expense_date', 'created_at'],
            cursor: $queries['cursor']
        );
    }

    public function getById(string $userId, string $id): Expense
    {
        $data = Expense::where('user_id', $userId)->findOrFail($id);

        return $data;
    }

    public function create(string $userId, array $data): Expense
    {
        $data['user_id'] = $userId;
        $expense = Expense::create($data);
        $expense->load('category:id,name,icon');
        return $expense;
    }

    public function update(string $userId, string $id, array $input): Expense
    {
        $data = $this->getById($userId, $id);
        $data->update($input);
        $data->load('category:id,name,icon');
        return $data;
    }

    public function delete(string $userId, string $id): Expense
    {
        $data = $this->getById($userId, $id);
        $data->load('category:id,name,icon');
        $data->delete();
        return $data;
    }

    public function calculateMonthly(string $userId)
    {
        $current = Expense::where('user_id', $userId)
            ->selectRaw('COALESCE(SUM(amount),0) as total')
            ->whereBetween('expense_date', [
                Carbon::now()->startOfMonth(),
                Carbon::now()->endOfMonth()
            ])
            ->first();

        $prev = Expense::where('user_id', $userId)
            ->selectRaw('COALESCE(SUM(amount),1) as prev_total')
            ->whereBetween('expense_date', [
                Carbon::now()->startOfMonth()->subMonth(),
                Carbon::now()->endOfMonth()->subMonth()
            ])
            ->first();

        $detail = Expense::where('user_id', $userId)
            ->selectRaw('category_id, COALESCE(SUM(amount),1) as total')
            ->whereBetween('expense_date', [
                Carbon::now()->startOfMonth(),
                Carbon::now()->endOfMonth(),
            ])
            ->groupBy('category_id')
            ->orderByDesc('total')
            ->get();

        $data = [
            'prev_total' => $prev['prev_total'],
            'current_total' => $current['total'],
            'percentage' => $current['total'] / $prev['prev_total'] * 100,
            'detail' => $detail
        ];

        return $data;
    }
}
