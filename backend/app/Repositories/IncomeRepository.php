<?php

use App\Models\Income;

class IncomeRepository
{
    public function getAll()
    {
        return Income::all();
    }

    public function findById($id)
    {
        return Income::findOrFail($id);
    }

    public function create(array $data)
    {
        return Income::create($data);
    }

    public function update($id, array $data)
    {
        $category = Income::findOrFail($id);
        $category->update($data);
        return $category;
    }

    public function delete($id)
    {
        $category = Income::findOrFail($id);
        return $category->delete();
    }
}
