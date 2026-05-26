@extends('admin.layouts.app')

@section('page_title', 'Manajemen Produk')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
  <div class="px-6 py-4 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
    <h3 class="text-base font-semibold text-gray-800">Daftar Produk</h3>
    <a href="{{ route('admin.products.create') }}" class="inline-flex items-center gap-1.5 px-4 py-2 bg-gradient-to-r from-sky-600 to-teal-600 text-white text-sm font-medium rounded-lg hover:shadow-md hover:shadow-sky-100 transition-all">
      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
      Produk Baru
    </a>
  </div>
  <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
    <form class="flex flex-wrap gap-2">
      <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari produk..." class="border border-gray-200 rounded-lg px-3 py-2 text-sm bg-white focus:ring-2 focus:ring-sky-400 outline-none">
      <select name="category_id" class="border border-gray-200 rounded-lg px-3 py-2 text-sm bg-white focus:ring-2 focus:ring-sky-400 outline-none">
        <option value="">Semua Kategori</option>
        @foreach($categories as $cat)<option value="{{ $cat->id }}" @selected(request('category_id') == $cat->id)>{{ $cat->name }}</option>@endforeach
      </select>
      <select name="brand_id" class="border border-gray-200 rounded-lg px-3 py-2 text-sm bg-white focus:ring-2 focus:ring-sky-400 outline-none">
        <option value="">Semua Merek</option>
        @foreach($brands as $brand)<option value="{{ $brand->id }}" @selected(request('brand_id') == $brand->id)>{{ $brand->name }}</option>@endforeach
      </select>
      <select name="status" class="border border-gray-200 rounded-lg px-3 py-2 text-sm bg-white focus:ring-2 focus:ring-sky-400 outline-none">
        <option value="">Semua Status</option>
        <option value="1" @selected(request('status') === '1')>Aktif</option>
        <option value="0" @selected(request('status') === '0')>Nonaktif</option>
      </select>
      <button type="submit" class="px-4 py-2 bg-gray-900 text-white text-sm font-medium rounded-lg hover:bg-gray-800 transition-colors">Filter</button>
      @if(request()->anyFilled(['search','category_id','brand_id','status']))
        <a href="{{ route('admin.products.index') }}" class="px-4 py-2 text-sm text-rose-600 hover:bg-rose-50 rounded-lg transition-colors">Reset</a>
      @endif
    </form>
  </div>
  <div class="overflow-x-auto">
    <table class="w-full text-sm">
      <thead>
        <tr class="bg-gray-50 text-left">
          <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wider text-gray-500">Nama</th>
          <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wider text-gray-500">Kategori</th>
          <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wider text-gray-500">Merek</th>
          <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wider text-gray-500">Varian</th>
          <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wider text-gray-500">Gender</th>
          <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wider text-gray-500">Status</th>
          <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wider text-gray-500">Aksi</th>
        </tr>
      </thead>
      <tbody>
        @foreach($products as $product)
        <tr class="border-t border-gray-100 hover:bg-sky-50/50 transition-colors">
          <td class="px-6 py-4 font-medium text-gray-800">{{ $product->name }}</td>
          <td class="px-6 py-4 text-gray-600">{{ $product->category?->name }}</td>
          <td class="px-6 py-4 text-gray-600">{{ $product->brand?->name }}</td>
          <td class="px-6 py-4 text-gray-600">{{ $product->variants_count }}</td>
          <td class="px-6 py-4"><span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-700">{{ ['1'=>'Wanita','2'=>'Pria','3'=>'Unisex'][$product->gender] }}</span></td>
          <td class="px-6 py-4">{!! $product->is_active
            ? '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">Aktif</span>'
            : '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600">Nonaktif</span>' !!}</td>
          <td class="px-6 py-4">
            <div class="flex items-center gap-2">
              <a href="{{ route('admin.products.show', $product) }}" class="text-sky-600 hover:text-sky-800 text-sm font-medium transition-colors">Detail</a>
              <a href="{{ route('admin.products.edit', $product) }}" class="text-gray-600 hover:text-gray-800 text-sm font-medium transition-colors">Edit</a>
            </div>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
  <div class="px-6 py-4 border-t border-gray-100">{{ $products->links() }}</div>
</div>
@endsection
