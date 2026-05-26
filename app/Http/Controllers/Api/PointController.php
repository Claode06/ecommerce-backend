<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PointResource;
use Illuminate\Http\JsonResponse;

class PointController extends Controller
{
    public function index(): JsonResponse
    {
        $user = auth()->user();
        $transactions = $user->pointTransactions()
            ->with('order')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return response()->json([
            'balance' => (int) ($user->userPoint?->balance ?? 0),
            'data' => PointResource::collection($transactions),
            'meta' => [
                'current_page' => $transactions->currentPage(),
                'last_page' => $transactions->lastPage(),
                'per_page' => $transactions->perPage(),
                'total' => $transactions->total(),
            ],
        ]);
    }
}
