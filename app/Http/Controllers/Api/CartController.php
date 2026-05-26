<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CartResource;
use App\Models\Cart;
use App\Models\ProductVariant;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CartController extends Controller
{
    public function index(): JsonResponse
    {
        $cartItems = Cart::where('user_id', auth()->id())
            ->with(['productVariant.product', 'productVariant.warehouseStocks'])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'data' => CartResource::collection($cartItems),
            'total' => $cartItems->sum(function ($item) {
                $price = $item->productVariant->price;
                $promo = $item->productVariant->promotionItems()
                    ->whereHas('promotion', fn ($q) => $q->active())
                    ->first();
                if ($promo) {
                    $price = $promo->override_price;
                }
                return $price * $item->quantity;
            }),
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = Validator::make($request->all(), [
            'product_variant_id' => 'required|exists:product_variants,id',
            'quantity' => 'required|integer|min:1',
        ])->validate();

        $variant = ProductVariant::findOrFail($validated['product_variant_id']);

        if (! $variant->is_active) {
            return response()->json(['message' => 'Varian tidak tersedia.'], 400);
        }

        $existing = Cart::where('user_id', auth()->id())
            ->where('product_variant_id', $validated['product_variant_id'])
            ->first();

        if ($existing) {
            $existing->increment('quantity', $validated['quantity']);
            $cart = $existing;
        } else {
            $cart = Cart::create([
                'user_id' => auth()->id(),
                'product_variant_id' => $validated['product_variant_id'],
                'quantity' => $validated['quantity'],
            ]);
        }

        return response()->json([
            'message' => 'Item ditambahkan ke keranjang.',
            'data' => new CartResource($cart->load(['productVariant.product', 'productVariant.warehouseStocks'])),
        ], 201);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $cart = Cart::where('user_id', auth()->id())->findOrFail($id);

        $validated = Validator::make($request->all(), [
            'quantity' => 'required|integer|min:1',
        ])->validate();

        $cart->update(['quantity' => $validated['quantity']]);

        return response()->json([
            'message' => 'Keranjang diperbarui.',
            'data' => new CartResource($cart->load(['productVariant.product', 'productVariant.warehouseStocks'])),
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        $cart = Cart::where('user_id', auth()->id())->findOrFail($id);
        $cart->delete();

        return response()->json(['message' => 'Item dihapus dari keranjang.']);
    }
}
