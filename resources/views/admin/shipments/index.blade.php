@extends('admin.layouts.app')

@section('page_title', 'Manajemen Pengiriman')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
  <div class="px-6 py-4 border-b border-gray-100"><h3 class="text-base font-semibold text-gray-800">Daftar Pengiriman</h3></div>
  <div class="overflow-x-auto">
  <table class="w-full text-sm">
    <thead><tr class="bg-gray-50 text-left">
      <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wider text-gray-500">Order#</th>
      <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wider text-gray-500">Kurir</th>
      <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wider text-gray-500">Resi</th>
      <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wider text-gray-500">Status</th>
      <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wider text-gray-500">Tanggal</th>
      <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wider text-gray-500">Aksi</th>
    </tr></thead>
    <tbody>
      @foreach($shipments as $shipment)
      <tr class="border-t border-gray-100 hover:bg-sky-50/50 transition-colors">
        <td class="px-6 py-4 font-medium text-gray-800">{{ $shipment->order?->order_number }}</td>
        <td class="px-6 py-4">{{ $shipment->courier_name ?? '-' }}</td>
        <td class="px-6 py-4">{{ $shipment->tracking_number ?? '-' }}</td>
        <td class="px-6 py-4">{!! ['<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800">Pending</span>','<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">Picked Up</span>','<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">In Transit</span>','<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">Out for Delivery</span>','<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">Delivered</span>','<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-rose-100 text-rose-800">Failed</span>'][$shipment->status-1] !!}</td>
        <td class="px-6 py-4">{{ $shipment->created_at->format('d/m/Y') }}</td>
        <td class="px-6 py-4"><a href="{{ route('admin.shipments.show', $shipment) }}" class="text-sky-600 hover:text-sky-800 text-sm font-medium transition-colors">Detail</a></td>
      </tr>
      @endforeach
    </tbody>
  </table>
  </div>
  <div class="px-6 py-4 border-t border-gray-100">{{ $shipments->links() }}</div>
</div>
@endsection
