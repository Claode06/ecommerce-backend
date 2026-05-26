@extends('admin.layouts.app')

@section('page_title', 'Edit Pengiriman')

@section('content')
<div class="bg-white rounded-lg shadow p-6">
  <form action="{{ route('admin.shipments.update', $shipment) }}" method="POST">
    @csrf @method('PUT')
    <div class="mb-4"><label class="block text-sm font-medium mb-1">Nama Kurir</label><input type="text" name="courier_name" value="{{ old('courier_name', $shipment->courier_name) }}" class="w-full border rounded px-3 py-2"></div>
    <div class="mb-4"><label class="block text-sm font-medium mb-1">Nomor Resi</label><input type="text" name="tracking_number" value="{{ old('tracking_number', $shipment->tracking_number) }}" class="w-full border rounded px-3 py-2"></div>
    <button type="submit" class="bg-gray-900 text-white px-4 py-2 rounded text-sm hover:bg-gray-800">Simpan</button>
    <a href="{{ route('admin.shipments.show', $shipment) }}" class="ml-2 text-gray-600 hover:underline text-sm">Kembali</a>
  </form>
</div>
@endsection
