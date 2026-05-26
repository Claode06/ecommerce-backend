<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Models\Cart;
use App\Models\FileStorage;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\PointTransaction;
use App\Models\ProductVariant;
use App\Models\UserPoint;
use App\Models\WarehouseStock;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CheckoutController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $user = auth()->user();

        $validated = Validator::make($request->all(), [
            'warehouse_id' => 'required|exists:warehouses,id',
            'shipping_address' => 'required|string',
            'shipping_note' => 'nullable|string',
            'shipping_cost' => 'required|numeric|min:0',
            'point_redeemed' => 'nullable|integer|min:0',
            'note' => 'nullable|string',
        ])->validate();

        $cartItems = Cart::where('user_id', $user->id)->with('productVariant')->get();

        if ($cartItems->isEmpty()) {
            return response()->json(['message' => 'Keranjang belanja kosong.'], 400);
        }

        foreach ($cartItems as $item) {
            if (! $item->productVariant || ! $item->productVariant->is_active) {
                return response()->json([
                    'message' => "Varian '{$item->productVariant?->label}' tidak tersedia.",
                ], 400);
            }
        }

        $pointRedeemed = min((int) ($validated['point_redeemed'] ?? 0), $user->userPoint?->balance ?? 0);
        $pointValueInRupiah = $pointRedeemed;

        return DB::transaction(function () use ($user, $cartItems, $validated, $pointRedeemed, $pointValueInRupiah) {
            $orderNumber = 'ORD-'.date('Y').'-'.strtoupper(substr(uniqid(), -6));

            $subtotal = 0;
            $totalPointsEarned = 0;
            $orderItemData = [];

            foreach ($cartItems as $item) {
                $variant = $item->productVariant;
                $price = (float) $variant->price;

                $promoItem = $variant->promotionItems()
                    ->whereHas('promotion', fn ($q) => $q->active())
                    ->first();

                if ($promoItem) {
                    $price = (float) $promoItem->override_price;
                }

                $itemSubtotal = $price * $item->quantity;
                $subtotal += $itemSubtotal;

                $orderItemData[] = [
                    'product_variant_id' => $variant->id,
                    'promotion_id' => $promoItem?->promotion_id,
                    'product_name' => $variant->product->name,
                    'variant_label' => $variant->label,
                    'unit_price' => $price,
                    'quantity' => $item->quantity,
                    'subtotal' => $itemSubtotal,
                ];

                $pointsEarned = (int) floor($price * $item->quantity / 10000);
                $totalPointsEarned += $pointsEarned;
            }

            $total = $subtotal + (float) $validated['shipping_cost'] - $pointValueInRupiah;

            if ($total < 0) {
                $total = 0;
            }

            $order = Order::create([
                'user_id' => $user->id,
                'warehouse_id' => $validated['warehouse_id'],
                'order_number' => $orderNumber,
                'status' => 1,
                'subtotal' => $subtotal,
                'shipping_cost' => $validated['shipping_cost'],
                'point_redeemed' => $pointRedeemed,
                'point_earned' => $totalPointsEarned,
                'total' => $total,
                'buyer_name' => $user->name,
                'buyer_email' => $user->email,
                'buyer_phone' => $user->phone,
                'shipping_address' => $validated['shipping_address'],
                'shipping_note' => $validated['shipping_note'] ?? null,
                'note' => $validated['note'] ?? null,
            ]);

            foreach ($orderItemData as $item) {
                $item['order_id'] = $order->id;
                OrderItem::create($item);

                WarehouseStock::where('warehouse_id', $validated['warehouse_id'])
                    ->where('product_variant_id', $item['product_variant_id'])
                    ->decrement('quantity', $item['quantity']);
            }

            if ($pointRedeemed > 0) {
                $user->userPoint->decrement('balance', $pointRedeemed);

                PointTransaction::create([
                    'user_id' => $user->id,
                    'order_id' => $order->id,
                    'type' => 2,
                    'amount' => $pointRedeemed,
                    'description' => 'Redeem poin untuk pesanan #'.$orderNumber,
                ]);
            }

            Cart::where('user_id', $user->id)->delete();

            return response()->json([
                'message' => 'Pesanan berhasil dibuat.',
                'data' => new OrderResource($order->load(['orderItems', 'payment', 'shipment'])),
            ], 201);
        });
    }
}
