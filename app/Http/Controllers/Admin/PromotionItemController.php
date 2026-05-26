<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductVariant;
use App\Models\Promotion;
use App\Models\PromotionItem;
use Illuminate\Http\Request;

class PromotionItemController extends Controller
{
    public function create(Promotion $promotion)
    {
        $variants = ProductVariant::whereNotIn('id', $promotion->promotionItems()->pluck('product_variant_id'))
            ->with('product')
            ->orderBy('sku')
            ->get();

        return view('admin.promotions.items.create', compact('promotion', 'variants'));
    }

    public function store(Request $request, Promotion $promotion)
    {
        $validated = $request->validate([
            'product_variant_id' => 'required|exists:product_variants,id',
            'override_price' => 'required|numeric|min:0.01',
        ]);

        $variant = ProductVariant::findOrFail($validated['product_variant_id']);

        if ($validated['override_price'] >= $variant->price) {
            return back()->withErrors(['override_price' => 'Harga promo harus lebih kecil dari harga normal.'])->withInput();
        }

        $exists = $promotion->promotionItems()->where('product_variant_id', $validated['product_variant_id'])->exists();

        if ($exists) {
            return back()->withErrors(['product_variant_id' => 'Varian ini sudah ada dalam promosi.'])->withInput();
        }

        $validated['promotion_id'] = $promotion->id;
        PromotionItem::create($validated);

        return redirect()->route('admin.promotions.show', $promotion)
            ->with('success', 'Item promosi berhasil ditambahkan.');
    }

    public function destroy(Promotion $promotion, PromotionItem $item)
    {
        $item->delete();

        return back()->with('success', 'Item promosi berhasil dihapus.');
    }

    public function edit(Promotion $promotion, PromotionItem $item)
    {
        $variants = ProductVariant::with('product')
            ->whereNotIn('id', $promotion->promotionItems()->where('id', '!=', $item->id)->pluck('product_variant_id'))
            ->orderBy('sku')
            ->get();

        return view('admin.promotions.items.edit', compact('promotion', 'item', 'variants'));
    }

    public function update(Request $request, Promotion $promotion, PromotionItem $item)
    {
        $validated = $request->validate([
            'product_variant_id' => 'required|exists:product_variants,id',
            'override_price' => 'required|numeric|min:0.01',
        ]);

        $variant = ProductVariant::findOrFail($validated['product_variant_id']);

        if ($validated['override_price'] >= $variant->price) {
            return back()->withErrors(['override_price' => 'Harga promo harus lebih kecil dari harga normal.'])->withInput();
        }

        $exists = $promotion->promotionItems()
            ->where('product_variant_id', $validated['product_variant_id'])
            ->where('id', '!=', $item->id)
            ->exists();

        if ($exists) {
            return back()->withErrors(['product_variant_id' => 'Varian ini sudah ada dalam promosi.'])->withInput();
        }

        $item->update($validated);

        return redirect()->route('admin.promotions.show', $promotion)
            ->with('success', 'Item promosi berhasil diperbarui.');
    }
}
