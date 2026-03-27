<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateExpenseRequest;
use App\Http\Requests\UpdateExpenseRequest;
use App\Services\ExpenseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    public function __construct(
        private ExpenseService $service
    ) {}

    public function index(Request $request): JsonResponse
    {
        $data = $this->service->getAll($request->user()->id);

        return response()->json([
            'success' => true,
            'message' => 'ok',
            'data' => $data
        ]);
    }

    public function detail(Request $request, string $id): JsonResponse
    {
        $data = $this->service->getById($request->user()->id, $id);

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
            'message' => $data['name'] . ' has been added successfully',
            'data'    => $data
        ]);
    }

    public function update(UpdateExpenseRequest $request, string $id): JsonResponse
    {
        $input = $request->validated();

        $data = $this->service->update($request->user()->id, $id, $input);

        return response()->json([
            'success' => true,
            'message' => $data['name'] . ' has been updated successfully',
            'data'    => $data
        ]);
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
