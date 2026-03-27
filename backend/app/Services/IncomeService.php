<?php

namespace App\Services;

use App\Models\Income;
use Illuminate\Database\Eloquent\Collection;

class IncomeService
{
    public function getAll(string $userId): Collection
    {
        $data = Income::where('user_id', $userId)->get();

        return $data;
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
