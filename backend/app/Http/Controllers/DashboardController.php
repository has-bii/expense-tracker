<?php

namespace App\Http\Controllers;

use App\Services\DashboardService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct(
        private DashboardService $service
    ) {}

    public function summary(Request $request): JsonResponse
    {
        $data = $this->service->getSummary($request->user()->id);

        return response()->json([
            'success' => true,
            'message' => 'ok',
            'data' => $data,
        ]);
    }
}
