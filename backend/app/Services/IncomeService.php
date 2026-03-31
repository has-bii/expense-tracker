<?php

namespace App\Services;

use App\Models\Income;
use Carbon\Carbon;
use Illuminate\Pagination\CursorPaginator;

class IncomeService
{
    public function getAll(string $userId, array $queries): CursorPaginator
    {
        $data = Income::where('user_id', $userId)
            ->when(isset($queries['income_date_from']), fn($q) => $q->where('income_date', '>=', $queries['income_date_from']))
            ->when(isset($queries['income_date_to']), fn($q) => $q->where('income_date', '<=', $queries['income_date_to']))
            ->when(isset($queries['source']), function ($q) use ($queries) {
                $escaped = str_replace(['%', '_'], ['\\%', '\\_'], $queries['source']);
                return $q->where('source', 'ilike', '%' . $escaped . '%');
            })
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

    public function calculateMonthly(string $userId)
    {
        $current = Income::where('user_id', $userId)
            ->selectRaw('COALESCE(SUM(amount),0) as total')
            ->whereBetween('income_date', [
                Carbon::now()->startOfMonth(),
                Carbon::now()->endOfMonth()
            ])
            ->first();

        $prev = Income::where('user_id', $userId)
            ->selectRaw('COALESCE(SUM(amount),0) as prev_total')
            ->whereBetween('income_date', [
                Carbon::now()->startOfMonth()->subMonth(),
                Carbon::now()->endOfMonth()->subMonth()
            ])
            ->first();

        $source = Income::where('user_id', $userId)
            ->selectRaw('source, COALESCE(SUM(amount),0) as total')
            ->whereBetween('income_date', [
                Carbon::now()->startOfMonth(),
                Carbon::now()->endOfMonth(),
            ])
            ->groupBy('source')
            ->orderByDesc('total')
            ->get();

        $data = [
            'prev_total' => $prev['prev_total'],
            'current_total' => $current['total'],
            'percentage' => $prev['prev_total'] == 0 ? null : round($current['total'] / $prev['prev_total'] * 100, 2),
            'sources' => $source
        ];

        return $data;
    }
}
