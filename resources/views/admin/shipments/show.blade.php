@extends('admin.layouts.app')

@section('page_title', 'Detail Pengiriman')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
  <div class="bg-white rounded-lg shadow p-6">
    <h4 class="font-semibold mb-4">Info Pengiriman</h4>
    <p class="text-sm mb-1"><span class="text-gray-500">Order:</span> {{ $shipment->order?->order_number }}</p>
    <p class="text-sm mb-1"><span class="text-gray-500">Gudang:</span> {{ $shipment->warehouse?->name }}</p>
    <p class="text-sm mb-1"><span class="text-gray-500">Kurir:</span> {{ $shipment->courier_name ?? '-' }}</p>
    <p class="text-sm mb-1"><span class="text-gray-500">Resi:</span> {{ $shipment->tracking_number ?? '-' }}</p>
    <p class="text-sm mb-1"><span class="text-gray-500">Status:</span> {!! ['<span class="text-yellow-600">Pending</span>','<span class="text-blue-600">Picked Up</span>','<span class="text-purple-600">In Transit</span>','<span class="text-sky-600">Out for Delivery</span>','<span class="text-green-600">Delivered</span>','<span class="text-red-600">Failed</span>'][$shipment->status-1] !!}</p>
    @if($shipment->delivered_at)<p class="text-sm"><span class="text-gray-500">Terkirim:</span> {{ \Carbon\Carbon::parse($shipment->delivered_at)->format('d/m/Y H:i') }}</p>@endif
    <a href="{{ route('admin.shipments.edit', $shipment) }}" class="mt-4 inline-block bg-gray-900 text-white px-4 py-2 rounded text-sm hover:bg-gray-800">Edit Data Pengiriman</a>
  </div>

  @if($shipment->status < 5)
  <div class="bg-white rounded-lg shadow p-6">
    <h4 class="font-semibold mb-4">Update Status</h4>
    <form action="{{ route('admin.shipments.update-status', $shipment) }}" method="POST">
      @csrf
      <div class="mb-4">
        <label class="block text-sm font-medium mb-1">Status Baru</label>
        <select name="status" class="w-full border rounded px-3 py-2">
          @php $nextStatuses = [1=>2, 2=>3, 3=>4, 4=>5]; $next = $nextStatuses[$shipment->status] ?? null; @endphp
          @if($next)
            @php $labels = [2=>'Picked Up', 3=>'In Transit', 4=>'Out for Delivery', 5=>'Delivered']; @endphp
            <option value="{{ $next }}">{{ $labels[$next] ?? $next }}</option>
          @endif
        </select>
      </div>
      <div class="mb-4"><label class="block text-sm font-medium mb-1">Catatan</label><textarea name="note" rows="2" class="w-full border rounded px-3 py-2"></textarea></div>
      <div class="mb-4"><label class="block text-sm font-medium mb-1">Lokasi</label><input type="text" name="location" class="w-full border rounded px-3 py-2"></div>
      <button type="submit" class="bg-gray-900 text-white px-4 py-2 rounded text-sm hover:bg-gray-800">Update Status</button>
    </form>
  </div>
  @endif

  <div class="lg:col-span-2 bg-white rounded-lg shadow p-6">
    <h4 class="font-semibold mb-4">Tracking Log</h4>
    @if($shipment->trackingLogs->isEmpty())
      <p class="text-gray-500 text-sm">Belum ada log.</p>
    @else
      <div class="space-y-3">
        @foreach($shipment->trackingLogs as $log)
        <div class="border-l-4 border-gray-900 pl-4">
          <p class="text-sm font-semibold">{{ ['Pending','Picked Up','In Transit','Out for Delivery','Delivered','Failed'][$log->status-1] }}</p>
          @if($log->location)<p class="text-xs text-gray-500">Lokasi: {{ $log->location }}</p>@endif
          @if($log->note)<p class="text-xs text-gray-600">{{ $log->note }}</p>@endif
          <p class="text-xs text-gray-400">{{ $log->created_at->format('d/m/Y H:i') }} — {{ $log->updatedBy?->name }}</p>
        </div>
        @endforeach
      </div>
    @endif
  </div>
</div>
@endsection
