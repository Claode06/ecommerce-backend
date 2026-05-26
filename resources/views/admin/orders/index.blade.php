@extends('admin.layouts.app')

@section('page_title', 'Manajemen Pesanan')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
  <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
    <form class="flex flex-wrap gap-2">
      <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari order#..." class="border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-sky-400 outline-none">
      <select name="status" class="border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-sky-400 outline-none">
        <option value="">Semua Status</option>
        @foreach([1=>'Pending',2=>'Paid',3=>'Processing',4=>'Shipped',5=>'Delivered',6=>'Cancelled'] as $k=>$v)
          <option value="{{ $k }}" @selected(request('status') == $k)>{{ $v }}</option>
        @endforeach
      </select>
      <input type="date" name="date_from" value="{{ request('date_from') }}" class="border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-sky-400 outline-none">
      <input type="date" name="date_to" value="{{ request('date_to') }}" class="border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-sky-400 outline-none">
      <button type="submit" class="px-4 py-2.5 bg-gradient-to-r from-sky-600 to-teal-600 text-white text-sm font-medium rounded-lg hover:shadow-md hover:shadow-sky-100 transition-all">Filter</button>
      @if(request()->anyFilled(['search','status','date_from','date_to']))<a href="{{ route('admin.orders.index') }}" class="text-gray-600 hover:text-gray-800 px-4 py-2.5 text-sm font-medium transition-colors">Reset</a>@endif
    </form>
  </div>
  <div class="overflow-x-auto">
  <table class="w-full text-sm">
    <thead><tr class="bg-gray-50 text-left">
      <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wider text-gray-500">Order#</th>
      <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wider text-gray-500">Customer</th>
      <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wider text-gray-500">Total</th>
      <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wider text-gray-500">Status</th>
      <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wider text-gray-500">Tanggal</th>
      <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wider text-gray-500">Aksi</th>
    </tr></thead>
    <tbody>
      @foreach($orders as $order)
      <tr class="border-t border-gray-100 hover:bg-sky-50/50 transition-colors">
        <td class="px-6 py-4 font-medium text-gray-800">{{ $order->order_number }}</td>
        <td class="px-6 py-4">{{ $order->buyer_name }}</td>
        <td class="px-6 py-4">Rp {{ number_format($order->total, 0, ',', '.') }}</td>
        <td class="px-6 py-4">{!! ['<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800">Pending</span>','<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">Paid</span>','<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">Processing</span>','<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">Shipped</span>','<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">Delivered</span>','<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-rose-100 text-rose-800">Cancelled</span>'][$order->status-1] !!}</td>
        <td class="px-6 py-4">{{ $order->created_at->format('d/m/Y') }}</td>
        <td class="px-6 py-4"><a href="{{ route('admin.orders.show', $order) }}" class="text-sky-600 hover:text-sky-800 text-sm font-medium transition-colors">Detail</a></td>
      </tr>
      @endforeach
    </tbody>
  </table>
  </div>
  <div class="px-6 py-4 border-t border-gray-100">{{ $orders->links() }}</div>
</div>
@endsection
