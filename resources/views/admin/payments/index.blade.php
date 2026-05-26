@extends('admin.layouts.app')

@section('page_title', 'Konfirmasi Pembayaran')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
  <div class="px-6 py-4 border-b border-gray-100">
    <form class="flex gap-2">
      <select name="status" class="border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-sky-400 outline-none">
        <option value="">Semua Status</option>
        <option value="1" @selected(request('status') === '1')>Pending</option>
        <option value="2" @selected(request('status') === '2')>Approved</option>
        <option value="3" @selected(request('status') === '3')>Rejected</option>
      </select>
      <button type="submit" class="px-4 py-2.5 bg-gradient-to-r from-sky-600 to-teal-600 text-white text-sm font-medium rounded-lg hover:shadow-md hover:shadow-sky-100 transition-all">Filter</button>
      @if(request('status'))<a href="{{ route('admin.payments.index') }}" class="text-gray-600 hover:text-gray-800 px-4 py-2.5 text-sm font-medium transition-colors">Reset</a>@endif
    </form>
  </div>
  <div class="overflow-x-auto">
  <table class="w-full text-sm">
    <thead><tr class="bg-gray-50 text-left">
      <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wider text-gray-500">Order#</th>
      <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wider text-gray-500">Customer</th>
      <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wider text-gray-500">Bank</th>
      <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wider text-gray-500">Jumlah</th>
      <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wider text-gray-500">Status</th>
      <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wider text-gray-500">Tanggal</th>
      <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wider text-gray-500">Aksi</th>
    </tr></thead>
    <tbody>
      @foreach($payments as $payment)
      <tr class="border-t border-gray-100 hover:bg-sky-50/50 transition-colors">
        <td class="px-6 py-4 font-medium text-gray-800">{{ $payment->order?->order_number }}</td>
        <td class="px-6 py-4">{{ $payment->order?->buyer_name }}</td>
        <td class="px-6 py-4">{{ $payment->paymentAccount?->bank_name }}</td>
        <td class="px-6 py-4">Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
        <td class="px-6 py-4">{!! ['<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800">Pending</span>','<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">Approved</span>','<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-rose-100 text-rose-800">Rejected</span>','<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600">Expired</span>'][$payment->status-1] !!}</td>
        <td class="px-6 py-4">{{ $payment->created_at->format('d/m/Y') }}</td>
        <td class="px-6 py-4"><a href="{{ route('admin.payments.show', $payment) }}" class="text-sky-600 hover:text-sky-800 text-sm font-medium transition-colors">Detail</a></td>
      </tr>
      @endforeach
    </tbody>
  </table>
  </div>
  <div class="px-6 py-4 border-t border-gray-100">{{ $payments->links() }}</div>
</div>
@endsection
