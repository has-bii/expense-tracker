<?php

use App\Models\Budget;

class BudgetRepository
{
    public function getAll()
    {
        return Budget::all();
    }

    public function findById($id)
    {
        return Budget::findOrFail($id);
    }

    public function create(array $data)
    {
        return Budget::create($data);
    }

    public function update($id, array $data)
    {
        $category = Budget::findOrFail($id);
        $category->update($data);
        return $category;
    }

    public function delete($id)
    {
        $category = Budget::findOrFail($id);
        return $category->delete();
    }
}
