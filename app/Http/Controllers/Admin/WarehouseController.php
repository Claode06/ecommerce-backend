<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class WarehouseController extends Controller
{
    public function index(): View
    {
        $warehouses = Warehouse::withCount('warehouseStocks')->orderBy('name')->paginate(15);

        return view('admin.warehouses.index', compact('warehouses'));
    }

    public function create(): View
    {
        return view('admin.warehouses.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'is_active' => 'boolean',
            'city' => 'required|string|max:100',
            'province' => 'required|string|max:100',
            'postal_code' => 'required|string|max:10|digits:5',
        ]);

        Warehouse::create($validated);
        Log::info('Warehouse created', ['admin_id' => auth('admin')->id(), 'name' => $validated['name']]);

        return redirect()->route('admin.warehouses.index')->with('success', 'Gudang berhasil dibuat.');
    }

    public function edit(Warehouse $warehouse): View
    {
        return view('admin.warehouses.edit', compact('warehouse'));
    }

    public function update(Request $request, Warehouse $warehouse)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'is_active' => 'boolean',
            'city' => 'required|string|max:100',
            'province' => 'required|string|max:100',
            'postal_code' => 'required|string|max:10|digits:5',
        ]);

        if ($warehouse->is_active !== ($validated['is_active'] ?? false)) {
            Log::info('Warehouse status changed', [
                'admin_id' => auth('admin')->id(),
                'warehouse_id' => $warehouse->id,
                'status' => $validated['is_active'],
            ]);
        }

        $warehouse->update($validated);

        return redirect()->route('admin.warehouses.index')->with('success', 'Gudang berhasil diperbarui.');
    }

    public function destroy(Warehouse $warehouse)
    {
        $hasStock = $warehouse->warehouseStocks()->where('quantity', '>', 0)->exists();
        $hasActiveOrders = $warehouse->orders()->whereNotIn('status', [6])->exists();

        if ($hasStock || $hasActiveOrders) {
            return back()->with('error', 'Gudang tidak bisa dihapus karena masih memiliki stok atau pesanan aktif.');
        }

        $warehouse->delete();
        Log::info('Warehouse deleted', ['admin_id' => auth('admin')->id(), 'warehouse_id' => $warehouse->id]);

        return redirect()->route('admin.warehouses.index')->with('success', 'Gudang berhasil dihapus.');
    }
}
