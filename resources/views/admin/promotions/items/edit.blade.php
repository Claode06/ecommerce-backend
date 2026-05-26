@extends('admin.layouts.app')

@section('page_title', 'Edit Item Promosi')

@section('content')
<div class="bg-white rounded-lg shadow p-6">
  <p class="text-sm text-gray-500 mb-4">Promosi: <strong>{{ $promotion->name }}</strong></p>
  <form action="{{ route('admin.promotions.items.update', [$promotion, $item]) }}" method="POST">
    @csrf @method('PUT')
    <div class="mb-4">
      <label class="block text-sm font-medium mb-1">Varian Produk</label>
      <select name="product_variant_id" class="w-full border rounded px-3 py-2 @error('product_variant_id') border-red-500 @enderror">
        @foreach($variants as $variant)
          <option value="{{ $variant->id }}" data-price="{{ $variant->price }}" @selected(old('product_variant_id', $item->product_variant_id) == $variant->id)>
            {{ $variant->product?->name }} - {{ $variant->label }} ({{ $variant->sku }}) - Rp {{ number_format($variant->price, 0, ',', '.') }}
          </option>
        @endforeach
      </select>
      @error('product_variant_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
    </div>
    <div class="mb-4">
      <label class="block text-sm font-medium mb-1">Harga Promo</label>
      <input type="number" step="0.01" name="override_price" value="{{ old('override_price', $item->override_price) }}" class="w-full border rounded px-3 py-2 @error('override_price') border-red-500 @enderror">
      @error('override_price')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
    </div>
    <button type="submit" class="bg-gray-900 text-white px-4 py-2 rounded text-sm hover:bg-gray-800">Simpan</button>
    <a href="{{ route('admin.promotions.show', $promotion) }}" class="ml-2 text-gray-600 hover:underline text-sm">Kembali</a>
  </form>
</div>
@endsection
