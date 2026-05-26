<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PaymentAccount;
use Illuminate\Http\JsonResponse;

class PaymentAccountController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json([
            'data' => PaymentAccount::where('is_active', true)->get(),
        ]);
    }
}
