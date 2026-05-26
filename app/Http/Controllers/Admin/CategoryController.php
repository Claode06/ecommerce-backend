<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\FileStorage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function index(): View
    {
        $categories = Category::withCount('products')->orderBy('sort_order')->paginate(15);

        return view('admin.categories.index', compact('categories'));
    }

    public function create(): View
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'image' => 'nullable|image|mimes:jpg,png,webp|max:2048',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $validated['slug'] = $this->uniqueSlug($validated['name']);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('categories', 'public');
            $fileStorage = FileStorage::create(['link' => $path]);
            $validated['image'] = $fileStorage->id;
        }

        Category::create($validated);
        Log::info('Category created', ['admin_id' => auth('admin')->id(), 'name' => $validated['name']]);

        return redirect()->route('admin.categories.index')->with('success', 'Kategori berhasil dibuat.');
    }

    public function edit(Category $category): View
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'image' => 'nullable|image|mimes:jpg,png,webp|max:2048',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        if ($request->hasFile('image')) {
            if ($category->image) {
                $oldFile = FileStorage::find($category->image);
                if ($oldFile) {
                    Storage::disk('public')->delete($oldFile->link);
                    $oldFile->delete();
                }
            }
            $path = $request->file('image')->store('categories', 'public');
            $fileStorage = FileStorage::create(['link' => $path]);
            $validated['image'] = $fileStorage->id;
        }

        if ($validated['name'] !== $category->name) {
            $validated['slug'] = $this->uniqueSlug($validated['name'], $category->id);
        }

        $category->update($validated);
        Log::info('Category updated', ['admin_id' => auth('admin')->id(), 'category_id' => $category->id]);

        return redirect()->route('admin.categories.index')->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy(Category $category)
    {
        $activeProductCount = $category->products()->whereNull('deleted_at')->count();

        if ($activeProductCount > 0) {
            return back()->with('error', 'Kategori masih digunakan oleh produk aktif.');
        }

        $category->delete();
        Log::info('Category deleted', ['admin_id' => auth('admin')->id(), 'category_id' => $category->id]);

        return redirect()->route('admin.categories.index')->with('success', 'Kategori berhasil dihapus.');
    }

    private function uniqueSlug(string $name, ?int $excludeId = null): string
    {
        $slug = Str::slug($name);
        $original = $slug;
        $counter = 1;

        while (Category::where('slug', $slug)->when($excludeId, fn ($q) => $q->where('id', '!=', $excludeId))->exists()) {
            $slug = $original.'-'.$counter++;
        }

        return $slug;
    }
}
