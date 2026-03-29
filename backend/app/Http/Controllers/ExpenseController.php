<?php

namespace App\Http\Controllers;

use App\Helpers\QueryPagination;
use App\Http\Requests\CreateExpenseRequest;
use App\Http\Requests\UpdateExpenseRequest;
use App\Services\ExpenseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ExpenseController extends Controller
{
    public function __construct(
        private ExpenseService $service
    ) {}

    public function index(Request $request): JsonResponse
    {
        // Query
        $queries = QueryPagination::getQueries($request);

        // Filter
        $filters = Validator::make($request->query(), [
            'expense_date_from' => ['nullable', 'date'],
            'expense_date_to' => ['nullable', 'date'],
            'category' => ['nullable', 'uuid', 'exists:categories,id'],
            'amount_from' => ['nullable', 'decimal:0,2', 'min:0'],
            'amount_to' => ['nullable', 'decimal:0,2', 'min:0'],
        ]);

        $queries_filters = array_merge($queries, $filters->fails() ? [] : $filters->validate());

        $data = $this->service->getAll($request->user()->id, $queries_filters);

        return response()->json([
            'success' => true,
            'message' => 'ok',
            'data' => $data
        ]);
    }

    public function create(CreateExpenseRequest $request): JsonResponse
    {
        $input = $request->validated();

        $data = $this->service->create($request->user()->id, $input);

        return response()->json([
            'success' => true,
            'message' => 'Expense has been added successfully',
            'data'    => $data
        ], 201);
    }

    public function update(UpdateExpenseRequest $request, string $id): JsonResponse
    {
        $input = $request->validated();

        $data = $this->service->update($request->user()->id, $id, $input);

        return response()->json([
            'success' => true,
            'message' => 'Expense has been updated successfully',
            'data'    => $data
        ]);
    }

    public function delete(Request $request, string $id): JsonResponse
    {
        $data = $this->service->delete($request->user()->id, $id);

        return response()->json([
            'success' => true,
            'message' => 'Expense has been deleted successfully',
            'data' => $data
        ]);
    }
}
