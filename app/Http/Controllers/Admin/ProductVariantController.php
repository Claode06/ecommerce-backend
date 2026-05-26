<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class ProductVariantController extends Controller
{
    public function create(Product $product): View
    {
        return view('admin.products.variants.create', compact('product'));
    }

    public function store(Request $request, Product $product)
    {
        $validated = $request->validate([
            'label' => 'required|string|max:100',
            'price' => 'required|numeric|min:0.01',
            'sku' => 'required|string|max:45|unique:product_variants,sku',
            'is_active' => 'boolean',
        ]);

        $validated['product_id'] = $product->id;
        ProductVariant::create($validated);
        Log::info('Variant created', ['admin_id' => auth('admin')->id(), 'sku' => $validated['sku']]);

        return redirect()->route('admin.products.show', $product)
            ->with('success', 'Varian berhasil dibuat.');
    }

    public function edit(Product $product, ProductVariant $variant): View
    {
        return view('admin.products.variants.edit', compact('product', 'variant'));
    }

    public function update(Request $request, Product $product, ProductVariant $variant)
    {
        $validated = $request->validate([
            'label' => 'required|string|max:100',
            'price' => 'required|numeric|min:0.01',
            'sku' => 'required|string|max:45|unique:product_variants,sku,'.$variant->id,
            'is_active' => 'boolean',
        ]);

        $variant->update($validated);

        if ($variant->wasChanged('price')) {
            Log::info('Variant price changed', [
                'admin_id' => auth('admin')->id(),
                'variant_id' => $variant->id,
                'old_price' => $variant->getOriginal('price'),
                'new_price' => $validated['price'],
            ]);
        }

        return redirect()->route('admin.products.show', $product)
            ->with('success', 'Varian berhasil diperbarui.');
    }

    public function destroy(Product $product, ProductVariant $variant)
    {
        $hasOrders = $variant->orderItems()->exists();

        if ($hasOrders) {
            return back()->with('error', 'Varian tidak bisa dihapus karena memiliki riwayat pesanan.');
        }

        $variant->delete();

        return redirect()->route('admin.products.show', $product)
            ->with('success', 'Varian berhasil dihapus.');
    }
}
