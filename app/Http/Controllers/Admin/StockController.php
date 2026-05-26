<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductVariant;
use App\Models\Warehouse;
use App\Models\WarehouseStock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class StockController extends Controller
{
    public function index(Request $request): View
    {
        $stocks = WarehouseStock::with(['warehouse', 'productVariant.product'])
            ->when($request->filled('warehouse_id'), fn ($q) => $q->where('warehouse_id', $request->input('warehouse_id')))
            ->when($request->filled('search'), function ($q) use ($request) {
                $search = $request->input('search');
                $q->whereHas('productVariant.product', fn ($q) => $q->where('name', 'like', "%{$search}%"))
                  ->orWhereHas('productVariant', fn ($q) => $q->where('sku', 'like', "%{$search}%"));
            })
            ->orderBy('quantity')
            ->paginate(25);

        $warehouses = Warehouse::orderBy('name')->get();

        return view('admin.stocks.index', compact('stocks', 'warehouses'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'warehouse_id' => 'required|exists:warehouses,id',
            'product_variant_id' => 'required|exists:product_variants,id',
            'quantity' => 'required|integer|min:0',
        ]);

        DB::transaction(function () use ($validated) {
            $old = WarehouseStock::where('warehouse_id', $validated['warehouse_id'])
                ->where('product_variant_id', $validated['product_variant_id'])
                ->first();

            WarehouseStock::updateOrCreate(
                [
                    'warehouse_id' => $validated['warehouse_id'],
                    'product_variant_id' => $validated['product_variant_id'],
                ],
                ['quantity' => $validated['quantity']]
            );

            Log::info('Stock updated', [
                'admin_id' => auth('admin')->id(),
                'warehouse_id' => $validated['warehouse_id'],
                'variant_id' => $validated['product_variant_id'],
                'old_quantity' => $old?->quantity ?? 0,
                'new_quantity' => $validated['quantity'],
            ]);
        });

        return back()->with('success', 'Stok berhasil diperbarui.');
    }
}
