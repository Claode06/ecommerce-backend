@extends('admin.layouts.app')

@section('page_title', 'Edit Varian')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
  <p class="text-sm text-gray-500 mb-4">Produk: <strong>{{ $product->name }}</strong></p>
  <form action="{{ route('admin.products.variants.update', [$product, $variant]) }}" method="POST">
    @csrf @method('PUT')
    <div class="mb-4">
      <label class="block text-sm font-medium mb-1">Label Varian</label>
      <input type="text" name="label" value="{{ old('label', $variant->label) }}" class="w-full border border-gray-200 rounded-lg px-3 py-2.5 focus:ring-2 focus:ring-sky-400 outline-none @error('label') border-rose-300 ring-2 ring-rose-200 @enderror">
      @error('label')<p class="text-rose-500 text-xs mt-1">{{ $message }}</p>@enderror
    </div>
    <div class="mb-4">
      <label class="block text-sm font-medium mb-1">Harga</label>
      <input type="number" step="0.01" name="price" value="{{ old('price', $variant->price) }}" class="w-full border border-gray-200 rounded-lg px-3 py-2.5 focus:ring-2 focus:ring-sky-400 outline-none @error('price') border-rose-300 ring-2 ring-rose-200 @enderror">
      @error('price')<p class="text-rose-500 text-xs mt-1">{{ $message }}</p>@enderror
    </div>
    <div class="mb-4">
      <label class="block text-sm font-medium mb-1">SKU</label>
      <input type="text" name="sku" value="{{ old('sku', $variant->sku) }}" class="w-full border border-gray-200 rounded-lg px-3 py-2.5 focus:ring-2 focus:ring-sky-400 outline-none @error('sku') border-rose-300 ring-2 ring-rose-200 @enderror">
      @error('sku')<p class="text-rose-500 text-xs mt-1">{{ $message }}</p>@enderror
    </div>
    <div class="mb-4">
      <label class="block text-sm font-medium mb-1">Status</label>
      <select name="is_active" class="w-full border border-gray-200 rounded-lg px-3 py-2.5 focus:ring-2 focus:ring-sky-400 outline-none">
        <option value="1" @selected($variant->is_active)>Aktif</option>
        <option value="0" @selected(!$variant->is_active)>Nonaktif</option>
      </select>
    </div>
    <button type="submit" class="px-4 py-2.5 bg-gradient-to-r from-sky-600 to-teal-600 text-white text-sm font-medium rounded-lg hover:shadow-md hover:shadow-sky-100 transition-all">Simpan</button>
    <a href="{{ route('admin.products.show', $product) }}" class="ml-2 text-gray-600 hover:text-gray-800 text-sm font-medium transition-colors">Kembali</a>
  </form>
</div>
@endsection
