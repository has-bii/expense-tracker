<?php

namespace App\Services;

use App\Models\Expense;
use Illuminate\Pagination\CursorPaginator;

class ExpenseService
{
    public function getAll(string $userId, array $queries): CursorPaginator
    {
        $data = Expense::where('user_id', $userId)
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
        return Expense::create($data);
    }

    public function update(string $userId, string $id, array $input): Expense
    {
        $data = $this->getById($userId, $id);
        $data->update($input);
        return $data;
    }

    public function delete(string $userId, string $id): Expense
    {
        $data = $this->getById($userId, $id);
        $data->delete();
        return $data;
    }
}
