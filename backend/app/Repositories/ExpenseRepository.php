<?php

use App\Models\Expense;

class ExpenseRepository
{
    public function getAll()
    {
        return Expense::all();
    }

    public function findById($id)
    {
        return Expense::findOrFail($id);
    }

    public function create(array $data)
    {
        return Expense::create($data);
    }

    public function update($id, array $data)
    {
        $category = Expense::findOrFail($id);
        $category->update($data);
        return $category;
    }

    public function delete($id)
    {
        $category = Expense::findOrFail($id);
        return $category->delete();
    }
}
