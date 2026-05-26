@extends('admin.layouts.app')

@section('page_title', 'Edit Produk')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
  <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
    @csrf @method('PUT')
    <div class="grid grid-cols-2 gap-4">
      <div class="mb-4">
        <label class="block text-sm font-medium mb-1">Nama Produk</label>
        <input type="text" name="name" value="{{ old('name', $product->name) }}" class="w-full border border-gray-200 rounded-lg px-3 py-2.5 focus:ring-2 focus:ring-sky-400 outline-none @error('name') border-rose-300 ring-2 ring-rose-200 @enderror">
        @error('name')<p class="text-rose-500 text-xs mt-1">{{ $message }}</p>@enderror
      </div>
      <div class="mb-4">
        <label class="block text-sm font-medium mb-1">Gender</label>
        <select name="gender" class="w-full border border-gray-200 rounded-lg px-3 py-2.5 focus:ring-2 focus:ring-sky-400 outline-none">
          <option value="1" @selected($product->gender === 1)>Wanita</option>
          <option value="2" @selected($product->gender === 2)>Pria</option>
          <option value="3" @selected($product->gender === 3)>Unisex</option>
        </select>
      </div>
      <div class="mb-4">
        <label class="block text-sm font-medium mb-1">Kategori</label>
        <select name="category_id" class="w-full border border-gray-200 rounded-lg px-3 py-2.5 focus:ring-2 focus:ring-sky-400 outline-none">
          @foreach($categories as $cat)<option value="{{ $cat->id }}" @selected($product->category_id === $cat->id)>{{ $cat->name }}</option>@endforeach
        </select>
      </div>
      <div class="mb-4">
        <label class="block text-sm font-medium mb-1">Merek</label>
        <select name="brand_id" class="w-full border border-gray-200 rounded-lg px-3 py-2.5 focus:ring-2 focus:ring-sky-400 outline-none">
          @foreach($brands as $brand)<option value="{{ $brand->id }}" @selected($product->brand_id === $brand->id)>{{ $brand->name }}</option>@endforeach
        </select>
      </div>
    </div>
    <div class="mb-4">
      <label class="block text-sm font-medium mb-1">Thumbnail (biarkan kosong jika tidak diganti)</label>
      <input type="file" name="thumbnail" accept="image/jpg,image/png,image/webp" class="w-full border border-gray-200 rounded-lg px-3 py-2.5 focus:ring-2 focus:ring-sky-400 outline-none">
    </div>
    <div class="mb-4">
      <label class="block text-sm font-medium mb-1">Deskripsi</label>
      <textarea name="description" rows="3" class="w-full border border-gray-200 rounded-lg px-3 py-2.5 focus:ring-2 focus:ring-sky-400 outline-none">{{ old('description', $product->description) }}</textarea>
    </div>
    <div class="mb-4">
      <label class="block text-sm font-medium mb-1">Features</label>
      <textarea name="features" rows="3" class="w-full border border-gray-200 rounded-lg px-3 py-2.5 focus:ring-2 focus:ring-sky-400 outline-none">{{ old('features', $product->features) }}</textarea>
    </div>
    <div class="mb-4">
      <label class="block text-sm font-medium mb-1">Status</label>
      <select name="is_active" class="w-full border border-gray-200 rounded-lg px-3 py-2.5 focus:ring-2 focus:ring-sky-400 outline-none">
        <option value="1" @selected($product->is_active)>Aktif</option>
        <option value="0" @selected(!$product->is_active)>Nonaktif</option>
      </select>
    </div>
    <button type="submit" class="px-4 py-2.5 bg-gradient-to-r from-sky-600 to-teal-600 text-white text-sm font-medium rounded-lg hover:shadow-md hover:shadow-sky-100 transition-all">Simpan</button>
    <a href="{{ route('admin.products.show', $product) }}" class="ml-2 text-gray-600 hover:text-gray-800 text-sm font-medium transition-colors">Batal</a>
  </form>
</div>
@endsection
