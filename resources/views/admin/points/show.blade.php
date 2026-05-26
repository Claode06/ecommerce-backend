@extends('admin.layouts.app')

@section('page_title', 'Riwayat Poin: '.$user->name)

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
  <div class="mb-4">
    <p class="text-sm"><span class="text-gray-500">Customer:</span> {{ $user->name }}</p>
    <p class="text-sm"><span class="text-gray-500">Email:</span> {{ $user->email }}</p>
    <p class="text-sm font-semibold"><span class="text-gray-500">Saldo Poin:</span> {{ number_format($balance) }}</p>
  </div>

  <div class="overflow-x-auto">
  <table class="w-full text-sm">
    <thead><tr class="bg-gray-50 text-left">
      <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wider text-gray-500">Tanggal</th>
      <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wider text-gray-500">Tipe</th>
      <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wider text-gray-500">Jumlah</th>
      <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wider text-gray-500">Keterangan</th>
      <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wider text-gray-500">Order</th>
    </tr></thead>
    <tbody>
      @forelse($transactions as $t)
      <tr class="border-t border-gray-100 hover:bg-sky-50/50 transition-colors">
        <td class="px-6 py-4">{{ $t->created_at->format('d/m/Y H:i') }}</td>
        <td class="px-6 py-4">{!! ['<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">Earn</span>','<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-rose-100 text-rose-800">Redeem</span>','<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">Adjust</span>'][$t->type-1] !!}</td>
        <td class="px-6 py-4 font-semibold">{{ $t->amount }}</td>
        <td class="px-6 py-4">{{ $t->description }}</td>
        <td class="px-6 py-4">{{ $t->order?->order_number ?? '-' }}</td>
      </tr>
      @empty
      <tr><td colspan="5" class="px-6 py-4 text-gray-500 text-center">Tidak ada transaksi.</td></tr>
      @endforelse
    </tbody>
  </table>
  </div>
  <div class="px-6 py-4 border-t border-gray-100 -mx-6 -mb-6">{{ $transactions->links() }}</div>
</div>
@endsection
