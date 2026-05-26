<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\WarehouseResource;
use App\Models\Warehouse;
use Illuminate\Http\JsonResponse;

class WarehouseController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json([
            'data' => WarehouseResource::collection(
                Warehouse::where('is_active', true)->orderBy('name')->get()
            ),
        ]);
    }
}
