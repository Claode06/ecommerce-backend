@extends('admin.layouts.app')

@section('page_title', 'Pesanan #'.$order->order_number)

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
  <div class="lg:col-span-2 space-y-6">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
      <h4 class="text-base font-semibold text-gray-800 mb-4 flex items-center gap-2">
        <span class="w-1 h-5 bg-indigo-500 rounded-full inline-block"></span>
        Item Pesanan
      </h4>
      <div class="overflow-x-auto">
        <table class="w-full text-sm">
          <thead><tr class="bg-gray-50 text-left"><th class="p-3 text-xs font-semibold uppercase tracking-wider text-gray-500">Produk</th><th class="p-3 text-xs font-semibold uppercase tracking-wider text-gray-500">Varian</th><th class="p-3 text-xs font-semibold uppercase tracking-wider text-gray-500">Harga</th><th class="p-3 text-xs font-semibold uppercase tracking-wider text-gray-500">Qty</th><th class="p-3 text-xs font-semibold uppercase tracking-wider text-gray-500">Subtotal</th></tr></thead>
          <tbody>
            @foreach($order->orderItems as $item)
            <tr class="border-t border-gray-100">
              <td class="p-3 text-gray-800">{{ $item->product_name }}</td>
              <td class="p-3 text-gray-600">{{ $item->variant_label }}</td>
              <td class="p-3 text-gray-600">Rp {{ number_format($item->unit_price, 0, ',', '.') }}</td>
              <td class="p-3 text-gray-600">{{ $item->quantity }}</td>
              <td class="p-3 font-medium">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
            </tr>
            @endforeach
          </tbody>
          <tfoot>
            <tr class="border-t"><td colspan="4" class="p-3 text-right text-sm text-gray-600">Subtotal</td><td class="p-3 font-medium">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</td></tr>
            <tr><td colspan="4" class="p-3 text-right text-sm text-gray-600">Ongkir</td><td class="p-3 font-medium">Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</td></tr>
            @if($order->point_redeemed)<tr><td colspan="4" class="p-3 text-right text-sm text-gray-600">Poin dipakai</td><td class="p-3 font-medium text-rose-600">-{{ $order->point_redeemed }}</td></tr>@endif
            <tr class="border-t"><td colspan="4" class="p-3 text-right font-semibold">Total</td><td class="p-3 font-bold text-lg">Rp {{ number_format($order->total, 0, ',', '.') }}</td></tr>
          </tfoot>
        </table>
      </div>
    </div>

    @if($order->payment)
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
      <h4 class="text-base font-semibold text-gray-800 mb-3 flex items-center gap-2">
        <span class="w-1 h-5 bg-emerald-500 rounded-full inline-block"></span>Pembayaran
      </h4>
      <div class="text-sm space-y-1.5">
        <p><span class="text-gray-500">Status:</span> {!! ['<span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800">Pending</span>','<span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">Approved</span>','<span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-rose-100 text-rose-800">Rejected</span>','<span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600">Expired</span>'][$order->payment->status-1] !!}</p>
        <p><span class="text-gray-500">Rekening:</span> {{ $order->payment->paymentAccount?->bank_name }} - {{ $order->payment->paymentAccount?->account_number }}</p>
        <p><span class="text-gray-500">Jumlah:</span> Rp {{ number_format($order->payment->amount, 0, ',', '.') }}</p>
      </div>
    </div>
    @endif

    @if($order->shipment)
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
      <h4 class="text-base font-semibold text-gray-800 mb-3 flex items-center gap-2">
        <span class="w-1 h-5 bg-purple-500 rounded-full inline-block"></span>Pengiriman
      </h4>
      <div class="text-sm space-y-1.5">
        <p><span class="text-gray-500">Status:</span> {!! ['<span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800">Pending</span>','<span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">Picked Up</span>','<span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">In Transit</span>','<span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">Out for Delivery</span>','<span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">Delivered</span>','<span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-rose-100 text-rose-800">Failed</span>'][$order->shipment->status-1] !!}</p>
        <p><span class="text-gray-500">Kurir:</span> {{ $order->shipment->courier_name ?? '-' }}</p>
        <p><span class="text-gray-500">Resi:</span> {{ $order->shipment->tracking_number ?? '-' }}</p>
      </div>
    </div>
    @endif
  </div>

  <div class="lg:col-span-1 space-y-6">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
      <h4 class="text-base font-semibold text-gray-800 mb-4">Info Pesanan</h4>
      <div class="text-sm space-y-2">
        <div class="flex justify-between"><span class="text-gray-500">Order#</span><span class="font-medium">{{ $order->order_number }}</span></div>
        <div class="flex justify-between"><span class="text-gray-500">Status</span>{!! ['<span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800">Pending</span>','<span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">Paid</span>','<span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">Processing</span>','<span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">Shipped</span>','<span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">Delivered</span>','<span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-rose-100 text-rose-800">Cancelled</span>'][$order->status-1] !!}</div>
        <div class="flex justify-between"><span class="text-gray-500">Tanggal</span><span class="font-medium">{{ $order->created_at->format('d/m/Y H:i') }}</span></div>
        <div class="flex justify-between"><span class="text-gray-500">Gudang</span><span class="font-medium">{{ $order->warehouse?->name }}</span></div>
      </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
      <h4 class="text-base font-semibold text-gray-800 mb-4">Data Pembeli</h4>
      <div class="text-sm space-y-1.5">
        <p class="font-medium">{{ $order->buyer_name }}</p>
        <p class="text-gray-500">{{ $order->buyer_email }}</p>
        <p class="text-gray-500">{{ $order->buyer_phone }}</p>
        <p class="mt-3 text-gray-500 text-xs font-medium uppercase tracking-wider">Alamat</p>
        <p class="text-gray-700">{{ $order->shipping_address }}</p>
        @if($order->shipping_note)<p class="mt-2"><span class="text-gray-500">Catatan:</span> {{ $order->shipping_note }}</p>@endif
      </div>
    </div>

    @if(in_array($order->status, [1, 3]))
    <form action="{{ route('admin.orders.cancel', $order) }}" method="POST" onsubmit="return confirm('Batalkan pesanan ini?')">
      @csrf
      <button class="w-full py-2.5 bg-rose-600 text-white text-sm font-medium rounded-lg hover:bg-rose-700 transition-colors">Batalkan Pesanan</button>
    </form>
    @endif
  </div>
</div>
@endsection
