<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\FileStorage;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(Request $request): View
    {
        $products = Product::with(['brand', 'category', 'variants'])
            ->withCount('variants')
            ->when($request->filled('category_id'), fn ($q) => $q->where('category_id', $request->input('category_id')))
            ->when($request->filled('brand_id'), fn ($q) => $q->where('brand_id', $request->input('brand_id')))
            ->when($request->filled('status'), fn ($q) => $q->where('is_active', $request->input('status')))
            ->when($request->filled('search'), fn ($q) => $q->where('name', 'like', '%'.$request->input('search').'%'))
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $categories = Category::orderBy('name')->get();
        $brands = Brand::orderBy('name')->get();

        return view('admin.products.index', compact('products', 'categories', 'brands'));
    }

    public function create(): View
    {
        $brands = Brand::orderBy('name')->get();
        $categories = Category::where('is_active', true)->orderBy('name')->get();

        return view('admin.products.create', compact('brands', 'categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'brand_id' => 'required|exists:brands,id',
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:100',
            'thumbnail' => 'required|image|mimes:jpg,png,webp|max:2048',
            'description' => 'nullable|string',
            'features' => 'nullable|string',
            'gender' => 'required|in:1,2,3',
            'is_active' => 'boolean',
        ]);

        $validated['slug'] = $this->uniqueSlug($validated['name']);

        $path = $request->file('thumbnail')->store('products', 'public');
        $fileStorage = FileStorage::create(['link' => $path]);
        $validated['thumbnail'] = $fileStorage->id;

        $product = Product::create($validated);
        Log::info('Product created', ['admin_id' => auth('admin')->id(), 'product_id' => $product->id]);

        return redirect()->route('admin.products.show', $product)
            ->with('success', 'Produk berhasil dibuat. Silakan tambahkan varian dan galeri.');
    }

    public function show(Product $product): View
    {
        $product->load(['brand', 'category', 'images', 'variants']);

        return view('admin.products.show', compact('product'));
    }

    public function edit(Product $product): View
    {
        $brands = Brand::orderBy('name')->get();
        $categories = Category::orderBy('name')->get();

        return view('admin.products.edit', compact('product', 'brands', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'brand_id' => 'required|exists:brands,id',
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:100',
            'thumbnail' => 'nullable|image|mimes:jpg,png,webp|max:2048',
            'description' => 'nullable|string',
            'features' => 'nullable|string',
            'gender' => 'required|in:1,2,3',
            'is_active' => 'boolean',
        ]);

        if ($request->hasFile('thumbnail')) {
            if ($product->thumbnail) {
                $oldFile = FileStorage::find($product->thumbnail);
                if ($oldFile) {
                    Storage::disk('public')->delete($oldFile->link);
                    $oldFile->delete();
                }
            }
            $path = $request->file('thumbnail')->store('products', 'public');
            $fileStorage = FileStorage::create(['link' => $path]);
            $validated['thumbnail'] = $fileStorage->id;
        }

        if ($validated['name'] !== $product->name) {
            $validated['slug'] = $this->uniqueSlug($validated['name'], $product->id);
        }

        $product->update($validated);
        Log::info('Product updated', ['admin_id' => auth('admin')->id(), 'product_id' => $product->id]);

        return redirect()->route('admin.products.show', $product)->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        Log::info('Product deleted', ['admin_id' => auth('admin')->id(), 'product_id' => $product->id]);

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil dihapus.');
    }

    public function toggleStatus(Product $product)
    {
        $product->update(['is_active' => ! $product->is_active]);

        return back()->with('success', 'Status produk berhasil diubah.');
    }

    private function uniqueSlug(string $name, ?int $excludeId = null): string
    {
        $slug = Str::slug($name);
        $original = $slug;
        $counter = 1;

        while (Product::where('slug', $slug)->when($excludeId, fn ($q) => $q->where('id', '!=', $excludeId))->exists()) {
            $slug = $original.'-'.$counter++;
        }

        return $slug;
    }
}
