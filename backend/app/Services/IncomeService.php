<?php

namespace App\Services;

use App\Models\Income;
use Illuminate\Pagination\CursorPaginator;

class IncomeService
{
    public function getAll(string $userId, array $queries): CursorPaginator
    {
        $data = Income::where('user_id', $userId)
            ->when(isset($queries['income_date_from']), fn($q) => $q->where('income_date', '>=', $queries['income_date_from']))
            ->when(isset($queries['income_date_to']), fn($q) => $q->where('income_date', '<=', $queries['income_date_to']))
            ->when(isset($queries['source']), fn($q) => $q->where('source', 'ilike', '%' . $queries['source'] . '%'))
            ->when(isset($queries['amount_from']), fn($q) => $q->where('amount', '>=', $queries['amount_from']))
            ->when(isset($queries['amount_to']), fn($q) => $q->where('amount', '<=', $queries['amount_to']))
            ->orderBy($queries['sort_by'], $queries['order']);

        return $data->cursorPaginate(
            perPage: $queries['limit'],
            columns: ['id', 'amount', 'source', 'description', 'income_date', 'created_at'],
            cursor: $queries['cursor']
        );
    }

    public function getById(string $userId, string $id): Income
    {
        $data = Income::where('user_id', $userId)->findOrFail($id);

        return $data;
    }

    public function create(string $userId, array $data): Income
    {
        $data['user_id'] = $userId;
        return Income::create($data);
    }

    public function update(string $userId, string $id, array $input): Income
    {
        $data = $this->getById($userId, $id);
        $data->update($input);
        return $data;
    }

    public function delete(string $userId, string $id): Income
    {
        $data = $this->getById($userId, $id);
        $data->delete();
        return $data;
    }
}
