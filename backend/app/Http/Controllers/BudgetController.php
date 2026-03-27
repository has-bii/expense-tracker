<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateBudgetRequest;
use App\Http\Requests\UpdateBudgetRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Services\BudgetService;

class BudgetController extends Controller
{
    public function __construct(
        private BudgetService $budgetService
    ) {}

    public function index(Request $request): JsonResponse
    {
        $data = $this->budgetService->getAll($request->user()->id);

        return response()->json([
            'success' => true,
            'message' => 'ok',
            'data' => $data
        ]);
    }

    public function detail(Request $request, string $id): JsonResponse
    {
        $data = $this->budgetService->getById($request->user()->id, $id);

        return response()->json([
            'success' => true,
            'message' => 'ok',
            'data' => $data
        ]);
    }

    public function create(CreateBudgetRequest $request): JsonResponse
    {
        $input = $request->validated();

        $data = $this->budgetService->create($request->user()->id, $input);

        return response()->json([
            'success' => true,
            'message' => $data['name'] . ' has been added successfully',
            'data'    => $data
        ]);
    }

    public function update(UpdateBudgetRequest $request, string $id): JsonResponse
    {
        $input = $request->validated();

        $data = $this->budgetService->update($request->user()->id, $id, $input);

        return response()->json([
            'success' => true,
            'message' => $data['name'] . ' has been updated successfully',
            'data'    => $data
        ]);
    }

    public function delete(Request $request, string $id): JsonResponse
    {
        $data = $this->budgetService->delete($request->user()->id, $id);

        return response()->json([
            'success' => true,
            'message' => $data['name'] . ' has been deleted successfully',
            'data' => $data
        ]);
    }
}
