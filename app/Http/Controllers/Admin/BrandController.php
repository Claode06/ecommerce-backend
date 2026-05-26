<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\View\View;

class BrandController extends Controller
{
    public function index(): View
    {
        $brands = Brand::withCount('products')->orderBy('name')->paginate(15);

        return view('admin.brands.index', compact('brands'));
    }

    public function create(): View
    {
        return view('admin.brands.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:brands,name',
        ]);

        $validated['slug'] = $this->uniqueSlug($validated['name']);
        Brand::create($validated);
        Log::info('Brand created', ['admin_id' => auth('admin')->id(), 'name' => $validated['name']]);

        return redirect()->route('admin.brands.index')->with('success', 'Merek berhasil dibuat.');
    }

    public function edit(Brand $brand): View
    {
        return view('admin.brands.edit', compact('brand'));
    }

    public function update(Request $request, Brand $brand)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:brands,name,'.$brand->id,
        ]);

        if ($validated['name'] !== $brand->name) {
            $validated['slug'] = $this->uniqueSlug($validated['name'], $brand->id);
        }

        $brand->update($validated);
        Log::info('Brand updated', ['admin_id' => auth('admin')->id(), 'brand_id' => $brand->id]);

        return redirect()->route('admin.brands.index')->with('success', 'Merek berhasil diperbarui.');
    }

    public function destroy(Brand $brand)
    {
        $activeProductCount = $brand->products()->whereNull('deleted_at')->count();

        if ($activeProductCount > 0) {
            return back()->with('error', 'Merek masih digunakan oleh produk aktif.');
        }

        $brand->delete();
        Log::info('Brand deleted', ['admin_id' => auth('admin')->id(), 'brand_id' => $brand->id]);

        return redirect()->route('admin.brands.index')->with('success', 'Merek berhasil dihapus.');
    }

    private function uniqueSlug(string $name, ?int $excludeId = null): string
    {
        $slug = Str::slug($name);
        $original = $slug;
        $counter = 1;

        while (Brand::where('slug', $slug)->when($excludeId, fn ($q) => $q->where('id', '!=', $excludeId))->exists()) {
            $slug = $original.'-'.$counter++;
        }

        return $slug;
    }
}
