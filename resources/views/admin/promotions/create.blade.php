@extends('admin.layouts.app')

@section('page_title', 'Buat Promosi')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
  <form action="{{ route('admin.promotions.store') }}" method="POST">
    @csrf
    <div class="mb-4"><label class="block text-sm font-medium mb-1">Nama Promosi</label><input type="text" name="name" value="{{ old('name') }}" class="w-full border border-gray-200 rounded-lg px-3 py-2.5 focus:ring-2 focus:ring-sky-400 outline-none @error('name') border-rose-300 ring-2 ring-rose-200 @enderror">@error('name')<p class="text-rose-500 text-xs mt-1">{{ $message }}</p>@enderror</div>
    <div class="mb-4"><label class="block text-sm font-medium mb-1">Tipe</label><select name="type" class="w-full border border-gray-200 rounded-lg px-3 py-2.5 focus:ring-2 focus:ring-sky-400 outline-none"><option value="1">Flash Sales</option></select></div>
    <div class="mb-4"><label class="block text-sm font-medium mb-1">Mulai</label><input type="datetime-local" name="start_at" value="{{ old('start_at') }}" class="w-full border border-gray-200 rounded-lg px-3 py-2.5 focus:ring-2 focus:ring-sky-400 outline-none @error('start_at') border-rose-300 ring-2 ring-rose-200 @enderror">@error('start_at')<p class="text-rose-500 text-xs mt-1">{{ $message }}</p>@enderror</div>
    <div class="mb-4"><label class="block text-sm font-medium mb-1">Berakhir</label><input type="datetime-local" name="end_at" value="{{ old('end_at') }}" class="w-full border border-gray-200 rounded-lg px-3 py-2.5 focus:ring-2 focus:ring-sky-400 outline-none @error('end_at') border-rose-300 ring-2 ring-rose-200 @enderror">@error('end_at')<p class="text-rose-500 text-xs mt-1">{{ $message }}</p>@enderror</div>
    <div class="mb-4"><label class="block text-sm font-medium mb-1">Status</label><select name="is_active" class="w-full border border-gray-200 rounded-lg px-3 py-2.5 focus:ring-2 focus:ring-sky-400 outline-none"><option value="1">Aktif</option><option value="0">Nonaktif</option></select></div>
    <button type="submit" class="px-4 py-2.5 bg-gradient-to-r from-sky-600 to-teal-600 text-white text-sm font-medium rounded-lg hover:shadow-md hover:shadow-sky-100 transition-all">Simpan</button>
    <a href="{{ route('admin.promotions.index') }}" class="ml-2 text-gray-600 hover:text-gray-800 text-sm font-medium transition-colors">Batal</a>
  </form>
</div>
@endsection
