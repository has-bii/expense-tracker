<?php

namespace App\Services;

use App\Models\Expense;
use Illuminate\Database\Eloquent\Collection;

class ExpenseService
{
    public function getAll(string $userId): Collection
    {
        $data = Expense::where('user_id', $userId)->get();

        return $data;
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
