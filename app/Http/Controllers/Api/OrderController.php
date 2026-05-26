<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Http\Resources\ShipmentResource;
use App\Models\FileStorage;
use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    public function index(): JsonResponse
    {
        $orders = Order::where('user_id', auth()->id())
            ->with(['orderItems', 'payment', 'shipment'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return response()->json([
            'data' => OrderResource::collection($orders),
            'meta' => [
                'current_page' => $orders->currentPage(),
                'last_page' => $orders->lastPage(),
                'per_page' => $orders->perPage(),
                'total' => $orders->total(),
            ],
        ]);
    }

    public function show(int $id): JsonResponse
    {
        $order = Order::where('user_id', auth()->id())
            ->with(['orderItems', 'payment.paymentAccount', 'payment.proofFile', 'shipment.trackingLogs'])
            ->findOrFail($id);

        return response()->json(new OrderResource($order));
    }

    public function uploadPayment(Request $request, int $id): JsonResponse
    {
        $order = Order::where('user_id', auth()->id())
            ->where('status', 1)
            ->findOrFail($id);

        $validated = Validator::make($request->all(), [
            'payment_account_id' => 'required|exists:payment_accounts,id',
            'proof' => 'required|image|mimes:jpg,png,webp|max:2048',
        ])->validate();

        $path = $request->file('proof')->store('payments', 'public');
        $fileStorage = FileStorage::create(['link' => $path]);

        $existingPayment = $order->payment;

        if ($existingPayment && $existingPayment->status === 3) {
            $existingPayment->update([
                'payment_account_id' => $validated['payment_account_id'],
                'proof_path' => $fileStorage->id,
                'amount' => $order->total,
                'status' => 1,
                'rejected_by' => null,
                'rejected_reason' => null,
                'rejected_at' => null,
            ]);
        } else {
            $order->payment()->create([
                'payment_account_id' => $validated['payment_account_id'],
                'proof_path' => $fileStorage->id,
                'amount' => $order->total,
                'status' => 1,
            ]);
        }

        return response()->json(['message' => 'Bukti pembayaran berhasil diupload.']);
    }

    public function shipment(int $id): JsonResponse
    {
        $order = Order::where('user_id', auth()->id())
            ->with('shipment.trackingLogs')
            ->findOrFail($id);

        if (! $order->shipment) {
            return response()->json(['message' => 'Belum ada data pengiriman.'], 404);
        }

        return response()->json(new ShipmentResource($order->shipment));
    }
}
