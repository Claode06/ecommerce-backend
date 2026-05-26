@extends('admin.layouts.app')

@section('page_title', 'Edit Merek')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
  <form action="{{ route('admin.brands.update', $brand) }}" method="POST">
    @csrf @method('PUT')
    <div class="mb-4">
      <label class="block text-sm font-medium mb-1">Nama Merek</label>
      <input type="text" name="name" value="{{ old('name', $brand->name) }}" class="w-full border border-gray-200 rounded-lg px-3 py-2.5 focus:ring-2 focus:ring-sky-400 outline-none @error('name') border-rose-300 ring-2 ring-rose-200 @enderror">
      @error('name')<p class="text-rose-500 text-xs mt-1">{{ $message }}</p>@enderror
    </div>
    <button type="submit" class="px-4 py-2.5 bg-gradient-to-r from-sky-600 to-teal-600 text-white text-sm font-medium rounded-lg hover:shadow-md hover:shadow-sky-100 transition-all">Simpan</button>
    <a href="{{ route('admin.brands.index') }}" class="ml-2 text-gray-600 hover:text-gray-800 text-sm font-medium transition-colors">Batal</a>
  </form>
</div>
@endsection
