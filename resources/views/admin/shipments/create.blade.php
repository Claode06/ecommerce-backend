@extends('admin.layouts.app')

@section('page_title', 'Buat Pengiriman')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
  <p class="text-sm text-gray-500 mb-4">Order: <strong>{{ $order->order_number }}</strong> — Gudang: <strong>{{ $order->warehouse?->name }}</strong></p>
  <form action="{{ route('admin.shipments.store') }}" method="POST">
    @csrf
    <input type="hidden" name="order_id" value="{{ $order->id }}">
    <input type="hidden" name="warehouse_id" value="{{ $order->warehouse_id }}">
    <div class="mb-4">
      <label class="block text-sm font-medium mb-1">Nama Kurir</label>
      <input type="text" name="courier_name" value="{{ old('courier_name') }}" class="w-full border border-gray-200 rounded-lg px-3 py-2.5 focus:ring-2 focus:ring-sky-400 outline-none @error('courier_name') border-rose-300 ring-2 ring-rose-200 @enderror">
      @error('courier_name')<p class="text-rose-500 text-xs mt-1">{{ $message }}</p>@enderror
    </div>
    <div class="mb-4">
      <label class="block text-sm font-medium mb-1">Biaya Kirim</label>
      <input type="number" step="0.01" name="shipping_cost" value="{{ old('shipping_cost', $order->shipping_cost) }}" class="w-full border border-gray-200 rounded-lg px-3 py-2.5 focus:ring-2 focus:ring-sky-400 outline-none @error('shipping_cost') border-rose-300 ring-2 ring-rose-200 @enderror">
      @error('shipping_cost')<p class="text-rose-500 text-xs mt-1">{{ $message }}</p>@enderror
    </div>
    <button type="submit" class="px-4 py-2.5 bg-gradient-to-r from-sky-600 to-teal-600 text-white text-sm font-medium rounded-lg hover:shadow-md hover:shadow-sky-100 transition-all">Simpan</button>
    <a href="{{ route('admin.orders.show', $order) }}" class="ml-2 text-gray-600 hover:text-gray-800 text-sm font-medium transition-colors">Kembali</a>
  </form>
</div>
@endsection
