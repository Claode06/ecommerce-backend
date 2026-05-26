@extends('admin.layouts.app')

@section('page_title', 'Detail Pembayaran')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
  <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
    <h4 class="font-semibold mb-4">Info Pembayaran</h4>
    <p class="text-sm mb-1"><span class="text-gray-500">Order:</span> {{ $payment->order?->order_number }}</p>
    <p class="text-sm mb-1"><span class="text-gray-500">Customer:</span> {{ $payment->order?->buyer_name }}</p>
    <p class="text-sm mb-1"><span class="text-gray-500">Rekening Tujuan:</span> {{ $payment->paymentAccount?->bank_name }} - {{ $payment->paymentAccount?->account_number }}</p>
    <p class="text-sm mb-1"><span class="text-gray-500">Jumlah Transfer:</span> Rp {{ number_format($payment->amount, 0, ',', '.') }}</p>
    <p class="text-sm mb-1"><span class="text-gray-500">Status:</span> {!! ['<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800">Pending</span>','<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">Approved</span>','<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-rose-100 text-rose-800">Rejected</span>','<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600">Expired</span>'][$payment->status-1] !!}</p>
    <p class="text-sm"><span class="text-gray-500">Tanggal Upload:</span> {{ $payment->created_at->format('d/m/Y H:i') }}</p>
  </div>

  <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
    <h4 class="font-semibold mb-4">Bukti Transfer</h4>
    @if($payment->proofFile)
      <img src="{{ asset('storage/'.$payment->proofFile->link) }}" class="max-w-full rounded border">
    @else
      <p class="text-gray-500 text-sm">Tidak ada bukti transfer.</p>
    @endif
  </div>

  @if($payment->status === 1)
  <div class="lg:col-span-2 flex gap-4">
    <form action="{{ route('admin.payments.approve', $payment) }}" method="POST">
      @csrf
      <button class="px-6 py-2.5 bg-gradient-to-r from-emerald-600 to-emerald-600 text-white text-sm font-medium rounded-lg hover:shadow-md hover:shadow-emerald-200 transition-all">Setujui Pembayaran</button>
    </form>
    <button type="button" class="px-6 py-2.5 bg-gradient-to-r from-rose-600 to-rose-600 text-white text-sm font-medium rounded-lg hover:shadow-md hover:shadow-rose-200 transition-all" onclick="openRejectModal()">Tolak Pembayaran</button>
  </div>

  <div id="rejectModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 max-w-lg w-full">
      <h4 class="font-semibold mb-4">Tolak Pembayaran</h4>
      <form action="{{ route('admin.payments.reject', $payment) }}" method="POST">
        @csrf
        <div class="mb-4">
          <label class="block text-sm font-medium mb-1">Alasan Penolakan</label>
          <textarea name="rejected_reason" rows="3" class="w-full border border-gray-200 rounded-lg px-3 py-2.5 focus:ring-2 focus:ring-sky-400 outline-none" required minlength="10"></textarea>
        </div>
        <div class="flex gap-2">
          <button type="submit" class="px-4 py-2.5 bg-gradient-to-r from-rose-600 to-rose-600 text-white text-sm font-medium rounded-lg hover:shadow-md hover:shadow-rose-200 transition-all">Tolak</button>
          <button type="button" class="text-gray-600 hover:text-gray-800 px-4 py-2.5 text-sm font-medium transition-colors" onclick="closeRejectModal()">Batal</button>
        </div>
      </form>
    </div>
  </div>
  <script>
  function openRejectModal() { document.getElementById('rejectModal').classList.remove('hidden'); document.getElementById('rejectModal').classList.add('flex'); }
  function closeRejectModal() { document.getElementById('rejectModal').classList.add('hidden'); document.getElementById('rejectModal').classList.remove('flex'); }
  </script>
  @endif
</div>
@endsection
