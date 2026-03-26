<?php

namespace App\Services;

use App\Models\Category;
use App\Repositories\CategoryRepository;
use Illuminate\Database\Eloquent\Collection;

class CategoryService
{
    public function __construct(
        private CategoryRepository $categoryRepository
    ) {}

    public function getAll(string $userId): Collection
    {
        return $this->categoryRepository->all($userId);
    }

    public function getById(string $userId, string $id): Category
    {
        return $this->categoryRepository->findById($userId, $id);
    }

    public function create(string $userId, array $data): Category
    {
        $data['user_id'] = $userId;
        return $this->categoryRepository->create($data);
    }

    public function update(string $userId, string $id, array $data): Category
    {
        return $this->categoryRepository->update($userId, $id, $data);
    }

    public function delete(string $userId, string $id): Category
    {
        return $this->categoryRepository->delete($userId, $id);
    }
}
