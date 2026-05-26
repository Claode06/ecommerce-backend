@extends('admin.layouts.app')

@section('page_title', 'Manajemen Gudang')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
  <div class="px-6 py-4 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
    <h3 class="text-base font-semibold text-gray-800">Daftar Gudang</h3>
    <a href="{{ route('admin.warehouses.create') }}" class="inline-flex items-center gap-1.5 px-4 py-2 bg-gradient-to-r from-sky-600 to-teal-600 text-white text-sm font-medium rounded-lg hover:shadow-md hover:shadow-sky-100 transition-all">
      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
      + Gudang Baru
    </a>
  </div>
  <div class="overflow-x-auto">
  <table class="w-full text-sm">
    <thead><tr class="bg-gray-50 text-left">
      <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wider text-gray-500">Nama</th>
      <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wider text-gray-500">Kota</th>
      <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wider text-gray-500">Provinsi</th>
      <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wider text-gray-500">Status</th>
      <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wider text-gray-500">SKU</th>
      <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wider text-gray-500">Aksi</th>
    </tr></thead>
    <tbody>
      @foreach($warehouses as $wh)
      <tr class="border-t border-gray-100 hover:bg-sky-50/50 transition-colors">
        <td class="px-6 py-4 font-medium text-gray-800">{{ $wh->name }}</td>
        <td class="px-6 py-4">{{ $wh->city }}</td>
        <td class="px-6 py-4">{{ $wh->province }}</td>
        <td class="px-6 py-4">{!! $wh->is_active ? '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">Aktif</span>' : '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600">Nonaktif</span>' !!}</td>
        <td class="px-6 py-4">{{ $wh->warehouse_stocks_count }}</td>
        <td class="px-6 py-4">
          <div class="flex gap-2">
          <a href="{{ route('admin.warehouses.edit', $wh) }}" class="text-sky-600 hover:text-sky-800 text-sm font-medium transition-colors">Edit</a>
          <form action="{{ route('admin.warehouses.destroy', $wh) }}" method="POST" onsubmit="return confirm('Hapus gudang?')">
            @csrf @method('DELETE')<button class="text-rose-600 hover:text-rose-800 text-sm font-medium transition-colors">Hapus</button>
          </form>
          </div>
        </td>
      </tr>
      @endforeach
    </tbody>
  </table>
  </div>
  <div class="px-6 py-4 border-t border-gray-100">{{ $warehouses->links() }}</div>
</div>
@endsection
