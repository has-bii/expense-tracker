<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Services\CategoryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function __construct(
        private CategoryService $categoryService
    ) {}

    public function index(Request $request): JsonResponse
    {
        $categories = $this->categoryService->getAll($request->user()->id);

        return response()->json([
            'success' => true,
            'message' => 'ok',
            'data' => $categories
        ]);
    }

    public function store(StoreCategoryRequest $request): JsonResponse
    {
        $data = $request->validated();

        $category = $this->categoryService->create($request->user()->id, $data);

        return response()->json([
            'success' => true,
            'message' => $category['name'] . ' has been added successfully',
            'data'    => $category
        ], 201);
    }

    public function update(UpdateCategoryRequest $request, string $id): JsonResponse
    {
        $data = $request->validated();

        $category = $this->categoryService->update($request->user()->id, $id, $data);

        return response()->json([
            'success' => true,
            'message' => $category['name'] . ' has been updated successfully',
            'data'    => $category
        ], 201);
    }

    public function delete(Request $request, string $id): JsonResponse
    {
        $category = $this->categoryService->delete($request->user()->id, $id);

        return response()->json([
            'success' => true,
            'message' => $category['name'] . ' has been deleted successfully',
            'data' => $category
        ]);
    }
}
