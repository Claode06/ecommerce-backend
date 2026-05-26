@extends('admin.layouts.app')

@section('page_title', 'Detail Customer')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
  <div class="lg:col-span-1">
    <div class="bg-white rounded-lg shadow p-6">
      <h4 class="font-semibold mb-4">Profil</h4>
      <p class="text-sm mb-1"><span class="text-gray-500">Nama:</span> {{ $user->name }}</p>
      <p class="text-sm mb-1"><span class="text-gray-500">Email:</span> {{ $user->email }}</p>
      <p class="text-sm mb-1"><span class="text-gray-500">Telepon:</span> {{ $user->phone }}</p>
      <p class="text-sm mb-1"><span class="text-gray-500">Saldo Poin:</span> {{ number_format($user->userPoint?->balance ?? 0) }}</p>
      <p class="text-sm mb-1"><span class="text-gray-500">Total Pesanan:</span> {{ $totalOrders }}</p>
      <p class="text-sm"><span class="text-gray-500">Daftar:</span> {{ $user->created_at->format('d/m/Y') }}</p>
      <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Nonaktifkan customer ini?')" class="mt-4">
        @csrf @method('DELETE')
        <button class="text-red-600 text-sm hover:underline">Nonaktifkan Customer</button>
      </form>
    </div>
  </div>
  <div class="lg:col-span-2">
    <div class="bg-white rounded-lg shadow p-6">
      <h4 class="font-semibold mb-4">Pesanan Terakhir</h4>
      @if($user->orders->isEmpty())
        <p class="text-gray-500 text-sm">Tidak ada pesanan.</p>
      @else
        <table class="w-full text-sm">
          <thead><tr class="bg-gray-50 text-left"><th class="p-2">Order#</th><th class="p-2">Total</th><th class="p-2">Status</th><th class="p-2">Tanggal</th></tr></thead>
          <tbody>
            @foreach($user->orders as $order)
            <tr class="border-t"><td class="p-2">{{ $order->order_number }}</td><td class="p-2">Rp {{ number_format($order->total, 0, ',', '.') }}</td><td class="p-2">{{ [1=>'Pending',2=>'Paid',3=>'Processing',4=>'Shipped',5=>'Delivered',6=>'Cancelled'][$order->status] }}</td><td class="p-2">{{ $order->created_at->format('d/m/Y') }}</td></tr>
            @endforeach
          </tbody>
        </table>
      @endif
    </div>
  </div>
</div>
@endsection
