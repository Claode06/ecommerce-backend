@extends('admin.layouts.app')

@section('page_title', 'Manajemen Promosi')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
  <div class="px-6 py-4 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
    <h3 class="text-base font-semibold text-gray-800">Daftar Promosi</h3>
    <a href="{{ route('admin.promotions.create') }}" class="inline-flex items-center gap-1.5 px-4 py-2 bg-gradient-to-r from-sky-600 to-teal-600 text-white text-sm font-medium rounded-lg hover:shadow-md hover:shadow-sky-100 transition-all">
      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
      + Promosi Baru
    </a>
  </div>
  <div class="overflow-x-auto">
  <table class="w-full text-sm">
    <thead><tr class="bg-gray-50 text-left">
      <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wider text-gray-500">Nama</th>
      <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wider text-gray-500">Status</th>
      <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wider text-gray-500">Mulai</th>
      <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wider text-gray-500">Berakhir</th>
      <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wider text-gray-500">Item</th>
      <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wider text-gray-500">Aksi</th>
    </tr></thead>
    <tbody>
      @foreach($promotions as $promo)
      <tr class="border-t border-gray-100 hover:bg-sky-50/50 transition-colors">
        <td class="px-6 py-4 font-medium text-gray-800">{{ $promo->name }}</td>
        <td class="px-6 py-4">{!! $promo->is_active ? '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">Aktif</span>' : '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600">Nonaktif</span>' !!}</td>
        <td class="px-6 py-4">{{ \Carbon\Carbon::parse($promo->start_at)->format('d/m/Y H:i') }}</td>
        <td class="px-6 py-4">{{ \Carbon\Carbon::parse($promo->end_at)->format('d/m/Y H:i') }}</td>
        <td class="px-6 py-4">{{ $promo->promotion_items_count }}</td>
        <td class="px-6 py-4">
          <div class="flex gap-2">
          <a href="{{ route('admin.promotions.show', $promo) }}" class="text-sky-600 hover:text-sky-800 text-sm font-medium transition-colors">Detail</a>
          <a href="{{ route('admin.promotions.edit', $promo) }}" class="text-sky-600 hover:text-sky-800 text-sm font-medium transition-colors">Edit</a>
          <form action="{{ route('admin.promotions.destroy', $promo) }}" method="POST" onsubmit="return confirm('Hapus promosi?')">
            @csrf @method('DELETE')<button class="text-rose-600 hover:text-rose-800 text-sm font-medium transition-colors">Hapus</button>
          </form>
          </div>
        </td>
      </tr>
      @endforeach
    </tbody>
  </table>
  </div>
  <div class="px-6 py-4 border-t border-gray-100">{{ $promotions->links() }}</div>
</div>
@endsection
