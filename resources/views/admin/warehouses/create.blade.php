@extends('admin.layouts.app')

@section('page_title', 'Buat Gudang')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
  <form action="{{ route('admin.warehouses.store') }}" method="POST">
    @csrf
    <div class="mb-4"><label class="block text-sm font-medium mb-1">Nama Gudang</label><input type="text" name="name" value="{{ old('name') }}" class="w-full border border-gray-200 rounded-lg px-3 py-2.5 focus:ring-2 focus:ring-sky-400 outline-none @error('name') border-rose-300 ring-2 ring-rose-200 @enderror">@error('name')<p class="text-rose-500 text-xs mt-1">{{ $message }}</p>@enderror</div>
    <div class="mb-4"><label class="block text-sm font-medium mb-1">Kota</label><input type="text" name="city" value="{{ old('city') }}" class="w-full border border-gray-200 rounded-lg px-3 py-2.5 focus:ring-2 focus:ring-sky-400 outline-none @error('city') border-rose-300 ring-2 ring-rose-200 @enderror">@error('city')<p class="text-rose-500 text-xs mt-1">{{ $message }}</p>@enderror</div>
    <div class="mb-4"><label class="block text-sm font-medium mb-1">Provinsi</label><input type="text" name="province" value="{{ old('province') }}" class="w-full border border-gray-200 rounded-lg px-3 py-2.5 focus:ring-2 focus:ring-sky-400 outline-none @error('province') border-rose-300 ring-2 ring-rose-200 @enderror">@error('province')<p class="text-rose-500 text-xs mt-1">{{ $message }}</p>@enderror</div>
    <div class="mb-4"><label class="block text-sm font-medium mb-1">Kode Pos</label><input type="text" name="postal_code" value="{{ old('postal_code') }}" class="w-full border border-gray-200 rounded-lg px-3 py-2.5 focus:ring-2 focus:ring-sky-400 outline-none @error('postal_code') border-rose-300 ring-2 ring-rose-200 @enderror">@error('postal_code')<p class="text-rose-500 text-xs mt-1">{{ $message }}</p>@enderror</div>
    <div class="mb-4"><label class="block text-sm font-medium mb-1">Status</label><select name="is_active" class="w-full border border-gray-200 rounded-lg px-3 py-2.5 focus:ring-2 focus:ring-sky-400 outline-none"><option value="1">Aktif</option><option value="0">Nonaktif</option></select></div>
    <button type="submit" class="px-4 py-2.5 bg-gradient-to-r from-sky-600 to-teal-600 text-white text-sm font-medium rounded-lg hover:shadow-md hover:shadow-sky-100 transition-all">Simpan</button>
    <a href="{{ route('admin.warehouses.index') }}" class="ml-2 text-gray-600 hover:text-gray-800 text-sm font-medium transition-colors">Batal</a>
  </form>
</div>
@endsection
