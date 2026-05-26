<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Promotion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class PromotionController extends Controller
{
    public function index(): View
    {
        $promotions = Promotion::withCount('promotionItems')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.promotions.index', compact('promotions'));
    }

    public function create(): View
    {
        return view('admin.promotions.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'type' => 'required|in:1',
            'is_active' => 'boolean',
            'start_at' => 'required|date',
            'end_at' => 'required|date|after:start_at',
        ]);

        $promotion = Promotion::create($validated);
        Log::info('Promotion created', ['admin_id' => auth('admin')->id(), 'promotion_id' => $promotion->id]);

        return redirect()->route('admin.promotions.show', $promotion)
            ->with('success', 'Promosi berhasil dibuat.');
    }

    public function show(Promotion $promotion): View
    {
        $promotion->load(['promotionItems.productVariant.product']);

        return view('admin.promotions.show', compact('promotion'));
    }

    public function edit(Promotion $promotion): View
    {
        return view('admin.promotions.edit', compact('promotion'));
    }

    public function update(Request $request, Promotion $promotion)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'type' => 'required|in:1',
            'is_active' => 'boolean',
            'start_at' => 'required|date',
            'end_at' => 'required|date|after:start_at',
        ]);

        if ($promotion->is_active !== ($validated['is_active'] ?? false)) {
            Log::info('Promotion status changed', [
                'admin_id' => auth('admin')->id(),
                'promotion_id' => $promotion->id,
                'status' => $validated['is_active'],
            ]);
        }

        $promotion->update($validated);

        return redirect()->route('admin.promotions.show', $promotion)
            ->with('success', 'Promosi berhasil diperbarui.');
    }

    public function destroy(Promotion $promotion)
    {
        $promotion->delete();

        return redirect()->route('admin.promotions.index')->with('success', 'Promosi berhasil dihapus.');
    }
}
