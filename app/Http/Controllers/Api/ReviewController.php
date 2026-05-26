<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ReviewResource;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Review;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ReviewController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $validated = Validator::make($request->all(), [
            'order_item_id' => 'required|exists:order_items,id',
            'rating' => 'required|integer|min:1|max:5',
            'reason' => 'nullable|string|max:1000',
        ])->validate();

        $orderItem = OrderItem::findOrFail($validated['order_item_id']);

        $order = Order::where('id', $orderItem->order_id)
            ->where('user_id', auth()->id())
            ->where('status', 5)
            ->first();

        if (! $order) {
            return response()->json(['message' => 'Anda hanya bisa mereview pesanan yang sudah delivered.'], 403);
        }

        $existing = Review::where('order_item_id', $orderItem->id)->where('user_id', auth()->id())->exists();

        if ($existing) {
            return response()->json(['message' => 'Item ini sudah direview.'], 409);
        }

        $review = Review::create([
            'user_id' => auth()->id(),
            'product_id' => $orderItem->productVariant->product_id,
            'order_item_id' => $orderItem->id,
            'rating' => $validated['rating'],
            'reason' => $validated['reason'] ?? null,
            'is_visible' => true,
        ]);

        return response()->json([
            'message' => 'Ulasan berhasil dikirim.',
            'data' => new ReviewResource($review),
        ], 201);
    }
}
