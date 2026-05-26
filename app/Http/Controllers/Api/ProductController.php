<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductDetailResource;
use App\Http\Resources\ProductListResource;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $products = Product::with(['brand', 'category', 'variants' => fn ($q) => $q->where('is_active', true)])
            ->withCount(['variants' => fn ($q) => $q->where('is_active', true)])
            ->where('is_active', true)
            ->when($request->filled('category_id'), fn ($q) => $q->where('category_id', $request->input('category_id')))
            ->when($request->filled('category_slug'), fn ($q) => $q->whereHas('category', fn ($q) => $q->where('slug', $request->input('category_slug'))))
            ->when($request->filled('brand_id'), fn ($q) => $q->where('brand_id', $request->input('brand_id')))
            ->when($request->filled('brand_slug'), fn ($q) => $q->whereHas('brand', fn ($q) => $q->where('slug', $request->input('brand_slug'))))
            ->when($request->filled('gender'), fn ($q) => $q->where('gender', $request->input('gender')))
            ->when($request->filled('search'), fn ($q) => $q->where('name', 'like', '%'.$request->input('search').'%'))
            ->when($request->filled('sort'), function ($q) use ($request) {
                match ($request->input('sort')) {
                    'price_asc' => $q->orderBy(
                        ProductVariant::select('price')->whereColumn('product_id', 'products.id')->where('is_active', true)->orderBy('price')->limit(1),
                        'asc'
                    ),
                    'price_desc' => $q->orderBy(
                        ProductVariant::select('price')->whereColumn('product_id', 'products.id')->where('is_active', true)->orderBy('price', 'desc')->limit(1),
                        'desc'
                    ),
                    'newest' => $q->orderBy('created_at', 'desc'),
                    'oldest' => $q->orderBy('created_at', 'asc'),
                    default => $q->orderBy('created_at', 'desc'),
                };
            }, fn ($q) => $q->orderBy('created_at', 'desc'))
            ->paginate($request->input('per_page', 20));

        return response()->json([
            'data' => ProductListResource::collection($products),
            'meta' => [
                'current_page' => $products->currentPage(),
                'last_page' => $products->lastPage(),
                'per_page' => $products->perPage(),
                'total' => $products->total(),
            ],
        ]);
    }

    public function show(string $slug): JsonResponse
    {
        $product = Product::with([
            'brand',
            'category',
            'variants' => fn ($q) => $q->where('is_active', true)->with(['warehouseStocks', 'promotionItems' => fn ($q) => $q->whereHas('promotion', fn ($q) => $q->active())]),
            'images.fileStorage',
            'reviews' => fn ($q) => $q->visible()->with('user')->orderBy('created_at', 'desc')->limit(10),
            'thumbnailFile',
        ])
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        return response()->json(new ProductDetailResource($product));
    }
}
