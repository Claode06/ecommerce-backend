@extends('admin.layouts.app')

@section('page_title', 'Edit Akun Pembayaran')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
  <form action="{{ route('admin.payment-accounts.update', $paymentAccount) }}" method="POST">
    @csrf @method('PUT')
    <div class="mb-4"><label class="block text-sm font-medium mb-1">Nama Bank</label><input type="text" name="bank_name" value="{{ old('bank_name', $paymentAccount->bank_name) }}" class="w-full border border-gray-200 rounded-lg px-3 py-2.5 focus:ring-2 focus:ring-sky-400 outline-none @error('bank_name') border-rose-300 ring-2 ring-rose-200 @enderror">@error('bank_name')<p class="text-rose-500 text-xs mt-1">{{ $message }}</p>@enderror</div>
    <div class="mb-4"><label class="block text-sm font-medium mb-1">No. Rekening</label><input type="text" name="account_number" value="{{ old('account_number', $paymentAccount->account_number) }}" class="w-full border border-gray-200 rounded-lg px-3 py-2.5 focus:ring-2 focus:ring-sky-400 outline-none @error('account_number') border-rose-300 ring-2 ring-rose-200 @enderror">@error('account_number')<p class="text-rose-500 text-xs mt-1">{{ $message }}</p>@enderror</div>
    <div class="mb-4"><label class="block text-sm font-medium mb-1">Atas Nama</label><input type="text" name="account_name" value="{{ old('account_name', $paymentAccount->account_name) }}" class="w-full border border-gray-200 rounded-lg px-3 py-2.5 focus:ring-2 focus:ring-sky-400 outline-none @error('account_name') border-rose-300 ring-2 ring-rose-200 @enderror">@error('account_name')<p class="text-rose-500 text-xs mt-1">{{ $message }}</p>@enderror</div>
    <div class="mb-4"><label class="block text-sm font-medium mb-1">Status</label><select name="is_active" class="w-full border border-gray-200 rounded-lg px-3 py-2.5 focus:ring-2 focus:ring-sky-400 outline-none"><option value="1" @selected($paymentAccount->is_active)>Aktif</option><option value="0" @selected(!$paymentAccount->is_active)>Nonaktif</option></select></div>
    <button type="submit" class="px-4 py-2.5 bg-gradient-to-r from-sky-600 to-teal-600 text-white text-sm font-medium rounded-lg hover:shadow-md hover:shadow-sky-100 transition-all">Simpan</button>
    <a href="{{ route('admin.payment-accounts.index') }}" class="ml-2 text-gray-600 hover:text-gray-800 text-sm font-medium transition-colors">Batal</a>
  </form>
</div>
@endsection
