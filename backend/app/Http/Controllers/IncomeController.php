<?php

namespace App\Http\Controllers;

use App\Helpers\QueryPagination;
use App\Http\Requests\CreateIncomeRequest;
use App\Http\Requests\UpdateIncomeRequest;
use App\Services\IncomeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class IncomeController extends Controller
{
    public function __construct(
        private IncomeService $service
    ) {}

    public function index(Request $request): JsonResponse
    {
        // Query
        $queries = QueryPagination::getQueries($request);

        // Filter
        $filters = Validator::make($request->query(), [
            'income_date_from' => ['nullable', 'date'],
            'income_date_to' => ['nullable', 'date'],
            'source' => ['nullable', 'string'],
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

    public function create(CreateIncomeRequest $request): JsonResponse
    {
        $input = $request->validated();

        $data = $this->service->create($request->user()->id, $input);

        return response()->json([
            'success' => true,
            'message' => $data['name'] . ' has been added successfully',
            'data'    => $data
        ], 201);
    }

    public function update(UpdateIncomeRequest $request, string $id): JsonResponse
    {
        $input = $request->validated();

        $data = $this->service->update($request->user()->id, $id, $input);

        return response()->json([
            'success' => true,
            'message' => $data['name'] . ' has been updated successfully',
            'data'    => $data
        ], 201);
    }

    public function delete(Request $request, string $id): JsonResponse
    {
        $data = $this->service->delete($request->user()->id, $id);

        return response()->json([
            'success' => true,
            'message' => $data['name'] . ' has been deleted successfully',
            'data' => $data
        ]);
    }
}
