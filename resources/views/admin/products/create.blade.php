@extends('admin.layouts.app')

@section('page_title', 'Buat Produk')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
  <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="grid grid-cols-2 gap-4">
      <div class="mb-4">
        <label class="block text-sm font-medium mb-1">Nama Produk</label>
        <input type="text" name="name" value="{{ old('name') }}" class="w-full border border-gray-200 rounded-lg px-3 py-2.5 focus:ring-2 focus:ring-sky-400 outline-none @error('name') border-rose-300 ring-2 ring-rose-200 @enderror">
        @error('name')<p class="text-rose-500 text-xs mt-1">{{ $message }}</p>@enderror
      </div>
      <div class="mb-4">
        <label class="block text-sm font-medium mb-1">Gender</label>
        <select name="gender" class="w-full border border-gray-200 rounded-lg px-3 py-2.5 focus:ring-2 focus:ring-sky-400 outline-none @error('gender') border-rose-300 ring-2 ring-rose-200 @enderror">
          <option value="1">Wanita</option>
          <option value="2">Pria</option>
          <option value="3">Unisex</option>
        </select>
        @error('gender')<p class="text-rose-500 text-xs mt-1">{{ $message }}</p>@enderror
      </div>
      <div class="mb-4">
        <label class="block text-sm font-medium mb-1">Kategori</label>
        <select name="category_id" class="w-full border border-gray-200 rounded-lg px-3 py-2.5 focus:ring-2 focus:ring-sky-400 outline-none @error('category_id') border-rose-300 ring-2 ring-rose-200 @enderror">
          @foreach($categories as $cat)<option value="{{ $cat->id }}">{{ $cat->name }}</option>@endforeach
        </select>
        @error('category_id')<p class="text-rose-500 text-xs mt-1">{{ $message }}</p>@enderror
      </div>
      <div class="mb-4">
        <label class="block text-sm font-medium mb-1">Merek</label>
        <select name="brand_id" class="w-full border border-gray-200 rounded-lg px-3 py-2.5 focus:ring-2 focus:ring-sky-400 outline-none @error('brand_id') border-rose-300 ring-2 ring-rose-200 @enderror">
          @foreach($brands as $brand)<option value="{{ $brand->id }}">{{ $brand->name }}</option>@endforeach
        </select>
        @error('brand_id')<p class="text-rose-500 text-xs mt-1">{{ $message }}</p>@enderror
      </div>
    </div>
    <div class="mb-4">
      <label class="block text-sm font-medium mb-1">Thumbnail</label>
      <input type="file" name="thumbnail" accept="image/jpg,image/png,image/webp" class="w-full border border-gray-200 rounded-lg px-3 py-2.5 focus:ring-2 focus:ring-sky-400 outline-none @error('thumbnail') border-rose-300 ring-2 ring-rose-200 @enderror">
      @error('thumbnail')<p class="text-rose-500 text-xs mt-1">{{ $message }}</p>@enderror
    </div>
    <div class="mb-4">
      <label class="block text-sm font-medium mb-1">Deskripsi</label>
      <textarea name="description" rows="3" class="w-full border border-gray-200 rounded-lg px-3 py-2.5 focus:ring-2 focus:ring-sky-400 outline-none">{{ old('description') }}</textarea>
    </div>
    <div class="mb-4">
      <label class="block text-sm font-medium mb-1">Features</label>
      <textarea name="features" rows="3" class="w-full border border-gray-200 rounded-lg px-3 py-2.5 focus:ring-2 focus:ring-sky-400 outline-none">{{ old('features') }}</textarea>
    </div>
    <div class="mb-4">
      <label class="block text-sm font-medium mb-1">Status</label>
      <select name="is_active" class="w-full border border-gray-200 rounded-lg px-3 py-2.5 focus:ring-2 focus:ring-sky-400 outline-none">
        <option value="1">Aktif</option>
        <option value="0">Nonaktif</option>
      </select>
    </div>
    <button type="submit" class="px-4 py-2.5 bg-gradient-to-r from-sky-600 to-teal-600 text-white text-sm font-medium rounded-lg hover:shadow-md hover:shadow-sky-100 transition-all">Simpan</button>
    <a href="{{ route('admin.products.index') }}" class="ml-2 text-gray-600 hover:text-gray-800 text-sm font-medium transition-colors">Batal</a>
  </form>
</div>
@endsection
