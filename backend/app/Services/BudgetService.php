<?php

namespace App\Services;

use App\Models\Budget;
use Illuminate\Database\Eloquent\Collection;

class BudgetService
{
    public function getAll(string $userId): Collection
    {
        $data = Budget::where('user_id', $userId)->with('category:id,name,icon')->get();

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
        return Budget::create($data);
    }

    public function update(string $userId, string $id, array $input): Budget
    {
        $data = $this->getById($userId, $id);
        $data->update($input);
        return $data;
    }

    public function delete(string $userId, string $id): Budget
    {
        $data = $this->getById($userId, $id);
        $data->delete();
        return $data;
    }
}
