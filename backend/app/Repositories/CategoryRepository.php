<?php

namespace App\Repositories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;

class CategoryRepository
{
    public function all(string $userId): Collection
    {
        return Category::where('user_id', $userId)->get();
    }

    public function findById(string $userId, string $id): Category
    {
        return Category::where('id', $id)->where('user_id', $userId)->firstOrFail();
    }

    public function create(array $data): Category
    {
        return Category::create($data);
    }

    public function update(string $userId, string $id, array $data): Category
    {
        $category = $this->findById($userId, $id);
        $category->update($data);
        return $category;
    }

    public function delete(string $userId, string $id): Category
    {
        $category = $this->findById($userId, $id);
        $category->delete();
        return $category;
    }
}
